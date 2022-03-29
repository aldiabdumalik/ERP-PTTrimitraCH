<div class="modal fade bd-example-modal-lg projects_post-modal-form" style="z-index: 1041" tabindex="-1" id="projects_post-modal-form" data-target="#projects_post-modal-form" data-whatever="@projects_postmodalindex"  role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="projects_post-form" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">POSTED</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="projects_post-id" id="projects_post-id" value="0">
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="projects_post-vlogs" class="auto-middle">Logs</label>
                        </div>
                        <div class="col-10">
                            <a href="javascript:void(0)" id="projects_post-vlogs">View Last Log Revision</a>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="projects_post-type" class="auto-middle">Type</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="projects_post-type" id="projects_post-type" class="form-control form-control-sm readonly-first" readonly required autocomplete="off">
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="projects_post-reff" class="auto-middle">Reff</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="projects_post-reff" id="projects_post-reff" class="form-control form-control-sm readonly-first" readonly required autocomplete="off">
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="projects_post-note" class="auto-middle">Note</label>
                        </div>
                        <div class="col-10">
                            <textarea name="projects_post-note" id="projects_post-note" class="form-control form-control-sm" cols="30" rows="10" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="projects_post-btn-close" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="projects_post-btn-save" class="btn btn-info">POST</button>
                </div>
            </div>
        </form>
    </div>
</div>