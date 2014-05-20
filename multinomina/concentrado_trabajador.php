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
	$trabajador = $_GET['trabajador'];
	$limite_inferior = $_GET['limite_inferior'];
	$limite_superior = $_GET['limite_superior'];
	$servicio = $_GET['servicio'];
	$result = $conn->query("SELECT Trabajador.CURP, Trabajador.Nombre FROM Trabajador WHERE RFC = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
	list($curp,$nombre) = $conn->fetchRow($result);
	$conn->freeResult($result);
	$result = $conn->query("SELECT Tipo FROM Tipo WHERE Trabajador = '$trabajador' AND Tipo = 'Asalariado' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$limite_superior',Fecha) >= 0" . ($servicio == 'Todos' ? "" : " AND Servicio = '$servicio'") . " LIMIT 1");
	list($tipo) = $conn->fetchRow($result);
	$conn->freeResult($result);
	echo "<img src = 'images/logo_blanco.jpg'/><br/><br/>";
	echo "Trabajador: $nombre<br/>RFC: $trabajador<br/>CURP: $curp<br/>Fecha de ingreso:";
	$result = $conn->query("SELECT Servicio, Fecha_de_ingreso FROM Servicio_Trabajador WHERE Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'" . ($servicio == 'Todos' ? "" : " AND Servicio = '$servicio'"));

	while(list($id,$ingreso) = $conn->fetchRow($result))
		echo " Servicio $id: $ingreso<br/>";

	echo "Ver totales<input type = 'button' onclick = 'show_totals(this)' value = '⌖' style = 'background:#3399cc;border:none;borderRadius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;color:#fff;text-align:center;cursor:pointer'><br/><br/>";
	//Bajas
	$txt = "<table><tr style = 'background:#555;color:#fff;text-align:center'><td colspan = 3>Bajas y reingresos</td></tr><tr style = 'text-align:center;color:#fff;background:#3399cc'><td>Servicio</td><td>Fecha de baja</td><td>Fecha de reingreso</td></tr>";
	$result = $conn->query("SELECT Servicio, Fecha_de_baja, Fecha_de_reingreso FROM Baja WHERE ((DATEDIFF(Fecha_de_baja,'$limite_inferior') >= 0 AND DATEDIFF(Fecha_de_baja, '$limite_superior') <= 0) OR (DATEDIFF(Fecha_de_reingreso,'$limite_inferior') >= 0 AND DATEDIFF(Fecha_de_reingreso, '$limite_superior') <= 0)) AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'" . ($servicio == 'Todos' ? " " : " AND Servicio = '$servicio' ") . "ORDER BY Fecha_de_baja ASC");

	while(list($id, $baja, $reingreso) = $conn->fetchRow($result))
		$txt .= "<tr style='text-align:center'><td>$id</td><td>$baja</td><td>$reingreso</td></tr>";

	$txt .= "<tr><td></td><td></td><td></td></tr>";//there are not totals
	$conn->freeResult($result);
	$txt .= "</table>";

	if(isset($tipo))
	{
		//Vacaciones y prima vacacional
		$txt .= "<table><tr style = 'background:#555;color:#fff;text-align:center'><td colspan = 7>Vacaciones y prima vacacional</td></tr><tr style = 'text-align:center;color:#fff;background:#3399cc' class = 'subtitles'><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Servicio</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Fecha</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Vacaciones</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Prima vacacional</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Compensación</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>ISR</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Saldo</td></tr>";
		$result = $conn->query("SELECT Servicio, Fecha, Vacaciones, Prima_vacacional, Compensacion, ISR, Saldo FROM Recibo_de_vacaciones WHERE DATEDIFF( Fecha, '$limite_inferior' ) >=0 AND DATEDIFF( Fecha, '$limite_superior' ) <=0 AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}' " . ($servicio == 'Todos' ? "" : "AND Servicio = '$servicio' ") . "ORDER BY Fecha ASC");
		$total_vacaciones = 0;
		$total_prima_vacacional = 0;
		$total_compensacion = 0;
		$total_isr = 0;
		$total_saldo = 0;

		while(list($_servicio, $fecha, $vacaciones, $prima_vacacional, $compensacion, $isr, $saldo) = $conn->fetchRow($result))
		{
			$_vacaciones = total_prestacion($vacaciones);
			$total_vacaciones += $_vacaciones;
			$_prima_vacacional = total_prestacion($prima_vacacional);
			$total_prima_vacacional += $_prima_vacacional;
			$total_compensacion += $compensacion;
			$total_isr += $isr;
			$total_saldo += $saldo;

			$txt .= "<tr style='text-align:right'><td style = 'text-align:center'>$_servicio</td><td>$fecha</td><td>". number_format($_vacaciones,2,'.',',') . "</td><td>". number_format($_prima_vacacional,2,'.',',') . "</td><td>". number_format($compensacion,2,'.',',') . "</td><td>". number_format($isr,2,'.',',') . "</td><td>". number_format($saldo,2,'.',',') . "</td></tr>";
		}

		$txt .= "<tr class = 'totals'><td style='background:#fff'></td><td style='text-align:center'>Total</td><td>" . number_format($total_vacaciones,2,'.',',') . "</td><td>" . number_format($total_prima_vacacional,2,'.',',') . "</td><td>" . number_format($total_compensacion,2,'.',',') . "</td><td>" . number_format($total_isr,2,'.',',') . "</td><td>" . number_format($total_saldo,2,'.',',') . "</td></tr>";
		$conn->freeResult($result);
		$txt .= "</table>";

		//Aguinaldo
		$txt .= "<table><tr style = 'background:#555;color:#fff;text-align:center'><td colspan = 7>Aguinaldo</td></tr><tr style = 'text-align:center;color:#fff;background:#3399cc' class = 'subtitles'><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Servicio</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Fecha de pago</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Número de días de aguinaldo</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Aguinaldo ordinario</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Gratificación adicional</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>ISR</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Saldo</td></tr>";
		$result = $conn->query("SELECT Aguinaldo.Servicio, Aguinaldo.Fecha_de_pago, aguinaldo_asalariados.Numero_de_dias_de_aguinaldo, aguinaldo_asalariados.Aguinaldo_ordinario, aguinaldo_asalariados.Gratificacion_adicional, aguinaldo_asalariados.ISR, aguinaldo_asalariados.Saldo FROM Aguinaldo LEFT JOIN aguinaldo_asalariados ON Aguinaldo.id = aguinaldo_asalariados.Aguinaldo WHERE DATEDIFF(Aguinaldo.Fecha_de_pago,'$limite_inferior') >= 0 AND DATEDIFF(Aguinaldo.Fecha_de_pago, '$limite_superior') <= 0 AND aguinaldo_asalariados.Trabajador = '$trabajador' AND Aguinaldo.Cuenta = '{$_SESSION['cuenta']}' AND aguinaldo_asalariados.Cuenta = '{$_SESSION['cuenta']}' " . ($servicio == 'Todos' ? "": "AND Aguinaldo.Servicio = '$servicio' ") . "ORDER BY Aguinaldo.Fecha_de_pago ASC");
		$total_numero_de_dias_de_aguinaldo = 0;
		$total_aguinaldo_ordinario = 0;
		$total_gratificacion_adicional = 0;
		$total_isr = 0;
		$total_saldo = 0;

		while(list($_servicio, $fecha_de_pago, $numero_de_dias_de_aguinaldo, $aguinaldo_ordinario, $gratificacion_adicional, $isr, $saldo) = $conn->fetchRow($result))
		{
			$total_numero_de_dias_de_aguinaldo += $numero_de_dias_de_aguinaldo;
			$total_aguinaldo_ordinario += $aguinaldo_ordinario;
			$total_gratificacion_adicional += $gratificacion_adicional;
			$total_isr += $isr;
			$total_saldo += $saldo;

			$txt .= "<tr style='text-align:right'><td>$_servicio</td><td>$fecha_de_pago</td><td>$numero_de_dias_de_aguinaldo</td><td>". number_format($aguinaldo_ordinario,2,'.',',') . "</td><td>". number_format($gratificacion_adicional,2,'.',',') . "</td><td>". number_format($isr,2,'.',',') . "</td><td>". number_format($saldo,2,'.',',') . "</td></tr>";
		}

		$txt .= "<tr class = 'totals'><td style='background:#fff'></td><td style='text-align:center'>Total</td><td>$total_numero_de_dias_de_aguinaldo</td><td>". number_format($total_aguinaldo_ordinario,2,'.',',') . "</td><td>". number_format($total_gratificacion_adicional,2,'.',',') . "</td><td>". number_format($total_isr,2,'.',',') . "</td><td>". number_format($total_saldo,2,'.',',') . "</td></tr>";
		$conn->freeResult($result);
		$txt .= "</table>";

		//registros de nómina
		$txt .= "<table><tr style = 'background:#555;color:#fff;text-align:center'><td colspan = 38>Registros de nómina asalariado</td></tr><tr style = 'text-align:center;color:#fff;background:#3399cc' class = 'subtitles'><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>No.</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Servicio</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Inicio</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Término</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Número de días laborados</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Sueldo</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Subsidio pagado</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Subsidio que se debió entregar</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Horas extra exentas</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Horas extra grabadas</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Prima dominical</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Días de descanso</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Premios de puntualidad y asistencia</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Bonos de productividad</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Estímulos</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Compensaciones</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Despensa</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Comida</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Alimentación</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Habitación</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Aportación patronal al fondo de ahorro</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>ISR Retenido</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>ISR Que se debió reter</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Cuotas IMSS</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Retención por alimentación</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Retención por habitación</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Retención INFONAVIT</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Retención FONACOT</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Aportación del trabajador al fondo de ahorro</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Pensión alimenticia</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Retardos</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Saldo</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Pago por seguro de vida</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Préstamo del fondo de ahorro</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Préstamo de caja</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Préstamo de cliente</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Préstamo de administradora</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Neto a recibir</td></tr>";
		$result = $conn->query("SELECT Nomina.id, Nomina.Servicio, Nomina.Limite_inferior_del_periodo, Nomina.Limite_superior_del_periodo, ISRasalariados.Numero_de_dias_laborados, nomina_asalariados.Sueldo, ISRasalariados.Subsidio_al_empleo,ISRasalariados.Subsidio, nomina_asalariados.Horas_extra, ISRasalariados.Horas_extra_grabadas, nomina_asalariados.Prima_dominical, nomina_asalariados.Dias_de_descanso, nomina_asalariados.Premios_de_puntualidad_y_asistencia, nomina_asalariados.Bonos_de_productividad, nomina_asalariados.Estimulos, nomina_asalariados.Compensaciones, nomina_asalariados.Despensa, nomina_asalariados.Comida, nomina_asalariados.Alimentacion, nomina_asalariados.Habitacion, nomina_asalariados.Aportacion_patronal_al_fondo_de_ahorro, ISRasalariados.ISR, ISRasalariados.Impuesto_determinado, nomina_asalariados.Cuotas_IMSS, nomina_asalariados.Retencion_por_alimentacion, nomina_asalariados.Retencion_por_habitacion, nomina_asalariados.Retencion_INFONAVIT, nomina_asalariados.Retencion_FONACOT, nomina_asalariados.Aportacion_del_trabajador_al_fondo_de_ahorro, nomina_asalariados.Pension_alimenticia, nomina_asalariados.Retardos, nomina_asalariados.Saldo, nomina_asalariados.Pago_por_seguro_de_vida, nomina_asalariados.Prestamo_del_fondo_de_ahorro, nomina_asalariados.Prestamo_caja, nomina_asalariados.Prestamo_cliente, nomina_asalariados.Prestamo_administradora, nomina_asalariados.Neto_a_recibir FROM (Nomina LEFT JOIN ISRasalariados ON Nomina.id = ISRasalariados.Nomina) LEFT JOIN nomina_asalariados ON Nomina.id = nomina_asalariados.Nomina WHERE ((DATEDIFF(Nomina.Limite_inferior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$limite_superior') <= 0) OR (DATEDIFF(Nomina.Limite_superior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$limite_superior') <= 0)) AND ISRasalariados.Trabajador = '$trabajador' AND nomina_asalariados.Trabajador = '$trabajador' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND ISRasalariados.Cuenta = '{$_SESSION['cuenta']}' AND nomina_asalariados.Cuenta = '{$_SESSION['cuenta']}' " . ($servicio == 'Todos' ? "" : "AND Nomina.Servicio = '$servicio' ") . "ORDER BY Nomina.Servicio, Nomina.Limite_inferior_del_periodo ASC");
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
		$total_compensaciones = 0;
		$total_despensa = 0;
		$total_comida = 0;
		$total_alimentacion = 0;
		$total_habitacion = 0;
		$total_aportacion_patronal_al_fondo_de_ahorro = 0;
		$total_isr = 0;
		$total_impuesto_determinado = 0;
		$total_cuotas_imss = 0;
		$total_retencion_por_alimentacion = 0;
		$total_retencion_por_habitacion = 0;
		$total_retencion_infonavit = 0;
		$total_retencion_fonacot = 0;
		$total_aportacion_del_trabajador_al_fondo_de_ahorro = 0;
		$total_pension_alimenticia = 0;
		$total_retardos = 0;
		$total_saldo = 0;
		$total_pago_por_seguro_de_vida = 0;
		$total_prestamo_del_fondo_de_ahorro = 0;
		$total_prestamo_de_caja = 0;
		$total_prestamo_de_cliente = 0;
		$total_prestamo_de_administradora = 0;
		$total_neto_a_recibir = 0;
		$i = 1;
		$n = $conn->num_rows($result);

		while(list($nomina, $_servicio, $lip, $lsp, $numero_de_dias_laborados, $sueldo, $subsidio_al_empleo,$subsidio, $horas_extra_exentas, $horas_extra_grabadas, $prima_dominical, $dias_de_descanso, $premios_de_puntualidad_y_asistencia, $bonos_de_productividad, $estimulos, $compensaciones, $despensa, $comida, $alimentacion, $habitacion, $aportacion_patronal_al_fondo_de_ahorro, $isr, $impuesto_determinado, $cuotas_imss, $retencion_por_alimentacion, $retencion_por_habitacion, $retencion_infonavit, $retencion_fonacot, $aportacion_del_trabajador_al_fondo_de_ahorro, $pension_alimenticia, $retardos, $saldo, $pago_por_seguro_de_vida, $prestamo_del_fondo_de_ahorro, $prestamo_de_caja, $prestamo_de_cliente, $prestamo_de_administradora, $neto_a_recibir) = $conn->fetchRow($result))
		{
			$horas_extra_exentas -= $horas_extra_grabadas;
			$_aportacion_patronal_al_fondo_de_ahorro = total($aportacion_patronal_al_fondo_de_ahorro);
			$_retencion_infonavit = total($retencion_infonavit);
			$_retencion_fonacot = total($retencion_fonacot);
			$_aportacion_del_trabajador_al_fondo_de_ahorro = total($aportacion_del_trabajador_al_fondo_de_ahorro);
			$_pension_alimenticia = total($pension_alimenticia);
			$_pago_por_seguro_de_vida = total($pago_por_seguro_de_vida);
			$_prestamo_del_fondo_de_ahorro = total($prestamo_del_fondo_de_ahorro);
			$_prestamo_de_caja = total($prestamo_de_caja);
			$_prestamo_de_cliente = total($prestamo_de_cliente);
			$_prestamo_de_administradora = total($prestamo_de_administradora);

			if($lip < $limite_inferior || $lsp > $limite_superior)
			{
				$numero_de_dias_laborados = prop($numero_de_dias_laborados, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$sueldo = prop($sueldo, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$subsidio_al_empleo = prop($subsidio_al_empleo, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$subsidio = prop($subsidio, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$horas_extra_exentas = prop($horas_extra_exentas, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$horas_extra_grabadas = prop($horas_extra_grabadas, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$prima_dominical = prop($prima_dominical, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$dias_de_descanso = prop($dias_de_descanso, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$premios_de_puntualidad_y_asistencia = prop($premios_de_puntualidad_y_asistencia, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$bonos_de_productividad = prop($bonos_de_productividad, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$estimulos = prop($estimulos, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$compensaciones = prop($compensaciones, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$despensa = prop($despensa, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$comida = prop($comida, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$alimentacion = prop($alimentacion, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$habitacion = prop($habitacion, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_aportacion_patronal_al_fondo_de_ahorro = prop($_aportacion_patronal_al_fondo_de_ahorro, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$isr = prop($isr, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$impuesto_determinado = prop($impuesto_determinado, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$cuotas_imss = prop($cuotas_imss, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$retencion_por_alimentacion = prop($retencion_por_alimentacion, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$retencion_por_habitacion = prop($retencion_por_habitacion, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_retencion_infonavit = prop($_retencion_infonavit, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_retencion_fonacot = prop($_retencion_fonacot, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_aportacion_del_trabajador_al_fondo_de_ahorro = prop($_aportacion_del_trabajador_al_fondo_de_ahorro, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_pension_alimenticia = prop($_pension_alimenticia, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$retardos = prop($retardos, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$saldo = prop($saldo, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_pago_por_seguro_de_vida = prop($_pago_por_seguro_de_vida, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_prestamo_del_fondo_de_ahorro = prop($_prestamo_del_fondo_de_ahorro, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_prestamo_de_caja = prop($_prestamo_de_caja, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_prestamo_de_cliente = prop($_prestamo_de_cliente, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_prestamo_de_administradora = prop($_prestamo_de_administradora, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$neto_a_recibir = prop($neto_a_recibir, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
			}

			$total_numero_de_dias_laborados += $numero_de_dias_laborados;
			$total_sueldo += $sueldo;
			$total_subsidio_al_empleo += $subsidio_al_empleo;
			$total_subsidio += $subsidio;
			$total_horas_extra_exentas += $horas_extra_exentas;
			$total_horas_extra_grabadas += $horas_extra_grabadas;
			$total_prima_dominical += $prima_dominical;
			$total_dias_de_descanso += $dias_de_descanso;
			$total_premios_de_puntualidad_y_asistencia += $premios_de_puntualidad_y_asistencia;
			$total_bonos_de_productividad += $bonos_de_productividad;
			$total_estimulos += $estimulos;
			$total_compensaciones += $compensaciones;
			$total_despensa += $despensa;
			$total_comida += $comida;
			$total_alimentacion += $alimentacion;
			$total_habitacion += $habitacion;
			$total_aportacion_patronal_al_fondo_de_ahorro += $_aportacion_patronal_al_fondo_de_ahorro;
			$total_isr += $isr;
			$total_impuesto_determinado += $impuesto_determinado;
			$total_cuotas_imss += $cuotas_imss;
			$total_retencion_por_alimentacion += $retencion_por_alimentacion;
			$total_retencion_por_habitacion += $retencion_por_habitacion;
			$total_retencion_infonavit += $_retencion_infonavit;
			$total_retencion_fonacot += $_retencion_fonacot;
			$total_aportacion_del_trabajador_al_fondo_de_ahorro += $_aportacion_del_trabajador_al_fondo_de_ahorro;
			$total_pension_alimenticia += $_pension_alimenticia;
			$total_retardos += $retardos;
			$total_saldo += $saldo;
			$total_pago_por_seguro_de_vida += $_pago_por_seguro_de_vida;
			$total_prestamo_del_fondo_de_ahorro += $_prestamo_del_fondo_de_ahorro;
			$total_prestamo_de_caja += $_prestamo_de_caja;
			$total_prestamo_de_cliente += $_prestamo_de_cliente;
			$total_prestamo_de_administradora += $_prestamo_de_administradora;
			$total_neto_a_recibir += $neto_a_recibir;

			$txt .= "<tr style='text-align:right'><td>$i</td><td>$_servicio</td><td>$lip</td><td>$lsp</td><td>$numero_de_dias_laborados</td><td>" . number_format($sueldo,2,'.',',') . "</td><td>" . number_format($subsidio_al_empleo,2,'.',',') . "</td><td>" . number_format($subsidio,2,'.',',') . "</td><td>" . number_format($horas_extra_exentas,2,'.',',') . "</td><td>" . number_format($horas_extra_grabadas,2,'.',',') . "</td><td>" . number_format($prima_dominical,2,'.',',') . "</td><td>" . number_format($dias_de_descanso,2,'.',',') . "</td><td>" . number_format($premios_de_puntualidad_y_asistencia,2,'.',',') . "</td><td>" . number_format($bonos_de_productividad,2,'.',',') . "</td><td>" . number_format($estimulos,2,'.',',') . "</td><td>" . number_format($compensaciones,2,'.',',') . "</td><td>" . number_format($despensa,2,'.',',') . "</td><td>" . number_format($comida,2,'.',',') . "</td><td>" . number_format($alimentacion,2,'.',',') . "</td><td>" . number_format($habitacion,2,'.',',') . "</td><td>" . number_format($_aportacion_patronal_al_fondo_de_ahorro,2,'.',',') . "</td><td>" . number_format($isr,2,'.',',') . "</td><td>" . number_format($impuesto_determinado,2,'.',',') . "</td><td>" . number_format($cuotas_imss,2,'.',',') . "</td><td>" . number_format($retencion_por_alimentacion,2,'.',',') . "</td><td>" . number_format($retencion_por_habitacion,2,'.',',') . "</td><td>" . number_format($_retencion_infonavit,2,'.',',') . "</td><td>" . number_format($_retencion_fonacot,2,'.',',') . "</td><td>" . number_format($_aportacion_del_trabajador_al_fondo_de_ahorro,2,'.',',') . "</td><td>" . number_format($_pension_alimenticia,2,'.',',') . "</td><td>" . number_format($retardos,2,'.',',') . "</td><td>" . number_format($saldo,2,'.',',') . "</td><td>" . number_format($_pago_por_seguro_de_vida,2,'.',',') . "</td><td>" . number_format($_prestamo_del_fondo_de_ahorro,2,'.',',') . "</td><td>" . number_format($_prestamo_de_caja,2,'.',',') . "</td><td>" . number_format($_prestamo_de_cliente,2,'.',',') . "</td><td>" . number_format($_prestamo_de_administradora,2,'.',',') . "</td><td>" . number_format($neto_a_recibir,2,'.',',') . "</td></tr>";
			$i++;
		}

		$txt .= "<tr class = 'totals'><td style='background:#fff'></td><td style='background:#fff'></td><td style='background:#fff'></td><td style='text-align:center'>Total</td><td>$total_numero_de_dias_laborados</td><td>" . number_format($total_sueldo,2,'.',',') . "</td><td>" . number_format($total_subsidio_al_empleo,2,'.',',') . "</td><td>" . number_format($total_subsidio,2,'.',',') . "</td><td>" . number_format($total_horas_extra_exentas,2,'.',',') . "</td><td>" . number_format($total_horas_extra_grabadas,2,'.',',') . "</td><td>" . number_format($total_prima_dominical,2,'.',',') . "</td><td>" . number_format($total_dias_de_descanso,2,'.',',') . "</td><td>" . number_format($total_premios_de_puntualidad_y_asistencia,2,'.',',') . "</td><td>" . number_format($total_bonos_de_productividad,2,'.',',') . "</td><td>" . number_format($total_estimulos,2,'.',',') . "</td><td>" . number_format($total_compensaciones,2,'.',',') . "</td><td>" . number_format($total_despensa,2,'.',',') . "</td><td>" . number_format($total_comida,2,'.',',') . "</td><td>" . number_format($total_alimentacion,2,'.',',') . "</td><td>" . number_format($total_habitacion,2,'.',',') . "</td><td>" . number_format($total_aportacion_patronal_al_fondo_de_ahorro,2,'.',',') . "</td><td>" . number_format($total_isr,2,'.',',') . "</td><td>" . number_format($total_impuesto_determinado,2,'.',',') . "</td><td>" . number_format($total_cuotas_imss,2,'.',',') . "</td><td>" . number_format($total_retencion_por_alimentacion,2,'.',',') . "</td><td>" . number_format($total_retencion_por_habitacion,2,'.',',') . "</td><td>" . number_format($total_retencion_infonavit,2,'.',',') . "</td><td>" . number_format($total_retencion_fonacot,2,'.',',') . "</td><td>" . number_format($total_aportacion_del_trabajador_al_fondo_de_ahorro,2,'.',',') . "</td><td>" . number_format($total_pension_alimenticia,2,'.',',') . "</td><td>" . number_format($total_retardos,2,'.',',') . "</td><td>" . number_format($total_saldo,2,'.',',') . "</td><td>" . number_format($total_pago_por_seguro_de_vida,2,'.',',') . "</td><td>" . number_format($total_prestamo_del_fondo_de_ahorro,2,'.',',') . "</td><td>" . number_format($total_prestamo_de_caja,2,'.',',') . "</td><td>" . number_format($total_prestamo_de_cliente,2,'.',',') . "</td><td>" . number_format($total_prestamo_de_administradora,2,'.',',') . "</td><td>" . number_format($total_neto_a_recibir,2,'.',',') . "</td></tr>";
		$conn->freeResult($result);
		$txt .= "</table>";
		//Retenciones para provisión de prestaciones
		$txt .= "<table><tr style = 'background:#555;color:#fff;text-align:center'><td colspan = 9>Provisión para prestaciones</td></tr><tr style = 'text-align:center;color:#fff;background:#3399cc' class = 'subtitles'><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>No.</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Servicio</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Inicio</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Término</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Vacaciones</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Prima vacacional</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Aguinaldo</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Prima de antigüedad</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Total</td></tr>";
		$result = $conn->query("SELECT Nomina.id, Nomina.Servicio, Nomina.Limite_inferior_del_periodo, Nomina.Limite_superior_del_periodo, prestaciones.Retencion_proporcional_de_vacaciones, prestaciones.Retencion_proporcional_de_prima_vacacional, prestaciones.Retencion_proporcional_de_aguinaldo,prestaciones.Retencion_proporcional_de_prima_de_antiguedad, prestaciones.Total_de_retenciones FROM Nomina LEFT JOIN prestaciones ON Nomina.id = prestaciones.Nomina WHERE((DATEDIFF(Nomina.Limite_inferior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$limite_superior') <= 0) OR (DATEDIFF(Nomina.Limite_superior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$limite_superior') <= 0)) AND prestaciones.Trabajador = '$trabajador' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND prestaciones.Cuenta = '{$_SESSION['cuenta']}' " . ($servicio == 'Todos' ? "" : "AND Nomina.Servicio = '$servicio' ") . "ORDER BY Nomina.Servicio, Nomina.Limite_inferior_del_periodo ASC");
		$total_vacaciones = 0;
		$total_prima_vacacional = 0;
		$total_aguinaldo = 0;
		$total_prima_de_antiguedad = 0;
		$total_total_retenciones = 0;
		$i = 1;
		$n = $conn->num_rows($result);

		while(list($nomina, $_servicio, $lip, $lsp, $vacaciones, $prima_vacacional, $aguinaldo, $prima_de_antiguedad, $total_retenciones) = $conn->fetchRow($result))
		{

			if($lip < $limite_inferior || $lsp > $limite_superior)
			{
				$vacaciones = prop($vacaciones, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$prima_vacacional = prop($prima_vacacional, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$aguinaldo = prop($aguinaldo, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$prima_de_antiguedad = prop($prima_de_antiguedad, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$total_retenciones = prop($total_retenciones, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
			}

			$total_vacaciones += $vacaciones;
			$total_prima_vacacional += $prima_vacacional;
			$total_aguinaldo += $aguinaldo;
			$total_prima_de_antiguedad += $prima_de_antiguedad;
			$total_total_retenciones += $total_retenciones;
			$txt .= "<tr style='text-align:right'><td>$i</td><td>$_servicio</td><td>$lip</td><td>$lsp</td><td>" . number_format($vacaciones,2,'.',',') . "</td><td>" . number_format($prima_vacacional,2,'.',',') . "</td><td>" . number_format($aguinaldo,2,'.',',') . "</td><td>" . number_format($prima_de_antiguedad,2,'.',',') . "</td><td>" . number_format($total_retenciones,2,'.',',') . "</td></tr>";
			$i++;
		}

		$txt .= "<tr class = 'totals'><td style='background:#fff'></td><td style='background:#fff'></td><td style='background:#fff'></td><td style='text-align:center'>Total</td><td>" . number_format($total_vacaciones,2,'.',',') . "</td><td>" . number_format($total_prima_vacacional,2,'.',',') . "</td><td>" . number_format($total_aguinaldo,2,'.',',') . "</td><td>" . number_format($total_prima_de_antiguedad,2,'.',',') . "</td><td>" . number_format($total_total_retenciones,2,'.',',') . "</td></tr>";
		$conn->freeResult($result);
		$txt .= "</table>";
	}

	$tipo = NULL;
	$result = $conn->query("SELECT Tipo FROM Tipo WHERE Trabajador = '$trabajador' AND Tipo = 'Asimilable' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$limite_superior',Fecha) >= 0" . ($servicio == 'Todos' ? "" : " AND Servicio = '$servicio'") . " LIMIT 1");
	list($tipo) = $conn->fetchRow($result);
	$conn->freeResult($result);

	if(isset($tipo))
	{
		//registros de nómina
		$txt .= "<table><tr style = 'background:#555;color:#fff;text-align:center'><td colspan = 15>Registros de nómina asimilable</td></tr><tr style = 'text-align:center;color:#fff;background:#3399cc'><td>No.</td><td colspan=2>Periodo</td><td>Número de días laborados</td><td>Honorarios asimilados</td><td>Subsidio pagado</td><td>Subsidio que se debió entregar</td><td>ISR retenido</td><td>ISR que se debió retener</td><td>Saldo</td><td>Pago por seguro de vida</td><td>Préstamo de caja</td><td>Préstamo de cliente</td><td>Préstamo de administradora</td><td>Neto a recibir</td></tr>";
		$result = $conn->query("SELECT Nomina.id, Nomina.Servicio, Nomina.Limite_inferior_del_periodo, Nomina.Limite_superior_del_periodo, ISRasimilables.Numero_de_dias_laborados, nomina_asimilables.Honorarios_asimilados, ISRasimilables.Subsidio_al_empleo, ISRasimilables.Subsidio,  ISRasimilables.ISR, ISRasimilables.Impuesto_determinado, nomina_asimilables.Saldo, nomina_asimilables.Pago_por_seguro_de_vida, nomina_asimilables.Prestamo_caja, nomina_asimilables.Prestamo_cliente, nomina_asimilables.Prestamo_administradora, nomina_asimilables.Neto_a_recibir FROM (Nomina LEFT JOIN ISRasimilables ON Nomina.id = ISRasimilables.Nomina) LEFT JOIN nomina_asimilables ON Nomina.id = nomina_asimilables.Nomina WHERE ((DATEDIFF(Nomina.Limite_inferior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$limite_superior') <= 0) OR (DATEDIFF(Nomina.Limite_superior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$limite_superior') <= 0)) AND ISRasimilables.Trabajador = '$trabajador' AND nomina_asimilables.Trabajador = '$trabajador' AND Nomina.Servicio = '$servicio' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND ISRasimilables.Cuenta = '{$_SESSION['cuenta']}' AND nomina_asimilables.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Nomina.Limite_inferior_del_periodo ASC");
		$total_numero_de_dias_laborados = 0;
		$total_honorarios = 0;
		$total_subsidio_al_empleo = 0;
		$total_subsidio = 0;
		$total_isr = 0;
		$total_impuesto_determinado = 0;
		$total_saldo = 0;
		$total_pago_por_seguro_de_vida = 0;
		$total_prestamo_de_caja = 0;
		$total_prestamo_de_cliente = 0;
		$total_prestamo_de_administradora = 0;
		$total_neto_a_recibir = 0;
		$i = 1;

		while(list($nomina, $_servicio, $lip, $lsp, $numero_de_dias_laborados, $honorarios, $subsidio_al_empleo, $subsidio, $isr, $impuesto_determinado, $saldo, $pago_por_seguro_de_vida, $prestamo_de_caja, $prestamo_de_cliente, $prestamo_de_administradora, $neto_a_recibir) = $conn->fetchRow($result))
		{
			$_pago_por_seguro_de_vida = total($pago_por_seguro_de_vida);
			$_prestamo_de_caja = total($prestamo_de_caja);
			$_prestamo_de_cliente = total($prestamo_de_cliente);
			$_prestamo_de_administradora = total($prestamo_de_administradora);


			if($lip < $limite_inferior || $lsp > $limite_superior)
			{
				$numero_de_dias_laborados = prop($numero_de_dias_laborados, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$honorarios = prop($honorarios, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$subsidio_al_empleo = prop($subsidio_al_empleo, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$isr = prop($isr, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$impuesto_determinado = prop($impuesto_determinado, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$saldo = prop($saldo, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_pago_por_seguro_de_vida = prop($_pago_por_seguro_de_vida, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_prestamo_de_caja = prop($_prestamo_de_caja, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_prestamo_de_cliente = prop($_prestamo_de_cliente, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$_prestamo_de_administradora = prop($_prestamo_de_administradora, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
				$neto_a_recibir = prop($neto_a_recibir, $limite_inferior, $limite_superior, $lip, $lsp, $trabajador, $tipo, $nomina, $_servicio);
			}

			$total_numero_de_dias_laborados += $numero_de_dias_laborados;
			$total_honorarios += $honorarios;
			$total_subsidio_al_empleo += $subsidio_al_empleo;
			$total_subsidio += $subsidio_al_empleo;
			$total_isr += $isr;
			$total_impuesto_determinado += $impuesto_determinado;
			$total_saldo += $saldo;
			$total_pago_por_seguro_de_vida += $_pago_por_seguro_de_vida;
			$total_prestamo_de_caja += $_prestamo_de_caja;
			$total_prestamo_de_cliente += $_prestamo_de_cliente;
			$total_prestamo_de_administradora += $_prestamo_de_administradora;
			$total_neto_a_recibir += $neto_a_recibir;
			$txt .= "<tr style='text-align:right'><td>$i</td><td>$lip</td><td>$lsp</td><td>$numero_de_dias_laborados</td><td>" . number_format($honorarios,2,'.',',') . "</td><td>" . number_format($subsidio_al_empleo,2,'.',',') . "</td><td>" . number_format($subsidio,2,'.',',') . "</td><td>" . number_format($isr,2,'.',',') . "</td><td>" . number_format($impuesto_determinado,2,'.',',') . "</td><td>" . number_format($saldo,2,'.',',') . "</td><td>" . number_format($_pago_por_seguro_de_vida,2,'.',',') . "</td><td>" . number_format($_prestamo_de_caja,2,'.',',') . "</td><td>" . number_format($_prestamo_de_cliente,2,'.',',') . "</td><td>" . number_format($_prestamo_de_administradora,2,'.',',') . "</td><td>" . number_format($neto_a_recibir,2,'.',',') . "</td></tr>";
			$i++;
		}

		$txt .= "<tr class = 'totals'><td style='background:#fff'></td><td style='background:#fff'></td><td style='text-align:center'>Total</td><td>$total_numero_de_dias_laborados</td><td>" . number_format($total_honorarios,2,'.',',') . "</td><td>" . number_format($total_subsidio_al_empleo,2,'.',',') . "</td><td>" . number_format($total_subsidio,2,'.',',') . "</td><td>" . number_format($total_isr,2,'.',',') . "</td><td>" . number_format($total_impuesto_determinado,2,'.',',') . "</td><td>" . number_format($total_saldo,2,'.',',') . "</td><td>" . number_format($total_pago_por_seguro_de_vida,2,'.',',') . "</td><td>" . number_format($total_prestamo_de_caja,2,'.',',') . "</td><td>" . number_format($total_prestamo_de_cliente,2,'.',',') . "</td><td>" . number_format($total_prestamo_de_administradora,2,'.',',') . "</td><td>" . number_format($total_neto_a_recibir,2,'.',',') . "</td></tr>";
		$conn->freeResult($result);
		$txt .= "</table>";
	}

	echo $txt;
?>
	</body>
</html>
