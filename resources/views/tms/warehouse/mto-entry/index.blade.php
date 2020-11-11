@extends('master')
@section('title', 'TMS | Warehouse - MTO Entry')
@section('css')

<!-- DATATABLES -->
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">

@endsection

@section('content')           

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
                        <h4 class="card-header-title">Many To One Entry</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                           
                            <div class="data-tables datatable-dark">
                                <div class="table-responsive">
                                <table id="mto-datatables" class="table table-striped" style="width:100%">
                                    {{ csrf_field() }}
                                    <thead class="text-center" style="font-size: 15px;">
                                        <tr>
                                            <th>MTO No</th>
                                            <th>Written</th>
                                            <th>Posted</th>
                                            <th>Voided</th>
                                            <th>Item Code</th>
                                            <th>Ref No</th>
                                            <th>Remark</th>
                                            <th>Brch</th>
                                            <th width="30%">ACTION</th>
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
@include('tms.warehouse.mto-entry.modal.view-mto-modal._viewmto')
@include('tms.warehouse.mto-entry.modal.edit-mto-modal._edit')
{{-- @include('tms.warehouse.mto-entry.modal.popup-mto-choicedata.popUpMto2')  --}}
@include('tms.warehouse.mto-entry.modal.create-mto-modal._create')
@include('tms.warehouse.mto-entry.modal.popup-mto-choicedata.popUpMto') 



@endsection
@section('script')
<script type="text/javascript">

// function keyPressedEdit(e){
//     if (e.keyCode == 120) { // PRESS KEYBOARD SHORTCUT F9 FOR APPEAR DATA ITEM FOR EDIT FORM
//         e.preventDefault();
//        $('#btnPopUp2').click();
//     } else if(e.keyCode){
//         e.preventDefault();
//     }
// }

function keyPressed(e){
    if (e.keyCode == 120) { // PRESS KEYBOARD SHORTCUT F9 FOR APPEAR DATA ITEM
        e.preventDefault();
       $('#btnPopUp').click();
    } else if(e.keyCode){
        e.preventDefault();
    }
}


function setTwoNumberDecimal(event) {
    this.value = parseFloat(this.value).toFixed(2);
}

$( document ).ready(function() {
    $('.select_create').select2();
    $('.select_view').select2();
    $('.select_edit').select2();
});

// ADDED NEW MTO DATA
$(document).on('click', '#addModal', function(e) {
   e.preventDefault();
 
   $('#createModal').after('#mtoModal');
   $('#createModal').modal('show');
   $('.modal-title').text('Many To One Entry (New)');
   
});
// EDIT DATA MTO
$(document).on('click', '.edit', function(e){
    e.preventDefault();
    var id = $(this).attr('row-id');
    var posted = $(this).attr('data-target');
    var mto_no = $(this).attr('data-id');
    if (posted !== '') {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'MTO entry no.' + mto_no + ' '+'has been posted cant edit',
        });
    } else {
        $('.modal-title').text('Edit Many To One Entry (New)');
        $('#EditModal').modal('show');
        // e.preventDefault();
        EditData(id)
        UpdateData(id, mto_no)
    }  
});
// SHOW VIEW DATA MTO
$(document).on('click', '.view', function(e){
    e.preventDefault();
    var id = $(this).attr('row-id');
    $('#viewModal').modal('show');
    $('.modal-title').text('Many To One Entry (New)');
    getDetail(id, 'VIEW')
});
// DELETE THIS DATA
$(document).on('click', '.delete', function(e){
    var id = $(this).attr('row-id');
    var mto_no = $(this).attr('data-id');
    var posted = $(this).attr('data-target');

    if (posted !== '') {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'MTO entry no.' + mto_no + ' '+'has been posted cant delete',
        });
    } else {
        e.preventDefault();
        deleteData(id, mto_no)
    }
    
});
//  POSTED VIA AJAX
$(document).on('click', '.posted', function(e){
    var id = $(this).attr('row-id');
    var mto_no = $(this).attr('data-id');
    // alert(mto_no);
    e.preventDefault();
    postedMTO(id, mto_no)
});


