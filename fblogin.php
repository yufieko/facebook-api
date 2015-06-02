<?php
// memulai session
session_start();

// mendefinisikan base_url dinamis
$root = "http://".$_SERVER['HTTP_HOST'];
$root .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
define('base_url',$root);

// load file autoload untuk meload file Facebook SDK
require __DIR__ . '/autoload.php';

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
FacebookSession::setDefaultApplication( 'APP_ID','SECRET_ID' );

// login helper dengan redirect_uri
$helper = new FacebookRedirectLoginHelper(base_url . "fblogin.php");

try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}

if ( isset( $session ) ) {
  // graph api melakukan permintaan untuk user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // mendapatkan respon
  $graphObject = $response->getGraphObject();
  $_SESSION['FBID'] = $graphObject->getProperty('id');
  $_SESSION['FULLNAME'] = (string) $graphObject->getProperty('name');
  $_SESSION['GENDER'] = (string) strtoupper(substr($graphObject->getProperty('gender'),0)); 
  $_SESSION['LOGGED_IN'] = TRUE;
  $_SESSION['FBDATA'] = $graphObject->asArray();

  // melakukan auto post jika berhasil login
  try {
    $postLink = (new FacebookRequest(
      $session, 'POST', '/me/feed', array(
        'link' => 'http://uekifoy.tumblr.com',
        'message' => 'uekifoy tumblr - uekifoy.dev'
      )
    ))->execute()->getGraphObject();
    echo "Posted with id: " . $postLink->getProperty('id');
  } catch(FacebookRequestException $e) {
    echo "Exception occured, code: " . $e->getCode();
    echo " with message: " . $e->getMessage();
  }

  // redirect ke base url
  header("Location: ".base_url);
} else {
  // show login url
  // 'scope' => 'publish_actions, public_profile, email, user_about_me, read_stream',
  $params = array(
    'scope' => 'publish_actions, public_profile, email, user_about_me, user_birthday, user_hometown, user_location, user_relationships',
  );
  $loginUrl = $helper->getLoginUrl($params);
  header("Location: ".$loginUrl);
  // echo "login ".$tombol;
}

?>