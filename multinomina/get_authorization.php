<?php
	//This page is called by an AJAX function at form_input_validation.js and compares the $_GET['val'] value with all primary keys ($_GET['id']) at table $_GET['tabla'] from database multinomina. if $_GET['val'] is already at database it echos 'false' or 'true' otherwise

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();

	if($_GET['tabla'] == 'Salario_minimo')//"Codigo" is gonna be checked, not id
		$result = $conn->query("SELECT Codigo FROM Salario_minimo WHERE Codigo = '{$_GET['Codigo']}' AND Nombre != '{$_GET['Nombre']}' AND Cuenta = '{$_SESSION['cuenta']}'");
	else
		$result = $conn->query("SELECT {$_GET['id']} FROM {$_GET['tabla']} WHERE {$_GET['id']} = '{$_GET['val']}' AND Cuenta = '{$_SESSION['cuenta']}'");

	list($id) = $conn->fetchRow($result);
	
	if(isset($id) && $_GET['mode'] == 'ADD')
		echo 'false';
	else
		echo 'true';

?>
