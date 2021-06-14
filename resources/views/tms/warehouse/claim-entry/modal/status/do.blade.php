<div class="modal fade claim-modal-status-do" style="z-index: 1041" tabindex="-1" id="claim-modal-status-do" data-target="#claim-modal-status-do" data-whatever="@claimmodallog"  role="dialog">
    <div class="modal-dialog">
        <form id="form-status-do" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delivery Order</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="claim-status-do-no">Claim No</label>
                        </div>
                        <div class="col-10">
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <input type="text" name="claim-status-do-no" id="claim-status-do-no" class="form-control form-control-sm readonly-first" readonly autocomplete="off">
                                </div>
                                <div class="col-8">
                                    <input type="text" name="claim-status-do-refno" id="claim-status-do-refno" class="form-control form-control-sm" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="claim-status-do-datejs">Date SJ</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="claim-status-do-datejs" id="claim-status-do-datejs" class="form-control form-control-sm this-datepicker" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="claim-btn-status-do-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="claim-btn-status-do-submit" class="btn btn-info">Delivered</button>
                </div>
            </div>
        </form>
    </div>
</div>