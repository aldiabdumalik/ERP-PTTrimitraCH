<div class="table-responsive" style="overflow-x:auto">
    <table id="thp-create-datatables" class="table table-bordered table-hover" style="width:100%;cursor:pointer">
        <thead class="text-center" style="font-size: 15px;">
            <tr>
                <th rowspan="2" class="align-middle">Route</th>
                <th rowspan="2" class="align-middle">Process</th>
                <th colspan="2">Plan THP</th>
                <th colspan="2">Actual</th>
                {{-- <th rowspan="2" class="align-middle">Act Hour</th> --}}
                <th rowspan="2" class="align-middle">Note</th>
                <th rowspan="2" class="align-middle">Apnormality</th>
                <th rowspan="2" class="align-middle">Action Plan</th>
            </tr>
            <tr>
                <th>Shift 1</th>
                <th>Shift 2</th>
                <th>Shift 1</th>
                <th>Shift 2</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="align-middle"><input type="text" name="thp-route" id="thp-route" class="form-control" autocomplete="off" required onkeydown="return false"></td>
                <td class="align-middle">
                    <div class="row">
                        <div class="col-5">
                            <input type="text" name="thp-process-1" id="thp-process-1" class="form-control" autocomplete="off" required onkeydown="return false">
                        </div>
                        <div class="col-2">/</div>
                        <div class="col-5">
                            <input type="text" name="thp-process-2" id="thp-process-2" class="form-control" autocomplete="off" required onkeydown="return false">
                        </div>
                    </div>
                </td>
                <td class="align-middle"><input type="number" name="thp-plan-1" id="thp-plan-1" class="form-control" value="0" autocomplete="off" required></td>
                <td class="align-middle"><input type="number" name="thp-plan-2" id="thp-plan-2" class="form-control" value="0" autocomplete="off" required></td>
                <td class="align-middle"><input type="text" name="thp-actual-1" id="thp-actual-1" class="form-control" value="0" autocomplete="off" required onkeydown="return false"></td>
                <td class="align-middle"><input type="text" name="thp-actual-2" id="thp-actual-2" class="form-control" value="0" autocomplete="off" required onkeydown="return false"></td>
                {{-- <td class="align-middle"><input type="number" name="thp-act-hour" id="thp-act-hour" class="form-control" min="0" step="0.25" value="0.00" autocomplete="off" required></td> --}}
                <td class="align-middle"><textarea name="thp-note" id="thp-note" class="form-control" autocomplete="off" cols="30" rows="5">DEV||{{date('ymdHis')}}</textarea></td>
                <td class="align-middle">
                    <select name="thp-apnormal" id="thp-apnormal" class="form-control">
                        <option value="">Pilih Apnormality</option>
                        <option value="DIES REPAIR">Dies Repair</option>
                        <option value="MATERIAL KOSONG">Material Kosong</option>
                        <option value="BELUM SELESAI">Belum Selesai</option>
                    </select>
                </td>
                <td class="align-middle"><input type="text" name="thp-action-plan" id="thp-action-plan" class="form-control" autocomplete="off"></td>
            </tr>
        </tbody>
    </table>
</div>