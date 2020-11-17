<div class="modal fade-out bd-example-modal-lg modalUnPost"  tabindex="-1" id="ModalUnPost"  role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" ></h4>
          </div>
          <div class="row">
             <div class="col">
                <div class="modal-body">
                <form  id="form-mto-un-post" method="post" action="javascript:void(0)">
                      @csrf
                      @method('POST')
                      <input type="hidden" id="id_mto_unpost" name="id_mto">
                      <div class="col">
                        <span id="form-output"></span>
                        {{-- <input class="form-control" type="hidden" name="id" id="role-id"> --}}
                        <div class="row">
                            <div class="col-7">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="col-form-label text-bold">Type </label>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="form-group">
                                            <input type="text" disabled  value="UN-POST"  name="mto_no" class="form-control form-control-sm" placeholder="">
                                        </div>  
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="col-form-label text-bold">Number </label>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="form-group">
                                            <input type="text" disabled  name="mto_no" class="form-control form-control-sm mto_no_unpost" placeholder="">
                                        </div>  
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="col-form-label text-bold">Exception note </label>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="form-group">
                                            <input type="text"  name="note" id="note" class="form-control form-control-sm" placeholder="">
                                        </div>  
                                    </div>
                                </div>
                            </div>
                      <hr>
                  </div>
             </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary btn-flat btn-sm " data-dismiss="modal">Cancel</button>
                      <button type="button"  class="btn btn-success btn-flat btn-sm ok_unpost" ><i class="ti-check"></i> Ok</button>
                    </form>
                  </div>
             </div>
        </div>
    </div>
  </div>
</div>
</div>