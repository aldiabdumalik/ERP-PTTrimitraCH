@extends('master')
@section('title', 'TMS | Manufacturing - THP Entry')
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
</style>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="addModal">
                    <i class="ti-plus"></i>  Add New Data
                </button>
                <button type="button"  class="btn btn-outline-success btn-flat btn-sm" id="importModal">
                    <i class="fa fa-upload"></i>  Import
                </button>
                <button type="button"  class="btn btn-outline-primary btn-flat btn-sm" id="printModal">
                    <i class="fa fa-print"></i>  Report
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">THP Entry</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="thp-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer">
                                        <thead class="text-center" style="font-size: 15px;">
                                            <tr>
                                                <th class="align-middle">THP Number</th>
                                                <th class="align-middle">Date</th>
                                                {{-- <th class="align-middle">Closed</th> --}}
                                                <th class="align-middle">Customer</th>
                                                <th class="align-middle">Production Code</th>
                                                <th class="align-middle">Part Name</th>
                                                <th class="align-middle">Part Type</th>
                                                <th class="align-middle">Route</th>
                                                <th class="align-middle">Process</th>
                                                <th class="align-middle">Shift</th>
                                                <th class="align-middle">Group</th>
                                                <th class="align-middle">THP Qty</th>
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
</div>
{{-- @include('tms.manufacturing.thp_entry._modal.create_thp_modal._createthp') --}}
@include('tms.manufacturing.thp_entry._modal.create.createForm')
@include('tms.manufacturing.thp_entry._modal.view_thp_modal._productioncode')
{{-- @include('tms.manufacturing.thp_entry._modal.view_thp_modal._viewthp') --}}
@include('tms.manufacturing.thp_entry._modal.detail.indexDetail')
@include('tms.manufacturing.thp_entry._modal.view_thp_modal._viewlog')
@include('tms.manufacturing.thp_entry._modal.view_thp_modal._printThp')
@include('tms.manufacturing.thp_entry._modal.import.importThp')
@include('tms.manufacturing.thp_entry._modal.close_thp_modal._closethp')


@endsection