// SAVE DATA TO DATABASE FROM AJAX
$('.modal-footer').on('click','.add', function(){
    $('.add').html('Saving...')
     $.ajax({
         url: "{{ route('tms.warehouse.mto-entry_store_mto_data') }}",
         type: "POST",
         data: $('#form-mto').serialize(),
         success: function(data){
            $("#createModal").modal('hide'); 
                Swal.fire(
                    'Successfully!',
                    'add new data MTO entry!',
                    'success'
                ).then(function(){
                    location.reload();
                });
             
         }, 
         error: function(){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
            })
         }
     })
})

// VIEW DATA SHOW DETAIL FROM AJAX
function getDetail(id, method){
        var route  = "{{ route('tms.warehouse.mto-entry_show_view_detail', ':id') }}";
            route  = route.replace(':id', id);
        $.ajax({
            url:      route,
            method:   'get',
            dataType: 'json',
            success:function(data){
                $('#mto_no_view').val(data['header'].mto_no);
                $('#ref_no_view').val(data['header'].ref_no);
                $('#item_code_view').val(data['header'].fin_code);
                $('#part_no_view').val(data['header'].frm_code);
                $('#descript_view').val(data['header'].descript);
                $('#quantity_view').val(data['header'].quantity);
                $('#qty_ng_view').val(data['header'].qty_ng);
                $('#unit_view').val(data['header'].unit);
                $('#remark_view').val(data['header'].remark);
                $('#staff_view').val(data['header'].staff);
                $('#period_view').val(data['header'].period);
                $('#written_view').val(formatDate(data['header'].written));
                $('#branch_view').val(data['header'].branch);
                $('#types_view').val(data['header'].types);
                $('#printed_view').val(formatDate(data['header'].printed));
                $('#voided_view').val(formatDate(data['header'].voided));
                $('#posted_view').val(formatDate(data['header'].posted));
                $('#warehouse_view').val(data['header'].warehouse); 
                
                var detailDataset = [];
                for(var i = 0; i < data['detail'].length; i++){
                    detailDataset.push([
                        data['detail'][i].fin_code, data['detail'][i].frm_code, data['detail'][i].descript,
                        data['detail'][i].unit, data['detail'][i].quantity, data['detail'][i].qty_ng, data['detail'][i].warehouse
                    ]);
                }
                $('#tbl-detail-mto').DataTable().clear().destroy();
                $('#tbl-detail-mto').DataTable({
                    data: detailDataset,
                    columns: [
                        { title: 'itemcode'},
                        { title: 'Part No.'},
                        { title: 'Description'},
                        { title: 'Unit' },
                        { title: 'Qty' },
                        { title: 'Qty NG' },
                        { title: 'Warehouse' }
                    ]
                });
            }
        });
    }

// VIEW EDIT FROM EDIT
function EditData(id){
        var route  = "{{ route('tms.warehouse.mto-entry_edit_mto_data', ':id') }}";
            route  = route.replace(':id', id);
        $.ajax({
            url:      route,
            method:   'get',
            dataType: 'json',
            success:function(data){
                $('#mto_no_edit').val(data['header'].mto_no);
                $('#branch_edit').val(data['header'].branch);
                $('#warehouse_edit').val(data['header'].warehouse);
                $('#ref_no_edit').val(data['header'].ref_no);
                $('#ITEMCODE').val(data['header'].fin_code);
                $('#PART_NO').val(data['header'].frm_code);
                $('#DESCRIPT').val(data['header'].descript);
                $('#quantity_edit').val(data['header'].quantity);
                $('#qty_ng_edit').val(data['header'].qty_ng);
                $('#unit_edit').val(data['header'].unit);
                $('#remark_edit').val(data['header'].remark);
                $('#written_edit').val(data['header'].written);
                $('#period_edit').val(data['header'].period);
                $('#types_edit').val(data['header'].types);
                $('#printed_edit').val(formatDate(data['header'].printed));
                $('#voided_edit').val(formatDate(data['header'].voided));
                $('#posted_edit').val(formatDate(data['header'].posted));
                
                

                var detailDataset = [];
                for(var i = 0; i < data['detail'].length; i++){
                    detailDataset.push([
                        data['detail'][i].fin_code, data['detail'][i].frm_code, data['detail'][i].descript,
                        data['detail'][i].unit, data['detail'][i].quantity, data['detail'][i].qty_ng, data['detail'][i].warehouse
                    ]);
                }
                $('#tbl-edit').DataTable().clear().destroy();
                $('#tbl-edit').DataTable({
                    data: detailDataset,
                    columns: [
                        { title: 'Itemcode'},
                        { title: 'Part No.'},
                        { title: 'Description'},
                        { title: 'Unit' },
                        { title: 'Qty' },
                        { title: 'Qty NG' },
                        { title: 'Warehouse' }
                    ]
                });
            }, 
            error: function(){
                alert('error');
            }
        });
        
        
    }


