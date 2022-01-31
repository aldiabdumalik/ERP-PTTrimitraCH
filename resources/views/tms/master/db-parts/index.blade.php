@extends('master')
@section('title', 'TMS | Master - Database Parts')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/datepicker/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
@include('tms.master.db-parts.style.custom-style')

<div class="main-content-inner">
    @include('tms.master.db-parts.table.index')
</div>
@include('tms.master.db-parts.modal.form.parts.index')
@include('tms.master.db-parts.modal.form.parts.form-add')
{{-- @include('tms.master.db-parts.modal.form.index') --}}
@include('tms.master.db-parts.modal.form.processing')
@include('tms.master.db-parts.modal.table.customer')
@include('tms.master.db-parts.modal.table.unit')
@include('tms.master.db-parts.modal.table.processing_detail')
@endsection
@section('script')
<script>
$(document).ready(function () {
    const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
    var index_table = $('#dbpart-datatable').DataTable();
    $('#dbpart-modal-index').modal('show');

    var tbl_process = $('#dbpart-datatables-index').DataTable({
        destroy: true,
        lengthChange: false,
        searching: false,
        sDom: 'lrtip',
        paging: false,
        ordering: false,
        scrollY: "200px",
        scrollCollapse: true,
        fixedHeader: true,
        scrollX: true
    });
    
    $('#dbpart-modal-index').on('shown.bs.modal', function () {
        adjustDraw(tbl_process);
    });

    var tbl_customer;
    $(document).on('keypress keydown', '#dbpart-index-customercode', function (e) {
        if(e.which == 13) {
            modalAction('#dbpart-modal-customer').then((resolve) => {
                tbl_customer = $('#dbpart-datatables-customer').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ route('tms.master.db_part.header_tools') }}",
                        method: 'POST',
                        data: {"type": "customer"},
                        headers: token_header
                    },
                    columns: [
                        {data: 'code', name: 'code'},
                        {data: 'name', name: 'name'},
                    ]
                });
            });
        }
        if(e.which == 8 || e.which == 46) { return false; }
        return false;
    });

    $(document).on('shown.bs.modal', '#dbpart-modal-customer', function () {
        $('#dbpart-datatables-customer_filter input').focus();
    });

    $('#dbpart-datatables-customer').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        var data = tbl_customer.row(this).data();
        modalAction('#dbpart-modal-customer', 'hide').then(resolve => {
            $('#dbpart-index-customercode').val(data.code);
            $('#dbpart-index-customername').val(data.name);
            $('#dbpart-index-customeraddr1').val(data.ad1);
            $('#dbpart-index-customeraddr2').val(data.ad2);
            $('#dbpart-index-customeraddr3').val(data.ad3);
            $('#dbpart-index-customeraddr4').val(data.ad4);
        });
    });

    var tbl_unit;
    $(document).on('keypress keydown', '#dbpart-index-unit', function (e) {
        if(e.which == 13) {
            modalAction('#dbpart-modal-unit').then((resolve) => {
                tbl_unit = $('#dbpart-datatables-unit').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ route('tms.master.db_part.header_tools') }}",
                        method: 'POST',
                        data: {"type": "unit"},
                        headers: token_header
                    },
                    columns: [
                        {data: 'unit', name: 'unit'},
                        {data: 'des', name: 'des'},
                    ]
                });
            });
        }
        if(e.which == 8 || e.which == 46) { return false; }
        return false;
    });
    $(document).on('shown.bs.modal', '#dbpart-modal-unit', function () {
        $('#dbpart-datatables-unit_filter input').focus();
    });
    $('#dbpart-datatables-unit').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        var data = tbl_unit.row(this).data();
        modalAction('#dbpart-modal-unit', 'hide').then(resolve => {
            $('#dbpart-index-unit').val(data.unit);
        });
    });

    $(document).on('click', '#dbpart-btn-add-item', function () {
        modalAction('#dbpart-modal-fparts').then(() => {
            // $('#dbpart-modal-index').addClass('d-none');
        });
    });
    $('#dbpart-modal-fparts').on('hidden.bs.modal', function () {
        $('#dbpart-form-fparts').trigger('reset');
        $('#dbpart-fparts-pict-x').html('Choose file');
    });

    $('#dbpart-modal-processing').on('shown.bs.modal', function () {
        ajaxCall({route: "{{ route('tms.master.db_part.header_tools') }}", method: "POST", data: {"type":"process"}}).then(resolve => {
            $('#dbpart-processing-name').html('');
            $.each(resolve.content, function (i, valas) {
                $('#dbpart-processing-name').append($('<option>', { 
                    value: valas.production_process,
                    text : valas.production_process 
                }));
            });
        })
    })

    $('#dbpart-modal-processing').on('hidden.bs.modal', function () {
        $('#dbpart-modal-index').removeClass('d-none');
    });

    var tbl_process_detail;
    $(document).on('keypree keydown', '.dbpart-processing-detail', function (e) {
        var i = $(this).data('id');
        if(e.which == 13) {
            if (!$('#dbpart-processing-name').val()) {
                console.log(null);
                return false; 
            }else{
                modalAction('#dbpart-modal-process-detail').then((resolve) => {
                    tbl_process_detail = $('#dbpart-datatables-process-detail').DataTable({
                        processing: true,
                        serverSide: true,
                        destroy: true,
                        ajax: {
                            url: "{{ route('tms.master.db_part.header_tools') }}",
                            method: 'POST',
                            data: {"type": "process_detail", "process": $('#dbpart-processing-name').val()},
                            headers: token_header
                        },
                        columns: [
                            {data: 'production_process', name: 'production_process'},
                            {data: 'process_detailname', name: 'process_detailname', className:`data_${i}`},
                        ],
                        createdRow: function( row, data, dataIndex ) {
                            $(row).attr('data-id', i);
                        }
                    });
                });
            }
        }
        if(e.which == 8 || e.which == 46) { return false; }
        return false;
    })

    $('#dbpart-datatables-process-detail').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        var id = $(this).data('id');
        var data = tbl_process_detail.row(this).data();
        modalAction('#dbpart-modal-process-detail', 'hide').then(() => {
            $('.dbpart-processing-detail[data-id='+id+']').val(data.process_detailname);
        });
    });

    $(document).on('click', '.add-row-proc_detail', function () {
        var x;
        $('.dbpart-processing-detail').each(function (i, d) {
            x = i+2;
        });
        $('#tbl_proc_detail_form').append(`
        <tr>
            <td width="15%" class="align-middle">
            </td>
            <td class="align-middle">
                <input type="text" name="dbpart-processing-detail" class="form-control form-control-sm dbpart-processing-detail" data-id="${x}">
            </td>
            <td width="5%" class="align-middle">
                <a href="javascript:void(0)" class="fa fa-trash text-danger rm-row-proc_detail"></a>
            </td>
        </tr>
        `);
    })
    $(document).on('click', '.rm-row-proc_detail', function () {
        $(this).parents('tr').remove();
        $('.dbpart-processing-detail').each(function (i, d) {
            $(d).attr('data-id', ++i)
        });
    });

    $(document).on('submit', '#dbpart-form-fparts', function (e) {
        e.preventDefault();
        loading_start();
        var formData = new FormData();
        formData.append('file', $('#dbpart-fparts-pict')[0].files[0]);
        $.ajax({
            url:"{{ route('tms.master.db_part.upload_temp') }}",
            method:"POST",
            data: formData,
            dataType:'JSON',
            contentType: false,
            cache: false,
            processData: false,
            headers: token_header,
            success: function (response) {
                loading_stop();
                
            },
            error: function(response, status, x){
                Swal.fire({
                    title: 'Something was wrong',
                    text: response.responseJSON.message,
                    icon: 'error'
                }).then(() => {
                    $('body').loading('stop');
                    console.clear();;
                });
            },
        });
    });

    $('#dbpart-fparts-pict').on('change', function(){
        var fileName = $(this).val().replace('C:\\fakepath\\', " ");
        var ext = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(ext, fileName.length).toLowerCase();
        if (!fileName) {
            fileName = 'Choose file';
        }
        if (extFile=="jpg" || extFile=="jpeg" || extFile=="png" || !extFile){
            $(this).next('#dbpart-fparts-pict-x').html(fileName);
        }else{
            $(this).next('#dbpart-fparts-pict-x').html('Choose file');
            $(this).val(null);
        }
    })
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
                    Swal.fire({
                        title: 'Something was wrong',
                        text: response.responseJSON.message,
                        icon: 'error'
                    }).then(() => {
                        $('body').loading('stop');
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