<div class="row">
    <div class="col-12 mt-5">
        <div class="#">
            <button type="button"  class="btn btn-outline-primary btn-flat btn-sm" id="custprice-btn-modal-search">
                <i class="fa fa-search"></i> Order By
            </button>
            <button type="button"  class="btn btn-primary btn-flat btn-sm" id="custprice-btn-modal-create">
                <i class="ti-plus"></i>  Add New Data
            </button>
            {{-- <button type="button"  class="btn btn-primary btn-flat btn-sm" id="custprice-btn-modal-print">
                <i class="fa fa-print"></i>  Print
            </button> --}}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <h4 class="card-header-title">Customer Price</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row mt-3">
                    <div class="col">
                        <div class="">
                            <div class="table-responsive">
                                <table id="custprice-datatables" class="display compact table table-hover" style="width:100%;cursor:pointer">
                                    <thead>
                                        <tr>
                                            <th class="align-middle">Group</th>
                                            <th class="align-middle">Part No</th>
                                            <th class="align-middle">Itemcode</th>
                                            <th class="align-middle">Descript.</th>
                                            <th class="align-middle text-right">New Price</th>
                                            <th class="align-middle text-right">Old Price</th>
                                            {{-- <th class="align-middle">Action</th> --}}
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>