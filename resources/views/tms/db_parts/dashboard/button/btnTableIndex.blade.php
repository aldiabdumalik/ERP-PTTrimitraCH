<div class="btn-group dropright">
    <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: transparent;border-color: rgb(240, 240, 240);">
        <i class="fa fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu">
        @if (is_null($data->deleted_at))
            <a class="dropdown-item text-warning projects-act-edit" href="javascript:void(0)" data-id="{{ $data->id }}"><i class="fa fa-pencil"></i> Edit</a>
            <a class="dropdown-item text-danger projects-act-delete" href="javascript:void(0)" data-id="{{ $data->id }}" data-customer="{{ $data->cust_id }}"><i class="fa fa-trash"></i> Delete</a>
            <a class="dropdown-item text-success projects-act-parts" href="javascript:void(0)" data-id="{{ $data->id }}"><i class="fa fa-eye"></i> Item Part</a>
            <a class="dropdown-item text-success projects-act-posted" href="javascript:void(0)" data-id="{{ $data->id }}"><i class="fa fa-share"></i> Posted Revision</a>
        @else
            <a class="dropdown-item text-success projects-act-active" href="javascript:void(0)" data-id="{{ $data->id }}"><i class="fa fa-check"></i> Reactived</a>
        @endif
            <a class="dropdown-item text-primary projects-act-log" href="javascript:void(0)" data-id="{{ $data->id }}"><i class="fa fa-share"></i> Log</a>
    </div>
</div>