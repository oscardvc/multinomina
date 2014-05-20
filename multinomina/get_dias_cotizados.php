<?php
	//This page is called by an AJAX function at dias_cotizados.php
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	//**********************************************************************functions**************************************************************************
		function calculate_numero_de_dias_de_baja($_trabajador,$limite_inferior,$limite_superior,$dias_del_periodo)
		{
			date_default_timezone_set('America/Mexico_City');
			$conn = new Connection();
			$_limite_inferior = date_create($limite_inferior);
			$_limite_superior = date_create($limite_superior);
			$result = $conn->query("SELECT Fecha_de_baja, Fecha_de_reingreso FROM Baja WHERE Trabajador = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}' AND ((DATEDIFF(Fecha_de_baja, '$limite_inferior') <= 0 AND (Fecha_de_reingreso = '0000-00-00' OR DATEDIFF(Fecha_de_reingreso,'$limite_inferior') > 0)) OR (DATEDIFF(Fecha_de_baja, '$limite_inferior') > 0 AND (DATEDIFF(Fecha_de_baja, '$limite_superior') <= 0 OR Fecha_de_reingreso = '0000-00-00' )))");
			$numero_de_dias_de_baja = 0;

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
							$numero_de_dias_de_baja ++;
						}

				}

			}

			return $numero_de_dias_de_baja;
		}

		function calculate_dias_de_baja($dias_del_periodo,$limite_inferior,$limite_superior,$numero_de_dias_de_baja,$_trabajador)
		{
			date_default_timezone_set('America/Mexico_City');
			$conn = new Connection();
			$_limite_inferior = date_create($limite_inferior);
			$_limite_superior = date_create($limite_superior);
			$result = $conn->query("SELECT Fecha_de_baja, Fecha_de_reingreso FROM Baja WHERE Trabajador = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}' AND ((DATEDIFF(Fecha_de_baja, '$limite_inferior') <= 0 AND (Fecha_de_reingreso = '0000-00-00' OR DATEDIFF(Fecha_de_reingreso,'$limite_inferior') > 0)) OR (DATEDIFF(Fecha_de_baja, '$limite_inferior') > 0 AND (DATEDIFF(Fecha_de_baja, '$limite_superior') <= 0 OR Fecha_de_reingreso = '0000-00-00' )))");
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

		function calculate_numero_de_dias_previos_al_ingreso($limite_inferior, $servicio, $_trabajador)
		{
			date_default_timezone_set('America/Mexico_City');
			$conn = new Connection();
			$_limite_inferior = date_create($limite_inferior);
			$result = $conn->query("SELECT Fecha_de_ingreso FROM Servicio_Trabajador WHERE Trabajador = '$_trabajador' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_ingreso, '$limite_inferior') > 0");
			list($fecha_de_ingreso) = $conn->fetchRow($result);

			if(isset($fecha_de_ingreso))
			{
				$interval = date_diff($_limite_inferior,date_create($fecha_de_ingreso));
				$numero_de_dias_previos_al_ingreso = $interval->days;
			}
			else
				$numero_de_dias_previos_al_ingreso = 0;

			return $numero_de_dias_previos_al_ingreso;
		}

		function calculate_dias_previos_al_ingreso($limite_inferior, $numero_de_dias_previos_al_ingreso)
		{
			date_default_timezone_set('America/Mexico_City');
			$_limite_inferior = date_create($limite_inferior);
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

		function calculate_dias_de_incapacidad($_limite_inferior, $_limite_superior, $_trabajador)
		{
			date_default_timezone_set('America/Mexico_City');
			$limite_inferior = date_create($_limite_inferior);
			$limite_superior = date_create($_limite_superior);
			$conn = new Connection();
			$dias_de_incapacidad = array();
			$result = $conn->query("SELECT Incapacidad FROM Trabajador_Incapacidad WHERE Trabajador = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($id) = $conn->fetchRow($result))
			{
				$result1 = $conn->query("SELECT Fecha_de_inicio,Fecha_de_termino FROM Incapacidad WHERE id = '$id' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($fecha_de_inicio,$fecha_de_termino) = $conn->fetchRow($result1);

				if(isset($fecha_de_inicio) && isset($fecha_de_termino))
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

//*********************************************************************************************************************************************
	date_default_timezone_set('America/Mexico_City');
	$conn = new Connection();
	$registro_patronal = $_POST['Registro_patronal'];
	$limite_inferior = $_POST['limite_inferior_del_periodo'];
	$limite_superior = $_POST['limite_superior_del_periodo'];
	$result = $conn->query("SELECT Trabajador.RFC,Trabajador.Nombre, Trabajador.Numero_IMSS, Trabajador.Tipo, Trabajador.Fecha_de_alta_IMSS FROM (Servicio LEFT JOIN Servicio_Trabajador ON Servicio.id = Servicio_Trabajador.Servicio) LEFT JOIN  Trabajador ON Servicio_Trabajador.Trabajador = Trabajador.RFC WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador.Cuenta = '{$_SESSION['cuenta']}'" . ($registro_patronal == "Todos" ? "" : " AND Servicio.Registro_patronal = '$registro_patronal'"));
	$txt = "<table class = 'report_titles'><tr class = 'title'><td colspan = 14>Días cotizados en el IMSS de los trabajadores de" . ($registro_patronal == "Todos" ? " todos los registros patronales" : "l registro patronal $registro_patronal") . "</td></tr><tr class = 'titles'><td>Número de trabajador</td><td>Nombre</td><td>Número de seguro social</td><td>Fecha de alta en IMSS</td><td>Faltas</td><td>Días de incapacidad</td><td>Bajas</td><td>Modificaciones de SDI</td><td>Número de días cotizados</td></tr></table><div class = 'options'><table class = 'report_options'>";
	$_limite_inferior = date_create($limite_inferior);
	$_limite_superior = date_create($limite_superior);
	$numero_de_dias_del_periodo = 0;

	if($_limite_superior > $_limite_inferior)
	{
		$interval = $_limite_inferior->diff($_limite_superior);
		$numero_de_dias_del_periodo = $interval->days + 1;
	}
	else
		$numero_de_dias_del_periodo = 0;

	$dias_del_periodo = array();

	for($i=0; $i<$numero_de_dias_del_periodo; $i++)
	{
		$interval = new DateInterval('P' . $i . 'D');
		$day = $_limite_inferior->add($interval);
		$dias_del_periodo[$i] = $day->format('Y-m-d');
		$day = $_limite_inferior->sub($interval);
	}



	if(isset($result))
	{
		$j = 0;

		while(list($rfc,$nombre,$numero_imss,$tipo,$fecha_de_alta_imss) = $conn->fetchRow($result))
		{
			$tr = '';

			if($tipo == 'Asalariado')
			{

				if(date_create($fecha_de_alta_imss) >= $_limite_inferior && date_create($fecha_de_alta_imss) <= $_limite_superior)
					$alta_imss = $fecha_de_alta_imss;
				else
					$alta_imss = '';

				$tr .= "<td>$nombre</td><td>$numero_imss</td><td>$alta_imss</td>";
				$result1 = $conn->query("SELECT ISRasalariados.Faltas FROM Nomina LEFT JOIN ISRasalariados ON Nomina.id = ISRasalariados.Nomina WHERE ((DATEDIFF(Nomina.Limite_inferior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$limite_superior') <= 0) OR (DATEDIFF(Nomina.Limite_superior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$limite_superior') <= 0)) AND ISRasalariados.Trabajador = '$rfc' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND ISRasalariados.Cuenta = '{$_SESSION['cuenta']}'");
				$total_faltas = '';

				while(list($faltas) = $conn->fetchRow($result1))

					if(isset($faltas))
						$total_faltas .= ($total_faltas == '') ? $faltas : (',' . $faltas);

				$conn->freeResult($result1);
				$_faltas = explode(',', $total_faltas);
				$_incapacidad = calculate_dias_de_incapacidad($limite_inferior, $limite_superior, $rfc);
				$numero_de_dias_de_baja = calculate_numero_de_dias_de_baja($rfc,$limite_inferior,$limite_superior,$dias_del_periodo);
				$dias_de_baja = calculate_dias_de_baja($dias_del_periodo,$limite_inferior,$limite_superior,$numero_de_dias_de_baja,$rfc);
				$numero_de_dias_previos_al_ingreso = calculate_numero_de_dias_previos_al_ingreso($limite_inferior, null, $rfc);//!!!!
				$dias_previos_al_ingreso = calculate_dias_previos_al_ingreso($limite_inferior, $numero_de_dias_previos_al_ingreso);
				$dias_laborados = calculate_dias_laborados($dias_del_periodo, $dias_previos_al_ingreso, $dias_de_baja, $_faltas, $_incapacidad);
				$numero_de_dias_laborados = count($dias_laborados);
				$result1 = $conn->query("SELECT Fecha_de_baja, Fecha_de_reingreso FROM Baja WHERE Trabajador = '$rfc' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$limite_inferior',Fecha_de_baja) <= 0 AND DATEDIFF(Fecha_de_baja, '$limite_superior') <= 0");
				$bajas = '';

				while(list($baja, $reingreso) = $conn->fetchRow($result1))
				{
					$bajas .= 'Baja:' . $baja . '<br/>Reingreso:' . $reingreso . '<br/>';
				}

				$conn->freeResult($result1);
				$numero_de_dias_de_incapacidad = count($_incapacidad);
				$numero_de_faltas = count($_faltas);
				$tr .= "<td>" . ($total_faltas != '' ? $numero_de_faltas : "") . "</td><td>" . ($numero_de_dias_de_incapacidad == 0 ? "" : $numero_de_dias_de_incapacidad) . "</td><td>$bajas</td>";
				$result1 = $conn->query("SELECT cuotas_IMSS.Salario_diario_integrado FROM Nomina LEFT JOIN cuotas_IMSS ON Nomina.id = cuotas_IMSS.Nomina WHERE ((DATEDIFF(Nomina.Limite_inferior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$limite_superior') <= 0) OR (DATEDIFF(Nomina.Limite_superior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$limite_superior') <= 0)) AND cuotas_IMSS.Trabajador = '$rfc' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND cuotas_IMSS.Cuenta = '{$_SESSION['cuenta']}'");
				$total_sdis = '';

				while(list($sdis) = $conn->fetchRow($result1))
				{
					$data = explode(',',$sdis);
					$len = count($data);

					if($len > 1)
					{

						for($i=0; $i<$len; $i++)
						{
							$values = explode('/',$data[$i]);
							$total_sdis .= $values[1] . ' --  $' . round($values[0],2) . '<br/>';
						}

					}

				}

				$conn->freeResult($result1);

				if($alta_imss != '')
				{
					$interval = new DateInterval('P1D');
					$day = date_create($dias_previos_al_ingreso[count($dias_previos_al_ingreso) - 1])->add($interval);

					if(date_create($fecha_de_alta_imss) > $_limite_inferior && date_create($fecha_de_alta_imss) > date_create($day->format('Y-m-d')))
					{
						$interval = $_limite_inferior->diff(date_create($fecha_de_alta_imss));
						//$interval = date_diff(date_create($fecha_de_alta_imss), $_limite_inferior);
						$numero_de_dias_laborados -= $interval->d;
					}

				}

				$tr .= "<td>$total_sdis</td><td>$numero_de_dias_laborados</td></tr>";

				if(isset($_POST['Solo_trabajadores_con_incidencias']))
				{

					if($alta_imss == '' && $total_faltas == '' && count($_incapacidad) == 0  && $bajas == '' && $total_sdis == '')
						$txt .= '';
					else
					{
						$j ++;
						$txt .= "<tr class = 'option'><td>$j</td>" . $tr;
					}

				}
				else
				{

					if($alta_imss == '' && $total_faltas == '' && count($_incapacidad) == 0  && $bajas == '' && $total_sdis == '' && $numero_de_dias_laborados == 0)
						$txt .= '';
					else
					{
						$j ++;
						$txt .= "<tr class = 'option'><td>$j</td>" . $tr;
					}

				}

			}

		}

	}

	$txt .= "</div></table>";
	echo $txt;
?>
