<div class="row mt-2">
    <div class="col-2">
        <label for="exampleInputEmail1">MTO No :</label>
        <div class="form-group">
        <input type="number" disabled value="{{ $get_no_mto }}"  name="mto_no" class="form-control form-control-sm" id="mto_no" aria-describedby="" placeholder="">
        </div>
    </div>
    <div class="col-2">
        <div class="form-group" >
            <label for="exampleInputEmail">HO :</label>
            <input type="text" value="HO" disabled class="form-control form-control-sm " id="ho" aria-describedby="" placeholder="">
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            <label for="exampleInputEmail1">Warehouse :</label>
            <input type="text" class="form-control form-control-sm" name="warehouse" value="90" disabled id="warehouse" aria-describedby="" placeholder="">
        </div>
    </div>

    <div class="col-2">
        <div class="form-group">
            <label for="exampleInputEmail1">Item :</label><br>
            <select name="" class="form-control form-control-sm " name="" id="select2">
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

<div class="row mt-2">
    <div class="col-4">
        <label for="exampleInputEmail1">Ref No :</label>
        <div class="form-group">
            <input type="text" name="ref_no" value="{{ $get_no_mto }}"  class="form-control form-control-sm" id="ref_no" aria-describedby="" placeholder="">
        </div>
    </div>
</div>
{{--  --}}
<div class="row mt-3">
    <div class="col-2">
        <label for="exampleInputEmail1">Periode / Date :</label>
        <div class="form-group" >
        <input type="text" name="" value="{{ $getDate1 }}"  class="form-control form-control-sm" id="period" aria-describedby="" placeholder="">
        </div>
    </div>
    <div class="col-2">
        <div class="form-group" style="margin-top: 19%">
            <input type="text" name="" value="{{ $getDate }}" class="form-control form-control-sm" id="vperiode" aria-describedby="" placeholder="">
        </div>
    </div>
</div>


{{--  --}}

<div class="row mt-3">
    <div class="col-3">
        <label for="exampleInputEmail1">Item Code : </label>
        <div class="form-group">
            <div class="input-group">
            <input type="text" name="itemcode" onkeydown="keyPressed(event)" class="form-control form-control-sm" id="ITEMCODE" aria-describedby="" placeholder="Cari Itemcode">
            <span class="input-group-btn">
                <button type="button" id="btnPopUp" class="btn btn-info btn-sm" data-toggle="modal" data-target="#mtoModal"><i class="fa fa-search"></i></button>
            </span><br>
            </div>
            <i style="color: red; font-size: 11px;">(*) tekan F9/Tombol Cari</i>
        </div>
    </div>
    <div class="col-3">
        <div class="form-group" >
            <label for="exampleInputEmail1">Part No :</label>
            <input type="text" name="part_no" class="form-control form-control-sm" id="PART_NO" aria-describedby="" placeholder="">
        </div>
    </div>
    <div class="col-3">
        <div class="form-group" >
            <label for="exampleInputEmail1">Description :</label>
            <input type="text" name="descript" class="form-control form-control-sm" id="DESCRIPT" aria-describedby="" placeholder="">
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-3">
        <label for="exampleInputEmail1">Qty In :</label>
        <div class="form-group">
            <input type="number" name="quantity" onchange="setTwoNumberDecimal(event)" min="0" max="10" step="0.25" value="0.00" value="" class="form-control form-control-sm" id="" aria-describedby="" placeholder="Qty IN">
        </div>
    </div>
    <div class="col-3">
        <div class="form-group" >
            <label for="exampleInputEmail1">Qty NG :</label>
            <input type="number" name="qty_ng" class="form-control form-control-sm" id="qty_ng" onchange="setTwoNumberDecimal(event)" min="0" max="10" step="0.25" value="0.00" aria-describedby="" placeholder="Qty NG">
        </div>
    </div>
    <div class="col-2">
        <div class="form-group" >
            <label for="exampleInputEmail1">Unit</label>
            <input type="text" name="unit" class="form-control form-control-sm" id="unit" aria-describedby="" placeholder="Unit">
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-6">
        <label for="exampleInputEmail1">Remark :</label>
        <div class="form-group">
            <input type="text" name="remark"  class="form-control form-control-sm" id="remark" aria-describedby="" placeholder="Remark">
        </div>
    </div>
</div>

{{-- <div class="pull-right">
<a href="{{ route('tms.warehouse.mto-entry') }}" class="btn btn-sm btn-danger">Kembali</a>
</div> --}}