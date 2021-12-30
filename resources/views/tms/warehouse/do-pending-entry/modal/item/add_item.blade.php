<div class="modal fade do-modal-additem" style="z-index: 1041" tabindex="-1" id="do-modal-additem" data-target="#do-modal-additem" data-whatever="@domodaladditem"  role="dialog">
    <div class="modal-dialog">
        <form id="do-form-additem" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Item</h4>
                </div>
                <div class="modal-body">
                    <div style="font-size: 11px;">
                        <div class="form-row align-items-center mb-1">
                            {{-- <div class="col-2">
                                <label for="do-additem-index">Index</label>
                            </div> --}}
                            <div class="col-10">
                                <input type="hidden" name="do-additem-index" id="do-additem-index" class="form-control form-control-sm readonly-first" readonly value="0">
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="do-additem-itemcode">Item Code</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="do-additem-itemcode" id="do-additem-itemcode" class="form-control form-control-sm" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="do-additem-partno">Part No.</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="do-additem-partno" id="do-additem-partno" class="form-control form-control-sm readonly-first" required readonly>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="do-additem-description">Description</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="do-additem-description" id="do-additem-description" class="form-control form-control-sm readonly-first" required readonly>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="do-additem-unit">Unit</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="do-additem-unit" id="do-additem-unit" class="form-control form-control-sm readonly-first" required readonly>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="do-additem-qtysj">Qty SJ</label>
                            </div>
                            <div class="col-10">
                                <input type="number" name="do-additem-qtysj" id="do-additem-qtysj" class="form-control form-control-sm" required value="0">
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="do-additem-qtybilled">Qty Billed</label>
                            </div>
                            <div class="col-10">
                                <input type="number" name="do-additem-qtybilled" id="do-additem-qtybilled" class="form-control form-control-sm readonly-first" value="0" required readonly>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="do-additem-qtytag">Qty Tag</label>
                            </div>
                            <div class="col-10">
                                <input type="number" name="do-additem-qtytag" id="do-additem-qtytag" class="form-control form-control-sm readonly-first" value="0" required readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="do-btn-additem-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="do-btn-additem-submit" class="btn btn-info">Add item</button>
                </div>
            </div>
        </form>
    </div>
</div>