@section('script')
<script>
$(document).ready(function(){
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
        },
        columns: [
            {data: 'id_thp', name: 'id_thp', searchable: false},
            {data: 'thp_date', name: 'thp_date', className: "text-center"},
            // {data: 'closed', name: 'closed', className: "text-center"},
            {data: 'customer_code', name: 'customer_code'},
            {data: 'production_code', name: 'production_code'},
            {data: 'part_name', name: 'part_name'},
            {data: 'part_type', name: 'part_type'},
            {data: 'route', name: 'route'},
            {data: 'process', name: 'process', orderable: false, searchable: false},
            {data: 'shift', name: 'shift', searchable: false},
            {data: 'group', name: 'group', searchable: false},
            {data: 'thp_qty', name: 'thp_qty', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        "order": [[ 1, "desc" ]],
        initComplete: function(settings, json) {
            $('.thp-act-view').on('click', function () {
                // $('#viewThpid').modal('show');
                // viewThp($(this).data('thp'));
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

                        $('#thp-detail-itemcode').val(data.item_code);
                        $('#thp-detail-production-process').val(data.production_process);
                        $('#thp-detail-operator').val(data.user);
                        sgm = data.thp_remark.split('_');
                        shift = sgm[0].split('');
                        $('#thp-detail-shift').val(shift[0]);
                        $('#thp-detail-grup').val(shift[1]);
                        $('#thp-detail-machine').val(sgm[1]);

                        apnormality = (data.apnormality != null ? data.apnormality : '//');
                        action_plan = (data.action_plan != null ? data.action_plan : '//');
                        $('#thp-detail-note').val(data.note);
                        $('#thp-detail-apnormal').val(apnormality);
                        $('#thp-detail-action-plan').val(action_plan);
                    }
                });
            });
            $('.thp-act-edit').on('click', function () {
                getThp($(this).data('thp'), function (response) {
                    response = response.responseJSON;
                    $('#createModal').modal('show');
                    if (response.status == true) {
                        var data = response.data;
                        var action_plan, date, apnormality, note, sgm, shift, grup, machine;
                        date = data.thp_date.split('-');
                        date = date[2] + '/' + date[1] + '/' + date[0];
                        $('#thp-id').val(data.id_thp);
                        $('#thp-date').val(date);
                        $('#thp-production-code').val(data.production_code);
                        $('#thp-part-number').val(data.part_number);
                        $('#thp-part-name').val(data.part_name);
                        $('#thp-part-type').val(data.part_type);
                        $('#thp-customer-code').val(data.customer_code);
                        $('#thp-route').val(data.route);
                        $('#thp-plan').val(data.plan);
                        $('#thp-ct').val(data.ct);
                        $('#thp-ton').val(data.ton);
                        $('#thp-time').val(data.time);
                        $('#thp-plan-hour').val(data.plan_hour);
                        $('#thp-process-1').val(data.process_sequence_1);
                        $('#thp-process-2').val(data.process_sequence_2);
                        $('#thp-qty').val(data.thp_qty);
                        $('#thp-itemcode').val(data.item_code);
                        $('#thp-production-process').val(data.production_process);
                        sgm = data.thp_remark.split('_');
                        shift = sgm[0].split('');

                        $('#thp-note').val(data.note);
                        $('#thp-apnormal').val(data.apnormality);
                        $('#thp-action-plan').val(data.action_plan);
                        getShiftGrupMachine('SHIFT', null, function (response) {
                            $('#thp-shift option[value!=""]').remove();
                            $.each(response.responseJSON.data, function (res, data) {
                                $('#thp-shift').append($('<option>', {
                                    value: data.oee_workshift,
                                    text: data.oee_workshift
                                }));
                            });
                            $('#thp-shift').val(shift[0]);
                            getShiftGrupMachine('GRUP', null, function (response) {
                                $('#thp-grup option[value!=""]').remove();
                                $.each(response.responseJSON.data, function (res, data) {
                                    $('#thp-grup').append($('<option>', {
                                        value: data.employee_group,
                                        text: data.employee_group
                                    }));
                                });
                                $('#thp-grup').val(shift[1]);
                            });
                        });
                        getShiftGrupMachine('MACHINE', data.production_process, function (response) {
                            $('#thp-machine option[value!=""]').remove();
                            $.each(response.responseJSON.data, function (res, data) {
                                $('#thp-machine').append($('<option>', {
                                    value: data.machine_number,
                                    text: data.machine_number
                                }));
                            });
                            $('#thp-machine').val(sgm[1]);
                        });
                    }
                });
            }).on('mouseup',function(){
                setTimeout(function(){ 
                    // $('#thp-form-create input,textarea').removeAttr('readonly');
                    // $('#thp-form-create select').removeAttr('disabled');
                    // $('#thp-edit-btn').prop('hidden', 'hidden');
                    // $('#thp-btn-production-code').removeAttr('disabled');
                    $('.thp-create-btn').text('Update');
                    $('.thp-create-btn').css({'display': 'block'});
                }, 1000);
            });
            $('.thp-act-log').on('click', function () {
                $('#thp-log-modal').modal('show');
                log_tbl($(this).data('thp'));
            });
            $('.thp-act-close').on('click', function () {
                var id = $(this).data('thp');
                close_thpentry(id);
            });
        }
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
        $('#createModal').modal('show');
        getShiftGrupMachine('SHIFT', null, function (response) {
            $('#thp-shift option[value!=""]').remove();
            $.each(response.responseJSON.data, function (res, data) {
                $('#thp-shift').append($('<option>', {
                    value: data.oee_workshift,
                    text: data.oee_workshift
                }));
            });
            getShiftGrupMachine('GRUP', null, function (response) {
                $('#thp-grup option[value!=""]').remove();
                $.each(response.responseJSON.data, function (res, data) {
                    $('#thp-grup').append($('<option>', {
                        value: data.employee_group,
                        text: data.employee_group
                    }));
                });
            });
        });
    });
    $(document).on('click', '#printModal', function(e) {
        e.preventDefault();
        $('#thp-print-modal').modal('show');
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
                        window.location.reload();
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
        });
        $('#thp-poduction-code-datatables tbody').off('click').on('click', 'tr', function () {
            var data = tbl_production_code.row(this).data();
            var process;
            $('#thp-part-number').val(data.part_number);
            $('#thp-part-name').val(data.part_name);
            $('#thp-part-type').val(data.part_type);
            $('#thp-production-code').val(data.production_code);
            $('#thp-customer-code').val(data.customer_id);
            $('#thp-route').val(data.process_detailname);
            processs = data.process.split('/');
            $('#thp-process-1').val(processs[0]);
            $('#thp-process-2').val(processs[1]);
            $('#thp-ct').val(data.ct_sph);
            $('#thp-itemcode').val(data.item_code);
            $('#thp-production-process').val(data.production_process);

            $('#poduction-code-modal').modal('hide');

            getShiftGrupMachine('MACHINE', data.production_process, function (response) {
                $('#thp-machine option[value!=""]').remove();
                $.each(response.responseJSON.data, function (res, data) {
                    $('#thp-machine').append($('<option>', {
                        value: data.machine_number,
                        text: data.machine_number
                    }));
                });
            });
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
        var encrypt = btoa(`${$('#thp_print_dari').val()}&${$('#thp_print_sampai').val()}&${$('#thp_print_process').val()}`);
        var url = '{{route('tms.manufacturing.thp_entry.printThpEntry')}}?print=' + encrypt;
        window.open(url, '_blank');
    });
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
            type: "GET",
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
});
</script>
@endsection


@push('js')
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('/vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>
    $('.print-datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    }).datepicker("setDate",'now');
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