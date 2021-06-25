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
                    });
            });
        });
    });

    $('#do-create-date').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        enableOnReadonly: false
    }).datepicker("setDate",'now').on('changeDate', function(e) {
        var date = e.format(0, "yyyy-mm");
        var bln = e.format(0, "mm");
        var thn = e.format(0, "yyyy");
        $('#do-create-priod').val(date);
        var refno = `DO/${$('#do-create-no').val().substr($('#do-create-no').val().length - 3)}/${toRoman(bln)}/${thn}`;
        $('#do-create-refno').val(refno);
    });

    $(document).on('keypress', '#do-create-branch', function (e) {
        var tbl_branch;
        if(e.which == 13) {
            modalAction('#do-modal-branch').then((resolve) => {
                var params = {"type": "branch"}
                var column = [
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                ];
                tbl_branch =  $('#do-datatables-branch').DataTable({
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
                    lengthChange: false,
                    searching: false,
                    paging: false,
                    ordering: false
                });
            });
        }
        $('#do-datatables-branch').off('click', 'tr').on('click', 'tr', function () {
            modalAction('#do-modal-branch', 'hide').then((resolve) => {
                var data = tbl_branch.row(this).data();
                if (data.code == 'CP') {
                    $('#do-create-warehouse').val(data.code);
                }else{
                    $('#do-create-warehouse').val("");
                }
                $('#do-create-branch').val(data.code);
            });
        });
        e.preventDefault();
        return false;
    });
    $(document).on('keypress', '#do-create-warehouse', function (e) {
        var tbl_wh;
        if(e.which == 13) {
            modalAction('#do-modal-warehouse').then((resolve) => {
                var branch = ($('#do-create-branch').val() == "") ? null : $('#do-create-branch').val();
                var params = {"type": "warehouse", "branch": branch}
                var column = [
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                ];
                tbl_wh =  $('#do-datatables-warehouse').DataTable({
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
                    lengthChange: false,
                    searching: false,
                    paging: false,
                    ordering: false
                });
            });
        }
        $('#do-datatables-warehouse').off('click', 'tr').on('click', 'tr', function () {
            modalAction('#do-modal-warehouse', 'hide').then((resolve) => {
                var data = tbl_wh.row(this).data();
                $('#do-create-warehouse').val(data.code);
            });
        });
        e.preventDefault();
        return false;
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
                var data = tbl_customer.row(this).data();
                $('#do-create-customercode').val(data.code);
                var params = {"type": "customerclick", "cust_code": data.code};
                ajax("{{ route('tms.warehouse.do_entry.header_tools') }}",
                    "POST",
                    params,
                    (response) => {
                    response = response.responseJSON;
                        $('#do-create-customerdoaddr').val(response.content.code);
                        $('#do-create-customername').val(response.content.name);
                        $('#do-create-customeraddr1').val(response.content.do_addr1);
                        $('#do-create-customeraddr2').val(response.content.do_addr2);
                        $('#do-create-customeraddr3').val(response.content.do_addr3);
                        $('#do-create-customeraddr4').val(response.content.do_addr4);
                    });
            });
        });
        e.preventDefault();
        return false;
    });
    $(document).on('keypress', '#do-create-customerdoaddr', function (e) {
        var tbl_doaddr;
        if(e.which == 13) {
            modalAction('#do-modal-doaddr').then((resolve) => {
                var customercode = ($('#do-create-customercode').val() == "") ? null : $('#do-create-customercode').val();
                var params = {"type": "doaddr", "cust_code": customercode}
                var column = [
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'do_addr1', name: 'do_addr1'},
                    {data: 'do_addr2', name: 'do_addr2'},
                    {data: 'do_addr3', name: 'do_addr3'},
                    {data: 'do_addr4', name: 'do_addr4'},
                ];
                tbl_doaddr = $('#do-datatables-doaddr').DataTable({
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
                    lengthChange: false,
                    searching: false,
                    paging: false,
                    ordering: false
                });
            });
        }
        $('#do-datatables-doaddr').off('click', 'tr').on('click', 'tr', function () {
            modalAction('#do-modal-doaddr', 'hide').then((resolve) => {
                var data = tbl_doaddr.row(this).data();
                $('#do-create-customerdoaddr').val(data.code);
                $('#do-create-customername').val(data.name);
                $('#do-create-customeraddr1').val(data.do_addr1);
                $('#do-create-customeraddr2').val(data.do_addr2);
                $('#do-create-customeraddr3').val(data.do_addr3);
                $('#do-create-customeraddr4').val(data.do_addr4);
            });
        });
        e.preventDefault();
        return false;
    });
    $(document).on('click', '#do-btn-add-item', function () {
        addItem();
    });
    $(document).on('keypress', '#do-additem-itemcode', function (e) {
        if(e.which == 13) {
            addItem();
        }
        e.preventDefault();
        return false;
    });

    $(document).on('hidden.bs.modal', '#do-modal-additem', () => {
        $('#do-form-additem').trigger('reset');
        $('#do-additem-index').val(0);
    });

    $(document).on('submit', '#do-form-additem', function () {
        var index = tbl_additem.data().length;
        if ($('#do-additem-index').val() == 0) {
            var add = tbl_additem.row.add([
                index+1,
                $('#do-additem-itemcode').val(),
                $('#do-additem-partno').val(),
                $('#do-additem-unit').val(),
                $('#do-additem-qtysj').val(),
                $('#do-additem-qtybilled').val(),
                $('#do-additem-qtytag').val(),
                $('#do-additem-description').val(),
            ]).node();
            $(add).attr('data-id', index+1);
            tbl_additem.draw();
        }else{
            var idx = parseInt($('#do-additem-index').val()) - 1;
            tbl_additem.row( idx ).data([
                $('#do-additem-index').val(),
                $('#do-additem-itemcode').val(),
                $('#do-additem-partno').val(),
                $('#do-additem-unit').val(),
                $('#do-additem-qtysj').val(),
                $('#do-additem-qtybilled').val(),
                $('#do-additem-qtytag').val(),
                $('#do-additem-description').val(),
            ]).draw();
        }
        $('#do-form-additem').trigger('reset');
        modalAction('#do-modal-additem', 'hide');
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
    $(document).on('click', '#do-btn-delete-item', function () {
        tbl_additem.row('.selected').remove().draw();
        for (let i = 0; i < tbl_additem.rows().data().toArray().length; i++) {
           var drw = tbl_additem.cell( i, 0 ).data(1+i); 
        }
        tbl_additem.draw();
        $('#do-btn-edit-item').prop('disabled', true);
        $('#do-btn-delete-item').prop('disabled', true);
    });
    $(document).on('click', '#do-btn-edit-item', function () {
        var data = tbl_additem.row('.selected').data();
        modalAction('#do-modal-additem').then((resolve) => {
            $('#do-additem-index').val(data[0]);
            $('#do-additem-itemcode').val(data[1]);
            $('#do-additem-partno').val(data[2]);
            $('#do-additem-unit').val(data[3]);
            $('#do-additem-qtysj').val(data[4]);
            $('#do-additem-qtybilled').val(data[5]);
            $('#do-additem-qtytag').val(data[6]);
            $('#do-additem-description').val(data[7]);
        });
    });

    function addItem() {
        return get_tbl_item().then((resolve) => {
            if (resolve != false) {
                tbl_item = resolve;
                resolve.ajax.reload();
                modalAction('#do-modal-itemtable').then((resolve) => {
                    $('#do-datatables-items').off('click', 'tr').on('click', 'tr', function () {
                        var row_id = $(this).data('id');
                        var data = tbl_item.row(this).data();
                        var cek = tbl_additem.rows().data().toArray();
                        var isExist = false;
                        if (cek.length > 0) {
                            for (let i = 0; i < cek.length; i++) {
                                if (data.ITEMCODE == cek[i][1]) {
                                    isExist = true;
                                    break;
                                }
                            }
                        }
                        if (isExist == true) {
                            Swal.fire({
                                title: 'Warning!',
                                text: "Itemcode ini tersedia, silahkan klik edit pada tabel untuk melakukan perubahan!",
                                icon: 'warning'
                            });
                        }else{
                            modalAction('#do-modal-itemtable', 'hide').then((resolve) => {
                                if (!$('#do-modal-additem').hasClass('show')) {
                                    modalAction('#do-modal-additem');
                                }
                                $('#do-additem-itemcode').val(data.ITEMCODE);
                                $('#do-additem-partno').val(data.PART_NO);
                                $('#do-additem-description').val(data.DESCRIPT);
                                $('#do-additem-unit').val(data.UNIT);
                            });
                        }
                    });
                });
            }
        }, (reject) => {
            Swal.fire({
                title: 'Warning!',
                text: "Silahkan mengisi customer code terlebih dahulu!",
                icon: 'warning'
            });
        });
    }
    
    var tbl_do_setting = $('#do-setting-datatables').DataTable(obj_tbl);
    $('#do-btn-modal-table-setting').on('click', function () {
        modalAction('#do-modal-setting').then((resolve) => {
            resolve.on('shown.bs.modal', () => {
                tbl_do_setting.columns.adjust().draw();
                var column = [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'data', name: 'data'},
                    {data: 'title', name: 'title'},
                    {data: 'status', name: 'status'},
                    // {data: 'status_ori', name: 'status_ori'},
                    // {data: 'idx', name: 'idx'},
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
                // tbl_do_setting.columns([4,5]).visible(false);
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
                    title: 'Success!',
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
    function modalAction(elementId=null, action='show'){
        return new Promise(resolve => {
            $(elementId).modal(action);
            resolve($(elementId));
        });
        // return $(elementId).modal(action);
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