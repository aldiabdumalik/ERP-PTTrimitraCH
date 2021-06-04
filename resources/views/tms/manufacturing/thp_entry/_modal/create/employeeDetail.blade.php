<div class="row">
    <div class="col-2">
        <div class="">
            <label style="font-size:12px;" for="thp-operator" class="col-form-label text-bold">Operator</label>
        </div>
    </div>
    <div class="col-10">
        <div class="">
            <input type="text" name="thp-operator" id="thp-operator" class="form-control" autocomplete="off" placeholder="" required value="{{Auth()->user()->FullName}}" onkeydown="return false">
        </div>
    </div> 
</div>
{{-- <div class="row">
    <div class="col-2">
        <div class="">
            <label style="font-size:12px;" for="thp-shift" class="col-form-label text-bold">Shift</label>
        </div>
    </div>
    <div class="col-10">
        <div class="row">
            <div class="col-12">
                <div class="">
                    <select name="thp-shift" id="thp-shift" class="form-control" required>
                        <option value="">Shift</option>
                    </select>
                </div>
            </div>
            <div class="col-2">
                <label style="font-size:12px;" for="thp-grup" class="col-form-label text-bold">/</label>
            </div>
            <div class="col-5">
                <div class="">
                    <select name="thp-grup" id="thp-grup" class="form-control" required>
                        <option value="">Grup</option>
                    </select>
                </div>
            </div> 
        </div>
    </div> 
</div> --}}
{{-- <div class="row">
    <div class="col-2">
        <div class="">
            <label style="font-size:12px;" for="thp-machine" class="col-form-label text-bold">Machine</label>
        </div>
    </div>
    <div class="col-10">
        <div class="">
            <select name="thp-machine" id="thp-machine" class="form-control" required>
                <option value="">Machine</option>
            </select>
        </div>
    </div> 
</div> --}}
<div class="row">
    <div class="col-2">
        <div class="">
            <label style="font-size:11px;" for="thp-apnormal" class="col-form-label text-bold">Apnormal</label>
        </div>
    </div>
    <div class="col-10">
        <div class="">
            <select name="thp-apnormal" id="thp-apnormal" class="form-control">
                <option value="">Apnormality</option>
                <option value="DIES REPAIR">Dies Repair</option>
                <option value="MATERIAL KOSONG">Material Kosong</option>
                <option value="BELUM SELESAI">Belum Selesai</option>
            </select>
        </div>
    </div> 
</div>
<div class="row">
    <div class="col-2">
        <div class="">
            <label style="font-size:12px;" for="thp-note" class="col-form-label text-bold">Note</label>
        </div>
    </div>
    <div class="col-10">
        <div class="">
            <textarea name="thp-note" id="thp-note" class="form-control" style="hight:10px">DEV|{{date('ymdhi')}}</textarea>
        </div>
    </div> 
</div>
<div class="row">
    <div class="col-2">
        <div class="">
            <label style="font-size:12px;" for="thp-thp-action-plan" class="col-form-label text-bold">Action plan</label>
        </div>
    </div>
    <div class="col-10">
        <div class="">
            <input type="text" name="thp-thp-action-plan" id="thp-thp-action-plan" class="form-control" autocomplete="off">
        </div>
    </div> 
</div>