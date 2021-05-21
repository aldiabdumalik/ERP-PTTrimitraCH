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
                                <div class="col-xl-6 col-md-12">
                                    <div id="thp-id" data-id="0"></div>
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-production-code" class="col-form-label text-bold">Production Code</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="">
                                                <div class="input-group">
                                                    <input type="text" name="thp-production-code" id="thp-production-code" class="form-control" autocomplete="off" placeholder="Search" required>
                                                    <span class="input-group-btn">
                                                        <button type="button" id="thp-btn-production-code" class="btn btn-info" data-toggle="modal" data-target="#productionModal"><i class="fa fa-search"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-part-number" class="col-form-label text-bold">Part number</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="">
                                                <input type="text" name="thp-part-number" id="thp-part-number" class="form-control" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-part-name" class="col-form-label text-bold">Part name</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="">
                                                <input type="text" name="thp-part-name" id="thp-part-name" class="form-control" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-part-type" class="col-form-label text-bold">Part type</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="">
                                                <input type="text" name="thp-part-type" id="thp-part-type" class="form-control" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-customer-code" class="col-form-label text-bold">Customer Code</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="">
                                                <input type="text" name="thp-customer-code" id="thp-customer-code" class="form-control" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-12">
                                    <div class="row">
                                        <div class="col-1">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-plan" class="col-form-label text-bold">Plan</label>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="">
                                                <input type="number" name="thp-plan" id="thp-plan" class="form-control" value="0" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-1">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-ct" class="col-form-label text-bold">C/T</label>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="">
                                                <input type="number" name="thp-ct" id="thp-ct" class="form-control" min="0" step="0.01" value="0.00" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-1">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-route" class="col-form-label text-bold">Route</label>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="">
                                                <input type="text" name="thp-route" id="thp-route" class="form-control" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-1">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-ton" class="col-form-label text-bold">TON</label>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="">
                                                <input type="text" name="thp-ton" id="thp-ton" class="form-control" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-1">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-process" class="col-form-label text-bold">Process</label>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="">
                                                <input type="number" name="thp-process" id="thp-process" class="form-control" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-1">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-time" class="col-form-label text-bold">Time</label>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="">
                                                <input type="number" name="thp-time" id="thp-time" class="form-control" min="0" step="0.01" value="0.00" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-1">
                                            <div class="">
                                                <label style="font-size:12px;" for="thp-plan-hour" class="col-form-label text-bold">Plan hour</label>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="">
                                                <input type="number" name="thp-plan-hour" id="thp-plan-hour" class="form-control" min="0" step="0.01" value="0.00" autocomplete="off" placeholder="" required>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            @include('tms.manufacturing.thp_entry._modal.create_thp_modal._createthpTable')
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary thp-cancel-create" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary thp-create-btn">Simpan</button>
                    <button type="button" id="thp-edit-btn" class="btn btn-primary thp-edit-btn" hidden><span>Edit</span></button>
                </div>
            </div>
        </form>
    </div>
</div>