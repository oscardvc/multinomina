<?php
	//This page is called by an AJAX function at some concept's .js and gets some values from a table Trabajador from database multinomina

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();

	if($_GET['dbtable'] == 'Prima')
		$result = $conn->query("SELECT Registro_patronal FROM Prima WHERE id = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}'");
	elseif($_GET['dbtable'] == 'Servicio_Registro_patronal')
		$result = $conn->query("SELECT Registro_patronal FROM Servicio_Registro_patronal WHERE Registro_patronal = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}' LIMIT 1");

	if(isset($result))

		while(list($numero) = $conn->fetchRow($result))
			echo "$numero>>";

?>
