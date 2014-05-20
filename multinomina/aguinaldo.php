<?php
	include_once('connection.php');
	include_once('actividad.php');

//Class Aguinaldo definition

	class Aguinaldo
	{
		//class properties
		//data
		private $id;
		private $Fecha_de_pago;
		private $Servicio;
		private $ISRaguinaldo;//html string
		private $aguinaldo_asalariados;//html string
		public $Resumen_aguinaldo;//string value,value,...,value
		private	$trabajador;//array wont be stored
		private	$gratificacion_adicional;//array wont be stored
		private	$pago_neto;//array wont be stored

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
			$result = $this->conn->query("SELECT id FROM Aguinaldo WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY id DESC LIMIT 1");
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
			$result = $this->conn->query("SELECT Empresa FROM Servicio_Empresa WHERE Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha_de_pago}', Fecha_de_asignacion) >=0 ORDER BY Fecha_de_asignacion DESC LIMIT 1");
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
				$this->ISRaguinaldo = '<table id="ISRaguinaldo"><tr><td colspan = "17" class = "title">Trabajadores asalariados</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">RFC</td><td class = "column_title">Número de días previos al ingreso</td><td class = "column_title">Número de días de baja</td><td class = "column_title">Número de días de aguinaldo</td><td class = "column_title">Salario diario</td><td class = "column_title">Aguinaldo ordinario</td><td class = "column_title">Gratificación adicional</td><td class = "column_title">Base</td><td class = "column_title">Límite inferior</td><td class = "column_title">Exedente del límite inferior</td><td class = "column_title">Porcentaje sobre el exedente del límite inferior</td><td class = "column_title">Impuesto marginal</td><td class = "column_title">Cuota fija</td><td class = "column_title">Impuesto determinado</td><td class = "column_title">Subsidio</td></tr>';
				$result = $this->conn->query("SELECT * FROM ISRaguinaldo WHERE Aguinaldo = {$this->id} AND Cuenta = '{$_SESSION['cuenta']}'");
				$n = 1;

				while($row = $this->conn->fetchRow($result,'ASSOC'))
				{
					$this->ISRaguinaldo .= '<tr>';

					foreach($row as $key => $value)

						if($key == 'Trabajador')
						{
							$result1 = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($nombre) = $this->conn->fetchRow($result1);
							$this->ISRaguinaldo .= "<td>$n</td><td>$nombre</td><td>$value</td>";
							$this->conn->freeResult($result1);
							$n ++;
						}
						elseif($key != 'Aguinaldo' && $key != 'Cuenta')
							$this->ISRaguinaldo .= "<td>$value</td>";

					$this->ISRaguinaldo .= '</tr>';
				}


				$this->ISRaguinaldo .= '</table>';
				$this->conn->freeResult($result);
				$this->aguinaldo_asalariados = '<table id="aguinaldo_asalariados"><tr><td colspan = "12" class = "title">Trabajadores asalariados</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">RFC</td><td class = "column_title">Número de dias de aguinaldo</td><td class = "column_title">Salario diario</td><td class = "column_title">Aguinaldo ordinario</td><td class = "column_title">Gratificación adicional</td><td class = "column_title">Total de percepciones</td><td class = "column_title">ISR</td><td class = "column_title">Total de deducciones</td><td class = "column_title">Saldo</td><td class = "column_title">Pago neto</td></tr>';
				$result = $this->conn->query("SELECT * FROM aguinaldo_asalariados WHERE Aguinaldo = {$this->id} AND Cuenta = '{$_SESSION['cuenta']}'");
				$n = 1;

				while($row = $this->conn->fetchRow($result,'ASSOC'))
				{
					$this->aguinaldo_asalariados .= '<tr>';

					foreach($row as $key => $value)

						if($key == 'Trabajador')
						{
							$result1 = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC  = '$value' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($nombre) = $this->conn->fetchRow($result1);
							$this->aguinaldo_asalariados .= "<td>$n</td><td>$nombre</td><td>$value</td>";
							$this->conn->freeResult($result1);
							$n ++;
						}
						elseif($key != 'Aguinaldo' && $key != 'Cuenta')
							$this->aguinaldo_asalariados .= "<td>$value</td>";

					$this->aguinaldo_asalaridos .= '</tr>';
				}

				$this->aguinaldo_asalariados .= '</table>';
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT * FROM Resumen_aguinaldo WHERE Aguinaldo = {$this->id} AND Cuenta = '{$_SESSION['cuenta']}'");

				while($row = $this->conn->fetchRow($result,'ASSOC'))
				{

					foreach($row as $key => $value)

						if($key != 'Aguinaldo' && $key != 'Cuenta')
							$this->Resumen_aguinaldo .= $value . ',';

				}

				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT * FROM Aguinaldo WHERE id = {$this->id} AND Cuenta = '{$_SESSION['cuenta']}'");
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
				$actividad->set('Dato','Aguinaldo');
				$result = $this->conn->query("SELECT Aguinaldo.Fecha_de_pago, Servicio.id, Servicio.Periodicidad_de_la_nomina, Empresa.Nombre, Servicio_Registro_patronal.Registro_patronal FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio_Empresa.Servicio = Servicio_Registro_patronal.Servicio LEFT JOIN Aguinaldo ON Servicio.id = Aguinaldo.Servicio WHERE Aguinaldo.id = '{$this->id}' AND DATEDIFF(Aguinaldo.Fecha_de_pago, Servicio_Empresa.Fecha_de_asignacion) >=0 AND DATEDIFF(Aguinaldo.Fecha_de_pago, Servicio_Registro_patronal.Fecha_de_asignacion) >=0 AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Aguinaldo.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
				list($fecha,$id,$periodicidad,$empresa,$registro) = $this->conn->fetchRow($result);
				$actividad->set('Identificadores',"Fecha de pago: $fecha Servicio: $id/$periodicidad/$empresa/$registro");
				$actividad->dbStore();
				$this->conn->query("DELETE FROM Aguinaldo WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties, if $update is true it updates all database registers (professedly one) with $this' id
		{
			$actividad = new Actividad();
			$actividad->set('Dato','Aguinaldo');
			$result = $this->conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Empresa.Nombre, Servicio_Registro_patronal.Registro_patronal FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio_Empresa.Servicio = Servicio_Registro_patronal.Servicio WHERE Servicio.id = '{$this->Servicio}' AND DATEDIFF('{$this->Fecha_de_pago}', Servicio_Empresa.Fecha_de_asignacion) >=0 AND DATEDIFF('{$this->Fecha_de_pago}', Servicio_Registro_patronal.Fecha_de_asignacion) >=0 AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
			list($id,$periodicidad,$empresa,$registro) = $this->conn->fetchRow($result);
			$actividad->set('Identificadores',"Fecha de pago: {$this->Fecha_de_pago} Servicio: $id/$periodicidad/$empresa/$registro");
			$this->conn->freeResult($result);

			if($update == 'false')
			{
				$this->setID();
				$this->conn->query("INSERT INTO Aguinaldo(id, Cuenta) VALUES ({$this->id}, '{$_SESSION['cuenta']}')");
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

					if($key == 'ISRaguinaldo')
					{
						$rows = explode('<tr>',$this->$key);
						$rows_len = count($rows);

						for($i=3; $i<$rows_len; $i++)
						{
							//echo "$i:";
							$row = str_replace('</tr>','',$rows[$i]);
							$cols = explode('<td>',$row);
							$cols_len = count($cols);

							for($j=3; $j<$cols_len; $j++)
							{
								$col = str_replace('</td>','',$cols[$j]);
								$values = explode('<',$col);
								$register[$j-3] = $values[0];
								//echo urlencode($register[$j-3]);
							}

							//echo '<br/>';
							$result = $this->conn->query("SELECT Trabajador FROM ISRaguinaldo WHERE Trabajador = '{$register[0]}' AND Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

							if($this->conn->num_rows($result) == 0)
								$this->conn->query("INSERT INTO ISRaguinaldo(Trabajador, Aguinaldo, Cuenta) VALUES('{$register[0]}', '{$this->id}', '{$_SESSION['cuenta']}')");

							$this->conn->query("UPDATE ISRaguinaldo SET Numero_de_dias_previos_al_ingreso = {$register[1]},Numero_de_dias_de_baja = {$register[2]},Numero_de_dias_de_aguinaldo = {$register[3]},Salario_diario = '{$register[4]}',Aguinaldo_ordinario = {$register[5]},Gratificacion_adicional = {$register[6]},Base = {$register[7]} ,Limite_inferior = {$register[8]},Exedente_del_limite_inferior = {$register[9]},Porcentaje_sobre_el_exedente_del_limite_inferior = {$register[10]},Impuesto_marginal = {$register[11]},Cuota_fija = {$register[12]},Impuesto_determinado = {$register[13]},Subsidio = {$register[14]} WHERE Trabajador = '{$register[0]}' AND Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
						}

					}
					elseif($key == 'aguinaldo_asalariados')
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
								$values = explode('</tbody>',$col);
								//echo urlencode($values[0]) . '<br/>';
								$register[$j-3] = $values[0];
								$register[$j-3] = ($register[$j-3] == '') ? 0.00 : $register[$j-3];
							}

							$result = $this->conn->query("SELECT Trabajador FROM aguinaldo_asalariados WHERE Trabajador = '{$register[0]}' AND Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

							if($this->conn->num_rows($result) == 0)
								$this->conn->query("INSERT INTO aguinaldo_asalariados(Trabajador, Aguinaldo, Cuenta) VALUES('{$register[0]}', '{$this->id}', '{$_SESSION['cuenta']}')");

							$this->conn->query("UPDATE aguinaldo_asalariados SET Numero_de_dias_de_aguinaldo = '{$register[1]}', Salario_diario = '{$register[2]}', Aguinaldo_ordinario = {$register[3]}, Gratificacion_adicional = '{$register[4]}', Total_de_percepciones = {$register[5]}, ISR = {$register[6]}, Total_de_deducciones = {$register[7]}, Saldo = {$register[8]}, Pago_neto = {$register[9]} WHERE Trabajador = '{$register[0]}' AND Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
						}

					}
					elseif($key == 'Resumen_aguinaldo')
					{
						$this->calculate_resumen();
						$register = explode(',',$this->Resumen_aguinaldo);
						$result = $this->conn->query("SELECT Aguinaldo FROM Resumen_aguinaldo WHERE Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

						if($this->conn->num_rows($result) == 0)
							$this->conn->query("INSERT INTO Resumen_aguinaldo(Aguinaldo, Cuenta) VALUES('{$this->id}', '{$_SESSION['cuenta']}')");

						$this->conn->query("UPDATE Resumen_aguinaldo SET Aguinaldo_ordinario = '{$register[0]}',Gratificacion_adicional = '{$register[1]}',Total_de_percepciones = '{$register[2]}',ISR = '{$register[3]}',Saldo = '{$register[4]}',Aguinaldo_pagado = '{$register[5]}',Diferencia = '{$register[6]}',Honorarios = '{$register[7]}',Subtotal_a_facturar = '{$register[8]}',iva = '{$register[9]}',Total_a_facturar = '{$register[10]}' WHERE Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
					}
					elseif($key == 'Servicio')
					{
						$data = explode('/',$this->Servicio);
						$this->conn->query("UPDATE Aguinaldo SET $key = {$data[0]} WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
					}
					elseif($key != 'conn' && $key != 'id' && $key != 'trabajador' && $key != 'gratificacion_adicional')
						$this->conn->query("UPDATE Aguinaldo SET $key  = '$value' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

				}
				elseif($key == 'Resumen_aguinaldo')
				{
						$this->calculate_resumen();
						$register = explode(',',$this->Resumen_aguinaldo);
						$result = $this->conn->query("SELECT Aguinaldo FROM Resumen_aguinaldo WHERE Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

						if($this->conn->num_rows($result) == 0)
							$this->conn->query("INSERT INTO Resumen_aguinaldo(Aguinaldo, Cuenta) VALUES('{$this->id}', '{$_SESSION['cuenta']}')");

						$this->conn->query("UPDATE Resumen_aguinaldo SET Aguinaldo_ordinario = '{$register[0]}',Gratificacion_adicional = '{$register[1]}',Total_de_percepciones = '{$register[2]}',ISR = '{$register[3]}',Saldo = '{$register[4]}',Aguinaldo_pagado = '{$register[5]}',Diferencia = '{$register[6]}',Honorarios = '{$register[7]}',Subtotal_a_facturar = '{$register[8]}',iva = '{$register[9]}',Total_a_facturar = '{$register[10]}' WHERE Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
				}

			return true;
		}

		private function update_trabajadores_list()
		{
			//updating ISRaguinaldo
			$rows = explode('<tr>',$this->ISRaguinaldo);
			$rows_len = count($rows);
			$trabajadores_ISRaguinaldo = array();

			for($i=3; $i<$rows_len; $i++)
			{
				$cols = explode('<td>',$rows[$i]);
				$value = str_replace('</td>','',$cols[3]);
				$trabajadores_ISRaguinaldo[$i - 3] = $value;
			}

			$trabajadores_len = count($trabajadores_ISRaguinaldo);
			$result = $this->conn->query("SELECT Trabajador FROM ISRaguinaldo WHERE Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($_trabajador) = $this->conn->fetchRow($result))
			{
				$del = true;

				for($i=0; $i<$trabajadores_len; $i++)

					if($_trabajador == $trabajadores_ISRaguinaldo)
					{
						$del = false;
						break;
					}

				if($del)
					$this->conn->query("DELETE FROM ISRaguinaldo WHERE Trabajador = '$_trabajador' AND Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			}

			//updating aguinaldo_asalariados
			$rows = explode('<tr>',$this->aguinaldo_asalariados);
			$rows_len = count($rows);
			$trabajadores_aguinaldo_asalariados = array();

			for($i=3; $i<$rows_len; $i++)
			{
				$cols = explode('<td>',$rows[$i]);
				$value = str_replace('</td>','',$cols[3]);
				$trabajadores_aguinaldo_asalariados[$i - 3] = $value;
			}

			$trabajadores_len = count($trabajadores_aguinaldo_asalariados);
			$result = $this->conn->query("SELECT Trabajador FROM aguinaldo_asalariados WHERE Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($_trabajador) = $this->conn->fetchRow($result))
			{
				$del = true;

				for($i=0; $i<$trabajadores_len; $i++)

					if($_trabajador == $trabajadores_aguinaldo_asalariados)
					{
						$del = false;
						break;
					}

				if($del)
					$this->conn->query("DELETE FROM aguinaldo_asalariados WHERE Trabajador = '$_trabajador' AND Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			}

		}

		public function calculate_ISR_trabajadores_asalariados()
		{

			if(isset($this->id) && isset($this->Servicio))
			{
				date_default_timezone_set('America/Mexico_City');
				//get this servicio trabajadores
				$this->ISRaguinaldo = '<table id="ISRaguinaldo"><tr><td colspan = "17" class = "title">Trabajadores asalariados</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">RFC</td><td class = "column_title">Número de días previos al ingreso</td><td class = "column_title">Número de días de baja</td><td class = "column_title">Número de días de aguinaldo</td><td class = "column_title">Salario diario</td><td class = "column_title">Aguinaldo ordinario</td><td class = "column_title">Gratificación adicional</td><td class = "column_title">Base</td><td class = "column_title">Límite inferior</td><td class = "column_title">Exedente del límite inferior</td><td class = "column_title">Porcentaje sobre el exedente del límite inferior</td><td class = "column_title">Impuesto marginal</td><td class = "column_title">Cuota fija</td><td class = "column_title">Impuesto determinado</td><td class = "column_title">Subsidio</td></tr>';
				$len = count($this->trabajador);
				$n = 1;

				for($i=0; $i<$len; $i++)
				{
					$result1 = $this->conn->query("SELECT Tipo FROM Tipo WHERE Trabajador = '{$this->trabajador[$i]}' AND Servicio = '{$this->Servicio}' AND DATEDIFF('{$this->Fecha_de_pago}',Fecha) >= 0 AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha DESC LIMIT 1");
					list($tipo) = $this->conn->fetchRow($result1);

					if(isset($tipo) && $tipo == 'Asalariado')
					{
						$this->calculate_ISR_trabajador_asalariado($this->trabajador[$i],$n);
						$n ++;
					}

				}

				$this->ISRaguinaldo .= '</table>';
			}

		}

		public function calculate_aguinaldo_trabajadores_asalariados()
		{

			if(isset($this->id) && isset($this->Servicio))
			{
				date_default_timezone_set('America/Mexico_City');
				$this->aguinaldo_asalariados = '<table id="aguinaldo_asalariados"><tr><td colspan = "12" class = "title">Trabajadores asalariados</td></tr><tr><td class = "column_title">#</td><td class = "column_title">Nombre</td><td class = "column_title">RFC</td><td class = "column_title">Número de días de aguinaldo</td><td class = "column_title">Salario diario</td><td class = "column_title">Aguinaldo ordinario</td><td class = "column_title">Gratificación adicional</td><td class = "column_title">Total de percepciones</td><td class = "column_title">ISR</td><td class = "column_title">Total de deducciones</td><td class = "column_title">Saldo</td><td class = "column_title">Pago neto</td></tr>';
				$len = count($this->trabajador);
				$n = 1;

				for($i=0; $i<$len; $i++)
				{
					$result1 = $this->conn->query("SELECT Tipo FROM Tipo WHERE Trabajador = '{$this->trabajador[$i]}' AND Servicio = '{$this->Servicio}' AND DATEDIFF('{$this->Fecha_de_pago}',Fecha) >= 0 AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha DESC LIMIT 1");
						list($tipo) = $this->conn->fetchRow($result1);

						if(isset($tipo) && $tipo == 'Asalariado')
						{
							$this->calculate_aguinaldo_trabajador_asalariado($this->trabajador[$i],$n);
							$n ++;
						}

				}

				$this->aguinaldo_asalariados .= '</table>';
			}

		}

		public function chk_saldo_trabajadores_asalariados()//if "saldo < 0" it'll reduct "retenciones" and reset "Pago neto" and "Total de deducciones"
		{
			$len = count($this->trabajador);

			for($i=0; $i<$len; $i++)
			{
				$saldo = $this->get_value('Saldo',$this->aguinaldo_asalariados,$this->trabajador[$i]);
				$pago_neto = $this->get_value('Pago neto',$this->aguinaldo_asalariados,$this->trabajador[$i]);

				if($saldo >= 0 && $saldo < $pago_neto)
				{
					$aguinaldo_ordinario = $this->get_value('Aguinaldo ordinario',$this->aguinaldo_asalariados,$this->trabajador[$i]);
					list($gratificacion_adicional,$base,$li,$eli,$pseli,$im,$cf,$id,$se,$tp,$td,$s,$dif) = $this->compensate_gratificacion_adicional($this->trabajador[$i],$aguinaldo_ordinario,$pago_neto);
					$isr = $id > $se ? ($id - $se) : 0.00;
					$this->set_value('Gratificación adicional','aguinaldo_asalariados',$this->trabajador[$i],$gratificacion_adicional);
					$this->set_value('Gratificación adicional','ISRaguinaldo',$this->trabajador[$i],$gratificacion_adicional);
					$this->set_value('Base','ISRaguinaldo',$this->trabajador[$i],$base);
					$this->set_value('Límite inferior','ISRaguinaldo',$this->trabajador[$i],$li);
					$this->set_value('Exedente del límite inferior','ISRaguinaldo',$this->trabajador[$i],$eli);
					$this->set_value('Porcentaje sobre el exedente del límite inferior','ISRaguinaldo',$this->trabajador[$i],$pseli);
					$this->set_value('Impuesto marginal','ISRaguinaldo',$this->trabajador[$i],$im);
					$this->set_value('Cuota fija','ISRaguinaldo',$this->trabajador[$i],$cf);
					$this->set_value('Impuesto determinado','ISRaguinaldo',$this->trabajador[$i],$id);
					$this->set_value('Subsidio','ISRaguinaldo',$this->trabajador[$i],$se);
					$this->set_value('ISR','aguinaldo_asalariados',$this->trabajador[$i],round($isr,2));
					$this->set_value('Total de percepciones','aguinaldo_asalariados',$this->trabajador[$i],$tp);
					$this->set_value('Total de deducciones','aguinaldo_asalariados',$this->trabajador[$i],$td);
					$this->set_value('Saldo','aguinaldo_asalariados',$this->trabajador[$i],round($s,2));
				}

			}

		}

		public function calculate_ISR_trabajador_asalariado($_trabajador,$n)
		{
			date_default_timezone_set('America/Mexico_City');
			$this->ISRaguinaldo .= '<tr>';
			//Trabajador
			$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			$row = $this->conn->fetchRow($result);
			list($nombre) = $row;
			$this->ISRaguinaldo .=  "<td>$n</td><td>$nombre</td><td>$_trabajador</td>";
			//Número de días previos al ingreso
			$numero_de_dias_previos_al_ingreso = $this->calculate_numero_de_dias_previos_al_ingreso($_trabajador);
			$this->ISRaguinaldo .= "<td>$numero_de_dias_previos_al_ingreso</td>";
			//Número de días de baja
			$numero_de_dias_de_baja = $this->calculate_numero_de_dias_de_baja($_trabajador);
			$this->ISRaguinaldo .= "<td>$numero_de_dias_de_baja</td>";
			//Número de días de aguinaldo
			$result = $this->conn->query("SELECT Dias_de_aguinaldo FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($dias_de_aguinaldo) = $this->conn->fetchRow($result);
			$numero_de_dias_de_aguinaldo = (365 - $numero_de_dias_de_baja - ($numero_de_dias_de_baja > 0 ? 0 : $numero_de_dias_previos_al_ingreso)) * $dias_de_aguinaldo / 365;
			$numero_de_dias_de_aguinaldo = number_format($numero_de_dias_de_aguinaldo, 2, '.', '');
			$this->ISRaguinaldo .= "<td>$numero_de_dias_de_aguinaldo</td>";
			//Salario diario
			$result = $this->conn->query("SELECT Base FROM Base WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha_de_pago}', Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
			list($base) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$salario_diario = $this->calculate_salario_diario($_trabajador,$base);
			$this->ISRaguinaldo .= "<td>$salario_diario</td>";
			//Aguinaldo ordinario
			$aguinaldo_ordinario = round($numero_de_dias_de_aguinaldo * $salario_diario,2);
			$this->ISRaguinaldo .= "<td>$aguinaldo_ordinario</td>";
			//Gratificación adicional
			$gratificacion_adicional = $this->calculate_gratificacion_adicional($_trabajador);
			$this->ISRaguinaldo .= "<td>$gratificacion_adicional</td>";
			//base ISR
			$salario_minimo = $this->calculate_salario_minimo($_trabajador);

			if($aguinaldo_ordinario <= ($salario_minimo * 30))
				$base_ISR = $gratificacion_adicional;
			else
				$base_ISR = $gratificacion_adicional + $aguinaldo_ordinario - $salario_minimo * 30;

			$this->ISRaguinaldo .= "<td>$base_ISR</td>";
			//Límite inferior
			$result = $this->conn->query("SELECT Periodicidad_de_la_nomina FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($periodicidad_de_la_nomina) = $this->conn->fetchRow($result);
			$numero_de_dias_del_periodo = $periodicidad_de_la_nomina == 'Semanal' ? 7 : 15;
			$limite_inferior = $this->calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
			$this->ISRaguinaldo .= "<td>$limite_inferior</td>";
			//Exedente del límite inferior
			$exedente_del_limite_inferior = $base_ISR - $limite_inferior;
			$this->ISRaguinaldo .= "<td>$exedente_del_limite_inferior</td>";
			//Porcentaje sobre el exedente del límite inferior
			$porcentaje_sobre_el_exedente_del_limite_inferior = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
			$this->ISRaguinaldo .= "<td>$porcentaje_sobre_el_exedente_del_limite_inferior</td>";
			//Impuesto marginal
			$impuesto_marginal = $exedente_del_limite_inferior * $porcentaje_sobre_el_exedente_del_limite_inferior;
			$this->ISRaguinaldo .= "<td>$impuesto_marginal</td>";
			//Cuota fija
			$cuota_fija = $this->calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo);
			$this->ISRaguinaldo .= "<td>$cuota_fija</td>";
			//Impuesto determinado
			$impuesto_determinado = $impuesto_marginal + $cuota_fija;
			$this->ISRaguinaldo .= "<td>$impuesto_determinado</td>";
			//Subsidio
			$subsidio = $this->calculate_subsidio($base_ISR,$numero_de_dias_del_periodo);
			$this->ISRaguinaldo .= "<td>$subsidio</td>";
			$this->ISRaguinaldo .= '</tr>';
		}

		private function calculate_aguinaldo_trabajador_asalariado($_trabajador,$n)
		{
			date_default_timezone_set('America/Mexico_City');
			$this->aguinaldo_asalariados .= '<tr>';
			//Trabajador
			$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '$_trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
			$row = $this->conn->fetchRow($result);
			list($nombre) = $row;
			$this->aguinaldo_asalariados .=  "<td>$n</td><td>$nombre</td><td>$_trabajador</td>";
			//Número de días de aguinaldo
			$numero_de_dias_de_aguinaldo = $this->get_value('Número de días de aguinaldo',$this->ISRaguinaldo,$_trabajador);
			$this->aguinaldo_asalariados .= "<td>$numero_de_dias_de_aguinaldo</td>";
			//Salario diario
			$salario_diario = $this->get_value('Salario diario',$this->ISRaguinaldo,$_trabajador);
			$this->aguinaldo_asalariados .= "<td>$salario_diario</td>";
			//Aguinaldo ordinario
			$aguinaldo_ordinario = $this->get_value('Aguinaldo ordinario',$this->ISRaguinaldo,$_trabajador);
			$this->aguinaldo_asalariados .= "<td>$aguinaldo_ordinario</td>";
			//Gratificación adicional
			$gratificacion_adicional = $this->get_value('Gratificación adicional',$this->ISRaguinaldo,$_trabajador);
			$this->aguinaldo_asalariados .= "<td>$gratificacion_adicional</td>";
			//Total de percepciones
			$total_de_percepciones = $aguinaldo_ordinario + $gratificacion_adicional;
			$this->aguinaldo_asalariados .= "<td>$total_de_percepciones</td>";
			//ISR
			$impuesto_determinado = $this->get_value('Impuesto determinado',$this->ISRaguinaldo,$_trabajador);
			$subsidio = $this->get_value('Subsidio',$this->ISRaguinaldo,$_trabajador);
			$isr = $impuesto_determinado > $subsidio ? ($impuesto_determinado - $subsidio) : 0.00;
			$this->aguinaldo_asalariados .= "<td>$isr</td>";
			//Total de deducciones
			$total_de_deducciones = $isr;
			$this->aguinaldo_asalariados .= "<td>$total_de_deducciones</td>";
			//Saldo
			$saldo = $total_de_percepciones - $total_de_deducciones;
			$this->aguinaldo_asalariados .= "<td>$saldo</td>";
			//Pago neto
			$pago_neto = $this->calculate_pago_neto($_trabajador);
			$this->aguinaldo_asalariados .= "<td>$pago_neto</td>";
			$this->aguinaldo_asalariados .= '</tr>';
		}

		private function calculate_ingreso($trabajador)
		{
			$ingreso = Null;
			$result = $this->conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '$trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND Fecha_de_reingreso != '0000-00-00' AND DATEDIFF(Fecha_de_reingreso, '{$this->Fecha_de_pago}') <= 0 AND DATEDIFF(Fecha_de_reingreso, Fecha_de_baja) > 1 ORDER BY Fecha_de_reingreso DESC LIMIT 1");
			list($ingreso) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if(!isset($ingreso))
			{
				$result = $this->conn->query("SELECT Antiguedad_prestaciones FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($antiguedad) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);

				if($antiguedad == 'Servicio')
					$result = $this->conn->query("SELECT Fecha_de_ingreso_servicio FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
				else
					$result = $this->conn->query("SELECT Fecha_de_ingreso_cliente FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Travahador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");

				list($ingreso) = $this->conn->fetchRow($result);
			}

			return $ingreso;
		}

		private function calculate_numero_de_dias_previos_al_ingreso($_trabajador)
		{
			date_default_timezone_set('America/Mexico_City');
			$fecha_de_pago = date_create($this->Fecha_de_pago);
			$firstday = date('Y', $fecha_de_pago->format('U')) . '-01-01';//YYYY-01-01
			$ingreso = $this->calculate_ingreso($_trabajador);

			if(isset($ingreso) && $ingreso > $firstday)
			{
				$interval = date_diff(date_create($firstday),date_create($ingreso));
				$numero_de_dias_previos_al_ingreso = $interval->days;
			}
			else
				$numero_de_dias_previos_al_ingreso = 0;

			return $numero_de_dias_previos_al_ingreso;
		}

		public function calculate_numero_de_dias_de_baja($_trabajador)
		{
			date_default_timezone_set('America/Mexico_City');
			$fecha_de_pago = date_create($this->Fecha_de_pago);
			$numero_de_dias_del_periodo = 365;
			$year = date('Y', $fecha_de_pago->format('U'));
			$result = $this->conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND YEAR(Fecha_de_reingreso) = '$year' ORDER BY Fecha_de_reingreso DESC LIMIT 1");
			list($fecha_de_reingreso) = $this->conn->fetchRow($result);

			if(isset($fecha_de_reingreso))
			{
				$reingreso = date_create($fecha_de_reingreso);
				$interval = date_diff(date_create($year.'-01-01'),$reingreso);
				$numero_de_dias_de_baja = $interval->format('%r%a');
			}
			else
				$numero_de_dias_de_baja = 0;

			return $numero_de_dias_de_baja;
		}

		public function calculate_salario_diario($_trabajador,$base)
		{
			date_default_timezone_set('America/Mexico_City');
			$fecha_de_pago = date_create($this->Fecha_de_pago);
			$year = date('Y', $fecha_de_pago->format('U'));

			if($base == 'Salario mínimo')
			{
				//cheching if this "Empresa" has any "Sucursal"
				$empresa = $this->get_empresa();
				$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");

				if($this->conn->num_rows($result) > 0)
				{
					$this->conn->freeResult($result);
					$result = $this->conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '$_trabajador' AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha_de_pago}', Trabajador_Sucursal.Fecha_de_ingreso) >= 0 ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");
				}
				else
				{
					$this->conn->freeResult($result);
					$result = $this->conn->query("SELECT Zona_geografica FROM Empresa WHERE RFC = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
				}

				list($zona_geografica) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Salario_minimo.$zona_geografica FROM Trabajador_Salario_minimo LEFT JOIN Salario_minimo ON Trabajador_Salario_minimo.Salario_minimo = Salario_minimo.Codigo WHERE Trabajador_Salario_minimo.Trabajador = '$_trabajador' AND Trabajador_Salario_minimo.Servicio = '{$this->Servicio}' AND Trabajador_Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Ano = '$year' AND DATEDIFF('{$this->Fecha_de_pago}', Trabajador_Salario_minimo.Fecha) > 0 ORDER BY Trabajador_Salario_minimo.Fecha DESC LIMIT 1");
				list($salario_diario) = $this->conn->fetchRow($result);
			}
			else
			{
				$result = $this->conn->query("SELECT Cantidad FROM Salario_diario WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha_de_pago}',Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
				list($salario_diario) = $this->conn->fetchRow($result);
			}

			if(isset($salario_diario))
				return $salario_diario;
			else
				return 0;

		}

		private function calculate_gratificacion_adicional($_trabajador)
		{
			date_default_timezone_set('America/Mexico_City');
			$gratificacion_adicional = 0;

			for($i=0; $i<count($this->trabajador); $i++)

				if($this->trabajador[$i] == $_trabajador)
					break;

			if(isset($this->gratificacion_adicional[$i]))
				$cantidad = $this->gratificacion_adicional[$i];
			else
				$cantidad = 0;

			$gratificacion_adicional += $cantidad;
			//getting "base para el cálculo de la nómna"
			$result = $this->conn->query("SELECT Base FROM Base WHERE Trabajador = '$_trabajador' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha_de_pago}', Fecha) >= 0 LIMIT 1");
			list($base_nomina) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			//getting "base para el cálculo de las prestaciones"
			$result = $this->conn->query("SELECT Base_de_prestaciones FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($base_prestaciones) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($base_prestaciones == 'Salario diario' && $base_nomina == 'Salario mínimo')
			{
				//Número de días previos al ingreso
				$numero_de_dias_previos_al_ingreso = $this->calculate_numero_de_dias_previos_al_ingreso($_trabajador);
				//Número de días de baja
				$numero_de_dias_de_baja = $this->calculate_numero_de_dias_de_baja($_trabajador);
				//Número de días de aguinaldo
				$result = $this->conn->query("SELECT Dias_de_aguinaldo FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($dias_de_aguinaldo) = $this->conn->fetchRow($result);
				$numero_de_dias_de_aguinaldo = (365 - $numero_de_dias_de_baja - ($numero_de_dias_de_baja > 0 ? 0 : $numero_de_dias_previos_al_ingreso)) * $dias_de_aguinaldo / 365;
				//Aguinaldo ordinario(Salario mínimo)
				$salario_diario = $this->calculate_salario_diario($_trabajador,'Salario mínimo');
				$aguinaldo_ordinario_sm = $numero_de_dias_de_aguinaldo * $salario_diario;
				//Aguinaldo ordinario(Salario diario)
				$salario_diario = $this->calculate_salario_diario($_trabajador,'Salario diario');
				$aguinaldo_ordinario_sd = $numero_de_dias_de_aguinaldo * $salario_diario;
				$diff = $aguinaldo_ordinario_sd - $aguinaldo_ordinario_sm;
				$gratificacion_adicional += $diff;
			}

			return round($gratificacion_adicional,2);
		}

		private function calculate_pago_neto($_trabajador)
		{
			$pago_neto = 0;

			for($i=0; $i<count($this->trabajador); $i++)

				if($this->trabajador[$i] == $_trabajador)
					break;

			$cantidad = $this->pago_neto[$i];
			$pago_neto += $cantidad;
			return $pago_neto;
		}

		private function calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo)
		{
			$year = substr($this->Fecha_de_pago, 0, 4);

			if($numero_de_dias_del_periodo < 7)
			{
				$base_ISR /= $numero_de_dias_del_periodo;
				$result = $this->conn->query("SELECT Limite_inferior FROM ISR_diario WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
				list($limite_inferior) = $this->conn->fetchRow($result);
				$limite_inferior *= $numero_de_dias_del_periodo;
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

		private function calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo)
		{
			$year = substr($this->Fecha_de_pago, 0, 4);

			if($numero_de_dias_del_periodo < 7)
			{
				$base_ISR /= $numero_de_dias_del_periodo;
				$result = $this->conn->query("SELECT Porcentaje FROM ISR_diario WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
			}
			elseif($numero_de_dias_del_periodo == 7)
				$result = $this->conn->query("SELECT Porcentaje FROM ISR_semanal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
			elseif($numero_de_dias_del_periodo <= 16)
				$result = $this->conn->query("SELECT Porcentaje FROM ISR_quincenal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
			else
				$result = $this->conn->query("SELECT Porcentaje FROM ISR_mensual WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");

			list($porcentaje_sobre_el_exedente_del_limite_inferior) = $this->conn->fetchRow($result);

			if(isset($porcentaje_sobre_el_exedente_del_limite_inferior))
				return $porcentaje_sobre_el_exedente_del_limite_inferior;
			else
				return 0;

		}

		private function calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo)
		{
			$year = substr($this->Fecha_de_pago, 0, 4);

			if($numero_de_dias_del_periodo < 7)
			{
				$base_ISR /= $numero_de_dias_del_periodo;
				$result = $this->conn->query("SELECT Cuota_fija FROM ISR_diario WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL) AND Ano = '$year'");
				list($cuota_fija) = $this->conn->fetchRow($result);
				$cuota_fija *= $numero_de_dias_del_periodo;
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

		private function calculate_subsidio($base_ISR,$numero_de_dias_del_periodo)
		{
			$year = substr($this->Fecha_de_pago, 0, 4);

			if($numero_de_dias_del_periodo < 7)
			{
				$base_ISR = round($base_ISR / $numero_de_dias_del_periodo, 2);
				$result = $this->conn->query("SELECT Subsidio FROM Credito_al_salario_diario WHERE Desde_ingresos_de <= $base_ISR AND (Hasta_ingresos_de >= $base_ISR OR Hasta_ingresos_de IS NULL) AND Ano = '$year'");
				list($subsidio) = $this->conn->fetchRow($result);
				$subsidio *= $numero_de_dias_del_periodo;
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

		public function calculate_resumen()//this function is called when storing the resume at db, so isr and aguinaldo are already stored
		{
			date_default_timezone_set('America/Mexico_City');
			$fecha_de_pago = date_create($this->Fecha_de_pago);
			$year = date('Y', $fecha_de_pago->format('U'));
			$result = $this->conn->query("SELECT Cobrar_IVA FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calcular_iva) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Cuotas_IMSS FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calculate_cuotas_imss) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Porcentaje_de_honorarios FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($honorarios) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			//aguinaldo ordinario
			$aguinaldo_ordinario = $this->total_col('Aguinaldo_ordinario','aguinaldo_asalariados');
			//gratificacion adicional
			$gratificacion_adicional = $this->total_col('Gratificacion_adicional','aguinaldo_asalariados');
			//total de percepciones
			$total_de_percepciones = $this->total_col('Total_de_percepciones','aguinaldo_asalariados');
			//isr
			$isr = $this->total_col('ISR','aguinaldo_asalariados');
			//total de deducciones
			$total_de_deducciones = $isr;
			//saldo
			$saldo = $this->total_col('Saldo','aguinaldo_asalariados');
			//diferencia
			$result = $this->conn->query("SELECT Aguinaldo FROM Resumen LEFT JOIN Nomina ON Resumen.Nomina = Nomina.id WHERE Nomina.Servicio = '{$this->Servicio}' AND Resumen.Cuenta = '{$_SESSION['cuenta']}' AND Nomina.Cuenta = '{$_SESSION['cuenta']}' AND YEAR(Nomina.Limite_superior_del_periodo) = '$year'");
			$aguinaldo_pagado = $this->ceros();

			while(list($cantidad) = $this->conn->fetchRow($result))
				$aguinaldo_pagado = $this->_sum(array($aguinaldo_pagado, $cantidad));

			$diferencia = $this->_sub($saldo,$aguinaldo_pagado);
			$result = $this->conn->query("SELECT Incluir_contribuciones FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($incluir_contribuciones) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($incluir_contribuciones == 'true')
				$honorarios_asalariados = $this->calculate_honorarios($diferencia, $isr, $honorarios);
			else
				$honorarios_asalariados = $this->calculate_honorarios($diferencia, $this->ceros(), $honorarios);

			$subtotal_a_facturar = $this->_sum(array($diferencia, $isr, $honorarios_asalariados));
			$iva = $this->calculate_iva($subtotal_a_facturar,$calcular_iva);
			$total_a_facturar = $this->_sum(array($subtotal_a_facturar, $iva));
			$this->Resumen_aguinaldo = $aguinaldo_ordinario . ',' . $gratificacion_adicional . ',' . $total_de_percepciones . ',' . $isr . ',' . $saldo . ',' . $aguinaldo_pagado . ',' . $diferencia . ',' . $honorarios_asalariados . ',' . $subtotal_a_facturar . ',' . $iva . ',' . $total_a_facturar;
		}

		private function total_col($concept,$table)
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
			$result = $this->conn->query("SELECT Trabajador FROM $table WHERE Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while(list($trabajador) = $this->conn->fetchRow($result))
			{
				$result1 = $this->conn->query("SELECT Nombre FROM Trabajador_Sucursal WHERE Trabajador = '$trabajador' AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($sucursal) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);
				$result1 = $this->conn->query("SELECT $concept FROM $table WHERE Trabajador = '$trabajador' AND Aguinaldo = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($val) = $this->conn->fetchRow($result1);
				$this->conn->freeResult($result1);
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
						$txt .= number_format($value,2,'.','');
					else
						$txt .= number_format($value,2,'.','') . '/';

					$i++;
				}

			else
				$txt .= number_format($totals[0],2,'.','');

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
					$txt .= number_format($totals[$i],2,'.','');
				else
					$txt .= number_format($totals[$i],2,'.','') . '/';

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
					$txt .= number_format($_values1[$i] - $_values2[$i],2,'.','');
				else
					$txt .= number_format($_values1[$i] - $_values2[$i],2,'.','') . '/';

			return $txt;
		}

		private function calculate_honorarios($saldo, $total_de_impuestos, $honorarios)
		{
			$value = $this->_sum(array($saldo, $total_de_impuestos));
			$values = explode('/',$value);

			foreach($values as $key => $val)
				$values[$key] = number_format($values[$key] * $honorarios / 100,2,'.','');

			$txt = implode('/',$values);
			return $txt;
		}

		private function calculate_iva($subtotal_a_facturar,$calcular_iva)
		{
			$values = explode('/',$subtotal_a_facturar);

			foreach($values as $key => $val)
			{
				$values[$key] = $values[$key] * ($calcular_iva == 'true' ? 0.16 : 0);
				$values[$key] = number_format($values[$key],2,'.','');
			}

			$txt = implode('/',$values);
			return $txt;
		}

		private function calculate_salario_minimo($_trabajador)
		{
			date_default_timezone_set('America/Mexico_City');
			//cheching if this "Empresa" has any "Sucursal"
			$empresa = $this->get_empresa();
			$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");

			if($this->conn->num_rows($result) > 0)
			{
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '$_trabajador' AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha_de_pago}', Trabajador_Sucursal.Fecha_de_ingreso) >= 0 ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");
			}
			else
			{
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Zona_geografica FROM Empresa WHERE RFC = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

			list($zona) = $this->conn->fetchRow($result);
			$fecha_de_pago = date_create($this->Fecha_de_pago);
			$year = date('Y', $fecha_de_pago->format('U'));
			$result = $this->conn->query("SELECT $zona FROM Salario_minimo WHERE Codigo = '1' AND Ano = '$year' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($salario_minimo) = $this->conn->fetchRow($result);
			return $salario_minimo;
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

			//getting the row columns
			$columns = explode('<td>',$row);
			//getting the right column
			$column = $columns[$column_number];
			//gettin strings from column
			$values = explode('</td>',$column);
			//getting the value
			$value = $values[0];
			return $value;
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

		private function dif_ga($_trabajador,$aguinaldo_ordinario,$numero_de_dias_del_periodo,$pago_neto,$A,$B,$_base_ISR,$ga)
		{
			$base_ISR = $_base_ISR;
			$base_ISR += $ga;
			$li = $this->calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
			$eli = $base_ISR - $li;
			$pseli = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
			$im = $eli * $pseli;
			$cf = $this->calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo);
			$id = $im + $cf;
			$se = $this->calculate_subsidio($base_ISR,$numero_de_dias_del_periodo);
			$isr = $id - $se;
			$subsidio_al_empleo = 0;
			$percepciones = $A + $ga;
			$deducciones = $isr;//$B + $isr;
			$saldo = $percepciones - $deducciones;
			$dif_ga = $saldo - $pago_neto;
			return $dif_ga;
		}

		public function compensate_gratificacion_adicional($_trabajador,$aguinaldo_ordinario,$pago_neto)
		{
			$salario_minimo = $this->calculate_salario_minimo($_trabajador);

			if($aguinaldo_ordinario <= ($salario_minimo * 30))
				$_base_ISR = 0;
			else
				$_base_ISR = $aguinaldo_ordinario - $salario_minimo * 30;

			$result = $this->conn->query("SELECT Periodicidad_de_la_nomina FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($periodicidad_de_la_nomina) = $this->conn->fetchRow($result);
			$numero_de_dias_del_periodo = $periodicidad_de_la_nomina == 'Semanal' ? 7 : 15;
			$li = $this->calculate_limite_inferior($_base_ISR,$numero_de_dias_del_periodo);
			$eli = $_base_ISR - $li;
			$pseli = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($_base_ISR,$numero_de_dias_del_periodo);
			$im = $eli * $pseli;
			$cf = $this->calculate_cuota_fija($_base_ISR,$numero_de_dias_del_periodo);
			$id = $im + $cf;
			$se = $this->calculate_subsidio($_base_ISR,$numero_de_dias_del_periodo);
			$A = $aguinaldo_ordinario;
			$B = ($id > $se) ? ($id - $se) : 0;
			$ga = 0;
			$i = 0;

			do
			{
				$base_ISR = $_base_ISR + $ga;
				$li = $this->calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
				$eli = $base_ISR - $li;
				$pseli = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
				$im = $eli * $pseli;
				$cf = $this->calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo);
				$id = $im + $cf;
				$se = $this->calculate_subsidio($base_ISR,$numero_de_dias_del_periodo);

				if($id > $se)
				{
					$isr = $id - $se;
					$subsidio_al_empleo = 0;
					$percepciones = $A + $ga;
					$deducciones = $isr;//$B + $isr;
					$saldo = $percepciones - $deducciones;
					$dif_ga = $pago_neto - $saldo;

					if($dif_ga < 0)
					{//Bisection method
						$j = 0;
						$error = 0.001;
						$b = $ga;

						while($j < 5000)
						{
							$m = ($a + $b) / 2;

							if( $this->dif_ga($_trabajador,$aguinaldo_ordinario,$numero_de_dias_del_periodo,$pago_neto,$A,$B,$_base_ISR,$m) == 0 || ($b - $a) / 2 < $error)
							{
								$ga = round($m,4);
								$base_ISR = $_base_ISR + $ga;
								$li = $this->calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
								$eli = $base_ISR - $li;
								$pseli = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
								$im = $eli * $pseli;
								$cf = $this->calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo);
								$id = $im + $cf;
								$se = $this->calculate_subsidio($base_ISR,$numero_de_dias_del_periodo);
								$isr = $id - $se;
								$subsidio_al_empleo = 0;
								$percepciones = $A + $ga;
								$deducciones = $isr;//$B + $isr;
								$saldo = $percepciones - $deducciones;
								$dif_ga = $saldo - $pago_neto;
								break;
							}
							else
							{

								if($this->dif_ga($_trabajador,$aguinaldo_ordinario,$numero_de_dias_del_periodo,$pago_neto,$A,$B,$_base_ISR,$a) * $this->dif_ga($_trabajador,$aguinaldo_ordinario,$numero_de_dias_del_periodo,$pago_neto,$A,$B,$_base_ISR,$m) < 0)
									$b = round($m,4);
								else
									$a = round($m,4);

							}

							$dif_ga = $this->dif_ga($_trabajador,$aguinaldo_ordinario,$numero_de_dias_del_periodo,$pago_neto,$A,$B,$_base_ISR,$m);
							$j++;
						}

						break;
					}
					else
					{
						$a = $ga;
						$ga += 50;
					}

				}
				else
				{
					$isr = 0;
					$subsidio_al_empleo = $se - $id;
					$percepciones = $A + $ga;
					$deducciones = $isr;//$B + $isr;
					$saldo = $percepciones - $deducciones;
					$dif_ga = $pago_neto - $saldo;
					$ga += $dif_ga;
					$a = $ga;

					if($dif_ga <= 0.001)
						break;
				}

				$i++;
			}while($i < 5000);

			return array($ga,$base_ISR,$li,$eli,$pseli,$im,$cf,$id,$se,$percepciones,$deducciones,$saldo,$dif_ga);
		}

		public function draw($act)//if act == 'EDIT' or act == 'ADD' some fields can be edited and the form is submitted. If act == 'DRAW' no fields can be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";//show at tabs.js
			echo "<div onclick = \"show('ISRaguinaldo_fieldset',this)\" class = \"isr_aguinaldo_tab\" style=\"color:#777\">ISR aguinaldo</div>";
			echo "<div onclick = \"show('Aguinaldo_asalariados_fieldset',this)\" class = \"aguinaldo_asalariados_tab\" style=\"color:#777\">Aguinaldo asalariados</div>";
			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>{$this->id}</textarea>";

					//getting administradora
					if(isset($this->Servicio))
					{
						$result = $this->conn->query("SELECT Empresa.Nombre FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero LEFT JOIN Empresa ON (Registro_patronal.Empresa = Empresa.RFC OR Registro_patronal.Empresa_sucursal = Empresa.RFC) WHERE Servicio_Registro_patronal.Servicio = '{$this->Servicio}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha_de_pago}', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
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

					//getting honorarios
					if(isset($this->Servicio))
					{
						$result = $this->conn->query("SELECT Porcentaje_de_honorarios FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($honorarios) = $this->conn->fetchRow($result);
					}
					else
						$honorarios = '';

					echo "<textarea name = \"Empresa\" class=\"hidden_textarea\" readonly=true>$empresa</textarea>";
					echo "<textarea name = \"Honorarios\" class=\"hidden_textarea\" readonly=true>$honorarios</textarea>";
					echo "<textarea name = \"Resumen_aguinaldo\" class=\"hidden_textarea\" readonly=true>{$this->Resumen_aguinaldo}</textarea>";
					echo "<label class = \"fecha_de_pago_label\">Fecha de pago</label>";
					echo "<textarea class = \"fecha_de_pago_textarea\" name = \"Fecha_de_pago\" title = \"Fecha de pago\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Fecha_de_pago}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";//show_calendar() at calendar.js

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
						$result = $this->conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Empresa.Nombre, Servicio_Registro_patronal.Registro_patronal FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio_Empresa.Servicio = Servicio_Registro_patronal.Servicio WHERE  Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Servicio = '{$this->Servicio}'");
						list($id,$periodicidad,$empresa,$registro) = $this->conn->fetchRow($result);
						echo "<textarea class = \"servicio_textarea\" name = \"Servicio\" title = \"Servicio\">$id/$periodicidad/$empresa/$registro</textarea>";
					}

					if($act != 'DRAW')
					{
						echo "<label class = \"calcular_label\">Calcular</label>";
						echo "<img class = 'calculate_button' onclick = \"get_workers_aguinaldo('$act',this)\" />";//function get_workers_aguinaldo at aguinaldo.js
					}

					if($act == 'DRAW')
					{
						echo "<label class = \"resumen_label\">Resumen</label>";
						echo "<img class = 'resumen_button' onclick = \"view_aguinaldo(this)\"/>";//function view_aguinaldo at aguinaldo.js
					}

				echo "</fieldset>";
				echo "<fieldset class =  \"ISRaguinaldo_fieldset\" style = \"visibility:hidden\"\>";

					if(isset($this->ISRaguinaldo))
						echo $this->ISRaguinaldo;

					if($act == 'DRAW')
						echo "<img class = 'view_button' onclick = \"view_aguinaldo(this)\"/>";//function view_aguinaldo at aguinaldo.js

				echo "</fieldset>";
				echo "<fieldset class =  \"Aguinaldo_asalariados_fieldset\" style = \"visibility:hidden\"\>";

					if(isset($this->aguinaldo_asalariados))
						echo $this->aguinaldo_asalariados;

					if($act == 'DRAW')
					{
						echo "<img class = 'view_button' onclick = \"view_aguinaldo(this)\"/>";
						echo "<img title = 'Recibos' class = 'recibos_button' onclick = \"recibos_aguinaldo(this)\"/>";
					}

				echo "</fieldset>";

				if($act == 'EDIT' || $act == 'ADD')
					echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Aguinaldo')\" />";//_submit() at common_entities.js
			
			echo "</form>";
		}

	}
?>
