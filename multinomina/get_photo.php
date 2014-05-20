<?php
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$rfc = $_GET['rfc'];
	$result = $conn->query("SELECT Contenido,Tipo FROM Photo WHERE Trabajador = '$rfc' AND Cuenta = '{$_SESSION['cuenta']}'");
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

		if($image_type == IMAGETYPE_JPEG)
		{
			imagejpeg($image);
		}
		elseif($image_type == IMAGETYPE_GIF)
		{
			imagegif($image);
		}
		elseif($image_type == IMAGETYPE_PNG)
		{
			imagepng($image);
		}

		unlink($filename);
	}
	else
	{

		$filename = 'images/photo.jpg';
		Header("Content-type: IMAGETYPE_JPG");
		$image = imagecreatefromjpeg($filename);
		$width = imagesx($image);
		$height = imagesy($image);
		imagejpeg($image);
	}

?>
