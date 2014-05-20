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
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	function calculate_dias_de_baja($dias_del_periodo,$limite_inferior,$limite_superior,$trabajador,$servicio)
	{
		date_default_timezone_set('America/Mexico_City');
		$conn = new Connection();
		$_limite_inferior = date_create($limite_inferior);
		$_limite_superior = date_create($limite_superior);
		$result = $conn->query("SELECT Fecha_de_baja, Fecha_de_reingreso FROM Baja WHERE Trabajador = '$trabajador' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND
(
	(
		DATEDIFF(Baja.Fecha_de_baja, '$limite_inferior') <= 0
		AND
		(Baja.Fecha_de_reingreso = '0000-00-00' OR DATEDIFF(Baja.Fecha_de_reingreso,'$limite_inferior') > 0)
	)

	OR

	(
		DATEDIFF(Baja.Fecha_de_baja, '$limite_inferior') > 0
		AND
		DATEDIFF(Baja.Fecha_de_baja, '$limite_superior') <= 0
	)
)");
		$dias_de_baja = array();
		$k = 0;

		while(list($fecha_de_baja,$fecha_de_reingreso) = $conn->fetchRow($result))
		{
			//número de días
			$numero_de_dias = 0;
			$baja = date_create($fecha_de_baja);

			if($fecha_de_reingreso == '0000-00-00')
			{

				if($baja >= $_limite_inferior)
				{
					$interval = $baja->diff($_limite_superior);
					$numero_de_dias = $interval->days;
				}
				else
				{
					$interval = $_limite_inferior->diff($_limite_superior);
					$numero_de_dias = $interval->days + 1;
				}


			}
			else
			{
				$reingreso = date_create($fecha_de_reingreso);

				if($reingreso > $baja)
				{

					if($baja >= $_limite_inferior)
					{
						$interval = $baja->diff($reingreso);
						$numero_de_dias = $interval->days;
					}
					else
					{
						$interval = $_limite_inferior->diff($reingreso);
						$numero_de_dias = $interval->days + 1;
					}

				}
				else
					$numero_de_dias = 0;

			}

			//dias de baja
			$dias = array();

			if($baja < $_limite_inferior)
			{

				for($i=0; $i<$numero_de_dias; $i++)
				{
					$interval = new DateInterval('P' . $i . 'D');
					$day = $_limite_inferior->add($interval);
					$dia = $day->format('Y-m-d');
					$dias[$i] = $dia;
					$day = $_limite_inferior->sub($interval);
				}

			}
			else
			{
				$interval = new DateInterval('P1D');

				for($i=0; $i<$numero_de_dias; $i++)
				{
					$day = $baja->add($interval);
					$dia = $day->format('Y-m-d');
					$dias[$i] = $dia;
				}

			}

			//comparison
			$len = count($dias);

			for($i=0; $i<$len; $i++)
			{
				$_len = count($dias_del_periodo);

				for($j=0; $j<$_len; $j++)

					if(isset($dias[$i]) && $dias[$i] == $dias_del_periodo[$j])
					{
						$dias_de_baja[$k] = $dias[$i];
						$k ++;
					}

			}

		}

		return $dias_de_baja;
	}

	function calculate_ingreso($trabajador, $limite_inferior, $servicio)
	{
		$ingreso = Null;
		$conn = new Connection();
		$result = $conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '$trabajador' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND Fecha_de_reingreso != '0000-00-00' AND DATEDIFF(Fecha_de_reingreso, '$limite_inferior') >= 0 AND DATEDIFF(Fecha_de_reingreso, Fecha_de_baja) > 1 ORDER BY Fecha_de_reingreso DESC LIMIT 1");
		list($ingreso) = $conn->fetchRow($result);
		$conn->freeResult($result);

		if(!isset($ingreso))
		{
			$result = $conn->query("SELECT Antiguedad_IMSS FROM Servicio WHERE id = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($antiguedad) = $conn->fetchRow($result);
			$conn->freeResult($result);

			if($antiguedad == 'Servicio')
				$result = $conn->query("SELECT Fecha_de_ingreso_servicio FROM Servicio_Trabajador WHERE Servicio = '$servicio' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			else
				$result = $conn->query("SELECT Fecha_de_ingreso_cliente FROM Servicio_Trabajador WHERE Servicio = '$servicio' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");

			list($ingreso) = $conn->fetchRow($result);
			$conn->freeResult($result);
		}

		return $ingreso;
	}

	function calculate_dias_previos_al_ingreso($limite_inferior, $trabajador, $servicio)
	{
		date_default_timezone_set('America/Mexico_City');
		$conn = new Connection();
		$_limite_inferior = date_create($limite_inferior);
		$fecha_de_ingreso = calculate_ingreso($trabajador, $limite_inferior, $servicio);

		if(isset($fecha_de_ingreso) && $fecha_de_ingreso > $limite_inferior)
		{
			$interval = date_diff($_limite_inferior,date_create($fecha_de_ingreso));
			$numero_de_dias_previos_al_ingreso = $interval->days;
		}
		else
			$numero_de_dias_previos_al_ingreso = 0;

		$dias_previos_al_ingreso = array();

		if($numero_de_dias_previos_al_ingreso > 0)
		{

			for($i=0; $i<$numero_de_dias_previos_al_ingreso; $i++)
			{
				$interval = new DateInterval('P' . $i . 'D');
				$day = $_limite_inferior->add($interval);
				$dias_previos_al_ingreso[$i] = $day->format('Y-m-d');
				$day = $_limite_inferior->sub($interval);
			}

		}

		return $dias_previos_al_ingreso;
	}

	function calculate_dias_de_incapacidad($_limite_inferior, $_limite_superior, $trabajador, $servicio)
	{
		date_default_timezone_set('America/Mexico_City');
		$limite_inferior = date_create($_limite_inferior);
		$limite_superior = date_create($_limite_superior);
		$conn = new Connection();
		$dias_de_incapacidad = array();
		$result = $conn->query("SELECT Fecha_de_inicio, Fecha_de_termino FROM Incapacidad WHERE Trabajador = '$trabajador' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");

		while(list($fecha_de_inicio, $fecha_de_termino) = $conn->fetchRow($result))
		{
			$inicio = date_create($fecha_de_inicio);
			$termino = date_create($fecha_de_termino);
			$numero_de_dias = 0;

			if($inicio < $termino)
			{
				$interval = $inicio->diff($termino);
				$numero_de_dias = $interval->days + 1;
			}
			else
				$numero_de_dias = 0;

			$aux = array();
			$j = 0;

			for($i=0; $i<$numero_de_dias; $i++)
			{
				$interval = new DateInterval('P' . $i . 'D');
				$day = $inicio->add($interval);

				if($limite_inferior <= date_create($day->format('Y-m-d')) && $limite_superior >= date_create($day->format('Y-m-d')))
				{
					$aux[$j] = $day->format('Y-m-d');
					$j++;
				}

				$day = $inicio->sub($interval);
			}

			$dias_de_incapacidad = array_merge($dias_de_incapacidad, $aux);
		}

		return $dias_de_incapacidad;
	}


	function calculate_dias_laborados($dias_del_periodo,$dias_previos_al_ingreso,$dias_de_baja,$_faltas,$dias_de_incapacidad)
	{
		$numero_de_dias_del_periodo = count($dias_del_periodo);
		$numero_de_dias_previos_al_ingreso = count($dias_previos_al_ingreso);
		$numero_de_dias_de_baja = count($dias_de_baja);
		$numero_de_faltas = count($_faltas);
		$numero_de_dias_de_incapacidad = count($dias_de_incapacidad);
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

		return $dias_laborados;
	}

	function myArray2Str($array)
	{
		$txt = '';
		$len = count($array);

		for($i=0; $i<$len; $i++)
			$txt .= $array[$i] . (($i == $len - 1) ? '' : ',');

		return $txt;
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

//*****************************Proportional functions***********************************************************************************************
	function prop($valor, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio)
	{
		$conn = new Connection();
		//Días del periodo
		$dias_del_periodo_nomina = calculate_dias_del_periodo_prop($lip, $lsp);
		$numero_de_dias_del_periodo = count($dias_del_periodo_nomina);
		//Dias previos al ingreso
		$result = $conn->query("SELECT Numero_de_dias_previos_al_ingreso FROM ISRasalariados WHERE Nomina = '$nomina' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
		list($numero_de_dias_previos_al_ingreso) = $conn->fetchRow($result);
		$conn->freeResult($result);
		$dias_previos_al_ingreso = calculate_dias_previos_al_ingreso_prop($numero_de_dias_previos_al_ingreso,$lip);
		//Días de baja
		$dias_de_baja = calculate_dias_de_baja_prop($trabajador, $lip, $lsp, $servicio);
		//faltas
//		$result = $conn->query("SELECT Faltas FROM ISRasalariados WHERE Trabajador = '$trabajador' AND Nomina = '$nomina' AND Cuenta = '{$_SESSION['cuenta']}'");
//		list($_faltas) = $conn->fetchRow($result);
//		$conn->freeResult($result);
//		$faltas = $_faltas != '' ? explode(',', $_faltas) : array();
		$faltas = array();
		//Incapacidad
		$dias_de_incapacidad = calculate_dias_de_incapacidad_prop($trabajador, $lip, $lsp, $servicio);
		//Vacaciones
//		$dias_de_vacaciones = calculate_dias_de_vacaciones_prop($trabajador, $lip, $lsp, $servicio);
		$dias_de_vacaciones = array();
		//Días laborados
		$dias_laborados = calculate_dias_laborados_prop($dias_del_periodo_nomina, $dias_previos_al_ingreso, $dias_de_baja, $faltas,$dias_de_incapacidad, $dias_de_vacaciones);
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

	function calculate_dias_del_periodo_prop($lip, $lsp)
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

	function calculate_dias_previos_al_ingreso_prop($numero_de_dias_previos_al_ingreso,$lip)
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

	function calculate_dias_de_baja_prop($trabajador, $lip, $lsp, $servicio)
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

	function calculate_dias_de_incapacidad_prop($trabajador, $lip, $lsp, $servicio)
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

	function calculate_dias_de_vacaciones_prop($trabajador, $lip, $lsp, $servicio)
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

	function calculate_dias_laborados_prop($dias_del_periodo, $dias_previos_al_ingreso, $dias_de_baja, $_faltas, $dias_de_incapacidad,$dias_de_vacaciones)
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
//**************************************************************************************************************************************************
	date_default_timezone_set('America/Mexico_City');
	$conn = new Connection();
	$limite_inferior = $_GET['limite_inferior'];
	$limite_superior = $_GET['limite_superior'];
	$_limite_inferior = date_create($limite_inferior);
	$_limite_superior = date_create($limite_superior);
	$registro = $_GET['Registro_patronal'];
	$len = count($registro);
	$empresa = $_GET['empresa'];
	echo "<img src = 'images/logo_blanco.jpg'/><br/><br/>";
	echo "Empresa: $empresa<br/>";

	for($i=0; $i<$len; $i++)
		echo "Registro patronal: {$registro[$i]}<br/>";

	echo "Concentrado de IMSS del $limite_inferior al $limite_superior<br/>Ver totales<input type = 'button' onclick = 'show_totals(this)' value = '⌖' style = 'background:#3399cc;border:none;borderRadius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;color:#fff;text-align:center;cursor:pointer'><br/><br/>";
	$txt = "<table><tr style = 'text-align:center;color:#fff;background:#555' class = 'titles'><td colspan = '8' onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Datos</td><td colspan = '22' onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Cuotas IMSS</td></tr><tr style = 'text-align:center;color:#fff;background:#3399cc' class = 'subtitles'><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>N</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Trabajador</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>RFC</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>CURP</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Número de seguro social</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Fecha de alta en IMSS</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Bajas</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Modificaciones de SDI</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Faltas</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Días de incapacidad</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Número de días cotizados IMSS</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Número de días cotizados Sistema</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Sueldo integrado</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Cuota fija</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Exedente</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Cuota sobre el exedente</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Prestaciones en dinero obreras</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Prestaciones en dinero patronales</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Gastos médicos y pensión obreros</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Gastos médicos y pensión patronales</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Invalidéz y vida obrera</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Invalidéz y vida patronal</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Cesantía y vejez obrera</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Cesantía y vejez patronal</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Guardería</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Retiro</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>INFONAVIT</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Riesgo de trabajo</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Total de cuotas obreras</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Total de cuotas patronales</td></tr>";
	$register_clause = "Servicio_Registro_patronal.Registro_patronal = '{$registro[0]}'";

	for($i=1; $i<$len; $i++)
		$register_clause .= " OR Servicio_Registro_patronal.Registro_patronal = '{$registro[$i]}'";

	$result = $conn->query("SELECT DISTINCT Servicio.id, Servicio_Trabajador.Trabajador, Servicio_Registro_patronal.Registro_patronal, Servicio_Registro_patronal.Fecha_de_asignacion FROM Servicio_Trabajador LEFT JOIN Servicio ON Servicio_Trabajador.Servicio = Servicio.id LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE ($register_clause) AND Servicio_Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Servicio_Trabajador.Trabajador ASC");

	$i = 1;
	$total_altas = 0;
	$total_cambios_sdi = 0;
	$total_bajas = 0;
	$total_numero_de_dias_de_incapacidad = 0;
	$total_numero_de_faltas = 0;
	$total_numero_de_dias_laborados = 0;
	$total_numero_de_dias_cotizados = 0;
	$total_sueldo_integrado = 0;
	$total_cuota_fija = 0;
	$total_exedente = 0;
	$total_cuota_sobre_el_exedente = 0;
	$total_prestaciones_en_dinero_obreras = 0;
	$total_prestaciones_en_dinero_patronales = 0;
	$total_gastos_medicos_y_pension_obreros = 0;
	$total_gastos_medicos_y_pension_patronales = 0;
	$total_invalidez_y_vida_obrera = 0;
	$total_invalidez_y_vida_patronal = 0;
	$total_cesantia_y_vejez_obrera = 0;
	$total_cesantia_y_vejez_patronal = 0;
	$total_guarderia = 0;
	$total_retiro = 0;
	$total_infonavit = 0;
	$total_riesgo_de_trabajo = 0;
	$total_cuotas_obreras = 0;
	$total_cuotas_patronales = 0;
	//dias del periodo
	if($_limite_superior > $_limite_inferior)
	{
		$interval = $_limite_inferior->diff($_limite_superior);
		$numero_de_dias_del_periodo = $interval->days + 1;
	}
	else
		$numero_de_dias_del_periodo = 0;

	$dias_del_periodo = array();

	for($j=0; $j<$numero_de_dias_del_periodo; $j++)
	{
		$interval = new DateInterval('P' . $j . 'D');
		$day = $_limite_inferior->add($interval);
		$dias_del_periodo[$j] = $day->format('Y-m-d');
		$day = $_limite_inferior->sub($interval);
	}

	while(list($servicio, $trabajador, $registro, $fecha_de_asignacion_del_registro_patronal) = $conn->fetchRow($result))
	{
		//setting lower limit
		if($fecha_de_asignacion_del_registro_patronal > $limite_inferior)
			$li = $fecha_de_asignacion_del_registro_patronal;
		else
			$li = $limite_inferior;

		//setting upper limit
		$result1 = $conn->query("SELECT Fecha_de_asignacion FROM Servicio_Registro_patronal WHERE Servicio = '$servicio' AND Registro_patronal != '$registro' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_asignacion,'$fecha_de_asignacion_del_registro_patronal') >= 0 AND DATEDIFF('$limite_superior', Fecha_de_asignacion) >= 0 ORDER BY Fecha_de_asignacion ASC LIMIT 1");

		if($conn->num_rows($result1) > 0)
			list($ls) = $conn->fetchRow($result1);
		else
			$ls = $limite_superior;

		$conn->freeResult($result1);

		$result1 = $conn->query("SELECT Trabajador.Nombre, Trabajador.CURP, Trabajador.Numero_IMSS FROM Trabajador WHERE RFC = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
		list($nombre,$curp,$numero_imss) = $conn->fetchRow($result1);
		$conn->freeResult($result1);
		$fecha_de_alta_imss = calculate_ingreso($trabajador, $li, $servicio);
		$result1 = $conn->query("SELECT Tipo FROM Tipo WHERE Trabajador = '$trabajador' AND Tipo = 'Asalariado' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$ls',Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
		list($tipo) = $conn->fetchRow($result1);
		$conn->freeResult($result1);

		if(isset($tipo))
		{
			//fecha de alta imss
			if($fecha_de_alta_imss >= $li && $fecha_de_alta_imss <= $ls)
			{
				$alta_imss = $fecha_de_alta_imss;
				$total_altas ++;
			}
			else
				$alta_imss = '';

			//faltas
			$result1 = $conn->query("SELECT ISRasalariados.Faltas FROM Nomina LEFT JOIN ISRasalariados ON Nomina.id = ISRasalariados.Nomina WHERE
(
	(DATEDIFF(Nomina.Limite_inferior_del_periodo,'$li') >= 0 AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$ls') <= 0)
	OR
	(DATEDIFF(Nomina.Limite_superior_del_periodo,'$li') >= 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$ls') <= 0)
) AND ISRasalariados.Trabajador = '$trabajador' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND ISRasalariados.Cuenta = '{$_SESSION['cuenta']}'");
			$faltas_ = '';

			while(list($faltas) = $conn->fetchRow($result1))

				if(isset($faltas) && $faltas != '')
					$faltas_ .= ($faltas_ == '') ? $faltas : (',' . $faltas);

			$conn->freeResult($result1);
			//dias laborados
			$_faltas = $faltas != '' ? explode(',', $faltas_) : array();
			$_incapacidad = calculate_dias_de_incapacidad($li, $ls, $trabajador, $servicio);
			$dias_de_baja = calculate_dias_de_baja($dias_del_periodo,$li,$ls,$trabajador,$servicio);
			$dias_previos_al_ingreso = calculate_dias_previos_al_ingreso($li, $trabajador, $servicio);
			$dias_laborados = calculate_dias_laborados($dias_del_periodo, $dias_previos_al_ingreso, $dias_de_baja, $_faltas, $_incapacidad);
			$numero_de_dias_laborados = count($dias_laborados);
			//Bajas y reingresos
			$result1 = $conn->query("SELECT Fecha_de_baja, Fecha_de_reingreso FROM Baja WHERE Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$li',Fecha_de_baja) <= 0 AND DATEDIFF(Fecha_de_baja, '$ls') <= 0");
			$bajas = '';

			while(list($baja, $reingreso) = $conn->fetchRow($result1))
			{
				$bajas .= 'Baja:' . $baja . '<br/>Reingreso:' . $reingreso . '<br/>';
				$total_bajas ++;
			}

			$conn->freeResult($result1);
			//numero de dias de incapacidad
			$numero_de_dias_de_incapacidad = count($_incapacidad);
			$total_numero_de_dias_de_incapacidad += $numero_de_dias_de_incapacidad;
			//numero de faltas
			$numero_de_faltas = count($_faltas);
			$total_numero_de_faltas += $numero_de_faltas;
			//cambios de salario diario integrado
			$result1 = $conn->query("SELECT cuotas_IMSS.Salario_diario_integrado FROM Nomina LEFT JOIN cuotas_IMSS ON Nomina.id = cuotas_IMSS.Nomina WHERE
(
	(DATEDIFF(Nomina.Limite_inferior_del_periodo,'$li') >= 0 AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$ls') <= 0)
	OR
	(DATEDIFF(Nomina.Limite_superior_del_periodo,'$li') >= 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$ls') <= 0)
) AND cuotas_IMSS.Trabajador = '$trabajador' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND cuotas_IMSS.Cuenta = '{$_SESSION['cuenta']}'");
			$total_sdis = '';

			while(list($sdis) = $conn->fetchRow($result1))
			{
				$data = explode(',',$sdis);
				$len = count($data);

				if($len > 1)
				{

					for($j=0; $j<$len; $j++)
					{
						$values = explode('/',$data[$j]);
						$total_sdis .= $values[1] . ' --  $' . round($values[0],2) . '<br/>';
						$total_cambios_sdi ++;
					}

					$total_cambios_sdi --;//El primer sdi no implica un cambio
				}

			}

			$conn->freeResult($result1);

			if($alta_imss != '')
			{
				$interval = new DateInterval('P1D');

				if(count($dias_previos_al_ingreso) > 0)
				{
					$day = date_create($dias_previos_al_ingreso[count($dias_previos_al_ingreso) - 1])->add($interval);

					if($fecha_de_alta_imss > $li && $fecha_de_alta_imss > $day->format('Y-m-d'))
					{
						$_li = date_create($li);
						$interval = $_li->diff(date_create($fecha_de_alta_imss));
						$numero_de_dias_laborados -= $interval->d;
					}

				}
				elseif($fecha_de_alta_imss > $li)
				{
					$_li = date_create($li);
					$interval = $_li->diff(date_create($fecha_de_alta_imss));
					$numero_de_dias_laborados -= $interval->days;
				}

			}

			$total_numero_de_dias_laborados += $numero_de_dias_laborados;
			$result1 = $conn->query("SELECT Nomina.id, Nomina.Limite_inferior_del_periodo, Nomina.Limite_superior_del_periodo, cuotas_IMSS.Numero_de_dias_cotizados, cuotas_IMSS.Sueldo_integrado, cuotas_IMSS.Cuota_fija_IMSS, cuotas_IMSS.Exedente_3_SMGDF_patronal, cuotas_IMSS.Exedente_3_SMGDF_obrero, cuotas_IMSS.Prestaciones_en_dinero_obreras, cuotas_IMSS.Prestaciones_en_dinero_patronales, cuotas_IMSS.Gastos_medicos_y_pension_obreros, cuotas_IMSS.Gastos_medicos_y_pension_patronales, cuotas_IMSS.Invalidez_y_vida_obrera, cuotas_IMSS.Invalidez_y_vida_patronal, cuotas_IMSS.Cesantia_y_vejez_obrera, cuotas_IMSS.Cesantia_y_vejez_patronal, cuotas_IMSS.Guarderia, cuotas_IMSS.Retiro, cuotas_IMSS.INFONAVIT, cuotas_IMSS.Riesgo_de_trabajo, cuotas_IMSS.Total_de_cuotas_IMSS_obreras, cuotas_IMSS.Total_de_cuotas_IMSS_patronales FROM Nomina LEFT JOIN cuotas_IMSS ON Nomina.id = cuotas_IMSS.Nomina WHERE
(
	(DATEDIFF(Nomina.Limite_inferior_del_periodo,'$li') >= 0 AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$ls') <= 0)
	OR
	(DATEDIFF(Nomina.Limite_superior_del_periodo,'$li') >= 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$ls') <= 0)
) AND cuotas_IMSS.Trabajador = '$trabajador' AND Nomina.Servicio = '$servicio' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND cuotas_IMSS.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Nomina.Limite_inferior_del_periodo ASC");
			$numero_de_dias_cotizados_ = 0;
			$sueldo_integrado_ = 0;
			$cuota_fija_ = 0;
			$exedente_ = 0;
			$cuota_sobre_el_exedente_ = 0;
			$prestaciones_en_dinero_obreras_ = 0;
			$prestaciones_en_dinero_patronales_ = 0;
			$gastos_medicos_y_pension_obreros_ = 0;
			$gastos_medicos_y_pension_patronales_ = 0;
			$invalidez_y_vida_obrera_ = 0;
			$invalidez_y_vida_patronal_ = 0;
			$cesantia_y_vejez_obrera_ = 0;
			$cesantia_y_vejez_patronal_ = 0;
			$guarderia_ = 0;
			$retiro_ = 0;
			$infonavit_ = 0;
			$riesgo_de_trabajo_ = 0;
			$cuotas_obreras_ = 0;
			$cuotas_patronales_ = 0;
			$j = 1;
			$n = $conn->num_rows($result1);

			while(list($nomina, $lip, $lsp, $numero_de_dias_cotizados, $sueldo_integrado, $cuota_fija, $exedente, $cuota_sobre_el_exedente, $prestaciones_en_dinero_obreras, $prestaciones_en_dinero_patronales, $gastos_medicos_y_pension_obreros, $gastos_medicos_y_pension_patronales, $invalidez_y_vida_obrera, $invalidez_y_vida_patronal, $cesantia_y_vejez_obrera, $cesantia_y_vejez_patronal, $guarderia, 	$retiro, $infonavit, $riesgo_de_trabajo, $cuotas_obreras, $cuotas_patronales) = $conn->fetchRow($result1))
			{

				if($lip < $li || $lsp > $ls)
				{
					$numero_de_dias_cotizados = prop($numero_de_dias_cotizados, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$sueldo_integrado = prop($sueldo_integrado, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$cuota_fija = prop($cuota_fija, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$exedente = prop($exedente, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$cuota_sobre_el_exedente = prop($cuota_sobre_el_exedente, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$prestaciones_en_dinero_obreras = prop($prestaciones_en_dinero_obreras, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$prestaciones_en_dinero_patronales = prop($prestaciones_en_dinero_patronales, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$gastos_medicos_y_pension_obreros = prop($gastos_medicos_y_pension_obreros, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$gastos_medicos_y_pension_patronales = prop($gastos_medicos_y_pension_patronales, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$invalidez_y_vida_obrera = prop($invalidez_y_vida_obrera, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$invalidez_y_vida_patronal = prop($invalidez_y_vida_patronal, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$cesantia_y_vejez_obrera = prop($cesantia_y_vejez_obrera, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$cesantia_y_vejez_patronal = prop($cesantia_y_vejez_patronal, $li, $limite_superior, $lip, $lsp, $trabajador, $nomina, $servicio);
					$guarderia = prop($guarderia, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$retiro = prop($retiro, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$infonavit = prop($infonavit, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$riesgo_de_trabajo = prop($riesgo_de_trabajo, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$cuotas_obreras = prop($cuotas_obreras, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
					$cuotas_patronales = prop($cuotas_patronales, $li, $ls, $lip, $lsp, $trabajador, $nomina, $servicio);
				}

				$numero_de_dias_cotizados_ += $numero_de_dias_cotizados;
				$sueldo_integrado_ += $sueldo_integrado;
				$cuota_fija_ += $cuota_fija;
				$exedente_ += $exedente;
				$cuota_sobre_el_exedente_ += $cuota_sobre_el_exedente;
				$prestaciones_en_dinero_obreras_ += $prestaciones_en_dinero_obreras;
				$prestaciones_en_dinero_patronales_ += $prestaciones_en_dinero_patronales;
				$gastos_medicos_y_pension_obreros_ += $gastos_medicos_y_pension_obreros;
				$gastos_medicos_y_pension_patronales_ += $gastos_medicos_y_pension_patronales;
				$invalidez_y_vida_obrera_ += $invalidez_y_vida_obrera;
				$invalidez_y_vida_patronal_ += $invalidez_y_vida_patronal;
				$cesantia_y_vejez_obrera_ += $cesantia_y_vejez_obrera;
				$cesantia_y_vejez_patronal_ += $cesantia_y_vejez_patronal;
				$guarderia_ += $guarderia;
				$retiro_ += $retiro;
				$infonavit_ += $infonavit;
				$riesgo_de_trabajo_ += $riesgo_de_trabajo;
				$cuotas_obreras_ += $cuotas_obreras;
				$cuotas_patronales_ += $cuotas_patronales;
			}

			$total_numero_de_dias_cotizados += $numero_de_dias_cotizados_;
			$total_sueldo_integrado += $sueldo_integrado_;
			$total_cuota_fija += $cuota_fija_;
			$total_exedente += $exedente_;
			$total_cuota_sobre_el_exedente += $cuota_sobre_el_exedente_;
			$total_prestaciones_en_dinero_obreras += $prestaciones_en_dinero_obreras_;
			$total_prestaciones_en_dinero_patronales += $prestaciones_en_dinero_patronales_;
			$total_gastos_medicos_y_pension_obreros += $gastos_medicos_y_pension_obreros_;
			$total_gastos_medicos_y_pension_patronales += $gastos_medicos_y_pension_patronales_;
			$total_invalidez_y_vida_obrera += $invalidez_y_vida_obrera_;
			$total_invalidez_y_vida_patronal += $invalidez_y_vida_patronal_;
			$total_cesantia_y_vejez_obrera += $cesantia_y_vejez_obrera_;
			$total_cesantia_y_vejez_patronal += $cesantia_y_vejez_patronal_;
			$total_guarderia += $guarderia_;
			$total_retiro += $retiro_;
			$total_infonavit += $infonavit_;
			$total_riesgo_de_trabajo += $riesgo_de_trabajo_;
			$total_cuotas_obreras += $cuotas_obreras_;
			$total_cuotas_patronales += $cuotas_patronales_;
			$txt .= "<tr style = 'text-align:right'><td style = 'text-align:center'>$i</td><td style = 'text-align:center'>$nombre</td><td>$trabajador</td><td>$curp</td><td>$numero_imss</td><td>$alta_imss</td><td>$bajas</td><td>$total_sdis</td><td>$numero_de_faltas</td><td>$numero_de_dias_de_incapacidad</td><td>$numero_de_dias_laborados</td><td>$numero_de_dias_cotizados_</td><td>" . number_format($sueldo_integrado_,2,'.',',') . "</td><td>" . number_format($cuota_fija_,2,'.',',') . "</td><td>" . number_format($exedente_,2,'.',',') . "</td><td>" . number_format($cuota_sobre_el_exedente_,2,'.',',') . "</td><td>" . number_format($prestaciones_en_dinero_obreras_,2,'.',',') . "</td><td>" . number_format($prestaciones_en_dinero_patronales_,2,'.',',') . "</td><td>" . number_format($gastos_medicos_y_pension_obreros_,2,'.',',') . "</td><td>" . number_format($gastos_medicos_y_pension_patronales_,2,'.',',') . "</td><td>" . number_format($invalidez_y_vida_obrera_,2,'.',',') . "</td><td>" . number_format($invalidez_y_vida_patronal_,2,'.',',') . "</td><td>" . number_format($cesantia_y_vejez_obrera_,2,'.',',') . "</td><td>" . number_format($cesantia_y_vejez_patronal_,2,'.',',') . "</td><td>" . number_format($guarderia_,2,'.',',') . "</td><td>" . number_format($retiro_,2,'.',',') . "</td><td>" . number_format($infonavit_,2,'.',',') . "</td><td>" . number_format($riesgo_de_trabajo_,2,'.',',') . "</td><td>" . number_format($cuotas_obreras_,2,'.',',') . "</td><td>" . number_format($cuotas_patronales_,2,'.',',') . "</td></tr>";
			$conn->freeResult($result1);
			$i++;
		}

	}

	$txt .= "<tr class = 'totals'><td style = 'background:#fff'></td><td style = 'background:#fff'></td><td style = 'background:#fff'></td><td style = 'background:#fff'></td><td style = 'background:#fff'>Total</td><td>$total_altas</td><td>$total_bajas</td><td>$total_cambios_sdi</td><td>$total_numero_de_faltas</td><td>$total_numero_de_dias_de_incapacidad</td><td>" . number_format($total_numero_de_dias_laborados,2,'.',',') . "</td><td>" . number_format($total_numero_de_dias_cotizados,2,'.',',') . "</td><td>" . number_format($total_sueldo_integrado,2,'.',',') . "</td><td>" . number_format($total_cuota_fija,2,'.',',') . "</td><td>" . number_format($total_exedente,2,'.',',') . "</td><td>" . number_format($total_cuota_sobre_el_exedente,2,'.',',') . "</td><td>" . number_format($total_prestaciones_en_dinero_obreras,2,'.',',') . "</td><td>" . number_format($total_prestaciones_en_dinero_patronales,2,'.',',') . "</td><td>" . number_format($total_gastos_medicos_y_pension_obreros,2,'.',',') . "</td><td>" . number_format($total_gastos_medicos_y_pension_patronales,2,'.',',') . "</td><td>" . number_format($total_invalidez_y_vida_obrera,2,'.',',') . "</td><td>" . number_format($total_invalidez_y_vida_patronal,2,'.',',') . "</td><td>" . number_format($total_cesantia_y_vejez_obrera,2,'.',',') . "</td><td>" . number_format($total_cesantia_y_vejez_patronal,2,'.',',') . "</td><td>" . number_format($total_guarderia,2,'.',',') . "</td><td>" . number_format($total_retiro,2,'.',',') . "</td><td>" . number_format($total_infonavit,2,'.',',') . "</td><td>" . number_format($total_riesgo_de_trabajo,2,'.',',') . "</td><td>" . number_format($total_cuotas_obreras,2,'.',',') . "</td><td>" . number_format($total_cuotas_patronales,2,'.',',') . "</td></tr>";
	$txt .= "</table>";
	echo $txt;
?>
	</body>
</html>
