<?php
/*memulai session*/
session_start();

/*load file autoload untuk meload file Facebook SDK
require __DIR__ . '/autoload.php';
ganti pakai composer*/
require_once __DIR__ . '/vendor/autoload.php';
require_once 'config.php';

/*load namespace Facebook SDK untuk PHP
gak perlu lagi*/

/*inisialisasi applikasi dengan app id dan secret id
FacebookSession::setDefaultApplication( APP_ID, APP_SECRET );*/
$fb = new Facebook\Facebook([
  'app_id' => APP_ID,
  'app_secret' => APP_SECRET,
  'default_graph_version' => 'v2.2',
]);

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
    $response = $fb->get('/me/photos?fields=source,picture,name', $accessToken);
    $_SESSION['FBPHOTOS'] = $response->getGraphEdge()->asArray();
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }

  

  /*redirect ke base url*/
  header("Location: ". BASE_URL);
} elseif ($helper->getError()) {
  /*The user denied the request*/
  exit;
}


?>