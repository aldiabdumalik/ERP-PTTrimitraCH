<div class="modal fade do-modal-print" style="z-index: 1041" tabindex="-1" id="do-modal-print" data-target="#do-modal-print" data-whatever="@domodallog"  role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">DO Entry Print</h4>
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
                                        <label for="do-print-dari">Dari</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" name="do-print-dari" id="do-print-dari" class="form-control form-control-sm" autocomplete="off" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-row align-items-center mb-1">
                                    <div class="col-4">
                                        <label for="do-print-sampai">Sampai</label>
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
                                <label for="do-print-type">Kertas</label>
                            </div>
                            <div class="col-10">
                                <select name="do-print-type" id="do-print-type" class="form-control">
                                    <option value="blank">Blank</option>
                                    <option value="grid">Grid</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="do-btn-print-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" id="do-btn-print-gas" class="btn btn-primary">Print</button>
            </div>
        </div>
    </div>
</div>