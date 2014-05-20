<?php
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$val = $_POST['val'];
	$result = $conn->query("SELECT RFC FROM Empresa WHERE Nombre = '$val' AND Cuenta = '{$_SESSION['cuenta']}'");
	$rfcs = array();
	$i = 0;
	//empresa rfc
	$txt = '<select class = "empresa_rfc_select">';

	while(list($rfc) = $conn->fetchRow($result))
	{
		$txt .= "<option>$rfc</option>";
		$rfcs[$i] = $rfc;
		$i ++;
	}

	$txt .= "</select>";
	$len = count($rfcs);
	//nombre (sucursal)
	$txt .= '<select class = "nombre_select">';

	for($i=0; $i<$len; $i++)
	{
		$result = $conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '{$rfcs[$i]}' AND Cuenta = '{$_SESSION['cuenta']}'");

		while(list($nombre) = $conn->fetchRow($result))
			$txt .= "<option>$nombre</option>";

	}

	$txt .= "</select>";
	echo $txt;
?>
