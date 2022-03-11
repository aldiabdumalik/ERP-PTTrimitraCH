@extends('master')
@section('title', 'TMS | Master - Customer Price')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/datepicker/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')

@include('tms.master.cust-price.style.custom-style')

<div class="main-content-inner">
    @include('tms.master.cust-price.table.index')
</div>
@include('tms.master.cust-price.modal.create.index')
@include('tms.master.cust-price.modal.header.customer')
@include('tms.master.cust-price.modal.header.customer-search')
@include('tms.master.cust-price.modal.create.itemTableAdd')
@include('tms.master.cust-price.modal.create.itemFormAdd')
@include('tms.master.cust-price.modal.log.tableLog')
@include('tms.master.cust-price.modal.log.itemcodeLog')
@include('tms.master.cust-price.modal.action.action')
@include('tms.master.cust-price.modal.action.posted')

@endsection
{{-- @section('script')
@endsection --}}
@push('js')
@include('tms.master.cust-price.ajax')
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