<div class="modal fade dbpart-modal-processing" style="z-index: 1041" tabindex="-1" id="dbpart-modal-processing" data-target="#dbpart-modal-processing" data-whatever="@domodalprocessing"  role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="dbpart-form-processing" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Processing form</h4>
                </div>
                <div class="modal-body">
                    <div style="font-size: 11px;">
                        <table class="table" id="tbl_proc_detail_form">
                            <tr>
                                <td width="15%" class="align-middle">
                                    <label for="dbpart-processing-name">Process Name</label>
                                </td>
                                <td colspan="2" class="align-middle">
                                    <select name="dbpart-processing-name" id="dbpart-processing-name" class="form-control form-control-sm" required></select>
                                </td>
                            </tr>
                            <tr>
                                <td width="15%" class="align-middle">
                                    <label for="dbpart-processing-detail">Process Detail</label>
                                </td>
                                <td class="align-middle">
                                    <input type="text" name="dbpart-processing-detail" id="dbpart-processing-detail" class="form-control form-control-sm dbpart-processing-detail" data-id="1">
                                </td>
                                <td width="5%" class="align-middle">
                                    <a href="javascript:void(0)" class="fa fa-plus text-success add-row-proc_detail"></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="dbpart-btn-processing-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="dbpart-btn-processing-submit" class="btn btn-info">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>