@extends('master')
@section('title', 'TMS | Warehouse - Customer Invoice')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/datepicker/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')

@include('tms.warehouse.cust-invoice.style.custom-style')

<div class="main-content-inner">
    @include('tms.warehouse.cust-invoice.table.tableindex')
</div>
@include('tms.warehouse.cust-invoice.modal.create.index')
@include('tms.warehouse.cust-invoice.modal.table.customer')
@include('tms.warehouse.cust-invoice.modal.table.doaddr')
@include('tms.warehouse.cust-invoice.modal.table.sysaccount')
@include('tms.warehouse.cust-invoice.modal.table.do')
@include('tms.warehouse.cust-invoice.modal.table.tableLog')
@include('tms.warehouse.cust-invoice.modal.report.mreport')
@include('tms.warehouse.cust-invoice.modal.table.note')

@endsection
{{-- @section('script')
@endsection --}}
@push('js')
@include('tms.warehouse.cust-invoice.ajax')
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('vendor/jqloading/jquery.loading.min.js') }}"></script>
<script src="{{ asset('vendor/moment/moment-with-locales.js') }}"></script>
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