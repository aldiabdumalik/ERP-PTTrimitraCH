<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="claim-create-no">Claim No</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-6">
                <input type="text" name="claim-create-no" id="claim-create-no" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
            </div>
            <div class="col-3">
                <input type="text" name="claim-create-branch" id="claim-create-branch" class="form-control form-control-sm readonly-first" readonly value="{{ auth()->user()->Branch }}" autocomplete="off" required>
            </div>
            <div class="col-3">
                <input type="text" name="claim-create-warehouse" id="claim-create-warehouse" class="form-control form-control-sm" autocomplete="off" required>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="claim-create-priod">Priod/Date</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-6">
                <input type="text" name="claim-create-priod" id="claim-create-priod" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
            </div>
            <div class="col-6">
                <input type="text" name="claim-create-date" id="claim-create-date" class="form-control form-control-sm" autocomplete="off" required>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="claim-create-pono">PO No</label>
    </div>
    <div class="col-10">
        <input type="number" name="claim-create-pono" id="claim-create-pono" class="form-control form-control-sm" autocomplete="off" required>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="claim-create-refno">Refs. No</label>
    </div>
    <div class="col-10">
        <input type="text" name="claim-create-refno" id="claim-create-refno" class="form-control form-control-sm" autocomplete="off" required>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="claim-create-delivery">Delivery</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-6">
                <input type="text" name="claim-create-delivery" id="claim-create-delivery" class="form-control form-control-sm readonly-first" readonly value="/ /">
            </div>
            <div class="col-6">
                <input type="text" name="claim-create-delivery2" id="claim-create-delivery2" class="form-control form-control-sm readonly-first" readonly>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="claim-create-remark" style="margin-bottom:0rem !important;">Remark</label>
    </div>
    <div class="col-10">
        <input type="text" name="claim-create-remark" id="claim-create-remark" class="form-control form-control-sm" autocomplete="off" required>
    </div>
</div>