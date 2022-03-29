<script>
    $(document).ready(function () {
        const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
        const arr_url = window.location.pathname.split( '/' );
        const table_index = $('#iparts-datatable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{route('tms.db_parts.parts.dt')}}",
                method: 'POST',
                headers: token_header,
                data: {type: arr_url[5]}
            },
            columns: [
                {data:'type', name: 'type', className: "align-middle"},
                {data:'part_no', name: 'part_no', className: "align-middle"},
                {data:'part_name', name: 'part_name', className: "align-middle"},
                {data:'cust_id', name: 'cust_id', className: "align-middle"},
                {data:'action', name: 'action', className: "align-middle text-center"},
            ],
            order: [[ 0, "asc" ]],
        });
    
        $('#iparts-btn-modal-create').on('click', function () {
            loading_start();
            ajaxCall({route: "{{ route('tms.db_parts.parts.tools') }}", method: "POST", data: {type: "modal_create", type_id: arr_url[5]}}).then(ress => {
                loading_stop();
                if (ress.content != null) {
                    let data = ress.content;
                    modalAction('#iparts-modal-index').then(() => {
                        $('#id').val(0);
                        $('#iparts-index-ppartno').removeAttr('data-id');
                        $('#iparts-index-customercode').val(data.custcode);
                        $('#iparts-index-customername').val(data.custname);
                        $('#iparts-index-parttype').val(data.type);
                        $('#iparts-index-reff').val(data.reff);
                    });
                }
            })
        });
    
        var tbl_iparent;
        $(document).on('keypress keydown', '#iparts-index-ppartno', function (e) {
            if(e.which == 13) {
                modalAction('#iparts-modal-itemparent').then((resolve) => {
                    tbl_iparent = $('#iparts-datatables-itemparent').DataTable({
                        processing: true,
                        serverSide: true,
                        destroy: true,
                        ajax: {
                            url: "{{ route('tms.db_parts.parts.tools') }}",
                            method: 'POST',
                            data: {type: "item_parent", type_id: arr_url[5]},
                            headers: token_header
                        },
                        columns: [
                            {data: 'part_no', name: 'part_no'},
                            {data: 'part_name', name: 'part_name'},
                            {data: 'type', name: 'type'},
                        ]
                    });
                });
            }
            if(e.which == 8 || e.which == 46) { return false; }
            return false;
        });
        $(document).on('shown.bs.modal', '#iparts-modal-itemparent', function () {
            $('#iparts-datatables-itemparent_filter input').focus();
        });
        $('#iparts-datatables-itemparent').off('dblclick', 'tr').on('dblclick', 'tr', function () {
            var data = tbl_iparent.row(this).data();
            modalAction('#iparts-modal-itemparent', 'hide').then(() => {
                $('#iparts-index-ppartno').val(data.part_no);
                $('#iparts-index-ppartno').attr('data-id', data.id);
                $('#iparts-index-ppartname').val(data.part_name);
            });
        });
    
        $(document).on('submit', '#iparts-modal-index', function (e) {
            e.preventDefault();
            loading_start();
            let id = $('#id').val();
            let data = {
                'type_id': arr_url[5],
                'cust_id': $('#iparts-index-customercode').val(),
                'parent_id': ($('#iparts-index-ppartno').data('id') == undefined) ? null : $('#iparts-index-ppartno').data('id'),
                'part_no': $('#iparts-index-partno').val(),
                'part_name': $('#iparts-index-partname').val(),
                'type': $('#iparts-index-parttype').val(),
                'part_pict': $('#iparts-index-pict-x').text(),
                'reff': $('#iparts-index-reff').val(),
                'part_vol': $('#iparts-index-vol').val(),
                'qty_part_item': $('#iparts-index-qty').val(),
                'gop_assy': $('#iparts-index-gopassy').val(),
                'gop_single': $('#iparts-index-gopsingle').val(),
                'purch_part': $('#iparts-index-purch').val(),
                'spec': $('#iparts-index-spec').val(),
                'ms_t': $('#iparts-index-t').val(),
                'ms_w': $('#iparts-index-w').val(),
                'ms_l': $('#iparts-index-l').val(),
                'ms_n_strip': $('#iparts-index-n').val(),
                'ms_coil_pitch': $('#iparts-index-cp').val(),
                'part_weight': $('#iparts-index-weight').val(),
                'vendor_name': $('#iparts-index-vendor').val()
            };
            let route = "{{ route('tms.db_parts.parts.detail', [':id']) }}";
                route  = route.replace(':id', id);
            let method = '';
            ajaxCall({ route: route, method: "GET"}).then(resolve => {
                if (resolve.message == 1) {
                    route = "{{ route('tms.db_parts.parts.update', [':id']) }}";
                    route  = route.replace(':id', id);
                    method = "PUT";
                }else{
                    route = "{{ route('tms.db_parts.parts.store') }}";
                    method = "POST";
                }
    
                console.log(route, method, data);
                submitForm(route, method, data);
            });
        });
    
        function submitForm(route, method, data) {
            ajaxCall({route:route, method:method, data:data}).then(resolve => {
                loading_stop();
                Swal.fire({
                    title: 'Success',
                    text: resolve.message,
                    icon: 'success'
                }).then(() => {
                    modalAction('#iparts-modal-index', 'hide').then(() => {
                        table_index.ajax.reload();
                    });
                });
            });
        }
    
        $('#iparts-modal-index').on('hidden.bs.modal', function () {
            $('#iparts-form-index').trigger('reset');
            $('#iparts-index-id').attr('data-val', 0);
            $('#id').val(0);
    
            $('#iparts-form-index input').not('.readonly-first').prop('readonly', false);
            $('#iparts-form-index input[type=file]').prop('disabled', false);
            $('#iparts-form-index select').prop('disabled', false);

            $('#iparts-index-ppartno').removeAttr('data-id');
            isHidden('#iparts-btn-index-submit', false)
    
            // delete file on server
            let name = $('#iparts-index-pict-x').html();
            if ((name.lastIndexOf(".") + 1) > 0) {
                let data = {type: "delete_temp", old_file: name};
                ajaxCall({route: "{{route('tms.db_parts.parts.tools')}}", method: "POST", data:data});
            }
            $('#iparts-index-pict-x').html('Choose file');
        });
    
        $(document).on('click', '.iparts-act-edit', function () {
            let id = $(this).data('id');
            let route = "{{ route('tms.db_parts.parts.detail', [':id']) }}";
                route  = route.replace(':id', id);
            ajaxCall({ route: route, method: "GET"}).then(resolve => {
                let dt = resolve.content;
                let parent_no = (dt.parent_partno == undefined) ? null : dt.parent_partno;
                let parent_name = (dt.parent_partname == undefined) ? null : dt.parent_partname;

                modalAction('#iparts-modal-index').then(() => {
                    $('#iparts-index-id').attr('data-val', dt.id);
                    $('#id').val(dt.id);
    
                    $('#iparts-index-customercode').val(dt.cust_id);
                    $('#iparts-index-customername').val(dt.cust_name);
    
                    $('#iparts-index-ppartno').val(parent_no);
                    if (parent_no !== null) {
                        $('#iparts-index-ppartno').attr('data-id', dt.parent_id)
                    }
                    $('#iparts-index-ppartname').val(parent_name);
    
                    $('#iparts-index-partno').val(dt.part_no);
                    $('#iparts-index-partname').val(dt.part_name);
                    $('#iparts-index-parttype').val(dt.type);
                    $('#iparts-index-pict-x').html(dt.part_pict);
                    $('#iparts-index-reff').val(dt.reff);
                    $('#iparts-index-vol').val(dt.part_vol);
                    $('#iparts-index-qty').val(dt.qty_part_item);
                    $('#iparts-index-gopassy').val(dt.gop_assy);
                    $('#iparts-index-gopsingle').val(dt.gop_single);
                    $('#iparts-index-purch').val(dt.purch_part);
                    $('#iparts-index-spec').val(dt.spec);
                    $('#iparts-index-t').val(dt.ms_t);
                    $('#iparts-index-w').val(dt.ms_w);
                    $('#iparts-index-l').val(dt.ms_l);
                    $('#iparts-index-n').val(dt.ms_n_strip);
                    $('#iparts-index-cp').val(dt.ms_coil_pitch);
                    $('#iparts-index-weight').val(dt.part_weight);
                    $('#iparts-index-vendor').val(dt.vendor_name);
    
                    // $('#iparts-form-index input').prop('readonly', true);
                    // $('#iparts-form-index input[type=file]').prop('disabled', true);
                    // $('#iparts-form-index select').prop('disabled', true);
                });
            });
        });
    
        $(document).on('click', '.iparts-act-view', function () {
            let id = $(this).data('id');
            let route = "{{ route('tms.db_parts.parts.detail', [':id']) }}";
                route  = route.replace(':id', id);
            ajaxCall({ route: route, method: "GET"}).then(resolve => {
                let dt = resolve.content;
                let parent_no = (dt.parent_partno == undefined) ? null : dt.parent_partno;
                let parent_name = (dt.parent_partname == undefined) ? null : dt.parent_partname;

                modalAction('#iparts-modal-index').then(() => {
                    $('#iparts-index-id').attr('data-val', dt.id);
                    $('#id').val(dt.id);
    
                    $('#iparts-index-customercode').val(dt.cust_id);
                    $('#iparts-index-customername').val(dt.cust_name);
    
                    $('#iparts-index-ppartno').val(parent_no);
                    if (parent_no != null) {
                        $('#iparts-index-ppartno').attr('data-id', dt.parent_id)
                    }
                    $('#iparts-index-ppartname').val(parent_name);
    
                    $('#iparts-index-partno').val(dt.part_no);
                    $('#iparts-index-partname').val(dt.part_name);
                    $('#iparts-index-parttype').val(dt.type);
                    $('#iparts-index-pict-x').html(dt.part_pict);
                    $('#iparts-index-reff').val(dt.reff);
                    $('#iparts-index-vol').val(dt.part_vol);
                    $('#iparts-index-qty').val(dt.qty_part_item);
                    $('#iparts-index-gopassy').val(dt.gop_assy);
                    $('#iparts-index-gopsingle').val(dt.gop_single);
                    $('#iparts-index-purch').val(dt.purch_part);
                    $('#iparts-index-spec').val(dt.spec);
                    $('#iparts-index-t').val(dt.ms_t);
                    $('#iparts-index-w').val(dt.ms_w);
                    $('#iparts-index-l').val(dt.ms_l);
                    $('#iparts-index-n').val(dt.ms_n_strip);
                    $('#iparts-index-cp').val(dt.ms_coil_pitch);
                    $('#iparts-index-weight').val(dt.part_weight);
                    $('#iparts-index-vendor').val(dt.vendor_name);
    
                    $('#iparts-form-index input').prop('readonly', true);
                    $('#iparts-form-index input[type=file]').prop('disabled', true);
                    $('#iparts-form-index select').prop('disabled', true);
                    isHidden('#iparts-btn-index-submit')
                });
            });
        });
    
        $(document).on('click', '.iparts-act-delete', function () {
            let id = $(this).data('id');
            let route = "{{ route('tms.db_parts.parts.destroy', [':id']) }}";
                route  = route.replace(':id', id);
            Swal.fire({
                icon: 'warning',
                text: `Are you sure delete this item, Now?`,
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
                            table_index.ajax.reload();
                        });
                    });
                }
            });
        });
    
        var tbl_log;
        $(document).on('click', '.iparts-act-log', function () {
            let id = $(this).data('id');
            modalAction('#iparts-modal-logs').then((resolve) => {
                tbl_log = $('#iparts-datatable-logs').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ route('tms.db_parts.parts.tools') }}",
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
    
        var tbl_trash;
        $('#iparts-btn-modal-trash').on('click', function () {
            modalAction('#iparts-modal-trash').then(() => {
                tbl_trash = $('#iparts-datatable-trash').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{route('tms.db_parts.parts.table_trash')}}",
                        method: 'POST',
                        headers: token_header,
                        data: {type: arr_url[5]}
                    },
                    columns: [
                        {data:'type', name: 'type', className: "align-middle"},
                        {data:'part_no', name: 'part_no', className: "align-middle"},
                        {data:'part_name', name: 'part_name', className: "align-middle"},
                        {data:'cust_id', name: 'cust_id', className: "align-middle"},
                        {data:'action', name: 'action', className: "align-middle text-center"},
                    ],
                    order: [[ 0, "asc" ]],
                });
            });
        });
    
        $(document).on('click', '.iparts-act-active', function () {
            loading_start();
            let id = $(this).data('id');
            let route = "{{ route('tms.db_parts.parts.to_active', [':id']) }}";
                route  = route.replace(':id', id);
            ajaxCall({route: route, method: "PUT"}).then(resolve => {
                loading_stop();
                Swal.fire({
                    title: 'Success',
                    text: resolve.message,
                    icon: 'success'
                }).then(() => {
                    modalAction('#iparts-modal-trash', 'hide').then(() => {
                        table_index.ajax.reload();
                    });
                });
            });
        });
    
        $('#iparts-index-pict').on('change', function(e){
            e.preventDefault();
            var fileName = $(this).val().replace('C:\\fakepath\\', " ");
            var ext = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(ext, fileName.length).toLowerCase();
            let oldName = $(this).next('#iparts-index-pict-x').html();
            if (!fileName) {
                fileName = 'Choose file';
            }
            if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){
                loading_start();
                let route = "{{ route('tms.db_parts.parts.upload_temp') }}";
                let formData = new FormData();
                formData.append('file', $('#iparts-index-pict')[0].files[0]);
                if ((oldName.lastIndexOf(".") + 1) > 0) {   
                    formData.append('old_file', oldName);
                }
                ajaxFormData(route, formData).then(resolve => {
                    loading_stop();
                    $(this).next('#iparts-index-pict-x').html(resolve.content);
                });
            }else{
                if (extFile != "") {
                    Swal.fire({
                        title: 'Something was wrong',
                        text: 'Sorry, extention not supported. Upload file only jpg, png or jpeg',
                        icon: 'warning'
                    });
                }else{
                    if ((oldName.lastIndexOf(".") + 1) > 0) {
                        let data = {type: "delete_temp", old_file: oldName};
                        ajaxCall({route: "{{route('tms.db_parts.parts.tools')}}", method: "POST", data:data});
                    }
                }
                $(this).next('#iparts-index-pict-x').html('Choose file');
                $(this).val(null);
            }
        });
    
        $(document).on('click', '.view-ppict', function () {
            let fileName = $('#iparts-index-pict-x').html();
            if ($('#iparts-index-pict-x').html() != 'Choose file') {
                modalAction('#iparts-modal-ppict').then(() => {
                    cekFile(`{{ asset('db-parts/pictures/${fileName}') }}`).then(
                        resolve => {
                            $('#view-ppict').attr('src', `{{ asset('db-parts/pictures/${fileName}') }}`);
                        }, 
                        reject => {
                            console.log(reject);
                            cekFile(`{{ asset('db-parts/temp/${fileName}') }}`).then(
                                resolve => {
                                    $('#view-ppict').attr('src', `{{ asset('db-parts/temp/${fileName}') }}`);
                                }, 
                                reject => {
                                    console.log(reject);
                                });
                        });
                });
            }
        });
    
        $('#iparts-modal-ppict').on('hidden.bs.modal', function () {
            $('#view-ppict').attr('src', '#');
        });
    
        // Lib func
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