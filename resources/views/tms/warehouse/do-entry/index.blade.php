@extends('master')
@section('title', 'TMS | Warehouse - DO Entry')
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
    /* input[readonly] {
        background-color: #fff !important;
        cursor: not-allowed;
        pointer-events: all !important;
    } */
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
</style>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="do-btn-modal-create">
                    <i class="ti-plus"></i>  Add New Data
                </button>
                <button type="button"  class="btn btn-outline-primary btn-flat btn-sm" id="do-btn-modal-table-setting">
                    <i class="fa fa-cogs"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">DO Entry</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="do-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer">
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
@include('tms.warehouse.do-entry.modal.table.tableIndexSetting')
@include('tms.warehouse.do-entry.modal.create.index')
@include('tms.warehouse.do-entry.modal.header.branch')
@include('tms.warehouse.do-entry.modal.header.warehouse')
@include('tms.warehouse.do-entry.modal.header.customer')
@include('tms.warehouse.do-entry.modal.header.doaddr')
@include('tms.warehouse.do-entry.modal.item.table_item')
@include('tms.warehouse.do-entry.modal.item.add_item')
@include('tms.warehouse.do-entry.modal.log.tableLog')
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
    const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
    const get_index = new Promise(function(resolve, reject) {
        ajax("{{route('tms.warehouse.do_entry.table_index_setting')}}", 'GET', {"tbl_index":1}, function (response) {
            var index_header = [];
            $.each(response.responseJSON.content, function( key, value ) {
                var my_item = {};
                my_item.data = value.data;
                my_item.name = value.data;
                my_item.title = value.title;
                if (value.data == 'action') {
                    my_item.orderable = false;
                    my_item.searchable = false;
                }
                my_item.className = 'text-center';
                index_header.push(my_item);
            });
            let tbl_index =  $('#do-datatables').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('tms.warehouse.do_entry.table_index')}}",
                    method: 'POST',
                    headers: token_header
                },
                columns: index_header,
                order: [[ 0, "desc" ]],
            });
            resolve(tbl_index);
        });
    });

    const get_tbl_item = () => {
        return new Promise(function(resolve, reject) {
            var customercode = ($('#do-create-customercode').val() == "") ? null : $('#do-create-customercode').val();
            if (customercode == null) {
                reject(customercode);
            }
            let params = {"type": "item", "cust_code": customercode}
            let column = [
                {data: 'ITEMCODE', name: 'ITEMCODE'},
                {data: 'PART_NO', name: 'PART_NO'},
                {data: 'DESCRIPT', name: 'DESCRIPT'},
                {data: 'UNIT', name: 'UNIT'},
            ];
            let tbl_item = $('#do-datatables-items').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('tms.warehouse.do_entry.header_tools') }}",
                    method: "POST",
                    headers: token_header,
                    data: params
                },
                columns: column,
                createdRow: function( row, data, dataIndex ) {
                    $(row).attr('data-id', data.ITEMCODE);
                    $(row).attr('id', `row-${data.ITEMCODE}`);
                },
            });
            resolve(tbl_item);
        });
    }

    var tbl_additem = $('#do-datatables-create').DataTable(obj_tbl);

    $('#do-btn-modal-create').on('click', function () {
        modalAction('#do-modal-create').then(function (resolve) {
            resolve.on('shown.bs.modal', () => {
                tbl_additem.columns.adjust().draw();
                var now = new Date();
                var currentMonth = ('0'+(now.getMonth()+1)).slice(-2);
                $('#do-create-priod').val(`${now.getFullYear()}-${currentMonth}`);
                var params = {"type": "DONo"};
                ajax("{{ route('tms.warehouse.do_entry.header_tools') }}",
                    "POST",
                    params,
                    (response) => {
                        var resText = response.responseText;
                        response = response.responseJSON;
                        $('#do-create-no').val(response);
                        var refno = `DO/${resText.substr(resText.length - 3)}/${toRoman(currentMonth)}/${now.getFullYear()}`;
                        $('#do-create-refno').val(refno);
                        $('#do-create-date').datepicker("setDate",'now');
                    });
            });
        });
    });
    $('#do-modal-create').on('hidden.bs.modal', () => {
        $('#do-form-create').trigger('reset');
        tbl_additem.clear().draw(false);
        hideShow('#item-button-div', false);
        hideShow('#do-btn-create-submit', false);
        $('#do-form-create input').not('.readonly-first').removeAttr('readonly');
        $('#do-form-create select').prop('disabled', false);
        $('#do-btn-create-submit').text('Simpan');
        $('#do-btn-edit-item').prop('disabled', true);
        $('#do-btn-delete-item').prop('disabled', true);
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
        var refno = `DO/${$('#do-create-no').val().substr($('#do-create-no').val().length - 3)}/${toRoman(bln)}/${thn}`;
        $('#do-create-refno').val(refno);
    });

    $(document).on('keypress', '#do-create-customercode', function (e) {
        var tbl_customer;
        if(e.which == 13) {
            modalAction('#do-modal-customer').then((resolve) => {
                var params = {"type": "customer"}
                var column = [
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                ];
                tbl_customer = $('#do-datatables-customer').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ route('tms.warehouse.do_entry.header_tools') }}",
                        method: 'POST',
                        data: params,
                        headers: token_header
                    },
                    columns: column,
                });
            });
        }
        $('#do-datatables-customer').off('click', 'tr').on('click', 'tr', function () {
            modalAction('#do-modal-customer', 'hide').then((resolve) => {
                resetCreateForm();
                var data = tbl_customer.row(this).data();
                $('#do-create-customercode').val(data.code);
                $('#do-create-customergroup').val(data.cg);
                if (data.code == 'H03' || data.code == 'H10' || data.code == 'Y01') {
                    $('#do-create-so').prop('readonly', false);
                    $('#do-create-sso').prop('readonly', true);
                }else{
                    $('#do-create-sso').prop('readonly', false);
                    $('#do-create-so').prop('readonly', true);
                }
                var params = {"type": "customerclick", "cust_code": data.code};
                ajax("{{ route('tms.warehouse.do_entry.header_tools') }}",
                    "POST",
                    params,
                    (response) => {
                    response = response.responseJSON;
                        $('#do-create-customername').val(response.content.name);
                    });
            });
        });
        e.preventDefault();
        return false;
    });
    $('#do-create-sso').on('keypress', (e) => {
        var sso = $('#do-create-sso').val();
        if(e.which == 13) {
            if (sso == "") {
                showNotif({
                    'title': 'Warning',
                    'message': 'Silahkan input SSO',
                    'icon': 'warning'
                });
            }else{
                ajax("{{ route('tms.warehouse.do_entry.header_tools') }}",
                    "POST",
                    {"type": "sso_header", "sso_header": sso},
                    (response) => {
                        response = response.responseJSON;
                        if (response.status == true) {
                            var data = response.content;
                            if (data.cust_id == $('#do-create-customercode').val()) {
                                if (data.closed_date == null) {
                                    $('#do-create-customerdoaddr').val(data.id_do);
                                    $('#do-create-customeraddr1').val(data.Address1);
                                    $('#do-create-customeraddr2').val(data.Address2);
                                    $('#do-create-customeraddr3').val(data.Address3);
                                    $('#do-create-customeraddr4').val(data.Address4);
                                    $('#do-create-so').val(data.so_header);
                                    $('#do-create-branch').val(data.branch);
                                    $('#do-create-warehouse').val(data.wh);
                                    $('#do-create-dnno').val(data.dn_no);
                                    $('#do-create-pono').val(data.po_no);
                                    $('#do-create-sso').prop('readonly', true);
                                    $('#do-create-so').prop('readonly', true);
                                }else{
                                    showNotif({
                                        'title': 'Warning',
                                        'message': 'SSO/SO hass been closed',
                                        'icon': 'error'
                                    });
                                }
                            }else{
                                showNotif({
                                    'title': 'Warning',
                                    'message': 'Customer tidak sesuai dengan SSO No',
                                    'icon': 'warning'
                                });
                            }
                        }
                    });
            }
            e.preventDefault();
            return false;
        }
    });
    $('#do-create-so').on('keypress', (e) => {
        var so = $('#do-create-so').val();
        if(e.which == 13) {
            if (so == "") {
                showNotif({
                    'title': 'Warning',
                    'message': 'Silahkan input SO',
                    'icon': 'warning'
                });
            }else{
                ajax("{{ route('tms.warehouse.do_entry.header_tools') }}",
                    "POST",
                    {"type": "so_header", "so_header": so},
                    (response) => {
                        response = response.responseJSON;
                        if (response.status == true) {
                            var data = response.content;
                            if (data.cust_id == $('#do-create-customercode').val()) {
                                if (data.closed_date == null) {
                                    $('#do-create-customerdoaddr').val(data.id_do);
                                    $('#do-create-customeraddr1').val(data.Address1);
                                    $('#do-create-customeraddr2').val(data.Address2);
                                    $('#do-create-customeraddr3').val(data.Address3);
                                    $('#do-create-customeraddr4').val(data.Address4);
                                    $('#do-create-so').val(data.so_header);
                                    $('#do-create-sso').val('*');
                                    $('#do-create-branch').val(data.branch);
                                    $('#do-create-warehouse').val(data.wh);
                                    $('#do-create-dnno').val(data.dn_no);
                                    $('#do-create-pono').val(data.po_no);
                                    $('#do-create-sso').prop('readonly', true);
                                    $('#do-create-so').prop('readonly', true);
                                }else{
                                    showNotif({
                                        'title': 'Warning',
                                        'message': 'SSO/SO hass been closed',
                                        'icon': 'error'
                                    });
                                }
                            }else{
                                showNotif({
                                    'title': 'Warning',
                                    'message': 'Customer tidak sesuai dengan SO No',
                                    'icon': 'warning'
                                });
                            }
                        }
                    });
            }
            e.preventDefault();
            return false;
        }
    });
    
    var tbl_items = $('#do-datatables-items').DataTable(obj_tbl);
    $(document).on('click', '#do-btn-add-item', function () {
        tbl_items.clear().draw();
        var sso = $('#do-create-sso').val();
        var so = $('#do-create-so').val();
        var send;
        if ($('#do-create-sso').val() != "" && $('#do-create-so').val() != "") {
            if ($('#do-create-sso').val() == "*") {
                send = {
                    "type": "sso_detail",
                    "so_header": $('#do-create-so').val()
                };
            }else{
                send = {
                    "type": "sso_detail",
                    "sso_header": $('#do-create-sso').val()
                }; 
            }
            ajax("{{ route('tms.warehouse.do_entry.header_tools') }}",
                "POST",
                send,
                (response) => {
                    response = response.responseJSON;
                    if (response.status == true) {
                        var data = response.content;
                        var sum_qty_sj = 0;
                        var sum_qty_sso = 0;
                        var id=0;
                        for (i=0; i < data.length; i++){
                            sum_qty_sj += parseInt(data[i].qty_sj);
                            sum_qty_sso += parseInt(data[i].qty_sso);
                        }
                        var max_qty = sum_qty_sso - sum_qty_sj;
                        if(max_qty > 0){
                            for (i=0; i < data.length; i++){
                                if(data[i].qty_sj < data[i].qty_sso){
                                    id++;
                                    tbl_items.row.add([
                                        data[i].dn_no,
                                        data[i].itemcode,
                                        data[i].part_no,
                                        data[i].sso_no,
                                        data[i].so_no,
                                        data[i].part_name,
                                        data[i].model,
                                        data[i].qty_so,
                                        data[i].qty_sso,
                                        data[i].qty_sj,
                                        data[i].unit,
                                        '<input autocomplete="off" type="number" class="insj form-control-sm" id="rowid-'+id+'">'
                                    ]).draw();
                                }
                            }
                            modalAction('#do-modal-itemtable').then(resolve => {
                                resolve.on('shown.bs.modal', function () {
                                    tbl_items.columns.adjust().draw();
                                });
                            });
                        }else{
                            showNotif({
                                'title': 'error',
                                'message': 'All Qty SSO/SO has been sent',
                                'icon': 'error'
                            }).then(resolve => {}, reject => {
                                // resetCreateForm();
                            });
                        }
                    }
                });
        }else{
            showNotif({
                'title': 'Warning',
                'message': 'Silahkan input SO/SSO terlebih dahulu',
                'icon': 'warning'
            });
        }
    });

    $(document).on('click', '#do-btn-itemtable-selectall', () => {
        var item = tbl_items.rows().data().toArray();
        for (i=0;i < item.length; i++){
            var qty =  item[i][8] - item[i][9];
            tbl_items.rows().cell(i,11).nodes().to$().find('input').val(qty);
        }
    });
    $(document).on('click', '#do-btn-itemtable-submit', () => {
        tbl_additem.clear().draw();
        var item = tbl_items.rows().data().toArray();
        var id = 0;
        var count = 0;
        var nu = 0;
        for (i=0;i < item.length; i++){
            var max_val_sj  =  item[i][8] - item[i][9];
            var qty_sj = tbl_items.rows().cell(i, 11).nodes().to$().find('input').val();
            if(qty_sj > max_val_sj){
                count++;
                id++;
                if(qty_sj > 0){
                    $(`#rowid-${id}`).removeClass('alert-success');
                    $(`#rowid-${id}`).addClass('alert-danger'); 
                }    
            }else if(qty_sj <= max_val_sj){
                id++;
                if(qty_sj > 0){
                    $(`#rowid-${id}`).removeClass('alert-danger');
                    $(`#rowid-${id}`).addClass('alert-success'); 
                }
            }
        }
        if(count == 0){
            for (i=0;i < item.length; i++){
                var max_val_sj  =  item[i][8] - item[i][9];
                var qty_sj = tbl_items.rows().cell(i, 11).nodes().to$().find('input').val();
                if (qty_sj > 0){
                    nu++;
                    tbl_additem.row.add([
                        nu,
                        item[i][1],
                        item[i][2],
                        item[i][10],
                        qty_sj,
                        0,
                        0,
                        item[i][3],
                        item[i][5]
                    ]).draw();
                    modalAction('#do-modal-itemtable', 'hide');
                }
            }
        }
    });

    $('#do-datatables-create tbody').off('click', 'tr').on('click', 'tr', function () {
        var data = tbl_additem.row(this).data();
        if (data != undefined) {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
                $('#do-btn-edit-item').prop('disabled', true);
                $('#do-btn-delete-item').prop('disabled', true);
            }else {
                tbl_additem.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                $('#do-btn-edit-item').removeAttr('disabled');
                $('#do-btn-delete-item').removeAttr('disabled');
            }
        }
    });

    $(document).off('click', '.do-act-view').on('click', '.do-act-view', function () {
        var id = $(this).data('dono');
        hideShow('#item-button-div', true);
        hideShow('#do-btn-create-submit', true);
        $('#do-form-create input').prop('readonly', true);
        $('#do-form-create select').prop('disabled', true);
        ajax("{{route('tms.warehouse.do_entry.read')}}", "GET", {"do_no": id, "view_do": true}, (response) => {
            response = response.responseJSON;
            var header = response.content.header;
            var items = response.content.items;

            var date = date_convert(header.delivery_date);
            var voided = date_convert(header.voided);
            var posted = date_convert(header.posted);
            var finished = date_convert(header.finished);
            var printed = date_convert(header.printed);

            $('#do-create-no').val(header.do_no);
            $('#do-create-branch').val(header.branch);
            $('#do-create-warehouse').val(header.warehouse);
            $('#do-create-direct').val(((header.sj_type != null) ? header.sj_type : 'Regular'));
            $('#do-create-priod').val(header.period);
            $('#do-create-date').val(date);
            $('#do-create-sso').val(header.sso_no);
            $('#do-create-so').val(header.so_no);
            $('#do-create-pono').val(header.po_no);
            $('#do-create-dnno').val(header.dn_no);
            $('#do-create-refno').val(header.ref_no);
            $('#do-create-remark').val(header.remark);
            $('#do-create-customercode').val(header.cust_id);
            $('#do-create-customerdoaddr').val(header.id_do);
            $('#do-create-customername').val(header.cust_name);
            $('#do-create-customergroup').val(10);
            $('#do-create-customeraddr1').val(header.address1);
            $('#do-create-customeraddr2').val(header.address2);
            $('#do-create-customeraddr3').val(header.address3);
            $('#do-create-customeraddr4').val(header.address4);
            $('#do-create-user').val(header.user);
            $('#do-create-printed').val(printed);
            $('#do-create-voided').val(voided);
            $('#do-create-posted').val(posted);
            $('#do-create-finished').val(finished);
            $('#do-create-inv').val(header.invoice);
            // $('#do-create-rgno').val(header.);
            // $('#do-create-rrno').val(header.);

            var no = 1;
            $.each(items, function (i, item) {
                tbl_additem.row.add([
                    no,
                    item['item_code'],
                    item['part_no'],
                    item['unit'],
                    item['quantity'],
                    0,
                    0,
                    item['sso_no'],
                    item['part_name']
                ]).draw();
                no++;
            });

            modalAction('#do-modal-create').then(resolve => {
                resolve.on('shown.bs.modal', function () {
                    tbl_additem.columns.adjust().draw();
                });
            })
        });
    });

    $(document).off('click', '.do-act-edit').on('click', '.do-act-edit', function () {
        var id = $(this).data('dono');
        ajax("{{route('tms.warehouse.do_entry.read')}}", "GET", {"do_no": id, "view_do": true}, (response) => {
            response = response.responseJSON;
            var header = response.content.header;
            var items = response.content.items;
            if (header.voided == null) {
                if (header.finished == null) {
                    if (header.posted == null) {
                        var date = date_convert(header.delivery_date);
                        var voided = date_convert(header.voided);
                        var posted = date_convert(header.posted);
                        var finished = date_convert(header.finished);
                        var printed = date_convert(header.printed);

                        $('#do-create-no').val(header.do_no);
                        $('#do-create-branch').val(header.branch);
                        $('#do-create-warehouse').val(header.warehouse);
                        $('#do-create-direct').val(((header.sj_type != null) ? header.sj_type : 'Regular'));
                        $('#do-create-priod').val(header.period);
                        $('#do-create-date').val(date);
                        $('#do-create-sso').val(header.sso_no);
                        $('#do-create-so').val(header.so_no);
                        $('#do-create-pono').val(header.po_no);
                        $('#do-create-dnno').val(header.dn_no);
                        $('#do-create-refno').val(header.ref_no);
                        $('#do-create-remark').val(header.remark);
                        $('#do-create-customercode').val(header.cust_id);
                        $('#do-create-customerdoaddr').val(header.id_do);
                        $('#do-create-customername').val(header.cust_name);
                        $('#do-create-customergroup').val(10);
                        $('#do-create-customeraddr1').val(header.address1);
                        $('#do-create-customeraddr2').val(header.address2);
                        $('#do-create-customeraddr3').val(header.address3);
                        $('#do-create-customeraddr4').val(header.address4);
                        $('#do-create-user').val(header.user);
                        $('#do-create-printed').val(printed);
                        $('#do-create-voided').val(voided);
                        $('#do-create-posted').val(posted);
                        $('#do-create-finished').val(finished);
                        $('#do-create-inv').val(header.invoice);
                        // $('#do-create-rgno').val(header.);
                        // $('#do-create-rrno').val(header.);

                        var no = 1;
                        $.each(items, function (i, item) {
                            tbl_additem.row.add([
                                no,
                                item['item_code'],
                                item['part_no'],
                                item['unit'],
                                item['quantity'],
                                0,
                                0,
                                item['sso_no'],
                                item['part_name']
                            ]).draw();
                            no++;
                        });

                        modalAction('#do-modal-create').then(resolve => {
                            resolve.on('shown.bs.modal', function () {
                                tbl_additem.columns.adjust().draw();
                            });
                        })
                    }else{
                        Swal.fire({
                            title: 'Access Denied!',
                            text: 'DO has been posted!',
                            icon: 'error'
                        });
                    }
                }else{
                    Swal.fire({
                        title: 'Access Denied!',
                        text: 'DO has been finished!',
                        icon: 'error'
                    });
                }
            }else{
                Swal.fire({
                    title: 'Access Denied!',
                    text: 'DO has been voided!',
                    icon: 'error'
                });
            }
        });
    });

    $(document).off('click', '.do-act-voided').on('click', '.do-act-voided', function () {
        var id = $(this).data('dono');
        ajax("{{route('tms.warehouse.do_entry.read')}}", "GET", {"do_no": id, "check": true}, (response) => {
            response = response.responseJSON;
            if (response.message == null) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Void DO No." + id + " Now?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, void it!'
                }).then(answer => {
                    if (answer.value == true) {
                        ajax("{{route('tms.warehouse.do_entry.void')}}", "POST", {"do_no": id}, (response) => {
                            response = response.responseJSON;
                            if (response.status == true) {
                                showNotif({
                                    'title': 'Notification',
                                    'message': response.message,
                                    'icon': 'success'
                                }).then(resolve => {
                                    get_index.then(resolve => {
                                        resolve.ajax.reload();
                                    })
                                });
                            }
                        });
                    }
                });
            }else{
                Swal.fire({
                    title: 'Access Denied!',
                    text: response.message,
                    icon: 'error'
                });
            }
        });
    });
    $(document).off('click', '.do-act-unvoided').on('click', '.do-act-unvoided', function () {
        var id = $(this).data('dono');
        Swal.fire({
            title: `Do you want to unvoid DO no. ${id}, now ?`,
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
                ajax("{{route('tms.warehouse.do_entry.unvoid')}}", "POST", {"do_no": id, "note": note}, (response) => {
                    response = response.responseJSON;
                    if (response.status == true) {
                        showNotif({
                            'title': 'Notification',
                            'message': response.message,
                            'icon': 'success'
                        }).then(resolve => {
                            get_index.then(resolve => {
                                resolve.ajax.reload();
                            })
                        });
                    }
                });
            }
        });
    });
    $(document).off('click', '.do-act-posted').on('click', '.do-act-posted', function () {
        var id = $(this).data('dono');
        ajax("{{route('tms.warehouse.do_entry.read')}}", "GET", {"do_no": id, "check": true}, (response) => {
            response = response.responseJSON;
            if (response.message == null) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Post DO No." + id + " Now?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, post it!'
                }).then(answer => {
                    if (answer.value == true) {
                        ajax("{{route('tms.warehouse.do_entry.post')}}", "POST", {"do_no": id}, (response) => {
                            response = response.responseJSON;
                            if (response.status == true) {
                                showNotif({
                                    'title': 'Notification',
                                    'message': response.message,
                                    'icon': 'success'
                                }).then(resolve => {
                                    get_index.then(resolve => {
                                        resolve.ajax.reload();
                                    })
                                });
                            }
                        });
                    }
                });
            }else{
                Swal.fire({
                    title: 'Access Denied!',
                    text: response.message,
                    icon: 'error'
                });
            }
        });
    });
    $(document).off('click', '.do-act-unposted').on('click', '.do-act-unposted', function () {
        var id = $(this).data('dono');
        Swal.fire({
            title: `Do you want to unpost DO no. ${id}, now ?`,
            input: 'text',
            inputPlaceholder: 'Type your note here...',
            showCancelButton: true,
            confirmButtonText: `Yes, unpost it!`,
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
                ajax("{{route('tms.warehouse.do_entry.unpost')}}", "POST", {"do_no": id, "note": note}, (response) => {
                    response = response.responseJSON;
                    if (response.status == true) {
                        showNotif({
                            'title': 'Notification',
                            'message': response.message,
                            'icon': 'success'
                        }).then(resolve => {
                            get_index.then(resolve => {
                                resolve.ajax.reload();
                            })
                        });
                    }
                });
            }
        });
    });

    $(document).on('submit', '#do-form-create', () => {
        var form_data = {
            "do_no": $('#do-create-no').val(),
            "branch": $('#do-create-branch').val(),
            "warehouse": $('#do-create-warehouse').val(),
            "sj_type": $('#do-create-direct').val(),
            "priod": $('#do-create-priod').val(),
            "date": $('#do-create-date').val(),
            "sso": $('#do-create-sso').val(),
            "so": $('#do-create-so').val(),
            "pono": $('#do-create-pono').val(),
            "dnno": $('#do-create-dnno').val(),
            "refno": $('#do-create-refno').val(),
            "delivery": $('#do-create-delivery').val(),
            "delivery2": $('#do-create-delivery2').val(),
            "remark": $('#do-create-remark').val(),
            "customercode": $('#do-create-customercode').val(),
            "customerdoaddr": $('#do-create-customerdoaddr').val(),
            "customername": $('#do-create-customername').val(),
            "customergroup": $('#do-create-customergroup').val(),
            "customeraddr1": $('#do-create-customeraddr1').val(),
            "customeraddr2": $('#do-create-customeraddr2').val(),
            "customeraddr3": $('#do-create-customeraddr3').val(),
            "customeraddr4": $('#do-create-customeraddr4').val(),
            "user": $('#do-create-user').val(),
            "printed": $('#do-create-printed').val(),
            "voided": $('#do-create-voided').val(),
            "posted": $('#do-create-posted').val(),
            "finished": $('#do-create-finished').val(),
            "inv": $('#do-create-inv').val(),
            "rgno": $('#do-create-rgno').val(),
            "rrno": $('#do-create-rrno').val(),
            "items": tbl_additem.rows().data().toArray()
        };
        if (tbl_additem.rows().data().toArray().length > 0) {
            $('#do-btn-create-submit').prop('disabled', true);
            var params = {
                "route": "{{route('tms.warehouse.do_entry.read')}}",
                "method": "GET",
                "data": {
                    "do_no": $('#do-create-no').val()
                }
            };
            ajaxWithPromise(params).then(resolve => {
                var response = resolve;
                var route;
                if (response.message == 'false') {
                    route = "{{route('tms.warehouse.do_entry.create')}}";
                }else{
                    route = "{{route('tms.warehouse.do_entry.update')}}";
                }
                var post = {
                    "route": route,
                    "method": "POST",
                    "data": form_data
                };
                ajaxWithPromise(post).then(resolve => {
                    var response = resolve;
                    if (response.status == true) {
                        showNotif({
                            'title': 'Notification',
                            'message': response.message,
                            'icon': 'success'
                        }).then(resolve => {
                            modalAction('#do-create-modal', 'hide').then(resolve => {
                                get_index.then(resolve => {
                                    resolve.ajax().reload();
                                });
                            });
                        });
                    }
                    $('#do-btn-create-submit').prop('disabled', false);
                }, reject => {
                    var response = reject;
                    $('#do-btn-create-submit').prop('disabled', false);
                });
            });
        }
    });

    var tbl_log = $('#do-datatables-log').DataTable(obj_tbl);
    $(document).on('click', '.do-act-log', function () {
        var id = $(this).data('dono');
        var column = [
            {data: 'date_log', name: 'date_log'},
            {data: 'time_log', name: 'time_log'},
            {data: 'status_log', name: 'status_log'},
            {data: 'user', name: 'user'},
            {data: 'note', name: 'note'}
        ];
        tbl_log = $('#do-datatables-log').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{ route('tms.warehouse.do_entry.header_tools') }}",
                method: 'POST',
                data: {"type":"log", "do_no":id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: column,
            lengthChange: false,
            searching: false,
            paging: false,
            ordering: false,
            scrollY: "200px",
            scrollCollapse: true,
            fixedHeader:true,
        });
        modalAction('#do-modal-log').then(resolve => {
            resolve.on('shown.bs.modal', function () {
                tbl_log.columns.adjust().draw();
            });
        });
    });
    
    var tbl_do_setting = $('#do-setting-datatables').DataTable(obj_tbl);
    $('#do-btn-modal-table-setting').on('click', function () {
        modalAction('#do-modal-setting').then((resolve) => {
            resolve.on('shown.bs.modal', () => {
                tbl_do_setting.columns.adjust().draw();
                var column = [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'data', name: 'data'},
                    {data: 'title', name: 'title'},
                    {data: 'status', name: 'status'}
                ];
                tbl_do_setting = $('#do-setting-datatables').DataTable({
                    processing: false,
                    serverSide: false,
                    destroy: true,
                    ajax: {
                        url: "{{ route('tms.warehouse.do_entry.table_index_setting') }}",
                        method: 'GET',
                        headers: token_header
                    },
                    columns: column,
                    lengthChange: false,
                    searching: false,
                    paging: false,
                    ordering: false,
                    scrollY: "200px",
                    scrollCollapse: true,
                    fixedHeader: true,
                });
            });
        });
    });
    $('#do-setting-datatables').off('click', 'tr').on('click', 'tr', function () {
        var data = tbl_do_setting.row(this).data();
        if (data.data !== 'action') {
            var status_change;
            if (data.status_ori == 1) {
                status_change = `<i class="fa fa-times text-danger">`;
            }else{
                status_change = `<i class="fa fa-check text-success">`;
            }
            tbl_do_setting.row(this).data({
                "DT_RowIndex": data.DT_RowIndex,
                "data": data.data,
                "title": data.title,
                "status": status_change,
                "status_ori": (data.status_ori == 1 ? 0 : 1),
                "idx": data.idx,
            }).draw();
        }
    });
    $(document).on('click', '#do-btn-setting-save', () => {
        var data = tbl_do_setting.rows().data().toArray();
        ajax(
            "{{ route('tms.warehouse.do_entry.header_tools') }}",
            "POST",
            {"type":"setting", "setting":data},
            (response) => {
                response = response.responseJSON;
                Swal.fire({
                    title: 'Notification',
                    text: response.message,
                    icon: 'success'
                }).then(() => {
                    modalAction('#do-modal-setting', 'hide').then((resolve) => {
                        location.reload();
                    });
                });
            }
        );
    });

    function ajax(route, method, params=null, callback) {
        $.ajax({
            url: route,
            method: method,
            dataType: "JSON",
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: params,
            error: function(response, status, x){
                Swal.fire({
                    title: 'Error!',
                    text: response.responseJSON.message,
                    icon: 'error'
                })
            },
            complete: function (response){
                callback(response);
            }
        });
    }
    function ajaxWithPromise(params) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: params.route,
                method: params.method,
                dataType: "JSON",
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: params.data,
                error: function(response, status, x){
                    Swal.fire({
                        title: 'Error!',
                        text: response.responseJSON.message,
                        icon: 'error'
                    });
                    reject(response);
                },
                complete: function (response){
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

    function hideShow(element=null, hide=true){
        return ((hide == true) ? $(element).addClass('d-none') : $(element).removeClass('d-none'));
    }

    function showNotif(params) {
        return new Promise((resolve, reject) => {
            Swal.fire({
                title: params.title,
                text: params.message,
                icon: params.icon
            }).then(function (res) {
                if (params.icon != 'error') {
                    resolve(res);
                }else{
                    reject(res);
                }
            });
        });
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
});
</script>
@endsection
@push('js')
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('/vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
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