<div class="modal fade bd-example-modal-lg dbpart-modal-index" style="z-index: 1041" tabindex="-1" id="dbpart-modal-index" data-target="#dbpart-modal-index" data-whatever="@dbpartmodalindex"  role="dialog">
    <div class="modal-dialog modal-80">
        <form id="dbpart-form-index" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Database Parts</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-row" style="font-size: 11px;">
                        <div class="col-xl-4 border-left">
                            @include('tms.master.db-parts.modal.form.parts.header1')
                        </div>
                        <div class="col-xl-8 border-left border-right">
                            <div class="row">
                                <div id="item-button-div" class="col-12 text-right">
                                    <button type="button" id="dbpart-btn-add-item" class="btn btn-sm btn-info">
                                        Add Item
                                    </button>
                                    <button type="button" id="dbpart-btn-edit-item" class="btn btn-sm btn-info" disabled>
                                        Edit
                                    </button>
                                    <button type="button" id="dbpart-btn-delete-item" class="btn btn-sm btn-info" disabled>
                                        Delete
                                    </button>
                                </div>
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <div class="">
                                            <table id="dbpart-datatables-index" class="table table-bordered" style="width:100%;cursor:pointer">
                                                <thead class="text-center btn-info" style="font-size: 11px;">
                                                    <tr style="font-size: 11px;">
                                                        <th class="align-middle">#</th>
                                                        <th class="align-middle">Part No.</th>
                                                        <th class="align-middle">Part Name</th>
                                                        <th class="align-middle">Picture</th>
                                                        <th class="align-middle">Vol/Mth</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="form-row mb-2">
                                <div class="col-8">
                                    <h5>Input parts</h5>
                                </div>
                                <div class="col-4 text-right auto-middle">
                                    <a href="javascript:void(0)" class="fa fa-plus text-success fa-2x mr-2"></a>
                                </div>
                            </div>
                            <table class="table">
                                <tr>
                                    <td width="15%" class="align-middle">
                                        <label for="dbpart-index-partno">Part No.</label>
                                    </td>
                                    <td class="align-middle">
                                        <input type="text" name="dbpart-index-partno[]" class="form-control form-control-sm" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" class="align-middle">
                                        <label for="dbpart-index-partname">Part Name</label>
                                    </td>
                                    <td class="align-middle">
                                        <input type="text" name="dbpart-index-partname[]" class="form-control form-control-sm" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" class="align-middle">
                                        <label for="dbpart-index-pict">Picture</label>
                                    </td>
                                    <td class="align-middle">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input form-control-sm" name="dbpart-index-pict[]" accept="image/*" required>
                                            <label class="custom-file-label" for="dbpart-index-pict">Choose file</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" class="align-middle">
                                        <label for="dbpart-index-vol">Vol/Mth</label>
                                    </td>
                                    <td class="align-middle">
                                        <input type="text" name="dbpart-index-vol[]" class="form-control form-control-sm" required>
                                    </td>
                                </tr>
                            </table> --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="dbpart-btn-index-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="dbpart-btn-index-submit" class="btn btn-info">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>