<?php
	//This page is called by a java script at arhivo_digital.js to open a file
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$result = $conn->query("SELECT Nombre,Tipo,Tamanio,Contenido FROM Archivo_digital WHERE Nombre ='{$_GET['name']}' AND Cuenta = '{$_SESSION['cuenta']}'");
	$row = $conn->fetchRow($result);
	list($nombre,$tipo,$tamanio,$contenido) = $row;
	header("Content-length: $tamanio");
	header("Content-type: $tipo");
	header("Content-Disposition: attachment; filename={$_GET['name']}");
	echo $contenido;
?>
