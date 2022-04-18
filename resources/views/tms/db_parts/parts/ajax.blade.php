<script>
    $(document).ready(function () {
        const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
        const arr_url = window.location.pathname.split( '/' );
        const table_index = $('#iparts-datatable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{route('tms.db_parts.parts.dt')}}",
                method: 'POST',
                headers: token_header,
                data: {type: arr_url[5]}
            },
            columns: [
                {data:'type', name: 'type', className: "align-middle"},
                {data:'part_no', name: 'part_no', className: "align-middle"},
                {data:'part_name', name: 'part_name', className: "align-middle"},
                {data:'cust_id', name: 'cust_id', className: "align-middle"},
                {data:'action', name: 'action', className: "align-middle text-center"},
            ],
            order: [[ 0, "asc" ]],
        });
    
        $('#iparts-btn-modal-create').on('click', function () {
            loading_start();
            ajaxCall({route: "{{ route('tms.db_parts.parts.tools') }}", method: "POST", data: {type: "modal_create", type_id: arr_url[5]}}).then(ress => {
                loading_stop();
                if (ress.content != null) {
                    let data = ress.content;
                    modalAction('#iparts-modal-index').then(() => {
                        $('#id').val(0);
                        $('#iparts-index-ppartno').removeAttr('data-id');
                        $('#iparts-index-customercode').val(data.custcode);
                        $('#iparts-index-customername').val(data.custname);
                        $('#iparts-index-parttype').val(data.type);
                        $('#iparts-index-reff').val(data.reff);
                    });
                }
            })
        });
    
        var tbl_iparent;
        $(document).on('keypress keydown', '#iparts-index-ppartno', function (e) {
            if(e.which == 13) {
                modalAction('#iparts-modal-itemparent').then((resolve) => {
                    tbl_iparent = $('#iparts-datatables-itemparent').DataTable({
                        processing: true,
                        serverSide: true,
                        destroy: true,
                        ajax: {
                            url: "{{ route('tms.db_parts.parts.tools') }}",
                            method: 'POST',
                            data: {type: "item_parent", type_id: arr_url[5]},
                            headers: token_header
                        },
                        columns: [
                            {data: 'part_no', name: 'part_no'},
                            {data: 'part_name', name: 'part_name'},
                            {data: 'type', name: 'type'},
                        ]
                    });
                });
            }
            if(e.which == 8 || e.which == 46) { return false; }
            return false;
        });
        $(document).on('shown.bs.modal', '#iparts-modal-itemparent', function () {
            $('#iparts-datatables-itemparent_filter input').focus();
        });
        $('#iparts-datatables-itemparent').off('dblclick', 'tr').on('dblclick', 'tr', function () {
            var data = tbl_iparent.row(this).data();
            modalAction('#iparts-modal-itemparent', 'hide').then(() => {
                $('#iparts-index-ppartno').val(data.part_no);
                $('#iparts-index-ppartno').attr('data-id', data.id);
                $('#iparts-index-ppartname').val(data.part_name);
            });
        });
    
        $(document).on('submit', '#iparts-modal-index', function (e) {
            e.preventDefault();
            loading_start();
            let id = $('#id').val();
            let data = {
                'type_id': arr_url[5],
                'cust_id': $('#iparts-index-customercode').val(),
                'parent_id': ($('#iparts-index-ppartno').data('id') == undefined) ? null : $('#iparts-index-ppartno').data('id'),
                'part_no': $('#iparts-index-partno').val(),
                'part_name': $('#iparts-index-partname').val(),
                'type': $('#iparts-index-parttype').val(),
                'part_pict': $('#iparts-index-pict-x').text(),
                'reff': $('#iparts-index-reff').val(),
                'part_vol': $('#iparts-index-vol').val(),
                'qty_part_item': $('#iparts-index-qty').val(),
                'gop_assy': $('#iparts-index-gopassy').val(),
                'gop_single': $('#iparts-index-gopsingle').val(),
                'purch_part': $('#iparts-index-purch').val(),
                'spec': $('#iparts-index-spec').val(),
                'ms_t': $('#iparts-index-t').val(),
                'ms_w': $('#iparts-index-w').val(),
                'ms_l': $('#iparts-index-l').val(),
                'ms_n_strip': $('#iparts-index-n').val(),
                'ms_coil_pitch': $('#iparts-index-cp').val(),
                'part_weight': $('#iparts-index-weight').val(),
                'vendor_name': $('#iparts-index-vendor').val()
            };
            let route = "{{ route('tms.db_parts.parts.detail', [':id']) }}";
                route  = route.replace(':id', id);
            let method = '';
            ajaxCall({ route: route, method: "GET"}).then(resolve => {
                if (resolve.message == 1) {
                    route = "{{ route('tms.db_parts.parts.update', [':id']) }}";
                    route  = route.replace(':id', id);
                    method = "PUT";
                }else{
                    route = "{{ route('tms.db_parts.parts.store') }}";
                    method = "POST";
                }
    
                console.log(route, method, data);
                submitForm(route, method, data);
            });
        });
    
        function submitForm(route, method, data) {
            ajaxCall({route:route, method:method, data:data}).then(resolve => {
                loading_stop();
                Swal.fire({
                    title: 'Success',
                    text: resolve.message,
                    icon: 'success'
                }).then(() => {
                    modalAction('#iparts-modal-index', 'hide').then(() => {
                        table_index.ajax.reload();
                    });
                });
            });
        }
    
        $('#iparts-modal-index').on('hidden.bs.modal', function () {
            $('#iparts-form-index').trigger('reset');
            $('#iparts-index-id').attr('data-val', 0);
            $('#id').val(0);
    
            $('#iparts-form-index input').not('.readonly-first').prop('readonly', false);
            $('#iparts-form-index input[type=file]').prop('disabled', false);
            $('#iparts-form-index select').prop('disabled', false);

            $('#iparts-index-ppartno').removeAttr('data-id');
            isHidden('#iparts-btn-index-submit', false)
    
            // delete file on server
            let name = $('#iparts-index-pict-x').html();
            if ((name.lastIndexOf(".") + 1) > 0) {
                let data = {type: "delete_temp", old_file: name};
                ajaxCall({route: "{{route('tms.db_parts.parts.tools')}}", method: "POST", data:data});
            }
            $('#iparts-index-pict-x').html('Choose file');
        });
    
        $(document).on('click', '.iparts-act-edit', function () {
            let id = $(this).data('id');
            let route = "{{ route('tms.db_parts.parts.detail', [':id']) }}";
                route  = route.replace(':id', id);
            ajaxCall({ route: route, method: "GET"}).then(resolve => {
                let dt = resolve.content;
                let parent_no = (dt.parent_partno == undefined) ? null : dt.parent_partno;
                let parent_name = (dt.parent_partname == undefined) ? null : dt.parent_partname;

                modalAction('#iparts-modal-index').then(() => {
                    $('#iparts-index-id').attr('data-val', dt.id);
                    $('#id').val(dt.id);
    
                    $('#iparts-index-customercode').val(dt.cust_id);
                    $('#iparts-index-customername').val(dt.cust_name);
    
                    $('#iparts-index-ppartno').val(parent_no);
                    if (parent_no !== null) {
                        $('#iparts-index-ppartno').attr('data-id', dt.parent_id)
                    }
                    $('#iparts-index-ppartname').val(parent_name);
    
                    $('#iparts-index-partno').val(dt.part_no);
                    $('#iparts-index-partname').val(dt.part_name);
                    $('#iparts-index-parttype').val(dt.type);
                    $('#iparts-index-pict-x').html(dt.part_pict);
                    $('#iparts-index-reff').val(dt.reff);
                    $('#iparts-index-vol').val(dt.part_vol);
                    $('#iparts-index-qty').val(dt.qty_part_item);
                    $('#iparts-index-gopassy').val(dt.gop_assy);
                    $('#iparts-index-gopsingle').val(dt.gop_single);
                    $('#iparts-index-purch').val(dt.purch_part);
                    $('#iparts-index-spec').val(dt.spec);
                    $('#iparts-index-t').val(dt.ms_t);
                    $('#iparts-index-w').val(dt.ms_w);
                    $('#iparts-index-l').val(dt.ms_l);
                    $('#iparts-index-n').val(dt.ms_n_strip);
                    $('#iparts-index-cp').val(dt.ms_coil_pitch);
                    $('#iparts-index-weight').val(dt.part_weight);
                    $('#iparts-index-vendor').val(dt.vendor_name);
    
                    // $('#iparts-form-index input').prop('readonly', true);
                    // $('#iparts-form-index input[type=file]').prop('disabled', true);
                    // $('#iparts-form-index select').prop('disabled', true);
                });
            });
        });
    
        $(document).on('click', '.iparts-act-view', function () {
            let id = $(this).data('id');
            let route = "{{ route('tms.db_parts.parts.detail', [':id']) }}";
                route  = route.replace(':id', id);
            ajaxCall({ route: route, method: "GET"}).then(resolve => {
                let dt = resolve.content;
                let parent_no = (dt.parent_partno == undefined) ? null : dt.parent_partno;
                let parent_name = (dt.parent_partname == undefined) ? null : dt.parent_partname;

                modalAction('#iparts-modal-index').then(() => {
                    $('#iparts-index-id').attr('data-val', dt.id);
                    $('#id').val(dt.id);
    
                    $('#iparts-index-customercode').val(dt.cust_id);
                    $('#iparts-index-customername').val(dt.cust_name);
    
                    $('#iparts-index-ppartno').val(parent_no);
                    if (parent_no != null) {
                        $('#iparts-index-ppartno').attr('data-id', dt.parent_id)
                    }
                    $('#iparts-index-ppartname').val(parent_name);
    
                    $('#iparts-index-partno').val(dt.part_no);
                    $('#iparts-index-partname').val(dt.part_name);
                    $('#iparts-index-parttype').val(dt.type);
                    $('#iparts-index-pict-x').html(dt.part_pict);
                    $('#iparts-index-reff').val(dt.reff);
                    $('#iparts-index-vol').val(dt.part_vol);
                    $('#iparts-index-qty').val(dt.qty_part_item);
                    $('#iparts-index-gopassy').val(dt.gop_assy);
                    $('#iparts-index-gopsingle').val(dt.gop_single);
                    $('#iparts-index-purch').val(dt.purch_part);
                    $('#iparts-index-spec').val(dt.spec);
                    $('#iparts-index-t').val(dt.ms_t);
                    $('#iparts-index-w').val(dt.ms_w);
                    $('#iparts-index-l').val(dt.ms_l);
                    $('#iparts-index-n').val(dt.ms_n_strip);
                    $('#iparts-index-cp').val(dt.ms_coil_pitch);
                    $('#iparts-index-weight').val(dt.part_weight);
                    $('#iparts-index-vendor').val(dt.vendor_name);
    
                    $('#iparts-form-index input').prop('readonly', true);
                    $('#iparts-form-index input[type=file]').prop('disabled', true);
                    $('#iparts-form-index select').prop('disabled', true);
                    isHidden('#iparts-btn-index-submit')
                });
            });
        });
    
        $(document).on('click', '.iparts-act-delete', function () {
            let id = $(this).data('id');
            let route = "{{ route('tms.db_parts.parts.destroy', [':id']) }}";
                route  = route.replace(':id', id);
            Swal.fire({
                icon: 'warning',
                text: `Are you sure delete this item, Now?`,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then(answer => {
                if (answer.value == true) {
                    loading_start();
                    ajaxCall({route: route, method: "DELETE"}).then(resolve => {
                        loading_stop();
                        Swal.fire({
                            title: 'success',
                            text: resolve.message,
                            icon: 'success'
                        }).then(() => {
                            table_index.ajax.reload();
                        });
                    });
                }
            });
        });
    
        var tbl_log;
        $(document).on('click', '.iparts-act-log', function () {
            let id = $(this).data('id');
            modalAction('#iparts-modal-logs').then((resolve) => {
                tbl_log = $('#iparts-datatable-logs').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ route('tms.db_parts.parts.tools') }}",
                        method: 'POST',
                        data: {type: "logs", id: id},
                        headers: token_header
                    },
                    columns: [
                        {data: 'status', name: 'status'},
                        {data: 'date', name: 'date'},
                        {data: 'time', name: 'time'},
                        {data: 'note', name: 'note'},
                        {data: 'log_by', name: 'log_by'},
                    ],
                    ordering: false,
                });
            });
        });
    
        var tbl_trash;
        $('#iparts-btn-modal-trash').on('click', function () {
            modalAction('#iparts-modal-trash').then(() => {
                tbl_trash = $('#iparts-datatable-trash').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{route('tms.db_parts.parts.table_trash')}}",
                        method: 'POST',
                        headers: token_header,
                        data: {type: arr_url[5]}
                    },
                    columns: [
                        {data:'type', name: 'type', className: "align-middle"},
                        {data:'part_no', name: 'part_no', className: "align-middle"},
                        {data:'part_name', name: 'part_name', className: "align-middle"},
                        {data:'cust_id', name: 'cust_id', className: "align-middle"},
                        {data:'action', name: 'action', className: "align-middle text-center"},
                    ],
                    order: [[ 0, "asc" ]],
                });
            });
        });
    
        $(document).on('click', '.iparts-act-active', function () {
            loading_start();
            let id = $(this).data('id');
            let route = "{{ route('tms.db_parts.parts.to_active', [':id']) }}";
                route  = route.replace(':id', id);
            ajaxCall({route: route, method: "PUT"}).then(resolve => {
                loading_stop();
                Swal.fire({
                    title: 'Success',
                    text: resolve.message,
                    icon: 'success'
                }).then(() => {
                    modalAction('#iparts-modal-trash', 'hide').then(() => {
                        table_index.ajax.reload();
                    });
                });
            });
        });
    
        $('#iparts-index-pict').on('change', function(e){
            e.preventDefault();
            var fileName = $(this).val().replace('C:\\fakepath\\', " ");
            var ext = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(ext, fileName.length).toLowerCase();
            let oldName = $(this).next('#iparts-index-pict-x').html();
            if (!fileName) {
                fileName = 'Choose file';
            }
            if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){
                loading_start();
                let route = "{{ route('tms.db_parts.parts.upload_temp') }}";
                let formData = new FormData();
                formData.append('file', $('#iparts-index-pict')[0].files[0]);
                if ((oldName.lastIndexOf(".") + 1) > 0) {   
                    formData.append('old_file', oldName);
                }
                ajaxFormData(route, formData).then(resolve => {
                    loading_stop();
                    $(this).next('#iparts-index-pict-x').html(resolve.content);
                });
            }else{
                if (extFile != "") {
                    Swal.fire({
                        title: 'Something was wrong',
                        text: 'Sorry, extention not supported. Upload file only jpg, png or jpeg',
                        icon: 'warning'
                    });
                }else{
                    if ((oldName.lastIndexOf(".") + 1) > 0) {
                        let data = {type: "delete_temp", old_file: oldName};
                        ajaxCall({route: "{{route('tms.db_parts.parts.tools')}}", method: "POST", data:data});
                    }
                }
                $(this).next('#iparts-index-pict-x').html('Choose file');
                $(this).val(null);
            }
        });
    
        $(document).on('click', '.view-ppict', function () {
            let fileName = $('#iparts-index-pict-x').html();
            if ($('#iparts-index-pict-x').html() != 'Choose file') {
                modalAction('#iparts-modal-ppict').then(() => {
                    cekFile(`{{ asset('db-parts/pictures/${fileName}') }}`).then(
                        resolve => {
                            $('#view-ppict').attr('src', `{{ asset('db-parts/pictures/${fileName}') }}`);
                        }, 
                        reject => {
                            console.log(reject);
                            cekFile(`{{ asset('db-parts/temp/${fileName}') }}`).then(
                                resolve => {
                                    $('#view-ppict').attr('src', `{{ asset('db-parts/temp/${fileName}') }}`);
                                }, 
                                reject => {
                                    console.log(reject);
                                });
                        });
                });
            }
        });
    
        $('#iparts-modal-ppict').on('hidden.bs.modal', function () {
            $('#view-ppict').attr('src', '#');
        });

        // Production Process
        let tbl_prodpro = $('#prodpro-datatables-index').DataTable({
            destroy: true,
            lengthChange: false,
            searching: false,
            paging: false,
            ordering: false,
            scrollY: "300px",
            scrollCollapse: true,
            fixedHeader: true,
        })

        $(document).on('click', '.iparts-act-prodpro', function () {
            let id = $(this).data('id'),
                route = "{{ route('tms.db_parts.parts.prodpro', [':id']) }}";
                route  = route.replace(':id', id);
            loading_start();
            ajaxCall({route: route, method: "GET"}).then(response => {
                loading_stop();
                if (response.message == 0) {
                    modalAction('#prodpro-modal-jml').then(() => {
                        $('#prodpro-jml-id').val(id)
                    })
                }else{
                    let data = response.content;
                    modalAction('#prodpro-modal-index').then(() => {
                        $('#prodpro-index-id').val(id);
                        let element_dprocess = 0;
                        $.each(data, function (i, dt) {
                            ++i;
                            element_dprocess = (dt.id_detail_process == null) ? `<input type="text" name="prodpro-index-process_det[]" id="prodpro-index-process_det-${i}" class="form-control form-control-sm prodpro-index-process_det" data-i="${i}" value="" placeholder="Press ENTER" autocomplete="off">` : `<input type="text" name="prodpro-index-process_det[]" id="prodpro-index-process_det-${i}" class="form-control form-control-sm prodpro-index-process_det" data-i="${i}" data-process="${dt.id_detail_process}" value="${dt.process_detail_name}" placeholder="Press ENTER" autocomplete="off">`;
                            let add = tbl_prodpro.row.add([
                                dt.process_sequence_2,
                                `<select name="prodpro-index-process[]" id="prodpro-index-process-${i}" class="form-control form-control-sm prodpro-index-process" data-i="${i}"></select>`,
                                element_dprocess,
                                `<input type="number" class="form-control form-control-sm" id="prodpro-index-ct-${i}" value="${dt.ct_second}" autocomplete="off">`,
                                `<input type="text" class="form-control form-control-sm" id="prodpro-index-tools-${i}" value="${dt.tool_parts}" autocomplete="off">`,
                                `<select class="form-control form-control-sm" id="prodpro-index-tonage-${i}">
                                    <option value="&lt;35">&lt;35</option>
                                    <option value="35">35</option>
                                    <option value="45">45</option>
                                    <option value="55">55</option>
                                    <option value="60">60</option>
                                    <option value="65">65</option>
                                    <option value="80">80</option>
                                    <option value="85">85</option>
                                    <option value="100">100</option>
                                    <option value="110">110</option>
                                    <option value="150">150</option>
                                    <option value="160">160</option>
                                    <option value="200">200</option>
                                    <option value="250">250</option>
                                    <option value="300">300</option>
                                    <option value="400">400</option>
                                    <option value="500">500</option>
                                    <option value="550">550</option>
                                    <option value="630">630</option>
                                    <option value="&gt;650">&gt;650</option>
                                </select>`,
                                `<input type="text" name="prodpro-index-prodline[]" id="prodpro-index-prodline-${i}" class="form-control form-control-sm prodpro-index-prodline" data-i="${i}" value="${dt.production_line}" autocomplete="off" readonly>`,
                                `<input type="text" id="prodpro-index-company-${i}" class="form-control form-control-sm prodpro-index-company" data-i="${i}" value="${dt.company_name}" autocomplete="off">`,
                            ]);
                            tbl_prodpro.draw(false);
                            add.nodes().to$().attr('data-indexid', i);
                            $(`#prodpro-index-tonage-${i}`).val(dt.tonage)

                            loading_start();
                            ajaxCall({route: "{{ route('tms.db_parts.parts.tools') }}", method: "POST", data: {type: "get_process"} }).then(datap => {
                                loading_stop();
                                $(`#prodpro-index-process-${i}`).html('<option value="">Select process</option>');
                                $.each(datap.content, function (x, item) {
                                    $(`#prodpro-index-process-${i}`).append($('<option>', { 
                                        value: item.process_id,
                                        text : item.process_name
                                    }).attr('data-routing', item.routing));
                                });
                                $('#prodpro-index-process-' +i).val(dt.id_process)
                            });
                        })
                    });
                }
            })
        })
        $('#prodpro-modal-jml').on('hidden.bs.modal', function () {
            $('#prodpro-jml-id').val(0)
            $('#prodpro-jml').val(null)
        })

        $(document).on('submit', '#prodpro-form-jml', function (e) {
            e.preventDefault()
            let id = $('#prodpro-jml-id').val();
            let jml = $('#prodpro-jml').val();
            let data = {
                jml: jml,
                id_part: id
            };
            modalAction('#prodpro-modal-jml', 'hide')
            modalAction('#prodpro-modal-index').then(() => {
                $('#prodpro-index-id').val(id);
                for (let i = 1; i <= parseInt(jml); i++) {
                    let add = tbl_prodpro.row.add([
                        i,
                        `<select name="prodpro-index-process[]" id="prodpro-index-process-${i}" class="form-control form-control-sm prodpro-index-process" data-i="${i}"></select>`,
                        `<input type="text" name="prodpro-index-process_det[]" id="prodpro-index-process_det-${i}" class="form-control form-control-sm prodpro-index-process_det" data-i="${i}" placeholder="Press ENTER" autocomplete="off">`,
                        `<input type="number" class="form-control form-control-sm" value="" autocomplete="off">`,
                        `<input type="text" class="form-control form-control-sm" value="" autocomplete="off">`,
                        `<select class="form-control form-control-sm">
                            <option value="&lt;35">&lt;35</option>
                            <option value="35">35</option>
                            <option value="45">45</option>
                            <option value="55">55</option>
                            <option value="60">60</option>
                            <option value="65">65</option>
                            <option value="80">80</option>
                            <option value="85">85</option>
                            <option value="100">100</option>
                            <option value="110">110</option>
                            <option value="150">150</option>
                            <option value="160">160</option>
                            <option value="200">200</option>
                            <option value="250">250</option>
                            <option value="300">300</option>
                            <option value="400">400</option>
                            <option value="500">500</option>
                            <option value="550">550</option>
                            <option value="630">630</option>
                            <option value="&gt;650">&gt;650</option>
                        </select>`,
                        `<input type="text" name="prodpro-index-prodline[]" id="prodpro-index-prodline-${i}" class="form-control form-control-sm prodpro-index-prodline" data-i="${i}" autocomplete="off" readonly>`,
                        `<input type="text" id="prodpro-index-company-${i}" class="form-control form-control-sm prodpro-index-company" data-i="${i}" autocomplete="off">`,
                    ]);
                    tbl_prodpro.draw(false);
                    add.nodes().to$().attr('data-indexid', i);

                    loading_start();
                    ajaxCall({route: "{{ route('tms.db_parts.parts.tools') }}", method: "POST", data: {type: "get_process"} }).then(data => {
                        loading_stop();
                        $(`#prodpro-index-process-${i}`).html('<option value="">Select process</option>');
                        $.each(data.content, function (x, item) {
                            $(`#prodpro-index-process-${i}`).append($('<option>', { 
                                value: item.process_id,
                                text : item.process_name
                            }).attr('data-routing', item.routing));
                        });
                    });
                }
            })
        })

        $(document).on('click', '#prodpro-btn-add-item', function () {
            let index = tbl_prodpro.data().length;
            let last = tbl_prodpro.row(':last').node();
            let idx = $(last).data('indexid');
            console.log(idx);
            let i = ++index;
            let x = (idx == undefined) ? 1 : ++idx;

            let add = tbl_prodpro.row.add([
                i,
                `<select name="prodpro-index-process[]" id="prodpro-index-process-${x}" class="form-control form-control-sm prodpro-index-process" data-i="${x}"></select>`,
                `<input type="text" name="prodpro-index-process_det[]" id="prodpro-index-process_det-${x}" class="form-control form-control-sm prodpro-index-process_det" data-i="${x}" placeholder="Press ENTER" autocomplete="off">`,
                `<input type="number" class="form-control form-control-sm" value="" autocomplete="off">`,
                `<input type="text" class="form-control form-control-sm" value="" autocomplete="off">`,
                `<select class="form-control form-control-sm">
                    <option value="&lt;35">&lt;35</option>
                    <option value="35">35</option>
                    <option value="45">45</option>
                    <option value="55">55</option>
                    <option value="60">60</option>
                    <option value="65">65</option>
                    <option value="80">80</option>
                    <option value="85">85</option>
                    <option value="100">100</option>
                    <option value="110">110</option>
                    <option value="150">150</option>
                    <option value="160">160</option>
                    <option value="200">200</option>
                    <option value="250">250</option>
                    <option value="300">300</option>
                    <option value="400">400</option>
                    <option value="500">500</option>
                    <option value="550">550</option>
                    <option value="630">630</option>
                    <option value="&gt;650">&gt;650</option>
                </select>`,
                `<input type="text" name="prodpro-index-prodline[]" id="prodpro-index-prodline-${x}" class="form-control form-control-sm prodpro-index-prodline" data-i="${x}" autocomplete="off" readonly>`,
                `<input type="text" id="prodpro-index-company-${x}" class="form-control form-control-sm prodpro-index-company" data-i="${x}" autocomplete="off">`,
            ]);
            tbl_prodpro.draw(false);
            add.nodes().to$().attr('data-indexid', x);
            loading_start();
            ajaxCall({route: "{{ route('tms.db_parts.parts.tools') }}", method: "POST", data: {type: "get_process"} }).then(data => {
                loading_stop();
                $(`#prodpro-index-process-${x}`).html('<option value="">Select process</option>');
                $.each(data.content, function (xx, item) {
                    $(`#prodpro-index-process-${x}`).append($('<option>', { 
                        value: item.process_id,
                        text : item.process_name
                    }).attr('data-routing', item.routing));
                });
            });
        });

        $('#prodpro-modal-index').on('shown.bs.modal', function () {
            adjustDraw(tbl_prodpro)
        })
        $('#prodpro-modal-index').on('hidden.bs.modal', function () {
            tbl_prodpro.clear().draw(false);
            $('#prodpro-index-id').val(0);
        });

        $('#prodpro-datatables-index tbody').off('dblclick', 'tr').on('dblclick', 'tr', function () {
            let data = tbl_prodpro.row(this).data();
            if (data != undefined) {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                    $('#prodpro-btn-delete-item').prop('disabled', true);
                }else {
                    tbl_prodpro.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    $('#prodpro-btn-delete-item').removeAttr('disabled');
                }
            }
        });
        $(document).on('click', '#prodpro-btn-delete-item', function () {
            let tbl = tbl_prodpro.row('.selected').data();
            let id = tbl[1];
    
            tbl_prodpro.row('.selected').remove().draw( false );
            for (let i = 0; i < tbl_prodpro.rows().data().toArray().length; i++) {
                let drw = tbl_prodpro.cell( i, 0 ).data(1+i);
            }
            tbl_prodpro.draw(false);
            
            $('#prodpro-btn-delete-item').prop('disabled', true);
        });

        let tbl_detail_process;
        $(document).on('keypress keydown', '.prodpro-index-process_det', function (e) {
            if(e.which == 13) {
                let i = $(this).data('i'),
                    process = $(`#prodpro-index-process-${i}`).val()
                modalAction('#prodpro-modal-dprocess').then(() => {
                    $('#process_detail_ke').val(i);
                    tbl_detail_process = $('#prodpro-datatable-dprocess').DataTable({
                        processing: true,
                        serverSide: true,
                        destroy: true,
                        ordering: false,
                        ajax: {
                            url: "{{ route('tms.db_parts.parts.tools') }}",
                            method: 'POST',
                            headers: token_header,
                            data: {type: "get_detail_process", process: process},
                        },
                        columns: [
                            {data:'process_name', name: 'process_name'},
                            {data:'process_detail_name', name: 'process_detail_name'},
                        ],
                    });
                })
            }
            if(e.which == 8 || e.which == 46) { return false; }
            return false;
        });

        $('#prodpro-datatable-dprocess').off('dblclick', 'tr').on('dblclick', 'tr', function () {
            let data = tbl_detail_process.row(this).data(),
                i = $('#process_detail_ke').val()
            modalAction('#prodpro-modal-dprocess', 'hide').then(() => {
                $('#prodpro-index-process_det-' + i).val(data.process_detail_name)
                $('#prodpro-index-process_det-' + i).attr('data-process', data.process_detail_id)
                $('#prodpro-index-prodline-' + i).val(data.routing)
            })
        })

        $('#prodpro-modal-dprocess').on('hidden.bs.modal', function () {
            $('#process_detail_ke').val(null)
        })

        $(document).on('change', '.prodpro-index-process', function () {
            let i = $(this).data('i')
            $('#prodpro-index-process_det-' + i).val(null);
            $('#prodpro-index-prodline-' + i).val($(this).find('option:selected').data('routing'));
            if ($(this).find('option:selected').data('routing') == 'INHOUSE') {
                $('#prodpro-index-company-' + i).val('TCH')
                $('#prodpro-index-company-' + i).prop('readonly', true)
            }else{
                $('#prodpro-index-company-' + i).val(null)
                $('#prodpro-index-company-' + i).prop('readonly', false)
            }
        })

        $(document).on('submit', '#prodpro-form-index', function (e) {
            e.preventDefault();
            let items = tbl_prodpro.rows().data().toArray();
            let items_fix = [];
            if (items.length > 0) {
                for (let i = 0; i < items.length; i++) {
                    let obj_tbl_index = {}

                    let process = tbl_prodpro.rows().cell(i, 1).nodes().to$().find('select').val();
                    let dprocess = tbl_prodpro.rows().cell(i, 2).nodes().to$().find('input').data('process');
                    let ct = tbl_prodpro.rows().cell(i, 3).nodes().to$().find('input').val();
                    let tool = tbl_prodpro.rows().cell(i, 4).nodes().to$().find('input').val();
                    let tonage = tbl_prodpro.rows().cell(i, 5).nodes().to$().find('select').val();
                    let routing = tbl_prodpro.rows().cell(i, 6).nodes().to$().find('input').val();
                    let company = tbl_prodpro.rows().cell(i, 7).nodes().to$().find('input').val();

                    if (process == "" || ct == "" || tool == "" || tonage == "" || routing == "" || company == "") {
                        Swal.fire({
                            title: 'Warning',
                            text: 'Harap lengkapi table input',
                            icon: 'warning'
                        }).then(() => {
                            return false;
                        });
                    }

                    obj_tbl_index.process = process;
                    obj_tbl_index.dprocess = (dprocess == undefined) ? null : dprocess;
                    obj_tbl_index.ct = ct;
                    obj_tbl_index.tool = tool;
                    obj_tbl_index.tonage = tonage;
                    obj_tbl_index.routing = routing;
                    obj_tbl_index.company = company;

                    items_fix.push(obj_tbl_index);
                }

                // console.log(items_fix);
                let data = {
                    part_id: $('#prodpro-index-id').val(),
                    items: JSON.stringify(items_fix)
                }

                let id = data.part_id,
                    route = "{{ route('tms.db_parts.parts.prodpro', [':id']) }}";
                    route  = route.replace(':id', id);
                let method = 0;
                loading_start();
                ajaxCall({route: route, method: "GET"}).then(response => {
                    if (response.message == 1) {
                        route = "{{ route('tms.db_parts.parts.prodpro.update', [':id']) }}";
                        route  = route.replace(':id', id);
                        method = "PUT";
                    }else{
                        route = "{{ route('tms.db_parts.parts.prodpro.store') }}";
                        method = "POST";
                    }

                    ajaxCall({route: route, method: method, data: data}).then(response => {
                        loading_stop();
                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            modalAction('#prodpro-modal-index', 'hide')
                        });
                    })
                })
            }else{
                Swal.fire({
                    title: 'Warning',
                    text: 'Harap lengkapi table input',
                    icon: 'warning'
                }).then(() => {
                    return false;
                });
            }
        });
    
        // Lib func
        function date_convert($date) {
            if ($date.length < 0) { return null; }
            return $date.split(' ')[0].split('-').reverse().join('-');
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
    
        function loading_start() {
            $('body').loading({
                message: "wait for a moment...",
                zIndex: 9999
            });
        }
    
        function loading_stop() {
            $('body').loading('stop');
        }
    
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
                        $('body').loading('stop');
                        Swal.fire({
                            title: 'Something was wrong',
                            text: response.responseJSON.message,
                            icon: 'error'
                        }).then(() => {
                            console.clear();
                            reject(response);
                        });
                    },
                    complete: function (response){
                        resolve(response);
                    }
                });
            });
        }
        function ajaxFormData(route, formData) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: route,
                    method: "POST",
                    data: formData,
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    headers: token_header,
                    error: function(response, status, x){
                        console.log(response);
                        loading_stop();
                        Swal.fire({
                            title: 'Something was wrong',
                            text: response.responseJSON.message,
                            icon: 'error'
                        }).then(() => {
                            // console.clear();
                        });
                    },
                    complete: function (response){
                        resolve(response);
                    }
                });
            });
        }
        function cekFile(url) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: url,
                    type:'HEAD',
                    error: function() {
                        reject('Not Exist');
                    },
                    success: function(){
                        resolve('Exist!');
                    }
                });
            })
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