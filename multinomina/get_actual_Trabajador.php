<?php
	//This page is called by an AJAX function at some concept's .js and gets some values from a table Trabajador from database multinomina

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();

	if($_GET['dbtable'] == 'Servicio')
		$result = $conn->query("SELECT Trabajador FROM Servicio_Trabajador WHERE Servicio = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}'");

	if(isset($result))

		while(list($rfc) = $conn->fetchRow($result))
			echo "$rfc>>";

?>
