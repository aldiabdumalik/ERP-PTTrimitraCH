<div class="modal fade bd-example-modal-lg thp-modal-notif" style="z-index: 1041" tabindex="-1" id="thp-modal-notif" data-target="#thp-modal-notif" data-whatever="@thpmodalindex"  role="dialog">
    <div class="modal-dialog modal-80">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Notifications <span class="badge badge-pill badge-success thpnotif-num2">{{$notif}}</span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="thp-notif-list">
                    @if ($notif == 0)
                        <div class="alert alert-success" role="alert">
                            Notification not found!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>