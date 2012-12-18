<?php

// GET PHOTO OFFSET
(isset($_GET['userPage']) ? $userPage = $_GET['userPage'] : $userPage = 0);
$userSkip = $userPage * 15;
(isset($_GET['tagPage']) ? $tagPage = $_GET['tagPage'] : $tagPage = 0);
$tagSkip = $tagPage * 15;

$userPhotos = $db->find('photos',array('type'=>'user'),array('limit'=>15,'sort'=>array('id'=>-1),'offset'=>$userSkip));
$userCount = (int) $db->count('photos', array('type'=>'user'));
$userPages = $userCount / 15;
if(($userCount % 15) != 0) $userPages++;
$tagPhotos = $db->find('photos',array('type'=>'tag'),array('limit'=>15,'sort'=>array('_id'=>-1),'offset'=>$tagSkip));
$tagCount = (int) $db->count('photos',array('type'=>'tag'));
$tagPages = $tagCount / 15;
if(($tagCount % 15) != 0) $tagPages++;
?>
<img src="img/header.png"/>
<div id="users">
	<h1>@SunPeaksResort</h1>
	<?php
	foreach($userPhotos as $photo) :
		echo "<div class='entry'>";
				echo "<a href='#modal'><img src='".$photo['images']['thm']."' id='".$photo['id']."'/></a>";
		echo "</div>";
	endforeach;
	echo "<br class='clear' />";
	echo "<p>Pages:</p> <ul class='pagination'>";
	for($i=1; $i<$userPages; $i++) {
		echo "<li><a href='".$_SERVER['PHP_SELF']."?userPage=".($i-1)."' ";
			if($i == $userPage) {
				echo "class='active'>$i</a></li>";
			} else {
				echo ">$i</a></li>";
			}

	}
	echo "</ul>";
	?>
</div>
<img src="img/divider.png" style="margin: 20px 0;"/>
<div id="tagged">
	<h1>#SunPeaks360</h1>
	<?php
	foreach($tagPhotos as $photo) :
		echo "<div class='entry'>";
				echo "<a href='#modal'><img src='".$photo['images']['thm']."' id='".$photo['id']."'/></a>";
		echo "</div>";
	endforeach;
	echo "<br class='clear'/>";
	echo "<p>Pages:</p> <ul class='pagination'>";
	for($i=1; $i<$tagPages; $i++) {
		echo "<li><a href='".$_SERVER['PHP_SELF']."?tagPage=".($i-1)."' ";
			if($i == $tagPage) {
				echo "class='active'>$i</a></li>";
			} else {
				echo ">$i</a></li>";
			}
	}
	echo "</ul>";
	?>
</div>

<div id="modal">
	<div id="toolbox" class='addthis_toolbox addthis_default_style '></div>
	<div class="content"></div>
</div>
