<div class="btn-group dropright">
    <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: transparent;border-color: rgb(240, 240, 240);">
        <i class="fa fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item text-primary custprice-act-view" href="javascript:void(0)" data-activedate="{{ $data->active_date }}" data-custid="{{ $data->cust_id }}"><i class="fa fa-eye"></i> View</a>
        <a class="dropdown-item text-warning custprice-act-edit" href="javascript:void(0)" data-activedate="{{ $data->active_date }}" data-custid="{{ $data->cust_id }}"><i class="fa fa-pencil"></i> Edit</a>
        
        @if ($data->posted_date == NULL)
        <a class="dropdown-item text-success custprice-act-posted" href="javascript:void(0)" data-activedate="{{ $data->active_date }}" data-custid="{{ $data->cust_id }}"><i class="fa fa-send"></i> Posted</a>
        @else
        <a class="dropdown-item text-danger custprice-act-unposted" href="javascript:void(0)" data-activedate="{{ $data->active_date }}" data-custid="{{ $data->cust_id }}"><i class="fa fa-send"></i> Unpost</a>
        @endif

        @if ($data->voided_date == NULL)
        <a class="dropdown-item text-danger custprice-act-voided" href="javascript:void(0)" data-activedate="{{ $data->active_date }}" data-custid="{{ $data->cust_id }}"><i class="fa fa-ban"></i> Void</a>
        @else
        <a class="dropdown-item text-danger custprice-act-unvoided" href="javascript:void(0)" data-activedate="{{ $data->active_date }}" data-custid="{{ $data->cust_id }}"><i class="fa fa-ban"></i> Unvoid</a>
        @endif

        <a class="dropdown-item text-primary custprice-act-report" href="javascript:void(0)" data-activedate="{{ $data->active_date }}" data-custid="{{ $data->cust_id }}"><i class="fa fa-print"></i> Print</a>
        <a class="dropdown-item text-primary custprice-act-log" href="javascript:void(0)" data-activedate="{{ $data->active_date }}" data-custid="{{ $data->cust_id }}"><i class="fa fa-share"></i> Log</a>
    </div>
</div>