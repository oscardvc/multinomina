<?php //This page is called by a javascript function named view at recibo_de_vacaciones.js?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

	<head>
		<style type="text/css">
			body
			{
				color:#555;
			}
			.font
			{
				font:normal normal normal 12px Arial , sans-serif;
			}
			.title_font
			{
				font:bold normal normal 14px Arial , sans-serif;
			}
			.titles
			{
				font:bold normal normal 12px Arial , sans-serif;
				color:#fff;
				background:#bbb;
				text-align:center;
			}

			table
			{
				text-align:left;
				border:1px solid #555;
				border-radius:10px;
				-moz-border-radius:10px;
				-webkit-border-radius:10px;
				padding:10px;
				margin:10px;
			}
		</style>
		<script type="text/javascript" src="moneda.js"></script>
		<script type = "text/javascript">
			function _letras()
			{
				var _cantidad = document.getElementById('letra');
				_cantidad.innerHTML = covertirNumLetras(_cantidad.innerHTML);
			}
		</script>
	</head>
	<body class = "font" onload = "_letras()">
		<?php
			include_once('connection.php');
			include_once('finiquito.php');

			if(!isset($_SESSION))
				session_start();

			$recibo = new Finiquito();
			$conn = new Connection();
			$finiquitador = $_GET['finiquitador'];
			$lugar = $_GET['lugar'];
			$months = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
			$recibo->set('id',$_GET['id']);
			$recibo->setFromDb();
			$result = $conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '" . $recibo->get('Trabajador') . "' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($nombre_trabajador) = $conn->fetchRow($result);

			if($finiquitador == 'Empresa administradora')
			{
				$result = $conn->query("SELECT Servicio_Registro_patronal.Registro_patronal, Empresa.Nombre, Empresa.RFC, Empresa.Domicilio_fiscal, Registro_patronal.Sucursal FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero LEFT JOIN Empresa ON (Registro_patronal.Empresa = Empresa.RFC OR Registro_patronal.Empresa_sucursal = Empresa.RFC) WHERE Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Servicio = '". $recibo->get('Servicio') . "' AND DATEDIFF('" . $recibo->get('Fecha') . "', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
				list($registro,$nombre_administradora, $administradora, $c_address, $sucursal) = $conn->fetchRow($result);

				if(isset($sucursal) && $sucursal != '')
				{
					$result = $conn->query("SELECT Domicilio FROM Sucursal WHERE Nombre = '$sucursal' AND Empresa = '$administradora' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($c_address) = $conn->fetchRow($result);
				}

			}
			else
			{
				$result = $conn->query("SELECT Empresa.Nombre, Empresa.Domicilio_fiscal FROM Servicio_Empresa LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC WHERE Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.id = '" . $recibo->get('Servicio') . "' AND DATEDIFF('" . $recibo->get('Fecha') . "', Servicio_Empresa.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC LIMIT 1");
				list($empresa, $c_address) = $conn->fetchRow($result);
				$result = $conn->query("SELECT Sucursal.Domicilio FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Empresa.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador_Sucursal.Trabajador = '" . $recibo->get('Trabajador') . "' AND Trabajador_Sucursal.Servicio = '" . $recibo->get('Servicio') . "' AND DATEDIFF('" . $recibo->get('Fecha') . "', Trabajador_Sucursal.Fecha_de_ingreso) >= 0 ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");

				if($conn->num_rows($result) > 0)
					list($c_address) = $conn->fetchRow($result);

			}
			echo ($finiquitador == 'Empresa administradora' ? "<img src = 'images/logo_blanco.jpg'/>" : "");
			echo "<br/><br/><br/><br/><br/><span class = 'title_font' style = 'display:block; text-align:center;'>RECIBO DE FINIQUITO</span>";
			echo "<br/><br/><br/><br/><br/><span class = 'font' style = 'display:block; text-align:right;'>Bueno por $" . number_format($recibo->get('Saldo'),2,'.',',') . "</span>";
			echo "<br/><br/><br/><br/><br/><span style = 'display:block; text-align:justify'>Recibí a mi entera satisfacción en " . strtolower($recibo->get('Forma_de_pago')) . " de la empresa " . ($finiquitador == 'Empresa administradora' ? $nombre_administradora : $empresa) . " y/o quien resulte ser dueño o responsable de la fuente de trabajo ubicada en $c_address, la cantidad de $" . number_format($recibo->get('Saldo'), 2, '.', ',') . " <span id = 'letra'>" . $recibo->get('Saldo') . "</span> por concepto de finiquito por los servicios personales subordinados brindados a la empresa.<br/><br/><br/>Manifiesto que no se me adeuda cantidad alguna por concepto de aguinaldo, salarios devengados, vacaciones, prima vacacional, pago de los séptimos dias, horas extras, dias festivos y de descanso obligatorio, ni por ningún otro concepto nacido de la ley o de mi contrato de trabajo, motivo por el cual la libero de toda responsabilidad, otorgándole el mas amplio finiquito de obligaciones que en derecho proceda y manifiesto que no me reservo acción ni derecho alguno que ejercer en contra de " . ($finiquitador == 'Empresa administradora' ? $nombre_administradora : $empresa) . " ni contra alguna persona física o moral relacionada con la empresa y/o quien resulte ser dueño o responsable de la fuente de trabajo ubicada en $c_address pues reconozco que fué mi único patrón durante el periodo que colaboré en la misma.<br/><br/><br/>Así mismo manifiesto que durante el tiempo que duró la relación laboral se me cubrieron todas las prestaciones señaladas en la ley federal del trabajo a que tengo derecho según el contrato celebrado, con base en el salario que percibía y que se especifica en el mismo.<br/><br/><br/>Manifiesto también que durante el tiempo que duró la relación laboral no trabajé horas extras<br/><br/><br/>";
			$result = $conn->query("SELECT CURP From Trabajador WHERE RFC = '" . $recibo->get("Trabajador") . "' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($curp) = $conn->fetchRow($result);
			$data = explode('.',$recibo->get('Anos_de_antiguedad'));
			$years = $data[0];
			$days = $data[1] / 100;
			$days = floor($days * 365);
			echo "<table style = 'float:left'>
					<tr class = 'titles'><td colspan = 2>Datos informativos</td></tr>
					<tr><td>Trabajador</td><td>$nombre_trabajador</td></tr>
					<tr><td>CURP</td><td>$curp</td></tr>
					<tr><td>Salario diario</td><td>$" . $recibo->get('Salario_diario') . "</td></tr>
					<tr><td>Fecha de ingreso</td><td>" . $recibo->get('Fecha_de_ingreso') . "</td></tr>
					<tr><td>Antigüedad</td><td>" . $years . ($days != 0 ? " Año(s) $days Día(s)" : " Año(s)") . "</td></tr>";
					$anos = explode(',',$recibo->get('Anos'));
					$dias_de_vacaciones = explode(',',$recibo->get('Dias_de_vacaciones'));
					$len = count($dias_de_vacaciones);

					for($i=0; $i<$len; $i++)
						echo "<tr><td>Dias de vacaciones ({$anos[$i]})</td><td>{$dias_de_vacaciones[$i]}</td></tr>";

					echo "<tr><td>Dias de aguinaldo</td><td>" . $recibo->get('Dias_de_aguinaldo') . "</td></tr>";

					if($recibo->get('Pagar_prima_de_antiguedad') == 'true')
						echo "<tr><td>Dias de prima de antiguedad</td><td>" . $recibo->get('Dias_de_prima_de_antiguedad') . "</td></tr>";

					echo "<tr><td>Fecha de pago</td><td>" . $recibo->get('Fecha') . "</td></tr>";
			echo "</table>";

			echo "<table style = 'float:right'>
					<tr class = 'titles'><td colspan = 2>Percepciones</td><td colspan = 2>Deducciones</td></tr>";
					$anos = explode(',',$recibo->get('Anos'));
					$vacaciones = explode(',',$recibo->get('Vacaciones'));
					$prima_vacacional = explode(',',$recibo->get('Prima_vacacional'));
					$len = count($anos);

					for($i=0; $i<$len; $i++)
					{
						echo "<tr><td>Vacaciones ({$anos[$i]})</td><td style = 'text-align:right'>" . ($i == 0 ? '$' : '') . number_format($vacaciones[$i], 2, '.', ',') . "</td>" . ($i==0 ? ("<td>ISR</td><td style = 'text-align:right'>$" . number_format($recibo->get('ISR'), 2, '.', ',') . "</td>") : "") . "</tr>";
						echo "<tr><td>Prima vacacional ({$anos[$i]})</td><td style = 'text-align:right'>" . number_format($prima_vacacional[$i], 2, '.', ',') . "</td></tr>";
					}

					echo "<tr><td>Aguinaldo</td><td style = 'text-align:right'>" . number_format($recibo->get('Aguinaldo'), 2, '.', ',') . "</td></tr>";

				if($recibo->get('Prima_de_antiguedad') > 0)
					echo "<tr><td>Prima de antigüedad</td><td style = 'text-align:right'>" . number_format($recibo->get('Prima_de_antiguedad'), 2, '.', ',') . "</td><td></td><td></td></tr>";

				if($recibo->get('Gratificacion') > 0)
					echo "<tr><td>Gratificación</td><td style = 'text-align:right'>" . number_format($recibo->get('Gratificacion'), 2, '.', ',') . "</td><td></td><td></td></tr>";

				echo "<tr><td>Total de percepciones</td><td style = 'border-top:1px solid #555; text-align:right'>$" . number_format($recibo->get('Total_de_percepciones'), 2, '.', ',') . "</td><td>Total de deducciones</td><td style = 'border-top:1px solid #555; text-align:right'>$" . number_format($recibo->get('ISR'), 2, '.', ',') . "</td></tr>";
				echo "<tr><td></td><td></td><td>Total a percibir</td><td style = 'text-align:right'>$" . number_format($recibo->get('Saldo'), 2, '.', ',') . "</td></tr>
				</table>";
			$n = substr($recibo->get("Fecha"),5,2);
			echo "<span style = 'display:block; margin:300px 150px 0px 150px; text-align:center;'>$lugar" . " A " . substr($recibo->get("Fecha"),8,2) . " DE " . $months[$n - 1] . " DEL " . substr($recibo->get("Fecha"),0,4) . "</span>";
			echo "<span style = 'display:block; margin:100px 250px 0px 250px; text-align:center; border-top:1px solid #555;'>$nombre_trabajador</span>";
		?>
	</body>
</html>
