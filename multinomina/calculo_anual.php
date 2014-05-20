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
				text-align:right;
			}
		 </style>
		<script type = "text/javascript" src = "concentrado.js"></script>
	</head>
	<body>
<?php
	include_once('connection.php');
	date_default_timezone_set('America/Mexico_City');

	if(!isset($_SESSION))
		session_start();

	function get_empresa($servicio,$ano)
	{
		$conn = new Connection();
		$result = $conn->query("SELECT Empresa FROM Servicio_Empresa WHERE Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND YEAR(Fecha_de_asignacion) <= '$ano' ORDER BY Fecha_de_asignacion DESC LIMIT 1");
		list($empresa) = $conn->fetchRow($result);
		return $empresa;
	}

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

	function calculate_limite_inferior($base, $ano)
	{
		$conn = new Connection();
		$result = $conn->query("SELECT Limite_inferior FROM ISR_anual WHERE Limite_inferior <= $base AND (Limite_superior >= $base OR Limite_superior IS NULL) AND Ano = '$ano'");

		list($limite_inferior) = $conn->fetchRow($result);

		if(isset($limite_inferior))
			return $limite_inferior;

		else
			return 0;

	}

	function calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base,$ano)
	{
		$conn = new Connection();
		$result = $conn->query("SELECT Porcentaje FROM ISR_anual WHERE Limite_inferior <= $base AND (Limite_superior >= $base OR Limite_superior IS NULL) AND Ano = '$ano'");

		list($porcentaje_sobre_el_exedente_del_limite_inferior) = $conn->fetchRow($result);

		if(isset($porcentaje_sobre_el_exedente_del_limite_inferior))
			return $porcentaje_sobre_el_exedente_del_limite_inferior;

		else
			return 0;

	}

	function calculate_cuota_fija($base,$ano)
	{
		$conn = new Connection();
		$result = $conn->query("SELECT Cuota_fija FROM ISR_anual WHERE Limite_inferior <= $base AND (Limite_superior >= $base OR Limite_superior IS NULL) AND Ano = '$ano'");

		list($cuota_fija) = $conn->fetchRow($result);

		if(isset($cuota_fija))
			return $cuota_fija;

		else
			return 0;

	}

	function calculate_salario_minimo_trabajador($_trabajador, $servicio, $ano)
	{
		$conn = new Connection();
		//cheching if this "Empresa" has any "Sucursal"
		$empresa = get_empresa($servicio, $ano);
		$result = $conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");

		if($conn->num_rows($result) > 0)
		{
			$conn->freeResult($result);
			$result = $conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '$_trabajador' AND Trabajador_Sucursal.Servicio = '$servicio' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$ano-12-31', Trabajador_Sucursal.Fecha_de_ingreso) >= 0 ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");
		}
		else
		{
			$conn->freeResult($result);
			$result = $conn->query("SELECT Zona_geografica FROM Empresa WHERE RFC = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
		}

		list($zona) = $conn->fetchRow($result);
		$result = $conn->query("SELECT $zona FROM Salario_minimo WHERE Codigo = '1' AND Ano = '$ano' AND Cuenta = '{$_SESSION['cuenta']}'");
		list($salario_minimo) = $conn->fetchRow($result);

		if(isset($salario_minimo))
			return $salario_minimo;
		else
			return 0;

	}

	$conn = new Connection();
	$trabajador = $_GET['trabajador'];
	$ano = $_GET['ano'];
	$limite_inferior = $ano . '-01-01';
	$limite_superior = $ano . '-12-31';
	$servicio = $_GET['servicio'];
	$result = $conn->query("SELECT CURP, Nombre FROM Trabajador WHERE RFC = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
	list($curp,$nombre) = $conn->fetchRow($result);
	$conn->freeResult($result);
	$result = $conn->query("SELECT Tipo FROM Tipo WHERE Trabajador = '$trabajador' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND Tipo = 'Asalariado' AND DATEDIFF('$limite_superior',Fecha) >= 0 LIMIT 1");
	list($tipo) = $conn->fetchRow($result);
	$conn->freeResult($result);
	$result = $conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '$trabajador'AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}' AND Fecha_de_reingreso != '0000-00-00' AND DATEDIFF(Fecha_de_reingreso, '$limite_superior') <= 0 ORDER BY Fecha_de_reingreso DESC LIMIT 1");
	list($ingreso) = $conn->fetchRow($result);

	if(!isset($ingreso))
	{
		$result = $conn->query("SELECT Fecha_de_ingreso FROM Servicio_Trabajador WHERE Servicio = '$servicio' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
		list($ingreso) = $conn->fetchRow($result);
	}

	echo "<img src = 'images/logo_blanco.jpg'/><br/><br/>";
	echo "CALCULO ANUAL DEL ISR del año $ano<br/><br/>Trabajador: $nombre<br/>RFC: $trabajador<br/>CURP: $curp<br/>Última fecha de ingreso: $ingreso<br/><br/>";
	$txt = '';

	if(isset($tipo))
	{
		//Vacaciones
		$txt .= "<table><tr style = 'background:#555;color:#fff;text-align:center'><td colspan = 3>Vacaciones</td></tr><tr style = 'text-align:center;color:#fff;background:#3399cc' class = 'subtitles'><td>Concepto</td><td>Exento</td><td>Gravado</td></tr>";
		$result = $conn->query("SELECT Vacaciones, Prima_vacacional, Compensacion, ISR FROM Recibo_de_vacaciones WHERE DATEDIFF( Fecha, '$limite_inferior' ) >=0 AND DATEDIFF( Fecha, '$limite_superior' ) <=0 AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha ASC");
		$total_vacaciones = 0;
		$total_prima_vacacional = 0;
		$total_compensacion = 0;
		$total_isr = 0;
		$total_exento = 0;
		$total_gravado = 0;

		while(list($vacaciones, $prima_vacacional, $compensacion, $isr) = $conn->fetchRow($result))
		{
			$_vacaciones = total_prestacion($vacaciones);
			$total_vacaciones += $_vacaciones;
			$_prima_vacacional = total_prestacion($prima_vacacional);
			$total_prima_vacacional += $_prima_vacacional;
			$total_compensacion += $compensacion;
			$total_isr += $isr;
		}

		$txt .= "<tr><td>Vacaciones</td><td>" . number_format($total_vacaciones,2,'.',',') . "</td><td>0.00</td></tr>";
		$total_exento += $total_vacaciones;
		$salario_minimo = calculate_salario_minimo_trabajador($trabajador, $servicio, $ano);
		$prima_vacacional_exenta = $total_prima_vacacional > (15 * $salario_minimo) ? (15 * $salario_minimo) : $total_prima_vacacional;
		$total_exento += $prima_vacacional_exenta;
		$prima_vacacional_gravada = $total_prima_vacacional - $prima_vacacional_exenta;
		$total_gravado += $prima_vacacional_gravada;
		$txt .= "<tr><td>Prima vacacional</td><td>" . number_format($prima_vacacional_exenta,2,'.',',') . "</td><td>" . number_format($prima_vacacional_gravada,2,'.',',') . "</td></tr>";
		$txt .= "<tr><td>Compensación</td><td>0.00</td><td>" . number_format($total_compensacion,2,'.',',') . "</td>";
		$total_gravado += $total_compensacion;
		//Aguinaldo
		$txt .= "<tr style = 'background:#555;color:#fff;text-align:center'><td colspan = 3>Aguinaldo</td></tr><tr style = 'text-align:center;color:#fff;background:#3399cc' class = 'subtitles'><td>Concepto</td><td>Exento</td><td>Gravado</td></tr>";
		$result = $conn->query("SELECT aguinaldo_asalariados.Aguinaldo_ordinario, aguinaldo_asalariados.Gratificacion_adicional, aguinaldo_asalariados.ISR, ISRaguinaldo.Base FROM Aguinaldo LEFT JOIN aguinaldo_asalariados ON Aguinaldo.id = aguinaldo_asalariados.Aguinaldo LEFT JOIN ISRaguinaldo ON ISRaguinaldo.Aguinaldo = Aguinaldo.id WHERE DATEDIFF(Aguinaldo.Fecha_de_pago,'$limite_inferior') >= 0 AND DATEDIFF(Aguinaldo.Fecha_de_pago, '$limite_superior') <= 0 AND aguinaldo_asalariados.Trabajador = '$trabajador' AND ISRaguinaldo.Trabajador = '$trabajador' AND Aguinaldo.Servicio = '$servicio' AND Aguinaldo.Cuenta = '{$_SESSION['cuenta']}' AND aguinaldo_asalariados.Cuenta = '{$_SESSION['cuenta']}' AND ISRaguinaldo.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Aguinaldo.Fecha_de_pago ASC");
		$total_aguinaldo_ordinario = 0;
		$total_gratificacion_adicional = 0;
		$total_base = 0;

		while(list($aguinaldo_ordinario, $gratificacion_adicional, $isr, $base) = $conn->fetchRow($result))
		{
			$total_aguinaldo_ordinario += $aguinaldo_ordinario;
			$total_gratificacion_adicional += $gratificacion_adicional;
			$total_isr += $isr;
			$total_base += $base;
		}

		$aguinaldo_gravado = $total_base - $total_gratificacion_adicional;
		$total_gravado += $aguinaldo_gravado;
		$aguinaldo_exento = $total_aguinaldo_ordinario - $aguinaldo_gravado;
		$total_exento += $aguinaldo_exento;
		$txt .= "<tr><td>Aguinaldo ordinario</td><td>" . number_format($aguinaldo_exento,2,'.','') . "</td><td>". number_format($aguinaldo_gravado,2,'.','') . "</td></tr>";
		$conn->freeResult($result);

		//registros de nómina
		$txt .= "<tr style = 'background:#555;color:#fff;text-align:center'><td colspan = 3>Nómina</td></tr><tr style = 'text-align:center;color:#fff;background:#3399cc' class = 'subtitles'><td>Concepto</td><td>Exento</td><td>Gravado</td></tr>";
		$result = $conn->query("SELECT ISRasalariados.Sueldo, ISRasalariados.Horas_extra, ISRasalariados.Horas_extra_grabadas, ISRasalariados.Prima_dominical, ISRasalariados.Prima_dominical_grabada, ISRasalariados.Dias_de_descanso, ISRasalariados.Dias_de_descanso_grabados, ISRasalariados.Premios_de_puntualidad_y_asistencia, ISRasalariados.Bonos_de_productividad, ISRasalariados.Estimulos, ISRasalariados.Compensaciones, ISRasalariados.Despensa, ISRasalariados.Comida, nomina_asalariados.Alimentacion, nomina_asalariados.Habitacion, ISRasalariados.Prevision_social_grabada, ISRasalariados.ISR, ISRasalariados.Subsidio, ISRasalariados.Subsidio_al_empleo FROM (Nomina LEFT JOIN ISRasalariados ON Nomina.id = ISRasalariados.Nomina) LEFT JOIN nomina_asalariados ON Nomina.id = nomina_asalariados.Nomina WHERE ((DATEDIFF(Nomina.Limite_inferior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$limite_superior') <= 0) OR (DATEDIFF(Nomina.Limite_superior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$limite_superior') <= 0)) AND ISRasalariados.Trabajador = '$trabajador' AND nomina_asalariados.Trabajador = '$trabajador' AND Nomina.Servicio = '$servicio' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND ISRasalariados.Cuenta = '{$_SESSION['cuenta']}' AND nomina_asalariados.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Nomina.Limite_inferior_del_periodo ASC");
		$total_sueldo = 0;
		$total_horas_extra = 0;
		$total_horas_extra_gravadas = 0;
		$total_prima_dominical = 0;
		$total_prima_dominical_gravada = 0;
		$total_dias_de_descanso = 0;
		$total_dias_de_descanso_gravados = 0;
		$total_premios_de_puntualidad_y_asistencia = 0;
		$total_bonos_de_productividad = 0;
		$total_estimulos = 0;
		$total_compensaciones = 0;
		$total_despensa = 0;
		$total_comida = 0;
		$total_prevision_social = 0;
		$total_prevision_social_gravada = 0;
		$total_isr = 0;
		$total_subsidio = 0;
		$total_subsidio_pagado = 0;
		$n = $conn->num_rows($result);

		while(list($sueldo, $horas_extra, $horas_extra_gravadas, $prima_dominical, $prima_dominical_gravada, $dias_de_descanso, $dias_de_descanso_gravados, $premios_de_puntualidad_y_asistencia, $bonos_de_productividad, $estimulos, $compensaciones, $despensa, $comida, $alimentacion, $habitacion, $prevision_social_gravada, $isr, $subsidio, $subsidio_pagado) = $conn->fetchRow($result))
		{
			$total_sueldo += $sueldo;
			$total_horas_extra += $horas_extra;
			$total_horas_extra_gravadas += $horas_extra_gravadas;
			$total_prima_dominical += $prima_dominical;
			$total_prima_dominical_gravada += $prima_dominical_gravada;
			$total_dias_de_descanso += $dias_de_descanso;
			$total_dias_de_descanso_gravados += $dias_de_descanso_gravados;
			$total_premios_de_puntualidad_y_asistencia += $premios_de_puntualidad_y_asistencia;
			$total_bonos_de_productividad += $bonos_de_productividad;
			$total_estimulos += $estimulos;
			$total_compensaciones += $compensaciones;
			$total_despensa += $despensa;
			$total_comida += $comida;
			$total_prevision_social += $alimentacion + $habitacion;
			$total_prevision_social_gravada += $prevision_social_gravada;
			$total_isr += $isr;
			$total_subsidio += $subsidio;
			$total_subsidio_pagado += $subsidio_pagado;
		}

		$txt .= "<tr><td style = 'text-align:left'>Sueldo</td><td>0.00</td><td>" . number_format($total_sueldo,2,'.',',') . "</td></tr>";
		$total_gravado += $total_sueldo;
		$txt .= "<tr><td style = 'text-align:left'>Horas extra</td><td>" . number_format($total_horas_extra - $total_horas_extra_gravadas,2,'.',',') . "</td><td>" . number_format($total_horas_extra_gravadas,2,'.',',') . "</td></tr>";
		$total_exento += $total_horas_extra - $total_horas_extra_gravadas;
		$total_gravado += $total_horas_extra_gravadas;
		$txt .= "<tr><td style = 'text-align:left'>Prima dominical</td><td>" . number_format($total_prima_dominical - $total_prima_dominical_gravada,2,'.',',') . "</td><td>" . number_format($total_prima_dominical_gravada,2,'.',',') . "</td></tr>";
		$total_exento += $total_prima_dominical - $total_prima_dominical_gravada;
		$total_gravado += $total_prima_dominical_gravada;
		$txt .=  "<tr><td style = 'text-align:left'>Días de descanso</td><td>" . number_format($total_dias_de_descanso - $total_dias_de_descanso_gravados,2,'.',',') . "</td><td>" . number_format($total_dias_de_descanso_gravados,2,'.',',') . "</td></tr>";
		$total_exento += $total_dias_de_descanso - $total_dias_de_descanso_gravados;
		$total_gravado += $total_dias_de_descanso_gravados;
		$txt .= "<tr><td style = 'text-align:left'>Premios de puntualidad y asistencia</td><td>0.00</td><td>" . number_format($total_premios_de_puntualidad_y_asistencia,2,'.',',') . "</td></tr>";
		$total_gravado += $total_dias_de_descanso;
		$txt .= "<tr><td style = 'text-align:left'>Bonos de productividad</td><td>0.00</td><td>" . number_format($total_bonos_de_productividad,2,'.',',') . "</td></tr>";
		$total_gravado += $total_bonos_de_productividad;
		$txt .= "<tr><td style = 'text-align:left'>Estímulos</td><td>0.00</td><td>" . number_format($total_estimulos,2,'.',',') . "</td></tr>";
		$total_gravado += $total_estimulos;
		$txt .= "<tr><td style = 'text-align:left'>Compensaciones</td><td>0.00</td><td>" . number_format($total_compensaciones,2,'.',',') . "</td></tr>";
		$total_gravado += $total_compensaciones;
		$txt .= "<tr><td style = 'text-align:left'>Despensa</td><td>0.00</td><td>" . number_format($total_despensa,2,'.',',') . "</td></tr>";
		$total_gravado += $total_despensa;
		$txt .= "<tr><td style = 'text-align:left'>Comida</td><td>0.00</td><td>". number_format($total_comida,2,'.',',') . "</td></tr>";
		$total_gravado += $total_comida;
		$txt .=  "<tr><td style = 'text-align:left'>Previsión social</td><td>" . number_format($total_prevision_social - $total_prevision_social_gravada,2,'.',',') . "</td><td>" . number_format($total_prevision_social_gravada,2,'.',',') . "</td></tr>";
		$total_exento += $total_prevision_social - $total_prevision_social_gravada;
		$total_gravado += $total_prevision_social_gravada;
		$txt .=  "<tr><td style = 'text-align:left'>Total</td><td style = 'border-top:1px solid #555'>" . number_format($total_exento,2,'.',',') . "</td><td style = 'border-top:1px solid #555'>" . number_format($total_gravado,2,'.',',') . "</td></tr>";
		$txt .= "</table>";
		echo $txt;
		echo "<br/>Base gravable: " . number_format($total_gravado,2,'.',',');
		echo "<br/>Subsidio correspondiente: " . number_format($total_subsidio,2,'.',',');
		echo "<br/>Subsidio pagado: " . number_format($total_subsidio_pagado,2,'.',',');
		echo "<br/>Impuesto retenido: " . number_format($total_isr,2,'.',',');
		echo "<br/><br/>::Cálculo::";
		//Límite inferior
		$limite_inferior = calculate_limite_inferior($total_gravado, $ano);
		echo "<br/><br/>Límite inferior: " . number_format($limite_inferior,2,'.',',');
		//Exedente del límite inferior
		$exedente_del_limite_inferior = $total_gravado - $limite_inferior;
		echo "<br/>Exedente del límite inferior: " . number_format($exedente_del_limite_inferior,2,'.',',');;
		//Porcentaje sobre el exedente del límite inferior
		$porcentaje_sobre_el_exedente_del_limite_inferior = calculate_porcentaje_sobre_el_exedente_del_limite_inferior($total_gravado, $ano);
		echo "<br/>Porcentaje sobre el exedente del límite inferior: $porcentaje_sobre_el_exedente_del_limite_inferior";
		//Impuesto marginal
		$impuesto_marginal = round($exedente_del_limite_inferior * $porcentaje_sobre_el_exedente_del_limite_inferior,2);
		echo "<br/>Impuesto marginal: " . number_format($impuesto_marginal,2,'.',',');
		//Cuota fija
		$cuota_fija = calculate_cuota_fija($total_gravado, $ano);
		echo "<br/>Cuota fija: " . number_format($cuota_fija,2,'.',',');
		//Impuesto determinado
		$impuesto_determinado = $impuesto_marginal + $cuota_fija;
		echo "<br/>Impuesto determinado: " . number_format($impuesto_determinado,2,'.',',');

		if($impuesto_determinado > $total_subsidio)
		{
			echo "<br/><br/> " . number_format($impuesto_determinado,2,'.',',') . " Impuesto determinado";
			echo "<br/>-";
			echo "<br/> " . number_format($total_subsidio,2,'.',',') . " Subsidio correspondiente";
			$isr_a_cargo_anual = $impuesto_determinado - $total_subsidio;
			echo "<br/> <span style = 'border-top:1px solid #555'>" . number_format($isr_a_cargo_anual,2,'.',',') . "</span> ISR a cargo anual";
			echo "<br/>-";
			echo "<br/> " . number_format($total_isr,2,'.',',') . " Impuesto retenido";
			$isr_a_cargo = $isr_a_cargo_anual - $total_isr;

			if($isr_a_cargo > 0)
				echo "<br/> <span style = 'border-top:1px solid #555'>" . number_format($isr_a_cargo,2,'.',',') . "</span> ISR a cargo";
			else
				echo "<br/> <span style = 'border-top:1px solid #555'>" . number_format($isr_a_cargo * (-1),2,'.',',') . " </span> Subsidio a favor";

		}
		else
		{
			echo "<br/><br/>" . number_format($total_subsidio,2,'.',',') . " Subsidio correspondiente";
			echo "<br/>-";
			echo "<br/> " . number_format($impuesto_determinado,2,'.',',') . " Impuesto determinado";
			$subsidio_a_favor_anual = $total_subsidio - $impuesto_determinado;
			echo "<br/> <span style = 'border-top:1px solid #555'>" . number_format($subsidio_a_favor_anual,2,'.',',') . "</span> Subsidio a favor anual";
			echo "<br/>-";
			echo "<br/> " . number_format($total_isr,2,'.',',') . " Impuesto retenido";
			$subsidio_a_favor = $subsidio_a_favor_anual - $total_isr;

			if($subsidio_a_favor > 0)
				echo "<br/> <span style = 'border-top:1px solid #555'>" . number_format($subsidio_a_favor,2,'.',',') . "</span> Subsidio a favor";
			else
				echo "<br/> <span style = 'border-top:1px solid #555'>" . number_format($subsidio_a_favor * (-1),2,'.',',') . "</span> ISR a cargo";
		}

	}
	else
	{
		//registros de nómina
		$txt .= "<table><tr style = 'text-align:center;color:#fff;background:#3399cc'><td>Concepto</td><td>Exento</td><td>Gravado</td></tr>";
		$result = $conn->query("SELECT nomina_asimilables.Honorarios_asimilados, ISRasimilables.ISR FROM (Nomina LEFT JOIN ISRasimilables ON Nomina.id = ISRasimilables.Nomina) LEFT JOIN nomina_asimilables ON Nomina.id = nomina_asimilables.Nomina WHERE ((DATEDIFF(Nomina.Limite_inferior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$limite_superior') <= 0) OR (DATEDIFF(Nomina.Limite_superior_del_periodo,'$limite_inferior') >= 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$limite_superior') <= 0)) AND ISRasimilables.Trabajador = '$trabajador' AND nomina_asimilables.Trabajador = '$trabajador' AND Nomina.Servicio = '$servicio' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND ISRasimilables.Cuenta = '{$_SESSION['cuenta']}' AND nomina_asimilables.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Nomina.Limite_inferior_del_periodo ASC");
		$total_numero_de_dias_laborados = 0;
		$total_honorarios = 0;
		$total_isr = 0;
		$i = 1;

		while(list($honorarios, $isr) = $conn->fetchRow($result))
		{
			$total_honorarios += $honorarios;
			$total_isr += $isr;
		}

		$txt .= "<tr><td>Honorarios asimilados</td><td>0.00</td><td>" . number_format($total_honorarios,2,'.',',') . "</td></tr>";
		$conn->freeResult($result);
		$txt .= "</table>";
		echo $txt;
		//Límite inferior
		$limite_inferior = calculate_limite_inferior($total_honorarios, $ano);
		echo "<br/>Límite inferior: " . number_format($limite_inferior,2,'.',',');
		//Exedente del límite inferior
		$exedente_del_limite_inferior = $total_honorarios - $limite_inferior;
		echo "<br/>Exedente del límite inferior: " . number_format($exedente_del_limite_inferior,2,'.',',');;
		//Porcentaje sobre el exedente del límite inferior
		$porcentaje_sobre_el_exedente_del_limite_inferior = calculate_porcentaje_sobre_el_exedente_del_limite_inferior($total_honorarios, $ano);
		echo "<br/>Porcentaje sobre el exedente del límite inferior: $porcentaje_sobre_el_exedente_del_limite_inferior";
		//Impuesto marginal
		$impuesto_marginal = round($exedente_del_limite_inferior * $porcentaje_sobre_el_exedente_del_limite_inferior,2);
		echo "<br/>Impuesto marginal: " . number_format($impuesto_marginal,2,'.',',');
		//Cuota fija
		$cuota_fija = calculate_cuota_fija($total_honorarios, $ano);
		echo "<br/>Cuota fija: $cuota_fija";
		//Impuesto determinado
		$impuesto_determinado = $impuesto_marginal + $cuota_fija;
		echo "<br/>Impuesto determinado: $impuesto_determinado";
		//Subsidio
		echo "<br/>Subsidio correspondiente: 0.00";
		echo "<br/>Subsidio pagado: 0.00";
		echo "<br/>Impuesto retenido: " . number_format($total_isr,2,'.',',');
		$isr_a_cargo_anual = $impuesto_determinado;
		echo "<br/><br/> " . number_format($isr_a_cargo_anual,2,'.',',') . " ISR a cargo anual";
		echo "<br/>-";
		echo "<br/> " . number_format($total_isr,2,'.',',') . " ISR retenido";
		$isr_a_cargo = $isr_a_cargo_anual - $total_isr;

		if($isr_a_cargo > 0)
			echo "<br/> <span style = 'border-top:1px solid #555'>" . number_format($isr_a_cargo,2,'.',',') . " ISR a cargo";
		else
			echo "<br/> <span style = 'border-top:1px solid #555'>" . number_format($isr_a_cargo * (-1),2,'.',',') . " Subsidio a favor";

	}

?>
	</body>
</html>
