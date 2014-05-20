<?php
	//This page is called by an AJAX function at entities.js and gets some values from table Servicio_Registro_patronal

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',',$_POST['values']);//values is set at javascript function get_options() at entities.js
	$result = $conn->query("SELECT Registro_patronal.Numero, Empresa.Nombre, Registro_patronal.Sucursal, Servicio_Registro_patronal.Fecha_de_asignacion FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero LEFT JOIN Empresa ON (Registro_patronal.Empresa = Empresa.RFC OR Registro_patronal.Empresa_sucursal = Empresa.RFC) WHERE Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[0]}%' AND Empresa.Nombre LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Registro_patronal.Sucursal LIKE '%{$values[2]}%' " : "") . "AND Servicio_Registro_patronal.Fecha_de_asignacion LIKE '%{$values[3]}%' AND Servicio_Registro_patronal.Servicio = '{$_GET['id']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}'");
	$i = 0;
	$txt = "";

	if(isset($result))
	{
		$txt .= "<table class = \"options_table\">";

		while($row = $conn->fetchRow($result))
		{

			$txt .= "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\">";
			list($numero,$empresa,$sucursal,$fecha) = $row;
			$txt .= "<td class = \"checkbox_input_cell\"></td><td>$numero</td><td>$empresa</td><td>$sucursal</td><td>$fecha</td>";

			if($_GET['mode'] != 'DRAW')
				$txt .= "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Servicio_Registro_patronal')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_edit(this,'Servicio_Registro_patronal')\" title = 'Editar' class = 'edit_cell'><img src = 'images/edit.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'Servicio_Registro_patronal')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td></tr>";//option_bright() and option_opaque() at presentation.js. select_optio() at general.js
			else
				$txt .= "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Servicio_Registro_patronal')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td></tr>";

		}

		$txt .= '</table>';
	}

	echo $txt;
?>
