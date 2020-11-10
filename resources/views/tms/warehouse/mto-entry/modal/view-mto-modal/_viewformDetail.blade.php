<div class="col">
        <span id="form-output"></span>
        {{-- <input class="form-control" type="hidden" name="id" id="role-id"> --}}
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <label class="col-form-label text-bold">MTO No.</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <input type="number" disabled   name="mto_no" class="form-control form-control-sm" id="mto_no_view" aria-describedby="" placeholder="">
                        </div>  
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" name="branch" disabled class="form-control form-control-sm " id="branch_view" aria-describedby="" placeholder="">
                        </div>  
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" disabled class="form-control form-control-sm" name="warehouse"  disabled id="warehouse_view" aria-describedby="" placeholder="">
                        </div>  
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-sm" name="types"  disabled id="types_view"  >
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
                            <input class="form-control disabled form-control-sm" name="staff" type="text" id="staff_view" disabled>
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
                            <input type="text" name="ref_no" disabled   class="form-control form-control-sm" id="ref_no_view" aria-describedby="" placeholder="">
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
                                    <input class="form-control form-control-sm" disabled  type="text" name="period" id="period_view" required >
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <input ype="date" class="form-control form-control-sm" disabled t name="written" id="written_view" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <label class="col-form-label text-bold">Item/Part No</label>
                        </div>
                    </div>
                    <div class="col-10">
                        <div class="row">
                            <div class="col-5">
                                <div class="form-group">
                                    <div class="input-group">
                                    <input class="form-control form-control-sm" name="fin_code" type="text" name="from-wh-branch"  required
                                     onkeydown="keyPressed(event)" disabled id="item_code_view" aria-describedby="" placeholder="Cari Itemcode">
                                     <span class="input-group-btn">
                                        <button type="button" id="btnPopUp" disabled class="btn btn-info btn-xs" data-toggle="modal" data-target="#mtoModal"><i class="fa fa-search"></i></button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <input type="text" disabled name="frm_code" class="form-control form-control-sm" id="part_no_view" aria-describedby="" placeholder="">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <input type="text" disabled name="descript" class="form-control form-control-sm" id="descript_view" aria-describedby="" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <label class="col-form-label text-bold">Qty IN / NG</label>
                        </div>
                    </div>
                    <div class="col-10">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <input type="number" name="quantity" onchange="setTwoNumberDecimal(event)" 
                                    min="0" max="10" step="0.25" value="0.00" value="" class="form-control form-control-sm" 
                                    id="quantity_view" disabled aria-describedby="" placeholder="Qty IN">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <input type="number" name="qty_ng" class="form-control form-control-sm" id="qty_ng_view" 
                                    onchange="setTwoNumberDecimal(event)" disabled min="0" max="10" step="0.25" value="0.00" aria-describedby="" placeholder="Qty NG">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <input type="text" name="unit" disabled class="form-control form-control-sm" id="unit_view" aria-describedby="" placeholder="Unit">
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
                            <input type="text" name="remark"  disabled class="form-control form-control-sm" id="remark_view" aria-describedby="" placeholder="Remark">
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
                            <input class="form-control form-control-sm"   name="printed" type="text" id="printed_view" disabled>
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
                            <input class="form-control form-control-sm" name="voided" type="text" id="voided_view" disabled>
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
                            <input class="form-control form-control-sm" name="posted" type="text" id="posted_view" disabled>
                        </div>  
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-8">
                        <div class="form-group align-right">
                            <label class="col-form-label text-bold">Finished</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <input class="form-control form-control-sm" type="text" id="FINISHED" disabled>
                        </div>  
                    </div>
                </div> --}}
            </div>
        </div>
</div>