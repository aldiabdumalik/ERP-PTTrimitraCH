<div class="modal fade bd-example-modal-lg modalcreate" style="z-index: 1041" tabindex="-1" id="createModal" data-target="#mtoModalCreate" data-whatever="@createMTO"  role="dialog">
    <div class="modal-dialog modal-80">
      <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" ></h4>
          </div>
          <div class="row">
             <div class="col">
                <div class="modal-body">
                <form  id="form-mto" method="post" action="javascript:void(0)">
                      @csrf
                      <input type="hidden" id="id_mto" name="id_mto">
                      @include('tms.warehouse.mto-entry.modal.create-mto-modal._form')
                      {{-- <hr>
                       <div class="row">
                          <div class="col-12">
                             <div class="data-tables datatable-dark">
                              <table id="tbl-detail" class="table table-bordered table-hover" width="100%" >
                                  <thead>
                                      <tr>
                                          <th>Item Code</th>
                                          <th>Part No</th>
                                          <th>Description</th>
                                          <th>Type</th>
                                          <th>Qty</th>
                                      </tr>
                                  </thead>
                                  <tbody></tbody>
                              </table>  
                            </div>
                          </div>  
                       </div>   --}}
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary " data-dismiss="modal">Close</button>
                      <button type="button"  class="btn btn-success add" ><i class="ti-check"></i> Save</button>
                    </form>
                  </div>
             </div>
        </div>
    </div>
  </div>
</div>
</div>