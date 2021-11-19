<div class="modal fade bd-example-modal-lg thp-modal-apnormal" style="z-index: 1041" tabindex="-1" id="thp-modal-apnormal" data-target="#thp-modal-apnormal" data-whatever="@thpmodalindex"  role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="thp-form-apnormal" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">THP Entry - Apnormality</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-row align-items-center">
                                <div class="col-2">
                                    <label for="thp-note-no" class="auto-middle">THP No.</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="thp-note-no" id="thp-note-no" class="form-control form-control-sm" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-row align-items-center">
                                <div class="col-2">
                                    <label for="thp-note-po" class="auto-middle">Apnormality</label>
                                </div>
                                <div class="col-10">
                                    <select name="thp-note-apnormality" id="thp-note-apnormality" class="form-control form-control-sm" required>
                                        <option value="">Select apnormality</option>
                                        <option value="DIES REPAIR">Dies Repair</option>
                                        <option value="MATERIAL KOSONG">Material Kosong</option>
                                        <option value="BELUM SELESAI">Belum Selesai</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-row align-items-center">
                                <div class="col-2">
                                    <label for="thp-note-note" class="auto-middle">Note</label>
                                </div>
                                <div class="col-10">
                                    <textarea name="thp-note-note" id="thp-note-note" class="form-control form-control-sm" required cols="30" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="thp-btn-apnormal-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="thp-btn-apnormal-submit" class="btn btn-info">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>