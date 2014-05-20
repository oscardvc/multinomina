<html>
	<head>
		<style type="text/css">
			body
			{
				font:normal normal normal 13px Arial , sans-serif;
				color:#555;
			}

			table
			{
				font:normal normal normal 13px Arial , sans-serif;
			}

			table tr td
			{
				border:1px solid #555;
			}

			table .totals td
			{
				background:#ddd;
				text-align:right;
			}
		 </style>
		<script type = "text/javascript" src = "concentrado.js"></script>
	</head>
	<body>
<?php
	function total($data)
	{
		$_data = explode(',',$data);
		$len = count($_data);
		$total = 0;

		for($i=0; $i<$len; $i++)
		{
			$values = explode('</span>',$_data[$i]);

			if(count($values) > 1)
				$value = str_replace('<span>','',$values[1]);
			else
				$value = str_replace('<span>','',$values[0]);

			$data_ = explode('/',$value);
			$total += $data_[0];
		}

		return $total;
	}

	function total_prestacion($data)
	{
		$_data = explode(',',$data);
		$len = count($_data);
		$total = 0;

		for($i=0; $i<$len; $i++)
			$total += $_data[$i];

		return $total;
	}

//*****************************Proportional functions**************************************************************
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	function prop($valor, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio)
	{
		$conn = new Connection();
		//Días del periodo
		$dias_del_periodo_nomina = calculate_dias_del_periodo($lip, $lsp);
		$numero_de_dias_del_periodo = count($dias_del_periodo_nomina);
		//Dias previos al ingreso
		$result = $conn->query("SELECT Numero_de_dias_previos_al_ingreso FROM " . ($tipo == 'Asalariado' ? "ISRasalariados" : "ISRasimilables") . " WHERE Nomina = '$nomina' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
		list($numero_de_dias_previos_al_ingreso) = $conn->fetchRow($result);
		$dias_previos_al_ingreso = calculate_dias_previos_al_ingreso($numero_de_dias_previos_al_ingreso,$lip);
		//Días de baja
		$dias_de_baja = calculate_dias_de_baja($trabajador, $lip, $lsp, $servicio);

		//faltas
		if($tipo == 'Asalariado')
		{
			$result = $conn->query("SELECT Faltas FROM ISRasalariados WHERE Trabajador = '$trabajador' AND Nomina = '$nomina' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($_faltas) = $conn->fetchRow($result);
			$conn->freeResult($result);
			$faltas = $_faltas != '' ? explode(',', $_faltas) : array();
		}
		else
			$faltas = array();

		//Incapacidad
		$dias_de_incapacidad = calculate_dias_de_incapacidad($trabajador, $lip, $lsp, $servicio);
		//Vacaciones
		$dias_de_vacaciones = calculate_dias_de_vacaciones($trabajador, $lip, $lsp, $servicio);
		//Días laborados
		$dias_laborados = calculate_dias_laborados($dias_del_periodo_nomina, $dias_previos_al_ingreso, $dias_de_baja, $faltas,$dias_de_incapacidad, $dias_de_vacaciones);
		$numero_de_dias_laborados = count($dias_laborados);
		$dias_dentro_del_periodo = $dias_laborados;

		for($i=0; $i<count($dias_dentro_del_periodo); $i++)

			if($dias_dentro_del_periodo[$i] < $li || $dias_dentro_del_periodo[$i] > $ls)
			{
				$dias_dentro_del_periodo = _extract($dias_dentro_del_periodo,$dias_dentro_del_periodo[$i]);
				$i--;
			}

		if($numero_de_dias_laborados > 0)
			return ($valor * count($dias_dentro_del_periodo) / $numero_de_dias_laborados);
		else
			return 0;

	}

	function calculate_dias_del_periodo($lip, $lsp)
	{
		date_default_timezone_set('America/Mexico_City');
		$limite_inferior = date_create($lip);
		$limite_superior = date_create($lsp);
		$dias_del_periodo = array();

		if($limite_inferior < $limite_superior)
		{
			$interval = $limite_inferior->diff($limite_superior);
			$numero_de_dias_del_periodo = $interval->days + 1;

			for($i=0; $i<$numero_de_dias_del_periodo; $i++)
			{
				$interval = new DateInterval('P' . $i . 'D');
				$day = $limite_inferior->add($interval);
				$dias_del_periodo[$i] = $day->format('Y-m-d');
				$day = $limite_inferior->sub($interval);
			}

		}

		return $dias_del_periodo;
	}

	function calculate_dias_previos_al_ingreso($numero_de_dias_previos_al_ingreso,$lip)
	{
		date_default_timezone_set('America/Mexico_City');
		$limite_inferior = date_create($lip);
		$dias_previos_al_ingreso = array();

		if($numero_de_dias_previos_al_ingreso > 0)
		{

			for($i=0; $i<$numero_de_dias_previos_al_ingreso; $i++)
			{
				$interval = new DateInterval('P'. $i .'D');
				$day = $limite_inferior->add($interval);
				$dias_previos_al_ingreso[$i] = $day->format('Y-m-d');
				$day = $limite_inferior->sub($interval);
			}

		}

		return $dias_previos_al_ingreso;
	}

	function calculate_dias_de_baja($trabajador, $lip, $lsp, $servicio)
	{
		date_default_timezone_set('America/Mexico_City');
		$conn = new Connection();
		$numero_de_dias_del_periodo = 0;
		$limite_inferior = date_create($lip);
		$limite_superior = date_create($lsp);

		if($limite_superior > $limite_inferior)
		{
			$interval = $limite_inferior->diff($limite_superior);
			$numero_de_dias_del_periodo = $interval->days + 1;
		}
		else
			$numero_de_dias_del_periodo = 0;

		$dias_del_periodo = array();

		for($i=0; $i<$numero_de_dias_del_periodo; $i++)
		{
			$interval = new DateInterval('P' . $i . 'D');
			$day = $limite_inferior->add($interval);
			$dias_del_periodo[$i] = $day->format('Y-m-d');
			$day = $limite_inferior->sub($interval);
		}

		$result = $conn->query("SELECT Fecha_de_baja, Fecha_de_reingreso FROM Baja WHERE Trabajador = '$trabajador' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_baja,'$lsp') <= 0");
		$dias_de_baja = array();
		$j = 0;

		while(list($fecha_de_baja,$fecha_de_reingreso) = $conn->fetchRow($result))
		{
			$baja = date_create($fecha_de_baja);

			if($fecha_de_reingreso != '0000-00-00')
				$reingreso = date_create($fecha_de_reingreso);
			else
			{
				$day_interval = new DateInterval('P1D');
				$_aux = $limite_superior->add($day_interval);
				$aux = $_aux->format('Y-m-d');
				$limite_superior->sub($day_interval);
				$reingreso = date_create($aux);
			}

			$interval = date_diff($baja,$reingreso);
			$numero_de_dias_de_baja = $interval->format('%r%a');
			$day_interval = new DateInterval('P1D');

			for($i=0; $i<$numero_de_dias_de_baja; $i++)
			{
				$day = $baja->add($day_interval);

				if($limite_inferior <= $day && $day <= $limite_superior && $day != $reingreso)
				{
					$dias_de_baja[$j] = $day->format('Y-m-d');
					$j++;
				}

			}

		}

		return $dias_de_baja;
	}

	function calculate_dias_de_incapacidad($trabajador, $lip, $lsp, $servicio)
	{
		date_default_timezone_set('America/Mexico_City');
		$conn = new Connection();
		$limite_inferior = date_create($lip);
		$limite_superior = date_create($lsp);
		$dias_de_incapacidad = array();
		$result = $conn->query("SELECT Fecha_de_inicio, Fecha_de_termino FROM Incapacidad WHERE Trabajador = '$trabajador' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
		$j = 0;

		while(list($fecha_de_inicio, $fecha_de_termino) = $conn->fetchRow($result))
		{

			if(isset($fecha_de_inicio) && isset($fecha_de_termino))
			{
				$inicio = date_create($fecha_de_inicio);
				$termino = date_create($fecha_de_termino);
				$interval = date_diff($inicio,$termino);
				$numero_de_dias_de_incapacidad = $interval->format('%r%a') + 1;

				for($i=0; $i<$numero_de_dias_de_incapacidad; $i++)
				{
					$days_interval = new DateInterval('P' . $i . 'D');
					$day = $inicio->add($days_interval);

					if($limite_inferior <= $day && $day <= $limite_superior)
					{
						$dias_de_incapacidad[$j] = $day->format('Y-m-d');
						$j++;
					}

					$day = $inicio->sub($days_interval);
				}

			}

		}

		return $dias_de_incapacidad;
	}

	function calculate_dias_de_vacaciones($trabajador, $lip, $lsp, $servicio)
	{
		date_default_timezone_set('America/Mexico_City');
		$conn = new Connection();
		$limite_inferior = date_create($lip);
		$limite_superior = date_create($lsp);
		$dias_de_vacaciones = array();
		$result = $conn->query("SELECT Fecha_de_inicio, Fecha_de_termino FROM Vacaciones WHERE Trabajador = '$trabajador' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
		$j = 0;

		while(list($fecha_de_inicio, $fecha_de_termino) = $conn->fetchRow($result))
		{

			if(isset($fecha_de_inicio) && isset($fecha_de_termino))
			{
				$inicio = date_create($fecha_de_inicio);
				$termino = date_create($fecha_de_termino);
				$interval = date_diff($inicio,$termino);
				$numero_de_dias_de_vacaciones = $interval->format('%r%a') + 1;

				for($i=0; $i<$numero_de_dias_de_vacaciones; $i++)
				{
					$days_interval = new DateInterval('P' . $i . 'D');
					$day = $inicio->add($days_interval);

					if($limite_inferior <= $day && $day <= $limite_superior)
					{
						$dias_de_vacaciones[$j] = $day->format('Y-m-d');
						$j++;
					}

					$day = $inicio->sub($days_interval);
				}

			}

		}

		return $dias_de_vacaciones;
	}

	function calculate_dias_laborados($dias_del_periodo, $dias_previos_al_ingreso, $dias_de_baja, $_faltas, $dias_de_incapacidad,$dias_de_vacaciones)
	{
		$numero_de_dias_del_periodo = count($dias_del_periodo);
		$numero_de_dias_previos_al_ingreso = count($dias_previos_al_ingreso);
		$numero_de_dias_de_baja = count($dias_de_baja);
		$numero_de_faltas = count($_faltas);
		$numero_de_dias_de_incapacidad = count($dias_de_incapacidad);
		$numero_de_dias_de_vacaciones = count($dias_de_vacaciones);
		$dias_laborados = $dias_del_periodo;

		if($numero_de_dias_previos_al_ingreso > 0)
		{

			for($i=0; $i<$numero_de_dias_previos_al_ingreso; $i++)
			{
				$len = count($dias_laborados);

				for($j=0; $j<$len; $j++)

					if($dias_previos_al_ingreso[$i] == $dias_laborados[$j])
					{
						$dias_laborados = _extract($dias_laborados,$dias_laborados[$j]);
						break;
					}

			}

		}

		if($numero_de_dias_de_baja > 0)
		{

			for($i=0; $i<$numero_de_dias_de_baja; $i++)
			{
				$len = count($dias_laborados);

				for($j=0; $j<$len; $j++)

					if($dias_de_baja[$i] == $dias_laborados[$j])
					{
						$dias_laborados = _extract($dias_laborados,$dias_laborados[$j]);
						break;
					}

			}

		}

		if($numero_de_faltas > 0)
		{

			for($i=0; $i<$numero_de_faltas; $i++)
			{
				$len = count($dias_laborados);

				for($j=0; $j<$len; $j++)

					if($_faltas[$i] == $dias_laborados[$j])
					{
						$dias_laborados = _extract($dias_laborados,$dias_laborados[$j]);
						break;
					}

			}

		}

		if($numero_de_dias_de_incapacidad > 0)
		{

			for($i=0; $i<$numero_de_dias_de_incapacidad; $i++)
			{
				$len = count($dias_laborados);

				for($j=0; $j<$len; $j++)

					if($dias_de_incapacidad[$i] == $dias_laborados[$j])
					{
						$dias_laborados = _extract($dias_laborados,$dias_laborados[$j]);
						break;
					}

			}

		}

		if($numero_de_dias_de_vacaciones > 0)
		{

			for($i=0; $i<$numero_de_dias_de_vacaciones; $i++)
			{
				$len = count($dias_laborados);

				for($j=0; $j<$len; $j++)

					if($dias_de_vacaciones[$i] == $dias_laborados[$j])
					{
						$dias_laborados = _extract($dias_laborados,$dias_laborados[$j]);
						break;
					}

			}

		}

		return $dias_laborados;
	}

	function _extract($array,$element)
	{
		$aux = array();
		$len = count($array);
		$j = 0;

		for($i=0; $i<$len; $i++)

			if($array[$i] != $element)
			{
				$aux[$j] = $array[$i];
				$j++;
			}

		return $aux;
	}
//*****************************************************************************************************************
	date_default_timezone_set('America/Mexico_City');
	$conn = new Connection();
	$limite_inferior = $_GET['limite_inferior'];
	$limite_superior = $_GET['limite_superior'];
	$servicio = $_GET['servicio'];
	$empresa = $_GET['empresa'];
	$rfc = $_GET['rfc'];
	$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio WHERE DATEDIFF('$limite_superior', Servicio_Empresa.Fecha_de_asignacion) >= 0 AND Empresa = '$rfc' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}'" . ($servicio == 'Todos' ? "" : " AND id = '$servicio'"));
	echo "<img src = 'images/logo_blanco.jpg'/><br/><br/>";
	echo "Empresa: $empresa<br/>RFC: $rfc<br/>";

	while(list($id, $periodicidad) = $conn->fetchRow($result))
		echo "Servicio: $id<br/>Periodicidad de la nómina: $periodicidad<br/>";

	echo "Ver totales<input type = 'button' onclick = 'show_totals(this)' value = '⌖' style = 'background:#3399cc; border:none; borderRadius:10px; -moz-border-radius:10px; -webkit-border-radius:10px;color:#fff; text-align:center; cursor:pointer'><br/><br/>";
	$conn->freeResult($result);
	$txt = "<table><tr style = 'text-align:center;color:#fff;background:#555' class = 'titles'><td colspan = '7' onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Datos</td><td colspan = '5' onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Vacaciones</td><td colspan = '4' onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Aguinaldo</td><td colspan = '34' onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Nómina</td></tr><tr style = 'text-align:center;color:#fff;background:#3399cc' class = 'subtitles'><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>N</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Trabajador</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>RFC</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>CURP</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Tipo</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Bajas</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Fecha de ingreso</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Vacaciones</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Prima vacacional</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Compensación</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>ISR</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Total</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Aguinaldo</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Gratificación adicional</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>ISR</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Total</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Número de días laborados</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Sueldo</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Subsidio pagado</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Subsidio que se debió entregar</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Horas extra exentas</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Horas extra grabadas</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Prima dominical</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Días de descanso</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Premios de puntualidad y asistencia</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Bonos de productividad</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Estímulos</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Compensaciones</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Despensa</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Comida</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Alimentación</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Habitación</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Aportación patronal al fondo de ahorro</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>ISR retenido</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>ISR que se debió reter</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Cuotas IMSS</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Retención por alimentación</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Retención por habitación</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Retención INFONAVIT</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Retención FONACOT</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Aportación del trabajador al fondo de ahorro</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Pensión alimenticia</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Retardos</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Saldo</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Pago por seguro de vida</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Préstamo del fondo de ahorro</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Préstamo de caja</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Préstamo de cliente</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Préstamo de administradora</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Neto a recibir</td></tr>";
	$i = 1;
	$total_vacaciones = 0;
	$total_prima_vacacional = 0;
	$total_compensacion_vacaciones = 0;
	$total_isr_vacaciones = 0;
	$total_saldo_vacaciones = 0;
	$total_aguinaldo_ordinario = 0;
	$total_gratificacion_adicional = 0;
	$total_isr_aguinaldo = 0;
	$total_saldo_aguinaldo = 0;
	$total_numero_de_dias_laborados = 0;
	$total_sueldo = 0;
	$total_subsidio_al_empleo = 0;
	$total_subsidio = 0;
	$total_horas_extra_exentas = 0;
	$total_horas_extra_grabadas = 0;
	$total_prima_dominical = 0;
	$total_dias_de_descanso = 0;
	$total_premios_de_puntualidad_y_asistencia = 0;
	$total_bonos_de_productividad = 0;
	$total_estimulos = 0;
	$total_compensaciones_nomina = 0;
	$total_despensa = 0;
	$total_comida = 0;
	$total_alimentacion = 0;
	$total_habitacion = 0;
	$total_aportacion_patronal_al_fondo_de_ahorro = 0;
	$total_isr_nomina = 0;
	$total_impuesto_determinado = 0;
	$total_cuotas_imss = 0;
	$total_retencion_por_alimentacion = 0;
	$total_retencion_por_habitacion = 0;
	$total_retencion_infonavit = 0;
	$total_retencion_fonacot = 0;
	$total_aportacion_del_trabajador_al_fondo_de_ahorro = 0;
	$total_pension_alimenticia = 0;
	$total_retardos = 0;
	$total_saldo_nomina = 0;
	$total_pago_por_seguro_de_vida = 0;
	$total_prestamo_del_fondo_de_ahorro = 0;
	$total_prestamo_caja = 0;
	$total_prestamo_cliente = 0;
	$total_prestamo_administradora = 0;
	$total_neto_a_recibir = 0;
	$total_retencion_vacaciones = 0;
	$total_retencion_prima_vacacional = 0;
	$total_retencion_aguinaldo = 0;
	$total_retencion_prima_de_antiguedad = 0;
	$total_diferencia_vacaciones = 0;
	$total_diferencia_prima_vacacional = 0;
	$total_diferencia_aguinaldo = 0;
	$total_retencion_prestaciones = 0;
	$result = $conn->query("SELECT Servicio.id, Servicio_Empresa.Fecha_de_asignacion FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio WHERE DATEDIFF('$limite_superior', Servicio_Empresa.Fecha_de_asignacion) >= 0 AND Servicio_Empresa.Empresa = '$rfc' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}'" . ($servicio == 'Todos' ? "" : " AND Servicio.id = '$servicio'"));

	while(list($servicio, $fecha_de_asignacion) = $conn->fetchRow($result))
	{
		//setting lower limit
		if($limite_inferior < $fecha_de_asignacion)
			$li = $fecha_de_asignacion;
		else
			$li = $limite_inferior;

		//setting upper limit
		$result1 = $conn->query("SELECT Fecha_de_asignacion FROM Servicio_Empresa WHERE Servicio = '$servicio' AND Empresa != '$rfc' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_asignacion, '$fecha_de_asignacion') >= 0 AND DATEDIFF('$limite_superior', Fecha_de_asignacion) >= 0 ORDER BY Fecha_de_asignacion ASC LIMIT 1");

		if($conn->num_rows($result1) > 0)
			list($ls) = $conn->fetchRow($result1);
		else
			$ls = $limite_superior;

		$conn->freeResult($result1);
		$result1 = $conn->query("SELECT DISTINCT Trabajador FROM Servicio_Trabajador LEFT JOIN Servicio_Empresa ON Servicio_Trabajador.Servicio = Servicio_Empresa.Servicio WHERE DATEDIFF('$ls', Servicio_Empresa.Fecha_de_asignacion) >= 0 AND Servicio_Empresa.Empresa = '$rfc' AND Servicio_Empresa.Servicio = '$servicio' AND Servicio_Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}'");

		while(list($trabajador) = $conn->fetchRow($result1))
		{
			$result2 = $conn->query("SELECT Trabajador.Nombre, Trabajador.CURP FROM Trabajador WHERE Trabajador.RFC = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($nombre,$curp) = $conn->fetchRow($result2);
			$conn->freeResult($result2);
			$result2 = $conn->query("SELECT Tipo.Tipo FROM Servicio LEFT JOIN Tipo ON Servicio.id = Tipo.Servicio WHERE Tipo.Trabajador = '$trabajador' AND Tipo.Tipo = 'Asalariado' AND DATEDIFF('$ls',Tipo.Fecha) >= 0 AND Servicio.id = '$servicio' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Tipo.Cuenta = '{$_SESSION['cuenta']}' LIMIT 1");
			list($tipo) = $conn->fetchRow($result2);
			$conn->freeResult($result2);
			//Bajas y reingresos
			$result2 = $conn->query("SELECT Baja.Servicio, Baja.Fecha_de_baja, Baja.Fecha_de_reingreso FROM Servicio LEFT JOIN Baja ON Servicio.id = Baja.Servicio WHERE Baja.Trabajador = '$trabajador' AND DATEDIFF('$li',Baja.Fecha_de_baja) <= 0 AND DATEDIFF(Baja.Fecha_de_baja, '$ls') <= 0 AND Baja.Servicio = '$servicio' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Baja.Cuenta = '{$_SESSION['cuenta']}'");
			$bajas = '';

			while(list($_servicio, $baja, $reingreso) = $conn->fetchRow($result2))
				$bajas .= "Servicio: $_servicio<br/>Baja: $baja<br/>Reingreso: $reingreso<br/>";

			$conn->freeResult($result2);
			//Fecha de ingreso
			$result2 = $conn->query("SELECT Antiguedad_IMSS FROM Servicio WHERE id = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($antiguedad) = $conn->fetchRow($result2);
			$conn->freeResult($result2);

			if($antiguedad == 'Servicio')
				$result2 = $conn->query("SELECT Servicio, Fecha_de_ingreso_servicio FROM Servicio_Trabajador WHERE Trabajador = '$trabajador' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
			else
				$result2 = $conn->query("SELECT Servicio, Fecha_de_ingreso_cliente FROM Servicio_Trabajador WHERE Trabajador = '$trabajador' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");

			$ingreso = "";

			while(list($_servicio, $_ingreso) = $conn->fetchRow($result2))
				$ingreso .= "Servicio: $_servicio<br/>$_ingreso<br/>";

			$conn->freeResult($result2);

			if(isset($tipo))
			{
				//Vacaciones y prima vacacional
				$result2 = $conn->query("SELECT Recibo_de_vacaciones.Vacaciones, Recibo_de_vacaciones.Prima_vacacional, Recibo_de_vacaciones.Compensacion, Recibo_de_vacaciones.ISR, Recibo_de_vacaciones.Saldo FROM Servicio LEFT JOIN Recibo_de_vacaciones ON Servicio.id = Recibo_de_vacaciones.Servicio WHERE DATEDIFF(Recibo_de_vacaciones.Fecha, '$li') >=0 AND DATEDIFF(Recibo_de_vacaciones.Fecha, '$ls') <=0 AND Recibo_de_vacaciones.Trabajador = '$trabajador' AND Servicio.id = '$servicio' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Recibo_de_vacaciones.Cuenta = '{$_SESSION['cuenta']}'");
				$vacaciones_ = 0;
				$prima_vacacional_ = 0;
				$compensacion_vacaciones_ = 0;
				$isr_vacaciones_ = 0;
				$saldo_vacaciones_ = 0;

				while(list($vacaciones, $prima_vacacional, $compensacion, $isr, $saldo) = $conn->fetchRow($result2))
				{
					$_vacaciones = total_prestacion($vacaciones);
					$vacaciones_ += $_vacaciones;
					$_prima_vacacional = total_prestacion($prima_vacacional);
					$prima_vacacional_ += $_prima_vacacional;
					$compensacion_vacaciones_ += $compensacion;
					$isr_vacaciones_ += $isr;
					$saldo_vacaciones_ += $saldo;
				}

				$conn->freeResult($result2);

				//Aguinaldo
				$result2 = $conn->query("SELECT aguinaldo_asalariados.Aguinaldo_ordinario, aguinaldo_asalariados.Gratificacion_adicional, aguinaldo_asalariados.ISR, aguinaldo_asalariados.Saldo FROM Servicio LEFT JOIN Aguinaldo ON Servicio.id = Aguinaldo.Servicio LEFT JOIN aguinaldo_asalariados ON Aguinaldo.id = aguinaldo_asalariados.Aguinaldo WHERE DATEDIFF(Aguinaldo.Fecha_de_pago,'$li') >= 0 AND DATEDIFF(Aguinaldo.Fecha_de_pago, '$ls') <= 0 AND aguinaldo_asalariados.Trabajador = '$trabajador' AND Aguinaldo.Servicio = '$servicio' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Aguinaldo.Cuenta = '{$_SESSION['cuenta']}' AND aguinaldo_asalariados.Cuenta = '{$_SESSION['cuenta']}'");
				$aguinaldo_ordinario_ = 0;
				$gratificacion_adicional_ = 0;
				$isr_aguinaldo_ = 0;
				$saldo_aguinaldo_ = 0;

				while(list($aguinaldo_ordinario, $gratificacion_adicional, $isr, $saldo) = $conn->fetchRow($result2))
				{
					$aguinaldo_ordinario_ += $aguinaldo_ordinario;
					$gratificacion_adicional_ += $gratificacion_adicional;
					$isr_aguinaldo_ += $isr;
					$saldo_aguinaldo_ += $saldo;
				}

				$conn->freeResult($result2);

				//registros de nómina
				$result2 = $conn->query("SELECT Nomina.id, Nomina.Limite_inferior_del_periodo, Nomina.Limite_superior_del_periodo, ISRasalariados.Numero_de_dias_laborados, nomina_asalariados.Sueldo, ISRasalariados.Subsidio_al_empleo, ISRasalariados.Subsidio, nomina_asalariados.Horas_extra, ISRasalariados.Horas_extra_grabadas, nomina_asalariados.Prima_dominical, nomina_asalariados.Dias_de_descanso, nomina_asalariados.Premios_de_puntualidad_y_asistencia, nomina_asalariados.Bonos_de_productividad, nomina_asalariados.Estimulos, nomina_asalariados.Compensaciones, nomina_asalariados.Despensa, nomina_asalariados.Comida, nomina_asalariados.Alimentacion, nomina_asalariados.Habitacion, nomina_asalariados.Aportacion_patronal_al_fondo_de_ahorro, ISRasalariados.ISR, ISRasalariados.Impuesto_determinado, nomina_asalariados.Cuotas_IMSS, nomina_asalariados.Retencion_por_alimentacion, nomina_asalariados.Retencion_por_habitacion, nomina_asalariados.Retencion_INFONAVIT, nomina_asalariados.Retencion_FONACOT, nomina_asalariados.Aportacion_del_trabajador_al_fondo_de_ahorro, nomina_asalariados.Pension_alimenticia, nomina_asalariados.Retardos, nomina_asalariados.Saldo, nomina_asalariados.Pago_por_seguro_de_vida, nomina_asalariados.Prestamo_del_fondo_de_ahorro, nomina_asalariados.Prestamo_caja, nomina_asalariados.Prestamo_cliente, nomina_asalariados.Prestamo_administradora, nomina_asalariados.Neto_a_recibir FROM Nomina LEFT JOIN ISRasalariados ON Nomina.id = ISRasalariados.Nomina LEFT JOIN nomina_asalariados ON Nomina.id = nomina_asalariados.Nomina WHERE ((DATEDIFF(Nomina.Limite_inferior_del_periodo, '$li') >= 0 AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$ls') <= 0) OR (DATEDIFF(Nomina.Limite_superior_del_periodo, '$li') >= 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$ls') <= 0)) AND ISRasalariados.Trabajador = '$trabajador' AND nomina_asalariados.Trabajador = '$trabajador' AND Nomina.Servicio = '$servicio' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND ISRasalariados.Cuenta = '{$_SESSION['cuenta']}' AND nomina_asalariados.Cuenta = '{$_SESSION['cuenta']}'");
				$numero_de_dias_laborados_ = 0;
				$sueldo_ = 0;
				$subsidio_al_empleo_ = 0;
				$subsidio_ = 0;
				$horas_extra_exentas_ = 0;
				$horas_extra_grabadas_ = 0;
				$prima_dominical_ = 0;
				$dias_de_descanso_ = 0;
				$premios_de_puntualidad_y_asistencia_ = 0;
				$bonos_de_productividad_ = 0;
				$estimulos_ = 0;
				$compensaciones_nomina_ = 0;
				$despensa_ = 0;
				$comida_ = 0;
				$alimentacion_ = 0;
				$habitacion_ = 0;
				$aportacion_patronal_al_fondo_de_ahorro_ = 0;
				$isr_nomina_ = 0;
				$impuesto_determinado_ = 0;
				$cuotas_imss_ = 0;
				$retencion_por_alimentacion_ = 0;
				$retencion_por_habitacion_ = 0;
				$retencion_infonavit_ = 0;
				$retencion_fonacot_ = 0;
				$aportacion_del_trabajador_al_fondo_de_ahorro_ = 0;
				$pension_alimenticia_ = 0;
				$retardos_ = 0;
				$saldo_nomina_ = 0;
				$pago_por_seguro_de_vida_ = 0;
				$prestamo_del_fondo_de_ahorro_ = 0;
				$prestamo_caja_ = 0;
				$prestamo_cliente_ = 0;
				$prestamo_administradora_ = 0;
				$neto_a_recibir_ = 0;
				$j = 1;
				$n = $conn->num_rows($result2);

				while(list($nomina, $lip, $lsp, $numero_de_dias_laborados, $sueldo, $subsidio_al_empleo,$subsidio, $horas_extra_exentas, $horas_extra_grabadas, $prima_dominical, $dias_de_descanso, $premios_de_puntualidad_y_asistencia, $bonos_de_productividad, $estimulos, $compensaciones, $despensa, $comida, $alimentacion, $habitacion, $aportacion_patronal_al_fondo_de_ahorro, $isr, $impuesto_determinado, $cuotas_imss, $retencion_por_alimentacion, $retencion_por_habitacion, $retencion_infonavit, $retencion_fonacot, $aportacion_del_trabajador_al_fondo_de_ahorro, $pension_alimenticia, $retardos, $saldo, $pago_por_seguro_de_vida, $prestamo_del_fondo_de_ahorro, $prestamo_caja, $prestamo_cliente, $prestamo_administradora, $neto_a_recibir) = $conn->fetchRow($result2))
				{
					$horas_extra_exentas -= $horas_extra_grabadas;
					$_aportacion_patronal_al_fondo_de_ahorro = total($aportacion_patronal_al_fondo_de_ahorro);
					$_retencion_infonavit = total($retencion_infonavit);
					$_retencion_fonacot = total($retencion_fonacot);
					$_aportacion_del_trabajador_al_fondo_de_ahorro = total($aportacion_del_trabajador_al_fondo_de_ahorro);
					$_pension_alimenticia = total($pension_alimenticia);
					$_pago_por_seguro_de_vida = total($pago_por_seguro_de_vida);
					$_prestamo_del_fondo_de_ahorro = total($prestamo_del_fondo_de_ahorro);
					$_prestamo_caja = total($prestamo_caja);
					$_prestamo_cliente = total($prestamo_cliente);
					$_prestamo_administradora = total($prestamo_administradora);

					if($lip < $li || $lsp > $ls)
					{
						$numero_de_dias_laborados = prop($numero_de_dias_laborados, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$sueldo = prop($sueldo, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$subsidio_al_empleo = prop($subsidio_al_empleo, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$subsidio = prop($subsidio, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$horas_extra_exentas = prop($horas_extra_exentas, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$horas_extra_grabadas = prop($horas_extra_grabadas, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$prima_dominical = prop($prima_dominical, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$dias_de_descanso = prop($dias_de_descanso, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$premios_de_puntualidad_y_asistencia = prop($premios_de_puntualidad_y_asistencia, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$bonos_de_productividad = prop($bonos_de_productividad, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$estimulos = prop($estimulos, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$compensaciones = prop($compensaciones, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$despensa = prop($despensa, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$comida = prop($comida, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$alimentacion = prop($alimentacion, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$habitacion = prop($habitacion, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_aportacion_patronal_al_fondo_de_ahorro = prop($_aportacion_patronal_al_fondo_de_ahorro, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$isr = prop($isr, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$impuesto_determinado = prop($impuesto_determinado, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$cuotas_imss = prop($cuotas_imss, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$retencion_por_alimentacion = prop($retencion_por_alimentacion, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$retencion_por_habitacion = prop($retencion_por_habitacion, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_retencion_infonavit = prop($_retencion_infonavit, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_retencion_fonacot = prop($_retencion_fonacot, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_aportacion_del_trabajador_al_fondo_de_ahorro = prop($_aportacion_del_trabajador_al_fondo_de_ahorro, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_pension_alimenticia = prop($_pension_alimenticia, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$retardos = prop($retardos, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$saldo = prop($saldo, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_pago_por_seguro_de_vida = prop($_pago_por_seguro_de_vida, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_prestamo_del_fondo_de_ahorro = prop($_prestamo_del_fondo_de_ahorro, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_prestamo_caja = prop($_prestamo_caja, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_prestamo_cliente = prop($_prestamo_cliente, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_prestamo_administradora = prop($_prestamo_administradora, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$neto_a_recibir = prop($neto_a_recibir, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
					}

					$numero_de_dias_laborados_ += $numero_de_dias_laborados;
					$sueldo_ += $sueldo;
					$subsidio_al_empleo_ += $subsidio_al_empleo;
					$subsidio_ += $subsidio;
					$horas_extra_exentas_ += $horas_extra_exentas;
					$horas_extra_grabadas_ += $horas_extra_grabadas;
					$prima_dominical_ += $prima_dominical;
					$dias_de_descanso_ += $dias_de_descanso;
					$premios_de_puntualidad_y_asistencia_ += $premios_de_puntualidad_y_asistencia;
					$bonos_de_productividad_ += $bonos_de_productividad;
					$estimulos_ += $estimulos;
					$compensaciones_nomina_ += $compensaciones;
					$despensa_ += $despensa;
					$comida_ += $comida;
					$alimentacion_ += $alimentacion;
					$habitacion_ += $habitacion;
					$aportacion_patronal_al_fondo_de_ahorro_ += $_aportacion_patronal_al_fondo_de_ahorro;
					$isr_nomina_ += $isr;
					$impuesto_determinado_ += $impuesto_determinado;
					$cuotas_imss_ += $cuotas_imss;
					$retencion_por_alimentacion_ += $retencion_por_alimentacion;
					$retencion_por_habitacion_ += $retencion_por_habitacion;
					$retencion_infonavit_ += $_retencion_infonavit;
					$retencion_fonacot_ += $_retencion_fonacot;
					$aportacion_del_trabajador_al_fondo_de_ahorro_ += $_aportacion_del_trabajador_al_fondo_de_ahorro;
					$pension_alimenticia_ += $_pension_alimenticia;
					$retardos_ += $retardos;
					$saldo_nomina_ += $saldo;
					$pago_por_seguro_de_vida_ += $_pago_por_seguro_de_vida;
					$prestamo_del_fondo_de_ahorro_ += $_prestamo_del_fondo_de_ahorro;
					$prestamo_caja_ += $_prestamo_caja;
					$prestamo_cliente_ += $_prestamo_cliente;
					$prestamo_administradora_ += $_prestamo_administradora;
					$neto_a_recibir_ += $neto_a_recibir;
				}

				//provisión de prestaciones
				$result2 = $conn->query("SELECT Nomina.id, Nomina.Limite_inferior_del_periodo, Nomina.Limite_superior_del_periodo, prestaciones.Retencion_proporcional_de_vacaciones, prestaciones.Retencion_proporcional_de_prima_vacacional, prestaciones.Retencion_proporcional_de_aguinaldo, prestaciones.Retencion_proporcional_de_prima_de_antiguedad, prestaciones.Total_de_retenciones FROM Nomina LEFT JOIN prestaciones ON Nomina.id = prestaciones.Nomina WHERE ((DATEDIFF(Nomina.Limite_inferior_del_periodo, '$li') >= 0 AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$ls') <= 0) OR (DATEDIFF(Nomina.Limite_superior_del_periodo, '$li') >= 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$ls') <= 0)) AND prestaciones.Trabajador = '$trabajador' AND Nomina.Servicio = '$servicio' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND prestaciones.Cuenta = '{$_SESSION['cuenta']}'");
				$retencion_vacaciones_ = 0;
				$retencion_prima_vacacional_ = 0;
				$retencion_aguinaldo_ = 0;
				$retencion_prima_de_antiguedad_ = 0;
				$retencion_prestaciones_ = 0;
				$j = 1;
				$n = $conn->num_rows($result2);

				while(list($nomina, $lip, $lsp, $retencion_vacaciones, $retencion_prima_vacacional, $retencion_aguinaldo, $retencion_prima_de_antiguedad, $retencion_prestaciones) = $conn->fetchRow($result2))
				{

					if($lip < $li || $lsp > $ls)
					{
						$retencion_vacaciones = prop($retencion_vacaciones, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$retencion_prima_vacacional = prop($retencion_prima_vacacional, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$retencion_aguinaldo = prop($retencion_aguinaldo, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$retencion_prima_de_antiguedad = prop($retencion_prima_de_antiguedad, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$retencion_prestaciones = prop($retencion_prestaciones, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
					}

					$retencion_vacaciones_ += $retencion_vacaciones;
					$retencion_prima_vacacional_ += $retencion_prima_vacacional;
					$retencion_aguinaldo_ += $retencion_aguinaldo;
					$retencion_prima_de_antiguedad_ += $retencion_prima_de_antiguedad;
					$retencion_prestaciones_ += $retencion_prestaciones;
				}

				$total_vacaciones += $vacaciones_;
				$total_prima_vacacional += $prima_vacacional_;
				$total_compensacion_vacaciones += $compensacion_vacaciones_;
				$total_isr_vacaciones += $isr_vacaciones_;
				$total_saldo_vacaciones += $saldo_vacaciones_;
				$total_aguinaldo_ordinario += $aguinaldo_ordinario_;
				$total_gratificacion_adicional += $gratificacion_adicional_;
				$total_isr_aguinaldo += $isr_aguinaldo_;
				$total_saldo_aguinaldo += $saldo_aguinaldo_;
				$total_numero_de_dias_laborados += $numero_de_dias_laborados_;
				$total_sueldo += $sueldo_;
				$total_subsidio_al_empleo += $subsidio_al_empleo_;
				$total_subsidio += $subsidio_;
				$total_horas_extra_exentas += $horas_extra_exentas_;
				$total_horas_extra_grabadas += $horas_extra_grabadas_;
				$total_prima_dominical += $prima_dominical_;
				$total_dias_de_descanso += $dias_de_descanso_;
				$total_premios_de_puntualidad_y_asistencia += $premios_de_puntualidad_y_asistencia_;
				$total_bonos_de_productividad += $bonos_de_productividad_;
				$total_estimulos += $estimulos_;
				$total_compensaciones_nomina += $compensaciones_nomina_;
				$total_despensa += $despensa_;
				$total_comida += $comida_;
				$total_alimentacion += $alimentacion_;
				$total_habitacion += $habitacion_;
				$total_aportacion_patronal_al_fondo_de_ahorro += $aportacion_patronal_al_fondo_de_ahorro_;
				$total_isr_nomina += $isr_nomina_;
				$total_impuesto_determinado += $impuesto_determinado_;
				$total_cuotas_imss += $cuotas_imss_;
				$total_retencion_por_alimentacion += $retencion_por_alimentacion_;
				$total_retencion_por_habitacion += $retencion_por_habitacion_;
				$total_retencion_infonavit += $retencion_infonavit_;
				$total_retencion_fonacot += $retencion_fonacot_;
				$total_aportacion_del_trabajador_al_fondo_de_ahorro += $aportacion_del_trabajador_al_fondo_de_ahorro_;
				$total_pension_alimenticia += $pension_alimenticia_;
				$total_retardos += $retardos_;
				$total_saldo_nomina += $saldo_nomina_;
				$total_pago_por_seguro_de_vida += $pago_por_seguro_de_vida_;
				$total_prestamo_del_fondo_de_ahorro += $prestamo_del_fondo_de_ahorro_;
				$total_prestamo_caja += $prestamo_caja_;
				$total_prestamo_cliente += $prestamo_cliente_;
				$total_prestamo_administradora += $prestamo_administradora_;
				$total_neto_a_recibir += $neto_a_recibir_;
				$total_retencion_vacaciones += $retencion_vacaciones_;
				$total_retencion_prima_vacacional += $retencion_prima_vacacional_;
				$total_retencion_aguinaldo += $retencion_aguinaldo_;
				$total_retencion_prima_de_antiguedad += $retencion_prima_de_antiguedad_;
				$total_retencion_prestaciones += $retencion_prestaciones_;
				$total_diferencia_vacaciones += $retencion_vacaciones_ - $vacaciones_;
				$total_diferencia_prima_vacacional += $retencion_prima_vacacional_ - $prima_vacacional_;
				$total_diferencia_aguinaldo += $retencion_aguinaldo_ - $aguinaldo_ordinario_;

				if($saldo_nomina_ > 0 || $retencion_prestaciones_ > 0)
				{
					$txt .= "<tr style = 'text-align:right'><td style = 'text-align:center'>$i</td><td style = 'text-align:center'>$nombre</td><td>$trabajador</td><td>$curp</td><td>$tipo</td><td>$bajas</td><td>$ingreso</td><td>" . number_format($vacaciones_,2,'.',',') . "</td><td>" . number_format($prima_vacacional_,2,'.',',') . "</td><td>" . number_format($compensacion_vacaciones_,2,'.',',') . "</td><td>" . number_format($isr_vacaciones_,2,'.',',') . "</td><td>" . number_format($saldo_vacaciones_,2,'.',',') . "</td><td>" . number_format($aguinaldo_ordinario_,2,'.',',') . "</td><td>" . number_format($gratificacion_adicional_,2,'.',',') . "</td><td>" . number_format($isr_aguinaldo_,2,'.',',') . "</td><td>" . number_format($saldo_aguinaldo_,2,'.',',') . "</td><td>" . $numero_de_dias_laborados_ . "</td><td>" . number_format($sueldo_,2,'.',',') . "</td><td>" . number_format($subsidio_al_empleo_,2,'.',',') . "</td><td>" . number_format($subsidio_,2,'.',',') . "</td><td>" . number_format($horas_extra_exentas_,2,'.',',') . "</td><td>" . number_format($horas_extra_grabadas_,2,'.',',') . "</td><td>" . number_format($prima_dominical_,2,'.',',') . "</td><td>" . number_format($dias_de_descanso_,2,'.',',') . "</td><td>" . number_format($premios_de_puntualidad_y_asistencia_,2,'.',',') . "</td><td>" . number_format($bonos_de_productividad_,2,'.',',') . "</td><td>" . number_format($estimulos_,2,'.',',') . "</td><td>" . number_format($compensaciones_nomina_,2,'.',',') . "</td><td>" . number_format($despensa_,2,'.',',') . "</td><td>" . number_format($comida_,2,'.',',') . "</td><td>" . number_format($alimentacion_,2,'.',',') . "</td><td>" . number_format($habitacion_,2,'.',',') . "</td><td>" . number_format($aportacion_patronal_al_fondo_de_ahorro_,2,'.',',') . "</td><td>" . number_format($isr_nomina_,2,'.',',') . "</td><td>" . number_format($impuesto_determinado_,2,'.',',') . "</td><td>" . number_format($cuotas_imss_,2,'.',',') . "</td><td>" . number_format($retencion_por_alimentacion_,2,'.',',') . "</td><td>" . number_format($retencion_por_habitacion_,2,'.',',') . "</td><td>" . number_format($retencion_infonavit_,2,'.',',') . "</td><td>" . number_format($retencion_fonacot_,2,'.',',') . "</td><td>" . number_format($aportacion_del_trabajador_al_fondo_de_ahorro_,2,'.',',') . "</td><td>" . number_format($pension_alimenticia_,2,'.',',') . "</td><td>" . number_format($retardos_,2,'.',',') . "</td><td>" . number_format($saldo_nomina_,2,'.',',') . "</td><td>" . number_format($pago_por_seguro_de_vida_,2,'.',',') . "</td><td>" . number_format($prestamo_del_fondo_de_ahorro_,2,'.',',') . "</td><td>" . number_format($prestamo_caja_,2,'.',',') . "</td><td>" . number_format($prestamo_cliente_,2,'.',',') . "</td><td>" . number_format($prestamo_administradora_,2,'.',',') . "</td><td>" . number_format($neto_a_recibir_,2,'.',',') . "</td></tr>";
					$i++;
				}

				$conn->freeResult($result2);
			}

			$tipo = NULL;
			$result2 = $conn->query("SELECT Tipo.Tipo FROM Servicio LEFT JOIN Tipo ON Servicio.id = Tipo.Servicio WHERE Tipo.Trabajador = '$trabajador' AND Tipo.Tipo = 'Asimilable' AND DATEDIFF('$ls',Tipo.Fecha) >= 0 AND Servicio.id = '$servicio' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Tipo.Cuenta = '{$_SESSION['cuenta']}' LIMIT 1");
			list($tipo) = $conn->fetchRow($result2);
			$conn->freeResult($result2);

			if(isset($tipo))
			{
				//registros de nómina
				$result2 = $conn->query("SELECT Nomina.id, Nomina.Limite_inferior_del_periodo, Nomina.Limite_superior_del_periodo, ISRasimilables.Numero_de_dias_laborados, nomina_asimilables.Honorarios_asimilados, ISRasimilables.Subsidio_al_empleo, ISRasimilables.Subsidio, ISRasimilables.ISR, ISRasimilables.Impuesto_determinado, nomina_asimilables.Saldo, nomina_asimilables.Pago_por_seguro_de_vida, nomina_asimilables.Prestamo_caja, nomina_asimilables.Prestamo_cliente, nomina_asimilables.Prestamo_administradora, nomina_asimilables.Neto_a_recibir FROM Nomina LEFT JOIN ISRasimilables ON Nomina.id = ISRasimilables.Nomina LEFT JOIN nomina_asimilables ON Nomina.id = nomina_asimilables.Nomina LEFT JOIN Servicio ON Nomina.Servicio = Servicio.id WHERE ((DATEDIFF(Nomina.Limite_inferior_del_periodo, '$li') >= 0 AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$ls') <= 0) OR (DATEDIFF(Nomina.Limite_superior_del_periodo, '$li') >= 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$ls') <= 0)) AND ISRasimilables.Trabajador = '$trabajador' AND nomina_asimilables.Trabajador = '$trabajador' AND Servicio.id = '$servicio' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND ISRasimilables.Cuenta = '{$_SESSION['cuenta']}' AND nomina_asimilables.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}'");
				$numero_de_dias_laborados_ = 0;
				$honorarios_ = 0;
				$subsidio_al_empleo_ = 0;
				$subsidio_ = 0;
				$isr_nomina_ = 0;
				$impuesto_determinado_ = 0;
				$saldo_nomina_ = 0;
				$pago_por_seguro_de_vida_ = 0;
				$prestamo_caja_ = 0;
				$prestamo_cliente_ = 0;
				$prestamo_administradora_ = 0;
				$neto_a_recibir_ = 0;
				$j = 1;
				$n = $conn->num_rows($result2);

				while(list($nomina, $lip, $lsp, $numero_de_dias_laborados, $honorarios, $subsidio_al_empleo, $subsidio, $isr, $impuesto_determinado, $saldo, $pago_por_seguro_de_vida, $prestamo_caja, $prestamo_cliente, $prestamo_administradora, $neto_a_recibir) = $conn->fetchRow($result2))
				{
					$_pago_por_seguro_de_vida = total($pago_por_seguro_de_vida);
					$_prestamo_caja = total($prestamo_caja);
					$_prestamo_cliente = total($prestamo_cliente);
					$_prestamo_administradora = total($prestamo_administradora);

					if($lip < $li || $lsp > $ls)
					{
						$numero_de_dias_laborados = prop($numero_de_dias_laborados, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$honorarios = prop($honorarios, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$subsidio_al_empleo = prop($subsidio_al_empleo, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$isr = prop($isr, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$impuesto_determinado = prop($impuesto_determinado, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$saldo = prop($saldo, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_pago_por_seguro_de_vida = prop($_pago_por_seguro_de_vida, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_prestamo_caja = prop($_prestamo_caja, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_prestamo_cliente = prop($_prestamo_cliente, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$_prestamo_administradora = prop($_prestamo_administradora, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
						$neto_a_recibir = prop($neto_a_recibir, $li, $ls, $lip, $lsp, $trabajador, $tipo, $nomina, $servicio);
					}

					$numero_de_dias_laborados_ += $numero_de_dias_laborados;
					$honorarios_ += $honorarios;
					$subsidio_al_empleo_ += $subsidio_al_empleo;
					$subsidio_ += $subsidio_al_empleo;
					$isr_nomina_ += $isr;
					$impuesto_determinado_ += $impuesto_determinado;
					$saldo_nomina_ += $saldo;
					$pago_por_seguro_de_vida_ += $_pago_por_seguro_de_vida;
					$prestamo_caja_ += $_prestamo_caja;
					$prestamo_cliente_ += $_prestamo_cliente;
					$prestamo_administradora_ += $_prestamo_administradora;
					$neto_a_recibir_ += $neto_a_recibir;
				}

				$total_numero_de_dias_laborados += $numero_de_dias_laborados_;
				$total_sueldo += $honorarios_;
				$total_subsidio_al_empleo += $subsidio_al_empleo_;
				$total_subsidio += $subsidio_;
				$total_isr_nomina += $isr_nomina_;
				$total_impuesto_determinado += $impuesto_determinado_;
				$total_saldo_nomina += $saldo_nomina_;
				$total_pago_por_seguro_de_vida += $pago_por_seguro_de_vida_;
				$total_prestamo_caja += $prestamo_caja_;
				$total_prestamo_cliente += $prestamo_cliente_;
				$total_prestamo_administradora += $prestamo_administradora_;
				$total_neto_a_recibir += $neto_a_recibir_;

				if($saldo_nomina_ > 0)
				{
					$txt .= "<tr style = 'text-align:right'><td style = 'text-align:center'>$i</td><td style = 'text-align:center'>$nombre</td><td>$trabajador</td><td>$curp</td><td>$tipo</td><td>$bajas</td><td>$ingreso</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>$numero_de_dias_laborados_</td><td>" . number_format($honorarios_,2,'.',',') . "</td><td>" . number_format($subsidio_al_empleo_,2,'.',',') . "</td><td>" . number_format($subsidio_,2,'.',',') . "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>" . number_format($isr_nomina_,2,'.',',') . "</td><td>" . number_format($impuesto_determinado_,2,'.',',') . "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>" . number_format($saldo_nomina_,2,'.',',') . "</td><td>" . number_format($pago_por_seguro_de_vida_,2,'.',',') . "</td><td></td><td>" . number_format($prestamo_caja_,2,'.',',') . "</td><td>" . number_format($prestamo_cliente_,2,'.',',') . "</td><td>" . number_format($prestamo_administradora_,2,'.',',') . "</td><td>" . number_format($neto_a_recibir_,2,'.',',') . "</td></tr>";
					$i++;
				}

				$conn->freeResult($result2);
			}

		}

	}

	$txt .= "<tr class = 'totals'><td style = 'background:#fff'></td><td style = 'background:#fff'></td><td style = 'background:#fff'></td><td style = 'background:#fff'></td><td style = 'background:#fff'></td><td style = 'background:#fff'></td><td style = 'text-align:center'>Total</td><td>" . number_format($total_vacaciones,2,'.',',') . "</td><td>" . number_format($total_prima_vacacional,2,'.',',') . "</td><td>" . number_format($total_compensacion_vacaciones,2,'.',',') . "</td><td>" . number_format($total_isr_vacaciones,2,'.',',') . "</td><td>" . number_format($total_saldo_vacaciones,2,'.',',') . "</td><td>" . number_format($total_aguinaldo_ordinario,2,'.',',') . "</td><td>" . number_format($total_gratificacion_adicional,2,'.',',') . "</td><td>" . number_format($total_isr_aguinaldo,2,'.',',') . "</td><td>" . number_format($total_saldo_aguinaldo,2,'.',',') . "</td><td>" . $total_numero_de_dias_laborados . "</td><td>" . number_format($total_sueldo,2,'.',',') . "</td><td>" . number_format($total_subsidio_al_empleo,2,'.',',') . "</td><td>" . number_format($total_subsidio,2,'.',',') . "</td><td>" . number_format($total_horas_extra_exentas,2,'.',',') . "</td><td>" . number_format($total_horas_extra_grabadas,2,'.',',') . "</td><td>" . number_format($total_prima_dominical,2,'.',',') . "</td><td>" . number_format($total_dias_de_descanso,2,'.',',') . "</td><td>" . number_format($total_premios_de_puntualidad_y_asistencia,2,'.',',') . "</td><td>" . number_format($total_bonos_de_productividad,2,'.',',') . "</td><td>" . number_format($total_estimulos,2,'.',',') . "</td><td>" . number_format($total_compensaciones_nomina,2,'.',',') . "</td><td>" . number_format($total_despensa,2,'.',',') . "</td><td>" . number_format($total_comida,2,'.',',') . "</td><td>" . number_format($total_alimentacion,2,'.',',') . "</td><td>" . number_format($total_habitacion,2,'.',',') . "</td><td>" . number_format($total_aportacion_patronal_al_fondo_de_ahorro,2,'.',',') . "</td><td>" . number_format($total_isr_nomina,2,'.',',') . "</td><td>" . number_format($total_impuesto_determinado,2,'.',',') . "</td><td>" . number_format($total_cuotas_imss,2,'.',',') . "</td><td>" . number_format($total_retencion_por_alimentacion,2,'.',',') . "</td><td>" . number_format($total_retencion_por_habitacion,2,'.',',') . "</td><td>" . number_format($total_retencion_infonavit,2,'.',',') . "</td><td>" . number_format($total_retencion_fonacot,2,'.',',') . "</td><td>" . number_format($total_aportacion_del_trabajador_al_fondo_de_ahorro,2,'.',',') . "</td><td>" . number_format($total_pension_alimenticia,2,'.',',') . "</td><td>" . number_format($total_retardos,2,'.',',') . "</td><td>" . number_format($total_saldo_nomina,2,'.',',') . "</td><td>" . number_format($total_pago_por_seguro_de_vida,2,'.',',') . "</td><td>" . number_format($total_prestamo_del_fondo_de_ahorro,2,'.',',') . "</td><td>" . number_format($total_prestamo_caja,2,'.',',') . "</td><td>" . number_format($total_prestamo_cliente,2,'.',',') . "</td><td>" . number_format($total_prestamo_administradora,2,'.',',') . "</td><td>" . number_format($total_neto_a_recibir,2,'.',',') . "</td></tr>";//"</td><td>" . number_format($total_retencion_prestaciones,2,'.',',') . 
	$txt .= "</table>";
	echo $txt;
?>
	</body>
</html>
