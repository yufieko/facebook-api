<?php
// memulai session
session_start();

// load file autoload untuk meload file Facebook SDK
require __DIR__ . '/autoload.php';
require_once 'config.php';

// load namespace Facebook SDK untuk PHP
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;

// pengecekan bila didapat parameter logout dari url
// untuk menghancurkan session

if(isset($_GET['logout']) == 1) {
	session_unset();
	$_SESSION['FBDATA'] = NULL;
	$_SESSION['LOGGED_IN'] = FALSE;
    $_SESSION['FBID'] = NULL;
    $_SESSION['FULLNAME'] = NULL;
    $_SESSION['BIRTHDAY'] =  NULL;
    $_SESSION['GENDER'] =  NULL;
    $_SESSION['FBPHOTOS'] =  NULL;
	header('Location: '. BASE_URL);
	die();
}

// pengecekan bila didapat parameter login dari url
// untuk melakukan login - redirect ke fblogin.php
// if(isset($_GET['login']) == 1) {
    
//     //header('Location: '.BASE_URL."fblogin.php");
// }

$tombol = "";
// pengecekan session LOGGED_IN untuk menentukan isi variabel tombol
if ( !empty($_SESSION['LOGGED_IN']) ) {
	$tombol = BASE_URL . '?logout=1';

    $helper = new FacebookRedirectLoginHelper(BASE_URL . 'fbphoto.php',APP_ID,APP_SECRET);
    $params = array(
        'scope' => 'user_photos',
    );
    $tombolfoto = $helper->getLoginUrl($params);
} else {
	//$tombol = BASE_URL . '?login=1';
    $helper = new FacebookRedirectLoginHelper(BASE_URL . 'fblogin.php',APP_ID,APP_SECRET);
    $params = array(
        'scope' => APP_SCOPE,
    );
    $tombol = $helper->getLoginUrl($params);
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
            body{padding-top:50px; background: url('http://static.tumblr.com/gmlcyjd/LdMmwf0o5/plaid.png') repeat #D5E8E8;}
            .page-header h1{font-size:4em}
            .bs-component{position:relative}
            .btn {padding: 6px 12px;border: 0;margin: 10px 1px;cursor: pointer;border-radius: 2px;text-transform: uppercase;
			  text-decoration: none;text-align: center;color: rgba(255,255,255,.84);
			  transition: background-color .2s ease,box-shadow .28s cubic-bezier(.4,0,.2,1);outline: none!important;
			  vertical-align: middle;touch-action: manipulation;line-height: 1.42857143;font-size: 24px;
			  white-space: nowrap;display: inline-block;font-weight: 400;}
			h1 {font-family: 'Lobster', cursive;font-size: 70px;margin-top: 0;}
			div {-webkit-transition: all 0.3s linear;-moz-transition: all 0.3s linear;-o-transition: all 0.3s linear;transition: all 0.3s linear;}
            .image{
    position:relative;
    overflow:hidden;
      height: 157px;
}
.image img{
    position:absolute;
      height: auto;
  width: auto;
}
a.thumbnail { text-decoration: none;}
            .bounding-box { background-size: contain; position: absolute; background-position: center; background-repeat: no-repeat; height: 200px; width: 200px; float:left; margin:5px; 
                box-shadow .28s cubic-bezier(.4,0,.2,1);}
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
        			                                        <b>Birthday:</b> <?php echo $_SESSION['FBDATA']['birthday']; ?><br>
        			                                        <b>Status:</b> <?php echo $_SESSION['FBDATA']['relationship_status']; ?><br>
        			                                        <b>Hometown:</b> <?php echo $_SESSION['FBDATA']['hometown']->name; ?><br>
        			                                        <b>Current Location:</b> <?php echo $_SESSION['FBDATA']['location']->name; ?><br>
        			                                        <button id="bukafull" class="btn btn-primary btn-xs">Show Full Data<div class="ripple-wrapper"></div></button>
        			                                        <div id="full" style="background:#009688;box-shadow: 0 1px 6px 0 rgba(0,0,0,.12),0 1px 6px 0 rgba(0,0,0,.12); padding:5px; border-radius: 2px; color:white;font-size: 12px; display:none"><b>Other:</b> <?php print_r($_SESSION['FBDATA']); ?></p></div>
        			                                    </div>
        			                                </div>
        			                                <div class="list-group-separator"></div>
        			                            </div>
                                            </div>
			                            </div>
                                        
                                        <?php if (!empty($_SESSION['FBPHOTOS'])): ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="links">
                                                <div class="row">
                                                    <?php 
                                                        $graphObject = $_SESSION['FBPHOTOS']; 
                                                        for($i = 0; $i < 8; $i++) { 
                                                    ?>
                                                    <div class="col-sm-3"> 
                                                        <a href="<?php echo $graphObject['data'][$i]->source; ?>" class="thumbnail" data-gallery>
                                                            <div class="caption"><?php echo isset($graphObject['data'][$i]->name) == TRUE ? substr($graphObject['data'][$i]->name,0,25) . '...' : 'Photo'; ?></div>
                                                            <div class="image">
                                                                <img src="<?php echo $graphObject['data'][$i]->source; ?>" class="img img-responsive full-width" alt="<?php echo isset($graphObject['data'][$i]->name) == TRUE ? $graphObject['data'][$i]->name : 'Photo'; ?>"  />
                                                            </div>
                                                         </a>
                                                    </div>
                                                    <?php } ?>
                                                    </div>

                                                </div>
                                                
                                                    <?php //var_dump($_SESSION['FBPHOTOS']); ?>
                                                    
                                                    
                                                        <!-- <div class="col-sm-3 bounding-box" style="background-image:url('<?php echo $graphObject['data'][$i]->source; ?>');"><a href="<?php echo $graphObject['data'][$i]->source; ?>" title="<?php echo isset($graphObject['data'][$i]->name) == TRUE ? $graphObject['data'][$i]->name : 'Photo'; ?>" data-gallery></a></div> -->

                                                    
                                                    
                                                
                                                
                                            </div>
                                        </div>
                                        <?php else: ?>
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="Get Photos"><button type="button" class="btn btn-info" id="btn-photo" data-toggle="snackbar"><i class="mdi-image-photo"></i>  Get Photos</button></span>
                                        <?php endif ?>
	                                    <span data-toggle="tooltip" data-placement="top" data-original-title="Logout Now"><button type="button" class="btn btn-info" id="btn-fb" data-toggle="snackbar"><i class="mdi-navigation-cancel"></i>  Logout Now</button></span>
                                    <?php else: ?>
                                    	<span data-toggle="tooltip" data-placement="top" data-original-title="Login Now"><button type="button" class="btn btn-info" id="btn-fb" data-toggle="snackbar"><i class="mdi-action-launch"></i>  Login Now</button></span>
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

                $('#btn-fb').click(function(){
                	var options =  {
					    content: "Loading, please wait .....", // text of the snackbar
					    style: "toast", // add a custom class to your snackbar
					    timeout: 2500 // time in milliseconds after the snackbar autohides, 0 is disabled
					}
					$.snackbar(options);

		            setTimeout(function(){ 
		              window.location.href = "<?=$tombol?>";
		            }, 2500);
		            return false;
		        });

                <?php if (!empty($_SESSION['LOGGED_IN'])): ?>
                $('#btn-photo').click(function(){
                    var options =  {
                        content: "Loading, please wait .....", // text of the snackbar
                        style: "toast", // add a custom class to your snackbar
                        timeout: 2500 // time in milliseconds after the snackbar autohides, 0 is disabled
                    }
                    $.snackbar(options);

                    setTimeout(function(){ 
                      window.location.href = "<?=$tombolfoto?>";
                    }, 2500);
                    return false;
                });
                <?php endif ?>

            });

        </script>
        <script src="assets/Bootstrap-Image-Gallery-3.1.1/js/jquery.blueimp-gallery.min.js"></script>
        <script src="assets/Bootstrap-Image-Gallery-3.1.1/js/bootstrap-image-gallery.min.js"></script>
        <script src="assets/dist/js/ripples.min.js"></script>
        <script src="assets/dist/js/material.min.js"></script>
        <script src="//fezvrasta.github.io/snackbarjs/dist/snackbar.min.js"></script>

    </body>
</html>