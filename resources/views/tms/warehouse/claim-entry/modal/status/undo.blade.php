<div class="modal fade claim-modal-status-undo" style="z-index: 1041" tabindex="-1" id="claim-modal-status-undo" data-target="#claim-modal-status-undo" data-whatever="@claimmodallog"  role="dialog">
    <div class="modal-dialog">
        <form id="form-status-undo" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">UnDelivery Order</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="claim-status-undo-no">Claim No</label>
                        </div>
                        <div class="col-10">
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <input type="text" name="claim-status-undo-no" id="claim-status-undo-no" class="form-control form-control-sm readonly-first" readonly autocomplete="off">
                                </div>
                                <div class="col-8">
                                    <input type="text" name="claim-status-undo-refno" id="claim-status-undo-refno" class="form-control form-control-sm" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="claim-status-undo-datejs">Date SJ</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="claim-status-undo-datejs" id="claim-status-undo-datejs" class="form-control form-control-sm" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="claim-btn-status-undo-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>