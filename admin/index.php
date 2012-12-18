<?php
session_start();
require_once('../config.php');

if(isset($_GET['debug'])) {
	$debug = 1;
}

$data = $session->read(session_id());
$session->write(session_id(), $data);

if(isset($_GET['logout']) == 1) {
	unset($data);
	$session->destroy(session_id());
	header('Location: '.$_SERVER['PHP_SELF']);
}

if(isset($data['email']))
	$user = User::find_by_email($data['email']);


?>

<!DOCTYPE html>
<html>
<head>

	<title><?=$site['name'];?> - Admin Panel</title>
	<meta charset="utf-8">

	<script src="js/html5shiv.js"></script>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/fonts.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/jquery.fancybox.css">

</head>

<body>

<header>

	<div id="login-box">
		<h2>
		<?php
		if(isset($user)) { 
			($user->get_first_name() != '') ? $name = $user->get_first_name()
							: $name = $user->get_email();
			?>
			Welcome, <?=$name;?> (<a href="<?=$_SERVER['PHP_SELF']?>?logout=1">Logout</a>)</div>		
		<?php } else { ?>
			<a href="#login_modal" class="modal_link">Login</a> to use admin panel.
		<?php } ?>
		</h2>
	</div>

	<h1><?=$site['name'];?></h1>
	<span>Admin Panel</span>

	<br class="clear" />
	
</header>
<?php
if(isset($debug) && $debug == 1) {
	print_r($session);
	echo "<br>\r\n";
	print_r($data);
}

if(isset($user)) {

echo '<section id="main">';

(!isset($_GET['p']))? $p = 'users' : $p = $_GET['p'];
switch($p) {
	case 'users':

		require_once('users.php');

	break;

	default:
		require_once('users.php');
}

echo '</section>';

} else {
	echo "<div class='error'>You must login to view the panel</div>";
?>
	<div class="error"></div>
	<div id="login_modal" class="modal">
		<span class="modalarrow"></span>
		<h3>Login</h3>
		<form method="POST" action="../www/ajax/login.php" id="login_form">
			<fieldset>
				<input type="text" name="email" id="email" value="E-Mail Address">
				<input type="password" name="password" id="password" value="Password">
			</fieldset>
			<fieldset>
				<a href="#forgot">Forgot Your Password?</a>
				<input type="submit" name="user_login" id="user_login" class="submit" value="Login">
				<input type="hidden" name="session_id" id="session_id" value="<?=session_id();?>">
			</fieldset>
		</form>
		<span class="close"><a href="#">Close</a></span>
	</div>
<?php
}
?>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script src="js/jquery.form.js"></script>
<script src="js/jquery.fancybox.pack.js"></script>
<script src="js/_site.js"></script>
<?php
?>
</body>
</html>
