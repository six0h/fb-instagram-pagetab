<?php

require_once('../config.php');

$errors = array();

// ----- EDIT USER SECTION ----- //
if(isset($_POST['editUser'])) {
	$message	= array();
	$success	= 1;
	$userId		= $_POST['userId'];
	$userFirstName	= $_POST['userFirstName'];
	$userLastName	= $_POST['userLastName'];
	$userEmail	= $_POST['userEmail'];
	$userAdmin	= $_POST['userAdmin'];
	$userPass	= $_POST['userPass'];

	($userAdmin == 'on') ? $userAdmin = 1 : $userAdmin = 0;
	if(!isset($userId) || $userId == '') die('No user given to edit');
	if(!isset($userFirstName) || $userFirstName == '') die('Please give the user a first name');
	if(!isset($userLastName) || $userLastName == '') die('Please give the user a last name');


	if($userPass != '') {
		$crit = array('email' => $userEmail);
		$update = array('$set' => array('password' => md5($userPass)));
		try {
			$db->update('users',$crit,$update);
		} catch (Exception $e) {
			$success = 0;
			$errors[] = $e->getMessage();
		}
	}

	$crit = array('_id' => new MongoId($userId));
	$data = array('$set' => array(
		'first_name' => $userFirstName,
		'last_name' => $userLastName,
		'email' => $userEmail,
		'admin' => $userAdmin));

	try {
		$db->update('users',$crit,$data);
	} catch (Exception $e) {
		$success = 0;
		$errors[] = $e->getMessage();
	}

	if ($success > 0) {
		$errors[] = "Successfully Edited User";
	} else {
		$errors[] = "Could not edit user, try again later";
	}

	foreach($errors as $output) echo "<div class='notify'>".$output."</div>";

}
if(isset($_GET['edit']) && isset($_GET['id']) && $_GET['id'] != '') {
	$id = new MongoId($_GET['id']);
	$crit = array('_id'=>$id);
	$user = $db->select('users',$crit);
	foreach($user as $u) {
		$first_name = $u['first_name'];
		$last_name = $u['last_name'];
		$email = $u['email'];
		if(!isset($u['admin'])) {
			$admin = 0;
		} else {
			$admin = $u['admin'];
		}

	}
	($admin == '1')
		?$checked = 'CHECKED'
		:$checked = '';
?>
	
	<div class="loginBox">
		<h3>Edit User</h3>
		<form method="POST" action="<?=$_SERVER['PHP_SELF'];?>?p=users" id="editForm">
		<div id="userFirstNameDiv"><label for="userFirstName">First Name</label><input type="text" id="userFirstName" name="userFirstName" value="<?=$first_name?>" /></div>
		<div id="userLastNameDiv"><label for="userLastName">Last Name</label><input type="text" id="userLastName" name="userLastName" value="<?=$last_name?>" /></div>
		<div id="userEmailDiv"><label for="userEmail">Email</label><input type="text" id="userEmail" name="userEmail" value="<?=$email?>" /></div>
		<div id="userPassDiv"><label for="userPass">Password</label><input type="text" id="userPass" name="userPass" value="" /></div>
		<div id="userAdminDiv"><label for="userAdmin">Admin</label><input type="checkbox" id="userAdmin" name="userAdmin" <?=$checked?> /></div>
		<div id="userSubmitDiv"><input type="submit" name="editUser" id="editUser" /></div>
		<input type="hidden" name="userId" id="userId" value="<?=$id?>" />
		</form>
	</div>
<?php 
}
// ----- END EDIT USER SECTION ----- //

// ----- DELETE USER SECTION ----- //

if(isset($_GET['delete']) && $_GET['id'] != '') {
	$success = 1;
	try {
		$db->remove('users', array('_id' => new MongoId($_GET['id'])));
	} catch (MongoException $e) {
		$errors[] = $e->getMessage();
		$success = 0;
	}

	if($success == 1) {
		$errors[] = "Successfully Deleted User";
	} 

	if(isset($errors)) { foreach($errors as $error) echo "<div class='error'>".$error."</div>"; }
	
}

// ----- END USER DELETE SECTION ----- //

// ----- ADD USER SECTION ----- //

if(isset($_POST['addUser']) && $_POST['userEmail'] != '') {

	$success	= 1;
	$message	= array();
	$userEmail	= $_POST['userEmail'];
	$userFirstName	= $_POST['userFirstName'];
	$userLastName	= $_POST['userLastName'];
	$userAdmin	= $_POST['userAdmin'];
	$userPass	= $_POST['userPass'];
	$userPass2	= $_POST['userPass2'];
	$userDate 	= new MongoDate();

	if($userPass != $userPass2) {
		$errors[] = 'Passwords Do Not Match';
		$success = '0';
	}

	if(isset($userAdmin)) {
		$userAdmin = 1;
	}

	// CHECK FOR EXISTING EMAIL ADDRESS
	$crit = array('email' => $userEmail);
	$numRows = $db->count('users',$crit);
	if($numRows > 0) { // IF EMAIL ALREADY EXISTS
		$errors[] = 'Email address exists already';
		$success = 0;
	} else { // IF EMAIL DOESNT ALREADY EXIST
		if($success > 0) { // IF PROGRAM HASNT FAILED YET, ADD USER
			$crit = array(
				'first_name' => $userFirstName,
				'last_name' => $userLastName,
				'email' => $userEmail,
				'date' => $userDate);
			if(isset($userAdmin)) $crit['admin'] = $userAdmin;
			$db->insert('users',$crit);
		}
	}

	if($success == 1) { // IF SUCCESSFULLY ADDED
		$errors[] = "User Successfully Added";
		
	}

	foreach($errors as $output) echo "<div class='notify'>".$output."</div>";

}

