<div class="btn-group dropright">
    <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: transparent;border-color: rgb(240, 240, 240);">
        <i class="fa fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item text-primary do-act-view" href="javascript:void(0)" data-dono="{{ $data->do_no }}"><i class="fa fa-eye"></i> View</a>
        <a class="dropdown-item text-warning do-act-edit" href="javascript:void(0)" data-dono="{{ $data->do_no }}"><i class="fa fa-pencil"></i> Edit</a>
        
        @if ($data->posted_date == NULL)
        <a class="dropdown-item text-success do-act-posted" href="javascript:void(0)" data-dono="{{ $data->do_no }}"><i class="fa fa-send"></i> Posted</a>
        @else
        <a class="dropdown-item text-danger do-act-unposted" href="javascript:void(0)" data-dono="{{ $data->do_no }}"><i class="fa fa-send"></i> Unpost</a>
        @endif
        

        @if ($data->voided_date == NULL)
        <a class="dropdown-item text-danger do-act-voided" href="javascript:void(0)" data-dono="{{ $data->do_no }}"><i class="fa fa-ban"></i> Void</a>
        @else
        <a class="dropdown-item text-danger do-act-unvoided" href="javascript:void(0)" data-dono="{{ $data->do_no }}"><i class="fa fa-ban"></i> Unvoid</a>
        @endif

        {{-- @if ($data->posted_date != NULL && $data->finished_date == NULL)
        <a class="dropdown-item text-success do-act-finished" href="javascript:void(0)" data-dono="{{ $data->do_no }}"><i class="fa fa-send"></i> Finish</a>
        @endif --}}
        @if ($data->finished_date !== NULL)
        <a class="dropdown-item text-danger do-act-unfinished" href="javascript:void(0)" data-dono="{{ $data->do_no }}"><i class="fa fa-times"></i> Unfinish</a>
        @endif

        @if ($data->finished_date !== NULL)
        <a class="dropdown-item text-success do-act-revise" href="javascript:void(0)" data-dono="{{ $data->do_no }}"><i class="fa fa-reply"></i> Revise</a>
        @endif

        <a class="dropdown-item text-primary do-act-report" href="javascript:void(0)" data-dono="{{ $data->do_no }}"><i class="fa fa-print"></i> Print</a>
        <a class="dropdown-item text-primary do-act-log" href="javascript:void(0)" data-dono="{{ $data->do_no }}"><i class="fa fa-share"></i> Log</a>
    </div>
</div>