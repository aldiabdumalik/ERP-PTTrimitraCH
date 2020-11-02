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
                            <input type="number" disabled   name="mto_no" class="form-control form-control-sm" id="MTO_NO" aria-describedby="" placeholder="">
                        </div>  
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" value="HO" disabled class="form-control form-control-sm " id="HO" aria-describedby="" placeholder="">
                        </div>  
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-sm" name="warehouse" value="90" disabled id="WAREHOUSE" aria-describedby="" placeholder="">
                        </div>  
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <select name="" class="form-control form-control-sm " name="" id="SELECT2">
                                <option value="">Pilih Item</option>
                                <option value="91 PRESSING">91 PRESSING</option>
                                <option value="92 WELDING">92 WELDING</option>
                                <option value="93 SPOT WELDING">93 SPOT WELDING</option>
                                <option value="94 ASSY">94 ASSY</option>
                                <option value="D5 PROCESS AT D5">D5 PROCESS AT D5</option>
                            </select>
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
                            <input class="form-control form-control-sm" name="staff" type="text" id="STAFF" disabled>
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
                            <input type="text" name="ref_no"   class="form-control form-control-sm" id="REF_NO" aria-describedby="" placeholder="">
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
                                    <input class="form-control form-control-sm"  type="text" name="period" id="period" required value="{{ $getDate1 }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <input class="form-control form-control-sm" type="text" name="vperiod" value="{{ $getDate }}" id="VPERIOD" required>
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
                                    <input class="form-control form-control-sm" name="itemcode" type="text" name="from-wh-branch"  required
                                     onkeydown="keyPressed(event)" id="ITEM_CODE" aria-describedby="" placeholder="Cari Itemcode">
                                     {{-- <span class="input-group-btn">
                                        <button type="button" id="btnPopUp" class="btn btn-info btn-xs" data-toggle="modal" data-target="#mtoModal"><i class="fa fa-search"></i></button>
                                    </span> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <input type="text" name="part_no" class="form-control form-control-sm" id="PARTNO" aria-describedby="" placeholder="">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <input type="text" name="descript" class="form-control form-control-sm" id="DESCRIPT_" aria-describedby="" placeholder="">
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
                                    id="QUANTITY" aria-describedby="" placeholder="Qty IN">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <input type="number" name="qty_ng" class="form-control form-control-sm" id="QTY_NG" 
                                    onchange="setTwoNumberDecimal(event)" min="0" max="10" step="0.25" value="0.00" aria-describedby="" placeholder="Qty NG">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <input type="text" name="unit" class="form-control form-control-sm" id="UNIT" aria-describedby="" placeholder="Unit">
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
                            <input type="text" name="remark"  class="form-control form-control-sm" id="REMARK" aria-describedby="" placeholder="Remark">
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
                            <input class="form-control form-control-sm" name="printed" type="text" id="PRINTED" disabled>
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
                            <input class="form-control form-control-sm" name="voided" type="text" id="VOIDED" disabled>
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
                            <input class="form-control form-control-sm" name="posted" type="text" id="POSTED" disabled>
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