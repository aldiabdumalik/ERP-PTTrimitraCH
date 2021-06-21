@extends('master')
@section('title', 'TMS | Warehouse - Claim Entry')
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
    /* .dataTables_scrollHeadInner {
        width: 100% !important;
    }
    .dataTables_scrollHeadInner table {
        width: 100% !important;
    }
    .dataTables_scrollBody::-webkit-scrollbar { 
        display: none;
    }
    .dataTables_scrollBody {
        -ms-overflow-style: none;
        scrollbar-width: none;
    } */
</style>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="claim-btn-modal-create">
                    <i class="ti-plus"></i>  Add New Data
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">Claim Entry</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="claim-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer">
                                        <thead class="text-center" style="font-size: 15px;">
                                            <tr>
                                                <th class="align-middle">CL No.</th>
                                                <th class="align-middle">Written</th>
                                                <th class="align-middle">Date DO</th>
                                                <th class="align-middle">Date RG</th>
                                                <th class="align-middle">RR No.</th>
                                                <th class="align-middle">Ref No.</th>
                                                <th class="align-middle">PO No.</th>
                                                <th class="align-middle">Customer</th>
                                                <th class="align-middle">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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
{{-- </div> --}}
@include('tms.warehouse.claim-entry.modal.create.index')
@include('tms.warehouse.claim-entry.modal.header.branch')
@include('tms.warehouse.claim-entry.modal.header.warehouse')
@include('tms.warehouse.claim-entry.modal.header.customer')
@include('tms.warehouse.claim-entry.modal.header.doaddr')
@include('tms.warehouse.claim-entry.modal.item.addItem')
@include('tms.warehouse.claim-entry.modal.item.tableItem')
@include('tms.warehouse.claim-entry.modal.log.tableLog')
@include('tms.warehouse.claim-entry.modal.status.do')
@include('tms.warehouse.claim-entry.modal.status.rg')
@include('tms.warehouse.claim-entry.modal.status.rgQty')
@include('tms.warehouse.claim-entry.modal.status.rgComplete')

@endsection

