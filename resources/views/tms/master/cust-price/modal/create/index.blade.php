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
                            @include('tms.master.cust-price.modal.create.header1')
                        </div>
                        {{-- <div class="col-xl-4 border-left">
                            @include('tms.master.cust-price.modal.create.header2')
                        </div> --}}
                        <div class="col-xl-4 border-left border-right">
                            @include('tms.master.cust-price.modal.create.header3')
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="row">
                            <div id="custprice-search-table-item" class="col-4 text-right align-items-center">
                                <input type="text" id="custprice-dtsearch" class="form-control form-control-sm custprice-dtsearch" placeholder="Search item...">
                            </div>
                            <div id="custprice-btn-table-item" class="col-8 text-right">
                                <button type="button" id="custprice-btn-add-item" class="btn btn-sm btn-info">
                                    Add Item
                                </button>
                                <button type="button" id="custprice-btn-delete-item" class="btn btn-sm btn-danger" disabled>
                                    Delete
                                </button>
                            </div>
                            <div class="col-12 mt-2">
                                @include('tms.master.cust-price.modal.create.itemTable')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="custprice-btn-action" class="d-none">
                        {{-- <button type="button" id="custprice-btn-index-edit" class="btn btn-info custprice-act-edit">Edit</button>
                        <button type="button" id="custprice-btn-index-post" class="btn btn-info custprice-act-posted">Post</button> --}}
                        <button type="button" id="custprice-btn-index-log" class="btn btn-info custprice-act-log">Log</button>
                        <button type="button" id="custprice-btn-index-print" class="btn btn-info custprice-act-print">Print</button>
                    </div>
                    <button type="button" id="custprice-btn-index-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="custprice-btn-index-submit" class="btn btn-info">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>