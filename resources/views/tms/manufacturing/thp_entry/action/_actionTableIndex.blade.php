<div class="btn-group dropright">
    <button type="button" class="btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: transparent;border-color: rgb(240, 240, 240);">
        <i class="fa fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item text-primary thp-act-view" href="javascript:void(0)" data-thp="{{ $data->id_thp }}" data-date="{{ $data->thp_date }}"><i class="fa fa-eye"></i> View</a>
        @if ($data->closed == NULL)
        <a class="dropdown-item text-warning thp-act-edit" href="javascript:void(0)" data-thp="{{ $data->id_thp }}"><i class="fa fa-pencil"></i> Edit</a>
        @endif
        @if ($data->closed == NULL)
        <a class="dropdown-item text-danger thp-act-close" href="javascript:void(0)" data-thp="{{ $data->id_thp }}"><i class="fa fa-times"></i> Close</a>
        @endif
        {{-- <a class="dropdown-item text-primary thp-act-print" href="javascript:void(0)" data-thp="{{ $data->id_thp }}"><i class="fa fa-print"></i> Print</a> --}}
        <a class="dropdown-item text-primary thp-act-log" href="javascript:void(0)" data-thp="{{ $data->id_thp }}"><i class="fa fa-share"></i> Log</a>
    </div>
</div>