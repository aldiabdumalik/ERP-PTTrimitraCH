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