<div class="modal fade setPersentase" style="z-index: 1041" tabindex="-1" id="setPersentase" data-target="#setPersentase" data-whatever="@createThp"  role="dialog">
    <div class="modal-dialog">
        <form id="setting-persentase-form" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Setting Minimal Persentase</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="setting-persentase-id" id="setting-persentase-id" class="form-control" autocomplete="off" required>
                    <div class="row">
                        <div class="col-2">
                            <label for="setting-persentase-name">Nama setting</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="setting-persentase-name" id="setting-persentase-name" class="form-control" autocomplete="off" required>
                        </div>
                        <div class="col-2">
                            <label for="setting-persentase-value">Value setting</label>
                        </div>
                        <div class="col-10">
                            <div class="input-group">
                                <input type="number" name="setting-persentase-value" id="setting-persentase-value" class="form-control" autocomplete="off" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>