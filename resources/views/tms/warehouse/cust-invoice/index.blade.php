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

@endsection
@section('script')
<script>
    $(document).ready(function () {
        moment().format();
        moment.locale('id');
        var do_selected = [];
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
            let tbl_index = $('#custinv-datatables').DataTable();
            resolve(tbl_index);
        });

        $('#custinv-btn-modal-create').on('click', function () {
            modalAction('#custinv-modal-index').then(resolve => {
                ajaxCall({route: "{{route('tms.warehouse.cust_invoice.header')}}", method: "POST", data: {type: 'invno'}}).then(resolve => {
                    $('#custinv-create-no').val(resolve);
                    $('#custinv-create-date').datepicker("setDate",'now');
                    var now = new Date();
                    var currentMonth = ('0'+(now.getMonth()+1)).slice(-2);
                    $('#custinv-create-priod').val(`${now.getFullYear()}-${currentMonth}`);

                    ajaxCall({route: "{{route('tms.warehouse.cust_invoice.header')}}", method: "POST", data: {type: 'currency'}}).then(resolve => {
                        var data = resolve.content;
                        $.each(data, function (i, valas) {
                            $('#custinv-create-currency-type').append($('<option>', { 
                                value: valas.valas,
                                text : valas.valas 
                            }));
                        });
                        
                        ajaxCall({route: "{{route('tms.warehouse.cust_invoice.header')}}", method: "POST", data: {type: 'currency', currency: 'IDR'}}).then(resolve => {
                            $('#custinv-create-currency-type').val('IDR');
                            $('#custinv-create-currenvy-value').val(currency(addZeroes(String(resolve.content.rate)))); 
                        });
                    });
                });
            });
        });

        $(document).on('change', '#custinv-create-currency-type', function () {
            var id = $(this).val();
            ajaxCall({route: "{{route('tms.warehouse.cust_invoice.header')}}", method: "POST", data: {type: 'currency', currency: id}}).then(resolve => {
                $('#custinv-create-currenvy-value').val(currency(addZeroes(String(resolve.content.rate))));
            });
        });

        $(document).on('keyup', '#custinv-create-terms', function (e) {
            var val = $(this).val();
            if (val>0) {
                $('#custinv-create-duedate').val(moment($('#custinv-create-date').val(), "DD/MM/YYYY").add(val, 'days').format('L'));
            }
        });

        var tbl_item = $('#custinv-datatables-index').DataTable(tbl_attr([0,7,8,9]));
        var tbl_item_part = $('#custinv-datatables-index-part').DataTable(tbl_attr([6,7,8]));

        $('#custinv-modal-index').on('shown.bs.modal', function () {
            tbl_item.columns.adjust().draw();
            tbl_item_part.columns.adjust().draw();
        });

        $('#carouselExampleSlidesOnly').on('slid.bs.carousel', function () {
            var idx = $(this).find('.active').index();
            if (idx == 0) {
                $('#custinv-text-view-by').text("VIEW BY DO NO.");
            }else{
                tbl_item_part.columns.adjust().draw();
                $('#custinv-text-view-by').text("VIEW BY PART NO.");
            }
        });

        var tbl_do;
        function tblDoEntry(cust_id) {
            tbl_do = $('#custinv-datatables-do').DataTable({
                processing: true,
                serverSide: true,
                // destroy: true,
                ajax: {
                    url: "{{ route('tms.warehouse.cust_invoice.do') }}",
                    method: 'POST',
                    data: {cust_id: cust_id},
                    headers: token_header
                },
                columns: [
                    {data: 'cust_id', name: 'cust_id', className: 'text-center'},
                    {data: 'do_no', name: 'do_no', className: 'text-center'},
                    {data: 'dn_no', name: 'dn_no', className: 'text-center'},
                    {data: 'po_no', name: 'po_no', className: 'text-center'},
                    {data: 'ref_no', name: 'ref_no', className: 'text-center'},
                    {data: 'sso_no', name: 'sso_no', className: 'text-center'},
                    {data: 'do_date', name: 'do_date', className: 'text-center'},
                    {data: 'tot_qty', name: 'tot_qty', className: 'text-center'},
                    // {data: 'cust_id', name: 'cust_id', className: 'text-center'},
                ],
                ordering: false,
                scrollY: "300px",
                scrollCollapse: true,
                fixedHeader: true,
                createdRow: function( row, data, dataIndex ) {
                    $(row).attr('data-id', data.do_no);
                    $(row).attr('id', data.do_no);
                },
                rowCallback: function( row, data ) {
                    if ( $.inArray(String(data.do_no), do_selected) !== -1 ) {
                        $(row).addClass('selected');
                    }
                }
            });
        }

        var tbl_customer;
        $(document).on('keypress', '#custinv-create-customercode', function (e) {
            if(e.which == 13) {
                modalAction('#custinv-modal-customer').then((resolve) => {
                    var params = {"type": "customer"}
                    var column = [
                        {data: 'code', name: 'code'},
                        {data: 'name', name: 'name'},
                    ];
                    tbl_customer = $('#custinv-datatables-customer').DataTable({
                        processing: true,
                        serverSide: true,
                        destroy: true,
                        ajax: {
                            url: "{{ route('tms.warehouse.cust_invoice.header') }}",
                            method: 'POST',
                            data: params,
                            headers: token_header
                        },
                        columns: column,
                    });
                });
            }
            return false;
        });
        $('#custinv-datatables-customer tbody').off('click', 'tr').on('click', 'tr', function () {
            var data = tbl_customer.row(this).data();
            modalAction('#custinv-modal-customer', 'hide').then(resolve => {
                var cust_id = data.code;
                $('#custinv-create-customercode').val(data.code);
                $('#custinv-create-customername').val(data.name);
                $('#custinv-create-an').val(data.cont);
                $('#custinv-create-customeraddr1').val(data.ad1);
                $('#custinv-create-customeraddr2').val(data.ad2);
                $('#custinv-create-customeraddr3').val(data.ad3);
                $('#custinv-create-customeraddr4').val(data.ad4);
                $('#custinv-create-glcode').val(data.glcode);
                do_selected = [];
                $('#custinv-datatables-do').DataTable().destroy();
                ajaxCall({route: "{{route('tms.warehouse.cust_invoice.header')}}", method: "POST", data: {type: 'sys_account', number: data.glcode}}).then(resolve => {
                    $('#custinv-create-glket').val(resolve.content.name);
                    modalAction('#custinv-modal-do').then(resolve => {
                        tblDoEntry(cust_id);
                    });
                });
            });
        });

        $(document).on('click', '#custinv-btn-add-item', function () {
            var cust_id = $('#custinv-create-customercode').val();
            if (cust_id == "") {
                Swal.fire({
                    title: 'Warning',
                    text: 'Input customer first!',
                    icon: 'warning'
                });
            }else{
                modalAction('#custinv-modal-do').then(resolve => {
                    tbl_do.destroy();
                    tblDoEntry(cust_id);
                });
            }
        });

        $('#custinv-datatables-do').off('click', 'tr').on('click', 'tr', function () {
            var id = this.id;
            var index = $.inArray(id, do_selected);

            if ( index === -1 ) {
                do_selected.push( id );
            } else {
                do_selected.splice( index, 1 );
            }

            $(this).toggleClass('selected');
        });

        $(document).on('click', '#custinv-btn-do-ok', function () {
            if (do_selected.length <= 0) {
                Swal.fire({
                    title: 'Warning',
                    text: 'Please select DO Entry first!',
                    icon: 'warning'
                });
            }else{
                modalAction('#custinv-modal-do', 'hide').then(() => {
                    ajaxCall({
                        route: "{{ route('tms.warehouse.cust_invoice.do') }}", 
                        method: "POST", 
                        data: {
                            call_do_id: true,
                            arr_do: do_selected,
                            cust_id: $('#custinv-create-customercode').val()
                        }
                    }).then(resolve => {
                        var by_item = resolve.content.itemcode;
                        console.log(resolve.content);
                        eachByDO(resolve.content.do).then(resolve => {
                            eachByItemcode(by_item);
                        });
                    });
                });
            }
        });

        function eachByDO(response) {
            return new Promise((resolve, reject) => {
                tbl_item.clear().draw(false);
                var no = 1;
                var subtotal = 0;
                $.each(response, function (i, data) {
                    var add = tbl_item.row.add([
                        no,
                        data.do_no,
                        data.ref_no,
                        data.cust_id,
                        data.dn_no,
                        data.po_no,
                        data.do_date,
                        addZeroes(String(data.qty_sj)),
                        '0.00',
                        currency(addZeroes(String(data.sub_ammount)))
                    ]).node();
                    $(add).attr('id', data.do_no);
                    $(add).addClass(data.do_no);
                    no++;
                    subtotal += data.sub_ammount;
                });
                tbl_item.draw(false);
                $('#custinv-create-subtotal').val(currency(addZeroes(String(subtotal))));
                var vat = $('#custinv-create-vat3').val();
                if (vat !== null) {
                    vat = subtotal * vat / 100;
                    var balance = subtotal + vat;
                    $('#custinv-create-vat').val(currency(addZeroes(String(vat))));
                    $('#custinv-create-balance').val(currency(addZeroes(String(balance))));
                    $('#custinv-create-total').val(currency(addZeroes(String(balance))));
                }else{
                    $('#custinv-create-balance').val(currency(addZeroes(String(subtotal))));
                    $('#custinv-create-total').val(currency(addZeroes(String(subtotal))));
                }
                resolve(tbl_item);
            });
        }

        function eachByItemcode(response) {
            return new Promise((resolve, reject) => {
                tbl_item_part.clear().draw(false);
                var no = 1;
                $.each(response, function (i, data) {
                    var price = (data.item_price_new === null ? currency(addZeroes(String(data.item_price))) : currency(addZeroes(String(data.item_price_new))));
                    var add = tbl_item_part.row.add([
                        data.do_no,
                        data.sso_no,
                        data.part_no,
                        data.item_code,
                        data.descript,
                        data.unit,
                        addZeroes(String(data.qty_sj)),
                        price,
                        currency(addZeroes(String(data.item_price_hasil))),
                    ]).node();
                    $(add).attr('data-id', data.do_no);
                    $(add).addClass(data.do_no);
                    no++;
                });
                tbl_item_part.draw(false);
                resolve(tbl_item_part);
            });
        }

        var tbl_account;
        $(document).on('keypress', '#custinv-create-glcode', function (e) {
            if(e.which == 13) {
                modalAction('#custinv-modal-sysacc').then((resolve) => {
                    var params = {"type": "sys_account"}
                    var column = [
                        {data: 'number', name: 'number', className: 'text-center'},
                        {data: 'name', name: 'name', className: 'text-left'},
                        {data: 'ldept', name: 'ldept', className: 'text-center'},
                        {data: 'ldiv', name: 'ldiv', className: 'text-center'},
                    ];
                    tbl_account = $('#custinv-datatables-sysacc').DataTable({
                        processing: true,
                        serverSide: true,
                        destroy: true,
                        ajax: {
                            url: "{{ route('tms.warehouse.cust_invoice.header') }}",
                            method: 'POST',
                            data: params,
                            headers: token_header
                        },
                        columns: column,
                        ordering: false,
                        scrollY: "300px",
                        scrollCollapse: true,
                        fixedHeader: true,
                    });
                });
            }
            return false;
        });

        $('#custinv-datatables-sysacc').off('click', 'tr').on('click', 'tr', function () {
            var data = tbl_account.row(this).data();
            modalAction('#custinv-modal-sysacc', 'hide').then(resolve => {
                $('#custinv-create-glcode').val(data.number);
                $('#custinv-create-glket').val(data.name);
            });
        });

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
        function addZeroes( num ) {
            var value = Number(num);
            var res = num.split(".");
            if(res.length == 1 || (res[1].length < 4)) {
                value = value.toFixed(2);
            }
            return value;
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

        $('#custinv-create-date').on('changeDate', function () {
            $('#custinv-create-terms').val(0);
            $('#custinv-create-duedate').val($(this).val());
        });
        $('#custinv-create-duedate').on('changeDate', function () {
            var days = moment($('#custinv-create-duedate').val(), "DD/MM/YYYY").diff(moment($('#custinv-create-date').val(), "DD/MM/YYYY"), 'days');
            $('#custinv-create-terms').val(days);
        });
    });
</script>
@endsection
@push('js')
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