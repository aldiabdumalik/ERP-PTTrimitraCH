<div class="modal fade bd-example-modal-lg custprice-modal-post" style="z-index: 1041" tabindex="-1" id="custprice-modal-post" data-target="#custprice-modal-post" data-whatever="@custpricemodalindex"  role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-row align-items-center mb-1">
                    <div class="col-3">
                        <label for="custprice-post-id" class="auto-middle" style="font-size:12px;">Customer</label>
                    </div>
                    <div class="col-9">
                        <input type="text" name="custprice-post-id" id="custprice-post-id" class="form-control form-control-sm readonly-first" readonly value="">
                    </div>
                </div>
                <div class="form-row align-items-center mb-1">
                    <div class="col-3">
                        <label for="custprice-post-activedate" class="auto-middle" style="font-size:12px;">Customer</label>
                    </div>
                    <div class="col-9">
                        <input type="text" name="custprice-post-activedate" id="custprice-post-activedate" class="form-control form-control-sm readonly-first" readonly value="">
                    </div>
                </div>
                <div class="form-row align-items-center mb-2">
                    <div class="col-3">
                        <label for="custprice-post-priceby" class="auto-middle" style="font-size:12px;">Price by</label>
                    </div>
                    <div class="col-9">
                        <input type="text" name="custprice-post-priceby" id="custprice-post-priceby" class="form-control form-control-sm readonly-first" readonly value="SO">
                    </div>
                </div>
                <div class="form-row align-items-center">
                    <div class="col-6">
                        <div class="form-check">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="custprice-post-stock" checked value="stock">
                                <label class="custom-control-label" for="custprice-post-stock">File stock</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="custprice-post-sso" checked value="sso">
                                <label class="custom-control-label" for="custprice-post-sso">File sso</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="custprice-post-so" checked value="so">
                                <label class="custom-control-label" for="custprice-post-so">File so</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="custprice-post-sj" checked value="sj">
                                <label class="custom-control-label" for="custprice-post-sj">File sj</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="custprice-btn-post-close" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="custprice-btn-post-submit" class="btn btn-info">Post</button>
                <button type="button" id="custprice-btn-post-submit-save" class="btn btn-info d-none">Yes, Post it!</button>
            </div>
        </div>
    </div>
</div>