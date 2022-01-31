<div class="modal fade bd-example-modal-lg mprocess-modal-index" style="z-index: 1041" tabindex="-1" id="mprocess-modal-index" data-target="#mprocess-modal-index" data-whatever="@mprocessmodalindex"  role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="mprocess-form-index" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Master Process</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row align-items-center mb-1" style="font-size: 11px;">
                        <div class="col-2">
                            <label for="mprocess-index-procid" class="auto-middle">Process ID</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="mprocess-index-procid" id="mprocess-index-procid" class="form-control form-control-sm" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1" style="font-size: 11px;">
                        <div class="col-2">
                            <label for="mprocess-index-procitem" class="auto-middle">Process Code for Itemcode</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="mprocess-index-procitem" id="mprocess-index-procitem" class="form-control form-control-sm" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1" style="font-size: 11px;">
                        <div class="col-2">
                            <label for="mprocess-index-procname" class="auto-middle">Process Name</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="mprocess-index-procname" id="mprocess-index-procname" class="form-control form-control-sm" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1" style="font-size: 11px;">
                        <div class="col-2">
                            <label for="mprocess-index-routing" class="auto-middle">Routing</label>
                        </div>
                        <div class="col-10">
                            <select name="mprocess-index-routing" id="mprocess-index-routing" class="form-control form-control-sm" required>
                                <option value="INHOUSE">INHOUSE</option>
                                <option value="SUBCONT">SUBCONT</option>
                                <option value="N/A">N/A</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="mprocess-btn-index-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="mprocess-btn-index-submit" class="btn btn-info">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>