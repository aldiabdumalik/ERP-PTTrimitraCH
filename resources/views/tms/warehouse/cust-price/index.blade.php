@extends('master')
@section('title', 'TMS | Warehouse - Customer Price')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/datepicker/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')

@include('tms.warehouse.cust-price.style.custom-style')

<div class="main-content-inner">
    @include('tms.warehouse.cust-price.table.index')
</div>
@include('tms.warehouse.cust-price.modal.create.index')
@include('tms.warehouse.cust-price.modal.header.customer')

@endsection
@section('script')
<script>
    $(document).ready(function () {
        const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
        const tbl_attr = (targets=[]) => {
            const obj = {
                destroy: true,
                lengthChange: false,
                searching: false,
                paging: false,
                ordering: false,
                scrollY: "200px",
                scrollCollapse: true,
                fixedHeader: true,
                columnDefs: [{
                    targets: targets,
                    createdCell:  function (td, cellData, rowData, row, col) {
                        $(td).addClass('text-right');
                    }
                }]
            };
            return obj;
        };

        const index_data = new Promise(function(resolve, reject) {
            let tbl_index = $('#custprice-datatables').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('tms.warehouse.cust_price.index')}}",
                    method: 'POST',
                    headers: token_header
                },
                columns: [
                    {data:'cust_id', name: 'cust_id', className: "text-center align-middle"},
                    {data:'CustomerName', name: 'CustomerName', className: "text-left align-middle"},
                    {data:'created_date', name: 'created_date', className: "text-center align-middle"},
                    {data:'active_date', name: 'active_date', className: "text-center align-middle"},
                    {data:'posted_date', name: 'posted_date', className: "text-center align-middle"},
                    {data:'voided_date', name: 'voided_date', className: "text-center align-middle"},
                    {data:'action', name: 'action', orderable: false, searchable: false, className: "text-center align-middle"},
                ],
                ordering: false,
            });
            resolve(tbl_index);
        });

        $('#custprice-btn-modal-create').on('click', function () {
            modalAction('#custprice-modal-index');
        });

        var tbl_item = $('#custprice-datatables-index').DataTable(tbl_attr([0,4,5]));
        
        $('#custprice-modal-index').on('shown.bs.modal', function () {
            adjustDraw(tbl_item);
        });

        var tbl_customer = $('#custprice-datatables-customer').DataTable({
            destroy: true,
            lengthChange: false,
            ordering: false,
            fixedHeader: true,
        });

        $('#custprice-create-customercode').on('keypress', function (e) {
            e.preventDefault();
            if (e.keyCode == 13) {
                modalAction('#custprice-modal-customer').then(resolve => {
                    ajaxCall({route: "{{route('tms.warehouse.cust_price.header')}}", method: "GET", data: {type: "customer"}}).then(resolve => {
                        let customer = resolve.content;
                        $.each(customer, function (i, cust) {
                            tbl_customer.row.add([
                                cust.code,
                                cust.name
                            ]);
                        });
                        tbl_customer.draw();
                    });
                });
            }
            return false;
        });

        $('#custprice-modal-customer').on('shown.bs.modal', function () {
            adjustDraw(tbl_customer);
        });

        $('#custprice-modal-index').on('hidden.bs.modal', function () {
            resetForm();
        });

        $('#custprice-datatables-customer').off('click', 'tr').on('click', 'tr', function () {
            var data = tbl_customer.row(this).data();
            modalAction('#custprice-modal-customer', 'hide').then(resolve => {
                $('#custprice-create-customercode').val(data[0]);
                $('#custprice-create-customername').val(data[1]);
                if (data[0] === "A01") {
                    $('#custprice-create-priceby').val('DATE');
                }else{
                    $('#custprice-create-priceby').val('SO');
                }
            });
        });

        $(document).on('click', '.custprice-act-view', function () {
            var cust = $(this).data('custid');
            var date = $(this).data('activedate');
            var route = "{{route('tms.warehouse.cust_price.detail', [':cust', ':date'])}}";
            route  = route.replace(':cust', cust);
            route  = route.replace(':date', date);
            modalAction('#custprice-modal-index').then(resolve => {
                $(tbl_item.table().header())
                    .removeClass('btn-info')
                    .addClass('bg-abu');
                isHidden('#custprice-btn-table-item', true);
                isHidden('#custprice-btn-index-submit', true);
                $('input').prop('readonly', true);

                ajaxCall({route: route, method: "GET"}).then(resolve => {
                    if (resolve.status == true) {
                        var no = 1;
                        var cust_id, cust_name, valas, active_date, created, user, posted, voided, printed;
                        $.each(resolve.content, function (i, data) {
                            tbl_item.row.add([
                                no,
                                data.item_code,
                                data.PART_NO,
                                data.DESCRIPT,
                                (data.price_new == null ? "0.00" : addZeroes(String(data.price_new))),
                                "0.00",
                            ]);
                            no++;
                            cust_id = data.cust_id;
                            cust_name = data.CustomerName;
                            valas = data.currency;
                            active_date = data.active_date;
                            created = data.created_date;
                            user = data.created_by;
                            posted = data.posted_date;
                            voided = data.voided_date;
                            printed = data.printed_date;
                        });
                        tbl_item.draw();
                        $('#custprice-create-customercode').val(cust_id);
                        $('#custprice-create-customername').val(cust_name);
                        $('#custprice-create-posted').val(date_convert(posted));
                        $('#custprice-create-voided').val(date_convert(voided));
                        $('#custprice-create-printed').val(date_convert(printed));
                        $('#custprice-create-user').val(user);
                        $('#custprice-create-valas').val(valas);
                        $('#custprice-create-priceby').val($('#custprice-create-priceby').data('val'));
                        $('#custprice-create-activedate').val(date_convert(active_date));
                        $('#custprice-create-entrydate').val(date_convert(created));
                    }
                });
            });
        });

        $(document).on('click', '.custprice-act-edit', function () {
            var cust = $(this).data('custid');
            var date = $(this).data('activedate');
            var route = "{{route('tms.warehouse.cust_price.detail', [':cust', ':date'])}}";
            route  = route.replace(':cust', cust);
            route  = route.replace(':date', date);
            modalAction('#custprice-modal-index').then(resolve => {

                ajaxCall({route: route, method: "GET"}).then(resolve => {
                    if (resolve.status == true) {
                        var no = 1;
                        var cust_id, cust_name, valas, active_date, created, user, posted, voided, printed;
                        $.each(resolve.content, function (i, data) {
                            tbl_item.row.add([
                                no,
                                data.item_code,
                                data.PART_NO,
                                data.DESCRIPT,
                                (data.price_new == null ? "0.00" : addZeroes(String(data.price_new))),
                                "0.00",
                            ]);
                            no++;
                            cust_id = data.cust_id;
                            cust_name = data.CustomerName;
                            valas = data.currency;
                            active_date = data.active_date;
                            created = data.created_date;
                            user = data.created_by;
                            posted = data.posted_date;
                            voided = data.voided_date;
                            printed = data.printed_date;
                        });
                        tbl_item.draw();
                        $('#custprice-create-customercode').val(cust_id);
                        $('#custprice-create-customername').val(cust_name);
                        $('#custprice-create-posted').val(date_convert(posted));
                        $('#custprice-create-voided').val(date_convert(voided));
                        $('#custprice-create-printed').val(date_convert(printed));
                        $('#custprice-create-user').val(user);
                        $('#custprice-create-valas').val(valas);
                        $('#custprice-create-priceby').val($('#custprice-create-priceby').data('val'));
                        $('#custprice-create-activedate').val(date_convert(active_date));
                        $('#custprice-create-entrydate').val(date_convert(created));
                    }
                });
            });
        });

        // Function lib
        function resetForm() {
            $('#custprice-create-customercode').val(null);
            $('#custprice-create-customername').val(null);
            $('#custprice-create-posted').val(null);
            $('#custprice-create-voided').val(null);
            $('#custprice-create-printed').val(null);
            $('#custprice-create-user').val($('#custprice-create-user').data('val'));
            $('#custprice-create-valas').val($('#custprice-create-valas').data('val'));
            $('#custprice-create-priceby').val($('#custprice-create-priceby').data('val'));
            $('#custprice-create-activedate').val("{{date('d/m/Y')}}");
            $(tbl_item.table().header())
                .removeClass('bg-abu')
                .addClass('btn-info');
            tbl_item.clear().draw(false);
            isHidden('#custprice-btn-table-item', false);
            isHidden('#custprice-btn-index-submit', false);
            $('input').not('.readonly-first').prop('readonly', false);
        }
        function date_convert($date) {
            if ($date == null) { return null; }
            var first = $date.split(' ');
            if (first[0] !== "") { $date = first[0]; }
            var convert = ($date !== null) ? $date.split('-') : null;
            return (convert !== null) ? `${convert[2]}/${convert[1]}/${convert[0]}` : null;
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

        function ajaxCall(params) {
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
                    headers: token_header,
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
        function isHidden(element=null, hide=true){
            return ((hide == true) ? $(element).addClass('d-none') : $(element).removeClass('d-none'));
        }
        function adjustDraw(tbl) {
            return tbl.columns.adjust().draw();
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