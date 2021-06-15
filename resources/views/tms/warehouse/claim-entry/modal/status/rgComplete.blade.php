<div class="modal fade claim-modal-status-rg-complete" style="z-index: 1041" tabindex="-1" id="claim-modal-status-rg-complete" data-target="#claim-modal-status-rg-complete" data-whatever="@claimmodallog"  role="dialog">
    <div class="modal-dialog modal-80"> 
        <form id="form-status-rg-complete" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Good Received</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row align-items-center mb-1" style="font-size: 11px;">
                        <div class="col-2">
                            <label for="claim-status-rg-complete-customer-id">Customer</label>
                        </div>
                        <div class="col-10">
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <input type="text" name="claim-status-rg-complete-customer-id" id="claim-status-rg-complete-customer-id" class="form-control form-control-sm readonly-first" readonly autocomplete="off">
                                </div>
                                <div class="col-8">
                                    <input type="text" name="claim-status-rg-complete-customer-name" id="claim-status-rg-complete-customer-name" class="form-control form-control-sm readonly-first" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1" style="font-size: 11px;">
                        <div class="col-2">
                            <label for="claim-status-rg-complete-no">Claim / PO No</label>
                        </div>
                        <div class="col-10">
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <input type="text" name="claim-status-rg-complete-no" id="claim-status-rg-complete-no" class="form-control form-control-sm readonly-first" readonly autocomplete="off">
                                </div>
                                <div class="col-8">
                                    <input type="text" name="claim-status-rg-complete-pono" id="claim-status-rg-complete-pono" class="form-control form-control-sm readonly-first" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="table-responsive">
                                <div class="datatable datatable-primary">
                                    <table id="claim-datatables-rg-complete" class="table table-bordered" style="width:100%;cursor:pointer">
                                        <thead class="text-center" style="font-size: 15px;">
                                            <tr style="font-size: 14px;">
                                                <th class="align-middle">#</th>
                                                <th class="align-middle">Claim No.</th>
                                                <th class="align-middle">Itemcode</th>
                                                <th class="align-middle">Description</th>
                                                <th class="align-middle">Unit</th>
                                                <th class="align-middle">Qty RG</th>
                                                <th class="align-middle">Doc No</th>
                                                <th class="align-middle">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="claim-btn-rg-complete-delete" class="btn btn-sm btn-info" disabled>
                        Delete
                    </button>
                    <button type="button" id="claim-btn-status-rg-complete-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>