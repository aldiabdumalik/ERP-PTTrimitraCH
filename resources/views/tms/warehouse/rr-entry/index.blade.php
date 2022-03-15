@extends('master')
@section('title', 'TMS | Warehouse - RR Entry')
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
            <div class="form-row align-items-center mb-1">
                <div class="col-1">
                    <label for="rr-create-dono">DO No.</label>
                </div>
                <div class="col-11">
                    <div class="form-row align-items-center mb-1">
                        <div class="col-8">
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <input type="text" name="rr-create-dono" id="rr-create-dono" class="form-control form-control-sm" autocomplete="off" required>
                                </div>
                                <div class="col-4">
                                    <input type="text" name="rr-create-ref" id="rr-create-ref" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
                                </div>
                                <div class="col-4">
                                    <input type="text" name="rr-create-dodate" id="rr-create-dodate" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-row align-items-center mb-1">
                                <div class="col-2">
                                    <label for="rr-create-user">User</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="rr-create-user" id="rr-create-user" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required value="{{auth()->user()->FullName}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row align-items-center mb-1">
                <div class="col-1">
                    <label for="rr-create-custcode">Company</label>
                </div>
                <div class="col-11">
                    <div class="row no-gutters">
                        <div class="col-2">
                            <input type="text" name="rr-create-custcode" id="rr-create-custcode" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
                        </div>
                        <div class="col-10">
                            <input type="text" name="rr-create-custname" id="rr-create-custname" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row align-items-center mb-1">
                <div class="col-1">
                    <label for="rr-create-dnno">DN/PO No.</label>
                </div>
                <div class="col-11">
                    <div class="row no-gutters">
                        <div class="col-8">
                            <div class="row no-gutters">
                                <div class="col-6">
                                    <input type="text" name="rr-create-dnno" id="rr-create-dnno" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="rr-create-pono" id="rr-create-pono" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row align-items-center mb-1">
                <div class="col-1">
                    <label for="rr-create-sso">SO/SSO No.</label>
                </div>
                <div class="col-11">
                    <div class="row no-gutters">
                        <div class="col-8">
                            <div class="row no-gutters">
                                <div class="col-6">
                                    <input type="text" name="rr-create-sso" id="rr-create-sso" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="rr-create-so" id="rr-create-so" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row align-items-center mb-1">
                <div class="col-1">
                    <label for="rr-create-rrno">RR No./Date.</label>
                </div>
                <div class="col-11">
                    <div class="row no-gutters">
                        <div class="col-8">
                            <div class="row no-gutters">
                                <div class="col-6">
                                    <input type="text" name="rr-create-rrno" id="rr-create-rrno" class="form-control form-control-sm" autocomplete="off" required>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="rr-create-rrdate" id="rr-create-rrdate" class="form-control form-control-sm this-datepicker" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row align-items-center mb-1">
                <div class="col-1">
                    <label for="rr-create-scuritystamp">Scurity stamp.</label>
                </div>
                <div class="col-11">
                    <div class="row no-gutters">
                        <div class="col-8">
                            <div class="row no-gutters">
                                <div class="col-6">
                                    <input type="text" name="rr-create-scuritystamp" id="rr-create-scuritystamp" class="form-control form-control-sm this-datepicker" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-3">
            <div class="table-responsive">
                <div class="datatable datatable-primary">
                    <table id="rr-datatables-create" class="table table-bordered" style="width:100%;cursor:pointer">
                        <thead class="text-center" style="font-size: 15px;">
                            <tr style="font-size: 14px;">
                                <th class="align-middle">#</th>
                                <th class="align-middle">Part No.</th>
                                <th class="align-middle">Itemcode</th>
                                <th class="align-middle">Descript</th>
                                <th class="align-middle">Unit</th>
                                <th class="align-middle">Qty</th>
                                <th class="align-middle">Retur</th>
                            </tr>
                        </thead>
                        <tbody class="text-center"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-end">
        <div class="col-6 mt-3 text-right">
            <button type="button" id="rr-create-btn-reset" class="btn btn-default">Cancel</button>
            <button type="button" id="rr-create-btn-ok" class="btn btn-primary">OK</button>
        </div>
    </div>
</div>
@include('tms.warehouse.rr-entry.modal.domodal')
@endsection
{{-- @section('script')

@endsection --}}
@push('js')
@include('tms.warehouse.rr-entry.ajax')
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