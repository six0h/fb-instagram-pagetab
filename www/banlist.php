<?php
$curl_handle=curl_init();
curl_setopt($curl_handle,CURLOPT_URL,'https://www.dan.me.uk/torlist/');
curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
$buffer = curl_exec($curl_handle);
curl_close($curl_handle);

require_once('../config.php');

$list = explode(',', $buffer);

$db->remove('ip');

foreach($list as $item) {
	$db->insert('ip', array('ip' => $item));
}
	
?>
