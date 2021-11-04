<div class="modal fade bd-example-modal-lg custinv-modal-report" style="z-index: 1041" tabindex="-1" id="custinv-modal-report" data-target="#custinv-modal-report" data-whatever="@domodallog"  role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Customer Invoice Print</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-row align-items-center">
                            <div class="col-2">
                                <label for="custinv-report-type" class="auto-middle">Invoice form</label>
                            </div>
                            <div class="col-10">
                                <select name="custinv-report-type" id="custinv-report-type" class="form-control form-control-sm" required>
                                    <option value="INV">Invoice - Commercial Invoice Form</option>
                                    <option value="OR">OR - Official Receipt (Kwitansi)</option>
                                    <option value="VAT">VAT - Geverment VAT Form</option>
                                    <option value="CN">CN - CREADIT NOTE</option>
                                    <option value="RR">RR Attachment</option>
                                    <option value="SJ">SJ - List of SJ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-row align-items-center">
                            <div class="col-2">
                                <label for="custinv-report-pic" class="auto-middle">PIC</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="custinv-report-pic" id="custinv-report-pic" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required value="{{auth()->user()->FullName}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-row align-items-center">
                            <div class="col-2">
                                <label for="custinv-report-vat" class="auto-middle">VAT Prefix</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="custinv-report-vat" id="custinv-report-vat" class="form-control form-control-sm" autocomplete="off" required value="010.001.21.2347">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-row align-items-center">
                            <div class="col-2">
                                <label for="custinv-report-noitem" class="auto-middle">No. of item</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="custinv-report-noitem" id="custinv-report-noitem" class="form-control form-control-sm text-right readonly-first" readonly autocomplete="off" required value="22">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-row align-items-center">
                            <div class="col-2">
                                <label for="custinv-report-cut" class="auto-middle">Cut off line</label>
                            </div>
                            <div class="col-10">
                                <input type="text" name="custinv-report-cut" id="custinv-report-cut" class="form-control form-control-sm text-right" autocomplete="off" required value="22">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="custinv-btn-report-close" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="custinv-btn-report-ok" class="btn btn-info">Print</button>
            </div>
        </div>
    </div>
</div>