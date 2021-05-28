<div class="modal fade bd-example-modal-lg modalDetail" style="z-index: 1041" tabindex="-1" id="modalDetail" data-target="#modalDetail" data-whatever="@modalDetail"  role="dialog">
    <div class="modal-dialog modal-80">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">THP Entry Detail</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-5 col-xs-12">
                        @include('tms.manufacturing.thp_entry._modal.detail.viewProductionDetail')
                    </div>
                    <div class="col-xl-7 col-xs-12">
                        <div class="row">
                            <div class="col-6">
                                @include('tms.manufacturing.thp_entry._modal.detail.viewThpDetail')
                            </div>
                            <div class="col-6">
                                @include('tms.manufacturing.thp_entry._modal.detail.viewEmployeeDetail')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary thp-cancel-detail" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary thp-detail-reload"><i class="fa fa-refresh"></i> Reload</button> --}}
            </div>
        </div>
    </div>
</div>