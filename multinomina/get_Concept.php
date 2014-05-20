<?php
	//This page is called by an AJAX function at entities.js and gets some values from table $_GET['dbtable']

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',',$_POST['values']);//values is set at javascript function get_options() at entities.js

	if($_GET['dbtable'] == 'Prestamo_administradora')
		$result = $conn->query("SELECT Prestamo_administradora FROM Trabajador_Prestamo_administradora WHERE Prestamo_administradora LIKE '%{$values[0]}%' AND Trabajador = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}'");
	elseif($_GET['dbtable'] == 'Prestamo_caja')
		$result = $conn->query("SELECT Prestamo_caja FROM Trabajador_Prestamo_caja WHERE Prestamo_caja LIKE '%{$values[0]}%' AND Trabajador = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}'");
	elseif($_GET['dbtable'] == 'Prestamo_cliente')
		$result = $conn->query("SELECT Prestamo_cliente, Fecha, Monto FROM Trabajador_Prestamo_cliente WHERE Prestamo_cliente LIKE '%{$values[0]}%' " . ($values[1] != '' ? "AND Fecha LIKE '%{$values[1]}%' " : "") . ($values[2] != '' ? "AND Monto LIKE '%{$values[2]}%' " : "") . "AND Trabajador = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}'");
	elseif($_GET['dbtable'] == 'Prestamo_del_fondo_de_ahorro')
		$result = $conn->query("SELECT Prestamo_del_fondo_de_ahorro FROM Trabajador_Prestamo_del_fondo_de_ahorro WHERE Prestamo_del_fondo_de_ahorro LIKE '%{$values[0]}%' AND Trabajador = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}'");

	$i = 0;
	$txt = "";

	if(isset($result))
	{
		while($row = $conn->fetchRow($result))
		{

			foreach($row as $value)

				if(!isset($val[$i]))
					$val[$i] = "<td class = \"checkbox_input_cell\"></td><td>" . $value . '</td>';
				else
					$val[$i] .= "<td>" . $value . '</td>';

			$i++;
		}

		if(isset($val))
		{

			$len = count($val);

			for($i = 0; $i < $len; $i++)

				if($_GET['mode'] != 'DRAW')
					$val[$i] = "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\">{$val[$i]}<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'{$_GET['dbtable']}')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_edit(this,'{$_GET['dbtable']}')\" title = 'Editar' class = 'edit_cell'><img src = 'images/edit.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'{$_GET['dbtable']}')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td></tr>";//option_bright() and option_opaque() at presentation.js. select_optio() at general.js
				else
					$val[$i] = "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\">{$val[$i]}<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'{$_GET['dbtable']}')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td></tr>";

			$val[0] = '<table class = "options_table">' . $val[0];
			$val[$len-1] .= '</table>';

			for($i=0; $i<$len; $i++)
				$txt .= $val[$i];

		}

	}

	echo $txt;
?>
