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
@section('script')
<script>
    $(document).ready(function () {
        var item_select = [];
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

        // const index_data = new Promise(function(resolve, reject) {
            
        //     resolve(tbl_index);
        // });

        function tbl_custprice_index() {
            var groupColumn = 0;
            let tbl_index = $('#custprice-datatables').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('tms.master.cust_price.index')}}",
                    method: 'POST',
                    headers: token_header
                },
                columns: [
                    {data:'group', name: 'group', className: "align-middle"},
                    {data:'part_no', name: 'part_no', className: "align-middle"},
                    {data:'item_code', name: 'item_code', className: "align-middle"},
                    {data:'desc', name: 'desc', className: "align-middle"},
                    {data:'price_new', name: 'price_new', className: "align-middle"},
                    {data:'price_old', name: 'price_old', className: "align-middle"},
                    // {data:'action', name: 'action', orderable: false, searchable: false, className: "text-center align-middle"},
                ],
                ordering: false,
                columnDefs: [
                    { "visible": false, "targets": groupColumn },
                    {
                        targets: [4, 5],
                        createdCell:  function (td, cellData, rowData, row, col) {
                            $(td).addClass('text-right');
                        }
                    }
                ],
                displayLength: 50,
                drawCallback: function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
                    var x = 1;
                    api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                        // var cellNode = api.cell(i, 1).node();
                        // var cust = group.split(' ')[0];
                        // var date = group.split(' ')[2];
                        var arr_group = group.split('|');
                        if (last !== group) {
                            x = 1;
                        }
                        if ( last !== group ) {
                            $(rows).eq( i ).before(`
                                <tr class="group bg-y" data-id="${arr_group[0]} - ${arr_group[1]}">
                                    <td colspan="2" class="text-bold align-middle">${arr_group[0]} - ${arr_group[1]} | ${arr_group[2]}</td>
                                    <td colspan="4" class="text-bold align-middle text-right">(double click to view) | ${arr_group[3]}</td>
                                </tr>
                            `);

                            last = group;
                        }
                        // $(cellNode).html(`${x++} ${$(cellNode).text()}`);
                    });
                }
            });
        }
        tbl_custprice_index();

        function tbl_index_bycust(cust) {
            var groupColumn = 0;
            let tbl_index_cust = $('#custprice-datatables').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('tms.master.cust_price.index')}}",
                    method: 'POST',
                    data: {customer: cust},
                    headers: token_header
                },
                columns: [
                    {data:'group', name: 'group', className: "align-middle"},
                    {data:'part_no', name: 'part_no', className: "align-middle"},
                    {data:'item_code', name: 'item_code', className: "align-middle"},
                    {data:'desc', name: 'desc', className: "align-middle"},
                    {data:'price_new', name: 'price_new', className: "align-middle"},
                    {data:'price_old', name: 'price_old', className: "align-middle"},
                    // {data:'action', name: 'action', orderable: false, searchable: false, className: "text-center align-middle"},
                ],
                ordering: false,
                columnDefs: [
                    { "visible": false, "targets": groupColumn },
                    {
                        targets: [4, 5],
                        createdCell:  function (td, cellData, rowData, row, col) {
                            $(td).addClass('text-right');
                        }
                    }
                ],
                displayLength: 50,
                drawCallback: function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
                    var x = 1;
                    api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                        // var cellNode = api.cell(i, 1).node();
                        // var cust = group.split(' ')[0];
                        // var date = group.split(' ')[2];
                        var arr_group = group.split('|');
                        if (last !== group) {
                            x = 1;
                        }
                        if ( last !== group ) {
                            $(rows).eq( i ).before(`
                                <tr class="group bg-y" data-id="${arr_group[0]} - ${arr_group[1]}">
                                    <td colspan="2" class="text-bold align-middle">${arr_group[0]} - ${arr_group[1]} | ${arr_group[2]}</td>
                                    <td colspan="4" class="text-bold align-middle text-right">(double click to view) | ${arr_group[3]}</td>
                                </tr>
                            `);

                            last = group;
                        }
                        // $(cellNode).html(`${x++} ${$(cellNode).text()}`);
                    });
                }
            });
        }
        

        $('#custprice-datatables tbody').on( 'dblclick', 'tr.group', function () {
            var group = $(this).data('id');
                group = group.split(' ');
            var cust = group[0];
            var date = group[2].split('/').reverse().join('-');
            var route = "{{route('tms.master.cust_price.detail', [':cust', ':date'])}}";
            route  = route.replace(':cust', cust);
            route  = route.replace(':date', date);
            modalAction('#custprice-modal-index').then(resolve => {
                $(tbl_item.table().header())
                    .removeClass('btn-info')
                    .addClass('bg-abu');
                isHidden('#custprice-btn-table-item', true);
                isHidden('#custprice-btn-index-submit', true);
                isHidden('#custprice-btn-action', false);
                $('input').not('.custprice-dtsearch').prop('readonly', true);
                loading_start();
                ajaxCall({route: route, method: "GET"}).then(resolve => {
                    if (resolve.status == true) {
                        var no = 1;
                        var cust_id, cust_name, valas, active_date, created, user, posted, voided, printed, price_by;
                        $.each(resolve.content, function (i, data) {
                            tbl_item.row.add([
                                no,
                                data.item_code,
                                data.PART_NO,
                                data.DESCRIPT,
                                (data.price_new == null ? "0.00" : currency(addZeroes(String(data.price_new)))),
                                currency(addZeroes(String(data.price_old))),
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
                            price_by = data.price_by;
                        });
                        tbl_item.draw();
                        $('#custprice-create-customercode').val(cust_id);
                        $('#custprice-create-customername').val(cust_name);
                        $('#custprice-create-posted').val(date_convert(posted));
                        $('#custprice-create-voided').val(date_convert(voided));
                        $('#custprice-create-printed').val(date_convert(printed));
                        $('#custprice-create-user').val(user);
                        $('#custprice-create-valas').val(valas);
                        $('#custprice-create-priceby').val(price_by);
                        $('#custprice-create-activedate').val(date_convert(active_date));
                        $('#custprice-create-entrydate').val(date_convert(created));
                        
                        $('#custprice-btn-index-edit').attr('data-custid', cust_id).attr('data-activedate', active_date.split('/').reverse().join('-'));
                        $('#custprice-btn-index-log').attr('data-custid', cust_id).attr('data-activedate', active_date.split('/').reverse().join('-'));
                        $('#custprice-btn-index-print').attr('data-custid', cust_id).attr('data-activedate', active_date.split('/').reverse().join('-'));
                        // console.log(posted);
                        
                        $('#custprice-create-valas').prop('disabled', true);
                        $('#custprice-create-priceby').prop('disabled', true);
                    }
                });
            });
        });

        var tbl_customer_s = $('#custprice-datatables-customer-search').DataTable({
            destroy: true,
            lengthChange: false,
            ordering: false,
            fixedHeader: true,
        });
        $('#custprice-btn-modal-search').on('click', function () {
            modalAction('#custprice-modal-customer-search').then(resolve => {
                ajaxCall({route: "{{route('tms.master.cust_price.header')}}", method: "POST", data: {type: "customer"}}).then(resolve => {
                    let customer = resolve.content;
                    $.each(customer, function (i, cust) {
                        tbl_customer_s.row.add([
                            cust.code,
                            cust.name
                        ]);
                    });
                    tbl_customer_s.draw();
                });

                resolve.on('shown.bs.modal', function () {
                    $('#custprice-datatables-customer-search_filter input').focus();
                });
            });
        })

        $('#custprice-datatables-customer-search').off('dblclick', 'tr').on('dblclick', 'tr', function () {
            var data = tbl_customer_s.row(this).data();
            var cust_id = data[0];
            modalAction('#custprice-modal-customer-search', 'hide').then(() => {
                tbl_index_bycust(cust_id);
            });
        })

        $('#custprice-modal-customer-search').on('hidden.bs.modal', function () {
            tbl_customer_s.clear();
        });

        $('#custprice-btn-modal-create').on('click', function () {
            modalAction('#custprice-modal-index').then(() => {
                // ajaxCall({route: "{{route('tms.master.cust_price.header')}}", method: "POST", data: {type: 'currency'}}).then(resolve => {
                //     var data = resolve.content;
                //     $('#custprice-create-valas').html('');
                //     $.each(data, function (i, valas) {
                //         $('#custprice-create-valas').append($('<option>', { 
                //             value: valas.valas,
                //             text : valas.valas 
                //         }));
                //     });
                //     $('#custprice-create-valas').val('IDR');
                // });
            });
        });

        var tbl_item = $('#custprice-datatables-index').DataTable({
            destroy: true,
            lengthChange: false,
            // searching: false,
            sDom: 'lrtip',
            paging: false,
            ordering: false,
            scrollY: "200px",
            scrollCollapse: true,
            fixedHeader: true,
            columnDefs: [{
                targets: [0,4,5],
                createdCell:  function (td, cellData, rowData, row, col) {
                    $(td).addClass('text-right');
                }
            }]
        });

        $(document).on('input', '#custprice-dtsearch', function () {
            tbl_item.search($(this).val()).draw();
        });
        
        $('#custprice-modal-index').on('shown.bs.modal', function () {
            adjustDraw(tbl_item);
            $('#custprice-create-customercode').focus();
            ajaxCall({route: "{{route('tms.master.cust_price.header')}}", method: "POST", data: {type: 'currency'}}).then(resolve => {
                var data = resolve.content;
                $('#custprice-create-valas').html('');
                $.each(data, function (i, valas) {
                    $('#custprice-create-valas').append($('<option>', { 
                        value: valas.valas,
                        text : valas.valas 
                    }));
                });
                $('#custprice-create-valas').val('IDR');
            });
        });

        // $('#custprice-create-priceby').on('change', function () {
        //     console.log($(this).val());
        //     $('#custprice-create-activedate').datepicker('remove');
        //     $('#custprice-create-activedate').val(null);
        //     if ($(this).val() === 'SO') {
        //         $('#custprice-create-activedate').datepicker({
        //             format: "mm/yyyy",
        //             viewMode: "months", 
        //             minViewMode: "months",
        //             autoclose: true,
        //         });
        //     }else{
        //         $('#custprice-create-activedate').datepicker({
        //             format: "dd/mm/yyyy",
        //             autoclose: true,
        //             enableOnReadonly: false,
        //         });
        //     }
        // });

        $('#custprice-datatables-index tbody').off('dblclick', 'tr').on('dblclick', 'tr', function () {
            var data = tbl_item.row(this).data();
            if (data != undefined) {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                    $('#custprice-btn-edit-item').prop('disabled', true);
                    $('#custprice-btn-delete-item').prop('disabled', true);
                }else {
                    tbl_item.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    $('#custprice-btn-edit-item').removeAttr('disabled');
                    $('#custprice-btn-delete-item').removeAttr('disabled');
                }
            }
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
                    tbl_item.clear().draw(false);
                    tbl_customer.clear().draw(false);
                    ajaxCall({route: "{{route('tms.master.cust_price.header')}}", method: "POST", data: {type: "customer"}}).then(resolve => {
                        let customer = resolve.content;
                        $.each(customer, function (i, cust) {
                            tbl_customer.row.add([
                                cust.code,
                                cust.name
                            ]);
                        });
                        tbl_customer.draw();
                        $('#custprice-datatables-customer-item').DataTable().destroy();
                        item_select = [];

                    });
                });
            }
            return false;
        });

        $('#custprice-modal-item').on('shown.bs.modal', function () {
            $('#custprice-datatables-customer-item_filter input').focus();
        });

        $('#custprice-modal-customer').on('shown.bs.modal', function () {
            adjustDraw(tbl_customer);
            $('#custprice-datatables-customer_filter input').focus();
        });

        $('#custprice-modal-index').on('hidden.bs.modal', function () {
            resetForm();
        });

        $('#custprice-datatables-customer').off('dblclick', 'tr').on('dblclick', 'tr', function () {
            var data = tbl_customer.row(this).data();
            var cust_id = data[0];
            modalAction('#custprice-modal-customer', 'hide').then(resolve => {
                $('#custprice-create-customercode').val(data[0]);
                $('#custprice-create-customername').val(data[1]);
                ajaxCall({route: "{{route('tms.master.cust_price.header')}}", method: "POST", data: {type: "customerclick", cust_id: data[0]}}).then(resolve => {
                    if (resolve.content) {
                        var no = 1;
                        var cust_id, cust_name, valas, active_date, created, user, posted, voided, printed, price_by;
                        $.each(resolve.content, function (i, data) {
                            var price_new = (data.price_new == null ? "0.00" : currency(addZeroes(String(data.price_new))));
                            tbl_item.row.add([
                                no,
                                data.itemcode_trims,
                                data.part_no,
                                data.desc,
                                `<input type="text" class="form-control form-control-sm text-right item-price-text" value="${price_new}">`,
                                // currency(addZeroes(String(data.price_old))),
                                price_new
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
                            price_by = data.price_by;
                        });
                        tbl_item.draw();
                    }
                });
                // if (data[0] === "A01") {
                //     $('#custprice-create-priceby').val('DATE');
                // }else{
                //     $('#custprice-create-priceby').val('SO');
                // }
                // modalAction('#custprice-modal-item').then(function () {
                //     getTblItem(cust_id);
                // });
            });
        });

        var tbl_item_log;
        $(document).on('click', '.custprice-itemcode-popup', function (e) {
            let id = $(this).data('item');
            modalAction('#custprice-modal-ilog').then(() => {
                tbl_item_log = $('#custprice-datatables-ilog').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{route('tms.master.cust_price.header')}}",
                        method: "POST",
                        data: {
                            type: "item_log",
                            id: id
                        },
                        headers: token_header
                    },
                    columns: [
                        {data:'item_code', name: 'item_code', className: "text-left align-middle"},
                        {data:'price_new', name: 'price_new', className: "text-left align-middle"},
                        {data:'price_old', name: 'price_old', className: "text-left align-middle"},
                        {data:'active_date', name: 'active_date', className: "text-left align-middle"},
                        {data:'range_date', name: 'range_date', className: "text-center align-middle"},
                    ],
                    ordering: false,
                    lengthChange: false,
                    searching: false,
                });
            });
        });

        $(document).on('change click keyup input paste', '.item-price-text', function () {
            $(this).val(function (index, value) {
                return value.replace(/(?!\.)\D/g, "")
                    .replace(/(?<=\..*)\./g, "")
                    // .replace(/(?<=\.\d\d).*/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    .replace(/(?<=\..*)\,/g, "");
            });
        });

        var tbl_item_add;
        function getTblItem(cust_id) {
            tbl_item_add = $('#custprice-datatables-customer-item').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('tms.master.cust_price.header')}}",
                    method: "POST",
                    data: {
                        type: "items",
                        cust_id: cust_id
                    },
                    headers: token_header
                },
                columns: [
                    {data:'itemcode', name: 'itemcode', className: "text-left align-middle"},
                    {data:'part_no', name: 'part_no', className: "text-left align-middle"},
                    {data:'model', name: 'model', className: "text-left align-middle"},
                    {data:'descript', name: 'descript', className: "text-left align-middle"},
                    {data:'unit', name: 'unit', className: "text-center align-middle"},
                ],
                ordering: false,
                lengthChange: false,
                createdRow: function( row, data, dataIndex ) {
                    $(row).attr('data-id', data.itemcode);
                    $(row).attr('id', data.itemcode);
                },
                // rowCallback: function( row, data ) {
                //     if ( $.inArray(data.itemcode, item_select) !== -1 ) {
                //         $(row).addClass('selected');
                //     }
                // }
            });
        }

        $('#custprice-btn-add-item').on('click', function () {
            var cust_id = $('#custprice-create-customercode').val();
            var cust_name = $('#custprice-create-customername').val();
            if (cust_name === "") {
                Swal.fire({
                    title: 'Warning!',
                    text: "Please add customer first!",
                    icon: 'warning'
                });
            }else{
                $('#custprice-datatables-customer-item').DataTable().clear();
                modalAction('#custprice-modal-item').then(() => {
                    getTblItem(cust_id);
                    // ajaxCall({route: "{{route('tms.master.cust_price.header')}}", method: "POST", data: {type: "items", cust_id: cust_id} }).then(resolve => {
                    //     console.log(resolve);
                    // });
                });
            }
        });

        $('#custprice-datatables-customer-item').off('dblclick', 'tr').on('dblclick', 'tr', function () {
            var item = tbl_item_add.row(this).data();
            var index = tbl_item.data().length;
            var cek = tbl_item.rows().data().toArray();
            var isExist = false;
            if (cek.length > 0) {
                for (let i = 0; i < cek.length; i++) {
                    if (item.itemcode == cek[i][1]) {
                        isExist = true;
                        break;
                    }
                }
            }
            if (isExist == true) {
                Swal.fire({
                    title: 'Warning!',
                    text: "This item is already in the table!",
                    icon: 'warning'
                });
            }else{
                modalAction('#custprice-modal-item', 'hide').then(() => {
                    var add = tbl_item.row.add([
                        index+1,
                        item.itemcode,
                        item.part_no,
                        item.descript,
                        `<input type="text" class="form-control form-control-sm text-right item-price-text" value="0.00">`,
                        currency(addZeroes(String(item.price))),
                    ]).node();
                    $(add).attr('id', item.itemcode);
                    $(add).addClass(item.itemcode);
                    tbl_item.draw(false);
                });
            }
        });

        // $('#custprice-datatables-customer-item').off('click', 'tr').on('click', 'tr', function () {
        //     var id = this.id;
        //     var index = $.inArray(id, item_select);

        //     if ( index === -1 ) {
        //         item_select.push( id );
        //     } else {
        //         item_select.splice( index, 1 );
        //     }

        //     $(this).toggleClass('selected');
        // });

        $(document).on('click', '#custprice-btn-item-submit', function () {
            ajaxCall({route: "{{route('tms.master.cust_price.header')}}", method: "POST", data: {type: "items_selected", items: item_select}}).then(resolve => {
                var data = resolve.content;
                modalAction('#custprice-modal-item', 'hide').then(resolve => {

                    tbl_item.clear().draw(false);
                    var no = 1;
                    
                    $.each(data, function (i, item) {
                        var old_price = (item.old_price == null) ? '0.00' : currency(addZeroes(String(item.old_price.price_new)));
                        var add = tbl_item.row.add([
                            no,
                            item.items.itemcode,
                            item.items.part_no,
                            item.items.descript,
                            `<input type="number" class="form-control form-control-sm text-right" value="0.00">`,
                            old_price,
                        ]).node();
                        $(add).attr('id', item.items.itemcode);
                        $(add).addClass(item.items.itemcode);
                        tbl_item.draw(false);
                        
                        
                        no++;
                    });

                });
            });
        });

        $(document).on('click', '#custprice-btn-delete-item', function () {
            var tbl = tbl_item.row('.selected').data();
            
            var id = tbl[1];
            var index = $.inArray(id, item_select);

            item_select.splice( index, 1 );

            tbl_item.row('.selected').remove().draw( false );
            for (let i = 0; i < tbl_item.rows().data().toArray().length; i++) {
                var drw = tbl_item.cell( i, 0 ).data(1+i);
            }
            tbl_item.draw(false);
            

            $('#custprice-btn-edit-item').prop('disabled', true);
            $('#custprice-btn-delete-item').prop('disabled', true);
        });

        $(document).on('submit', '#custprice-form-itemadd', function () {
            var index = tbl_item.data().length;
            if ($('#custprice-additem-index').val() == 0) {
                var add = tbl_item.row.add([
                    index+1,
                    $('#custprice-additem-itemcode').val(),
                    $('#custprice-additem-partno').val(),
                    $('#custprice-additem-description').val(),
                    currency(addZeroes(String($('#custprice-additem-newprice').val()))),
                    "0.00",
                ]).node();
                $(add).attr('data-id', index+1);
                tbl_item.draw(false);
            }else{
                var idx = parseInt($('#custprice-additem-index').val()) - 1;
                tbl_item.row( idx ).data([
                    $('#custprice-additem-index').val(),
                    $('#custprice-additem-itemcode').val(),
                    $('#custprice-additem-partno').val(),
                    $('#custprice-additem-description').val(),
                    currency(addZeroes(String($('#custprice-additem-newprice').val()))),
                    "0.00",
                ]).draw(false);
            }
            modalAction('#custprice-modal-itemadd', 'hide');
        });

        $(document).on('hidden.bs.modal', '#custprice-modal-itemadd', function () {
            $(this).find('#custprice-form-itemadd').trigger('reset');
            $('#custprice-additem-index').val(0);
        });

        $(document).on('click', '.custprice-act-view', function () {
            var cust = $('#custprice-create-customercode').val(); // $(this).data('custid');
            var date = $('#custprice-create-activedate').val().split('/').reverse().join('-'); // $(this).data('activedate');
            var route = "{{route('tms.master.cust_price.detail', [':cust', ':date'])}}";
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
                        var cust_id, cust_name, valas, active_date, created, user, posted, voided, printed, price_by;
                        $.each(resolve.content, function (i, data) {
                            tbl_item.row.add([
                                no,
                                data.item_code,
                                data.PART_NO,
                                data.DESCRIPT,
                                (data.price_new == null ? "0.00" : currency(addZeroes(String(data.price_new)))),
                                currency(addZeroes(String(data.price_old))),
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
                            price_by = data.price_by;
                        });
                        tbl_item.draw();
                        $('#custprice-create-customercode').val(cust_id);
                        $('#custprice-create-customername').val(cust_name);
                        $('#custprice-create-posted').val(date_convert(posted));
                        $('#custprice-create-voided').val(date_convert(voided));
                        $('#custprice-create-printed').val(date_convert(printed));
                        $('#custprice-create-user').val(user);
                        // $('#custprice-create-valas').val(valas);
                        $('#custprice-create-valas').html('');
                        $('#custprice-create-valas').append(`
                            <option val="${valas}">${valas}</option>
                        `);
                        $('#custprice-create-priceby').val(price_by);
                        $('#custprice-create-activedate').val(date_convert(active_date));
                        $('#custprice-create-entrydate').val(date_convert(created));

                    }
                });
            });
        });

        $(document).on('click', '.custprice-act-edit', function () {
            var cust = $('#custprice-create-customercode').val(); // $(this).data('custid');
            var date = $('#custprice-create-activedate').val().split('/').reverse().join('-'); // $(this).data('activedate');

            var route = "{{route('tms.master.cust_price.detail', [':cust', ':date'])}}";
            route  = route.replace(':cust', cust);
            route  = route.replace(':date', date);

            modalAction('#custprice-modal-index', 'hide').then(() => {
                loading_start();
                setTimeout(() => {
                    ajaxCall({route: "{{route('tms.master.cust_price.header')}}", method: "POST", data: {type: "validation", cust_id: cust, active: date} }).then(resolve => {
                        var status = resolve.status;
                        if (status == true) {
                            modalAction('#custprice-modal-index').then(resolve => {
                                ajaxCall({route: route, method: "GET"}).then(resolve => {
                                    if (resolve.status == true) {
                                        var no = 1;
                                        var cust_id, cust_name, valas, active_date, created, user, posted, voided, printed, price_by;
                                        $.each(resolve.content, function (i, data) {
                                            var price_new = (data.price_new == null ? "0.00" : addZeroes(String(data.price_new)));
                                            tbl_item.row.add([
                                                no,
                                                data.item_code,
                                                data.PART_NO,
                                                data.DESCRIPT,
                                                `<input type="text" class="form-control form-control-sm text-right" value="${price_new}">`,
                                                // (data.price_new == null ? "0.00" : currency(addZeroes(String(data.price_new)))),
                                                currency(addZeroes(String(data.price_old))),
                                                // price_new
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
                                            price_by = data.price_by;
                                        });
                                        tbl_item.draw();
                                        $('#custprice-create-customercode').val(cust_id);
                                        $('#custprice-create-customername').val(cust_name);
                                        $('#custprice-create-posted').val(date_convert(posted));
                                        $('#custprice-create-voided').val(date_convert(voided));
                                        $('#custprice-create-printed').val(date_convert(printed));
                                        $('#custprice-create-user').val(user);
                                        // $('#custprice-create-valas').html('');
                                        // $('#custprice-create-valas').append(`
                                        //     <option val="${valas}">${valas}</option>
                                        // `);
                                        $('#custprice-create-valas').val(valas);
                                        $('#custprice-create-priceby').val(price_by);
                                        $('#custprice-create-activedate').val(date_convert(active_date));
                                        $('#custprice-create-entrydate').val(date_convert(created));
                                        // $('#custprice-create-activedate').prop('readonly', true);
                                        $('#custprice-create-customercode').prop('readonly', true);
                                    }
                                });
                            });
                        }
                    });
                }, 300)
            });

        });

        $(document).on('submit', '#custprice-form-index', function () {
            loading_start();
            var items = tbl_item.rows().data().toArray();
            var items_fix = [];
            for (let i = 0; i < items.length; i++) {
                var obj_tbl_index = {}
                var new_price = tbl_item.rows().cell(i, 4).nodes().to$().find('input').val();
                
                if (new_price !== "0.00") {
                    obj_tbl_index.itemcode = items[i][1];
                    obj_tbl_index.part_no = items[i][2];
                    obj_tbl_index.descript = items[i][3];
                    obj_tbl_index.new_price = new_price;
                    obj_tbl_index.old_price = items[i][5];

                    items_fix.push(obj_tbl_index);
                }
            }
            var data = {
                cust_id: $('#custprice-create-customercode').val(),
                valas: $('#custprice-create-valas').val(),
                active_date: $('#custprice-create-activedate').val().split("/").reverse().join("-"),
                price_by: $('#custprice-create-priceby').val(),
                items: JSON.stringify(items_fix)
            };
            // Cek
            var route = "{{route('tms.master.cust_price.detail', [':cust', ':date'])}}";
            route  = route.replace(':cust', $('#custprice-create-customercode').val());
            route  = route.replace(':date', $('#custprice-create-activedate').val().split("/").reverse().join("-"));
            ajaxCall({route: route, method: "GET"}).then(resolve => {
                var rou;
                if (resolve.content.length <= 0) {
                    rou = "{{route('tms.master.cust_price.save')}}";
                }else{
                    rou = "{{route('tms.master.cust_price.update', [':cust', ':active'])}}";
                    rou  = rou.replace(':cust', $('#custprice-create-customercode').val());
                    rou  = rou.replace(':active', $('#custprice-create-activedate').val().split("/").reverse().join("-"));
                }
                // submit(rou, data);

                var cust = $('#custprice-create-customercode').val(); // $(this).data('custid');
                var date = $('#custprice-create-activedate').val().split('/').reverse().join('-'); // $(this).data('activedate');
                var priceby = $('#custprice-create-priceby').val(); // $(this).data('priceby');
                // modalAction('#custprice-modal-index', 'hide').then(() => {
                    modalAction('#custprice-modal-post').then(() => {
                        $('#custprice-post-id').val(cust);
                        $('#custprice-post-activedate').val(date);
                        $('#custprice-post-priceby').val(priceby);
                        if ($('#custprice-post-priceby').val() == 'SO') {
                            $('#custprice-post-stock').prop('checked', true);
                            $('#custprice-post-so').prop('checked', true);
                            $('#custprice-post-sso').prop('checked', false);
                            $('#custprice-post-sj').prop('checked', false);

                            // $('#custprice-post-sso').prop('disabled', true);
                            // $('#custprice-post-sj').prop('disabled', true);
                        }else{
                            // $('#custprice-post-sso').prop('disabled', false);
                            // $('#custprice-post-sj').prop('disabled', false);

                            $('#custprice-post-stock').prop('checked', true);
                            $('#custprice-post-sso').prop('checked', true);
                            $('#custprice-post-so').prop('checked', true);
                            $('#custprice-post-sj').prop('checked', true);
                        }

                        $('#custprice-btn-post-submit').addClass('d-none');
                        $('#custprice-btn-post-submit-save').removeClass('d-none');

                        $('#custprice-btn-post-submit-save').off('click').on('click', function () {
                            var post_data = {
                                post: {
                                    cust_id: $('#custprice-post-id').val(),
                                    date: $('#custprice-post-activedate').val(),
                                    priceby: $('#custprice-post-priceby').val(),
                                    stock: $('#custprice-post-stock').is(':checked'),
                                    so: $('#custprice-post-so').is(':checked'),
                                    sso: $('#custprice-post-sso').is(':checked'),
                                    sj: $('#custprice-post-sj').is(':checked'),
                                }
                            };
                            data = {...data, ...post_data};
                            submit(rou, data);
                        });
                    });
                // });
            });
        });

        function submit(route, data) {
            var method = (route == "{{route('tms.master.cust_price.save')}}" ? "POST" : "PUT");
            loading_start();
            ajaxCall({route: route, method: method, data: data}).then(resolve => {
                var msg = resolve.message;
                if (resolve.status == true) {
                    modalAction('#custprice-modal-index', 'hide');
                    Swal.fire({
                        title: 'Notification',
                        text: msg,
                        icon: 'success'
                    }).then(answer => {
                        modalAction('#custprice-modal-post', 'hide').then(() => {
                            tbl_custprice_index();
                        });
                    });
                }
            });
        }

        $(document).on('click', '.custprice-act-voided', function () {
            var cust = $('#custprice-create-customercode').val(); // $(this).data('custid');
            var date = $('#custprice-create-activedate').val().split('/').reverse().join('-'); // $(this).data('activedate');
            ajaxCall({route: "{{route('tms.master.cust_price.voided')}}", method: "POST", data: {cust_id: cust, date: date}}).then(resolve => {
                var msg = resolve.message;
                Swal.fire({
                    title: 'Notification',
                    text: msg,
                    icon: 'success'
                }).then(answer => {
                    // index_data.then(resolve => {
                    //     resolve.ajax.reload();
                    // });
                    tbl_custprice_index();
                });
            });
        });

        $(document).on('click', '.custprice-act-unvoided', function () {
            var cust = $('#custprice-create-customercode').val(); // $(this).data('custid');
            var date = $('#custprice-create-activedate').val().split('/').reverse().join('-'); // $(this).data('activedate');
            Swal.fire({
                title: `Do you want to unvoid Cust Price, now?`,
                input: 'text',
                inputPlaceholder: 'Type your note here...',
                showCancelButton: true,
                confirmButtonText: `Yes, unVoid it!`,
                confirmButtonColor: '#DC3545',
                icon: 'warning',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to write something!'
                    }
                }
            }).then((answer) => {
                if (answer.value != "" && answer.value != undefined) {
                    var note = answer.value;
                    ajaxCall({route: "{{route('tms.master.cust_price.unvoided')}}", method: "POST", data: {cust_id: cust, date: date, note: note}}).then(resolve => {
                        var msg = resolve.message;
                        Swal.fire({
                            title: 'Notification',
                            text: msg,
                            icon: 'success'
                        }).then(answer => {
                            tbl_custprice_index();
                        });
                    });
                }
            });
        });

        $(document).on('click', '.custprice-act-posted', function () {
            var cust = $('#custprice-create-customercode').val();
            var date = $('#custprice-create-activedate').val().split('/').reverse().join('-');
            var priceby = $('#custprice-create-priceby').val();
            modalAction('#custprice-modal-post').then(() => {
                $('#custprice-post-id').val(cust);
                $('#custprice-post-activedate').val(date);
                $('#custprice-post-priceby').val(priceby);
                if ($('#custprice-post-priceby').val() == 'SO') {
                    $('#custprice-post-stock').prop('checked', true);
                    $('#custprice-post-so').prop('checked', true);
                    $('#custprice-post-sso').prop('checked', false);
                    $('#custprice-post-sj').prop('checked', false);

                    // $('#custprice-post-sso').prop('disabled', true);
                    // $('#custprice-post-sj').prop('disabled', true);
                }else{
                    // $('#custprice-post-sso').prop('disabled', false);
                    // $('#custprice-post-sj').prop('disabled', false);

                    $('#custprice-post-stock').prop('checked', true);
                    $('#custprice-post-sso').prop('checked', true);
                    $('#custprice-post-so').prop('checked', true);
                    $('#custprice-post-sj').prop('checked', true);
                }

                $('#custprice-btn-post-submit-save').addClass('d-none');
                $('#custprice-btn-post-submit').removeClass('d-none');

                $('#custprice-btn-post-submit-save').off('click').on('click', function () {
                    var post_data = {
                        cust: $('#custprice-post-id').val(),
                        date: $('#custprice-post-activedate').val(),
                        priceby: $('#custprice-post-priceby').val(),
                        stock: $('#custprice-post-stock').is(':checked'),
                        so: $('#custprice-post-so').is(':checked'),
                        sso: $('#custprice-post-sso').is(':checked'),
                        sj: $('#custprice-post-sj').is(':checked'),
                    };

                    console.log(post_data);
                });
            });
        });
        $(document).on('click', '#custprice-btn-post-submit', function () {
            loading_start();
            const data = {
                cust: $('#custprice-post-id').val(),
                date: $('#custprice-post-activedate').val(),
                priceby: $('#custprice-post-priceby').val(),
                stock: $('#custprice-post-stock').is(':checked'),
                so: $('#custprice-post-so').is(':checked'),
                sso: $('#custprice-post-sso').is(':checked'),
                sj: $('#custprice-post-sj').is(':checked'),
            };
            ajaxCall({route: "{{route('tms.master.cust_price.posted')}}", method: "POST", data: data}).then(resolve => {
                var msg = resolve.message;
                Swal.fire({
                    title: 'Notification',
                    text: msg,
                    icon: 'success'
                }).then(answer => {
                    modalAction('#custprice-modal-post', 'hide').then(() => {
                        tbl_custprice_index();
                    });
                });
            });
        });

        $(document).on('click', '.custprice-act-unposted', function () {
            var cust = $('#custprice-create-customercode').val(); // $(this).data('custid');
            var date = $('#custprice-create-activedate').val().split('/').reverse().join('-'); // $(this).data('activedate');
            modalAction('#custprice-modal-index', 'hide').then(() => {
                Swal.fire({
                    title: `Do you want to unposted now?`,
                    input: 'text',
                    inputPlaceholder: 'Type your note here...',
                    showCancelButton: true,
                    confirmButtonText: `Yes, unposted it!`,
                    confirmButtonColor: '#DC3545',
                    icon: 'warning',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'You need to write something!'
                        }
                    }
                }).then((answer) => {
                    if (answer.value != "" && answer.value != undefined) {
                        var note = answer.value;
                        ajaxCall({route: "{{route('tms.master.cust_price.unposted')}}", method: "POST", data: {cust_id: cust, date: date, note: note}}).then(resolve => {
                            var msg = resolve.message;
                            Swal.fire({
                                title: 'Notification',
                                text: msg,
                                icon: 'success'
                            }).then(answer => {
                                // index_data.then(resolve => {
                                //     resolve.ajax.reload();
                                // });
                                tbl_custprice_index();
                            });
                        });
                    }
                });

            });
        });
        
        var tbl_log;
        $(document).on('click', '.custprice-act-log', function () {
            var cust = $('#custprice-create-customercode').val(); // $(this).data('custid');
            var date = $('#custprice-create-activedate').val().split('/').reverse().join('-'); // $(this).data('activedate');
            modalAction('#custprice-modal-log').then(resolve => {
                tbl_log = $('#custprice-datatables-log').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    searching: false,
                    ajax: {
                        url: "{{route('tms.master.cust_price.header')}}",
                        method: "POST",
                        data: {
                            type: "log",
                            cust_id: cust,
                            date: date,
                        },
                        headers: token_header
                    },
                    columns: [
                        {data:'date', name: 'date', className: "text-center align-middle"},
                        {data:'time', name: 'time', className: "text-left align-middle"},
                        {data:'status', name: 'status', className: "text-left align-middle"},
                        {data:'user', name: 'user', className: "text-center align-middle"},
                        {data:'note', name: 'note', className: "text-center align-middle"},
                    ],
                    ordering: false,
                    lengthChange: false
                });
            });
        });

        $('#custprice-btn-modal-print').on('click', function () {
            modalAction('#custprice-modal-action');
        });

        $(document).on('click', '.custprice-act-print', function () {
            var cust = $('#custprice-create-customercode').val(); // $(this).data('custid');
            var date = $('#custprice-create-activedate').val().split('/').reverse().join('-'); // $(this).data('activedate');
            var encrypt = btoa(`${cust}&${date}`);
            // index_data.then(resolve => {
            //     resolve.ajax.reload();
            // });
            tbl_custprice_index();
            modalAction('#custprice-modal-index', 'hide');
            var route = "{{route('tms.master.cust_price.print', [':code'])}}";
            route  = route.replace(':code', encrypt);
            window.open(route, '_blank');
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
            $('#custprice-create-priceby').val('DATE');
            $('#custprice-create-activedate').val("{{date('d/m/Y')}}");
            $('#custprice-create-entrydate').val("{{date('d/m/Y')}}");
            $('#custprice-dtsearch').val(null);
            $(tbl_item.table().header())
                .removeClass('bg-abu')
                .addClass('btn-info');
            tbl_item.clear().draw();
            tbl_item.search('').draw();
            isHidden('#custprice-btn-table-item', false);
            isHidden('#custprice-btn-index-submit', false);
            $('input').not('.readonly-first').prop('readonly', false);
            isHidden('#custprice-btn-action', true);

            $('#custprice-create-valas').prop('disabled', false);
            $('#custprice-create-priceby').prop('disabled', false);
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

        function loading_start() {
            $('body').loading({
                message: "wait for a moment...",
                zIndex: 9999
            });
        }

        function ajaxCall(params) {
            return new Promise((resolve, reject) => {
                // $('body').loading({
                //     message: "wait for a moment...",
                //     zIndex: 9999
                // });
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