<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custinv-create-user" class="auto-middle">User</label>
    </div>
    <div class="col-10">
        <input type="text" name="custinv-create-user" id="custinv-create-user" class="form-control form-control-sm readonly-first" readonly required value="{{Auth()->user()->FullName}}">
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-6">
        <div class="form-row align-items-center">
            <div class="col-4">
                <label for="custinv-create-posted" class="auto-middle">Posted</label>
            </div>
            <div class="col-8">
                <input type="text" name="custinv-create-posted" id="custinv-create-posted" class="form-control form-control-sm readonly-first" readonly>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-row align-items-center">
            <div class="col-4">
                <label for="custinv-create-voided" class="auto-middle">Voided</label>
            </div>
            <div class="col-8">
                <input type="text" name="custinv-create-voided" id="custinv-create-voided" class="form-control form-control-sm readonly-first" readonly>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custinv-create-printed" class="auto-middle">Printed</label>
    </div>
    <div class="col-10">
        <input type="text" name="custinv-create-printed" id="custinv-create-printed" class="form-control form-control-sm readonly-first" readonly required>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-6">
        <div class="form-row align-items-center">
            <div class="col-4">
                <label for="custinv-create-subtotal" class="auto-middle">Subtotal</label>
            </div>
            <div class="col-8">
                <input type="text" name="custinv-create-subtotal" id="custinv-create-subtotal" class="form-control form-control-sm readonly-first" value="0.00" readonly>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-row align-items-center">
            <div class="col-4">
                <label for="custinv-create-cndisc" class="auto-middle">CN/Disc</label>
            </div>
            <div class="col-8">
                <input type="text" name="custinv-create-cndisc" id="custinv-create-cndisc" class="form-control form-control-sm readonly-first" value="0.00" readonly>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-6">
        <div class="form-row align-items-center">
            <div class="col-4">
                <label for="custinv-create-vat" class="auto-middle">VAT</label>
            </div>
            <div class="col-8">
                <input type="text" name="custinv-create-vat" id="custinv-create-vat" class="form-control form-control-sm readonly-first" value="0.00" readonly>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-row align-items-center">
            <div class="col-4">
                <label for="custinv-create-total" class="auto-middle">Total</label>
            </div>
            <div class="col-8">
                <input type="text" name="custinv-create-total" id="custinv-create-total" class="form-control form-control-sm readonly-first" value="0.00" readonly>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-6">
        <div class="form-row align-items-center">
            <div class="col-4">
                <label for="custinv-create-payment" class="auto-middle">Payment</label>
            </div>
            <div class="col-8">
                <input type="text" name="custinv-create-payment" id="custinv-create-payment" class="form-control form-control-sm readonly-first" value="0.00" readonly>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-row align-items-center">
            <div class="col-4">
                <label for="custinv-create-balance" class="auto-middle">Balance</label>
            </div>
            <div class="col-8">
                <input type="text" name="custinv-create-balance" id="custinv-create-balance" class="form-control form-control-sm readonly-first" value="0.00" readonly>
            </div>
        </div>
    </div>
</div>