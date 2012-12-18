<?php

function isMultiArray($multiarray) {
	if (is_array($multiarray)) {  // confirms array
		foreach ($multiarray as $array) {  // goes one level deeper
			if (is_array($array)) {  // is subarray an array
				return true;  // return will stop function
			}  // end 2nd check
		}  // end loop
	}  // end 1st check
	return false;  // not a multiarray if this far
}

function sqThm($src,$dest,$size=220){
   
   list($w,$h) = getimagesize($src);

   if($w > $h){
      exec("convert ".$src." -resize x".$size." -quality 100 ".$dest);
   }else{
      exec("convert ".$src." -resize ".$size." -quality 100 ".$dest);
   }

   exec("convert ".$dest." -gravity Center -crop ".$size."x".$size."+0+0 ".$dest);

}

function parse_signed_request($signed_request, $secret) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2);

  // decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }

  // check sig
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

function base64_url_decode($input) {
  return base64_decode(strtr($input, '-_', '+/'));
}

function check_bans() {

        global $db;
        $crit = array('ip' => $_SERVER['REMOTE_ADDR']);
        $count = $db->count('ip',$crit);
        return($count);

}


?>
