@extends('master')
@section('title', 'TMS | Manufacturing - THP Entry')
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
    #thp-select-datepicker .datepicker table tr td, #thp-select-datepicker .datepicker table tr th{
        text-align:center;
        width: 50px !important;
        height: 50px !important;
        border-radius:4px;
        border:none
    }
    #thp-select-datepicker .datepicker {
        width: 100% !important;
    }
    input[readonly] {
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
    .auto-middle {
        margin-top: auto;
        margin-bottom: auto;
    }
    .bg-abu {
        background-color: #d3d3d3;
    }
</style>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-info btn-flat btn-sm" id="searchModal">
                    <i class="fa fa-calendar"></i>  Search By Date
                </button>
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="addModal">
                    <i class="ti-plus"></i>  Add New Data
                </button>
                <button type="button"  class="btn btn-outline-success btn-flat btn-sm" id="importModal">
                    <i class="fa fa-upload"></i>  Import
                </button>
                <div class="dropdown" style="display: inline !important;">
                    <button class="btn btn-outline-danger btn-flat btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-download"></i> Report
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a id="printModal" class="dropdown-item" href="javascript:void(0)">Daily Report</a>
                        <a id="printModalSummary" class="dropdown-item" href="javascript:void(0)">Summary Report</a>
                    </div>
                </div>
                <div class="dropdown" style="display: inline !important;">
                    <button class="btn btn-outline-primary btn-flat btn-sm dropdown-toggle" type="button" id="dropdownSettingButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-cog"></i> Setting
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownSettingButton">
                        <a id="settingPersentaseModal" class="dropdown-item" href="javascript:void(0)">Min Persentase</a>
                    </div>
                </div>
                <button type="button"  class="btn btn-success btn-flat btn-sm" id="refreshlhp">
                    <i class="fa fa-refresh"></i>  Refresh LHP
                </button>
                <button type="button" class="btn btn-warning btn-flat btn-sm" id="thpnotif">
                    <i class="fa fa-bell-o"></i> Notifications <span class="badge badge-pill badge-light thpnotif-num">{{$notif==0?'':$notif}}</span>
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-header-title">THP Entry</h4>
                        </div>
                        <div class="col-6"><h4 class="card-header-title text-right">Date : <span id="thp-date">{{date('d/m/Y')}}</span></h4></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="thp-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer;font-size:12px;">
                                        <thead class="text-center" style="font-size: 15px;">
                                            <tr>
                                                <th>THP No.</th>
                                                <th>Date</th>
                                                {{-- <th>Date Order</th> --}}
                                                <th>Customer</th>
                                                <th>Production Code</th>
                                                <th>Part Name</th>
                                                {{-- <th>Part Type</th> --}}
                                                <th>Route</th>
                                                <th>Process</th>
                                                <th>THP Qty</th>
                                                <th>LHP Qty</th>
                                                <th>Apnormal.</th>
                                                <th>Action</th>
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
</div>
@include('tms.manufacturing.thp_entry._modal.create.createForm')
@include('tms.manufacturing.thp_entry._modal.view_thp_modal._productioncode')
@include('tms.manufacturing.thp_entry._modal.detail.indexDetail')
@include('tms.manufacturing.thp_entry._modal.detail.apnormal')
@include('tms.manufacturing.thp_entry._modal.view_thp_modal._viewlog')
@include('tms.manufacturing.thp_entry._modal.view_thp_modal._printThp')
@include('tms.manufacturing.thp_entry._modal.view_thp_modal._printThpsummary')
@include('tms.manufacturing.thp_entry._modal.import.importThp')
@include('tms.manufacturing.thp_entry._modal.close_thp_modal._closethp')
@include('tms.manufacturing.thp_entry._modal.setting.setPersentase')
@include('tms.manufacturing.thp_entry._modal.setting.notif')
@include('tms.manufacturing.thp_entry._modal.view_thp_modal.shortThpByDate')
@include('tms.manufacturing.thp_entry._modal.form.index')


@endsection

{{-- @section('script')

@endsection --}}


@push('js')
@include('tms.manufacturing.thp_entry.ajax')

<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('vendor/jqloading/jquery.loading.min.js') }}"></script>
<script>
    $('.print-datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    }).datepicker("setDate",'now');
    $('.this-datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
    }).datepicker("setDate",'now');
    $("input[type=number]").on("input", function() {
        var nonNumReg = /[^0-9.]/g
        $(this).val($(this).val().replace(nonNumReg, ''));
    });
    @if(\Session::has('msg'))
    setTimeout(function () {
        Swal.fire({
            title: 'Warning!',
            text: "{{\Session::get('msg')}}",
            icon: 'warning'
        }).then(function () {
            window.close();
        });
    }, 1000);
    @endif
</script>
@endpush