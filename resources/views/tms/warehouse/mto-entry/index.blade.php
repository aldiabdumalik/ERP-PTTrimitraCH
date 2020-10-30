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
               <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">
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

@include('tms.warehouse.mto-entry.modal._create')
@include('tms.warehouse.mto-entry.modal.popUpMto') 
  
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
    $('#select2').select2();
});

</script>
@endsection
@push('js')
<!-- Datatables -->
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {
        //get data from datatables
        $('#mto-datatables').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('tms.warehouse.mto-entry_datatables') }}",
            order: [[ 0, 'desc']],
            columnDefs: [
                {"className": "align-right vertical-center", "targets": 6},
                {"className": "align-center vertical-center", "targets": [0, 1, 2, 3, 4, 5]}
            ],
            columns: [
                { data: 'mto_no', name: 'mto_no' },
                { data: 'written', name: 'written' },
                { data: 'posted', name: 'posted' },
                { data: 'voided', name: 'voided' },
                { data: 'itemcode', name: 'itemcode' },
                { data: 'ref_no', name: 'ref_no' },
                { data: 'remark', name: 'remark' },
                { data: 'branch', name: 'branch' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]


            
        });

        // get Datatables choices data
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
                        document.getElementById("ITEMCODE").value = value["ITEMCODE"];
                        document.getElementById("PART_NO").value = value["PART_NO"];
                        document.getElementById("DESCRIPT").value = value["DESCRIPT"];
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
  

    });
    
  
</script>
@endpush