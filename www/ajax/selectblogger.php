<?php

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

$success = 1;
$error = array();
$response = array('status' => 'success');

if(isset($_POST['blogger']) && $_POST['blogger'] != '' && isset($_POST['blog_email']) && $_POST['blog_email'] != '') {

	try {
		$m = new Mongo('localhost', array('persist' => 'x'));
		$db = $m->montreal;
		$db->authenticate('montreal', 'letmein!');
	} catch (MongoException $e) {
		$error[] = $e->getMessage();
		$error['status'] = 'fail';
		$success = 0;
	}

	if($success == 1) {
		try {
			$existUser = $db->users->count(array('email' => $_POST['blog_email']));
			if($existUser < 1) {
				$error[] = "Sorry, We couldn't find your email address in the database, please refresh this page and try again.";
				$response['status'] = "fail";
				$success = 0;
			}
		} catch (MongoException $e) {
			$error[] = $e->getMessage();
			$success = 0;
		}
	}

	$crit = array('email' => $_POST['blog_email']);
	$newobj = array('$set' => array('blogger' => $_POST['blogger']));

	if($success == 1) {
		try {
			$db->users->update($crit, $newobj);

			$response['email'] = $_POST['blog_email'];
			$response['blogger'] = $_POST['blogger'];
		} catch (MongoException $e) {
			$error[] = $e->getMessage();
			$response['status'] = 'fail';
		}
	} else {
		$error[] = 'Failed before we tried';
		$response['status'] = 'fail';
	}
	
} else {
	$error[] = "We didn't receive your choice of blogger, please try again";
	$response['status'] = 'fail';
}

$response['errors'] = $error;
echo json_encode($response);

?>
