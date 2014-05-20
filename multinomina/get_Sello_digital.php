<?php
	//This page is called by an AJAX function at entities.js and gets some values from table Sello_digital

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',',$_POST['values']);//values is set at javascript function get_options() at entities.js

	if($_GET['dbtable'] == 'Empresa')
		$result = $conn->query("SELECT id FROM Sello_digital WHERE id LIKE '%{$values[0]}%' AND Empresa = '{$_GET['id']}' AND Cuenta = '{$_SESSION['cuenta']}'");
	elseif($_GET['dbtable'] == 'Sucursal')
	{
		$data = explode('>>',$_GET['id']);
		$sucursal = $data[0];
		$empresa = $data[1];
		$result = $conn->query("SELECT id FROM Sello_digital WHERE id LIKE '%{$values[0]}%' AND Sucursal = '$sucursal' AND Empresa_sucursal = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
	}

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
					$val[$i] = "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\">{$val[$i]}<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Sello_digital')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'Sello_digital')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td></tr>";//option_bright() and option_opaque() at presentation.js. select_optio() at general.js
				else
					$val[$i] = "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\">{$val[$i]}<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Sello_digital')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td></tr>";

			$val[0] = '<table class = "options_table">' . $val[0];
			$val[$len-1] .= '</table>';

			for($i=0; $i<$len; $i++)
				$txt .= $val[$i];

		}

	}

	echo $txt;
?>
