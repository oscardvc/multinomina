<?php

	if(!isset($_SESSION))
		session_start();

	include_once('connection.php');
	$conn = new Connection();

	if(isset($_POST['trabajador']))
		$result = $conn->query("SELECT Contenido, Tipo FROM Sign WHERE Trabajador = '{$_POST['trabajador']}' AND Cuenta = '{$_SESSION['cuenta']}'");
	else
		$result = $conn->query("SELECT Contenido, Tipo FROM Sign WHERE Usuario = '{$_POST['usuario']}' AND Cuenta = '{$_SESSION['cuenta']}'");

	list($contenido,$tipo) = $conn->fetchRow($result);

	if(isset($contenido) && isset($tipo))
	{

		if($tipo == 'image/jpeg')
			$filename = 'temp/' . uniqid() . '.jpg';
		elseif($tipo == 'image/png')
			$filename = 'temp/' . uniqid() . '.png';

		file_put_contents($filename, $contenido);
		$image_info = getimagesize($filename);
		$image_type = $image_info[2];
		Header("Content-type: $image_type");

		if($image_type == IMAGETYPE_JPEG)
		{
			$image = imagecreatefromjpeg($filename);
		}
		elseif($image_type == IMAGETYPE_GIF )
		{
			$image = imagecreatefromgif($filename);
		}
		elseif($image_type == IMAGETYPE_PNG)
		{
			$image = imagecreatefrompng($filename);
		}

		$width = imagesx($image);
		$height = imagesy($image);

		if($width > $height)
		{
			$new_width = $width > $_POST['width'] ? $_POST['width'] : $width;
			$ratio = $new_width / $width;
			$new_height = $height * $ratio;

			if($new_height > $_POST['height'])
			{
				$ratio = $_POST['height'] / $new_height;
				$new_height = $_POST['height'];
				$new_width = $new_width * $ratio;
			}

		}
		else
		{
			$new_height = $height > $_POST['height'] ? $_POST['height'] : $height;
			$ratio = $new_height / $height;
			$new_width = $width * $ratio;

			if($new_width > $_POST['width'])
			{
				$ratio = $_POST['width'] / $new_width;
				$new_width = $_POST['width'];
				$new_height = $new_height * $ratio;
			}

		}

		echo "<span>$new_width</span><span>$new_height</span>";
		unlink($filename);
	}
	else
	{
		$filename = 'images/sign.png';
		Header("Content-type: IMAGETYPE_PNG");
		$image = imagecreatefrompng($filename);
		$width = imagesx($image);
		$height = imagesy($image);
		$new_width = $width > $_POST['width'] ? $_POST['width'] : $width;
		$ratio = $new_width / $width;
		$new_height = $height * $ratio;

		if($new_height > $_POST['height'])
		{
			$ratio = $_POST['height'] / $new_height;
			$new_height = $_POST['height'];
			$new_width = $new_width * $ratio;
		}

		echo "<span>$new_width</span><span>$new_height</span>";
	}

?>
