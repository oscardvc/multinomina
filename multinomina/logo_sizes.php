<?php
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();

	if(isset($_POST['empresa']))
		$empresa = $_POST['empresa'];

	if(isset($_POST['sucursal']))
		$sucursal = $_POST['sucursal'];

	if(isset($_POST['empresa_sucursal']))
		$empresa_sucursal = $_POST['empresa_sucursal'];

	if(isset($sucursal) && $sucursal != '' && $sucursal != 'undefined' && $sucursal != 'null')
	{
		$result = $conn->query("SELECT Contenido, Tipo FROM Logo WHERE Sucursal = '$sucursal' AND Empresa_sucursal = '$empresa_sucursal' AND Cuenta = '{$_SESSION['cuenta']}'");

		if($conn->num_rows($result) == 0)
			$result = $conn->query("SELECT Contenido, Tipo FROM Logo WHERE Empresa = '$empresa_sucursal' AND Cuenta = '{$_SESSION['cuenta']}'");

	}
	else
		$result = $conn->query("SELECT Contenido, Tipo FROM Logo WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");

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
		$filename = 'images/logo_blanco.jpg';
		Header("Content-type: IMAGETYPE_JPEG");
		$image = imagecreatefromjpeg($filename);
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
