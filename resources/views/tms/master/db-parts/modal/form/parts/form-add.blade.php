<div class="modal fade dbpart-modal-fparts" style="z-index: 1041" tabindex="-1" id="dbpart-modal-fparts" data-target="#dbpart-modal-fparts" data-whatever="@domodalfparts"  role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="dbpart-form-fparts" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Input part</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="dbpart-fparts-no" class="auto-middle">Part No</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="dbpart-fparts-partno" id="dbpart-fparts-partno" class="form-control form-control-sm" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="dbpart-fparts-name" class="auto-middle">Part name</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="dbpart-fparts-partname" id="dbpart-fparts-partname" class="form-control form-control-sm" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="dbpart-fparts-pict" class="auto-middle">Part picture</label>
                        </div>
                        <div class="col-10">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input form-control-sm" name="dbpart-fparts-pict" id="dbpart-fparts-pict" accept="image/*" required>
                                <label class="custom-file-label" id="dbpart-fparts-pict-x" for="dbpart-fparts-pict">Choose file</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="dbpart-fparts-vol" class="auto-middle">Vol/Mth</label>
                        </div>
                        <div class="col-10">
                            <div class="input-group">
                                <input type="text" name="dbpart-fparts-vol" id="dbpart-fparts-vol" class="form-control form-control-sm" autocomplete="off" required>
                                <div class="input-group-prepend">
                                    <span class="input-group-text form-control-sm" id="dbpart-fparts-vol-x">PCS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="dbpart-btn-fparts-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="dbpart-btn-fparts-submit" class="btn btn-info">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>