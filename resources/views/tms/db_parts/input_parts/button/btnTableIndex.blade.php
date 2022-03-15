<div class="btn-group dropright">
    <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: transparent;border-color: rgb(240, 240, 240);">
        <i class="fa fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu">
        @if ($data->is_active == 1)
            <a class="dropdown-item text-success iparts-act-view" href="javascript:void(0)" data-id="{{ $data->id }}"><i class="fa fa-eye"></i> View</a>
            <a class="dropdown-item text-warning iparts-act-edit" href="javascript:void(0)" data-id="{{ $data->id }}"><i class="fa fa-pencil"></i> Edit</a>
            <a class="dropdown-item text-danger iparts-act-delete" href="javascript:void(0)" data-id="{{ $data->id }}"><i class="fa fa-trash"></i> Delete</a>
        @else
            <a class="dropdown-item text-success iparts-act-active" href="javascript:void(0)" data-id="{{ $data->id }}"><i class="fa fa-check"></i> Reactived</a>
        @endif      
        <a class="dropdown-item text-primary iparts-act-revisi" href="javascript:void(0)" data-id="{{ $data->id }}" data-no="{{ $data->part_no }}" data-name="{{ $data->part_name }}"><i class="fa fa-share"></i> Revision</a>
        <a class="dropdown-item text-primary iparts-act-log" href="javascript:void(0)" data-id="{{ $data->id }}"><i class="fa fa-share"></i> Log</a>
    </div>
</div>