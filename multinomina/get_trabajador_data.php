<?php
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$val = $_POST['val'];
	$result = $conn->query("SELECT RFC FROM Trabajador WHERE Nombre = '$val' AND Cuenta = '{$_SESSION['cuenta']}'");
	$rfcs = array();
	$i = 0;
	//trabajador rfc
	$txt = '<select class = "trabajador_rfc_select">';

	while(list($rfc) = $conn->fetchRow($result))
	{
		$txt .= "<option>$rfc</option>";
		$rfcs[$i] = $rfc;
		$i ++;
	}

	$txt .= "</select>";
	$len = count($rfcs);
	//servicio
	$txt .= '<select class = "servicio_select">';

	for($i=0; $i<$len; $i++)
	{
		$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Empresa.Nombre FROM Servicio_Trabajador LEFT JOIN Servicio ON Servicio_Trabajador.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC WHERE Servicio_Trabajador.Trabajador = '{$rfcs[$i]}' AND Servicio_Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' GROUP BY Servicio.id");

		while(list($id,$periodicidad,$empresa) = $conn->fetchRow($result))
			$txt .= "<option>$id/$periodicidad/$empresa</option>";

		if($conn->num_rows($result) > 1)
			$txt .= "<option>Todos</option>";

	}

	$txt .= "</select>";
	echo $txt;
?>
