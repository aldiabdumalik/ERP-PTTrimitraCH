<div class="form-row align-items-center">
    <div class="col-2">
        <label class="auto-middle" for="thp-create-user">User</label>
    </div>
    <div class="col-10">
        <input type="text" name="thp-create-user" id="thp-create-user" class="form-control form-control-sm readonly-first" readonly autocomplete="off" required value="{{auth()->user()->FullName}}">
    </div>
</div>
<div class="form-row align-items-center">
    <div class="col-2">
        <label class="auto-middle" for="thp-create-apnormality">Apnormality</label>
    </div>
    <div class="col-10">
        <select name="thp-create-apnormality" id="thp-create-apnormality" class="form-control form-control-sm">
            <option value="">Select apnormality</option>
            <option value="DIES REPAIR">Dies Repair</option>
            <option value="MATERIAL KOSONG">Material Kosong</option>
            <option value="BELUM SELESAI">Belum Selesai</option>
        </select>
    </div>
</div>
{{-- <div class="form-row align-items-center">
    <div class="col-2">
        <label class="auto-middle" for="thp-create-ket">Keterangan</label>
    </div>
    <div class="col-10">
        <input type="text" name="thp-create-ket" id="thp-create-ket" class="form-control form-control-sm" autocomplete="off">
    </div>
</div> --}}
<div class="form-row align-items-center">
    <div class="col-2">
        <label class="auto-middle" for="thp-create-actionplan">Action plan</label>
    </div>
    <div class="col-10">
        <input type="text" name="thp-create-actionplan" id="thp-create-actionplan" class="form-control form-control-sm" autocomplete="off">
    </div>
</div>
<div class="form-row align-items-center">
    <div class="col-2">
        <label class="auto-middle" for="thp-create-note">Note</label>
    </div>
    <div class="col-10">
        <input type="text" name="thp-create-note" id="thp-create-note" class="form-control form-control-sm" autocomplete="off" value="DEV/{{date('dmy')}}">
    </div>
</div>