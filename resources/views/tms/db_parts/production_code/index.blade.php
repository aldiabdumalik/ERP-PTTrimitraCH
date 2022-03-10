@extends('master')
@section('title', 'TMS | Database Parts - Production Code')
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
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="prodcode-btn-modal-create">
                    <i class="ti-plus"></i>  Add New Data
                </button>
                <button type="button"  class="btn btn-danger btn-flat btn-sm" id="prodcode-btn-modal-trash">
                    <i class="ti-trash"></i>  View Trash Data
                </button>
            </div>
        </div>
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">Production Code</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="prodcode-datatable" class="display compact table table-hover" style="width:100%;cursor:pointer">
                                        <thead>
                                            <tr>
                                                <th class="align-middle">Type</th>
                                                <th class="align-middle">Part No</th>
                                                <th class="align-middle">Part Name</th>
                                                <th class="align-middle">Customer ID</th>
                                                <th class="align-middle">SEQ</th>
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
@include('tms.db_parts.production_code.modal.form.index')
@include('tms.db_parts.production_code.modal.table.imageView')
@include('tms.db_parts.production_code.modal.table.part')
@include('tms.db_parts.production_code.modal.table.detail_process')
@endsection
@section('script')
@include('tms.db_parts.production_code.jscript')
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