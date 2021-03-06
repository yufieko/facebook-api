<?php
/*memulai session*/
session_start();

/*require __DIR__ . '/autoload.php';
load file autoload untuk meload file Facebook SDK
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
$status['pesan'] = "";
$status['status'] = "";

if ( isset($accessToken) ) {
  $cekisi = isset($_POST['isi']);
/*  var_dump($_POST);
  die();*/

  if( $cekisi ) {
    $isi = $_POST['isi'];
    $ceklink = isset($_POST['link']);
    
    if(strlen(trim($isi)) == 0 || empty($isi)) {
      $status['pesan'] = 'Please fill the content';
      $status['status'] = 0;
    } else {
      if($ceklink) {
        $linkData = [
          'link' => BASE_URL,
          'message' => htmlspecialchars($isi),
        ];
      } else {
        $linkData = [
          'message' => htmlspecialchars($isi),
        ];
      }
      
      try {
        /*Returns a `Facebook\FacebookResponse` object*/
        $response = $fb->post('/me/feed', $linkData, $accessToken);
        $graphNode = $response->getGraphNode();
        
        $status['pesan'] = 'Posted with id: ' . $graphNode['id'];
        /*$status['pesan'] = 'Posted with id: ' . var_dump($isi);*/
        $status['status'] = 1;

      } catch(Facebook\Exceptions\FacebookResponseException $e) {
        $status['pesan'] = 'Graph returned an error: ' . $e->getMessage();
        $status['status'] = 0;
        /*exit;*/
      } catch(Facebook\Exceptions\FacebookSDKException $e) {
        $status['pesan'] = 'Facebook SDK returned an error: ' . $e->getMessage();
        $status['status'] = 0;
        /*exit;*/
      }
    }
    
  } else {
    $status['pesan'] = 'Please fill the content';
    $status['status'] = 0;
  }

} else {
  $status['pesan'] = 'You are not logged in';
  $status['status'] = 0;
}

echo json_encode($status);

?>