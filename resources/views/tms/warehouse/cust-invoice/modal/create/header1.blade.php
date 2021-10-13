<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custinv-create-no" class="auto-middle">Invoice No.</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-6">
                <input type="text" name="custinv-create-no" id="custinv-create-no" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
            </div>
            <div class="col-3">
                <select name="custinv-create-type" id="custinv-create-type" class="form-control form-control-sm" required>
                    <option value="RG">RG - Non SJ</option>
                    <option value="SJ" selected>SJ - Dari surat</option>
                </select>
            </div>
            <div class="col-3">
                <input type="text" name="custinv-create-branch" id="custinv-create-branch" class="form-control form-control-sm readonly-first" readonly required value="{{auth()->user()->Branch}}">
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custinv-create-priod" class="auto-middle">Priod/Date</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-6">
                <input type="text" name="custinv-create-priod" id="custinv-create-priod" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
            </div>
            <div class="col-6">
                <input type="text" name="custinv-create-date" id="custinv-create-date" class="form-control form-control-sm this-datepicker" autocomplete="off" required>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custinv-create-refno" class="auto-middle">Refs. No</label>
    </div>
    <div class="col-10">
        <input type="text" name="custinv-create-refno" id="custinv-create-refno" class="form-control form-control-sm" autocomplete="off" required>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custinv-create-vat1" class="auto-middle">VAT No.</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-2">
                <input type="number" name="custinv-create-vat1" id="custinv-create-vat1" class="form-control form-control-sm" autocomplete="off" required maxlength="1" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
            </div>
            <div class="col-5">
                <input type="number" name="custinv-create-vat2" id="custinv-create-vat2" class="form-control form-control-sm" autocomplete="off" required>
            </div>
            <div class="col-5">
                <div class="input-group input-group-sm">
                    <input type="number" name="custinv-create-vat3" id="custinv-create-vat3" class="form-control form-control-sm text-right" autocomplete="off" required min="0" step="0.01" value="0.00">
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custinv-create-sales" class="auto-middle">Sales/PIC</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-6">
                <input type="text" name="custinv-create-sales" id="custinv-create-sales" class="form-control form-control-sm readonly-first" readonly value="{{ auth()->user()->Branch }}" autocomplete="off" required>
            </div>
            <div class="col-6">
                <input type="text" name="custinv-create-pic" id="custinv-create-pic" class="form-control form-control-sm readonly-first" readonly value="{{ auth()->user()->FullName }}" autocomplete="off" required>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custinv-create-currency-type" class="auto-middle">Currency</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-6">
                <select name="custinv-create-currency-type" id="custinv-create-currency-type" class="form-control form-control-sm"></select>
            </div>
            <div class="col-6">
                <input type="text" name="custinv-create-currenvy-value" id="custinv-create-currenvy-value" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custinv-create-terms" class="auto-middle">Terms</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-12">
                <div class="input-group input-group-sm">
                    <input type="number" name="custinv-create-terms" id="custinv-create-terms" class="form-control form-control-sm" autocomplete="off" required min="0" value="0">
                    <div class="input-group-append">
                        <span class="input-group-text">Days</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custinv-create-duedate" class="auto-middle">Due Date</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-12">
                <input type="text" name="custinv-create-duedate" id="custinv-create-duedate" class="form-control form-control-sm this-datepicker" autocomplete="off" required>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custinv-create-remark" class="auto-middle">Remark</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-12">
                <input type="text" name="custinv-create-remark" id="custinv-create-remark" class="form-control form-control-sm" autocomplete="off" required>
            </div>
        </div>
    </div>
</div>