// UPDATE MTO FROM EDIT
function UpdateData(id, mto_no){
    var route  = "{{ route('tms.warehouse.mto-entry_update_mto_entry', ':id') }}";
        route  = route.replace(':id', id);
        //
    $('.modal-footer').on('click','.update', function(){
            $('.update').html('Saving...');
                $.ajax({
                    url: route,
                    type: "PUT",
                    data: $('#form-mto-edit').serialize(),
                    success: function(data){
                    $("#EditModal").modal('hide'); 
                        Swal.fire(
                            'Successfully!',
                            'update data MTO entry no.' + mto_no,
                            'success'
                            ).then(function(){
                                location.reload();
                        });
                        
                    }, 
                        error: function(){
                            Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })
                    }
                });
        });
        
}
// CALL TOKEN FOR DELETE THIS DATA FROM AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function deleteData(id, mto_no){
    Swal.fire({
        title: 'Are you sure?',
        text: "delete this data mto no." + mto_no,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).
        then((willDelete) => {
            var route  = "{{ route('tms.warehouse.mto-entry_delete_mto_entry', ':id') }}";
                route  = route.replace(':id', id);
            if(willDelete.value){
                    $.ajax({
                        url: route,
                        type: "POST",
                        data : {
                            '_method' : 'DELETE'
                        },
                        success: function(data){   
                        Swal.fire(       
                            'Deleted!',
                            'Data has been deleted.',
                            'success'
                        ).then(function(){
                            location.reload();
                        });
                            
                        }, 
                        error: function(){
                            Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })
                    }
                })
            } else {
                console.log(`data MTO was dismissed by ${willDelete.dismiss}`);
            }
         
        
        })
}
// method POSTED 
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function postedMTO(id, mto_no){
    Swal.fire({
        title: 'Are you sure Post?',
        text: "this data mto no." + mto_no,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Post it!'
        }).
        then((willPosted) => {
            var route  = "{{ route('tms.warehouse.mto-entry_posted_mto_entry_data', ':id') }}";
                route  = route.replace(':id', id);
            if(willPosted.value){
                    $.ajax({
                        url: route,
                        type: "POST",
                        data : {
                            '_method' : 'POST'
                        },
                        success: function(data){   
                        Swal.fire(       
                            'Posted!',
                            'Data has been Posted.',
                            'success'
                        ).then(function(){
                            location.reload();
                        });
                            
                        }, 
                        error: function(){
                            Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })
                    }
                })
            } else {
                console.log(`data MTO was dismissed by ${willPosted.dismiss}`);
            }
         
        
        })
}


