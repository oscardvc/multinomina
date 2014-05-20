<?php
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$subject = $_POST['subject'];
	$values = explode(',',$_POST['values']);//values is set at javascript function _load() at menu.js

	if(!isset($values[0]))
		$values[0] = '';

	if(!isset($values[1]))
		$values[1] = '';

	if(!isset($values[2]))
		$values[2] = '';

	if(!isset($values[3]))
		$values[3] = '';

	if(!isset($values[4]))
		$values[4] = '';

	if(!isset($values[5]))
		$values[5] = '';

	if(!isset($values[6]))
		$values[6] = '';

	$conn = new Connection();
	$txt = '<table class = "options_table">';
	echo "<table class = \"titles_table\"><tr><td class = \"button_cell\">" . ($subject == 'Actividad' || $subject == 'CFDI_Trabajador' ? "" : "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('$subject','new')\"/>") . "</td>";

	if($subject == 'Actividad')
	{
		$result = $conn->query("SELECT Usuario, Operacion, Dato, Identificadores, Hora_y_fecha FROM Actividad WHERE Cuenta = '{$_SESSION['cuenta']}' AND Usuario LIKE '%{$values[0]}%' AND Operacion LIKE '%{$values[1]}%' AND Dato LIKE '%{$values[2]}%' AND Identificadores LIKE '%{$values[3]}%' AND Hora_y_fecha LIKE '%{$values[4]}%' ORDER BY Hora_y_fecha DESC LIMIT 200");
		echo '<td class = "column_title">Usuario</td><td class = "column_title">Operación</td><td class = "column_title">Dato</td><td class = "column_title">Identificadores</td><td class = "column_title">Fecha y hora</td></tr>';
		$len = 5;
	}
	elseif($subject == 'Aguinaldo')
	{
		$result = $conn->query("SELECT Aguinaldo.id, Aguinaldo.Servicio, Servicio.Periodicidad_de_la_nomina, Aguinaldo.Fecha_de_pago, Empresa.Nombre FROM Aguinaldo LEFT JOIN Servicio ON Aguinaldo.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC WHERE Aguinaldo.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Aguinaldo.id LIKE '%{$values[0]}%' AND Aguinaldo.Servicio LIKE '%{$values[1]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[2]}%' AND Aguinaldo.Fecha_de_pago LIKE '%{$values[3]}%' AND Empresa.Nombre LIKE '%{$values[4]}%' AND DATEDIFF(Aguinaldo.Fecha_de_pago, Servicio_Empresa.Fecha_de_asignacion) >= 0 GROUP BY Aguinaldo.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Aguinaldo.Fecha_de_pago DESC LIMIT 200");
		echo '<td class = "column_title">id</td><td class = "column_title">Servicio</td><td class = "column_title">Periodicidad de la nómina</td><td class = "column_title">Fecha de pago</td><td class = "column_title">Empresa</td></tr>';
		$len = 5;
	}
	elseif($subject == 'CFDI_Trabajador')
	{
		$result = $conn->query("SELECT CFDI_Trabajador.id, CFDI_Trabajador.Fecha, CFDI_Trabajador.Status, Empresa.Nombre, Trabajador.Nombre, CFDI_Trabajador.Tipo FROM CFDI_Trabajador LEFT JOIN Empresa ON CFDI_Trabajador.Emisor = Empresa.RFC LEFT JOIN Trabajador ON CFDI_Trabajador.Receptor = Trabajador.RFC WHERE CFDI_Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND CFDI_Trabajador.id LIKE '%{$values[0]}%' AND CFDI_Trabajador.Fecha LIKE '%{$values[1]}%' AND CFDI_Trabajador.Status LIKE '%{$values[2]}%'AND Empresa.Nombre LIKE '%{$values[3]}%' AND Trabajador.Nombre LIKE '%{$values[4]}%' AND CFDI_Trabajador.Tipo LIKE '%{$values[5]}%' ORDER BY id DESC LIMIT 200");
		echo '<td class = "column_title">id</td><td class = "column_title">Fecha</td><td class = "column_title">Status</td><td class = "column_title">Emisor</td><td class = "column_title">Receptor</td><td class = "column_title">Tipo</td></tr>';
		$len = 6;
	}
	elseif($subject == 'Credito_al_salario_diario')
	{
		$result = $conn->query("SELECT DISTINCT Ano FROM Credito_al_salario_diario WHERE Ano LIKE '%{$values[0]}%'");
		echo '<td class = "column_title">Año</td></tr>';
		$len = 1;
	}
	elseif($subject == 'Credito_al_salario_quincenal')
	{
		$result = $conn->query("SELECT DISTINCT Ano FROM Credito_al_salario_quincenal WHERE Ano LIKE '%{$values[0]}%'");
		echo '<td class = "column_title">Año</td></tr>';
		$len = 1;
	}
	elseif($subject == 'Credito_al_salario_mensual')
	{
		$result = $conn->query("SELECT DISTINCT Ano FROM Credito_al_salario_mensual WHERE Ano LIKE '%{$values[0]}%'");
		echo '<td class = "column_title">Año</td></tr>';
		$len = 1;
	}
	elseif($subject == 'Credito_al_salario_semanal')
	{
		$result = $conn->query("SELECT DISTINCT Ano FROM Credito_al_salario_semanal WHERE Ano LIKE '%{$values[0]}%'");
		echo '<td class = "column_title">Año</td></tr>';
		$len = 1;
	}
	elseif($subject == 'Empresa')
	{
		$result = $conn->query("SELECT RFC, Nombre FROM Empresa WHERE Cuenta = '{$_SESSION['cuenta']}' AND RFC LIKE '%{$values[0]}%' AND Nombre LIKE '%{$values[1]}%' ORDER BY Nombre ASC");
		echo '<td class = "column_title">RFC</td><td class = "column_title">Nombre</td></tr>';
		$len = 2;
	}
	elseif($subject == 'Finiquito')
	{
		$result = $conn->query("SELECT Finiquito.id, Trabajador.Nombre, Trabajador.RFC, Finiquito.Fecha, Servicio.Periodicidad_de_la_nomina, Empresa.Nombre FROM Finiquito LEFT JOIN Trabajador ON Finiquito.Trabajador = Trabajador.RFC LEFT JOIN Servicio ON Finiquito.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC WHERE Finiquito.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Finiquito.id LIKE '%{$values[0]}%' AND Trabajador.Nombre LIKE '%{$values[1]}%' AND Trabajador.RFC LIKE '%{$values[2]}%' AND Finiquito.Fecha LIKE '%{$values[3]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[4]}%' AND Empresa.Nombre LIKE '%{$values[5]}%' AND DATEDIFF(Finiquito.Fecha, Servicio_Empresa.Fecha_de_asignacion) >= 0 GROUP BY Finiquito.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC LIMIT 200");
		echo '<td class = "column_title">id</td><td class = "column_title">Trabajador</td><td class = "column_title">RFC</td><td class = "column_title">Fecha</td><td class = "column_title">Periodicidad de la nómina</td><td class = "column_title">Empresa</td></tr>';
		$len = 6;
	}
	elseif($subject == 'ISR_anual')
	{
		$result = $conn->query("SELECT DISTINCT Ano FROM ISR_anual WHERE Ano LIKE '%{$values[0]}%'");
		echo '<td class = "column_title">Año</td></tr>';
		$len = 1;
	}
	elseif($subject == 'ISR_diario')
	{
		$result = $conn->query("SELECT DISTINCT Ano FROM ISR_diario WHERE Ano LIKE '%{$values[0]}%'");
		echo '<td class = "column_title">Año</td></tr>';
		$len = 1;
	}
	elseif($subject == 'ISR_quincenal')
	{
		$result = $conn->query("SELECT DISTINCT Ano FROM ISR_quincenal WHERE Ano LIKE '%{$values[0]}%'");
		echo '<td class = "column_title">Año</td></tr>';
		$len = 1;
	}
	elseif($subject == 'ISR_mensual')
	{
		$result = $conn->query("SELECT DISTINCT Ano FROM ISR_mensual WHERE Ano LIKE '%{$values[0]}%'");
		echo '<td class = "column_title">Año</td></tr>';
		$len = 1;
	}
	elseif($subject == 'ISR_semanal')
	{
		$result = $conn->query("SELECT DISTINCT Ano FROM ISR_semanal WHERE Ano LIKE '%{$values[0]}%'");
		echo '<td class = "column_title">Año</td></tr>';
		$len = 1;
	}
	elseif($subject == 'Nomina')
	{
		$result = $conn->query("SELECT Nomina.id, Nomina.Servicio, Servicio.Periodicidad_de_la_nomina, Nomina.Limite_inferior_del_periodo, Nomina.Limite_superior_del_periodo, Nomina.Etapa, Empresa.Nombre FROM Nomina LEFT JOIN Servicio ON Nomina.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC WHERE Nomina.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Nomina.id LIKE '%{$values[0]}%' AND Nomina.Servicio LIKE '%{$values[1]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[2]}%' AND Nomina.Limite_inferior_del_periodo LIKE '%{$values[3]}%' AND Nomina.Limite_superior_del_periodo LIKE '%{$values[4]}%' AND Nomina.Etapa LIKE '%{$values[5]}%' AND Empresa.Nombre LIKE '%{$values[6]}%' AND DATEDIFF(Nomina.Limite_superior_del_periodo, Servicio_Empresa.Fecha_de_asignacion) >= 0 GROUP BY Nomina.id ORDER BY Nomina.Limite_inferior_del_periodo DESC LIMIT 200");
		echo '<td class = "column_title">id</td><td class = "column_title">Servicio</td><td class = "column_title">Periodicidad de la nómina</td><td class = "column_title">Límite inferior del periodo</td><td class = "column_title">Límite superior del periodo</td><td>Etapa</td><td class = "column_title">Empresa</td></tr>';
		$len = 7;
	}
	elseif($subject == 'Porcentaje_de_cuotas_IMSS')
	{
		$result = $conn->query("SELECT Nombre, Ano, Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre LIKE '%{$values[0]}%' AND Ano LIKE '%{$values[1]}%' AND Valor LIKE '%{$values[2]}%' ORDER BY Ano DESC, Nombre ASC");
		echo '<td class = "column_title">Nombre</td><td class = "column_title">Año</td><td class = "column_title">Valor</td></tr>';
		$len = 3;
	}
	elseif($subject == 'Propuesta')
	{
		$result = $conn->query("SELECT id, Empresa FROM Propuesta WHERE id LIKE '%{$values[0]}%' AND Empresa LIKE '%{$values[1]}%' AND Cuenta = '{$_SESSION['cuenta']}'");
		echo '<td class = "column_title">id</td><td class = "column_title">Empresa</td></tr>';
		$len = 2;
	}
	elseif($subject == 'Recibo_de_vacaciones')
	{
		$result = $conn->query("SELECT Recibo_de_vacaciones.id, Recibo_de_vacaciones.Fecha, Recibo_de_vacaciones.Status, Trabajador.Nombre, Empresa.Nombre FROM Recibo_de_vacaciones LEFT JOIN Trabajador ON Recibo_de_vacaciones.Trabajador = Trabajador.RFC LEFT JOIN Servicio_Empresa ON Recibo_de_vacaciones.Servicio = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC WHERE Recibo_de_vacaciones.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Recibo_de_vacaciones.id LIKE '%{$values[0]}%' AND Recibo_de_vacaciones.Fecha LIKE '%{$values[1]}%' AND Recibo_de_vacaciones.Status LIKE '%{$values[2]}%' AND Trabajador.Nombre LIKE '%{$values[3]}%' AND Empresa.Nombre LIKE '%{$values[4]}%' AND DATEDIFF(Recibo_de_vacaciones.Fecha, Servicio_Empresa.Fecha_de_asignacion) >= 0 GROUP BY Recibo_de_vacaciones.id ORDER BY Recibo_de_vacaciones.id DESC LIMIT 200");
		echo '<td class = "column_title">id</td><td class = "column_title">Fecha</td><td class = "column_title">Status</td><td class = "column_title">Trabajador</td><td class = "column_title">Empresa</td></tr>';
		$len = 5;
	}
	elseif($subject == 'Salario_minimo')
	{
		$result = $conn->query("SELECT id,Codigo,Nombre,A,B,C,Ano FROM Salario_minimo WHERE Cuenta = '{$_SESSION['cuenta']}' AND id LIKE '%{$values[0]}%' AND Codigo LIKE '%{$values[1]}%'" . ($values[2] == "" ? "" : " AND Nombre LIKE '%{$values[2]}%'") . ($values[3] == "" ? "" : " AND A LIKE '%{$values[3]}%'") . ($values[4] == "" ? "" : " AND B LIKE '%{$values[4]}%'") . ($values[5] == "" ? "" : " AND C LIKE '%{$values[5]}%'") . ($values[6] == "" ? "" : " AND Ano LIKE '%{$values[6]}%'") . " ORDER BY Codigo ASC, Ano DESC");
		echo '<td class = "column_title">id</td><td class = "column_title">Codigo</td><td class = "column_title">Nombre</td><td class = "column_title">A</td><td class = "column_title">B</td><td class = "column_title">C</td><td class = "column_title">Año</td></tr>';
		$len = 7;
	}
	elseif($subject == 'Seguro_por_danos_a_la_vivienda')
	{
		$result = $conn->query("SELECT Ano, Valor FROM Seguro_por_danos_a_la_vivienda WHERE Ano LIKE '%{$values[0]}%' AND Valor LIKE '%{$values[1]}%'");
		echo '<td class = "column_title">Año</td><td class = "column_title">Valor</td></tr>';
		$len = 2;
	}
	elseif($subject == 'Servicio')
	{
		$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Servicio LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio AND Servicio.Cuenta = Servicio_Registro_patronal.Cuenta LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio AND Servicio.Cuenta = Servicio_Empresa.Cuenta LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC AND Servicio_Empresa.Cuenta = Empresa.Cuenta WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' " . ($values[1] != '' ? "AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " : "") . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . ($values[3] != '' ? "AND Empresa.Nombre LIKE '%{$values[3]}%' " : "") . "GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		echo '<td class = "column_title">id</td><td class = "column_title">Periodicidad de la nómina</td><td class = "column_title">Registro patronal</td><td class = "column_title">Empresa</td></tr>';
		$len = 4;
	}
	elseif($subject == 'Trabajador')
	{
		$result = $conn->query("SELECT Trabajador.RFC,Trabajador.Nombre,Empresa.Nombre AS Empresa, Trabajador_Sucursal.Nombre FROM Trabajador LEFT JOIN Servicio_Trabajador ON Trabajador.RFC = Servicio_Trabajador.Trabajador AND Trabajador.Cuenta = Servicio_Trabajador.Cuenta LEFT JOIN Servicio ON Servicio_Trabajador.Servicio = Servicio.id AND Servicio_Trabajador.Cuenta = Servicio.Cuenta LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio AND Servicio.Cuenta = Servicio_Empresa.Cuenta LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC AND Servicio_Empresa.Cuenta = Empresa.Cuenta LEFT JOIN Trabajador_Sucursal ON Trabajador.RFC = Trabajador_Sucursal.Trabajador AND Trabajador.Cuenta = Trabajador_Sucursal.Cuenta WHERE Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador.RFC LIKE '%{$values[0]}%' AND Trabajador.Nombre LIKE '%{$values[1]}%'" . ($values[2] != '' ? " AND Empresa.Nombre LIKE '%{$values[2]}%'" : '') . ($values[3] != '' ? " AND Trabajador_Sucursal.Nombre LIKE '%{$values[3]}%'" : '') . "GROUP BY Trabajador.RFC ORDER BY Trabajador.Nombre ASC LIMIT 200");

		echo '<td class = "column_title">RFC</td><td class = "column_title">Nombre</td><td class = "column_title">Empresa</td><td class = "column_title">Sucursal</td></tr>';
		$len = 4;
	}
	elseif($subject == 'Usuario')
	{
		$result = $conn->query("SELECT Nombre FROM Usuario WHERE Cuenta = '{$_SESSION['cuenta']}' AND Nombre LIKE '%{$values[0]}%'");

		echo '<td class = "column_title">Nombre</td></tr>';
		$len = 1;
	}

	echo '</table>';//titles table
	$i = 0;
	$txt = "";

	if(isset($result))
	{

		while($row = $conn->fetchRow($result))
		{

			foreach($row as $value)
			{

				if(!isset($val[$i]))
					$val[$i] = "<td = '$value'>" . $value . '</td>';
				else
					$val[$i] .= "<td = '$value'>" . $value . '</td>';

			}

			$i++;
		}

		if(isset($val))
		{

			$_len = count($val);

			for($i = 0; $i < $_len; $i++)

				if($subject == 'Actividad')
					$val[$i] = "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\"'><td class = \"checkbox_input_cell\"></td>{$val[$i]}</tr>";//option_bright() and option_opaque() at presentation.js. select_option() at general.js
				elseif($subject == 'CFDI_Trabajador')
					$val[$i] = "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\"'><td class = \"checkbox_input_cell\"></td>{$val[$i]}<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'$subject')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td></tr>";
				else
					$val[$i] = "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\"'><td class = \"checkbox_input_cell\"></td>{$val[$i]}<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'$subject')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_edit(this,'$subject')\" title = 'Editar' class = 'edit_cell'><img src = 'images/edit.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'$subject')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td></tr>";

			$val[0] = '<table class = "options_table">' . $val[0];
			$val[$_len-1] .= '</table>';

			for($i=0; $i<$_len; $i++)
				$txt .= $val[$i];

		}

	}

	$search_table = "<table class = \"search_table\"><tr>";

	for($j=0; $j<$len; $j++)
	{

		if($j == 0)
			$search_table .= "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"_load('$subject')\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $j . "' value = '{$values[$j]}'/></td>";
		else
			$search_table .= "<td><input type = 'text' class = 'search_field'  id = 'search_field" . $j . "' value = '{$values[$j]}'/></td>";

	}

	$search_table .= "</tr></table>";
	echo $txt;
	echo $search_table;
?>
