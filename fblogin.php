<?php
/*memulai session*/
session_start();

/*load file autoload untuk meload file Facebook SDK
require __DIR__ . '/autoload.php';
ganti pakai composer*/
require_once __DIR__ . '/vendor/autoload.php';
require_once 'config.php';

/*load namespace Facebook SDK untuk PHP
gak perlu lagi
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
use Facebook\HttpClients\FacebookHttpable;*/

/*inisialisasi applikasi dengan app id dan secret id
FacebookSession::setDefaultApplication( APP_ID, APP_SECRET );*/
$fb = new Facebook\Facebook([
  'app_id' => APP_ID,
  'app_secret' => APP_SECRET,
  'default_graph_version' => 'v2.2',
]);

/*login helper dengan redirect_uri
$helper = new FacebookRedirectLoginHelper(BASE_URL . 'fblogin.php');*/

$helper = $fb->getRedirectLoginHelper();
try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  /*When Graph returns an error*/
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  /*When validation fails or other local issues*/
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}


if ( isset($accessToken) ) {
  /*graph api melakukan permintaan untuk user data*/
  try {
    /*Returns a `Facebook\FacebookResponse` object*/
    $response = $fb->get('/me', $accessToken);
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }

  $user = $response->getGraphObject();
  /*mendapatkan respon*/
  $_SESSION['facebook_access_token'] = (string) $accessToken;
  $_SESSION['FBID'] = $user->getProperty('id');
  $_SESSION['FULLNAME'] = (string) $user->getProperty('name');
  $_SESSION['GENDER'] = (string) strtoupper(substr($user->getProperty('gender'),0)); 
  $_SESSION['LOGGED_IN'] = TRUE;
  $_SESSION['FBDATA'] = $user->asArray();

  /*redirect ke base url*/
  header("Location: ". BASE_URL);
} elseif ($helper->getError()) {
  /*The user denied the request*/
  exit;
}


?>