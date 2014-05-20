<?php
	$filename = 'images/' . $_GET['subject'] . '.png';
	Header("Content-type: IMAGETYPE_PNG");
	$image = imagecreatefrompng($filename);
	$width = imagesx($image);
	$height = imagesy($image);
	$new_height = $_GET['height'];
	$ratio = $new_height / $height;
	$new_width = $width * $ratio;
	$new_image = imagecreatetruecolor($new_width, $new_height);
	imagealphablending($new_image,false);
	imagesavealpha($new_image,true);
	imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	$image = $new_image;
	imagepng($image);
?>
