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
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">Report</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-6">
                            <form action="javascript:void(0)" id="form-print">
                                <div class="form-row align-items-center mb-1">
                                    <div class="col-2">
                                        <label for="partreport-index-customercode" class="auto-middle">Customer</label>
                                    </div>
                                    <div class="col-10">
                                        <div class="row no-gutters">
                                            <div class="col-4">
                                                <input type="text" name="partreport-index-customercode" id="partreport-index-customercode" class="form-control form-control-sm" placeholder="Press Enter..." autocomplete="off" required>
                                            </div>
                                            <div class="col-8">
                                                <input type="text" name="partreport-index-customername" id="partreport-index-customername" class="form-control form-control-sm readonly-first" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row align-items-center mb-1">
                                    <div class="col-2">
                                        <label for="partreport-index-parttype" class="auto-middle">Part Type</label>
                                    </div>
                                    <div class="col-10">
                                        <select name="partreport-index-parttype" id="partreport-index-parttype" class="form-control form-control-sm" required>
                                            <option value="">Select part type</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row align-items-center mb-1">
                                    <div class="col-12 text-right">
                                        <button type="submit" class="btn btn-info btn-sm" style="padding-left: 30px;padding-right: 30px;">Print</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('tms.db_parts.report.modal.customer')
@endsection
{{-- @section('script')

@endsection --}}
@push('js')
@include('tms.db_parts.report.ajax')
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