<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="claim-create-user">User</label>
    </div>
    <div class="col-10">
        <input type="text" name="claim-create-user" id="claim-create-user" class="form-control form-control-sm readonly-first" readonly required value="{{Auth()->user()->FullName}}">
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-6">
        <div class="form-row align-items-center">
            <div class="col-4">
                <label for="claim-create-printed">Printed</label>
            </div>
            <div class="col-8">
                <input type="text" name="claim-create-printed" id="claim-create-printed" class="form-control form-control-sm readonly-first" readonly>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-row align-items-center">
            <div class="col-4">
                <label for="claim-create-voided">Voided</label>
            </div>
            <div class="col-8">
                <input type="text" name="claim-create-voided" id="claim-create-voided" class="form-control form-control-sm readonly-first" readonly>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-6">
        <div class="form-row align-items-center">
            <div class="col-4">
                <label for="claim-create-rgdate">RG date</label>
            </div>
            <div class="col-8">
                <input type="text" name="claim-create-rgdate" id="claim-create-rgdate" class="form-control form-control-sm readonly-first" readonly>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-row align-items-center">
            <div class="col-4">
                <label for="claim-create-dodate">DO date</label>
            </div>
            <div class="col-8">
                <input type="text" name="claim-create-dodate" id="claim-create-dodate" class="form-control form-control-sm readonly-first" readonly>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="claim-create-closed">Closed</label>
    </div>
    <div class="col-10">
        <input type="text" name="claim-create-closed" id="claim-create-closed" class="form-control form-control-sm readonly-first" readonly>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="claim-create-rrno">RR No</label>
    </div>
    <div class="col-10">
        <input type="text" name="claim-create-rrno" id="claim-create-rrno" class="form-control form-control-sm readonly-first" readonly>
    </div>
</div>