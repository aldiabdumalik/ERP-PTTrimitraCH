<div class="modal fade bd-example-modal-lg custprice-modal-index" style="z-index: 1041" tabindex="-1" id="custprice-modal-index" data-target="#custprice-modal-index" data-whatever="@custpricemodalindex"  role="dialog">
    <div class="modal-dialog modal-80">
        <form id="custprice-form-index" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Customer Price</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-row" style="font-size: 11px;">
                        <div class="col-xl-8 border-left">
                            @include('tms.warehouse.cust-price.modal.create.header1')
                        </div>
                        {{-- <div class="col-xl-4 border-left">
                            @include('tms.warehouse.cust-price.modal.create.header2')
                        </div> --}}
                        <div class="col-xl-4 border-left border-right">
                            @include('tms.warehouse.cust-price.modal.create.header3')
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="button" id="custprice-btn-add-item" class="btn btn-sm btn-info">
                                    Add Item
                                </button>
                                <button type="button" id="custprice-btn-delete-item" class="btn btn-sm btn-info" disabled>
                                    Delete
                                </button>
                            </div>
                            <div class="col-12 mt-2">
                                @include('tms.warehouse.cust-price.modal.create.itemTable')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="custprice-btn-index-close" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="custprice-btn-index-submit" class="btn btn-info">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>