<?php //this document is oppened by a  function called _load at view_aguinaldo.php and gets the 'aguinaldo' tables sparated by 'sucursales'
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$_class = $_GET['_class'];
	$aguinaldo = $_GET['aguinaldo'];
	$result = $conn->query("SELECT Servicio_Empresa.Servicio, Servicio_Empresa.Empresa FROM Servicio_Empresa INNER JOIN Aguinaldo ON Servicio_Empresa.Servicio = Aguinaldo.Servicio WHERE Aguinaldo.id = '$aguinaldo' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Aguinaldo.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Aguinaldo.Fecha_de_pago, Servicio_Empresa.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC LIMIT 1");
	list($servicio,$empresa) = $conn->fetchRow($result);
	$conn->freeResult($result);
	$txt = '';
	$i = 0;
	$result = $conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
	$n = $conn->num_rows($result);

	while(list($sucursal) = $conn->fetchRow($result))
	{

		if($_class != 'Datos_fieldset')
		{

			if($_class == 'ISRaguinaldo_fieldset')
			{
				$txt .= '<table id="ISRaguinaldo"><tr><td colspan = "17" class = "title">Trabajadores asalariados de la sucursal ' . $sucursal . '</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">RFC</td><td class = "column_title">Número de días previos al ingreso</td><td class = "column_title">Número de días de baja</td><td class = "column_title">Número de días de aguinaldo</td><td class = "column_title">Salario diario</td><td class = "column_title">Aguinaldo ordinario</td><td class = "column_title">Gratificación adicional</td><td class = "column_title">Base</td><td class = "column_title">Límite inferior</td><td class = "column_title">Exedente del límite inferior</td><td class = "column_title">Porcentaje sobre el exedente del límite inferior</td><td class = "column_title">Impuesto marginal</td><td class = "column_title">Cuota fija</td><td class = "column_title">Impuesto determinado</td><td class = "column_title">Subsidio</td></tr>';
				$result1 = $conn->query("SELECT Trabajador FROM ISRaguinaldo WHERE Aguinaldo = '$aguinaldo' AND Cuenta = '{$_SESSION['cuenta']}'");
				$n = 1;

				while(list($trabajador) = $conn->fetchRow($result1))
				{
					$result2 = $conn->query("SELECT Nombre FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre = '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($flag) = $conn->fetchRow($result2);

					if(isset($flag))
					{
						$conn->freeResult($result2);
						$result2 = $conn->query("SELECT * FROM ISRaguinaldo WHERE Aguinaldo = '$aguinaldo' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");


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
			elseif($_class == 'Aguinaldo_asalariados_fieldset')
			{
				$txt .= '<table id="aguinaldo_asalariados"><tr><td colspan = "12" class = "title">Trabajadores asalariados de la sucursal ' . $sucursal . '</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">RFC</td><td class = "column_title">Número de dias de aguinaldo</td><td class = "column_title">Salario diario</td><td class = "column_title">Aguinaldo ordinario</td><td class = "column_title">Gratificación adicional</td><td class = "column_title">Total de percepciones</td><td class = "column_title">ISR</td><td class = "column_title">Total de deducciones</td><td class = "column_title">Saldo</td><td class = "column_title">Pago neto</td></tr>';
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
		else
		{

			if($i < ($n-1))
				$txt .= $sucursal . ',';
			else
				$txt .= $sucursal;

			$i++;
		}

	}

	echo $txt;
?>
