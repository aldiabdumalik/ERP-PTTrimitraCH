@extends('master')
@section('title', 'TMS | Master - Database Parts')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/datepicker/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
@include('tms.master.db-parts.style.custom-style')

<div class="main-content-inner">
    @include('tms.master.db-parts.table.index')
</div>
@include('tms.master.db-parts.modal.form.index')
@include('tms.master.db-parts.modal.table.customer')
@include('tms.master.db-parts.modal.table.unit')
@endsection
@section('script')
<script>
$(document).ready(function () {
    const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
    var index_table = $('#dbpart-datatable').DataTable();
    $('#dbpart-modal-index').modal('show');

    var tbl_process = $('#dbpart-datatables-index').DataTable({
        destroy: true,
        lengthChange: false,
        searching: false,
        sDom: 'lrtip',
        paging: false,
        ordering: false,
        scrollY: "200px",
        scrollCollapse: true,
        fixedHeader: true,
        scrollX: true
    });
    
    $('#dbpart-modal-index').on('shown.bs.modal', function () {
        adjustDraw(tbl_process);
    });

    var tbl_customer;
    $(document).on('keypress keydown', '#dbpart-index-customercode', function (e) {
        if(e.which == 13) {
            modalAction('#dbpart-modal-customer').then((resolve) => {
                tbl_customer = $('#dbpart-datatables-customer').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ route('tms.master.db_part.header_tools') }}",
                        method: 'POST',
                        data: {"type": "customer"},
                        headers: token_header
                    },
                    columns: [
                        {data: 'code', name: 'code'},
                        {data: 'name', name: 'name'},
                    ]
                });
            });
        }
        if(e.which == 8 || e.which == 46) { return false; }
        return false;
    });

    $(document).on('shown.bs.modal', '#dbpart-modal-customer', function () {
        $('#dbpart-datatables-customer_filter input').focus();
    });

    $('#dbpart-datatables-customer').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        var data = tbl_customer.row(this).data();
        modalAction('#dbpart-modal-customer', 'hide').then(resolve => {
            $('#dbpart-index-customercode').val(data.code);
            $('#dbpart-index-customername').val(data.name);
            $('#dbpart-index-customeraddr1').val(data.ad1);
            $('#dbpart-index-customeraddr2').val(data.ad2);
            $('#dbpart-index-customeraddr3').val(data.ad3);
            $('#dbpart-index-customeraddr4').val(data.ad4);
        });
    });

    var tbl_unit;
    $(document).on('keypress keydown', '#dbpart-index-unit', function (e) {
        if(e.which == 13) {
            modalAction('#dbpart-modal-unit').then((resolve) => {
                tbl_unit = $('#dbpart-datatables-unit').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ route('tms.master.db_part.header_tools') }}",
                        method: 'POST',
                        data: {"type": "unit"},
                        headers: token_header
                    },
                    columns: [
                        {data: 'unit', name: 'unit'},
                        {data: 'des', name: 'des'},
                    ]
                });
            });
        }
        if(e.which == 8 || e.which == 46) { return false; }
        return false;
    });
    $(document).on('shown.bs.modal', '#dbpart-modal-unit', function () {
        $('#dbpart-datatables-unit_filter input').focus();
    });
    $('#dbpart-datatables-unit').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        var data = tbl_unit.row(this).data();
        modalAction('#dbpart-modal-unit', 'hide').then(resolve => {
            $('#dbpart-index-unit').val(data.unit);
        });
    });

    $('#dbpart-index-pict').on('change',function(){
        var fileName = $(this).val().replace('C:\\fakepath\\', " ");
        var ext = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(ext, fileName.length).toLowerCase();
        if (!fileName) {
            fileName = 'Choose file';
        }
        if (extFile=="jpg" || extFile=="jpeg" || extFile=="png" || !extFile){
            $(this).next('.custom-file-label').html(fileName);
        }else{
            $(this).next('.custom-file-label').html('Choose file');
            $(this).val(null);
        }
    })
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
                    Swal.fire({
                        title: 'Access Denied',
                        text: response.responseJSON.message,
                        icon: 'error'
                    }).then(() => {
                        $('body').loading('stop');
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