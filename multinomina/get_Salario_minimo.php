<?php
	//This page is called by an AJAX function at entities.js and gets some values from table Salario_minimo

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',',$_POST['values']);//values is set at javascript function get_options() at entities.js

	if($_GET['time'] != 'First' && ($_GET['mode'] == 'ADD' || $_GET['mode'] == 'EDIT'))
		$result = $conn->query("SELECT DISTINCT Codigo, Nombre FROM Salario_minimo WHERE Codigo LIKE '%{$values[0]}%' AND Nombre LIKE '%{$values[1]}%' AND Cuenta = '{$_SESSION['cuenta']}'");
	elseif($_GET['mode'] == 'DRAW' || ($_GET['mode'] == 'EDIT' && $_GET['time'] == 'First'))
	{

		if($_GET['dbtable'] == 'Trabajador')
			$result = $conn->query("SELECT DISTINCT Salario_minimo.Codigo, Salario_minimo.Nombre FROM Trabajador_Salario_minimo LEFT JOIN Salario_minimo ON Trabajador_Salario_minimo.Salario_minimo = Salario_minimo.Codigo WHERE Salario_minimo.Codigo LIKE '%{$values[0]}%' AND Salario_minimo.Nombre LIKE '%{$values[1]}%'AND Trabajador_Salario_minimo.Trabajador = '{$_GET['id']}' AND Trabajador_Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Cuenta = '{$_SESSION['cuenta']}'");

	}

	$i = 0;
	$txt = "";

	if(isset($result))
	{
		$txt .= "<table class = \"options_table\">";

		while($row = $conn->fetchRow($result))
		{
			$txt .= "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\" onclick=\"select_option(this)\">";
			list($codigo,$nombre) = $row;
			$txt .= "<td class = \"checkbox_input_cell\">" . ($_GET['mode'] != 'DRAW' ? "<input type= \"checkbox\" class = \"checkbox_input\" name=\"Salario_minimo[]\" value = \"$codigo\"/>" : "") . "</td><td>$codigo</td><td>$nombre</td>";
		}

		$txt .= '</table>';

	}

	echo $txt;
?>
