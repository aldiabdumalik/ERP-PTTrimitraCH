<div class="modal fade bd-example-modal-lg projects-modal-logrev" style="z-index: 1041" tabindex="-1" id="projects-modal-logrev" data-target="#projects-modal-logrev" data-whatever="@projectsmodalindex"  role="dialog">
    <div class="modal-dialog modal-xl">
        <form id="projects-form-logrev" action="javascript:void(0)">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modal Last Revision Log</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="projects-table-revlogs" class="display compact table table-hover" style="width:100%;cursor:pointer">
                            <thead>
                                <tr>
                                    <th class="align-middle">Group</th>
                                    {{-- <th class="align-middle">No.</th> --}}
                                    <th class="align-middle">Field Name</th>
                                    <th class="align-middle">Before Update</th>
                                    <th class="align-middle">After Update</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="projects-btn-logrev-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>