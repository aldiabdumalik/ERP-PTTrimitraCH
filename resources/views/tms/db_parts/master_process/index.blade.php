@extends('master')
@section('title', 'TMS | Database Parts - Master Process')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/datepicker/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
@include('tms.db_parts.style.custom-style')

<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="mprocess-btn-modal-create">
                    <i class="ti-plus"></i>  Add New Data
                </button>
                <button type="button"  class="btn btn-danger btn-flat btn-sm" id="mprocess-btn-modal-trash">
                    <i class="ti-trash"></i>  View Trash Data
                </button>
            </div>
        </div>
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">Master Process</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="mprocess-datatable" class="display compact table table-hover" style="width:100%;cursor:pointer">
                                        <thead>
                                            <tr>
                                                <th class="align-middle">Process ID</th>
                                                <th class="align-middle">Code Process for Itemcode</th>
                                                <th class="align-middle">Name</th>
                                                <th class="align-middle">Routing</th>
                                                <th class="align-middle">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('tms.db_parts.master_process.modal.form.index')
@endsection
@section('script')
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
@endsection
@push('js')
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('vendor/jqloading/jquery.loading.min.js') }}"></script>
<script>
    $('.this-datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        enableOnReadonly: false,
    }).datepicker("setDate",'now');
    $("input[type=number]").on("input", function() {
        var nonNumReg = /[^0-9.]/g
        $(this).val($(this).val().replace(nonNumReg, ''));
    });
    @if(\Session::has('message'))
    setTimeout(function () {
        Swal.fire({
            title: 'Warning!',
            text: "{{\Session::get('message')}}",
            icon: 'warning'
        }).then(function () {
            window.close();
        });
    }, 1000);
    @endif
</script>
@endpush