@section('script')
<script>
$(document).ready(function(){
    var tbl_index = $('#claim-datatables').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: "{{route('tms.warehouse.claim_entry.read')}}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: [
            {data:'cl_no', name: 'cl_no'},
            {data:'written', name: 'written'},
            {data:'date_do', name: 'date_do', className: "text-center",},
            {data:'date_rg', name: 'date_rg', className: "text-center",},
            {data:'rr_no', name: 'rr_no', className: "text-center",},
            {data:'ref_no', name: 'ref_no'},
            {data:'po_no', name: 'po_no'},
            {data:'cust_code', name: 'cust_code'},
            {data:'action', name: 'action', orderable: false, searchable: false, className: "text-center",},
        ],
        order: [[ 0, "asc" ]],
    });
    var tbl_create = $('#claim-datatables-create').DataTable({
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "ordering": false,
        "scrollY": "200px",
        "scrollCollapse": true,
        "fixedHeader":true,
    });
    var tbl_rg = $('#claim-datatables-rg').DataTable({
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "ordering": false,
        "scrollY": "200px",
        "scrollCollapse": true,
        "fixedHeader":true,
    });
    var tbl_rg_complete = $('#claim-datatables-rg-complete').DataTable({
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "ordering": false,
        "scrollY": "200px",
        "scrollCollapse": true,
        "fixedHeader":true,
    });
    var tbl_log = $('#claim-datatables-log').DataTable({
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "ordering": false,
        "scrollY": "200px",
        "scrollCollapse": true,
        "fixedHeader":true,
    });
    $('#claim-btn-modal-create').on('click', function () {
        modalAction('#claim-modal-create');
        var now = new Date();
        var currentMonth = ('0'+(now.getMonth()+1)).slice(-2);
        $('#claim-create-priod').val(`${now.getFullYear()}-${currentMonth}`);
        var params = {"type": "CLNo"};
        ajax("{{ route('tms.warehouse.claim_entry.header_tools') }}",
            "POST",
            params,
            function (response) {
                var resText = response.responseText;
                response = response.responseJSON;
                $('#claim-create-no').val(response);
                var refno = `CL/${resText.substr(resText.length - 3)}/${toRoman(currentMonth)}/${now.getFullYear()}`;
                $('#claim-create-refno').val(refno);
        });
    });
    $('#claim-datatables-create tbody').off('click', 'tr').on('click', 'tr', function () {
        var data = tbl_create.row(this).data();
        if (data != undefined) {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
                $('#claim-btn-edit-item').prop('disabled', true);
                $('#claim-btn-delete-item').prop('disabled', true);
            }else {
                tbl_create.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                $('#claim-btn-edit-item').removeAttr('disabled');
                $('#claim-btn-delete-item').removeAttr('disabled');
            }
        }
    });
    $(document).on('click', '#claim-btn-delete-item', function () {
        tbl_create.row('.selected').remove().draw( false );
        for (let i = 0; i < tbl_create.rows().data().toArray().length; i++) {
           var drw = tbl_create.cell( i, 0 ).data(1+i); 
        }
        tbl_create.draw(false);
        $('#claim-btn-edit-item').prop('disabled', true);
        $('#claim-btn-delete-item').prop('disabled', true);
    });
    $(document).on('click', '#claim-btn-edit-item', function () {
        var data = tbl_create.row('.selected').data();
        modalAction('#claim-modal-additem');
        $('#claim-additem-index').val(data[0]);
        $('#claim-additem-itemcode').val(data[1]);
        $('#claim-additem-partno').val(data[2]);
        $('#claim-additem-description').val(data[3]);
        $('#claim-additem-unit').val(data[4]);
        $('#claim-additem-qtysj').val(data[5]);
        $('#claim-additem-qtyrg').val(data[6]);
        $('#claim-additem-note').val(data[7]);
    });

    $('#claim-create-date').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        enableOnReadonly: false
    }).datepicker("setDate",'now').on('changeDate', function(e) {
        var date = e.format(0, "yyyy-mm");
        var bln = e.format(0, "mm");
        var thn = e.format(0, "yyyy");
        $('#claim-create-priod').val(date);
        var refno = `CL/${$('#claim-create-no').val().substr($('#claim-create-no').val().length - 3)}/${toRoman(bln)}/${thn}`;
        $('#claim-create-refno').val(refno);
    });

    $(document).on('keypress', '#claim-create-branch', function (e) {
        var tbl_branch;
        if(e.which == 13) {
            modalAction('#claim-modal-branch');
            var params = {"type": "branch"}
            var column = [
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name'},
            ];
            tbl_branch =  $('#claim-datatables-branch').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('tms.warehouse.claim_entry.header_tools') }}",
                    method: 'POST',
                    data: params,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                columns: column,
                lengthChange: false,
                searching: false,
                paging: false,
                ordering: false
            });
            
        }
        $('#claim-datatables-branch').off('click').on('click', 'tr', function () {
            modalAction('#claim-modal-branch', 'hide');
            var data = tbl_branch.row(this).data();
            if (data.code == 'CP') {
                $('#claim-create-warehouse').val(data.code);
            }else{
                $('#claim-create-warehouse').val("");
            }
            $('#claim-create-branch').val(data.code);
        });
        e.preventDefault();
        return false;
    });

    $(document).on('keypress', '#claim-create-warehouse', function (e) {
        var tbl_wh;
        if(e.which == 13) {
            modalAction('#claim-modal-warehouse');
            var branch = ($('#claim-create-branch').val() == "") ? null : $('#claim-create-branch').val();
            var params = {"type": "warehouse", "branch": branch}
            var column = [
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name'},
            ];
            tbl_wh =  $('#claim-datatables-warehouse').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('tms.warehouse.claim_entry.header_tools') }}",
                    method: 'POST',
                    data: params,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                columns: column,
                lengthChange: false,
                searching: false,
                paging: false,
                ordering: false
            });
            
        }
        $('#claim-datatables-warehouse').off('click').on('click', 'tr', function () {
            modalAction('#claim-modal-warehouse', 'hide');
            var data = tbl_wh.row(this).data();
            $('#claim-create-warehouse').val(data.code);
        });
        e.preventDefault();
        return false;
    });

    $(document).on('keypress', '#claim-create-customercode', function (e) {
        var tbl_customer;
        if(e.which == 13) {
            modalAction('#claim-modal-customer');
            var params = {"type": "customer"}
            var column = [
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name'},
            ];
            tbl_customer = $('#claim-datatables-customer').DataTable(dataTables(
                "{{ route('tms.warehouse.claim_entry.header_tools') }}",
                "POST",
                params,
                column
            ));
        }
        $('#claim-datatables-customer').off('click').on('click', 'tr', function () {
            modalAction('#claim-modal-customer', 'hide');
            var data = tbl_customer.row(this).data();
            $('#claim-create-customercode').val(data.code);
            var params = {"type": "customerclick", "cust_code": data.code};
            ajax("{{ route('tms.warehouse.claim_entry.header_tools') }}",
                "POST",
                params,
                function (response) {
                response = response.responseJSON;
                $('#claim-create-customerdoaddr').val(response.content.code);
                $('#claim-create-customername').val(response.content.name);
                $('#claim-create-customeraddr1').val(response.content.do_addr1);
                $('#claim-create-customeraddr2').val(response.content.do_addr2);
                $('#claim-create-customeraddr3').val(response.content.do_addr3);
                $('#claim-create-customeraddr4').val(response.content.do_addr4);
            });
        });
        e.preventDefault();
        return false;
    });

    $(document).on('keypress', '#claim-create-customerdoaddr', function (e) {
        var tbl_doaddr;
        if(e.which == 13) {
            modalAction('#claim-modal-doaddr');
            var customercode = ($('#claim-create-customercode').val() == "") ? null : $('#claim-create-customercode').val();
            var params = {"type": "doaddr", "cust_code": customercode}
            var column = [
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name'},
                {data: 'do_addr1', name: 'do_addr1'},
                {data: 'do_addr2', name: 'do_addr2'},
                {data: 'do_addr3', name: 'do_addr3'},
                {data: 'do_addr4', name: 'do_addr4'},
            ];
            tbl_doaddr = $('#claim-datatables-doaddr').DataTable(dataTables(
                "{{ route('tms.warehouse.claim_entry.header_tools') }}",
                "POST",
                params,
                column
            ));
        }
        $('#claim-datatables-doaddr').off('click').on('click', 'tr', function () {
            modalAction('#claim-modal-doaddr', 'hide');
            var data = tbl_doaddr.row(this).data();
            $('#claim-create-customerdoaddr').val(data.code);
            $('#claim-create-customername').val(data.name);
            $('#claim-create-customeraddr1').val(data.do_addr1);
            $('#claim-create-customeraddr2').val(data.do_addr2);
            $('#claim-create-customeraddr3').val(data.do_addr3);
            $('#claim-create-customeraddr4').val(data.do_addr4);
        });
        e.preventDefault();
        return false;
    });

    $(document).on('click', '#claim-btn-add-item', function () {
        modalAction('#claim-modal-additem');
    });
    $(document).on('hidden.bs.modal', '#claim-modal-additem', function () {
        $(this).find('form').trigger('reset');
    });
    $(document).on('submit', '#claim-form-additem', function () {
        var index = tbl_create.data().length;
        if ($('#claim-additem-index').val() == 0) {
            var add = tbl_create.row.add([
                index+1,
                $('#claim-additem-itemcode').val(),
                $('#claim-additem-partno').val(),
                $('#claim-additem-description').val(),
                $('#claim-additem-unit').val(),
                $('#claim-additem-qtysj').val(),
                $('#claim-additem-qtyrg').val(),
                $('#claim-additem-note').val(),
            ]).node();
            $(add).attr('data-id', index+1);
            tbl_create.draw(false);
        }else{
            var idx = parseInt($('#claim-additem-index').val()) - 1;
            tbl_create.row( idx ).data([
                $('#claim-additem-index').val(),
                $('#claim-additem-itemcode').val(),
                $('#claim-additem-partno').val(),
                $('#claim-additem-description').val(),
                $('#claim-additem-unit').val(),
                $('#claim-additem-qtysj').val(),
                $('#claim-additem-qtyrg').val(),
                $('#claim-additem-note').val(),
            ]).draw(false);
        }
        $('#claim-form-additem').trigger('reset');
        modalAction('#claim-modal-additem', 'hide');
    });
    $(document).on('keypress', '#claim-additem-itemcode', function (e) {
        var tbl_item;
        if(e.which == 13) {
            var customercode = ($('#claim-create-customercode').val() == "") ? null : $('#claim-create-customercode').val();
            if (customercode == null) {
                Swal.fire({
                    title: 'Warning!',
                    text: "Silahkan mengisi customer code terlebih dahulu!",
                    icon: 'warning'
                });
            }else{
                modalAction('#claim-modal-itemtable');
                var params = {"type": "item", "cust_code": customercode}
                var column = [
                    {data: 'ITEMCODE', name: 'ITEMCODE'},
                    {data: 'PART_NO', name: 'PART_NO'},
                    {data: 'DESCRIPT', name: 'DESCRIPT'},
                    {data: 'UNIT', name: 'UNIT'},
                ];
                tbl_item = $('#claim-datatables-items').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ route('tms.warehouse.claim_entry.header_tools') }}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: params
                    },
                    columns: column,
                    createdRow: function( row, data, dataIndex ) {
                        $(row).attr('data-id', data.ITEMCODE);
                        $(row).attr('id', `row-${data.ITEMCODE}`);
                    },
                });
            }
            $('#claim-datatables-items').off('click').on('click', 'tr', function () {
                var row_id = $(this).data('id');
                var data = tbl_item.row(this).data();
                var cek = tbl_create.rows().data().toArray();
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
                    modalAction('#claim-modal-itemtable', 'hide');
                    $('#claim-additem-itemcode').val(data.ITEMCODE);
                    $('#claim-additem-partno').val(data.PART_NO);
                    $('#claim-additem-description').val(data.DESCRIPT);
                    $('#claim-additem-unit').val(data.UNIT);
                }
            });
        }
    });

    $(document).on('click', '.claim-act-view', function () {
        var id = $(this).data('clno');
        ajax(
            "{{route('tms.warehouse.claim_entry.read')}}",
            "POST",
            {"cl_no": id},
            function (response) {
                response = response.responseJSON;
                if (response.status == true) {
                    modalAction('#claim-modal-create');
                    hideShow('#item-button-div', true);
                    hideShow('#claim-btn-create-submit', true);
                    $('#claim-form-create input').prop('readonly', true);
                    var no=1;
                    $.each(response.content, function (i, data) {
                        var written = data.written.split('-');
                        var printed = (data.printed != null) ? data.printed.split('-') : null;
                        var voided = (data.voided != null) ? data.voided.split('-') : null;
                        var date_rg = (data.date_rg != null) ? data.date_rg.split('-') : null;
                        var date_do = (data.date_do != null) ? data.date_do.split('-') : null;
                        var closed = (data.closed != null) ? data.closed.split('-') : null;

                        $('#claim-create-no').val(data.cl_no);
                        $('#claim-create-branch').val(data.branch);
                        $('#claim-create-warehouse').val(data.warehouse);
                        $('#claim-create-priod').val(data.period);
                        $('#claim-create-date').val(`${written[2]}/${written[1]}/${written[0]}`);
                        $('#claim-create-pono').val(data.po_no);
                        $('#claim-create-refno').val(data.ref_no);
                        // $('#claim-create-delivery').val(data.);
                        // $('#claim-create-delivery2').val(data.);
                        $('#claim-create-remark').val(data.remark);
                        $('#claim-create-customercode').val(data.cust_code);
                        $('#claim-create-customerdoaddr').val(data.do_addr);
                        $('#claim-create-customername').val(data.company);
                        $('#claim-create-customeraddr1').val(data.addr1);
                        $('#claim-create-customeraddr2').val(data.addr2);
                        $('#claim-create-customeraddr3').val(data.addr3);
                        $('#claim-create-customeraddr4').val(data.addr4);
                        $('#claim-create-user').val(data.operator);
                        $('#claim-create-printed').val((printed != null) ? `${printed[2]}/${printed[1]}/${printed[0]}` : '//');
                        $('#claim-create-voided').val((voided != null) ? `${voided[2]}/${voided[1]}/${voided[0]}` : '//');
                        $('#claim-create-rgdate').val((date_rg != null) ? `${date_rg[2]}/${date_rg[1]}/${date_rg[0]}` : '//');
                        $('#claim-create-dodate').val((date_do != null) ? `${date_do[2]}/${date_do[1]}/${date_do[0]}` : '//');
                        $('#claim-create-closed').val((closed != null) ? `${closed[2]}/${closed[1]}/${closed[0]}` : '//');
                        $('#claim-create-rrno').val((data.rr_no != null) ? data.rr_no : '//');
                        tbl_create.row.add([
                            no,
                            data.itemcode,
                            data.part_no,
                            data.descript,
                            data.unit_item,
                            data.qty,
                            data.qty_rg,
                            data.notes
                        ]);
                        no++;
                    });
                    tbl_create.draw();
                }else{
                    Swal.fire({
                        title: 'Warning!',
                        text: response.message,
                        icon: 'warning'
                    });
                }
            }
        );
    });
    $(document).on('click', '.claim-act-edit', function () {
        var id = $(this).data('clno');
        ajax(
            "{{route('tms.warehouse.claim_entry.read')}}",
            "POST",
            {"cl_no": id, "cek":"all"},
            function (response) {
                response = response.responseJSON;
                if (response.message != null) {
                    Swal.fire({
                        title: 'Warning!',
                        text: response.message,
                        icon: 'warning'
                    });
                }else{
                    edit();
                }
            }
        );
        function edit() {
            ajax(
                "{{route('tms.warehouse.claim_entry.read')}}",
                "POST",
                {"cl_no": id},
                function (response) {
                    response = response.responseJSON;
                    if (response.status == true) {
                        $('#claim-btn-create-submit').text('Update');
                        modalAction('#claim-modal-create');
                        var no=1;
                        $.each(response.content, function (i, data) {
                            var written = data.written.split('-');
                            var printed = (data.printed != null) ? data.printed.split('-') : null;
                            var voided = (data.voided != null) ? data.voided.split('-') : null;
                            var date_rg = (data.date_rg != null) ? data.date_rg.split('-') : null;
                            var date_do = (data.date_do != null) ? data.date_do.split('-') : null;
                            var closed = (data.closed != null) ? data.closed.split('-') : null;

                            $('#claim-create-no').val(data.cl_no);
                            $('#claim-create-branch').val(data.branch);
                            $('#claim-create-warehouse').val(data.warehouse);
                            $('#claim-create-priod').val(data.period);
                            $('#claim-create-date').val(`${written[2]}/${written[1]}/${written[0]}`);
                            $('#claim-create-pono').val(data.po_no);
                            $('#claim-create-refno').val(data.ref_no);
                            $('#claim-create-remark').val(data.remark);
                            $('#claim-create-customercode').val(data.cust_code);
                            $('#claim-create-customerdoaddr').val(data.do_addr);
                            $('#claim-create-customername').val(data.company);
                            $('#claim-create-customeraddr1').val(data.addr1);
                            $('#claim-create-customeraddr2').val(data.addr2);
                            $('#claim-create-customeraddr3').val(data.addr3);
                            $('#claim-create-customeraddr4').val(data.addr4);
                            // $('#claim-create-user').val(data.operator);
                            $('#claim-create-printed').val((printed != null) ? `${printed[2]}/${printed[1]}/${printed[0]}` : '');
                            $('#claim-create-voided').val((voided != null) ? `${voided[2]}/${voided[1]}/${voided[0]}` : '');
                            $('#claim-create-rgdate').val((date_rg != null) ? `${date_rg[2]}/${date_rg[1]}/${date_rg[0]}` : '');
                            $('#claim-create-dodate').val((date_do != null) ? `${date_do[2]}/${date_do[1]}/${date_do[0]}` : '');
                            $('#claim-create-closed').val((closed != null) ? `${closed[2]}/${closed[1]}/${closed[0]}` : '');
                            $('#claim-create-rrno').val((data.rr_no != null) ? data.rr_no : '');
                            tbl_create.row.add([
                                no,
                                data.itemcode,
                                data.part_no,
                                data.descript,
                                data.unit_item,
                                data.qty,
                                data.qty_rg,
                                data.notes
                            ]);
                            no++;
                        });
                        tbl_create.draw(false);
                    }else{
                        Swal.fire({
                            title: 'Warning!',
                            text: response.message,
                            icon: 'warning'
                        });
                    }
                }
            );
        }
    });
    $(document).on('click', '.claim-act-do', function () {
        var id = $(this).data('clno');
        var ref = $(this).data('refno');
        ajax(
            "{{route('tms.warehouse.claim_entry.read')}}",
            "POST",
            {"cl_no": id, "cek":"deliver"},
            function (response) {
                response = response.responseJSON;
                if (response.message != null) {
                    Swal.fire({
                        title: 'Warning!',
                        text: response.message,
                        icon: 'warning'
                    });
                }else{
                    modalAction('#claim-modal-status-do');
                    $('#claim-status-do-no').val(id);
                    $('#claim-status-do-refno').val(ref);
                    delivery_order();
                }
            }
        );
        function delivery_order() {
            $('#form-status-do').on('submit', function () {
                ajax(
                    "{{ route('tms.warehouse.claim_entry.delivery_order.do') }}",
                    "POST",
                    {"cl_no": $('#claim-status-do-no').val(), "date_sj": $('#claim-status-do-datejs').val()},
                    function (response) {
                        response = response.responseJSON;
                        if (response.status == true) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success'
                            }).then(()=>{
                                modalAction('#claim-modal-status-do', 'hide');
                                tbl_index.ajax.reload();
                            });
                        }else{
                            Swal.fire({
                                title: 'Warning!',
                                text: response.message,
                                icon: 'warning'
                            });
                        }
                    }
                );
            });
        }
    });
    $(document).on('click', '.claim-act-undo', function () {
        var id = $(this).data('clno');
        ajax(
            "{{route('tms.warehouse.claim_entry.read')}}",
            "POST",
            {"cl_no": id, "cek":"deliver"},
            function (response) {
                response = response.responseJSON;
                if (response.message != null) {
                    Swal.fire({
                        title: 'Warning!',
                        text: response.message,
                        icon: 'warning'
                    });
                }else{
                    Swal.fire({
                        title: `Do you want to undelivered Claim no. ${id}, now ?`,
                        input: 'text',
                        inputPlaceholder: 'Type your note here...',
                        showCancelButton: true,
                        confirmButtonText: `Yes, undelivered it!`,
                        confirmButtonColor: '#DC3545',
                        icon: 'warning',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'You need to write something!'
                            }
                        }
                    }).then((result) => {
                        if (result.value != "" && result.value != undefined) {
                            var note = result.value;
                            ajax(
                                "{{route('tms.warehouse.claim_entry.delivery_order.undo')}}",
                                "POST",
                                {"cl_no": id, "note": note},
                                function (response) {
                                    response = response.responseJSON;
                                    if (response.status == true) {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.message,
                                            icon: 'success'
                                        }).then(()=>{
                                            tbl_index.ajax.reload();
                                        });
                                    }else{
                                        Swal.fire({
                                            title: 'Warning!',
                                            text: response.message,
                                            icon: 'warning'
                                        });
                                    }
                                }
                            );
                        }
                    });
                }
            }
        );
    });
    $(document).on('click', '.claim-act-rg', function () {
        var id = $(this).data('clno');
        var po = $(this).data('pono');
        var customer = $(this).data('customer').split('|');
        ajax(
            "{{route('tms.warehouse.claim_entry.read')}}",
            "POST",
            {"cl_no": id, "cek":"receive"},
            function (response) {
                response = response.responseJSON;
                if (response.message != null) {
                    Swal.fire({
                        title: 'Warning!',
                        text: response.message,
                        icon: 'warning'
                    });
                }else{
                    ajax(
                        "{{route('tms.warehouse.claim_entry.header_tools')}}",
                        "POST",
                        {"cl_no": id, "type":"cek_rg"},
                        function (response) {
                            response = response.responseJSON;
                            if (response.content != null) {
                                modalAction('#claim-modal-status-rg-complete');
                                $('#claim-status-rg-complete-customer-id').val(customer[0]);
                                $('#claim-status-rg-complete-customer-name').val(customer[1]);
                                $('#claim-status-rg-complete-no').val(id);
                                $('#claim-status-rg-complete-pono').val(po);
                                tbl_rg_complete = $('#claim-datatables-rg-complete').DataTable({
                                    processing: true,
                                    serverSide: true,
                                    destroy: true,
                                    ajax: {
                                        url: "{{route('tms.warehouse.claim_entry.get_rg')}}",
                                        method: 'POST',
                                        data: {"cl_no": id},
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    },
                                    columns: [
                                        {data:'id', name: 'id', visible: false},
                                        {data:'cl_no', name: 'cl_no'},
                                        {data:'itemcode', name: 'itemcode'},
                                        {data:'descript', name: 'descript'},
                                        {data:'unit', name: 'unit'},
                                        {data:'qty_rg', name: 'qty_rg'},
                                        {data:'doc_no', name: 'doc_no'},
                                        {data:'creation_at', name: 'creation_at', className: "text-center"},
                                    ],
                                    lengthChange: false,
                                    searching: false,
                                    paging: false,
                                    ordering: false,
                                    scrollY: "200px",
                                    scrollCollapse: true,
                                    fixedHeader:true,
                                });
                                deleteRG();
                            }else{
                                modalAction('#claim-modal-status-rg');
                                $('#claim-status-rg-customer-id').val(customer[0]);
                                $('#claim-status-rg-customer-name').val(customer[1]);
                                $('#claim-status-rg-no').val(id);
                                $('#claim-status-rg-pono').val(po);
                                getData();
                                $('#claim-datatables-rg tbody').off('click', 'tr').on('click', 'tr', function () {
                                    var data = tbl_rg.row(this).data();
                                    if (data != undefined) {
                                        if ($(this).hasClass('selected')) {
                                            $(this).removeClass('selected');
                                            $('#claim-btn-tobe-rg').prop('disabled', true);
                                        }else {
                                            tbl_rg.$('tr.selected').removeClass('selected');
                                            $(this).addClass('selected');
                                            $('#claim-btn-tobe-rg').removeAttr('disabled');
                                        }
                                    }
                                });
                            }
                        }
                    );
                }
            }
        );
        $('#claim-datatables-rg-complete tbody').off('click', 'tr').on('click', 'tr', function () {
            var data = tbl_rg_complete.row(this).data();
            if (data != undefined) {
                $(this).toggleClass('selected');
                if ($('#claim-datatables-rg-complete tbody tr').hasClass('selected')) {
                    $('#claim-btn-rg-complete-delete').removeAttr('disabled');
                }else{
                    $('#claim-btn-rg-complete-delete').prop('disabled', true);
                }
            }
        });
        function getData() {
            ajax(
                "{{route('tms.warehouse.claim_entry.read')}}",
                "POST",
                {"cl_no": id},
                function (response) {
                    response = response.responseJSON;
                    if (response.status == true) {
                        var no=1;
                        $.each(response.content, function (i, data) {
                            if (data.qty == data.qty_rg) {
                                return;
                            }
                            tbl_rg.row.add([
                                no,
                                data.itemcode,
                                data.descript,
                                data.unit_item,
                                data.qty,
                                data.qty_rg,
                                data.qty - data.qty_rg
                            ]);
                            no++;
                        });
                        tbl_rg.draw();
                        addQty();
                        onSubmit();
                    }
                }
            );
        }
        function addQty() {
            $('#claim-btn-tobe-rg').on('click', function () {
                modalAction('#claim-modal-status-rgqty');
                var data = tbl_rg.row('.selected').data();
                var qty_sj = parseInt(data[4]) - parseInt(data[5]);
                $('#claim-status-rgqty-id').val(data[0]);
                $('#claim-status-rgqty-qtyclaim').val(qty_sj);
                $('#claim-status-rgqty-qty').val(data[6]);
            });
            $('#form-status-rgqty').on('submit', function () {
                var data = tbl_rg.row('.selected').data();
                if (parseInt($('#claim-status-rgqty-qtyclaim').val()) < parseInt($('#claim-status-rgqty-qty').val())) {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Invalid quantity!',
                        icon: 'warning'
                    });
                }else{
                    var idx = parseInt($('#claim-status-rgqty-id').val()) - 1;
                    tbl_rg.row( idx ).data([
                        $('#claim-status-rgqty-id').val(),
                        data[1],
                        data[2],
                        data[3],
                        data[4],
                        data[5],
                        $('#claim-status-rgqty-qty').val(),
                    ]).draw(false);
                    modalAction('#claim-modal-status-rgqty', 'hide');
                    $(this).find('form').trigger('reset');
                }
            });
        }
        $('#claim-modal-status-rgqty').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });
        
        function onSubmit() {
            $('#form-status-rg').off('submit').on('submit', function () {
                var data = {
                    "cl_no": $('#claim-status-rg-no').val(),
                    "date": $('#claim-status-rg-date').val(),
                    "doc_no": $('#claim-status-rg-docno').val(),
                    "items": tbl_rg.rows().data().toArray(),
                }
                ajax(
                    "{{ route('tms.warehouse.claim_entry.receive_good.rg') }}",
                    "POST",
                    data,
                    function (response) {
                        response = response.responseJSON;
                        if (response.status == true) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success'
                            }).then(()=>{
                                modalAction('#claim-modal-status-rg', 'hide');
                                tbl_index.ajax.reload();
                            });
                        }else{
                            Swal.fire({
                                title: 'Warning!',
                                text: response.message,
                                icon: 'warning'
                            });
                        }
                    }
                );
            });
        }
        function deleteRG() {
            $('#claim-btn-rg-complete-delete').off('click').on('click', function () {
                var items = tbl_rg_complete.rows('.selected').data().toArray();
                var data = {
                    "cl_no": $('#claim-status-rg-complete-no').val(),
                    "items": items
                };
                ajax(
                    "{{ route('tms.warehouse.claim_entry.receive_good.unrg') }}",
                    "POST",
                    data,
                    function (response) {
                        response = response.responseJSON;
                        if (response.status == true) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success'
                            }).then(()=>{
                                modalAction('#claim-modal-status-rg-complete', 'hide');
                                tbl_index.ajax.reload();
                            });
                        }else{
                            Swal.fire({
                                title: 'Warning!',
                                text: response.message,
                                icon: 'warning'
                            });
                        }
                    }
                );
            });
        } 
    });
    $(document).on('shown.bs.modal', '#claim-modal-status-rg-complete', function () {
        tbl_rg_complete.columns.adjust().draw();
    });
    $(document).on('hidden.bs.modal', '#claim-modal-status-rg-complete', function () {
        tbl_rg_complete.clear().draw();
    });
    $(document).on('click', '.claim-act-voided', function () {
        var id = $(this).data('clno');
        ajax(
            "{{route('tms.warehouse.claim_entry.read')}}",
            "POST",
            {"cl_no": id, "cek":"all"},
            function (response) {
                response = response.responseJSON;
                if (response.message != null) {
                    Swal.fire({
                        title: 'Warning!',
                        text: response.message,
                        icon: 'warning'
                    });
                }else{
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `Do you want to void Claim no. ${id}, now ?`,
                        showCancelButton: true,
                        confirmButtonText: `Yes, Void it!`,
                        confirmButtonColor: '#DC3545',
                        icon: 'warning',
                    }).then((result) => {
                        if (result.value == true) {
                            ajax(
                                "{{route('tms.warehouse.claim_entry.void.voided')}}",
                                "POST",
                                {"cl_no": id},
                                function (response) {
                                    response = response.responseJSON;
                                    if (response.status == true) {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.message,
                                            icon: 'success'
                                        }).then(()=>{
                                            tbl_index.ajax.reload();
                                        });
                                    }else{
                                        Swal.fire({
                                            title: 'Warning!',
                                            text: response.message,
                                            icon: 'warning'
                                        });
                                    }
                                }
                            );
                        }
                    });
                }
            }
        );
    });
    $(document).on('click', '.claim-act-unvoided', function () {
        var id = $(this).data('clno');
        Swal.fire({
            title: `Do you want to unvoid Claim no. ${id}, now ?`,
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
        }).then((result) => {
            if (result.value != "" && result.value != undefined) {
                var note = result.value;
                ajax(
                    "{{route('tms.warehouse.claim_entry.void.unvoided')}}",
                    "POST",
                    {"cl_no": id, "note": note},
                    function (response) {
                        response = response.responseJSON;
                        if (response.status == true) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success'
                            }).then(()=>{
                                tbl_index.ajax.reload();
                            });
                        }else{
                            Swal.fire({
                                title: 'Warning!',
                                text: response.message,
                                icon: 'warning'
                            });
                        }
                    }
                );
            }
        });
    });
    $(document).on('click', '.claim-act-unclose', function () {
        var id = $(this).data('clno');
        ajax(
            "{{route('tms.warehouse.claim_entry.read')}}",
            "POST",
            {"cl_no": id, "cek":"unclose"},
            function (response) {
                response = response.responseJSON;
                if (response.message != null) {
                    Swal.fire({
                        title: 'Warning!',
                        text: response.message,
                        icon: 'warning'
                    });
                }else{
                    Swal.fire({
                        title: `Do you want to unclosed Claim no. ${id}, now ?`,
                        input: 'text',
                        inputPlaceholder: 'Type your note here...',
                        showCancelButton: true,
                        confirmButtonText: `Yes, unclose it!`,
                        confirmButtonColor: '#DC3545',
                        icon: 'warning',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'You need to write something!'
                            }
                        }
                    }).then((result) => {
                        if (result.value != "" && result.value != undefined) {
                            var note = result.value;
                            ajax(
                                "{{route('tms.warehouse.claim_entry.close.unclosed')}}",
                                "POST",
                                {"cl_no": id, "note": note},
                                function (response) {
                                    response = response.responseJSON;
                                    if (response.status == true) {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.message,
                                            icon: 'success'
                                        }).then(()=>{
                                            tbl_index.ajax.reload();
                                        });
                                    }else{
                                        Swal.fire({
                                            title: 'Warning!',
                                            text: response.message,
                                            icon: 'warning'
                                        });
                                    }
                                }
                            );
                        }
                    });
                }
            }
        );
    });

    $(document).on('click', '.claim-act-report', function () {
        var encrypt = btoa(`${$(this).data('clno')}`);
        var url = "{{route('tms.warehouse.claim_entry.report')}}?print=" + encrypt;
        window.open(url, '_blank');
    });

    $(document).on('click', '.claim-act-log', function () {
        var id = $(this).data('clno');
        modalAction('#claim-modal-log');
        var column = [
            {data: 'date_written', name: 'date_written'},
            {data: 'time_written', name: 'time_written'},
            {data: 'status_change', name: 'status_change'},
            {data: 'user', name: 'user'},
            {data: 'note', name: 'note'}
        ];
        tbl_log = $('#claim-datatables-log').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{ route('tms.warehouse.claim_entry.header_tools') }}",
                method: 'POST',
                data: {"type":"log", "cl_no":id},
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
        // var tbl_log = $('#claim-datatables-log').DataTable(dataTables(
        //     "{{ route('tms.warehouse.claim_entry.header_tools') }}",
        //     "POST",
        //     {"type":"log", "cl_no":id},
        //     column
        // ));
    });
    $(document).on('shown.bs.modal', '#claim-modal-log', function () {
        tbl_log.columns.adjust().draw();
        console.log('p');
    });

    $(document).on('hidden.bs.modal', '#claim-modal-create', function () {
        $(this).find('form').trigger('reset');
        tbl_create.clear().draw(false);
        hideShow('#item-button-div', false);
        hideShow('#claim-btn-create-submit', false);
        $('#claim-form-create input').not('.readonly-first').removeAttr('readonly');
        $('#claim-btn-create-submit').text('Simpan');
    });
    $(document).on('shown.bs.modal', '#claim-modal-create', function () {
        tbl_create.columns.adjust().draw();
    });
    $(document).on('shown.bs.modal', '#claim-modal-status-rg', function () {
        $('#claim-status-rg-docno').focus();
        tbl_rg.columns.adjust().draw();
    });
    $(document).on('hidden.bs.modal', '#claim-modal-status-rg', function () {
        $(this).find('form').trigger('reset');
        tbl_rg.clear().draw();
    });

    $(document).on('submit', '#claim-form-create', function () {
        if (tbl_create.rows().data().toArray().length > 0) {
            $('#claim-btn-create-submit').prop('disabled', true);
            var route;
            ajax(
                "{{route('tms.warehouse.claim_entry.read')}}",
                "POST",
                {"cl_no": $('#claim-create-no').val()},
                function (response) {
                    var route;
                    response = response.responseJSON;
                    if (response.status == true) {
                        route = "{{route('tms.warehouse.claim_entry.update')}}";
                    }else{
                        route = "{{route('tms.warehouse.claim_entry.create')}}";
                    }
                    postData(route);
                }
            );

            function postData(route) {
                var data = {
                    "cl_no": $('#claim-create-no').val(),
                    "branch": $('#claim-create-branch').val(),
                    "warehouse": $('#claim-create-warehouse').val(),
                    "priod": $('#claim-create-priod').val(),
                    "date": $('#claim-create-date').val(),
                    "pono": $('#claim-create-pono').val(),
                    "refno": $('#claim-create-refno').val(),
                    "delivery": $('#claim-create-delivery').val(),
                    "delivery2": $('#claim-create-delivery2').val(),
                    "remark": $('#claim-create-remark').val(),
                    "customercode": $('#claim-create-customercode').val(),
                    "customerdoaddr": $('#claim-create-customerdoaddr').val(),
                    "customername": $('#claim-create-customername').val(),
                    "customeraddr1": $('#claim-create-customeraddr1').val(),
                    "customeraddr2": $('#claim-create-customeraddr2').val(),
                    "customeraddr3": $('#claim-create-customeraddr3').val(),
                    "customeraddr4": $('#claim-create-customeraddr4').val(),
                    "user": $('#claim-create-user').val(),
                    "printed": $('#claim-create-printed').val(),
                    "voided": $('#claim-create-voided').val(),
                    "rgdate": $('#claim-create-rgdate').val(),
                    "dodate": $('#claim-create-dodate').val(),
                    "closed": $('#claim-create-closed').val(),
                    "rrno": $('#claim-create-rrno').val(),
                    "items": tbl_create.rows().data().toArray()
                };
                ajax(
                    route,
                    "POST",
                    data,
                    function (response) {
                        response = response.responseJSON;
                        if (response.status == true) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success'
                            }).then(function(){
                                // window.location.reload();
                                modalAction('#claim-modal-create', 'hide');
                                tbl_index.ajax.reload();
                                $('#claim-btn-create-submit').prop('disabled', true);
                            });
                        }
                    }
                );
            }
            
        }else{
            Swal.fire({
                title: 'warning!',
                text: 'Periksa kembali form Anda!',
                icon: 'warning'
            })
        }
    });

    const dataTables = (route, method, params=null, columns) => {
        const dtbl = {
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: route,
                method: method,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: params
            },
            columns: columns
        };
        return dtbl;
    }
    const ajax = (route, type, params=null, callback) => {
        $.ajax({
            url: route,
            type: type,
            dataType: "JSON",
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: params,
            error: function(response, status, x){
                $('#claim-btn-create-submit').removeAttr('disabled');
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
    const modalAction = (elementId=null, action='show') => {
        $(elementId).modal(action);
    }

    const hideShow = (elelemt=null, hide=true) => {
        if (hide == true) {
            $(elelemt).addClass('d-none');
        }else{
            $(elelemt).removeClass('d-none');
        }
    }

    const toRoman = (num) => {
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