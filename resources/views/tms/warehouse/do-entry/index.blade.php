@extends('master')
@section('title', 'TMS | Warehouse - DO Entry')
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
    /* input[readonly] {
        background-color: #fff !important;
        cursor: not-allowed;
        pointer-events: all !important;
    } */
    .row.no-gutters {
        margin-right: 0;
        margin-left: 0;

        & > [class^="col-"],
        & > [class*=" col-"] {
            padding-right: 0;
            padding-left: 0;
        }
    }
    button:disabled {
        cursor: not-allowed;
        pointer-events: all !important;
    }
    .selected {
        background-color: #dddddd;
    }
</style>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="do-btn-modal-create">
                    <i class="ti-plus"></i>  Add New Data
                </button>
                <button type="button"  class="btn btn-outline-primary btn-flat btn-sm" id="do-btn-modal-table-setting">
                    <i class="fa fa-cogs"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">DO Entry</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="do-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer">
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
@include('tms.warehouse.do-entry.modal.table.tableIndexSetting')
@endsection
@section('script')
<script>
$(document).ready(function () {
    const get_index = new Promise(function(resolve, reject) {
        ajax("{{route('tms.warehouse.do_entry.table_index_setting')}}", 'GET', null, function (response) {
            var index_header = [];
            $.each(response.responseJSON.content, function( key, value ) {
                var my_item = {};
                my_item.data = value.data;
                my_item.name = value.data;
                my_item.title = value.title;
                if (value.data == 'action') {
                    my_item.orderable = false;
                    my_item.searchable = false;
                }
                my_item.className = 'text-center';
                index_header.push(my_item);
            });
            let tbl_index =  $('#do-datatables').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('tms.warehouse.do_entry.table_index')}}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                columns: index_header,
                order: [[ 0, "desc" ]],
            });
            resolve(tbl_index);
        });
    });
    
    var tbl_do_setting = $('#do-setting-datatables').DataTable({
        "destroy": true,
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "ordering": false,
        "scrollY": "200px",
        "scrollCollapse": true,
        "fixedHeader":true,
        "createdRow": function (row, data) {
            console.log(data);
        }
    });
    $('#do-btn-modal-table-setting').on('click', function () {
        modalAction('#do-modal-setting').then((resolve) => {
            resolve.on('shown.bs.modal', () => {
                tbl_do_setting.columns.adjust().draw();
                ajax("{{route('tms.warehouse.do_entry.table_index_setting')}}", 'GET', null, function (response) {
                    response = response.responseJSON;
                    $.each(response.content, function (i, val) {
                        if ($('#do-setting-datatables tbody tr').is(`[data-sett=${val.data}]`) == true) {
                            $(`#do-setting-datatables tbody tr td[data-tdsett=${val.data}]`).html("");
                            $(`#do-setting-datatables tbody tr td[data-tdsett=${val.data}]`).append(`<i class="fa fa-check"></i>`);
                        }
                    });
                    // tbl_do_setting.rows().data().draw();
                    
                });
            });
        });
    });
    $('#do-setting-datatables').off('click', 'tr').on('click', 'tr', function () {
        var data = tbl_do_setting.row(this).data();
        console.log(data);
    });
    function ajax(route, method, params=null, callback) {
        $.ajax({
            url: route,
            method: method,
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
    function modalAction(elementId=null, action='show'){
        return new Promise(resolve => {
            $(elementId).modal(action);
            resolve($(elementId));
        });
        // return $(elementId).modal(action);
    }

    function hideShow(elelemt=null, hide=true){
        return ((hide == true) ? $(elelemt).addClass('d-none') : $(elelemt).removeClass('d-none'));
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