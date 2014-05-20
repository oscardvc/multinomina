<?php
	//This page is called by an AJAX function at entities.js and gets some values from table Factor_de_descuento

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',',$_POST['values']);//values is set at javascript function get_options() at entities.js
	$result = $conn->query("SELECT id, Factor_de_descuento, Fecha_de_inicio FROM Factor_de_descuento WHERE id LIKE '%{$values[0]}%' AND Factor_de_descuento LIKE '%{$values[1]}%' AND Fecha_de_inicio LIKE '%{$values[2]}%' AND Retencion_INFONAVIT = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}'");
	$txt = "";

	if(isset($result))
	{
		$txt .= "<table class = \"options_table\">";

		while($row = $conn->fetchRow($result))
		{

			$txt .= "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\">";
			list($id,$factor,$fecha) = $row;
			$txt .= "<td class = \"checkbox_input_cell\"></td><td>$id</td><td>$factor</td><td>$fecha</td>";

			if($_GET['mode'] != 'DRAW')
				$txt .= "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Factor_de_descuento')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_edit(this,'Factor_de_descuento')\" title = 'Editar' class = 'edit_cell'><img src = 'images/edit.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'Factor_de_descuento')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td></tr>";//option_bright() and option_opaque() at presentation.js. select_optio() at general.js
			else
				$txt .= "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Factor_de_descuento')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td></tr>";

		}

		$txt .= '</table>';

	}

	echo $txt;
?>
