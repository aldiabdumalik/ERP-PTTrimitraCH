<div class="modal fade bd-example-modal-lg thp-modal-index" style="z-index: 1041" tabindex="-1" id="thp-modal-index" data-target="#thp-modal-index" data-whatever="@thpmodalindex"  role="dialog">
    <div class="modal-dialog modal-80">
        <form id="thp-form-index" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">THP Entry</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-row" style="font-size: 11px;">
                        <div class="col-xl-4 border-left">
                            @include('tms.manufacturing.thp_entry._modal.form.form1')
                        </div>
                        <div class="col-xl-4 border-left">
                            @include('tms.manufacturing.thp_entry._modal.form.form2')
                        </div>
                        <div class="col-xl-4 border-left border-right">
                            @include('tms.manufacturing.thp_entry._modal.form.form3')
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="thp-btn-index-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="thp-btn-index-submit" class="btn btn-info">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>