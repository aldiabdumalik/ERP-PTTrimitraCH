@extends('master')
@section('title', 'TMS | Manufacturing - THP Entry')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
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
                                                <th rowspan="2" class="align-middle">Customer</th>
                                                <th rowspan="2" class="align-middle">Production Code</th>
                                                <th rowspan="2" class="align-middle">Part Name</th>
                                                <th rowspan="2" class="align-middle">Part Type</th>
                                                {{-- <th>Plan</th> --}}
                                                {{-- <th>C/T</th> --}}
                                                <th rowspan="2" class="align-middle">Route</th>
                                                {{-- <th>TON</th> --}}
                                                <th rowspan="2" class="align-middle">Process</th>
                                                {{-- <th>Time</th> --}}
                                                {{-- <th>Plan Hour</th> --}}
                                                <th colspan="2">Plan THP</th>
                                                <th colspan="2">Actual</th>
                                                {{-- <th>Action</th> --}}
                                            </tr>
                                            <tr>
                                                <th>Shift 1</th>
                                                <th>Shift 2</th>
                                                <th>Shift 1</th>
                                                <th>Shift 2</th>
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


@endsection

@section('script')
<script>
$(document).ready(function(){
    var tbl_index = $('#thp-datatables').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: "{{ route('tms.manufacturing.thp_entry.dataTable_index') }}",
        columns: [
            {data: 'id_thp', name: 'id_thp'},
            {data: 'id_cust', name: 'id_cust'},
            {data: 'production_code', name: 'production_code'},
            {data: 'part_name', name: 'part_name'},
            {data: 'part_type', name: 'part_type'},
            {data: 'route', name: 'route'},
            {data: 'process', name: 'process'},
            {data: 'plan_1', name: 'plan_1'},
            {data: 'plan_2', name: 'plan_2'},
            {data: 'actual_1', name: 'actual_1'},
            {data: 'actual_2', name: 'actual_2'},
        ],
        initComplete: function(settings, json) {
            $('#thp-datatables tbody').on('click', 'tr', function () {
                var dataArr = [];
                var rows = $(this);
                var rowData = tbl_index.rows(rows).data();
                $.each($(rowData),function(key, data){
                    getThp(data.id_thp);
                });
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
                        tbl_index.ajax.reload();
                        // window.location.reload();
                    });
                }else{
                    Swal.fire({
                        title: 'Oops...',
                        text: response.message,
                        icon: 'warning',
                    }); 
                }
            },
            error: function(){
                Swal.fire({
                    title: 'Oops...',
                    text: 'Something went wrong data not saved please check form input',
                    icon: 'warning',
                });
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
            error: function(){
                Swal.fire({
                    title: 'Oops...',
                    text: 'Something went wrong data not saved please check form input',
                    icon: 'warning',
                });
            }
        });
    }

    function productioncode_tbl(proc="PRESSING", cust="A01"){
        var tbl_production_code = $('#thp-poduction-code-datatables').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{ route('tms.manufacturing.thp_entry.dataTable_production') }}",
                method: "GET",
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
            initComplete: function(settings, json) {
                $('#thp-poduction-code-datatables tbody').on('click', 'tr', function () {
                    var dataArr = [];
                    var rows = $(this);
                    var rowData = tbl_production_code.rows(rows).data();
                    var processs;
                    $.each($(rowData),function(key, data){
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
                    });
                });
            }
        });
    }
});
</script>
@endsection


@push('js')
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>

</script>
@endpush