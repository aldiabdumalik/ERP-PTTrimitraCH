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
</style>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="addModal">
                    <i class="ti-plus"></i>  Add New Data
                </button>
                <button type="button"  class="btn btn-outline-primary btn-flat btn-sm" id="printModal">
                    <i class="fa fa-print"></i>  Print
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
                                                <th rowspan="2" class="align-middle">id</th>
                                                <th rowspan="2" class="align-middle">Written</th>
                                                <th rowspan="2" class="align-middle">Closed</th>
                                                <th rowspan="2" class="align-middle">Customer</th>
                                                <th rowspan="2" class="align-middle">Production Code</th>
                                                <th rowspan="2" class="align-middle">Part Name</th>
                                                <th rowspan="2" class="align-middle">Part Type</th>
                                                <th rowspan="2" class="align-middle">Route</th>
                                                <th rowspan="2" class="align-middle">Process</th>
                                                <th colspan="2">Plan THP</th>
                                                <th colspan="3">Actual</th>
                                                <th rowspan="2" class="align-middle">Action</th>
                                            </tr>
                                            <tr>
                                                <th>Shift 1</th>
                                                <th>Shift 2</th>
                                                <th>Shift 1</th>
                                                <th>Shift 2</th>
                                                <th>%</th>
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
@include('tms.manufacturing.thp_entry._modal.create_thp_modal._createthp')
@include('tms.manufacturing.thp_entry._modal.view_thp_modal._productioncode')
@include('tms.manufacturing.thp_entry._modal.view_thp_modal._viewlog')
@include('tms.manufacturing.thp_entry._modal.view_thp_modal._printThp')
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
            {data: 'date', name: 'date', className: "text-center"},
            {data: 'closed', name: 'closed', className: "text-center"},
            {data: 'id_cust', name: 'id_cust'},
            {data: 'production_code', name: 'production_code'},
            {data: 'part_name', name: 'part_name'},
            {data: 'part_type', name: 'part_type'},
            {data: 'route', name: 'route'},
            {data: 'process', name: 'process', orderable: false, searchable: false},
            {data: 'plan_1', name: 'plan_1', orderable: false, searchable: false},
            {data: 'plan_2', name: 'plan_2', orderable: false, searchable: false},
            {data: 'actual_1', name: 'actual_1', orderable: false, searchable: false},
            {data: 'actual_2', name: 'actual_2', orderable: false, searchable: false},
            {data: 'persentase', name: 'persentase', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        "order": [[ 1, "desc" ]],
        initComplete: function(settings, json) {
            // $('#thp-datatables tbody').on('click', 'tr', function () {
            //     var dataArr = [];
            //     var rows = $(this);
            //     var rowData = tbl_index.rows(rows).data();
            //     $.each($(rowData),function(key, data){
            //         getThp(data.id_thp);
            //     });
            // });
            $('.thp-act-view').on('click', function () {
                getThp($(this).data('thp'));
            });
            $('.thp-act-edit').on('click', function () {
                getThp($(this).data('thp'));
            }).on('mouseup',function(){
                setTimeout(function(){ 
                    // $('#thp-edit-btn').trigger('click');
                    $('#thp-form-create input,textarea').removeAttr('readonly');
                    $('#thp-form-create select').removeAttr('disabled');
                    $('#thp-edit-btn').prop('hidden', 'hidden');
                    $('#thp-btn-production-code').removeAttr('disabled');
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
    tbl_index.column( 0 ).visible( false );
    var tbl_create = $('#thp-create-datatables').DataTable({
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "ordering": false,
    });
    $(document).on('click', '#addModal', function(e) {
        e.preventDefault();
        // $('#createModal').after('#mtoModal');
        $('#createModal').modal('show');
    });
    $(document).on('click', '#printModal', function(e) {
        e.preventDefault();
        // $('#createModal').after('#mtoModal');
        $('#thp-print-modal').modal('show');
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
            "id_thp": $('#thp-id').data('id'),
            "production_code": $('#thp-production-code').val(),
            "part_number": $('#thp-part-number').val(),
            "part_name": $('#thp-part-name').val(),
            "part_type": $('#thp-part-type').val(),
            "customer_code": $('#thp-customer-code').val(),
            "route": $('#thp-route').val(),
            "process_1": $('#thp-process-1').val(),
            "process_2": $('#thp-process-2').val(),
            "plan": $('#thp-plan').val(),
            "ct": $('#thp-ct').val(),
            "ton": $('#thp-ton').val(),
            "time": $('#thp-time').val(),
            "plan_hour": $('#thp-plan-hour').val(),
            "plan_1": $('#thp-plan-1').val(),
            "plan_2": $('#thp-plan-2').val(),
            "actual_1": $('#thp-actual-1').val(),
            "actual_2": $('#thp-actual-2').val(),
            // "act_hour": $('#thp-act-hour').val(),
            "note": $('#thp-note').val(),
            "apnormal": $('#thp-apnormal').val(),
            "action_plan": $('#thp-action-plan').val(),
            "_token": $('meta[name="csrf-token"]').attr('content')
        };
        // console.log(data);
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
                // console.log(response);
                if(response.status == true){
                    $('#createModal').modal('hide');
                    $('#thp-form-create').trigger("reset");
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success'
                    }).then(function(){
                        // tbl_index.ajax.reload();
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

    $(document).on('hidden.bs.modal', '#createModal', function () {
        $('#thp-form-create input,textarea').removeAttr('readonly');
        $('#thp-form-create select').removeAttr('disabled');
        $('#thp-edit-btn').prop('hidden', 'hidden');
        $('#thp-btn-production-code').removeAttr('disabled');
        $('#thp-form-create').trigger('reset');
        $('#thp-edit-btn').prop('hidden', 'hidden');
        $('.thp-create-btn').css({'display': 'block'});
        $('.thp-create-btn').text('Simpan');
        $('#thp-id').attr('data-id', 0);
    });
    $(document).on('click', '#thp-edit-btn', function (e) {
        $('#thp-form-create input,textarea').removeAttr('readonly');
        $('#thp-form-create select').removeAttr('disabled');
        $('#thp-edit-btn').prop('hidden', 'hidden');
        $('#thp-btn-production-code').removeAttr('disabled');
        $('.thp-create-btn').text('Update');
        $('.thp-create-btn').css({'display': 'block'});
    });

    function getThp(id="") {
        var route  = "{{ route('tms.manufacturing.thp_entry.dataTable_edit', ':id') }}";
            route  = route.replace(':id', id);
        $.ajax({
            url: route,
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.status == true) {
                    var data = response.data;
                    // console.log(data.id_thp);
                    $('#thp-id').attr('data-id', data.id_thp);
                    $('#thp-production-code').val(data.production_code);
                    $('#thp-part-number').val(data.part_number);
                    $('#thp-part-name').val(data.part_name);
                    $('#thp-part-type').val(data.part_type);
                    $('#thp-customer-code').val(data.id_cust);
                    $('#thp-route').val(data.route);
                    $('#thp-plan').val(data.plan);
                    $('#thp-ct').val(data.ct);
                    $('#thp-ton').val(data.ton);
                    $('#thp-time').val(data.time);
                    $('#thp-plan-hour').val(data.plan_hour);
                    var processs = data.process.split('/');
                    $('#thp-process-1').val(processs[0]);
                    $('#thp-process-2').val(processs[1]);
                    $('#thp-plan-1').val(data.plan_1);
                    $('#thp-plan-2').val(data.plan_2);
                    $('#thp-actual-1').val(data.actual_1);
                    $('#thp-actual-2').val(data.actual_2);
                    $('#thp-note').val(data.note);
                    $('#thp-apnormal').val(data.apnormality);
                    $('#thp-action-plan').val(data.action_plan);

                    $('#createModal').modal('show');
                    $('#thp-form-create input,textarea').prop('readonly', 'true');
                    $('#thp-form-create select').prop('disabled', 'true');
                    $('#thp-form-create button[type=submit]').hide();
                    $('#thp-btn-production-code').prop('disabled', 'true');
                    $('#thp-edit-btn').removeAttr('hidden');
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
                {data: 'production_code', name: 'production_code'},
                {data: 'part_number', name: 'part_number'},
                {data: 'part_name', name: 'part_name'},
                {data: 'part_type', name: 'part_type'},
                {data: 'process', name: 'process'},
                {data: 'process_detailname', name: 'process_detailname'},
                {data: 'customer_id', name: 'customer_id'},
                {data: 'ct_sph', name: 'ct_sph'}
            ],
            // initComplete: function(settings, json) {
            //     $('#thp-poduction-code-datatables tbody').on('click', 'tr', function () {
            //         var dataArr = [];
            //         var rows = $(this);
            //         var rowData = tbl_production_code.rows(rows).data();
            //         var processs;
            //         $.each($(rowData),function(key, data){
            //             $('#thp-part-number').val(data.part_number);
            //             $('#thp-part-name').val(data.part_name);
            //             $('#thp-part-type').val(data.part_type);
            //             $('#thp-production-code').val(data.production_code);
            //             $('#thp-customer-code').val(data.customer_id);
            //             $('#thp-route').val(data.process_detailname);
            //             processs = data.process.split('/');
            //             $('#thp-process-1').val(processs[0]);
            //             $('#thp-process-2').val(processs[1]);
            //             $('#thp-ct').val(data.ct_sph);
            //             $('#poduction-code-modal').modal('hide');
            //         });
            //         $.ajax({
            //             url: "{{ route('tms.manufacturing.thp_entry.dataTable_production') }}",
            //             type: "POST",
            //             data: {
            //                 "post_production_code" : $('#thp-production-code').val()
            //             },
            //             headers: {
            //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //             },
            //             success: function (response) {
            //                 if (response.status == true) {
            //                     response = response.data;
            //                     var shift_1 = response[0]['shift_1'] != null ? response[0]['shift_1'] : 0;
            //                     var shift_2 = response[1]['shift_2'] != null ? response[0]['shift_2'] : 0;
            //                     $('#thp-actual-1').val(shift_1);
            //                     $('#thp-actual-2').val(shift_2);
            //                 }
            //             },
            //             error: function(response, status, x){
            //                 Swal.fire({
            //                     title: 'Warning!',
            //                     text: response.responseJSON.message,
            //                     icon: 'warning'
            //                 })
            //             }
            //         });
            //     });
            // }
        });
        $('#thp-poduction-code-datatables tbody').off('click').on('click', 'tr', function () {
            var data = tbl_production_code.row(this).data();
            $.ajax({
                url: "{{ route('tms.manufacturing.thp_entry.dataTable_production') }}",
                type: "POST",
                data: {
                    "post_production_code" : data.production_code
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.status == true) {
                        response = response.data;
                        var shift_1 = response[0]['shift_1'] != null ? response[0]['shift_1'] : 0;
                        var shift_2 = response[1]['shift_2'] != null ? response[0]['shift_2'] : 0;
                        $('#thp-actual-1').val(shift_1);
                        $('#thp-actual-2').val(shift_2);
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
                        $('#poduction-code-modal').modal('hide');
                    }
                },
                error: function(response, status, x){
                    Swal.fire({
                        title: 'Warning!',
                        text: response.responseJSON.message,
                        icon: 'warning'
                    });
                }
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
                                    // tbl_index.ajax.reload();
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
    });
    @if(\Session::has('msg'))
    setTimeout(function () {
        Swal.fire({
            title: 'Warning!',
            text: "{{\Session::get('msg')}}",
            icon: 'warning'
        })
    }, 1000);
    @endif
</script>
@endpush