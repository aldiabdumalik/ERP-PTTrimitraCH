<script>
$(document).ready(function () {
    const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
    var table_index = $('#project-datatable').DataTable({
        destroy: true,
        dom: domDatatable(),
        initComplete: function() {
            $("div.customer-filter").html(rawDatatable());
        }
    });

    function dt(customer) {
        loading_start();
        table_index = $('#project-datatable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{route('tms.db_parts.projects.dt')}}",
                method: 'POST',
                headers: token_header,
                data: {
                    cust_id: customer,
                    deleted: 0
                }
            },
            columns: [
                {data:'DT_RowIndex', name: 'DT_RowIndex', className: "align-middle"},
                {data:'cust_id', name: 'cust_id', className: "align-middle"},
                {data:'custname', name: 'custname', className: "align-middle"},
                {data:'type', name: 'type', className: "align-middle"},
                {data:'reff', name: 'reff', className: "align-middle"},
                {data:'action', name: 'action', className: "align-middle text-center"},
            ],
            order: [[ 3, "asc" ]],
            dom: domDatatable(),
            initComplete: function() {
                loading_stop();
                $("div.customer-filter").html(rawDatatable(customer));
                countDashboard();
            }
        });
    }

    $(document).on('keyup', '#customer-filter', delay(function (e) {
        if ($(this).val().length > 2) {
            let customer = $(this).val().toUpperCase();
            dt(customer)
        }
    }, 500));

    $(document).on('keypress', '#projects-customer', function (e) {
        if (e.keyCode == 13 && ($(this).val().length > 2)) {
            let customer = $(this).val().toUpperCase();
            loading_start();
            ajaxCall({route: "{{ route('tms.db_parts.projects.tools') }}", method: "POST", data: {type: "customer_enter", cust_id: customer}}).then(response => {
                loading_stop();
                let data = response.content
                $('#projects-customer').val();
                $('#projects-customername').val(data.custname);
            })

            return false;
        }
    });

    $(document).on('click', '.projects-act-edit', function () {
        let id = $(this).data('id'),
            route = "{{ route('tms.db_parts.projects.detail', [':id']) }}";
            route = route.replace(':id', id);
        loading_start();
        ajaxCall({route: "{{ route('tms.db_parts.projects.tools') }}", method: "POST", data: {type: "check_revision", type_id: id}}).then(response => {
            if (response.message == 1) {
                loading_stop();
                Swal.fire({
                    icon: 'warning',
                    text: `Project ini sudah ter-POST dengan jumlah revisi ${response.content}, Anda yakin ingin menambah revisi?`,
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then(answer => {
                    if (answer.value == true) {
                        loading_start();
                        modalAction('#projects-modal-form').then(() => {
                            ajaxCall({route: route, method: "GET"}).then(res => {
                                loading_stop();
                                let data = res.content
                                $('#projects-id').val(data.id)
                                $('#projects-customer').val(data.cust_id)
                                $('#projects-customername').val(data.custname)
                                $('#projects-type').val(data.type)
                                $('#projects-reff').val(data.reff)
                            });

                        })
                    }
                });
            }else{
                modalAction('#projects-modal-form').then(() => {
                    ajaxCall({route: route, method: "GET"}).then(res => {
                        loading_stop();
                        let data = res.content
                        $('#projects-id').val(data.id)
                        $('#projects-customer').val(data.cust_id)
                        $('#projects-customername').val(data.custname)
                        $('#projects-type').val(data.type)
                        $('#projects-reff').val(data.reff)
                    });

                })
            }
            // let data = response.content
            // $('#projects-customer').val();
            // $('#projects-customername').val(data.custname);
        })
    });

    $(document).on('click', '.projects-act-parts', function () {
        let id = $(this).data('id'),
            url = "{{ route('tms.db_parts.parts.index', [':type']) }}";
            url = url.replace(':type', btoa(id));
        window.open(url, '_blank');
    });

    $(document).on('click', '.projects-act-delete', function () {
        let id = $(this).data('id'),
            cust_id = $(this).data('customer');
        let route = "{{ route('tms.db_parts.projects.destroy', [':id']) }}";
            route  = route.replace(':id', id);
        Swal.fire({
            icon: 'warning',
            text: `Are you sure non active this projects, Now?`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then(answer => {
            if (answer.value == true) {
                loading_start();
                ajaxCall({route: route, method: "DELETE"}).then(resolve => {
                    loading_stop();
                    Swal.fire({
                        title: 'success',
                        text: resolve.message,
                        icon: 'success'
                    }).then(() => {
                        dt(cust_id)
                    });
                });
            }
        });
    });

    $(document).on('click', '.projects-act-approved', function () {
        let id = $(this).data('id'),
            route = "{{ route('tms.db_parts.projects.approved') }}",
            method = "POST",
            data = {project_id: id};
        loading_start();
        ajaxCall({route: route, method: method, data:data}).then(response => {
            loading_stop();
            Swal.fire({
                title: 'success',
                text: response.message,
                icon: 'success'
            })
        })
    });

    $(document).on('click', '.projects-act-published', function () {
        let id = $(this).data('id'),
            route = "{{ route('tms.db_parts.projects.published') }}",
            method = "POST",
            data = {project_id: id};
        loading_start();
        ajaxCall({route: route, method: method, data:data}).then(response => {
            loading_stop();
            Swal.fire({
                title: 'success',
                text: response.message,
                icon: 'success'
            })
        })
    });

    $(document).on('click', '.projects-act-report', function () {
        let id = $(this).data('id'),
            encrypt = btoa(`${id}`),
            url = "{{ route('tms.db_parts.report.print', [':type']) }}";
            url = url.replace(':type', id);
        window.open(url, '_blank');
    })

    let tbl_log;
    $(document).on('click', '.projects-act-log', function () {
        let id = $(this).data('id');
        modalAction('#projects-modal-logs').then((resolve) => {
            tbl_log = $('#projects-datatable-logs').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('tms.db_parts.projects.tools') }}",
                    method: 'POST',
                    data: {type: "logs", id: id},
                    headers: token_header
                },
                columns: [
                    {data: 'status', name: 'status'},
                    {data: 'date', name: 'date'},
                    {data: 'time', name: 'time'},
                    {data: 'note', name: 'note'},
                    {data: 'log_by', name: 'log_by'},
                ],
                ordering: false,
            });
        });
    });

    let tbl_revlog;
    $(document).on('click', '#projects_post-vlogs', function (e) {
        e.preventDefault();
        let groupColumn = 0,
            id = $('#projects_post-id').val(),
            route = "{{ route('tms.db_parts.projects.rev_logs', [':id']) }}",
            method = "GET";
            route = route.replace(':id', id);
        modalAction('#projects-modal-logrev').then(() => {
            tbl_revlog = $('#projects-table-revlogs').DataTable({
                processing: true,
                serverSide: false,
                destroy: true,
                ajax: {
                    url: route,
                    method: 'GET',
                    headers: token_header
                },
                columns: [
                    {data:'group', name: 'group', className: "align-middle"},
                    {data:'DT_RowIndex', name: 'DT_RowIndex', className: "align-middle"},
                    {data:'name', name: 'name', className: "align-middle"},
                    {data:'old', name: 'old', className: "align-middle"},
                    {data:'new', name: 'new', className: "align-middle"}
                ],
                ordering: false,
                lengthChange: false,
                searching: false,
                paging: false,
                ordering: false,
                scrollY: "500px",
                scrollCollapse: true,
                fixedHeader: true,
                columnDefs: [
                    { "visible": false, "targets": groupColumn },
                ],
                drawCallback: function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
                    var x = 1;
                    api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                        var arr_group = group.split('|');
                        if ( last !== group ) {
                            let log_type = `Log Type : ${arr_group[0]}`;
                            let part_nomer = (arr_group[1] !== " ") ? ` - Part No : ${arr_group[1]}` : '';
                            let part_names = (arr_group[2] !== " ") ? ` - Part Name : ${arr_group[2]}` : '';
                            $(rows).eq( i ).before(`
                                <tr class="group bg-y">
                                    <td colspan="4" class="text-bold align-middle">${log_type}${part_nomer}${part_names}</td>
                                </tr>
                            `);

                            last = group;
                        }
                    });
                }
            })
        })
        // ajaxCall({route: route, method: method}).then(response => {
        //     let data = response.content;
        //     let c_part = 0;
        //     $.each(data, function (type, val) {
        //         // console.log(type, val);
        //         if (type == 'PART') {
        //             $.each(val, function (part_id, val_part) {
        //                 $.each(val_part, function (i, item) {
        //                     ++c_part;
        //                     $('#projects-table-revlogs tbody').append(`<tr>
        //                         <td rowspan="${c_part}">${type}</td>
        //                         <td>${type}</td>
        //                         <td>${type}</td>
        //                         <td>${type}</td>
        //                         <td>${type}</td>
        //                     </tr>`)
        //                 })
        //             })

        //         }
        //     })
        //     console.log(c_part);
        //     modalAction('#projects-modal-logrev')
        // })
    })

    $(document).on('click', '.projects-act-posted', function () {
        loading_start();
        let id = $(this).data('id'),
            route = "{{ route('tms.db_parts.projects.detail', [':id']) }}",
            method = "GET";
            route = route.replace(':id', id);
        modalAction('#projects_post-modal-form').then(() => {
            ajaxCall({route: route, method: method}).then(ress => {
                loading_stop();
                let data = ress.content;
                $('#projects_post-id').val(data.id)
                $('#projects_post-type').val(data.type)
                $('#projects_post-reff').val(data.reff)
            });
        })
    })

    $(document).on('submit', '#projects_post-form', function (e) {
        e.preventDefault()
        let id = $('#projects_post-id').val(),
            route = "{{ route('tms.db_parts.projects.posted', [':id']) }}",
            method = "POST",
            data = {
                note: $('#projects_post-note').val()
            };
            route = route.replace(':id', id);
        loading_start();
        ajaxCall({route: route, method: method, data: data}).then(ress => {
            loading_stop();
            if (ress.content != 3) {
                Swal.fire({
                    title: 'Success',
                    text: ress.message,
                    icon: 'success'
                }).then(() => {
                    modalAction('#projects_post-modal-form', 'hide').then(() => {
                        // table_index.ajax.reload();
                        // dt(data.cust_id)
                    });
                });
            } else {
                Swal.fire({
                    title: 'Warning',
                    text: ress.message,
                    icon: 'warning'
                })
            }
        })
    })
    $('#projects_post-modal-form').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset')
        $(this).find('#projects_post-id').val(0)
    });


    $('#projects-btn-modal-create').on('click', function () {
        modalAction('#projects-modal-form')
    });

    $('#projects-form').on('submit', function (e) {
        e.preventDefault();
        loading_start();
        let id = $('#projects-id').val(),
            route = "{{ route('tms.db_parts.projects.detail', [':id']) }}",
            method = "GET",
            data = {
                'cust_id': $('#projects-customer').val().toUpperCase(),
                'type': $('#projects-type').val(),
                'reff': $('#projects-reff').val(),
            };
            route = route.replace(':id', id);
        ajaxCall({route: route, method: "GET"}).then(cek => {
            if (cek.message == 1) {
                route = "{{ route('tms.db_parts.projects.update', [':id']) }}";
                route = route.replace(':id', id);
                method = "PUT";
            }else{
                route = "{{ route('tms.db_parts.projects.store') }}";
                method = "POST";
            }

            ajaxCall({route: route, method:method, data:data}).then(response => {
                loading_stop();
                
                Swal.fire({
                    title: 'Success',
                    text: response.message,
                    icon: 'success'
                }).then(() => {
                    modalAction('#projects-modal-form', 'hide').then(() => {
                        // table_index.ajax.reload();
                        dt(data.cust_id)
                    });
                });
            });
        });
    });

    $('#projects-modal-form').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset')
        $(this).find('#projects-id').val(0)
    });
    function countDashboard() {
        return ajaxCall({route: "{{ route('tms.db_parts.projects.tools') }}", method: "POST", data: {type: "init"}}).then(response => {
            let data = response.content;
            $('#c-project').text(data.projects);
            $('#c-customer').text(data.countcustomer);
            $('#c-revisi').text(data.revisi);
        });
    }

    function rawDatatable(val="") {
        return `
        <div class="form-row align-items-center mb-1">
            <div class="col-2">
                <label for="customer-filter" class="auto-middle">Filter :</label>
            </div>
            <div class="col-8">
                <input type="text" name="customer-filter" id="customer-filter" class="form-control form-control-sm" placeholder="By Customer" autocomplete="off" value="${val}" autofocus style="text-transform: uppercase">    
            </div>
        </div>
        `
    }

    function domDatatable(params) {
        return "<'row'<'col-sm-12 col-md-4' <'customer-filter'>><'col-sm-12 col-md-8'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
    }
    // Lib func
    function delay(callback, ms) {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
            callback.apply(context, args);
            }, ms || 0);
        };
    }
    function date_convert($date) {
        if ($date.length < 0) { return null; }
        return $date.split(' ')[0].split('-').reverse().join('-');
    }
    function addZeroes( num ) {
        var value = Number(num);
        var res = num.split(".");
        if(res.length == 1 || (res[1].length < 4)) {
            value = value.toFixed(2);
        }
        return value;
    }
    function modalAction(elementId=null, action='show'){
        return new Promise(resolve => {
            $(elementId).modal(action);
            resolve($(elementId));
        });
    }

    function loading_start() {
        $('body').loading({
            message: "wait for a moment...",
            zIndex: 9999
        });
    }

    function loading_stop() {
        $('body').loading('stop');
    }

    function ajaxCall(params) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: params.route,
                method: params.method,
                dataType: "JSON",
                cache: false,
                headers: token_header,
                data: params.data,
                error: function(response, status, x){
                    $('body').loading('stop');
                    Swal.fire({
                        title: 'Something was wrong',
                        text: response.responseJSON.message,
                        icon: 'error'
                    }).then(() => {
                        console.clear();
                        reject(response);
                    });
                },
                complete: function (response){
                    resolve(response);
                }
            });
        });
    }
    function ajaxFormData(route, formData) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: route,
                method: "POST",
                data: formData,
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                headers: token_header,
                error: function(response, status, x){
                    console.log(response);
                    loading_stop();
                    Swal.fire({
                        title: 'Something was wrong',
                        text: response.responseJSON.message,
                        icon: 'error'
                    }).then(() => {
                        // console.clear();
                    });
                },
                complete: function (response){
                    resolve(response);
                }
            });
        });
    }
    function cekFile(url) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                type:'HEAD',
                error: function() {
                    reject('Not Exist');
                },
                success: function(){
                    resolve('Exist!');
                }
            });
        })
    }
    function isHidden(element=null, hide=true){
        return ((hide == true) ? $(element).addClass('d-none') : $(element).removeClass('d-none'));
    }
    function adjustDraw(tbl) {
        return tbl.columns.adjust().draw();
    }
    
    function currency(bilangan) {
        var	number_string = bilangan.toString(),
        split	= number_string.split('.'),
        sisa 	= split[0].length % 3,
        rupiah 	= split[0].substr(0, sisa),
        ribuan 	= split[0].substr(sisa).match(/\d{1,3}/gi);

        if (ribuan) {
            separator = sisa ? ',' : '';
            rupiah += separator + ribuan.join(',');
        }
        return rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;
    }
});
</script>