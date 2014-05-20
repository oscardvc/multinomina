<?php //This page is called by a javascript function named view at nomina.js?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
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
		<script type="text/javascript">
			if( typeof( window.innerWidth ) == 'number' )
			{ 
				//Non-IE
				window_width = window.innerWidth;
				window_height = window.innerHeight;
				window.moveTo(0,0);
				window.resizeTo(screen.availWidth, screen.availHeight);
			}
			else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) )
			{
				//IE 6+ in 'standards compliant mode'
				window_width = document.documentElement.clientWidth; 
				window_height = document.documentElement.clientHeight;
				window.moveTo(0,0);
				window.resizeTo(screen.availWidth, screen.availHeight);
			}
			else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) )
			{
				//IE 4 compatible
				window_width = document.body.clientWidth;
				window_height = document.body.clientHeight;
				window.moveTo(0,0);
				window.resizeTo(screen.availWidth, screen.availHeight);
			}
		</script>
	</head>

	<body>
<?php
	include_once('connection.php');
	include_once('prestamo_del_fondo_de_ahorro.php');
	$prestamo = new Prestamo_del_fondo_de_ahorro();
	$prestamo->set('Numero_de_prestamo',$_GET['numero']);
	$prestamo->setFromDb();
	$_fechas = $prestamo->get('Fecha_de_descuento');
	$fechas = explode(',',$_fechas);
	$servicio = $prestamo->get('Servicio');
	$trabajador = $prestamo->get('Trabajador');
	$len = count($fechas);
	$conn = new Connection();
	$result = $conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
	list($nombre_trabajador) = $conn->fetchRow($result);
	$conn->freeResult($result);
	echo "<img src = 'images/logo_blanco.jpg'/><br/><br/>Préstamo del fondo de ahorro del trabajador $nombre_trabajador";
	$txt = "<table><tr style = 'text-align:center;color:#fff;background:#555' class = 'titles'><td colspan = '4'>Préstamo ID: {$_GET['numero']}</td></tr><tr style = 'text-align:center;color:#fff;background:#3399cc' class = 'subtitles'><td>N</td><td>Límite inferior del periodo</td><td>Límite superior del periodo</td><td>Monto pagado</td></tr>";
	$total = 0.00;
	$n = 1;

	for($i=0; $i<$len; $i++)
	{
		$result = $conn->query("SELECT Nomina.Limite_inferior_del_periodo, Nomina.Limite_superior_del_periodo, nomina_asalariados.Prestamo_del_fondo_de_ahorro FROM Nomina LEFT JOIN nomina_asalariados ON Nomina.id = nomina_asalariados.Nomina WHERE Nomina.Servicio = '$servicio' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND nomina_asalariados.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$fechas[$i]}',Limite_inferior_del_periodo) >= 0 AND DATEDIFF(Limite_superior_del_periodo,'{$fechas[$i]}') >= 0 AND Trabajador = '$trabajador'");
		list($lip, $lsp, $data) = $conn->fetchRow($result);
		$conn->freeResult($result);
		$_data = explode(',',$data);
		$_len = count($_data);

		for($j=0; $j<$_len; $j++)
		{
			$values = explode('</span>',$_data[$j]);

			if(count($values) > 1)
			{
				preg_match('/\d+/', $values[0], $matches);
				$id = $matches[0];
				$value = str_replace('<span>','',$values[1]);
				$data_ = explode('/',$value);

				if($id == $_GET['numero'] && $data_[0] > 0.00)
				{
					$total += $data_[0];
					$txt .= "<tr><td>$n</td><td>$lip</td><td>$lsp</td><td style = 'text-align:Right'>" . number_format($data_[0],2,'.',',') . "</td></tr>";
					$n++;
				}

			}

		}

	}

	$txt .= "<tr class = 'totals'><td style = 'background:#fff'></td><td style = 'background:#fff'></td><td style = 'background:#fff'>Total</td><td>" . number_format($total,2,'.',',') . "</td></tr></table>";
	$txt .= "</table>";
	echo $txt;
?>
	</body>
</html>
