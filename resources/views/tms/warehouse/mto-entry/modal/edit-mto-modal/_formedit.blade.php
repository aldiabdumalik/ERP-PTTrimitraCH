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
                        <input type="number" disabled   name="mto_no" class="form-control form-control-sm" id="mto_no_edit" aria-describedby="" placeholder="">
                    </div>  
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <input type="text" disabled class="form-control form-control-sm " id="branch_edit" aria-describedby="" placeholder="">
                    </div>  
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm" name="warehouse" disabled  id="warehouse_edit" aria-describedby="" placeholder="">
                    </div>  
                </div>
                <div class="col-2">
                    <div class="form-group">
                        {{-- <select name="types" class="form-control form-control-sm select_edit"  id="types_edit">
                            <option value=""></option>
                            <option value="91">91 PRESSING</option>
                            <option value="92">92 WELDING</option>
                            <option value="93">93 SPOT WELDING</option>
                            <option value="94">94 ASSY</option>
                            <option value="D5">D5 PROCESS AT D5</option>
                        </select> --}}
                        <input type="text" disabled class="form-control form-control-sm" name="types"  id="types_edit" aria-describedby="" placeholder="">
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
                    <input class="form-control form-control-sm" value="{{ Auth::user()->UserID }}" name="staff_edit" type="text" id="STAFF" disabled>
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
                        <input type="text" name="ref_no" disabled class="form-control form-control-sm" id="ref_no_edit" aria-describedby="" placeholder="">
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
                                <input class="form-control form-control-sm"   type="text" name="period" id="period_edit" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <input class="form-control form-control-sm" type="date"  name="written"  id="written_edit">
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
                                <input class="form-control form-control-sm" name="fin_code" type="text"   
                                 onkeydown="keyPressedEdit(event)" id="ITEMCODE" placeholder="Cari Itemcode">
                                 <span class="input-group-btn">
                                    <button type="button" id="btnPopUp2" disabled class="btn btn-info btn-xs" data-toggle="modal" data-target="#mtoModal2"><i class="fa fa-search"></i></button>
                                </span><br>
                                <i style="color: red; font-size: 11px;">(*) tekan F9/Tombol Cari</i>
                                </div>
                               
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <input type="text" name="frm_code" disabled class="form-control form-control-sm" id="PART_NO" aria-describedby="" placeholder="">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="text" name="descript" disabled class="form-control form-control-sm" id="DESCRIPT" aria-describedby="" placeholder="">
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
                                min="0" step="0.25" value="0.00" value="" class="form-control form-control-sm" 
                                id="quantity_edit" aria-describedby="" placeholder="Qty IN">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="number" name="qty_ng" class="form-control form-control-sm" id="qty_ng_edit" 
                                onchange="setTwoNumberDecimal(event)" min="0" max="10" step="0.25" value="0.00" aria-describedby="" placeholder="Qty NG">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="text" name="unit" autocomplete="off" class="form-control form-control-sm" id="unit_edit" aria-describedby="" placeholder="Unit">
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
                        <input type="text" name="remark" autocomplete="off"  class="form-control form-control-sm" id="remark_edit" aria-describedby="" placeholder="Remark">
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
                        <input class="form-control form-control-sm" name="printed" type="text" id="printed_edit" disabled>
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
                        <input class="form-control form-control-sm" name="voided" type="text" id="voided_edit" disabled>
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
                        <input class="form-control form-control-sm" name="posted" type="text" id="posted_edit" disabled>
                    </div>  
                </div>
            </div>
        </div>
    </div>
{{-- <div class="pull-right">
<a href="{{ route('tms.warehouse.mto-entry') }}" class="btn btn-sm btn-danger">Kembali</a>
</div> --}}