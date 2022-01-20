<div class="modal fade bd-example-modal-lg dbpart-modal-index" style="z-index: 1041" tabindex="-1" id="dbpart-modal-index" data-target="#dbpart-modal-index" data-whatever="@dbpartmodalindex"  role="dialog">
    <div class="modal-dialog modal-xl">
        <form id="dbpart-form-index" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Database Parts</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-row" style="font-size: 11px;">
                        <div class="col-xl-4 border-left">
                            @include('tms.master.db-parts.modal.form.header1')
                        </div>
                        <div class="col-xl-4 border-left border-right">
                            @include('tms.master.db-parts.modal.form.header2')
                        </div>
                        <div class="col-xl-4 border-right">
                            @include('tms.master.db-parts.modal.form.header3')
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="row">
                            <div id="item-button-div" class="col-12 text-right">
                                <button type="button" id="dbpart-btn-add-item" class="btn btn-sm btn-info">
                                    Add Item
                                </button>
                                <button type="button" id="dbpart-btn-edit-item" class="btn btn-sm btn-info" disabled>
                                    Edit
                                </button>
                                <button type="button" id="dbpart-btn-delete-item" class="btn btn-sm btn-info" disabled>
                                    Delete
                                </button>
                            </div>
                            <div class="col-12 mt-2">
                                @include('tms.master.db-parts.modal.table.tbl_index')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="dbpart-btn-index-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="dbpart-btn-index-submit" class="btn btn-info">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>