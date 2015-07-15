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

$accessToken = $_SESSION['facebook_access_token'];

if ( isset($accessToken) ) {      
  try {
    /*Returns a `Facebook\FacebookResponse` object*/
    $response = $fb->get('/me/feed?fields=id,application,caption,created_time,description,from,icon,message,name,picture,story', $accessToken);
    $graphNode = $response->getGraphEdge()->asArray();
    
    echo json_encode($graphNode);

  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    $status['pesan'] = 'Graph returned an error: ' . $e->getMessage();
    $status['status'] = 0;
    /*exit;*/
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    $status['pesan'] = 'Facebook SDK returned an error: ' . $e->getMessage();
    $status['status'] = 0;
    /*exit;*/
  }

} else {
  $status['pesan'] = 'You are not logged in';
  $status['status'] = 0;
}

echo json_encode($status);

?>