<div class="modal fade bd-example-modal-lg custinv-modal-note" style="z-index: 1041" tabindex="-1" id="custinv-modal-note" data-target="#custinv-modal-note" data-whatever="@domodallog"  role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Customer Invoice Print</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-row align-items-center">
                            <div class="col-2">
                                <label for="custinv-note-invno" class="auto-middle">Inv No.</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="custinv-note-invno" id="custinv-note-invno" class="form-control form-control-sm" required readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-row align-items-center">
                            <div class="col-2">
                                <label for="custinv-note-po" class="auto-middle">PO No.</label>
                            </div>
                            <div class="col-10">
                                <textarea name="custinv-note-po" id="custinv-note-po" class="form-control form-control-sm" required cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-row align-items-center">
                            <div class="col-2">
                                <label for="custinv-note-sj" class="auto-middle">SJ No.</label>
                            </div>
                            <div class="col-10">
                                <textarea name="custinv-note-sj" id="custinv-note-sj" class="form-control form-control-sm" required cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="custinv-btn-note-close" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="custinv-btn-note-ok" class="btn btn-info">Update</button>
            </div>
        </div>
    </div>
</div>