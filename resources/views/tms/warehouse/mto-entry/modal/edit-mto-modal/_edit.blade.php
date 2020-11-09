<div class="modal fade bd-example-modal-lg modaledit"  tabindex="-1" id="EditModal"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-80">
      <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" ></h4>
          </div>
          <div class="row">
             <div class="col">
                <div class="modal-body">
                <form  id="form-mto-edit" method="post" action="javascript:void(0)">
                      @csrf
                      @method('PUT')
                      <input type="hidden" id="id_mto_edit" name="id_mto">
                      @include('tms.warehouse.mto-entry.modal.edit-mto-modal._formedit')
                      <hr>
                       <div class="row">
                          <div class="col-12">
                             <div class="data-tables datatable-dark">
                              <table id="tbl-edit" class="table table-bordered table-hover" width="100%" >
                                  <thead>
                                      <tr>
                                          <th>Item Code</th>
                                          <th>Part No</th>
                                          <th>Description</th>
                                          <th>Unit</th>
                                          <th>Qty</th>
                                          <th>Qty NG</th>
                                          <th>Warehouse</th>
                                      </tr>
                                  </thead>
                                  <tbody></tbody>
                              </table>  
                            </div>
                          </div>  
                       </div>  
                  </div>
             </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary " data-dismiss="modal">Close</button>
                      <button type="button"  class="btn btn-success edit" ><i class="ti-check"></i> Save</button>
                    </form>
                  </div>
             </div>
        </div>
    </div>
  </div>
</div>
</div>