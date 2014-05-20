<?php
	//This page is called by an AJAX function at entities.js and gets some values from table Trabajador_Salario_minimo

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',', $_POST['values']);//values is set at javascript function get_options() at entities.js
	$result = $conn->query("SELECT DISTINCT Trabajador_Salario_minimo.Salario_minimo, Salario_minimo.Nombre, Trabajador_Salario_minimo.Servicio, Trabajador_Salario_minimo.Fecha FROM Trabajador_Salario_minimo LEFT JOIN Salario_minimo ON Trabajador_Salario_minimo.Salario_minimo = Salario_minimo.Codigo WHERE Trabajador_Salario_minimo.Salario_minimo LIKE '%{$values[0]}%' AND Salario_minimo.Nombre LIKE '%{$values[1]}%' AND Trabajador_Salario_minimo.Servicio LIKE '%{$values[2]}%' AND Trabajador_Salario_minimo.Fecha LIKE '%{$values[3]}%' AND Trabajador_Salario_minimo.Trabajador = '{$_GET['id']}' AND Trabajador_Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Cuenta = '{$_SESSION['cuenta']}'");
	$i = 0;
	$txt = "";

	if(isset($result))
	{
		$txt .= "<table class = \"options_table\">";

		while($row = $conn->fetchRow($result))
		{

			$txt .= "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\">";
			list($codigo,$nombre,$servicio,$fecha) = $row;
			$txt .= "<td class = \"checkbox_input_cell\"></td><td>$codigo</td><td>$nombre</td><td>$servicio</td><td>$fecha</td>";

			if($_GET['mode'] != 'DRAW')
				$txt .= "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Trabajador_Salario_minimo')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_edit(this,'Trabajador_Salario_minimo')\" title = 'Editar' class = 'edit_cell'><img src = 'images/edit.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'Trabajador_Salario_minimo')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td></tr>";//option_bright() and option_opaque() at presentation.js. select_optio() at general.js
			else
				$txt .= "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Trabajador_Salario_minimo')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td></tr>";

		}

		$txt .= '</table>';

	}

	echo $txt;
?>
