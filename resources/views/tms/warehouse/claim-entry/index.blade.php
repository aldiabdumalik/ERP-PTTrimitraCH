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
    input[readonly] {
        background-color: #fff !important;
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
    .dataTables_scrollHeadInner {
        width: 100% !important;
    }
    .dataTables_scrollHeadInner table {
        width: 100% !important;
    }
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
            {data:'cl_no', name: 'cl_no'},
            {data:'cl_no', name: 'cl_no'},
            {data:'cl_no', name: 'cl_no'},
            {data:'cl_no', name: 'cl_no'},
            {data:'cl_no', name: 'cl_no'},
            {data:'cl_no', name: 'cl_no'},
            {data:'cl_no', name: 'cl_no'},
            {data:'cl_no', name: 'cl_no'},
        ]
    });
    var tbl_create = $('#claim-datatables-create').DataTable({
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "ordering": false,
        "scrollY": "200px",
        "scrollCollapse": true,
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
            response = response.responseJSON;
            $('#claim-create-no').val(response);
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
    }).datepicker("setDate",'now').on('changeDate', function(e) {
        var date = e.format(0, "yyyy-mm");
        $('#claim-create-priod').val(date);
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
            tbl_branch = $('#claim-datatables-branch').DataTable(dataTables(
                "{{ route('tms.warehouse.claim_entry.header_tools') }}",
                "POST",
                params,
                column
            ));
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
            tbl_wh = $('#claim-datatables-warehouse').DataTable(dataTables(
                "{{ route('tms.warehouse.claim_entry.header_tools') }}",
                "POST",
                params,
                column
            ));
            $('#claim-datatables-warehouse').off('click').on('click', 'tr', function () {
                modalAction('#claim-modal-warehouse', 'hide');
                var data = tbl_wh.row(this).data();
                $('#claim-create-warehouse').val(data.code);
            });
        }
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
        }
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
        }
    });

    $(document).on('click', '#claim-btn-add-item', function () {
        modalAction('#claim-modal-additem');
    });
    $(document).on('hidden.bs.modal', '#claim-modal-additem', function () {
        $(this).find('form').trigger('reset');
    });
    $(document).on('click', '#claim-btn-additem-submit', function () {
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
                tbl_item = $('#claim-datatables-items').DataTable(dataTables(
                    "{{ route('tms.warehouse.claim_entry.header_tools') }}",
                    "POST",
                    params,
                    column
                ));
            }
            $('#claim-datatables-items').off('click').on('click', 'tr', function () {
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

    $(document).on('hidden.bs.modal', '#claim-modal-create', function () {
        $(this).find('form').trigger('reset');
    });

    $(document).on('submit', '#claim-form-create', function () {
        // $('#claim-btn-create-submit').prop('disabled', true);
        var route = null;
        ajax(
            "{{route('tms.warehouse.claim_entry.read')}}",
            "POST",
            {"cl_no": $('#claim-create-no').val()},
            function (response) {
                response = response.responseJSON;
                if (response.status == true) {
                    route = "{{route('tms.warehouse.claim_entry.update')}}";
                }else{
                    route = "{{route('tms.warehouse.claim_entry.create')}}";
                }
            }
        );
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
            "customername": $('#claim-create-remark').val(),
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
                        text: response.msg,
                        icon: 'success'
                    }).then(function(){
                        window.location.reload();
                    });
                }
            }
        );
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
                $(this).find('button[disabled=true]').removeAttr('disabled');
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
    @if(\Session::has('msg'))
    setTimeout(function () {
        Swal.fire({
            title: 'Warning!',
            text: "{{\Session::get('msg')}}",
            icon: 'warning'
        }).then(function () {
            window.close();
        });
    }, 1000);
    @endif
</script>
@endpush