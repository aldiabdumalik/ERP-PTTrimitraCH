<script>
$(document).ready(function () {
    const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
    const table_index = $('#mprocess-datatable').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: "{{route('tms.db_parts.master.process.tbl_index')}}",
            method: 'POST',
            headers: token_header
        },
        columns: [
            {data:'process_id', name: 'process_id'},
            {data:'itemcode_process_id', name: 'itemcode_process_id'},
            {data:'process_name', name: 'process_name'},
            {data:'routing', name: 'routing'},
            {data:'action', name: 'action', orderable: false, searchable: false, className: "text-center"},
        ]
    });

    $('#mprocess-btn-modal-create').on('click', function () {
        modalAction('#mprocess-modal-index');
    });

    $(document).on('submit', '#mprocess-form-index', function () {
        loading_start();
        var data = {
            process_id: $('#mprocess-index-procid').val(),
            itemcode_process_id: $('#mprocess-index-procitem').val(),
            process_name: $('#mprocess-index-procname').val(),
            routing: $('#mprocess-index-routing').val()
        };

        let route = "{{route('tms.db_parts.master.process.detail', [':id'])}}";
            route  = route.replace(':id', data.process_id);

        ajaxCall({route: route, method: "GET"}).then(resolve => {
            let route = "{{route('tms.db_parts.master.process.store')}}";
            let method = "POST";
            if (resolve.message == 'OK') {
                route = "{{route('tms.db_parts.master.process.update', [':id'])}}";
                route = route.replace(':id', data.process_id);
                method = "PUT";
            }
            submit(route, method, data);
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
                modalAction('#mprocess-modal-index', 'hide').then(() => {
                    table_index.ajax.reload();
                });
            });
        });
    }

    $(document).on('click', '.mprocess-act-edit', function () {
        let id = $(this).data('id');
        let route = "{{route('tms.db_parts.master.process.detail', [':id'])}}";
            route  = route.replace(':id', id);

        ajaxCall({route: route, method: "GET"}).then(resolve => {
            let data = resolve.content;
            if (resolve.message == 'OK') {
                modalAction('#mprocess-modal-index').then(resolve => {
                    $('#mprocess-index-procid').val(data.process_id)
                    $('#mprocess-index-procitem').val(data.itemcode_process_id)
                    $('#mprocess-index-procname').val(data.process_name)
                    $('#mprocess-index-routing').val(data.routing)

                    $('#mprocess-index-procid').prop('readonly', true);
                    $('#mprocess-index-procitem').prop('readonly', true);
                });
            }
        });
    });

    $('#mprocess-modal-index').on('hidden.bs.modal', function () {
        $('#mprocess-form-index').trigger('reset');
        $('#mprocess-index-procid').prop('readonly', false);
        $('#mprocess-index-procitem').prop('readonly', false);
    });

    $(document).on('click', '.mprocess-act-delete', function () {
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
                let route = "{{route('tms.db_parts.master.process.destroy', [':id'])}}";
                    route  = route.replace(':id', id);
                    console.log(route);
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

    $(document).on('click', '.mprocess-act-log', function () {
        let id = $(this).data('id');
        modalAction('#mprocess-modal-logs').then(() => {
            $('#mprocess-datatable-logs').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('tms.db_parts.master.process.logs')}}",
                    method: 'POST',
                    data: {id: id},
                    headers: token_header
                },
                columns: [
                    {data:'process_id', name: 'process_id', orderable: false, searchable: false},
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

    $('#mprocess-btn-modal-trash').on('click', function () {
        modalAction('#mprocess-modal-trash').then(() => {
            $('#mprocess-datatable-trash').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('tms.db_parts.master.process.trash')}}",
                    method: 'POST',
                    headers: token_header
                },
                columns: [
                    {data:'process_id', name: 'process_id'},
                    {data:'itemcode_process_id', name: 'itemcode_process_id'},
                    {data:'process_name', name: 'process_name'},
                    {data:'routing', name: 'routing'},
                    {data:'action', name: 'action', orderable: false, searchable: false, className: "text-center"},
                ],
                ordering: false,
                lengthChange: false,
                searching: false
            });
        });
    });

    $(document).on('click', '.mprocess-act-active', function () {
        let id = $(this).data('id');
        let route = "{{route('tms.db_parts.master.process.trash_to_active', [':id'])}}";
            route  = route.replace(':id', id);
        loading_start();
        modalAction('#mprocess-modal-trash', 'hide').then(() => {
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