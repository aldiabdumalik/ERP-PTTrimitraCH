<div class="modal fade claim-modal-additem" style="z-index: 1041" tabindex="-1" id="claim-modal-additem" data-target="#claim-modal-additem" data-whatever="@claimmodaladditem"  role="dialog">
    <div class="modal-dialog">
        <form id="claim-form-additem" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Item</h4>
                </div>
                <div class="modal-body">
                    <div style="font-size: 11px;">
                        <div class="form-row align-items-center mb-1">
                            {{-- <div class="col-2">
                                <label for="claim-additem-index">Index</label>
                            </div> --}}
                            <div class="col-10">
                                <input type="hidden" name="claim-additem-index" id="claim-additem-index" class="form-control form-control-sm" readonly value="0">
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="claim-additem-itemcode">Item Code</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="claim-additem-itemcode" id="claim-additem-itemcode" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="claim-additem-partno">Part No.</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="claim-additem-partno" id="claim-additem-partno" class="form-control form-control-sm" required readonly>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="claim-additem-description">Description</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="claim-additem-description" id="claim-additem-description" class="form-control form-control-sm" required readonly>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="claim-additem-unit">Unit</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="claim-additem-unit" id="claim-additem-unit" class="form-control form-control-sm" required readonly>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="claim-additem-qtysj">Qty SJ</label>
                            </div>
                            <div class="col-10">
                                <input type="number" name="claim-additem-qtysj" id="claim-additem-qtysj" class="form-control form-control-sm" required value="0">
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="claim-additem-qtyrg">Qty RG</label>
                            </div>
                            <div class="col-10">
                                <input type="number" name="claim-additem-qtyrg" id="claim-additem-qtyrg" class="form-control form-control-sm" value="0" required readonly>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="claim-additem-note">Note</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="claim-additem-note" id="claim-additem-note" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="claim-btn-additem-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="claim-btn-additem-submit" class="btn btn-info">Add item</button>
                </div>
            </div>
        </form>
    </div>
</div>