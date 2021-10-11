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
@include('tms.warehouse.cust-price.modal.create.itemTableAdd')
@include('tms.warehouse.cust-price.modal.create.itemFormAdd')
@include('tms.warehouse.cust-price.modal.log.tableLog')

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
            modalAction('#custprice-modal-index').then(() => {
                ajaxCall({route: "{{route('tms.warehouse.cust_price.header')}}", method: "POST", data: {type: 'currency'}}).then(resolve => {
                    var data = resolve.content;
                    $.each(data, function (i, valas) {
                        $('#custprice-create-valas').append($('<option>', { 
                            value: valas.valas,
                            text : valas.valas 
                        }));
                    });
                    $('#custprice-create-valas').val('IDR');
                });
            });
        });

        var tbl_item = $('#custprice-datatables-index').DataTable(tbl_attr([0,4,5]));
        
        $('#custprice-modal-index').on('shown.bs.modal', function () {
            adjustDraw(tbl_item);
        });

        $('#custprice-datatables-index tbody').off('click', 'tr').on('click', 'tr', function () {
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
                    ajaxCall({route: "{{route('tms.warehouse.cust_price.header')}}", method: "POST", data: {type: "customer"}}).then(resolve => {
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

        $('#custprice-modal-customer').on('shown.bs.modal', function () {
            adjustDraw(tbl_customer);
        });

        $('#custprice-modal-index').on('hidden.bs.modal', function () {
            resetForm();
        });

        var tbl_item_add;
        function getTblItem(cust_id) {
            tbl_item_add = $('#custprice-datatables-customer-item').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('tms.warehouse.cust_price.header')}}",
                    method: "POST",
                    data: {
                        type: "items",
                        cust_id: cust_id
                    },
                    headers: token_header
                },
                columns: [
                    {data:'itemcode', name: 'itemcode', className: "text-center align-middle"},
                    {data:'part_no', name: 'part_no', className: "text-left align-middle"},
                    {data:'descript', name: 'descript', className: "text-left align-middle"},
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
        $('#custprice-datatables-customer').off('click', 'tr').on('click', 'tr', function () {
            var data = tbl_customer.row(this).data();
            var cust_id = data[0];
            modalAction('#custprice-modal-customer', 'hide').then(resolve => {
                $('#custprice-create-customercode').val(data[0]);
                $('#custprice-create-customername').val(data[1]);
                if (data[0] === "A01") {
                    $('#custprice-create-priceby').val('DATE');
                }else{
                    $('#custprice-create-priceby').val('SO');
                }
                modalAction('#custprice-modal-item').then(function () {
                    getTblItem(cust_id);
                });
            });
        });

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
                modalAction('#custprice-modal-item').then(function () {
                    tbl_item_add.destroy();
                    getTblItem(cust_id);
                });
            }
        });

        $('#custprice-datatables-customer-item').off('click', 'tr').on('click', 'tr', function () {
            var id = this.id;
            var index = $.inArray(id, item_select);

            if ( index === -1 ) {
                item_select.push( id );
            } else {
                item_select.splice( index, 1 );
            }

            $(this).toggleClass('selected');
        });

        $(document).on('click', '#custprice-btn-item-submit', function () {
            ajaxCall({route: "{{route('tms.warehouse.cust_price.header')}}", method: "POST", data: {type: "items_selected", items: item_select}}).then(resolve => {
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
                            `<input type="number" id="new_price" class="form-control form-control-sm text-right" value="0.00">`,
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

            ajaxCall({route: "{{route('tms.warehouse.cust_price.header')}}", method: "POST", data: {type: "validation", cust_id: cust, active: date} }).then(resolve => {
                var status = resolve.status;
                if (status == true) {
                    modalAction('#custprice-modal-index').then(resolve => {
                        ajaxCall({route: route, method: "GET"}).then(resolve => {
                            if (resolve.status == true) {
                                var no = 1;
                                var cust_id, cust_name, valas, active_date, created, user, posted, voided, printed;
                                $.each(resolve.content, function (i, data) {
                                    var price_new = (data.price_new == null ? "0.00" : addZeroes(String(data.price_new)));
                                    tbl_item.row.add([
                                        no,
                                        data.item_code,
                                        data.PART_NO,
                                        data.DESCRIPT,
                                        `<input type="text" id="new_price" class="form-control form-control-sm text-right" value="${price_new}">`,
                                        // (data.price_new == null ? "0.00" : currency(addZeroes(String(data.price_new)))),
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
                                $('#custprice-create-activedate').prop('readonly', true);
                                $('#custprice-create-customercode').prop('readonly', true);
                            }
                        });
                    });
                }
            });
        });

        $(document).on('submit', '#custprice-form-index', function () {
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
                items: items_fix
            };
            // Cek
            var route = "{{route('tms.warehouse.cust_price.detail', [':cust', ':date'])}}";
            route  = route.replace(':cust', $('#custprice-create-customercode').val());
            route  = route.replace(':date', $('#custprice-create-activedate').val().split("/").reverse().join("-"));
            ajaxCall({route: route, method: "GET"}).then(resolve => {
                var rou;
                if (resolve.content.length <= 0) {
                    rou = "{{route('tms.warehouse.cust_price.save')}}";
                }else{
                    rou = "{{route('tms.warehouse.cust_price.update', [':cust', ':active'])}}";
                    rou  = rou.replace(':cust', $('#custprice-create-customercode').val());
                    rou  = rou.replace(':active', $('#custprice-create-activedate').val().split("/").reverse().join("-"));
                }
                submit(rou, data);
            });
        });

        function submit(route, data) {
            var method = (route == "{{route('tms.warehouse.cust_price.save')}}" ? "POST" : "PUT");
            return ajaxCall({route: route, method: method, data: data}).then(resolve => {
                var msg = resolve.message;
                if (resolve.status == true) {
                    modalAction('#custprice-modal-index', 'hide');
                    Swal.fire({
                        title: 'Notification',
                        text: msg,
                        icon: 'success'
                    }).then(answer => {
                        index_data.then(resolve => {
                            resolve.ajax.reload();
                        });
                    });
                }
            });
        }

        $(document).on('click', '.custprice-act-voided', function () {
            var cust = $(this).data('custid');
            var date = $(this).data('activedate');
            ajaxCall({route: "{{route('tms.warehouse.cust_price.voided')}}", method: "POST", data: {cust_id: cust, date: date}}).then(resolve => {
                var msg = resolve.message;
                Swal.fire({
                    title: 'Notification',
                    text: msg,
                    icon: 'success'
                }).then(answer => {
                    index_data.then(resolve => {
                        resolve.ajax.reload();
                    });
                });
            });
        });

        $(document).on('click', '.custprice-act-unvoided', function () {
            var cust = $(this).data('custid');
            var date = $(this).data('activedate');
            Swal.fire({
                title: `Do you want to unvoid Cust Price, now ?`,
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
                    ajaxCall({route: "{{route('tms.warehouse.cust_price.unvoided')}}", method: "POST", data: {cust_id: cust, date: date, note: note}}).then(resolve => {
                        var msg = resolve.message;
                        Swal.fire({
                            title: 'Notification',
                            text: msg,
                            icon: 'success'
                        }).then(answer => {
                            index_data.then(resolve => {
                                resolve.ajax.reload();
                            });
                        });
                    });
                }
            });
        });

        $(document).on('click', '.custprice-act-posted', function () {
            var cust = $(this).data('custid');
            var date = $(this).data('activedate');
            ajaxCall({route: "{{route('tms.warehouse.cust_price.posted')}}", method: "POST", data: {cust_id: cust, date: date}}).then(resolve => {
                var msg = resolve.message;
                Swal.fire({
                    title: 'Notification',
                    text: msg,
                    icon: 'success'
                }).then(answer => {
                    index_data.then(resolve => {
                        resolve.ajax.reload();
                    });
                });
            });
        });

        $(document).on('click', '.custprice-act-unposted', function () {
            var cust = $(this).data('custid');
            var date = $(this).data('activedate');
            Swal.fire({
                title: `Do you want to unposted now ?`,
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
                    ajaxCall({route: "{{route('tms.warehouse.cust_price.unposted')}}", method: "POST", data: {cust_id: cust, date: date, note: note}}).then(resolve => {
                        var msg = resolve.message;
                        Swal.fire({
                            title: 'Notification',
                            text: msg,
                            icon: 'success'
                        }).then(answer => {
                            index_data.then(resolve => {
                                resolve.ajax.reload();
                            });
                        });
                    });
                }
            });
        });
        
        var tbl_log;
        $(document).on('click', '.custprice-act-log', function () {
            var cust = $(this).data('custid');
            var date = $(this).data('activedate');
            modalAction('#custprice-modal-log').then(resolve => {
                tbl_log = $('#custprice-datatables-log').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    searching: false,
                    ajax: {
                        url: "{{route('tms.warehouse.cust_price.header')}}",
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
            $('#custprice-create-entrydate').val("{{date('d/m/Y')}}");
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