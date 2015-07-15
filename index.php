<?php
/*memulai session*/
session_start();

/*load file autoload untuk meload file Facebook SDK
require __DIR__ . '/autoload.php';
ganti pakai composer*/
require_once __DIR__ . '/vendor/autoload.php';
require_once 'config.php';

/*use Facebook\FacebookSession;
load namespace Facebook SDK untuk PHP
use Facebook\FacebookRedirectLoginHelper;*/

/*load fb*/
$fb = new Facebook\Facebook([
  'app_id' => APP_ID,
  'app_secret' => APP_SECRET,
  'default_graph_version' => 'v2.2',
]);

/*pengecekan bila didapat parameter logout dari url
untuk menghancurkan session*/

if(isset($_GET['logout']) == 1) {
	session_unset();
	$_SESSION['FBDATA'] = NULL;
	$_SESSION['LOGGED_IN'] = FALSE;
    $_SESSION['FBID'] = NULL;
    $_SESSION['FULLNAME'] = NULL;
    $_SESSION['BIRTHDAY'] =  NULL;
    $_SESSION['GENDER'] =  NULL;
    $_SESSION['FBPHOTOS'] =  NULL;
    $_SESSION['FBINBOX'] =  NULL;
    $_SESSION['FBWALL'] =  NULL;
    $_SESSION['facebook_access_token'] =  NULL;
	header('Location: '. BASE_URL);
	die();
}

