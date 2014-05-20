<?php
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	function calculate_pago_neto($conn,$rfc,$_id)//this function calculates "pago neto" for "Trabajador Asalariado" when editting a "nomina"
	{
		$result = $conn->query("SELECT Pago_neto FROM incidencias WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
		list($pago_neto) = $conn->fetchRow($result);
		$conn->freeResult($result);

		if(isset($pago_neto))
			return $pago_neto;

		$result = $conn->query("SELECT Pago_neto FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
		list($pago_neto) = $conn->fetchRow($result);
		$conn->freeResult($result);

		if($pago_neto != '')
		{
			$result = $conn->query("SELECT Retencion_INFONAVIT FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($retencion_INFONAVIT_txt) = $conn->fetchRow($result);
			$conn->freeResult($result);
			$result = $conn->query("SELECT Retencion_FONACOT FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($retencion_FONACOT_txt) = $conn->fetchRow($result);
			$conn->freeResult($result);
			$result = $conn->query("SELECT Pension_alimenticia FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($pension_alimenticia_txt) =  $conn->fetchRow($result);
			$conn->freeResult($result);
			$retencion_INFONAVIT_array = explode(',',$retencion_INFONAVIT_txt);
			$retencion_FONACOT_array = explode(',',$retencion_FONACOT_txt);
			$pension_alimenticia_array = explode(',',$pension_alimenticia_txt);
			//total retención INFONAVIT
			$len = count($retencion_INFONAVIT_array);
			$total_retencion_INFONAVIT = 0;

			for($i=0; $i<$len; $i++)
			{
				$values = explode('</span>',$retencion_INFONAVIT_array[$i]);

				if(count($values) > 1)
				{
					$value = str_replace('<span>','',$values[1]);//values[0] is id
					$data = explode('/',$value);

					if(count($data) > 1)
						$val = 0;
					else
						$val = $data[0];

					$total_retencion_INFONAVIT += $val;
				}

			}

			//total retención FONACOT
			$len = count($retencion_FONACOT_array);
			$total_retencion_FONACOT = 0;

			for($i=0; $i<$len; $i++)
			{
				$values = explode('</span>',$retencion_FONACOT_array[$i]);

				if(count($values) > 1)
				{
					$value = str_replace('<span>','',$values[1]);//values[0] is id
					$data = explode('/',$value);

					if(count($data) > 1)
						$val = 0;
					else
						$val = $data[0];

					$total_retencion_FONACOT += $val;
				}

			}

			//total pensión alimenticia
			$len = count($pension_alimenticia_array);
			$total_pension_alimenticia = 0;

			for($i=0; $i<$len; $i++)
			{
				$values = explode('</span>',$pension_alimenticia_array[$i]);

				if(count($values) > 1)
				{
					$value = str_replace('<span>','',$values[1]);//values[0] is id
					$data = explode('/',$value);

					if(count($data) > 1)
						$val = 0;
					else
						$val = $data[0];

					$total_pension_alimenticia += $val;
				}

			}

			$pago_neto = $pago_neto + $total_retencion_INFONAVIT + $total_retencion_FONACOT + $total_pension_alimenticia;

			if($pago_neto < 0)
				$pago_neto = 0;

			return number_format($pago_neto,2,'.','');
		}
		else
			return $pago_neto;

	}

	function calculate_pago_liquido($conn,$rfc,$_id,$servicio)//this function calculates "pago líquido" for "Trabajador Asalariado" when editting a "nomina"
	{
		$result = $conn->query("SELECT Pago_liquido FROM incidencias WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
		list($pago_liquido) = $conn->fetchRow($result);
		$conn->freeResult($result);

		if(isset($pago_liquido))
			return $pago_liquido;

		$result = $conn->query("SELECT Pago_liquido FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
		list($pago_liquido) = $conn->fetchRow($result);
		$conn->freeResult($result);

		if($pago_liquido != '' && $pago_liquido > 0.00)
		{
			$result = $conn->query("SELECT dcipla FROM Servicio WHERE id = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($dcipla) = $conn->fetchRow($result);
			$conn->freeResult($result);
			$result = $conn->query("SELECT dppla FROM Servicio WHERE id = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($dppla) = $conn->fetchRow($result);
			$conn->freeResult($result);
			$result = $conn->query("SELECT dgapla FROM Servicio WHERE id = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($dgapla) = $conn->fetchRow($result);
			$conn->freeResult($result);

			//cuotas IMSS obreras
			if($dcipla != 'true')
				$cuotas_IMSS = 0.00;
			else
			{
				$result = $conn->query("SELECT Cuotas_IMSS FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($cuotas_IMSS) = $conn->fetchRow($result);
				$conn->freeResult($result);
			}

			//prestaciones
			if($dppla != 'true')
				$prestaciones = 0.00;
			else
			{
				$result = $conn->query("SELECT Prestaciones FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($prestaciones) = $conn->fetchRow($result);
				$conn->freeResult($result);
			}

			//gestión administrativa
			if($dcipla != 'true')
				$gestion_administrativa = 0.00;
			else
			{
				$result = $conn->query("SELECT Gestion_administrativa FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($gestion_administrativa) = $conn->fetchRow($result);
				$conn->freeResult($result);
			}

			$result = $conn->query("SELECT Retencion_INFONAVIT FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($retencion_INFONAVIT_txt) = $conn->fetchRow($result);
			$conn->freeResult($result);
			$result = $conn->query("SELECT Retencion_FONACOT FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($retencion_FONACOT_txt) = $conn->fetchRow($result);
			$conn->freeResult($result);
			$result = $conn->query("SELECT Pension_alimenticia FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($pension_alimenticia_txt) =  $conn->fetchRow($result);
			$conn->freeResult($result);
			$retencion_INFONAVIT_array = explode(',',$retencion_INFONAVIT_txt);
			$retencion_FONACOT_array = explode(',',$retencion_FONACOT_txt);
			$pension_alimenticia_array = explode(',',$pension_alimenticia_txt);
			//total retención INFONAVIT
			$len = count($retencion_INFONAVIT_array);
			$total_retencion_INFONAVIT = 0;

			for($i=0; $i<$len; $i++)
			{
				$values = explode('</span>',$retencion_INFONAVIT_array[$i]);

				if(count($values) > 1)
				{
					$value = str_replace('<span>','',$values[1]);//values[0] is id
					$data = explode('/',$value);

					if(count($data) > 1)
						$val = 0;
					else
						$val = $data[0];

					$total_retencion_INFONAVIT += $val;
				}

			}

			//total retención FONACOT
			$len = count($retencion_FONACOT_array);
			$total_retencion_FONACOT = 0;

			for($i=0; $i<$len; $i++)
			{
				$values = explode('</span>',$retencion_FONACOT_array[$i]);

				if(count($values) > 1)
				{
					$value = str_replace('<span>','',$values[1]);//values[0] is id
					$data = explode('/',$value);

					if(count($data) > 1)
						$val = 0;
					else
						$val = $data[0];

					$total_retencion_FONACOT += $val;
				}

			}

			//total pensión alimenticia
			$len = count($pension_alimenticia_array);
			$total_pension_alimenticia = 0;

			for($i=0; $i<$len; $i++)
			{
				$values = explode('</span>',$pension_alimenticia_array[$i]);

				if(count($values) > 1)
				{
					$value = str_replace('<span>','',$values[1]);//values[0] is id
					$data = explode('/',$value);

					if(count($data) > 1)
						$val = 0;
					else
						$val = $data[0];

					$total_pension_alimenticia += $val;
				}

			}

			$pago_liquido = $pago_liquido + $cuotas_IMSS + $total_retencion_INFONAVIT + $total_retencion_FONACOT + $total_pension_alimenticia + $prestaciones + $gestion_administrativa;

			if($pago_liquido < 0)
				$pago_liquido = 0;

			return number_format($pago_liquido,2,'.','');
		}
		else
			return $pago_liquido;

	}

	$conn = new Connection();
	date_default_timezone_set('America/Mexico_City');
	$limite_inferior = $_GET['limite_inferior'];
	$limite_superior = $_GET['limite_superior'];
	$servicio = $_GET['servicio'];
	$_id = $_GET['id'];
	$mode = $_GET['mode'];
	$result = $conn->query("SELECT id FROM Nomina WHERE DATEDIFF(Limite_superior_del_periodo, '$limite_inferior') >= 0 AND DATEDIFF(Limite_inferior_del_periodo, '$limite_superior') <= 0 AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
	list($sign) = $conn->fetchRow($result);
	$conn->freeResult($result);
	$result = $conn->query("SELECT id FROM Nomina WHERE DATEDIFF(Limite_superior_del_periodo, '$limite_inferior') >= 0 AND DATEDIFF(Limite_inferior_del_periodo, '$limite_superior') <= 0 AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
	list($sign) = $conn->fetchRow($result);
	$conn->freeResult($result);

	if(isset($sign) && $mode == 'ADD')
		echo "<label>El periodo que ha elegido interfiere con otras nominas</label>";
	else
	{
		//número de días del periodo
		$numero_de_dias_del_periodo = 0;
		$linferior = date_create($limite_inferior);
		$lsuperior = date_create($limite_superior);

		if($lsuperior >= $linferior)
		{
			$interval = $linferior->diff($lsuperior);
			$numero_de_dias_del_periodo = $interval->days + 1;
		}
		else
			$numero_de_dias_del_periodo = 0;

		//días del periodo
		$dias_del_periodo = array();

		for($i=0; $i<$numero_de_dias_del_periodo; $i++)
		{
			$interval = new DateInterval('P'. $i .'D');
			$day = $linferior->add($interval);
			$dias_del_periodo[$i] = $day->format('Y-m-d');
			$day = $linferior->sub($interval);
		}

		$result = $conn->query("SELECT Trabajador.Nombre AS Nombre,Trabajador.RFC AS RFC FROM (Trabajador LEFT JOIN Servicio_Trabajador ON Trabajador.RFC = Servicio_Trabajador.Trabajador) LEFT JOIN Servicio ON Servicio_Trabajador.Servicio = Servicio.id WHERE Servicio.id = '$servicio' AND Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$limite_superior',Servicio_Trabajador.Fecha_de_ingreso_servicio) >= 0 ORDER BY Trabajador.Nombre ASC");
		$txt = "";

		if(isset($result))
		{
			$dias = array();
			$txt = '<table class = "workers_options">';

			while(list($nombre,$rfc) = $conn->fetchRow($result))
			{
				$result1 = $conn->query("SELECT Tipo FROM Tipo WHERE Trabajador = '$rfc' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$limite_superior',Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
				list($tipo) = $conn->fetchRow($result1);
				$conn->freeResult($result1);
				$result1 = $conn->query("SELECT Fecha_de_baja, Fecha_de_reingreso FROM Baja WHERE Trabajador = '$rfc' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'
		 AND (
			(
				DATEDIFF(Fecha_de_baja, '$limite_inferior') <= 0 AND (
											Fecha_de_reingreso = '0000-00-00'
											OR
											DATEDIFF(Fecha_de_reingreso,'$limite_inferior') > 0
										      )
			)

			OR

			(
				DATEDIFF(Fecha_de_baja, '$limite_inferior') > 0 AND (
											DATEDIFF(Fecha_de_baja, '$limite_superior') <= 0
											OR
											Fecha_de_reingreso = '0000-00-00'
										     )
			)
		)");
				$aux = $dias_del_periodo;

				while(list($fecha_de_baja,$fecha_de_reingreso) = $conn->fetchRow($result1))
				{
					//número de días
					$numero_de_dias = 0;
					$baja = date_create($fecha_de_baja);

					if($fecha_de_reingreso == '0000-00-00')
					{

						if($baja >= $limite_inferior)
						{
							$interval = $baja->diff($lsuperior);
							$numero_de_dias = $interval->days;
						}
						else
						{
							$interval = $limite_inferior->diff($lsuperior);
							$numero_de_dias = $interval->days + 1;
						}

					}
					else
					{
						$reingreso = date_create($fecha_de_reingreso);

						if($reingreso > $baja)
						{

							if($baja >= $limite_inferior)
							{
								$interval = $baja->diff($reingreso);
								$numero_de_dias = $interval->days;
							}
							else
							{
								$interval = $limite_inferior->diff($reingreso);
								$numero_de_dias = $interval->days + 1;
							}

						}
						else
							$numero_de_dias = 0;

					}

					//dias de baja
					$dias = array();
					$interval = new DateInterval('P1D');

					for($i=0; $i<$numero_de_dias; $i++)
					{
						$day = $baja->add($interval);
						$dia = $day->format('Y-m-d');
						$dias[$i] = $dia;
					}

					//comparison
					$len = count($dias);

					for($i=0; $i<$len; $i++)
					{
						$_len = count($aux);

						for($j=0; $j<$_len; $j++)

							if(isset($dias[$i]) && isset($aux[$j]) && $dias[$i] == $aux[$j])
							{
								$_aux = array();
								$len_ = count($aux);
								$m = 0;

								for($k=0; $k<$len_; $k++)

									if($aux[$k] != $dias[$i])
									{
										$_aux[$m] = $aux[$k];
										$m++;
									}

								$aux = $_aux;
							}

					}

				}

				if(count($aux) == 0)
					continue;

				$txt .= "<tr onmouseover=\"option_bright(this)\" onmouseout=\"option_opaque(this)\">";//option_bright() and option_opaque() at presentation.js
				$bonos_de_productividad = '';
				$comida = '';
				$compensaciones = '';
				$despensa = '';
				$dias_de_descanso = '';
				$estimulos = '';
				$horas_extra = '';
				$premios_de_puntualidad_y_asistencia = '';
				$prima_dominical = '';
				$faltas = '';
				$retardos = '';
				$pago_neto = '';
				$pago_liquido = '';
				$diferencia = '';
				$forma_de_pago = '';

				if($mode == 'EDIT')
				{
					//getting bonos de productividad
					$result1 = $conn->query("SELECT Bonos_de_productividad FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($bonos_de_productividad) = $conn->fetchRow($result1);
					$conn->freeResult($result1);
					//getting comida
					$result1 = $conn->query("SELECT Comida FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($comida) = $conn->fetchRow($result1);
					$conn->freeResult($result1);
					//getting compensaciones
					$result1 = $conn->query("SELECT Compensaciones FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($compensaciones) = $conn->fetchRow($result1);
					$conn->freeResult($result1);
					//getting despensa
					$result1 = $conn->query("SELECT Despensa FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($despensa) = $conn->fetchRow($result1);
					$conn->freeResult($result1);
					//getting dias de descanso
					$result1 = $conn->query("SELECT Dias_de_descanso FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($dias_de_descanso) = $conn->fetchRow($result1);
					$conn->freeResult($result1);
					//getting estimulos
					$result1 = $conn->query("SELECT Estimulos FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($estimulos) = $conn->fetchRow($result1);
					$conn->freeResult($result1);
					//getting horas extra
					$result1 = $conn->query("SELECT Horas_extra FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($horas_extra) = $conn->fetchRow($result1);
					$conn->freeResult($result1);
					//getting premios de puntualidad y asistencia
					$result1 = $conn->query("SELECT Premios_de_puntualidad_y_asistencia FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($premios_de_puntualidad_y_asistencia) = $conn->fetchRow($result1);
					$conn->freeResult($result1);
					//getting prima dominical
					$result1 = $conn->query("SELECT Domingos_laborados FROM ISRasalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($prima_dominical) = $conn->fetchRow($result1);
					$conn->freeResult($result1);
					//getting faltas
					$result1 = $conn->query("SELECT Faltas FROM ISRasalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($faltas) = $conn->fetchRow($result1);
					$conn->freeResult($result1);
					//getting retardos
					$result1 = $conn->query("SELECT Retardos FROM nomina_asalariados WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($retardos) = $conn->fetchRow($result1);
					$conn->freeResult($result1);

					//getting pago neto
					if($tipo == 'Asalariado')
						$pago_neto = calculate_pago_neto($conn,$rfc,$_id);
					else
					{
						$result1 = $conn->query("SELECT Pago_neto FROM incidencias WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($pago_neto) = $conn->fetchRow($result1);
						$conn->freeResult($result1);

						if(! isset($pago_neto))
						{
							$result1 = $conn->query("SELECT Pago_neto FROM nomina_asimilables WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($pago_neto) = $conn->fetchRow($result1);
							$conn->freeResult($result1);
						}

					}

					//getting pago liquido
					if($tipo == 'Asalariado')
						$pago_liquido = calculate_pago_liquido($conn,$rfc,$_id,$servicio);
					else
					{
						$result1 = $conn->query("SELECT Pago_liquido FROM incidencias WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($pago_liquido) = $conn->fetchRow($result1);
						$conn->freeResult($result1);

						if(! isset($pago_liquido))
						{
							$result1 = $conn->query("SELECT Pago_liquido FROM nomina_asimilables WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($pago_liquido) = $conn->fetchRow($result1);
							$conn->freeResult($result1);
						}

					}

					//getting diferencias
					if($tipo == 'Asalariado')
					{
						$result1 = $conn->query("SELECT Diferencia FROM incidencias WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");

						if($conn->num_rows($result1) > 0)
							list($diferencia) = $conn->fetchRow($result1);
						else
							$diferencia = '';

						$conn->freeResult($result1);
					}

					//getting forma de pago
					$result1 = $conn->query("SELECT Forma_de_pago FROM " . ($tipo == 'Asalariado' ? "nomina_asalariados" : "nomina_asimilables") . " WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($forma_de_pago) = $conn->fetchRow($result1);
					$conn->freeResult($result1);
					//getting Status
					$result1 = $conn->query("SELECT Status FROM " . ($tipo == 'Asalariado' ? "nomina_asalariados" : "nomina_asimilables") . " WHERE Trabajador = '$rfc' AND Nomina = '$_id' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($status) = $conn->fetchRow($result1);
					$conn->freeResult($result1);

					if($status == 'Comprobante timbrado satisfactoriamente')
					{
						$restriction = "style = 'color:#aaa'; readonly = true disabled = true";
						$onclick = "'_alert(\"Recibo timbrado no editable\")'";
					}
					else
					{
						$restriction = "";
						$onclick = "'_capture(this)'";
					}

				}
				else
				{
					$restriction = "";
					$onclick = "'_capture(this)'";
				}

				$n = 0;
				$result2 = $conn->query("SELECT Alimentacion FROM Servicio WHERE id = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($alimentacion) = $conn->fetchRow($result2);

				if($alimentacion == 'true')
					$n ++;

				$result2 = $conn->query("SELECT Habitacion FROM Servicio WHERE id = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($habitacion) = $conn->fetchRow($result2);

				if($habitacion == 'true')
					$n ++;

				$txt .= "<td title = 'Al desmarcar esta casilla el trabajador no saldrá en la nómina' class = 'checkbox_cell'><input type = 'checkbox' checked $restriction/></td><td $restriction>" . $nombre . "</td>" . "<td  $restriction>" . $rfc . '</td>' . ($n == 0 ? ("<td value = '$bonos_de_productividad' name = 'Bonos_de_productividad' title = 'Bonos de productividad' $restriction onclick = $onclick>" . (strlen($bonos_de_productividad) > 6 ? substr ($bonos_de_productividad, 0, 6) . '...' : $bonos_de_productividad) . "</td>") : "") . ($n == 0 ? ("<td value = '$comida' name = 'Comida' title = 'Comida' $restriction onclick = $onclick>" . (strlen($comida) > 6 ? substr ($comida, 0, 6) . '...' : $comida) . "</td>") : "") . ($n == 0 ? ("<td value = '$compensaciones' name = 'Compensaciones' title = 'Compensaciones' $restriction onclick = $onclick>" . (strlen($compensaciones) > 6 ? substr ($compensaciones, 0, 6) . '...' : $compensaciones) . "</td>") : "") . ($n == 0 ? ("<td value = '$despensa' name = 'Despensa' title = 'Despensa' $restriction onclick = $onclick>" . (strlen($despensa) > 6 ? substr ($despensa, 0, 6) . '...' : $despensa) . "</td>") : "") . "<td value = '$dias_de_descanso' name = 'Dias_de_descanso' title = 'Días de descanso' $restriction onclick = $onclick>" . (strlen($dias_de_descanso) > 6 ? substr ($dias_de_descanso, 0, 6) . '...' : $dias_de_descanso) . "</td>" . ($n == 0 ? ("<td value = '$estimulos' name = 'Estimulos' title = 'Estímulos' $restriction onclick = $onclick>" . (strlen($estimulos) > 6 ? substr ($estimulos, 0, 6) . '...' : $estimulos) . "</td>") : "") . "<td value = '$horas_extra' name = 'Horas_extra' title = 'Horas extra' $restriction onclick = $onclick>" . (strlen($horas_extra) > 6 ? substr ($horas_extra, 0, 6) . '...' : $horas_extra) . "</td>" . ($n == 0 ? ("<td value = '$premios_de_puntualidad_y_asistencia' name = 'Premios_de_puntualidad_y_asistencia' title = 'Premios de puntualidad y asistencia' $restriction onclick = $onclick>" . (strlen($premios_de_puntualidad_y_asistencia) > 6 ? substr ($premios_de_puntualidad_y_asistencia, 0, 6) . '...' : $premios_de_puntualidad_y_asistencia) . "</td>") : "") . "<td value = '$prima_dominical' name = 'Prima_dominical' title = 'Prima dominical' $restriction onclick = $onclick>" . (strlen($prima_dominical) > 6 ? substr ($prima_dominical, 0, 6) . '...' : $prima_dominical) . "</td><td value = '$faltas' name = 'Faltas' title = 'Faltas' $restriction onclick = $onclick>" . (strlen($faltas) > 6 ? substr ($faltas, 0, 6) . '...' : $faltas) . "</td><td value = '$retardos' name = 'Retardos' title = 'Retardos' $restriction onclick = $onclick>" . (strlen($retardos) > 6 ? substr ($retardos, 0, 6) . '...' : $retardos) . "</td><td value = '$pago_neto' name = 'Pago_neto' title = 'Pago neto' $restriction onclick = $onclick>" . (strlen($pago_neto) > 6 ? substr ($pago_neto, 0, 6) . '...' : $pago_neto) . "</td>" . "</td><td value = '$pago_liquido' name = 'Pago_liquido' title = 'Pago líquido' $restriction onclick = $onclick>" . (strlen($pago_liquido) > 6 ? substr ($pago_liquido, 0, 6) . '...' : $pago_liquido) . "</td><td value = '$diferencia' name = 'Diferencia_por_vacaciones_y_finiquito' title = 'Diferencia por vacaciones y finiquito' $restriction onclick = $onclick>" . (strlen($diferencia) > 6 ? substr ($diferencia, 0, 6) . '...' : $diferencia) . "</td><td value = '$forma_de_pago' name = 'Forma_de_pago' title = 'Forma de pago' $restriction onclick = $onclick>" . (strlen($forma_de_pago) > 6 ? substr ($forma_de_pago, 0, 6) . '...' : $forma_de_pago) . "</td>";

				$txt .= "</tr>";
			}

			$txt .= '</table>';
		}

		$n = 0;
		$result2 = $conn->query("SELECT Alimentacion FROM Servicio WHERE id = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
		list($alimentacion) = $conn->fetchRow($result2);

		if($alimentacion == 'true')
			$n ++;

		$result2 = $conn->query("SELECT Habitacion FROM Servicio WHERE id = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
		list($habitacion) = $conn->fetchRow($result2);

		if($habitacion == 'true')
			$n ++;

		$conn->freeResult($result2);
		echo '<table class = "workers_titles"><tr class = "column_titles"><td>Nombre</td><td>RFC</td>' . ($n == 0 ? '<td>Bonos de prod.</td>' : '') . ($n == 0 ? '<td>Comida</td>' : '') . ($n == 0 ? '<td>Comp.</td><td>Despensa</td>' : '') . '<td>Días de descanso</td>' . ($n == 0 ? '<td>Estímulos</td>' : '') . '<td>Horas extra</td>' . ($n == 0 ? '<td>Premios de puntualidad y asistencia</td>' : '') . '<td>Prima dominical</td><td>Faltas</td><td>Retardos</td><td>Pago neto</td><td>Pago líquido</td><td>Diferencia por vacaciones y finiquito</td><td>Forma de pago</td></tr></table>';
		echo $txt;
	}

?>
