<?php
	//This page is called by an AJAX function at entities.js and gets some values from table Servicio

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$values = explode(',',$_POST['values']);//values is set at javascript function get_options() at entities.js

	if($_GET['time'] != 'First' && ($_GET['mode'] == 'ADD' || $_GET['mode'] == 'EDIT'))
	{

		if($_GET['dbtable'] == 'Servicio_adicional')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_adicional ON Servicio.id = Servicio_adicional.Servicio LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_adicional.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%'" : "") . " AND Empresa.Nombre LIKE '%{$values[3]}%') AND Servicio_Servicio_adicional.Servicio_adicional = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		else
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");

	}
	elseif($_GET['mode'] == 'DRAW' || ($_GET['mode'] == 'EDIT' && $_GET['time'] == 'First'))
	{

		if($_GET['dbtable'] == 'Aportacion_del_trabajador_al_fondo_de_ahorro')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Aportacion_del_trabajador_al_fondo_de_ahorro LEFT JOIN Servicio ON Aportacion_del_trabajador_al_fondo_de_ahorro.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Aportacion_del_trabajador_al_fondo_de_ahorro.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Aportacion_del_trabajador_al_fondo_de_ahorro.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Incapacidad')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Incapacidad LEFT JOIN Servicio ON Incapacidad.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Incapacidad.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Incapacidad.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Vacaciones')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Vacaciones LEFT JOIN Servicio ON Vacaciones.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Vacaciones.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Vacaciones.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Pension_alimenticia')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Pension_alimenticia LEFT JOIN Servicio ON Pension_alimenticia.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Pension_alimenticia.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Pension_alimenticia.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Prestamo_administradora')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Trabajador_Prestamo_administradora LEFT JOIN Servicio ON Trabajador_Prestamo_administradora.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador_Prestamo_administradora.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Trabajador_Prestamo_administradora.Prestamo_administradora = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Prestamo_caja')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Trabajador_Prestamo_caja LEFT JOIN Servicio ON Trabajador_Prestamo_caja.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador_Prestamo_caja.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Trabajador_Prestamo_caja.Prestamo_caja = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Prestamo_cliente')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Trabajador_Prestamo_cliente LEFT JOIN Servicio ON Trabajador_Prestamo_cliente.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador_Prestamo_cliente.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Trabajador_Prestamo_cliente.Prestamo_cliente = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Prestamo_del_fondo_de_ahorro')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Trabajador_Prestamo_del_fondo_de_ahorro LEFT JOIN Servicio ON Trabajador_Prestamo_del_fondo_de_ahorro.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador_Prestamo_del_fondo_de_ahorro.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Trabajador_Prestamo_del_fondo_de_ahorro.Prestamo_del_fondo_de_ahorro = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Retencion_FONACOT')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Retencion_FONACOT LEFT JOIN Servicio ON Retencion_FONACOT.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Retencion_FONACOT.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Retencion_FONACOT.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Retencion_INFONAVIT')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Retencion_INFONAVIT LEFT JOIN Servicio ON Retencion_INFONAVIT.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Retencion_INFONAVIT.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Retencion_INFONAVIT.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Pago_por_seguro_de_vida')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Pago_por_seguro_de_vida LEFT JOIN Servicio ON Pago_por_seguro_de_vida.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Pago_por_seguro_de_vida.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Pago_por_seguro_de_vida.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Salario_diario')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Salario_diario LEFT JOIN Servicio ON Salario_diario.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Salario_diario.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Salario_diario.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Servicio_Trabajador')
		{
			$data = explode(',',$_GET['id']);
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Servicio_Trabajador LEFT JOIN Servicio ON Servicio_Trabajador.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Servicio_Trabajador.Servicio = '{$data[0]}' AND Servicio_Trabajador.Trabajador = '{$data[1]}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		}
		elseif($_GET['dbtable'] == 'Trabajador_Sucursal')
		{
			$data = explode(',',$_GET['id']);
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Trabajador_Sucursal LEFT JOIN Servicio ON Trabajador_Sucursal.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Trabajador_Sucursal.Trabajador = '{$data[0]}' AND Trabajador_Sucursal.Nombre = '{$data[1]}' AND Trabajador_Sucursal.Empresa = '{$data[2]}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		}
		elseif($_GET['dbtable'] == 'Trabajador_Salario_minimo')
		{
			$data = explode(',',$_GET['id']);
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Trabajador_Salario_minimo LEFT JOIN Servicio ON Trabajador_Salario_minimo.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador_Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Trabajador_Salario_minimo.Trabajador = '{$data[0]}' AND Trabajador_Salario_minimo.Servicio = '{$data[1]}' AND Trabajador_Salario_minimo.Fecha = '{$data[2]}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		}
		elseif($_GET['dbtable'] == 'Baja')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Baja LEFT JOIN Servicio ON Baja.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Baja.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Baja.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Contrato')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Contrato LEFT JOIN Servicio ON Contrato.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Contrato.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Contrato.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Banco')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Banco LEFT JOIN Servicio ON Banco.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Banco.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Banco.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'UMF')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM UMF LEFT JOIN Servicio ON UMF.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND UMF.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND UMF.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Tipo')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Tipo LEFT JOIN Servicio ON Tipo.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Tipo.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Tipo.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Base')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Base LEFT JOIN Servicio ON Base.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Base.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Base.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");
		elseif($_GET['dbtable'] == 'Fondo_de_garantia')
			$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre FROM Fondo_de_garantia LEFT JOIN Servicio ON Fondo_de_garantia.Servicio = Servicio.id LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Fondo_de_garantia.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id LIKE '%{$values[0]}%' AND Servicio.Periodicidad_de_la_nomina LIKE '%{$values[1]}%' " . ($values[2] != '' ? "AND Servicio_Registro_patronal.Registro_patronal LIKE '%{$values[2]}%' " : "") . "AND Empresa.Nombre LIKE '%{$values[3]}%' AND Fondo_de_garantia.id = '{$_GET['id']}' GROUP BY Servicio.id ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC");

	}

	$i = 0;
	$txt = "";

	if(isset($result))
	{
		while($row = $conn->fetchRow($result))
		{

			foreach($row as $value)

				if(!isset($val[$i]))
					$val[$i] = "<td class = \"checkbox_input_cell\">" . ($_GET['mode'] != 'DRAW' && $_GET['dbtable'] != 'Empresa' ? "<input type= \"checkbox\" class = \"checkbox_input\" name=\"" . (($_GET['dbtable'] != 'Prevision_social' && $_GET['dbtable'] != 'Servicio_adicional') ? "Servicio" : "Servicio[]") . "\" value = \"$value\"/>" : "") . "</td><td>" . $value . '</td>';
				else
					$val[$i] .= "<td>" . $value . '</td>';

			$i++;
		}

		if(isset($val))
		{

			$len = count($val);

			for($i = 0; $i < $len; $i++)

				if($_GET['mode'] != 'DRAW')
					$val[$i] = "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\" onclick=" . (($_GET['dbtable'] != 'Prevision_social' && $_GET['dbtable'] != 'Servicio_adicional') ? ($_GET['dbtable'] != 'Empresa' ? "'select_option(this)'" : "") : "'select_multiple_option(this)'") . ">{$val[$i]}<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Servicio')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_edit(this,'Servicio')\" title = 'Editar' class = 'edit_cell'><img src = 'images/edit.png'/></td><td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_delete(this,'Servicio')\" title = 'Eliminar' class = 'delete_cell'><img src = 'images/delete.png'/></td></tr>";//option_bright() and option_opaque() at presentation.js. select_optio() at general.js
				else
					$val[$i] = "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\">{$val[$i]}<td onmouseover = 'om_bright(this)' onmouseout = 'om_opaque(this)' onclick = \"_view(this,'Servicio')\" title = 'Ver' class = 'view_cell'><img src = 'images/view.png'/></td></tr>";

			$val[0] = '<table class = "options_table">' . $val[0];
			$val[$len-1] .= '</table>';

			for($i=0; $i<$len; $i++)
				$txt .= $val[$i];

		}

	}

	echo $txt;
?>