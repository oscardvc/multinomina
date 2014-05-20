<?php
	//This page is called by an AJAX function at entities.js and gets some values from table Registro_patronal

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',',$_POST['values']);//values is set at javascript function get_options() at entities.js

	if($_GET['time'] != 'First' && ($_GET['mode'] == 'ADD' || $_GET['mode'] == 'EDIT'))
	{

		if($_GET['dbtable'] == 'Prima' || $_GET['dbtable'] == 'Propuesta' || $_GET['dbtable'] == 'Servicio_Registro_patronal')
			$result = $conn->query("SELECT Registro_patronal.Numero, Empresa.Nombre, Registro_patronal.Sucursal FROM Registro_patronal LEFT JOIN Empresa ON (Registro_patronal.Empresa = Empresa.RFC OR Registro_patronal.Empresa_sucursal = Empresa.RFC) WHERE Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Numero LIKE '%{$values[0]}%' AND Empresa.Nombre LIKE '%{$values[1]}%'" . ($values[2] != '' ? " AND Registro_patronal.Sucursal LIKE '%{$values[2]}%'" : ""));
		elseif($_GET['dbtable'] == 'Empresa')
			$result = $conn->query("SELECT Numero FROM Registro_patronal WHERE Cuenta = '{$_SESSION['cuenta']}' AND Numero LIKE '%{$values[0]}%' AND Empresa = '{$_GET['id']}' AND Sucursal IS NULL");
		elseif($_GET['dbtable'] == 'Sucursal')
		{
			$data = explode('>>',$_GET['id']);
			$sucursal = $data[0];
			$empresa = $data[1];
			$result = $conn->query("SELECT Numero FROM Registro_patronal WHERE Cuenta = '{$_SESSION['cuenta']}' AND Numero LIKE '%{$values[0]}%' AND Empresa_sucursal = '$empresa' AND Sucursal = '$sucursal'");
		}

	}
	elseif($_GET['mode'] == 'DRAW' || ($_GET['mode'] == 'EDIT' && $_GET['time'] == 'First'))
	{

		if($_GET['dbtable'] == 'Servicio_Registro_patronal')
			$result = $conn->query("SELECT Registro_patronal.Numero, Empresa.Nombre, Registro_patronal.Sucursal FROM Registro_patronal LEFT JOIN Empresa ON (Registro_patronal.Empresa = Empresa.RFC OR Registro_patronal.Empresa_sucursal = Empresa.RFC) WHERE Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Numero LIKE '%{$values[0]}%' AND Empresa.Nombre LIKE '%{$values[1]}%'" . ($values[2] != '' ? " AND Registro_patronal.Sucursal LIKE '%{$values[2]}%'" : "") . " AND Registro_patronal.Numero = '{$_GET['id']}'");
		elseif($_GET['dbtable'] == 'Prima')
			$result = $conn->query("SELECT Registro_patronal.Numero, Empresa.Nombre, Registro_patronal.Sucursal FROM Prima LEFT JOIN Registro_patronal ON Prima.Registro_patronal = Registro_patronal.Numero LEFT JOIN Empresa ON (Registro_patronal.Empresa = Empresa.RFC OR Registro_patronal.Empresa_sucursal = Empresa.RFC) WHERE Prima.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Numero LIKE '%{$values[0]}%' " . ($values[1] != '' ? "AND Empresa.Nombre LIKE '%{$values[1]}%' " : '') . ($values[2] != '' ? "AND Registro_patronal.Sucursal LIKE '%{$values[2]}%' " : '') . "AND Prima.id = '{$_GET['id']}'");
		elseif($_GET['dbtable'] == 'Propuesta')
			$result = $conn->query("SELECT Registro_patronal.Numero, Empresa.Nombre, Registro_patronal.Sucursal FROM Propuesta LEFT JOIN Registro_patronal ON Propuesta.Registro_patronal = Registro_patronal.Numero LEFT JOIN Empresa ON (Registro_patronal.Empresa = Empresa.RFC OR Registro_patronal.Empresa_sucursal = Empresa.RFC) WHERE Propuesta.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Numero LIKE '%{$values[0]}%' " . ($values[1] != '' ? "AND Empresa.Nombre LIKE '%{$values[1]}%' " : '') . ($values[2] != '' ? "AND Registro_patronal.Sucursal LIKE '%{$values[2]}%' " : '') . "AND Propuesta.id = '{$_GET['id']}'");
		elseif($_GET['dbtable'] == 'Empresa')
			$result = $conn->query("SELECT Numero FROM Registro_patronal WHERE Empresa = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}' AND Sucursal IS NULL");
		elseif($_GET['dbtable'] == 'Sucursal')
		{
			$data = explode('>>',$_GET['id']);
			$sucursal = $data[0];
			$empresa = $data[1];
			$result = $conn->query("SELECT Numero FROM Registro_patronal WHERE Empresa_sucursal = '$empresa' AND Sucursal = '$sucursal' AND Cuenta = '{$_SESSION['cuenta']}'");
		}

	}

	$txt = "";

	if(isset($result))
	{
		$txt = '<table class = "options_table">';

		while($row = $conn->fetchRow($result))
		{

			if($_GET['dbtable'] == 'Servicio_Registro_patronal' || $_GET['dbtable'] == 'Prima' || $_GET['dbtable'] == 'Propuesta')
			{
				list($numero, $empresa, $sucursal) = $row;
				$txt .= "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\"" . ($_GET['mode'] != 'DRAW' ? " onclick='select_option(this)'>" : ">") . "<td class = \"checkbox_input_cell\">" . ($_GET['mode'] != 'DRAW' ? "<input type= \"checkbox\" class = \"checkbox_input\" name=\"Registro_patronal\" value = \"$numero\"/>" : "") . "</td><td>$numero</td><td>$empresa</td><td>$sucursal</td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Registro_patronal')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td>";
			}
			else
			{
				list($numero) = $row;
				$txt .= "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\"><td class = \"checkbox_input_cell\"></td><td>$numero</td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Registro_patronal')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td>";
			}

			$txt .= ($_GET['mode'] != 'DRAW' ? "<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_edit(this,'Registro_patronal')\" title = 'Editar' class = 'edit_cell'><img src = 'images/edit.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'Registro_patronal')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td>" : "") . '</tr>';
		}

		$txt .= '</table>';
	}

	echo $txt;
?>
