<div class="modal fade bd-example-modal-lg do-modal-itemtable" style="z-index: 1042" tabindex="-1" id="do-modal-itemtable" data-target="#do-modal-itemtable" data-whatever="@domodalitemtable"  role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Items <span style="font-size: 12px;">(Multiple select)</span></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <div class="datatable datatable-primary">
                        <table id="do-datatables-items" class="table table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="" style="font-size: 15px;">
                                <tr style="font-size: 14px;">
                                    <th class="align-middle">Itemcode</th>
                                    <th class="align-middle">Part No</th>
                                    <th class="align-middle">Descript</th>
                                    <th class="align-middle">Model</th>
                                    <th class="align-middle">Unit</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" id="do-btn-itemtable-selectall" class="btn btn-info">Select All Item</button> --}}
                <button type="button" id="do-btn-itemtable-submit" class="btn btn-info">Add Item Selected</button>
                <button type="button" id="do-btn-itemtable-close" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>