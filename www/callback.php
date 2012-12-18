<?php
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);
require_once('../config.php');

if(isset($_GET['error'])) {
	echo "There was an issue with authorization to Instagram.";
	echo "Reason: " . $_GET['error_reason'];
	echo "Description: " . $_GET['error_description'];
	return false;
}

if(isset($_GET['code'])) {
	$url = 'https://api.instagram.com/oauth/access_token';
	$auth_info = array(
					'code'=>$_GET['code'],
					'client_id'=>'b8767acf56534c6bb1224c65e57ba11e',
					'client_secret'=>'e9d95ef4a5fa48ee9a3a9b53c9871d34',
					'grant_type'=>'authorization_code',
					'redirect_uri'=>'http://dev.telenova.ca/spinstagram/www/callback.php');

	$auth_info = http_build_query($auth_info);
	$len = strlen($auth_info);
	$auth_response = file_get_contents($url, false, stream_context_create(array(
				'http'=>array(
					'method'=>'POST',
					'header'=>"Connection: close\r\nContent-Length: $len\r\nContent-Type: application/x-www-form-urlencoded",
					'content'=>$auth_info))));

	if(!$auth_response) {
		echo "Auth Didn't Work.";
		return false;
	}

	$auth = json_decode($auth_response);

	$user = array(
		'username'=>$auth->user->username,
		'profile_picture'=>$auth->user->profile_picture,
		'full_name'=>$auth->user->full_name,
		'igid'=>$auth->user->id,
		'access_token'=>$auth->access_token);

	$db->insert('users',$user);

	echo $user;

}

?>
