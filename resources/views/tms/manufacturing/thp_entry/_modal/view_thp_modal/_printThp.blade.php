<div class="modal fade thp-print-modal" style="z-index: 1041" tabindex="-1" id="thp-print-modal" data-target="#thp-print-modal" data-whatever="@createThp"  role="dialog">
    <div class="modal-dialog" role="document">
        <form id="thp-form-print" method="GET" action="{{route('tms.manufacturing.thp_entry.printThpEntry')}}" target="_blank">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">PRINT THP</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2">
                            <div class="">
                                <label style="font-size:12px;" for="thp_print_dari" class="col-form-label text-bold">Dari tanggal</label>
                            </div>
                        </div>
                        <div class="col-10" style="margin-left:-25px;">
                            <div class="">
                                <input type="text" name="thp_print_dari" id="thp_print_dari" class="form-control print-datepicker" autocomplete="off" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="">
                                <label style="font-size:12px;" for="thp_print_sampai" class="col-form-label text-bold">Sampai tanggal</label>
                            </div>
                        </div>
                        <div class="col-10" style="margin-left:-25px;">
                            <div class="">
                                <input type="text" name="thp_print_sampai" id="thp_print_sampai" class="form-control print-datepicker" autocomplete="off" placeholder="" required>
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