@extends('master')

@section('title', 'TMS | Warehouse - Transfer Order')

@section('css')

<!-- DATATABLES -->
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">

@endsection

@section('content')           

@include('tms.warehouse.transfer-order.transfer-order-modal')

<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">Transfer Order (TO Adjustment Only)</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="data-tables datatable-dark">
                                <table id="transfer-order-datatable" class="table table-striped" style="width:100%">
                                    {{ csrf_field() }}
                                    <thead class="text-center">
                                        <tr>
                                            <th>TO No</th>
                                            <th>REF No</th>
                                            <th>Period</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Remark</th>
                                            <th>Table Actions</th>
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
        
@endsection

@push('js')

<!-- Datatables -->
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>


<script>

    $(document).ready(function() {
        
        // @@ Load Datatable
        loadDatatable('#transfer-order-datatable');
        $('#transfer-order-detail-datatable').DataTable();

        // 2. View Button
        $(document).on('click', '.view', function(){
            var id = $(this).attr('row-id');
            $('#modal-transfer-order').modal('show');
            getDetail(id, 'VIEW')
        });
        
        
    });

    function loadDatatable(id){
        $(id).DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('tms.warehouse.transfer-order.datatables.header') }}",
            order: [[ 0, 'desc']],
            columnDefs: [
                {"className": "align-right vertical-center", "targets": 6},
                {"className": "align-center vertical-center", "targets": [0, 1, 2, 3, 4, 5]}
            ],
            columns: [
                { data: 'TO_NO', name: 'TO_NO' },
                { data: 'REF_NO', name: 'REF_NO' },
                { data: 'PERIOD', name: 'PERIOD' },
                { data: 'WH_FROM', name: 'WH_FROM' },
                { data: 'WH_TO', name: 'WH_TO' },
                { data: 'REMARK', name: 'REMARK' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }

    function getDetail(id, method){
        var route  = "{{ route('tms.warehouse.transfer-order.detail', ':id') }}";
            route  = route.replace(':id', id);
        $.ajax({
            url:      route,
            method:   'get',
            dataType: 'json',
            success:function(data){
                $('#to-no').val(data['header'].TO_NO);
                $('#staff').val(data['header'].OPERATOR);
                $('#ref-no').val(data['header'].REF_NO);
                $('#period').val(data['header'].PERIOD);
                $('#date').val(data['header'].WRITTEN);
                $('#from-wh-branch').val(data['header'].BRANCH);
                $('#from-wh-code').val(data['header'].WH_FROM);
                $('#to-wh-branch').val(data['header'].BRANCH_TO);
                $('#to-wh-code').val(data['header'].WH_TO);
                $('#remark').val(data['header'].REMARK);
                $('#printed').val(formatDate(data['header'].PRINTED));
                $('#voided').val(formatDate(data['header'].VOIDED));
                $('#posted').val(formatDate(data['header'].POSTED));
                $('#finished').val(formatDate(data['header'].FINISHED));
                $('#total').val(data['header'].TOTAL);

                var detailDataset = [];

                for(var i = 0; i < data['detail'].length; i++){
                    detailDataset.push([
                        data['detail'][i].ITEMCODE, data['detail'][i].PART_NO, data['detail'][i].DESCRIPT,
                        data['detail'][i].FAC_UNIT, data['detail'][i].FAC_QTY, data['detail'][i].FACTOR,
                        data['detail'][i].UNIT, data['detail'][i].QUANTITY
                    ]);
                }

                $('#transfer-order-detail-datatable').DataTable().clear().destroy();
                $('#transfer-order-detail-datatable').DataTable({
                    data: detailDataset,
                    columns: [
                        { title: 'Itemcode'},
                        { title: 'Part No.'},
                        { title: 'Description'},
                        { title: 'F. Unit' },
                        { title: 'F.Qty' },
                        { title: 'Factor' },
                        { title: 'Unit' },
                        { title: 'Qty' }
                    ]
                });
                
                // resetForm();
                // if(method == 'FORM') {
                //     $('#role-form').attr('action', editURL);
                //     $('#modal-role-name').html(data.name);
                //     $('#role-id').val(data.id);
                //     $('#role-name').val(data.name);
                //     $('#role-description').val(data.description);
                // } else if(method == 'VIEW') {
                //     viewForm('SHOW');
                //     inputForm('HIDE');
                //     $('#modal-role-name').html(data.name)
                //     $('#role-id').val(data.id);
                //     $('#role-view-name').html(data.name);
                //     $('#role-view-description').html(data.description);
                // }
                // $('#modal-role-form').modal('show');
            }
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

@endpush