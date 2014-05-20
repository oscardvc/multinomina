<?php
	include_once('cfdi_trabajador.php');
	include_once('connection.php');
	include_once('actividad.php');

//Class Nomina definition

	class Nomina
	{
		//class properties
		//data
		private $id;
		private $Limite_inferior_del_periodo;
		private $Limite_superior_del_periodo;
		private $Etapa;
		private $Servicio;
		private $Fecha_de_pago;
		private $ISRasalariados;//html string
		private $ISRasimilables;//html string
		private $cuotas_IMSS;//html string
		private $prestaciones;//html string
		private $nomina_asalariados;//html string
		private $nomina_asimilables;//html string
		private $incidencias;//html string
		public  $Resumen;//string value,value,...,value
		private	$trabajador;//array wont be stored
		//database connection
		private $conn;

		//class methods
		public function __construct()
		{

			if(!isset($_SESSION))
				session_start();

			$this->conn = new Connection();
		}

		public function __destruct()
		{
		}

		public function set($name, $value) //sets property named $name with value $value
		{
			$this->$name = $value;
		}

		private function setID()
		{
			$result = $this->conn->query("SELECT id FROM Nomina WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY id DESC LIMIT 1");
			$row = $this->conn->fetchRow($result);

			if(isset($row))
			{
				list($this->id) = $row;
				$this->id ++;
			}
			else
				$this->id = 1;

		}

		public function get($name)//gets property named $name
		{
			return $this->$name;
		}

		private function get_empresa()
		{
			$result = $this->conn->query("SELECT Empresa FROM Servicio_Empresa WHERE Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}', Fecha_de_asignacion) >=0 ORDER BY Fecha_de_asignacion DESC LIMIT 1");
			list($empresa) = $this->conn->fetchRow($result);
			return $empresa;
		}

		public function showProperties()//prints properties values
		{

			foreach($this as $key => $value)

				if($key != 'conn')
					echo "$key = $value <br />";

		}

		public function setFromBrowser()//sets properties from superglobal $_POST
		{

			foreach($this as $key => $value)

				if(isset($_POST["$key"]))
					$this->$key = trim($_POST["$key"]);

		}

		public function setFromDb()//sets properties from data base, but $id has to be set before
		{

			if(isset($this->id))
			{
				$this->ISRasalariados = '<table id="ISRasalariados"><tr><td colspan = "40" class = "title">Trabajadores asalariados</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">NSS</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Número de días previos al ingreso</td><td class = "column_title">Número de días de baja</td><td class = "column_title">Faltas</td><td class = "column_title">Número de faltas</td><td class = "column_title">Número de días de incapacidad</td><td class = "column_title">Número de días de vacaciones</td><td class = "column_title">Número de días laborados</td><td class = "column_title">Salario diario</td><td class = "column_title">Sueldo</td><td class = "column_title">Horas extra</td><td class = "column_title">Horas extra gravadas</td><td class = "column_title">Domingos laborados</td><td class = "column_title">Número de domingos laborados</td><td class = "column_title">Prima dominical</td><td class = "column_title">Prima dominical gravada</td><td class = "column_title">Días de descanso</td><td class = "column_title">Días de descanso gravados</td><td class = "column_title">Premios de puntualidad y asistencia</td><td class = "column_title">Bonos de productividad</td><td class = "column_title">Estímulos</td><td class = "column_title">Compensaciones</td><td class = "column_title">Despensa</td><td class = "column_title">Comida</td><td class = "column_title">Previsión social gravada</td><td class = "column_title">Base</td><td class = "column_title">Límite inferior</td><td class = "column_title">Exedente del límite inferior</td><td class = "column_title">Porcentaje sobre el exedente del límite inferior</td><td class = "column_title">Impuesto marginal</td><td class = "column_title">Cuota fija</td><td class = "column_title">Impuesto determinado</td><td class = "column_title">Subsidio</td><td class = "column_title">ISR</td><td class = "column_title">Subsidio al empleo</td></tr>';
				$result = $this->conn->query("SELECT * FROM ISRasalariados WHERE Nomina = {$this->id} AND Cuenta = '{$_SESSION['cuenta']}'");
				$n = 1;

				while($row = $this->conn->fetchRow($result,'ASSOC'))
				{
					$this->ISRasalariados .= '<tr>';

					foreach($row as $key => $value)

						if($key == 'Trabajador')
						{
							$result1 = $this->conn->query("SELECT Nombre, Numero_IMSS, CURP FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($nombre,$nss,$curp) = $this->conn->fetchRow($result1);
							$this->ISRasalariados .= "<td>$n</td><td>$nombre</td><td>$nss</td><td>$curp</td><td>$value</td>";
							$this->conn->freeResult($result1);
							$n ++;
						}
						elseif($key != 'Nomina' && $key != 'Cuenta')
							$this->ISRasalariados .= "<td>$value</td>";

					$this->ISRasalariados .= '</tr>';
				}


				$this->ISRasalariados .= '</table>';
				$this->conn->freeResult($result);
				$this->ISRasimilables = '<table id="ISRasimilables"><tr><td colspan = "21" class = "title">Asimilables</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Número de días previos al ingreso</td><td class = "column_title">Número de días de baja</td><td class = "column_title">Número de días laborados</td><td class = "column_title">Salario diario</td><td class = "column_title">Honorarios asimilados</td><td class = "column_title">Base</td><td class = "column_title">Límite inferior</td><td class = "column_title">Exedente del límite inferior</td><td class = "column_title">Porcentaje sobre el exedente del límite inferior</td><td class = "column_title">Impuesto marginal</td><td class = "column_title">Cuota fija</td><td class = "column_title">Impuesto determinado</td><td class = "column_title">Subsidio</td><td class = "column_title">ISR</td><td class = "column_title">Subsidio al empleo</td></tr>';
				$result = $this->conn->query("SELECT * FROM ISRasimilables WHERE Nomina = {$this->id} AND Cuenta = '{$_SESSION['cuenta']}'");
				$n = 1;

				while($row = $this->conn->fetchRow($result,'ASSOC'))
				{
					$this->ISRasimilables .= '<tr>';

					foreach($row as $key => $value)

						if($key == 'Trabajador')
						{
							$result1 = $this->conn->query("SELECT Nombre, CURP FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($nombre,$curp) = $this->conn->fetchRow($result1);
							$this->ISRasimilables .= "<td>$n</td><td>$nombre</td><td>$curp</td><td>$value</td>";
							$this->conn->freeResult($result1);
							$n ++;
						}
						elseif($key != 'Nomina' && $key != 'Cuenta')
							$this->ISRasimilables .= "<td>$value</td>";

					$this->ISRasimilables .= '</tr>';
				}

				$this->ISRasimilables .= '</table>';
				$this->conn->freeResult($result);
				$this->cuotas_IMSS = '<table id="cuotas_IMSS"><tr><td colspan = "42" class = "title">Trabajadores asalariados</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Número de días previos al ingreso</td><td class = "column_title">Número de días de baja</td><td class = "column_title">Número de días de incapacidad</td><td class = "column_title">Número de días cotizados</td><td class = "column_title">Años de antigüedad</td><td class = "column_title">Días de vacaciones</td><td class = "column_title">Factor de prima vacacional</td><td class = "column_title">Factor de aguinaldo</td><td class = "column_title">Factor de integración</td><td class = "column_title">Salario diario integrado</td><td class = "column_title">Sueldo integrado</td><td class = "column_title">Cuota fija IMSS</td><td class = "column_title">Exedente 3 SMGDF patronal</td><td class = "column_title">Exedente 3 SMGDF obrero</td><td class = "column_title">Prestaciones en dinero obreras</td><td class = "column_title">Prestaciones en dinero patronales</td><td class = "column_title">Gastos médicos y pensión obreros</td><td class = "column_title">Gastos médicos y pensión patronales</td><td class = "column_title">Invalidez y vida obrera</td><td class = "column_title">Invalidez y vida patronal</td><td class = "column_title">Cesantía y vejez obrera</td><td class = "column_title">Cesantía y vejez patronal</td><td class = "column_title">Guardería</td><td class = "column_title">Retiro</td><td class = "column_title">INFONAVIT</td><td class = "column_title">Riesgo de trabajo</td><td class = "column_title">Total de cuotas IMSS obreras</td><td class = "column_title">Total de cuotas IMSS patronales</td><td class = "column_title">Adeudo</td></tr>';
				$result = $this->conn->query("SELECT * FROM cuotas_IMSS WHERE Nomina = {$this->id} AND Cuenta = '{$_SESSION['cuenta']}'");
				$n = 1;

				while($row = $this->conn->fetchRow($result,'ASSOC'))
				{
					$this->cuotas_IMSS .= '<tr>';

					foreach($row as $key => $value)

						if($key == 'Trabajador')
						{
							$result1 = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($nombre) = $this->conn->fetchRow($result1);
							$this->cuotas_IMSS .= "<td>$n</td><td>$nombre</td><td>$value</td>";
							$this->conn->freeResult($result1);
							$n ++;
						}
						elseif($key != 'Nomina' && $key != 'Cuenta')
							$this->cuotas_IMSS .= "<td>$value</td>";

					$this->cuotas_IMSS .= '</tr>';
				}

				$this->cuotas_IMSS .= '</table>';
				$this->conn->freeResult($result);
				$this->prestaciones = '<table id="prestaciones"><tr><td colspan = "18" class = "title">Trabajadores asalariados</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">NSS</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días</td><td class = "column_title">Años de antigüedad</td><td class = "column_title">Sueldo</td><td class = "column_title">Vacaciones</td><td class = "column_title">Retención proporcional de vacaciones</td><td class = "column_title">Prima vacacional</td><td class = "column_title">Retención proporcional de prima vacacional</td><td class = "column_title">Aguinaldo</td><td class = "column_title">Retención proporcional de aguinaldo</td><td class = "column_title">Prima de antigüedad</td><td class = "column_title">Retención proporcional de prima de antigüedad</td><td class = "column_title">Total de prestaciones</td><td class = "column_title">Total de retenciones</td></tr>';
				$result = $this->conn->query("SELECT * FROM prestaciones WHERE Nomina = {$this->id} AND Cuenta = '{$_SESSION['cuenta']}'");
				$n = 1;

				while($row = $this->conn->fetchRow($result,'ASSOC'))
				{
					$this->prestaciones .= '<tr>';

					foreach($row as $key => $value)

						if($key == 'Trabajador')
						{
							$result1 = $this->conn->query("SELECT Nombre, Numero_IMSS, CURP FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($nombre,$nss,$curp) = $this->conn->fetchRow($result1);
							$this->prestaciones .= "<td>$n</td><td>$nombre</td><td>$nss</td><td>$curp</td><td>$value</td>";
							$this->conn->freeResult($result1);
							$n ++;
						}
						elseif($key != 'Nomina' && $key != 'Cuenta')
							$this->prestaciones .= "<td>$value</td>";

					$this->prestaciones .= '</tr>';
				}

				$this->prestaciones .= '</table>';
				$this->conn->freeResult($result);
				$this->nomina_asalariados = '<table id="nomina_asalariados"><tr><td colspan = "46" class = "title">Trabajadores asalariados</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">NSS</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días laborados</td><td class = "column_title">Salario</td><td class = "column_title">Sueldo</td><td class = "column_title">Subsidio al empleo</td><td class = "column_title">Horas extra</td><td class = "column_title">Prima dominical</td><td class = "column_title">Días de descanso</td><td class = "column_title">Premios de puntualidad y asistencia</td><td class = "column_title">Bonos de productividad</td><td class = "column_title">Estímulos</td><td class = "column_title">Compensaciones</td><td class = "column_title">Despensa</td><td class = "column_title">Comida</td><td class = "column_title">Alimentación</td><td class = "column_title">Habitación</td><td class = "column_title">Aportación patronal al fondo de ahorro</td><td class = "column_title">Total de percepciones</td><td class = "column_title">ISR</td><td class = "column_title">Cuotas IMSS</td><td class = "column_title">Retención por alimentación</td><td class = "column_title">Retención por habitación</td><td class = "column_title">Retención INFONAVIT</td><td class = "column_title">Retención FONACOT</td><td class = "column_title">Aportación del trabajador al fondo de ahorro</td><td class = "column_title">Pensión alimenticia</td><td class = "column_title">Retardos</td><td class = "column_title">Prestaciones</td><td class = "column_title">Gestión administrativa</td><td class = "column_title">Total de deducciones</td><td class = "column_title">Saldo</td><td class = "column_title">Pago neto</td><td class = "column_title">Pago líquido</td><td class = "column_title">Fondo de garantía</td><td class = "column_title">Pago por seguro de vida</td><td class = "column_title">Préstamo del fondo de ahorro</td><td class = "column_title">Préstamo de caja</td><td class = "column_title">Préstamo de cliente</td><td class = "column_title">Préstamo de administradora</td><td class = "column_title">Neto a recibir</td><td class = "column_title">Forma de pago</td><td class = "column_title">Status</td></tr>';
				$result = $this->conn->query("SELECT * FROM nomina_asalariados WHERE Nomina = {$this->id} AND Cuenta = '{$_SESSION['cuenta']}'");
				$n = 1;

				while($row = $this->conn->fetchRow($result,'ASSOC'))
				{
					$this->nomina_asalariados .= '<tr>';

					foreach($row as $key => $value)

						if($key == 'Trabajador')
						{
							$result1 = $this->conn->query("SELECT Nombre, Numero_IMSS, CURP FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($nombre,$nss,$curp) = $this->conn->fetchRow($result1);
							$this->nomina_asalariados .= "<td>$n</td><td>$nombre</td><td>$nss</td><td>$curp</td><td>$value</td>";
							$this->conn->freeResult($result1);
							$n ++;
						}
						elseif($key != 'Nomina' && $key != 'Cuenta')
							$this->nomina_asalariados .= "<td>$value</td>";

					$this->nomina_asalaridos .= '</tr>';
				}

				$this->nomina_asalariados .= '</table>';
				$this->conn->freeResult($result);
				$this->nomina_asimilables = '<table id="nomina_asimilables"><tr><td colspan = "20" class = "title">Asimilables</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Honorarios asimilados</td><td class = "column_title">Total de percepciones</td><td class = "column_title">ISR</td><td class = "column_title">Gestión administrativa</td><td class = "column_title">Total de deducciones</td><td class = "column_title">Saldo</td><td class = "column_title">Pago neto</td><td class = "column_title">Pago líquido</td><td class = "column_title">Pago por seguro de vida</td><td class = "column_title">Préstamo de caja</td><td class = "column_title">Préstamo de cliente</td><td class = "column_title">Préstamo de administradora</td><td class = "column_title">Neto a recibir</td><td class = "column_title">Forma de pago</td><td class = "column_title">Status</td></tr>';
				$result = $this->conn->query("SELECT * FROM nomina_asimilables WHERE Nomina = {$this->id} AND Cuenta = '{$_SESSION['cuenta']}'");
				$n = 1;

				while($row = $this->conn->fetchRow($result,'ASSOC'))
				{
					$this->nomina_asimilables .= '<tr>';

					foreach($row as $key => $value)

						if($key == 'Trabajador')
						{
							$result1 = $this->conn->query("SELECT Nombre, CURP FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($nombre,$curp) = $this->conn->fetchRow($result1);
							$this->nomina_asimilables .= "<td>$n</td><td>$nombre</td><td>$curp</td><td>$value</td>";
							$this->conn->freeResult($result1);
							$n ++;
						}
						elseif($key != 'Nomina' && $key != 'Cuenta')
							$this->nomina_asimilables .= "<td>$value</td>";

					$this->nomina_asimilables .= '</tr>';
				}

				$this->nomina_asimilables .= '</table>';
				$this->conn->freeResult($result);
				$this->incidencias = '<table id="incidencias"><tr><td>Incidencias</td></tr><tr><td>Trabajador</td><td>Pago neto</td><td>Pago líquido</td><td>Diferencia</td></tr>';
				$result = $this->conn->query("SELECT * FROM incidencias WHERE Nomina = {$this->id} AND Cuenta = '{$_SESSION['cuenta']}'");

				while($row = $this->conn->fetchRow($result,'ASSOC'))
				{
					$this->incidencias .= '<tr>';

					foreach($row as $key => $value)

						if($key != 'Nomina' && $key != 'Cuenta')
							$this->incidencias .= "<td>$value</td>";

					$this->incidencias .= '</tr>';
				}

				$this->incidencias .= '</table>';
				$this->conn->freeResult($result);

				$result = $this->conn->query("SELECT * FROM Resumen WHERE Nomina = {$this->id} AND Cuenta = '{$_SESSION['cuenta']}'");

				while($row = $this->conn->fetchRow($result,'ASSOC'))
				{

					foreach($row as $key => $value)

						if($key != 'Nomina' && $key != 'Cuenta')
							$this->Resumen .= $value . ',';

				}

				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT * FROM Nomina WHERE id = {$this->id} AND Cuenta = '{$_SESSION['cuenta']}'");
				$row = $this->conn->fetchRow($result,'ASSOC');

				foreach($row as $key => $value)

					if($key != 'id' && $key != 'Cuenta')
						$this->$key = $value;

				$this->conn->freeResult($result);
			}

		}

		public function dbDelete()//delete this entity from database but id has to be set before
		{

			if(isset($this->id))
			{
				$actividad = new Actividad();
				$actividad->set('Operacion','Eliminar');
				$actividad->set('Dato','Nomina');
				$result = $this->conn->query("SELECT Nomina.Limite_inferior_del_periodo,Nomina.Limite_superior_del_periodo, Servicio.id, Servicio.Periodicidad_de_la_nomina, Empresa.Nombre, Servicio_Registro_patronal.Registro_patronal FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio_Empresa.Servicio = Servicio_Registro_patronal.Servicio LEFT JOIN Nomina ON Servicio.id = Nomina.Servicio WHERE Nomina.id = '{$this->id}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Nomina.Limite_superior_del_periodo, Servicio_Empresa.Fecha_de_asignacion) >=0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, Servicio_Registro_patronal.Fecha_de_asignacion) >=0 ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
				list($lip,$lsp,$id,$periodicidad,$empresa,$registro) = $this->conn->fetchRow($result);
				$actividad->set('Identificadores',"Límite inferior del periodo: $lip Límite superior del periodo: $lsp Servicio: $id/$periodicidad/$empresa/$registro");
				$actividad->dbStore();
				$this->conn->query("DELETE FROM Nomina WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. If $update is true it updates all database registers (professedly one) with $this' id
		{
			$actividad = new Actividad();
			$actividad->set('Dato','Nomina');
			$result = $this->conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Empresa.Nombre, Servicio_Registro_patronal.Registro_patronal FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio_Empresa.Servicio = Servicio_Registro_patronal.Servicio WHERE Servicio.id = '{$this->Servicio}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}', Servicio_Empresa.Fecha_de_asignacion) >=0 AND DATEDIFF('{$this->Limite_superior_del_periodo}', Servicio_Registro_patronal.Fecha_de_asignacion) >=0 ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
			list($id,$periodicidad,$empresa,$registro) = $this->conn->fetchRow($result);
			$actividad->set('Identificadores',"Límite inferior del periodo: {$this->Limite_inferior_del_periodo} Límite superior del periodo: {$this->Limite_superior_del_periodo} Servicio: $id/$periodicidad/$empresa/$registro");
			$this->conn->freeResult($result);

			if($update == 'false')
			{
				$this->setID();
				$this->conn->query("INSERT INTO Nomina(id, Cuenta) VALUES({$this->id}, '{$_SESSION['cuenta']}')");
				$actividad->set('Operacion','Nuevo');
			}
			else
			{
				$this->update_trabajadores_list();
				$actividad->set('Operacion','Editar');
			}

			$actividad->dbStore();

			foreach($this as $key => $value)

				if(isset($this->$key))
				{

					if($key == 'ISRasalariados')
					{
						$rows = explode('<tr>',$this->$key);
						$rows_len = count($rows);

						for($i=3; $i<$rows_len; $i++)
						{
							//echo "$i:";
							$row = str_replace('</tr>','',$rows[$i]);
							$cols = explode('<td>',$row);
							$cols_len = count($cols);

							for($j=5; $j<$cols_len; $j++)
							{
								$col = str_replace('</td>','',$cols[$j]);
								$values = explode('<',$col);
								$register[$j-5] = $values[0];
								//echo urlencode($register[$j-5]);
							}

							//echo '<br/>';
							$result = $this->conn->query("SELECT Trabajador FROM ISRasalariados WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

							if($this->conn->num_rows($result) == 0)
								$this->conn->query("INSERT INTO ISRasalariados(Trabajador, Nomina, Cuenta) VALUES('{$register[0]}', '{$this->id}', '{$_SESSION['cuenta']}')");

							$this->conn->freeResult($result);
							$result = $this->conn->query("SELECT Status FROM nomina_asalariados WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($status) = $this->conn->fetchRow($result);
							$this->conn->freeResult($result);

							if(!isset($status) || $status != 'Comprobante timbrado satisfactoriamente')
								$this->conn->query("UPDATE ISRasalariados SET Numero_de_dias_del_periodo = '{$register[1]}', Numero_de_dias_previos_al_ingreso = '{$register[2]}', Numero_de_dias_de_baja = '{$register[3]}', Faltas = '{$register[4]}', Numero_de_faltas = '{$register[5]}', Numero_de_dias_de_incapacidad = '{$register[6]}', Numero_de_dias_de_vacaciones = '{$register[7]}', Numero_de_dias_laborados = '{$register[8]}', Salario_diario = '{$register[9]}', Sueldo = '{$register[10]}', Horas_extra = '{$register[11]}', Horas_extra_grabadas = '{$register[12]}', Domingos_laborados = '{$register[13]}', Numero_de_domingos_laborados = '{$register[14]}', Prima_dominical = '{$register[15]}', Prima_dominical_grabada = '{$register[16]}', Dias_de_descanso = '{$register[17]}', Dias_de_descanso_grabados = '{$register[18]}', Premios_de_puntualidad_y_asistencia = '{$register[19]}', Bonos_de_productividad = '{$register[20]}', Estimulos = '{$register[21]}', Compensaciones = '{$register[22]}', Despensa = '{$register[23]}', Comida = '{$register[24]}', Prevision_social_grabada = '{$register[25]}', Base = '{$register[26]}' ,Limite_inferior = '{$register[27]}', Exedente_del_limite_inferior = '{$register[28]}', Porcentaje_sobre_el_exedente_del_limite_inferior = '{$register[29]}', Impuesto_marginal = '{$register[30]}', Cuota_fija = '{$register[31]}', Impuesto_determinado = '{$register[32]}', Subsidio = '{$register[33]}', ISR = '{$register[34]}', Subsidio_al_empleo = '{$register[35]}' WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

						}

					}
					elseif($key == 'ISRasimilables')
					{
						$rows = explode('<tr>',$this->$key);
						$rows_len = count($rows);

						for($i=3; $i<$rows_len; $i++)
						{
							$row = str_replace('</tr>','',$rows[$i]);
							$cols = explode('<td>',$row);
							$cols_len = count($cols);

							for($j=4; $j<$cols_len; $j++)
							{
								$col = str_replace('</td>','',$cols[$j]);
								$values = explode('<',$col);
								$register[$j-4] = $values[0];
								//echo $j-4 . ': ' . urlencode($register[$j-4]) . '<br/>';
							}

							$result = $this->conn->query("SELECT Trabajador FROM ISRasimilables WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

							if($this->conn->num_rows($result) == 0)
								$this->conn->query("INSERT INTO ISRasimilables(Trabajador, Nomina, Cuenta) VALUES('{$register[0]}','{$this->id}', '{$_SESSION['cuenta']}')");

							$this->conn->freeResult($result);
							$result = $this->conn->query("SELECT Status FROM nomina_asimilables WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($status) = $this->conn->fetchRow($result);
							$this->conn->freeResult($result);

							if(!isset($status) || $status != 'Comprobante timbrado satisfactoriamente')
								$this->conn->query("UPDATE ISRasimilables SET Numero_de_dias_del_periodo = '{$register[1]}', Numero_de_dias_previos_al_ingreso = '{$register[2]}', Numero_de_dias_de_baja = '{$register[3]}', Numero_de_dias_laborados = '{$register[4]}', Salario_diario = '{$register[5]}', Honorarios_asimilados = '{$register[6]}', Base = '{$register[7]}', Limite_inferior = '{$register[8]}', Exedente_del_limite_inferior = '{$register[9]}', Porcentaje_sobre_el_exedente_del_limite_inferior = '{$register[10]}', Impuesto_marginal = '{$register[11]}', Cuota_fija = '{$register[12]}', Impuesto_determinado = '{$register[13]}', Subsidio = '{$register[14]}', ISR = '{$register[15]}', Subsidio_al_empleo = '{$register[16]}' WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

						}

					}
					elseif($key == 'cuotas_IMSS')
					{
						$rows = explode('<tr>',$this->$key);
						$rows_len = count($rows);

						for($i=3; $i<$rows_len; $i++)
						{
							$row = str_replace('</tr>','',$rows[$i]);
							$cols = explode('<td>',$row);
							$cols_len = count($cols);

							for($j=3; $j<$cols_len; $j++)
							{
								$col = str_replace('</td>','',$cols[$j]);
								$values = explode('<',$col);
								$register[$j-3] = $values[0];
								//echo $j-3 . ': ' . urlencode($register[$j-3]) . '<br/>';
							}

							$result = $this->conn->query("SELECT Trabajador FROM cuotas_IMSS WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

							if($this->conn->num_rows($result) == 0)
								$this->conn->query("INSERT INTO cuotas_IMSS(Trabajador, Nomina, Cuenta) VALUES('{$register[0]}', '{$this->id}', '{$_SESSION['cuenta']}')");

							$this->conn->freeResult($result);
							$result = $this->conn->query("SELECT Status FROM nomina_asalariados WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($status) = $this->conn->fetchRow($result);
							$this->conn->freeResult($result);

							if(!isset($status) || $status != 'Comprobante timbrado satisfactoriamente')
								$this->conn->query("UPDATE cuotas_IMSS SET Numero_de_dias_del_periodo = {$register[1]},Numero_de_dias_previos_al_ingreso = {$register[2]},Numero_de_dias_de_baja = {$register[3]},Numero_de_dias_de_incapacidad = {$register[4]},Numero_de_dias_cotizados = {$register[5]},Anos_de_antiguedad = '{$register[6]}',Dias_de_vacaciones = {$register[7]},Factor_de_prima_vacacional = '{$register[8]}',Factor_de_aguinaldo = {$register[9]},Factor_de_integracion = '{$register[10]}',Salario_diario_integrado = '{$register[11]}',Sueldo_integrado = '{$register[12]}',Cuota_fija_IMSS = '{$register[13]}',Exedente_3_SMGDF_patronal = '{$register[14]}',Exedente_3_SMGDF_obrero = '{$register[15]}',Prestaciones_en_dinero_obreras = '{$register[16]}',Prestaciones_en_dinero_patronales = '{$register[17]}',Gastos_medicos_y_pension_obreros = '{$register[18]}',Gastos_medicos_y_pension_patronales = '{$register[19]}',Invalidez_y_vida_obrera = '{$register[20]}',Invalidez_y_vida_patronal = '{$register[21]}',Cesantia_y_vejez_obrera = '{$register[22]}',Cesantia_y_vejez_patronal = '{$register[23]}',Guarderia = '{$register[24]}',Retiro = '{$register[25]}',INFONAVIT = '{$register[26]}',Riesgo_de_trabajo = '{$register[27]}',Total_de_cuotas_IMSS_obreras = '{$register[28]}',Total_de_cuotas_IMSS_patronales = '{$register[29]}',Adeudo = '{$register[30]}' WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

						}

					}
					elseif($key == 'prestaciones')
					{
						$rows = explode('<tr>',$this->$key);
						$rows_len = count($rows);

						for($i=3; $i<$rows_len; $i++)
						{
							$row = str_replace('</tr>','',$rows[$i]);
							$cols = explode('<td>',$row);
							$cols_len = count($cols);

							for($j=5; $j<$cols_len; $j++)
							{
								$col = str_replace('</td>','',$cols[$j]);
								$values = explode('<',$col);
								$register[$j-5] = $values[0];
								//echo $j-5 . ': ' . urlencode($register[$j-5]) . '<br/>';
							}

							$result = $this->conn->query("SELECT Trabajador FROM prestaciones WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

							if($this->conn->num_rows($result) == 0)
								$this->conn->query("INSERT INTO prestaciones(Trabajador, Nomina, Cuenta) VALUES('{$register[0]}','{$this->id}', '{$_SESSION['cuenta']}')");

							$this->conn->freeResult($result);
							$result = $this->conn->query("SELECT Status FROM nomina_asalariados WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($status) = $this->conn->fetchRow($result);
							$this->conn->freeResult($result);

							if(!isset($status) || $status != 'Comprobante timbrado satisfactoriamente')
								$this->conn->query("UPDATE prestaciones SET Numero_de_dias = '{$register[1]}',Anos_de_antiguedad = {$register[2]},Sueldo = '{$register[3]}',Vacaciones = {$register[4]},Retencion_proporcional_de_vacaciones = {$register[5]},Prima_vacacional = '{$register[6]}',Retencion_proporcional_de_prima_vacacional = {$register[7]},Aguinaldo = {$register[8]},Retencion_proporcional_de_aguinaldo = {$register[9]},Prima_de_antiguedad = '{$register[10]}',Retencion_proporcional_de_prima_de_antiguedad = {$register[11]},Total_de_prestaciones = {$register[12]},Total_de_retenciones = {$register[13]} WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

						}

					}
					elseif($key == 'nomina_asalariados')
					{
						$rows = explode('<tr>',$this->$key);
						$rows_len = count($rows);

						for($i=3; $i<$rows_len; $i++)
						{
							$row = str_replace('</tr>','',$rows[$i]);
							$cols = explode('<td>',$row);
							$cols_len = count($cols);
							$id = 0;//For descuento pendiente

							for($j=5; $j<$cols_len; $j++)
							{
								$col = str_replace('</td>','',$cols[$j]);
								$values = explode('</tbody>',$col);
								//echo urlencode($values[0]) . '<br/>';
								$register[$j-5] = $values[0];

								if($j == 5)
								{
									$result = $this->conn->query("SELECT Status FROM nomina_asalariados WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
									list($status) = $this->conn->fetchRow($result);
									$this->conn->freeResult($result);
								}

								if((!isset($status) || $status != 'Comprobante timbrado satisfactoriamente') && ($j==27 || $j==28 || $j==30 || $j==39 || $j==40 || $j==41 || $j==42 || $j==43))
								{//update descuentos pendientes
									$rets = explode(',',$register[$j-5]);
									$rets_len = count($rets);

									for($k=0; $k<$rets_len; $k++)
									{
										$ret = explode('</span>',$rets[$k]);

										if(count($ret) > 1)
										{
											//echo urlencode($ret[0]) . '<br/>';
											preg_match('/\d+/', $ret[0], $matches);
											//$id = str_replace('<span style = "visibility:hidden">','',$ret[0]);
											$id = $matches[0];
											$_body = str_replace('<span>','',$ret[1]);
											$body = explode('/',$_body);
											$body_len = count($body);

											if($body_len == 2)
											{
												$partial = $body[0];
												$total = $body[1];
												$pending = $total - $partial;

												if($j == 27)
													$r = 'Retención INFONAVIT';
												elseif($j == 28)
													$r = 'Retención FONACOT';
												elseif($j == 30)
													$r = 'Pensión alimenticia';
												elseif($j == 39)
													$r = 'Pago por seguro de vida';
												elseif($j == 40)
													$r = 'Préstamo del fondo de ahorro';
												elseif($j == 41)
													$r = 'Préstamo caja';
												elseif($j == 42)
													$r = 'Préstamo cliente';
												elseif($j == 43)
													$r = 'Préstamo administradora';

												$result = $this->conn->query("SELECT Cantidad FROM Descuento_pendiente WHERE Nomina = '{$this->id}' AND Retencion = '$r' AND id = '$id' AND Trabajador = '{$register[0]}' AND Cuenta = '{$_SESSION['cuenta']}'");

												if($this->conn->num_rows($result) == 0)
													$q = "INSERT INTO Descuento_pendiente(Nomina, Retencion, id, Trabajador, Cuenta, Cantidad) VALUES('{$this->id}', '$r', '$id', '{$register[0]}', '{$_SESSION['cuenta']}', $pending)";
												elseif($pending > 0)
													$q = "UPDATE Descuento_pendiente SET Cantidad = $pending WHERE Nomina = '{$this->id}' AND Retencion = '$r' AND id = '$id' AND Trabajador = '{$register[0]}' AND Cuenta = '{$_SESSION['cuenta']}'";

												$this->conn->query($q);
											}
											else
											{

												if($j == 27)
													$r = 'Retención INFONAVIT';
												elseif($j == 28)
													$r = 'Retención FONACOT';
												elseif($j == 30)
													$r = 'Pensión alimenticia';
												elseif($j == 39)
													$r = 'Pago por seguro de vida';
												elseif($j == 40)
													$r = 'Préstamo del fondo de ahorro';
												elseif($j == 41)
													$r = 'Préstamo caja';
												elseif($j == 42)
													$r = 'Préstamo cliente';
												elseif($j == 43)
													$r = 'Préstamo administradora';

												$result = $this->conn->query("SELECT Cantidad FROM Descuento_pendiente WHERE Nomina = '{$this->id}' AND Retencion = '$r' AND id = '$id' AND Trabajador = '{$register[0]}' AND Cuenta = '{$_SESSION['cuenta']}'");

												if($this->conn->num_rows($result) > 0)
													$this->conn->query("DELETE FROM Descuento_pendiente WHERE Nomina = '{$this->id}' AND Retencion = '$r' AND id = '$id' AND Trabajador = '{$register[0]}' AND Cuenta = '{$_SESSION['cuenta']}'");

											}

										}

									}

								}
								elseif((!isset($status) || $status != 'Comprobante timbrado satisfactoriamente') && ($j==24 || $j==32 || $j==33))
								{//update descuentos pendientes for cuotas IMSS, Prestaciones & Gestión adminsitrativa
									//echo urlencode($register[$j-5]) . '<br/>';
									$body = explode('/',$register[$j-5]);
									$body_len = count($body);

									if($body_len == 2)
									{
										$partial = $body[0];
										$total = $body[1];
										$pending = $total - $partial;

										if($j == 24)
											$r = 'Cuotas IMSS';
										elseif($j == 32)
											$r = 'Prestaciones';
										elseif($j == 33)
											$r = 'Gestión administrativa';

										$result = $this->conn->query("SELECT Cantidad FROM Descuento_pendiente WHERE Nomina = '{$this->id}' AND Retencion = '$r' AND id = '$id' AND Trabajador = '{$register[0]}' AND Cuenta = '{$_SESSION['cuenta']}'");

										if($this->conn->num_rows($result) == 0)
											$q = "INSERT INTO Descuento_pendiente(Nomina, Retencion, id, Trabajador, Cuenta, Cantidad) VALUES('{$this->id}', '$r', '$id', '{$register[0]}', '{$_SESSION['cuenta']}', $pending)";
										elseif($pending > 0)
											$q = "UPDATE Descuento_pendiente SET Cantidad = $pending WHERE Nomina = '{$this->id}' AND Retencion = '$r' AND id = '$id' AND Trabajador = '{$register[0]}' AND Cuenta = '{$_SESSION['cuenta']}'";

										$this->conn->query($q);
									}
									else
									{

										if($j == 24)
											$r = 'Cuotas IMSS';
										elseif($j == 32)
											$r = 'Prestaciones';
										elseif($j == 33)
											$r = 'Gestión administrativa';

										$result = $this->conn->query("SELECT Cantidad FROM Descuento_pendiente WHERE Nomina = '{$this->id}' AND Retencion = '$r' AND id = '$id' AND Trabajador = '{$register[0]}' AND Cuenta = '{$_SESSION['cuenta']}'");

										if($this->conn->num_rows($result) > 0)
											$this->conn->query("DELETE FROM Descuento_pendiente WHERE Nomina = '{$this->id}' AND Retencion = '$r' AND id = '$id' AND Trabajador = '{$register[0]}' AND Cuenta = '{$_SESSION['cuenta']}'");

									}

								}

								$register[$j-5] = ($register[$j-5] == '' && $j != 36) ? 0.00 : $register[$j-5];//36 is "pago_neto" and has to mark the difference between 0.00 and ''
							}

							$result = $this->conn->query("SELECT Trabajador FROM nomina_asalariados WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

							if($this->conn->num_rows($result) == 0)
								$this->conn->query("INSERT INTO nomina_asalariados(Trabajador, Nomina, Cuenta) VALUES('{$register[0]}', '{$this->id}', '{$_SESSION['cuenta']}')");

							if(!isset($status) || $status != 'Comprobante timbrado satisfactoriamente')
								$this->conn->query("UPDATE nomina_asalariados SET Numero_de_dias_laborados = '{$register[1]}',Salario = '{$register[2]}',Sueldo = {$register[3]},Subsidio_al_empleo = '{$register[4]}',Horas_extra = {$register[5]},Prima_dominical = '{$register[6]}',Dias_de_descanso = {$register[7]},Premios_de_puntualidad_y_asistencia = '{$register[8]}',Bonos_de_productividad = {$register[9]},Estimulos = '{$register[10]}',Compensaciones = '{$register[11]}',Despensa = '{$register[12]}',Comida = '{$register[13]}',Alimentacion = {$register[14]},Habitacion = '{$register[15]}',Aportacion_patronal_al_fondo_de_ahorro = '{$register[16]}',Total_de_percepciones = {$register[17]},ISR = {$register[18]},Cuotas_IMSS = '{$register[19]}',Retencion_por_alimentacion = {$register[20]},Retencion_por_habitacion = {$register[21]},Retencion_INFONAVIT = '{$register[22]}',Retencion_FONACOT = '{$register[23]}',Aportacion_del_trabajador_al_fondo_de_ahorro = '{$register[24]}',Pension_alimenticia = '{$register[25]}',Retardos = '{$register[26]}',Prestaciones = '{$register[27]}',Gestion_administrativa = '{$register[28]}',Total_de_deducciones = {$register[29]},Saldo = {$register[30]},Pago_neto = '{$register[31]}',Pago_liquido = '{$register[32]}',Fondo_de_garantia = '{$register[33]}',Pago_por_seguro_de_vida = '{$register[34]}',Prestamo_del_fondo_de_ahorro = '{$register[35]}',Prestamo_caja = '{$register[36]}',Prestamo_cliente = '{$register[37]}',Prestamo_administradora = '{$register[38]}',Neto_a_recibir = {$register[39]},Forma_de_pago = '{$register[40]}',Status = '{$register[41]}' WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
						}

					}
					elseif($key == 'nomina_asimilables')
					{
						$rows = explode('<tr>',$this->$key);
						$rows_len = count($rows);

						for($i=3; $i<$rows_len; $i++)
						{
							$row = str_replace('</tr>','',$rows[$i]);
							$cols = explode('<td>',$row);
							$cols_len = count($cols);
							$id = 0;//For descuento pendiente

							for($j=4; $j<$cols_len; $j++)
							{
								$col = str_replace('</td>','',$cols[$j]);
								$values = explode('</tbody>',$col);
								//echo $j-4 . ': ' . urlencode($values[0]) . '<br/>';
								$register[$j-4] = $values[0];

								if($j == 4)
								{
									$result = $this->conn->query("SELECT Status FROM nomina_asimilables WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
									list($status) = $this->conn->fetchRow($result);
									$this->conn->freeResult($result);
								}

								if((!isset($status) || $status != 'Comprobante timbrado satisfactoriamente') && ($j==14 || $j==15 || $j==16 || $j==17))
								{//update descuentos pendientes
									$rets = explode(',',$register[$j-4]);
									$rets_len = count($rets);

									for($k=0; $k<$rets_len; $k++)
									{
										$ret = explode('</span>',$rets[$k]);

										if(count($ret) > 1)
										{
											//echo urlencode($ret[0]) . '<br/>';
											preg_match('/\d+/', $ret[0], $matches);
											//$id = str_replace('<span style = "visibility:hidden">','',$ret[0]);
											$id = $matches[0];
											$_body = str_replace('<span>','',$ret[1]);
											$body = explode('/',$_body);
											$body_len = count($body);

											if($body_len == 2)
											{
												$partial = $body[0];
												$total = $body[1];
												$pending = $total - $partial;

												if($j == 14)
													$r = 'Pago por seguro de vida';
												elseif($j == 15)
													$r = 'Préstamo caja';
												elseif($j == 16)
													$r = 'Préstamo cliente';
												elseif($j == 17)
													$r = 'Préstamo administradora';

												$result = $this->conn->query("SELECT Cantidad FROM Descuento_pendiente WHERE Nomina = '{$this->id}' AND Retencion = '$r' AND id = '$id' AND Trabajador = '{$register[0]}' AND Cuenta = '{$_SESSION['cuenta']}'");

												if($this->conn->num_rows($result) == 0)
													$q = "INSERT INTO Descuento_pendiente(Nomina, Retencion, id, Trabajador, Cuenta, Cantidad) VALUES('{$this->id}', '$r', '$id', '{$register[0]}', '{$_SESSION['cuenta']}', $pending)";
												elseif($pending > 0)
													$q = "UPDATE Descuento_pendiente SET Cantidad = $pending WHERE Nomina = '{$this->id}' AND Retencion = '$r' AND id = '$id' AND Trabajador = '{$register[0]}' AND Cuenta = '{$_SESSION['cuenta']}'";

												$this->conn->query($q);
											}
											else
											{

												if($j == 14)
													$r = 'Pago por seguro de vida';
												elseif($j == 15)
													$r = 'Préstamo caja';
												elseif($j == 16)
													$r = 'Préstamo cliente';
												elseif($j == 17)
													$r = 'Préstamo administradora';

												$result = $this->conn->query("SELECT Cantidad FROM Descuento_pendiente WHERE Nomina = '{$this->id}' AND Retencion = '$r' AND id = '$id' AND Trabajador = '{$register[0]}' AND Cuenta = '{$_SESSION['cuenta']}'");

												if($this->conn->num_rows($result) > 0)
													$this->conn->query("DELETE FROM Descuento_pendiente WHERE Nomina = '{$this->id}' AND Retencion = '$r' AND id = '$id' AND Trabajador = '{$register[0]}' AND Cuenta = '{$_SESSION['cuenta']}'");

											}

										}

									}

								}

								$register[$j-4] = ($register[$j-4] == '') ? 0.00 : $register[$j-4];
							}

							$result = $this->conn->query("SELECT Trabajador FROM nomina_asimilables WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

							if($this->conn->num_rows($result) == 0)
								$this->conn->query("INSERT INTO nomina_asimilables(Trabajador, Nomina, Cuenta) VALUES('{$register[0]}', '{$this->id}', '{$_SESSION['cuenta']}')");

							if(!isset($status) || $status != 'Comprobante timbrado satisfactoriamente')
								$this->conn->query("UPDATE nomina_asimilables SET Numero_de_dias_del_periodo = '{$register[1]}',Honorarios_asimilados = {$register[2]},Total_de_percepciones = {$register[3]},ISR = {$register[4]},Gestion_administrativa = {$register[5]},Total_de_deducciones = {$register[6]},Saldo = {$register[7]},Pago_neto = {$register[8]},Pago_liquido = {$register[9]},Pago_por_seguro_de_vida = '{$register[10]}',Prestamo_caja = '{$register[11]}',Prestamo_cliente = '{$register[12]}',Prestamo_administradora = '{$register[13]}',Neto_a_recibir = {$register[14]},Forma_de_pago = '{$register[15]}',Status = '{$register[16]}' WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

						}

					}
					elseif($key == 'incidencias')
					{
						$rows = explode('<tr>',$this->$key);
						$rows_len = count($rows);

						for($i=3; $i<$rows_len; $i++)
						{
							$row = str_replace('</tr>','',$rows[$i]);
							$cols = explode('<td>',$row);
							$cols_len = count($cols);

							for($j=1; $j<$cols_len; $j++)
							{
								$col = str_replace('</td>','',$cols[$j]);
								$values = explode('<',$col);
								$register[$j - 1] = $values[0];
								//echo ($j-1) . ': ' . urlencode($register[$j - 1]) . '<br/>';
							}

							$result = $this->conn->query("SELECT Trabajador FROM incidencias WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

							if($this->conn->num_rows($result) == 0)
								$this->conn->query("INSERT INTO incidencias(Trabajador, Nomina, Cuenta) VALUES('{$register[0]}', '{$this->id}', '{$_SESSION['cuenta']}')");

							$this->conn->query("UPDATE incidencias SET Pago_neto = '{$register[1]}', Pago_liquido = '{$register[2]}', Diferencia = '{$register[3]}' WHERE Trabajador = '{$register[0]}' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
						}

					}
					elseif($key == 'Resumen')
					{
						$this->calculate_resumen();
						$register = explode(',',$this->Resumen);
						$result = $this->conn->query("SELECT Nomina FROM Resumen WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

						if($this->conn->num_rows($result) == 0)
							$this->conn->query("INSERT INTO Resumen(Nomina, Cuenta) VALUES('{$this->id}', '{$_SESSION['cuenta']}')");

						$this->conn->query("UPDATE Resumen SET Sueldo_asalariados = '{$register[0]}',Sueldo_asimilables = '{$register[1]}',Total_de_sueldo = '{$register[2]}',Subsidio_al_empleo = '{$register[3]}',Horas_extra_asalariados = '{$register[4]}',Prima_dominical = '{$register[5]}',Dias_de_descanso = '{$register[6]}',Premios_de_puntualidad_y_asistencia = '{$register[7]}',Bonos_de_productividad = '{$register[8]}',Estimulos_asalariados = '{$register[9]}',Compensaciones = '{$register[10]}',Despensa = '{$register[11]}',Comida = '{$register[12]}',Alimentacion = '{$register[13]}',Habitacion = '{$register[14]}',Aportacion_patronal_al_fondo_de_ahorro = '{$register[15]}',Total_de_percepciones_asalariados = '{$register[16]}',Total_de_percepciones_asimilables = '{$register[17]}',Total_de_percepciones = '{$register[18]}',ISR_asalariados = '{$register[19]}',Contribuciones_ISR_asalariados = '{$register[20]}',ISR_asimilables = '{$register[21]}',Total_ISR = '{$register[22]}',Cuotas_IMSS_obreras = '{$register[23]}',Cesantia_y_vejez_obrera = '{$register[24]}',Retencion_por_alimentacion = '{$register[25]}',Retencion_por_habitacion = '{$register[26]}',Retencion_INFONAVIT = '{$register[27]}',Retencion_FONACOT = '{$register[28]}',Aportacion_del_trabajador_al_fondo_de_ahorro = '{$register[29]}',Pension_alimenticia = '{$register[30]}',Retardos = '{$register[31]}',Prestaciones = '{$register[32]}',Gestion_administrativa_asalariados = '{$register[33]}',Gestion_administrativa_asimilables = '{$register[34]}',Gestion_administrativa = '{$register[35]}',Total_de_deducciones_asalariados = '{$register[36]}',Total_de_deducciones_asimilables = '{$register[37]}',Total_de_deducciones = '{$register[38]}',Saldo_asalariados = '{$register[39]}',Saldo_asimilables = '{$register[40]}',Total_saldo = '{$register[41]}',Vacaciones = '{$register[42]}',Prima_vacacional = '{$register[43]}',Aguinaldo = '{$register[44]}',Prima_de_antiguedad = '{$register[45]}',Total_prestaciones = '{$register[46]}',Fondo_de_garantia = '{$register[47]}',Pago_por_seguro_de_vida_asalariados = '{$register[48]}',Pago_por_seguro_de_vida_asimilables = '{$register[49]}',Total_pago_por_seguro_de_vida = '{$register[50]}',Prestamo_del_fondo_de_ahorro = '{$register[51]}',Prestamo_caja_asalariados = '{$register[52]}',Prestamo_caja_asimilables = '{$register[53]}',Total_prestamo_caja = '{$register[54]}',Prestamo_cliente_asalariados = '{$register[55]}',Prestamo_cliente_asimilables = '{$register[56]}',Total_prestamo_cliente = '{$register[57]}',Prestamo_administradora_asalariados = '{$register[58]}',Prestamo_administradora_asimilables = '{$register[59]}',Total_prestamo_administradora = '{$register[60]}',Total_otros_asalariados = '{$register[61]}',Total_otros_asimilables = '{$register[62]}',Total_otros = '{$register[63]}',Neto_a_recibir_asalariados = '{$register[64]}',Neto_a_recibir_asimilables = '{$register[65]}',Total_neto_a_recibir = '{$register[66]}',CIO = '{$register[67]}',CISM = '{$register[68]}',CIP = '{$register[69]}',Adeudo_IMSS = '{$register[70]}',Impuesto_sobre_nomina_asalariados = '{$register[71]}',Impuesto_sobre_nomina_asimilables = '{$register[72]}',Total_de_impuesto_sobre_nomina = '{$register[73]}',INFONAVIT = '{$register[74]}',Retiro = '{$register[75]}',CAV_Obrera = '{$register[76]}',CAVSM = '{$register[77]}',CAV_Patronal = '{$register[78]}',Total_de_impuestos_asalariados = '{$register[79]}',Total_de_impuestos_asimilables = '{$register[80]}',Total_de_impuestos = '{$register[81]}',Diferencias = '{$register[82]}',Honorarios_asalariados = '{$register[83]}',Honorarios_asimilables = '{$register[84]}',Honorarios = '{$register[85]}',Subtotal_a_facturar_asalariados = '{$register[86]}',Subtotal_a_facturar_asimilables = '{$register[87]}',Subtotal_a_facturar = '{$register[88]}',iva_asalariados = '{$register[89]}',iva_asimilables = '{$register[90]}',iva = '{$register[91]}',Total_a_facturar_asalariados = '{$register[92]}',Total_a_facturar_asimilables = '{$register[93]}',Total_a_facturar = '{$register[94]}' WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
					}
					elseif($key == 'Servicio')
					{
						$data = explode('/',$this->Servicio);
						$this->conn->query("UPDATE Nomina SET $key = {$data[0]} WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
					}
					elseif($key == 'id' || $key == 'Etapa' || $key == 'Limite_inferior_del_periodo' || $key == 'Limite_superior_del_periodo' || $key == 'Fecha_de_pago')
						$this->conn->query("UPDATE Nomina SET $key  = '$value' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

				}
				elseif($key == 'Resumen')
				{
					$this->calculate_resumen();
					$register = explode(',',$this->Resumen);
					$result = $this->conn->query("SELECT Nomina FROM Resumen WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

					if($this->conn->num_rows($result) == 0)
						$this->conn->query("INSERT INTO Resumen(Nomina, Cuenta) VALUES('{$this->id}', '{$_SESSION['cuenta']}')");

					$this->conn->query("UPDATE Resumen SET Sueldo_asalariados = '{$register[0]}',Sueldo_asimilables = '{$register[1]}',Total_de_sueldo = '{$register[2]}',Subsidio_al_empleo = '{$register[3]}',Horas_extra_asalariados = '{$register[4]}',Prima_dominical = '{$register[5]}',Dias_de_descanso = '{$register[6]}',Premios_de_puntualidad_y_asistencia = '{$register[7]}',Bonos_de_productividad = '{$register[8]}',Estimulos_asalariados = '{$register[9]}',Compensaciones = '{$register[10]}',Despensa = '{$register[11]}',Comida = '{$register[12]}',Alimentacion = '{$register[13]}',Habitacion = '{$register[14]}',Aportacion_patronal_al_fondo_de_ahorro = '{$register[15]}',Total_de_percepciones_asalariados = '{$register[16]}',Total_de_percepciones_asimilables = '{$register[17]}',Total_de_percepciones = '{$register[18]}',ISR_asalariados = '{$register[19]}',Contribuciones_ISR_asalariados = '{$register[20]}',ISR_asimilables = '{$register[21]}',Total_ISR = '{$register[22]}',Cuotas_IMSS_obreras = '{$register[23]}',Cesantia_y_vejez_obrera = '{$register[24]}',Retencion_por_alimentacion = '{$register[25]}',Retencion_por_habitacion = '{$register[26]}',Retencion_INFONAVIT = '{$register[27]}',Retencion_FONACOT = '{$register[28]}',Aportacion_del_trabajador_al_fondo_de_ahorro = '{$register[29]}',Pension_alimenticia = '{$register[30]}',Retardos = '{$register[31]}',Prestaciones = '{$register[32]}',Gestion_administrativa_asalariados = '{$register[33]}',Gestion_administrativa_asimilables = '{$register[34]}',Gestion_administrativa = '{$register[35]}',Total_de_deducciones_asalariados = '{$register[36]}',Total_de_deducciones_asimilables = '{$register[37]}',Total_de_deducciones = '{$register[38]}',Saldo_asalariados = '{$register[39]}',Saldo_asimilables = '{$register[40]}',Total_saldo = '{$register[41]}',Vacaciones = '{$register[42]}',Prima_vacacional = '{$register[43]}',Aguinaldo = '{$register[44]}',Prima_de_antiguedad = '{$register[45]}',Total_prestaciones = '{$register[46]}',Fondo_de_garantia = '{$register[47]}',Pago_por_seguro_de_vida_asalariados = '{$register[48]}',Pago_por_seguro_de_vida_asimilables = '{$register[49]}',Total_pago_por_seguro_de_vida = '{$register[50]}',Prestamo_del_fondo_de_ahorro = '{$register[51]}',Prestamo_caja_asalariados = '{$register[52]}',Prestamo_caja_asimilables = '{$register[53]}',Total_prestamo_caja = '{$register[54]}',Prestamo_cliente_asalariados = '{$register[55]}',Prestamo_cliente_asimilables = '{$register[56]}',Total_prestamo_cliente = '{$register[57]}',Prestamo_administradora_asalariados = '{$register[58]}',Prestamo_administradora_asimilables = '{$register[59]}',Total_prestamo_administradora = '{$register[60]}',Total_otros_asalariados = '{$register[61]}',Total_otros_asimilables = '{$register[62]}',Total_otros = '{$register[63]}',Neto_a_recibir_asalariados = '{$register[64]}',Neto_a_recibir_asimilables = '{$register[65]}',Total_neto_a_recibir = '{$register[66]}',CIO = '{$register[67]}',CISM = '{$register[68]}',CIP = '{$register[69]}',Adeudo_IMSS = '{$register[70]}',Impuesto_sobre_nomina_asalariados = '{$register[71]}',Impuesto_sobre_nomina_asimilables = '{$register[72]}',Total_de_impuesto_sobre_nomina = '{$register[73]}',INFONAVIT = '{$register[74]}',Retiro = '{$register[75]}',CAV_Obrera = '{$register[76]}',CAVSM = '{$register[77]}',CAV_Patronal = '{$register[78]}',Total_de_impuestos_asalariados = '{$register[79]}',Total_de_impuestos_asimilables = '{$register[80]}',Total_de_impuestos = '{$register[81]}',Diferencias = '{$register[82]}',Honorarios_asalariados = '{$register[83]}',Honorarios_asimilables = '{$register[84]}',Honorarios = '{$register[85]}',Subtotal_a_facturar_asalariados = '{$register[86]}',Subtotal_a_facturar_asimilables = '{$register[87]}',Subtotal_a_facturar = '{$register[88]}',iva_asalariados = '{$register[89]}',iva_asimilables = '{$register[90]}',iva = '{$register[91]}',Total_a_facturar_asalariados = '{$register[92]}',Total_a_facturar_asimilables = '{$register[93]}',Total_a_facturar = '{$register[94]}' WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
				}

			return true;
		}

		private function update_trabajadores_list()
		{
			//updating ISRasalariados
			$rows = explode('<tr>',$this->ISRasalariados);
			$rows_len = count($rows);
			$trabajadores_ISRasalariados = array();

			for($i=3; $i<$rows_len; $i++)
			{
				$cols = explode('<td>',$rows[$i]);
				$value = str_replace('</td>','',$cols[5]);
				$trabajadores_ISRasalariados[$i - 3] = $value;
			}

			$trabajadores_len = count($trabajadores_ISRasalariados);
			$result = $this->conn->query("SELECT Trabajador FROM ISRasalariados WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($_trabajador) = $this->conn->fetchRow($result))
			{
				$del = true;

				for($i=0; $i<$trabajadores_len; $i++)

					if($_trabajador == $trabajadores_ISRasalariados)
					{
						$del = false;
						break;
					}

				if($del)
					$this->conn->query("DELETE FROM ISRasalariados WHERE Trabajador = '$_trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			}

			//updating ISRasimilables
			$rows = explode('<tr>',$this->ISRasimilables);
			$rows_len = count($rows);
			$trabajadores_ISRasimilables = array();

			for($i=3; $i<$rows_len; $i++)
			{
				$cols = explode('<td>',$rows[$i]);
				$value = str_replace('</td>','',$cols[4]);
				$trabajadores_ISRasimilables[$i - 3] = $value;
			}

			$trabajadores_len = count($trabajadores_ISRasimilables);
			$result = $this->conn->query("SELECT Trabajador FROM ISRasimilables WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($_trabajador) = $this->conn->fetchRow($result))
			{
				$del = true;

				for($i=0; $i<$trabajadores_len; $i++)

					if($_trabajador == $trabajadores_ISRasimilables)
					{
						$del = false;
						break;
					}

				if($del)
					$this->conn->query("DELETE FROM ISRasimilables WHERE Trabajador = '$_trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			}

			//updating cuotas_IMSS
			$rows = explode('<tr>',$this->cuotas_IMSS);
			$rows_len = count($rows);
			$trabajadores_cuotas_IMSS = array();

			for($i=3; $i<$rows_len; $i++)
			{
				$cols = explode('<td>',$rows[$i]);
				$value = str_replace('</td>','',$cols[5]);
				$trabajadores_cuotas_IMSS[$i - 3] = $value;
			}

			$trabajadores_len = count($trabajadores_cuotas_IMSS);
			$result = $this->conn->query("SELECT Trabajador FROM cuotas_IMSS WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($_trabajador) = $this->conn->fetchRow($result))
			{
				$del = true;

				for($i=0; $i<$trabajadores_len; $i++)

					if($_trabajador == $trabajadores_cuotas_IMSS)
					{
						$del = false;
						break;
					}

				if($del)
					$this->conn->query("DELETE FROM cuotas_IMSS WHERE Trabajador = '$_trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			}

			//updating prestaciones
			$rows = explode('<tr>',$this->prestaciones);
			$rows_len = count($rows);
			$trabajadores_prestaciones = array();

			for($i=3; $i<$rows_len; $i++)
			{
				$cols = explode('<td>',$rows[$i]);
				$value = str_replace('</td>','',$cols[5]);
				$trabajadores_prestaciones[$i - 3] = $value;
			}

			$trabajadores_len = count($trabajadores_prestaciones);
			$result = $this->conn->query("SELECT Trabajador FROM prestaciones WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($_trabajador) = $this->conn->fetchRow($result))
			{
				$del = true;

				for($i=0; $i<$trabajadores_len; $i++)

					if($_trabajador == $trabajadores_prestaciones)
					{
						$del = false;
						break;
					}

				if($del)
					$this->conn->query("DELETE FROM prestaciones WHERE Trabajador = '$_trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			}

			//updating nomina_asalariados
			$rows = explode('<tr>',$this->nomina_asalariados);
			$rows_len = count($rows);
			$trabajadores_nomina_asalariados = array();

			for($i=3; $i<$rows_len; $i++)
			{
				$cols = explode('<td>',$rows[$i]);
				$value = str_replace('</td>','',$cols[5]);
				$trabajadores_nomina_asalariados[$i - 3] = $value;
			}

			$trabajadores_len = count($trabajadores_nomina_asalariados);
			$result = $this->conn->query("SELECT Trabajador FROM nomina_asalariados WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($_trabajador) = $this->conn->fetchRow($result))
			{
				$del = true;

				for($i=0; $i<$trabajadores_len; $i++)

					if($_trabajador == $trabajadores_nomina_asalariados)
					{
						$del = false;
						break;
					}

				if($del)
				{
					$this->conn->query("DELETE FROM nomina_asalariados WHERE Trabajador = '$_trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
					$this->conn->query("DELETE FROM Descuento_pendiente WHERE Trabajador = '$_trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
				}

			}

			//updating nomina_asimilables
			$rows = explode('<tr>',$this->nomina_asimilables);
			$rows_len = count($rows);
			$trabajadores_nomina_asimilables = array();

			for($i=3; $i<$rows_len; $i++)
			{
				$cols = explode('<td>',$rows[$i]);
				$value = str_replace('</td>','',$cols[4]);
				$trabajadores_nomina_asimilables[$i - 3] = $value;
			}

			$trabajadores_len = count($trabajadores_nomina_asimilables);
			$result = $this->conn->query("SELECT Trabajador FROM nomina_asimilables WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($_trabajador) = $this->conn->fetchRow($result))
			{
				$del = true;

				for($i=0; $i<$trabajadores_len; $i++)

					if($_trabajador == $trabajadores_nomina_asimilables)
					{
						$del = false;
						break;
					}

				if($del)
				{
					$this->conn->query("DELETE FROM nomina_asimilables WHERE Trabajador = '$_trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
					$this->conn->query("DELETE FROM Descuento_pendiente WHERE Trabajador = '$_trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
				}

			}

			//updating incidencias
			$rows = explode('<tr>',$this->incidencias);
			$rows_len = count($rows);
			$trabajadores_incidencias = array();

			for($i=3; $i<$rows_len; $i++)
			{
				$cols = explode('<td>',$rows[$i]);
				$value = str_replace('</td>','',$cols[0]);
				$trabajadores_incidencias[$i - 3] = $value;
			}

			$trabajadores_len = count($trabajadores_incidencias);
			$result = $this->conn->query("SELECT Trabajador FROM incidencias WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($_trabajador) = $this->conn->fetchRow($result))
			{
				$del = true;

				for($i=0; $i<$trabajadores_len; $i++)

					if($_trabajador == $trabajadores_incidencias)
					{
						$del = false;
						break;
					}

				if($del)
					$this->conn->query("DELETE FROM incidencias WHERE Trabajador = '$_trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			}

		}

		public function calculate_ISR_trabajadores_asalariados()
		{

			if(isset($this->id) && isset($this->Servicio))
			{
				$this->ISRasalariados = '<table id="ISRasalariados"><tr><td colspan = "40" class = "title">Trabajadores asalariados</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">NSS</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Número de días previos al ingreso</td><td class = "column_title">Número de días de baja</td><td class = "column_title">Faltas</td><td class = "column_title">Número de faltas</td><td class = "column_title">Número de días de incapacidad</td><td class = "column_title">Número de días de vacaciones</td><td class = "column_title">Número de días laborados</td><td class = "column_title">Salario diario</td><td class = "column_title">Sueldo</td><td class = "column_title">Horas extra</td><td class = "column_title">Horas extra gravadas</td><td class = "column_title">Domingos laborados</td><td class = "column_title">Número de domingos laborados</td><td class = "column_title">Prima dominical</td><td class = "column_title">Prima dominical gravada</td><td class = "column_title">Días de descanso</td><td class = "column_title">Días de descanso gravados</td><td class = "column_title">Premios de puntualidad y asistencia</td><td class = "column_title">Bonos de productividad</td><td class = "column_title">Estímulos</td><td class = "column_title">Compensaciones</td><td class = "column_title">Despensa</td><td class = "column_title">Comida</td><td class = "column_title">Previsión social gravada</td><td class = "column_title">Base</td><td class = "column_title">Límite inferior</td><td class = "column_title">Exedente del límite inferior</td><td class = "column_title">Porcentaje sobre el exedente del límite inferior</td><td class = "column_title">Impuesto marginal</td><td class = "column_title">Cuota fija</td><td class = "column_title">Impuesto determinado</td><td class = "column_title">Subsidio</td><td class = "column_title">ISR</td><td class = "column_title">Subsidio al empleo</td></tr>';
				$len = count($this->trabajador);
				$n = 1;

				for($i=0; $i<$len; $i++)
				{
					$tipo = $this->getTipoTrabajador($this->trabajador[$i]);

					if(isset($tipo) && $tipo == 'Asalariado')
					{
						$this->calculate_ISR_trabajador_asalariado($this->trabajador[$i],$n);
						$n ++;
					}

				}

				$this->ISRasalariados .= '</table>';
			}

		}

		public function calculate_ISR_trabajadores_asimilables()
		{

			if(isset($this->id) && isset($this->Servicio))
			{
				$this->ISRasimilables = '<table id="ISRasimilables"><tr><td colspan = "21" class = "title">Asimilables</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Número de días previos al ingreso</td><td class = "column_title">Número de días de baja</td><td class = "column_title">Número de días laborados</td><td class = "column_title">Salario diario</td><td class = "column_title">Honorarios asimilados</td><td class = "column_title">Base</td><td class = "column_title">Límite inferior</td><td class = "column_title">Exedente del límite inferior</td><td class = "column_title">Porcentaje sobre el exedente del límite inferior</td><td class = "column_title">Impuesto marginal</td><td class = "column_title">Cuota fija</td><td class = "column_title">Impuesto determinado</td><td class = "column_title">Subsidio</td><td class = "column_title">ISR</td><td class = "column_title">Subsidio al empleo</td></tr>';
				$len = count($this->trabajador);
				$n = 1;

				for($i=0; $i<$len; $i++)
				{
					$tipo = $this->getTipoTrabajador($this->trabajador[$i]);

					if(isset($tipo) && $tipo == 'Asimilable')
					{
						$this->calculate_ISR_trabajador_asimilable($this->trabajador[$i],$n);
						$n ++;
					}

				}

				$this->ISRasimilables .= '</table>';
			}

		}

		public function calculate_cuotas_IMSS_trabajadores_asalariados()
		{

			if(isset($this->id) && isset($this->Servicio))
			{
				$this->cuotas_IMSS = '<table id="cuotas_IMSS"><tr><td colspan = "42" class = "title">Trabajadores asalariados</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Número de días previos al ingreso</td><td class = "column_title">Número de días de baja</td><td class = "column_title">Número de días de incapacidad</td><td class = "column_title">Número de días cotizados</td><td class = "column_title">Años de antigüedad</td><td class = "column_title">Días de vacaciones</td><td class = "column_title">Factor de prima vacacional</td><td class = "column_title">Factor de aguinaldo</td><td class = "column_title">Factor de integración</td><td class = "column_title">Salario diario integrado</td><td class = "column_title">Sueldo integrado</td><td class = "column_title">Cuota fija IMSS</td><td class = "column_title">Exedente 3 SMGDF patronal</td><td class = "column_title">Exedente 3 SMGDF obrero</td><td class = "column_title">Prestaciones en dinero obreras</td><td class = "column_title">Prestaciones en dinero patronales</td><td class = "column_title">Gastos médicos y pensión obreros</td><td class = "column_title">Gastos médicos y pensión patronales</td><td class = "column_title">Invalidez y vida obrera</td><td class = "column_title">Invalidez y vida patronal</td><td class = "column_title">Cesantía y vejez obrera</td><td class = "column_title">Cesantía y vejez patronal</td><td class = "column_title">Guardería</td><td class = "column_title">Retiro</td><td class = "column_title">INFONAVIT</td><td class = "column_title">Riesgo de trabajo</td><td class = "column_title">Total de cuotas IMSS obreras</td><td class = "column_title">Total de cuotas IMSS patronales</td><td class = "column_title">Adeudo</td></tr>';
				$len = count($this->trabajador);
				$n = 1;

				for($i=0; $i<$len; $i++)
				{
					$tipo = $this->getTipoTrabajador($this->trabajador[$i]);

					if(isset($tipo) && $tipo == 'Asalariado')
					{
						$this->calculate_cuotas_IMSS($this->trabajador[$i],$n);
						$n ++;
					}

				}

				$this->cuotas_IMSS .= '</table>';
			}

		}

		public function calculate_prestaciones_trabajadores_asalariados()
		{

			if(isset($this->id) && isset($this->Servicio))
			{
				$this->prestaciones = '<table id="prestaciones"><tr><td colspan = "18" class = "title">Trabajadores asalariados</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">NSS</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días</td><td class = "column_title">Años de antigüedad</td><td class = "column_title">Sueldo</td><td class = "column_title">Vacaciones</td><td class = "column_title">Retención proporcional de vacaciones</td><td class = "column_title">Prima vacacional</td><td class = "column_title">Retención proporcional de prima vacacional</td><td class = "column_title">Aguinaldo</td><td class = "column_title">Retención proporcional de aguinaldo</td><td class = "column_title">Prima de antigüedad</td><td class = "column_title">Retención proporcional de prima de antigüedad</td><td class = "column_title">Total de prestaciones</td><td class = "column_title">Total de retenciones</td></tr>';
				$len = count($this->trabajador);
				$n = 1;

				for($i=0; $i<$len; $i++)
				{
					$tipo = $this->getTipoTrabajador($this->trabajador[$i]);

					if(isset($tipo) && $tipo == 'Asalariado')
					{
						$this->calculate_prestaciones_trabajador_asalariado($this->trabajador[$i],$n);
						$n ++;
					}

				}

				$this->prestaciones .= '</table>';
			}

		}

		public function calculate_nomina_trabajadores_asalariados()
		{

			if(isset($this->id) && isset($this->Servicio))
			{
				$this->nomina_asalariados = '<table id="nomina_asalariados"><tr><td colspan = "46" class = "title">Trabajadores asalariados</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">NSS</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días laborados</td><td class = "column_title">Salario</td><td class = "column_title">Sueldo</td><td class = "column_title">Subsidio al empleo</td><td class = "column_title">Horas extra</td><td class = "column_title">Prima dominical</td><td class = "column_title">Días de descanso</td><td class = "column_title">Premios de puntualidad y asistencia</td><td class = "column_title">Bonos de productividad</td><td class = "column_title">Estímulos</td><td class = "column_title">Compensaciones</td><td class = "column_title">Despensa</td><td class = "column_title">Comida</td><td class = "column_title">Alimentación</td><td class = "column_title">Habitación</td><td class = "column_title">Aportación patronal al fondo de ahorro</td><td class = "column_title">Total de percepciones</td><td class = "column_title">ISR</td><td class = "column_title">Cuotas IMSS</td><td class = "column_title">Retención por alimentación</td><td class = "column_title">Retención por habitación</td><td class = "column_title">Retención INFONAVIT</td><td class = "column_title">Retención FONACOT</td><td class = "column_title">Aportación del trabajador al fondo de ahorro</td><td class = "column_title">Pensión alimenticia</td><td class = "column_title">Retardos</td><td class = "column_title">Prestaciones</td><td class = "column_title">Gestión administrativa</td><td class = "column_title">Total de deducciones</td><td class = "column_title">Saldo</td><td class = "column_title">Pago neto</td><td class = "column_title">Pago líquido</td><td class = "column_title">Fondo de garantía</td><td class = "column_title">Pago por seguro de vida</td><td class = "column_title">Préstamo del fondo de ahorro</td><td class = "column_title">Préstamo de caja</td><td class = "column_title">Préstamo de cliente</td><td class = "column_title">Préstamo de administradora</td><td class = "column_title">Neto a recibir</td><td class = "column_title">Forma de pago</td><td class = "column_title">Status</td></tr>';
				$len = count($this->trabajador);
				$n = 1;

				for($i=0; $i<$len; $i++)
				{
					$tipo = $this->getTipoTrabajador($this->trabajador[$i]);

					if(isset($tipo) && $tipo == 'Asalariado')
					{
						$this->calculate_nomina_trabajador_asalariado($this->trabajador[$i],$n);
						$n ++;
					}

				}

				$this->nomina_asalariados .= '</table>';
			}

		}

		public function calculate_nomina_trabajadores_asimilables()
		{

			if(isset($this->id) && isset($this->Servicio))
			{
				$this->nomina_asimilables = '<table id="nomina_asimilables"><tr><td colspan = "20" class = "title">Asimilables</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">CURP</td><td class = "column_title">RFC</td><td class = "column_title">Número de días del periodo</td><td class = "column_title">Honorarios asimilados</td><td class = "column_title">Total de percepciones</td><td class = "column_title">ISR</td><td class = "column_title">Gestión administrativa</td><td class = "column_title">Total de deducciones</td><td class = "column_title">Saldo</td><td class = "column_title">Pago neto</td><td class = "column_title">Pago líquido</td><td class = "column_title">Pago por seguro de vida</td><td class = "column_title">Préstamo de caja</td><td class = "column_title">Préstamo de cliente</td><td class = "column_title">Préstamo de administradora</td><td class = "column_title">Neto a recibir</td><td class = "column_title">Forma de pago</td><td class = "column_title">Status</td></tr>';
				$len = count($this->trabajador);
				$n = 1;

				for($i=0; $i<$len; $i++)
				{
					$tipo = $this->getTipoTrabajador($this->trabajador[$i]);

					if(isset($tipo) && $tipo == 'Asimilable')
					{
						$this->calculate_nomina_trabajador_asimilable($this->trabajador[$i],$n);
						$n ++;
					}

				}

				$this->nomina_asimilables .= '</table>';
			}

		}

		public function calculate_incidencias_trabajadores()
		{

			if(isset($this->id) && isset($this->Servicio))
			{
				$_incidencias = '<table id="incidencias"><tr><td>Incidencias</td></tr><tr><td>Trabajador</td><td>Pago neto</td><td>Pago líquido</td><td>Diferencia</td></tr>';
				$len = count($this->trabajador);

				for($i=0; $i<$len; $i++)
				{
					$diferencias = $this->get_value('Diferencia por vacaciones y finiquito',$this->incidencias,$this->trabajador[$i]);
					$pago_neto = $this->get_value('Pago neto',$this->incidencias,$this->trabajador[$i]);
					$pago_liquido = $this->get_value('Pago líquido',$this->incidencias,$this->trabajador[$i]);
					$_incidencias .= "<tr><td>{$this->trabajador[$i]}</td><td>$pago_neto</td><td>$pago_liquido</td><td>$diferencias</td></tr>";
				}

				$_incidencias .= '</table>';
				$this->incidencias = $_incidencias;
			}

		}

		public function chk_saldo_trabajadores_asalariados()//if "saldo < 0" it'll reduct "retenciones" and reset "Pago neto" and "Total de deducciones"
		{
			$len = count($this->trabajador);

			for($i=0; $i<$len; $i++)
			{
				$tipo = $this->getTipoTrabajador($this->trabajador[$i]);

				if(isset($tipo) && $tipo == 'Asalariado')
				{
					$_pago_neto = $this->get_value('Pago neto',$this->nomina_asalariados,$this->trabajador[$i]);
					$_pago_liquido = $this->get_value('Pago líquido',$this->nomina_asalariados,$this->trabajador[$i]);

					if($_pago_neto == '' && ($_pago_liquido == '' || $_pago_liquido == 0.00))//if user lets the software to determine "saldo"
						$saldo = $this->get_value('Saldo',$this->nomina_asalariados,$this->trabajador[$i]);
					elseif($_pago_neto != '')
					{
						$retencion_infonavit = $this->_total($i,'Retención INFONAVIT','nomina_asalariados');
						$retencion_fonacot = $this->_total($i,'Retención FONACOT','nomina_asalariados');
						$pension_alimenticia = $this->_total($i,'Pensión alimenticia','nomina_asalariados');
						$_pago_neto = $_pago_neto - $retencion_infonavit - $retencion_fonacot - $pension_alimenticia;
						$_saldo = $this->get_value('Saldo',$this->nomina_asalariados,$this->trabajador[$i]);
						$saldo = $_saldo > $_pago_neto ? $_saldo : $_pago_neto;//trying to respect automatic "Saldo"
					}
					else
					{
						$result = $this->conn->query("SELECT dcipla FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($dcipla) = $this->conn->fetchRow($result);
						$this->conn->freeResult($result);
						$result = $this->conn->query("SELECT dppla FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($dppla) = $this->conn->fetchRow($result);
						$this->conn->freeResult($result);
						$result = $this->conn->query("SELECT dgapla FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($dgapla) = $this->conn->fetchRow($result);
						$this->conn->freeResult($result);
						$cuotas_imss = $dcipla == 'true' ? $this->get_value('Cuotas IMSS',$this->nomina_asalariados,$this->trabajador[$i]) : 0.00;
						$prestaciones = $dppla == 'true' ? $this->get_value('Prestaciones',$this->nomina_asalariados,$this->trabajador[$i]) : 0.00;
						$gestion_administrativa = $dgapla == 'true' ? $this->get_value('Gestión administrativa',$this->nomina_asalariados,$this->trabajador[$i]) : 0.00;
						$retencion_infonavit = $this->_total($i,'Retención INFONAVIT','nomina_asalariados');
						$retencion_fonacot = $this->_total($i,'Retención FONACOT','nomina_asalariados');
						$pension_alimenticia = $this->_total($i,'Pensión alimenticia','nomina_asalariados');
						$_pago_liquido = $_pago_liquido - $cuotas_imss - $retencion_infonavit - $retencion_fonacot - $pension_alimenticia - $prestaciones - $gestion_administrativa;
						$_saldo = $this->get_value('Saldo',$this->nomina_asalariados,$this->trabajador[$i]);
						$saldo = $_saldo > $_pago_liquido ? $_saldo : $_pago_liquido;//trying to respect automatic "Saldo"
					}

					if($saldo < 0)
					{
						//reduct retenciones
						$saldo = $this->reduct_retenciones($i);
						$pago_neto = $this->get_value('Pago neto',$this->nomina_asalariados,$this->trabajador[$i]);
						$pago_liquido = $this->get_value('Pago líquido',$this->nomina_asalariados,$this->trabajador[$i]);

						if($pago_neto == '' && ($pago_liquido == '' || $pago_liquido == 0.00))
							$pago = 0.00;
						elseif($pago_neto != '')
							$pago = $pago_neto;
						else
							$pago = $pago_liquido;

					}
					else
					{
						$pago = $this->calcular_pago($i);
						$saldo = $this->get_value('Saldo',$this->nomina_asalariados,$this->trabajador[$i]);
					}

					if($saldo < $pago)//calculate prevision social
						$this->prev_soc($i);

				}

			}

		}

		private function reduct_retenciones($_index)
		{
			$saldo = $this->get_value('Saldo',$this->nomina_asalariados,$this->trabajador[$_index]);
			$retencion_infonavit = $this->_total($_index,'Retención INFONAVIT','nomina_asalariados');
			$retencion_fonacot = $this->_total($_index,'Retención FONACOT','nomina_asalariados');
			$pension_alimenticia = $this->_total($_index,'Pensión alimenticia','nomina_asalariados');
			$prestaciones = $this->_total($_index,'Prestaciones','nomina_asalariados');
			$gestion_administrativa = $this->_total($_index,'Gestión administrativa','nomina_asalariados');
			$cuotas_IMSS = $this->_total($_index,'Cuotas IMSS','nomina_asalariados');
			$saldo = round($saldo + $retencion_infonavit + $retencion_fonacot + $pension_alimenticia + $prestaciones + $gestion_administrativa + $cuotas_IMSS, 2);
			//According to paying priority
			//reduct retención INFONAVIT
			$saldo = $this->reduct_retencion($saldo,$_index,'Retención INFONAVIT',false);

			if($saldo - $retencion_fonacot - $pension_alimenticia - $prestaciones - $gestion_administrativa - $cuotas_IMSS < 0)
			//reduct retención FONACOT
				$saldo = $this->reduct_retencion($saldo,$_index,'Retención FONACOT',false);

			if($saldo - $pension_alimenticia - $prestaciones - $gestion_administrativa - $cuotas_IMSS < 0)
			//reduct pensión alimenticia
				$saldo = $this->reduct_retencion($saldo,$_index,'Pensión alimenticia',false);

			if($saldo - $prestaciones - $gestion_administrativa - $cuotas_IMSS < 0)
			//reduct prestaciones
				$saldo = $this->reduct_retencion($saldo,$_index,'Prestaciones',true);

			if($saldo - $gestion_administrativa - $cuotas_IMSS < 0)
			//reduct gestión administrativa
				$saldo = $this->reduct_retencion($saldo,$_index,'Gestión administrativa',true);

			if($saldo - $cuotas_IMSS < 0)
			{
			//reduct cuotas IMSS
				$saldo = $this->reduct_retencion($saldo,$_index,'Cuotas IMSS',true);
				$this->update_cuotas_IMSS($_index);
			}

			$saldo = $this->recalculate_total_deducciones($_index);
			return $saldo;
		}

		private function reduct_retencion($saldo,$_index,$title,$part)
		{
			$retencion = $this->get_value($title,$this->nomina_asalariados,$this->trabajador[$_index]);
			$pago_neto = $this->get_value('Pago neto',$this->nomina_asalariados,$this->trabajador[$_index]);
			$pago_liquido = $this->get_value('Pago líquido',$this->nomina_asalariados,$this->trabajador[$_index]);
			$retenciones = explode(',',$retencion);
			$len = count($retenciones);

			for($i=0; $i<$len; $i++)
			{
				$vars = explode('</span>',$retenciones[$i]);

				if(count($vars) > 1)
				{
					$ret = str_replace('<span>','',$vars[1]);//vars[0] = id
					$spans = true;
				}
				elseif($vars[0] != '' && $vars[0] > 0)
				{
					$ret = $vars[0];
					$spans = false;
				}

				if(isset($ret))
				{

					if(($pago_neto == ''  || $pago_neto == 0.00) && ($pago_liquido == '' || $pago_liquido == 0.00))
					{

						if($saldo >= 0.00)
						{

							if($saldo < $ret)
							{

								if($spans)
								{

									if($part)
									{
										$vars[1] = '<span>' . round($saldo,2) . '/' . str_replace('<span>','',$vars[1]);
										$saldo = 0.00;
									}
									else
										$vars[1] = '<span>0/' . str_replace('<span>','',$vars[1]);

									$retenciones[$i] = $vars[0] . '</span>' . $vars[1] . '</span>';
								}
								else
								{

									if($part)
									{
										$retenciones[$i] = round($saldo,2) . '/' . $ret;
										$saldo = 0.00;
									}
									else
										$retenciones[$i] = '0/' . $ret;

								}

							}
							else
								$saldo = $saldo - $ret;

							$this->set_value('Saldo','nomina_asalariados',$this->trabajador[$_index],round($saldo,2));
						}

					}
					elseif($pago_neto != '')
					{

						if($saldo >= $pago_neto)
						{

							if($saldo < $ret)
							{

								if($spans)
								{

									if($part)
									{
										$vars[1] = '<span>' . round($saldo,2) . '/' . str_replace('<span>','',$vars[1]);
										$saldo = 0.00;
										$pago_neto = 0.00;
									}
									else
										$vars[1] = '<span>0/' . str_replace('<span>','',$vars[1]);

									$retenciones[$i] = $vars[0] . '</span>' . $vars[1] . '</span>';
								}
								else
								{

									if($part)
									{
										$retenciones[$i] = round($saldo,2) . '/' . $ret;
										$saldo = 0.00;
										$pago_neto = 0.00;
									}
									else
										$retenciones[$i] = '0/' . $ret;

								}

							}
							else
							{
								$saldo = $saldo - $ret;
								$pago_neto = ($pago_neto - $ret) < 0.00 ? 0.00 : ($pago_neto - $ret);
							}

						}
						else
						{

							if($pago_neto < $ret)
							{

								if($spans)
								{

									if($part)
									{
										$vars[1] = '<span>' . round($pago_neto,2) . '/' . str_replace('<span>','',$vars[1]);
										$saldo = 0.00;
										$pago_neto = 0.00;
									}
									else
										$vars[1] = '<span>0/' . str_replace('<span>','',$vars[1]);

									$retenciones[$i] = $vars[0] . '</span>' . $vars[1] . '</span>';
								}
								else
								{

									if($part)
									{
										$retenciones[$i] = round($pago_neto,2) . '/' . $ret;
										$saldo = 0.00;
										$pago_neto = 0.00;
									}
									else
										$retenciones[$i] = '0/' . $ret;

								}

							}
							else
							{
								$pago_neto = $pago_neto - $ret;
								$saldo = ($saldo - $ret) < 0.00 ? 0.00 : ($saldo - $ret);
							}

						}

						$this->set_value('Saldo','nomina_asalariados',$this->trabajador[$_index],round($saldo,2));
						$this->set_value('Pago neto','nomina_asalariados',$this->trabajador[$_index],round($pago_neto,2));
					}
					elseif($pago_liquido != '')
					{

						if($saldo >= $pago_liquido)
						{

							if($saldo < $ret)
							{

								if($spans)
								{

									if($part)
									{
										$vars[1] = '<span>' . round($saldo,2) . '/' . str_replace('<span>','',$vars[1]);
										$saldo = 0.00;
										$pago_liquido = 0.00;
									}
									else
										$vars[1] = '<span>0/' . str_replace('<span>','',$vars[1]);

									$retenciones[$i] = $vars[0] . '</span>' . $vars[1] . '</span>';
								}
								else
								{

									if($part)
									{
										$retenciones[$i] = round($saldo,2) . '/' . $ret;
										$saldo = 0.00;
										$pago_liquido = 0.00;
									}
									else
										$retenciones[$i] = '0/' . $ret;

								}

							}
							else
							{
								$saldo = $saldo - $ret;
								$pago_liquido = ($pago_liquido - $ret) < 0.00 ? 0.00 : ($pago_liquido - $ret);
							}

						}
						else
						{

							if($pago_liquido < $ret)
							{

								if($spans)
								{

									if($part)
									{
										$vars[1] = '<span>' . round($pago_liquido,2) . '/' . str_replace('<span>','',$vars[1]);
										$saldo = 0.00;
										$pago_liquido = 0.00;
									}
									else
										$vars[1] = '<span>0/' . str_replace('<span>','',$vars[1]);

									$retenciones[$i] = $vars[0] . '</span>' . $vars[1] . '</span>';
								}
								else
								{

									if($part)
									{
										$retenciones[$i] = round($pago_liquido,2) . '/' . $ret;
										$saldo = 0.00;
										$pago_liquido = 0.00;
									}
									else
										$retenciones[$i] = '0/' . $ret;

								}

							}
							else
							{
								$pago_liquido = $pago_liquido - $ret;
								$saldo = ($saldo - $ret) < 0.00 ? 0.00 : ($saldo - $ret);
							}

						}

						$this->set_value('Saldo','nomina_asalariados',$this->trabajador[$_index],round($saldo,2));
						$this->set_value('Pago líquido','nomina_asalariados',$this->trabajador[$_index],round($pago_liquido,2));
					}

				}

			}

			$aux = '';

			for($i=0; $i<$len; $i++)
				$aux .= $retenciones[$i] . ($i == $len - 1 ? '' : ',');

			$this->set_value($title,'nomina_asalariados',$this->trabajador[$_index],$aux);
			return round($saldo,2);
		}

		private function recalculate_total_deducciones($_index)
		{
			$dif_retencion_FONACOT = $this->dif_retencion($_index,'Retención FONACOT');
			$dif_retencion_INFONAVIT = $this->dif_retencion($_index,'Retención INFONAVIT');
			$dif_pension_alimenticia = $this->dif_retencion($_index,'Pensión alimenticia');
			$dif_prestaciones = $this->dif_retencion($_index,'Prestaciones');
			$dif_ga = $this->dif_retencion($_index,'Gestión administrativa');
			$dif_cuotas_IMSS = $this->dif_retencion($_index,'Cuotas IMSS');
			$total_dif = $dif_retencion_FONACOT + $dif_retencion_INFONAVIT + $dif_pension_alimenticia + $dif_prestaciones + $dif_ga + $dif_cuotas_IMSS;
			$total_de_deducciones = $this->get_value('Total de deducciones',$this->nomina_asalariados,$this->trabajador[$_index]);
			$total_de_percepciones = $this->get_value('Total de percepciones',$this->nomina_asalariados,$this->trabajador[$_index]);
			$aux = $total_de_deducciones - $total_dif;
			$saldo = $total_de_percepciones - $aux;
			$this->set_value('Total de deducciones','nomina_asalariados',$this->trabajador[$_index],round($aux,2));
			$this->set_value('Saldo','nomina_asalariados',$this->trabajador[$_index],round($saldo,2));
			return $saldo;
		}

		private function dif_retencion($_index,$title)
		{
			$retencion = $this->get_value($title,$this->nomina_asalariados,$this->trabajador[$_index]);
			$retenciones = explode(',',$retencion);
			$len = count($retenciones);
			$dif = 0;

			for($i=0; $i<$len; $i++)
			{
				$vars = explode('</span>',$retenciones[$i]);

				if(isset($vars) && count($vars) >= 2)
				{
					$_var = str_replace('<span>','',$vars[1]);//vars[0] = id
					$ret = explode('/',$_var);

					if(isset($ret) && count($ret) >= 2)
						$dif += $ret[1] - $ret[0];

				}
				elseif(isset($vars) && $vars[0] != '' && $vars[0] != '0' && $vars[0] != '0.00')
				{
					$ret = explode('/',$vars[0]);

					if(isset($ret) && count($ret) >= 2)
						$dif += $ret[1] - $ret[0];

				}

			}

			return $dif;
		}

		private function update_cuotas_IMSS($_index)
		{
			$cuotas_obreras = array('Exedente 3 SMGDF obrero', 'Prestaciones en dinero obreras', 'Gastos médicos y pensión obreros', 'Invalidez y vida obrera', 'Cesantía y vejez obrera');
			$cuotas_patronales = array('Cuota fija IMSS', 'Exedente 3 SMGDF patronal', 'Prestaciones en dinero patronales', 'Gastos médicos y pensión patronales', 'Invalidez y vida patronal', 'Cesantía y vejez patronal', 'Guardería', 'Retiro' , 'INFONAVIT', 'Riesgo de trabajo','Adeudo');
			$value = $this->get_value('Cuotas IMSS',$this->nomina_asalariados,$this->trabajador[$_index]);
			$data = explode('/',$value);
			$amount = $data[0];
			$dif_obrera = 0.00;
			$dif_patronal = 0.00;

			foreach($cuotas_obreras as $title)
			{
				$val = $this->get_value($title,$this->cuotas_IMSS,$this->trabajador[$_index]);

				if($amount < $val)
				{
					$dif_obrera += round($val - $amount,2);
					$val = $amount . '/' . $val;
					$this->set_value($title,'cuotas_IMSS',$this->trabajador[$_index],$val);
					$amount = 0.00;
				}
				else
					$amount -= $val;

			}

			$cio = $this->get_value('Total de cuotas IMSS obreras',$this->cuotas_IMSS,$this->trabajador[$_index]);
			$this->set_value('Total de cuotas IMSS obreras','cuotas_IMSS',$this->trabajador[$_index],round($cio - $dif_obrera,2));

			foreach($cuotas_patronales as $title)
			{
				$val = $this->get_value($title,$this->cuotas_IMSS,$this->trabajador[$_index]);

				if($amount < $val)
				{
					$dif_patronal += round($val - $amount,2);
					$val = $amount . '/' . $val;
					$this->set_value($title,'cuotas_IMSS',$this->trabajador[$_index],$val);
					$amount = 0.00;
				}
				else
					$amount -= $val;

			}

			$cip = $this->get_value('Total de cuotas IMSS patronales',$this->cuotas_IMSS,$this->trabajador[$_index]);
			$this->set_value('Total de cuotas IMSS patronales','cuotas_IMSS',$this->trabajador[$_index],round($cip - $dif_patronal,2));
		}

		private function calcular_pago($i)
		{
			$pago_neto = $this->get_value('Pago neto',$this->nomina_asalariados,$this->trabajador[$i]);
			$pago_liquido = $this->get_value('Pago líquido',$this->nomina_asalariados,$this->trabajador[$i]);

			if($pago_neto == '' && ($pago_liquido == '' || $pago_liquido == 0.00))
				$pago = 0.00;
			elseif($pago_neto != '')
			{
				//retención INFONAVIT
				$retencion_infonavit = $this->_total($i,'Retención INFONAVIT','nomina_asalariados');
				//retención FONACOT
				$retencion_fonacot = $this->_total($i,'Retención FONACOT','nomina_asalariados');
				//pensión alimenticia
				$pension_alimenticia = $this->_total($i,'Pensión alimenticia','nomina_asalariados');
				$pago = $pago_neto - $retencion_infonavit - $retencion_fonacot - $pension_alimenticia;

				if($pago < 0)
					$pago = 0.00;

				$this->set_value('Pago neto','nomina_asalariados',$this->trabajador[$i],$pago);
			}
			else
			{
				$result = $this->conn->query("SELECT dcipla FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($dcipla) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$cuotas_imss = $dcipla == 'true' ? $this->get_value('Cuotas IMSS',$this->nomina_asalariados,$this->trabajador[$i]) : 0.00;
				$result = $this->conn->query("SELECT dppla FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($dppla) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$prestaciones = $dppla == 'true' ? $this->get_value('Prestaciones',$this->nomina_asalariados,$this->trabajador[$i]) : 0.00;
				$result = $this->conn->query("SELECT dgapla FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($dgapla) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$gestion_administrativa = $dgapla == 'true' ? $this->get_value('Gestión administrativa',$this->nomina_asalariados,$this->trabajador[$i]) : 0.00;
				//retención INFONAVIT
				$retencion_infonavit = $this->_total($i,'Retención INFONAVIT','nomina_asalariados');
				//retención FONACOT
				$retencion_fonacot = $this->_total($i,'Retención FONACOT','nomina_asalariados');
				//pensión alimenticia
				$pension_alimenticia = $this->_total($i,'Pensión alimenticia','nomina_asalariados');
				$pago = $pago_liquido - $cuotas_imss - $retencion_infonavit - $retencion_fonacot - $pension_alimenticia - $prestaciones - $gestion_administrativa;

				if($pago < 0)
					$pago = 0.00;

				$this->set_value('Pago líquido','nomina_asalariados',$this->trabajador[$i],$pago);
			}

			return $pago;
		}

		private function prev_soc($_index)
		{
			$sueldo = $this->get_value('Sueldo',$this->nomina_asalariados,$this->trabajador[$_index]);
			$numero_de_dias_del_periodo = $this->get_value('Número de días del periodo',$this->ISRasalariados,$this->trabajador[$_index]);
			$numero_de_dias_laborados = $this->get_value('Número de días laborados',$this->ISRasalariados,$this->trabajador[$_index]);
			$total_de_percepciones = $this->get_value('Total de percepciones',$this->nomina_asalariados,$this->trabajador[$_index]);
			$subsidio_al_empleo = $this->get_value('Subsidio al empleo',$this->nomina_asalariados,$this->trabajador[$_index]);
			$total_de_deducciones = $this->get_value('Total de deducciones',$this->nomina_asalariados,$this->trabajador[$_index]);
			$saldo = $this->get_value('Saldo',$this->nomina_asalariados,$this->trabajador[$_index]);
			$pago_neto = $this->get_value('Pago neto',$this->nomina_asalariados,$this->trabajador[$_index]);
			$pago_liquido = $this->get_value('Pago líquido',$this->nomina_asalariados,$this->trabajador[$_index]);

			if($pago_neto == '' && ($pago_liquido == '' || $pago_liquido == 0.00))
				$pago = 0.00;
			elseif($pago_neto != '')
				$pago = $pago_neto;
			else
				$pago = $pago_liquido;

			$isr = $this->get_value('ISR',$this->nomina_asalariados,$this->trabajador[$_index]);
			$base_ISR = $this->get_value('Base',$this->ISRasalariados,$this->trabajador[$_index]);
			list($alimentacion,$retencion_por_alimentacion,$habitacion,$retencion_por_habitacion,$psg,$base,$li,$eli,$pseli,$im,$cf,$id,$se,$isr,$subsidio,$tp,$td,$s,$dif,$ps) = $this->calculate_prevision_social($this->trabajador[$_index],$sueldo,$numero_de_dias_del_periodo,$numero_de_dias_laborados,$pago,$total_de_percepciones-$subsidio_al_empleo,$total_de_deducciones-$isr,$base_ISR);
			$this->set_value('Alimentación','nomina_asalariados',$this->trabajador[$_index],$alimentacion);
			$this->set_value('Retención por alimentación','nomina_asalariados',$this->trabajador[$_index],round($retencion_por_alimentacion,2));
			$this->set_value('Habitación','nomina_asalariados',$this->trabajador[$_index],$habitacion);
			$this->set_value('Retención por habitación','nomina_asalariados',$this->trabajador[$_index],round($retencion_por_habitacion,2));
			$this->set_value('Previsión social gravada','ISRasalariados',$this->trabajador[$_index],$psg);
			$this->set_value('Base','ISRasalariados',$this->trabajador[$_index],$base);
			$this->set_value('Límite inferior','ISRasalariados',$this->trabajador[$_index],$li);
			$this->set_value('Exedente del límite inferior','ISRasalariados',$this->trabajador[$_index],$eli);
			$this->set_value('Porcentaje sobre el exedente del límite inferior','ISRasalariados',$this->trabajador[$_index],$pseli);
			$this->set_value('Impuesto marginal','ISRasalariados',$this->trabajador[$_index],$im);
			$this->set_value('Cuota fija','ISRasalariados',$this->trabajador[$_index],$cf);
			$this->set_value('Impuesto determinado','ISRasalariados',$this->trabajador[$_index],$id);
			$this->set_value('Subsidio','ISRasalariados',$this->trabajador[$_index],$se);
			$this->set_value('ISR','ISRasalariados',$this->trabajador[$_index],$isr);
			$this->set_value('Subsidio al empleo','ISRasalariados',$this->trabajador[$_index],$subsidio);
			$this->set_value('ISR','nomina_asalariados',$this->trabajador[$_index],$isr);
			$this->set_value('Subsidio al empleo','nomina_asalariados',$this->trabajador[$_index],$subsidio);
			$this->set_value('Total de percepciones','nomina_asalariados',$this->trabajador[$_index],round($tp,2));
			$this->set_value('Total de deducciones','nomina_asalariados',$this->trabajador[$_index],round($td,2));
			$this->set_value('Saldo','nomina_asalariados',$this->trabajador[$_index],round($s,2));
		}

		public function calculate_neto_a_recibir_asalariados()
		{
			$len = count($this->trabajador);

			for($i=0; $i<$len; $i++)
			{
				$tipo = $this->getTipoTrabajador($this->trabajador[$i]);

				if(isset($tipo) && $tipo == 'Asalariado')
				{
					$saldo = $this->get_value('Saldo',$this->nomina_asalariados,$this->trabajador[$i]);
					$fondo_de_garantia = $this->calculate_fondo_de_garantia($this->trabajador[$i]);
					$this->set_value('Fondo de garantía','nomina_asalariados',$this->trabajador[$i],$fondo_de_garantia);
					$descuentos = $this->calculate_descuentos($i,'nomina_asalariados');
					$_neto_a_recibir = $saldo - $descuentos;

					if($_neto_a_recibir < 0)
						$_neto_a_recibir = $this->reduct_descuentos($_neto_a_recibir,$i,'nomina_asalariados');

					$this->set_value('Neto a recibir','nomina_asalariados',$this->trabajador[$i],round($_neto_a_recibir,2));
				}

			}

		}

		public function calculate_neto_a_recibir_asimilables()
		{
			$len = count($this->trabajador);

			for($i=0; $i<$len; $i++)
			{
				$tipo = $this->getTipoTrabajador($this->trabajador[$i]);

				if(isset($tipo) && $tipo == 'Asimilable')
				{
					$saldo = $this->get_value('Saldo',$this->nomina_asimilables,$this->trabajador[$i]);
					$descuentos = $this->calculate_descuentos($i,'nomina_asimilables');
					$_neto_a_recibir = $saldo - $descuentos;

					if($_neto_a_recibir < 0)
						$_neto_a_recibir = $this->reduct_descuentos($_neto_a_recibir,$i,'nomina_asimilables');

					$this->set_value('Neto a recibir','nomina_asimilables',$this->trabajador[$i],round($_neto_a_recibir,2));
				}

			}

		}

		private function calculate_descuentos($_index,$table)
		{

			if($table == 'nomina_asalariados')
				$descuentos = array('Fondo de garantía','Pago por seguro de vida','Préstamo del fondo de ahorro','Préstamo de caja','Préstamo de cliente','Préstamo de administradora');
			else
				$descuentos = array('Pago por seguro de vida','Préstamo de caja','Préstamo de cliente','Préstamo de administradora');

			$len = count($descuentos);
			$total_descuento = 0.00;

			for($i=0; $i<$len; $i++)
			{
				$descuento = $this->get_value($descuentos[$i],$this->$table,$this->trabajador[$_index]);
				$_descuentos = explode(',',$descuento);
				$_len = count($_descuentos);

				for($j=0; $j<$_len; $j++)
				{
					$vars = explode('</span>',$_descuentos[$j]);

					if(count($vars) > 1)
						$total_descuento += str_replace('<span>','',$vars[1]);
					else
						$total_descuento += $vars[0];

				}

			}

			return $total_descuento;
		}

		private function reduct_descuentos($_neto_a_recibir,$_index,$table)
		{
			$pago_por_seguro_de_vida = $this->_total($_index,'Pago por seguro de vida',$table);

			if($table == 'nomina_asalariados')
				$prestamo_del_fondo_de_ahorro = $this->_total($_index,'Préstamo del fondo de ahorro',$table);
			else
				$prestamo_del_fondo_de_ahorro = 0.00;

			$prestamo_caja = $this->_total($_index,'Préstamo de caja',$table);
			$prestamo_cliente = $this->_total($_index,'Préstamo de cliente',$table);
			$prestamo_administradora = $this->_total($_index,'Préstamo de administradora',$table);
			$_neto_a_recibir = $_neto_a_recibir + $pago_por_seguro_de_vida + $prestamo_del_fondo_de_ahorro + $prestamo_caja + $prestamo_cliente + $prestamo_administradora;
			//reduct Pago por seguro de vida
			$_neto_a_recibir = $this->reduct_descuento($_neto_a_recibir,$_index,'Pago por seguro de vida',$table);

			//reduct Préstamo del fondo de ahorro
			if($table == 'nomina_asalariados')
				$_neto_a_recibir = $this->reduct_descuento($_neto_a_recibir,$_index,'Préstamo del fondo de ahorro',$table);

			//reduct Préstamo de caja
			$_neto_a_recibir = $this->reduct_descuento($_neto_a_recibir,$_index,'Préstamo de caja',$table);
			//reduct Préstamo de cliente
			$_neto_a_recibir = $this->reduct_descuento($_neto_a_recibir,$_index,'Préstamo de cliente',$table);
			//reduct Préstamo de administradora
			$_neto_a_recibir = $this->reduct_descuento($_neto_a_recibir,$_index,'Préstamo de administradora',$table);
			return $_neto_a_recibir;
		}

		private function reduct_descuento($neto,$_index,$title,$table)
		{
			$descuento = $this->get_value($title,$this->$table,$this->trabajador[$_index]);
			$descuentos = explode(',',$descuento);
			$len = count($descuentos);

			for($i=0; $i<$len; $i++)
			{
				$vars = explode('</span>',$descuentos[$i]);

				if(isset($vars[1]))
					$desc = str_replace('<span>','',$vars[1]);//$vars[0] = id

				if(isset($desc) && $neto < $desc)
				{
					$vars[1] = '<span>0/' . str_replace('<span>','',$vars[1]);
					$descuentos[$i] = $vars[0] . '</span>' . $vars[1] . '</span>';
				}
				elseif(isset($desc) && $neto >= $desc)
					$neto -= $desc;

			}

			$aux = '';

			for($i=0; $i<$len; $i++)
				$aux .= $descuentos[$i] . ($i == $len - 1 ? '' : ',');

			$this->set_value($title,$table,$this->trabajador[$_index],$aux);
			return round($neto,2);
		}

		private function _total($_index,$title,$table)
		{
			$retencion = $this->get_value($title,$this->$table,$this->trabajador[$_index]);
			$retenciones = explode(',',$retencion);
			$len = count($retenciones);
			$total = 0.00;

			for($i=0; $i<$len; $i++)
			{
				$vars = explode('</span>',$retenciones[$i]);

				if(isset($vars[1]))
					$ret = str_replace('<span>','',$vars[1]);//vars[0] = id
				elseif($vars[0] != '' && $vars[0] > 0)
					$ret = $vars[0];

				if(isset($ret))
					$total += $ret;

			}

			return round($total,2);
		}

		public function calculate_ISR_trabajador_asalariado($_trabajador,$n)
		{
			date_default_timezone_set('America/Mexico_City');
			$this->ISRasalariados .= '<tr>';
			//Trabajador
			$result = $this->conn->query("SELECT Nombre, Numero_IMSS, CURP FROM Trabajador WHERE RFC = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			$row = $this->conn->fetchRow($result);
			list($nombre,$nss,$curp) = $row;
			$this->ISRasalariados .=  "<td>$n</td><td>$nombre</td><td>$nss</td><td>$curp</td><td>$_trabajador</td>";
			//Días del periodo and Número de días del periodo
			$numero_de_dias_del_periodo = $this->calculate_numero_de_dias_del_periodo();
			$dias_del_periodo = $this->calculate_dias_del_periodo($numero_de_dias_del_periodo);
			$this->ISRasalariados .= "<td>$numero_de_dias_del_periodo</td>";
			//Días previos al ingreso and numero de días previos al ingreso
			$numero_de_dias_previos_al_ingreso = $this->calculate_numero_de_dias_previos_al_ingreso($_trabajador, 'ISR');
			$dias_previos_al_ingreso = $this->calculate_dias_previos_al_ingreso($numero_de_dias_previos_al_ingreso);
			$this->ISRasalariados .= "<td>$numero_de_dias_previos_al_ingreso</td>";
			//Días de baja and Número de días de baja
			$dias_de_baja = $this->calculate_dias_de_baja($_trabajador);
			$numero_de_dias_de_baja = count($dias_de_baja);
			$this->ISRasalariados .= "<td>$numero_de_dias_de_baja</td>";
			//Faltas and Número de faltas
			$faltas = $this->calculate_faltas($_trabajador);
			$numero_de_faltas = count($faltas);
			$txt = $this->myArray2Str($faltas);
			$this->ISRasalariados .= "<td>$txt</td>";
			$this->ISRasalariados .= "<td>$numero_de_faltas</td>";
			//Incapacidad
			$dias_de_incapacidad = $this->calculate_dias_de_incapacidad($_trabajador);
			$numero_de_dias_de_incapacidad = count($dias_de_incapacidad);
			$this->ISRasalariados .= "<td>$numero_de_dias_de_incapacidad</td>";
			//Vacaciones
			$dias_de_vacaciones = $this->calculate_dias_de_vacaciones($_trabajador);
			$numero_de_dias_de_vacaciones = count($dias_de_vacaciones);
			$this->ISRasalariados .= "<td>$numero_de_dias_de_vacaciones</td>";
			//Días laborados and Número de días laborados
			$dias_laborados = $this->calculate_dias_laborados($dias_del_periodo,$dias_previos_al_ingreso,$dias_de_baja,$faltas,$dias_de_incapacidad,$dias_de_vacaciones);
			$numero_de_dias_laborados = count($dias_laborados);
			$this->ISRasalariados .= "<td>$numero_de_dias_laborados</td>";
			//Salario diario
			$result = $this->conn->query("SELECT Base FROM Base WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
			list($base) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$salario_diario = $this->calculate_salario_diario($_trabajador,$base);

			if(is_array($salario_diario))
			{
				$salario_len = count($salario_diario);
				$txt = '';

				for($i=0; $i<$salario_len; $i++)
					$txt .= $salario_diario[$i]['Cantidad'] . '/' . $salario_diario[$i]['Fecha'] . (($i == $salario_len - 1) ? '' : ',');

				$this->ISRasalariados .= "<td>$txt</td>";
			}
			else
				$this->ISRasalariados .= "<td>$salario_diario</td>";

			//Sueldo
			$sueldo = $this->calculate_sueldo($salario_diario,$dias_laborados);
			$this->ISRasalariados .= "<td>$sueldo</td>";
			//Horas extra
			list($horas_extra,$horas_extra_gravadas) = $this->calculate_horas_extra($salario_diario,$numero_de_dias_del_periodo,$_trabajador);
			$this->ISRasalariados .= "<td>$horas_extra</td>";
			$this->ISRasalariados .= "<td>$horas_extra_gravadas</td>";
			//Domingos laborados and  Número de domingos laborados
			$domingos_laborados = $this->calculate_domingos_laborados($_trabajador);
			$numero_de_domingos_laborados = count($domingos_laborados);
			$txt = $this->myArray2Str($domingos_laborados);
			$this->ISRasalariados .= "<td>$txt</td>";
			$this->ISRasalariados .= "<td>$numero_de_domingos_laborados</td>";
			//Prima dominical
			list($prima_dominical,$prima_dominical_gravada) = $this->calculate_prima_dominical($salario_diario,$domingos_laborados,$_trabajador);
			$this->ISRasalariados .= "<td>$prima_dominical</td>";
			$this->ISRasalariados .= "<td>$prima_dominical_gravada</td>";
			//Dias de descanso
			list($dias_de_descanso,$dias_de_descanso_gravados) = $this->calculate_dias_de_descanso($salario_diario,$_trabajador);
			$this->ISRasalariados .= "<td>$dias_de_descanso</td>";
			$this->ISRasalariados .= "<td>$dias_de_descanso_gravados</td>";
			//premios de puntualidad y asistencia
			$premios_de_puntualidad_y_asistencia = $this->get_value('Premios de puntualidad y asistencia',$this->incidencias,$_trabajador);
			$this->ISRasalariados .= "<td>$premios_de_puntualidad_y_asistencia</td>";
			//bonos de productividad
			$bonos_de_productividad = $this->get_value('Bonos de prod.',$this->incidencias,$_trabajador);
			$this->ISRasalariados .= "<td>$bonos_de_productividad</td>";
			//estímulos
			$estimulos = $this->get_value('Estímulos',$this->incidencias,$_trabajador);
			$this->ISRasalariados .= "<td>$estimulos</td>";
			//compensaciones
			$compensaciones = $this->get_value('Comp.',$this->incidencias,$_trabajador);
			$this->ISRasalariados .= "<td>$compensaciones</td>";
			//despensa
			$despensa = $this->get_value('Despensa',$this->incidencias,$_trabajador);
			$this->ISRasalariados .= "<td>$despensa</td>";
			//comida
			$comida = $this->get_value('Comida',$this->incidencias,$_trabajador);
			$this->ISRasalariados .= "<td>$comida</td>";
			//previsión social
			$prevision_social = 0;
			$prevision_social_gravada = 0;
			$this->ISRasalariados .= "<td>$prevision_social_gravada</td>";
			//base ISR
			$base_ISR = $sueldo + $horas_extra_gravadas + $prima_dominical_gravada + $dias_de_descanso_gravados + $premios_de_puntualidad_y_asistencia + $bonos_de_productividad + $estimulos + $compensaciones + $despensa + $comida + $prevision_social_gravada;
			$this->ISRasalariados .= "<td>$base_ISR</td>";
			//Límite inferior
			$limite_inferior = $this->calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			$this->ISRasalariados .= "<td>$limite_inferior</td>";
			//Exedente del límite inferior
			$exedente_del_limite_inferior = $base_ISR - $limite_inferior;
			$this->ISRasalariados .= "<td>$exedente_del_limite_inferior</td>";
			//Porcentaje sobre el exedente del límite inferior
			$porcentaje_sobre_el_exedente_del_limite_inferior = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			$this->ISRasalariados .= "<td>$porcentaje_sobre_el_exedente_del_limite_inferior</td>";
			//Impuesto marginal
			$impuesto_marginal = round($exedente_del_limite_inferior * $porcentaje_sobre_el_exedente_del_limite_inferior,2);
			$this->ISRasalariados .= "<td>$impuesto_marginal</td>";
			//Cuota fija
			$cuota_fija = $this->calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			$this->ISRasalariados .= "<td>$cuota_fija</td>";
			//Impuesto determinado
			$impuesto_determinado = $impuesto_marginal + $cuota_fija;
			$this->ISRasalariados .= "<td>$impuesto_determinado</td>";
			//Subsidio
			$subsidio = $this->calculate_subsidio($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			$this->ISRasalariados .= "<td>$subsidio</td>";
			//ISR and Subsidio al empleo
			if($impuesto_determinado - $subsidio > 0)
			{
				$ISR = $impuesto_determinado - $subsidio;
				$subsidio_al_empleo = 0;
			}
			else
			{
				$subsidio_al_empleo = $subsidio - $impuesto_determinado;
				$ISR = 0;
			}

			$this->ISRasalariados .= "<td>$ISR</td>";
			$this->ISRasalariados .= "<td>$subsidio_al_empleo</td>";
			$this->ISRasalariados .= '</tr>';
		}

		private function calculate_ISR_trabajador_asimilable($_trabajador,$n)
		{
			date_default_timezone_set('America/Mexico_City');
			$this->ISRasimilables .= '<tr>';
			//Trabajador
			$result = $this->conn->query("SELECT Nombre, CURP FROM Trabajador WHERE RFC = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			$row = $this->conn->fetchRow($result);
			list($nombre,$curp) = $row;
			$this->ISRasimilables .=  "<td>$n</td><td>$nombre</td><td>$curp</td><td>$_trabajador</td>";
			//Días del periodo and Número de días del periodo
			$numero_de_dias_del_periodo = $this->calculate_numero_de_dias_del_periodo();
			$dias_del_periodo = $this->calculate_dias_del_periodo($numero_de_dias_del_periodo);
			$this->ISRasimilables .= "<td>$numero_de_dias_del_periodo</td>";
			//Días previos al ingreso and numero de días previos al ingreso
			$numero_de_dias_previos_al_ingreso = $this->calculate_numero_de_dias_previos_al_ingreso($_trabajador, 'ISR');
			$dias_previos_al_ingreso = $this->calculate_dias_previos_al_ingreso($numero_de_dias_previos_al_ingreso);
			$this->ISRasimilables .= "<td>$numero_de_dias_previos_al_ingreso</td>";
			//Días de baja and Número de días de baja
			$dias_de_baja = $this->calculate_dias_de_baja($_trabajador);
			$numero_de_dias_de_baja = count($dias_de_baja);
			$this->ISRasimilables .= "<td>$numero_de_dias_de_baja</td>";
			//Días laborados and Número de días laborados
			$dias_laborados = $this->calculate_dias_laborados($dias_del_periodo,$dias_previos_al_ingreso,$dias_de_baja,array(),array(),array());//faltas, dias_de_incapacidad and vacaciones are empty 
			$numero_de_dias_laborados = count($dias_laborados);
			$this->ISRasimilables .= "<td>$numero_de_dias_laborados</td>";
			$pago_liquido = $this->get_value('Pago líquido',$this->incidencias,$_trabajador);

			if($pago_liquido != '' && $pago_liquido > 0)
				$honorarios_asimilados = $pago_liquido;//it has to be calculated here
			else
				$honorarios_asimilados = $this->calculate_honorarios_asimilados($_trabajador,$numero_de_dias_del_periodo,$numero_de_dias_laborados);//it has to be calculated here

			//Salario diario
			$salario_diario = round($honorarios_asimilados / $numero_de_dias_laborados,2);
			$this->ISRasimilables .= "<td>$salario_diario</td>";
			//Honorarios asimilados
			$this->ISRasimilables .= "<td>$honorarios_asimilados</td>";//honorarios_asimilados set above
			//base ISR
			$base_ISR = $honorarios_asimilados;
			$this->ISRasimilables .= "<td>$base_ISR</td>";
			//Límite inferior
			$limite_inferior = $this->calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			$this->ISRasimilables .= "<td>$limite_inferior</td>";
			//Exedente del límite inferior
			$exedente_del_limite_inferior = round($base_ISR - $limite_inferior, 2);
			$this->ISRasimilables .= "<td>$exedente_del_limite_inferior</td>";
			//Porcentaje sobre el exedente del límite inferior
			$porcentaje_sobre_el_exedente_del_limite_inferior = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			$this->ISRasimilables .= "<td>$porcentaje_sobre_el_exedente_del_limite_inferior</td>";
			//Impuesto marginal
			$impuesto_marginal = round($exedente_del_limite_inferior * $porcentaje_sobre_el_exedente_del_limite_inferior,2);
			$this->ISRasimilables .= "<td>$impuesto_marginal</td>";
			//Cuota fija
			$cuota_fija = $this->calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			$this->ISRasimilables .= "<td>$cuota_fija</td>";
			//Impuesto determinado
			$impuesto_determinado = round($impuesto_marginal + $cuota_fija, 2);
			$this->ISRasimilables .= "<td>$impuesto_determinado</td>";
			//Subsidio
			$subsidio = 0;//$this->calculate_subsidio($base_ISR,$numero_de_dias_del_periodo);
			$this->ISRasimilables .= "<td>$subsidio</td>";
			//ISR and Subsidio al empleo
			if($impuesto_determinado - $subsidio > 0)
			{
				$ISR = round($impuesto_determinado - $subsidio, 2);
				$subsidio_al_empleo = 0;
			}
			else
			{
				$subsidio_al_empleo = round($subsidio - $impuesto_determinado, 2);
				$ISR = 0;
			}

			$this->ISRasimilables .= "<td>$ISR</td>";
			$this->ISRasimilables .= "<td>$subsidio_al_empleo</td>";
			$this->ISRasimilables .= '</tr>';
		}

		public function calculate_cuotas_IMSS_trabajador_asalariado($_trabajador)//used to calculate "cuotas IMSS" for "nomina"
		{
			date_default_timezone_set('America/Mexico_City');
			$result = $this->conn->query("SELECT Cuotas_IMSS FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calculate_cuotas_imss) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			//Días del periodo and Número de días del periodo
			$numero_de_dias_del_periodo = $this->calculate_numero_de_dias_del_periodo();
			$dias_del_periodo = $this->calculate_dias_del_periodo($numero_de_dias_del_periodo);
			//Días previos al ingreso and numero de días previos al ingreso
			$numero_de_dias_previos_al_ingreso = $this->calculate_numero_de_dias_previos_al_ingreso($_trabajador, 'IMSS');
			$dias_previos_al_ingreso = $this->calculate_dias_previos_al_ingreso($numero_de_dias_previos_al_ingreso);
			//Días de baja and Número de días de baja
			$dias_de_baja = $this->calculate_dias_de_baja($_trabajador);
			$numero_de_dias_de_baja = count($dias_de_baja);
			//Faltas and Número de faltas
			$faltas = $this->calculate_faltas($_trabajador);
			$numero_de_faltas = count($faltas);
			//Incapacidad
			$dias_de_incapacidad = $this->calculate_dias_de_incapacidad($_trabajador);
			$numero_de_dias_de_incapacidad = count($dias_de_incapacidad);
			//Vacaciones
			$dias_de_vacaciones = $this->calculate_dias_de_vacaciones($_trabajador);
			$numero_de_dias_de_vacaciones = count($dias_de_vacaciones);
			//Días laborados and Número de días laborados
			$dias_laborados = $this->calculate_dias_laborados($dias_del_periodo,$dias_previos_al_ingreso,$dias_de_baja,$faltas,$dias_de_incapacidad,$dias_de_vacaciones);
			$numero_de_dias_laborados = count($dias_laborados);
			//Años de antigüedad
			$ingreso = $this->calculate_ingreso($_trabajador, 'IMSS');

			if(isset($ingreso))
			{
				$interval = date_diff(date_create($this->Limite_superior_del_periodo),date_create($ingreso));
				$dias_de_antiguedad = $interval->days;
				$anos_de_antiguedad = ceil(($dias_de_antiguedad)/365);
			}
			else
				$anos_de_antiguedad = 0;

			//Dias de vacaciones
			if($anos_de_antiguedad < 5)
				$dias_de_vacaciones = $anos_de_antiguedad * 2 + 4;
			else
				$dias_de_vacaciones = floor($anos_de_antiguedad / 5) * 2 + 12;

			//Factor de prima vacacional
			$factor_de_prima_vacacional = round($dias_de_vacaciones * 0.25 / 365,4);
			//Factor de aguinaldo
			$result = $this->conn->query("SELECT Dias_de_aguinaldo FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($dias_de_aguinaldo) = $this->conn->fetchRow($result);
			$factor_de_aguinaldo = round($dias_de_aguinaldo / 365,4);
			//Factor de integración
			$factor_de_integracion = 1 + $factor_de_prima_vacacional + $factor_de_aguinaldo;
			//Salario diario
			$result = $this->conn->query("SELECT Base FROM Base WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
			list($base) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$salario_diario = $this->calculate_salario_diario($_trabajador,$base);
			//Salario diario integrado
			$salario_diario_integrado = $this->calculate_salario_diario_integrado($_trabajador,$factor_de_integracion);
			//Cuota fija IMSS
			if($calculate_cuotas_imss != 'No administrado')
				$cuota_fija_imss = $this->calculate_cuota_fija_imss($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador);
			else
				$cuota_fija_imss = 0;

			//Exedente 3 SMGFD patronal
			if($calculate_cuotas_imss != 'No administrado')
				$exedente_patronal = $this->calculate_exedente_patronal($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador);
			else
				$exedente_patronal = 0;

			//Exedente 3 SMGDF obrero
			if($calculate_cuotas_imss != 'No administrado')
				$exedente_obrero = $this->calculate_exedente_obrero($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador);
			else
				$exedente_obrero = 0;

			//Prestaciones en dinero obreras
			if($calculate_cuotas_imss != 'No administrado')
				$prestaciones_en_dinero_obreras = $this->calculate_prestaciones_en_dinero_obreras($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador);
			else
				$prestaciones_en_dinero_obreras = 0;

			//Gastos médicos y pensión obreros
			if($calculate_cuotas_imss != 'No administrado')
				$gastos_medicos_y_pension_obreros = $this->calculate_gastos_medicos_y_pension_obreros($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador);
			else
				$gastos_medicos_y_pension_obreros = 0;

			//Invalidez y vida obrera
			if($calculate_cuotas_imss != 'No administrado')
				$invalidez_y_vida_obrera = $this->calculate_invalidez_y_vida_obrera($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador);
			else
				$invalidez_y_vida_obrera = 0;

			//Cesantía y vejez obrera
			if($calculate_cuotas_imss != 'No administrado')
				$cesantia_y_vejez_obrera = $this->calculate_cesantia_y_vejez_obrera($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador);
			else
				$cesantia_y_vejez_obrera = 0;

			//Total de cuotas IMSS obreras
			$total_de_cuotas_IMSS_obreras = $exedente_obrero + $prestaciones_en_dinero_obreras + $gastos_medicos_y_pension_obreros + $invalidez_y_vida_obrera + $cesantia_y_vejez_obrera;
			return $total_de_cuotas_IMSS_obreras;
		}

		public function calculate_cuotas_IMSS($_trabajador,$n)//used to calculate "cuotas IMSS obrero patronales"
		{
			date_default_timezone_set('America/Mexico_City');
			$result = $this->conn->query("SELECT Cuotas_IMSS FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calculate_cuotas_imss) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$this->cuotas_IMSS .= '<tr>';
			//Trabajador
			$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			$row = $this->conn->fetchRow($result);
			list($nombre) = $row;
			$this->cuotas_IMSS .=  "<td>$n</td><td>$nombre</td><td>$_trabajador</td>";
			//Días del periodo and Número de días del periodo
			$numero_de_dias_del_periodo = $this->calculate_numero_de_dias_del_periodo();
			$dias_del_periodo = $this->calculate_dias_del_periodo($numero_de_dias_del_periodo);
			$this->cuotas_IMSS .= "<td>$numero_de_dias_del_periodo</td>";
			//Días previos al ingreso and numero de días previos al ingreso
			$numero_de_dias_previos_al_ingreso = $this->calculate_numero_de_dias_previos_al_ingreso($_trabajador, 'IMSS');
			$dias_previos_al_ingreso = $this->calculate_dias_previos_al_ingreso($numero_de_dias_previos_al_ingreso);
			$this->cuotas_IMSS .= "<td>$numero_de_dias_previos_al_ingreso</td>";
			//Días de baja and Número de días de baja
			$dias_de_baja = $this->calculate_dias_de_baja($_trabajador);
			$numero_de_dias_de_baja = count($dias_de_baja);
			$this->cuotas_IMSS .= "<td>$numero_de_dias_de_baja</td>";
			//Incapacidad
			$dias_de_incapacidad = $this->calculate_dias_de_incapacidad($_trabajador);
			$numero_de_dias_de_incapacidad = count($dias_de_incapacidad);
			$this->cuotas_IMSS .= "<td>$numero_de_dias_de_incapacidad</td>";
			//Días cotizados and Número de días cotizados
			$dias_cotizados = $this->calculate_dias_laborados($dias_del_periodo,$dias_previos_al_ingreso,$dias_de_baja,array(),$dias_de_incapacidad,array());
			$numero_de_dias_cotizados = count($dias_cotizados);
			$this->cuotas_IMSS .= "<td>$numero_de_dias_cotizados</td>";
			//Dias laborados
			$dias_laborados = $this->calculate_dias_laborados($dias_del_periodo,$dias_previos_al_ingreso,$dias_de_baja,array(),array(),array());
			$numero_de_dias_laborados = count($dias_laborados);
			//Años de antigüedad
			$ingreso = $this->calculate_ingreso($_trabajador, 'IMSS');

			if(isset($ingreso))
			{
				$interval = date_diff(date_create($this->Limite_superior_del_periodo),date_create($ingreso));
				$dias_de_antiguedad = $interval->days;
				$anos_de_antiguedad = ceil(($dias_de_antiguedad)/365);
			}
			else
				$anos_de_antiguedad = 0;

			$this->cuotas_IMSS .=  "<td>$anos_de_antiguedad</td>";
			//Dias de vacaciones
			if($anos_de_antiguedad < 5)
				$dias_de_vacaciones = $anos_de_antiguedad * 2 + 4;
			else
				$dias_de_vacaciones = floor($anos_de_antiguedad / 5) * 2 + 12;

			$this->cuotas_IMSS .=  "<td>$dias_de_vacaciones</td>";
			//Factor de prima vacacional
			$factor_de_prima_vacacional = round($dias_de_vacaciones * 0.25 / 365,4);
			$this->cuotas_IMSS .=  "<td>$factor_de_prima_vacacional</td>";
			//Factor de aguinaldo
			$result = $this->conn->query("SELECT Dias_de_aguinaldo FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($dias_de_aguinaldo) = $this->conn->fetchRow($result);
			$factor_de_aguinaldo = round($dias_de_aguinaldo / 365,4);
			$this->cuotas_IMSS .=  "<td>$factor_de_aguinaldo</td>";
			//Factor de integración
			$factor_de_integracion = 1 + $factor_de_prima_vacacional + $factor_de_aguinaldo;
			$this->cuotas_IMSS .=  "<td>$factor_de_integracion</td>";
			//Salario diario integrado
			$salario_diario_integrado = $this->calculate_salario_diario_integrado($_trabajador,$factor_de_integracion);

			if(is_array($salario_diario_integrado))
			{
				$salario_len = count($salario_diario_integrado);
				$txt = '';

				for($i=0; $i<$salario_len; $i++)
					$txt .= $salario_diario_integrado[$i]['Cantidad'] . '/' . $salario_diario_integrado[$i]['Fecha'] . (($i == $salario_len - 1) ? '' : ',');

				$this->cuotas_IMSS .= "<td>$txt</td>";
			}
			else
				$this->cuotas_IMSS .= "<td>$salario_diario_integrado</td>";

			//Sueldo integrado
			$sueldo_integrado = $this->calculate_sueldo($salario_diario_integrado,$dias_cotizados);
			$this->cuotas_IMSS .= "<td>$sueldo_integrado</td>";

			//Cuota fija IMSS
			if($calculate_cuotas_imss != 'No administrado')
				$cuota_fija_imss = $this->calculate_cuota_fija_imss($salario_diario_integrado,$salario_diario_integrado,$dias_cotizados,$numero_de_dias_cotizados,$_trabajador);//passing "salario diario integrado" instead "salario diario" to allways cover it 
			else
				$cuota_fija_imss = 0;

			$this->cuotas_IMSS .= "<td>$cuota_fija_imss</td>";

			//Exedente 3 SMGDF patronal
			if($calculate_cuotas_imss != 'No administrado')
				$exedente_patronal = $this->calculate_exedente_patronal($salario_diario_integrado,$salario_diario_integrado,$dias_cotizados,$numero_de_dias_cotizados,$_trabajador);
			else
				$exedente_patronal = 0;

			$this->cuotas_IMSS .= "<td>$exedente_patronal</td>";

			//Exedente 3 SMGDF obrero
			if($calculate_cuotas_imss != 'No administrado')
				$exedente_obrero = $this->calculate_exedente_obrero($salario_diario_integrado,$salario_diario_integrado,$dias_cotizados,$numero_de_dias_cotizados,$_trabajador);
			else
				$exedente_obrero = 0;

			$this->cuotas_IMSS .= "<td>$exedente_obrero</td>";

			//Prestaciones en dinero obreras
			if($calculate_cuotas_imss != 'No administrado')
				$prestaciones_en_dinero_obreras = $this->calculate_prestaciones_en_dinero_obreras($salario_diario_integrado,$salario_diario_integrado,$dias_cotizados,$numero_de_dias_cotizados,$_trabajador);
			else
				$prestaciones_en_dinero_obreras = 0;

			$this->cuotas_IMSS .= "<td>$prestaciones_en_dinero_obreras</td>";

			//Prestaciones en dinero patronales
			if($calculate_cuotas_imss != 'No administrado')
				$prestaciones_en_dinero_patronales = $this->calculate_prestaciones_en_dinero_patronales($salario_diario_integrado,$dias_cotizados,$numero_de_dias_cotizados,$_trabajador);
			else
				$prestaciones_en_dinero_patronales = 0;

			$this->cuotas_IMSS .= "<td>$prestaciones_en_dinero_patronales</td>";

			//Gastos médicos y pensión obreros
			if($calculate_cuotas_imss != 'No administrado')
				$gastos_medicos_y_pension_obreros = $this->calculate_gastos_medicos_y_pension_obreros($salario_diario_integrado,$salario_diario_integrado,$dias_cotizados,$numero_de_dias_cotizados,$_trabajador);
			else
				$gastos_medicos_y_pension_obreros = 0;

			$this->cuotas_IMSS .= "<td>$gastos_medicos_y_pension_obreros</td>";

			//Gastos médicos y pensión patronales
			if($calculate_cuotas_imss != 'No administrado')
				$gastos_medicos_y_pension_patronales = $this->calculate_gastos_medicos_y_pension_patronales($salario_diario_integrado,$dias_cotizados,$numero_de_dias_cotizados,$_trabajador);
			else
				$gastos_medicos_y_pension_patronales = 0;

			$this->cuotas_IMSS .= "<td>$gastos_medicos_y_pension_patronales</td>";

			//Invalidez y vida obrera
			if($calculate_cuotas_imss != 'No administrado')
				$invalidez_y_vida_obrera = $this->calculate_invalidez_y_vida_obrera($salario_diario_integrado,$salario_diario_integrado,$dias_cotizados,$numero_de_dias_cotizados,$_trabajador);
			else
				$invalidez_y_vida_obrera = 0;

			$this->cuotas_IMSS .= "<td>$invalidez_y_vida_obrera</td>";

			//Invalidez y vida patronal
			if($calculate_cuotas_imss != 'No administrado')
				$invalidez_y_vida_patronal = $this->calculate_invalidez_y_vida_patronal($salario_diario_integrado,$dias_cotizados,$numero_de_dias_cotizados,$_trabajador);
			else
				$invalidez_y_vida_patronal = 0;

			$this->cuotas_IMSS .= "<td>$invalidez_y_vida_patronal</td>";

			//Cesantía y vejez obrera
			if($calculate_cuotas_imss != 'No administrado')
				$cesantia_y_vejez_obrera = $this->calculate_cesantia_y_vejez_obrera($salario_diario_integrado,$salario_diario_integrado,$dias_cotizados,$numero_de_dias_cotizados,$_trabajador);
			else
				$cesantia_y_vejez_obrera = 0;

			$this->cuotas_IMSS .= "<td>$cesantia_y_vejez_obrera</td>";

			//Cesantía y vejez patronal
			if($calculate_cuotas_imss != 'No administrado')
				$cesantia_y_vejez_patronal = $this->calculate_cesantia_y_vejez_patronal($salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador);
			else
				$cesantia_y_vejez_patronal = 0;

			$this->cuotas_IMSS .= "<td>$cesantia_y_vejez_patronal</td>";

			//Guardería
			if($calculate_cuotas_imss != 'No administrado')
				$guarderia = $this->calculate_guarderia($salario_diario_integrado,$dias_cotizados,$numero_de_dias_cotizados,$_trabajador);
			else
				$guarderia = 0;

			$this->cuotas_IMSS .= "<td>$guarderia</td>";

			//Retiro
			if($calculate_cuotas_imss != 'No administrado')
				$retiro = $this->calculate_retiro($salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador);
			else
				$retiro = 0;

			$this->cuotas_IMSS .= "<td>$retiro</td>";

			//INFONAVIT
			if($calculate_cuotas_imss != 'No administrado')
				$infonavit = $this->calculate_infonavit($salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador);
			else
				$infonavit = 0;

			$this->cuotas_IMSS .= "<td>$infonavit</td>";

			//Riesgo de trabajo
			if($calculate_cuotas_imss != 'No administrado')
				$riesgo_de_trabajo = $this->calculate_riesgo_de_trabajo($salario_diario_integrado,$dias_cotizados,$numero_de_dias_cotizados,$_trabajador);
			else
				$riesgo_de_trabajo = 0;

			$this->cuotas_IMSS .= "<td>$riesgo_de_trabajo</td>";
			//Total de cuotas IMSS obreras
			$total_de_cuotas_IMSS_obreras = $exedente_obrero + $prestaciones_en_dinero_obreras + $gastos_medicos_y_pension_obreros + $invalidez_y_vida_obrera + $cesantia_y_vejez_obrera;
			$this->cuotas_IMSS .= "<td>$total_de_cuotas_IMSS_obreras</td>";
			//Total de cuotas IMSS patronales
			$total_de_cuotas_IMSS_patronales = $cuota_fija_imss + $exedente_patronal + $prestaciones_en_dinero_patronales + $gastos_medicos_y_pension_patronales + $invalidez_y_vida_patronal + $cesantia_y_vejez_patronal + $guarderia + $retiro + $infonavit + $riesgo_de_trabajo;
			$this->cuotas_IMSS .= "<td>$total_de_cuotas_IMSS_patronales</td>";
			//Adeudo
			if($calculate_cuotas_imss != 'No administrado')
				$adeudo = $this->calculate_adeudo_cuotas_imss($_trabajador);
			else
				$adeudo = 0;

			$this->cuotas_IMSS .= "<td>$adeudo</td>";
			$this->cuotas_IMSS .= '</tr>';
		}

		private function calculate_prestaciones_trabajador_asalariado($_trabajador,$n)
		{
			date_default_timezone_set('America/Mexico_City');
			$this->prestaciones .= '<tr>';
			//Trabajador
			$result = $this->conn->query("SELECT Nombre, Numero_IMSS, CURP FROM Trabajador WHERE RFC = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			$row = $this->conn->fetchRow($result);
			list($nombre,$nss,$curp) = $row;
			$this->prestaciones .=  "<td>$n</td><td>$nombre</td><td>$nss</td><td>$curp</td><td>$_trabajador</td>";
			//Días del periodo and Número de días del periodo
			$numero_de_dias_del_periodo = $this->calculate_numero_de_dias_del_periodo();
			$dias_del_periodo = $this->calculate_dias_del_periodo($numero_de_dias_del_periodo);
			//Días previos al ingreso and numero de días previos al ingreso
			$numero_de_dias_previos_al_ingreso = $this->calculate_numero_de_dias_previos_al_ingreso($_trabajador, 'prestaciones');
			$dias_previos_al_ingreso = $this->calculate_dias_previos_al_ingreso($numero_de_dias_previos_al_ingreso);
			//Días de baja and Número de días de baja
			$dias_de_baja = $this->calculate_dias_de_baja($_trabajador);
			$numero_de_dias_de_baja = count($dias_de_baja);
			//Días and Número de días
			$dias = $this->calculate_dias_laborados($dias_del_periodo,$dias_previos_al_ingreso,$dias_de_baja,array(),array(),array());
			$numero_de_dias = count($dias);
			$this->prestaciones .= "<td>$numero_de_dias</td>";
			//Años de antigüedad
			$ingreso = $this->calculate_ingreso($_trabajador, 'prestaciones');

			if(isset($ingreso))
			{
				$interval = date_diff(date_create($this->Limite_superior_del_periodo),date_create($ingreso));
				$dias_de_antiguedad = $interval->days;
				$anos_de_antiguedad = ceil(($dias_de_antiguedad)/365);
			}
			else
				$anos_de_antiguedad = 0;

			$this->prestaciones .= "<td>$anos_de_antiguedad</td>";
			$result = $this->conn->query("SELECT Base_de_prestaciones FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($base_de_prestaciones) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($base_de_prestaciones == 'Pago neto/líquido')
			{
				$pago_neto = $this->get_value('Pago neto',$this->incidencias,$_trabajador);
				$pago_liquido = $this->get_value('Pago líquido',$this->incidencias,$_trabajador);
				$pago = $pago_neto > $pago_liquido ? $pago_neto : $pago_liquido;
				$salario_diario = $pago / $numero_de_dias;
			}
			else
				$salario_diario = $this->calculate_salario_diario($_trabajador,$base_de_prestaciones);

			//Sueldo
			$sueldo = $this->calculate_sueldo($salario_diario,$dias);
			$this->prestaciones .= "<td>$sueldo</td>";
			//Vacaciones
			$result = $this->conn->query("SELECT Vacaciones FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calculate_vacaciones) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($calculate_vacaciones != 'No administrado')
				$vacaciones = $this->calculate_vacaciones($anos_de_antiguedad,$salario_diario,$_trabajador);
			else
				$vacaciones = 0;

			$this->prestaciones .= "<td>$vacaciones</td>";
			//Retención proporcional de vacaciones
			$retencion_proporcional_de_vacaciones = $this->calculate_retencion_proporcional_de_vacaciones($vacaciones,$numero_de_dias);
			$this->prestaciones .= "<td>$retencion_proporcional_de_vacaciones</td>";
			//Prima vacacional
			$result = $this->conn->query("SELECT Prima_vacacional FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calculate_prima_vacacional) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($calculate_prima_vacacional != 'No administrado')
				$prima_vacacional = $this->calculate_prima_vacacional($vacaciones);
			else
				$prima_vacacional = 0;

			$this->prestaciones .= "<td>$prima_vacacional</td>";
			//Retención proporcional de prima vacacional
			$retencion_proporcional_de_prima_vacacional = $this->calculate_retencion_proporcional_de_prima_vacacional($retencion_proporcional_de_vacaciones);
			$this->prestaciones .= "<td>$retencion_proporcional_de_prima_vacacional</td>";
			//Aguinaldo
			$result = $this->conn->query("SELECT Aguinaldo FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calculate_aguinaldo) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($calculate_aguinaldo != 'No administrado')
				$aguinaldo = $this->calculate_aguinaldo($salario_diario,$_trabajador);
			else
				$aguinaldo = 0;

			$this->prestaciones .= "<td>$aguinaldo</td>";
			//Retención proporcional de aguinaldo
			$retencion_proporcional_de_aguinaldo = $this->calculate_retencion_proporcional_de_aguinaldo($salario_diario,$numero_de_dias,$_trabajador);
			$this->prestaciones .= "<td>$retencion_proporcional_de_aguinaldo</td>";
			//Prima de antigüedad
			$result = $this->conn->query("SELECT Prima_de_antiguedad FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calculate_prima_de_antiguedad) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($calculate_prima_de_antiguedad != 'No administrado')
				$prima_de_antiguedad = $this->calculate_prima_de_antiguedad($salario_diario,$_trabajador);
			else
				$prima_de_antiguedad = 0;

			$this->prestaciones .= "<td>$prima_de_antiguedad</td>";
			//Retención proporcional de prima de antigüedad

			if($calculate_prima_de_antiguedad != 'No administrado')
			{
				$result = $this->conn->query("SELECT Quince_anos FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($quince_anos) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);

				if($quince_anos != 'true')
					$retencion_proporcional_de_prima_de_antiguedad = $this->calculate_retencion_proporcional_de_prima_de_antiguedad($salario_diario,$_trabajador,$numero_de_dias);
				elseif($anos_de_antiguedad >= 15)
					$retencion_proporcional_de_prima_de_antiguedad = $this->calculate_retencion_proporcional_de_prima_de_antiguedad($salario_diario,$_trabajador,$numero_de_dias);
				else
					$retencion_proporcional_de_prima_de_antiguedad = 0;

			}
			else
				$retencion_proporcional_de_prima_de_antiguedad = 0;

			$this->prestaciones .= "<td>$retencion_proporcional_de_prima_de_antiguedad</td>";
			//Total de prestaciones
			$total_de_prestaciones = $vacaciones + $prima_vacacional + $aguinaldo + $prima_de_antiguedad;
			$this->prestaciones .= "<td>$total_de_prestaciones</td>";
			//Total de retenciones
			$total_de_retenciones = $retencion_proporcional_de_vacaciones + $retencion_proporcional_de_prima_vacacional + $retencion_proporcional_de_aguinaldo + $retencion_proporcional_de_prima_de_antiguedad;
			$this->prestaciones .= "<td>$total_de_retenciones</td>";
			$this->prestaciones .= '</tr>';
		}

		private function calculate_nomina_trabajador_asalariado($_trabajador,$n)
		{
			date_default_timezone_set('America/Mexico_City');
			$this->nomina_asalariados .= '<tr>';
			//Trabajador
			$result = $this->conn->query("SELECT Nombre, Numero_IMSS, CURP FROM Trabajador WHERE RFC = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($nombre,$nss,$curp) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$this->nomina_asalariados .=  "<td>$n</td><td>$nombre</td><td>$nss</td><td>$curp</td><td>$_trabajador</td>";
			//Número de días laborados
			$numero_de_dias_laborados = $this->get_value('Número de días laborados',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$numero_de_dias_laborados</td>";
			//Salario
			$salario = $this->get_value('Salario diario',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$salario</td>";
			//Sueldo
			$sueldo = $this->get_value('Sueldo',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$sueldo</td>";
			//Subsidio al empleo
			$subsidio_al_empleo = $this->get_value('Subsidio al empleo',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$subsidio_al_empleo</td>";
			//Horas extra
			$horas_extra = $this->get_value('Horas extra',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$horas_extra</td>";
			//Prima dominical
			$prima_dominical = $this->get_value('Prima dominical',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$prima_dominical</td>";
			//Días de descanso
			$dias_de_descanso = $this->get_value('Días de descanso',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$dias_de_descanso</td>";
			//Premios de puntualidad y asistencia
			$premios_de_puntualidad_y_asistencia = $this->get_value('Premios de puntualidad y asistencia',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$premios_de_puntualidad_y_asistencia</td>";
			//Bonos de productividad
			$bonos_de_productividad = $this->get_value('Bonos de productividad',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$bonos_de_productividad</td>";
			//Estímulos
			$estimulos = $this->get_value('Estímulos',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$estimulos</td>";
			//Compensaciones
			$compensaciones = $this->get_value('Compensaciones',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$compensaciones</td>";
			//Despensa
			$despensa = $this->get_value('Despensa',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$despensa</td>";
			//Comida
			$comida = $this->get_value('Comida',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$comida</td>";
			//Alimentación
			$alimentacion = 0;
			$this->nomina_asalariados .= "<td>$alimentacion</td>";
			//Habitación
			$habitacion = 0;
			$this->nomina_asalariados .= "<td>$habitacion</td>";
			//Aportacion patronal al fondo de ahorro
			$aportacion_patronal_al_fondo_de_ahorro = $this->calculate_aportacion_al_fondo_de_ahorro($_trabajador);
			$this->nomina_asalariados .= "<td>$aportacion_patronal_al_fondo_de_ahorro</td>";
			//Total de percepciones
			$total_de_percepciones = $this->calculate_total_de_percepciones($_trabajador);
			$this->nomina_asalariados .= "<td>$total_de_percepciones</td>";
			//ISR
			$isr = $this->get_value('ISR',$this->ISRasalariados,$_trabajador);
			$this->nomina_asalariados .= "<td>$isr</td>";
			//cuotas IMSS
			$result = $this->conn->query("SELECT dcipn FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($dcipn) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($dcipn == 'true')
			{
				$cio = $this->get_value('Total de cuotas IMSS obreras',$this->cuotas_IMSS,$_trabajador);
				$cip = $this->get_value('Total de cuotas IMSS patronales',$this->cuotas_IMSS,$_trabajador);
				$adeudo = $this->get_value('Adeudo',$this->cuotas_IMSS,$_trabajador);
				$cuotas_IMSS = $cio + $cip + $adeudo;
			}
			else
				$cuotas_IMSS = $this->calculate_cuotas_IMSS_trabajador_asalariado($_trabajador);

			$this->nomina_asalariados .= "<td>$cuotas_IMSS</td>";
			//Retención por alimentación
			$retencion_por_alimentacion = 0;
			$this->nomina_asalariados .= "<td>$retencion_por_alimentacion</td>";
			//Retención por habitación
			$retencion_por_habitacion = 0;
			$this->nomina_asalariados .= "<td>$retencion_por_habitacion</td>";
			//Retención INFONAVIT
			$salario_diario_integrado = explode(',',$this->get_value('Salario diario integrado',$this->cuotas_IMSS,$_trabajador));
			$numero_de_dias_del_periodo = $this->get_value('Número de días del periodo',$this->ISRasalariados,$_trabajador);
			$retencion_infonavit = $this->calculate_retencion_infonavit($_trabajador,$numero_de_dias_del_periodo,$salario_diario_integrado);
			$this->nomina_asalariados .= "<td>$retencion_infonavit</td>";
			//Retención FONACOT
			$retencion_fonacot = $this->calculate_retencion_fonacot($_trabajador,$numero_de_dias_del_periodo);
			$this->nomina_asalariados .= "<td>$retencion_fonacot</td>";
			//Aportacion del trabajador al fondo de ahorro
			$aportacion_del_trabajador_al_fondo_de_ahorro = $this->calculate_aportacion_al_fondo_de_ahorro($_trabajador);
			$this->nomina_asalariados .= "<td>$aportacion_del_trabajador_al_fondo_de_ahorro</td>";
			//Pensión alimenticia
			$pension_alimenticia = $this->calculate_pension_alimenticia($_trabajador);
			$this->nomina_asalariados .= "<td>$pension_alimenticia</td>";
			//Retardos
			$retardos = $this->get_value('Retardos',$this->incidencias,$_trabajador);
			$this->nomina_asalariados .= "<td>$retardos</td>";
			//Prestaciones
			$result = $this->conn->query("SELECT dpn FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($dpn) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$prestaciones = $dpn == 'true' ? $this->get_value('Total de retenciones',$this->prestaciones,$_trabajador) : 0.00;
			$this->nomina_asalariados .= "<td>$prestaciones</td>";
			//Gestión administrativa
			$gestion_administrativa = $this->calculate_gestion_administrativa($_trabajador,'nomina_asalariados');
			$this->nomina_asalariados .= "<td>$gestion_administrativa</td>";
			//Total de deducciones
			$total_de_deducciones = $this->calculate_total_de_deducciones($_trabajador);
			$this->nomina_asalariados .= "<td>$total_de_deducciones</td>";
			//Saldo
			$saldo = $total_de_percepciones - $total_de_deducciones;
			$this->nomina_asalariados .= "<td>$saldo</td>";
			//Pago neto
			$pago_neto = $this->get_value('Pago neto',$this->incidencias,$_trabajador);
			$this->nomina_asalariados .= "<td>$pago_neto</td>";
			//Pago líquido
			$pago_liquido = $this->get_value('Pago líquido',$this->incidencias,$_trabajador);
			$this->nomina_asalariados .= "<td>$pago_liquido</td>";
			//Fondo de garantía
			$this->nomina_asalariados .= "<td>0.00</td>";
			//Pago por seguro de vida
			$pago_por_seguro_de_vida = $this->calculate_pago_por_seguro_de_vida($_trabajador);
			$this->nomina_asalariados .= "<td>$pago_por_seguro_de_vida</td>";
			//Prestamo del fondo de ahorro
			$prestamo_del_fondo_de_ahorro = $this->calculate_prestamo_del_fondo_de_ahorro($_trabajador);
			$this->nomina_asalariados .= "<td>$prestamo_del_fondo_de_ahorro</td>";
			//Prestamo de caja
			$prestamo_caja = $this->calculate_prestamo_caja($_trabajador);
			$this->nomina_asalariados .= "<td>$prestamo_caja</td>";
			//Prestamo de cliente
			$prestamo_cliente = $this->calculate_prestamo_cliente($_trabajador);
			$this->nomina_asalariados .= "<td>$prestamo_cliente</td>";
			//Prestamo de administradora
			$prestamo_administradora = $this->calculate_prestamo_administradora($_trabajador);
			$this->nomina_asalariados .= "<td>$prestamo_administradora</td>";
			//Neto a recibir
			$neto_a_recibir = '';
			$this->nomina_asalariados .= "<td>$neto_a_recibir</td>";
			//Forma de pago
			$forma_de_pago = $this->calculate_forma_de_pago($_trabajador);
			$this->nomina_asalariados .= "<td>$forma_de_pago</td>";
			//Status
			$result = $this->conn->query("SELECT Status FROM nomina_asalariados WHERE Trabajador = '$_trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($status) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if(!isset($status) || $status == '')
				$status = 'Sin timbrar';

			$this->nomina_asalariados .= "<td>$status</td>";
			$this->nomina_asalariados .= '</tr>';
		}

		private function calculate_nomina_trabajador_asimilable($_trabajador,$n)
		{
			date_default_timezone_set('America/Mexico_City');
			$this->nomina_asimilables .= '<tr>';
			//Trabajador
			$result = $this->conn->query("SELECT Nombre, CURP FROM Trabajador WHERE RFC = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			$row = $this->conn->fetchRow($result);
			list($nombre,$curp) = $row;
			$this->nomina_asimilables .=  "<td>$n</td><td>$nombre</td><td>$curp</td><td>$_trabajador</td>";
			//Número de días del periodo
			$numero_de_dias_del_periodo = $this->get_value('Número de días del periodo',$this->ISRasimilables,$_trabajador);
			$this->nomina_asimilables .= "<td>$numero_de_dias_del_periodo</td>";
			//Honorarios asimilados
			$honorarios_asimilados = $this->get_value('Honorarios asimilados',$this->ISRasimilables,$_trabajador);
			$this->nomina_asimilables .= "<td>$honorarios_asimilados</td>";
			//Total de percepciones
			$total_de_percepciones = $honorarios_asimilados;
			$this->nomina_asimilables .= "<td>$total_de_percepciones</td>";
			//ISR
			$isr = $this->get_value('ISR',$this->ISRasimilables,$_trabajador);
			$this->nomina_asimilables .= "<td>$isr</td>";
			//Gestión administrativa
			$gestion_administrativa = $this->calculate_gestion_administrativa($_trabajador,'nomina_asimilables');
			$this->nomina_asimilables .= "<td>$gestion_administrativa</td>";
			//Total de deducciones
			$total_de_deducciones = $isr + $gestion_administrativa;
			$this->nomina_asimilables .= "<td>$total_de_deducciones</td>";
			//Saldo
			$saldo = $total_de_percepciones - $total_de_deducciones;
			$this->nomina_asimilables .= "<td>$saldo</td>";
			//Pago neto
			$pago_neto = $this->get_value('Pago neto',$this->incidencias,$_trabajador);
			$this->nomina_asimilables .= "<td>$pago_neto</td>";
			//Pago líquido
			$pago_liquido = $this->get_value('Pago líquido',$this->incidencias,$_trabajador);
			$this->nomina_asimilables .= "<td>$pago_liquido</td>";
			//Pago por seguro de vida
			$pago_por_seguro_de_vida = $this->calculate_pago_por_seguro_de_vida($_trabajador);
			$this->nomina_asimilables .= "<td>$pago_por_seguro_de_vida</td>";
			//Prestamo de caja
			$prestamo_caja = $this->calculate_prestamo_caja($_trabajador);
			$this->nomina_asimilables .= "<td>$prestamo_caja</td>";
			//Prestamo de cliente
			$prestamo_cliente = $this->calculate_prestamo_cliente($_trabajador);
			$this->nomina_asimilables .= "<td>$prestamo_cliente</td>";
			//Prestamo de administradora
			$prestamo_administradora = $this->calculate_prestamo_administradora($_trabajador);
			$this->nomina_asimilables .= "<td>$prestamo_administradora</td>";
			//Neto a recibir
			$neto_a_recibir = '';
			$this->nomina_asimilables .= "<td>$neto_a_recibir</td>";
			//Forma de pago
			$forma_de_pago = $this->calculate_forma_de_pago($_trabajador);
			$this->nomina_asimilables .= "<td>$forma_de_pago</td>";
			//Status
			$result = $this->conn->query("SELECT Status FROM nomina_asimilables WHERE Trabajador = '$_trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($status) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if(!isset($status) || $status == '')
				$status = 'Sin timbrar';

			$this->nomina_asimilables .= "<td>$status</td>";
			$this->nomina_asimilables .= '</tr>';
		}

		private function calculate_numero_de_dias_del_periodo()
		{
			date_default_timezone_set('America/Mexico_City');
			$limite_inferior = date_create($this->Limite_inferior_del_periodo);
			$limite_superior = date_create($this->Limite_superior_del_periodo);
			$numero_de_dias_del_periodo = 0;

			if($limite_superior >= $limite_inferior)
			{
				$interval = $limite_inferior->diff($limite_superior);
				$numero_de_dias_del_periodo = $interval->days + 1;
			}
			else
				$numero_de_dias_del_periodo = 0;

			return $numero_de_dias_del_periodo;
		}

		private function calculate_dias_del_periodo($numero_de_dias_del_periodo)
		{
			date_default_timezone_set('America/Mexico_City');
			$limite_inferior = date_create($this->Limite_inferior_del_periodo);
			$limite_superior = date_create($this->Limite_superior_del_periodo);
			$dias_del_periodo = array();
			$txt = '';

			for($i=0; $i<$numero_de_dias_del_periodo; $i++)
			{
				$interval = new DateInterval('P' . $i . 'D');
				$day = $limite_inferior->add($interval);
				$dias_del_periodo[$i] = $day->format('Y-m-d');
				$day = $limite_inferior->sub($interval);
			}

			return $dias_del_periodo;
		}

		private function calculate_ingreso($trabajador, $tabla)
		{
			$ingreso = Null;
			$result = $this->conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '$trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND Fecha_de_reingreso != '0000-00-00' AND DATEDIFF(Fecha_de_reingreso, '{$this->Limite_superior_del_periodo}') <= 0 AND DATEDIFF(Fecha_de_reingreso, Fecha_de_baja) > 1 ORDER BY Fecha_de_reingreso DESC LIMIT 1");
			list($ingreso) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if(!isset($ingreso))
			{

				if($tabla == 'prestaciones')
					$result = $this->conn->query("SELECT Antiguedad_prestaciones FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				else
					$result = $this->conn->query("SELECT Antiguedad_IMSS FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");

				list($antiguedad) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);

				if($antiguedad == 'Servicio')
					$result = $this->conn->query("SELECT Fecha_de_ingreso_servicio FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
				else
					$result = $this->conn->query("SELECT Fecha_de_ingreso_cliente FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");

				list($ingreso) = $this->conn->fetchRow($result);
			}

			return $ingreso;
		}

		private function calculate_numero_de_dias_previos_al_ingreso($_trabajador, $tabla)
		{
			date_default_timezone_set('America/Mexico_City');
			$limite_inferior = date_create($this->Limite_inferior_del_periodo);
			$ingreso = $this->calculate_ingreso($_trabajador, $tabla);

			if(isset($ingreso) && $ingreso > $this->Limite_inferior_del_periodo)
			{
				$interval = date_diff($limite_inferior,date_create($ingreso));
				$numero_de_dias_previos_al_ingreso = $interval->days;
			}
			else
				$numero_de_dias_previos_al_ingreso = 0;

			return $numero_de_dias_previos_al_ingreso;
		}

		private function calculate_dias_previos_al_ingreso($numero_de_dias_previos_al_ingreso)
		{
			date_default_timezone_set('America/Mexico_City');
			$limite_inferior = date_create($this->Limite_inferior_del_periodo);
			$dias_previos_al_ingreso = array();

			if($numero_de_dias_previos_al_ingreso > 0)
			{

				for($i=0; $i<$numero_de_dias_previos_al_ingreso; $i++)
				{
					$interval = new DateInterval('P'. $i .'D');
					$day = $limite_inferior->add($interval);
					$dias_previos_al_ingreso[$i] = $day->format('Y-m-d');
					$day = $limite_inferior->sub($interval);
				}

			}

			return $dias_previos_al_ingreso;
		}

		public function calculate_dias_de_baja($_trabajador)
		{
			date_default_timezone_set('America/Mexico_City');
			$numero_de_dias_del_periodo = 0;
			$limite_inferior = date_create($this->Limite_inferior_del_periodo);
			$limite_superior = date_create($this->Limite_superior_del_periodo);

			if($limite_superior > $limite_inferior)
			{
				$interval = $limite_inferior->diff($limite_superior);
				$numero_de_dias_del_periodo = $interval->days + 1;
			}
			else
				$numero_de_dias_del_periodo = 0;

			$dias_del_periodo = array();

			for($i=0; $i<$numero_de_dias_del_periodo; $i++)
			{
				$interval = new DateInterval('P' . $i . 'D');
				$day = $limite_inferior->add($interval);
				$dias_del_periodo[$i] = $day->format('Y-m-d');
				$day = $limite_inferior->sub($interval);
			}

			$result = $this->conn->query("SELECT Fecha_de_baja, Fecha_de_reingreso FROM Baja WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_baja,'$this->Limite_superior_del_periodo') <= 0");
			$dias_de_baja = array();
			$j = 0;

			while(list($fecha_de_baja,$fecha_de_reingreso) = $this->conn->fetchRow($result))
			{
				$baja = date_create($fecha_de_baja);

				if($fecha_de_reingreso != '0000-00-00')
					$reingreso = date_create($fecha_de_reingreso);
				else
				{
					$day_interval = new DateInterval('P1D');
					$_aux = $limite_superior->add($day_interval);
					$aux = $_aux->format('Y-m-d');
					$limite_superior->sub($day_interval);
					$reingreso = date_create($aux);
				}

				$interval = date_diff($baja,$reingreso);
				$numero_de_dias_de_baja = $interval->format('%r%a');
				$day_interval = new DateInterval('P1D');

				for($i=0; $i<$numero_de_dias_de_baja; $i++)
				{
					$day = $baja->add($day_interval);

					if($limite_inferior <= $day && $day <= $limite_superior && $day != $reingreso)
					{
						$dias_de_baja[$j] = $day->format('Y-m-d');
						$j++;
					}

				}

			}

			return $dias_de_baja;
		}

		private function calculate_faltas($_trabajador)
		{

			date_default_timezone_set('America/Mexico_City');
			$limite_inferior = date_create($this->Limite_inferior_del_periodo);
			$limite_superior = date_create($this->Limite_superior_del_periodo);
			$fechas = $this->get_value('Faltas',$this->incidencias,$_trabajador);
			$_faltas = array();
			$j = 0;
			$values = explode(',',$fechas);
			$len = count($values);

			for($i=0; $i<$len; $i++)
			{

				if($values[$i] != '')//comparison needed because of the last comma ('DATE','DATE','')
				{
					$falta = date_create($values[$i]);

					if($limite_inferior <= $falta && $limite_superior >= $falta)
					{
						$_faltas[$j] = $values[$i];
						$j++;
					}

				}

			}

			return $_faltas;
		}

		private function calculate_dias_de_incapacidad($_trabajador)
		{
			date_default_timezone_set('America/Mexico_City');
			$limite_inferior = date_create($this->Limite_inferior_del_periodo);
			$limite_superior = date_create($this->Limite_superior_del_periodo);
			$dias_de_incapacidad = array();
			$result = $this->conn->query("SELECT Fecha_de_inicio,Fecha_de_termino FROM Incapacidad WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			$j = 0;

			while(list($fecha_de_inicio,$fecha_de_termino) = $this->conn->fetchRow($result))
			{

				if(isset($fecha_de_inicio) && isset($fecha_de_termino))
				{
					$inicio = date_create($fecha_de_inicio);
					$termino = date_create($fecha_de_termino);
					$interval = date_diff($inicio,$termino);
					$numero_de_dias_de_incapacidad = $interval->format('%r%a') + 1;

					for($i=0; $i<$numero_de_dias_de_incapacidad; $i++)
					{
						$days_interval = new DateInterval('P' . $i . 'D');
						$day = $inicio->add($days_interval);

						if($limite_inferior <= $day && $day <= $limite_superior)
						{
							$dias_de_incapacidad[$j] = $day->format('Y-m-d');
							$j++;
						}

						$day = $inicio->sub($days_interval);
					}

				}

			}

			return $dias_de_incapacidad;
		}

		private function calculate_dias_de_vacaciones($_trabajador)
		{
			date_default_timezone_set('America/Mexico_City');
			$limite_inferior = date_create($this->Limite_inferior_del_periodo);
			$limite_superior = date_create($this->Limite_superior_del_periodo);
			$dias_de_vacaciones = array();
			$result = $this->conn->query("SELECT Fecha_de_inicio, Fecha_de_termino FROM Vacaciones WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			$j = 0;

			while(list($fecha_de_inicio,$fecha_de_termino) = $this->conn->fetchRow($result))
			{

				if(isset($fecha_de_inicio) && isset($fecha_de_termino))
				{
					$inicio = date_create($fecha_de_inicio);
					$termino = date_create($fecha_de_termino);
					$interval = date_diff($inicio,$termino);
					$numero_de_dias_de_vacaciones = $interval->format('%r%a') + 1;

					for($i=0; $i<$numero_de_dias_de_vacaciones; $i++)
					{
						$days_interval = new DateInterval('P' . $i . 'D');
						$day = $inicio->add($days_interval);

						if($limite_inferior <= $day && $day <= $limite_superior)
						{
							$dias_de_vacaciones[$j] = $day->format('Y-m-d');
							$j++;
						}

						$day = $inicio->sub($days_interval);
					}

				}

			}

			return $dias_de_vacaciones;
		}

		private function calculate_dias_laborados($dias_del_periodo,$dias_previos_al_ingreso,$dias_de_baja,$_faltas,$dias_de_incapacidad,$dias_de_vacaciones)
		{
			$numero_de_dias_del_periodo = count($dias_del_periodo);
			$numero_de_dias_previos_al_ingreso = count($dias_previos_al_ingreso);
			$numero_de_dias_de_baja = count($dias_de_baja);
			$numero_de_faltas = count($_faltas);
			$numero_de_dias_de_incapacidad = count($dias_de_incapacidad);
			$numero_de_dias_de_vacaciones = count($dias_de_vacaciones);
			$dias_laborados = $dias_del_periodo;

			if($numero_de_dias_previos_al_ingreso > 0)
			{

				for($i=0; $i<$numero_de_dias_previos_al_ingreso; $i++)
				{
					$len = count($dias_laborados);

					for($j=0; $j<$len; $j++)

						if($dias_previos_al_ingreso[$i] == $dias_laborados[$j])
						{
							$dias_laborados = $this->extract($dias_laborados,$dias_laborados[$j]);
							break;
						}

				}

			}

			if($numero_de_dias_de_baja > 0)
			{

				for($i=0; $i<$numero_de_dias_de_baja; $i++)
				{
					$len = count($dias_laborados);

					for($j=0; $j<$len; $j++)

						if($dias_de_baja[$i] == $dias_laborados[$j])
						{
							$dias_laborados = $this->extract($dias_laborados,$dias_laborados[$j]);
							break;
						}

				}

			}

			if($numero_de_faltas > 0)
			{

				for($i=0; $i<$numero_de_faltas; $i++)
				{
					$len = count($dias_laborados);

					for($j=0; $j<$len; $j++)

						if($_faltas[$i] == $dias_laborados[$j])
						{
							$dias_laborados = $this->extract($dias_laborados,$dias_laborados[$j]);
							break;
						}

				}

			}

			if($numero_de_dias_de_incapacidad > 0)
			{

				for($i=0; $i<$numero_de_dias_de_incapacidad; $i++)
				{
					$len = count($dias_laborados);

					for($j=0; $j<$len; $j++)

						if($dias_de_incapacidad[$i] == $dias_laborados[$j])
						{
							$dias_laborados = $this->extract($dias_laborados,$dias_laborados[$j]);
							break;
						}

				}

			}

			if($numero_de_dias_de_vacaciones > 0)
			{

				for($i=0; $i<$numero_de_dias_de_vacaciones; $i++)
				{
					$len = count($dias_laborados);

					for($j=0; $j<$len; $j++)

						if($dias_de_vacaciones[$i] == $dias_laborados[$j])
						{
							$dias_laborados = $this->extract($dias_laborados,$dias_laborados[$j]);
							break;
						}

				}

			}

			return $dias_laborados;
		}

		private function myArray2Str($array)
		{
			$txt = '';
			$len = count($array);

			for($i=0; $i<$len; $i++)
				$txt .= $array[$i] . (($i == $len - 1) ? '' : ',');

			return $txt;
		}

		private function extract($array,$element)
		{
			$aux = array();
			$len = count($array);
			$j = 0;

			for($i=0; $i<$len; $i++)

				if($array[$i] != $element)
				{
					$aux[$j] = $array[$i];
					$j++;
				}

			return $aux;
		}

		public function calculate_salario_diario($_trabajador,$base)
		{
			date_default_timezone_set('America/Mexico_City');

			if($base == 'Salario mínimo')
			{
				//getting every "Sucursal" for this "Empresa"
				$empresa = $this->get_empresa();
				$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");

				if($this->conn->num_rows($result) > 0)//if there are "Sucursales"
				{
					$this->conn->freeResult($result);
					//getting every "Zona geografica" and "Fecha_de_ingreso" related to every "Sucursal" between "Limite inferior" and "Limite superior"
					$result = $this->conn->query("SELECT Sucursal.Zona_geografica, Trabajador_Sucursal.Fecha_de_ingreso FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '$_trabajador' AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Trabajador_Sucursal.Fecha_de_ingreso,'{$this->Limite_inferior_del_periodo}') >= 0 AND DATEDIFF('{$this->Limite_superior_del_periodo}', Trabajador_Sucursal.Fecha_de_ingreso) >= 0 ORDER BY Trabajador_Sucursal.Fecha_de_ingreso ASC");
					$i = 0;
					$zonas = array();

					while(list($zona, $fecha) = $this->conn->fetchRow($result))
					{

						if($i == 0 && $fecha > $this->Limite_inferior_del_periodo)
						{
							$result1 = $this->conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '$_trabajador' AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_inferior_del_periodo}',Trabajador_Sucursal.Fecha_de_ingreso) > 0 ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");

							if($this->conn->num_rows($result1) > 0)
								list($zona1) = $this->conn->fetchRow($result1);
							else
								$zona1 = $zona;//when worker enters after "Limite inferior del periodo"

							$zonas[$i] = array($zona1,$this->Limite_inferior_del_periodo);
							$i++;
						}

						if(($i > 0 && $zona != $zonas[$i - 1][0]) || $i == 0)//recording only distinct "Zonas geograficas"
						{
							$zonas[$i] = array($zona,$fecha);
							$i++;
						}

					}

					if(count($zonas) == 0)
					{
						$result = $this->conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '$_trabajador' AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_inferior_del_periodo}',Trabajador_Sucursal.Fecha_de_ingreso) > 0 ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");
						list($zona) = $this->conn->fetchRow($result);
						$zonas[$i] = array($zona,$this->Limite_inferior_del_periodo);
					}

				}
				else//There are not "Sucursales"
				{
					$result = $this->conn->query("SELECT Zona_geografica FROM Empresa WHERE RFC = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($zona) = $this->conn->fetchRow($result);

					if(isset($zona))
					{
						$zonas[0] = array($zona,$this->Limite_inferior_del_periodo);
						$this->conn->freeResult($result);
					}
					else
						$sd = 0.00;

				}

				//getting every "Salario minimo" for every "Zona geografica"
				$salario_diario = array();
				$len = count($zonas);

				for($i=0, $j=0; $i<$len; $i++, $j++)
				{
					$year = substr($zonas[$i][1], 0, 4);
					$result = $this->conn->query("SELECT Salario_minimo.{$zonas[$i][0]}, Trabajador_Salario_minimo.Fecha FROM Trabajador_Salario_minimo LEFT JOIN Salario_minimo ON Trabajador_Salario_minimo.Salario_minimo = Salario_minimo.Codigo WHERE Trabajador_Salario_minimo.Trabajador = '$_trabajador' AND Trabajador_Salario_minimo.Servicio = '{$this->Servicio}' AND Trabajador_Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Ano = YEAR(Trabajador_Salario_minimo.Fecha) AND DATEDIFF('{$zonas[$i][1]}', Trabajador_Salario_minimo.Fecha) <= 0" . (isset($zonas[$i+1]) ? " AND DATEDIFF('{$zonas[$i+1][1]}', Trabajador_Salario_minimo.Fecha) >= 0" : "") . " AND DATEDIFF('{$this->Limite_superior_del_periodo}', Trabajador_Salario_minimo.Fecha) >= 0 ORDER BY Trabajador_Salario_minimo.Fecha ASC");
					list($cantidad,$fecha) = $this->conn->fetchRow($result);

					if($this->conn->num_rows($result) == 0)
					{
						$result1 = $this->conn->query("SELECT Salario_minimo.{$zonas[$i][0]} FROM Trabajador_Salario_minimo LEFT JOIN Salario_minimo ON Trabajador_Salario_minimo.Salario_minimo = Salario_minimo.Codigo WHERE Trabajador_Salario_minimo.Trabajador = '$_trabajador' AND Trabajador_Salario_minimo.Servicio = '{$this->Servicio}' AND Trabajador_Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Ano = '$year' AND DATEDIFF('{$zonas[$i][1]}', Trabajador_Salario_minimo.Fecha) >= 0 ORDER BY Trabajador_Salario_minimo.Fecha DESC LIMIT 1");
						list($cantidad1) = $this->conn->fetchRow($result1);
						$salario_diario[$j]['Cantidad'] = isset($cantidad1) ? $cantidad1 : 0.00;
						$salario_diario[$j]['Fecha'] = $zonas[$i][1];
					}
					elseif($this->conn->num_rows($result) > 0 && $fecha > $zonas[$i][1])
					{
						$result1 = $this->conn->query("SELECT Salario_minimo.{$zonas[$i][0]} FROM Trabajador_Salario_minimo LEFT JOIN Salario_minimo ON Trabajador_Salario_minimo.Salario_minimo = Salario_minimo.Codigo WHERE Trabajador_Salario_minimo.Trabajador = '$_trabajador' AND Trabajador_Salario_minimo.Servicio = '{$this->Servicio}' AND Trabajador_Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Ano = '$year' AND DATEDIFF('{$zonas[$i][1]}', Trabajador_Salario_minimo.Fecha) >= 0 ORDER BY Trabajador_Salario_minimo.Fecha DESC LIMIT 1");
						list($cantidad1) = $this->conn->fetchRow($result1);
						$salario_diario[$j]['Cantidad'] = isset($cantidad1) ? $cantidad1 : 0.00;
						$salario_diario[$j]['Fecha'] = $zonas[$i][1];
						$j++;
					}

					$this->conn->data_seek($result,0);//reset result

					while(list($cantidad,$fecha) = $this->conn->fetchRow($result))
					{
						$salario_diario[$j]['Cantidad'] = $cantidad;
						$salario_diario[$j]['Fecha'] = $fecha;
						$j++;
					}

				}

				if(substr($this->Limite_inferior_del_periodo, 0, 4) != substr($this->Limite_superior_del_periodo, 0, 4))//if "nomina" covers two years
				{
					$flag = true;
					$new_year = substr($this->Limite_superior_del_periodo, 0, 4) . '-01' . '-01';
					$len = count($salario_diario);

					for($i=0; $i<$len; $i++)

						if($salario_diario[$i]['Fecha'] == $new_year)
							$flag = false;

					if($flag)//if there is not a "salario" related to change of year
					{
						$i = 0;

						//getting the last "zona geografica" for the first year
						while(isset($zonas[$i]) && substr($this->Limite_inferior_del_periodo, 0, 4) == substr($zonas[$i][1], 0, 4))
						{
							$zona = $zonas[$i][0];
							$i++;
						}

						//getting the last salary code
						$result = $this->conn->query("SELECT Salario_minimo.Codigo FROM Trabajador_Salario_minimo LEFT JOIN Salario_minimo ON Trabajador_Salario_minimo.Salario_minimo = Salario_minimo.Codigo WHERE Trabajador_Salario_minimo.Trabajador = '$_trabajador' AND Trabajador_Salario_minimo.Servicio = '{$this->Servicio}' AND Trabajador_Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$new_year', Trabajador_Salario_minimo.Fecha) >= 0 ORDER BY Trabajador_Salario_minimo.Fecha DESC LIMIT 1");
						list($codigo) = $this->conn->fetchRow($result);
						$year = substr($this->Limite_superior_del_periodo, 0, 4);
						$result = $this->conn->query("SELECT Salario_minimo.$zona FROM Trabajador_Salario_minimo LEFT JOIN Salario_minimo ON Trabajador_Salario_minimo.Salario_minimo = Salario_minimo.Codigo WHERE Trabajador_Salario_minimo.Trabajador = '$_trabajador' AND Trabajador_Salario_minimo.Servicio = '{$this->Servicio}' AND Salario_minimo.Codigo = '$codigo' AND Trabajador_Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Ano = '$year' AND DATEDIFF('$new_year', Trabajador_Salario_minimo.Fecha) > 0 ORDER BY Trabajador_Salario_minimo.Fecha DESC LIMIT 1");
						list($cantidad) = $this->conn->fetchRow($result);
						$_salario_diario = array();
						$i = $len - 1;

						//inserting a "salario" related to change of year
						if(isset($cantidad))
						{

							while($salario_diario[$i]['Fecha'] > $new_year)
								$i--;

							for($j=0; $j<$len; $j++)

								if($j < $i)
								{
									$_salario_diario[$j]['Cantidad'] = $salario_diario[$j]['Cantidad'];
									$_salario_diario[$j]['Fecha'] = $salario_diario[$j]['Fecha'];
								}
								elseif($j == $i)
								{
									$_salario_diario[$j]['Cantidad'] = $salario_diario[$j]['Cantidad'];
									$_salario_diario[$j]['Fecha'] = $salario_diario[$j]['Fecha'];
									$_salario_diario[$j + 1]['Cantidad'] = $cantidad;
									$_salario_diario[$j + 1]['Fecha'] = $new_year;
								}
								else
								{
									$_salario_diario[$j + 1]['Cantidad'] = $salario_diario[$j]['Cantidad'];
									$_salario_diario[$j + 1]['Fecha'] = $salario_diario[$j]['Fecha'];
								}


							$salario_diario = $_salario_diario;
						}

					}

				}

				if(count($salario_diario) > 1)
					$sd = array_reverse($salario_diario);//dates must go descendant
				else
					$sd = $salario_diario[0]['Cantidad'];

			}
			else
			{
				$limite_inferior = date_create($this->Limite_inferior_del_periodo);
				$limite_superior = date_create($this->Limite_superior_del_periodo);
				$result = $this->conn->query("SELECT Cantidad, Fecha FROM Salario_diario WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}', Fecha) >= 0 ORDER BY Fecha DESC");
				list($cantidad,$fecha) = $this->conn->fetchRow($result);

				if(isset($cantidad) && isset($fecha))//If there is a Salario diario related to this trabajador
				{
					$i = 0;
					//get a count of $rows at $result. NOTE: result has not been reset, so, if i is 0 it means there's 1 row
					while($row = $this->conn->fetchRow($result))
						$i++;

					$this->conn->data_seek($result,0);//reset result

					if($i > 0)//if there's not only one row
					{
						$j = 0;
						//check if salario diario changes in this period
						while($row = $this->conn->fetchRow($result,'ASSOC'))
						{
							$interval = date_diff($limite_inferior,date_create($row['Fecha']));//$limite_inferior set above

							if($interval->format('%r%a') > 0)//if concepto's fecha > limite inferior del periodo
								$j++;

						}

						$this->conn->data_seek($result,0);//reset result

						if($j > 0)//if salario diario changes in this period
						{
							$salario_diario = array();//salario diario has to be an array
							$j = 0;

							while($row = $this->conn->fetchRow($result,'ASSOC'))
							{
								$interval = date_diff($limite_inferior,date_create($row['Fecha']));//$limite_inferior set above

								if($interval->format('%r%a') >= 0)//if salario diario fecha >= Limite inferior del periodo
									$salario_diario[$j] = $row;
								else
								{
									$salario_diario[$j]['Cantidad'] = $row['Cantidad'];
									$salario_diario[$j]['Fecha'] = $this->Limite_inferior_del_periodo;//use the last salario diario
									break;
								}

								$j++;
							}

							$sd = $salario_diario;
						}
						else
							$sd = $cantidad;

					}
					else
						$sd = $cantidad;

				}
				else
					$sd = 0.00;

			}

			if(isset($sd) && is_array($sd))
			{
				//checking if any salary date is a discharge date
				$dias_de_baja = $this->calculate_dias_de_baja($_trabajador);
				$sd = array_reverse($sd);//ascendant order to iterate

				for($i=0; $i<count($sd); $i++)

					foreach($dias_de_baja as $day)

						if($day == $sd[$i]['Fecha'])
						{
							$date = date_create($day);
							$date->add(new DateInterval('P1D'));

							if($date > date_create($this->Limite_superior_del_periodo) || ((count($sd) > $i + 1) && $date->format('Y-m-d') == $sd[$i + 1]['Fecha']))
								$sd[$i]['Cantidad'] = 0.00;
							else
								$sd[$i]['Fecha'] = $date->format('Y-m-d');

						}

				//deleting 0.00 values
				$aux = array();
				$i = 0;

				foreach($sd as $value)

					if($value['Cantidad'] > 0.00)
					{
						$aux[$i] = $value;
						$i++;
					}

				$sd = array_reverse($aux);//dates must go descendant

				if(count($sd) == 0)
					$sd = 0.00;
				elseif(count($sd) == 1)
					$sd = $sd[0]['Cantidad'];

			}

			if(isset($sd))
				return $sd;
			else
				return 0.00;

		}

		private function calculate_sueldo($salario_diario,$dias_laborados)
		{
			$numero_de_dias_laborados = count($dias_laborados);

			if(is_array($salario_diario))
			{
				$sueldo = 0;
				$len = count($salario_diario);

				for($i=0; $i<$numero_de_dias_laborados; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario[$j]['Fecha'] <= $dias_laborados[$i])
						{
							$sueldo += $salario_diario[$j]['Cantidad'];
							break;
						}

			}
			else
				$sueldo = $numero_de_dias_laborados * $salario_diario;

			return $sueldo;
		}

		private function calculate_horas_extra($salario_diario,$numero_de_dias_del_periodo,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$result = $this->conn->query("SELECT Jornada FROM Trabajador WHERE RFC = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($jornada) = $this->conn->fetchRow($result);
			$_horas_extra = $this->get_value('Horas extra',$this->incidencias,$_trabajador);
			$horas_extra_gravadas = 0;
			$total_horas_extra = 0;

			if(isset($_horas_extra) && isset($jornada) && $jornada > 0)
			{

				if(is_array($salario_diario))
					$salario = $salario_diario[0]['Cantidad'];//the last salario diario will be used
				else
					$salario = $salario_diario;

				$salario_por_hora = round($salario / $jornada, 2);

				if($salario > $salario_minimo)
				{
					$_5vsmag = 5 * $salario_minimo;

					if($salario_por_hora * 9 > $_5vsmag)
						$limite_a_la_exencion_de_horas_extra = $_5vsmag;
					else
						$limite_a_la_exencion_de_horas_extra = $salario_por_hora * 9;

				}
				else
					$limite_a_la_exencion_de_horas_extra = $salario_por_hora * 9 * 2;

				if($numero_de_dias_del_periodo > 7 && $numero_de_dias_del_periodo <= 16)
					$limite_a_la_exencion_de_horas_extra *= 2;
				else if($numero_de_dias_del_periodo > 16)
					$limite_a_la_exencion_de_horas_extra *= 4;

				if($_horas_extra <= $limite_a_la_exencion_de_horas_extra)
					$horas_extra_no_gravadas = $_horas_extra;
				else
					$horas_extra_no_gravadas = $limite_a_la_exencion_de_horas_extra;

				$aux = $_horas_extra - $horas_extra_no_gravadas;
				$horas_extra_gravadas += $aux;
				$total_horas_extra += $_horas_extra; 

			}

			return array($total_horas_extra,$horas_extra_gravadas);
		}

		private function calculate_domingos_laborados($_trabajador)
		{
			date_default_timezone_set('America/Mexico_City');
			$limite_inferior = date_create($this->Limite_inferior_del_periodo);
			$limite_superior = date_create($this->Limite_superior_del_periodo);
			$domingos_laborados = array();
			$j = 0;

			for($i=0; $i<count($this->trabajador); $i++)

				if($this->trabajador[$i] == $_trabajador)
					break;

			$fechas = $this->get_value('Prima dominical',$this->incidencias,$_trabajador);
			$values = explode(',',$fechas);
			$len = count($values);

			for($i=0; $i<$len; $i++)
			{

				if($values[$i] != '')//comparison needed because of the last comma ('DATE','DATE','')
				{
					$domingo = date_create($values[$i]);

					if($limite_inferior <= $domingo && $limite_superior >= $domingo)
					{
						$domingos_laborados[$j] = $values[$i];
						$j++;
					}

				}

			}

			return $domingos_laborados;
		}

		private function calculate_prima_dominical($salario_diario,$domingos_laborados,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$numero_de_domingos_laborados = count($domingos_laborados);

			if(is_array($salario_diario))
			{
				$prima_dominical = 0;
				$prima_dominical_gravada = 0;
				$prima_dominical_no_gravada = 0;
				$len = count($salario_diario);

				for($i=0; $i<$numero_de_domingos_laborados; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario[$j]['Fecha'] <= $domingos_laborados[$i])
						{
							$prima_dominical += $salario_diario[$j]['Cantidad'] * 0.25;

							if($salario_diario[$j]['Cantidad'] * 0.25 > $salario_minimo)
								$prima_dominical_no_gravada += $salario_minimo;
							else
								$prima_dominical_no_gravada += $salario_diario[$j]['Cantidad'] * 0.25;

							break;
						}

			}
			else
			{
				$prima_dominical = $numero_de_domingos_laborados * $salario_diario * 0.25;

				if($prima_dominical > $salario_minimo * $numero_de_domingos_laborados)
					$prima_dominical_no_gravada = $salario_minimo * $numero_de_domingos_laborados;
				else
					$prima_dominical_no_gravada = $prima_dominical;

			}

			$prima_dominical_gravada = $prima_dominical - $prima_dominical_no_gravada;
			return array($prima_dominical,$prima_dominical_gravada);
		}

		private function calculate_dias_de_descanso($salario_diario,$_trabajador)
		{
			$dias_de_descanso = 0;
			$dias_de_descanso_gravados = 0;
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$_5vsmag = 5 * $salario_minimo;
			$cantidad = $this->get_value('Días de descanso',$this->incidencias,$_trabajador);

			if(is_array($salario_diario))
				$salario = $salario_diario[0]['Cantidad'];//the last salario diario will be used
			else
				$salario = $salario_diario;

			if($salario > $salario_minimo)
			{

				if(round($cantidad/2,2) > $_5vsmag)
					$dias_de_descanso_no_gravados = $_5vsmag;
				else
					$dias_de_descanso_no_gravados = round($cantidad/2,2);

			}
			else
				$dias_de_descanso_no_gravados = $cantidad;

			$dias_de_descanso += $cantidad;
			$dias_de_descanso_gravados += $cantidad - $dias_de_descanso_no_gravados;
			return array($dias_de_descanso,$dias_de_descanso_gravados);
		}

		private function calculate_forma_de_pago($_trabajador)
		{
			$forma_de_pago = $this->get_value('Forma de pago',$this->incidencias,$_trabajador);
			return ($forma_de_pago != '' ? $forma_de_pago : 'DEPOSITO');
		}

		private function calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados)
		{
			$year = substr($this->Limite_superior_del_periodo, 0, 4);

			if($numero_de_dias_laborados < $numero_de_dias_del_periodo || $numero_de_dias_del_periodo < 7)
			{
				$base_ISR /= $numero_de_dias_laborados > 0 ? $numero_de_dias_laborados : $numero_de_dias_del_periodo;
				$result = $this->conn->query("SELECT Limite_inferior FROM ISR_diario WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
				list($limite_inferior) = $this->conn->fetchRow($result);
				$limite_inferior *= $numero_de_dias_laborados;
			}
			else
			{

				if($numero_de_dias_del_periodo == 7)
					$result = $this->conn->query("SELECT Limite_inferior FROM ISR_semanal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
				elseif($numero_de_dias_del_periodo <= 16)
					$result = $this->conn->query("SELECT Limite_inferior FROM ISR_quincenal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
				else
					$result = $this->conn->query("SELECT Limite_inferior FROM ISR_mensual WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");

				list($limite_inferior) = $this->conn->fetchRow($result);

			}

			if(isset($limite_inferior))
				return $limite_inferior;
			else
				return 0;

		}

		private function calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados)
		{
			$year = substr($this->Limite_superior_del_periodo, 0, 4);

			if($numero_de_dias_laborados < $numero_de_dias_del_periodo || $numero_de_dias_del_periodo < 7)
			{
				$base_ISR /= $numero_de_dias_laborados > 0 ? $numero_de_dias_laborados : $numero_de_dias_del_periodo;
				$result = $this->conn->query("SELECT Porcentaje FROM ISR_diario WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
				list($porcentaje_sobre_el_exedente_del_limite_inferior) = $this->conn->fetchRow($result);
			}
			else
			{

				if($numero_de_dias_del_periodo == 7)
					$result = $this->conn->query("SELECT Porcentaje FROM ISR_semanal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
				elseif($numero_de_dias_del_periodo <= 16)
					$result = $this->conn->query("SELECT Porcentaje FROM ISR_quincenal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
				else
					$result = $this->conn->query("SELECT Porcentaje FROM ISR_mensual WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");

				list($porcentaje_sobre_el_exedente_del_limite_inferior) = $this->conn->fetchRow($result);
			}

			if(isset($porcentaje_sobre_el_exedente_del_limite_inferior))
				return $porcentaje_sobre_el_exedente_del_limite_inferior;
			else
				return 0;

		}

		private function calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados)
		{
			$year = substr($this->Limite_superior_del_periodo, 0, 4);

			if($numero_de_dias_laborados < $numero_de_dias_del_periodo || $numero_de_dias_del_periodo < 7)
			{
				$base_ISR /= $numero_de_dias_laborados > 0 ? $numero_de_dias_laborados : $numero_de_dias_del_periodo;
				$result = $this->conn->query("SELECT Cuota_fija FROM ISR_diario WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
				list($cuota_fija) = $this->conn->fetchRow($result);
				$cuota_fija *= $numero_de_dias_laborados;
			}
			else
			{

				if($numero_de_dias_del_periodo == 7)
					$result = $this->conn->query("SELECT Cuota_fija FROM ISR_semanal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
				elseif($numero_de_dias_del_periodo <= 16)
					$result = $this->conn->query("SELECT Cuota_fija FROM ISR_quincenal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
				else
					$result = $this->conn->query("SELECT Cuota_fija FROM ISR_mensual WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");

				list($cuota_fija) = $this->conn->fetchRow($result);
			 }

			if(isset($cuota_fija))
				return $cuota_fija;
			else
				return 0;

		}

		private function calculate_subsidio($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados)
		{
			$year = substr($this->Limite_superior_del_periodo, 0, 4);

			if($numero_de_dias_laborados < $numero_de_dias_del_periodo || $numero_de_dias_del_periodo < 7)
			{
				$base_ISR /= $numero_de_dias_laborados > 0 ? $numero_de_dias_laborados : $numero_de_dias_del_periodo;
				$base_ISR = round($base_ISR, 2);
				$result = $this->conn->query("SELECT Subsidio FROM Credito_al_salario_diario WHERE Desde_ingresos_de <= $base_ISR AND (Hasta_ingresos_de >= $base_ISR OR Hasta_ingresos_de IS NULL) AND Ano = '$year'");
				list($subsidio) = $this->conn->fetchRow($result);
				$subsidio *= $numero_de_dias_laborados;
			}
			else
			{

				if($numero_de_dias_del_periodo == 7)
					$result = $this->conn->query("SELECT Subsidio FROM Credito_al_salario_semanal WHERE Desde_ingresos_de <= $base_ISR AND (Hasta_ingresos_de >= $base_ISR OR Hasta_ingresos_de IS NULL) AND Ano = '$year'");
				elseif($numero_de_dias_del_periodo <= 16)
					$result = $this->conn->query("SELECT Subsidio FROM Credito_al_salario_quincenal WHERE Desde_ingresos_de <= $base_ISR AND (Hasta_ingresos_de >= $base_ISR OR Hasta_ingresos_de IS NULL) AND Ano = '$year'");
				else
					$result = $this->conn->query("SELECT Subsidio FROM Credito_al_salario_mensual WHERE Desde_ingresos_de <= $base_ISR AND (Hasta_ingresos_de >= $base_ISR OR Hasta_ingresos_de IS NULL) AND Ano = '$year'");

				list($subsidio) = $this->conn->fetchRow($result);
			}

			if(isset($subsidio))
				return $subsidio;
			else
				return 0;

		}

		private function calculate_salario_diario_integrado($_trabajador,$factor_de_integracion)
		{
			$result = $this->conn->query("SELECT Base FROM Base WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
			list($base) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$salario_diario = $this->calculate_salario_diario($_trabajador,$base);

			if(is_array($salario_diario))
			{
				$salario_diario_integrado = array();
				$len = count($salario_diario);

				for($i=0; $i<$len; $i++)
				{
					$salario_diario_integrado[$i]['Cantidad'] = round($salario_diario[$i]['Cantidad'] * $factor_de_integracion,2);
					$salario_diario_integrado[$i]['Fecha'] = $salario_diario[$i]['Fecha'];
				}

			}
			else
			{
				$salario_diario_integrado = round($salario_diario * $factor_de_integracion,2);
			}

			return $salario_diario_integrado;
		}

		private function calculate_cuota_fija_imss($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de cuota fija' AND Ano = '$year'");
			list($porcentaje_de_cuota_fija) = $this->conn->fetchRow($result);
			$cuota_fija = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_laborados; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_laborados[$i])
						{

							if($salario_diario[$j]['Cantidad'] > $salario_minimo)
								$cuota_fija += round($salario_minimo_zona_A * $porcentaje_de_cuota_fija,2);

							break;
						}

			}
			elseif($salario_diario > $salario_minimo)
				$cuota_fija = round($salario_minimo_zona_A * $porcentaje_de_cuota_fija * $numero_de_dias_laborados,2);

			return $cuota_fija;
		}

		private function calculate_exedente_patronal($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de exedente patronal' AND Ano = '$year'");
			list($porcentaje_de_exedente) = $this->conn->fetchRow($result);
			$exedente = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_laborados; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_laborados[$i])
						{

							if($salario_diario[$j]['Cantidad'] > $salario_minimo)
							{
								$aux = $salario_diario_integrado[$j]['Cantidad'] - 3 * $salario_minimo_zona_A;
								$exedente += ($aux < 0 ? 0 : round($aux * $porcentaje_de_exedente,2));
							}

							break;
						}

			}
			elseif($salario_diario > $salario_minimo)
			{
				$aux = ($salario_diario_integrado - 3 * $salario_minimo_zona_A) * $numero_de_dias_laborados;
				$exedente = ($aux < 0 ? 0 : round($aux * $porcentaje_de_exedente,2));
			}

			return $exedente;
		}

		private function calculate_exedente_obrero($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de exedente obrero' AND Ano = '$year'");
			list($porcentaje_de_exedente) = $this->conn->fetchRow($result);
			$exedente = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_laborados; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_laborados[$i])
						{

							if($salario_diario[$j]['Cantidad'] > $salario_minimo)
							{
								$aux = $salario_diario_integrado[$j]['Cantidad'] - 3 * $salario_minimo_zona_A;
								$exedente += ($aux < 0 ? 0 : round($aux * $porcentaje_de_exedente,2));
							}

							break;
						}

			}
			elseif($salario_diario > $salario_minimo)
			{
				$aux = ($salario_diario_integrado - 3 * $salario_minimo_zona_A) * $numero_de_dias_laborados;
				$exedente = ($aux < 0 ? 0 : round($aux * $porcentaje_de_exedente,2));
			}

			return $exedente;
		}

		private function calculate_prestaciones_en_dinero_obreras($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de prestaciones en dinero obrero' AND Ano = '$year'");
			list($porcentaje_de_prestaciones_en_dinero_obrero) = $this->conn->fetchRow($result);
			$prestaciones_en_dinero = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_laborados; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_laborados[$i])
						{

							if($salario_diario[$j]['Cantidad'] > $salario_minimo)
								$prestaciones_en_dinero += round($salario_diario_integrado[$j]['Cantidad'] * $porcentaje_de_prestaciones_en_dinero_obrero,2);

							break;
						}

			}
			elseif($salario_diario > $salario_minimo)
				$prestaciones_en_dinero = round($salario_diario_integrado * $porcentaje_de_prestaciones_en_dinero_obrero * $numero_de_dias_laborados,2);

			return $prestaciones_en_dinero;
		}

		private function calculate_prestaciones_en_dinero_patronales($salario_diario_integrado,$dias_del_periodo,$numero_de_dias_del_periodo,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de prestaciones en dinero patronal' AND Ano = '$year'");
			list($porcentaje_de_prestaciones_en_dinero_patronal) = $this->conn->fetchRow($result);
			$prestaciones_en_dinero = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_del_periodo; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_del_periodo[$i])
						{
							$prestaciones_en_dinero += round($salario_diario_integrado[$j]['Cantidad'] * $porcentaje_de_prestaciones_en_dinero_patronal,2);
							break;
						}

			}
			else
				$prestaciones_en_dinero = round($salario_diario_integrado * $porcentaje_de_prestaciones_en_dinero_patronal * $numero_de_dias_del_periodo,2);

			return $prestaciones_en_dinero;
		}

		private function calculate_gastos_medicos_y_pension_obreros($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de gastos médicos obrero' AND Ano = '$year'");
			list($porcentaje_de_gastos_medicos_obrero) = $this->conn->fetchRow($result);
//			$numero_de_dias_laborados = $this->get_value('Número de días laborados',$this->ISRasalariados,$_trabajador);
			$gastos_medicos_y_pension = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_laborados; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_laborados[$i])
						{

							if($salario_diario[$j]['Cantidad'] > $salario_minimo)
								$gastos_medicos_y_pension += round($salario_diario_integrado[$j]['Cantidad'] * $porcentaje_de_gastos_medicos_obrero,2);

							break;
						}

			}
			elseif($salario_diario > $salario_minimo)
				$gastos_medicos_y_pension = round($salario_diario_integrado * $porcentaje_de_gastos_medicos_obrero * $numero_de_dias_laborados,2);

			return $gastos_medicos_y_pension;
		}

		private function calculate_gastos_medicos_y_pension_patronales($salario_diario_integrado,$dias_del_periodo,$numero_de_dias_del_periodo,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de gastos médicos patronal' AND Ano = '$year'");
			list($porcentaje_de_gastos_medicos_patronal) = $this->conn->fetchRow($result);
			$gastos_medicos_y_pension = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_del_periodo; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_del_periodo[$i])
						{
							$gastos_medicos_y_pension += round($salario_diario_integrado[$j]['Cantidad'] * $porcentaje_de_gastos_medicos_patronal,2);
							break;
						}

			}
			else
				$gastos_medicos_y_pension = round($salario_diario_integrado * $porcentaje_de_gastos_medicos_patronal * $numero_de_dias_del_periodo,2);

			return $gastos_medicos_y_pension;
		}

		private function calculate_invalidez_y_vida_obrera($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de invalidez y vida obrero' AND Ano = '$year'");
			list($porcentaje_de_invalidez_y_vida_obrero) = $this->conn->fetchRow($result);
//			$numero_de_dias_laborados = $this->get_value('Número de días laborados',$this->ISRasalariados,$_trabajador);
			$invalidez_y_vida = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_laborados; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_laborados[$i])
						{

							if($salario_diario[$j]['Cantidad'] > $salario_minimo)
								$invalidez_y_vida += round($salario_diario_integrado[$j]['Cantidad'] * $porcentaje_de_invalidez_y_vida_obrero,2);

							break;
						}

			}
			elseif($salario_diario > $salario_minimo)
				$invalidez_y_vida = round($salario_diario_integrado * $porcentaje_de_invalidez_y_vida_obrero * $numero_de_dias_laborados,2);

			return $invalidez_y_vida;
		}

		private function calculate_invalidez_y_vida_patronal($salario_diario_integrado,$dias_del_periodo,$numero_de_dias_del_periodo,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de invalidez y vida patronal' AND Ano = '$year'");
			list($porcentaje_de_invalidez_y_vida_patronal) = $this->conn->fetchRow($result);
			$invalidez_y_vida = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_del_periodo; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_del_periodo[$i])
						{
							$invalidez_y_vida += round($salario_diario_integrado[$j]['Cantidad'] * $porcentaje_de_invalidez_y_vida_patronal,2);
							break;
						}

			}
			else
				$invalidez_y_vida = round($salario_diario_integrado * $porcentaje_de_invalidez_y_vida_patronal * $numero_de_dias_del_periodo,2);

			return $invalidez_y_vida;
		}

		private function calculate_cesantia_y_vejez_obrera($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de cesantía y vejez obrero' AND Ano = '$year'");
			list($porcentaje_de_cesantia_y_vejez_obrero) = $this->conn->fetchRow($result);
			$cesantia_y_vejez = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_laborados; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_laborados[$i])
						{

							if($salario_diario[$j]['Cantidad'] > $salario_minimo)
								$cesantia_y_vejez += round($salario_diario_integrado[$j]['Cantidad'] * $porcentaje_de_cesantia_y_vejez_obrero,2);

							break;
						}

			}
			elseif($salario_diario > $salario_minimo)
				$cesantia_y_vejez = round($salario_diario_integrado * $porcentaje_de_cesantia_y_vejez_obrero * $numero_de_dias_laborados,2);

			return $cesantia_y_vejez;
		}

		private function calculate_cesantia_y_vejez_patronal($salario_diario_integrado,$dias_del_periodo,$numero_de_dias_del_periodo,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de cesantía y vejez patronal' AND Ano = '$year'");
			list($porcentaje_de_cesantia_y_vejez_patronal) = $this->conn->fetchRow($result);
			$cesantia_y_vejez = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_del_periodo; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_del_periodo[$i])
						{
							$cesantia_y_vejez += round($salario_diario_integrado[$j]['Cantidad'] * $porcentaje_de_cesantia_y_vejez_patronal,2);
							break;
						}

			}
			else
				$cesantia_y_vejez = round($salario_diario_integrado * $porcentaje_de_cesantia_y_vejez_patronal * $numero_de_dias_del_periodo,2);

			return $cesantia_y_vejez;
		}

		private function calculate_guarderia($salario_diario_integrado,$dias_del_periodo,$numero_de_dias_del_periodo,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de guardería' AND Ano = '$year'");
			list($porcentaje_de_guarderia) = $this->conn->fetchRow($result);
			$guarderia = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_del_periodo; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_del_periodo[$i])
						{
							$guarderia += round($salario_diario_integrado[$j]['Cantidad'] * $porcentaje_de_guarderia,2);
							break;
						}

			}
			else
				$guarderia = round($salario_diario_integrado * $porcentaje_de_guarderia * $numero_de_dias_del_periodo,2);

			return $guarderia;
		}

		private function calculate_retiro($salario_diario_integrado,$dias_del_periodo,$numero_de_dias_del_periodo,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de retiro' AND Ano = '$year'");
			list($porcentaje_de_retiro) = $this->conn->fetchRow($result);
			$retiro = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_del_periodo; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_del_periodo[$i])
						{
							$retiro += round($salario_diario_integrado[$j]['Cantidad'] * $porcentaje_de_retiro,2);
							break;
						}

			}
			else
				$retiro = round($salario_diario_integrado * $porcentaje_de_retiro * $numero_de_dias_del_periodo,2);

			return $retiro;
		}

		private function calculate_infonavit($salario_diario_integrado,$dias_del_periodo,$numero_de_dias_del_periodo,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de INFONAVIT' AND Ano = '$year'");
			list($porcentaje_de_infonavit) = $this->conn->fetchRow($result);
			$infonavit = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_del_periodo; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_del_periodo[$i])
						{
							$infonavit += round($salario_diario_integrado[$j]['Cantidad'] * $porcentaje_de_infonavit,2);
							break;
						}

			}
			else
				$infonavit = round($salario_diario_integrado * $porcentaje_de_infonavit * $numero_de_dias_del_periodo,2);

			return $infonavit;
		}

		private function calculate_riesgo_de_trabajo($salario_diario_integrado,$dias_del_periodo,$numero_de_dias_del_periodo,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$result = $this->conn->query("SELECT Prima.Valor FROM Servicio_Empresa LEFT JOIN Prima ON Servicio_Empresa.Empresa = Prima.Empresa LEFT JOIN Servicio_Registro_patronal ON Servicio_Registro_patronal.Servicio = Servicio_Empresa.Servicio AND Servicio_Registro_patronal.Registro_patronal = Prima.Registro_patronal WHERE Servicio_Empresa.Servicio = '{$this->Servicio}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Prima.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}', Prima.Fecha) >= 0 AND DATEDIFF('{$this->Limite_superior_del_periodo}', Servicio_Empresa.Fecha_de_asignacion) >= 0  AND DATEDIFF('{$this->Limite_superior_del_periodo}', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC, Prima.Fecha DESC LIMIT 1");
			list($prima) = $this->conn->fetchRow($result);
			$riesgo_de_trabajo = 0;

			if(is_array($salario_diario_integrado))
			{
				$len = count($salario_diario_integrado);

				for($i=0; $i<$numero_de_dias_del_periodo; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario_integrado[$j]['Fecha'] <= $dias_del_periodo[$i])
						{
							$riesgo_de_trabajo += round($salario_diario_integrado[$j]['Cantidad'] * $prima,2);
							break;
						}

			}
			else
				$riesgo_de_trabajo = round($salario_diario_integrado * $prima * $numero_de_dias_del_periodo,2);

			return $riesgo_de_trabajo;
		}

		private function calculate_adeudo_cuotas_imss($_trabajador)
		{
			$adeudo_imss = 0.00;

			if(isset($this->id))
				$result1 = $this->conn->query("SELECT Cantidad, Numero_de_descuentos, Fecha_de_inicio, Fecha_de_termino FROM Descuento_pendiente WHERE Nomina != '{$this->id}' AND Retencion = 'Cuotas IMSS' AND Trabajador = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			else
				$result1 = $this->conn->query("SELECT Cantidad, Numero_de_descuentos, Fecha_de_inicio, Fecha_de_termino FROM Descuento_pendiente WHERE Retencion = 'Cuotas IMSS' AND Trabajador = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($cantidad, $numero_de_descuentos, $fecha_de_inicio, $fecha_de_termino) = $this->conn->fetchRow($result1))

				if(isset($cantidad) && isset($numero_de_descuentos) && isset($fecha_de_inicio) && isset($fecha_de_termino))
				{

					if(($this->Limite_inferior_del_periodo >= $fecha_de_inicio && $this->Limite_inferior_del_periodo <= $fecha_de_termino) || ($this->Limite_superior_del_periodo <= $fecha_de_termino && $this->Limite_superior_del_periodo >= $fecha_de_inicio))
					{
						$descuento = round($cantidad / $numero_de_descuentos,2);
						$adeudo_imss += $descuento;
					}

				}

			return $adeudo_imss;
		}

		public function calculate_resumen()//this function is called when storing the resume at db, so isr, imss and nomina are already stored
		{
			$result = $this->conn->query("SELECT Cobrar_IVA FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calcular_iva) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Cobrar_impuesto_sobre_nomina FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calcular_impuesto_sobre_nomina) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Cuotas_IMSS FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calculate_cuotas_imss) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT _5_INFONAVIT FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calculate_5_INFONAVIT) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Porcentaje_de_honorarios FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($honorarios) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Honorarios_pendientes FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($honorarios_pendientes) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT ivash FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($ivash) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$sueldo_asalariados = $this->total_col('Sueldo','nomina_asalariados');
			$sueldo_asimilables = $this->total_col('Honorarios_asimilados','nomina_asimilables');
			$total_de_sueldo = $this->_sum(array($sueldo_asalariados, $sueldo_asimilables));
			$subsidio_al_empleo = $this->total_col('Subsidio_al_empleo','nomina_asalariados');//$this->total_col('Subsidio','ISRasalariados');
			$horas_extra_asalariados = $this->total_col('Horas_extra','nomina_asalariados');
			$prima_dominical = $this->total_col('Prima_dominical','nomina_asalariados');
			$dias_de_descanso = $this->total_col('Dias_de_descanso','nomina_asalariados');
			$premios_de_puntualidad_y_asistencia = $this->total_col('Premios_de_puntualidad_y_asistencia','nomina_asalariados');
			$bonos_de_productividad = $this->total_col('Bonos_de_productividad','nomina_asalariados');
			$estimulos_asalariados = $this->total_col('Estimulos','nomina_asalariados');
			$compensaciones = $this->total_col('Compensaciones','nomina_asalariados');
			$despensa = $this->total_col('Despensa','nomina_asalariados');
			$comida = $this->total_col('Comida','nomina_asalariados');
			$alimentacion = $this->total_col('Alimentacion','nomina_asalariados');
			$habitacion = $this->total_col('Habitacion','nomina_asalariados');
			$aportacion_patronal_al_fondo_de_ahorro = $this->total_col('Aportacion_patronal_al_fondo_de_ahorro','nomina_asalariados');
			$total_de_percepciones_asalariados = $this->total_col('Total_de_percepciones','nomina_asalariados');
			$total_de_percepciones_asimilables = $this->total_col('Total_de_percepciones','nomina_asimilables');
			$total_de_percepciones = $this->_sum(array($total_de_percepciones_asalariados, $total_de_percepciones_asimilables));
			$isr_asalariados = $this->total_col('ISR','nomina_asalariados');//$this->total_col('Impuesto_determinado','ISRasalariados');
			$isr = explode('/',$isr_asalariados);
			$subsidio = explode('/',$subsidio_al_empleo);
			$contribuciones_isr = $isr;
			$len = count($isr);

			for($i=0; $i<$len; $i++)

				if($subsidio[$i] > $isr[$i])
					$contribuciones_isr[$i] = '0.00';
				else
					$contribuciones_isr[$i] = round($isr[$i] - $subsidio[$i], 2);

			$contribuciones_isr_asalariados = implode('/',$contribuciones_isr);
			$isr_asimilables = $this->total_col('ISR','nomina_asimilables');
			$total_isr = $this->_sum(array($isr_asalariados, $isr_asimilables));
			$cuotas_imss_nomina = $this->total_col('Cuotas_IMSS','nomina_asalariados');
			$cesantia_y_vejez_nomina = $this->calculate_cesantia_y_vejez_nomina();
			$cuotas_imss_nomina = $this->_sub($cuotas_imss_nomina, $cesantia_y_vejez_nomina);
			$retencion_por_alimentacion = $this->total_col('Retencion_por_alimentacion','nomina_asalariados');
			$retencion_por_habitacion = $this->total_col('Retencion_por_habitacion','nomina_asalariados');
			$retencion_infonavit = $this->total_col('Retencion_INFONAVIT','nomina_asalariados');
			$retencion_fonacot = $this->total_col('Retencion_FONACOT','nomina_asalariados');
			$aportacion_del_trabajador_al_fondo_de_ahorro = $this->total_col('Aportacion_del_trabajador_al_fondo_de_ahorro','nomina_asalariados');
			$pension_alimenticia = $this->total_col('Pension_alimenticia','nomina_asalariados');
			$retardos = $this->total_col('Retardos','nomina_asalariados');
			$prestaciones = $this->total_col('Prestaciones','nomina_asalariados');
			$gestion_administrativa_asalariados = $this->total_col('Gestion_administrativa','nomina_asalariados');
			$gestion_administrativa_asimilables = $this->total_col('Gestion_administrativa','nomina_asimilables');
			$gestion_administrativa = $this->_sum(array($gestion_administrativa_asalariados, $gestion_administrativa_asimilables));
			$total_de_deducciones_asalariados = $this->total_col('Total_de_deducciones','nomina_asalariados');
			$total_de_deducciones_asimilables = $this->total_col('Total_de_deducciones','nomina_asimilables');
			$total_de_deducciones = $this->_sum(array($total_de_deducciones_asalariados, $total_de_deducciones_asimilables));
			$saldo_asalariados = $this->total_col('Saldo','nomina_asalariados');
			$saldo_asimilables = $this->total_col('Saldo','nomina_asimilables');
			$total_saldo = $this->_sum(array($saldo_asalariados, $saldo_asimilables));
			$result = $this->conn->query("SELECT Vacaciones FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calcular_vacaciones) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($calcular_vacaciones == 'Cobrado')
				$vacaciones = $this->total_col('Retencion_proporcional_de_vacaciones','prestaciones');
			else
				$vacaciones = $this->ceros();

			$result = $this->conn->query("SELECT Prima_vacacional FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calcular_prima_vacacional) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($calcular_prima_vacacional == 'Cobrado')
				$prima_vacacional = $this->total_col('Retencion_proporcional_de_prima_vacacional','prestaciones');
			else
				$prima_vacacional = $this->ceros();

			$result = $this->conn->query("SELECT Aguinaldo FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calcular_aguinaldo) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($calcular_aguinaldo == 'Cobrado')
				$aguinaldo = $this->total_col('Retencion_proporcional_de_aguinaldo','prestaciones');
			else
				$aguinaldo = $this->ceros();

			$result = $this->conn->query("SELECT Prima_de_antiguedad FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calcular_prima_de_antiguedad) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($calcular_prima_de_antiguedad == 'Cobrado')
				$prima_de_antiguedad = $this->total_col('Retencion_proporcional_de_prima_de_antiguedad','prestaciones');
			else
				$prima_de_antiguedad = $this->ceros();

			$total_prestaciones = $this->_sum(array($vacaciones, $prima_vacacional, $aguinaldo, $prima_de_antiguedad));//$this->total_col('Total_de_retenciones','prestaciones');
			$fondo_de_garantia = $this->total_col('Fondo_de_garantia','nomina_asalariados');
			$pago_por_seguro_de_vida_asalariados = $this->total_col('Pago_por_seguro_de_vida','nomina_asalariados');
			$pago_por_seguro_de_vida_asimilables = $this->total_col('Pago_por_seguro_de_vida','nomina_asimilables');
			$total_pago_por_seguro_de_vida = $this->_sum(array($pago_por_seguro_de_vida_asalariados, $pago_por_seguro_de_vida_asimilables));
			$prestamo_del_fondo_de_ahorro = $this->total_col('Prestamo_del_fondo_de_ahorro','nomina_asalariados');
			$prestamo_caja_asalariados = $this->total_col('Prestamo_caja','nomina_asalariados');
			$prestamo_caja_asimilables = $this->total_col('Prestamo_caja','nomina_asimilables');
			$total_prestamo_caja = $this->_sum(array($prestamo_caja_asalariados, $prestamo_caja_asimilables));
			$prestamo_cliente_asalariados = $this->total_col('Prestamo_cliente','nomina_asalariados');
			$prestamo_cliente_asimilables = $this->total_col('Prestamo_cliente','nomina_asimilables');
			$total_prestamo_cliente = $this->_sum(array($prestamo_cliente_asalariados, $prestamo_cliente_asimilables));
			$prestamo_administradora_asalariados = $this->total_col('Prestamo_administradora','nomina_asalariados');
			$prestamo_administradora_asimilables = $this->total_col('Prestamo_administradora','nomina_asimilables');
			$total_prestamo_administradora = $this->_sum(array($prestamo_administradora_asalariados, $prestamo_administradora_asimilables));
			$total_otros_asalariados = $this->_sum(array($fondo_de_garantia, $pago_por_seguro_de_vida_asalariados, $prestamo_del_fondo_de_ahorro, $prestamo_caja_asalariados, $prestamo_cliente_asalariados, $prestamo_administradora_asalariados));
			$total_otros_asimilables = $this->_sum(array($pago_por_seguro_de_vida_asimilables, $prestamo_caja_asimilables,$prestamo_cliente_asimilables, $prestamo_administradora_asimilables));
			$total_otros = $this->_sum(array($total_otros_asalariados, $total_otros_asimilables));
			$neto_a_recibir_asalariados = $this->total_col('Neto_a_recibir','nomina_asalariados');
			$neto_a_recibir_asimilables = $this->total_col('Neto_a_recibir','nomina_asalariados');
			$total_neto_a_recibir = $this->_sum(array($neto_a_recibir_asalariados, $neto_a_recibir_asimilables));

			if($calculate_cuotas_imss == 'Cobrado')
			{
				$cio = $this->total_col('Total_de_cuotas_IMSS_obreras','cuotas_IMSS');
				$cav_obrera = $this->total_col('Cesantia_y_vejez_obrera','cuotas_IMSS');
				$cio = $this->_sub($cio, $cav_obrera);
				$cism = $this->calculate_cism();
				$cio = $this->_sub($cio, $cism);
				$cavsm = $this->calculate_cavsm();
				$cav_obrera = $this->_sub($cav_obrera, $cavsm);
				$cip = $this->total_col('Total_de_cuotas_IMSS_patronales','cuotas_IMSS');
				$adeudo_imss = $this->total_col('Adeudo','cuotas_IMSS');
			}
			else
			{
				$cio = $this->ceros();
				$cav_obrera = $this->ceros();
				$cism = $this->ceros();
				$cavsm = $this->ceros();
				$cip = $this->ceros();
				$adeudo_imss = $this->ceros();
			}

			if($calcular_impuesto_sobre_nomina == 'true')
			{
				$impuesto_sobre_nomina_asalariados = $this->calculate_impuesto_sobre_nomina_asalariados();
				$impuesto_sobre_nomina_asimilables = $this->calculate_impuesto_sobre_nomina_asimilables();
			}
			else
			{
				$impuesto_sobre_nomina_asalariados = $this->ceros();
				$impuesto_sobre_nomina_asimilables = $this->ceros();
			}

			$total_de_impuesto_sobre_nomina = $this->_sum(array($impuesto_sobre_nomina_asalariados, $impuesto_sobre_nomina_asimilables));

			if($calculate_cuotas_imss == 'Cobrado')
				$cav_patronal = $this->total_col('Cesantia_y_vejez_patronal','cuotas_IMSS');
			else
				$cav_patronal = $this->ceros();

			if($calculate_cuotas_imss == 'Cobrado')
				$retiro = $this->total_col('Retiro','cuotas_IMSS');
			else
				$retiro = $this->ceros();

			if($calculate_5_INFONAVIT == 'Cobrado')
				$infonavit = $this->total_col('INFONAVIT','cuotas_IMSS');
			else
				$infonavit = $this->ceros();

			if($cip != $this->ceros())
			{
				$cip = $this->_sub($cip, $cav_patronal);//substracting "cesantia y vejez patronal" from "cuotas imss patronales"
				$cip = $this->_sub($cip, $retiro);//substracting "retiro" from "cuotas imss patronales"
				$cip = $this->_sub($cip, $infonavit);//substracting "INFONAVIT" from "cuotas imss patronales"
			}

			$total_de_impuestos_asalariados = $this->_sum(array($contribuciones_isr_asalariados, $cio, $cism, $cip, $adeudo_imss, $retencion_infonavit, $retencion_fonacot, $aportacion_del_trabajador_al_fondo_de_ahorro, $aportacion_del_trabajador_al_fondo_de_ahorro, $impuesto_sobre_nomina_asalariados, $retiro, $cav_obrera, $cavsm, $cav_patronal, $infonavit, $pension_alimenticia));//aportacion_del_trabajador_al_fondo_de_ahorro * 2
			$total_de_impuestos_asimilables = $this->_sum(array($isr_asimilables, $impuesto_sobre_nomina_asimilables));
			$total_de_impuestos = $this->_sum(array($total_de_impuestos_asalariados, $total_de_impuestos_asimilables));
			$result = $this->conn->query("SELECT Incluir_contribuciones FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($incluir_contribuciones) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$diferencias = $this->total_col('Diferencia','incidencias');

			if($incluir_contribuciones == 'true')
				$honorarios_asalariados = $this->calculate_honorarios(array($saldo_asalariados, $total_de_impuestos_asalariados, $total_prestaciones, $diferencias, $gestion_administrativa_asalariados), $honorarios);
			else
				$honorarios_asalariados = $this->calculate_honorarios(array($saldo_asalariados, $total_prestaciones, $diferencias, $gestion_administrativa_asalariados), $honorarios);

			$honorarios_asimilables = $this->calculate_honorarios(array($saldo_asimilables, $gestion_administrativa_asimilables, $total_de_impuestos_asimilables), $honorarios);
			$_honorarios = $this->_sum(array($honorarios_asalariados, $honorarios_asimilables));

			if($honorarios_pendientes == 'true' || $ivash == 'true')
			{
				$subtotal_a_facturar_asalariados = $this->_sum(array($saldo_asalariados, $total_de_impuestos_asalariados, $total_prestaciones, $diferencias, $this->ceros()));
				$subtotal_a_facturar_asimilables = $this->_sum(array($saldo_asimilables, $total_de_impuestos_asimilables, $this->ceros()));
			}
			else
			{
				$subtotal_a_facturar_asalariados = $this->_sum(array($saldo_asalariados, $total_de_impuestos_asalariados, $total_prestaciones, $diferencias, $honorarios_asalariados));
				$subtotal_a_facturar_asimilables = $this->_sum(array($saldo_asimilables, $total_de_impuestos_asimilables, $honorarios_asimilables));
			}

			$subtotal_a_facturar = $this->_sum(array($subtotal_a_facturar_asalariados, $subtotal_a_facturar_asimilables));

			if($ivash == 'true')//IVA solo por honorarios
			{
				$iva_asalariados = $this->calculate_iva($honorarios_asalariados,$calcular_iva);
				$iva_asimilables = $this->calculate_iva($honorarios_asimilables,$calcular_iva);
			}
			else
			{
				$iva_asalariados = $this->calculate_iva($subtotal_a_facturar_asalariados,$calcular_iva);
				$iva_asimilables = $this->calculate_iva($subtotal_a_facturar_asimilables,$calcular_iva);
			}

			$iva = $this->_sum(array($iva_asalariados, $iva_asimilables));

			if($ivash == 'true')
			{
				$total_a_facturar_asalariados = $subtotal_a_facturar_asalariados;
				$total_a_facturar_asimilables = $subtotal_a_facturar_asimilables;
			}
			else
			{
				$total_a_facturar_asalariados = $this->_sum(array($subtotal_a_facturar_asalariados, $iva_asalariados));
				$total_a_facturar_asimilables = $this->_sum(array($subtotal_a_facturar_asimilables, $iva_asimilables));
			}

			$total_a_facturar = $this->_sum(array($total_a_facturar_asalariados, $total_a_facturar_asimilables));
			$this->Resumen = $sueldo_asalariados . ',' . $sueldo_asimilables . ',' . $total_de_sueldo . ',' . $subsidio_al_empleo . ',' . $horas_extra_asalariados . ',' . $prima_dominical . ',' . $dias_de_descanso . ',' . $premios_de_puntualidad_y_asistencia . ',' . $bonos_de_productividad . ',' . $estimulos_asalariados . ',' . $compensaciones . ',' . $despensa . ',' . $comida . ',' . $alimentacion . ',' . $habitacion . ',' . $aportacion_patronal_al_fondo_de_ahorro . ',' . $total_de_percepciones_asalariados . ',' . $total_de_percepciones_asimilables . ',' . $total_de_percepciones . ',' . $isr_asalariados . ',' . $contribuciones_isr_asalariados . ',' . $isr_asimilables . ',' . $total_isr . ',' . $cuotas_imss_nomina . ',' . $cesantia_y_vejez_nomina . ',' . $retencion_por_alimentacion . ',' . $retencion_por_habitacion . ',' . $retencion_infonavit . ',' . $retencion_fonacot . ',' . $aportacion_del_trabajador_al_fondo_de_ahorro . ',' . $pension_alimenticia . ',' . $retardos . ',' . $prestaciones . ',' . $gestion_administrativa_asalariados . ',' . $gestion_administrativa_asimilables . ',' . $gestion_administrativa . ',' .  $total_de_deducciones_asalariados . ',' . $total_de_deducciones_asimilables . ',' . $total_de_deducciones . ',' . $saldo_asalariados . ',' . $saldo_asimilables . ',' . $total_saldo . ',' . $vacaciones . ',' . $prima_vacacional . ',' . $aguinaldo . ',' . $prima_de_antiguedad . ',' . $total_prestaciones . ',' . $fondo_de_garantia . ',' . $pago_por_seguro_de_vida_asalariados . ',' . $pago_por_seguro_de_vida_asimilables . ',' . $total_pago_por_seguro_de_vida . ',' . $prestamo_del_fondo_de_ahorro . ',' . $prestamo_caja_asalariados . ',' . $prestamo_caja_asimilables . ',' . $total_prestamo_caja . ',' . $prestamo_cliente_asalariados . ',' . $prestamo_cliente_asimilables . ',' . $total_prestamo_cliente . ',' . $prestamo_administradora_asalariados . ',' . $prestamo_administradora_asimilables . ',' . $total_prestamo_administradora . ',' . $total_otros_asalariados . ',' . $total_otros_asimilables . ',' . $total_otros . ',' . $neto_a_recibir_asalariados . ',' . $neto_a_recibir_asimilables . ',' . $total_neto_a_recibir . ',' . $cio . ',' . $cism . ',' . $cip . ',' . $adeudo_imss . ',' . $impuesto_sobre_nomina_asalariados . ',' . $impuesto_sobre_nomina_asimilables . ',' . $total_de_impuesto_sobre_nomina . ',' . $infonavit . ',' . $retiro . ',' . $cav_obrera . ',' . $cavsm . ',' . $cav_patronal . ',' . $total_de_impuestos_asalariados . ',' . $total_de_impuestos_asimilables . ',' . $total_de_impuestos . ',' . $diferencias . ',' . $honorarios_asalariados . ',' . $honorarios_asimilables . ',' . $_honorarios . ',' . $subtotal_a_facturar_asalariados . ',' . $subtotal_a_facturar_asimilables . ',' . $subtotal_a_facturar . ',' . $iva_asalariados . ',' . $iva_asimilables . ',' . $iva . ',' . $total_a_facturar_asalariados . ',' . $total_a_facturar_asimilables . ',' . $total_a_facturar;
		}

		private function total_col($concept,$table)
		{
			$empresa = $this->get_empresa();
			$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			$totals = array();
			$n = $this->conn->num_rows($result);

			if($n > 0)

				while(list($sucursal) = $this->conn->fetchRow($result))
					$totals[$sucursal] = 0.00;

			else
				$totals[0] = 0.00;

			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Trabajador FROM $table WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($trabajador) = $this->conn->fetchRow($result))
			{
				$result1 = $this->conn->query("SELECT Nombre FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador'AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha_de_ingreso) >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
				list($sucursal) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);
				$result1 = $this->conn->query("SELECT $concept FROM $table WHERE Trabajador = '$trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($val) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);
				$data = explode(',',$val);
				$len = count($data);
				$value = 0.00;

				for($i=0; $i<$len; $i++)
				{
					$values = explode('</span>',$data[$i]);

					if(count($values) > 1)
					{
						$num = str_replace('<span>','',$values[1]);
						$value += $num;
					}
					else
					{
						$num = str_replace('<span>','',$values[0]);
						$value += $num;
					}

				}

				if($n > 0)
					$totals[$sucursal] += $value;
				else
					$totals[0] += $value;

			}

			$txt = '';
			$i = 0;

			if($n > 0)

				foreach($totals as $key => $value)
				{

					if($i == $n - 1)
						$txt .= number_format($value,2,'.','');
					else
						$txt .= number_format($value,2,'.','') . '/';

					$i++;
				}

			else
				$txt .= number_format($totals[0],2,'.','');

			return $txt;
		}

		private function ceros()
		{
			$empresa = $this->get_empresa();
			$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			$totals = array();
			$n = $this->conn->num_rows($result);

			if($n > 0)

				while(list($sucursal) = $this->conn->fetchRow($result))
					$totals[$sucursal] = 0;

			else
				$totals[0] = 0;

			$this->conn->freeResult($result);
			$txt = '';
			$i = 0;

			if($n > 0)

				foreach($totals as $key => $value)
				{

					if($i == $n - 1)
						$txt .= round($value,2);
					else
						$txt .= round($value,2) . '/';

					$i++;
				}

			else
				$txt .= round($totals[0],2);

			return $txt;
		}

		public function _sum($concepts)
		{
			$_concepts = array();

			foreach($concepts as $key => $concept)
				$_concepts[$key] = explode('/',$concept);

			$concepts_len = count($_concepts);
			$branches_len = count($_concepts[0]);
			$totals = array();

			for($i=0; $i<$branches_len; $i++)
				$totals[$i] = 0;

			for($i=0; $i<$branches_len; $i++)

				for($j=0; $j<$concepts_len; $j++)

					if(isset($_concepts[$j][$i]))
						$totals[$i] += $_concepts[$j][$i];

			$txt = '';

			for($i=0; $i<$branches_len; $i++)

				if($i == $branches_len - 1)
					$txt .= round($totals[$i],2);
				else
					$txt .= round($totals[$i],2) . '/';

			return $txt;
		}

		public function _sub($values1,$values2)
		{
			$_values1 = explode('/',$values1);
			$_values2 = explode('/',$values2);
			$values_len = count($_values1);
			$txt = '';

			for($i=0; $i<$values_len; $i++)

				if($i == $values_len - 1)
					$txt .= round($_values1[$i] - $_values2[$i],2);
				else
					$txt .= round($_values1[$i] - $_values2[$i],2) . '/';

			return $txt;
		}

		public function calculate_cesantia_y_vejez_nomina()
		{
			$empresa = $this->get_empresa();
			$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			$totals = array();
			$n = $this->conn->num_rows($result);

			if($n > 0)

				while(list($sucursal) = $this->conn->fetchRow($result))
					$totals[$sucursal] = 0;

			else
				$totals[0] = 0;

			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Trabajador FROM cuotas_IMSS WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($trabajador) = $this->conn->fetchRow($result))
			{
				$result1 = $this->conn->query("SELECT Nombre FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador'AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha_de_ingreso) >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
				list($sucursal) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);
				$result1 = $this->conn->query("SELECT dcipn FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($dcipn) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);

				if($dcipn == 'false')
				{
					//Días del periodo and Número de días del periodo
					$numero_de_dias_del_periodo = $this->calculate_numero_de_dias_del_periodo();
					$dias_del_periodo = $this->calculate_dias_del_periodo($numero_de_dias_del_periodo);
					//Días previos al ingreso and numero de días previos al ingreso
					$numero_de_dias_previos_al_ingreso = $this->calculate_numero_de_dias_previos_al_ingreso($trabajador, 'IMSS');
					$dias_previos_al_ingreso = $this->calculate_dias_previos_al_ingreso($numero_de_dias_previos_al_ingreso);
					//Días de baja and Número de días de baja
					$dias_de_baja = $this->calculate_dias_de_baja($trabajador);
					$numero_de_dias_de_baja = count($dias_de_baja);
					//Faltas and Número de faltas
					$_faltas = $this->get_value('Faltas',$this->ISRasalariados,$trabajador);
					$faltas = explode(',', $_faltas);
					$numero_de_faltas = count($faltas);
					//Incapacidad
					$dias_de_incapacidad = $this->calculate_dias_de_incapacidad($trabajador);
					$numero_de_dias_de_incapacidad = count($dias_de_incapacidad);
					//Vacaciones
					$dias_de_vacaciones = $this->calculate_dias_de_vacaciones($trabajador);
					$numero_de_dias_de_vacaciones = count($dias_de_vacaciones);
					//Días laborados and Número de días laborados
					$dias_laborados = $this->calculate_dias_laborados($dias_del_periodo,$dias_previos_al_ingreso,$dias_de_baja,$faltas,$dias_de_incapacidad,$dias_de_vacaciones);
					$numero_de_dias_laborados = count($dias_laborados);
					//Años de antigüedad
					$ingreso = $this->calculate_ingreso($trabajador, 'IMSS');

					if(isset($ingreso))
					{
						$interval = date_diff(date_create($this->Limite_superior_del_periodo),date_create($ingreso));
						$dias_de_antiguedad = $interval->days;
						$anos_de_antiguedad = ceil(($dias_de_antiguedad)/365);
					}
					else
						$anos_de_antiguedad = 0;

					//Dias de vacaciones
					if($anos_de_antiguedad < 5)
						$dias_de_vacaciones = $anos_de_antiguedad * 2 + 4;
					else
						$dias_de_vacaciones = floor($anos_de_antiguedad / 5) * 2 + 12;

					//Factor de prima vacacional
					$factor_de_prima_vacacional = round($dias_de_vacaciones * 0.25 / 365,4);
					//Factor de aguinaldo
					$result1 = $this->conn->query("SELECT Dias_de_aguinaldo FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($dias_de_aguinaldo) = $this->conn->fetchRow($result1);
					$this->conn->freeResult($result1);
					$factor_de_aguinaldo = round($dias_de_aguinaldo / 365,4);
					//Factor de integración
					$factor_de_integracion = 1 + $factor_de_prima_vacacional + $factor_de_aguinaldo;
					//Salario diario
					$result1 = $this->conn->query("SELECT Base FROM Base WHERE Trabajador = '$trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
					list($base) = $this->conn->fetchRow($result1);
					$this->conn->freeResult($result1);
					$salario_diario = $this->calculate_salario_diario($trabajador,$base);
					//Salario diario integrado
					$salario_diario_integrado = $this->calculate_salario_diario_integrado($trabajador,$factor_de_integracion);
					//Cesantía y vejez obrera
					$cesantia_y_vejez_obrera = $this->calculate_cesantia_y_vejez_obrera($salario_diario,$salario_diario_integrado,$dias_laborados,$numero_de_dias_laborados,$trabajador);
				}
				else
				{
					$result1 = $this->conn->query("SELECT Cesantia_y_vejez_obrera FROM cuotas_IMSS WHERE Nomina = '{$this->id}' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($cesantia_y_vejez_obrera) = $this->conn->fetchRow($result1);
					$this->conn->freeResult($result1);
				}

				if($n > 0)
					$totals[$sucursal] += $cesantia_y_vejez_obrera;
				else
					$totals[0] += $cesantia_y_vejez_obrera;

			}

			$txt = '';
			$i = 0;

			if($n > 0)

				foreach($totals as $key => $value)
				{

					if($i == $n - 1)
						$txt .= number_format($value,2,'.','');
					else
						$txt .= number_format($value,2,'.','') . '/';

					$i++;
				}

			else
				$txt .= number_format($totals[0],2,'.','');

			return $txt;
		}

		private function calculate_cism()
		{
			$empresa = $this->get_empresa();
			$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			$totals = array();
			$n = $this->conn->num_rows($result);

			if($n > 0)

				while(list($sucursal) = $this->conn->fetchRow($result))
					$totals[$sucursal] = 0;

			else
				$totals[0] = 0;

			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Trabajador FROM cuotas_IMSS WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($trabajador) = $this->conn->fetchRow($result))
			{
				$result1 = $this->conn->query("SELECT Nombre FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador'AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha_de_ingreso) >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
				list($sucursal) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);
				$result1 = $this->conn->query("SELECT cuotas_IMSS FROM nomina_asalariados WHERE Trabajador = '$trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($val1) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);
				$result1 = $this->conn->query("SELECT Base FROM Base WHERE Trabajador = '$trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}', Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
				list($base) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);

				if($base == 'Salario mínimo')
				{
					$result1 = $this->conn->query("SELECT Salario_minimo FROM Trabajador_Salario_minimo WHERE Trabajador = '$trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}', Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
					list($code) = $this->conn->fetchRow($result1);
					$this->conn->freeResult($result1);
					$flag = $code == '1' ? true : false;
				}
				else
					$flag = false;

				if($val1 == 0 && $flag)
				{
					$result1 = $this->conn->query("SELECT Total_de_cuotas_IMSS_obreras FROM cuotas_IMSS WHERE Trabajador = '$trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($val2) = $this->conn->fetchRow($result1);
					$this->conn->freeResult($result1);
					$result1 = $this->conn->query("SELECT Cesantia_y_vejez_obrera FROM cuotas_IMSS WHERE Trabajador = '$trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($val3) = $this->conn->fetchRow($result1);
					$this->conn->freeResult($result1);


					if($n > 0)
						$totals[$sucursal] += $val2 - $val3;
					else
						$totals[0] += $val2 - $val3;

				}
				else
				{

					if($n > 0)
						$totals[$sucursal] += 0.00;
					else
						$totals[0] += 0.00;

				}

			}

			$txt = '';
			$i = 0;

			if($n > 0)

				foreach($totals as $key => $value)
				{

					if($i == $n - 1)
						$txt .= number_format($value,2,'.','');
					else
						$txt .= number_format($value,2,'.','') . '/';

					$i++;
				}

			else
				$txt .= number_format($totals[0],2,'.','');

			return $txt;
		}

		private function calculate_cavsm()
		{
			$empresa = $this->get_empresa();
			$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			$totals = array();
			$n = $this->conn->num_rows($result);

			if($n > 0)

				while(list($sucursal) = $this->conn->fetchRow($result))
					$totals[$sucursal] = 0;

			else
				$totals[0] = 0;

			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Trabajador FROM cuotas_IMSS WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($trabajador) = $this->conn->fetchRow($result))
			{
				$result1 = $this->conn->query("SELECT Nombre FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador'AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha_de_ingreso) >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
				list($sucursal) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);
				$result1 = $this->conn->query("SELECT cuotas_IMSS FROM nomina_asalariados WHERE Trabajador = '$trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($val1) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);
				$result1 = $this->conn->query("SELECT Base FROM Base WHERE Trabajador = '$trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}', Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
				list($base) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);

				if($base == 'Salario mínimo')
				{
					$result1 = $this->conn->query("SELECT Salario_minimo FROM Trabajador_Salario_minimo WHERE Trabajador = '$trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}', Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
					list($code) = $this->conn->fetchRow($result1);
					$this->conn->freeResult($result1);
					$flag = $code == '1' ? true : false;
				}
				else
					$flag = false;

				if($val1 == 0 && $flag)
				{
					$result1 = $this->conn->query("SELECT Cesantia_y_vejez_obrera FROM cuotas_IMSS WHERE Trabajador = '$trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($val2) = $this->conn->fetchRow($result1);
					$this->conn->freeResult($result1);

					if($n > 0)
						$totals[$sucursal] += $val2;
					else
						$totals[0] += $val2;

				}
				else
				{

					if($n > 0)
						$totals[$sucursal] += 0.00;
					else
						$totals[0] += 0.00;

				}

			}

			$txt = '';
			$i = 0;

			if($n > 0)

				foreach($totals as $key => $value)
				{

					if($i == $n - 1)
						$txt .= round($value,2);
					else
						$txt .= round($value,2) . '/';

					$i++;
				}

			else
				$txt .= round($totals[0],2);

			return $txt;
		}

		private function calculate_impuesto_sobre_nomina_asalariados()//this function is called when storing the resume at db, so isr, imss and nomina are already stored
		{
			$empresa = $this->get_empresa();
			$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			$impuestos = array();
			$n = $this->conn->num_rows($result);

			if($n > 0)

				while(list($sucursal) = $this->conn->fetchRow($result))
					$impuestos[$sucursal] = 0;

			else
				$impuestos[0] = 0;

			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Trabajador FROM nomina_asalariados WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			//Días del periodo and Número de días del periodo
			$numero_de_dias_del_periodo = $this->calculate_numero_de_dias_del_periodo();
			$dias_del_periodo = $this->calculate_dias_del_periodo($numero_de_dias_del_periodo);

			while(list($_trabajador) = $this->conn->fetchRow($result))
			{
				$result1 = $this->conn->query("SELECT Nombre FROM Trabajador_Sucursal WHERE Trabajador = '$_trabajador' AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha_de_ingreso) >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
				list($sucursal) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);
				//Días previos al ingreso and numero de días previos al ingreso
				$numero_de_dias_previos_al_ingreso = $this->calculate_numero_de_dias_previos_al_ingreso($_trabajador, 'nomina');
				$dias_previos_al_ingreso = $this->calculate_dias_previos_al_ingreso($numero_de_dias_previos_al_ingreso);
				//Días de baja and Número de días de baja
				$dias_de_baja = $this->calculate_dias_de_baja($_trabajador);
				$numero_de_dias_de_baja = count($dias_de_baja);
				//Faltas and Número de faltas
				$result1 = $this->conn->query("SELECT Faltas FROM ISRasalariados WHERE Nomina = '{$this->id}' AND Trabajador = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($_faltas) = $this->conn->fetchRow($result1);
				$faltas = explode(',',$_faltas);
				$numero_de_faltas = count($faltas);
				//Incapacidad
				$dias_de_incapacidad = $this->calculate_dias_de_incapacidad($_trabajador);
				$numero_de_dias_de_incapacidad = count($dias_de_incapacidad);
				//Vacaciones
				$dias_de_vacaciones = $this->calculate_dias_de_vacaciones($_trabajador);
				$numero_de_dias_de_vacaciones = count($dias_de_vacaciones);
				//Días laborados and Número de días laborados
				$dias_laborados = $this->calculate_dias_laborados($dias_del_periodo,$dias_previos_al_ingreso,$dias_de_baja,$faltas,$dias_de_incapacidad,$dias_de_vacaciones);
				$numero_de_dias_laborados = count($dias_laborados);

				if($n > 0)
					$impuestos[$sucursal] += $this->calculate_impuesto_sobre_nomina($dias_laborados,$_trabajador);
				else
					$impuestos[0] += $this->calculate_impuesto_sobre_nomina($dias_laborados,$_trabajador);

			}

			$txt = '';
			$i = 0;

			if($n > 0)

				foreach($impuestos as $key => $value)
				{

					if($i == $n - 1)
						$txt .= round($value,2);
					else
						$txt .= round($value,2) . '/';

					$i++;
				}

			else
				$txt .= round($impuestos[0],2);

			return $txt;
		}

		private function calculate_impuesto_sobre_nomina_asimilables()//this function is called when storing the resume at db, so isr, imss and nomina are already stored
		{
			$empresa = $this->get_empresa();
			$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			$impuestos = array();
			$n = $this->conn->num_rows($result);

			if($n > 0)

				while(list($sucursal) = $this->conn->fetchRow($result))
					$impuestos[$sucursal] = 0;

			else
				$impuestos[0] = 0;

			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Trabajador FROM nomina_asimilables WHERE Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($_trabajador) = $this->conn->fetchRow($result))
			{
				$result1 = $this->conn->query("SELECT Nombre FROM Trabajador_Sucursal WHERE Trabajador = '$_trabajador' AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha_de_ingreso) >= 0 ORDER BY Fecha_de_ingreso DESC LIMIT 1");
				list($sucursal) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);

				if($n > 0)
					$impuestos[$sucursal] += $this->calculate_impuesto_sobre_nomina_asimilable($_trabajador);
				else
					$impuestos[0] += $this->calculate_impuesto_sobre_nomina_asimilable($_trabajador);

			}

			$txt = '';
			$i = 0;

			if($n > 0)

				foreach($impuestos as $key => $value)
				{

					if($i == $n - 1)
						$txt .= round($value,2);
					else
						$txt .= round($value,2) . '/';

					$i++;
				}

			else
				$txt .= round($impuestos[0],2);

			return $txt;
		}

		private function calculate_honorarios($concepts, $fee)
		{
			$value = $this->_sum($concepts);
			$values = explode('/',$value);

			foreach($values as $key => $val)
				$values[$key] = round($values[$key] * $fee / 100,2);

			$txt = implode('/',$values);
			return $txt;
		}

		private function calculate_iva($subtotal_a_facturar,$calcular_iva)
		{
			$values = explode('/',$subtotal_a_facturar);

			foreach($values as $key => $val)
			{
				$values[$key] = $values[$key] * ($calcular_iva == 'true' ? 0.16 : 0);
				$values[$key] = round($values[$key],2);
			}

			$txt = implode('/',$values);
			return $txt;
		}

		private function calculate_impuesto_sobre_nomina($dias_laborados,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de impuesto sobre nómina' AND Ano = '$year'");
			list($porcentaje_de_impuesto_sobre_nomina) = $this->conn->fetchRow($result);
			$numero_de_dias_laborados = count($dias_laborados);
			$result = $this->conn->query("SELECT Base FROM Base WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
			list($base) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$salario_diario = $this->calculate_salario_diario($_trabajador,$base);
			$impuesto_sobre_nomina = 0;

			if(is_array($salario_diario))
			{
				$len = count($salario_diario);

				for($i=0; $i<$numero_de_dias_laborados; $i++)

					for($j=0; $j<$len; $j++)

						if($salario_diario[$j]['Fecha'] <= $dias_laborados[$i])
						{
							$impuesto_sobre_nomina += round($salario_diario[$j]['Cantidad'] * $porcentaje_de_impuesto_sobre_nomina,2);
							break;
						}

			}
			else
				$impuesto_sobre_nomina = round($salario_diario * $porcentaje_de_impuesto_sobre_nomina * $numero_de_dias_laborados,2);

			return $impuesto_sobre_nomina;
		}

		private function calculate_impuesto_sobre_nomina_asimilable($_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = 'Porcentaje de impuesto sobre nómina' AND Ano = '$year'");
			list($porcentaje_de_impuesto_sobre_nomina) = $this->conn->fetchRow($result);
			$result = $this->conn->query("SELECT Total_de_percepciones FROM nomina_asimilables WHERE Trabajador = '$_trabajador' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($percepciones) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$impuesto_sobre_nomina = 0;
			$impuesto_sobre_nomina = round($percepciones * $porcentaje_de_impuesto_sobre_nomina,2);
			return $impuesto_sobre_nomina;
		}

		private function calculate_salario_minimo_trabajador($_trabajador)
		{
			//cheching if this "Empresa" has any "Sucursal"
			$empresa = $this->get_empresa();
			$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			if($this->conn->num_rows($result) > 0)
			{
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '$_trabajador' AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}', Trabajador_Sucursal.Fecha_de_ingreso) >= 0 ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");
			}
			else
			{
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Zona_geografica FROM Empresa WHERE RFC = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

			list($zona) = $this->conn->fetchRow($result);
			$data = explode('-',$this->Limite_inferior_del_periodo);
			$year_inferior = $data[0];
			$data = explode('-',$this->Limite_superior_del_periodo);
			$year_superior = $data[0];
			$result = $this->conn->query("SELECT $zona, Ano FROM Salario_minimo WHERE Codigo = '1' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($cantidad,$ano) = $this->conn->fetchRow($result))

				if($ano == $year_inferior)
					$salario_minimo_inferior = array('Cantidad' => $cantidad, 'Ano' => $ano);
				elseif($ano == $year_superior)
					$salario_minimo_superior = array('Cantidad' => $cantidad, 'Ano' => $ano);

			if($year_inferior == $year_superior)
			{

				if(isset($salario_minimo_inferior))
				{
					$salario_minimo = $salario_minimo_inferior;
					return $salario_minimo_inferior['Cantidad'];//for the moment
				}
				else
				{
					$salario_minimo = array();
					return 0;//for the moment
				}

			}
			else
			{

				if(isset($salario_minimo_inferior) && isset($salario_minimo_superior))
				{
					$salario_minimo = array(0 => $salario_minimo_inferior, 1 => $salario_minimo_superior);
					return $salario_minimo_superior['Cantidad'];//for the moment
				}
				elseif(isset($salario_minimo_inferior))
				{
					$salario_minimo = array(0 => $salario_minimo_inferior);
					return $salario_minimo_inferior['Cantidad'];//for the moment
				}
				elseif(isset($salario_minimo_superior))
				{
					$salario_minimo = array(0 => $salario_minimo_superior);
					return $salario_minimo_superior['Cantidad'];//for the moment
				}
				else
				{
					$salario_minimo = array();
					return 0;//for the moment
				}

			}

			//return $salario_minimo;
		}

		private function calculate_salario_minimo_zona($zona)
		{
			$data = explode('-',$this->Limite_inferior_del_periodo);
			$year_inferior = $data[0];
			$data = explode('-',$this->Limite_superior_del_periodo);
			$year_superior = $data[0];
			$result = $this->conn->query("SELECT $zona, Ano FROM Salario_minimo WHERE Codigo = '1' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($cantidad,$ano) = $this->conn->fetchRow($result))

				if($ano == $year_inferior)
					$salario_minimo_inferior = array('Cantidad' => $cantidad, 'Ano' => $ano);
				elseif($ano == $year_superior)
					$salario_minimo_superior = array('Cantidad' => $cantidad, 'Ano' => $ano);

			if($year_inferior == $year_superior)
			{

				if(isset($salario_minimo_inferior))
				{
					$salario_minimo = $salario_minimo_inferior;
					return $salario_minimo_inferior['Cantidad'];//for the moment
				}
				else
				{
					$salario_minimo = array();
					return 0;//for the moment
				}

			}
			else
			{

				if(isset($salario_minimo_inferior) && isset($salario_minimo_superior))
				{
					$salario_minimo = array(0 => $salario_minimo_inferior, 1 => $salario_minimo_superior);
					return $salario_minimo_superior['Cantidad'];//for the moment
				}
				elseif(isset($salario_minimo_inferior))
				{
					$salario_minimo = array(0 => $salario_minimo_inferior);
					return $salario_minimo_inferior['Cantidad'];//for the moment
				}
				elseif(isset($salario_minimo_superior))
				{
					$salario_minimo = array(0 => $salario_minimo_superior);
					return $salario_minimo_superior['Cantidad'];//for the moment
				}
				else
				{
					$salario_minimo = array();
					return 0;//for the moment
				}

			}

			//return $salario_minimo;
		}

		private function getTipoTrabajador($trabajador)
		{
			date_default_timezone_set('America/Mexico_City');
			$result = $this->conn->query("SELECT Tipo FROM Tipo WHERE Trabajador = '$trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
			list($tipo) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			return $tipo;
		}

		private function get_value($title,$table,$_trabajador)
		{
			//getting table rows
			$rows = explode('<tr>',$table);
			$rows_len = count($rows);
			//getting the second row columns (the titles columns)
			$titles = explode('<td',$rows[2]);//rows[0] contains table declaration, rows[1] contains table title
			$titles_len = count($titles);
			//getting the column number of the column whose content is $title
			$column_number = 0;

			for($i=0; $i<$titles_len; $i++)
			{

				if(preg_match('/'.$title.'/',$titles[$i]) > 0)
					break;

				$column_number++;
			}

			//getting the row containing the $_trabajador
			for($i=0; $i<$rows_len; $i++)
			{

				if(preg_match('/'.$_trabajador.'/',$rows[$i]) > 0)
				{
					$row = $rows[$i];
					break;
				}

			}

			if(isset($row))
			{
				//getting the row columns
				$columns = explode('<td>',$row);
				//getting the right column

				if($column_number < count($columns))
				{
					$column = $columns[$column_number];
					//gettin strings from column
					$values = explode('</td>',$column);
					//getting the value
					$value = $values[0];
					return $value;
				}
				else
					return 0.00;

			}
			else
				return 0.00;
		}

		private function set_value($title,$_table,$_trabajador,$value)
		{
			$table = $this->$_table;
			//getting table rows
			$rows = explode('<tr>',$table);
			$rows_len = count($rows);
			//getting the second row columns (the titles columns)
			$titles = explode('<td',$rows[2]);//rows[0] contains table declaration, rows[1] contains table title
			$titles_len = count($titles);
			//getting the column number of the column whose content is $title
			$column_number = 0;

			for($i=0; $i<$titles_len; $i++)
			{

				if(preg_match('/'.$title.'/',$titles[$i]) > 0)
					break;

				$column_number++;
			}

			//getting the row containing the $_trabajador
			for($i=0; $i<$rows_len; $i++)
			{

				if(preg_match('/'.$_trabajador.'/',$rows[$i]) > 0)
				{
					$row = $rows[$i];
					break;
				}

			}

			//getting the columns
			$columns = explode('<td>',$row);
			//getting the column
			$column = $columns[$column_number];
			//gettin string
			$values = explode('</td>',$column);
			//setting the value
			$values[0] = $value;
			//setting the column
			$column = implode('</td>',$values);
			$columns[$column_number] = $column;
			//setting the row
			$row = implode('<td>',$columns);
			$rows[$i] = $row;
			//setting the table
			$table = implode('<tr>',$rows);
			$this->$_table = $table;
		}

		private function calculate_retencion_por_alimentacion($numero_de_dias_laborados)
		{
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$result = $this->conn->query("SELECT Alimentacion FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($alimentacion) = $this->conn->fetchRow($result);

			if($alimentacion == 'true')
				$retencion_por_alimentacion = $salario_minimo_zona_A * 0.2 * $numero_de_dias_laborados;
			else
				$retencion_por_alimentacion = 0;

			return $retencion_por_alimentacion;
		}

		private function calculate_retencion_por_habitacion($numero_de_dias_laborados)
		{
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			$result = $this->conn->query("SELECT Habitacion FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($habitacion) = $this->conn->fetchRow($result);

			if($habitacion == 'true')
				$retencion_por_habitacion = $salario_minimo_zona_A * 0.2 * $numero_de_dias_laborados;
			else
				$retencion_por_habitacion = 0;

			return $retencion_por_habitacion;
		}

		private function calculate_descuentos_pendientes($retencion, $id)
		{
			$limite_inferior = date_create($this->Limite_inferior_del_periodo);
			$limite_superior = date_create($this->Limite_superior_del_periodo);
			$descuento = 0.00;

			if(isset($this->id))
				$result1 = $this->conn->query("SELECT Cantidad, Numero_de_descuentos, Fecha_de_inicio, Fecha_de_termino FROM Descuento_pendiente WHERE Nomina != '{$this->id}' AND Retencion = '$retencion' AND id = '$id' AND Cuenta = '{$_SESSION['cuenta']}'");
			else
				$result1 = $this->conn->query("SELECT Cantidad, Numero_de_descuentos, Fecha_de_inicio, Fecha_de_termino FROM Descuento_pendiente WHERE Retencion = '$retencion' AND id = '$id' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($cantidad, $numero_de_descuentos, $fecha_de_inicio, $fecha_de_termino) = $this->conn->fetchRow($result1))

				if(isset($cantidad) && isset($numero_de_descuentos) && isset($fecha_de_inicio) && isset($fecha_de_termino))
				{

					$inicio = date_create($fecha_de_inicio);
					$termino = date_create($fecha_de_termino);

					if(($limite_inferior >= $inicio && $limite_inferior <= $termino) || ($limite_superior <= $termino && $limite_superior >= $inicio))
						$descuento += round($cantidad / $numero_de_descuentos,2);

				}

			return $descuento;
		}

		private function calculate_retencion_infonavit($_trabajador,$numero_de_dias_del_periodo,$salario_diario_integrado)
		{
			$retencion_infonavit = 0.00;
			$txt = '';
			$sdi_array = explode('/',$salario_diario_integrado[0]);
			$sdi = $sdi_array[0];
			$year = substr($this->Limite_superior_del_periodo, 0, 4);
			$result = $this->conn->query("SELECT Valor FROM Seguro_por_danos_a_la_vivienda WHERE Ano = '$year'");
			list($seguro_por_danos_a_la_vivienda) = $this->conn->fetchRow($result);
			$salario_minimo_zona_A = $this->calculate_salario_minimo_zona('A');
			date_default_timezone_set('America/Mexico_City');
			$limite_inferior = date_create($this->Limite_inferior_del_periodo);
			$limite_superior = date_create($this->Limite_superior_del_periodo);
			$result = $this->conn->query("SELECT id, Fecha_de_inicio, Fecha_de_termino, Tipo, Dias_exactos FROM Retencion_INFONAVIT WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($id, $fecha_de_inicio, $fecha_de_termino, $tipo, $dias_exactos) = $this->conn->fetchRow($result))
			{

				if(isset($fecha_de_inicio) && isset($fecha_de_termino))
				{
					$inicio = date_create($fecha_de_inicio);
					$termino = date_create($fecha_de_termino);

					if(($limite_inferior >= $inicio && ($fecha_de_termino == '0000-00-00' || $limite_inferior <= $termino)) || (( $fecha_de_termino == '0000-00-00' || $limite_superior <= $termino) && $limite_superior >= $inicio))
					{

						if($tipo == 'VSM')
						{
							//getting the last factor
							$result1 = $this->conn->query("SELECT Factor_de_descuento, Cobrar_diferencia_inicial, Fecha_de_inicio, Fecha_de_cobro FROM Factor_de_descuento WHERE DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha_de_inicio) >= 0 AND Retencion_INFONAVIT = '$id' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_inicio DESC LIMIT 1");

							if($this->conn->num_rows($result1) > 0)
								list($factor,$cobrar_diferencia_inicial,$fecha_inicio,$fecha_cobro) = $this->conn->fetchRow($result1);
							else
							{
								$factor = 0;
								$cobrar_diferencia_inicial = 'false';
							}

							$retencion_bimestral = round($factor * $salario_minimo_zona_A * 2,2);
						}
						elseif($tipo == 'Porcentual')
						{
							//getting the last "porcentaje"
							$result1 = $this->conn->query("SELECT Porcentaje_de_descuento, Cobrar_diferencia_inicial, Fecha_de_inicio, Fecha_de_cobro FROM Porcentaje_de_descuento WHERE DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha_de_inicio) >= 0 AND Retencion_INFONAVIT = '$id' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_inicio DESC LIMIT 1");

							if($this->conn->num_rows($result1) > 0)
								list($porcentaje,$cobrar_diferencia_inicial,$fecha_inicio,$fecha_cobro) = $this->conn->fetchRow($result1);
							else
							{
								$porcentaje = 0;
								$cobrar_diferencia_inicial = 'false';
							}

							$descuento_diario = round($sdi * $porcentaje,2);
							$retencion_bimestral = round($descuento_diario * 365 / 6,2);
						}
						else//monto fijo mensual
						{
							//getting the last "monto"
							$result1 = $this->conn->query("SELECT Monto_fijo_mensual, Cobrar_diferencia_inicial, Fecha_de_inicio, Fecha_de_cobro FROM Monto_fijo_mensual WHERE DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha_de_inicio) >= 0 AND Retencion_INFONAVIT = '$id' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_inicio DESC LIMIT 1");

							if($this->conn->num_rows($result1) > 0)
								list($monto,$cobrar_diferencia_inicial,$fecha_inicio,$fecha_cobro) = $this->conn->fetchRow($result1);
							else
							{
								$monto = 0;
								$cobrar_diferencia_inicial = 'false';
							}


							$retencion_bimestral = round($monto * 2, 2);
						}

						$retencion_bimestral_total = $retencion_bimestral + $seguro_por_danos_a_la_vivienda;

						if($dias_exactos == 'true')
						{

							for($i=0; $i<$numero_de_dias_del_periodo; $i++)
							{
								$days = new DateInterval('P' . $i . 'D');
								$day = $limite_inferior->add($days);

								if($day >= $inicio && ($fecha_de_termino == '0000-00-00' || $day <= $termino))
								{
									$b = ceil($day->format('n') / 2);
									$hb = date_create($day->format('Y') . '-' . ($b * 2) . '-' . cal_days_in_month(CAL_GREGORIAN, ($b * 2), $day->format('Y')));
									$lb = date_create($day->format('Y') . '-' . ($b * 2 - 1) . '-01');
									$interval = $lb->diff($hb);
									$retencion_infonavit += round($retencion_bimestral_total / ($interval->days + 1), 2);
								}

								$day = $limite_inferior->sub($days);
							}

						}
						else
						{

							if($numero_de_dias_del_periodo <= 7)
								$retencion_infonavit = round($retencion_bimestral_total / 56 * $numero_de_dias_del_periodo,2);
							else if($numero_de_dias_del_periodo <= 16)
								$retencion_infonavit = round($retencion_bimestral_total / 4,2);
							else
								$retencion_infonavit = round($retencion_bimestral_total / 2,2);

							//Parte proporcional de retención en caso de que la retencion comience despues del lip o termine antes del lsp
							$ret_info_1D = $retencion_infonavit / $numero_de_dias_del_periodo;

							if($limite_inferior < $inicio)
							{
								$interval = $limite_inferior->diff($inicio);
								$n = $interval->days;
								$retencion_infonavit -= round($ret_info_1D * $n, 2);
							}

							if($fecha_de_termino != '0000-00-00' && $termino < $limite_superior)
							{
								$interval = $termino->diff($limite_superior);
								$n = $interval->days;
								$retencion_infonavit -= round($ret_info_1D * $n, 2);
							}

						}

						//Diferencia inicial
						$diff = 0.00;

						if($cobrar_diferencia_inicial == 'true' && $fecha_cobro >= $this->Limite_inferior_del_periodo && $fecha_cobro <= $this->Limite_superior_del_periodo && $fecha_inicio < $this->Limite_inferior_del_periodo)
						{
							//calculating the amount to be covered
							$result1 = $this->conn->query("SELECT Periodicidad_de_la_nomina FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($periodicidad) = $this->conn->fetchRow($result1);
							$this->conn->freeResult($result1);
							$_inicio = date_create($fecha_inicio);
							$interval = $_inicio->diff($limite_inferior);

							if($periodicidad == 'Semanal')
								$diff = $retencion_infonavit * ($interval->days) / 7;
							elseif($periodicidad == 'Quincenal')
							{
								$months = $limite_inferior->format('m') - $_inicio->format('m');

								if($_inicio->format('d') == '01')
								{
									$complete_periods = $months * 2;
									$partial_period = 0;
								}
								elseif($_inicio->format('d') <= '16')
								{
									$complete_periods = ($months - 1) * 2 + 1;
									$partial_period = (16 - $_inicio->format('d')) / 15;
								}
								else
								{
									$complete_periods = ($months - 1) * 2;
									$partial_period = (cal_days_in_month(CAL_GREGORIAN, $_inicio->format('m'), $_inicio->format('Y')) - $_inicio->format('d') + 1) / (cal_days_in_month(CAL_GREGORIAN, $_inicio->format('m'), $_inicio->format('Y')) - 15);
								}

								$diff = $retencion_infonavit * ($complete_periods + $partial_period);
							}
							else//mensual
							{
								$months = $limite_inferior->format('m') - $_inicio->format('m');

								if($_inicio->format('d') == '01')
								{
									$complete_periods = $months;
									$partial_period = 0;
								}
								else
								{
									$complete_periods = $months - 1;
									$partial_period = (cal_days_in_month(CAL_GREGORIAN, $_inicio->format('m'), $_inicio->format('Y')) - $_inicio->format('d') + 1) / (cal_days_in_month(CAL_GREGORIAN, $_inicio->format('m'), $_inicio->format('Y')));
								}

								$diff = $retencion_infonavit * ($complete_periods + $partial_period);
							}

							$this->conn->freeResult($result1);
							//calculating the amount already covered
							$result1 = $this->conn->query("SELECT nomina_asalariados.Retencion_INFONAVIT FROM Nomina LEFT JOIN nomina_asalariados ON Nomina.id = nomina_asalariados.Nomina WHERE DATEDIFF('{$this->Limite_inferior_del_periodo}', Nomina.Limite_superior_del_periodo) > 0 AND DATEDIFF(Nomina.Limite_superior_del_periodo, '$fecha_de_inicio') > 0 AND Nomina.Servicio = '{$this->Servicio}'AND nomina_asalariados.Trabajador = '$_trabajador' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND nomina_asalariados.Cuenta = '{$_SESSION['cuenta']}'");
							$payed = 0;

							while(list($val) = $this->conn->fetchRow($result1))
							{

								$data = explode(',',$val);
								$len = count($data);

								for($i=0; $i<$len; $i++)
								{
									$values = explode('</span>',$data[$i]);

									if(count($values) > 1)
									{
										$_id = str_replace('<span style="visibility:hidden">','',$values[0]);

										if($_id == $id)
										{
											$value = str_replace('<span>','',$values[1]);
											$payed += $value;
											break;
										}

									}

								}

							}

							$diff -= $payed;
						}

						//Descuentos pendientes
						$retencion_infonavit += $this->calculate_descuentos_pendientes('Retención INFONAVIT', $id);
						//adding "diferencia inicial"
						$retencion_infonavit += $diff;

						if($txt != '')
							$txt .= ',<span style = "visibility:hidden">' . $id . '</span><span>' . $retencion_infonavit . '</span>';
						else
							$txt = '<span style = "visibility:hidden">' . $id . '</span><span>' . $retencion_infonavit . '</span>';

					}

				}

			}

			return $txt;
		}

		private function calculate_retencion_fonacot($_trabajador,$numero_de_dias_del_periodo)
		{
			$retencion_fonacot = 0;
			$txt = '';
			date_default_timezone_set('America/Mexico_City');
			$limite_inferior = date_create($this->Limite_inferior_del_periodo);
			$limite_superior = date_create($this->Limite_superior_del_periodo);
			$result = $this->conn->query("SELECT id, Importe_total, Numero_de_descuentos, Fecha_de_inicio, Fecha_de_termino FROM Retencion_FONACOT WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($id, $importe_total, $numero_de_descuentos, $fecha_de_inicio, $fecha_de_termino) = $this->conn->fetchRow($result))
			{

				if(isset($importe_total) && isset($numero_de_descuentos) && isset($fecha_de_inicio) && isset($fecha_de_termino))
				{
					$inicio = date_create($fecha_de_inicio);
					$termino = date_create($fecha_de_termino);

					if(($limite_inferior >= $inicio && $limite_inferior <= $termino) || ($limite_superior <= $termino && $limite_superior >= $inicio))//if this retention enters in this period
					{
//						$interval = date_diff($inicio, $termino);
//						$meses = $interval->y * 12 + $interval->m;
//						$descuento_mensual = round($importe_total / $meses,2);

//						if($numero_de_dias_del_periodo <= 7)//weekly or less
//							$retencion_fonacot = round($importe_total / ($interval->days + 1) * $numero_de_dias_del_periodo,2);
//						else//fortnightly and monthly
							$retencion_fonacot = round($importe_total / $numero_de_descuentos,2);

						//Descuentos pendientes
						$retencion_fonacot += $this->calculate_descuentos_pendientes('Retención FONACOT', $id);
						$retencion_fonacot = round($retencion_fonacot, 2);
						$result1 = $this->conn->query("SELECT Cobrar_un_mes_anterior FROM Retencion_FONACOT WHERE id = '$id' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($cobrar_un_mes_anterior) = $this->conn->fetchRow($result1);

						if($cobrar_un_mes_anterior == 'true' && date('m', $inicio->format('U')) == date('m', $limite_inferior->format('U')))
							$retencion_fonacot *= 2;

						if($retencion_fonacot > 0)
						{

							if($txt != '')
								$txt .= ',<span style = "visibility:hidden">' . $id . '</span><span>' . $retencion_fonacot . '</span>';
							else
								$txt = '<span style = "visibility:hidden">' . $id . '</span><span>' . $retencion_fonacot . '</span>';

						}

					}

				}

			}

			return $txt;
		}

		private function calculate_pago_por_seguro_de_vida($_trabajador)
		{
			$pago_por_seguro_de_vida = 0;
			$txt = '';
			date_default_timezone_set('America/Mexico_City');
			$result = $this->conn->query("SELECT id, Cantidad FROM Pago_por_seguro_de_vida WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($id, $cantidad) = $this->conn->fetchRow($result))
			{

				if(isset($cantidad))
				{
					$pago_por_seguro_de_vida = $cantidad;
					$pago_por_seguro_de_vida += $this->calculate_descuentos_pendientes('Pago por seguro de vida', $id);

					if($txt != '')
						$txt .= ',<span style = "visibility:hidden">' . $id . '</span><span>' . $pago_por_seguro_de_vida . '</span>';
					else
						$txt = '<span style = "visibility:hidden">' . $id . '</span><span>' . $pago_por_seguro_de_vida . '</span>';

				}

			}

			return $txt;
		}

		private function calculate_aportacion_al_fondo_de_ahorro($_trabajador)
		{
			$aportacion_del_trabajador_al_fondo_de_ahorro = 0;
			$txt = '';
			date_default_timezone_set('America/Mexico_City');
			$limite_inferior = date_create($this->Limite_inferior_del_periodo);
			$limite_superior = date_create($this->Limite_superior_del_periodo);
			$sueldo = $this->get_value('Sueldo',$this->nomina_asalariados,$_trabajador);
			$result = $this->conn->query("SELECT id, Porcentaje_del_salario, Fecha_de_inicio, Fecha_de_termino FROM Aportacion_del_trabajador_al_fondo_de_ahorro WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($id, $porcentaje, $fecha_de_inicio, $fecha_de_termino) = $this->conn->fetchRow($result))
			{

				if(isset($porcentaje) && isset($fecha_de_inicio) && isset($fecha_de_termino))
				{
					$inicio = date_create($fecha_de_inicio);
					$termino = date_create($fecha_de_termino);

					if($inicio <= $limite_superior && ($fecha_de_termino == '0000-00-00' || $termino >= $limite_inferior))
					{

						$aportacion_del_trabajador_al_fondo_de_ahorro = $porcentaje * $sueldo / 100;

						if($txt != '')
							$txt .= ',<span style = "visibility:hidden">' . $id . '</span><span>' . $aportacion_del_trabajador_al_fondo_de_ahorro . '</span>';
						else
							$txt = '<span style = "visibility:hidden">' . $id . '</span><span>' . $aportacion_del_trabajador_al_fondo_de_ahorro . '</span>';

					}

				}

			}

			return $txt;
		}

		private function calculate_pension_alimenticia($_trabajador)
		{
			$pension_alimenticia = 0;
			$txt = '';
			date_default_timezone_set('America/Mexico_City');
			$result = $this->conn->query("SELECT id,Cantidad FROM Pension_alimenticia WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_inicio, '{$this->Limite_superior_del_periodo}') <= 0 AND (DATEDIFF(Fecha_de_termino, '{$this->Limite_inferior_del_periodo}') >= 0 OR Fecha_de_termino = '0000-00-00')");

			while(list($id,$cantidad) = $this->conn->fetchRow($result))
			{

				if(isset($cantidad))
				{
					$pension_alimenticia = $cantidad;
					$pension_alimenticia += $this->calculate_descuentos_pendientes('Pensión alimenticia', $id);

					if($txt != '')
						$txt .= ',<span style = "visibility:hidden">' . $id . '</span><span>' . $pension_alimenticia . '</span>';
					else
						$txt = '<span style = "visibility:hidden">' . $id . '</span><span>' . $pension_alimenticia . '</span>';

				}

			}

			return $txt;
		}

		private function calculate_gestion_administrativa($_trabajador,$table)
		{

			if($table == 'nomina_asalariados')
				$result = $this->conn->query("SELECT dgan FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			else
				$result = $this->conn->query("SELECT cgaa FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");

			list($flag) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$gestion = 0.00;

			if($flag == 'true')
			{
				$result = $this->conn->query("SELECT Porcentaje_de_honorarios FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($honorarios) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$pago_neto = $this->get_value('Pago neto',$this->incidencias,$_trabajador);
				$pago_liquido = $this->get_value('Pago líquido',$this->incidencias,$_trabajador);

				if($pago_neto > $pago_liquido)
					$gestion = round($pago_neto * $honorarios / 100, 2);
				else
					$gestion = round($pago_liquido * $honorarios / 100, 2);

			}

			return $gestion;
		}

		private function calculate_total_de_deducciones($_trabajador)
		{
			$isr = $this->get_value('ISR',$this->nomina_asalariados,$_trabajador);
			$cuotas_IMSS_obreras = $this->get_value('Cuotas IMSS',$this->nomina_asalariados,$_trabajador);
			$retencion_por_alimentacion = $this->get_value('Retención por alimentación',$this->nomina_asalariados,$_trabajador);
			$retencion_por_habitacion = $this->get_value('Retención por habitación',$this->nomina_asalariados,$_trabajador);
			$retencion_INFONAVIT_txt = $this->get_value('Retención INFONAVIT',$this->nomina_asalariados,$_trabajador);
			$retencion_FONACOT_txt = $this->get_value('Retención FONACOT',$this->nomina_asalariados,$_trabajador);
			$aportacion_del_trabajador_al_fondo_de_ahorro_txt = $this->get_value('Aportación del trabajador al fondo de ahorro',$this->nomina_asalariados,$_trabajador);
			$pension_alimenticia_txt =  $this->get_value('Pensión alimenticia',$this->nomina_asalariados,$_trabajador);
			$retardos = $this->get_value('Retardos',$this->nomina_asalariados,$_trabajador);
			$retencion_INFONAVIT_array = explode(',',$retencion_INFONAVIT_txt);
			$retencion_FONACOT_array = explode(',',$retencion_FONACOT_txt);
			$aportacion_del_trabajador_al_fondo_de_ahorro_array = explode(',',$aportacion_del_trabajador_al_fondo_de_ahorro_txt);
			$pension_alimenticia_array = explode(',',$pension_alimenticia_txt);
			$prestaciones = $this->get_value('Prestaciones',$this->nomina_asalariados,$_trabajador);
			$gestion_administrativa = $this->get_value('Gestión administrativa',$this->nomina_asalariados,$_trabajador);

			//total retención INFONAVIT
			$len = count($retencion_INFONAVIT_array);
			$total_retencion_INFONAVIT = 0;

			for($i=0; $i<$len; $i++)
			{
				$values = explode('</span>',$retencion_INFONAVIT_array[$i]);

				if(count($values) > 1)
				{
					$value = str_replace('<span>','',$values[1]);//values[0] is id
					$total_retencion_INFONAVIT += $value;
				}

			}

			//total retención FONACOT
			$len = count($retencion_FONACOT_array);
			$total_retencion_FONACOT = 0;

			for($i=0; $i<$len; $i++)
			{
				$values = explode('</span>',$retencion_FONACOT_array[$i]);

				if(count($values) > 1)
				{
					$value = str_replace('<span>','',$values[1]);//values[0] is id
					$total_retencion_FONACOT += $value;
				}

			}

			//total aportacion del trabajador al fondo de ahorro
			$len = count($aportacion_del_trabajador_al_fondo_de_ahorro_array);
			$total_aportacion_del_trabajador_al_fondo_de_ahorro = 0;

			for($i=0; $i<$len; $i++)
			{
				$values = explode('</span>',$aportacion_del_trabajador_al_fondo_de_ahorro_array[$i]);

				if(count($values) > 1)
				{
					$value = str_replace('<span>','',$values[1]);//values[0] is id
					$total_aportacion_del_trabajador_al_fondo_de_ahorro += $value;
				}

			}

			//total pensión alimenticia
			$len = count($pension_alimenticia_array);
			$total_pension_alimenticia = 0;

			for($i=0; $i<$len; $i++)
			{
				$values = explode('</span>',$pension_alimenticia_array[$i]);

				if(count($values) > 1)
				{
					$value = str_replace('<span>','',$values[1]);//values[0] is id
					$total_pension_alimenticia += $value;
				}

			}

			//total retardos
			$total_retardos = $retardos;

			$total_de_deducciones = $total_retencion_INFONAVIT + $total_retencion_FONACOT + $total_aportacion_del_trabajador_al_fondo_de_ahorro + $total_pension_alimenticia + $total_retardos + $cuotas_IMSS_obreras + $retencion_por_alimentacion + $retencion_por_habitacion + $isr + $prestaciones + $gestion_administrativa;
			return $total_de_deducciones;
		}

		private function calculate_total_de_percepciones($_trabajador)
		{
			//$despensa + $comida + $alimentacion + $habitacion + $aportacion_patronal_al_fondo_de_ahorro;
			$sueldo = $this->get_value('Sueldo',$this->nomina_asalariados,$_trabajador);
			$subsidio_al_empleo = $this->get_value('Subsidio al empleo',$this->nomina_asalariados,$_trabajador);
			$horas_extra = $this->get_value('Horas extra',$this->nomina_asalariados,$_trabajador);
			$prima_dominical = $this->get_value('Prima dominical',$this->nomina_asalariados,$_trabajador);
			$dias_de_descanso = $this->get_value('Días de descanso',$this->nomina_asalariados,$_trabajador);
			$premios_de_puntualidad_y_asistencia = $this->get_value('Premios de puntualidad y asistencia',$this->nomina_asalariados,$_trabajador);
			$bonos_de_productividad = $this->get_value('Bonos de productividad',$this->nomina_asalariados,$_trabajador);
			$estimulos =  $this->get_value('Estímulos',$this->nomina_asalariados,$_trabajador);
			$compensaciones = $this->get_value('Compensaciones',$this->nomina_asalariados,$_trabajador);
			$despensa = $this->get_value('Despensa',$this->nomina_asalariados,$_trabajador);
			$comida = $this->get_value('Comida',$this->nomina_asalariados,$_trabajador);
			$alimentacion = $this->get_value('Alimentación',$this->nomina_asalariados,$_trabajador);
			$habitacion = $this->get_value('Habitación',$this->nomina_asalariados,$_trabajador);
			$aportacion_patronal_al_fondo_de_ahorro = $this->get_value('Aportación patronal al fondo de ahorro',$this->nomina_asalariados,$_trabajador);
			$aportacion_patronal_al_fondo_de_ahorro_array = explode(',',$aportacion_patronal_al_fondo_de_ahorro);

			//total aportacion patronal al fondo de ahorro
			$len = count($aportacion_patronal_al_fondo_de_ahorro_array);
			$total_aportacion_patronal_al_fondo_de_ahorro = 0;

			for($i=0; $i<$len; $i++)
			{
				$values = explode('</span>',$aportacion_patronal_al_fondo_de_ahorro_array[$i]);

				if(count($values) > 1)
				{
					$value = str_replace('<span>','',$values[1]);//values[0] is id
					$total_aportacion_patronal_al_fondo_de_ahorro += $value;
				}

			}

			$total_de_percepciones = $sueldo + $subsidio_al_empleo + $horas_extra + $prima_dominical + $dias_de_descanso + $premios_de_puntualidad_y_asistencia + $bonos_de_productividad + $estimulos + $compensaciones + $despensa + $comida + $alimentacion + $habitacion + $total_aportacion_patronal_al_fondo_de_ahorro;
			return $total_de_percepciones;
		}

		private function dif_ps($_trabajador,$sueldo,$numero_de_dias_del_periodo,$numero_de_dias_laborados,$pago,$A,$B,$_base_ISR,$ps)
		{
			$base_ISR = $_base_ISR;
			$psg = $this->calculate_prevision_social_gravada($ps,$sueldo,$numero_de_dias_del_periodo,$_trabajador);
			$base_ISR += $psg;
			$li = $this->calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			$eli = $base_ISR - $li;
			$pseli = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			$im = round($eli * $pseli,2);
			$cf = $this->calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			$id = $im + $cf;
			$se = $this->calculate_subsidio($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			$isr = $id - $se;
			$subsidio_al_empleo = 0;
			$percepciones = $A + $subsidio_al_empleo + $ps;
			$deducciones = $B + $isr;
			$saldo = $percepciones - $deducciones;
			$dif_ps = $saldo - $pago;
			return $dif_ps;
		}

		public function calculate_prevision_social($_trabajador,$sueldo,$numero_de_dias_del_periodo,$numero_de_dias_laborados,$pago,$A,$B,$_base_ISR)
		{
			$retencion_por_alimentacion = $this->calculate_retencion_por_alimentacion($numero_de_dias_laborados);
			$retencion_por_habitacion = $this->calculate_retencion_por_habitacion($numero_de_dias_laborados);
			$base_ISR = $_base_ISR;
			$B += $retencion_por_alimentacion + $retencion_por_habitacion;
			$ps = 0;
			$i = 0;

			do
			{
				$psg = $this->calculate_prevision_social_gravada($ps,$sueldo,$numero_de_dias_del_periodo,$_trabajador);
				$base_ISR = $_base_ISR + $psg;
				$li = $this->calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
				$eli = $base_ISR - $li;
				$pseli = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
				$im = round($eli * $pseli,2);
				$cf = $this->calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
				$id = $im + $cf;
				$se = $this->calculate_subsidio($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);

				if($id > $se)
				{
					$isr = $id - $se;
					$subsidio_al_empleo = 0;
					$percepciones = $A + $subsidio_al_empleo + $ps;
					$deducciones = $B + $isr;
					$saldo = $percepciones - $deducciones;
					$dif_ps = $pago - $saldo;

					if($dif_ps < 0)
					{//Bisection method
						$j = 0;
						$error = 0.001;
						$b = $ps;

						while($j < 3000)
						{
							$m = ($a + $b) / 2;

							if( $this->dif_ps($_trabajador,$sueldo,$numero_de_dias_del_periodo,$numero_de_dias_laborados,$pago,$A,$B,$_base_ISR,$m) == 0 || ($b - $a) / 2 < $error)
							{
								$ps = round($m,4);
								$psg = $this->calculate_prevision_social_gravada($ps,$sueldo,$numero_de_dias_del_periodo,$_trabajador);
								$base_ISR = $_base_ISR + $psg;
								$li = $this->calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
								$eli = $base_ISR - $li;
								$pseli = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
								$im = round($eli * $pseli,2);
								$cf = $this->calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
								$id = $im + $cf;
								$se = $this->calculate_subsidio($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
								$isr = $id - $se;
								$subsidio_al_empleo = 0;
								$percepciones = $A + $subsidio_al_empleo + $ps;
								$deducciones = $B + $isr;
								$saldo = $percepciones - $deducciones;
								$dif_ps = $saldo - $pago;
								break;
							}
							else
							{

								if($this->dif_ps($_trabajador,$sueldo,$numero_de_dias_del_periodo,$numero_de_dias_laborados,$pago,$A,$B,$_base_ISR,$a) * $this->dif_ps($_trabajador,$sueldo,$numero_de_dias_del_periodo,$numero_de_dias_laborados,$pago,$A,$B,$_base_ISR,$m) < 0)
									$b = round($m,4);
								else
									$a = round($m,4);

							}

							$dif_ps = $this->dif_ps($_trabajador,$sueldo,$numero_de_dias_del_periodo,$numero_de_dias_laborados,$pago,$A,$B,$_base_ISR,$m);
							$j++;
						}

						break;
					}
					else
					{
						$a = $ps;
						$ps += 50;
					}

				}
				else
				{
					$isr = 0;
					$subsidio_al_empleo = $se - $id;
					$percepciones = $A + $subsidio_al_empleo + $ps;
					$deducciones = $B + $isr;
					$saldo = $percepciones - $deducciones;
					$dif_ps = $pago - $saldo;
					$ps += $dif_ps;
					$a = $ps;

					if($dif_ps <= 0.001)
						break;
				}

				$i++;
			}while($i < 3000);

			$alimentacion = $this->calculate_alimentacion($ps);
			$habitacion = $this->calculate_habitacion($ps);
			return array($alimentacion,$retencion_por_alimentacion,$habitacion,$retencion_por_habitacion,$psg,$base_ISR,$li,$eli,$pseli,$im,$cf,$id,$se,$isr,$subsidio_al_empleo,$percepciones,$deducciones,$saldo,$dif_ps,$ps);
		}

		private function calculate_alimentacion($prevision_social)
		{
			$result = $this->conn->query("SELECT Alimentacion FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($_alimentacion) = $this->conn->fetchRow($result);

			if($_alimentacion == 'true')
			{
				$result = $this->conn->query("SELECT Habitacion FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($_habitacion) = $this->conn->fetchRow($result);

				if($_habitacion == 'true')
					$alimentacion = round($prevision_social / 2,2);
				else
					$alimentacion = round($prevision_social,2);

			}
			else
				$alimentacion = 0;

			return $alimentacion;
		}

		private function calculate_habitacion($prevision_social)
		{
			$result = $this->conn->query("SELECT Habitacion FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($_habitacion) = $this->conn->fetchRow($result);

			if($_habitacion == 'true')
			{
				$result = $this->conn->query("SELECT Alimentacion FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($_alimentacion) = $this->conn->fetchRow($result);

				if($_alimentacion == 'true')
					$habitacion = round($prevision_social / 2,2);
				else
					$habitacion = round($prevision_social,2);

			}
			else
				$habitacion = 0;

			return $habitacion;
		}

		private function calculate_prevision_social_gravada($ps,$sueldo,$numero_de_dias_del_periodo,$_trabajador)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($_trabajador);
			$_7vsmag = 7 * $salario_minimo * $numero_de_dias_del_periodo;

			if($sueldo + $ps > $_7vsmag)
			{
				$limite = $salario_minimo * $numero_de_dias_del_periodo;

				if($sueldo + $limite >= $_7vsmag)
					$psng = $limite;
				else
					$psng = $_7vsmag - $sueldo;

			}
			else
				$psng = $ps;

			$psg = ($ps - $psng) < 0.00 ? 0.00 : ($ps - $psng);
			return round($psg,2);
		}

		private function calculate_fondo_de_garantia($_trabajador)
		{
			date_default_timezone_set('America/Mexico_City');
			$result = $this->conn->query("SELECT Porcentaje_del_pago_neto FROM Fondo_de_garantia WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha_de_inicio) >= 0");
			$pago_neto = $this->get_value('Pago neto',$this->nomina_asalariados,$_trabajador);
			$fondo = 0.00;

			while(list($porcentaje) = $this->conn->fetchRow($result))
				$fondo += $pago_neto * $porcentaje / 100;

			return $fondo;
		}

		private function calculate_prestamo_caja($_trabajador)
		{
			$txt = '';
			date_default_timezone_set('America/Mexico_City');
			$result = $this->conn->query("SELECT Prestamo_caja FROM Trabajador_Prestamo_caja WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($id) = $this->conn->fetchRow($result))
			{
				$prestamo_caja = 0;
				$result1 = $this->conn->query("SELECT Cantidad_a_descontar FROM Prestamo_caja WHERE Numero_de_prestamo = '$id' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_descuento, '{$this->Limite_superior_del_periodo}') <= 0 AND DATEDIFF(Fecha_de_descuento, '{$this->Limite_inferior_del_periodo}') >= 0");

				while(list($cantidad) = $this->conn->fetchRow($result1))

					if(isset($cantidad))
						$prestamo_caja += $cantidad;

				$prestamo_caja += $this->calculate_descuentos_pendientes('Préstamo caja', $id);

				if($txt != '' && $prestamo_caja != 0)
					$txt .= ',<span style = "visibility:hidden">' . $id . '</span><span>' . $prestamo_caja . '</span>';
				elseif($prestamo_caja != 0)
					$txt = '<span style = "visibility:hidden">' . $id . '</span><span>' . $prestamo_caja . '</span>';

			}

			return $txt;
		}

		private function calculate_prestamo_del_fondo_de_ahorro($_trabajador)
		{
			$txt = '';
			date_default_timezone_set('America/Mexico_City');
			$result = $this->conn->query("SELECT Prestamo_del_fondo_de_ahorro FROM Trabajador_Prestamo_del_fondo_de_ahorro WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($id) = $this->conn->fetchRow($result))
			{
				$prestamo_del_fondo_de_ahorro = 0;
				$result1 = $this->conn->query("SELECT Cantidad_a_descontar FROM Prestamo_del_fondo_de_ahorro WHERE Numero_de_prestamo = '$id' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_descuento, '{$this->Limite_superior_del_periodo}') <= 0 AND DATEDIFF(Fecha_de_descuento, '{$this->Limite_inferior_del_periodo}') >= 0");

				while(list($cantidad) = $this->conn->fetchRow($result1))

					if(isset($cantidad))
						$prestamo_del_fondo_de_ahorro += $cantidad;

				$prestamo_del_fondo_de_ahorro += $this->calculate_descuentos_pendientes('Préstamo del fondo de ahorro', $id);

				if($txt != '' && $prestamo_del_fondo_de_ahorro != 0)
					$txt .= ',<span style = "visibility:hidden">' . $id . '</span><span>' . $prestamo_del_fondo_de_ahorro . '</span>';
				elseif($prestamo_del_fondo_de_ahorro != 0)
					$txt = '<span style = "visibility:hidden">' . $id . '</span><span>' . $prestamo_del_fondo_de_ahorro . '</span>';

			}

			return $txt;
		}

		private function calculate_prestamo_cliente($_trabajador)
		{
			$txt = '';
			date_default_timezone_set('America/Mexico_City');
			$result = $this->conn->query("SELECT Prestamo_cliente FROM Trabajador_Prestamo_cliente WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($id) = $this->conn->fetchRow($result))
			{
				$prestamo_cliente = 0;
				$result1 = $this->conn->query("SELECT Cantidad_a_descontar FROM Prestamo_cliente WHERE Numero_de_prestamo = '$id' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_descuento, '{$this->Limite_superior_del_periodo}') <= 0 AND DATEDIFF(Fecha_de_descuento, '{$this->Limite_inferior_del_periodo}') >= 0");

				while(list($cantidad) = $this->conn->fetchRow($result1))

					if(isset($cantidad))
						$prestamo_cliente += $cantidad;

				$prestamo_cliente += $this->calculate_descuentos_pendientes('Préstamo cliente', $id);

				if($txt != '' && $prestamo_cliente != 0)
					$txt .= ',<span style = "visibility:hidden">' . $id . '</span><span>' . $prestamo_cliente . '</span>';
				elseif($prestamo_cliente != 0)
					$txt = '<span style = "visibility:hidden">' . $id . '</span><span>' . $prestamo_cliente . '</span>';

			}

			return $txt;
		}

		private function calculate_prestamo_administradora($_trabajador)
		{
			$txt = '';
			date_default_timezone_set('America/Mexico_City');
			$result = $this->conn->query("SELECT Prestamo_administradora FROM Trabajador_Prestamo_administradora WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($id) = $this->conn->fetchRow($result))
			{
				$prestamo_administradora = 0;
				$result1 = $this->conn->query("SELECT Cantidad_a_descontar FROM Prestamo_administradora WHERE Numero_de_prestamo = '$id' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_descuento, '{$this->Limite_superior_del_periodo}') <= 0 AND DATEDIFF(Fecha_de_descuento, '{$this->Limite_inferior_del_periodo}') >= 0");

				while(list($cantidad) = $this->conn->fetchRow($result1))

					if(isset($cantidad))
						$prestamo_administradora += $cantidad;

				$prestamo_administradora += $this->calculate_descuentos_pendientes('Préstamo administradora', $id);

				if($txt != '' && $prestamo_administradora != 0)
					$txt .= ',<span style = "visibility:hidden">' . $id . '</span><span>' . $prestamo_administradora . '</span>';
				elseif($prestamo_administradora != 0)
					$txt = '<span style = "visibility:hidden">' . $id . '</span><span>' . $prestamo_administradora . '</span>';

			}

			return $txt;
		}

		private function calculate_vacaciones($anos_de_antiguedad,$salario_diario,$_trabajador)
		{

			if(is_array($salario_diario))
				$salario = $salario_diario[0]['Cantidad'];//the last salario diario will be used
			else
				$salario = $salario_diario;

			if($anos_de_antiguedad < 5)
				$dias_de_vacaciones = $anos_de_antiguedad * 2 + 4;
			else
				$dias_de_vacaciones = floor($anos_de_antiguedad / 5) * 2 + 12;

			return round($dias_de_vacaciones * $salario,2);
		}

		private function calculate_retencion_proporcional_de_vacaciones($vacaciones,$numero_de_dias)
		{
			$retencion_proporcional_de_vacaciones = round($vacaciones * $numero_de_dias / 365,2);
			return $retencion_proporcional_de_vacaciones;
		}

		private function calculate_prima_vacacional($vacaciones)
		{
			return $vacaciones * 0.25;
		}

		private function calculate_retencion_proporcional_de_prima_vacacional($retencion_proporcional_de_vacaciones)
		{

			return $retencion_proporcional_de_vacaciones * 0.25;
		}

		private function calculate_aguinaldo($salario_diario,$_trabajador)
		{
			$result = $this->conn->query("SELECT Dias_de_aguinaldo FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($dias_de_aguinaldo) = $this->conn->fetchRow($result);
			$fecha_de_ingreso = $this->calculate_ingreso($_trabajador, 'prestaciones');
			$lsp = date_create($this->Limite_superior_del_periodo);

			if(date_create($fecha_de_ingreso) > date_create($lsp->format('Y') . '-01-01'))
			{
				$interval = date_diff(date_create($fecha_de_ingreso),date_create(date('Y') . '-01-01'));
				$aux = 365 - $interval->days;
				$dias_de_aguinaldo = round($aux * $dias_de_aguinaldo / 365, 2);
			}

			if(is_array($salario_diario))
				$salario = $salario_diario[0]['Cantidad'];//the last salario diario will be used
			else
				$salario = $salario_diario;

			return round($salario * $dias_de_aguinaldo,2);
		}

		private function calculate_retencion_proporcional_de_aguinaldo($salario_diario,$numero_de_dias,$_trabajador)
		{
			$result = $this->conn->query("SELECT Dias_de_aguinaldo FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($dias_de_aguinaldo) = $this->conn->fetchRow($result);

			if(is_array($salario_diario))
				$salario = $salario_diario[0]['Cantidad'];//the last salario diario will be used
			else
				$salario = $salario_diario;

			$retencion_proporcional_de_aguinaldo = round($salario * $numero_de_dias * $dias_de_aguinaldo / 365,2);
			return $retencion_proporcional_de_aguinaldo;
		}

		private function calculate_prima_de_antiguedad($salario_diario,$_trabajador)
		{
			$anos_de_antiguedad = $this->get_value('Años de antigüedad',$this->cuotas_IMSS,$_trabajador);
			$dias_de_prima_de_antiguedad = $anos_de_antiguedad * 12;

			if(is_array($salario_diario))
				$salario = $salario_diario[0]['Cantidad'];//the last salario diario will be used
			else
				$salario = $salario_diario;

			return $salario * $dias_de_prima_de_antiguedad;
		}

		private function calculate_retencion_proporcional_de_prima_de_antiguedad($salario_diario,$_trabajador,$numero_de_dias)
		{
			$anos_de_antiguedad = $this->get_value('Años de antigüedad',$this->cuotas_IMSS,$_trabajador);
			$dias_de_prima_de_antiguedad = 12;

			if(is_array($salario_diario))
				$salario = $salario_diario[0]['Cantidad'];//the last salario diario will be used
			else
				$salario = $salario_diario;

			$prima_de_antiguedad = $salario * $dias_de_prima_de_antiguedad;
			$retencion_proporcional_de_prima_de_antiguedad = round($prima_de_antiguedad * $numero_de_dias / 365,2);
			return $retencion_proporcional_de_prima_de_antiguedad;
		}

		private function calculate_honorarios_asimilados($_trabajador,$numero_de_dias_del_periodo,$numero_de_dias_laborados)
		{
			$pago_neto = $this->get_value('Pago neto',$this->incidencias,$_trabajador);
			$jornada = 8;
			$honorarios_asimilados = 0;
			$dif_honorarios_asimilados = $this->dif_honorarios_asimilados($pago_neto,$jornada,$numero_de_dias_del_periodo,$numero_de_dias_laborados,$honorarios_asimilados,$_trabajador);

			if($dif_honorarios_asimilados > 0)
			{
				$a = $honorarios_asimilados;
				$i = 0;

				while($i < 450)
				{
					$honorarios_asimilados += 100;
					$dif_honorarios_asimilados = $this->dif_honorarios_asimilados($pago_neto,$jornada,$numero_de_dias_del_periodo,$numero_de_dias_laborados,$honorarios_asimilados,$_trabajador);

					if($dif_honorarios_asimilados < 0)//Bisection method
					{
						$b = $honorarios_asimilados;
						$j = 0;

						while($j < 450)
						{
							$m = ($a + $b) / 2;

							if($this->dif_honorarios_asimilados($pago_neto,$jornada,$numero_de_dias_del_periodo,$numero_de_dias_laborados,$m,$_trabajador) == 0 || ($b - $a) / 2 < 0.001)
							{
								$honorarios_asimilados = round($m,2);
								break;
							}
							else
							{

								if($this->dif_honorarios_asimilados($pago_neto,$jornada,$numero_de_dias_del_periodo,$numero_de_dias_laborados,$a,$_trabajador) * $this->dif_honorarios_asimilados($pago_neto,$jornada,$numero_de_dias_del_periodo,$numero_de_dias_laborados,$m,$_trabajador) < 0)
									$b = round($m,4);
								else
									$a = round($m,4);

							}

							$j++;
						}

						break;
					}

					$i++;
				}

			}

			return $honorarios_asimilados;
		}

		private function dif_honorarios_asimilados($pago_neto,$jornada,$numero_de_dias_del_periodo,$numero_de_dias_laborados,$honorarios_asimilados,$_trabajador)
		{
			$salario = round($honorarios_asimilados / $numero_de_dias_laborados,2);
			$base_ISR = $honorarios_asimilados;
			//Límite inferior
			$limite_inferior = $this->calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			//Exedente del límite inferior
			$exedente_del_limite_inferior = round($base_ISR - $limite_inferior, 2);
			//Porcentaje sobre el exedente del límite inferior
			$porcentaje_sobre_el_exedente_del_limite_inferior = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			//Impuesto marginal
			$impuesto_marginal = round($exedente_del_limite_inferior * $porcentaje_sobre_el_exedente_del_limite_inferior,2);
			//Cuota fija
			$cuota_fija = $this->calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo,$numero_de_dias_laborados);
			//Impuesto determinado
			$impuesto_determinado = round($impuesto_marginal + $cuota_fija, 2);
			//Subsidio
			$subsidio = 0;//$this->calculate_subsidio($base_ISR,$numero_de_dias_del_periodo);
			//ISR and Subsidio al empleo
			if($impuesto_determinado - $subsidio > 0)
			{
				$ISR = round($impuesto_determinado - $subsidio, 2);
				$subsidio_al_empleo = 0;
			}
			else
			{
				$subsidio_al_empleo = round($subsidio - $impuesto_determinado, 2);
				$ISR = 0;
			}

			$dif = $pago_neto + $ISR - $subsidio_al_empleo - $honorarios_asimilados;
			return $dif;
		}

		public function draw($act)//if act == 'EDIT' or act == 'ADD' some fields can be edited and the form is submitted to store_nomina.php. If act == 'DRAW' no fields can be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";//show at tabs.js
			echo "<div onclick = \"show('ISRasalariados_fieldset',this)\" class = \"isr_asalariados_tab\" style=\"color:#777\">ISR asalariados</div>";
			echo "<div onclick = \"show('ISRasimilables_fieldset',this)\" class = \"isr_asimilables_tab\" style=\"color:#777\">ISR asimilables</div>";
			echo "<div onclick = \"show('IMSS',this)\" class = \"imss_tab\" style=\"color:#777\">IMSS</div>";
			echo "<div onclick = \"show('Prestaciones',this)\" class = \"prestaciones_tab\" style=\"color:#777\">Prestaciones</div>";
			echo "<div onclick = \"show('Nomina_asalariados',this)\" class = \"nomina_asalariados_tab\" style=\"color:#777\">Nomina asalariados</div>";
			echo "<div onclick = \"show('Nomina_asimilables',this)\" class = \"nomina_asimilables_tab\" style=\"color:#777\">Nomina asimilables</div>";
			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>{$this->id}</textarea>";

					//getting administradora
					if(isset($this->Servicio))
					{
						$result = $this->conn->query("SELECT Empresa.Nombre FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero LEFT JOIN Empresa ON (Registro_patronal.Empresa = Empresa.RFC OR Registro_patronal.Empresa_sucursal = Empresa.RFC) WHERE Servicio_Registro_patronal.Servicio = '{$this->Servicio}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
						list($administradora) = $this->conn->fetchRow($result);
					}
					else
						$administradora = '';

					echo "<textarea name = \"Administradora\" class=\"hidden_textarea\" readonly=true>$administradora</textarea>";

					//getting empresa
					if(isset($this->Servicio))
					{
						$empresa_rfc = $this->get_empresa();
						$result = $this->conn->query("SELECT Nombre FROM Empresa WHERE RFC = '$empresa_rfc' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($empresa) = $this->conn->fetchRow($result);
					}
					else
						$empresa = '';

					//setting expedidor
					if(isset($this->Servicio))
					{
						$result = $this->conn->query("SELECT Registro_patronal.Empresa, Registro_patronal.Empresa_sucursal FROM Registro_patronal LEFT JOIN Servicio_Registro_patronal ON Registro_patronal.Numero = Servicio_Registro_patronal.Registro_patronal WHERE Servicio_Registro_patronal.Servicio = '{$this->Servicio}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
						list($e, $e_sucursal) = $this->conn->fetchRow($result);

						if(!isset($e) || $e == '')
							$e1 = $e_sucursal;
						else
							$e1 = $e;

						$e2 = $this->get_empresa();

						if($e1 == $e2)
							$expedidor = 'Cliente';
						else
							$expedidor = 'Empresa administradora';

					}
					else
						$expedidor = '';

					//getting honorarios
					if(isset($this->Servicio))
					{
						$result = $this->conn->query("SELECT Porcentaje_de_honorarios FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($honorarios) = $this->conn->fetchRow($result);
					}
					else
						$honorarios = '';

					//getting honorarios pendientes
					if(isset($this->Servicio))
					{
						$result = $this->conn->query("SELECT Honorarios_pendientes FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($honorarios_pendientes) = $this->conn->fetchRow($result);
					}
					else
						$honorarios_pendientes = '';

					//getting cobrar IVA
					if(isset($this->Servicio))
					{
						$result = $this->conn->query("SELECT Cobrar_IVA FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($cobrar_iva) = $this->conn->fetchRow($result);
					}
					else
						$cobrar_iva = '';

					//getting iva solo por honorarios
					if(isset($this->Servicio))
					{
						$result = $this->conn->query("SELECT ivash FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($ivash) = $this->conn->fetchRow($result);
					}
					else
						$ivash = '';

					echo "<textarea name = \"Empresa\" class=\"hidden_textarea\" readonly=true>$empresa</textarea>";
					echo "<textarea name = \"Expedidor\" class=\"hidden_textarea\" readonly=true>$expedidor</textarea>";
					echo "<textarea name = \"Honorarios\" class=\"hidden_textarea\" readonly=true>$honorarios</textarea>";
					echo "<textarea name = \"Honorarios_pendientes\" class=\"hidden_textarea\" readonly=true>$honorarios_pendientes</textarea>";
					echo "<textarea name = \"Cobrar_IVA\" class=\"hidden_textarea\" readonly=true>$cobrar_iva</textarea>";
					echo "<textarea name = \"ivash\" class=\"hidden_textarea\" readonly=true>$ivash</textarea>";
					echo "<textarea name = \"incidencias\" class=\"hidden_textarea\" readonly=true>{$this->incidencias}</textarea>";
					echo "<textarea name = \"Resumen\" class=\"hidden_textarea\" readonly=true>{$this->Resumen}</textarea>";
					echo "<label class = \"limite_inferior_del_periodo_label\">Límite inferior del periodo</label>";
					echo "<textarea class = \"limite_inferior_del_periodo_textarea\" name = \"Limite_inferior_del_periodo\" title = \"Límite inferior del periodo\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Limite_inferior_del_periodo}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";//show_calendar() at calendar.js

					echo "<label class = \"limite_superior_del_periodo_label\">Límite superior del periodo</label>";
					echo "<textarea class = \"limite_superior_del_periodo_textarea\" name = \"Limite_superior_del_periodo\" title = \"Límite superior del periodo\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Limite_superior_del_periodo}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";//show_calendar() at calendar.js

					echo "<label class = \"etapa_label\">Etapa</label>";
					echo "<textarea class = \"etapa_textarea\" name = \"Etapa\" title = \"Etapa\" readonly=true>" . (isset($this->Etapa) ? $this->Etapa : 'Pendiente') . "</textarea>";

					echo "<label class = \"servicio_label\">Servicio</label>";

					if($act != 'DRAW')
					{
						$result = $this->conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Empresa.Nombre, Servicio_Registro_patronal.Registro_patronal FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio_Empresa.Servicio = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' GROUP BY Servicio.id");
						echo'<select title = "Servicio" class = "servicio_select" name = "Servicio" required=true>';

						while(list($id,$periodicidad,$empresa,$registro) = $this->conn->fetchRow($result))
						{

							if(isset($this->Servicio) && $this->Servicio == $id)
								echo "<option selected>$id/$periodicidad/$empresa/$registro</option>";
							else
								echo "<option>$id/$periodicidad/$empresa/$registro</option>";

						}

						$this->conn->freeResult($result);
						echo '</select>';
					}
					else
					{
						$result = $this->conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Empresa.Nombre, Servicio_Registro_patronal.Registro_patronal FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio_Empresa.Servicio = Servicio_Registro_patronal.Servicio WHERE Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Servicio = '{$this->Servicio}'");
						list($id,$periodicidad,$empresa,$registro) = $this->conn->fetchRow($result);
						echo "<textarea class = \"servicio_textarea\" name = \"Servicio\" title = \"Servicio\">$id/$periodicidad/$empresa/$registro</textarea>";
					}

					echo "<label class = \"fecha_de_pago_label\">Fecha de pago</label>";
					echo "<textarea class = \"fecha_de_pago_textarea\" name = \"Fecha_de_pago\" title = \"Fecha de elaboración\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Fecha_de_pago}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";//show_calendar() at calendar.js

					if($act != 'DRAW')
					{
						echo "<label class = \"calcular_label\">Calcular</label>";
						echo "<img class = 'calculate_button' onclick = \"get_workers('$act',this)\" />";//function calculate at nomina.js & functions calculate_button_opaque and bright at presentation.js
					}

					if($act == 'DRAW')
					{
						echo "<label class = \"resumen_label\">Resumen</label>";
						echo "<img class = 'resumen_button' onclick = \"view(this)\" />";//function view at nomina.js
						echo "<label class = \"timbrar_label\">Timbrar</label>";
						echo "<img class = 'timbrar_button' onclick = \"timbrar(this)\" />";//function timbrar at nomina.js
						echo "<label class = \"view_cfdi_label\">Representación impresa de CFDIs</label>";
						echo "<img class = 'view_cfdi_button' onclick = \"view_cfdi_prin_nomina(this)\" />";//function view_cfdi_prin_nomina at nomina.js
						echo "<label class = \"cancel_label\">Cancelar</label>";
						echo "<img class = 'cancel_button' onclick = \"cancel_nomina(this)\" />";//function cancel_nomina at nomina.js
						echo "<label class = \"descargar_cfdi_label\">Descargar CFDIs</label>";
						echo "<img class = 'download_button' onclick=\"download_cfdi_nomina(this)\" />";//function download_cfdi_nomina() at nomina.js
					}

				echo "</fieldset>";
				echo "<fieldset class =  \"ISRasalariados_fieldset\" style = \"visibility:hidden\"\>";

					if(isset($this->ISRasalariados))
						echo $this->ISRasalariados;

					if($act == 'DRAW')
						echo "<img class = 'view_button' onclick = \"view(this)\"/>";//function view at nomina.js

				echo "</fieldset>";
				echo "<fieldset class =  \"ISRasimilables_fieldset\" style = \"visibility:hidden\"\>";

					if(isset($this->ISRasimilables))
						echo $this->ISRasimilables;

					if($act == 'DRAW')
						echo "<img class = 'view_button' onclick = \"view(this)\"/>";

				echo "</fieldset>";
				echo "<fieldset class =  \"IMSS\" style = \"visibility:hidden\"\>";

					if(isset($this->cuotas_IMSS))
						echo $this->cuotas_IMSS;

					if($act == 'DRAW')
						echo "<img class = 'view_button' onclick = \"view(this)\"/>";

				echo "</fieldset>";
				echo "<fieldset class =  \"Prestaciones\" style = \"visibility:hidden\"\>";

					if(isset($this->prestaciones))
						echo $this->prestaciones;

					if($act == 'DRAW')
						echo "<img class = 'view_button' onclick = \"view(this)\"/>";

				echo "</fieldset>";
				echo "<fieldset class =  \"Nomina_asalariados\" style = \"visibility:hidden\"\>";

					if(isset($this->nomina_asalariados))
						echo $this->nomina_asalariados;

					if($act == 'DRAW')
					{
						echo "<img title = 'Reporte de nómina' class = 'view_button' onclick = \"view(this)\"/>";
						echo "<img title = 'Reporte para depósitos' class = 'deposit_button' onclick = \"deposit_menu(this)\"/>";
						echo "<img title = 'Recibos de nómina' class = 'recibos_nomina_button' onclick = \"recibos(this)\"/>";//function recibos at nomina.js
						echo "<img title = 'Recibos de pago de préstamos del cliente' class = 'recibos_prestamo_button' onclick = \"recibos_prestamo_cliente(this)\"/>";//function recibos_prestamo_cliente at nomina.js
						echo "<img title = 'Recibos de pensión alimenticia' class = 'recibos_pensiones_button' onclick = \"pensiones(this)\"/>";//function pensiones at nomina.js
					}

				echo "</fieldset>";
				echo "<fieldset class =  \"Nomina_asimilables\" style = \"visibility:hidden\"\>";

					if(isset($this->nomina_asimilables))
						echo $this->nomina_asimilables;

					if($act == 'DRAW')
					{
						echo "<img title = 'Reporte de nómina' class = 'view_button' onclick = \"view(this)\"/>";
						echo "<img title = 'Reporte para depósitos' class = 'deposit_button' onclick = \"deposit_menu(this)\"/>";
						echo "<img class = 'recibos_button' onclick = \"recibos(this)\"/>";
						echo "<img title = 'Recibos de pago de préstamos del cliente' class = 'recibos_prestamo_button' onclick = \"recibos_prestamo_cliente(this)\"/>";//function recibos_prestamo_cliente at nomina.js
					}

				echo "</fieldset>";

				if($act != 'DRAW' && $this->Etapa != 'Timbrada')
					echo "<img title = \"Guardar\" class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Nomina')\" />";//_submit() at common_entities.js
			
			echo "</form>";
		}

		public function timbrar_nomina()
		{
			$this->setFromDB();
			$msg = $this->timbrar_nomina_asalariados();

			if(isset($msg))
				return $msg;

			$msg = $this->timbrar_nomina_asimilables();

			if(isset($msg))
				return $msg;

		}

		private function timbrar_nomina_asalariados()
		{
			$rows = explode('<tr>',$this->nomina_asalariados);
			$rows_len = count($rows);
			$result = $this->conn->query("SELECT Registro_patronal.Numero, Registro_patronal.Empresa, Registro_patronal.Sucursal, Registro_patronal.Empresa_sucursal, Registro_patronal.Clase_de_riesgo FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero WHERE Servicio_Registro_patronal.Servicio = '{$this->Servicio}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
			list($registro_patronal_admin,$rfc_empresa,$sucursal_admin,$rfc_empresa_sucursal,$clase_de_riesgo) = $this->conn->fetchRow($result);

			if(isset($rfc_empresa) && $rfc_empresa != '')
				$rfc_admin = $rfc_empresa;
			else
				$rfc_admin = $rfc_empresa_sucursal;

			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT dcipn FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($dcipn) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$FechaPago = $this->Fecha_de_pago;
			$matches = array();
			$flag = preg_match('/[0-9,A-Z]{1,20}/' , $registro_patronal_admin, $matches);

			if(!isset($registro_patronal_admin) || !$flag || count($matches) != 1)
				return "El registro patronal $registro_patronal_admin debe tener de 1 a 20 caracteres alfanuméricos";

			//testing data
			$timbrar = false;

			for($i=3; $i<$rows_len; $i++)
			{
				$cna = $this->complemento_nomina_asalariados($rows[$i], $registro_patronal_admin, $rfc_admin, $sucursal_admin, $clase_de_riesgo, $dcipn, $FechaPago);

				if(isset($cna) && is_array($cna))//if cna is NULL means that Saldo is <= 0.00
				{
					$cfdi = new CFDI_Trabajador();
					$msg = $cfdi->CFDINomina($cna, $timbrar, 'Nomina');

					if(isset($msg) && is_string($msg))
						break;

				}
				elseif(isset($cna) && is_string($cna))
				{
					$msg = $cna;
					break;
				}

			}

			if(isset($msg) && is_string($msg))
				return $msg;//error message

			//timbrando
			$timbrar = true;

			for($i=3; $i<$rows_len; $i++)
			{
				$cna = $this->complemento_nomina_asalariados($rows[$i], $registro_patronal_admin, $rfc_admin, $sucursal_admin, $clase_de_riesgo, $dcipn, $FechaPago);

				if(isset($cna) && is_array($cna))//if cna is NULL means that Saldo is 0.00
				{
					$cfdi = new CFDI_Trabajador();
					$msg = $cfdi->CFDINomina($cna, $timbrar, 'Nomina');

					if(isset($msg))//$msg NULL cuando ya ha sido timbrado anteriormente
					{
						$data = explode(',', $msg);
						$status = $data[0];
						$trabajador = $data[1];
						$this->conn->query("UPDATE nomina_asalariados SET Status = '$status' WHERE Nomina = '{$this->id}' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
					}

				}

			}

		}

		private function timbrar_nomina_asimilables()
		{
			$rows = explode('<tr>',$this->nomina_asimilables);
			$rows_len = count($rows);
			$result = $this->conn->query("SELECT Registro_patronal.Numero, Registro_patronal.Empresa, Registro_patronal.Sucursal, Registro_patronal.Empresa_sucursal FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero WHERE Servicio_Registro_patronal.Servicio = '{$this->Servicio}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
			list($registro_patronal_admin,$rfc_empresa,$sucursal_admin,$rfc_empresa_sucursal) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if(isset($rfc_empresa) && $rfc_empresa != '')
				$rfc_admin = $rfc_empresa;
			else
				$rfc_admin = $rfc_empresa_sucursal;

			$FechaPago = $this->Fecha_de_pago;
			$matches = array();
			$flag = preg_match('/[0-9,A-Z]{1,20}/' , $registro_patronal_admin, $matches);

			if(!isset($registro_patronal_admin) || !$flag || count($matches) != 1)
				return "El registro patronal $registro_patronal_admin debe tener de 1 a 20 caracteres alfanuméricos";

			//testing data
			$timbrar = false;

			for($i=3; $i<$rows_len; $i++)
			{
				$cna = $this->complemento_nomina_asimilables($rows[$i], $registro_patronal_admin, $rfc_admin, $sucursal_admin, $FechaPago);

				if(isset($cna) && is_array($cna))//if cna is NULL means that Saldo is <= 0.00
				{
					$cfdi = new CFDI_Trabajador();
					$msg = $cfdi->CFDINomina($cna, $timbrar, 'Nomina');

					if(isset($msg) && is_string($msg))
						break;

				}
				elseif(isset($cna) && is_string($cna))
				{
					$msg = $cna;
					break;
				}

			}

			if(isset($msg) && is_string($msg))
				return $msg;//error message

			//timbrando
			$timbrar = true;

			for($i=3; $i<$rows_len; $i++)
			{
				$cna = $this->complemento_nomina_asimilables($rows[$i], $registro_patronal_admin, $rfc_admin, $sucursal_admin, $FechaPago);

				if(isset($cna) && is_array($cna))//if cna is NULL means that Saldo is 0.00
				{
					$cfdi = new CFDI_Trabajador();
					$msg = $cfdi->CFDINomina($cna, $timbrar, 'Nomina');

					if(isset($msg))//$msg NULL cuando ya ha sido timbrado anteriormente
					{
						$data = explode(',', $msg);
						$status = $data[0];
						$trabajador = $data[1];
						$this->conn->query("UPDATE nomina_asimilables SET Status = '$status' WHERE Nomina = '{$this->id}' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
					}

				}

			}

			$this->conn->query("UPDATE Nomina SET Etapa = 'Timbrada' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
		}

		private function complemento_nomina_asalariados($row, $registro_patronal_admin, $rfc_admin, $sucursal_admin, $clase_de_riesgo, $dcipn, $FechaPago)
		{
			date_default_timezone_set('America/Mexico_City');
			//generando complemento Nomina asalariados
			$cols = explode('<td>',$row);
			$cols_len = count($cols);
			$NominaNS = 'http://www.sat.gob.mx/nomina';
			$SchemaInstanceNS = 'http://www.w3.org/2001/XMLSchema-instance';
			$SATSchemaLocation = 'http://www.sat.gob.mx/nomina http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina11.xsd';
			$XMLDoc = new DOMDocument('1.0', 'UTF-8');
			$ComplementoNomina = $XMLDoc->createElementNS($NominaNS, 'nomina:Nomina');
			$ComplementoNomina->setAttributeNS($SchemaInstanceNS, 'xsi:schemaLocation', $SATSchemaLocation);
			$values = explode('<',str_replace('</td>','',$cols[35]));//35 => Saldo
			$saldo = $values[0];
			$values = explode('<',str_replace('</td>','',$cols[45]));//45 => Metodo de pago
			$metodoDePago = $values[0];

			if($saldo <= 0.00)
				return Null;

			//Datos
			for($j=1; $j<=7; $j++)
			{
				$col = str_replace('</td>','',$cols[$j]);
				$values = explode('<',$col);

				if($j == 1)
					$NumEmpleado = $values[0];
				elseif($j == 3)
					$NumSeguridadSocial = trim(preg_replace('/\s+/',' ', $values[0]));
				elseif($j == 4)
					$CURP = trim(preg_replace('/\s+/',' ', $values[0]));
				elseif($j == 5)
					$RFC = trim(preg_replace('/\s+/',' ', $values[0]));
				elseif($j == 6)
					$NumDiasPagados = $values[0];
				elseif($j == 7 && is_numeric($values[0]))
					$SalarioBaseCotApor = $values[0];

			}

			$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '$RFC' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($NombreTrabajador) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$NombreTrabajador = trim(preg_replace('/\s+/',' ', $NombreTrabajador));
			$matches = array();
			$flag = preg_match('/[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?/' , $RFC, $matches);

			if(!$flag || count($matches) != 1 || strlen($RFC) > 13 || strlen($RFC) < 12)
				return "Por favor corrija el RFC de $NombreTrabajador";

			//Percepciones
			$Percepciones = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepciones');
			$TotalGravado_percepciones = 0.00;
			$TotalExento_percepciones = 0.00;

			for($j=8; $j<=21; $j++)
			{
				$col = str_replace('</td>','',$cols[$j]);
				$values = explode('</tbody>',$col);

				if($j == 8 && $NumDiasPagados > 0)//Sueldo
				{
					$Sueldo = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$Sueldo->setAttribute('TipoPercepcion', '001');
					$Sueldo->setAttribute('Clave', '008');
					$Sueldo->setAttribute('Concepto', 'Sueldo');
					$Sueldo->setAttribute('ImporteGravado', $values[0]);
					$Sueldo->setAttribute('ImporteExento', 0.00);
					$Percepciones->appendChild($Sueldo);
					$TotalGravado_percepciones += $values[0];
				}
				elseif($j == 9 && $values[0] > 0.00)//Subsidio
				{
					$Subsidio = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$Subsidio->setAttribute('TipoPercepcion', '017');
					$Subsidio->setAttribute('Clave', '009');
					$Subsidio->setAttribute('Concepto', 'Subsidio');
					$Subsidio->setAttribute('ImporteGravado', 0.00);
					$Subsidio->setAttribute('ImporteExento', $values[0]);
					$Percepciones->appendChild($Subsidio);
					$TotalExento_percepciones += $values[0];
				}
				elseif($j == 10 && $values[0] > 0.00)//Horas extra
				{
					$HorasExtraGravadas = $this->get_value('Horas extra gravadas',$this->ISRasalariados,$RFC);
					$HorasExtraExentas = round($values[0] - $HorasExtraGravadas, 2);
					$HorasExtra = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$HorasExtra->setAttribute('TipoPercepcion', '019');
					$HorasExtra->setAttribute('Clave', '010');
					$HorasExtra->setAttribute('Concepto', 'Horas extra');
					$HorasExtra->setAttribute('ImporteGravado', $HorasExtraGravadas);
					$HorasExtra->setAttribute('ImporteExento', $HorasExtraExentas);
					$Percepciones->appendChild($HorasExtra);
					$TotalGravado_percepciones += $HorasExtraGravadas;
					$TotalExento_percepciones += $HorasExtraExentas;
				}
				elseif($j == 11 && $values[0] > 0.00)//Prima dominical
				{
					$PrimaDominicalGravada = $this->get_value('Prima dominical gravada',$this->ISRasalariados,$RFC);
					$PrimaDominicalExenta = round($values[0] - $PrimaDominicalGravada, 2);
					$PrimaDominical = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$PrimaDominical->setAttribute('TipoPercepcion', '020');
					$PrimaDominical->setAttribute('Clave', '011');
					$PrimaDominical->setAttribute('Concepto', 'Prima dominical');
					$PrimaDominical->setAttribute('ImporteGravado', $PrimaDominicalGravada);
					$PrimaDominical->setAttribute('ImporteExento', $PrimaDominicalExenta);
					$Percepciones->appendChild($PrimaDominical);
					$TotalGravado_percepciones += $PrimaDominicalGravada;
					$TotalExento_percepciones += $PrimaDominicalExenta;
				}
				elseif($j == 12 && $values[0] > 0.00)//Dias de descanso
				{
					$DiasDeDescansoGravados = $this->get_value('Días de descanso gravados',$this->ISRasalariados,$RFC);
					$DiasDeDescansoExentos = round($values[0] - $DiasDeDescansoGravados, 2);
					$DiasDeDescanso = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$DiasDeDescanso->setAttribute('TipoPercepcion', '016');
					$DiasDeDescanso->setAttribute('Clave', '012');
					$DiasDeDescanso->setAttribute('Concepto', 'Dias de descanso');
					$DiasDeDescanso->setAttribute('ImporteGravado', $DiasDeDescansoGravados);
					$DiasDeDescanso->setAttribute('ImporteExento', $DiasDeDescansoExentos);
					$Percepciones->appendChild($DiasDeDescanso);
					$TotalGravado_percepciones += $DiasDeDescansoGravados;
					$TotalExento_percepciones += $DiasDeDescansoExentos;
				}
				elseif($j == 13 && $values[0] > 0.00)//Premios de puntualidad y asistencia
				{
					$PremiosDePuntualidadYAsistencia = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$PremiosDePuntualidadYAsistencia->setAttribute('TipoPercepcion', '010');
					$PremiosDePuntualidadYAsistencia->setAttribute('Clave', '013');
					$PremiosDePuntualidadYAsistencia->setAttribute('Concepto', 'Premios de puntualidad y asistencia');
					$PremiosDePuntualidadYAsistencia->setAttribute('ImporteGravado', $values[0]);
					$PremiosDePuntualidadYAsistencia->setAttribute('ImporteExento', 0.00);
					$Percepciones->appendChild($PremiosDePuntualidadYAsistencia);
					$TotalGravado_percepciones += $values[0];
				}
				elseif($j == 14 && $values[0] > 0.00)//Bonos de productividad
				{
					$BonosDeProductividad = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$BonosDeProductividad->setAttribute('TipoPercepcion', '016');
					$BonosDeProductividad->setAttribute('Clave', '014');
					$BonosDeProductividad->setAttribute('Concepto', 'Bonos de productividad');
					$BonosDeProductividad->setAttribute('ImporteGravado', $values[0]);
					$BonosDeProductividad->setAttribute('ImporteExento', 0.00);
					$Percepciones->appendChild($BonosDeProductividad);
					$TotalGravado_percepciones += $values[0];
				}
				elseif($j == 15 && $values[0] > 0.00)//Estímulos
				{
					$Estimulos = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$Estimulos->setAttribute('TipoPercepcion', '016');
					$Estimulos->setAttribute('Clave', '015');
					$Estimulos->setAttribute('Concepto', 'Estimulos');
					$Estimulos->setAttribute('ImporteGravado', $values[0]);
					$Estimulos->setAttribute('ImporteExento', 0.00);
					$Percepciones->appendChild($Estimulos);
					$TotalGravado_percepciones += $values[0];
				}
				elseif($j == 16 && $values[0] > 0.00)//Compensaciones
				{
					$Compensaciones = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$Compensaciones->setAttribute('TipoPercepcion', '016');
					$Compensaciones->setAttribute('Clave', '016');
					$Compensaciones->setAttribute('Concepto', 'Compensaciones');
					$Compensaciones->setAttribute('ImporteGravado', $values[0]);
					$Compensaciones->setAttribute('ImporteExento', 0.00);
					$Percepciones->appendChild($Compensaciones);
					$TotalGravado_percepciones += $values[0];
				}
				elseif($j == 17 && $values[0] > 0.00)//Despensa
				{
					$Despensa = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$Despensa->setAttribute('TipoPercepcion', '016');
					$Despensa->setAttribute('Clave', '017');
					$Despensa->setAttribute('Concepto', 'Despensa');
					$Despensa->setAttribute('ImporteGravado', $values[0]);
					$Despensa->setAttribute('ImporteExento', 0.00);
					$Percepciones->appendChild($Despensa);
					$TotalGravado_percepciones += $values[0];
				}
				elseif($j == 18 && $values[0] > 0.00)//Comida
				{
					$Comida = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$Comida->setAttribute('TipoPercepcion', '016');
					$Comida->setAttribute('Clave', '018');
					$Comida->setAttribute('Concepto', 'Comida');
					$Comida->setAttribute('ImporteGravado', $values[0]);
					$Comida->setAttribute('ImporteExento', 0.00);
					$Percepciones->appendChild($Comida);
					$TotalGravado_percepciones += $values[0];
				}
				elseif($j == 19 && $values[0] > 0.00)//Alimentacion
				{
					$_values = explode('<',str_replace('</td>','',$cols[$j+1]));
					$Habitacion = $_values[0];

					if($Habitacion > 0)
						$AlimentacionGravada = round($this->get_value('Previsión social gravada',$this->ISRasalariados,$RFC) / 2, 2);
					else
						$AlimentacionGravada = $this->get_value('Previsión social gravada',$this->ISRasalariados,$RFC);

					$AlimentacionExenta = round($values[0] - $AlimentacionGravada, 2);
					$Alimentacion = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$Alimentacion->setAttribute('TipoPercepcion', '016');
					$Alimentacion->setAttribute('Clave', '019');
					$Alimentacion->setAttribute('Concepto', 'Alimentacion');
					$Alimentacion->setAttribute('ImporteGravado', $AlimentacionGravada);
					$Alimentacion->setAttribute('ImporteExento', $AlimentacionExenta);
					$Percepciones->appendChild($Alimentacion);
					$TotalGravado_percepciones += $AlimentacionGravada;
					$TotalExento_percepciones += $AlimentacionExenta;
				}
				elseif($j == 20 && $values[0] > 0.00)//Habitacion
				{
					$_values = explode('<',str_replace('</td>','',$cols[$j-1]));
					$Alimentacion = $_values[0];

					if($Alimentacion > 0)
						$HabitacionGravada = round($this->get_value('Previsión social gravada',$this->ISRasalariados,$RFC) / 2, 2);
					else
						$HabitacionGravada = $this->get_value('Previsión social gravada',$this->ISRasalariados,$RFC);

					$HabitacionExenta = round($values[0] - $HabitacionGravada, 2);
					$Habitacion = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$Habitacion->setAttribute('TipoPercepcion', '016');
					$Habitacion->setAttribute('Clave', '020');
					$Habitacion->setAttribute('Concepto', 'Habitacion');
					$Habitacion->setAttribute('ImporteGravado', $HabitacionGravada);
					$Habitacion->setAttribute('ImporteExento', $HabitacionExenta);
					$Percepciones->appendChild($Habitacion);
					$TotalGravado_percepciones += $HabitacionGravada;
					$TotalExento_percepciones += $HabitacionExenta;
				}
				elseif($j == 21 && $this->total($values[0]) > 0.00)//Aportacion patronal al fondo de ahorro
				{
					$AportacionPatronalAlFondoDeAhorro = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$AportacionPatronalAlFondoDeAhorro->setAttribute('TipoPercepcion', '016');
					$AportacionPatronalAlFondoDeAhorro->setAttribute('Clave', '021');
					$AportacionPatronalAlFondoDeAhorro->setAttribute('Concepto', 'Aportacion patronal al fondo de ahorro');
					$AportacionPatronalAlFondoDeAhorro->setAttribute('ImporteGravado', 0.00);
					$AportacionPatronalAlFondoDeAhorro->setAttribute('ImporteExento', $this->total($values[0]));
					$Percepciones->appendChild($AportacionPatronalAlFondoDeAhorro);
					$TotalGravado_percepciones += 0.00;
					$TotalExento_percepciones += $this->total($values[0]);
				}

			}

			if($Percepciones->hasChildNodes())
			{
				$ComplementoNomina->appendChild($Percepciones);
				$Percepciones->setAttribute('TotalGravado', $TotalGravado_percepciones);
				$Percepciones->setAttribute('TotalExento', $TotalExento_percepciones);
			}

			//Deducciones
			$Deducciones = $XMLDoc->createElementNS($NominaNS, 'nomina:Deducciones');
			$TotalGravado_deducciones = 0.00;
			$TotalExento_deducciones = 0.00;

			for($j=23; $j<=33; $j++)
			{
				$col = str_replace('</td>','',$cols[$j]);
				$values = explode('</tbody>',$col);

				if($j == 23)//ISR
				{
					$ISR = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
					$ISR->setAttribute('TipoDeduccion', '002');
					$ISR->setAttribute('Clave', '023');
					$ISR->setAttribute('Concepto', 'ISR');
					$ISR->setAttribute('ImporteGravado', 0.00);
					$ISR->setAttribute('ImporteExento', $values[0]);
					$TotalGravado_deducciones += 0.00;
					$TotalExento_deducciones += $values[0];
					$Deducciones->appendChild($ISR);
				}
				elseif($j == 24 && $this->total($values[0]) > 0.00)//Cuotas IMSS
				{
					$CuotasIMSS = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
					$tipo = $dcipn == 'true' ? '021' : '001';
					$CuotasIMSS->setAttribute('TipoDeduccion', $tipo);
					$CuotasIMSS->setAttribute('Clave', '024');
					$CuotasIMSS->setAttribute('Concepto', 'Cuotas IMSS');
					$CuotasIMSS->setAttribute('ImporteGravado', 0.00);
					$CuotasIMSS->setAttribute('ImporteExento', $this->total($values[0]));
					$TotalGravado_deducciones += 0.00;
					$TotalExento_deducciones += $this->total($values[0]);
					$Deducciones->appendChild($CuotasIMSS);
				}
				elseif($j == 25 && $values[0] > 0.00)//Retencion por alimentacion
				{
					$RetencionPorAlimentacion = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
					$RetencionPorAlimentacion->setAttribute('TipoDeduccion', '004');
					$RetencionPorAlimentacion->setAttribute('Clave', '025');
					$RetencionPorAlimentacion->setAttribute('Concepto', 'Retencion por alimentacion');
					$RetencionPorAlimentacion->setAttribute('ImporteGravado', 0.00);
					$RetencionPorAlimentacion->setAttribute('ImporteExento', $values[0]);
					$TotalGravado_deducciones += 0.00;
					$TotalExento_deducciones += $values[0];
					$Deducciones->appendChild($RetencionPorAlimentacion);
				}
				elseif($j == 26 && $values[0] > 0.00)//Retencion por habitacion
				{
					$RetencionPorHabitacion = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
					$RetencionPorHabitacion->setAttribute('TipoDeduccion', '004');
					$RetencionPorHabitacion->setAttribute('Clave', '026');
					$RetencionPorHabitacion->setAttribute('Concepto', 'Retencion por habitacion');
					$RetencionPorHabitacion->setAttribute('ImporteGravado', 0.00);
					$RetencionPorHabitacion->setAttribute('ImporteExento', $values[0]);
					$TotalGravado_deducciones += 0.00;
					$TotalExento_deducciones += $values[0];
					$Deducciones->appendChild($RetencionPorHabitacion);
				}
				elseif($j == 27 && $this->total($values[0]) > 0.00)//Retencion INFONAVIT
				{
					$RetencionINFONAVIT = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
					$RetencionINFONAVIT->setAttribute('TipoDeduccion', '010');
					$RetencionINFONAVIT->setAttribute('Clave', '027');
					$RetencionINFONAVIT->setAttribute('Concepto', 'Retencion INFONAVIT');
					$RetencionINFONAVIT->setAttribute('ImporteGravado', 0.00);
					$RetencionINFONAVIT->setAttribute('ImporteExento', $this->total($values[0]));
					$TotalGravado_deducciones += 0.00;
					$TotalExento_deducciones += $this->total($values[0]);
					$Deducciones->appendChild($RetencionINFONAVIT);
				}
				elseif($j == 28 && $this->total($values[0]) > 0.00)//Retencion FONACOT
				{
					$RetencionFONACOT = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
					$RetencionFONACOT->setAttribute('TipoDeduccion', '011');
					$RetencionFONACOT->setAttribute('Clave', '028');
					$RetencionFONACOT->setAttribute('Concepto', 'Retencion FONACOT');
					$RetencionFONACOT->setAttribute('ImporteGravado', 0.00);
					$RetencionFONACOT->setAttribute('ImporteExento', $this->total($values[0]));
					$TotalGravado_deducciones += 0.00;
					$TotalExento_deducciones += $this->total($values[0]);
					$Deducciones->appendChild($RetencionFONACOT);
				}
				elseif($j == 29 && $this->total($values[0]) > 0.00)//Aportacion del trabajador al fondo de ahorro
				{
					$AportacionDelTrabajadorAlFondoDeAhorro = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
					$AportacionDelTrabajadorAlFondoDeAhorro->setAttribute('TipoDeduccion', '004');
					$AportacionDelTrabajadorAlFondoDeAhorro->setAttribute('Clave', '029');
					$AportacionDelTrabajadorAlFondoDeAhorro->setAttribute('Concepto', 'Aportacion del trabajador al fondo de ahorro');
					$AportacionDelTrabajadorAlFondoDeAhorro->setAttribute('ImporteGravado', 0.00);
					$AportacionDelTrabajadorAlFondoDeAhorro->setAttribute('ImporteExento', $this->total($values[0]));
					$TotalGravado_deducciones += 0.00;
					$TotalExento_deducciones += $this->total($values[0]);
					$Deducciones->appendChild($AportacionDelTrabajadorAlFondoDeAhorro);
				}
				elseif($j == 30 && $this->total($values[0]) > 0.00)//Pension alimenticia
				{
					$PensionAlimenticia = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
					$PensionAlimenticia->setAttribute('TipoDeduccion', '007');
					$PensionAlimenticia->setAttribute('Clave', '030');
					$PensionAlimenticia->setAttribute('Concepto', 'Pension alimenticia');
					$PensionAlimenticia->setAttribute('ImporteGravado', 0.00);
					$PensionAlimenticia->setAttribute('ImporteExento', $this->total($values[0]));
					$TotalGravado_deducciones += 0.00;
					$TotalExento_deducciones += $this->total($values[0]);
					$Deducciones->appendChild($PensionAlimenticia);
				}
				elseif($j == 31 && $this->total($values[0]) > 0.00)//Retardos
				{
					$Retardos = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
					$Retardos->setAttribute('TipoDeduccion', '004');
					$Retardos->setAttribute('Clave', '031');
					$Retardos->setAttribute('Concepto', 'Retardos');
					$Retardos->setAttribute('ImporteGravado', 0.00);
					$Retardos->setAttribute('ImporteExento', $this->total($values[0]));
					$TotalGravado_deducciones += 0.00;
					$TotalExento_deducciones += $this->total($values[0]);
					$Deducciones->appendChild($Retardos);
				}
				elseif($j == 32 && $this->total($values[0]) > 0.00)//Prestaciones
				{
					$Prestaciones = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
					$Prestaciones->setAttribute('TipoDeduccion', '004');
					$Prestaciones->setAttribute('Clave', '032');
					$Prestaciones->setAttribute('Concepto', 'Prestaciones');
					$Prestaciones->setAttribute('ImporteGravado', 0.00);
					$Prestaciones->setAttribute('ImporteExento', $this->total($values[0]));
					$TotalGravado_deducciones += 0.00;
					$TotalExento_deducciones += $this->total($values[0]);
					$Deducciones->appendChild($Prestaciones);
				}
				elseif($j == 33 && $this->total($values[0]) > 0.00)//Gestion administrativa
				{
					$GestionAdministrativa = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
					$GestionAdministrativa->setAttribute('TipoDeduccion', '004');
					$GestionAdministrativa->setAttribute('Clave', '033');
					$GestionAdministrativa->setAttribute('Concepto', 'Gestion administrativa');
					$GestionAdministrativa->setAttribute('ImporteGravado', 0.00);
					$GestionAdministrativa->setAttribute('ImporteExento', $this->total($values[0]));
					$TotalGravado_deducciones += 0.00;
					$TotalExento_deducciones += $this->total($values[0]);
					$Deducciones->appendChild($GestionAdministrativa);
				}

			}

			$TotalGravado_deducciones = round($TotalGravado_deducciones, 2);
			$TotalExento_deducciones = round($TotalExento_deducciones, 2);

			if($Deducciones->hasChildNodes())
			{
				$ComplementoNomina->appendChild($Deducciones);
				$Deducciones->setAttribute('TotalGravado', $TotalGravado_deducciones);
				$Deducciones->setAttribute('TotalExento', $TotalExento_deducciones);
			}
/*
			//Incapacidad
			$DiasIncapacidad = $this->get_value('Número de días de incapacidad',$this->ISRasalariados,$RFC);

			if($DiasIncapacidad > 0)
			{
				$Descuento = $DiasIncapacidad * $SalarioBaseCotApor;
				$Incapacidad = $XMLDoc->createElementNS($NominaNS, 'nomina:Incapacidad');
				$Incapacidad->setAttribute('DiasIncapacidad', $DiasIncapacidad);
				$Incapacidad->setAttribute('TipoIncapacidad', 'Pendiente');
				$Incapacidad->setAttribute('Descuento', $Descuento);
				$ComplementoNomina->appendChild($Incapacidad);
			}

			//HorasExtra
			if(isset($HorasExtraExentas))
			{
				$HorasExtra = $XMLDoc->createElementNS($NominaNS, 'nomina:HorasExtra');
				$HorasExtra->setAttribute('Dias', 1);
				$HorasExtra->setAttribute('TipoHoras', 'Dobles');
				$HorasExtra->setAttribute('HorasExtra', 3);
				$HorasExtra->setAttribute('ImportePagado', $HorasExtraExentas + $HorasExtraGravadas);
				$ComplementoNomina->appendChild($HorasExtra);
			}
*/
			//Atributos Complemento Nomina
			if($ComplementoNomina->hasChildNodes())
			{
				$result = $this->conn->query("SELECT Puesto, Tipo, Tipo_de_jornada FROM Contrato WHERE Trabajador = '$RFC' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Limite_superior_del_periodo}',Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
				list($Puesto, $TipoContrato, $TipoJornada) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Fecha_de_ingreso_servicio FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '$RFC' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_ingreso_servicio, '{$this->Limite_superior_del_periodo}') <= 0 ORDER BY Fecha_de_ingreso_servicio DESC LIMIT 1");
				list($FechaInicioRelLaboral) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '$RFC' AND Fecha_de_reingreso != '0000-00-00' AND DATEDIFF(Fecha_de_reingreso, '$FechaInicioRelLaboral') > 0 AND DATEDIFF(Fecha_de_reingreso, '{$this->Limite_superior_del_periodo}') <= 0 AND (Fecha_de_reingreso = '0000-00-00' OR DATEDIFF(Fecha_de_reingreso,Fecha_de_baja) > 1) AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_reingreso DESC LIMIT 1");
				list($Reingreso) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);

				if(isset($Reingreso))
					$FechaInicioRelLaboral = $Reingreso;

				$result = $this->conn->query("SELECT Periodicidad_de_la_nomina FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($PeriodicidadPago) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$RiesgoPuesto = array('I' => '1', 'II' => '2', 'III' => '3', 'IV' => '4', 'V' => '5');
				$lsp = date_create("{$this->Limite_superior_del_periodo}");
				$inicio = date_create("$FechaInicioRelLaboral");
				$interval = $inicio->diff($lsp);
				$semanas = floor(($interval->days + 1) / 7);

				if(is_numeric($this->get_value('Salario diario integrado',$this->cuotas_IMSS,$RFC)))
					$SalarioDiarioIntegrado = $this->get_value('Salario diario integrado',$this->cuotas_IMSS,$RFC);

				//Version
				$ComplementoNomina->setAttribute('Version', 1.1);
				//Registro patronal
				$ComplementoNomina->setAttribute('RegistroPatronal', $registro_patronal_admin);
				//Numero de empleado
				$ComplementoNomina->setAttribute('NumEmpleado', $NumEmpleado);
				//CURP
				$matches = array();
				$flag = preg_match('/[A-Z][A,E,I,O,U,X][A-Z]{2}[0-9]{2}[0-1][0-9][0-3][0-9][M,H][A-Z]{2}[B,C,D,F,G,H,J,K,L,M,N,Ñ,P,Q,R,S,T,V,W,X,Y,Z]{3}[0-9,A-Z][0-9]/' , $CURP, $matches);

				if(isset($CURP) && $flag && count($matches) == 1 && strlen($CURP) == 18)
					$ComplementoNomina->setAttribute('CURP', $CURP);
				else
					return "Por favor corrija la CURP del trabajador $NombreTrabajador";

				//Tipo de régimen
				$ComplementoNomina->setAttribute('TipoRegimen', 2);//Sueldos y Salarios
				//Numero de seguridad social
				$matches = array();
				$flag = preg_match('/\d{1,20}/' , $NumSeguridadSocial, $matches);

				if(isset($NumSeguridadSocial) && $flag && count($matches) == 1)
					$ComplementoNomina->setAttribute('NumSeguridadSocial', $NumSeguridadSocial);
				else
					return "Por favor corrija el número IMSS del trabajador $NombreTrabajador";

				//Fecha de pago
				$ComplementoNomina->setAttribute('FechaPago', $FechaPago);
				//Fecha inicial de pago
				$ComplementoNomina->setAttribute('FechaInicialPago', $this->Limite_inferior_del_periodo);
				//Fecha final de pago
				$ComplementoNomina->setAttribute('FechaFinalPago', $this->Limite_superior_del_periodo);
				//Número de días pagados
				$ComplementoNomina->setAttribute('NumDiasPagados', $NumDiasPagados);

				if(isset($FechaInicioRelLaboral) && $FechaInicioRelLaboral != '')
					$ComplementoNomina->setAttribute('FechaInicioRelLaboral', $FechaInicioRelLaboral);
				else
					return "Por favor corrija la fecha de ingreso del trabajador $NombreTrabajador";

				$ComplementoNomina->setAttribute('Antiguedad', $semanas);

				if(isset($Puesto) && $Puesto != '')
					$ComplementoNomina->setAttribute('Puesto', $Puesto);

				if(isset($TipoContrato) && $TipoContrato != '')
					$ComplementoNomina->setAttribute('TipoContrato', $TipoContrato);

				if(isset($TipoJornada) && $TipoJornada != '')
					$ComplementoNomina->setAttribute('TipoJornada', $TipoJornada);

				if(isset($PeriodicidadPago) && $PeriodicidadPago != '')
					$ComplementoNomina->setAttribute('PeriodicidadPago', $PeriodicidadPago);
				else
					return "Por favor corrija la periodicidad de la nómina";

				if(isset($SalarioBaseCotApor) && $SalarioBaseCotApor != '')
					$ComplementoNomina->setAttribute('SalarioBaseCotApor', $SalarioBaseCotApor);

				if(isset($clase_de_riesgo) && isset($RiesgoPuesto[$clase_de_riesgo]))
					$ComplementoNomina->setAttribute('RiesgoPuesto', $RiesgoPuesto[$clase_de_riesgo]);

				if(isset($SalarioDiarioIntegrado) && $SalarioDiarioIntegrado != '')
					$ComplementoNomina->setAttribute('SalarioDiarioIntegrado', $SalarioDiarioIntegrado);

				$XMLDoc->appendChild($ComplementoNomina);
			}

			return array($XMLDoc->saveXML(), $FechaPago, $metodoDePago, $rfc_admin, $sucursal_admin, $RFC, $this->id);
		}

		private function complemento_nomina_asimilables($row, $registro_patronal_admin, $rfc_admin, $sucursal_admin, $FechaPago)
		{
			date_default_timezone_set('America/Mexico_City');
			//generando complemento Nomina asimilables
			$cols = explode('<td>',$row);
			$cols_len = count($cols);
			$NominaNS = 'http://www.sat.gob.mx/nomina';
			$SchemaInstanceNS = 'http://www.w3.org/2001/XMLSchema-instance';
			$SATSchemaLocation = 'http://www.sat.gob.mx/nomina http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina11.xsd';
			$XMLDoc = new DOMDocument('1.0', 'UTF-8');
			$ComplementoNomina = $XMLDoc->createElementNS($NominaNS, 'nomina:Nomina');
			$ComplementoNomina->setAttributeNS($SchemaInstanceNS, 'xsi:schemaLocation', $SATSchemaLocation);
			$values = explode('<',str_replace('</td>','',$cols[11]));//11 => Saldo
			$saldo = $values[0];
			$values = explode('<',str_replace('</td>','',$cols[19]));//19 => Metodo de pago
			$metodoDePago = $values[0];

			if($saldo <= 0.00)
				return Null;

			//Datos
			for($j=1; $j<=5; $j++)
			{
				$col = str_replace('</td>','',$cols[$j]);
				$values = explode('<',$col);

				if($j == 1)
					$NumEmpleado = $values[0];
				elseif($j == 3)
					$CURP = trim(preg_replace('/\s+/',' ', $values[0]));
				elseif($j == 4)
					$RFC = trim(preg_replace('/\s+/',' ', $values[0]));
				elseif($j == 5)
					$NumDiasPagados = $values[0];

			}

			//Percepciones
			$Percepciones = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepciones');
			$TotalGravado_percepciones = 0.00;
			$TotalExento_percepciones = 0.00;

			for($j=6; $j<=6; $j++)
			{
				$col = str_replace('</td>','',$cols[$j]);
				$values = explode('<',$col);

				if($j == 6)//Honorarios asimilados
				{
					$HonorariosAsimilados = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
					$HonorariosAsimilados->setAttribute('TipoPercepcion', '016');
					$HonorariosAsimilados->setAttribute('Clave', '006');
					$HonorariosAsimilados->setAttribute('Concepto', 'Honorarios asimilados');
					$HonorariosAsimilados->setAttribute('ImporteGravado', $values[0]);
					$HonorariosAsimilados->setAttribute('ImporteExento', 0.00);
					$Percepciones->appendChild($HonorariosAsimilados);
					$TotalGravado_percepciones += $values[0];
				}

			}

			if($Percepciones->hasChildNodes())
			{
				$ComplementoNomina->appendChild($Percepciones);
				$Percepciones->setAttribute('TotalGravado', $TotalGravado_percepciones);
				$Percepciones->setAttribute('TotalExento', $TotalExento_percepciones);
			}

			//Deducciones
			$Deducciones = $XMLDoc->createElementNS($NominaNS, 'nomina:Deducciones');
			$TotalGravado_deducciones = 0.00;
			$TotalExento_deducciones = 0.00;

			for($j=8; $j<=9; $j++)
			{
				$col = str_replace('</td>','',$cols[$j]);
				$values = explode('</tbody>',$col);

				if($j == 8)//ISR
				{
					$ISR = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
					$ISR->setAttribute('TipoDeduccion', '002');
					$ISR->setAttribute('Clave', '008');
					$ISR->setAttribute('Concepto', 'ISR');
					$ISR->setAttribute('ImporteGravado', 0.00);
					$ISR->setAttribute('ImporteExento', $values[0]);
					$TotalGravado_deducciones += 0.00;
					$TotalExento_deducciones += $values[0];
					$Deducciones->appendChild($ISR);
				}
				elseif($j == 9 && $this->total($values[0]) > 0.00)//Gestion administrativa
				{
					$GestionAdministrativa = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
					$GestionAdministrativa->setAttribute('TipoDeduccion', '004');
					$GestionAdministrativa->setAttribute('Clave', '009');
					$GestionAdministrativa->setAttribute('Concepto', 'Gestion administrativa');
					$GestionAdministrativa->setAttribute('ImporteGravado', 0.00);
					$GestionAdministrativa->setAttribute('ImporteExento', $this->total($values[0]));
					$TotalGravado_deducciones += 0.00;
					$TotalExento_deducciones += $this->total($values[0]);
					$Deducciones->appendChild($GestionAdministrativa);
				}

			}

			$TotalGravado_deducciones = round($TotalGravado_deducciones, 2);
			$TotalExento_deducciones = round($TotalExento_deducciones, 2);

			if($Deducciones->hasChildNodes())
			{
				$ComplementoNomina->appendChild($Deducciones);
				$Deducciones->setAttribute('TotalGravado', $TotalGravado_deducciones);
				$Deducciones->setAttribute('TotalExento', $TotalExento_deducciones);
			}

			//Atributos Complemento Nomina
			if($ComplementoNomina->hasChildNodes())
			{
				$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '$RFC' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($NombreTrabajador) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Periodicidad_de_la_nomina FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($PeriodicidadPago) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$RiesgoPuesto = array('I' => '1', 'II' => '2', 'III' => '3', 'IV' => '4', 'V' => '5');
				//Version
				$ComplementoNomina->setAttribute('Version', 1.1);
				//Registro patronal
				$ComplementoNomina->setAttribute('RegistroPatronal', $registro_patronal_admin);
				//Numero de empleado
				$ComplementoNomina->setAttribute('NumEmpleado', $NumEmpleado);
				//CURP
				$matches = array();
				$flag = preg_match('/[A-Z][A,E,I,O,U,X][A-Z]{2}[0-9]{2}[0-1][0-9][0-3][0-9][M,H][A-Z]{2}[B,C,D,F,G,H,J,K,L,M,N,Ñ,P,Q,R,S,T,V,W,X,Y,Z]{3}[0-9,A-Z][0-9]/' , $CURP, $matches);

				if(isset($CURP) && $flag && count($matches) == 1 && strlen($CURP) == 18)
					$ComplementoNomina->setAttribute('CURP', $CURP);
				else
					return "Por favor corrija la CURP del trabajador $NombreTrabajador";

				//Tipo de régimen
				$ComplementoNomina->setAttribute('TipoRegimen', 1);//Asimilados a salarios
				//Fecha de pago
				$ComplementoNomina->setAttribute('FechaPago', $FechaPago);
				//Fecha inicial de pago
				$ComplementoNomina->setAttribute('FechaInicialPago', $this->Limite_inferior_del_periodo);
				//Fecha final de pago
				$ComplementoNomina->setAttribute('FechaFinalPago', $this->Limite_superior_del_periodo);
				//Número de días pagados
				$ComplementoNomina->setAttribute('NumDiasPagados', $NumDiasPagados);

				//Periodicidad de pago
				if(isset($PeriodicidadPago) && $PeriodicidadPago != '')
					$ComplementoNomina->setAttribute('PeriodicidadPago', $PeriodicidadPago);
				else
					return "Por favor corrija la periodicidad de la nómina";

				$XMLDoc->appendChild($ComplementoNomina);
			}

			return array($XMLDoc->saveXML(), $FechaPago, $metodoDePago, $rfc_admin, $sucursal_admin, $RFC, $this->id);
		}

		private function total($val)//calculates the total value for data like <span></span>,...
		{
			$data = explode(',',$val);
			$len = count($data);
			$value = 0;

			for($i=0; $i<$len; $i++)
			{
				$values = explode('</span>',$data[$i]);

				if(count($values) > 1)
				{
					$num = str_replace('<span>','',$values[1]);
					$value += $num;
				}
				else
				{
					$num = str_replace('<span>','',$values[0]);
					$value += $num;
				}

			}

			return $value;
		}

		public function getCFDIPrint()//prints CFDIs related to this nomina
		{
			$CFDINS = 'http://www.sat.gob.mx/cfd/3';
			$NominaNS = 'http://www.sat.gob.mx/nomina';
			$XMLDoc = new DOMDocument('1.0', 'UTF-8');
			$xslt = $XMLDoc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="cfdi_trabajador.xsl"');
			$XMLDoc->appendChild($xslt);
			$Root = $XMLDoc->createElement('Raiz');
			$XMLDoc->appendChild($Root);
			$result = $this->conn->query("SELECT id FROM CFDI_Trabajador WHERE Status = 'Activo' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($id) = $this->conn->fetchRow($result))
			{
				$_cfdi = new CFDI_Trabajador();
				$_cfdi->set('id', $id);
				$_cfdi->setFromDB();
				$cfdi = new DOMDocument('1.0', 'UTF-8');
				$cfdi->loadXML($_cfdi->get('CFDI'));
				$xpath = new DOMXPath($cfdi);
				$xpath->registerNamespace('cfdi', $CFDINS);
				$xpath->registerNamespace('nomina', $NominaNS);
				$emisor = $xpath->query("/cfdi:Comprobante/cfdi:Emisor");
				$RFC_emisor = $emisor->item(0)->getAttribute('rfc');
				$nomina = $xpath->query("//nomina:Nomina");
				$RegistroPatronal = $nomina->item(0)->getAttribute('RegistroPatronal');
				$result1 = $this->conn->query("SELECT Empresa, Sucursal, Empresa_sucursal FROM Registro_patronal WHERE Numero = '$RegistroPatronal' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($empresa, $sucursal, $empresa_sucursal) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);

				if(isset($sucursal))
				{
					$result1 = $this->conn->query("SELECT Width, Height FROM Logo WHERE Sucursal = '$sucursal' AND Empresa_sucursal = '$empresa_sucursal' AND Cuenta = '{$_SESSION['cuenta']}'");

					if($this->conn->num_rows($result1) == 0)
						$result1 = $this->conn->query("SELECT Width, Height FROM Logo WHERE Empresa = '$RFC_emisor' AND Cuenta = '{$_SESSION['cuenta']}'");

				}
				else
					$result1 = $this->conn->query("SELECT Width, Height FROM Logo WHERE Empresa = '$RFC_emisor' AND Cuenta = '{$_SESSION['cuenta']}'");

				list($width, $height) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);
				$emisor->item(0)->setAttribute('width', $width);
				$emisor->item(0)->setAttribute('height', $height);

				if(isset($sucursal))
					$emisor->item(0)->setAttribute('sucursal', $sucursal);

				$c = $XMLDoc->importNode($cfdi->firstChild, true);
				$Root->appendChild($c);
			}

			echo $XMLDoc->saveXML();
		}

		public function cancel_nomina()//cancel CFDIs related to this nomina
		{
			$result = $this->conn->query("SELECT id FROM CFDI_Trabajador WHERE Status = 'Activo' AND Nomina = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			$cancel = true;

			while(list($id) = $this->conn->fetchRow($result))
			{
				$cfdi = new CFDI_Trabajador();
				$cfdi->set('id', $id);
				$msg = $cfdi->cancel();

				if($msg != 'Cancelación exitosa' && $msg != 'Error 202')//202 Previamente cancelado
				{
					$cancel = false;
					return $msg;
				}

			}

			if($cancel)
				$this->conn->query("UPDATE Nomina Set Etapa = 'Cancelada' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

		}

		public function download_cfdi()//download CFDIs related to this nomina
		{
			$this->setFromDB();
			$CFDINS = 'http://www.sat.gob.mx/cfd/3';
			$NominaNS = 'http://www.sat.gob.mx/nomina';
			$tfdNS = 'http://www.sat.gob.mx/TimbreFiscalDigital';
			$xsl_string = file_get_contents('cfdi_trabajador_to_go.xsl');
			$result = $this->conn->query("SELECT Trabajador.Nombre, CFDI_Trabajador.CFDI FROM CFDI_Trabajador LEFT JOIN Trabajador ON CFDI_Trabajador.Receptor = Trabajador.RFC WHERE CFDI_Trabajador.Status = 'Activo' AND CFDI_Trabajador.Nomina = '{$this->id}' AND CFDI_Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Trabajador.Cuenta = '{$_SESSION['cuenta']}'");
			$path = "temp/CFDI_" . uniqid();
			$zip_file = $path . '.zip';
			$zip = new ZipArchive();
			$zip->open($zip_file, ZIPARCHIVE::CREATE);
			mkdir($path, 0777);
			chmod($path, 0777);
			$first = true;//first iterarion (to read Logo)

			while(list($trabajador, $_cfdi) = $this->conn->fetchRow($result))
			{
				//Creating dir
				$trabajador = strtoupper($trabajador);
				$trabajador = str_replace('Á', 'A', $trabajador);
				$trabajador = str_replace('É', 'E', $trabajador);
				$trabajador = str_replace('Í', 'I', $trabajador);
				$trabajador = str_replace('Ó', 'O', $trabajador);
				$trabajador = str_replace('Ú', 'U', $trabajador);
				$trabajador = str_replace('Ñ', 'N', $trabajador);
				$trabajador = str_replace('Ü', 'U', $trabajador);
				$dir = $path . '/' . $trabajador;
				mkdir($dir, 0777);
				chmod($dir, 0777);
				//Adding CFDI
				$cfdi_filename = $dir . '/CFDI.xml';
				file_put_contents($cfdi_filename, $_cfdi);
				$zip->addFile($cfdi_filename, $trabajador . '/CFDI.xml');
				//Adding Logo
				$xml_string = $_cfdi;
				$XMLDoc = new DOMDocument('1.0', 'UTF-8');
				$Root = $XMLDoc->createElement('Raiz');
				$Root = $XMLDoc->appendChild($Root);
				$cfdi = new DOMDocument('1.0', 'UTF-8');
				$cfdi->loadXML($xml_string);
				$xpath = new DOMXPath($cfdi);
				$xpath->registerNamespace('cfdi', $CFDINS);
				$xpath->registerNamespace('nomina', $NominaNS);
				$xpath->registerNamespace('tfd', $tfdNS);
				$emisor = $xpath->query("/cfdi:Comprobante/cfdi:Emisor");
				$RFC_emisor = $emisor->item(0)->getAttribute('rfc');
				$receptor = $xpath->query("/cfdi:Comprobante/cfdi:Receptor");
				$RFC_receptor = $receptor->item(0)->getAttribute('rfc');
				$comprobante = $xpath->query("/cfdi:Comprobante");
				$total = $comprobante->item(0)->getAttribute('total');
				$data = explode('.', $total);
				$data[0] = str_pad($data[0], 10, '0', STR_PAD_LEFT);
				$data[1] = isset($data[1]) ? str_pad($data[1], 6, '0', STR_PAD_RIGHT) : '000000';
				$total = implode('.', $data);
				$timbre = $xpath->query("/cfdi:Comprobante/cfdi:Complemento/tfd:TimbreFiscalDigital");
				$uuid = $timbre->item(0)->getAttribute('UUID');

				if($first)//only the first time the Logo will be read
				{
					$nomina = $xpath->query("//nomina:Nomina");
					$RegistroPatronal = $nomina->item(0)->getAttribute('RegistroPatronal');
					$result1 = $this->conn->query("SELECT Empresa, Sucursal, Empresa_sucursal FROM Registro_patronal WHERE Numero = '$RegistroPatronal' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($empresa, $sucursal, $empresa_sucursal) = $this->conn->fetchRow($result1);
					$this->conn->freeResult($result1);

					if(isset($sucursal))
					{
						$result1 = $this->conn->query("SELECT Contenido, Width, Height FROM Logo WHERE Sucursal = '$sucursal' AND Empresa_sucursal = '$empresa_sucursal' AND Cuenta = '{$_SESSION['cuenta']}'");

						if($this->conn->num_rows($result1) == 0)
							$result1 = $this->conn->query("SELECT Contenido, Width, Height FROM Logo WHERE Empresa = '$RFC_emisor' AND Cuenta = '{$_SESSION['cuenta']}'");

					}
					else
						$result1 = $this->conn->query("SELECT Contenido, Width, Height FROM Logo WHERE Empresa = '$RFC_emisor' AND Cuenta = '{$_SESSION['cuenta']}'");

					list($logo, $width, $height) = $this->conn->fetchRow($result1);
					$this->conn->freeResult($result1);
					$first = false;
				}

				if(isset($logo))
				{
					$logo_filename = $dir . '/logo.png';
					file_put_contents($logo_filename, $logo);
//					$zip->addFile($logo_filename, $trabajador . '/logo.png');
				}

				//Adding QRcode
				$cbb = file_get_contents("https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=%3Fre%3D$RFC_emisor%26rr%3D$RFC_receptor%26tt%3D$total%26id%3D$uuid&choe=UTF-8");

				if(isset($cbb))
				{
					$cbb_filename = $dir . '/cbb.png';
					file_put_contents($cbb_filename, $cbb);
//					$zip->addFile($cbb_filename, $trabajador . '/cbb.png');
				}

				//Adding HTML
				$emisor->item(0)->setAttribute('width', $width);
				$emisor->item(0)->setAttribute('height', $height);
				$c = $XMLDoc->importNode($cfdi->firstChild, true);
				$Root->appendChild($c);
				$xsl = new XSLTProcessor();
				$xsl->importStylesheet(new SimpleXMLElement($xsl_string));
				$html_filename = $dir . '/RepresentacionImpresa.html';
				$html_str = $xsl->transformToXml(new SimpleXMLElement($XMLDoc->saveXML()));
				file_put_contents($html_filename, $html_str);
//				$zip->addFile($html_filename, $trabajador . '/RepresentacionImpresa.html');
				//Adding PDF
				$pdf_filename = $dir . '/CFDI.pdf';
				$cmd = "wkhtmltopdf --margin-bottom 0 --margin-left 0 --margin-right 0 --margin-top 0 '$html_filename' '$pdf_filename'";
				system($cmd, $retval);
				$zip->addFile($pdf_filename, $trabajador . '/CFDI.pdf');
			}

			$zip->close();
			header("Content-length: " . filesize($zip_file));
			header('Content-type: application/zip');
			header("Content-Disposition: attachment; filename=$zip_file");
			$content = file_get_contents($zip_file);
			echo $content;
			unlink($zip_file);
			system('rm -rf ' . escapeshellarg($path), $retval);
		}

	}
?>
