<div class="modal fade bd-example-modal-lg custprice-modal-itemadd" style="z-index: 1041" tabindex="-1" id="custprice-modal-itemadd" data-target="#custprice-modal-itemadd" data-whatever="@custpricemodalindex"  role="dialog">
    <div class="modal-dialog">
        <form id="custprice-form-itemadd" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-body">
                    <div style="font-size: 11px;">
                        <div class="form-row align-items-center mb-1">
                            <div class="col-10">
                                <input type="hidden" name="custprice-additem-index" id="custprice-additem-index" class="form-control form-control-sm" readonly value="0">
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="custprice-additem-itemcode">Item Code</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="custprice-additem-itemcode" id="custprice-additem-itemcode" class="form-control form-control-sm" readonly autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="custprice-additem-partno">Part No.</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="custprice-additem-partno" id="custprice-additem-partno" class="form-control form-control-sm" required readonly>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="custprice-additem-description">Description</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="custprice-additem-description" id="custprice-additem-description" class="form-control form-control-sm" required readonly>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="custprice-additem-unit">Unit</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="custprice-additem-unit" id="custprice-additem-unit" class="form-control form-control-sm" required readonly>
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-1">
                            <div class="col-2">
                                <label for="custprice-additem-newprice">New Price</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="custprice-additem-newprice" id="custprice-additem-newprice" class="form-control form-control-sm" required value="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="custprice-btn-itemadd-close" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="custprice-btn-itemadd-submit" class="btn btn-info">Add Item</button>
                </div>
            </div>
        </form>
    </div>
</div>