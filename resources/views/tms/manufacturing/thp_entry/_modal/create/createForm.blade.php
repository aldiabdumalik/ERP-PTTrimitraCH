<div class="modal fade bd-example-modal-lg modalcreate" style="z-index: 1041" tabindex="-1" id="createModal" data-target="#mtoModalCreate" data-whatever="@createThp"  role="dialog">
    <div class="modal-dialog modal-80">
        <form id="thp-form-create" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">THP Entry</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-12">
                                    <input type="hidden" name="thp-id" id="thp-id">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-5 col-xs-12">
                                    @include('tms.manufacturing.thp_entry._modal.create.productionDetail')
                                </div>
                                <div class="col-xl-7 col-xs-12">
                                    <div class="row">
                                        <div class="col-6">
                                            @include('tms.manufacturing.thp_entry._modal.create.thpDetail')

                                        </div>
                                        <div class="col-6">
                                            @include('tms.manufacturing.thp_entry._modal.create.employeeDetail')
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-xl-3 col-md-12">
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary thp-cancel-create" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary thp-create-btn">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>