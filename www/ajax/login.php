<?php

require_once('../../config.php');

$success = 1;
$error = array();
$response = array();

if(isset($_POST['user_login'])) {
	if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['session_id'])) {
		$session_id = $_POST['session_id'];
		if(!isset($data['email'])) {
			$user = User::check_login($_POST['email'],$_POST['password']);
			if(isset($user) && $user != false) :
				$data = $session->read($session_id);
				$data['email'] = $user->get_email();
				$data['first_name'] = $user->get_first_name();
				$session->write($session_id,$data);
			else :
				$data['email'] = '';
				$success = 0;
				$error[] = 'Username and Password Incorrect';
			endif;
		}
	} else {
		$success = 0;
		$error[] = 'Missing username or password, please try again.';
	}

	if($success == 1) {
		$response = array(
			'status' => 'success',
			'email' => $data['email'],
			'first_name' => $data['first_name']);
		echo json_encode($response);
	} else {
		$response = array(
			'status' => 'fail',
			'errors' => $error,
			'email' => '',
			'first_name' => '');
		echo json_encode($response);
	}
}

?>
