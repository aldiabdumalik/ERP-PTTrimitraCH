<div class="form-row align-items-center">
    <div class="col-2">
        <label class="auto-middle" for="thp-create-shift">Shift</label>
    </div>
    <div class="col-10">
        <select name="thp-create-shift" id="thp-create-shift" class="form-control form-control-sm" required>
            <option value="" disabled>Select shift</option>
            <option value="1">Shift 1</option>
            <option value="2">Shift 2</option>
        </select>
    </div>
</div>
<div class="form-row align-items-center">
    <div class="col-2">
        <label class="auto-middle" for="thp-create-machine">Machine (TON)</label>
    </div>
    <div class="col-10">
        <input type="text" name="thp-create-machine" id="thp-create-machine" class="form-control form-control-sm" autocomplete="off">
    </div>
</div>
<div class="form-row align-items-center">
    <div class="col-2">
        <label class="auto-middle" for="thp-create-time">Time</label>
    </div>
    <div class="col-10">
        <input type="number" name="thp-create-time" id="thp-create-time" class="form-control form-control-sm" min="0" step="0.01" value="0.00" autocomplete="off" required>
    </div>
</div>
<div class="form-row align-items-center">
    <div class="col-2">
        <label class="auto-middle" for="thp-create-ph">Plan Hour</label>
    </div>
    <div class="col-10">
        <input type="number" name="thp-create-ph" id="thp-create-ph" class="form-control form-control-sm" min="0" step="0.01" value="0.00" autocomplete="off" required>
    </div>
</div>
<div class="form-row align-items-center">
    <div class="col-2">
        <label class="auto-middle" for="thp-create-qty">THP Qty</label>
    </div>
    <div class="col-10">
        <div class="input-group">
            <input type="number" name="thp-create-qty" id="thp-create-qty" class="form-control form-control-sm" autocomplete="off" placeholder="" required>
            <div class="input-group-append">
                <span class="input-group-text">PCS</span>
            </div>
        </div>
    </div>
</div>