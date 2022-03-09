@extends('master')
@section('title', 'TMS | Warehouse - DO Temp Entry')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/datepicker/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
<style>
    .modal{
        overflow: auto;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none !important; 
        margin: 0 !important; 
    }
    input[readonly] {
        /* background-color: #fff !important; */
        cursor: not-allowed;
        pointer-events: all !important;
    }
    .row.no-gutters {
        margin-right: 0;
        margin-left: 0;

        & > [class^="col-"],
        & > [class*=" col-"] {
            padding-right: 0;
            padding-left: 0;
        }
    }
    button:disabled {
        cursor: not-allowed;
        pointer-events: all !important;
    }
    .selected {
        background-color: #dddddd;
    }
    .btn-td {
        display:block;
        width: 100%;
        height: 100%;
    }
    .bg-abu {
        background-color: #d3d3d3;
    }
</style>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="do-btn-modal-create">
                    <i class="ti-plus"></i>  Add New Data
                </button>
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="do-btn-modal-print">
                    <i class="fa fa-print"></i>  Print
                </button>
                {{-- <button type="button"  class="btn btn-outline-primary btn-flat btn-sm" id="do-btn-modal-table-setting">
                    <i class="fa fa-cogs"></i>
                </button> --}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">DO Temporary Entry</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="do-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer">
                                        <thead>
                                            <tr>
                                                <th>DO No</th>
                                                <th>Date</th>
                                                <th>Posted</th>
                                                <th>Finish</th>
                                                <th>Voided</th>
                                                <th>Ref No</th>
                                                <th>DN No</th>
                                                <th>PO No</th>
                                                <th>Customer</th>
                                                <th>Action</th>
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
@include('tms.warehouse.do-pending-entry.modal.create.index')
{{-- @include('tms.warehouse.do-pending-entry.modal.header.branch') --}}
@include('tms.warehouse.do-pending-entry.modal.header.warehouse')
@include('tms.warehouse.do-pending-entry.modal.header.customer')
@include('tms.warehouse.do-pending-entry.modal.header.doaddr')
@include('tms.warehouse.do-pending-entry.modal.header.posted')
@include('tms.warehouse.do-pending-entry.modal.item.table_item')
{{-- @include('tms.warehouse.do-pending-entry.modal.item.add_item') --}}
@include('tms.warehouse.do-pending-entry.modal.log.tableLog')
@include('tms.warehouse.do-pending-entry.modal.print.modalPrint')
@include('tms.warehouse.do-pending-entry.modal.print.modalPrintDo')
@include('tms.warehouse.do-pending-entry.modal.table.tableNG')
@endsection
@section('script')
@include('tms.warehouse.do-pending-entry.ajax')
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