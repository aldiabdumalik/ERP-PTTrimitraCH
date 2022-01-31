<div class="btn-group dropright">
    <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: transparent;border-color: rgb(240, 240, 240);">
        <i class="fa fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu">
        {{-- <a class="dropdown-item text-primary mprocess-act-view" href="javascript:void(0)" data-id="{{ $data->process_id }}"><i class="fa fa-eye"></i> View</a> --}}
        <a class="dropdown-item text-warning mprocess-act-edit" href="javascript:void(0)" data-id="{{ $data->process_id }}"><i class="fa fa-pencil"></i> Edit</a>
        <a class="dropdown-item text-danger mprocess-act-delete" href="javascript:void(0)" data-id="{{ $data->process_id }}"><i class="fa fa-trash"></i> Delete</a>
        <a class="dropdown-item text-primary mprocess-act-log" href="javascript:void(0)" data-id="{{ $data->process_id }}"><i class="fa fa-share"></i> Log</a>
    </div>
</div>