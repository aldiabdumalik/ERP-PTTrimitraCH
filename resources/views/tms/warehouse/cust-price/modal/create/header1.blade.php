<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custprice-create-no" class="auto-middle">Customer Price No.</label>
    </div>
    <div class="col-10">
        <input type="text" name="custprice-create-no" id="custprice-create-no" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custprice-create-customercode">Customer</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-4">
                <input type="text" name="custprice-create-customercode" id="custprice-create-customercode" class="form-control form-control-sm" autocomplete="off" required>
            </div>
            <div class="col-8">
                <input type="text" name="custprice-create-customername" id="custprice-create-customername" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required>
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custprice-create-currency-type" class="auto-middle">Currency</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-4">
                <input type="text" name="custprice-create-currency-type" id="custprice-create-currency-type" class="form-control form-control-sm" autocomplete="off" required value="IDR">
            </div>
        </div>
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="custprice-create-posted" class="auto-middle">Price By</label>
    </div>
    <div class="col-10">
        <div class="row no-gutters">
            <div class="col-4">
                <select name="custprice-create-posted" id="custprice-create-posted" class="form-control form-control-sm" required style="pointer-events: none">
                    <option value="DATE">DATE</option>
                    <option value="SO" selected>SO</option>
                </select>
            </div>
            <div class="col-8">
                <div class="form-row align-items-center align-center">
                    <div class="col-3">
                        <label for="custprice-create-voided" class="auto-middle">Active Date</label>
                    </div>
                    <div class="col-9">
                        <input type="text" name="custprice-create-voided" id="custprice-create-voided" class="form-control form-control-sm this-datepicker" required autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>