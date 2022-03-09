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
@section('script')
<script>
$(document).ready(function () {
    const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};

    var tbl_customer;
    $(document).on('keypress keydown', '#partreport-index-customercode', function (e) {
        if(e.which == 13) {
            modalAction('#partreport-modal-customer').then((resolve) => {
                tbl_customer = $('#partreport-datatables-customer').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ route('tms.db_parts.input_parts.header_tools') }}",
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
    $(document).on('shown.bs.modal', '#partreport-modal-customer', function () {
        $('#partreport-datatables-customer_filter input').focus();
    });
    $('#partreport-datatables-customer').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        var data = tbl_customer.row(this).data();
        modalAction('#partreport-modal-customer', 'hide').then(() => {
            $('#partreport-index-customercode').val(data.code);
            $('#partreport-index-customername').val(data.name);
            
            getType(data.code)
        });
    });

    $('#form-print').on('submit', function (e) {
        e.preventDefault();
        let customer = $('#partreport-index-customercode').val(),
            type = $('#partreport-index-parttype').val(),
            encrypt = btoa(`${customer}&${type}`),
            url = "{{route('tms.db_parts.report.print')}}?params=" + encrypt;
        window.open(url, '_blank');
    });

    function getType(customer) {
        $('#partreport-index-parttype').find('option').not(':first').remove();
        let route = "{{ route('tms.db_parts.report.parts', [':customer']) }}";
        route  = route.replace(':customer', customer);
        ajaxCall({route:route, method: "GET"}).then(response => {
            if (response.content != null) {
                $.each(response.content, function (i, type) {
                    $('#partreport-index-parttype').append($('<option>', {
                        text: type.type,
                        value: type.type,
                    }));
                });
            }
        });
    }

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
                    $('body').loading('stop');
                    Swal.fire({
                        title: 'Something was wrong',
                        text: response.responseJSON.message,
                        icon: 'error'
                    }).then(() => {
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