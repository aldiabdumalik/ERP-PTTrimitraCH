<div class="modal fade bd-example-modal-lg viewMto" id="viewModal"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-80">
      <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title view"></h4>
          </div>
          <div class="row">
             <div class="col">
                <div class="modal-body">
                <form  method="POST">
                      @csrf
                      @include('tms.warehouse.mto-entry.modal.view-mto-modal._viewformDetail')
                      <hr>
                       <div class="row">
                          <div class="col-12">
                             <div class="data-tables datatable-dark">
                              <table id="tbl-detail-mto" class="table table-bordered table-hover" width="100%" >
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
                  <div class="modal-footer">
                      <button type="button" class="btn btn-success" data-dismiss="modal">Done</button>
                    </form>
                  </div>
             </div>
        </div>
    </div>
  </div>
</div>
</div>