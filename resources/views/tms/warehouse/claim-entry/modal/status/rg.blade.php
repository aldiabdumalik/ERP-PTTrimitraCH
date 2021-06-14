<div class="modal fade claim-modal-status-rg" style="z-index: 1041" tabindex="-1" id="claim-modal-status-rg" data-target="#claim-modal-status-rg" data-whatever="@claimmodallog"  role="dialog">
    <div class="modal-dialog modal-80"> 
        <form id="form-status-rg" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Good Received</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row align-items-center mb-1" style="font-size: 11px;">
                        <div class="col-2">
                            <label for="claim-status-rg-customer-id">Customer</label>
                        </div>
                        <div class="col-10">
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <input type="text" name="claim-status-rg-customer-id" id="claim-status-rg-customer-id" class="form-control form-control-sm readonly-first" readonly autocomplete="off">
                                </div>
                                <div class="col-8">
                                    <input type="text" name="claim-status-rg-customer-name" id="claim-status-rg-customer-name" class="form-control form-control-sm readonly-first" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1" style="font-size: 11px;">
                        <div class="col-2">
                            <label for="claim-status-rg-no">Claim / PO No</label>
                        </div>
                        <div class="col-10">
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <input type="text" name="claim-status-rg-no" id="claim-status-rg-no" class="form-control form-control-sm readonly-first" readonly autocomplete="off">
                                </div>
                                <div class="col-8">
                                    <input type="text" name="claim-status-rg-pono" id="claim-status-rg-pono" class="form-control form-control-sm readonly-first" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1" style="font-size: 11px;">
                        <div class="col-2">
                            <label for="claim-status-rg-date">Date / Doc No</label>
                        </div>
                        <div class="col-10">
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <input type="text" name="claim-status-rg-date" id="claim-status-rg-date" class="form-control form-control-sm this-datepicker" autocomplete="off" required>
                                </div>
                                <div class="col-8">
                                    <input type="text" name="claim-status-rg-docno" id="claim-status-rg-docno" class="form-control form-control-sm" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div id="item-button-div-rg" class="col-12 text-right">
                            <button type="button" id="claim-btn-tobe-rg" class="btn btn-sm btn-info" disabled>
                                Edit Tobe RG
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <div class="datatable datatable-primary">
                                    <table id="claim-datatables-rg" class="table table-bordered" style="width:100%;cursor:pointer">
                                        <thead class="text-center" style="font-size: 15px;">
                                            <tr style="font-size: 14px;">
                                                <th class="align-middle">#</th>
                                                <th class="align-middle">Itemcode</th>
                                                <th class="align-middle">Description</th>
                                                <th class="align-middle">Unit</th>
                                                <th class="align-middle">Qty Claim</th>
                                                <th class="align-middle">Qty RG</th>
                                                <th class="align-middle">Tobe RG</th>
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
                    <button type="button" id="claim-btn-status-rg-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    {{-- <button type="button" id="claim-btn-status-rg-del" class="btn btn-danger" disabled>Del</button> --}}
                    <button type="submit" id="claim-btn-status-rg-submit" class="btn btn-info">Ok</button>
                </div>
            </div>
        </form>
    </div>
</div>