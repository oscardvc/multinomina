<?php //this document is oppened by a  function called _load at recibos.php and gets the 'nomina' tables sparated by 'sucursales'
	include_once('connection.php');

	if(!isset($_SESSION))
		session_start();

	$conn = new Connection();
	$_class = $_GET['_class'];
	$nomina = $_GET['nomina'];
	$result = $conn->query("SELECT Nomina.Limite_superior_del_periodo, Servicio_Empresa.Servicio, Servicio_Empresa.Empresa FROM Servicio_Empresa INNER JOIN Nomina ON Servicio_Empresa.Servicio = Nomina.Servicio WHERE Nomina.id = '$nomina' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Nomina.Limite_superior_del_periodo, Servicio_Empresa.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC LIMIT 1");
	list($lsp,$servicio,$empresa) = $conn->fetchRow($result);
	$conn->freeResult($result);
	$result = $conn->query("SELECT Registro_patronal.Numero, Registro_patronal.Empresa FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero WHERE Servicio_Registro_patronal.Servicio = '$servicio' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$lsp', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
	list($registro_patronal,$rfc_administradora) = $conn->fetchRow($result);
	$conn->freeResult($result);
	$txt = '';
	$i = 0;

	if($_class == 'Prestamos_cliente')
	{
		$result = $conn->query("SELECT Sucursal FROM Registro_patronal WHERE Numero = '$registro_patronal' AND Cuenta = '{$_SESSION['cuenta']}' AND Sucursal IS NOT NULL");
		$n = $conn->num_rows($result);
		$tabla = $_GET['tabla'] == 'Nomina_asalariados' ? 'nomina_asalariados' : 'nomina_asimilables';
		$txt .= "<table id='$tabla'><tr><td>Nombre</td><td>Préstamo de cliente</td><td>Localidad</td></tr>";
		$result1 = $conn->query("SELECT Trabajador, Prestamo_cliente FROM $tabla WHERE Nomina = '$nomina' AND Cuenta = '{$_SESSION['cuenta']}' AND Prestamo_cliente != '0'");

		while(list($trabajador,$prestamo) = $conn->fetchRow($result1))
		{
			$prestamos = explode(',',$prestamo);
			$result2 = $conn->query("SELECT Nombre FROM Trabajador WHERE RFC  = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($nombre) = $conn->fetchRow($result2);
			$conn->freeResult($result2);

			if($n > 0)//it's asigned to a branch
				$result2 = $conn->query("SELECT Sucursal.Localidad FROM Registro_patronal LEFT JOIN Sucursal ON Sucursal.Nombre = Registro_patronal.Sucursal AND Sucursal.Empresa = Registro_patronal.Empresa WHERE Registro_patronal.Numero = '$registro_patronal' AND Registro_patronal.Empresa = '$rfc_administradora' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}'");
			else
				$result2 = $conn->query("SELECT Localidad FROM Empresa WHERE RFC = '$rfc_administradora' AND Cuenta = '{$_SESSION['cuenta']}'");

			list($localidad) = $conn->fetchRow($result2);
			$conn->freeResult($result2);

			foreach($prestamos as $data)
			{
				$values = explode('</span>',$data);
				$id = str_replace('<span style="visibility:hidden">','',$values[0]);
				$value = str_replace('<span>','',$values[1]);
				$quantities = explode('/',$value);
				$amount = $quantities[0];
				$txt .= "<tr><td>$nombre</td><td>$amount</td><td>$localidad</td></tr>";
			}

		}

		$txt .= '</table>';
	}
	elseif($_class == 'Pensiones')
	{
		$txt .= "<table><tr><td>Nombre</td><td>Pensión alimenticia</td><td>Beneficiario</td><td>Folio</td><td>No. de expediente</td><td>No. de oficio</td><td>Porcentaje del salario</td></tr>";
		$result1 = $conn->query("SELECT Trabajador, Pension_alimenticia FROM nomina_asalariados WHERE Nomina = '$nomina' AND Cuenta = '{$_SESSION['cuenta']}' AND Pension_alimenticia != '0'");

		while(list($trabajador,$pension) = $conn->fetchRow($result1))
		{
			$pensiones = explode(',',$pension);
			$result2 = $conn->query("SELECT Nombre FROM Trabajador WHERE RFC  = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($nombre) = $conn->fetchRow($result2);
			$conn->freeResult($result2);

			foreach($pensiones as $data)
			{
				$values = explode('</span>',$data);
				$id = str_replace('<span style="visibility:hidden">','',$values[0]);
				$result2 = $conn->query("SELECT Beneficiario, Folio_IFE, No_de_expediente, No_de_oficio, Porcentaje_del_salario FROM Pension_alimenticia WHERE id  = '$id' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($beneficiary, $folio, $expediente, $oficio, $porcentaje) = $conn->fetchRow($result2);
				$conn->freeResult($result2);
				$value = str_replace('<span>','',$values[1]);
				$quantities = explode('/',$value);
				$amount = $quantities[0];

				if($amount > 0.00)
					$txt .= "<tr><td>$nombre</td><td>$amount</td><td>$beneficiary</td><td>$folio</td><td>$expediente</td><td>$oficio</td><td>$porcentaje</td></tr>";

			}

		}

		$txt .= '</table>';
	}
	else
	{
		$result = $conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
		$n = $conn->num_rows($result);

		while(list($sucursal) = $conn->fetchRow($result))
		{

			if($_class == 'Nomina_asalariados')
			{
				$txt .= '<table id="nomina_asalariados"><tr><td colspan = "46" class = "title">' . $sucursal . '</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">NSS</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días laborados</td><td class = "column_title">Salario</td><td class = "column_title">Sueldo</td><td class = "column_title">Subsidio al empleo</td><td class = "column_title">Horas extra</td><td class = "column_title">Prima dominical</td><td class = "column_title">Días de descanso</td><td class = "column_title">Premios de puntualidad y asistencia</td><td class = "column_title">Bonos de productividad</td><td class = "column_title">Estímulos</td><td class = "column_title">Compensaciones</td><td class = "column_title">Despensa</td><td class = "column_title">Comida</td><td class = "column_title">Alimentación</td><td class = "column_title">Habitación</td><td class = "column_title">Aportación patronal al fondo de ahorro</td><td class = "column_title">Total de percepciones</td><td class = "column_title">ISR</td><td class = "column_title">Cuotas IMSS</td><td class = "column_title">Retención por alimentación</td><td class = "column_title">Retención por habitación</td><td class = "column_title">Retención INFONAVIT</td><td class = "column_title">Retención FONACOT</td><td class = "column_title">Aportación del trabajador al fondo de ahorro</td><td class = "column_title">Pensión alimenticia</td><td class = "column_title">Retardos</td><td class = "column_title">Prestaciones</td><td class = "column_title">Gestión administrativa</td><td class = "column_title">Total de deducciones</td><td class = "column_title">Saldo</td><td class = "column_title">Pago neto</td><td class = "column_title">Pago líquido</td><td class = "column_title">Fondo de garantía</td><td class = "column_title">Pago por seguro de vida</td><td class = "column_title">Préstamo del fondo de ahorro</td><td class = "column_title">Préstamo de caja</td><td class = "column_title">Préstamo de cliente</td><td class = "column_title">Préstamo de administradora</td><td class = "column_title">Neto a recibir</td><td class = "column_title">Forma de pago</td><td class = "column_title">Status</td></tr>';
				$result1 = $conn->query("SELECT Trabajador FROM nomina_asalariados WHERE Nomina = '$nomina' AND Cuenta = '{$_SESSION['cuenta']}'");
				$n = 1;

				while(list($trabajador) = $conn->fetchRow($result1))
				{
					$result2 = $conn->query("SELECT Nombre FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre = '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($flag) = $conn->fetchRow($result2);

					if(isset($flag))
					{
						$conn->freeResult($result2);
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
			elseif($_class == 'Nomina_asimilables')
			{
				$txt .= '<table id="nomina_asimilables"><tr><td colspan = "20" class = "title">' . $sucursal . '</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Honorarios asimilados</td><td class = "column_title">Total de percepciones</td><td class = "column_title">ISR</td><td class = "column_title">Gestión administrativa</td><td class = "column_title">Total de deducciones</td><td class = "column_title">Saldo</td><td class = "column_title">Pago neto</td><td class = "column_title">Pago líquido</td><td class = "column_title">Pago por seguro de vida</td><td class = "column_title">Préstamo de caja</td><td class = "column_title">Préstamo de cliente</td><td class = "column_title">Préstamo de administradora</td><td class = "column_title">Neto a recibir</td><td class = "column_title">Forma de pago</td></tr>';
				$result1 = $conn->query("SELECT Trabajador FROM nomina_asimilables WHERE Nomina = '$nomina' AND Cuenta = '{$_SESSION['cuenta']}'");
				$n = 1;

				while(list($trabajador) = $conn->fetchRow($result1))
				{
					$result2 = $conn->query("SELECT Nombre FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Nombre = '$sucursal' AND Empresa = '$empresa' AND Servicio = '$servicio' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($flag) = $conn->fetchRow($result2);

					if(isset($flag))
					{
						$conn->freeResult($result2);
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

	}

	$result = $conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina FROM Nomina LEFT JOIN Servicio ON Nomina.Servicio = Servicio.id WHERE Nomina.id = '$nomina' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}'");
	list($servicio,$periodicidad) = $conn->fetchRow($result);
	$conn->freeResult($result);

	if($periodicidad == 'Semanal')
		$txt .= '<div>Semana</div>';
	else
		$txt .= '<div>Quincena</div>';

	$txt .= "<div>$rfc_administradora</div>";
	$txt .= "<div>$registro_patronal</div>";
	echo $txt;
?>
