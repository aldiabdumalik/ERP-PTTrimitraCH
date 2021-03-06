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
        var index_header = [
            {title: 'DO No', name: 'do_no', data: 'do_no'},
            {title: 'Date', name: 'delivery_date', data: 'delivery_date'},
            {title: 'Posted', name: 'posted_date', data: 'posted_date'},
            {title: 'Finish', name: 'finished_date', data: 'finished_date'},
            {title: 'Voided', name: 'voided_date', data: 'voided_date'},
            {title: 'Reff No', name: 'ref_no', data: 'ref_no'},
            {title: 'DN No', name: 'dn_no', data: 'dn_no'},
            {title: 'PO No', name: 'po_no', data: 'po_no'},
            {title: 'Customer', name: 'cust_id', data: 'cust_id'},
            {title: 'Action', name: 'action', data: 'action'}
        ];

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

    const get_tbl_item = () => {
        return new Promise(function(resolve, reject) {
            var customercode = ($('#do-create-customercode').val() == "") ? null : $('#do-create-customercode').val().toUpperCase();
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

    var obj_merge_additem = {...obj_tbl, ...{
        "columnDefs": [{
            "targets": [0,4,5,6],
            "createdCell":  function (td, cellData, rowData, row, col) {
                $(td).addClass('text-right');
            }
        }]
    }};
    var tbl_additem = $('#do-datatables-create').DataTable(obj_merge_additem);

    $('#do-btn-modal-create').on('click', function () {
        modalAction('#do-modal-create').then(function (resolve) {
            hideShow('#do-btn-create-reset', false);
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
                    // var refno = `DO/${resText.substr(resText.length - 3)}/${toRoman(currentMonth)}/${now.getFullYear()}`;
                    // $('#do-create-refno').val(refno);
                    $('#do-create-date').datepicker("setDate",'now');
                });
            resolve.on('shown.bs.modal', () => {
                tbl_additem.columns.adjust().draw();
            });
        });
    });
    $('#do-modal-create').on('hidden.bs.modal', () => {
        $('#do-form-create').trigger('reset');
        tbl_additem.clear().draw(false);
        hideShow('#item-button-div', false);
        hideShow('#do-btn-create-submit', false);
        hideShow('#do-btn-revise', true);
        hideShow('#do-btn-create-reset', true);
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
        // var refno = `DO/${$('#do-create-no').val().substr($('#do-create-no').val().length - 3)}/${toRoman(bln)}/${thn}`;
        // $('#do-create-refno').val(refno);
    });

    $(document).on('keypress', '#do-create-customercode', function (e) {
        let cust_id = $('#do-create-customercode').val().toUpperCase();
        if (e.keyCode == 13) {
            resetCreateForm();
            if (cust_id.length >= 3) {
                ajaxWithPromise({route: "{{route('tms.warehouse.do_entry.header_tools')}}", method: "POST", data: {type: "customerclick", cust_code: cust_id}}).then(resolve => {
                    if (resolve.content) {
                        let data = resolve.content;
                        $('#do-create-customercode').val(data.code);
                        $('#do-create-customername').val(data.name);
                        $('#do-create-customergroup').val(data.cg);
                        if (data.code == 'H03' || data.code == 'H10' || data.code == 'Y01') {
                            $('#do-create-so').prop('readonly', false);
                            $('#do-create-sso').prop('readonly', true);
                        }else{
                            $('#do-create-sso').prop('readonly', false);
                            $('#do-create-so').prop('readonly', true);
                        }
                    }
                });
            }

            return false;
        }
        // var tbl_customer;
        // if(e.which == 13) {
        //     modalAction('#do-modal-customer').then((resolve) => {
        //         var params = {"type": "customer"}
        //         var column = [
        //             {data: 'code', name: 'code'},
        //             {data: 'name', name: 'name'},
        //         ];
        //         tbl_customer = $('#do-datatables-customer').DataTable({
        //             processing: true,
        //             serverSide: true,
        //             destroy: true,
        //             ajax: {
        //                 url: "{{ route('tms.warehouse.do_entry.header_tools') }}",
        //                 method: 'POST',
        //                 data: params,
        //                 headers: token_header
        //             },
        //             columns: column,
        //         });
        //     });
        // }
        // $('#do-datatables-customer').off('click', 'tr').on('click', 'tr', function () {
        //     modalAction('#do-modal-customer', 'hide').then((resolve) => {
        //         resetCreateForm();
        //         var data = tbl_customer.row(this).data();
        //         $('#do-create-customercode').val(data.code);
        //         $('#do-create-customergroup').val(data.cg);
        //         if (data.code == 'H03' || data.code == 'H10' || data.code == 'Y01') {
        //             $('#do-create-so').prop('readonly', false);
        //             $('#do-create-sso').prop('readonly', true);
        //         }else{
        //             $('#do-create-sso').prop('readonly', false);
        //             $('#do-create-so').prop('readonly', true);
        //         }
        //         var params = {"type": "customerclick", "cust_code": data.code};
        //         ajax("{{ route('tms.warehouse.do_entry.header_tools') }}",
        //             "POST",
        //             params,
        //             (response) => {
        //             response = response.responseJSON;
        //                 $('#do-create-customername').val(response.content.name);
        //             });
        //     });
        // });
        // e.preventDefault();
        // return false;
    });
    // $('#do-create-customercode').on('input', delay(function (e) {
    //     let like = $(this).val().toUpperCase();
    //     if (like.length > 0) {
    //         ajaxWithPromise({route: "{{route('tms.warehouse.do_entry.header_tools')}}", method: "POST", data: {type: "customer_list", like:like}}).then(response => {
    //             $('#list').empty();
    //             $.each(response.content, function (id, data) {
    //                 $('#list').append($('<option/>', { 
    //                     value: data.code,
    //                     text : data.code 
    //                 }));
    //             })
    //         });
    //     }else{
    //         // tbl_item.clear().draw(false);
    //         $('#do-create-customername').val(null)
    //     }
    // }, 1000));

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
                            if (data.cust_id == $('#do-create-customercode').val().toUpperCase()) {
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
                                    $('#do-create-delivery').val(data.dn_date);
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
                            if (data.cust_id == $('#do-create-customercode').val().toUpperCase()) {
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

    $(document).on('click', '#do-btn-create-close', function () {
        // ajax("{{ route('tms.warehouse.do_entry.cancel_form') }}",
        //     "POST",
        //     {"do_no": $('#do-create-no').val()},
        //     (response) => {
        //         response = response.responseJSON;
        //         modalAction('#do-modal-create', 'hide')
        //     })
            modalAction('#do-modal-create', 'hide')
    });
    
    var tbl_items;
    $(document).on('click', '#do-btn-add-item', function () {
        var obj_class_right = {
            "columnDefs": [{
                "targets": [7, 8, 9],
                "createdCell":  function (td, cellData, rowData, row, col) {
                    $(td).addClass('text-right');
                }
            }]
        };
        obj_merge = {...obj_tbl, ...obj_class_right};
        tbl_items = $('#do-datatables-items').DataTable(obj_merge);

        tbl_items.clear().draw();
        var sso = $('#do-create-sso').val();
        var so = $('#do-create-so').val();
        var send;
        if ($('#do-create-sso').val() != "" && $('#do-create-so').val() != "") {
            if ($('#do-create-sso').val() == "*") {
                send = {
                    "type": "sso_detail",
                    "so_header": $('#do-create-so').val(),
                    "do_no": $('#do-create-no').val()
                };
            }else{
                send = {
                    "type": "sso_detail",
                    "sso_header": $('#do-create-sso').val(),
                    "do_no": $('#do-create-no').val()
                }; 
            }
            ajax("{{ route('tms.warehouse.do_entry.header_tools') }}",
                "POST",
                send,
                (response) => {
                    response = response.responseJSON;
                    console.log(response)
                    if (response.status == true) {
                        var data = response.content.result;
                        var data_do = response.content.do;

                        var sum_qty_sj = 0;
                        var sum_qty_sso = 0;
                        var id=0;
                        for (i=0; i < data.length; i++){
                            sum_qty_sj += parseInt(data[i].qty_sj);
                            sum_qty_sso += parseInt(data[i].qty_sso);
                        }
                        var max_qty = sum_qty_sso - sum_qty_sj;
                        var qty = 0;
                        if(max_qty > 0){
                            for (i=0; i < data.length; i++){
                                if (data_do.length > 0) {
                                    for (let d = 0; d < data_do.length; d++) {
                                        if (data_do[d].item_code == data[i].itemcode) {
                                            qty = data[i].qty_sj - data_do[d].quantity;
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
                                                qty,
                                                data[i].unit,
                                                '<input autocomplete="off" type="number" class="form-control-sm" id="rowid-'+id+'" value="'+data[i].qty_sj+'">'
                                            ]).draw();
                                        }else{
                                            if(data[i].qty_sj < data[i].qty_sso){
                                                console.log(data[i].qty_sj)
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
                                                    '<input autocomplete="off" type="number" class="form-control-sm" id="rowid-'+id+'">'
                                                ]).draw();
                                            }
                                        }
                                    }
                                    
                                }else{
                                    if(data[i].qty_sj < data[i].qty_sso){
                                        console.log(data[i].qty_sj)
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
                                            '<input autocomplete="off" type="number" class="form-control-sm" id="rowid-'+id+'">'
                                        ]).draw();
                                    } 
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

    $(document).on('click', '#do-btn-delete-item', function () {
        var do_no = $('#do-create-no').val();
        var itemcode = tbl_additem.row('.selected').data()[1];
        var params = {"do_no": do_no, "itemcode": itemcode};
        // ajax("{{route('tms.warehouse.do_entry.delete_item')}}", "POST", params, (response) => {
        //     response = response.responseJSON;
        //     tbl_additem.row('.selected').remove().draw( false );
        //     for (let i = 0; i < tbl_additem.rows().data().toArray().length; i++) {
        //         var drw = tbl_additem.cell( i, 0 ).data(1+i); 
        //     }
        //     tbl_additem.draw(false);
        //     $('#do-btn-delete-item').prop('disabled', true);
        // });
        tbl_additem.row('.selected').remove().draw( false );
        for (let i = 0; i < tbl_additem.rows().data().toArray().length; i++) {
            var drw = tbl_additem.cell( i, 0 ).data(1+i); 
        }
        tbl_additem.draw(false);
    });

    $(document).on('click', '#do-btn-create-reset', function () {
        tbl_additem.clear().draw(false);
        $('#do-form-create input').not('.readonly-first').removeAttr('readonly');
        $('#do-form-create select').prop('disabled', false);
        $('#do-btn-edit-item').prop('disabled', true);
        $('#do-btn-delete-item').prop('disabled', true);

        $('#do-create-sso').val(null);
        $('#do-create-so').val(null);
        $('#do-create-warehouse').val(null);
        $('#do-create-dnno').val(null);
        $('#do-create-pono').val(null);
        $('#do-create-delivery').val(null);
        $('#do-create-customername').val(null);
        $('#do-create-customerdoaddr').val(null);
        $('#do-create-customergroup').val(null);
        $('#do-create-customeraddr1').val(null);
        $('#do-create-customeraddr2').val(null);
        $('#do-create-customeraddr3').val(null);
        $('#do-create-customeraddr4').val(null);
    })

    $(document).off('click', '.do-act-view').on('click', '.do-act-view', function () {
        var id = $(this).data('dono');
        hideShow('#item-button-div', true);
        hideShow('#do-btn-create-submit', true);
        hideShow('#do-btn-revise', true);
        $('#do-form-create input').prop('readonly', true);
        $('#do-form-create select').prop('disabled', true);
        ajax("{{route('tms.warehouse.do_entry.read')}}", "GET", {"do_no": id, "view_do": true}, (response) => {
            response = response.responseJSON;
            var header = response.content.header;
            var items = response.content.items;

            var date = date_convert(header.delivery_date);
            var voided = datetime_convert(header.voided);
            var posted = datetime_convert(header.posted);
            var finished = datetime_convert(header.finished);
            var printed = datetime_convert(header.printed);

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
            $('#do-create-delivery').val(header.dn_date);
            $('#do-create-delivery2').val(header.drv_name);
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
            $('#do-create-rrno').val(header.rr_no);
            $('#do-create-rgno').val(header.rg_no);

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

    var tbl_ng;
    $(document).off('click', '.do-act-edit').on('click', '.do-act-edit', function () {
        var id = $(this).data('dono');
        ajax("{{route('tms.warehouse.do_entry.read')}}", "GET", {"do_no": id, "view_do": true}, (response) => {
            response = response.responseJSON;
            var header = response.content.header;
            var items = response.content.items;
            if (header.voided == null) {
                if (header.finished == null) {
                    if (header.posted == null) {
                            Swal.fire({
                            text: 'Do you want to create NG?',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then(answer => {
                            if (answer.value == true) {
                                modalAction('#do-modal-ng').then(resolve => {
                                    $('#do-ng-no').val(header.do_no);
                                    $('#do-ng-refno').val(header.ref_no);
                                    
                                    var obj_merge_ng = {...obj_tbl, ...{
                                        "columnDefs": [{
                                            "targets": [0,6],
                                            "createdCell":  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('text-right');
                                            }
                                        }]
                                    }};
                                    tbl_ng = $('#do-ng-datatables').DataTable(obj_merge_ng);

                                    var no = 1;
                                    $.each(items, function (i, item) {
                                        tbl_ng.row.add([
                                            no,
                                            item['part_no'],
                                            item['item_code'],
                                            item['part_name'],
                                            item['unit'],
                                            item['sso_no'],
                                            item['quantity'],
                                            `<input type="number" class="form-control-sm" autocomplete="off" id="rowngid-${no}">`
                                        ]).draw();
                                        no++;
                                    });
                                    resolve.on('shown.bs.modal', function () {
                                        tbl_ng.columns.adjust().draw();
                                    });
                                });
                            }else{
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
                                $('#do-create-delivery').val(header.dn_date);
                                $('#do-create-delivery2').val(header.drv_name);
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
                                $('#do-create-rrno').val(header.rr_no);
                                $('#do-create-rgno').val(header.rg_no);

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

                            }
                        });
                    }else{
                        Swal.fire({
                            title: 'Something was wrong!',
                            text: 'DO has been posted!',
                            icon: 'error'
                        });
                    }
                }else{
                    Swal.fire({
                        title: 'Something was wrong!',
                        text: 'DO has been finished!',
                        icon: 'error'
                    });
                }
            }else{
                Swal.fire({
                    title: 'Something was wrong!',
                    text: 'DO has been voided!',
                    icon: 'error'
                });
            }
        });
    });

    $(document).on('submit', '#do-form-ng', function () {
        var fix_data = [];
        var item = tbl_ng.rows().data().toArray();
        var id = 0;
        var count = 0;
        var nu = 0;
        for (i=0;i < item.length; i++){
            var obj_tbl_ng = {}

            var max_val_sj  =  item[i][6];
            var qty_sj = tbl_ng.rows().cell(i, 7).nodes().to$().find('input').val();

            obj_tbl_ng.do_no = $('#do-ng-no').val();
            obj_tbl_ng.itemcode = item[i][2];
            obj_tbl_ng.sso = item[i][5];
            obj_tbl_ng.qty_sj = max_val_sj;
            obj_tbl_ng.qty_ng = qty_sj;

            if(qty_sj > max_val_sj){
                count++;
                id++;
                if(qty_sj > 0){
                    $(`#rowngid-${id}`).removeClass('alert-success');
                    $(`#rowngid-${id}`).addClass('alert-danger'); 
                }    
            }else if(qty_sj <= max_val_sj){
                id++;
                if(qty_sj > 0){
                    $(`#rowngid-${id}`).removeClass('alert-danger');
                    $(`#rowngid-${id}`).addClass('alert-success'); 
                }
            }

            fix_data.push(obj_tbl_ng);
        }
        if (count == 0) {
            var data = {"items": fix_data};
            var post = {
                "route": "{{route('tms.warehouse.do_entry.ng')}}",
                "method": "POST",
                "data": data
            };
            $('#do-btn-revise').prop('disabled', true);
            ajaxWithPromise(post).then(resolve => {
                if (resolve.status == true) {
                    var response = resolve;
                    modalAction('#do-modal-ng', 'hide').then(resolve => {
                        Swal.fire({
                            title: 'Notification',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            get_index.then(resolve => {
                                resolve.ajax.reload();
                            })
                        });
                    });
                }
            });
        }
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
                    title: 'Something was wrong!',
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
                    // title: 'Are you sure?',
                    title: "Post DO No." + id + " Now?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, post it!',
                    input: 'text',
                    inputPlaceholder: 'Type your RR No. here...',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'You need to write RR No!'
                        }
                    }
                }).then(answer => {
                    if (answer.value != "" && answer.value != undefined) {
                        var rr_no = answer.value;
                        ajax("{{route('tms.warehouse.do_entry.post')}}", "POST", {"do_no": id, "rr_no": rr_no}, (response) => {
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
                    title: 'Something was wrong!',
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

    $(document).off('click', '.do-act-finished').on('click', '.do-act-finished', function () {
        var id = $(this).data('dono');
        Swal.fire({
            title: `Do you want to finish DO no. ${id}, now ?`,
            showCancelButton: true,
            confirmButtonText: `Yes, finish it!`,
            confirmButtonColor: '#DC3545',
            icon: 'warning',
        }).then((answer) => {
            if (answer.value != "" && answer.value != undefined) {
                var note = answer.value;
                ajax("{{route('tms.warehouse.do_entry.finish')}}", "POST", {"do_no": id}, (response) => {
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
    $(document).off('click', '.do-act-unfinished').on('click', '.do-act-unfinished', function () {
        var id = $(this).data('dono');
        Swal.fire({
            title: `Do you want to Unfinish DO no. ${id}, now ?`,
            input: 'text',
            inputPlaceholder: 'Type your note here...',
            showCancelButton: true,
            confirmButtonText: `Yes, unfinish it!`,
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
                ajax("{{route('tms.warehouse.do_entry.unfinish')}}", "POST", {"do_no": id, "note": note}, (response) => {
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

    $(document).off('click', '.do-act-report').on('click', '.do-act-report', function () {
        var id = $(this).data('dono');
        ajax("{{route('tms.warehouse.do_entry.read')}}", "GET", {"do_no": id, "check_print": true}, (response) => {
            response = response.responseJSON;
            if (response.message !== null) {
                Swal.fire({
                    title: 'Something was wrong!',
                    text: response.message,
                    icon: 'error'
                });
            }else{
                modalAction('#do-modal-print').then(resolve => {
                    $('#do-print-dari').val(id);
                    $('#do-print-sampai').val(id);

                    $('#do-print-dari').prop('readonly', true);
                    $('#do-print-sampai').prop('readonly', true);
                });
            }
        });
    });

    $('#do-btn-modal-print').on('click', function () {
        modalAction('#do-modal-print').then(resolve => {});
    });

    $(document).on('click', '#do-btn-print-gas', function () {
        if ($('#do-print-dari').val() == "" || $('#do-print-sampai').val() == "") {
            var dari = ($('#do-print-dari').val() == "") ? $('#do-print-dari').addClass('alert-danger') : $('#do-print-dari').removeClass('alert-danger')
            var sampai = ($('#do-print-sampai').val() == "") ? $('#do-print-sampai').addClass('alert-danger') : $('#do-print-sampai').removeClass('alert-danger')
        }else{
            var dari = $('#do-print-dari').val();
            var sampai = $('#do-print-sampai').val();
            var type = $('#do-print-type').val();
            var encrypt = btoa(`${dari}&${sampai}&${type}`);
            get_index.then(resolve => {
                resolve.ajax.reload();
            });
            modalAction('#do-modal-print', 'hide');
            var url = "{{route('tms.warehouse.do_entry.print')}}?params=" + encrypt;
            window.open(url, '_blank');
        }
    });

    $('#do-modal-print').on('hidden.bs.modal', function () {
        $(this).find('input').val(null);
        $(this).find('input').prop('readonly', false);
    });
    var tbl_do_print;
    $(document).on('keypress', '#do-print-dari', function (e) {
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
                        url: "{{ route('tms.warehouse.do_entry.header_tools') }}",
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
    });
    $(document).on('keypress', '#do-print-sampai', function (e) {
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
                            url: "{{ route('tms.warehouse.do_entry.header_tools') }}",
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
    });
    $('#do-print-dodata-datatables').off('click', 'tr').on('click', 'tr', function () {
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
    $('#do-modal-print-dodata').on('hidden.bs.modal', function () {
        $(this).find('input').val(null);
        $(this).find('input').prop('readonly', false);
    });

    $(document).off('click', '.do-act-revise').on('click', '.do-act-revise', function () {
        var id = $(this).data('dono');
        hideShow('#item-button-div', true);
        hideShow('#do-btn-create-submit', true);
        $('#do-form-create input').not('#do-create-date').prop('readonly', true);
        $('#do-form-create select').prop('disabled', true);
        ajax("{{route('tms.warehouse.do_entry.read')}}", "GET", {"do_no": id, "view_do": true}, (response) => {
            hideShow('#do-btn-revise', false);
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
            $('#do-create-rrno').val(header.rr_no);
            $('#do-create-rgno').val(header.rg_no);

            window.localStorage.setItem('date-old', date);

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
                    "customercode": $('#do-create-customercode').val().toUpperCase(),
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
                var post = {
                    "route": "{{route('tms.warehouse.do_entry.revise')}}",
                    "method": "POST",
                    "data": form_data
                };
                $('#do-btn-revise').prop('disabled', true);
                ajaxWithPromise(post).then(resolve => {
                    $('#do-btn-revise').prop('disabled', false);
                    var response = resolve;
                    modalAction('#do-modal-create', 'hide');
                    if (response.status == true) {
                        Swal.fire({
                            title: 'Notification',
                            text: response.message,
                            icon: 'success'
                        }).then(answer => {
                            get_index.then(resolve => {
                                resolve.ajax.reload();
                            });
                        });
                    }
                }, reject => {
                    var response = reject;
                    $('#do-btn-revise').prop('disabled', false);
                });
            }
        }
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
            "customercode": $('#do-create-customercode').val().toUpperCase(),
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
                            modalAction('#do-modal-create', 'hide').then(resolve => {
                                get_index.then(resolve => {
                                    resolve.ajax.reload();
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
        $('body').loading({
            message: "wait for a moment...",
            zIndex: 9999
        });
        return $.ajax({
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
                    title: 'Something was wrong',
                    text: response.responseJSON.message,
                    icon: 'error'
                })
                $('body').loading('stop');
            },
            complete: function (response){
                callback(response);
                $('body').loading('stop');
            }
        });
    }
    function ajaxWithPromise(params) {
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
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: params.data,
                error: function(response, status, x){
                    Swal.fire({
                        title: 'Something was wrong',
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
    function modalAction(elementId=null, action='show'){
        return new Promise(resolve => {
            $(elementId).modal(action);
            resolve($(elementId));
        });
    }
    function delay(callback, ms) {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
            callback.apply(context, args);
            }, ms || 0);
        };
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
        $('#do-create-sso').val(null);
        $('#do-create-so').val(null);
        $('#do-create-pono').val(null);
        $('#do-create-dnno').val(null);
        $('#do-create-warehouse').val(null);
        $('#do-create-delivery').val(null);
        $('#do-create-customerdoaddr').val(null);
        $('#do-create-customeraddr1').val(null);
        $('#do-create-customeraddr2').val(null);
        $('#do-create-customeraddr3').val(null);
        $('#do-create-customeraddr4').val(null);
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
});
</script>