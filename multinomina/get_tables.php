<?php //this document is oppened by a  function called _load at view.php and gets the 'nomina' tables sparated by 'sucursales'
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

	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$_class = $_GET['_class'];
	$_type = $_GET['_type'];//to difference between nomina report and deposit report
	$_classify = $_GET['_classify'];//to classify or not deposit report
	$nomina = $_GET['nomina'];
	$result = $conn->query("SELECT Nomina.Limite_superior_del_periodo, Servicio_Empresa.Servicio, Servicio_Empresa.Empresa FROM Servicio_Empresa INNER JOIN Nomina ON Servicio_Empresa.Servicio = Nomina.Servicio WHERE Nomina.id = '$nomina' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Nomina.Limite_superior_del_periodo, Servicio_Empresa.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC LIMIT 1");
	list($lsp,$servicio,$empresa) = $conn->fetchRow($result);
	$conn->freeResult($result);
	$txt = '';
	$i = 0;
	$result = $conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
	$n = $conn->num_rows($result);

	if($_type == 'deposit_button')
	{
		$tabla = $_class == "Nomina_asalariados" ? "nomina_asalariados" : "nomina_asimilables";
		$total_fondo = 0.00;
		$total_ppsv = 0.00;
		$total_pfondo = 0.00;
		$total_pension = 0.00;
		$total_pcaja = 0.00;
		$total_pcliente = 0.00;
		$total_padmin = 0.00;
		$total_pago = 0.00;
		$types = array();

		if($_classify == 'yes' && $n > 0)
		{

			while(list($sucursal) = $conn->fetchRow($result))
			{

				$total_sucursal_fondo = 0.00;
				$total_sucursal_ppsv = 0.00;
				$total_sucursal_pfondo = 0.00;
				$total_sucursal_pension = 0.00;
				$total_sucursal_pcaja = 0.00;
				$total_sucursal_pcliente = 0.00;
				$total_sucursal_padmin = 0.00;
				$total_sucursal_pago = 0.00;
				$types_sucursal = array();

				if($_class == 'Nomina_asalariados')
				{
					$txt .= '<table><tr><td colspan = "12" class = "title">Sucursal ' . $sucursal . '</td></tr><tr><td class = "column_title">Número de cuenta</td><td class = "column_title">Neto a recibir</td><td class = "column_title">Nombre</td><td class = "column_title">Forma de pago</td><td class = "column_title">Banco</td><td class = "column_title">Pensión alimenticia</td><td class = "column_title">Fondo de garantía</td><td class = "column_title">Pago por seguro de vida</td><td class = "column_title">Préstamo del fondo de ahorro</td><td class = "column_title">Préstamo de caja</td><td class = "column_title">Préstamo de cliente</td><td class = "column_title">Préstamo de administradora</td></tr>';
					$result1 = $conn->query("SELECT Trabajador.RFC, Trabajador.Nombre, nomina_asalariados.Forma_de_pago, nomina_asalariados.Pago_por_seguro_de_vida, nomina_asalariados.Fondo_de_garantia, nomina_asalariados.Prestamo_del_fondo_de_ahorro, nomina_asalariados.Pension_alimenticia, nomina_asalariados.Prestamo_caja, nomina_asalariados.Prestamo_cliente, nomina_asalariados.Prestamo_administradora, nomina_asalariados.Neto_a_recibir, n, cta FROM Nomina LEFT JOIN nomina_asalariados ON Nomina.id = nomina_asalariados.Nomina LEFT JOIN Trabajador ON nomina_asalariados.Trabajador = Trabajador.RFC LEFT JOIN (SELECT Nombre n, Trabajador t, Numero_de_cuenta cta, Fecha f, Servicio s FROM Banco WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha DESC) bank ON Nomina.Servicio = s AND nomina_asalariados.Trabajador = t WHERE nomina_asalariados.Nomina = '$nomina' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND nomina_asalariados.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND (DATEDIFF('$lsp', f) >= 0 OR f IS NULL) GROUP BY nomina_asalariados.Trabajador ORDER BY nomina_asalariados.Forma_de_pago ASC, n ASC, Trabajador.Nombre ASC");

					while(list($trabajador, $nombre, $fdp, $ppsv, $fondo, $pfondo, $pension, $pcaja, $pcliente, $padmin, $neto, $banco, $cta) = $conn->fetchRow($result1))
					{
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre = '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($fdi) = $conn->fetchRow($result2);
						$conn->freeResult($result2);
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre != '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_ingreso, '$fdi') >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($flag) = $conn->fetchRow($result2);
						$conn->freeResult($result2);

						if(isset($fdi) && !isset($flag))
						{
							if(!isset($types[$fdp . ' ' . $banco]))
								$types[$fdp . ' ' . $banco] = $neto;
							else
								$types[$fdp . ' ' . $banco] += $neto;

							if(!isset($types_sucursal[$fdp . ' ' . $banco]))
								$types_sucursal[$fdp . ' ' . $banco] = $neto;
							else
								$types_sucursal[$fdp . ' ' . $banco] += $neto;

							$_ppsv = total($ppsv);
							$_pfondo = total($pfondo);
							$_pension = total($pension);
							$_pcaja = total($pcaja);
							$_pcliente = total($pcliente);
							$_padmin = total($padmin);
							$total_ppsv += $_ppsv;
							$total_fondo += $fondo;
							$total_pfondo += $_pfondo;
							$total_pension += $_pension;
							$total_pcaja += $_pcaja;
							$total_pcliente += $_pcliente;
							$total_padmin += $_padmin;
							$total_pago += $neto + $_ppsv + $fondo + $_pfondo + $_pcaja + $_pcliente + $_padmin;
							$total_sucursal_ppsv += $_ppsv;
							$total_sucursal_fondo += $fondo;
							$total_sucursal_pfondo += $_pfondo;
							$total_sucursal_pension += $_pension;
							$total_sucursal_pcaja += $_pcaja;
							$total_sucursal_pcliente += $_pcliente;
							$total_sucursal_padmin += $_padmin;
							$total_sucursal_pago += $neto + $_ppsv + $fondo + $_pfondo + $_pcaja + $_pcliente + $_padmin;
							$txt .= "<tr><td>$cta</td><td>$neto</td><td>$nombre</td><td>$fdp</td><td>$banco</td><td>" . number_format($_pension,2,'.',',') . "</td><td>" . number_format($fondo,2,'.',',') . "</td><td>" . number_format($_ppsv,2,'.',',') . "</td><td>" . number_format($_pfondo,2,'.',',') . "</td><td>" . number_format($_pcaja,2,'.',',') . "</td><td>" . number_format($_pcliente,2,'.',',') . "</td><td>" . number_format($_padmin,2,'.',',') . "</td></tr>";
						}

					}

				}
				else//Nomina_asimilables
				{
					$txt .= '<table><tr><td colspan = "9" class = "title">Sucursal ' . $sucursal . '</td></tr><tr><td class = "column_title">Número de cuenta</td><td class = "column_title">Neto a recibir</td><td class = "column_title">Nombre</td><td class = "column_title">Forma de pago</td><td class = "column_title">Banco</td><td class = "column_title">Pago por seguro de vida</td><td class = "column_title">Préstamo de caja</td><td class = "column_title">Préstamo de cliente</td><td class = "column_title">Préstamo de administradora</td></tr>';
					$result1 = $conn->query("SELECT Trabajador.RFC, Trabajador.Nombre, nomina_asimilables.Forma_de_pago, nomina_asimilables.Pago_por_seguro_de_vida, nomina_asimilables.Prestamo_caja, nomina_asimilables.Prestamo_cliente, nomina_asimilables.Prestamo_administradora, nomina_asimilables.Neto_a_recibir, n, cta FROM Nomina LEFT JOIN nomina_asimilables ON Nomina.id = nomina_asimilables.Nomina LEFT JOIN Trabajador ON nomina_asimilables.Trabajador = Trabajador.RFC LEFT JOIN (SELECT Nombre n, Trabajador t, Numero_de_cuenta cta, Fecha f, Servicio s FROM Banco WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha DESC) bank ON Nomina.Servicio = s AND nomina_asimilables.Trabajador = t WHERE nomina_asimilables.Nomina = '$nomina' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND nomina_asimilables.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND (DATEDIFF('$lsp', f) >= 0 OR f IS NULL) GROUP BY nomina_asimilables.Trabajador ORDER BY nomina_asimilables.Forma_de_pago ASC, n ASC, Trabajador.Nombre ASC");

					while(list($trabajador, $nombre, $fdp, $ppsv, $pcaja, $pcliente, $padmin, $neto, $banco, $cta) = $conn->fetchRow($result1))
					{
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre = '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($fdi) = $conn->fetchRow($result2);
						$conn->freeResult($result2);
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre != '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_ingreso, '$fdi') >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($flag) = $conn->fetchRow($result2);
						$conn->freeResult($result2);

						if(isset($fdi) && !isset($flag))
						{
							if(!isset($types[$fdp . ' ' . $banco]))
								$types[$fdp . ' ' . $banco] = $neto;
							else
								$types[$fdp . ' ' . $banco] += $neto;

							if(!isset($types_sucursal[$fdp . ' ' . $banco]))
								$types_sucursal[$fdp . ' ' . $banco] = $neto;
							else
								$types_sucursal[$fdp . ' ' . $banco] += $neto;

							$_ppsv = total($ppsv);
							$_pcaja = total($pcaja);
							$_pcliente = total($pcliente);
							$_padmin = total($padmin);
							$total_ppsv += $_ppsv;
							$total_pcaja += $_pcaja;
							$total_pcliente += $_pcliente;
							$total_padmin += $_padmin;
							$total_pago += $neto + $_ppsv + $_pcaja + $_pcliente + $_padmin;
							$total_sucursal_ppsv += $_ppsv;
							$total_sucursal_pcaja += $_pcaja;
							$total_sucursal_pcliente += $_pcliente;
							$total_sucursal_padmin += $_padmin;
							$total_sucursal_pago += $neto + $_ppsv + $_pcaja + $_pcliente + $_padmin;
							$txt .= "<tr><td>$cta</td><td>$neto</td><td>$nombre</td><td>$fdp</td><td>$banco</td><td>" . number_format($_ppsv,2,'.',',') . "</td><td>" . number_format($_pcaja,2,'.',',') . "</td><td>" . number_format($_pcliente,2,'.',',') . "</td><td>" . number_format($_padmin,2,'.',',') . "</td></tr>";
						}

					}

				}

				$txt .= '</table>';
				$txt .= "<table><tr><td colspan = 2>Total sucursal $sucursal</td></tr>";

				foreach($types_sucursal as $type => $amount)
					$txt .= "<tr><td>$type</td><td style='text-align:right'>". number_format($amount,2,'.',',') . "</td></tr>";

				$txt .= $total_sucursal_fondo > 0.00 ? '<tr><td>Fondo de garantía</td><td style="text-align:right">'. number_format($total_sucursal_fondo,2,'.',',') . '</td></tr>' : '';
				$txt .= $total_sucursal_ppsv > 0.00 ? '<tr><td>Pago por seguro de vida</td><td style="text-align:right">'. number_format($total_sucursal_ppsv,2,'.',',') . '</td></tr>' : '';
				$txt .= $total_sucursal_pfondo > 0.00 ? '<tr><td>Préstamo del fondo de ahorro</td><td style="text-align:right">'. number_format($total_sucursal_pfondo,2,'.',',') . '</td></tr>' : '';
				$txt .= $total_sucursal_pension > 0.00 ? '<tr><td>Pago por pensión alimenticia</td><td style="text-align:right">'. number_format($total_sucursal_pension,2,'.',',') . '</td></tr>' : '';
				$txt .= $total_sucursal_pcaja > 0.00 ? '<tr><td>Préstamo de caja</td><td style="text-align:right">'. number_format($total_sucursal_pcaja,2,'.',',') . '</td></tr>' : '';
				$txt .= $total_sucursal_pcliente > 0.00 ? '<tr><td>Préstamo de cliente</td><td style="text-align:right">'. number_format($total_sucursal_pcliente,2,'.',',') . '</td></tr>' : '';
				$txt .= $total_sucursal_padmin > 0.00 ? '<tr><td>Préstamo de administradora</td><td style="text-align:right">'. number_format($total_sucursal_padmin,2,'.',',') . '</td></tr>' : '';
				$txt .= '<tr style="background:#eee"><td>Total a pagar</td><td style="text-align:right">$'. number_format($total_sucursal_pago,2,'.',',') . '</td></tr>';
				$txt .= '</table>';
			}

		}
		else
		{
			$txt .= '<table><tr><td colspan = "12" class = "title">' . ($_class == 'Nomina_asalariados' ? 'Asalariados' : 'Asimilables') . '</td></tr><tr><td class = "column_title">Número de cuenta</td><td class = "column_title">Neto a recibir</td><td class = "column_title">Nombre</td><td class = "column_title">Forma de pago</td><td class = "column_title">Banco</td>' . ($_class == 'Nomina_asalariados' ? '<td class = "column_title">Pensión alimenticia</td><td class = "column_title">Fondo de garantía</td>' : '') . '<td class = "column_title">Pago por seguro de vida</td>' . ($_class == 'Nomina_asalariados' ? '<td class = "column_title">Préstamo del fondo de ahorro</td>' : '') . '<td class = "column_title">Préstamo de caja</td><td class = "column_title">Préstamo de cliente</td><td class = "column_title">Préstamo de administradora</td></tr>';
			$result1 = $conn->query("SELECT Trabajador.RFC, Trabajador.Nombre, cta, n, $tabla.Forma_de_pago, $tabla.Pago_por_seguro_de_vida, " . ($tabla == 'nomina_asalariados' ? "$tabla.Fondo_de_garantia, $tabla.Prestamo_del_fondo_de_ahorro, $tabla.Pension_alimenticia, " : "") . "$tabla.Prestamo_caja, $tabla.Prestamo_cliente, $tabla.Prestamo_administradora, $tabla.Neto_a_recibir FROM Nomina LEFT JOIN $tabla ON Nomina.id = $tabla.Nomina LEFT JOIN Trabajador ON $tabla.Trabajador = Trabajador.RFC LEFT JOIN (SELECT Nombre n, Trabajador t, Numero_de_cuenta cta, Fecha f, Servicio s FROM Banco WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha DESC) bank ON Nomina.Servicio = s AND $tabla.Trabajador = t WHERE $tabla.Nomina = '$nomina' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND $tabla.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND (DATEDIFF('$lsp', f) >= 0 OR f IS NULL) GROUP BY $tabla.Trabajador ORDER BY $tabla.Forma_de_pago ASC, n ASC, Trabajador.Nombre ASC");

			if($tabla == 'nomina_asalariados')
			{

				while(list($trabajador, $nombre, $cta_bancaria, $banco, $fdp, $ppsv, $fondo, $pfondo, $pension, $pcaja, $pcliente, $padmin, $neto) = $conn->fetchRow($result1))
				{

					if(!isset($types[$fdp . ' ' . $banco]))
						$types[$fdp . ' ' . $banco] = $neto;
					else
						$types[$fdp . ' ' . $banco] += $neto;

					$_ppsv = total($ppsv);
					$_pfondo = total($pfondo);
					$_pension = total($pension);
					$_pcaja = total($pcaja);
					$_pcliente = total($pcliente);
					$_padmin = total($padmin);
					$total_ppsv += $_ppsv;
					$total_fondo += $fondo;
					$total_pfondo += $_pfondo;
					$total_pension += $_pension;
					$total_pcaja += $_pcaja;
					$total_pcliente += $_pcliente;
					$total_padmin += $_padmin;
					$total_pago += $neto + $_ppsv + $fondo + $_pfondo + $_pcaja + $_pcliente + $_padmin;
					$txt .= "<tr><td>$cta_bancaria</td><td>$neto</td><td>$nombre</td><td>$fdp</td><td>$banco</td><td>" . number_format($_pension,2,'.',',') . "</td><td>" . number_format($fondo,2,'.',',') . "</td><td>" . number_format($_ppsv,2,'.',',') . "</td><td>" . number_format($_pfondo,2,'.',',') . "</td><td>" . number_format($_pcaja,2,'.',',') . "</td><td>" . number_format($_pcliente,2,'.',',') . "</td><td>" . number_format($_padmin,2,'.',',') . "</td></tr>";
				}

			}
			else
			{

				while(list($trabajador, $nombre, $cta_bancaria, $banco, $fdp, $ppsv, $pcaja, $pcliente, $padmin, $neto) = $conn->fetchRow($result1))
				{

					if(!isset($types[$fdp . ' ' . $banco]))
						$types[$fdp . ' ' . $banco] = $neto;
					else
						$types[$fdp . ' ' . $banco] += $neto;

					$_ppsv = total($ppsv);
					$_pcaja = total($pcaja);
					$_pcliente = total($pcliente);
					$_padmin = total($padmin);
					$total_ppsv += $_ppsv;
					$total_pcaja += $_pcaja;
					$total_pcliente += $_pcliente;
					$total_padmin += $_padmin;
					$total_pago += $neto + $_ppsv + $_pcaja + $_pcliente + $_padmin;
					$txt .= "<tr><td>$cta_bancaria</td><td>$neto</td><td>$nombre</td><td>$fdp</td><td>$banco</td><td>" .  number_format($_ppsv,2,'.',',') . "</td><td>" . number_format($_pcaja,2,'.',',') . "</td><td>" . number_format($_pcliente,2,'.',',') . "</td><td>" . number_format($_padmin,2,'.',',') . "</td></tr>";
				}

			}

			$txt .= '</table>';
		}

		$txt .= '<table><tr><td colspan = 2>Totales</td></tr>';

		foreach($types as $type => $amount)
			$txt .= "<tr><td>$type</td><td style='text-align:right'>". number_format($amount,2,'.',',') . "</td></tr>";

		$txt .= $total_fondo > 0.00 ? '<tr><td>Fondo de garantía</td><td style="text-align:right">'. number_format($total_fondo,2,'.',',') . '</td></tr>' : '';
		$txt .= $total_ppsv > 0.00 ? '<tr><td>Pago por seguro de vida</td><td style="text-align:right">'. number_format($total_ppsv,2,'.',',') . '</td></tr>' : '';
		$txt .= $total_pfondo > 0.00 ? '<tr><td>Préstamo del fondo de ahorro</td><td style="text-align:right">'. number_format($total_pfondo,2,'.',',') . '</td></tr>' : '';
		$txt .= $total_pension > 0.00 ? '<tr><td>Pago por pensión alimenticia</td><td style="text-align:right">'. number_format($total_pension,2,'.',',') . '</td></tr>' : '';
		$txt .= $total_pcaja > 0.00 ? '<tr><td>Préstamo de caja</td><td style="text-align:right">'. number_format($total_pcaja,2,'.',',') . '</td></tr>' : '';
		$txt .= $total_pcliente > 0.00 ? '<tr><td>Préstamo de cliente</td><td style="text-align:right">'. number_format($total_pcliente,2,'.',',') . '</td></tr>' : '';
		$txt .= $total_padmin > 0.00 ? '<tr><td>Préstamo de administradora</td><td style="text-align:right">'. number_format($total_padmin,2,'.',',') . '</td></tr>' : '';
		$txt .= '<tr style="background:#eee"><td>Total a pagar</td><td style="text-align:right">$'. number_format($total_pago,2,'.',',') . '</td></tr>';
		$txt .= '</table>';
	}
	else
	{

		while(list($sucursal) = $conn->fetchRow($result))
		{

			if($_class != 'Datos_fieldset')
			{

				if($_class == 'ISRasalariados_fieldset')
				{
					$txt .= '<table id="ISRasalariados"><tr><td colspan = "40" class = "title">Trabajadores asalariados de la sucursal ' . $sucursal . '</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">NSS</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Número de días previos al ingreso</td><td class = "column_title">Número de días de baja</td><td class = "column_title">Faltas</td><td class = "column_title">Número de faltas</td><td class = "column_title">Número de días de incapacidad</td><td class = "column_title">Número de días de vacaciones</td><td class = "column_title">Número de días laborados</td><td class = "column_title">Salario diario</td><td class = "column_title">Sueldo</td><td class = "column_title">Horas extra</td><td class = "column_title">Horas extra gravadas</td><td class = "column_title">Domingos laborados</td><td class = "column_title">Número de domingos laborados</td><td class = "column_title">Prima dominical</td><td class = "column_title">Prima dominical gravada</td><td class = "column_title">Días de descanso</td><td class = "column_title">Días de descanso gravados</td><td class = "column_title">Premios de puntualidad y asistencia</td><td class = "column_title">Bonos de productividad</td><td class = "column_title">Estímulos</td><td class = "column_title">Compensaciones</td><td class = "column_title">Despensa</td><td class = "column_title">Comida</td><td class = "column_title">Previsión social gravada</td><td class = "column_title">Base</td><td class = "column_title">Límite inferior</td><td class = "column_title">Exedente del límite inferior</td><td class = "column_title">Porcentaje sobre el exedente del límite inferior</td><td class = "column_title">Impuesto marginal</td><td class = "column_title">Cuota fija</td><td class = "column_title">Impuesto determinado</td><td class = "column_title">Subsidio</td><td class = "column_title">ISR</td><td class = "column_title">Subsidio al empleo</td></tr>';
					$result1 = $conn->query("SELECT Trabajador FROM ISRasalariados WHERE Nomina = '$nomina' AND Cuenta = '{$_SESSION['cuenta']}'");
					$n = 1;

					while(list($trabajador) = $conn->fetchRow($result1))
					{
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre = '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($fdi) = $conn->fetchRow($result2);
						$conn->freeResult($result2);
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre != '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_ingreso, '$fdi') >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($flag) = $conn->fetchRow($result2);
						$conn->freeResult($result2);

						if(isset($fdi) && !isset($flag))
						{
							$result2 = $conn->query("SELECT * FROM ISRasalariados WHERE Nomina = '$nomina' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");


							while($row = $conn->fetchRow($result2,'ASSOC'))
							{
								$txt .= '<tr>';

								foreach($row as $key => $value)

									if($key == 'Trabajador')
									{
										$result3 = $conn->query("SELECT Nombre, Numero_IMSS, CURP FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
										list($nombre,$nss,$curp) = $conn->fetchRow($result3);
										$txt .= "<td>$n</td><td>$nombre</td><td>$nss</td><td>$curp</td><td>$trabajador</td>";
										$conn->freeResult($result3);
										$n ++;
									}
									elseif($key != 'Nomina' && $key != 'Cuenta')
										$txt .= "<td>$value</td>";

								$txt .= '</tr>';
							}

						}

					}

				}
				if($_class == 'IMSS')
				{
					$txt .= '<table id="cuotas_IMSS"><tr><td colspan = "42" class = "title">Trabajadores asalariados de la sucursal ' . $sucursal . '</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Número de días previos al ingreso</td><td class = "column_title">Número de días de baja</td><td class = "column_title">Número de días de incapacidad</td><td class = "column_title">Número de días cotizados</td><td class = "column_title">Años de antigüedad</td><td class = "column_title">Días de vacaciones</td><td class = "column_title">Factor de prima vacacional</td><td class = "column_title">Factor de aguinaldo</td><td class = "column_title">Factor de integración</td><td class = "column_title">Salario diario integrado</td><td class = "column_title">Sueldo integrado</td><td class = "column_title">Cuota fija IMSS</td><td class = "column_title">Exedente 3 SMGDF patronal</td><td class = "column_title">Exedente 3 SMGDF obrero</td><td class = "column_title">Prestaciones en dinero obreras</td><td class = "column_title">Prestaciones en dinero patronales</td><td class = "column_title">Gastos médicos y pensión obreros</td><td class = "column_title">Gastos médicos y pensión patronales</td><td class = "column_title">Invalidez y vida obrera</td><td class = "column_title">Invalidez y vida patronal</td><td class = "column_title">Cesantía y vejez obrera</td><td class = "column_title">Cesantía y vejez patronal</td><td class = "column_title">Guardería</td><td class = "column_title">Retiro</td><td class = "column_title">INFONAVIT</td><td class = "column_title">Riesgo de trabajo</td><td class = "column_title">Total de cuotas IMSS obreras</td><td class = "column_title">Total de cuotas IMSS patronales</td><td class = "column_title">Adeudo</td></tr>';
					$result1 = $conn->query("SELECT Trabajador FROM cuotas_IMSS WHERE Nomina = '$nomina' AND Cuenta = '{$_SESSION['cuenta']}'");
					$n = 1;

					while(list($trabajador) = $conn->fetchRow($result1))
					{
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre = '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($fdi) = $conn->fetchRow($result2);
						$conn->freeResult($result2);
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre != '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_ingreso, '$fdi') >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($flag) = $conn->fetchRow($result2);
						$conn->freeResult($result2);

						if(isset($fdi) && !isset($flag))
						{
							$result2 = $conn->query("SELECT * FROM cuotas_IMSS WHERE Nomina = '$nomina' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");


							while($row = $conn->fetchRow($result2,'ASSOC'))
							{
								$txt .= '<tr>';

								foreach($row as $key => $value)

									if($key == 'Trabajador')
									{
										$result3 = $conn->query("SELECT Nombre FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
										list($nombre) = $conn->fetchRow($result3);
										$txt .= "<td>$n</td><td>$nombre</td><td>$value</td>";
										$conn->freeResult($result3);
										$n ++;
									}
									elseif($key != 'Nomina' && $key != 'Cuenta')
										$txt .= "<td>$value</td>";

								$txt .= '</tr>';
							}

						}

					}

				}
				if($_class == 'Nomina_asalariados')
				{
					$txt .= '<table id="nomina_asalariados"><tr><td colspan = "46" class = "title">Trabajadores asalariados de la sucursal ' . $sucursal . '</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">NSS</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días laborados</td><td class = "column_title">Salario</td><td class = "column_title">Sueldo</td><td class = "column_title">Subsidio al empleo</td><td class = "column_title">Horas extra</td><td class = "column_title">Prima dominical</td><td class = "column_title">Días de descanso</td><td class = "column_title">Premios de puntualidad y asistencia</td><td class = "column_title">Bonos de productividad</td><td class = "column_title">Estímulos</td><td class = "column_title">Compensaciones</td><td class = "column_title">Despensa</td><td class = "column_title">Comida</td><td class = "column_title">Alimentación</td><td class = "column_title">Habitación</td><td class = "column_title">Aportación patronal al fondo de ahorro</td><td class = "column_title">Total de percepciones</td><td class = "column_title">ISR</td><td class = "column_title">Cuotas IMSS</td><td class = "column_title">Retención por alimentación</td><td class = "column_title">Retención por habitación</td><td class = "column_title">Retención INFONAVIT</td><td class = "column_title">Retención FONACOT</td><td class = "column_title">Aportación del trabajador al fondo de ahorro</td><td class = "column_title">Pensión alimenticia</td><td class = "column_title">Retardos</td><td class = "column_title">Prestaciones</td><td class = "column_title">Gestión administrativa</td><td class = "column_title">Total de deducciones</td><td class = "column_title">Saldo</td><td class = "column_title">Pago neto</td><td class = "column_title">Pago líquido</td><td class = "column_title">Fondo de garantía</td><td class = "column_title">Pago por seguro de vida</td><td class = "column_title">Préstamo del fondo de ahorro</td><td class = "column_title">Préstamo de caja</td><td class = "column_title">Préstamo de cliente</td><td class = "column_title">Préstamo de administradora</td><td class = "column_title">Neto a recibir</td><td class = "column_title">Forma de pago</td><td class = "column_title">Status</td></tr>';
					$result1 = $conn->query("SELECT Trabajador FROM nomina_asalariados WHERE Nomina = '$nomina' AND Cuenta = '{$_SESSION['cuenta']}'");
					$n = 1;

					while(list($trabajador) = $conn->fetchRow($result1))
					{
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre = '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($fdi) = $conn->fetchRow($result2);
						$conn->freeResult($result2);
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre != '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_ingreso, '$fdi') >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($flag) = $conn->fetchRow($result2);
						$conn->freeResult($result2);

						if(isset($fdi) && !isset($flag))
						{
							$result2 = $conn->query("SELECT * FROM nomina_asalariados WHERE Nomina = '$nomina' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");


							while($row = $conn->fetchRow($result2,'ASSOC'))
							{
								$txt .= '<tr>';

								foreach($row as $key => $value)

									if($key == 'Trabajador')
									{
										$result3 = $conn->query("SELECT Nombre, Numero_IMSS, CURP FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
										list($nombre,$nss,$curp) = $conn->fetchRow($result3);
										$txt .= "<td>$n</td><td>$nombre</td><td>$nss</td><td>$curp</td><td>$value</td>";
										$conn->freeResult($result3);
										$n ++;
									}
									elseif($key != 'Nomina' && $key != 'Cuenta')
										$txt .= "<td>$value</td>";

								$txt .= '</tr>';
							}

						}

					}

				}
				if($_class == 'Prestaciones')
				{
					$txt .= '<table id="prestaciones"><tr><td colspan = "18" class = "title">Trabajadores asalariados de la sucursal ' . $sucursal . '</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">NSS</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Años de antigüedad</td><td class = "column_title">Sueldo</td><td class = "column_title">Vacaciones</td><td class = "column_title">Retención proporcional de vacaciones</td><td class = "column_title">Prima vacacional</td><td class = "column_title">Retención proporcional de prima vacacional</td><td class = "column_title">Aguinaldo</td><td class = "column_title">Retención proporcional de aguinaldo</td><td class = "column_title">Prima de antigüedad</td><td class = "column_title">Retención proporcional de prima de antigüedad</td><td class = "column_title">Total de prestaciones</td><td class = "column_title">Total de retenciones</td></tr>';
					$result1 = $conn->query("SELECT Trabajador FROM prestaciones WHERE Nomina = '$nomina' AND Cuenta = '{$_SESSION['cuenta']}'");
					$n = 1;

					while(list($trabajador) = $conn->fetchRow($result1))
					{
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre = '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($fdi) = $conn->fetchRow($result2);
						$conn->freeResult($result2);
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre != '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_ingreso, '$fdi') >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($flag) = $conn->fetchRow($result2);
						$conn->freeResult($result2);

						if(isset($fdi) && !isset($flag))
						{
							$result2 = $conn->query("SELECT * FROM prestaciones WHERE Nomina = '$nomina' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");


							while($row = $conn->fetchRow($result2,'ASSOC'))
							{
								$txt .= '<tr>';

								foreach($row as $key => $value)

									if($key == 'Trabajador')
									{
										$result3 = $conn->query("SELECT Nombre, Numero_IMSS, CURP FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
										list($nombre,$nss,$curp) = $conn->fetchRow($result3);
										$txt .= "<td>$n</td><td>$nombre</td><td>$nss</td><td>$curp</td><td>$value</td>";
										$conn->freeResult($result3);
										$n ++;
									}
									elseif($key != 'Nomina' && $key != 'Cuenta')
										$txt .= "<td>$value</td>";

								$txt .= '</tr>';
							}

						}

					}

				}
				if($_class == 'ISRasimilables_fieldset')
				{
					$txt .= '<table id="ISRasimilables"><tr><td colspan = "21" class = "title">Sucursal ' . $sucursal . '</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Número de días previos al ingreso</td><td class = "column_title">Número de días de baja</td><td class = "column_title">Número de días laborados</td><td class = "column_title">Salario diario</td><td class = "column_title">Honorarios asimilados</td><td class = "column_title">Base</td><td class = "column_title">Límite inferior</td><td class = "column_title">Exedente del límite inferior</td><td class = "column_title">Porcentaje sobre el exedente del límite inferior</td><td class = "column_title">Impuesto marginal</td><td class = "column_title">Cuota fija</td><td class = "column_title">Impuesto determinado</td><td class = "column_title">Subsidio</td><td class = "column_title">ISR</td><td class = "column_title">Subsidio al empleo</td></tr>';
					$result1 = $conn->query("SELECT Trabajador FROM ISRasimilables WHERE Nomina = '$nomina' AND Cuenta = '{$_SESSION['cuenta']}'");
					$n = 1;

					while(list($trabajador) = $conn->fetchRow($result1))
					{
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre = '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($fdi) = $conn->fetchRow($result2);
						$conn->freeResult($result2);
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre != '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_ingreso, '$fdi') >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($flag) = $conn->fetchRow($result2);
						$conn->freeResult($result2);

						if(isset($fdi) && !isset($flag))
						{
							$result2 = $conn->query("SELECT * FROM ISRasimilables WHERE Nomina = '$nomina' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");


							while($row = $conn->fetchRow($result2,'ASSOC'))
							{
								$txt .= '<tr>';

								foreach($row as $key => $value)

									if($key == 'Trabajador')
									{
										$result3 = $conn->query("SELECT Nombre, CURP FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
										list($nombre,$curp) = $conn->fetchRow($result3);
										$txt .= "<td>$n</td><td>$nombre</td><td>$curp</td><td>$value</td>";
										$conn->freeResult($result3);
										$n ++;
									}
									elseif($key != 'Nomina' && $key != 'Cuenta')
										$txt .= "<td>$value</td>";

								$txt .= '</tr>';
							}

						}

					}

				}
				if($_class == 'Nomina_asimilables')
				{
					$txt .= '<table id="nomina_asimilables"><tr><td colspan = "20" class = "title">Sucursal ' . $sucursal . '</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Honorarios asimilados</td><td class = "column_title">Total de percepciones</td><td class = "column_title">ISR</td><td class = "column_title">Gestión administrativa</td><td class = "column_title">Total de deducciones</td><td class = "column_title">Saldo</td><td class = "column_title">Pago neto</td><td class = "column_title">Pago líquido</td><td class = "column_title">Pago por seguro de vida</td><td class = "column_title">Préstamo de caja</td><td class = "column_title">Préstamo de cliente</td><td class = "column_title">Préstamo de administradora</td><td class = "column_title">Neto a recibir</td><td class = "column_title">Forma de pago</td></tr>';
					$result1 = $conn->query("SELECT Trabajador FROM nomina_asimilables WHERE Nomina = '$nomina' AND Cuenta = '{$_SESSION['cuenta']}'");
					$n = 1;

					while(list($trabajador) = $conn->fetchRow($result1))
					{
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre = '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($fdi) = $conn->fetchRow($result2);
						$conn->freeResult($result2);
						$result2 = $conn->query("SELECT Fecha_de_ingreso FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre != '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_ingreso, '$fdi') >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
						list($flag) = $conn->fetchRow($result2);
						$conn->freeResult($result2);

						if(isset($fdi) && !isset($flag))
						{
							$result2 = $conn->query("SELECT * FROM nomina_asimilables WHERE Nomina = '$nomina' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");


							while($row = $conn->fetchRow($result2,'ASSOC'))
							{
								$txt .= '<tr>';

								foreach($row as $key => $value)

									if($key == 'Trabajador')
									{
										$result3 = $conn->query("SELECT Nombre, CURP FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
										list($nombre,$curp) = $conn->fetchRow($result3);
										$txt .= "<td>$n</td><td>$nombre</td><td>$curp</td><td>$value</td>";
										$conn->freeResult($result3);
										$n ++;
									}
									elseif($key != 'Nomina' && $key != 'Cuenta')
										$txt .= "<td>$value</td>";

								$txt .= '</tr>';
							}

						}

					}

				}

				$txt .= '</table>';
			}
			else
			{

				if($i < ($n-1))
					$txt .= $sucursal . ',';
				else
					$txt .= $sucursal;

				$i++;
			}

		}

	}

	if($_class != 'Datos_fieldset')
	{
		$result = $conn->query("SELECT Registro_patronal.Numero, Registro_patronal.Empresa, Registro_patronal.Empresa_sucursal FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero WHERE Servicio_Registro_patronal.Servicio = '$servicio' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$lsp', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
		list($registro_patronal,$empresa, $empresa_sucursal) = $conn->fetchRow($result);
		$conn->freeResult($result);

		if(!isset($empresa) || $empresa == '')
			$rfc = $empresa_sucursal;
		else
			$rfc = $empresa;

		$txt .= "<div>$rfc</div>";
		$txt .= "<div>$registro_patronal</div>";
	}

	echo $txt;
?>
