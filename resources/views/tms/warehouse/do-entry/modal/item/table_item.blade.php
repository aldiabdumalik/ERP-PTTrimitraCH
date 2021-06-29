<div class="modal fade bd-example-modal-lg do-modal-itemtable" style="z-index: 1042" tabindex="-1" id="do-modal-itemtable" data-target="#do-modal-itemtable" data-whatever="@domodalitemtable"  role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delivery Order Entry</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <div class="datatable datatable-primary">
                        <table id="do-datatables-items" class="table table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="text-center" style="font-size: 15px;">
                                <tr style="font-size: 14px;">
                                    <th class="text-center align-middle">DN No</th>
                                    <th class="text-center align-middle">Itemcode</th>
                                    <th class="text-center align-middle">Part No</th>
                                    <th class="text-center align-middle">SSO No</th>
                                    <th class="text-center align-middle">SO No</th>
                                    <th class="text-center align-middle">Descript</th>
                                    <th class="text-center align-middle">Type</th>
                                    <th class="text-center align-middle">SO Qty</th>
                                    <th class="text-center align-middle">SSO Qty</th>
                                    <th class="text-center align-middle">Already Sent</th>
                                    <th class="text-center align-middle">Unit</th>
                                    <th class="text-center align-middle">Will be Sent</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="do-btn-itemtable-selectall" class="btn btn-info">Select All Item</button>
                <button type="button" id="do-btn-itemtable-submit" class="btn btn-info">Add Item</button>
                <button type="button" id="do-btn-itemtable-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>