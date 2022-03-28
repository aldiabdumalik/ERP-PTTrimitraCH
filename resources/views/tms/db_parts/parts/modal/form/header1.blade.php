<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="iparts-index-customercode" class="auto-middle">Customer*</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-4">
                <input type="text" name="iparts-index-customercode" id="iparts-index-customercode" class="form-control form-control-sm readonly-first" readonly placeholder="Press Enter..." autocomplete="off" required>
            </div>
            <div class="col-8">
                <input type="text" name="iparts-index-customername" id="iparts-index-customername" class="form-control form-control-sm readonly-first" readonly>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="iparts-index-ppartno" class="auto-middle">Parent of Part</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-4">
                <input type="text" name="iparts-index-ppartno" id="iparts-index-ppartno" class="form-control form-control-sm" placeholder="Press Enter..." autocomplete="off">
            </div>
            <div class="col-8">
                <input type="text" name="iparts-index-ppartname" id="iparts-index-ppartname" class="form-control form-control-sm readonly-first" readonly>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="iparts-index-partno" class="auto-middle">Part No*</label>
    </div>
    <div class="col-10">
        <input type="text" name="iparts-index-partno" id="iparts-index-partno" class="form-control form-control-sm" autocomplete="off" required>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="iparts-index-partname" class="auto-middle">Part name*</label>
    </div>
    <div class="col-10">
        <input type="text" name="iparts-index-partname" id="iparts-index-partname" class="form-control form-control-sm" autocomplete="off" required>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="iparts-index-parttype" class="auto-middle">Part type*</label>
    </div>
    <div class="col-10">
        <input type="text" name="iparts-index-parttype" id="iparts-index-parttype" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="iparts-index-pict" class="auto-middle">Part picture*</label>
    </div>
    <div class="col-8">
        <div class="custom-file">
            <input type="file" class="custom-file-input form-control-sm" name="iparts-index-pict" id="iparts-index-pict" accept="image/*">
            <label class="custom-file-label" id="iparts-index-pict-x" for="iparts-index-pict-x">Choose file</label>
        </div>
    </div>
    <div class="col-2">
        <button type="button" class="btn view-ppict" style="font-size: 10px;"><i>View Image</i></button>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="iparts-index-reff" class="auto-middle">Reff*</label>
    </div>
    <div class="col-10">
        <input type="text" name="iparts-index-reff" id="iparts-index-reff" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
    </div>
</div>
{{-- <div class="form-row align-items-center mb-1">
    <div class="col-2">
    </div>
    <div class="col-10">
        <a href="javascript:void(0)" class="view-ppict">View image</a>
    </div>
</div> --}}
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="iparts-index-vol" class="auto-middle">Vol/Mth*</label>
    </div>
    <div class="col-10">
        <div class="input-group">
            <input type="text" name="iparts-index-vol" id="iparts-index-vol" class="form-control form-control-sm" autocomplete="off" required>
            <div class="input-group-append">
                <span class="input-group-text"><i>Pcs Per Month</i></span>
            </div>
        </div>
    </div>
</div>