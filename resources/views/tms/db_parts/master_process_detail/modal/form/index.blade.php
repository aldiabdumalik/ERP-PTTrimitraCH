<div class="modal fade bd-example-modal-lg mdprocess-modal-index" style="z-index: 1041" tabindex="-1" id="mdprocess-modal-index" data-target="#mdprocess-modal-index" data-whatever="@mdprocessmodalindex"  role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="mdprocess-form-index" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Master Detail Process</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="mdprocess-index-id" id="mdprocess-index-id" class="form-control form-control-sm" autocomplete="off" value="0">
                    <div class="form-row align-items-center mb-1" style="font-size: 11px;">
                        <div class="col-2">
                            <label for="mdprocess-index-procid" class="auto-middle">Process ID</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="mdprocess-index-procid" id="mdprocess-index-procid" placeholder="Press ENTER" class="form-control form-control-sm" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1" style="font-size: 11px;">
                        <div class="col-2">
                            <label for="mdprocess-index-procname" class="auto-middle">Process name</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="mdprocess-index-procname" id="mdprocess-index-procname" class="form-control form-control-sm readonly-first" autocomplete="off" required readonly>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1" style="font-size: 11px;">
                        <div class="col-2">
                            <label for="mdprocess-index-procdetailname" class="auto-middle">Process detail name</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="mdprocess-index-procdetailname" id="mdprocess-index-procdetailname" class="form-control form-control-sm" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="mdprocess-btn-index-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="mdprocess-btn-index-submit" class="btn btn-info">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>