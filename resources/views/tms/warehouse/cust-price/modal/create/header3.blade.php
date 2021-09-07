<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custprice-create-user" class="auto-middle">User</label>
    </div>
    <div class="col-10">
        <input type="text" name="custprice-create-user" id="custprice-create-user" class="form-control form-control-sm readonly-first" readonly required value="{{Auth()->user()->FullName}}">
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custprice-create-posted" class="auto-middle">Posted</label>
    </div>
    <div class="col-10">
        <input type="text" name="custprice-create-posted" id="custprice-create-posted" class="form-control form-control-sm readonly-first" readonly>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custprice-create-voided" class="auto-middle">Voided</label>
    </div>
    <div class="col-10">
        <input type="text" name="custprice-create-voided" id="custprice-create-voided" class="form-control form-control-sm readonly-first" readonly>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custprice-create-printed" class="auto-middle">Printed</label>
    </div>
    <div class="col-10">
        <input type="text" name="custprice-create-printed" id="custprice-create-printed" class="form-control form-control-sm readonly-first" readonly required>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custprice-create-entrydate" class="auto-middle">Entry Date</label>
    </div>
    <div class="col-10">
        <input type="text" name="custprice-create-entrydate" id="custprice-create-entrydate" class="form-control form-control-sm readonly-first" value="{{date('d/m/Y')}}" readonly required>
    </div>
</div>