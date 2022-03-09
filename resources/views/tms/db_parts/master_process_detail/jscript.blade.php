<script>
    $(document).ready(function () {
        const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
        const table_index = $('#mdprocess-datatable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{route('tms.db_parts.master.detail_process.tbl_index')}}",
                method: 'POST',
                headers: token_header
            },
            columns: [
                {data:'process_id', name: 'process_id'},
                {data:'process_name', name: 'process_name'},
                {data:'process_detail_name', name: 'process_detail_name'},
                {data:'action', name: 'action', orderable: false, searchable: false, className: "text-center"},
            ]
        });
    
        $('#mdprocess-btn-modal-create').on('click', function () {
            modalAction('#mdprocess-modal-index');
        });
    
        var tbl_process;
        $(document).on('keypress keydown', '#mdprocess-index-procid', function (e) {
            if(e.which == 13) {
                modalAction('#mdprocess-modal-process').then(resolve => {
                    tbl_process = $('#mdprocess-datatable-process').DataTable({
                        processing: true,
                        serverSide: true,
                        destroy: true,
                        ordering: false,
                        ajax: {
                            url: "{{ route('tms.db_parts.master.detail_process.tbl_process') }}",
                            method: 'POST',
                            headers: token_header
                        },
                        columns: [
                            {data:'process_id', name: 'process_id'},
                            {data:'itemcode_process_id', name: 'itemcode_process_id'},
                            {data:'process_name', name: 'process_name'},
                            {data:'routing', name: 'routing'},
                        ],
                    });
    
                    resolve.on('shown.bs.modal', function () {
                        $('#mdprocess-datatable-process_filter input').focus();
                    });
                });
            }
            if(e.which == 8 || e.which == 46) { return false; }
            return false;
        });
    
        $('#mdprocess-datatable-process').off('dblclick', 'tr').on('dblclick', 'tr', function () {
            var data = tbl_process.row(this).data();
            modalAction('#mdprocess-modal-process', 'hide').then(() => {
                $('#mdprocess-index-procid').val(data.process_id);
                $('#mdprocess-index-procname').val(data.process_name);
            });
        });
    
        $(document).on('submit', '#mdprocess-form-index', function () {
            loading_start();
            var data = {
                id: $('#mdprocess-index-id').val(),
                process_id: $('#mdprocess-index-procid').val(),
                process_detail: $('#mdprocess-index-procdetailname').val(),
            };
    
            let route = "{{route('tms.db_parts.master.detail_process.detail', [':id'])}}";
                route  = route.replace(':id', data.id);
    
            ajaxCall({route: route, method: "GET"}).then(resolve => {
                let route = "{{route('tms.db_parts.master.detail_process.store')}}";
                let method = "POST";
                if (resolve.message == 'OK') {
                    route = "{{route('tms.db_parts.master.detail_process.update', [':id'])}}";
                    route = route.replace(':id', data.id);
                    method = "PUT";
                }
                submit(route, method, data);
                // console.log(route, method, data);
            });
        });
    
        function submit(route, method, data) {
            return ajaxCall({route: route, method: method, data: data}).then(resolve => {
                loading_stop();
                Swal.fire({
                    title: 'Success',
                    text: resolve.message,
                    icon: 'success'
                }).then(() => {
                    modalAction('#mdprocess-modal-index', 'hide').then(() => {
                        table_index.ajax.reload();
                    });
                });
            });
        }
    
        $(document).on('click', '.mdprocess-act-edit', function () {
            let id = $(this).data('id');
            let route = "{{route('tms.db_parts.master.detail_process.detail', [':id'])}}";
                route  = route.replace(':id', id);
    
            ajaxCall({route: route, method: "GET"}).then(resolve => {
                let data = resolve.content;
                if (resolve.message == 'OK') {
                    modalAction('#mdprocess-modal-index').then(resolve => {
                        $('#mdprocess-index-procid').val(data.process_id)
                        $('#mdprocess-index-procname').val(data.process_name)
                        $('#mdprocess-index-procdetailname').val(data.process_detail_name)
                        $('#mdprocess-index-id').val(id)
    
                        $('#mdprocess-index-procid').prop('readonly', true);
                        $('#mdprocess-index-procname').prop('readonly', true);
                    });
                }
            });
        });
    
        $('#mdprocess-modal-index').on('hidden.bs.modal', function () {
            $('#mdprocess-form-index').trigger('reset');
            $('#mdprocess-index-id').val(0);
            $('#mdprocess-index-procid').prop('readonly', false);
        });
    
        $(document).on('click', '.mdprocess-act-delete', function () {
            let id = $(this).data('id');
            Swal.fire({
                icon: 'warning',
                text: `Are you sure delete process ${id}, Now?`,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then(answer => {
                if (answer.value == true) {
                    loading_start();
                    let route = "{{route('tms.db_parts.master.detail_process.destroy', [':id'])}}";
                        route  = route.replace(':id', id);
                    ajaxCall({route: route, method: "DELETE"}).then(resolve => {
                        loading_stop();
                        Swal.fire({
                            title: 'Success',
                            text: resolve.message,
                            icon: 'success'
                        }).then(() => {
                            table_index.ajax.reload();
                        });
                    });
                }
            });
        });
    
        $(document).on('click', '.mdprocess-act-log', function () {
            let id = $(this).data('id');
            modalAction('#mdprocess-modal-logs').then(() => {
                $('#mdprocess-datatable-logs').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{route('tms.db_parts.master.detail_process.logs')}}",
                        method: 'POST',
                        data: {id: id},
                        headers: token_header
                    },
                    columns: [
                        {data:'status', name: 'status', orderable: false, searchable: false},
                        {data:'date', name: 'date', orderable: false, searchable: false},
                        {data:'time', name: 'time', orderable: false, searchable: false},
                        {data:'log_by', name: 'log_by', orderable: false, searchable: false, className: "text-center"},
                    ],
                    ordering: false,
                    lengthChange: false,
                    searching: false
                });
            });
        });
    
        $('#mdprocess-btn-modal-trash').on('click', function () {
            modalAction('#mdprocess-modal-trash').then(() => {
                $('#mdprocess-datatable-trash').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{route('tms.db_parts.master.detail_process.trash')}}",
                        method: 'POST',
                        headers: token_header
                    },
                    columns: [
                        {data:'process_id', name: 'process_id'},
                        {data:'process_name', name: 'process_name'},
                        {data:'process_detail_name', name: 'process_detail_name'},
                        {data:'action', name: 'action', orderable: false, searchable: false, className: "text-center"},
                    ],
                    ordering: false,
                    lengthChange: false,
                    searching: false
                });
            });
        });
    
        $(document).on('click', '.mdprocess-act-active', function () {
            let id = $(this).data('id');
            let route = "{{route('tms.db_parts.master.detail_process.trash_to_active', [':id'])}}";
                route  = route.replace(':id', id);
            loading_start();
            modalAction('#mdprocess-modal-trash', 'hide').then(() => {
                ajaxCall({route: route, method: "PUT"}).then(resolve => {
                    loading_stop();
                    table_index.ajax.reload();
                });
            });
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