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
                        <input type="number" disabled  value="{{ $get_no_mto }}"  name="mto_no" class="form-control form-control-sm" id="mto_no_create" aria-describedby="" placeholder="">
                    </div>  
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <input type="text" value="HO" disabled class="form-control form-control-sm " id="branch_create" aria-describedby="" placeholder="">
                    </div>  
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm" name="warehouse" value="90" disabled id="warehouse_create" aria-describedby="" placeholder="">
                    </div>  
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <select name="types" autocomplete="off" onchange="validateCreateMto()"  class="form-control select_create"    id="types_create"  required>
                            <option value="">-Choice-</option>
                            <option value="91">91 PRESSING</option>
                            <option value="92">92 WELDING</option>
                            <option value="93">93 SPOT WELDING</option>
                            <option value="94">94 ASSY</option>
                            <option value="D5">D5 PROCESS AT D5</option>
                        </select>
                    </div>  
                </div>
            </div>
        </div>
{{-- right form --}}
        <div class="col-6">
            <div class="row">
                <div class="col-6">
                    <div class="form-group align-right">
                        <label class="col-form-label text-bold">Staff</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                    <input class="form-control form-control-sm" value="{{ Auth::user()->UserID }}" name="staff" type="text" id="staff_create" disabled>
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
                        <input type="text" name="ref_no"  value="{{ $get_no_mto }}"  class="form-control form-control-sm" id="ref_no_create" aria-describedby="" placeholder="">
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
                                <input class="form-control form-control-sm"  value="{{ $getDate1 }}"  type="text" name="period" id="period_create" required value="{{ $getDate1 }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm"  name="written" value="{{ $getDate }}" id="written_create" required>
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
                                 onkeydown="keyPressed(event)" autocomplete="off" id="itemcode_create" onchange="validateCreateMto()" placeholder="Search">
                                 <span class="input-group-btn">
                                    <button type="button" id="btnPopUp" class="btn btn-info btn-xs" data-toggle="modal" data-target="#mtoModal"><i class="fa fa-search"></i></button>
                                </span><br>
                                <i style="color: red; font-size: 11px;">(*) Press Enter/Search Button</i>
                                </div>
                               
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <input type="text" name="frm_code" class="form-control form-control-sm" autocomplete="off" onchange="validateCreateMto()" id="part_no_create" aria-describedby="" placeholder="">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="text" name="descript" class="form-control form-control-sm" autocomplete="off" onchange="validateCreateMto()" id="descript_create" aria-describedby="" placeholder="">
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
                                min="0" step="0.25" value="0.00" value=""  class="form-control form-control-sm" 
                                id="quantity_create" aria-describedby="" placeholder="Qty IN">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="number" name="qty_ng" class="form-control form-control-sm" id="qty_ng" 
                                onchange="setTwoNumberDecimal(event)" min="0" max="10" step="0.25" value="0.00" aria-describedby="" placeholder="Qty NG">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="text" name="unit"  onchange="validateCreateMto()"  autocomplete="off" class="form-control form-control-sm" id="unit_create" aria-describedby="" placeholder="Unit">
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
                        <input type="text" name="remark" autocomplete="off" class="form-control form-control-sm" id="remark_create" aria-describedby="" placeholder="Remark">
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
                        <input class="form-control form-control-sm" name="printed" type="text" id="printed_create" disabled>
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
                        <input class="form-control form-control-sm" name="voided" type="text" id="voided_voided" disabled>
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
                        <input class="form-control form-control-sm" name="posted" type="text" id="posted_create" disabled>
                    </div>  
                </div>
            </div>
        </div>
{{-- <div class="pull-right">
<a href="{{ route('tms.warehouse.mto-entry') }}" class="btn btn-sm btn-danger">Kembali</a>
</div> --}}