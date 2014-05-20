<?php
	//This page is called by an AJAX function at some concept's .js and gets some values from table Trabajador

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',',$_POST['values']);//values is set at javascript function get_Trabajadores() at some concept's .js

	if($_GET['time'] != 'First' && ($_GET['mode'] == 'ADD' || $_GET['mode'] == 'EDIT'))
	{

		if($_GET['dbtable'] == 'Servicio')
			$result = $conn->query("SELECT Trabajador.RFC,Trabajador.Nombre,Empresa.Nombre AS Empresa FROM Trabajador LEFT JOIN Servicio_Trabajador ON Trabajador.RFC = Servicio_Trabajador.Trabajador LEFT JOIN Servicio ON Servicio_Trabajador.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC WHERE (Trabajador.RFC LIKE '%{$values[0]}%' AND Trabajador.Nombre LIKE '%{$values[1]}%' AND Empresa.Nombre LIKE '%{$values[2]}%') OR Servicio_Trabajador.Servicio = '{$_GET['id']}' AND Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' GROUP BY Trabajador.RFC");

	}
	elseif($_GET['mode'] == 'DRAW' || ($_GET['mode'] == 'EDIT' && $_GET['time'] == 'First'))
	{

		if($_GET['dbtable'] == 'Servicio')
			$result = $conn->query("SELECT Trabajador.RFC,Trabajador.Nombre,Empresa.Nombre AS Empresa FROM Trabajador LEFT JOIN Servicio_Trabajador ON Trabajador.RFC = Servicio_Trabajador.Trabajador LEFT JOIN Servicio ON Servicio_Trabajador.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC WHERE Trabajador.RFC LIKE '%{$values[0]}%' AND Trabajador.Nombre LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Empresa.Nombre LIKE '%{$values[2]}%' " : "") . "AND Servicio.id = '{$_GET['id']}' AND Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}'");

	}

	$i = 0;
	$txt = "";

	if(isset($result))
	{
		while($row = $conn->fetchRow($result))
		{

			foreach($row as $value)

				if(!isset($val[$i]))
					$val[$i] = "<td class = \"checkbox_input_cell\">" . ($_GET['mode'] != 'DRAW' ? "<input type= \"checkbox\" class = \"checkbox_input\" name=" . ($_GET['dbtable'] == 'Archivo_digital' ? '"Trabajador"' : 'Trabajador[]') . " value = \"$value\"/>" : "") . "</td><td>" . $value . '</td>';
				else
					$val[$i] .= "<td>" . $value . '</td>';

			$i++;
		}

		if(isset($val))
		{

			$len = count($val);

			for($i = 0; $i < $len; $i++)

				if($_GET['mode'] != 'DRAW')
					$val[$i] = "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\"".(($_GET['dbtable'] == 'Archivo_digital') ? ' onclick="select_option(this)"' : ' onclick="select_multiple_option(this)"') . ">{$val[$i]}<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Trabajador')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_edit(this,'Trabajador')\" title = 'Editar' class = 'edit_cell'><img src = 'images/edit.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'Trabajador')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td></tr>";//option_bright() and option_opaque() at presentation.js. select_opt() at general.js
				else
					$val[$i] = "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\">{$val[$i]}<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Trabajador')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td></tr>";

			$val[0] = '<table class = "options_table">' . $val[0];
			$val[$len-1] .= '</table>';

			for($i=0; $i<$len; $i++)
				$txt .= $val[$i];

		}

	}

	echo $txt;
?>
