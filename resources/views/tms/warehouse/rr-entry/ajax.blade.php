<script>
    $(document).ready(function () {
        var tbl_index = $('#rr-datatables-create').DataTable({
            destroy: true,
            lengthChange: false,
            searching: false,
            paging: false,
            ordering: false,
            scrollY: "200px",
            scrollCollapse: true,
            fixedHeader: true,
            "columnDefs": [{
                "targets": [0,5],
                "createdCell":  function (td, cellData, rowData, row, col) {
                    $(td).addClass('text-right');
                }
            }]
        });
        const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
        var tbl_do;
        $('#rr-create-dono').on('keypress', function (e) {
            if(e.which == 13) {
                modalAction('#rr-modal-create').then(resolve => {
                    var params = {"type": "dodata"}
                    tbl_do = $('#rr-dodata-datatables').DataTable({
                        processing: true,
                        serverSide: true,
                        destroy: true,
                        ordering: false,
                        ajax: {
                            url: "{{ route('tms.warehouse.rr_entry.headerTools') }}",
                            method: 'POST',
                            data: params,
                            headers: token_header
                        },
                        columns: [
                            {data: 'do_no', name: 'do_no'},
                            {data: 'ref_no', name: 'ref_no'},
                            {data: 'delivery_date', name: 'delivery_date'},
                            {data: 'po_no', name: 'po_no'},
                            {data: 'dn_no', name: 'dn_no'},
                            {data: 'cust_id', name: 'cust_id'},
                        ],
                    });
                });
            }
            e.preventDefault();
            return false;
        });
        $('#rr-dodata-datatables').off('click', 'tr').on('click', 'tr', function () {
            var data = tbl_do.row(this).data();
            var params = {
                "route": "{{route('tms.warehouse.rr_entry.headerTools')}}",
                "method": "POST",
                "data": {"type": "dodataclick", "dono": data.do_no}
            };
            ajaxWithPromise(params).then(resolve => {
                modalAction('#rr-modal-create', 'hide');
                formReset();
                if (resolve.status == true) {
                    var res = resolve.content[0];
                    $('#rr-create-dono').val(res.do_no);
                    $('#rr-create-refno').val(res.ref_no);
                    $('#rr-create-dodate').val(date_convert(res.delivery_date));
                    $('#rr-create-custcode').val(res.cust_id);
                    $('#rr-create-custname').val(res.cust_name);
                    $('#rr-create-dnno').val(res.dn_no);
                    $('#rr-create-pono').val(res.po_no);
                    $('#rr-create-sso').val(res.sso_no);
                    $('#rr-create-so').val(res.so_no);
                    $('#rr-create-rrno').val((res.rr_no == null) ? res.po_no : res.rr_no);
                    var no = 1;
                    $.each(resolve.content, function (i, item) {
                        tbl_index.row.add([
                            no,
                            item.PART_NO,
                            item.item_code,
                            item.DESCRIPT,
                            item.unit,
                            item.quantity,
                            '<input autocomplete="off" type="number" class="form-control-sm text-right" id="rowid-'+no+'" min="0" value="0">'
                        ]).draw();
                        no++;
                    });
                }
            });
        });
    
        $('#rr-create-btn-ok').on('click', function () {
            var item = tbl_index.rows().data().toArray();
            var id = 0;
            var count = 0;
            var nu = 0;
            var to_submit = [];
            for (i=0;i < item.length; i++){
                var obj_tbl_index = {}
    
                var max_val_sj  =  item[i][5];
                var qty_sj = tbl_index.rows().cell(i, 6).nodes().to$().find('input').val();
    
                obj_tbl_index.do_no = $('#rr-create-dono').val();
                obj_tbl_index.rr_no = $('#rr-create-rrno').val();
                obj_tbl_index.rr_date = $('#rr-create-rrdate').val();
                obj_tbl_index.scuritystamp = $('#rr-create-scuritystamp').val();
                obj_tbl_index.itemcode = item[i][2];
                obj_tbl_index.qty_sj = max_val_sj;
                obj_tbl_index.retur = qty_sj;
                obj_tbl_index.fix_qty = parseInt(max_val_sj) - parseInt(qty_sj);
    
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
    
                to_submit.push(obj_tbl_index);
            }
    
            var isvalid = validationBeforeSubmit();
            if (isvalid == true) {
                if(count == 0){
                    var params = {
                        "route": "{{route('tms.warehouse.rr_entry.save')}}",
                        "method": "POST",
                        "data": {"items": to_submit, "do_no": $('#rr-create-dono').val()}
                    };
                    ajaxWithPromise(params).then(resolve => {
                        formReset();
                        if (resolve.status == true) {
                            Swal.fire({
                                title: 'Notification',
                                text: resolve.message,
                                icon: 'success'
                            });
                        }
                    });
                }
            }
        });
    
        function validationBeforeSubmit() {
            var do_no = $('#rr-create-dono').val();
            var rr_no = $('#rr-create-rrno').val();
            var rr_date = $('#rr-create-rrdate').val();
            var scuritystamp = $('#rr-create-scuritystamp').val();
            var custcode = $('#rr-create-custcode').val();
            if (do_no == "") {
                $('#rr-create-dono').removeClass('alert-success');
                $('#rr-create-dono').addClass('alert-danger');
            }else{
                $('#rr-create-dono').removeClass('alert-danger');
                $('#rr-create-dono').addClass('alert-success');
            }
            if (rr_no == "") {
                $('#rr-create-rrno').removeClass('alert-success');
                $('#rr-create-rrno').addClass('alert-danger');
            }else{
                $('#rr-create-rrno').removeClass('alert-danger');
                $('#rr-create-rrno').addClass('alert-success');
            }
            if (rr_date == "") {
                $('#rr-create-rrdate').removeClass('alert-success');
                $('#rr-create-rrdate').addClass('alert-danger');
            }else{
                $('#rr-create-rrdate').removeClass('alert-danger');
                $('#rr-create-rrdate').addClass('alert-success');
            }
            if (scuritystamp == "") {
                $('#rr-create-scuritystamp').removeClass('alert-success');
                $('#rr-create-scuritystamp').addClass('alert-danger');
            }else{
                $('#rr-create-scuritystamp').removeClass('alert-danger');
                $('#rr-create-scuritystamp').addClass('alert-success');
            }
            if (custcode == "") {
                $('#rr-create-custcode').removeClass('alert-success');
                $('#rr-create-custcode').addClass('alert-danger');
            }else{
                $('#rr-create-custcode').removeClass('alert-danger');
                $('#rr-create-custcode').addClass('alert-success');
            }
            if (do_no != "" && rr_no != "" && rr_date != "" && scuritystamp != "" && custcode != "") {
                return true;
            }
            return false;
        }
    
        $('#rr-create-btn-reset').on('click', function () {
            formReset();
        });
        function formReset() {
            $('input').not('.this-datepicker').not('#rr-create-user').val(null);
            $('input').removeClass('alert-success');
            $('input').removeClass('alert-danger');
            tbl_index.clear().draw(false);
        }
        function modalAction(elementId=null, action='show'){
            return new Promise(resolve => {
                $(elementId).modal(action);
                resolve($(elementId));
            });
        }
        function date_convert($date) {
            var convert = ($date !== null) ? $date.split('-') : null;
            return (convert !== null) ? `${convert[2]}/${convert[1]}/${convert[0]}` : null;
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
            return value
        }
    });
    </script>