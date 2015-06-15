<?php

// facebook app
define('APP_ID','APP ID');
define('APP_SECRET', 'APP SECRET');
$scope = 'publish_actions, public_profile, email, user_photos, user_about_me, user_birthday, user_hometown, user_location, user_relationships';
define('APP_SCOPE', $scope);
// mendefinisikan base_url dinamis
$root = "http://".$_SERVER['HTTP_HOST'];
$root .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
define('BASE_URL',$root);

?>