<div class="modal fade thp-print-modal" style="z-index: 1041" tabindex="-1" id="thp-print-modal" data-target="#thp-print-modal" data-whatever="@createThp"  role="dialog">
    <div class="modal-dialog" role="document">
        <form id="thp-form-print" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">REPORT THP</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2">
                            <div class="">
                                <label style="font-size:12px;" for="thp_print_dari" class="col-form-label text-bold">Tanggal</label>
                            </div>
                        </div>
                        <div class="col-10" style="margin-left:-25px;">
                            <div class="">
                                <input type="text" name="thp_print_dari" id="thp_print_dari" class="form-control this-datepicker" autocomplete="off" placeholder="" required>
                            </div>
                        </div>

                        {{-- <div class="col-2">
                            <div class="">
                                <label style="font-size:12px;" for="thp_print_sampai" class="col-form-label text-bold">Sampai tanggal</label>
                            </div>
                        </div>
                        <div class="col-10" style="margin-left:-25px;">
                            <div class="">
                                <input type="text" name="thp_print_sampai" id="thp_print_sampai" class="form-control print-datepicker" autocomplete="off" placeholder="" required>
                            </div>
                        </div> --}}
                        <div class="col-2">
                            <div class="">
                                <label style="font-size:12px;" for="thp_print_process" class="col-form-label text-bold">Dept.</label>
                            </div>
                        </div>
                        <div class="col-10" style="margin-left:-25px;">
                            <div class="">
                                <select name="thp_print_process" id="thp_print_process" class="form-control" required>
                                    <option value="">Pilih departemen</option>
                                    <option value="ASSEMBLING">ASSEMBLING</option>
                                    <option value="PRESSING" selected>PRESSING</option>
                                    <option value="WELDING">WELDING</option>
                                    <option value="SPOT">SPOT</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="">
                                <label style="font-size:12px;" for="thp_print_type" class="col-form-label text-bold">Type.</label>
                            </div>
                        </div>
                        <div class="col-10" style="margin-left:-25px;">
                            <div class="">
                                <select name="thp_print_type" id="thp_print_type" class="form-control" required>
                                    <option value="PDF">PDF</option>
                                    <option value="EXCEL" selected>EXCEL</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary thp-cancel-print" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary thp-print-btn">Print</button>
                </div>
            </div>
        </form>
    </div>
</div>