$tombol = "";
/*pengecekan session LOGGED_IN untuk menentukan isi variabel tombol*/
if ( !empty($_SESSION['facebook_access_token']) ) {
	$tombol = BASE_URL . '?logout=1';

    $helper = $fb->getRedirectLoginHelper();
    $photo = $helper->getLoginUrl(BASE_URL . 'fbphoto.php', array('user_photos'));

} else {

    $helper = $fb->getRedirectLoginHelper();
    $tombol = $helper->getLoginUrl(BASE_URL . 'fblogin.php', $scope);
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>uekifoy</title>
        <link rel="shortcut icon" href="http://31.media.tumblr.com/avatar_87544e021365_128.png">
    	<link rel="apple-touch-icon-precomposed" href="http://31.media.tumblr.com/avatar_87544e021365_128.png">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/dist/css/roboto.min.css" rel="stylesheet">
        <link href="assets/dist/css/material-fullpalette.min.css" rel="stylesheet">
        <link href="assets/dist/css/ripples.min.css" rel="stylesheet">
        <link href="assets/Bootstrap-Image-Gallery-3.1.1/css/blueimp-gallery.min.css" rel="stylesheet" >
        <link href="assets/Bootstrap-Image-Gallery-3.1.1/css/bootstrap-image-gallery.min.css" rel="stylesheet" >
        <link href="//fezvrasta.github.io/snackbarjs/dist/snackbar.min.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body{padding-top:50px;background:url('http://static.tumblr.com/gmlcyjd/LdMmwf0o5/plaid.png') repeat #D5E8E8}
            .page-header h1{font-size:4em}
            .bs-component{position:relative}
            .btn{padding:6px 12px;border:0;margin:10px 1px;cursor:pointer;border-radius:2px;text-transform:uppercase;text-decoration:none;text-align:center;color:rgba(255,255,255,.84);transition:background-color .2s ease,box-shadow .28s cubic-bezier(.4,0,.2,1);outline:none!important;vertical-align:middle;touch-action:manipulation;line-height:1.42857143;font-size:24px;white-space:nowrap;display:inline-block;font-weight:400}
            h1{font-family:'Lobster',cursive;font-size:70px;margin-top:0}
            div{-webkit-transition:all 0.3s linear;-moz-transition:all 0.3s linear;-o-transition:all 0.3s linear;transition:all 0.3s linear}
            .image{position:relative;overflow:hidden;height:157px}
            .image img{position:absolute;height:auto;width:auto}
            a.thumbnail{text-decoration:none}
            .bounding-box{background-size:contain;position:absolute;background-position:center;background-repeat:no-repeat;height:200px;width:200px;float:left;margin:5px;box-shadow .28s cubic-bezier(.4,0,.2,1)}
            body.modal-open .container{filter:blur(3px);-webkit-filter:blur(3px);-moz-filter:blur(3px);-o-filter:blur(3px);-ms-filter:blur(3px);filter:url("data:image/svg+xml;utf9,<svg%20version=!string!%20xmlns=!string!><filter%20id=!string!><feGaussianBlur%20stdDeviation=!string!%20/></filter></svg>#blur");filter:progid:DXImageTransform.Microsoft.Blur(PixelRadius='1.1')}
            .modal-backdrop.in{filter:alpha(opacity=0);opacity:0}
            .modal{background:rgba(0,0,0,0.7)}
            .boox{border:1px solid;border-color:#e5e6e9 #dfe0e4 #d0d1d5;-webkit-border-radius:3px;background-color:#fff;margin-bottom:10px;padding:12px;position:relative;word-wrap:break-word}
            .gif{display:none;position:fixed;z-index:9000;top:0;left:0;height:100%;width:100%;background:rgba(255,255,255,1) 
              url('<?php echo BASE_URL ;?>assets/dist/img/loading.gif') 
              50% 50% 
            no-repeat}
            body.loading{overflow:hidden}
            body.loading .gif{display:block}
        </style>
    </head>
    <body>
        <div class="container">
            <div class="bs-docs-section">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 style="text-align:center;">uekifoy</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="bs-component">

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Login with Facebook</h3>
                                </div>
                                <div class="panel-body" style="text-align:center;">
                                	<?php if (!empty($_SESSION['LOGGED_IN'])): ?>
                                		<div class="row">
                    					   <div class="col-md-12" style="padding: 0 auto;text-align: left;">
        	                                	<div class="list-group">
        			                                <div class="list-group-item">
        			                                    <div class="row-picture">
        			                                        <img class="circle" src="https://graph.facebook.com/<?php echo $_SESSION['FBID']; ?>/picture?type=large" alt="icon">
        			                                    </div>
        			                                    <div class="row-content">
        			                                    	<div class="least-content"><?php echo $_SESSION['GENDER']; ?></div>
        			                                        <h4 class="list-group-item-heading"><?php echo $_SESSION['FULLNAME']; ?></h4>
        			                                        <p class="list-group-item-text">
        			                                        <b>App User ID:</b> <?php echo $_SESSION['FBID']; ?><br>
        			                                        <b>Bio:</b> <?php echo $_SESSION['FBDATA']['bio']; ?><br>
        			                                        <b>Birthday:</b> 
                                                            <?php 
                                                                $date = new DateTime($_SESSION['FBDATA']['birthday']->date);
                                                                $result = $date->format('d M Y');
                                                                echo $result;
                                                            ?><br>
        			                                        <b>Status:</b> <?php echo $_SESSION['FBDATA']['relationship_status']; ?><br>
        			                                        <b>Hometown:</b> <?php echo $_SESSION['FBDATA']['hometown']['name']; ?><br>
        			                                        <b>Current Location:</b> <?php echo $_SESSION['FBDATA']['location']['name']; ?><br>
        			                                        <button id="bukafull" class="btn btn-primary btn-xs">Show Full Data<div class="ripple-wrapper"></div></button>
        			                                        <div id="full" style="background:#009688;box-shadow: 0 1px 6px 0 rgba(0,0,0,.12),0 1px 6px 0 rgba(0,0,0,.12); padding:5px; border-radius: 2px; color:white;font-size: 12px; display:none"><b>Other:</b> <?php print_r($_SESSION['FBDATA']); ?></p></div>
        			                                    </div>
        			                                </div>
        			                                <div class="list-group-separator"></div>
        			                            </div>
                                            </div>
			                            </div>
                                        
                                        <?php if (!empty($_SESSION['FBPHOTOS'])): 
                                        ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="links">
                                                <div class="row">
                                                    <?php 
                                                        $graphObject = $_SESSION['FBPHOTOS']; 
                                                        for($i = 0; $i < 8; $i++) { 
                                                    ?>
                                                    <div class="col-sm-3"> 
                                                        <a href="<?php echo $graphObject[$i]['source']; ?>" class="thumbnail" data-gallery>
                                                            <div class="caption"><?php echo isset($graphObject[$i]['name']) == TRUE ? substr($graphObject[$i]['name'],0,25) . '...' : 'Photo'; ?></div>
                                                            <div class="image">
                                                                <img src="<?php echo $graphObject[$i]['source']; ?>" class="img img-responsive full-width" alt="<?php echo isset($graphObject[$i]['name']) == TRUE ? $graphObject['data'][$i]->name : 'Photo'; ?>"  />
                                                            </div>
                                                         </a>
                                                    </div>
                                                    <?php } ?>
                                                    </div>

                                                </div>
                                                
                                                
                                            </div>
                                        </div>
                                        
                                        <?php endif ?>
                                        <form id="form1" name="form1" method="post" action="<?php echo BASE_URL; ?>index.php">
                                        <?php /*if (empty($_SESSION['FBPHOTOS'])):*/ $cekfoto = empty($_SESSION['FBPHOTOS']); ?>
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="<?= $cekfoto ? 'Get Photos' : 'Update Photos';?>"><button type="button" class="btn btn-info" id="btn-photo" data-link="<?php echo $photo; ?>"><i class="mdi-image-photo"></i>  <?= $cekfoto ? 'Get Photos' : 'Update Photos';?></button></span>
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="Get Wall Feed"><button type="button" class="btn btn-primary" id="btn-wall" data-toggle="modal" data-target="#modalwall"><i class="mdi-action-dashboard"></i>  Get Walls</button></span>
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="Get Inbox"><button type="button" class="btn btn-primary" id="btn-inbox" data-toggle="modal" data-target="#modalinbox"><i class="mdi-communication-message"></i>  Get Inbox</button></span>
                                        <span data-toggle="tooltip" data-placement="top" data-original-title="Post via Uekifoy"><button type="button" class="btn btn-primary" id="btn-post" data-toggle="modal" data-target="#modalpost"><i class="mdi-content-send"></i>  Post via..</button></span>
	                                    </form>
                                        <span data-toggle="tooltip" data-placement="top" data-original-title="Logout Now"><button type="button" class="btn btn-info" id="btn-fb" data-link="<?php echo $tombol; ?>"><i class="mdi-navigation-cancel"></i>  Logout Now</button></span>
                                    <?php else: ?>
                                    	<span data-toggle="tooltip" data-placement="top" data-original-title="Login Now"><button type="button" class="btn btn-info" id="btn-fb" data-link="<?php echo $tombol; ?>"><i class="mdi-action-launch"></i>  Login Now</button></span>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
        <div id="blueimp-gallery" class="blueimp-gallery" data-use-bootstrap-modal="false">
            <!-- The container for the modal slides -->
            <div class="slides"></div>
            <!-- Controls for the borderless lightbox -->
            <h3 class="title"></h3>
            <a class="prev">‹</a>
            <a class="next">›</a>
            <a class="close">×</a>
            <a class="play-pause"></a>
            <ol class="indicator"></ol>
            <!-- The modal dialog, which will be used to wrap the lightbox content -->
            <div class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body next"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left prev">
                                <i class="glyphicon glyphicon-chevron-left"></i>
                                Previous
                            </button>
                            <button type="button" class="btn btn-primary next">
                                Next
                                <i class="glyphicon glyphicon-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="modal" id="modalpost">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Post to Wall</h4>
                    </div>
                    <div class="modal-body">
                        <span class="form-pesan"></span>
                        <form id="formpost" action="fbpost.php" method="post">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="form-group">
                                      <textarea class="form-control" id="isi" name="isi" placeholder="What's on your mind ?" style="width: 100%; height: 150px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: none;"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox" id="linl" name="link" value="link" checked=""><span class="toggle"></span> include uekifoy link
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" data-loading-text="Posting..." autocomplete="off" id="submitpost" class="btn btn-primary">Post</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="modalwall">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Read Wall</h4>
                    </div>
                    <div class="modal-body">
                        <span class="form-pesan"></span>
                        <div class="container-fluid">
                            <div class="row" id="here">
                                
                            </div>
                        </div><!-- /.box-body -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="modalinbox">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Read Wall</h4>
                    </div>
                    <div class="modal-body">
                        <span class="form-pesan"></span>
                        <div class="container-fluid">
                           <div class="row" id="hereinbox">
                                
                           </div>
                        </div><!-- /.box-body -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="gif"></div>

        <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function(){

                $('.bs-component [data-toggle="tooltip"]').tooltip();

                $('#bukafull').click(function(){
                	var cek = $('#full').is(":visible"); 
                	if(cek) {
                		$('#full').fadeOut();
                		$(this).html('Show Full Data<div class="ripple-wrapper">');
                	} else {
                		$('#full').fadeIn();
                		$(this).html('Hide Full Data<div class="ripple-wrapper">');
                	}
                	
                });

                $('button.btn-info').click(function(){
                    var options =  {
                        content: "Loading, please wait .....", /*// text of the snackbar*/
                        style: "toast", /*// add a custom class to your snackbar*/
                        timeout: 2500 /*// time in milliseconds after the snackbar autohides, 0 is disabled*/
                    }
                    $.snackbar(options);
                    var link = $(this).data('link');
                    setTimeout(function(){
                        window.location.href = link;
                    }, 2500);
                });

                $('#submitpost').click(function(){
                    var btn = $(this).button('loading');
                    setTimeout(function () {
                        btn.button('reset');
                    }, 2000);
                    $('#formpost').submit();
                });

                $('#formpost').submit(function(){
                    var modal = $('#modalpost');
                    $.ajax({
                        url:"<?=BASE_URL . 'fbpost.php';?>",
                        type:"POST",
                        data:$(this).serialize(),
                        cache: false,
                        success:function(respon){
                            var obj = $.parseJSON(respon);
                            if(obj.status==1){
                                modal.find('.form-pesan').html(pesan_succ(obj.pesan));
                                $('#formpost').trigger('reset');
                                setTimeout(function(){ modal.find('.form-pesan').html('') }, 5000);
                            }else{
                                modal.find('.form-pesan').html(pesan_err(obj.pesan));
                            }
                        }
                    });
                    return false;
                });

                $('#modalwall').on('show.bs.modal', function (e) {
                    var modal = $(this);
                    var el = modal.find('#here');
                    el.empty();
                    $("body").addClass("loading");

                    $.ajax({
                        url: "<?=BASE_URL . 'fbwall.php';?>",
                        cache: false,
                        success: function (data) {
                            var obj = $.parseJSON(data);
                            setTimeout(function(){ $("body").removeClass("loading"); }, 2500);
                            for(var i=0;i<obj.length;i++){
                                /*ini membuat div tiap iterasi*/
                                var arr = obj[i];
                                var isi = '<div class="col-xs-12 boox">' + ( arr['story'] ? arr['story']:arr['name'] ? arr['name'] : "" )
                                        + '<blockquote><p>'+ ( arr['description'] ? arr['description'] :arr['message'] ? arr['message'] : '<img src="'+ arr['picture'] +'" width="50%" />' )  +'</p><small>'+ ( arr['name'] ? arr['name'] : arr['from']['name'] ) +'</small></blockquote>'
                                        + '</div>';
                                el.append(isi);
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                });

                $('#modalinbox').on('show.bs.modal', function (e) {
                    var modal = $(this);
                    var el = modal.find('#hereinbox');
                    el.empty();
                    $("body").addClass("loading");

                    $.ajax({
                        url: "<?=BASE_URL . 'fbinbox.php';?>",
                        cache: false,
                        success: function (data) {
                            var obj = $.parseJSON(data);
                            setTimeout(function(){ $("body").removeClass("loading"); }, 2500);
                            for(var i=0;i<obj.length;i++){
                                /*ini membuat div tiap iterasi*/
                                var arr = obj[i];
                                var isi = '<div class="col-xs-12 boox"><b>Ke:</b>' + arr['ke']
                                        + '<br><p><b>Pesan Terbaru:</b></p><blockquote><p>'+ arr['terakhir_pesan'] +'</p><small>'+ arr['terakhir_user'] +'</small></blockquote>'
                                        + '</div>';
                                el.append(isi);
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                });


            });

        </script>
        <script src="assets/Bootstrap-Image-Gallery-3.1.1/js/jquery.blueimp-gallery.min.js"></script>
        <script src="assets/Bootstrap-Image-Gallery-3.1.1/js/bootstrap-image-gallery.min.js"></script>
        <script src="assets/dist/js/ripples.min.js"></script>
        <script src="assets/dist/js/material.min.js"></script>
        <script src="assets/dist/js/misc.js"></script>
        <script src="//fezvrasta.github.io/snackbarjs/dist/snackbar.min.js"></script>

    </body>
</html>