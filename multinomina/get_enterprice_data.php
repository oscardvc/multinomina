<?php
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$result = $conn->query("SELECT Registro_patronal.Numero, Empresa.Nombre, Registro_patronal.Sucursal FROM Registro_patronal LEFT JOIN Empresa ON Registro_patronal.Empresa = Empresa.RFC WHERE Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}'" . (isset($_POST['val']) ? " AND Empresa.Nombre = '{$_POST['val']}'" : "") . " ORDER BY Empresa.Nombre ASC, Registro_patronal.Sucursal ASC");
	$txt = '<div class = "registro_div">';

	while(list($registro, $empresa, $sucursal) = $conn->fetchRow($result))
	{
		$data = explode(' ',$empresa);
		$txt .= "<input type = 'checkbox' name = 'Registro_patronal[]' value = '$registro'/>$registro {$data[0]} " . (count($data) > 1 ? $data[1] : "") . " $sucursal<br/>";
	}

	$txt .= "</div>";
	echo $txt;
?>
