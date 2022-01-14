@extends('master')
@section('title', 'TMS | Warehouse - DO Temp Entry')
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
    .btn-td {
        display:block;
        width: 100%;
        height: 100%;
    }
    .bg-abu {
        background-color: #d3d3d3;
    }
</style>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="do-btn-modal-create">
                    <i class="ti-plus"></i>  Add New Data
                </button>
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="do-btn-modal-print">
                    <i class="fa fa-print"></i>  Print
                </button>
                {{-- <button type="button"  class="btn btn-outline-primary btn-flat btn-sm" id="do-btn-modal-table-setting">
                    <i class="fa fa-cogs"></i>
                </button> --}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">DO Temporary Entry</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="do-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer">
                                        <thead>
                                            <tr>
                                                <th>DO No</th>
                                                <th>Date</th>
                                                <th>Posted</th>
                                                <th>Finish</th>
                                                <th>Voided</th>
                                                <th>Ref No</th>
                                                <th>DN No</th>
                                                <th>PO No</th>
                                                <th>Customer</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('tms.warehouse.do-pending-entry.modal.create.index')
{{-- @include('tms.warehouse.do-pending-entry.modal.header.branch') --}}
@include('tms.warehouse.do-pending-entry.modal.header.warehouse')
@include('tms.warehouse.do-pending-entry.modal.header.customer')
@include('tms.warehouse.do-pending-entry.modal.header.doaddr')
@include('tms.warehouse.do-pending-entry.modal.header.posted')
@include('tms.warehouse.do-pending-entry.modal.item.table_item')
{{-- @include('tms.warehouse.do-pending-entry.modal.item.add_item') --}}
@include('tms.warehouse.do-pending-entry.modal.log.tableLog')
@include('tms.warehouse.do-pending-entry.modal.print.modalPrint')
@include('tms.warehouse.do-pending-entry.modal.print.modalPrintDo')
@include('tms.warehouse.do-pending-entry.modal.table.tableNG')
@endsection
@section('script')
<script>
$(document).ready(function () {
    const obj_tbl = {
        destroy: true,
        lengthChange: false,
        searching: false,
        paging: false,
        ordering: false,
        scrollY: "200px",
        scrollCollapse: true,
        fixedHeader: true,
    };
    var item_select = [];
    var new_select = [];
    const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
    var table_index = new Promise((resolve, reject) => {
        let tbl_index =  $('#do-datatables').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{route('tms.warehouse.do_temp.tbl_index')}}",
                method: 'POST',
                headers: token_header
            },
            columns: [
                {data:'do_no', name: 'do_no', className: "align-middle"},
                {data:'delivery_date', name: 'delivery_date', className: "align-middle"},
                {data:'posted_date', name: 'posted_date', className: "align-middle"},
                {data:'finished_date', name: 'finished_date', className: "align-middle"},
                {data:'voided_date', name: 'voided_date', className: "align-middle"},
                {data:'ref_no', name: 'ref_no', className: "align-middle"},
                {data:'dn_no', name: 'dn_no', className: "align-middle"},
                {data:'po_no', name: 'po_no', className: "align-middle"},
                {data:'cust_name', name: 'cust_name', className: "align-middle"},
                {data:'action', name: 'action', className: "align-middle text-center"},
            ],
            order: [[ 1, "desc" ]],
        });
        resolve(tbl_index);
    });
    var tbl_item = $('#do-datatables-create').DataTable({
        destroy: true,
        lengthChange: false,
        searching: false,
        ordering: false,
        paging: false,
        scrollY: "200px",
        scrollCollapse: true,
        fixedHeader: true,
        columnDefs: [
            {
                targets: [5],
                createdCell:  function (td, cellData, rowData, row, col) {
                    $(td).addClass('text-right');
                }
            }
        ],
    });

    var tbl_items;
    function getTblItem(cust_id) {
        tbl_items = $('#do-datatables-items').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{route('tms.warehouse.do_temp.header_tools')}}",
                method: "POST",
                data: {
                    type: "item",
                    cust_code: cust_id
                },
                headers: token_header
            },
            columns: [
                {data:'itemcode', name: 'itemcode', className: "text-left align-middle"},
                {data:'part_no', name: 'part_no', className: "text-left align-middle"},
                {data:'descript', name: 'descript', className: "text-left align-middle"},
                {data:'model', name: 'model', className: "text-left align-middle"},
                {data:'unit', name: 'unit', className: "text-center align-middle"},
            ],
            ordering: false,
            lengthChange: false,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-id', data.itemcode);
                $(row).attr('id', data.itemcode);
            },
            rowCallback: function( row, data ) {
                if ( $.inArray(data.itemcode, item_select) !== -1 ) {
                    $(row).addClass('selected');
                }
            }
        });
    }

    $('#do-btn-modal-create').on('click', function () {
        modalAction('#do-modal-create').then(() => {
            var now = new Date();
            var currentMonth = ('0'+(now.getMonth()+1)).slice(-2);
            $('#do-create-priod').val(`${now.getFullYear()}-${currentMonth}`);
            $('#do-create-date').datepicker("setDate",'now');
            ajaxCall({route:"{{route('tms.warehouse.do_temp.header_tools')}}", method:"POST", data:{type: "DONo"}}).then(resolve => {
                loading_start();
                $('#do-create-no').val(resolve);
                loading_stop();
            });
        });
    });
    $('#do-create-date').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        enableOnReadonly: false
    }).on('changeDate', function(e) {
        var date = e.format(0, "yyyy-mm");
        var bln = e.format(0, "mm");
        var thn = e.format(0, "yyyy");
        $('#do-create-priod').val(date);
        // var refno = `DO/${$('#do-create-no').val().substr($('#do-create-no').val().length - 3)}/${toRoman(bln)}/${thn}`;
        // $('#do-create-refno').val(refno);
    });
    $('#do-modal-create').on('shown.bs.modal', function () {
        adjustDraw(tbl_item);
        $('#do-create-customercode').focus();
    });

    var tbl_customer;
    $(document).on('keypress keydown', '#do-create-customercode', function (e) {
        if(e.which == 13) {
            modalAction('#do-modal-customer').then((resolve) => {
                tbl_customer = $('#do-datatables-customer').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ route('tms.warehouse.do_temp.header_tools') }}",
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
    $(document).on('shown.bs.modal', '#do-modal-customer', function () {
        $('#do-datatables-customer_filter input').focus();
    });
    $('#do-datatables-customer').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        var data = tbl_customer.row(this).data();
        if (data.code != $('#do-create-customercode').val()) {
            tbl_item.clear().draw();
        }
        modalAction('#do-modal-customer', 'hide').then(() => {
            item_select = [];
            $('#do-create-customercode').val(data.code);
            $('#do-create-customergroup').val(data.cg);
            $('#do-create-customername').val(data.name);
        });
    });

    var tbl_doaddr;
    $(document).on('keypress keydown', '#do-create-customerdoaddr', function (e) {
        if(e.which == 13) {
            if ($('#do-create-customercode').val() == "") {
                Swal.fire({
                    title: 'Notification',
                    text: 'Please input customer first!',
                    icon: 'warning'
                });
            }else{
                modalAction('#do-modal-doaddr').then(() => {
                    tbl_doaddr = $('#do-datatables-doaddr').DataTable({
                        processing: true,
                        serverSide: true,
                        destroy: true,
                        ajax: {
                            url: "{{ route('tms.warehouse.do_temp.header_tools') }}",
                            method: 'POST',
                            data: {"type": "doaddr", "cust_code": $('#do-create-customercode').val()},
                            headers: token_header
                        },
                        columns: [
                            {data: 'code', name: 'code'},
                            {data: 'name', name: 'name'},
                            {data: 'do_addr1', name: 'do_addr1'},
                            {data: 'do_addr2', name: 'do_addr2'},
                            {data: 'do_addr3', name: 'do_addr3'},
                            {data: 'do_addr4', name: 'do_addr4'},
                        ]
                    });
                });
            }
        }
        if(e.which == 8 || e.which == 46) { return false; }
        return false;
    });
    $('#do-datatables-doaddr').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        var data = tbl_doaddr.row(this).data();
        modalAction('#do-modal-doaddr', 'hide').then(() => {
            $('#do-create-customerdoaddr').val(data.code);
            $('#do-create-customeraddr1').val(data.do_addr1);
            $('#do-create-customeraddr2').val(data.do_addr2);
            $('#do-create-customeraddr3').val(data.do_addr3);
            $('#do-create-customeraddr4').val(data.do_addr4);
        });
    });
    $(document).on('shown.bs.modal', '#do-modal-doaddr', function () {
        $('#do-datatables-doaddr_filter input').focus();
    });

    var tbl_wh;
    $(document).on('keypress keydown', '#do-create-warehouse', function (e) {
        if(e.which == 13) {
            modalAction('#do-modal-warehouse').then(() => {
                tbl_wh = $('#do-datatables-warehouse').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ route('tms.warehouse.do_temp.header_tools') }}",
                        method: 'POST',
                        data: {"type": "warehouse", "branch": $('#do-create-branch').val()},
                        headers: token_header
                    },
                    columns: [
                        {data: 'code', name: 'code'},
                        {data: 'name', name: 'name'},
                    ],
                    ordering: false,
                    lengthChange: false,
                    searching: false,
                });
            });
        }
        if(e.which == 8 || e.which == 46) { return false; }
        return false;
    });
    $('#do-datatables-warehouse').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        var data = tbl_wh.row(this).data();
        modalAction('#do-modal-warehouse', 'hide').then(() => {
            $('#do-create-warehouse').val(data.code);
        });
    });

    $(document).on('click', '#do-btn-add-item', function () {
        if ($('#do-create-customercode').val() == "") {
            Swal.fire({
                title: 'Notification',
                text: 'Please input customer first!',
                icon: 'warning'
            });
        }else{
            modalAction('#do-modal-itemtable').then(() => {
                var arr_item = tbl_item.rows().data().toArray();
                if (arr_item.length > 0) {
                    item_select = [];
                    for (let i = 0; i < arr_item.length; i++) {
                        item_select.push( arr_item[i][1] );
                    }
                }
                getTblItem($('#do-create-customercode').val());
            });
        }
    });
    $('#do-datatables-items').off('click', 'tr').on('click', 'tr', function () {
        var data = tbl_items.row(this).data();
        var id = this.id;
        var index = $.inArray(id, item_select);

        if ( index === -1 ) {
            item_select.push( id );
        } else {
            tbl_item.row('[class*="'+item_select[index]+'"]').remove().draw();
            var arr_item = tbl_item.rows().data().toArray();
            for (let i = 0; i < arr_item.length; i++) {
                var drw = tbl_item.cell( i, 0 ).data(1+i);
            }
            tbl_item.draw(false);
            item_select.splice( index, 1 );
        }

        $(this).toggleClass('selected');
    });
    $(document).on('click', '#do-btn-itemtable-submit', function () {
        if (item_select.length > 0) {
            var arr_item = tbl_item.rows().data().toArray();
            modalAction('#do-modal-itemtable', 'hide').then(() => {
                if (arr_item.length > 0) {
                    for (let i = 0; i < arr_item.length; i++) {
                        var cek = $.inArray(arr_item[i][1], item_select);
                        if ( index != -1 ) {
                            item_select.splice( cek, 1 );
                        }
                    }
                }
                var index = tbl_item.data().length;
                loading_start();
                ajaxCall({
                    route: "{{ route('tms.warehouse.do_temp.header_tools') }}",
                    method: "POST",
                    data: {"type": "item_select", "item_selected": JSON.stringify(item_select)}
                }).then(resolve => {
                    var add;
                    $.each(resolve.content, function (i, data) {
                        add = tbl_item.row.add([
                            index+1,
                            data.itemcode,
                            data.part_no,
                            data.descript,
                            data.unit,
                            `<input type="text" class="form-control form-control-sm text-right item-price-text" value="0.00">`,
                            // `<button type="button" class="text-bold text-white btn-td btn-danger">x</button>`
                        ]).node();
                        $(add).attr('data-id', index+1);
                        $(add).attr('id', data.itemcode);
                        $(add).addClass('data-'+index);
                        $(add).addClass(data.itemcode);
                        index++;
                    });
                    tbl_item.draw();
                    loading_stop();
                });
            });
        }else{
            Swal.fire({
                title: 'Warning',
                text: 'Please select item first!',
                icon: 'warning'
            });
        }
    });
    $(document).on('click', '#do-btn-itemtable-close', function () {
        item_select = [];
        var arr_item = tbl_item.rows().data().toArray();
        if (arr_item.length > 0) {
            for (let i = 0; i < arr_item.length; i++) {
                item_select.push( arr_item[i][1] );
            }
        }
    });
    $('#do-datatables-create tbody').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        var data = tbl_item.row(this).data();
        if (data != undefined) {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
                $('#do-btn-delete-item').prop('disabled', true);
            }else {
                tbl_item.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                $('#do-btn-delete-item').removeAttr('disabled');
            }
        }
    });
    $(document).on('click', '#do-btn-delete-item', function () {
        var tbl = tbl_item.row('.selected').data();
        var id = tbl[1];
        var index = $.inArray(id, item_select);

        item_select.splice( index, 1 );

        tbl_item.row('.selected').remove().draw( false );
        for (let i = 0; i < tbl_item.rows().data().toArray().length; i++) {
            var drw = tbl_item.cell( i, 0 ).data(1+i);
        }
        tbl_item.draw(false);
        
        $('#do-btn-delete-item').prop('disabled', true);
    });

    $(document).off('submit', '#do-modal-create').on('submit', '#do-modal-create', function () {
        loading_start();
        var items = tbl_item.rows().data().toArray();
        var items_fix = [];
        for (let i = 0; i < items.length; i++) {
            var obj_tbl_index = {}
            var qty = tbl_item.rows().cell(i, 5).nodes().to$().find('input').val();
            
            if (qty !== "0.00") {
                obj_tbl_index.itemcode = items[i][1];
                obj_tbl_index.part_no = items[i][2];
                obj_tbl_index.descript = items[i][3];
                obj_tbl_index.unit = items[i][4];
                obj_tbl_index.qty = qty;

                items_fix.push(obj_tbl_index);
            }

        }
        var data = {
            cust_id: $('#do-create-customercode').val(),
            cust_name: $('#do-create-customername').val(),
            sso: $('#do-create-sso').val(),
            so: $('#do-create-so').val(),
            doaddr: $('#do-create-customerdoaddr').val(),
            no: $('#do-create-no').val(),
            branch: $('#do-create-branch').val(),
            warehouse: $('#do-create-warehouse').val(),
            direct: $('#do-create-direct').val(),
            priod: $('#do-create-priod').val(),
            date: $('#do-create-date').val().split('/').reverse().join('-'),
            dnno: $('#do-create-dnno').val(),
            pono: $('#do-create-pono').val(),
            refno: $('#do-create-refno').val(),
            delivery: $('#do-create-delivery').val(),
            delivery2: $('#do-create-delivery2').val(),
            remark: $('#do-create-remark').val(),
            items: JSON.stringify(items_fix)
        };
        var route = "{{route('tms.warehouse.do_temp.detail', [':do_no', ':is_check'])}}";
            route  = route.replace(':do_no', data.no);
            route  = route.replace(':is_check', 1);
        var method = "POST";
        ajaxCall({route: route, method: "GET"}).then(resolve => {
            if (resolve.content == 'not_exist') {
                route = "{{route('tms.warehouse.do_temp.store')}}";
                method = "POST";
            }else{
                route = "{{route('tms.warehouse.do_temp.update', [':do_no'])}}";
                route  = route.replace(':do_no', data.no);
                method = "PUT";
            }
            submit_form(route, method, data);
        });
    });

    function submit_form(route, method, data) {
        ajaxCall({route: route, method: method, data: data}).then(resolve => {
            loading_stop();
            Swal.fire({
                title: 'Success',
                text: resolve.message,
                icon: 'success'
            }).then(() => {
                modalAction('#do-modal-create', 'hide').then(() => {
                    table_index.then((resolve) => {
                        resolve.ajax.reload();
                    });
                });
            });
        });
    }

    $(document).on('click', '.do-act-view', function () {
        var id = $(this).data('dono'),
            route = "{{route('tms.warehouse.do_temp.detail', [':do_no', ':is_check'])}}";
            route  = route.replace(':do_no', id);
            route  = route.replace(':is_check', 0);
        loading_start();
        modalAction('#do-modal-create').then(() => {
            isHidden('#item-button-div', true);
            isHidden('#do-btn-create-submit', true);
            isHidden('#do-btn-revise', true);
            $(tbl_item.table().header())
                    .removeClass('btn-info')
                    .addClass('bg-abu');
            $('#do-form-create input').prop('readonly', true);
            $('#do-form-create select').prop('disabled', true);
            ajaxCall({route: route, method: "GET"}).then(resolve => {
                var content = resolve.content;
                var no = 1;
                $.each(content, function (i, data) {
                    $('#do-create-customercode').val(data.cust_id)
                    $('#do-create-customername').val(data.custname)
                    $('#do-create-sso').val(data.sso_no)
                    $('#do-create-so').val(data.so_no)
                    $('#do-create-customerdoaddr').val(data.do_address)
                    $('#do-create-no').val(data.do_no)
                    $('#do-create-branch').val(data.branch)
                    $('#do-create-warehouse').val(data.warehouse)
                    $('#do-create-direct').val(data.sj_type)
                    $('#do-create-priod').val(data.period)
                    $('#do-create-date').val(date_convert(data.delivery_date))
                    $('#do-create-dnno').val(data.dn_no)
                    $('#do-create-pono').val(data.po_no)
                    $('#do-create-refno').val(data.ref_no)
                    $('#do-create-remark').val(data.remark)
                    $('#do-create-customergroup').val(data.custgroup);
                    $('#do-create-customeraddr1').val(data.do_addr1);
                    $('#do-create-customeraddr2').val(data.do_addr2);
                    $('#do-create-customeraddr3').val(data.do_addr3);
                    $('#do-create-customeraddr4').val(data.do_addr4);
                    $('#do-create-user').val(data.created_by);
                    $('#do-create-printed').val(datetime_convert(data.printed_date));
                    $('#do-create-voided').val(datetime_convert(data.voided_date));
                    $('#do-create-posted').val(datetime_convert(data.posted_date));
                    $('#do-create-finished').val(datetime_convert(data.finished_date));
                    $('#do-create-inv').val(data.invoice);
                    $('#do-create-rrno').val(data.rr_no);
                    $('#do-create-rgno').val(data.rg_no);

                    tbl_item.row.add([
                        no,
                        data.item_code,
                        data.part_no,
                        data.descript,
                        data.unit,
                        currency(addZeroes(String(data.quantity))),
                    ]).draw();
                    no++;
                });
                loading_stop();
            });
        });
    });

    var tbl_ng;
    $(document).on('click', '.do-act-edit', function () {
        var id = $(this).data('dono'),
            route = "{{route('tms.warehouse.do_temp.edit', [':do_no'])}}";
            route  = route.replace(':do_no', id);
        loading_start();
        ajaxCall({route: route, method: "GET"}).then(resolve => {
            if (resolve.message == true) {
                route = "{{route('tms.warehouse.do_temp.detail', [':do_no', ':is_check'])}}";
                route  = route.replace(':do_no', id);
                route  = route.replace(':is_check', 0);
                ajaxCall({route: route, method: "GET"}).then(resolve => {
                    loading_stop();
                    var content = resolve.content;
                    Swal.fire({
                        text: 'Do you want to create NG?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then(answer => {
                        if (answer.value == true) {
                            modalAction('#do-modal-ng').then(() => {
                                tbl_ng = $('#do-ng-datatables').DataTable({
                                    destroy: true,
                                    lengthChange: false,
                                    searching: false,
                                    paging: false,
                                    ordering: false,
                                    scrollY: "200px",
                                    scrollCollapse: true,
                                    fixedHeader: true,
                                    "columnDefs": [{
                                        "targets": [0,6],
                                        "createdCell":  function (td, cellData, rowData, row, col) {
                                            $(td).addClass('text-right');
                                        }
                                    }]
                                });
                                var no = 1;
                                var qty_ng;
                                tbl_ng.clear().draw();
                                $.each(content, function (i, data) {
                                    $('#do-ng-no').val(data.do_no);
                                    $('#do-ng-refno').val(data.ref_no);
                                    qty_ng = "0.00"; // (data.qty_ng == null) ? "0.00" : currency(addZeroes(String(data.qty_ng)));
                                    tbl_ng.row.add([
                                        no,
                                        data.part_no,
                                        data.item_code,
                                        data.descript,
                                        data.unit,
                                        currency(addZeroes(String(data.quantity))),
                                        `<input type="text" class="form-control-sm text-right item-price-text" autocomplete="off" id="rowngid-${no}" value="${qty_ng}">`
                                    ]).draw();
                                    no++;
                                });
                            });
                        }else{
                            modalAction('#do-modal-create').then(() => {
                                isHidden('#item-button-div', false);
                                isHidden('#do-btn-create-submit', false);

                                $('#do-create-customercode').prop('readonly', true);
                                item_select = [];
                                var no = 1;
                                $.each(content, function (i, data) {
                                    item_select.push( data.item_code );
                                    $('#do-create-customercode').val(data.cust_id)
                                    $('#do-create-customername').val(data.custname)
                                    $('#do-create-sso').val(data.sso_no)
                                    $('#do-create-so').val(data.so_no)
                                    $('#do-create-customerdoaddr').val(data.do_address)
                                    $('#do-create-no').val(data.do_no)
                                    $('#do-create-branch').val(data.branch)
                                    $('#do-create-warehouse').val(data.warehouse)
                                    $('#do-create-direct').val(data.sj_type)
                                    $('#do-create-priod').val(data.period)
                                    $('#do-create-date').val(date_convert(data.delivery_date))
                                    $('#do-create-dnno').val(data.dn_no)
                                    $('#do-create-pono').val(data.po_no)
                                    $('#do-create-refno').val(data.ref_no)
                                    $('#do-create-remark').val(data.remark)
                                    $('#do-create-customergroup').val(data.custgroup);
                                    $('#do-create-customeraddr1').val(data.do_addr1);
                                    $('#do-create-customeraddr2').val(data.do_addr2);
                                    $('#do-create-customeraddr3').val(data.do_addr3);
                                    $('#do-create-customeraddr4').val(data.do_addr4);
                                    $('#do-create-user').val(data.created_by);
                                    $('#do-create-printed').val(datetime_convert(data.printed_date));
                                    $('#do-create-voided').val(datetime_convert(data.voided_date));
                                    $('#do-create-posted').val(datetime_convert(data.posted_date));
                                    $('#do-create-finished').val(datetime_convert(data.finished_date));
                                    $('#do-create-inv').val(data.invoice);
                                    $('#do-create-rrno').val(data.rr_no);
                                    $('#do-create-rgno').val(data.rg_no);

                                    tbl_item.row.add([
                                        no,
                                        data.item_code,
                                        data.part_no,
                                        data.descript,
                                        data.unit,
                                        `<input type="text" class="form-control form-control-sm text-right item-price-text" value="${currency(addZeroes(String(data.quantity)))}">`,
                                        // currency(addZeroes(String(data.quantity))),
                                    ]).draw();
                                    no++;
                                });
                            });
                        }
                    });
                });
            }
        });
    });
    $(document).off('submit', '#do-form-ng').on('submit', '#do-form-ng', function () {
        loading_start();
        var fix_data = [];
        var item = tbl_ng.rows().data().toArray();
        var id = 0;
        var count = 0;
        var nu = 0;
        for (i=0;i < item.length; i++){
            var obj_tbl_ng = {}
            var qty_sj = tbl_ng.rows().cell(i, 6).nodes().to$().find('input').val();
                qty_sj = qty_sj.replace(/,/g, '')

            if (qty_sj != 0 && qty_sj != "" && qty_sj != "0.00") {
                var max_val_sj  =  item[i][5];

                obj_tbl_ng.do_no = $('#do-ng-no').val();
                obj_tbl_ng.itemcode = item[i][2];
                obj_tbl_ng.qty_sj = max_val_sj;
                
                obj_tbl_ng.qty_ng = qty_sj;

                if(parseFloat(qty_sj) > parseFloat(max_val_sj)){
                    count++;
                    id++;
                    if(qty_sj > 0){
                        $(`#rowngid-${id}`).removeClass('alert-success');
                        $(`#rowngid-${id}`).addClass('alert-danger'); 
                    }    
                }else if(qty_sj <= max_val_sj){
                    id++;
                    if(parseFloat(qty_sj) > 0){
                        $(`#rowngid-${id}`).removeClass('alert-danger');
                        $(`#rowngid-${id}`).addClass('alert-success'); 
                    }
                }

                fix_data.push(obj_tbl_ng);
            }
        }
        if (fix_data.length > 0) {
            if (count == 0) {
                var data = {"item": JSON.stringify(fix_data)},
                    route = "{{route('tms.warehouse.do_temp.ng_entry', [':do_no'])}}";
                    route  = route.replace(':do_no', $('#do-ng-no').val());
                ajaxCall({route: route, method: "PUT", data: data}).then(resolve => {
                    loading_stop();
                    var message = resolve.message;
                    modalAction('#do-modal-ng', 'hide').then(resolve => {
                        Swal.fire({
                            title: 'Success',
                            text: message,
                            icon: 'success'
                        }).then(() => {
                            table_index.then(resolve => {
                                resolve.ajax.reload();
                            });
                        });
                    });
                });
            }
        }
    });
    $(document).on('click', '.do-act-posted', function () {
        var id = $(this).data('dono');
        loading_start();
        ajaxCall({route: "{{route('tms.warehouse.do_temp.header_tools')}}", method: "POST", data:{type: "validation", do_no: id, cek: "posted"} }).then(resolve => {
            if (resolve.message == true) {
                modalAction('#do-modal-posted').then(() => {
                    loading_stop();
                    $('#do-posted-id').val(id);
                });
            }
        });
    });
    $(document).on('submit', '#do-form-posted', function () {
        Swal.fire({
            text: `POSTED DO Temporary ${$('#do-posted-id').val()} Now?`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then(answer => {
            if (answer.value == true) {
                loading_start();
                var data = {
                    rr_no: $('#do-posted-rrno').val(),
                    rr_date: $('#do-posted-rrdate').val(),
                    st: $('#do-posted-st').val(),
                    note: $('#do-posted-note').val()
                };
                var route = "{{route('tms.warehouse.do_temp.posted', [':do_no'])}}";
                    route  = route.replace(':do_no', $('#do-posted-id').val());
                ajaxCall({route: route, method: "PUT", data: data}).then(resolve => {
                    loading_stop();
                    Swal.fire({
                        title: 'Success',
                        text: resolve.message,
                        icon: 'success'
                    }).then(() => {
                        modalAction('#do-modal-posted', 'hide').then(() => {
                            table_index.then(resolve => {
                                resolve.ajax.reload();
                            });
                        });
                    });
                });
                // End
            }
        });
    });
    $(document).on('click', '.do-act-unposted', function () {
        var id = $(this).data('dono');
        Swal.fire({
            title: `Do you want to UNPOSTED DO Temporary ${id}, now ?`,
            input: 'text',
            inputPlaceholder: 'Type your note here...',
            showCancelButton: true,
            confirmButtonText: `Yes, Unposted it!`,
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
                loading_start();
                var data = {
                    note: note
                };
                var route = "{{route('tms.warehouse.do_temp.unposted', [':do_no'])}}";
                    route  = route.replace(':do_no', id);
                ajaxCall({route: route, method: "PUT", data: data}).then(resolve => {
                    loading_stop();
                    Swal.fire({
                        title: 'Success',
                        text: resolve.message,
                        icon: 'success'
                    }).then(() => {
                        table_index.then(resolve => {
                            resolve.ajax.reload();
                        });
                    });
                });
                // End
            }
        });
    });

    $(document).on('click', '.do-act-voided', function () {
        var id = $(this).data('dono');
        loading_start();
        ajaxCall({route: "{{route('tms.warehouse.do_temp.header_tools')}}", method: "POST", data:{type: "validation", do_no: id, cek: "voided"} }).then(resolve => {
            if (resolve.message == true) {
                loading_stop();
                Swal.fire({
                    title: `Do you want to VOID DO Temporary ${id}, now ?`,
                    input: 'text',
                    inputPlaceholder: 'Type your note here...',
                    showCancelButton: true,
                    confirmButtonText: `Yes, Void it!`,
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
                        loading_start();
                        var data = {
                            note: note
                        };
                        var route = "{{route('tms.warehouse.do_temp.voided', [':do_no'])}}";
                            route  = route.replace(':do_no', id);
                        ajaxCall({route: route, method: "PUT", data: data}).then(resolve => {
                            loading_stop();
                            Swal.fire({
                                title: 'Success',
                                text: resolve.message,
                                icon: 'success'
                            }).then(() => {
                                table_index.then(resolve => {
                                    resolve.ajax.reload();
                                });
                            });
                        });
                        // End
                    }
                });
            } // END IF MESSAGE
        });
    });

    $(document).on('click', '.do-act-unvoided', function () {
        var id = $(this).data('dono');
        Swal.fire({
            title: `Do you want to UNVOID DO Temporary ${id}, now ?`,
            input: 'text',
            inputPlaceholder: 'Type your note here...',
            showCancelButton: true,
            confirmButtonText: `Yes, Unvoid it!`,
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
                loading_start();
                var data = {
                    note: note
                };
                var route = "{{route('tms.warehouse.do_temp.unvoided', [':do_no'])}}";
                    route  = route.replace(':do_no', id);
                ajaxCall({route: route, method: "PUT", data: data}).then(resolve => {
                    loading_stop();
                    Swal.fire({
                        title: 'Success',
                        text: resolve.message,
                        icon: 'success'
                    }).then(() => {
                        table_index.then(resolve => {
                            resolve.ajax.reload();
                        });
                    });
                });
                // End
            }
        });
    });

    var tbl_log;
    $(document).on('click', '.do-act-log', function () {
        var id = $(this).data('dono');
        modalAction('#do-modal-log').then(() => {
            tbl_log = $('#do-datatables-log').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('tms.warehouse.do_temp.header_tools')}}",
                    method: "POST",
                    data: {
                        type: "log",
                        do_no: id
                    },
                    headers: token_header
                },
                columns: [
                    {data:'do_no', name: 'do_no', className: "text-left align-middle"},
                    {data:'created_date', name: 'created_date', className: "text-left align-middle"},
                    {data:'created_time', name: 'created_time', className: "text-left align-middle"},
                    {data:'status', name: 'status', className: "text-left align-middle"},
                    {data:'created_by', name: 'created_by', className: "text-center align-middle"},
                    {data:'note', name: 'note', className: "text-center align-middle"},
                ],
                ordering: false,
                lengthChange: false,
                searching: false
            });
        });
    });

    $(document).off('click', '.do-act-report').on('click', '.do-act-report', function () {
        var id = $(this).data('dono');
        modalAction('#do-modal-print').then(resolve => {
            $('#do-print-dari').val(id);
            $('#do-print-sampai').val(id);

            $('#do-print-dari').prop('readonly', true);
            $('#do-print-sampai').prop('readonly', true);
        });
    });

    $('#do-btn-modal-print').on('click', function () {
        modalAction('#do-modal-print');
    });

    var tbl_do_print;
    $(document).on('keypress keydown', '#do-print-dari', function (e) {
        if(e.which == 13) {
            modalAction('#do-modal-print-dodata').then(resolve => {
                $('#do-print-dodata-where').val('dari');
                var params = {"type": "dodataforprint"}
                tbl_do_print = $('#do-print-dodata-datatables').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ordering: false,
                    ajax: {
                        url: "{{ route('tms.warehouse.do_temp.header_tools') }}",
                        method: 'POST',
                        data: params,
                        headers: token_header
                    },
                    columns: [
                        {data: 'do_no', name: 'do_no'},
                        {data: 'delivery_date', name: 'delivery_date'},
                        {data: 'po_no', name: 'po_no'},
                        {data: 'cust_id', name: 'cust_id'},
                    ],
                });
            });
        }
        if(e.which == 8 || e.which == 46) { return false; }
        return false;
    });
    $(document).on('keypress keydown', '#do-print-sampai', function (e) {
        if(e.which == 13) {
            if ($('#do-print-dari').val() !== "") {
                modalAction('#do-modal-print-dodata').then(resolve => {
                    $('#do-print-dodata-where').val('sampai');
                    var params = {"type": "dodataforprint", "dari": $('#do-print-dari').val()}
                    tbl_do_print = $('#do-print-dodata-datatables').DataTable({
                        processing: true,
                        serverSide: true,
                        destroy: true,
                        ordering: false,
                        ajax: {
                            url: "{{ route('tms.warehouse.do_temp.header_tools') }}",
                            method: 'POST',
                            data: params,
                            headers: token_header
                        },
                        columns: [
                            {data: 'do_no', name: 'do_no'},
                            {data: 'delivery_date', name: 'delivery_date'},
                            {data: 'po_no', name: 'po_no'},
                            {data: 'cust_id', name: 'cust_id'},
                        ],
                    });
                });
            }
        }
        if(e.which == 8 || e.which == 46) { return false; }
        return false;
    });
    $('#do-print-dodata-datatables').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        var data = tbl_do_print.row(this).data();
        modalAction('#do-modal-print-dodata', 'hide').then((resolve) => {
            if ($('#do-print-dodata-where').val() == 'dari') {
                $('#do-print-dari').val(data.do_no);
                $('#do-print-sampai').val(null);
            }else{
                $('#do-print-sampai').val(data.do_no);
            }
        });
    });

    $(document).on('submit', '#do-form-print', function () {
        var dari = $('#do-print-dari').val();
        var sampai = $('#do-print-sampai').val();
        var type = $('#do-print-type').val();
        var encrypt = btoa(`${dari}&${sampai}&${type}`);
        table_index.then(resolve => {
            resolve.ajax.reload();
        });
        modalAction('#do-modal-print', 'hide');
        var url = "{{route('tms.warehouse.do_temp.print', [':enc'])}}";
            url = url.replace(':enc', encrypt);
        window.open(url, '_blank');
    });

    $(document).on('click', '.do-act-revise', function () {
        var id = $(this).data('dono'),
            route = "{{route('tms.warehouse.do_temp.detail', [':do_no', ':is_check'])}}";
            route  = route.replace(':do_no', id);
            route  = route.replace(':is_check', 0);
        loading_start();
        modalAction('#do-modal-create').then(resolve => {
            hideShow('#item-button-div', true);
            hideShow('#do-btn-create-submit', true);
            isHidden('#do-btn-revise', false);
            $('#do-form-create input').not('#do-create-date').prop('readonly', true);
            $('#do-form-create select').prop('disabled', true);

            ajaxCall({route: route, method: "GET"}).then(resolve => {
                var content = resolve.content;
                var no = 1;
                $.each(content, function (i, data) {
                    window.localStorage.setItem('date-old', date_convert(data.delivery_date));

                    $('#do-create-customercode').val(data.cust_id)
                    $('#do-create-customername').val(data.custname)
                    $('#do-create-sso').val(data.sso_no)
                    $('#do-create-so').val(data.so_no)
                    $('#do-create-customerdoaddr').val(data.do_address)
                    $('#do-create-no').val(data.do_no)
                    $('#do-create-branch').val(data.branch)
                    $('#do-create-warehouse').val(data.warehouse)
                    $('#do-create-direct').val(data.sj_type)
                    $('#do-create-priod').val(data.period)
                    $('#do-create-date').val(date_convert(data.delivery_date))
                    $('#do-create-dnno').val(data.dn_no)
                    $('#do-create-pono').val(data.po_no)
                    $('#do-create-refno').val(data.ref_no)
                    $('#do-create-remark').val(data.remark)
                    $('#do-create-customergroup').val(data.custgroup);
                    $('#do-create-customeraddr1').val(data.do_addr1);
                    $('#do-create-customeraddr2').val(data.do_addr2);
                    $('#do-create-customeraddr3').val(data.do_addr3);
                    $('#do-create-customeraddr4').val(data.do_addr4);
                    $('#do-create-user').val(data.created_by);
                    $('#do-create-printed').val(datetime_convert(data.printed_date));
                    $('#do-create-voided').val(datetime_convert(data.voided_date));
                    $('#do-create-posted').val(datetime_convert(data.posted_date));
                    $('#do-create-finished').val(datetime_convert(data.finished_date));
                    $('#do-create-inv').val(data.invoice);
                    $('#do-create-rrno').val(data.rr_no);
                    $('#do-create-rgno').val(data.rg_no);

                    tbl_item.row.add([
                        no,
                        data.item_code,
                        data.part_no,
                        data.descript,
                        data.unit,
                        currency(addZeroes(String(data.quantity))),
                    ]).draw();
                    no++;
                });
                loading_stop();
            });
        });
    });
    $(document).on('click', '#do-btn-revise', function () {
        if ($('#do-create-date').val() == "") {
            Swal.fire({
                title: 'Warning',
                text: 'Date input cannot be empty!',
                icon: 'warning'
            });
        }else{
            if ($('#do-create-date').val() == window.localStorage.getItem('date-old')) {
                Swal.fire({
                    title: 'Warning',
                    text: 'Input date cannot be the same as the previous date!',
                    icon: 'warning'
                });
            }else{
                loading_start();
                var data = {
                    "period": $('#do-create-priod').val(),
                    "date": $('#do-create-date').val().split('/').reverse().join('-'),
                }
                var route = "{{route('tms.warehouse.do_temp.revise', [':do_no'])}}";
                    route  = route.replace(':do_no', $('#do-create-no').val());
                ajaxCall({route: route, method: "PUT", data: data}).then(resolve => {
                    loading_stop();
                    Swal.fire({
                        title: 'Success',
                        text: resolve.message,
                        icon: 'success'
                    }).then(() => {
                        modalAction('#do-modal-create', 'hide').then(() => {
                            table_index.then(resolve => {
                                resolve.ajax.reload();
                            });
                        });
                    });
                });
            }
        }
    });

    $('#do-modal-print').on('hidden.bs.modal', function () {
        $(this).find('input').val(null);
        $(this).find('input').prop('readonly', false);
    });

    $('#do-modal-ng').on('shown.bs.modal', function () {
        tbl_ng.columns.adjust().draw();
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
    $('#do-modal-create').on('hidden.bs.modal', function () {
        $('#do-form-create').trigger('reset');
        $(tbl_item.table().header())
                .removeClass('bg-abu')
                .addClass('btn-info');
        tbl_item.clear().draw();
        isHidden('#item-button-div', false);
        isHidden('#do-btn-create-submit', false);
        isHidden('#do-btn-revise', true);
        $('#do-form-create input').not('.readonly-first').prop('readonly', false);
        $('#do-form-create select').prop('disabled', false);
    });
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
                        console.clear();
                    });
                    $('body').loading('stop');
                    reject(response);
                },
                complete: function (response){
                    // $('body').loading('stop');
                    resolve(response);
                }
            });
        });
    }
    function modalAction(elementId=null, action='show'){
        return new Promise(resolve => {
            $(elementId).modal(action);
            resolve($(elementId));
        });
    }
    function loading_start() {
        return $('body').loading({
            message: "wait for a moment...",
            zIndex: 9999
        });
    }

    function loading_stop() {
        return $('body').loading('stop');
    }

    function hideShow(element=null, hide=true){
        return ((hide == true) ? $(element).addClass('d-none') : $(element).removeClass('d-none'));
    }

    function toRoman(num){
        var roman = {
            M: 1000,
            CM: 900,
            D: 500,
            CD: 400,
            C: 100,
            XC: 90,
            L: 50,
            XL: 40,
            X: 10,
            IX: 9,
            V: 5,
            IV: 4,
            I: 1
        };
        var str = '';
        for (var i of Object.keys(roman)) {
            var q = Math.floor(num / roman[i]);
            num -= q * roman[i];
            str += i.repeat(q);
        }
        return str;
    }

    function resetCreateForm() {
        $('#do-create-sso').val('');
        $('#do-create-so').val('');
        $('#do-create-pono').val('');
        $('#do-create-dnno').val('');
        tbl_additem.clear().draw(false);
    }

    function date_convert($date) {
        var convert = ($date !== null) ? $date.split('-') : null;
        return (convert !== null) ? `${convert[2]}/${convert[1]}/${convert[0]}` : null;
    }
    function datetime_convert($date) {
        if ($date == null) {
            return null;
        }
        $date = $date.split(' ');
        return $date[0].split('-').reverse().join('/');
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
    function addZeroes( num ) {
        var value = Number(num);
        var res = num.split(".");
        if(res.length == 1 || (res[1].length < 4)) {
            value = value.toFixed(2);
        }
        return value;
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