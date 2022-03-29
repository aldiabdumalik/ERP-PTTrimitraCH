@extends('master')
@section('title', 'TMS | Database Parts - Dashboard')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/datepicker/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
@include('tms.db_parts.style.custom-style')

<div class="main-content-inner">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-4 mt-5 mb-3">
                    <div class="card">
                        <div class="seo-fact sbg1 pb-5">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-cubes"></i> Projects</div>
                                <h2 id="c-project">{{$projects}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-5 mb-3">
                    <div class="card">
                        <div class="seo-fact sbg2 pb-5">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-user"></i> Customer</div>
                                <h2 id="c-customer">{{$countcustomer}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-5 mb-3">
                    <div class="card">
                        <div class="seo-fact sbg3 pb-5">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="ti-thumb-up"></i> Revision</div>
                                <h2 id="c-revisi">{{$revisi}}x</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="projects-btn-modal-create">
                    <i class="ti-plus"></i>  Add New Project
                </button>
                <button type="button"  class="btn btn-danger btn-flat btn-sm" id="projects-btn-modal-trash">
                    <i class="ti-trash"></i>  View Trash Project
                </button>
            </div>
        </div>
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">List Project</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="project-datatable" class="display compact table table-hover" style="width:100%;cursor:pointer">
                                        <thead>
                                            <tr>
                                                <th class="align-middle">No.</th>
                                                <th class="align-middle">Customer ID</th>
                                                <th class="align-middle">Customer Name</th>
                                                <th class="align-middle">Type</th>
                                                <th class="align-middle">Reff</th>
                                                <th class="align-middle">Action</th>
                                            </tr>
                                        </thead>
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

@include('tms.db_parts.dashboard.modal.form.form_projects')
@include('tms.db_parts.dashboard.modal.form.form_projects_post')
@include('tms.db_parts.dashboard.modal.table.logs')
@endsection
@push('js')
@include('tms.db_parts.dashboard.ajax')
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