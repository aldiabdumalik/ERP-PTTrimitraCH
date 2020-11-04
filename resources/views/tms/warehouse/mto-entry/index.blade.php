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
               <button type="button"  class="btn btn-primary" id="addModal">
                Add
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
                                    <thead class="text-center">
                                        <tr>
                                            <th>MTO No</th>
                                            <th>Written</th>
                                            <th>Posted</th>
                                            <th>Voided</th>
                                            <th>Item Code</th>
                                            <th>Ref No</th>
                                            <th>Remark</th>
                                            <th>Brch</th>
                                            <th>ACTION</th>
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
@include('tms.warehouse.mto-entry.modal.popup-mto-choicedata.popUpMto') 
@include('tms.warehouse.mto-entry.modal.edit-mto-modal._edit')
@include('tms.warehouse.mto-entry.modal.view-mto-modal._viewmto')
@include('tms.warehouse.mto-entry.modal.create-mto-modal._create')
@endsection
@section('script')
<script type="text/javascript">

function keyPressed(e){
    if (e.keyCode == 120) {
       $('#btnPopUp').click();
    } else if(e.keyCode){
        e.preventDefault();
    }
}
function setTwoNumberDecimal(event) {
    this.value = parseFloat(this.value).toFixed(2);
}

$(function(){
    $('#select_create').select2();
    $('#select_view').select2();
    $('#select_edit').select2();
});



// SHOW VIEW DATA MTO
$(document).on('click', '.view', function(){
    var id = $(this).attr('row-id');
    $('#viewModal').modal('show');
    getDetail(id, 'VIEW')
});
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
                $('#vperiod_view').val(data['header'].vperiod);
                $('#branch_view').val(data['header'].branch);
                $('#warehouse_view').val(data['header'].warehouse);
                
                var detailDataset = [];
                for(var i = 0; i < data['detail'].length; i++){
                    detailDataset.push([
                        data['detail'][i].fin_code, data['detail'][i].frm_code, data['detail'][i].descript,
                        data['detail'][i].types, data['detail'][i].quantity
                    ]);
                }
                $('#tbl-detail-mto').DataTable().clear().destroy();
                $('#tbl-detail-mto').DataTable({
                    data: detailDataset,
                    columns: [
                        { title: 'itemcode'},
                        { title: 'Part No.'},
                        { title: 'Description'},
                        { title: 'Type' },
                        { title: 'Qty' }
                    ]
                });
            }
        });
    }

// ADDED NEW MTO DATA
$(document).on('click', '#addModal', function() {
   $('#createModal').modal('show');
   $('.modal-title').text('Many To One Entry (New)');
});
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
                'Added new data!',
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
// EDIT DATA MTO
$(document).on('click', '.edit', function(e){
    var id = $(this).attr('row-id');
    $('.modal-title').text('Edit Many To One Entry (New)');
    $('#EditModal').modal('show');
    EditData(id)
    UpdateData(id)
});

$(document).on('click', '.delete', function(e){
    var id = $(this).attr('row-id');
    
});

function EditData(id){
        var route  = "{{ route('tms.warehouse.mto-entry_edit_mto_data', ':id') }}";
            route  = route.replace(':id', id);
        $.ajax({
            url:      route,
            method:   'get',
            dataType: 'json',
            success:function(data){
                $('#mto_no_edit').val(data['data'].mto_no);
                $('#branch_edit').val(data['data'].branch);
                $('#warehouse_edit').val(data['data'].warehouse);
                $('#ref_no_edit').val(data['data'].ref_no);
                $('#ITEMCODE').val(data['data'].fin_code);
                $('#PART_NO').val(data['data'].frm_code);
                $('#DESCRIPT').val(data['data'].descript);
                $('#quantity_edit').val(data['data'].quantity);
                $('#qty_ng_edit').val(data['data'].qty_ng);
                $('#unit_edit').val(data['data'].unit);
                $('#remark_edit').val(data['data'].remark);
                $('#printed_edit').val(data['data'].printed);
                $('#voided_edit').val(data['data'].voided);
                $('#posted_edit').val(data['data'].posted);

                var detailDataset = [];
                for(var i = 0; i < data['detail'].length; i++){
                    detailDataset.push([
                        data['detail'][i].fin_code, data['detail'][i].frm_code, data['detail'][i].descript,
                        data['detail'][i].types, data['detail'][i].quantity
                    ]);
                }
                $('#tbl-edit').DataTable().clear().destroy();
                $('#tbl-edit').DataTable({
                    data: detailDataset,
                    columns: [
                        { title: 'itemcode'},
                        { title: 'Part No.'},
                        { title: 'Description'},
                        { title: 'Type' },
                        { title: 'Qty' }
                    ]
                });
            }, 
            error: function(){
                alert('error');
            }
        });
        
        
    }
// UPDATE MTO
function UpdateData(id){
    var route  = "{{ route('tms.warehouse.mto-entry_update_mto_entry', ':id') }}";
        route  = route.replace(':id', id);
    $('.modal-footer').on('click','.edit', function(){
            $('.edit').html('Saving...');
            $.ajax({
                url: route,
                type: "PUT",
                data: $('#form-mto-edit').serialize(),
                success: function(data){
                   $("#EditModal").modal('hide'); 
                   Swal.fire(
                    'Successfully!',
                    'Update data!',
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
        });
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
                    var itemcode = document.getElementById("ITEMCODE").value.trim();
                    if(itemcode === '') {
                        document.getElementById("part_no").value = "";
                        $('#PART_NO').focus();
                    }
                });
            },
        });
        
        
        // EDIT CHOICE DATA
        
        

    });
    
  
</script>
@endpush