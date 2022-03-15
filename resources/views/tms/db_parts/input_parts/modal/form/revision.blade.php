<div class="modal fade bd-example-modal-lg iparts-modal-revisi" style="z-index: 1041" tabindex="-1" id="iparts-modal-revisi" data-target="#iparts-modal-revisi" data-whatever="@ipartsmodalindex"  role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="iparts-form-revisi" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Form Revision</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="iparts-revisi-id" id="iparts-revisi-id" value="0">
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="iparts-revisi-partno" class="auto-middle">Part</label>
                        </div>
                        <div class="col-10">
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <input type="text" name="iparts-revisi-partno" id="iparts-revisi-partno" class="form-control form-control-sm readonly-first" readonly>
                                </div>
                                <div class="col-8">
                                    <input type="text" name="iparts-revisi-partname" id="iparts-revisi-partname" class="form-control form-control-sm readonly-first" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="iparts-revisi-note" class="auto-middle">Note</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="iparts-revisi-note" id="iparts-revisi-note" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="iparts-revisi-field" class="auto-middle">Changed Field</label>
                        </div>
                        <div class="col-10">
                            <select name="iparts-revisi-field[]" id="iparts-revisi-field" class="form-control form-control-sm select2" multiple="multiple" required style="width: 100%">
                                <option value="">Select field</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="iparts-btn-revisi-close" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="iparts-btn-revisi-submit" class="btn btn-info">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>