<?php
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$val = $_POST['val'];
	$result = $conn->query("SELECT Codigo FROM Salario_minimo WHERE Nombre = '$val' AND Cuenta = '{$_SESSION['cuenta']}'");
	list($codigo) = $conn->fetchRow($result);
	echo $codigo;
?>
