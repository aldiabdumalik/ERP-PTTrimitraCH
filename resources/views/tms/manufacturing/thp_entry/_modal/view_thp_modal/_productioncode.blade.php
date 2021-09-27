<div class="modal fade bd-example-modal-lg poduction-code-modal" style="z-index: 1041" tabindex="-1" id="poduction-code-modal" data-target="#poduction-code-modal" data-whatever="@createThp"  role="dialog">
    <div class="modal-dialog modal-80">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Production Code</h5>
                <select name="pc-search-process" id="pc-search-process" class="form-control form-control-sm" style="width: 30%">
                    <option value="" disabled>Pilih berdasarkan proses</option>
                    <option value="ASSEMBLING">ASSEMBLING</option>
                    <option value="PRESSING" selected>PRESSING</option>
                    <option value="WELDING">WELDING</option>
                    <option value="SPOT">SPOT</option>
                </select>
            </div>
            <div class="modal-body">
                <div class="datatable datatable-primary">
                    <div class="table-responsive">
                        <table id="thp-poduction-code-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer!important;">
                            <thead class="text-center" style="font-size: 15px;">
                                <tr>
                                    <th class="align-middle">Cust.</th>
                                    <th class="align-middle">Dept.</th>
                                    <th class="align-middle">Production code</th>
                                    <th class="align-middle">Part No.</th>
                                    <th class="align-middle">Part name</th>
                                    <th class="align-middle">Type</th>
                                    <th class="align-middle">Itemcode</th>
                                    <th class="align-middle">Process</th>
                                    <th class="align-middle">Process detail</th>
                                    <th class="align-middle">C/T</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>