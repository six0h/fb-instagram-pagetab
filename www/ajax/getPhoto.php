<?php

require_once('../../config.php');
$status = 200;

$id = $_POST['id'];

$result = $db->findOne('photos',array('id'=>$id));

echo json_encode(array('status'=>$status,'photo'=>$result));

?>