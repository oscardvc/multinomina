<?php
	//This page is called by an AJAX function at some concept's .js

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();

	if($_GET['dbtable'] == 'Trabajador')
		$result = $conn->query("SELECT Salario_minimo FROM Trabajador_Salario_minimo WHERE Trabajador = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}'");

	if(isset($result))

		while(list($salario_minimo) = $conn->fetchRow($result))
			echo "$salario_minimo>>";

?>
