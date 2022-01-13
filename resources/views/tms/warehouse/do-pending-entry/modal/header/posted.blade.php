<div class="modal fade do-modal-posted" style="z-index: 1041" tabindex="-1" id="do-modal-posted" data-target="#do-modal-posted" data-whatever="@domodalposted"  role="dialog">
    <div class="modal-dialog">
        <form id="do-form-posted" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Posted DO Temp</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="do-posted-id" class="auto-middle" style="font-size:12px;">Do No.</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="do-posted-id" id="do-posted-id" class="form-control form-control-sm readonly-first" readonly>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="do-posted-rrno" class="auto-middle" style="font-size:12px;">RR No.</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="do-posted-rrno" id="do-posted-rrno" class="form-control form-control-sm" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="do-posted-rrdate" class="auto-middle" style="font-size:12px;">RR Date</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="do-posted-rrdate" id="do-posted-rrdate" class="form-control form-control-sm this-datepicker" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="do-posted-st" class="auto-middle" style="font-size:12px;">Scurity stamp.</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="do-posted-st" id="do-posted-st" class="form-control form-control-sm this-datepicker" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="do-posted-note" class="auto-middle" style="font-size:12px;">Note</label>
                        </div>
                        <div class="col-10">
                            <textarea name="do-posted-note" id="do-posted-note" class="form-control" cols="30" rows="10" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="do-btn-posted-submit" class="btn btn-info">Posted</button>
                    <button type="button" id="do-btn-posted-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>