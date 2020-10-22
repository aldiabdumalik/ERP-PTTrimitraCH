<div class="modal fade" id="modal-transfer-order" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-80">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transfer Order (<span id="modal-role-name">New</span>)</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- header -->
                <div class="row">
                    <div class="col">
                        <form method="post" id="transfer-order-form">
                            
                            <span id="form-output"></span>

                            {{ csrf_field() }}
                            <input class="form-control" type="hidden" name="id" id="role-id">
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label class="col-form-label text-bold">TO No.</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input class="form-control form-control-sm" type="text" name="to-no" id="to-no" required>
                                            </div>  
                                        </div>
                                    </div>
                                </div>

                                
                                
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group align-right">
                                                <label class="col-form-label text-bold">Staff</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input class="form-control form-control-sm" type="text" id="staff" disabled>
                                            </div>  
                                        </div>
                                    </div> 
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label class="col-form-label text-bold">Ref No.</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input class="form-control form-control-sm" type="text" name="ref-no" id="ref-no" required>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label class="col-form-label text-bold">Period/Date</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <input class="form-control form-control-sm" type="text" name="period" id="period" required>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <input class="form-control form-control-sm" type="date" name="date" id="date" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label class="col-form-label text-bold">From Wh Id</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="row">
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <input class="form-control form-control-sm" type="text" name="from-wh-branch" id="from-wh-branch" required>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <input class="form-control form-control-sm" type="text" name="from-wh-code" id="from-wh-code" required>
                                                    </div>
                                                </div>
                                                <div class="col-8">
                                                    <div class="form-group">
                                                        <input class="form-control form-control-sm" type="text" name="from-wh-name" id="from-wh-name" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label class="col-form-label text-bold">To Wh Id</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="row">
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <input class="form-control form-control-sm" type="text" name="to-wh-branch" id="to-wh-branch" required>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <input class="form-control form-control-sm" type="text" name="to-wh-code" id="to-wh-code" required>
                                                    </div>
                                                </div>
                                                <div class="col-8">
                                                    <div class="form-group">
                                                        <input class="form-control form-control-sm" type="text" name="to-wh-name" id="to-wh-name" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label class="col-form-label text-bold">Remark</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <input class="form-control form-control-sm" type="text" name="remark" id="remark" required>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="form-group align-right">
                                                <label class="col-form-label text-bold">Printed</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <input class="form-control form-control-sm" type="text" id="printed" disabled>
                                            </div>  
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-8">
                                            <div class="form-group align-right">
                                                <label class="col-form-label text-bold">Voided</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <input class="form-control form-control-sm" type="text" id="voided" disabled>
                                            </div>  
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-8">
                                            <div class="form-group align-right">
                                                <label class="col-form-label text-bold">Posted</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <input class="form-control form-control-sm" type="text" id="posted" disabled>
                                            </div>  
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-8">
                                            <div class="form-group align-right">
                                                <label class="col-form-label text-bold">Finished</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <input class="form-control form-control-sm" type="text" id="finished" disabled>
                                            </div>  
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-8">
                                            <div class="form-group align-right">
                                                <label class="col-form-label text-bold">No of Item</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <input class="form-control form-control-sm" type="text" id="total" disabled>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- end of header -->
                
                <hr />

                <!-- detail -->
                <div class="row">
                    <div class="col-12">
                        <div class="data-tables datatable-dark">
                            <table id="transfer-order-detail-datatable" class="table table-striped" style="width:100%">
                                {{ csrf_field() }}
                                <thead class="text-center">
                                    <tr>
                                        <th>Itemcode</th>
                                        <th>Part No</th>
                                        <th>Description</th>
                                        <th>F. Unit</th>
                                        <th>F. Qty</th>
                                        <th>Factor</th>
                                        <th>Unit</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- end of detail -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="transfer-order-form" class="btn btn-success" id="transfer-order-submit"><i class="ti-check"></i> Save</button>
            </div>
        </div>
    </div>
</div>
