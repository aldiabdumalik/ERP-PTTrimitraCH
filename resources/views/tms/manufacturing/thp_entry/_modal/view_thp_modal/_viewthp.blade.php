<div class="modal fade bd-example-modal-lg viewThpid" style="z-index: 1041" tabindex="-1" id="viewThpid" data-target="#viewThpid" data-whatever="@createThp"  role="dialog">
    <div class="modal-dialog modal-80">
        <form id="thp-view-form-create" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">THP Entry</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-xl-6 col-md-12">
                                    {{-- <div id="thp-view-id" data-id="0"></div> --}}
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-view-production-code" class="col-form-label text-bold">Production Code</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="">
                                                <div class="">
                                                    <input type="text" name="thp-view-production-code" id="thp-view-production-code" class="form-control" autocomplete="off" placeholder="Search" required onkeydown="return false">
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-view-part-number" class="col-form-label text-bold">Part number</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="">
                                                <input type="text" name="thp-view-part-number" id="thp-view-part-number" class="form-control" autocomplete="off" placeholder="" required onkeydown="return false">
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-view-part-name" class="col-form-label text-bold">Part name</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="">
                                                <input type="text" name="thp-view-part-name" id="thp-view-part-name" class="form-control" autocomplete="off" placeholder="" required onkeydown="return false">
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-view-part-type" class="col-form-label text-bold">Part type</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="">
                                                <input type="text" name="thp-view-part-type" id="thp-view-part-type" class="form-control" autocomplete="off" placeholder="" required onkeydown="return false">
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-view-customer-code" class="col-form-label text-bold">Customer Code</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="">
                                                <input type="text" name="thp-view-customer-code" id="thp-view-customer-code" class="form-control" autocomplete="off" placeholder="" required onkeydown="return false">
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-12">
                                    <div class="row">
                                        <div class="col-1">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-view-plan" class="col-form-label text-bold">Plan</label>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="">
                                                <input type="number" name="thp-view-plan" id="thp-view-plan" class="form-control" value="0" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-1">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-view-ct" class="col-form-label text-bold">C/T</label>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="">
                                                <input type="number" name="thp-view-ct" id="thp-view-ct" class="form-control" min="0" step="0.01" value="0.00" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-1">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-view-ton" class="col-form-label text-bold">TON</label>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="">
                                                <input type="text" name="thp-view-ton" id="thp-view-ton" class="form-control" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-1">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-view-time" class="col-form-label text-bold">Time</label>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="">
                                                <input type="number" name="thp-view-time" id="thp-view-time" class="form-control" min="0" step="0.01" value="0.00" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-1">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-view-plan-hour" class="col-form-label text-bold">Plan hour</label>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="">
                                                <input type="number" name="thp-view-plan-hour" id="thp-view-plan-hour" class="form-control" min="0" step="0.01" value="0.00" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="table-responsive" style="overflow-x:auto">
                                <table id="thp-view-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer">
                                    <thead class="text-center" style="font-size: 15px;">
                                        <tr>
                                            <th rowspan="2" class="align-middle">Route</th>
                                            <th rowspan="2" class="align-middle">Process</th>
                                            <th colspan="2">Plan THP</th>
                                            <th colspan="3">Actual</th>
                                            <th rowspan="2" class="align-middle">Act Hour</th>
                                            <th rowspan="2" class="align-middle">Note</th>
                                            <th rowspan="2" class="align-middle">Apnormality</th>
                                            <th rowspan="2" class="align-middle">Action Plan</th>
                                        </tr>
                                        <tr>
                                            <th>Shift 1</th>
                                            <th>Shift 2</th>
                                            <th>Shift 1</th>
                                            <th>Shift 2</th>
                                            <th>%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="align-middle">
                                                <span id="thp-view-route"></span>
                                            </td>
                                            <td class="align-middle">
                                                <span id="thp-view-process-squance"></span>
                                            </td>
                                            <td class="align-middle">
                                                <span id="thp-view-plan-1"></span>
                                            </td>
                                            <td class="align-middle">
                                                <span id="thp-view-plan-2"></span>
                                            </td>
                                            <td class="align-middle">
                                                <span id="thp-view-actual-1"></span>
                                            </td>
                                            <td class="align-middle">
                                                <span id="thp-view-actual-2"></span>
                                            </td>
                                            <td class="align-middle">
                                                <span id="thp-view-persentase"></span>
                                            </td>
                                            <td class="align-middle">
                                                <span id="thp-view-act-hour"></span>
                                            </td>
                                            <td class="align-middle">
                                                <span id="thp-view-note"></span>
                                            </td>
                                            <td class="align-middle">
                                                <span id="thp-view-apnormal"></span>
                                            </td>
                                            <td class="align-middle">
                                                <span id="thp-view-action-plan"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary thp-view-cancel-create" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>