<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	</head>
	<body>
	<?php
		$wsdl = file_get_contents('test.php');
//		$wsdl = file_get_contents('https://facturacion.finkok.com/servicios/soap/stamp.wsdl');
		echo $wsdl;
	?>
	</body>
</html>
