<?php
	//This page is called by an AJAX function at entities.js and gets some values from table Descuento_pendiente

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',',$_POST['values']);//values is set at javascript function get_options() at entities.js
	$result = $conn->query("SELECT Nomina, Retencion, id, Cantidad, Numero_de_descuentos, Fecha_de_inicio, Fecha_de_termino FROM Descuento_pendiente WHERE Trabajador = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}' AND Nomina LIKE '%{$values[0]}%' AND Retencion LIKE '%{$values[1]}%' AND id LIKE '%{$values[2]}%' AND Cantidad LIKE '%{$values[3]}%'" . ($values[4] == "" ? " AND (Numero_de_descuentos IS NULL OR Numero_de_descuentos LIKE '%{$values[4]}%')" : " AND Numero_de_descuentos LIKE '%{$values[4]}%'") . ($values[5] == "" ? " AND (Fecha_de_inicio IS NULL OR Fecha_de_inicio LIKE '%{$values[5]}%')" : " AND Fecha_de_inicio LIKE '%{$values[5]}%'") . ($values[6] == "" ? " AND (Fecha_de_termino IS NULL OR Fecha_de_termino LIKE '%{$values[6]}%')" : " AND Fecha_de_termino LIKE '%{$values[6]}%'"));
	$txt = "";

	if(isset($result))
	{

		if($txt == "")
			$txt = '<table class = "options_table">';

		while(list($nomina, $retencion, $id, $cantidad, $numero_de_descuentos, $fecha_de_inicio, $fecha_de_termino) = $conn->fetchRow($result))

			if($_GET['mode'] != 'DRAW')
				$txt .= "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\"><td class = \"checkbox_input_cell\"></td><td>$nomina</td><td>$retencion</td><td>$id</td><td>$cantidad</td><td>$numero_de_descuentos</td><td>$fecha_de_inicio</td><td>$fecha_de_termino</td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Descuento_pendiente')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_edit(this,'Descuento_pendiente')\" title = 'Editar' class = 'edit_cell'><img src = 'images/edit.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'Descuento_pendiente')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td></tr>";//option_bright() and option_opaque() at presentation.js. select_optio() at general.js
			else
				$txt .= "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\"><td class = \"checkbox_input_cell\"></td><td>$nomina</td><td>$retencion</td><td>$id</td><td>$cantidad</td><td>$numero_de_descuentos</td><td>$fecha_de_inicio</td><td>$fecha_de_termino</td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Descuento_pendiente')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td></tr>";

	}

	if($txt != "")
		$txt .= '</table>';

	echo $txt;
?>