function validateCreateMto(){
    var part_no = document.getElementById('part_no_create').value;
        descript = document.getElementById('descript_create').value;
        types = document.getElementById('types_create').value;
        itemcode = document.getElementById('itemcode_create').value
    if (part_no !== '' || descript !== '') {
        Swal.fire({
            icon: 'error',
            title: 'not valid',
            text: 'please press F9 or button search at itemcode input',
        })
    } else if (itemcode == '') {
        Swal.fire({
            icon: 'warning',
            title: 'please fill in itemcode',
            text: 'please press F9 or button search',
        })
    }
}
function formatDate (input) {
    if (input !== null) {
        var datePart = input.match(/\d+/g),
            year = datePart[0].substring(0),
            month = datePart[1], day = datePart[2];
        return day+'/'+month+'/'+year;
    } else {
        return null;
    }
}
</script>
@endsection
@push('js')
<!-- Datatables -->
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {
    
        //get data from datatables
        var table = $('#mto-datatables').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('tms.warehouse.mto-entry_datatables') }}",
            order: [[ 0, 'desc']],
            responsive: true,
            // columnDefs: [
            //     {"className": "align-right vertical-center", "targets": 6},
            //     {"className": "align-center vertical-center", "targets": [0, 1, 2, 3, 4, 5]}
            // ],
            columns: [
                { data: 'mto_no', name: 'mto_no' },
                { data: 'written', name: 'written' },
                { data: 'posted', name: 'posted' },
                { data: 'voided', name: 'voided' },
                { data: 'frm_code', name: 'frm_code' },
                { data: 'ref_no', name: 'ref_no' },
                { data: 'remark', name: 'remark' },
                { data: 'branch', name: 'branch' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]    
        });


        // get Datatables choices data from ITEM / CREATE
        var url = "{{ route('tms.warehouse.mto-entry_datatables_choice_data') }}";
        var lookUpdata =  $('#lookUpdata').DataTable({
            processing: true, 
            serverSide: true,
            "pagingType": "numbers",
            ajax: url,
            responsive: true,
            // "scrollX": true,
            // "scrollY": "500px",
            // "scrollCollapse": true,
            "order": [[1, 'asc']],
            columns: [
                { data: 'ITEMCODE', name: 'ITEMCODE' },
                { data: 'PART_NO', name: 'PART_NO' },
                { data: 'DESCRIPT', name: 'DESCRIPT' },
                { data: 'DESCRIPT1', name: 'DESCRIPT1' }

            ],
            "bDestroy": true,
            "initComplete": function(settings, json) {
                // $('div.dataTables_filter input').focus();
                $('#lookUpdata tbody').on( 'dblclick', 'tr', function () {
                    var dataArr = [];
                    var rows = $(this);
                    var rowData = lookUpdata.rows(rows).data();
                    $.each($(rowData),function(key,value){
                        document.getElementById("itemcode_create").value = value["ITEMCODE"];
                        document.getElementById("part_no_create").value = value["PART_NO"];
                        document.getElementById("descript_create").value = value["DESCRIPT"];
                        $('#mtoModal').modal('hide');
                    });
                });
                $('#mtoModalLabel').on('hidden.bs.modal', function () {
                    var itemcode = document.getElementById("itemcode_create").value.trim();
                    if(itemcode === '') {
                        document.getElementById("part_no_create").value = "";
                        $('#part_no_create').focus();
                    }
                });
            },
        });


        
        // GET DATATABLES CHOICE DATA 2x CLICK AT EDIT FORM
        var url2 = "{{ route('tms.warehouse.mto-entry_datatables_choice_data') }}";
        var lookUpdata2 =  $('#lookUpdata2').DataTable({
            processing: true, 
            serverSide: true,
            "pagingType": "numbers",
            ajax: url2,
            responsive: true,
            // "scrollX": true,
            // "scrollY": "500px",
            // "scrollCollapse": true,
            "order": [[1, 'asc']],
            columns: [
                { data: 'ITEMCODE', name: 'ITEMCODE' },
                { data: 'PART_NO', name: 'PART_NO' },
                { data: 'DESCRIPT', name: 'DESCRIPT' },
                { data: 'DESCRIPT1', name: 'DESCRIPT1' }

            ],
            "bDestroy": true,
            "initComplete": function(settings, json) {
                // $('div.dataTables_filter input').focus();
                $('#lookUpdata2 tbody').on('dblclick', 'tr', function () {
                    var dataArr = [];
                    var rows2 = $(this);
                    var rowData2 = lookUpdata2.rows(rows2).data();
                    $.each($(rowData2),function(key,value){
                        document.getElementById("ITEMCODE").value = value["ITEMCODE"];
                        document.getElementById("PART_NO").value = value["PART_NO"];
                        document.getElementById("DESCRIPT").value = value["DESCRIPT"];
                        $('#mtoModal2').modal('hide');
                        // $('.edit').focus();
                    });
                });
                $('#mtoModalLabel').on('hidden.bs.modal', function () {
                    var itemcode2 = document.getElementById("ITEMCODE").value.trim();
                    if(itemcode2 === '') {
                        document.getElementById("PART_NO").value = "";
                        $('#PART_NO').focus();
                    }
                });
            },
        });
    });
</script>
@endpush