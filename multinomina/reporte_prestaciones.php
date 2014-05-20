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
		<script type = "text/javascript" src = "moneda.js"></script>
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

	date_default_timezone_set('America/Mexico_City');
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$registro = $_GET['Registro_patronal'];
	$limite_inferior = $_GET['limite_inferior'];
	$limite_superior = $_GET['limite_superior'];
	$len = count($registro);
	echo "<img src = 'images/logo_blanco.jpg'/><br/><br/>";

	for($i=0; $i<$len; $i++)
		echo "Registro patronal: {$registro[$i]}<br/>";

	echo "Periodo: $limite_inferior / $limite_superior<br/><br/>";
	echo "Ver totales<input type = 'button' onclick = 'show_totals(this)' value = '⌖' style = 'background:#3399cc; border:none; borderRadius:10px; -moz-border-radius:10px; -webkit-border-radius:10px;color:#fff; text-align:center; cursor:pointer'><br/><br/>";
	$txt = "<table><tr style = 'text-align:center;color:#fff;background:#555' class = 'titles'><td colspan = '2' onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Empresa</td><td colspan = '8' onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Vacaciones y prima vacacional</td><td colspan = '6' onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Aguinaldo</td><td colspan = '1' onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Prima de antigüedad</td><td colspan = '7' onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Finiquito</td><td colspan = '3' onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Total</td></tr><tr style = 'text-align:center;color:#fff;background:#3399cc' class = 'subtitles'><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Nombre</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>RFC</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Provisión de vacaciones</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Provición de prima vacacional</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Vacaciones</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Prima vacacional</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Compensación</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>ISR</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Total pagado</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Diferencia</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Provisión</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Aguinaldo ordinario</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Gratificación adicional</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>ISR</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Total pagado</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Diferencia</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Provisión</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Vacaciones</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Prima vacacional</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Aguinaldo</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Prima de antigüedad</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Gratificación</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>ISR</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Total pagado</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Provisión</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Pago</td><td onmouseover = '_marck(this)' onmouseout = '_unmarck(this)' onclick = '_del(this)' style = 'cursor:pointer'>Diferencia</td></tr>";
	$register_clause = "Servicio_Registro_patronal.Registro_patronal = '{$registro[0]}'";

	for($i=1; $i<$len; $i++)
		$register_clause .= " OR Servicio_Registro_patronal.Registro_patronal = '{$registro[$i]}'";

	$total_provision_vacaciones = 0;
	$total_provision_prima_vacacional = 0;
	$total_vacaciones = 0;
	$total_prima_vacacional = 0;
	$total_compensacion_vacaciones = 0;
	$total_isr_vacaciones = 0;
	$total_saldo_vacaciones = 0;
	$total_diferencia_vacaciones = 0;
	$total_provision_aguinaldo = 0;
	$total_aguinaldo_ordinario = 0;
	$total_gratificacion_adicional = 0;
	$total_isr_aguinaldo = 0;
	$total_saldo_aguinaldo = 0;
	$total_diferencia_aguinaldo = 0;
	$total_provision_prima_de_antiguedad = 0;
	$total_vacaciones_finiquito = 0;
	$total_prima_vacacional_finiquito = 0;
	$total_aguinaldo_finiquito = 0;
	$total_prima_de_antiguedad_finiquito = 0;
	$total_gratificacion_finiquito = 0;
	$total_isr_finiquito = 0;
	$total_saldo_finiquito = 0;
	$total_provision = 0;
	$total_pago = 0;
	$total_diferencia = 0;
	$result = $conn->query("SELECT Nombre, RFC FROM Empresa WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY Nombre ASC");

	while(list($nombre,$rfc) = $conn->fetchRow($result))
	{
		$provision_vacaciones_ = 0;
		$provision_prima_vacacional_ = 0;
		$vacaciones_ = 0;
		$prima_vacacional_ = 0;
		$compensacion_vacaciones_ = 0;
		$isr_vacaciones_ = 0;
		$saldo_vacaciones_ = 0;
		$diferencia_vacaciones_ = 0;
		$provision_aguinaldo_ = 0;
		$aguinaldo_ordinario_ = 0;
		$gratificacion_adicional_ = 0;
		$isr_aguinaldo_ = 0;
		$saldo_aguinaldo_ = 0;
		$diferencia_aguinaldo_ = 0;
		$provision_prima_de_antiguedad_ = 0;
		$vacaciones_finiquito_ = 0;
		$prima_vacacional_finiquito_ = 0;
		$aguinaldo_finiquito_ = 0;
		$prima_de_antiguedad_finiquito_ = 0;
		$gratificacion_finiquito_ = 0;
		$isr_finiquito_ = 0;
		$saldo_finiquito_ = 0;
		$provision_ = 0;
		$pago_ = 0;
		$diferencia_ = 0;
		$result1 = $conn->query("SELECT Servicio.id, Servicio_Registro_patronal.Registro_patronal, Servicio_Empresa.Fecha_de_asignacion, Servicio_Registro_patronal.Fecha_de_asignacion FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Servicio_Registro_patronal ON Servicio.id = Servicio_Registro_patronal.Servicio WHERE DATEDIFF('$limite_superior', Servicio_Empresa.Fecha_de_asignacion) >= 0 AND DATEDIFF('$limite_superior', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 AND Servicio_Empresa.Empresa = '$rfc' AND ($register_clause) AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}'");

		while(list($servicio, $registro, $fecha_de_asignacion_empresa, $fecha_de_asignacion_registro) = $conn->fetchRow($result1))
		{
			//setting lower limit
			if($limite_inferior < $fecha_de_asignacion_empresa)
			{

				if($fecha_de_asignacion_empresa < $fecha_de_asignacion_registro)
					$li = $fecha_de_asignacion_registro;
				else
					$li = $fecha_de_asignacion_empresa;

			}
			elseif($limite_inferior < $fecha_de_asignacion_registro)
				$li = $fecha_de_asignacion_registro;
			else
				$li = $limite_inferior;

			//setting upper limit
			$result2 = $conn->query("SELECT Fecha_de_asignacion FROM Servicio_Empresa WHERE Servicio = '$servicio' AND Empresa != '$rfc' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_asignacion, '$fecha_de_asignacion_empresa') >= 0 AND DATEDIFF('$limite_superior', Fecha_de_asignacion) >= 0 ORDER BY Fecha_de_asignacion ASC LIMIT 1");
			list($ls_empresa) = $conn->fetchRow($result2);
			$conn->freeResult($result2);
			$result2 = $conn->query("SELECT Fecha_de_asignacion FROM Servicio_Registro_patronal WHERE Servicio = '$servicio' AND Registro_patronal != '$registro' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_asignacion,'$fecha_de_asignacion_registro') >= 0 AND DATEDIFF('$limite_superior', Fecha_de_asignacion) >= 0 ORDER BY Fecha_de_asignacion ASC LIMIT 1");
			list($ls_registro) = $conn->fetchRow($result2);
			$conn->freeResult($result2);

			if(isset($ls_empresa) && $limite_superior > $ls_empresa)
			{

				if(isset($ls_registro) && $ls_empresa > $ls_registro)
					$ls = $ls_registro;
				else
					$ls = $ls_empresa;

			}
			elseif(isset($ls_registro) && $limite_superior > $ls_registro)
				$ls = $ls_registro;
			else
				$ls = $limite_superior;

			$result2 = $conn->query("SELECT DISTINCT Servicio_Trabajador.Trabajador FROM Servicio_Trabajador LEFT JOIN Servicio_Empresa ON Servicio_Trabajador.Servicio = Servicio_Empresa.Servicio LEFT JOIN Servicio_Registro_patronal ON Servicio_Trabajador.Servicio = Servicio_Registro_patronal.Servicio WHERE DATEDIFF('$ls', Servicio_Empresa.Fecha_de_asignacion) >= 0 AND DATEDIFF('$ls', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 AND Servicio_Trabajador.Servicio = '$servicio' AND Servicio_Empresa.Empresa = '$rfc' AND ($register_clause) AND Servicio_Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}'");

			while(list($trabajador) = $conn->fetchRow($result2))
			{
				//Vacaciones y prima vacacional
				$result3 = $conn->query("SELECT Recibo_de_vacaciones.Vacaciones, Recibo_de_vacaciones.Prima_vacacional, Recibo_de_vacaciones.Compensacion, Recibo_de_vacaciones.ISR, Recibo_de_vacaciones.Saldo FROM Servicio LEFT JOIN Recibo_de_vacaciones ON Servicio.id = Recibo_de_vacaciones.Servicio WHERE DATEDIFF(Recibo_de_vacaciones.Fecha, '$li') >=0 AND DATEDIFF(Recibo_de_vacaciones.Fecha, '$ls') <=0 AND Recibo_de_vacaciones.Trabajador = '$trabajador' AND Servicio.id = '$servicio' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Recibo_de_vacaciones.Cuenta = '{$_SESSION['cuenta']}'");

				while(list($vacaciones, $prima_vacacional, $compensacion, $isr, $saldo) = $conn->fetchRow($result3))
				{
					$_vacaciones = total_prestacion($vacaciones);
					$vacaciones_ += $_vacaciones;
					$_prima_vacacional = total_prestacion($prima_vacacional);
					$prima_vacacional_ += $_prima_vacacional;
					$compensacion_vacaciones_ += $compensacion;
					$isr_vacaciones_ += $isr;
					$saldo_vacaciones_ += $saldo;
					$pago_ += $saldo;
				}

				$conn->freeResult($result3);

				//Aguinaldo
				$result3 = $conn->query("SELECT aguinaldo_asalariados.Aguinaldo_ordinario, aguinaldo_asalariados.Gratificacion_adicional, aguinaldo_asalariados.ISR, aguinaldo_asalariados.Saldo FROM Servicio LEFT JOIN Aguinaldo ON Servicio.id = Aguinaldo.Servicio LEFT JOIN aguinaldo_asalariados ON Aguinaldo.id = aguinaldo_asalariados.Aguinaldo WHERE DATEDIFF(Aguinaldo.Fecha_de_pago,'$li') >= 0 AND DATEDIFF(Aguinaldo.Fecha_de_pago, '$ls') <= 0 AND aguinaldo_asalariados.Trabajador = '$trabajador' AND Aguinaldo.Servicio = '$servicio' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Aguinaldo.Cuenta = '{$_SESSION['cuenta']}' AND aguinaldo_asalariados.Cuenta = '{$_SESSION['cuenta']}'");

				while(list($aguinaldo_ordinario, $gratificacion_adicional, $isr, $saldo) = $conn->fetchRow($result3))
				{
					$aguinaldo_ordinario_ += $aguinaldo_ordinario;
					$gratificacion_adicional_ += $gratificacion_adicional;
					$isr_aguinaldo_ += $isr;
					$saldo_aguinaldo_ += $saldo;
					$pago_ += $saldo;
				}

				$conn->freeResult($result3);
				//Finiquito
				$result3 = $conn->query("SELECT Finiquito.Vacaciones, Finiquito.Prima_vacacional, Finiquito.Aguinaldo, Finiquito.Prima_de_antiguedad, Finiquito.Gratificacion, Finiquito.ISR, Finiquito.Saldo FROM Servicio LEFT JOIN Finiquito ON Servicio.id = Finiquito.Servicio WHERE DATEDIFF(Finiquito.Fecha,'$li') >= 0 AND DATEDIFF(Finiquito.Fecha, '$ls') <= 0 AND Finiquito.Trabajador = '$trabajador' AND Finiquito.Servicio = '$servicio' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Finiquito.Cuenta = '{$_SESSION['cuenta']}'");

				while(list($vacaciones, $prima_vacacional, $aguinaldo, $prima_de_antiguedad, $gratificacion, $isr, $saldo) = $conn->fetchRow($result3))
				{
					$vacaciones_finiquito_ += $vacaciones;
					$prima_vacacional_finiquito_ += $prima_vacacional;
					$aguinaldo_finiquito_ += $aguinaldo;
					$prima_de_antiguedad_finiquito_ += $prima_de_antiguedad;
					$gratificacion_finiquito_ += $gratificacion;
					$isr_finiquito_ += $isr;
					$saldo_finiquito_ += $saldo;
					$pago_ += $saldo;
				}

				$conn->freeResult($result3);
				//provisión de prestaciones
				$result3 = $conn->query("SELECT Nomina.id, prestaciones.Retencion_proporcional_de_vacaciones, prestaciones.Retencion_proporcional_de_prima_vacacional, prestaciones.Retencion_proporcional_de_aguinaldo, prestaciones.Retencion_proporcional_de_prima_de_antiguedad, prestaciones.Total_de_retenciones FROM Nomina LEFT JOIN prestaciones ON Nomina.id = prestaciones.Nomina WHERE DATEDIFF(Nomina.Fecha_de_pago, '$li') >= 0 AND DATEDIFF(Nomina.Fecha_de_pago, '$ls') <= 0 AND prestaciones.Trabajador = '$trabajador' AND Nomina.Servicio = '$servicio' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND prestaciones.Cuenta = '{$_SESSION['cuenta']}'");

				while(list($nomina, $provision_vacaciones, $provision_prima_vacacional, $provision_aguinaldo, $provision_prima_de_antiguedad, $provision_prestaciones) = $conn->fetchRow($result3))
				{
					$provision_vacaciones_ += $provision_vacaciones;
					$provision_prima_vacacional_ += $provision_prima_vacacional;
					$provision_aguinaldo_ += $provision_aguinaldo;
					$provision_prima_de_antiguedad_ += $provision_prima_de_antiguedad;
					$provision_ += $provision_prestaciones;
				}

				$diferencia_vacaciones_ = $provision_vacaciones_ + $provision_prima_vacacional_ - $saldo_vacaciones_;
				$diferencia_aguinaldo_ = $provision_aguinaldo_ - $saldo_aguinaldo_;
				$diferencia_ = $provision_ - $pago_;
				$conn->freeResult($result3);
			}

		}

		$total_provision_vacaciones += $provision_vacaciones_;
		$total_provision_prima_vacacional += $provision_prima_vacacional_;
		$total_vacaciones += $vacaciones_;
		$total_prima_vacacional += $prima_vacacional_;
		$total_compensacion_vacaciones += $compensacion_vacaciones_;
		$total_isr_vacaciones += $isr_vacaciones_;
		$total_saldo_vacaciones += $saldo_vacaciones_;
		$total_diferencia_vacaciones += $diferencia_vacaciones_;
		$total_provision_aguinaldo += $provision_aguinaldo_;
		$total_aguinaldo_ordinario += $aguinaldo_ordinario_;
		$total_gratificacion_adicional += $gratificacion_adicional_;
		$total_isr_aguinaldo += $isr_aguinaldo_;
		$total_saldo_aguinaldo += $saldo_aguinaldo_;
		$total_diferencia_aguinaldo += $diferencia_aguinaldo_;
		$total_provision_prima_de_antiguedad += $provision_prima_de_antiguedad_;
		$total_vacaciones_finiquito += $vacaciones_finiquito_;
		$total_prima_vacacional_finiquito += $prima_vacacional_finiquito_;
		$total_aguinaldo_finiquito += $aguinaldo_finiquito_;
		$total_prima_de_antiguedad_finiquito += $prima_de_antiguedad_finiquito_;
		$total_gratificacion_finiquito += $gratificacion_finiquito_;
		$total_isr_finiquito += $isr_finiquito_;
		$total_saldo_finiquito += $saldo_finiquito_;
		$total_provision += $provision_;
		$total_pago += $pago_;
		$total_diferencia += $diferencia_;

		if($provision_ > 0.00 || $pago_ > 0.00 || $diferencia_ > 0.00)
			$txt .= "<tr style = 'text-align:right; cursor:pointer;' onmouseover = '_mark_row(this)' onmouseout = '_unmark_row(this)' onclick = '_del_row(this)'><td style = 'text-align:center'>$nombre</td><td>$rfc</td><td>" . number_format($provision_vacaciones_,2,'.',',') . "</td><td>" . number_format($provision_prima_vacacional_,2,'.',',') . "</td><td>" . number_format($vacaciones_,2,'.',',') . "</td><td>" . number_format($prima_vacacional_,2,'.',',') . "</td><td>" . number_format($compensacion_vacaciones_,2,'.',',') . "</td><td>" . number_format($isr_vacaciones_,2,'.',',') . "</td><td>" . number_format($saldo_vacaciones_,2,'.',',') . "</td><td>" . number_format($diferencia_vacaciones_,2,'.',',') . "</td><td>" . number_format($provision_aguinaldo_,2,'.',',') . "</td><td>" . number_format($aguinaldo_ordinario_,2,'.',',') . "</td><td>" . number_format($gratificacion_adicional_,2,'.',',') . "</td><td>" . number_format($isr_aguinaldo_,2,'.',',') . "</td><td>" . number_format($saldo_aguinaldo_,2,'.',',') . "</td><td>" . number_format($diferencia_aguinaldo_,2,'.',',') . "</td><td>" . number_format($provision_prima_de_antiguedad_,2,'.',',') . "</td><td>" . number_format($vacaciones_finiquito_,2,'.',',') . "</td><td>" . number_format($prima_vacacional_finiquito_,2,'.',',') . "</td><td>" . number_format($aguinaldo_finiquito_,2,'.',',') . "</td><td>" . number_format($prima_de_antiguedad_finiquito_,2,'.',',') . "</td><td>" . number_format($gratificacion_finiquito_,2,'.',',') . "</td><td>" . number_format($isr_finiquito_,2,'.',',') . "</td><td>" . number_format($saldo_finiquito_,2,'.',',') . "</td><td>" . number_format($provision_,2,'.',',') . "</td><td>" . number_format($pago_,2,'.',',') . "</td><td>" . number_format($diferencia_,2,'.',',') . "</td></tr>";

	}

	$txt .= "<tr class = 'totals' style = 'text-align:right'><td style = 'background:#fff'></td><td style = 'background:#fff'>Total</td><td>" . number_format($total_provision_vacaciones,2,'.',',') . "</td><td>" . number_format($total_provision_prima_vacacional,2,'.',',') . "</td><td>" . number_format($total_vacaciones,2,'.',',') . "</td><td>" . number_format($total_prima_vacacional,2,'.',',') . "</td><td>" . number_format($total_compensacion_vacaciones,2,'.',',') . "</td><td>" . number_format($total_isr_vacaciones,2,'.',',') . "</td><td>" . number_format($total_saldo_vacaciones,2,'.',',') . "</td><td>" . number_format($total_diferencia_vacaciones,2,'.',',') . "</td><td>" . number_format($total_provision_aguinaldo,2,'.',',') . "</td><td>" . number_format($total_aguinaldo_ordinario,2,'.',',') . "</td><td>" . number_format($total_gratificacion_adicional,2,'.',',') . "</td><td>" . number_format($total_isr_aguinaldo,2,'.',',') . "</td><td>" . number_format($total_saldo_aguinaldo,2,'.',',') . "</td><td>" . number_format($total_diferencia_aguinaldo,2,'.',',') . "</td><td>" . number_format($total_provision_prima_de_antiguedad,2,'.',',') . "</td><td>" . number_format($total_vacaciones_finiquito,2,'.',',') . "</td><td>" . number_format($total_prima_vacacional_finiquito,2,'.',',') . "</td><td>" . number_format($total_aguinaldo_finiquito,2,'.',',') . "</td><td>" . number_format($total_prima_de_antiguedad_finiquito,2,'.',',') . "</td><td>" . number_format($total_gratificacion_finiquito,2,'.',',') . "</td><td>" . number_format($total_isr_finiquito,2,'.',',') . "</td><td>" . number_format($total_saldo_finiquito,2,'.',',') . "</td><td>" . number_format($total_provision,2,'.',',') . "</td><td>" . number_format($total_pago,2,'.',',') . "</td><td>" . number_format($total_diferencia,2,'.',',') . "</td></tr>";
	$txt .= "</table>";
	echo $txt;
?>
	</body>
</html>
