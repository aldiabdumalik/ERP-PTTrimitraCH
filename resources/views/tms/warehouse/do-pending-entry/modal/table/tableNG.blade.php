<div class="modal fade bd-example-modal-lg do-modal-ng" style="z-index: 1041" tabindex="-1" id="do-modal-ng" data-target="#do-modal-ng" data-whatever="@domodalng"  role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="do-form-ng" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">DO Entry QTY NG</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row" style="font-size: 12px;">
                        <div class="col-2">
                            <label for="do-ng-no">DO No.</label>
                        </div>
                        <div class="col-10">
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <input type="text" name="do-ng-no" id="do-ng-no" class="form-control form-control-sm readonly-first" autocomplete="off" readonly required>
                                </div>
                                <div class="col-8">
                                    <input type="text" name="do-ng-refno" id="do-ng-refno" class="form-control form-control-sm readonly-first" autocomplete="off" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <div class="datatable datatable-primary">
                                    <table id="do-ng-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer">
                                        <thead class="text-center" style="font-size: 15px;">
                                            <tr>
                                                <th class="align-middle">#</th>
                                                <th class="align-middle">Part No.</th>
                                                <th class="align-middle">Itemcode</th>
                                                <th class="align-middle">Descript</th>
                                                <th class="align-middle">Unit</th>
                                                <th class="align-middle">SSO No.</th>
                                                <th class="align-middle">Qty SJ</th>
                                                <th class="align-middle">Qty NG</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="do-btn-ng-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="do-btn-ng-submit" class="btn btn-info">Add NG</button>
                </div>
            </div>
        </form>
    </div>
</div>