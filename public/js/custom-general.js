function buildNotif(category, message){
    var $html = '<div class="alert-dismiss"> \
                    <div class="alert alert-' + category + ' alert-dismissible fade show" role="alert"> \
                        ' + message + ' \
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span class="fa fa-times"></span></button> \
                    </div> \
                </div>';
    return $html
}