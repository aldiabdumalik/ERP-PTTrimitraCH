@extends('master')

@section('title', 'TMS | Master Item')

@section('css')

<!-- DATATABLES -->
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/Responsive-2.2.5/css/responsive.dataTables.min.css') }}">

@endsection

<!-- @section('tms_content_menuHorizontal')
<div class="page-title-area">
    <div class="row" >
        <div class="#">
            <a href="#" class="btn btn-primary btn-round" id="add_form">
                Add Item
            </a>
        </div>
    </div>
</div>
@endsection -->

@section('content')

    @include('tms.__layouts.tms-menuMaster-horizontal')

    <div class="main-content-inner">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div id="form_addItem" style="display:none">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Entry of Master Item TMS</h4>
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your
                                email with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4" id="post_Item">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">Products</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="data-tables datatable-dark">
                                <table id="tms_MasterItem_Datatable" class="table table-striped" style="width:100%">
                                    {{ csrf_field() }}
                                    <thead class="text-center">
                                        <tr>
                                            <th>Item Code</th>
                                            <th>Part Number</th>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th>Cust</th>
                                            <th>Group</th>
                                            <th>Type</th>
                                            <th>State</th>
                                            <th>Track</th>
                                            <th>BoM</th>
                                            <th>Unit</th>
                                            <th>Fac_Qty</th>
                                            <th>Fac_Unit</th>
                                            <th>Action</th>
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

    <!-- <div id="data_table"> -->
        <!-- data table start -->
        <!-- <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Master Item TMS</h4>
                    <div class="data-tables datatable-dark">
                        <table id="tms_MasterItem_Datatable" class="table table-striped" style="width:100%"> -->
                        <!-- <table id="tms_MasterItem_Datatable"
                            class="table table-striped text-center cell-border display compact"
                            style="width:100%"> -->
                            <!-- <thead class="text-uppercase">
                                <tr>
                                    <th>Item Code</th>
                                    <th>Part Number</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Cust</th>
                                    <th>Group</th>
                                    <th>Type</th>
                                    <th>State</th>
                                    <th>Track</th>
                                    <th>BoM</th>
                                    <th>Unit</th>
                                    <th>Fac_Qty</th>
                                    <th>Fac_Unit</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- data table end -->
    <!-- </div> -->
</div>
@endsection

@push('js')

<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/custom_tms_datatable.js') }}"></script>

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function() {
   

    } );
</script>
@endpush
