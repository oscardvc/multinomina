<?php
	//This page is called by an AJAX function at entities.js and gets some values from table Servicio_Trabajador

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',',$_POST['values']);//values is set at javascript function get_options() at entities.js
	$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Servicio_Trabajador LEFT JOIN Servicio ON Servicio_Trabajador.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Servicio_Trabajador.Trabajador = '{$_GET['id']}' AND Servicio_Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' GROUP BY Servicio.id");
	$i = 0;
	$txt = "";

	if(isset($result))
	{
		$txt .= "<table class = \"options_table\">";

		while($row = $conn->fetchRow($result))
		{

			$txt .= "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\">";
			list($id,$periodo,$registro,$empresa) = $row;
			$txt .= "<td class = \"checkbox_input_cell\"></td><td>$id</td><td>$periodo</td><td>$registro</td><td>$empresa</td>";

			if($_GET['mode'] != 'DRAW')
				$txt .= "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Servicio_Trabajador')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_edit(this,'Servicio_Trabajador')\" title = 'Editar' class = 'edit_cell'><img src = 'images/edit.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'Servicio_Trabajador')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td></tr>";//option_bright() and option_opaque() at presentation.js. select_optio() at general.js
			else
				$txt .= "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Servicio_Trabajador')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td></tr>";

		}

		$txt .= '</table>';

	}

	echo $txt;
?>
