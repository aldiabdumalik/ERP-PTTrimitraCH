@extends('master')
@section('title', 'TMS | Database Parts - Production Code')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/datepicker/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
@include('tms.db_parts.style.custom-style')

<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="prodcode-btn-modal-create">
                    <i class="ti-plus"></i>  Add New Data
                </button>
                <button type="button"  class="btn btn-danger btn-flat btn-sm" id="prodcode-btn-modal-trash">
                    <i class="ti-trash"></i>  View Trash Data
                </button>
            </div>
        </div>
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">Production Code</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="prodcode-datatable" class="display compact table table-hover" style="width:100%;cursor:pointer">
                                        <thead>
                                            <tr>
                                                <th class="align-middle">Type</th>
                                                <th class="align-middle">Part No</th>
                                                <th class="align-middle">Part Name</th>
                                                <th class="align-middle">Customer ID</th>
                                                <th class="align-middle">SEQ</th>
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
@include('tms.db_parts.production_code.modal.form.index')
@include('tms.db_parts.production_code.modal.table.imageView')
@endsection
@section('script')
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

    $(document).on('click', '#prodcode-btn-add-item', function () {
        var index = tbl_item.data().length;
        let i = ++index;

        let add = tbl_item.row.add([
            i,
            `<input type="text" class="form-control form-control-sm" value="">`,
            `<select name="prodcode-index-process[]" id="prodcode-index-process-${i}" class="form-control form-control-sm"></select>`,
            `<input type="text" name="prodcode-index-process_det[]" id="prodcode-index-process_det-${i}" class="form-control form-control-sm prodcode-index-process_det" placeholder="Press ENTER">`,
            `<input type="text" class="form-control form-control-sm" value="">`,
            `<input type="text" class="form-control form-control-sm" value="">`,
            `<input type="text" class="form-control form-control-sm" value="">`,
            `<input type="text" class="form-control form-control-sm" value="">`,
            `<input type="text" class="form-control form-control-sm" value="">`,
        ]);
        tbl_item.draw(false);
        loading_start();
        ajaxCall({route: "{{ route('tms.db_parts.production_code.header_tools') }}", method: "POST", data: {type: "get_process"} }).then(data => {
            loading_stop();
            $(`#prodcode-index-process-${i}`).html('');
            $.each(data.content, function (x, item) {
                $(`#prodcode-index-process-${i}`).append($('<option>', { 
                    value: item.process_id,
                    text : item.process_name 
                }));
            });
        });
    });

    $('#prodcode-modal-index').on('shown.bs.modal', function () {
        adjustDraw(tbl_item)
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
@endsection
@push('js')
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