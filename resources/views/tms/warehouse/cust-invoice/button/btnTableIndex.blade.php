<div class="btn-group dropright">
    <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: transparent;border-color: rgb(240, 240, 240);">
        <i class="fa fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item text-primary custinv-act-view" href="javascript:void(0)" data-invno="{{ $data->inv_no }}" style="font-size: 12px!important;" style="font-size: 12px!important;"><i class="fa fa-eye"></i> View</a>
        <a class="dropdown-item text-warning custinv-act-edit" href="javascript:void(0)" data-invno="{{ $data->inv_no }}" style="font-size: 12px!important;"><i class="fa fa-pencil"></i> Edit</a>
        
        @if ($data->posted_date == NULL)
        <a class="dropdown-item text-success custinv-act-posted" href="javascript:void(0)" data-invno="{{ $data->inv_no }}" style="font-size: 12px!important;"><i class="fa fa-send"></i> Posted</a>
        @else
        <a class="dropdown-item text-danger custinv-act-unposted" href="javascript:void(0)" data-invno="{{ $data->inv_no }}" style="font-size: 12px!important;"><i class="fa fa-send"></i> Unpost</a>
        @endif
        

        @if ($data->voided_date == NULL)
        <a class="dropdown-item text-danger custinv-act-voided" href="javascript:void(0)" data-invno="{{ $data->inv_no }}" style="font-size: 12px!important;"><i class="fa fa-ban"></i> Void</a>
        @else
        <a class="dropdown-item text-danger custinv-act-unvoided" href="javascript:void(0)" data-invno="{{ $data->inv_no }}" style="font-size: 12px!important;"><i class="fa fa-ban"></i> Unvoid</a>
        @endif
        
        @if ($data->finished_date !== NULL)
        <a class="dropdown-item text-danger custinv-act-unfinished" href="javascript:void(0)" data-invno="{{ $data->inv_no }}" style="font-size: 12px!important;"><i class="fa fa-times"></i> Unfinish</a>
        @endif

        <a class="dropdown-item text-primary custinv-act-report" href="javascript:void(0)" data-invno="{{ $data->inv_no }}" style="font-size: 12px!important;"><i class="fa fa-print"></i> Print</a>
        <a class="dropdown-item text-primary custinv-act-note" href="javascript:void(0)" data-invno="{{ $data->inv_no }}" style="font-size: 12px!important;"><i class="fa fa-list"></i> Note</a>
        <a class="dropdown-item text-primary custinv-act-payment" href="javascript:void(0)" data-invno="{{ $data->inv_no }}" style="font-size: 12px!important;"><i class="fa fa-credit-card"></i> Payment</a>
        <a class="dropdown-item text-primary custinv-act-log" href="javascript:void(0)" data-invno="{{ $data->inv_no }}" style="font-size: 12px!important;"><i class="fa fa-share"></i> Log</a>
    </div>
</div>