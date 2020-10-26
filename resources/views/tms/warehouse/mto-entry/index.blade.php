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
                <a href="#" class="btn btn-primary btn-round" id="add_form">
                    Add 
                </a>
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
        
@endsection

@push('js')

<!-- Datatables -->
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>


<script>

    $(document).ready(function() {
        // var tableDetail = $('#mto-datatables').DataTable({
        //     "columnDefs": [{
        //         "searchable": false,
        //         "orderable": false,
        //         "targets": 0,

        //         render: function (data, type, row, meta) {
        //         return meta.row + meta.settings._iDisplayStart + 1;
        //         }
        //     }],
        //     "aLengthMenu": [[5, 10, 25, 50, 75, 100, -1], [5, 10, 25, 50, 75, 100, "All"]],
        //     "iDisplayLength": 10,
        //     responsive: true,
        //     "order": [[1, 'asc']],
        //     processing: true, 
        //     serverSide: true,
        //     ajax: "",
        //     columns: [
        //     {data: null, name: null, orderable: false, searchable: false},



            

        //     ]
        // });
    });

</script>

@endpush