<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="iparts-index-user" class="auto-middle">User</label>
    </div>
    <div class="col-10">
        <input type="text" name="iparts-index-user" id="iparts-index-user" class="form-control form-control-sm readonly-first" autocomplete="off" required readonly value="{{auth()->user()->FullName}}">
    </div>
</div>
<div class="form-row align-items-center mb-1">
    <div class="col-2">
        <label for="iparts-index-date" class="auto-middle">Entry Date</label>
    </div>
    <div class="col-10">
        <input type="text" name="iparts-index-date" id="iparts-index-date" class="form-control form-control-sm readonly-first" autocomplete="off" required readonly value="{{date('d/m/Y')}}">
    </div>
</div>