@extends('master')
@section('title', 'TMS | Warehouse - Claim Entry')
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
    .row.no-gutters {
    margin-right: 0;
    margin-left: 0;

    & > [class^="col-"],
    & > [class*=" col-"] {
    padding-right: 0;
    padding-left: 0;
    }
    }
</style>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="claim-btn-modal-create">
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
                        <h4 class="card-header-title">Claim Entry</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="claim-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer">
                                        <thead class="text-center" style="font-size: 15px;">
                                            <tr>
                                                <th class="align-middle">CL No.</th>
                                                <th class="align-middle">Written</th>
                                                <th class="align-middle">Date DO</th>
                                                <th class="align-middle">Date RG</th>
                                                <th class="align-middle">RR No.</th>
                                                <th class="align-middle">Ref No.</th>
                                                <th class="align-middle">PO No.</th>
                                                <th class="align-middle">Customer</th>
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
{{-- </div> --}}
@include('tms.warehouse.claim-entry.modal.create.index')
@include('tms.warehouse.claim-entry.modal.header.branch')
@include('tms.warehouse.claim-entry.modal.header.warehouse')

@endsection

@section('script')
<script>
$(document).ready(function(){
    var tbl_create = $('#claim-datatables-create').DataTable({
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "ordering": false,
    });
    setTimeout(() => {
        modalAction('#claim-modal-create');
    }, 1000);
    $('#claim-btn-modal-create').on('click', function () {
        modalAction('#claim-modal-create');
    });

    $(document).on('keypress', '#claim-create-branch', function (e) {
        var tbl_branch;
        if(e.which == 13) {
            modalAction('#claim-modal-branch');
            var params = {"type": "branch"}
            var column = [
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name'},
            ];
            tbl_branch = $('#claim-datatables-branch').DataTable(dataTables(
                "{{ route('tms.warehouse.claim_entry.header_tools') }}",
                "POST",
                params,
                column
            ));
        }
        $('#claim-datatables-branch').off('click').on('click', 'tr', function () {
            modalAction('#claim-modal-branch', 'hide');
            var data = tbl_branch.row(this).data();
            if (data.code == 'CP') {
                $('#claim-create-warehouse').val(data.code);
            }else{
                $('#claim-create-warehouse').val("");
            }
            $('#claim-create-branch').val(data.code);
        });
    });

    $(document).on('keypress', '#claim-create-warehouse', function (e) {
        if(e.which == 13) {
            modalAction('#claim-modal-warehouse');
            var branch = ($('#claim-create-branch').val() == "") ? null : $('#claim-create-branch').val();
            var params = {"type": "warehouse", "branch": branch}
            var column = [
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name'},
            ];
            $('#claim-datatables-warehouse').DataTable(dataTables(
                "{{ route('tms.warehouse.claim_entry.header_tools') }}",
                "POST",
                params,
                column
            ));
        }
    });

    const dataTables = (route, method, params=null, columns) => {
        const dtbl = {
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: route,
                method: method,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: params
            },
            columns: columns
        };
        return dtbl;
    }
    const ajax = (route, type, params=null, callback) => {
        $.ajax({
            url: route,
            type: type,
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
    const modalAction = (elementId=null, action='show') => {
        $(elementId).modal(action);
    }
});
</script>
@endsection

@push('js')
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('/vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>
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