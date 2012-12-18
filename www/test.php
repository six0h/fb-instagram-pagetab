<?php

$url = 'https://api.instagram.com/v1/tags/sunpeaks/media/recent?client_id=b8767acf56534c6bb1224c65e57ba11e';
$result = json_decode(file_get_contents($url));

echo "<pre>";
print_r($result);
echo "</pre>";

?>
