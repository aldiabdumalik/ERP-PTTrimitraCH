<script>
$(document).ready(function () {
    const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
    const table_index = $('#prodcode-datatable').DataTable();

    $('#prodcode-btn-modal-create').on('click', function () {
        modalAction('#prodcode-modal-index');
    });

    var tbl_item = $('#prodcode-datatables-index').DataTable({
        destroy: true,
        lengthChange: false,
        searching: false,
        paging: false,
        ordering: false,
        scrollY: "200px",
        scrollCollapse: true,
        fixedHeader: true,
    });
    var tbl_part;
    $(document).on('keypress keydown', '#prodcode-index-partno', function (e) {
        if(e.which == 13) {
            modalAction('#prodcode-modal-part').then(resolve => {
                tbl_part = $('#prodcode-datatables-part').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ordering: false,
                    ajax: {
                        url: "{{ route('tms.db_parts.production_code.header_tools') }}",
                        method: 'POST',
                        headers: token_header,
                        data: {type: "get_part"},
                    },
                    columns: [
                        {data:'part_no', name: 'part_no'},
                        {data:'part_name', name: 'part_name'},
                        {data:'type', name: 'type'},
                    ],
                });

                resolve.on('shown.bs.modal', function () {
                    $('#prodcode-datatables-part_filter input').focus();
                });
            });
        }
        if(e.which == 8 || e.which == 46) { return false; }
        return false;
    });

    $('#prodcode-datatables-part').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        let data = tbl_part.row(this).data();
        modalAction('#prodcode-modal-part', 'hide').then(resolve => {
            $('#prodcode-index-id').val(data.id);
            $('#prodcode-index-partno').val(data.part_no);
            $('#prodcode-index-partname').val(data.part_name);
            $('#prodcode-index-parttype').val(data.type);
            $('#prodcode-index-pict-x').text(data.part_pict);
        });
    });

    $(document).on('click', '#prodcode-btn-add-item', function () {
        var index = tbl_item.data().length;
        let i = ++index;

        let add = tbl_item.row.add([
            i,
            // `<input type="text" class="form-control form-control-sm" value="">`,
            `<select name="prodcode-index-process[]" id="prodcode-index-process-${i}" class="form-control form-control-sm prodcode-index-process" data-i="${i}"></select>`,
            `<input type="text" name="prodcode-index-process_det[]" id="prodcode-index-process_det-${i}" class="form-control form-control-sm prodcode-index-process_det" data-i="${i}" placeholder="Press ENTER" autocomplete="off">`,
            `<input type="text" class="form-control form-control-sm" value="" autocomplete="off">`,
            `<input type="text" class="form-control form-control-sm" value="" autocomplete="off">`,
            `<input type="text" class="form-control form-control-sm" value="" autocomplete="off">`,
            `<input type="text" name="prodcode-index-prodline[]" id="prodcode-index-prodline-${i}" class="form-control form-control-sm prodcode-index-prodline" data-i="${i}" autocomplete="off" readonly>`,
            `<input type="text" class="form-control form-control-sm" value="" autocomplete="off">`,
        ]);
        tbl_item.draw(false);
        loading_start();
        ajaxCall({route: "{{ route('tms.db_parts.production_code.header_tools') }}", method: "POST", data: {type: "get_process"} }).then(data => {
            loading_stop();
            $(`#prodcode-index-process-${i}`).html('<option value="">Select process</option>');
            $.each(data.content, function (x, item) {
                $(`#prodcode-index-process-${i}`).append($('<option>', { 
                    value: item.process_id,
                    text : item.process_name
                }).attr('data-routing', item.routing));
            });
        });
    });
    let tbl_detail_process;
    $(document).on('keypress keydown', '.prodcode-index-process_det', function (e) {
        if(e.which == 13) {
            let i = $(this).data('i'),
                process = $(`#prodcode-index-process-${i}`).val()
            modalAction('#prodcode-modal-dprocess').then(() => {
                $('#process_detail_ke').val(i);
                tbl_detail_process = $('#prodcode-datatable-dprocess').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ordering: false,
                    ajax: {
                        url: "{{ route('tms.db_parts.production_code.header_tools') }}",
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

    $('#prodcode-datatable-dprocess').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        let data = tbl_detail_process.row(this).data(),
            i = $('#process_detail_ke').val()
        modalAction('#prodcode-modal-dprocess', 'hide').then(() => {
            $('#prodcode-index-process_det-' + i).val(data.process_detail_name)
            $('#prodcode-index-process_det-' + i).attr('data-process', data.process_detail_id)
            $('#prodcode-index-prodline-' + i).val(data.routing)
        })
    })

    $('#prodcode-modal-dprocess').on('hidden.bs.modal', function () {
        $('#process_detail_ke').val(null)
    })

    $(document).on('change', '.prodcode-index-process', function () {
        let i = $(this).data('i')
        $('#prodcode-index-process_det-' + i).val(null);
        $('#prodcode-index-prodline-' + i).val($(this).find('option:selected').data('routing'));
    })

    $(document).on('click', '.view-ppict', function () {
        let fileName = $('#prodcode-index-pict-x').html();
        if ($('#prodcode-index-pict-x').html() != 'Choose file') {
            modalAction('#prodcode-modal-ppict').then(() => {
                cekFile(`{{ asset('db-parts/pictures/${fileName}') }}`).then(
                    resolve => {
                        $('#view-ppict').attr('src', `{{ asset('db-parts/pictures/${fileName}') }}`);
                    }, 
                    reject => {
                        cekFile(`{{ asset('db-parts/temp/${fileName}') }}`).then(
                            resolve => {
                                $('#view-ppict').attr('src', `{{ asset('db-parts/temp/${fileName}') }}`);
                            }, 
                            reject => {});
                    });
            });
        }
    });

    $(document).on('submit', '#prodcode-form-index', function (e) {
        e.preventDefault();
        let items = tbl_item.rows().data().toArray();
        let items_fix = [];
        if (items.length > 0) {
            for (let i = 0; i < items.length; i++) {
                let obj_tbl_index = {}

                let process = tbl_item.rows().cell(i, 1).nodes().to$().find('select').val();
                let dprocess = tbl_item.rows().cell(i, 2).nodes().to$().find('input').data('process');
                let ct = tbl_item.rows().cell(i, 3).nodes().to$().find('input').val();
                let tool = tbl_item.rows().cell(i, 4).nodes().to$().find('input').val();
                let tonage = tbl_item.rows().cell(i, 5).nodes().to$().find('input').val();
                let routing = tbl_item.rows().cell(i, 6).nodes().to$().find('input').val();
                let company = tbl_item.rows().cell(i, 7).nodes().to$().find('input').val();

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

            let data = {
                part_id: $('#prodcode-index-partno').val(),
                items: JSON.stringify(items_fix)
            }

            let url = "{{ route('tms.db_parts.production_code.store') }}";
            // console.log(url);

            ajaxCall({route: url, method: "POST", data: data}).then(response => {
                console.log(response);
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

    $('#prodcode-modal-index').on('shown.bs.modal', function () {
        adjustDraw(tbl_item)
    });

    $('#prodcode-modal-index').on('hidden.bs.modal', function () {
        tbl_item.clear().draw(false);
        $('#prodcode-form-index').trigger('reset')
        $('#prodcode-index-id').val(0);
        $('#prodcode-index-pict-x').text('Pricture Name')
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