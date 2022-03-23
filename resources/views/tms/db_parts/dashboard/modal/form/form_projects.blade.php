<div class="modal fade bd-example-modal-lg projects-modal-form" style="z-index: 1041" tabindex="-1" id="projects-modal-form" data-target="#projects-modal-form" data-whatever="@projectsmodalindex"  role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="projects-form" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Form Project</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="projects-id" id="projects-id" value="0">
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="projects-customer" class="auto-middle">Customer</label>
                        </div>
                        <div class="col-10">
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <input type="text" name="projects-customer" id="projects-customer" class="form-control form-control-sm" required autocomplete="off" style="text-transform: uppercase">
                                </div>
                                <div class="col-8">
                                    <input type="text" name="projects-customername" id="projects-customername" class="form-control form-control-sm readonly-first" readonly autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="projects-type" class="auto-middle">Type</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="projects-type" id="projects-type" class="form-control form-control-sm" required autocomplete="off">
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-1">
                        <div class="col-2">
                            <label for="projects-reff" class="auto-middle">Reff</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="projects-reff" id="projects-reff" class="form-control form-control-sm" required autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="projects-btn-close" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="projects-btn-save" class="btn btn-info">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>