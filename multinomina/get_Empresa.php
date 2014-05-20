<?php
	//This page is called by an AJAX function at entities.js and gets some values from table Empresa

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',',$_POST['values']);//values is set at javascript function get_options() at entities.js

	if($_GET['time'] != 'First' && ($_GET['mode'] == 'ADD' || $_GET['mode'] == 'EDIT'))
		$result = $conn->query("SELECT RFC, Nombre FROM Empresa WHERE RFC LIKE '%{$values[0]}%' AND Nombre LIKE '%{$values[1]}%' AND Cuenta = '{$_SESSION['cuenta']}'");
	elseif($_GET['mode'] == 'DRAW' || ($_GET['mode'] == 'EDIT' && $_GET['time'] == 'First'))
	{

		if($_GET['dbtable'] == 'Servicio_Empresa')
			$result = $conn->query("SELECT Empresa.RFC, Empresa.Nombre FROM Servicio_Empresa LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC WHERE Empresa.RFC = '{$_GET['id']}' AND Empresa.RFC LIKE '%{$values[0]}%' AND Empresa.Nombre LIKE '%{$values[1]}%' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' GROUP BY Empresa.RFC");

	}

	$txt = "";

	if(isset($result))
	{
		$txt = '<table class = "options_table">';

		while($row = $conn->fetchRow($result))
		{
			list($rfc, $nombre) = $row;
			$txt .= "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\"" . ($_GET['mode'] != 'DRAW' ? " onclick='select_option(this)'>" : ">") . "<td class = \"checkbox_input_cell\">" . ($_GET['mode'] != 'DRAW' ? "<input type= \"checkbox\" class = \"checkbox_input\" name=\"Empresa\" value = \"$rfc\"/>" : "") . "</td><td>$rfc</td><td>$nombre</td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Empresa')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td>" . ($_GET['mode'] != 'DRAW' ? "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_edit(this,'Empresa')\" title = 'Editar' class = 'edit_cell'><img src = 'images/edit.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'Empresa')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td>" : "") . '</tr>';
		}

		$txt .= '</table>';
	}

	echo $txt;
?>
