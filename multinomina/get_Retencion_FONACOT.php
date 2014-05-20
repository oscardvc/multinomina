<?php
	//This page is called by an AJAX function at entities.js and gets some values from table Retencion_FONACOT

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',',$_POST['values']);//values is set at javascript function get_options() at entities.js
	$result = $conn->query("SELECT id, Numero_de_credito, Importe_total, Fecha_de_inicio, Fecha_de_termino FROM Retencion_FONACOT WHERE id LIKE '%{$values[0]}%' AND Importe_total LIKE '%{$values[1]}%' AND Fecha_de_inicio LIKE '%{$values[2]}%' AND Fecha_de_termino LIKE '%{$values[3]}%' AND Trabajador = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}'");
	$i = 0;
	$txt = "";

	if(isset($result))
	{
		$txt .= "<table class = \"options_table\">";

		while($row = $conn->fetchRow($result))
		{

			$txt .= "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\">";
			list($id,$numero,$importe,$fecha_de_inicio,$fecha_de_termino) = $row;
			$txt .= "<td class = \"checkbox_input_cell\"></td><td>$id</td><td>$numero</td><td>$importe</td><td>$fecha_de_inicio</td><td>$fecha_de_termino</td>";

			if($_GET['mode'] != 'DRAW')
				$txt .= "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Retencion_FONACOT')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_edit(this,'Retencion_FONACOT')\" title = 'Editar' class = 'edit_cell'><img src = 'images/edit.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'Retencion_FONACOT')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td></tr>";//option_bright() and option_opaque() at presentation.js. select_optio() at general.js
			else
				$txt .= "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Retencion_FONACOT')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td></tr>";

		}

		$txt .= '</table>';

	}

	echo $txt;
?>
