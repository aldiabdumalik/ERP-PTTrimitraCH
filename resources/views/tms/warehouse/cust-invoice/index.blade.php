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
            let tbl_index = $('#custinv-datatables').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('tms.warehouse.cust_invoice.tbl')}}",
                    method: 'POST',
                    headers: token_header
                },
                columns: [
                    {data:'inv_no', name: 'inv_no', className: "text-center align-middle"},
                    {data:'written_date', name: 'written_date', className: "text-center align-middle"},
                    {data:'posted_date', name: 'posted_date', className: "text-center align-middle"},
                    {data:'voided_date', name: 'voided_date', className: "text-center align-middle"},
                    {data:'ref_no', name: 'ref_no', className: "text-center align-middle"},
                    {data:'tax_no', name: 'tax_no', className: "text-center align-middle"},
                    {data:'cust_id', name: 'cust_id', className: "text-center align-middle"},
                    {data:'action', name: 'action', orderable: false, searchable: false, className: "text-center align-middle"},
                ],
                ordering: false,
            });
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
                            $('#custinv-create-currency-value').val(currency(addZeroes(String(resolve.content.rate)))); 
                        });
                    });
                });
            });
        });

        $(document).on('change', '#custinv-create-currency-type', function () {
            var id = $(this).val();
            ajaxCall({route: "{{route('tms.warehouse.cust_invoice.header')}}", method: "POST", data: {type: 'currency', currency: id}}).then(resolve => {
                $('#custinv-create-currency-value').val(currency(addZeroes(String(resolve.content.rate))));
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

        $('#custinv-modal-index').on('hidden.bs.modal', function () {
            $(tbl_item.table().header())
                .removeClass('bg-abu')
                .addClass('btn-info');
            $(tbl_item_part.table().header())
                .removeClass('bg-abu')
                .addClass('btn-info');
            isHidden('#custinv-btn-add-item', false);
            isHidden('#custinv-btn-delete-item', false);
            isHidden('#custinv-btn-index-submit', false);
            $('input').not('.readonly-first').prop('readonly', false);
            $('select').prop('disabled', false);
            $('#custinv-create-currency-type option').remove();

            $('#custinv-form-index').trigger('reset');
            tbl_item.clear().draw(false);
            tbl_item_part.clear().draw(false);
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
                    {data: 'sub_ammount', name: 'sub_ammount', className: 'text-right'},
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
            var index = $.inArray(String(id), do_selected);

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
                        tbl_item.clear().draw();
                        tbl_item_part.clear().draw();
                        var by_item = resolve.content.itemcode;
                        console.log(resolve.content);
                        eachByDO(resolve.content.do).then(resolve => {
                            eachByItemcode(by_item);
                        });

                        $('#custinv-create-totline').val(by_item.length);
                    });
                });
            }
        });

        function eachByDO(response) {
            return new Promise((resolve, reject) => {
                // tbl_item.clear().draw(false);
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
                        data.do_date.split("-").reverse().join('/'),
                        addZeroes(String(data.tot_qty)),
                        '0.00',
                        currency(addZeroes(String(data.sub_ammount)))
                    ]).node();
                    $(add).addClass(data.do_no);
                    $(add).attr('id', data.do_no);
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
                // tbl_item_part.clear().draw(false);
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

        $('#custinv-datatables-index').off('click', 'tr').on('click', 'tr', function () {
            var data = tbl_item.row(this).data();
            if (data != undefined) {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                    $('#custinv-btn-delete-item').prop('disabled', true);

                    tbl_item_part.$('tr[data-id='+data[1]+']').removeClass('selected');
                }else {
                    tbl_item.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    $('#custinv-btn-delete-item').removeAttr('disabled');

                    tbl_item_part.$('tr.selected').removeClass('selected');
                    tbl_item_part.$('tr[data-id='+data[1]+']').addClass('selected');
                }
            }
        });

        $(document).on('click', '#custinv-btn-delete-item', function () {
            tbl_item.row('.selected').remove().draw();
            for (let i = 0; i < tbl_item.rows().data().toArray().length; i++) {
                var drw = tbl_item.cell( i, 0 ).data(1+i); 
            }
            tbl_item.draw();

            tbl_item_part.rows('.selected').remove().draw();
            $('#custinv-btn-delete-item').prop('disabled', true);

            var vat = $('#custinv-create-vat3').val(),
                arr_item = tbl_item.rows().data().toArray(),
                subtotal = 0;

            if (arr_item.length > 0) {
                for (let i = 0; i < arr_item.length; i++) {
                    subtotal += parseFloat(arr_item[i][9].replace(/,/g, ''));
                }
                $('#custinv-create-subtotal').val(currency(addZeroes(String(subtotal))));
                if(!isNaN(parseFloat(vat))) {
                    vat = subtotal * vat / 100;
                    var balance = subtotal + vat;
                    $('#custinv-create-vat').val(currency(addZeroes(String(vat))));
                    $('#custinv-create-balance').val(currency(addZeroes(String(balance))));
                    $('#custinv-create-total').val(currency(addZeroes(String(balance))));
                }
            }

            $('#custinv-create-totline').val(tbl_item_part.rows().data().toArray().length);
        });

        $(document).on('change', '#custinv-create-vat3', function () {
            var vat = $(this).val(),
                arr_item = tbl_item.rows().data().toArray(),
                subtotal = 0;

            if (arr_item.length > 0) {
                for (let i = 0; i < arr_item.length; i++) {
                    subtotal += parseFloat(arr_item[i][9].replace(/,/g, ''));
                }
                $('#custinv-create-subtotal').val(currency(addZeroes(String(subtotal))));
                if(!isNaN(parseFloat(vat))) {
                    vat = subtotal * vat / 100;
                    var balance = subtotal + vat;
                    $('#custinv-create-vat').val(currency(addZeroes(String(vat))));
                    $('#custinv-create-balance').val(currency(addZeroes(String(balance))));
                    $('#custinv-create-total').val(currency(addZeroes(String(balance))));
                }
            }
        });

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

        $(document).on('submit', '#custinv-form-index', function () {
            var data = {
                inv_no: $('#custinv-create-no').val(),
                inv_type: $('#custinv-create-type').val(),
                inv_branch: $('#custinv-create-branch').val(),
                inv_priod: $('#custinv-create-priod').val(),
                inv_date: $('#custinv-create-date').val().split("/").reverse().join("-"),
                inv_refno: $('#custinv-create-refno').val(),
                inv_vat1: $('#custinv-create-vat1').val(),
                inv_vat2: $('#custinv-create-vat2').val(),
                inv_vat3: $('#custinv-create-vat3').val(),
                inv_sales: $('#custinv-create-sales').val(),
                inv_pic: $('#custinv-create-pic').val(),
                inv_currencytype: $('#custinv-create-currency-type').val(),
                inv_currencyvalue: $('#custinv-create-currency-value').val(),
                inv_term: $('#custinv-create-terms').val(),
                inv_duedate: $('#custinv-create-duedate').val().split("/").reverse().join("-"),
                inv_remark: $('#custinv-create-remark').val(),
                inv_customercode: $('#custinv-create-customercode').val(),
                inv_customerdoaddr: $('#custinv-create-customerdoaddr').val(),
                inv_totline: $('#custinv-create-totline').val(),
                inv_an: $('#custinv-create-an').val(),
                inv_glcode: $('#custinv-create-glcode').val(),
                inv_glket: $('#custinv-create-glket').val(),
                inv_subtotal: $('#custinv-create-subtotal').val(),
                inv_cndisc: $('#custinv-create-cndisc').val(),
                inv_vat: $('#custinv-create-vat').val(),
                inv_total: $('#custinv-create-total').val(),
                inv_payment: $('#custinv-create-payment').val(),
                inv_balance: $('#custinv-create-balance').val(),
                inv_item: tbl_item.rows().data().toArray()
            };

            // cek cust inv
            if (data.inv_item.length > 0) {
                ajaxCall({route: "{{ route('tms.warehouse.cust_invoice.header') }}", method: "POST", data: {type: "cek_invno", inv_no: data.inv_no}}).then(resolve => {
                    var route, method;
                    if (resolve.message == 'isnt_exist') {
                        route = "{{route('tms.warehouse.cust_invoice.save')}}";
                        method = "POST";
                    }else{
                        route = "{{route('tms.warehouse.cust_invoice.update', [':inv_no'])}}";
                        route  = route.replace(':inv_no', data.inv_no);
                        method = "PUT";
                    }
                    ajaxCall({route: route, method: method, data: data}).then(resolve => {
                        var message = resolve.message;
                        Swal.fire({
                            title: 'Success',
                            text: message,
                            icon: 'success'
                        }).then(() => {
                            modalAction('#custinv-modal-index', 'hide').then(() => {
                                index_data.then(resolve => {
                                    resolve.ajax.reload();
                                });
                            });
                        });
                    });
                });
            }
        });

        $(document).on('click', '.custinv-act-view', function () {
            var inv_no = $(this).data('invno');
            modalAction('#custinv-modal-index').then(() => {
                $(tbl_item.table().header())
                    .removeClass('btn-info')
                    .addClass('bg-abu');
                $(tbl_item_part.table().header())
                    .removeClass('btn-info')
                    .addClass('bg-abu');
                isHidden('#custinv-btn-add-item', true);
                isHidden('#custinv-btn-delete-item', true);
                isHidden('#custinv-btn-index-submit', true);
                $('input').prop('readonly', true);
                $('select').prop('disabled', true);

                var route = "{{route('tms.warehouse.cust_invoice.detail', [':inv_no'])}}";
                route  = route.replace(':inv_no', inv_no);
                ajaxCall({route: route, method: 'GET'}).then(resolve => {
                    var data = resolve.content;
                    eachByDO(data.by_do).then(resolve => {
                        eachByItemcode(data.by_item).then(() => {
                            $.each(data.custinv, function (i, inv) {
                                $('#custinv-create-no').val(inv.inv_no);
                                $('#custinv-create-type').val(inv.inv_type);
                                $('#custinv-create-branch').val(inv.branch);
                                $('#custinv-create-priod').val(inv.periode);
                                $('#custinv-create-date').val(convertDateTime(inv.written_date));
                                $('#custinv-create-refno').val(inv.ref_no);
                                $('#custinv-create-vat1').val(inv.pref_tax);
                                $('#custinv-create-vat2').val(inv.tax_no);
                                $('#custinv-create-vat3').val(inv.tax_rate);
                                $('#custinv-create-sales').val(inv.written_by);
                                $('#custinv-create-pic').val(inv.branch);
                                $('#custinv-create-currency-type').append($('<option>', { 
                                    value: inv.valas,
                                    text : inv.valas 
                                }));
                                $('#custinv-create-currency-value').val(currency(addZeroes(String(inv.rate))));
                                $('#custinv-create-terms').val(inv.term);
                                $('#custinv-create-duedate').val(convertDateTime(inv.due_date));
                                $('#custinv-create-remark').val(inv.remark);
                                $('#custinv-create-customercode').val(inv.cust_id);
                                $('#custinv-create-customername').val(inv.cust_name);
                                $('#custinv-create-customeraddr1').val(inv.ad1);
                                $('#custinv-create-customeraddr2').val(inv.ad2);
                                $('#custinv-create-customeraddr3').val(inv.ad3);
                                $('#custinv-create-customeraddr4').val(inv.ad4);
                                $('#custinv-create-customerdoaddr').val(inv.combine_id);
                                $('#custinv-create-totline').val(inv.totline);
                                $('#custinv-create-an').val(inv.cust_contact);
                                $('#custinv-create-glcode').val(inv.glar);
                                $('#custinv-create-glket').val(inv.glname);

                                $('#custinv-create-subtotal').val(currency(addZeroes(String(inv.amount_sub))));
                                $('#custinv-create-cndisc').val(currency(addZeroes(String(inv.amount_cn))));
                                $('#custinv-create-vat').val(currency(addZeroes(String(inv.amount_tax))));
                                $('#custinv-create-total').val(currency(addZeroes(String(inv.amount_bal))));
                                $('#custinv-create-payment').val(currency(addZeroes(String(inv.amount_pay))));
                                $('#custinv-create-balance').val(currency(addZeroes(String(inv.amount_bal))));

                                $('#custinv-create-posted').val((inv.posted_date != null ? convertDateTime(inv.posted_date) : null));
                                $('#custinv-create-voided').val((inv.voided_date != null ? convertDateTime(inv.voided_date) : null));
                                $('#custinv-create-printed').val((inv.printed_date != null ? convertDateTime(inv.printed_date) : null));
                            });
                        });
                    });
                });
            });
        });

        $(document).on('click', '.custinv-act-edit', function () {
            var inv_no = $(this).data('invno');
            ajaxCall({route: "{{ route('tms.warehouse.cust_invoice.header') }}", method: "POST", data: {type: "validation", inv_no: inv_no} }).then(resolve => {
                if (resolve.status == true) {
                    modalAction('#custinv-modal-index').then(() => {
                        ajaxCall({route: "{{route('tms.warehouse.cust_invoice.header')}}", method: "POST", data: {type: 'currency'}}).then(resolve => {
                            var select = resolve.content;
                            $.each(select, function (i, valas) {
                                $('#custinv-create-currency-type').append($('<option>', { 
                                    value: valas.valas,
                                    text : valas.valas 
                                }));
                            });
                        });
                        var route = "{{route('tms.warehouse.cust_invoice.detail', [':inv_no'])}}";
                        route  = route.replace(':inv_no', inv_no);
                        ajaxCall({route: route, method: 'GET'}).then(resolve => {
                            var data = resolve.content;
                            eachByDO(data.by_do).then(resolve => {
                                eachByItemcode(data.by_item).then(() => {
                                    $.each(data.custinv, function (i, inv) {
                                        $('#custinv-create-no').val(inv.inv_no);
                                        $('#custinv-create-type').val(inv.inv_type);
                                        $('#custinv-create-branch').val(inv.branch);
                                        $('#custinv-create-priod').val(inv.periode);
                                        $('#custinv-create-date').val(convertDateTime(inv.written_date));
                                        $('#custinv-create-refno').val(inv.ref_no);
                                        $('#custinv-create-vat1').val(inv.pref_tax);
                                        $('#custinv-create-vat2').val(inv.tax_no);
                                        $('#custinv-create-vat3').val(inv.tax_rate);
                                        $('#custinv-create-sales').val(inv.written_by);
                                        $('#custinv-create-pic').val(inv.branch);
                                        $('#custinv-create-currency-type').val(inv.valas);
                                        $('#custinv-create-currency-value').val(currency(addZeroes(String(inv.rate))));
                                        $('#custinv-create-terms').val(inv.term);
                                        $('#custinv-create-duedate').val(convertDateTime(inv.due_date));
                                        $('#custinv-create-remark').val(inv.remark);
                                        $('#custinv-create-customercode').val(inv.cust_id);
                                        $('#custinv-create-customername').val(inv.cust_name);
                                        $('#custinv-create-customeraddr1').val(inv.ad1);
                                        $('#custinv-create-customeraddr2').val(inv.ad2);
                                        $('#custinv-create-customeraddr3').val(inv.ad3);
                                        $('#custinv-create-customeraddr4').val(inv.ad4);
                                        $('#custinv-create-customerdoaddr').val(inv.combine_id);
                                        $('#custinv-create-totline').val(inv.totline);
                                        $('#custinv-create-an').val(inv.cust_contact);
                                        $('#custinv-create-glcode').val(inv.glar);
                                        $('#custinv-create-glket').val(inv.glname);

                                        $('#custinv-create-subtotal').val(currency(addZeroes(String(inv.amount_sub))));
                                        $('#custinv-create-cndisc').val(currency(addZeroes(String(inv.amount_cn))));
                                        $('#custinv-create-vat').val(currency(addZeroes(String(inv.amount_tax))));
                                        $('#custinv-create-total').val(currency(addZeroes(String(inv.amount_bal))));
                                        $('#custinv-create-payment').val(currency(addZeroes(String(inv.amount_pay))));
                                        $('#custinv-create-balance').val(currency(addZeroes(String(inv.amount_bal))));

                                        $('#custinv-create-posted').val((inv.posted_date != null ? convertDateTime(inv.posted_date) : null));
                                        $('#custinv-create-voided').val((inv.voided_date != null ? convertDateTime(inv.voided_date) : null));
                                        $('#custinv-create-printed').val((inv.printed_date != null ? convertDateTime(inv.printed_date) : null));
                                    });
                                });
                            });
                        });
                    });
                }
            });
        });

        $(document).on('click', '.custinv-act-posted', function () {
            var inv_no = $(this).data('invno');
            Swal.fire({
                title: 'Are you sure?',
                text: "Post Invoice No." + inv_no + " Now?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, post it!'
            }).then(answer => {
                if (answer.value == true) {
                    ajaxCall({route: "{{ route('tms.warehouse.cust_invoice.posted') }}", method: "POST", data: {inv_no: inv_no}}).then(resolve => {
                        var message = resolve.message;
                        Swal.fire({
                            title: 'Success',
                            text: message,
                            icon: 'success'
                        }).then(() => {
                            index_data.then(resolve => {
                                resolve.ajax.reload();
                            });
                        });
                    });
                }
            });
        });
        $(document).on('click', '.custinv-act-unposted', function () {
            var inv_no = $(this).data('invno');
            Swal.fire({
                // title: 'Are you sure?',
                title: "Unpost Inv No." + inv_no + " Now?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, unpost it!',
                input: 'text',
                inputPlaceholder: 'Type your Note here...',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to write Note!'
                    }
                }
            }).then(answer => {
                if (answer.value != "" && answer.value != undefined) {
                    var note = answer.value;
                    ajaxCall({route: "{{ route('tms.warehouse.cust_invoice.unposted') }}", method: "POST", data: {inv_no: inv_no, note: note}}).then(resolve => {
                        var message = resolve.message;
                        Swal.fire({
                            title: 'Success',
                            text: message,
                            icon: 'success'
                        }).then(() => {
                            index_data.then(resolve => {
                                resolve.ajax.reload();
                            });
                        });
                    });
                }
            });
        });

        $(document).on('click', '.custinv-act-voided', function () {
            var inv_no = $(this).data('invno');
            Swal.fire({
                // title: 'Are you sure?',
                title: "Void Inv No." + inv_no + " Now?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, void it!',
                input: 'text',
                inputPlaceholder: 'Type your Note here...',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to write Note!'
                    }
                }
            }).then(answer => {
                if (answer.value != "" && answer.value != undefined) {
                    var note = answer.value;
                    ajaxCall({route: "{{ route('tms.warehouse.cust_invoice.voided') }}", method: "POST", data: {inv_no: inv_no, note: note}}).then(resolve => {
                        var message = resolve.message;
                        Swal.fire({
                            title: 'Success',
                            text: message,
                            icon: 'success'
                        }).then(() => {
                            index_data.then(resolve => {
                                resolve.ajax.reload();
                            });
                        });
                    });
                }
            });
        });
        $(document).on('click', '.custinv-act-unvoided', function () {
            var inv_no = $(this).data('invno');
            Swal.fire({
                // title: 'Are you sure?',
                title: "Unvoid Inv No." + inv_no + " Now?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, unvoid it!',
                input: 'text',
                inputPlaceholder: 'Type your Note here...',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to write Note!'
                    }
                }
            }).then(answer => {
                if (answer.value != "" && answer.value != undefined) {
                    var note = answer.value;
                    ajaxCall({route: "{{ route('tms.warehouse.cust_invoice.unvoided') }}", method: "POST", data: {inv_no: inv_no, note: note}}).then(resolve => {
                        var message = resolve.message;
                        Swal.fire({
                            title: 'Success',
                            text: message,
                            icon: 'success'
                        }).then(() => {
                            index_data.then(resolve => {
                                resolve.ajax.reload();
                            });
                        });
                    });
                }
            });
        });

        var tbl_log;
        $(document).on('click', '.custinv-act-log', function () {
            var inv_no = $(this).data('invno');
            modalAction('#custinv-modal-log').then(resolve => {
                tbl_log = $('#custinv-datatables-log').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    searching: false,
                    ajax: {
                        url: "{{route('tms.warehouse.cust_invoice.header')}}",
                        method: "POST",
                        data: {
                            type: "log",
                            inv_no: inv_no
                        },
                        headers: token_header
                    },
                    columns: [
                        {data:'date', name: 'date', className: "text-left align-middle"},
                        {data:'time', name: 'time', className: "text-left align-middle"},
                        {data:'status', name: 'status', className: "text-left align-middle"},
                        {data:'written_by', name: 'written_by', className: "text-left align-middle"},
                        {data:'note', name: 'note', className: "text-left align-middle"},
                    ],
                    ordering: false,
                    lengthChange: false
                });
            });
        });

        $(document).on('click', '.custinv-act-report', function () {
            var inv_no = $(this).data('invno');
            modalAction('#custinv-modal-report').then(() => {
                $('#custinv-btn-report-ok').on('click', function () {
                    var type = $('#custinv-report-type').val(),
                        pic = $('#custinv-report-pic').val(),
                        vat = $('#custinv-report-vat').val(),
                        noitem = $('#custinv-report-noitem').val(),
                        cut = $('#custinv-report-cut').val(),
                        encrypt = btoa(`${inv_no}&${type}&${pic}&${vat}&${noitem}&${cut}`);
                    index_data.then(resolve => {
                        resolve.ajax.reload();
                    });
                    modalAction('#custinv-modal-report', 'hide');
                    var url = "{{route('tms.warehouse.cust_invoice.report')}}?params=" + encrypt;
                    window.open(url, '_blank');

                });
            });
        });

        $(document).on('change', '#custinv-report-type', function () {
            var val = $(this).val();
            if (val == 'INV') {
                $('#custinv-report-cut').val(22);
            }else if (val == 'OR') {
                $('#custinv-report-cut').val(0);
            }else if (val == 'VAT') {
                $('#custinv-report-cut').val(18);
            }else if (val == 'CN') {
                $('#custinv-report-cut').val(0);
            }else{
                $('#custinv-report-cut').val(0);
            }
        });

        $(document).on('click', '.custinv-act-note', function () {
            var inv_no = $(this).data('invno');
            modalAction('#custinv-modal-note').then(() => {
                ajaxCall({route: "{{route('tms.warehouse.cust_invoice.header')}}", method: "POST", data: {type: "note", inv_no: inv_no}}).then(resolve => {
                    var data = resolve.content;
                    $('#custinv-note-invno').val(inv_no);
                    $('#custinv-note-po').val(data.note_po);
                    $('#custinv-note-sj').val(data.note_sj);
                });
            });
        });

        $(document).on('click', '#custinv-btn-note-ok', function () {
            var data = {
                inv_no: $('#custinv-note-invno').val(),
                note_po: $('#custinv-note-po').val(),
                note_sj: $('#custinv-note-sj').val()
            };
            var route = "{{route('tms.warehouse.cust_invoice.update.note', [':inv_no'])}}";
            route  = route.replace(':inv_no', data.inv_no);
            ajaxCall({route: route, method: "PUT", data: data}).then(resolve => {
                var message = resolve.message;
                modalAction('#custinv-modal-note', 'hide').then(() => {
                    Swal.fire({
                        title: 'Success',
                        text: message,
                        icon: 'success'
                    }).then(() => {
                        index_data.then(resolve => {
                            resolve.ajax.reload();
                        });
                    });
                });
            });;
        });

        $('#custinv-modal-note').on('hidden.bs.modal', function () {
            $('#custinv-note-po').val();
            $('#custinv-note-sj').val();
        });

        

        // Function

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

        function isInt(n){
            return Number(n) === n && n % 1 === 0;
        }

        function isFloat(n){
            return Number(n) === n && n % 1 !== 0;
        }

        function isHidden(element=null, hide=true){
            return ((hide == true) ? $(element).addClass('d-none') : $(element).removeClass('d-none'));
        }

        function convertDateTime(date) {
            date = date.split(' ');
            date = date[0].split('-').reverse().join('/');
            return date;
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