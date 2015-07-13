function pesan_err(pesan) {
    var temp = '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Error</h4>  ' + pesan + ' </div>';
    return temp;
}

function pesan_succ(pesan) {
    var temp = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Success</h4> ' + pesan + ' </div>';
    return temp;
}