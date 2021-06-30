<div class="modal fade bd-example-modal-lg do-modal-create" style="z-index: 1041" tabindex="-1" id="do-modal-create" data-target="#do-modal-create" data-whatever="@domodalcreate"  role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form id="do-form-create" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">DO Entry</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row" style="font-size: 11px;">
                        <div class="col-xl-4 border-left">
                            @include('tms.warehouse.do-entry.modal.create.header1')
                        </div>
                        <div class="col-xl-4 border-left">
                            @include('tms.warehouse.do-entry.modal.create.header2')
                        </div>
                        <div class="col-xl-4 border-left border-right">
                            @include('tms.warehouse.do-entry.modal.create.header3')
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div id="item-button-div" class="col-12 text-right">
                            <button type="button" id="do-btn-add-item" class="btn btn-sm btn-info">
                                Add Item
                            </button>
                            {{-- <button type="button" id="do-btn-edit-item" class="btn btn-sm btn-info" disabled>
                                Edit
                            </button>
                            <button type="button" id="do-btn-delete-item" class="btn btn-sm btn-info" disabled>
                                Delete
                            </button> --}}
                        </div>
                        <div class="col-12">
                            @include('tms.warehouse.do-entry.modal.create.table_create_item')
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="do-btn-create-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="do-btn-create-submit" class="btn btn-info">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>