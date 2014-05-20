<?php //This page is called by a javascript function named view at nomina.js?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

	<head>
		<style type="text/css">
			body
			{
				font:normal normal normal 12px Arial , sans-serif;
				color:#555;
			}

			._title
			{
				font:bold normal normal 12px Arial , sans-serif;
				text-align:center;
			}

			.column_title
			{
				font:bold normal normal 12px Arial , sans-serif;
				color:#fff;
				background:#555;
				text-align:center;
			}

			.column_subtitle
			{
				font:bold normal normal 12px Arial , sans-serif;
				color:#fff;
				background:#3399cc;
				text-align:center;
			}
		</style>
		<script type = "text/javascript">
			function fit_table()
			{
				var tables = document.getElementsByTagName('table');
				var _table = tables[0];
				_table.style.display = 'block';
				_table.style.padding = 0;
				_table.style.margin = 0;

				for(var i=0; i<_table.rows.length; i++)

					if(_table.rows[i].firstChild.getAttribute('class') == '_title')
					{
						_table.rows[i].style.display = 'block';
						_table.rows[i].style.textAlign = 'center';
						_table.rows[i].firstChild.style.display = 'block';

						if(i == 0)
							_table.rows[i].style.margin = '15px 0px 0px 0px';
						else if(i == 2)
							_table.rows[i].style.margin = '0px 0px 15px 0px';

					}

			}
		</script>
	</head>
	<body><!-- onload = "fit_table()" -->
	<?php
		include_once('connection.php');

		if(!isset($_SESSION))
			session_start();

		$conn = new Connection();
		include_once('recibo_de_vacaciones.php');
		$recibo = new Recibo_de_vacaciones();
		$recibo->set('id',$_GET['id']);
		$recibo->setFromDb();
		$result = $conn->query("SELECT Empresa.Nombre, Empresa.RFC FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero LEFT JOIN Empresa ON (Registro_patronal.Empresa = Empresa.RFC OR Registro_patronal.Empresa_sucursal = Empresa.RFC) WHERE Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Servicio = '". $recibo->get('Servicio') . "' AND DATEDIFF('" . $recibo->get('Fecha') . "', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
		list($nombre_administradora, $administradora) = $conn->fetchRow($result);
		$result = $conn->query("SELECT Empresa.Nombre FROM Servicio_Empresa LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC WHERE Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Servicio = '" . $recibo->get('Servicio') . "' AND DATEDIFF('" . $recibo->get('Fecha') . "', Servicio_Empresa.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC LIMIT 1");
		list($empresa) = $conn->fetchRow($result);
		echo "<img src='images/logo_blanco.jpg'><br/><br/>";
		$txt = "<table align='center'>";
			$txt .= "<tr><td colspan=2 class='_title'>$nombre_administradora</td></tr>";
			$result = $conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '". $recibo->get('Trabajador') . "' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($trabajador) = $conn->fetchRow($result);
			$txt .= "<tr><td colspan=2 class='_title'>Resumen de vacaciones" . ($nombre_administradora != $empresa ? " del personal asignado a $empresa" : "") . "</td></tr>";
			$txt .= "<tr><td colspan=2 class='_title'>Trabajador: $trabajador</td></tr>";
			$vacaciones = explode(',',$recibo->get('Vacaciones'));
			$prima_vacacional = explode(',',$recibo->get('Prima_vacacional'));
			$anos = explode(',',$recibo->get('Anos'));
			$len = count($anos);
			$txt .= "<tr><td colspan=2 class='column_title'>Vacaciones</td></tr>";

			for($i=0; $i<$len; $i++)
			{
				$txt .= "<tr><td>Vacaciones ({$anos[$i]})</td><td style='text-align:right'>" . ($i==0 ? '$' : '') . number_format($vacaciones[$i], 2, '.' , ',') . "</td></tr>";
				$txt .= "<tr><td>Prima vacacional ({$anos[$i]})</td><td style='text-align:right'>" . number_format($prima_vacacional[$i], 2, '.' , ',') . "</td></tr>";
			}

			if($recibo->get('Compensacion') > 0)
				$txt .= "<tr><td>Compensación</td><td style='text-align:right'>" . number_format($recibo->get('Compensacion'), 2, '.' , ',') . "</td></tr>";

			$txt .= "<tr><td>Total de percepciones</td><td style='text-align:right; border-top:1px solid #555'>$" . number_format($recibo->get('Total_de_percepciones'), 2, '.' , ',') . "</td></tr>";
			$txt .= "<tr><td>ISR</td><td style='text-align:right'>$" . number_format($recibo->get('ISR'), 2, '.' , ',') . "</td></tr>";
			$txt .= "<tr><td>Total de deducciones</td><td style='text-align:right; border-top:1px solid #555'>$" . number_format($recibo->get('ISR'), 2, '.' , ',') . "</td></tr>";
			$txt .= "<tr style='background:#eee'><td>Total a percibir</td><td style='text-align:right'>$" . number_format($recibo->get('Saldo'), 2, '.' , ',') . "</td></tr>";
			$txt .= "<tr><td colspan=2 class='column_title'>Retenciones</td></tr>";
			$txt .= "<tr style='background:#eee'><td>ISR</td><td style='text-align:right'>$" . number_format($recibo->get('ISR'), 2, '.' , ',') . "</td></tr>";
			$txt .= "<tr><td colspan=2 class='column_title'>Diferencia</td></tr>";
			$vacaciones_retenidas = explode(',',$recibo->get('Vacaciones_retenidas'));
			$prima_vacacional_retenida = explode(',',$recibo->get('Prima_vacacional_retenida'));
			$txt .= "<tr><td>Cantidad a pagar</td><td style='text-align:right'>$" . number_format($recibo->get('Saldo'), 2, '.' , ',') . "</td></tr>";

			for($i=0; $i<$len; $i++)
			{
				$txt .= "<tr><td>Vacaciones retenidas ({$anos[$i]})</td><td style='text-align:right'>" . number_format($vacaciones_retenidas[$i], 2, '.' , ',') . "</td></tr>";
				$txt .= "<tr><td>Prima vacacional retenida ({$anos[$i]})</td><td style='text-align:right'>" . number_format($prima_vacacional_retenida[$i], 2, '.' , ',') . "</td></tr>";
			}

			$txt .= "<tr style='background:#eee'><td>Diferencia</td><td style='text-align:right'>$" . number_format($recibo->get('Diferencia'), 2, '.' , ',') . "</td></tr>";
			$txt .= "<tr><td colspan=2 class='column_title'>Integración de la facturación</td></tr>";
			$txt .= "<tr><td>Retenciones</td><td style='text-align:right'>$" . number_format($recibo->get('ISR'), 2, '.' , ',') . "</td></tr>";
			$txt .= "<tr><td>Diferencia</td><td style='text-align:right'>" . number_format($recibo->get('Diferencia'), 2, '.' , ',') . "</td></tr>";
			$txt .= "<tr><td>Honorarios</td><td style='text-align:right'>" . number_format($recibo->get('Honorarios'), 2, '.' , ',') . "</td></tr>";
			$txt .= "<tr><td>Subtotal a facturar</td><td style='text-align:right; border-top:1px solid #555'>" . number_format($recibo->get('Subtotal_a_facturar'), 2, '.' , ',') . "</td></tr>";
			$txt .= "<tr><td>IVA</td><td style='text-align:right'>" . number_format($recibo->get('iva'), 2, '.' , ',') . "</td></tr>";
			$txt .= "<tr style='background:#eee'><td>Total a facturar</td><td style='text-align:right;'>" . number_format($recibo->get('Total_a_facturar'), 2, '.' , ',') . "</td></tr>";
			echo $txt;
	?>
	</body>
</html>
