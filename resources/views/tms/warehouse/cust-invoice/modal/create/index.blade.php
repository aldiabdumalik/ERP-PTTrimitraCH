<div class="modal fade bd-example-modal-lg custinv-modal-index" style="z-index: 1041" tabindex="-1" id="custinv-modal-index" data-target="#custinv-modal-index" data-backdrop="static" data-keyboard="false" data-whatever="@custinvmodalindex"  role="dialog">
    <div class="modal-dialog modal-xl">
        <form id="custinv-form-index" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Customer Invoice</h4>
                </div>
                <div class="modal-body">
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-4">
                                <a href="#carouselExampleSlidesOnly" role="button" data-slide="prev">
                                    <button type="button" id="custinv-btn-part-view" class="btn btn-sm btn-default" data-slide="prev"><i class="fa fa-angle-left"></i></button>
                                </a>
                                <a href="#carouselExampleSlidesOnly" role="button" data-slide="next">
                                    <button type="button" id="custinv-btn-do-view" class="btn btn-sm btn-default" data-slide="next"><i class="fa fa-angle-right"></i></button>
                                </a>
                            </div>
                            <div class="col-4 text-center" style="margin-top:auto;margin-left:auto">
                                <h5 id="custinv-text-view-by" class="align-middle">VIEW BY DO NO.</h5>
                            </div>
                            <div class="col-4 text-right">
                                <button type="button" id="custinv-btn-add-item" class="btn btn-sm btn-info">
                                    Add DO Entry
                                </button>
                                <button type="button" id="custinv-btn-delete-item" class="btn btn-sm btn-info" disabled>
                                    Delete
                                </button>
                            </div>
                            <div class="col-12 mt-2">
                                <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel" data-interval="0">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            @include('tms.warehouse.cust-invoice.modal.create.itemTable')
                                        </div>
                                        <div class="carousel-item">
                                            <h1>Test</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="custinv-btn-index-close" class="btn btn-secondary">Batal</button>
                    <button type="submit" id="custinv-btn-index-submit" class="btn btn-info">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>