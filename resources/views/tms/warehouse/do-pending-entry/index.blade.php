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
@include('tms.warehouse.do-pending-entry.modal.item.table_item')
{{-- @include('tms.warehouse.do-pending-entry.modal.item.add_item') --}}
@include('tms.warehouse.do-pending-entry.modal.log.tableLog')
@include('tms.warehouse.do-pending-entry.modal.print.modalPrint')
@include('tms.warehouse.do-pending-entry.modal.print.modalPrintDo')
{{-- @include('tms.warehouse.do-pending-entry.modal.table.tableNG') --}}
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
                {data:'written_date', name: 'written_date', className: "align-middle"},
                {data:'posted_date', name: 'posted_date', className: "align-middle"},
                {data:'finished_date', name: 'finished_date', className: "align-middle"},
                {data:'voided_date', name: 'voided_date', className: "align-middle"},
                {data:'ref_no', name: 'ref_no', className: "align-middle"},
                {data:'dn_no', name: 'dn_no', className: "align-middle"},
                {data:'po_no', name: 'po_no', className: "align-middle"},
                {data:'cust_id', name: 'cust_id', className: "align-middle"},
            ],
            order: [[ 0, "desc" ]],
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
        // columnDefs: [
        //     {
        //         targets: [6],
        //         createdCell:  function (td, cellData, rowData, row, col) {
        //             console.log(rowData);
        //             $(td).addClass('text-center');
        //         }
        //     }
        // ],
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
        // loading_start();
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
            console.log(resolve);
        });
    }

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
        tbl_item.clear().draw();
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