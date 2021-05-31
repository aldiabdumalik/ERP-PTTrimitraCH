<div class="modal fade thp-import-modal" style="z-index: 1041" tabindex="-1" id="thp-import-modal" data-target="#thp-import-modal" data-whatever="@createThp"  role="dialog">
    <div class="modal-dialog" role="document">
        <form id="thp-form-import" action="javascript:void(0)" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">IMPORT THP</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2">
                            <div class="">
                                <label style="font-size:12px;" for="thp_import_tanggal" class="col-form-label text-bold">Tanggal</label>
                            </div>
                        </div>
                        <div class="col-10" style="margin-left:-25px;">
                            <div class="">
                                <input type="text" name="thp_import_tanggal" id="thp_import_tanggal" class="form-control this-datepicker" autocomplete="off" placeholder="" required>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="">
                                <label style="font-size:12px;" for="thp_import_process" class="col-form-label text-bold">Dept.</label>
                            </div>
                        </div>
                        <div class="col-10" style="margin-left:-25px;">
                            <div class="">
                                <select name="thp_import_process" id="thp_import_process" class="form-control" required>
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
                                <label style="font-size:12px;" for="thp_import_file" class="col-form-label text-bold">File (Excel)</label>
                            </div>
                        </div>
                        <div class="col-10" style="margin-left:-25px;margin-top:18px;">
                            <div class="">
                                <input type="file" name="thp_import_file" id="thp_import_file" class="form-control-file" autocomplete="off" placeholder="" required>
                            </div>
                            <div class="progress progress-import" style="display: none">
                                <div id="progress-import" class="" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                    </div>
                    {{-- <div class="row" style="margin-top: 5px;">
                        <div class="col-12">
                            <div class="progress progress-import" hidden>
                                <div id="progress-import" class="" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary thp-cancel-import" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary thp-import-btn">Import</button>
                </div>
            </div>
        </form>
    </div>
</div>