if(isset($_GET['add']) || isset($_POST['tryadd'])) {

	(isset($_GET['admin'])) ? $checked = 'CHECKED' : $checked = '';

?>
	<div class="modal" id="add_user">
		<a href="<?=$_SERVER['PHP_SELF']; ?>" class="close" />Close</a>
		<h3>Add User</h3>
		<form method="POST" action="<?=$_SERVER['PHP_SELF'];?>?p=users" id="addForm">
		<div id="userFirstNameDiv"><label for="userFirstName">First Name</label><input type="text" id="userFirstName" name="userFirstName" value="<?=$userFirstName;?>" /></div>
		<div id="userLastNameDiv"><label for="userLastName">Last Name</label><input type="text" id="userLastName" name="userLastName" value="<?=$userLastName;?>" /></div>
		<div id="userEmailDiv"><label for="userEmail">Email</label><input type="text" id="userEmail" name="userEmail" value="<?=$userEmail;?>" /></div>
		<div id="userPassDiv"><label for="userPass">Password</label><input type="text" id="userPass" name="userPass"  value="" /></div>
		<div id="userPass2Div"><label for="userPass2">... Again</label><input type="text" id="userPass2" name="userPass2" value="" /></div>
		<div id="userAdminDiv" style="margin-top: 20px;"><label for="userAdmin">Admin</label><input type="checkbox" id="userAdmin" name="userAdmin" <?=$checked;?> /></div>
		<div id="userSubmitDiv"><input type="submit" name="addUser" id="addUser" class="submit" value="Add User" /></div><br class="clear"/>
		<input type="hidden" name="tryadd" id="tryadd" value="1" />
		</form>
	</div>
<?php
}
// ----- END ADD USER SECTION ----- //

(isset($user))?:exit('You must be logged in to view this page');
$crit = array('admin' => 1);
$results = $db->select('users',$crit);
$count = $db->count('users',$crit);
echo "<span class='content'>Total Users: ".$count." ( <a href='".$_SERVER['PHP_SELF']."?p=users&add=1&admin=1'>Add User</a> )</span><a id='exportLink' href='#'>Export to CSV</a>";
echo "<br />";
?>

<table class="modal">
	<thead>
		<tr>
			<th>First</th>
			<th>Last</th>
			<th>Email</th>
			<th>Date</th>
			<th>IP</th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach($results as $res) {
			foreach($res as $key => $value) $$key = $value;
			if($res['ip'] == '') $ip = '0.0.0.0';
		?>
		<tr id="user_<?php echo $_id;?>">
			<td><?php echo $first_name; ?></td>
			<td><?php echo $last_name; ?></td>
			<td><?php echo $email; ?></td>
			<td><?php echo date('m/d/Y h:i:s', $date->sec); ?></td>
			<td><?php echo $ip; ?></td>
			<td><a href="index.php?p=users&edit=1&id=<?php echo $_id;?>">Edit</a></td>
			<td><a href="index.php?p=users&delete=1&id=<?php echo $_id;?>">Delete</a></td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php
$crit = array('admin' => array('$exists' => false));
$results = $db->select('users',$crit);
$count = $db->count('users',$crit);
echo "<span class='content'>Total Users: ".$count." ( <a href='".$_SERVER['PHP_SELF']."?p=users&add=1'>Add User</a> )</span><a id='exportLink' href='#'>Export to CSV</a>";
echo "<br />";
?>

<table class="modal">
	<thead>
		<tr>
			<th>First</th>
			<th>Last</th>
			<th>Email</th>
			<th>Date</th>
			<th>IP</th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach($results as $res) {
			foreach($res as $key => $value) $$key = $value;
			if($res['ip'] == '') $ip = '0.0.0.0';
		?>
		<tr id="user_<?php echo $_id;?>">
			<td><?php echo $first_name; ?></td>
			<td><?php echo $last_name; ?></td>
			<td><?php echo $email; ?></td>
			<td><?php echo date('m/d/Y h:i:s', $date->sec); ?></td>
			<td><?php echo $ip; ?></td>
			<td><a href="index.php?p=users&edit=1&id=<?php echo $_id;?>">Edit</a></td>
			<td><a href="index.php?p=users&delete=1&id=<?php echo $_id;?>">Delete</a></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<script type="text/javascript">
/*
$(function() {
	$('#exportLink').click(function() {
		MyTimestamp = new Date().getTime(); 
		$.get('csv.php','timestamp='+MyTimestamp,function(){
			document.location.href='csv.php?timestamp='+MyTimestamp;
		});
		return false();
	});
});
*/
</script>
