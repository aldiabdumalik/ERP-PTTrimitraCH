<div class="modal fade bd-example-modal-lg poduction-code-modal" style="z-index: 1041" tabindex="-1" id="poduction-code-modal" data-target="#poduction-code-modal" data-whatever="@createThp"  role="dialog">
    <div class="modal-dialog modal-80">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pilih Production Code</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <select name="pc-search-process" id="pc-search-process" class="form-control">
                                <option value="">Pilih berdasarkan proses</option>
                                <option value="ASSEMBLING">ASSEMBLING</option>
                                <option value="PRESSING" selected>PRESSING</option>
                                <option value="WELDING">WELDING</option>
                                <option value="SPOT">SPOT</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="datatable datatable-primary">
                    <div class="table-responsive">
                        <table id="thp-poduction-code-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer!important;">
                            <thead class="text-center" style="font-size: 15px;">
                                <tr>
                                    <th>customer</th>
                                    <th>dept.</th>
                                    <th>production_code</th>
                                    <th>part_number</th>
                                    <th>part_name</th>
                                    <th>part_type</th>
                                    <th>item_code</th>
                                    <th>process</th>
                                    <th>process detail</th>
                                    <th>C/T</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>