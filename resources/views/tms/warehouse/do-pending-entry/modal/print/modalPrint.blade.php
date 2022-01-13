<div class="modal fade do-modal-print" style="z-index: 1041" tabindex="-1" id="do-modal-print" data-target="#do-modal-print" data-whatever="@domodallog"  role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <form id="do-form-print" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">DO Temporary Print</h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="font-size: 13px;">
                        <div class="col-12">
                            <div class="form-row align-items-center mb-1">
                                <div class="col-12">
                                    <label for="do-print-do">DO No. </label>
                                    <span class="text-danger"><small>(write manually or press enter on the input field)</small></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-row align-items-center mb-1">
                                <div class="col-6">
                                    <div class="form-row align-items-center mb-1">
                                        <div class="col-4">
                                            <label for="do-print-dari">Start</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="text" name="do-print-dari" id="do-print-dari" class="form-control form-control-sm" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-row align-items-center mb-1">
                                        <div class="col-4">
                                            <label for="do-print-sampai">To</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="text" name="do-print-sampai" id="do-print-sampai" class="form-control form-control-sm" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-row align-items-center mb-1">
                                <div class="col-2">
                                    <label for="do-print-type">Paper</label>
                                </div>
                                <div class="col-10">
                                    <select name="do-print-type" id="do-print-type" class="form-control" required>
                                        <option value="blank">Blank</option>
                                        <option value="template">Template</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="do-btn-print-close" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="do-btn-print-gas" class="btn btn-primary">Print</button>
                </div>
            </div>
        </form>
    </div>
</div>