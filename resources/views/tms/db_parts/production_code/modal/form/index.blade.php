<div class="modal fade bd-example-modal-lg prodcode-modal-index" style="z-index: 1041" tabindex="-1" id="prodcode-modal-index" data-target="#prodcode-modal-index" data-whatever="@prodcodemodalindex"  role="dialog">
    <div class="modal-dialog modal-xl">
        <form id="prodcode-form-index" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Input Part Item</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row" style="font-size: 11px;">
                        <div id="prodcode-index-id" data-val="0"></div>
                        <div class="col-xl-8 border-left">
                            @include('tms.db_parts.production_code.modal.form.header1')
                        </div>
                        {{-- <div class="col-xl-5 border-left border-right">
                            @include('tms.db_parts.production_code.modal.form.header2')
                        </div> --}}
                        <div class="col-xl-4 border-left">
                            @include('tms.db_parts.production_code.modal.form.header3')
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="prodcode-btn-index-close" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="prodcode-btn-index-submit" class="btn btn-info">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>