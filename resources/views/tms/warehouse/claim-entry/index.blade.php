@extends('master')
@section('title', 'TMS | Warehouse - Claim Entry')
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
    /* input[readonly] {
        background-color: #fff !important;
        cursor: not-allowed;
        pointer-events: all !important;
    } */
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
    /* .dataTables_scrollHeadInner {
        width: 100% !important;
    }
    .dataTables_scrollHeadInner table {
        width: 100% !important;
    }
    .dataTables_scrollBody::-webkit-scrollbar { 
        display: none;
    }
    .dataTables_scrollBody {
        -ms-overflow-style: none;
        scrollbar-width: none;
    } */
</style>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="claim-btn-modal-create">
                    <i class="ti-plus"></i>  Add New Data
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">Claim Entry</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="claim-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer">
                                        <thead class="text-center" style="font-size: 15px;">
                                            <tr>
                                                <th class="align-middle">CL No.</th>
                                                <th class="align-middle">Written</th>
                                                <th class="align-middle">Date DO</th>
                                                <th class="align-middle">Date RG</th>
                                                <th class="align-middle">RR No.</th>
                                                <th class="align-middle">Ref No.</th>
                                                <th class="align-middle">PO No.</th>
                                                <th class="align-middle">Customer</th>
                                                <th class="align-middle">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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
{{-- </div> --}}
@include('tms.warehouse.claim-entry.modal.create.index')
@include('tms.warehouse.claim-entry.modal.header.branch')
@include('tms.warehouse.claim-entry.modal.header.warehouse')
@include('tms.warehouse.claim-entry.modal.header.customer')
@include('tms.warehouse.claim-entry.modal.header.doaddr')
@include('tms.warehouse.claim-entry.modal.item.addItem')
@include('tms.warehouse.claim-entry.modal.item.tableItem')
@include('tms.warehouse.claim-entry.modal.log.tableLog')
@include('tms.warehouse.claim-entry.modal.status.do')
@include('tms.warehouse.claim-entry.modal.status.rg')
@include('tms.warehouse.claim-entry.modal.status.rgQty')
@include('tms.warehouse.claim-entry.modal.status.rgComplete')

@endsection

@section('script')
@include('tms.warehouse.claim-entry.ajax')
@endsection

@push('js')
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('/vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
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