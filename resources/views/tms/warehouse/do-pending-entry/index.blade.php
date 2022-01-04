@extends('master')
@section('title', 'TMS | Warehouse - DO Pending Entry')
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
                        <h4 class="card-header-title">DO Pending Entry</h4>
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
@include('tms.warehouse.do-pending-entry.modal.header.branch')
@include('tms.warehouse.do-pending-entry.modal.header.warehouse')
@include('tms.warehouse.do-pending-entry.modal.header.customer')
@include('tms.warehouse.do-pending-entry.modal.header.doaddr')
@include('tms.warehouse.do-pending-entry.modal.item.table_item')
@include('tms.warehouse.do-pending-entry.modal.item.add_item')
@include('tms.warehouse.do-pending-entry.modal.log.tableLog')
@include('tms.warehouse.do-pending-entry.modal.print.modalPrint')
@include('tms.warehouse.do-pending-entry.modal.print.modalPrintDo')
@include('tms.warehouse.do-pending-entry.modal.table.tableNG')
@endsection
@section('script')
<script>
$(document).ready(function () {
    const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
    var table_index = new Promise((resolve, reject) => {
        let tbl_index =  $('#do-datatables').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{route('tms.warehouse.do_pending.tbl_index')}}",
                method: 'POST',
                headers: token_header
            },
            columns: [
                {data:'do_no', name: 'do_no', className: "align-middle"},
                {data:'written_date', name: 'written_date', className: "align-middle"},
                {data:'posted_date', name: 'posted_date', className: "align-middle"},
                {data:'finished_date', name: 'finished_date', className: "align-middle"},
                {data:'voided_date', name: 'voided_date', className: "align-middle"},
                {data:'ref_no', name: 'ref_no', className: "align-middle"},
                {data:'dn_no', name: 'dn_no', className: "align-middle"},
                {data:'po_no', name: 'po_no', className: "align-middle"},
                {data:'cust_id', name: 'cust_id', className: "align-middle"},
            ],
            order: [[ 0, "desc" ]],
        });
        resolve(tbl_index);
    });

    function ajax(route, method, params=null, callback) {
        $('body').loading({
            message: "wait for a moment...",
            zIndex: 9999
        });
        return $.ajax({
            url: route,
            method: method,
            dataType: "JSON",
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: params,
            error: function(response, status, x){
                Swal.fire({
                    title: 'Access Denied',
                    text: response.responseJSON.message,
                    icon: 'error'
                })
                $('body').loading('stop');
            },
            complete: function (response){
                callback(response);
                $('body').loading('stop');
            }
        });
    }
    function ajaxWithPromise(params) {
        return new Promise((resolve, reject) => {
            $('body').loading({
                message: "wait for a moment...",
                zIndex: 9999
            });
            $.ajax({
                url: params.route,
                method: params.method,
                dataType: "JSON",
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: params.data,
                error: function(response, status, x){
                    Swal.fire({
                        title: 'Access Denied',
                        text: response.responseJSON.message,
                        icon: 'error'
                    });
                    $('body').loading('stop');
                    reject(response);

                },
                complete: function (response){
                    $('body').loading('stop'); 
                    resolve(response);

                }
            });
        });
    }
    function modalAction(elementId=null, action='show'){
        return new Promise(resolve => {
            $(elementId).modal(action);
            resolve($(elementId));
        });
    }

    function hideShow(element=null, hide=true){
        return ((hide == true) ? $(element).addClass('d-none') : $(element).removeClass('d-none'));
    }

    function showNotif(params) {
        return new Promise((resolve, reject) => {
            Swal.fire({
                title: params.title,
                text: params.message,
                icon: params.icon
            }).then(function (res) {
                if (params.icon != 'error') {
                    resolve(res);
                }else{
                    reject(res);
                }
            });
        });
    }

    function toRoman(num){
        var roman = {
            M: 1000,
            CM: 900,
            D: 500,
            CD: 400,
            C: 100,
            XC: 90,
            L: 50,
            XL: 40,
            X: 10,
            IX: 9,
            V: 5,
            IV: 4,
            I: 1
        };
        var str = '';
        for (var i of Object.keys(roman)) {
            var q = Math.floor(num / roman[i]);
            num -= q * roman[i];
            str += i.repeat(q);
        }
        return str;
    }

    function resetCreateForm() {
        $('#do-create-sso').val('');
        $('#do-create-so').val('');
        $('#do-create-pono').val('');
        $('#do-create-dnno').val('');
        tbl_additem.clear().draw(false);
    }

    function date_convert($date) {
        var convert = ($date !== null) ? $date.split('-') : null;
        return (convert !== null) ? `${convert[2]}/${convert[1]}/${convert[0]}` : null;
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