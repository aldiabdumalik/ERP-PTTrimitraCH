<div class="btn-group dropright">
    <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: transparent;border-color: rgb(240, 240, 240);">
        <i class="fa fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu">
        @if ($data->is_active == 1)
            <a class="dropdown-item text-warning mprocess-act-edit" href="javascript:void(0)" data-id="{{ $data->process_id }}"><i class="fa fa-pencil"></i> Edit</a>
            <a class="dropdown-item text-danger mprocess-act-delete" href="javascript:void(0)" data-id="{{ $data->process_id }}"><i class="fa fa-trash"></i> Delete</a>
        @else
            <a class="dropdown-item text-success mprocess-act-active" href="javascript:void(0)" data-id="{{ $data->process_id }}"><i class="fa fa-check"></i> Reactived</a>
        @endif      
        <a class="dropdown-item text-primary mprocess-act-log" href="javascript:void(0)" data-id="{{ $data->process_id }}"><i class="fa fa-share"></i> Log</a>
    </div>
</div>