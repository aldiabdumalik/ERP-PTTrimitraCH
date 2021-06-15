<div class="btn-group dropright">
    <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: transparent;border-color: rgb(240, 240, 240);">
        <i class="fa fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item text-primary claim-act-view" href="javascript:void(0)" data-clno="{{ $data->cl_no }}"><i class="fa fa-eye"></i> View</a>
        <a class="dropdown-item text-warning claim-act-edit" href="javascript:void(0)" data-clno="{{ $data->cl_no }}"><i class="fa fa-pencil"></i> Edit</a>
        @if ($data->date_do == NULL)
        <a class="dropdown-item text-success claim-act-do" href="javascript:void(0)" data-clno="{{ $data->cl_no }}" data-refno="{{ $data->ref_no }}"><i class="fa fa-send"></i> DO</a>
        @else
        <a class="dropdown-item text-success claim-act-undo" href="javascript:void(0)" data-clno="{{ $data->cl_no }}" data-refno="{{ $data->ref_no }}"><i class="fa fa-send"></i> Undelivered</a>
        @endif
        <a class="dropdown-item text-success claim-act-rg" href="javascript:void(0)" data-clno="{{ $data->cl_no }}" data-pono="{{$data->po_no}}" data-customer="{{$data->cust_code}}|{{$data->company}}" ><i class="fa fa-check"></i> RG</a>
        {{-- @if ($data->date_rg == NULL)
        <a class="dropdown-item text-success claim-act-rg" href="javascript:void(0)" data-clno="{{ $data->cl_no }}" data-pono="{{$data->po_no}}" data-customer="{{$data->cust_code}}|{{$data->company}}" ><i class="fa fa-check"></i> RG</a>
        @else
        <a class="dropdown-item text-success claim-act-unrg" href="javascript:void(0)" data-clno="{{ $data->cl_no }}"><i class="fa fa-check"></i> Unreceived</a>
        @endif --}}
        @if ($data->voided == NULL)
        <a class="dropdown-item text-danger claim-act-voided" href="javascript:void(0)" data-clno="{{ $data->cl_no }}"><i class="fa fa-ban"></i> Void</a>
        @else
        <a class="dropdown-item text-danger claim-act-unvoided" href="javascript:void(0)" data-clno="{{ $data->cl_no }}"><i class="fa fa-ban"></i> Unvoided</a>
        @endif
        @if ($data->closed != NULL)
        <a class="dropdown-item text-danger claim-act-unclose" href="javascript:void(0)" data-clno="{{ $data->cl_no }}"><i class="fa fa-times"></i> Unclose</a>
        @endif
        <a class="dropdown-item text-primary claim-act-report" href="javascript:void(0)" data-clno="{{ $data->cl_no }}"><i class="fa fa-print"></i> Print</a>
        <a class="dropdown-item text-primary claim-act-log" href="javascript:void(0)" data-clno="{{ $data->cl_no }}"><i class="fa fa-share"></i> Log</a>
    </div>
</div>