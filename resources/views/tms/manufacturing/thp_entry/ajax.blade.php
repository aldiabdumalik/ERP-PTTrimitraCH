<script>
$(document).ready(function(){
    // dtbl_index();
    var tbl_index = $('#thp-datatables').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: "{{ route('tms.manufacturing.thp_entry.dataTable_index') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {"date_thp":null}
        },
        columns: [
            {data: 'id_thp', name: 'id_thp', className: "text-center", orderable: false},
            {data: 'thp_date', name: 'thp_date', className: "text-center", orderable: false},
            // {data: 'date_order', name: 'date_order', className: "text-center", visible: false}
            {data: 'customer_code', name: 'customer_code', orderable: false},
            {data: 'production_code', name: 'production_code', orderable: false},
            {data: 'part_name', name: 'part_name', orderable: false},
            // {data: 'part_type', name: 'part_type', orderable: false},
            {data: 'route', name: 'route', orderable: false},
            {data: 'process', name: 'process', orderable: false, searchable: false},
            {data: 'thp_qty', name: 'thp_qty', orderable: false, searchable: false, className: "text-right"},
            {data: 'lhp_qty', name: 'lhp_qty', orderable: false, searchable: false, className: "text-right"},
            {data: 'apnormality', name: 'apnormality', orderable: false, className: "text-center"},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        "order": [[ 2, "desc" ]],
    });
    $('#searchModal').on('click', function () {
        $('#thp-view-by-date-modal').modal('show');
        $('#thp-select-datepicker').datepicker({
            format: 'dd/mm/yyyy',
        }).on('changeDate', function(e) {
            var date = e.format(0,"dd/mm/yyyy");
            $('#thp-view-by-date-modal').modal('hide');
            tbl_index.clear();
            dtbl_index(date);
            $('#thp-date').text(date);
            get_num_notif();
        });
    });
    function dtbl_index(date=null) {   
        $('#thp-datatables').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{ route('tms.manufacturing.thp_entry.dataTable_index') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {"date_thp":date}
            },
            columns: [
                {data: 'id_thp', name: 'id_thp', className: "text-center", orderable: false},
                {data: 'thp_date', name: 'thp_date', className: "text-center", orderable: false},
                // {data: 'date_order', name: 'date_order', className: "text-center", visible: false}
                {data: 'customer_code', name: 'customer_code', orderable: false},
                {data: 'production_code', name: 'production_code', orderable: false},
                {data: 'part_name', name: 'part_name', orderable: false},
                // {data: 'part_type', name: 'part_type', orderable: false},
                {data: 'route', name: 'route', orderable: false},
                {data: 'process', name: 'process', orderable: false, searchable: false},
                {data: 'thp_qty', name: 'thp_qty', orderable: false, searchable: false, className: "text-right"},
                {data: 'lhp_qty', name: 'lhp_qty', orderable: false, searchable: false, className: "text-right"},
                {data: 'apnormality', name: 'apnormality', orderable: false, className: "text-center"},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "order": [[ 1, "desc" ]],
        });
    }
    $('#refreshlhp').on('click', function () {
        var date = $('#thp-date').text(),
            thp_date = date.split('/').reverse().join('-')
            route = "{{ route('tms.manufactuting.thp_entry.refresh', [':date']) }}";
            route  = route.replace(':date', thp_date);
        $('body').loading({
            message: "wait for a moment...",
            zIndex: 9999
        });
        ajaxCall({route: route, method: "GET"}).then(resolve => {
            // console.log(resolve);
            var status = resolve.status;
            if (status == true) {
                $('body').loading('stop');
            }
            tbl_index.clear();
            dtbl_index(date);
            get_num_notif();
        });
    });
    $(document).on('click', '.thp-act-apnormal', function () {
        var id = $(this).data('thp'),
            route = "{{ route('tms.manufactuting.thp_entry.apnormal', [':number']) }}";
            route  = route.replace(':number', id);
        $('#thp-modal-apnormal').modal('show');
        $('#thp-note-no').val(id);
    });
    $(document).on('submit', '#thp-form-apnormal', function () {
        var route = "{{ route('tms.manufactuting.thp_entry.apnormal', [':number']) }}";
            route  = route.replace(':number', $('#thp-note-no').val());
        $('body').loading({
            message: "wait for a moment...",
            zIndex: 9999
        });
        ajaxCall({route: route, method: "PUT", data: {note: $('#thp-note-note').val(), apnormality: $('#thp-note-apnormality').val()}}).then(resolve => {
            $('body').loading('stop');
            $('#thp-modal-apnormal').modal('hide');
            tbl_index.ajax.reload();
            get_num_notif();
        });
    });
    $('#thp-modal-apnormal').on('hidden.bs.modal', function () {
        $('#thp-form-apnormal').trigger('reset');
    });
    $(document).on('click', '.thp-act-view', function () {
        getThp($(this).data('thp'), function (response) {
            response = response.responseJSON;
            $('#modalDetail').modal('show');
            if (response.status == true) {
                var data = response.data;
                var lhp = response.lhp;
                var action_plan, date, apnormality, note, sgm, shift, grup, machine, lhp_qty;
                date = data.thp_date.split('-');
                date = date[2] + '/' + date[1] + '/' + date[0];
                $('#thp-detail-id').val(data.id_thp);
                $('#thp-detail-date').val(date);
                $('#thp-detail-production-code').val(data.production_code);
                $('#thp-detail-part-number').val(data.part_number);
                $('#thp-detail-part-name').val(data.part_name);
                $('#thp-detail-part-type').val(data.part_type);
                $('#thp-detail-customer-code').val(data.customer_code);
                $('#thp-detail-route').val(data.route);
                $('#thp-detail-plan').val(data.plan);
                $('#thp-detail-ct').val(data.ct);
                $('#thp-detail-ton').val(data.ton);
                $('#thp-detail-time').val(data.time);
                $('#thp-detail-plan-hour').val(data.plan_hour);
                $('#thp-detail-process-1').val(data.process_sequence_1);
                $('#thp-detail-process-2').val(data.process_sequence_2);
                $('#thp-detail-qty').val(data.thp_qty);
                lhp_qty = (lhp.lhp_qty != null ? lhp.lhp_qty : 0)
                $('#lhp-detail-qty').val(lhp_qty);

                $('#thp-detail-item-code').val(data.item_code);
                $('#thp-detail-production-process').val(data.production_process);
                $('#thp-detail-operator').val(data.user);

                apnormality = (data.apnormality != null ? data.apnormality : '//');
                action_plan = (data.action_plan != null ? data.action_plan : '//');
                $('#thp-detail-note').val(data.note);
                $('#thp-detail-apnormal').val(apnormality);
                $('#thp-detail-action-plan').val(action_plan);
            }
        });
    });
    $(document).on('click', '.thp-act-edit', function () {
        var id = $(this).data('thp'),
            prod = $(this).data('prod'),
            date = $(this).data('date'),
            route = "{{ route('tms.manufacturing.thp_entry.check', [':prodcode', ':date']) }}";
            route  = route.replace(':prodcode', prod);
            route  = route.replace(':date', date);
        ajaxCall({route: route, method: "GET"}).then(resolve => {
            var data = resolve.content;
            if (resolve.message === 'is_exist') {
                $('#thp-modal-index').modal('show');
                $('#thp-create-prodcode').prop('readonly', true);

                var action_plan, date, apnormality, note, sgm, shift, grup, machine;
                date = data.thp_date.split("-").reverse().join("/");
                $('#thp-create-date').val(date);
                $('#thp-create-prodcode').val(data.production_code);
                $('#thp-create-itemcode').val(data.item_code);
                $('#thp-create-partno').val(data.part_number);
                $('#thp-create-partname').val(data.part_name);
                $('#thp-create-type').val(data.part_type);
                $('#thp-create-cust').val(data.customer_code);
                $('#thp-create-route').val(data.route);
                $('#thp-create-ct').val(addZeroes(String(data.ct)));
                $('#thp-create-machine').val(data.ton);
                $('#thp-create-time').val(addZeroes(String(data.time)));
                $('#thp-create-ph').val(addZeroes(String(data.plan_hour)));
                $('#thp-create-subprocess1').val(data.process_sequence_1);
                $('#thp-create-subprocess2').val(data.process_sequence_2);
                $('#thp-create-qty').val(data.thp_qty);
                sgm = data.thp_remark.split('_');
                shift = sgm[0];

                $('#thp-create-shift').val(shift);
                $('#thp-create-note').val(data.note);
                $('#thp-create-apnormality').val(data.apnormality);
                $('#thp-create-actionplan').val(data.action_plan);
            }
        });
    }).on('mouseup',function(){
        // setTimeout(function(){ 
        //     // $('#thp-form-create input,textarea').removeAttr('readonly');
        //     // $('#thp-form-create select').removeAttr('disabled');
        //     // $('#thp-edit-btn').prop('hidden', 'hidden');
        //     // $('#thp-btn-production-code').removeAttr('disabled');
        //     $('.thp-create-btn').text('Update');
        //     $('.thp-create-btn').css({'display': 'block'});
        // }, 1000);
    });
    $(document).on('click', '.thp-act-log', function () {
        $('#thp-log-modal').modal('show');
        log_tbl($(this).data('thp'));
    });
    $(document).on('click', '.thp-act-close', function () {
        var id = $(this).data('thp');
        close_thpentry(id);
    });
    var tbl_create = $('#thp-create-datatables').DataTable({
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "ordering": false,
    });
    var tbl_view = $('#thp-view-datatables').DataTable({
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "ordering": false,
    });
    $(document).on('click', '#addModal', function(e) {
        e.preventDefault();
        // $('#createModal').modal('show');
        $('#thp-modal-index').modal('show');
    });
    $('#thp-modal-index').on('hidden.bs.modal', function () {
        $('#thp-form-index').trigger('reset');
        $('input').not('.readonly-first').prop('readonly', false);
    });
    $('#thpnotif').on('click', function () {
        $('body').loading({
            message: "wait for a moment...",
            zIndex: 9999
        });
        $('#thp-notif-list').empty();
        ajaxCall({route: "{{route('tms.manufactuting.thp_entry.get_notif')}}", method: "GET"}).then(resolve => {
            $('body').loading('stop');
            if (resolve.message != 'not_exist') {
                $.each(resolve.content, function (i, data) {
                    $('#thp-notif-list').append(`
                        <div class="alert alert-warning alert-dismissible fade show alert-thp" role="alert" data-id="${data.id}">
                            ${data.notif_note}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `);
                });
            }
            $('#thp-modal-notif').modal('show');
        });
        // $('#thp-modal-notif').modal('show');
    });
    $(document).on('close.bs.alert', '.alert-thp',function () {
        var id = $(this).data('id');
        ajaxCall({route: "{{route('tms.manufactuting.thp_entry.delete_notif')}}", method: "POST", data: {id:id}}).then(resolve => {
            var count = $('.thpnotif-num').text();
            var jml = parseInt(count) - 1;
            $('.thpnotif-num').html(jml);
            $('.thpnotif-num2').html(jml);
        });
    })
    $(document).on('keypress', '#thp-create-prodcode', function(e){
        e.preventDefault();
        if(e.which == 13) {
            $('#poduction-code-modal').modal('show');
            productioncode_tbl();
        }
        return false;
    });
    $(document).on('click', '#printModal', function(e) {
        e.preventDefault();
        $('#thp-print-modal').modal('show');
    });
    $(document).on('click', '#printModalSummary', function(e) {
        e.preventDefault();
        $('#thp-print-summary-modal').modal('show');
    });
    $(document).on('click', '#importModal', function(e) {
        e.preventDefault();
        $('#thp-import-modal').modal('show');
    });
    $(document).on('click', '#thp-btn-production-code', function(e) {
        e.preventDefault();
        $('#poduction-code-modal').modal('show');
        productioncode_tbl();
    });
    $(document).on('change keyup', '#thp-production-code', function(e){
        e.preventDefault();
        $('#poduction-code-modal').modal('show');
        productioncode_tbl();
    });
    $(document).on('change', '#pc-search-process', function (e) {
        e.preventDefault();
        productioncode_tbl($(this).val(), $('#pc-search-customer').val());
    });
    $(document).on('change', '#pc-search-customer', function (e) {
        e.preventDefault();
        productioncode_tbl($('#pc-search-process').val(), $(this).val());
    });
    $(document).on('submit', '#thp-form-index', function () {
        var data = {
            "thp_date": $('#thp-create-date').val().split("/").reverse().join("-"),
            "customer_code": $('#thp-create-cust').val(),
            "production_code": $('#thp-create-prodcode').val(),
            "part_number": $('#thp-create-partno').val(),
            "part_name": $('#thp-create-partname').val(),
            "part_type": $('#thp-create-type').val(),
            "route": $('#thp-create-route').val(),
            "process_1": $('#thp-create-subprocess1').val(),
            "process_2": $('#thp-create-subprocess2').val(),
            "ct": $('#thp-create-ct').val(),
            "ton": $('#thp-create-machine').val(),
            "time": $('#thp-create-time').val(),
            "plan_hour": $('#thp-create-ph').val(),
            "thp_qty": $('#thp-create-qty').val(),
            "shift": $('#thp-create-shift').val(),
            "note": $('#thp-create-note').val(),
            "apnormal": $('#thp-create-apnormality').val(),
            "action_plan": $('#thp-create-actionplan').val(),
        }
        var route = "{{ route('tms.manufacturing.thp_entry.check', [':prodcode', ':date']) }}";
        route  = route.replace(':prodcode', $('#thp-create-prodcode').val());
        route  = route.replace(':date', $('#thp-create-date').val().split("/").reverse().join("-"));
        var date = $('#thp-create-date').val(),
            thp_date = date.split('/').reverse().join('-');
        ajaxCall({route: route, method: "GET"}).then(resolve => {
            $('body').loading({
                message: "wait for a moment...",
                zIndex: 9999
            });
            var msg = resolve.message;
            if (msg === 'isnt_exist') {
                ajaxCall({route: "{{ route('tms.manufacturing.thp_entry.save') }}", method: "POST", data: data}).then(resolve => { 
                    Swal.fire({
                        title: 'Success!',
                        text: resolve.message,
                        icon: 'success'
                    }).then(function(){
                        $('body').loading('stop');
                        $('#thp-modal-index').modal('hide');
                        tbl_index.clear();
                        dtbl_index(date);
                        $('#thp-date').text(date);
                        get_num_notif();
                    });
                });
            }else{
                var update = "{{ route('tms.manufacturing.thp_entry.update', [':prodcode']) }}";
                update  = update.replace(':prodcode', $('#thp-create-prodcode').val());
                ajaxCall({route: update, method: "PUT", data: data}).then(resolve => { 
                    Swal.fire({
                        title: 'Success!',
                        text: resolve.message,
                        icon: 'success'
                    }).then(function(){
                        $('body').loading('stop');
                        $('#thp-modal-index').modal('hide');
                        tbl_index.clear();
                        dtbl_index(date);
                        $('#thp-date').text(date);
                        get_num_notif();
                    });
                });
            }
        });
    });
    $(document).on('submit', '#thp-form-create', function () {
        var data = {
            "id_thp": $('#thp-id').val(),
            "thp_date": $('#thp-date').val(),
            "customer_code": $('#thp-customer-code').val(),
            "production_code": $('#thp-production-code').val(),
            "item_code": $('#thp-itemcode').val(),
            "part_number": $('#thp-part-number').val(),
            "part_name": $('#thp-part-name').val(),
            "part_type": $('#thp-part-type').val(),
            "production_process": $('#thp-production-process').val(),
            "route": $('#thp-route').val(),
            "process_1": $('#thp-process-1').val(),
            "process_2": $('#thp-process-2').val(),
            "ct": $('#thp-ct').val(),
            "plan": $('#thp-plan').val(),
            "ton": $('#thp-ton').val(),
            "time": $('#thp-time').val(),
            "plan_hour": $('#thp-plan-hour').val(),
            "thp_qty": $('#thp-qty').val(),
            "shift": $('#thp-shift').val(),
            "grup": $('#thp-grup').val(),
            "machine": $('#thp-machine').val(),
            "note": $('#thp-note').val(),
            "apnormal": $('#thp-apnormal').val(),
            "action_plan": $('#thp-action-plan').val(),
            "_token": $('meta[name="csrf-token"]').attr('content')
        };
        $.ajax({
            url: "{{ route('tms.manufacturing.thp_entry.thpentry_create') }}",
            type: "POST",
            dataType: "JSON",
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
            success: function (response) {
                if(response.status == true){
                    $('#createModal').modal('hide');
                    $('#thp-form-create').trigger("reset");
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success'
                    }).then(function(){
                        window.location.reload();
                    });
                }else{
                    Swal.fire({
                        title: 'Oops...',
                        text: response.message,
                        icon: 'warning',
                    }); 
                }
            },
            error: function(response, status, x){
                Swal.fire({
                    title: 'Error!',
                    text: response.responseJSON.message,
                    icon: 'error'
                })
            }
        });
    });

    $(document).on('show.bs.modal', '#createModal', function () {});

    $(document).on('hidden.bs.modal', '#createModal', function () {
        $('#thp-form-create').trigger('reset');
        $('.thp-create-btn').css({'display': 'block'});
        $('.thp-create-btn').text('Simpan');
        $('#thp-id').val(0);
    });

    $(document).on('submit', '#thp-form-import', function () {
        $('.thp-import-btn').prop('disabled', true);
        $('.thp-import-btn').text('Loading...');
        $('#thp_import_file').css('display', 'none');
        $('.progress-import').css('display', 'block');
        var timerId, percent;

        // reset progress bar
        percent = 0;
        $('#progress-import').css('width', '0px');
        $('#progress-import').addClass('progress-bar progress-bar-striped progress-bar-animated active');

        timerId = setInterval(function() {
            percent += 5;
            $('#progress-import').css('width', percent + '%');
            $('#progress-import').html(percent + '%');

            // complete
            if (percent >= 90) {
                clearInterval(timerId);
            }

        }, 50);

        var form = new FormData();
        form.append("thp_import_file", $('#thp_import_file')[0].files[0]);
        form.append("thp_import_tanggal", $('#thp_import_tanggal').val());
        $.ajax({
            url: "{{ route('tms.manufacturing.thp_entry.importToDB') }}",
            type: "POST",
            contentType: false,
            processData: false,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: form,
            success: function (response) {
                $('#progress-import').css('width', '100%');
                $('#progress-import').html('100%');
                if(response.status == true){
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success'
                    }).then(function(){
                        // window.location.reload();
                        $('.thp-import-btn').prop('disabled', false);
                        $('.thp-import-btn').text('Import');
                        $('#thp_import_file').css('display', 'block');
                        $('.progress-import').css('display', 'none');
                        $('#thp-import-modal').modal('hide');
                        $('#thp_import_file').val('');
                        tbl_index.ajax.reload();
                        get_num_notif();
                    });
                }
            },
            error: function(response, status, x){
                $('#progress-import').addClass('bg-danger');
                Swal.fire({
                    title: 'Error!',
                    text: response.responseJSON.message,
                    icon: 'error'
                }).then(function(){
                    $('#progress-import').css('width', '100%');
                    $('#progress-import').html('100%');
                    window.location.reload();
                });
            }
        });
    });

    function getThp(id="", callback) {
        var route  = "{{ route('tms.manufacturing.thp_entry.dataTable_edit', ':id') }}";
            route  = route.replace(':id', id);
        $.ajax({
            url: route,
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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

    function productioncode_tbl(proc="PRESSING", cust=""){
        var tbl_production_code = $('#thp-poduction-code-datatables').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{ route('tms.manufacturing.thp_entry.dataTable_production') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "process": proc,
                    "cust": cust
                }
            },
            columns: [
                {data: 'customer_id', name: 'customer_id'},
                {data: 'production_process', name: 'production_process'},
                {data: 'production_code', name: 'production_code'},
                {data: 'part_number', name: 'part_number'},
                {data: 'part_name', name: 'part_name'},
                {data: 'part_type', name: 'part_type'},
                {data: 'item_code', name: 'item_code'},
                {data: 'process', name: 'process'},
                {data: 'process_detailname', name: 'process_detailname'},
                {data: 'ct_sph', name: 'ct_sph'}
            ],
            ordering: false,
            scrollY: "200px",
            scrollCollapse: true,
            fixedHeader: true,
        });
        $('#thp-poduction-code-datatables tbody').off('click').on('click', 'tr', function () {
            var data = tbl_production_code.row(this).data();
            var process;
            processs = data.process.split('/');
            $('#thp-part-number').val(data.part_number);
            $('#thp-part-name').val(data.part_name);
            $('#thp-part-type').val(data.part_type);
            $('#thp-production-code').val(data.production_code);
            $('#thp-customer-code').val(data.customer_id);
            $('#thp-route').val(data.process_detailname);
            $('#thp-process-1').val(processs[0]);
            $('#thp-process-2').val(processs[1]);
            $('#thp-ct').val(data.ct_sph);
            $('#thp-itemcode').val(data.item_code);
            $('#thp-production-process').val(data.production_process);

            $('#thp-create-partno').val(data.part_number);
            $('#thp-create-partname').val(data.part_name);
            $('#thp-create-type').val(data.part_type);
            $('#thp-create-prodcode').val(data.production_code);
            $('#thp-create-itemcode').val(data.item_code);
            $('#thp-create-cust').val(data.customer_id);
            $('#thp-create-route').val(data.process_detailname);
            $('#thp-create-subprocess1').val(processs[0]);
            $('#thp-create-subprocess2').val(processs[1]);
            $('#thp-create-ct').val(data.ct_sph);

            $('#poduction-code-modal').modal('hide');
        });
        $(document).on('hidden.bs.modal', '#thp-poduction-code-datatables', function () {
            tbl_production_code.clear();
        });
    }

    function log_tbl(id_thp=null) {
        var tbl_log = $('#thp-log-datatables').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{ route('tms.manufacturing.thp_entry.dataTable_log') }}",
                method: "GET",
                data: {
                    "id": id_thp,
                }
            },
            columns: [
                {data: 'date_written', name: 'date_written'},
                {data: 'time_written', name: 'time_written'},
                {data: 'status_change', name: 'status_change'},
                {data: 'user', name: 'user'},
                {data: 'note', name: 'note'}
            ],
        });
    }

    function close_thpentry(id=null) {
        $('#thp-close-modal').modal('show');
        $('#thp-form-closed').submit(function () {
        Swal.fire({
            text: 'Do you want to close the changes?',
            showCancelButton: true,
            confirmButtonText: `Close`,
            confirmButtonColor: '#DC3545',
            }).then((result) => {
                if (result.value == true) {
                    $.ajax({
                        url: "{{ route('tms.manufacturing.thp_entry.closeThpEntry') }}",
                        type: "POST",
                        dataType: "JSON",
                        cache: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            "id": id,
                            "note": $('#thp-close-note').val()
                        },
                        success: function (response) {
                            if(response.status == true){
                                $('#thp-form-closed').trigger('reset');
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success'
                                }).then(function(){
                                    window.location.reload();
                                });
                            }else{
                                Swal.fire({
                                    title: 'Warning!',
                                    text: response.message,
                                    icon: 'warning'
                                })
                            }
                        },
                        error: function(response, status, x){
                            Swal.fire({
                                title: 'Warning!',
                                text: response.responseJSON.message,
                                icon: 'warning'
                            })
                        }
                    });
                }else{
                    $('#thp-close-modal').modal('hide');
                    $('#thp-form-closed').trigger('reset');
                }
            })
        });
    }
    $(document).on('submit', '#thp-form-print', function () {
        var dari = $('#thp_print_dari').val();
        var sampai = $('#thp_print_sampai').val();
        var process = $('#thp_print_process').val();
        var type = 'reportDate';
        var encrypt = btoa(`${dari}&${process}&${type}`);
        var url = '{{route('tms.manufacturing.thp_entry.printThpEntry')}}?print=' + encrypt + '&what='+$('#thp_print_type').val();
        window.open(url, '_blank');
    });
    $(document).on('submit', '#thp-form-print-summary', function () {
        var dari = $('#thp_print_dari_summary').val();
        var sampai = $('#thp_print_sampai_summary').val();
        var process = $('#thp_print_process_summary').val();
        var type = 'reportSummary';
        var encrypt = btoa(`${dari}&${sampai}&${process}&${type}`);
        var url = '{{route('tms.manufacturing.thp_entry.printThpEntry')}}?print=' + encrypt + '&what='+$('#thp_print_type_summary').val();
        window.open(url, '_blank');
    });
    $('#settingPersentaseModal').on('click', function () {
        $('#setPersentase').modal('show');
        var data = {
            "type": "GET",
            "id": 1
        }
        setting(data, function (response) {
            response = response.responseJSON;
            if(response.status == true){
                $('#setting-persentase-id').val(response.data.id);
                $('#setting-persentase-name').val(response.data.name_setting);
                $('#setting-persentase-value').val(response.data.value_setting);
            }
        });
        $(document).on('submit', '#setting-persentase-form', function () {
            var insert = {
                "type": "POST",
                "id": $('#setting-persentase-id').val(),
                "setting_name": $('#setting-persentase-name').val(),
                "setting_value": $('#setting-persentase-value').val()
            }
            setting(insert, function (response) {
                response = response.responseJSON;
                if(response.status == true){
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success'
                    }).then(function () {
                        window.location.reload();
                    });
                }
            });
        });
    });
    function get_num_notif() {
        $.ajax({
            url: "{{ route('tms.manufactuting.thp_entry.count_notif') }}",
            method: "GET",
            success: function (response) {
                var num = response.content;
                if (num != 0) {
                    $('.thpnotif-num').html(num);
                    $('.thpnotif-num2').html(num);
                }
            }
        });
    }
    function getShiftGrupMachine(type="", process=null, callback) {
        var query1 = {
            "type": type
        }
        var query2 = {
            "type": type,
            "process": process
        }
        var params = (process != null ? query2 : query1);
        $.ajax({
            url: "{{ route('tms.manufacturing.thp_entry.getShiftGroupMachine') }}",
            type: "POST",
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
    function setting(data=null, callback) {
        $.ajax({
            url: "{{ route('tms.manufacturing.thp_entry.settingThpEntry') }}",
            type: "POST",
            dataType: "JSON",
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
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
    function addZeroes( num ) {
        var value = Number(num);
        var res = num.split(".");
        if(res.length == 1 || (res[1].length < 4)) {
            value = value.toFixed(2);
        }
        return value;
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
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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
});
</script>