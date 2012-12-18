<?php

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

require_once('../../config.php');

$response = array('status'=>200);
$error = array();

if(isset($_POST['first_name'])
&& isset($_POST['last_name'])
&& isset($_POST['email'])
&& isset($_POST['hometown'])
&& isset($_POST['province'])
&& isset($_POST['agree'])) {

	if(isset($_POST['confirm']) && $_POST['confirm'] == 'false') {
		$confirm = false;
	} else {
		$confirm = true;
	}
	$response['confirm'] = $confirm;
	// CHECK IF USER EXISTS ALREADY
	try {
		$existUser = $db->count('users', array('email' => $_POST['email']));
	} catch (MongoException $e) {
		$error[] = $e->getMessage();
		$response['status'] = 500;
	}

	if($existUser > 0) {
		$error[] = "You are already registered with us, we'll add this file to your existing collection.\n";
		$response['status'] = 200;
	}

	($_POST['news'] == 'on') ? $news = 1 : $news = 0;

	$user = array(
		'first_name' => $_POST['first_name'],
		'last_name' => $_POST['last_name'],
		'email' => $_POST['email'],
		'province' => $_POST['province'],
		'agree' => $_POST['agree'],
		'news' => $news,
		'date' => new MongoDate(),
		'ip' => $_POST['ip'],
		'agent' => $_POST['agent']
	);

	switch($_POST['type']) {
		case 'photo':
			$upload_dir = UPLOAD_PATH . 'photos';
			$size_limit = 10485760;
		break;

		case 'music':
			$upload_dir = UPLOAD_PATH . 'sounds';
			$size_limit = 26214400;
		break;

		case 'video':
			$upload_dir = UPLOAD_PATH . 'videos';
			$size_limit = 104857600;
		break;
	}
			
	$uploadTo = $upload_dir . '/' . $user['email'] . '-' . $_FILES['clip']['name'];
	$fileCheck = $db->count('files', array('file'=>$uploadTo));
	
	if($_FILES['clip']['size'] > $size_limit) {
		$response['status'] = 500;
		$error[] = 'Your file is too large, please scale it down.';
		$sizeCheck = '1';
	}

	if($fileCheck > 0 && $confirm == false && !isset($sizeCheck)) {
		$response['status'] = 502;
		$error[] = "You have already uploaded this file. Click Submit again to overwrite it, otherwise, please upload a different file.";
	} elseif ($fileCheck > 0 && $confirm == true) {
		$myfile = move_uploaded_file($_FILES['clip']['tmp_name'], $uploadTo); 
		if(!$myfile) {
			$response['status'] = 500;
			$error[] = 'Could not upload file';
		}
	}

	if($response['status'] == 200 && $existUser == 0) {
		try {
			$db->insert('users', $user);
		} catch (MongoException $e) {
			$error[] = 'Could not insert user';
			$response['status'] = 500;
		}

	}

	if($response['status'] == 200 && $fileCheck == 0) {
		try {
			$fileInsert = array('email'=>$user['email'],'file'=>$uploadTo,'type'=>$_POST['type']);
			$db->insert('files', $fileInsert);
		} catch (MongoException $e) {
			$error[] = $e->getMessage();
			$response['status'] = 500;
		}
	}
	
} else {
	$error[] = 'You did not fill out all necessary fields';
	$response['status'] = 500;
}

$response['error'] = $error;
$response['email'] = $_POST['email'];
echo json_encode($response);

?>
