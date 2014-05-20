<?php //This page is called by a javascript function named view at recibo_de_vacaciones.js?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

	<head>
		<style type="text/css">
			body
			{
				color:#000;
			}
			.font
			{
				font:normal normal normal 15px Times New Roman, serif;
			}
			.title_font
			{
				font:bold normal normal 15px Times New Roman, serif;
			}
		</style>
	</head>
	<body class = "font">
		<?php
			include_once('connection.php');
			include_once('finiquito.php');

			if(!isset($_SESSION))
				session_start();

			$recibo = new Finiquito();
			$conn = new Connection();
			$finiquitador = $_GET['finiquitador'];
			$months = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
			$recibo->set('id',$_GET['id']);
			$recibo->setFromDb();
			$result = $conn->query("SELECT Trabajador.Nombre, Trabajador.Horario, Contrato.Puesto FROM Trabajador LEFT JOIN Contrato ON Trabajador.RFC = Contrato.Trabajador WHERE Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Contrato.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador.RFC = '" . $recibo->get('Trabajador') . "' AND Contrato.Servicio = '" . $recibo->get('Servicio') . "' AND DATEDIFF('" . $recibo->get('Fecha') . "', Puesto.Fecha) >=0 ORDER BY Contrato.Fecha DESC LIMIT 1");
			list($nombre_trabajador, $horario, $puesto) = $conn->fetchRow($result);

			if($finiquitador == 'Empresa administradora')
			{
				$result = $conn->query("SELECT Empresa.Nombre, Empresa.RFC, Registro_patronal.Numero, Registro_patronal.Sucursal, Empresa.Domicilio_fiscal, Empresa.Lugar FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero LEFT JOIN Empresa ON Registro_patronal.Empresa = Empresa.RFC WHERE Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Servicio = '". $recibo->get('Servicio') . "' AND DATEDIFF('" . $recibo->get('Fecha') . "',Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
				list($nombre_administradora, $administradora, $registro, $sucursal, $c_address, $place) = $conn->fetchRow($result);

				if(isset($sucursal) && $sucursal != '')
				{
					$result = $conn->query("SELECT Domicilio, Lugar FROM Sucursal WHERE Nombre = '$sucursal' AND Empresa = '$administradora'");
					list($c_address, $place) = $conn->fetchRow($result);
				}

			}
			else
			{
				$result = $conn->query("SELECT Empresa.Nombre, Empresa.Domilicio, Empresa.Lugar FROM Servicio_Empresa LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC WHERE Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Servicio = '" . $recibo->get('Servicio') . "' AND DATEDIFF('" . $recibo->get('Fecha') . "', Servicio_Empresa.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC LIMIT 1");
				list($empresa, $c_address, $place) = $conn->fetchRow($result);
				$result = $conn->query("SELECT Sucursal.Domicilio, Sucursal.Lugar FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Empresa.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador_Sucursal.Trabajador = '" . $recibo->get('Trabajador') . "' AND Trabajador_Sucursal.Servicio = '" . $recibo->get('Servicio') . "' AND DATEDIFF('" . $recibo->get('Fecha') . "', Trabajador_Sucursal.Fecha_de_ingreso) >= 0 ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");

				if($conn->num_rows($result) > 0)
					list($c_address, $place) = $conn->fetchRow($result);

			}

			echo "<span class = 'title_font' style = 'display:block; text-align:center;'>CARTA DE RENUNCIA</span>";
			echo "<br/><br/><span class = 'font' style = 'display:block; text-align:left;'>REPRESENTANTE LEGAL DE LA EMPRESA<br/>" . ($finiquitador == 'Empresa administradora' ? $nombre_administradora : $empresa) . "<br/>Y/O DUEÑO O RESPONSABLE DE TRABAJO UBICADO EN $c_address<br/>$place<br/>PRESENTE:</span>";
			echo "<br/><br/><br/><br/><br/><br/><span style = 'display:block; text-align:justify'>POR MEDIO DE LA PRESENTE LE MANIFIESTO QUE A PARTIR DE ESTA FECHA Y POR ASÍ CONVENIR A MIS INTERESES Y SIN COACCIÓN ALGUNA RENUNCIO VOLUNTARIA E IRREVOCABLEMENTE AL PUESTO DE $puesto, QUE VENIA DESEMPEÑANDO PARA ESTA EMPRESA. RECONOCIENDO QUE LABORE CON UN HORARIO DE $horario DESCANSANDO EL DÍA DOMINGO DE CADA SEMANA, ASÍ COMO LOS DÍAS DE DESCANSO OBLIGATORIOS MARCADOS POR LA LEY Y FESTIVOS.<br/><br/><br/>EXPRESAMENTE MANIFIESTO QUE DURANTE EL TIEMPO QUE DURÓ LA RELACIÓN DE TRABAJO SIEMPRE SE ME OTORGARON Y PAGARON ÍNTEGRAMENTE TODAS LAS PRESTACIONES QUE CONFORME A LA LEY ME CORRESPONDÍAN, POR LO NO SE ME ADEUDA NINGUNA CANTIDAD POR CONCEPTO DE SALARIOS, VACACIONES, PRIMA VACACIONAL, AGUINALDO, REPARTO DE UTILIDADES, HORAS EXTRA, SÉPTIMOS DÍAS, DÍAS DE DESCANSO, NI POR ALGÚN OTRO CONCEPTO DERIVADO DE LA RELACIÓN DE TRABAJO QUE TUVE CON LA EMPRESA, ASÍ MISMO MANIFIESTO QUE A QUIEN DIRIJO ESTE DOCUMENTO, SIEMPRE CUMPLIÓ PARA CONMIGO CON SUS OBLIGACIONES EN MATERIA DE SEGURIDAD SOCIAL TALES COMO AFILIARME Y PAGAR PUNTUALMENTE LAS CUOTAS OBRERO PATRONALES AL IMSS E INFONAVIT.<br/><br/><br/>POR LO ANTERIOR, NO ME RESERVO DERECHO O ACCIÓN ALGUNA QUE EJERCER CON POSTERIORIDAD A ESTA FECHA, EN CONTRA DE ESTA EMPRESA NI EN CONTRA DE ALGUNA PERSONA FÍSICA O MORAL RELACIONADA CON LA MISMA, PUES RECONOZCO QUE FUE MI ÚNICO PATRON Y RESPONSABLE DE LA FUENTE DE TRABAJO ARRIBA MENCIONADA. <br/><br/><br/>AGRADEZCO TODAS LAS ATENCIONES QUE ME OFRECIERON DURANTE EL TIEMPO QUE DURÓ LA RELACIÓN DE TRABAJO.<br/><br/><br/></span>";
			$n = substr($recibo->get("Fecha"),5,2);
			echo "<span style = 'display:block; margin:100px 150px 0px 150px; text-align:center;'>$place" . " A " . substr($recibo->get("Fecha"),8,2) . " DE " . $months[$n - 1] . " DEL " . substr($recibo->get("Fecha"),0,4) . "</span>";
			echo "<span style = 'display:block; margin:30px 250px 0px 250px; text-align:center;'>ATENTAMENTE</span>";
			$data = explode(' ', $nombre_trabajador);
			$len = count($data);
			$first = $data[0] . ' ' . $data[1];
			$last = '';

			for($i=2; $i<$len; $i++)
				$last .= $data[$i] . ' ';

			echo "<span style = 'display:block; margin:150px 250px 0px 250px; text-align:center; border-top:1px solid #555;'>" . $last . '  ' . $first . "</span>";
		?>
	</body>
</html>
