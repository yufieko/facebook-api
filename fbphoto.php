<?php
// memulai session
session_start();

// load file autoload untuk meload file Facebook SDK
require __DIR__ . '/autoload.php';
require_once 'config.php';

// load namespace Facebook SDK untuk PHP
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

// inisialisasi applikasi dengan app id dan secret id
FacebookSession::setDefaultApplication( APP_ID, APP_SECRET );

// login helper dengan redirect_uri
$helper = new FacebookRedirectLoginHelper(BASE_URL . 'fbphoto.php');

try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}

if ( isset($session) ) {
  // graph api melakukan permintaan untuk user data - foto
  $request = new FacebookRequest( $session, 'GET', '/me/photos?fields=source,picture,name' );
  $response = $request->execute();
  // mendapatkan respon
  $_SESSION['FBPHOTOS'] = $response->getGraphObject()->asArray();
  // redirect ke base url
  header("Location: ". BASE_URL);
} 

?>