<?php
	//This page is called by an AJAX function at entities.js and gets some values from table Sucursal

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',',$_POST['values']);//values is set at javascript function get_options() at entities.js

	if($_GET['dbtable'] == 'Empresa')
	{

		if($values[0] == '')
			$result = $conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}'");
		else
			$result = $conn->query("SELECT Nombre FROM Sucursal WHERE Nombre LIKE '%{$values[0]}%' AND Empresa = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}'");

	}

	$i = 0;
	$txt = "";

	if(isset($result))
	{
		$txt .= "<table class = \"options_table\">";

		while($row = $conn->fetchRow($result))
		{

			if($_GET['dbtable'] == 'Empresa')
			{
				$txt .= "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\">";
				list($sucursal) = $row;
				$txt .= "<td class = \"checkbox_input_cell\"></td><td>$sucursal</td>";
			}

			if($_GET['mode'] != 'DRAW')
				$txt .= "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Sucursal')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_edit(this,'Sucursal')\" title = 'Editar' class = 'edit_cell'><img src = 'images/edit.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'Sucursal')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td></tr>";//option_bright() and option_opaque() at presentation.js. select_optio() at general.js
			else
				$txt .= "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Sucursal')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td></tr>";


		}

		$txt .= '</table>';

	}

	echo $txt;
?>
