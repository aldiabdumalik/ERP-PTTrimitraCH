<div class="modal fade thp-close-modal" style="z-index: 1041" tabindex="-1" id="thp-close-modal" data-target="#thp-close-modal" data-whatever="@createThp"  role="dialog">
    <div class="modal-dialog" role="document">
        <form id="thp-form-closed" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">CLOSE THP</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2">
                            <div class="">
                                <label style="font-size:12px;" for="thp-close-note" class="col-form-label text-bold">Note</label>
                            </div>
                        </div>
                        <div class="col-10" style="margin-left:-25px;">
                            <div class="">
                                <textarea name="thp-close-note" id="thp-close-note" class="form-control"  cols="30" rows="10" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary thp-cancel-closed" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary thp-close-btn">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>