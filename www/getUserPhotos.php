<?php
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);

require_once('../config.php');

$i = 1;
$next_url = null;
$c = 1;
while($i == 1) {
	($next_url == null
			? $url = 'https://api.instagram.com/v1/users/188266179/media/recent?access_token=188266179.b8767ac.fa6f0d3fce0440b38366343f07401d06&count=30'
			: $url = $next_url . "&count=30");

	echo $next_url."<br /><br /><br />";

	$data = json_decode(file_get_contents($url));

	foreach($data->data as $item) {

		if(is_object($item)) {
			$insert = array(
					'user'=> $item->user->username,
					'id'=> $item->id,
					'profile_pic'=> $item->user->profile_picture,
					'type'=> 'user',
					'link'=> $item->link,
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

			echo "<pre>";
			print_r($insert);
			echo "</pre>";
		}

	}

	$c++;
	if(!isset($data->pagination->next_url) || $c == 5) {
		$i = 0;
	} else {
		$next_url = $data->pagination->next_url;
	}

}
echo "<pre>";
print_r($data);
echo "</pre>";



?>

<!DOCTYPE html>
<html>

<head>
	<title>Spinstagram</title>
</head>

<body>

</body>

</html>
