<?php //this document is oppened by a  function called _load at _recibos_aguinaldo.php and gets the 'aguinaldo' tables sparated by 'sucursales'
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$_class = $_GET['_class'];
	$aguinaldo = $_GET['aguinaldo'];
	$result = $conn->query("SELECT Aguinaldo.Fecha_de_pago, Servicio_Empresa.Servicio, Servicio_Empresa.Empresa FROM Servicio_Empresa INNER JOIN Aguinaldo ON Servicio_Empresa.Servicio = Aguinaldo.Servicio WHERE Aguinaldo.id = '$aguinaldo' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Aguinaldo.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Aguinaldo.Fecha_de_pago, Servicio_Empresa.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC LIMIT 1");
	list($fdp,$servicio,$empresa) = $conn->fetchRow($result);
	$conn->freeResult($result);
	$txt = '';
	$i = 0;
	$result = $conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
	$n = $conn->num_rows($result);

	while(list($sucursal) = $conn->fetchRow($result))
	{

		if($_class == 'Aguinaldo_asalariados_fieldset')
		{
			$txt .= '<table id="aguinaldo_asalariados"><tr><td colspan = "12" class = "title">' . $sucursal . '</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">RFC</td><td class = "column_title">Número de dias de aguinaldo</td><td class = "column_title">Salario diario</td><td class = "column_title">Aguinaldo ordinario</td><td class = "column_title">Gratificación adicional</td><td class = "column_title">Total de percepciones</td><td class = "column_title">ISR</td><td class = "column_title">Total de deducciones</td><td class = "column_title">Saldo</td><td class = "column_title">Pago neto</td></tr>';
			$result1 = $conn->query("SELECT Trabajador FROM aguinaldo_asalariados WHERE Aguinaldo = '$aguinaldo' AND Cuenta = '{$_SESSION['cuenta']}'");
			$n = 1;

			while(list($trabajador) = $conn->fetchRow($result1))
			{
				$result2 = $conn->query("SELECT Nombre FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre = '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($flag) = $conn->fetchRow($result2);

				if(isset($flag))
				{
					$conn->freeResult($result2);
					$result2 = $conn->query("SELECT * FROM aguinaldo_asalariados WHERE Aguinaldo = '$aguinaldo' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");


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
							elseif($key != 'Aguinaldo' && $key != 'Cuenta')
								$txt .= "<td>$value</td>";

						$txt .= '</tr>';
					}

				}

			}

		}

		$txt .= '</table>';
	}

	$result = $conn->query("SELECT Servicio FROM Aguinaldo WHERE id = '$aguinaldo' AND Cuenta = '{$_SESSION['cuenta']}'");
	list($servicio) = $conn->fetchRow($result);
	$conn->freeResult($result);
	$result = $conn->query("SELECT Registro_patronal.Numero, Registro_patronal.Empresa FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero WHERE Servicio_Registro_patronal.Servicio = '$servicio' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$fdp', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
	list($registro_patronal,$administradora_rfc) = $conn->fetchRow($result);
	$conn->freeResult($result);
	$txt .= "<div>$administradora_rfc</div>";
	$txt .= "<div>$registro_patronal</div>";
	echo $txt;
?>
