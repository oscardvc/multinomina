<?php
	//This page is called by an AJAX function at some concept's .js

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();

	if($_GET['dbtable'] == 'Trabajador')
		$result = $conn->query("SELECT Trabajador_Sucursal.Nombre, Trabajador_Sucursal.Cliente FROM Trabajador_Sucursal WHERE Trabajador = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}'");

	if(isset($result))

		while(list($nombre, $cliente) = $conn->fetchRow($result))
			echo "$nombre/$cliente>>";

?>
