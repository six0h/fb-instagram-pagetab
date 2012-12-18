<?php
//ini_set('display_errors', 1);
//error_Reporting('E_ALL');

if(isset($_GET['hub_challenge'])) {
	$challenge = $_GET['hub_challenge'];
	echo $challenge;
	exit();
}

require_once('../config.php');

if(isset($_GET['action']) && $_GET['action'] == 'add' && $_GET['type'] == 'tag' && isset($_GET['tag_id'])) {
	$url = 'https://api.instagram.com/v1/subscriptions/';
	$auth_info = array(
					'client_id'=>'b8767acf56534c6bb1224c65e57ba11e',
					'client_secret'=>'e9d95ef4a5fa48ee9a3a9b53c9871d34',
					'callback_url'=>'http://dev.telenova.ca/spinstagram/www/subCallback.php',
					'object'=>'tag',
					'object_id'=>$_GET['tag_id'],
					'aspect'=>'media');
}

if(isset($_GET['action']) && $_GET['action'] == 'add' && $_GET['type'] == 'user') {
	$url = 'https://api.instagram.com/v1/subscriptions/';
	$auth_info = array(
					'client_id'=>'b8767acf56534c6bb1224c65e57ba11e',
					'client_secret'=>'e9d95ef4a5fa48ee9a3a9b53c9871d34',
					'callback_url'=>'http://dev.telenova.ca/spinstagram/www/subCallback.php',
					'object'=>'user',
					'aspect'=>'media');
}

if(isset($_GET['action'])) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $auth_info);
	$auth_response = curl_exec($ch);
	curl_close($ch);

	if(!$auth_response) {
		echo "subscription failed";
		return false;
	}

	if(isset($auth_response)) {
		echo "<pre>";
		print_r(json_decode($auth_response));
		echo "</pre>";

		$db->insert('subscriptions', json_decode($auth_response));
	}
}

if(isset($_POST) && !isset($_GET['hub_challenge'])) {

	$POST = json_decode(file_get_contents('php://input'));

	$data = "\r\n";
	$file = fopen('./update.txt', 'a');
	$data .= date('m d Y H:i:s') . ' - ' . $data;
	fwrite($file, $data);
	fclose($file);
	
	foreach($POST as $update) {
		switch($update->object) {
			case 'tag':

				$url = 'https://api.instagram.com/v1/tags/sunpeaks360/media/recent?client_id=b8767acf56534c6bb1224c65e57ba11e&count=1';
				$type = 'tag';

			break;

			case 'user':

				$url = 'https://api.instagram.com/v1/users/188266179/media/recent?access_token=188266179.b8767ac.fa6f0d3fce0440b38366343f07401d06&count=1';
				$type = 'user';

			break;
		}

		$data = json_decode(file_get_contents($url));

		foreach($data->data as $item) {

			if(is_object($item)) {

				$exists = $db->count('photos',array('id'=>$item->id));
				
				if($exists == 0) {
					$insert = array(
							'user'=> $item->user->username,
							'id'=> $item->id,
							'profile_pic'=> $item->user->profile_picture,
							'type'=> $type,
							'link'=>$item->link,
							'images'=> array(
								'std'=>$item->images->standard_resolution->url,
								'thm'=>$item->images->thumbnail->url,
								'low'=>$item->images->low_resolution->url));

					if(isset($item->caption->text)) {
						$insert['text'] = $item->caption->text;
					} else {
						$insert['text'] = '';
					}

					$db->insert('photos', $insert);
				}
			}

		}
	}
}
?>