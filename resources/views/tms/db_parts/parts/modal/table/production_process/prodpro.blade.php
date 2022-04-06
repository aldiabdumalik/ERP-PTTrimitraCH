<div class="modal fade bd-example-modal-lg prodpro-modal-index" style="z-index: 1041" tabindex="-1" id="prodpro-modal-index" data-target="#prodpro-modal-index" data-whatever="@prodpromodalindex"  role="dialog">
    <div class="modal-dialog modal-xl">
        <form id="prodpro-form-index" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Production Process</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row" style="font-size: 11px;">
                        <input type="hidden" name="prodpro-index-id" id="prodpro-index-id" value="0">
                    </div>
                    <div class="mt-2">
                        <div class="row">
                            <div id="prodpro-btn-table-item" class="col-12 text-right">
                                <button type="button" id="prodpro-btn-add-item" class="btn btn-sm btn-info">
                                    Add
                                </button>
                                <button type="button" id="prodpro-btn-delete-item" class="btn btn-sm btn-danger" disabled>
                                    Delete
                                </button>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="table-responsive">
                                    <div class="">
                                        <table id="prodpro-datatables-index" class="table table-bordered" style="width:100%;cursor:pointer">
                                            <thead class="text-center btn-info" style="font-size: 15px;">
                                                <tr style="font-size: 14px;">
                                                    <th class="align-middle">Sequence</th>
                                                    <th class="align-middle">Process</th>
                                                    <th class="align-middle">Detail Process</th>
                                                    <th class="align-middle">CT <i>(Sec)</i></th>
                                                    <th class="align-middle">Tools Parts</th>
                                                    <th class="align-middle">Tonage</th>
                                                    <th class="align-middle">Prod. Line</th>
                                                    <th class="align-middle">Name <br> <i>(PT/CV/etc.)</i></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="prodpro-btn-index-close" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="prodpro-btn-index-submit" class="btn btn-info">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>