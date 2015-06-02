<?php
// memulai session
session_start();

// mendefinisikan base_url dinamis
$root = "http://".$_SERVER['HTTP_HOST'];
$root .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
define('base_url',$root);

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
	header('Location: '.base_url);
	die();
}

// pengecekan bila didapat parameter login dari url
// untuk melakukan login - redirect ke fblogin.php
if(isset($_GET['login']) == 1) {
	header('Location: '.base_url."fblogin.php");
}

$tombol = "";
// pengecekan session LOGGED_IN untuk menentukan isi variabel tombol
if ( $_SESSION['LOGGED_IN'] ) {
	$tombol = base_url . '?logout=1';
} else {
	$tombol = base_url . '?login=1';
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
                                	<?php if ($_SESSION['LOGGED_IN']): ?>
                                		<div class="row">
                    					<div style="padding: 0 auto;text-align: left;">
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

            });

        </script>
        <script src="assets/dist/js/ripples.min.js"></script>
        <script src="assets/dist/js/material.min.js"></script>
        <script src="//fezvrasta.github.io/snackbarjs/dist/snackbar.min.js"></script>

    </body>
</html>