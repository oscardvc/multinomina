<?php
	include_once('connection.php');
	include_once('actividad.php');

//Class Servicio definition

	class Servicio
	{
		//class properties
		//data
		private $Prestamo;
		private $Limite_del_prestamo;
		private $Porcentaje_de_honorarios;
		private $Antiguedad_prestaciones;
		private $Antiguedad_IMSS;
		private $Fecha_de_inicio = 'AAAA-MM-DD';
		private $Porcentaje_de_comision;
		private $Periodicidad_de_la_nomina;
		private $id;
		private $Trabajador;
		private $Vacaciones;
		private $Prima_vacacional;
		private $Prima_de_antiguedad;
		private $Aguinaldo;
		private $Cuotas_IMSS;
		private $_5_INFONAVIT;
		private $Estado;
		private $Dias_de_aguinaldo;
		private $Base_de_prestaciones;
		private $Cobrar_IVA;
		private $Cobrar_impuesto_sobre_nomina;
		private $Quince_anos;
		private $Incluir_contribuciones;
		private $Honorarios_pendientes;
		private $Alimentacion;
		private $Habitacion;
		private $dcipn;//descontar cuotas imss patronales en nómina
		private $dcipla;//descontar cuotas imss del pago líquido para asalariados
		private $dppla;//descontar prestaciones del pago líquido para asalariados
		private $dgapla;//descontar gestión administrativa del pago líquido para asalariados
		private $dpn;//descontar prestaciones en nómina
		private $dgan;//descontar gestión administrativa en nómina
		private $cgaa;//cobrar gestión administrativa a asimilables
		private $ivash;//iva solo por honorarios
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
			$result = $this->conn->query("SELECT id FROM Servicio WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY id DESC LIMIT 1");
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

		public function setFromBrowser()//sets properties from superglobal $_POST
		{

			foreach($this as $key => $value)

				if(isset($_POST["$key"]))
				{

					if($key == 'Trabajador')
					{
						$len = count($_POST['Trabajador']);

						for($i=0; $i<$len; $i++)
						{

							if(!isset($this->Trabajador))
								$this->Trabajador = trim($_POST['Trabajador'][$i]);
							else
								$this->Trabajador .= ',' . trim($_POST['Trabajador'][$i]);

						}

					}
					elseif($key == 'Cobrar_IVA' || $key == 'Cobrar_impuesto_sobre_nomina' || $key == 'Quince_anos' || $key == 'Incluir_contribuciones' || $key == 'Honorarios_pendientes' || $key == 'Alimentacion' || $key == 'Habitacion' || $key == 'Habitacion' || $key == 'dcipn' || $key == 'dcipla' || $key == 'dppla' || $key == 'dgapla' || $key == 'dpn' || $key == 'dgan' || $key == 'cgaa' || $key == 'ivash')
						$this->$key = 'true';
					else
						$this->$key = trim($_POST["$key"]);

				}
				elseif($key == 'Cobrar_IVA' || $key == 'Cobrar_impuesto_sobre_nomina' || $key == 'Quince_anos' || $key == 'Incluir_contribuciones' || $key == 'Honorarios_pendientes' || $key == 'Alimentacion' || $key == 'Habitacion' || $key == 'dcipn' || $key == 'dcipla' || $key == 'dppla' || $key == 'dgapla' || $key == 'dpn' || $key == 'dgan' || $key == 'cgaa' || $key == 'ivash')
					$this->$key = 'false';


		}

		public function showProperties()//prints properties values
		{

			foreach($this as $key => $value)

				if($key != 'conn')
					echo "$key = $value <br />";

		}

		public function setFromDb()//sets properties from data base, but $id has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Servicio WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

			$result = $this->conn->query("SELECT Trabajador FROM Servicio_Trabajador WHERE Servicio = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)
				{

					if(!isset($this->Trabajador))
						$this->Trabajador = $value;
					else
						$this->Trabajador .= ',' . $value;

				}

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. if $update is true it updates all database registers (professedly one) with $this' id
		{
			$actividad = new Actividad();
			$actividad->set('Dato','Servicio');

			if($update == 'false')
			{
				$this->setID();
				$this->conn->query("INSERT INTO Servicio (id, Cuenta) VALUES ('{$this->id}', '{$_SESSION['cuenta']}')");
				$actividad->set('Identificadores',"{$this->id}/{$this->Periodicidad_de_la_nomina}");
				$actividad->set('Operacion','Nuevo');
				$actividad->dbStore();

			}
			else
			{
				$servicio = $this->notation();
				$result = $this->conn->query("SELECT Trabajador FROM Servicio_Trabajador WHERE Servicio = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
				$nw = $this->conn->num_rows($result);
				$this->conn->freeResult($result);
				$trabajadores = explode(',',$this->Trabajador);
				$len = count($trabajadores);
				$actividad->set('Identificadores',"$servicio Trabajadores relacionados anteriormente: $nw Trabajadores relacionados actualmente: " . ($trabajadores[0] != '' ? $len : $len - 1));
				$actividad->set('Operacion','Editar');
				$actividad->dbStore();
			}

			foreach($this as $key => $value)

				if(isset($this->$key))
				{

					if($key != 'conn' && $key != 'id')
					{

						if($key == 'Trabajador')
						{
/*							$trabajadores = explode(',',$value);
							$len = count($trabajadores);

							for($i=0; $i<$len; $i++)
								$this->conn->query("INSERT INTO Servicio_Trabajador(Servicio, Trabajador, Cuenta) VALUES('{$this->id}', '{$trabajadores[$i]}', '{$_SESSION['cuenta']}')");

							$this->updateTrabajadorRelations($trabajadores);
*/						}
						else
							$this->conn->query("UPDATE Servicio SET $key = '$value' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

					}

				}
//				elseif($key == 'Trabajador')
//					$this->updateTrabajadorRelations(array());

			return true;
		}

		public function dbDelete()//delete this entity from database but id has to be set before
		{

			if(isset($this->id))
			{
				$actividad = new Actividad();
				$actividad->set('Operacion','Eliminar');
				$actividad->set('Dato','Servicio');
				$servicio = $this->notation();
				$result = $this->conn->query("SELECT Trabajador FROM Servicio_Trabajador WHERE Servicio = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
				$nw = $this->conn->num_rows($result);
				$this->conn->freeResult($result);
				$trabajadores = explode(',',$this->Trabajador);
				$len = count($trabajadores);
				$actividad->set('Identificadores',"$servicio Trabajadores relacionados anteriormente: $nw Trabajadores relacionados actualmente: " . ($trabajadores[0] != '' ? $len : $len - 1));
				$actividad->dbStore();
				$this->conn->query("DELETE FROM Servicio WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

		}

		public function notation()//Returns the Service notation id/periodicidad/empresa/registro
		{
			$result = $this->conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Empresa.Nombre, Servicio_Registro_patronal.Registro_patronal FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC LEFT JOIN Servicio_Registro_patronal ON Servicio_Empresa.Servicio = Servicio_Registro_patronal.Servicio WHERE Servicio.id = '{$this->id}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC, Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
			list($id,$periodicidad,$empresa,$registro) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			return "$id/$periodicidad/$empresa/$registro";
		}

		public function draw($act)//draws $this Servicio. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";
			echo "<div onclick = \"show('Configuraciones_adicionales_fieldset',this)\" class = \"configuraciones_adicionales_tab\">Configuraciones adicionales</div>";

			if($act != 'ADD')
			{
				echo "<div onclick = \"show('Empresa_fieldset',this)\" class = \"empresa_tab\">Empresa</div>";
				echo "<div onclick = \"show('Registro_patronal_fieldset',this)\" class = \"registro_patronal_tab\">Registro patronal</div>";
				echo "<div onclick = \"show('Trabajadores_fieldset',this)\" class = \"trabajadores_tab\">Trabajadores</div>";
				echo "<div onclick = \"show('Servicios_adicionales_fieldset',this)\" class = \"servicios_adicionales_tab\">Servicios adicionales</div>";
			}

			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>{$this->id}</textarea>";
					echo "<label class = \"antiguedad_prestaciones_label\">Antigüedad para prestaciones</label>";

					if ($act != 'DRAW')
					{
						echo'<select title = "Antigüedad para prestaciones" class = "antiguedad_prestaciones_select" name = "Antiguedad_prestaciones" required=true >';

						if(isset($this->Antiguedad_prestaciones))
						{

							if($this->Antiguedad_prestaciones == 'Cliente')
								echo '<option selected>Cliente</option>';
							else
								echo '<option>Cliente</option>';

							if($this->Antiguedad_prestaciones == 'Servicio')
								echo '<option selected>Servicio</option>';
							else
								echo '<option>Servicio</option>';

						}
						else
						{
							echo '<option>Cliente</option>';
							echo '<option>Servicio</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"antiguedad_prestaciones_textarea\" name = \"Antiguedad_prestaciones\" title = \"Antigüedad para prestaciones\" readonly=true>{$this->Antiguedad_prestaciones}</textarea>";

					echo "<label class = \"antiguedad_imss_label\">Antigüedad para IMSS</label>";

					if ($act != 'DRAW')
					{
						echo'<select title = "Antigüedad para IMSS" class = "antiguedad_imss_select" name = "Antiguedad_IMSS" required=true >';

						if(isset($this->Antiguedad_IMSS))
						{

							if($this->Antiguedad_IMSS == 'Cliente')
								echo '<option selected>Cliente</option>';
							else
								echo '<option>Cliente</option>';

							if($this->Antiguedad_IMSS == 'Servicio')
								echo '<option selected>Servicio</option>';
							else
								echo '<option>Servicio</option>';

						}
						else
						{
							echo '<option>Cliente</option>';
							echo '<option>Servicio</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"antiguedad_imss_textarea\" name = \"Antiguedad_IMSS\" title = \"Antigüedad para IMSS\" readonly=true>{$this->Antiguedad_IMSS}</textarea>";

					echo "<label class = \"porcentaje_de_honorarios_label\">Porcentaje de honorarios</label>";
					echo "<textarea class = \"porcentaje_de_honorarios_textarea\" name = \"Porcentaje_de_honorarios\" title = \"Porcentaje de honorarios\"". ($act == 'EDIT' || $act == 'ADD'?" required=true >":"readonly=true>")."{$this->Porcentaje_de_honorarios}</textarea>";
					echo "<label class = \"fecha_de_inicio_label\">Fecha de inicio</label>";
					echo "<textarea class=\"fecha_de_inicio_textarea\" name = \"Fecha_de_inicio\" title = \"Fecha de inicio\"". ($act == 'EDIT' || $act == 'ADD'? "required=true>":"readonly=true>")."{$this->Fecha_de_inicio}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";//show_calendar() at calendar.js

					echo "<label class = \"porcentaje_de_comision_label\">Porcentaje de comisión</label>";
					echo "<textarea class = \"porcentaje_de_comision_textarea\" name = \"Porcentaje_de_comision\" title = \"Porcentaje_de_comision\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>") . "{$this->Porcentaje_de_comision}</textarea>";
					echo "<label class = \"vacaciones_label\">Vacaciones</label>";

					if($act != 'DRAW')
					{
						echo '<select title = "Vacaciones" class = "vacaciones_select" name = "Vacaciones" required=true >';

							if(isset($this->Vacaciones))
							{

								if($this->Vacaciones == 'Cobrado')
									echo '<option selected>Cobrado</option>';
								else
									echo '<option>Cobrado</option>';

								if($this->Vacaciones == 'Administrado')
									echo '<option selected>Administrado</option>';
								else
									echo '<option>Administrado</option>';

								if($this->Vacaciones == 'No administrado')
									echo '<option selected>No administrado</option>';
								else
									echo '<option>No administrado</option>';

							}
							else
							{
								echo '<option>Cobrado</option>';
								echo '<option>Administrado</option>';
								echo '<option>No administrado</option>';
							}

						echo '</select>';
					}
					else
						echo "<textarea class = \"vacaciones_textarea\" name = \"Vacaciones\" title = \"Vacaciones\" readonly=true>{$this->Vacaciones}</textarea>";

					echo "<label class = \"prima_vacacional_label\">Prima vacacional</label>";
				
					if($act != 'DRAW')
					{
						echo '<select title = "Prima vacacional" class = "prima_vacacional_select" name = "Prima_vacacional" required=true >';
							
						if(isset($this->Prima_vacacional))
						{

							if($this->Prima_vacacional == 'Cobrado')
								echo '<option selected>Cobrado</option>';
							else
								echo '<option>Cobrado</option>';

							if($this->Prima_vacacional == 'Administrado')
								echo '<option selected>Administrado</option>';
							else
								echo '<option>Administrado</option>';

							if($this->Prima_vacacional == 'No administrado')
								echo '<option selected>No administrado</option>';
							else
								echo '<option>No administrado</option>';

						}
						else
						{
							echo '<option>Cobrado</option>';
							echo '<option>Administrado</option>';
							echo '<option>No administrado</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"prima_vacacional_textarea\" name = \"Prima_vacacional\" title = \"Prima vacacional\" readonly=true>{$this->Prima_vacacional}</textarea>";

					echo "<label class = \"prima_de_antiguedad_label\">Prima de antigüedad</label>";

					if($act != 'DRAW')
					{
						echo '<select title = "Prima de antigüedad" class = "prima_de_antiguedad_select" name = "Prima_de_antiguedad" required=true >';

						if(isset($this->Prima_de_antiguedad))
						{

							if($this->Prima_de_antiguedad == 'Cobrado')
								echo '<option selected>Cobrado</option>';
							else
								echo '<option>Cobrado</option>';

							if($this->Prima_de_antiguedad == 'Administrado')
								echo '<option selected>Administrado</option>';
							else
								echo '<option>Administrado</option>';

							if($this->Prima_de_antiguedad == 'No administrado')
								echo '<option selected>No administrado</option>';
							else
								echo '<option>No administrado</option>';

						}
						else
						{
							echo '<option>Cobrado</option>';
							echo '<option>Administrado</option>';
							echo '<option>No administrado</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"prima_de_antiguedad_textarea\" name = \"Prima_de_antiguedad\" title = \"Prima de antigüedad\" readonly=true>{$this->Prima_de_antiguedad}</textarea>";

					echo "<label class = \"aguinaldo_label\">Aguinaldo</label>";
					
					if($act != 'DRAW')
					{
						echo '<select title = "Aguinaldo" class = "aguinaldo_select" name = "Aguinaldo" required=true >';

						if(isset($this->Aguinaldo))
						{

							if($this->Aguinaldo == 'Cobrado')
								echo '<option selected>Cobrado</option>';
							else
								echo '<option>Cobrado</option>';

							if($this->Aguinaldo == 'Administrado')
								echo '<option selected>Administrado</option>';
							else
								echo '<option>Administrado</option>';

							if($this->Aguinaldo == 'No administrado')
								echo '<option selected>No administrado</option>';
							else
								echo '<option>No administrado</option>';

						}
						else
						{
							echo '<option>Cobrado</option>';
							echo '<option>Administrado</option>';
							echo '<option>No administrado</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"aguinaldo_textarea\" name = \"Aguinaldo\" title = \"Aguinaldo\" readonly=true>{$this->Aguinaldo}</textarea>";

					echo "<label class = \"cuotas_imss_label\">Cuotas IMSS</label>";
					
					if($act != 'DRAW')
					{
						echo '<select title = "Cuotas IMSS" class = "cuotas_imss_select" name = "Cuotas_IMSS" required=true >';

						if(isset($this->Cuotas_IMSS))
						{

							if($this->Cuotas_IMSS == 'Cobrado')
								echo '<option selected>Cobrado</option>';
							else
								echo '<option>Cobrado</option>';

							if($this->Cuotas_IMSS == 'Administrado')
								echo '<option selected>Administrado</option>';
							else
								echo '<option>Administrado</option>';

							if($this->Cuotas_IMSS == 'No administrado')
								echo '<option selected>No administrado</option>';
							else
								echo '<option>No administrado</option>';

						}
						else
						{
							echo '<option>Cobrado</option>';
							echo '<option>Administrado</option>';
							echo '<option>No administrado</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"cuotas_imss_textarea\" name = \"Cuotas_IMSS\" title = \"Cuotas IMSS\" readonly=true>{$this->Cuotas_IMSS}</textarea>";

					echo "<label class = \"_5_infonavit_label\">5% INFONAVIT</label>";
					
					if($act != 'DRAW')
					{
						echo '<select title = "5% INFONAVIT" class = "_5_infonavit_select" name = "_5_INFONAVIT" required=true >';

						if(isset($this->_5_INFONAVIT))
						{

							if($this->_5_INFONAVIT == 'Cobrado')
								echo '<option selected>Cobrado</option>';
							else
								echo '<option>Cobrado</option>';

							if($this->_5_INFONAVIT == 'Administrado')
								echo '<option selected>Administrado</option>';
							else
								echo '<option>Administrado</option>';

						}
						else
						{
							echo '<option>Cobrado</option>';
							echo '<option>Administrado</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"_5_infonavit_textarea\" name = \"_5_INFONAVIT\" title = \"5% INFONAVIT\" readonly=true>{$this->_5_INFONAVIT}</textarea>";

					echo "<label class = \"estado_label\">Estado</label>";
					
					if($act != 'DRAW')
					{
						echo '<select title = "Estado" class = "estado_select" name = "Estado" required=true>';

						if(isset($this->Estado))
						{

							if($this->Estado == 'Activo')
								echo '<option selected>Activo</option>';
							else
								echo '<option>Activo</option>';

							if($this->Estado == 'Inactivo')
								echo '<option selected>Inactivo</option>';
							else
								echo '<option>Inactivo</option>';

						}
						else
						{
							echo '<option>Activo</option>';
							echo '<option>Inactivo</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"estado_textarea\" name = \"Estado\" title = \"Estado\" readonly=true>{$this->Estado}</textarea>";

					echo "<label class = \"periodicidad_de_la_nomina_label\">Periodicidad de la nomina</label>";

					if($act != 'DRAW')
					{
						echo '<select title = "Periodicidad_de_la_nomina" class = "periodicidad_de_la_nomina_select" name = "Periodicidad_de_la_nomina" required=true>';

						if(isset($this->Periodicidad_de_la_nomina))
						{

							if($this->Periodicidad_de_la_nomina == 'Semanal')
								echo '<option selected>Semanal</option>';
							else
								echo '<option>Semanal</option>';

							if($this->Periodicidad_de_la_nomina == 'Quincenal')
								echo '<option selected>Quincenal</option>';
							else
								echo '<option>Quincenal</option>';

							if($this->Periodicidad_de_la_nomina == 'Mensual')
								echo '<option selected>Mensual</option>';
							else
								echo '<option>Mensual</option>';

						}
						else
						{
							echo '<option>Semanal</option>';
							echo '<option>Quincenal</option>';
							echo '<option>Mensual</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"periodicidad_de_la_nomina\" name = \"Periodicidad_de_la_nomina\" title = \"Periodicidad de la nómina\" readonly=true>{$this->Periodicidad_de_la_nomina}</textarea>";

					echo "<label class = \"limite_del_prestamo_label\">Límite del prestamo</label>";
					echo "<textarea class = \"limite_del_prestamo_textarea\" name = \"Limite_del_prestamo\" title = \"Limite del prestamo\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>") . "{$this->Limite_del_prestamo}</textarea>";
					echo "<label class = \"prestamo_label\">Préstamo</label>";
					echo "<textarea class = \"prestamo_textarea\" name = \"Prestamo\" title = \"Prestamo\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>") . "{$this->Prestamo}</textarea>";
					echo "<label class = \"dias_de_aguinaldo_label\">Días de aguinaldo</label>";
					echo "<textarea class = \"dias_de_aguinaldo_textarea\" name = \"Dias_de_aguinaldo\" title = \"Días de aguinaldo\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>") . "{$this->Dias_de_aguinaldo}</textarea>";
					echo "<label class = \"base_de_prestaciones_label\">Base de prestaciones</label>";
					
					if($act != 'DRAW')
					{
						echo '<select title = "Base de prestaciones" class = "base_de_prestaciones_select" name = "Base_de_prestaciones" required=true>';

						if(isset($this->Base_de_prestaciones))
							{

								if($this->Base_de_prestaciones == 'Salario diario')
									echo '<option selected>Salario diario</option>';
								else
									echo '<option>Salario diario</option>';

								if($this->Base_de_prestaciones == 'Salario mínimo')
									echo '<option selected>Salario mínimo</option>';
								else
									echo '<option>Salario mínimo</option>';

								if($this->Base_de_prestaciones == 'Pago neto/líquido')
									echo '<option selected>Pago neto/líquido</option>';
								else
									echo '<option>Pago neto/líquido</option>';

							}
							else
							{
								echo '<option>Salario diario</option>';
								echo '<option>Salario mínimo</option>';
								echo '<option>Pago neto/líquido</option>';
							}

						echo '</select>';
					}
					else
						echo "<textarea class = \"base_de_prestaciones_textarea\" name = \"Base_de_prestaciones\" title = \"Base de prestaciones\" readonly=true>{$this->Base_de_prestaciones}</textarea>";

					echo "<label class = \"cobrar_iva_label\">Cobrar IVA</label>";
					echo "<input type = \"checkbox\" class = \"cobrar_iva_input\" name = \"Cobrar_IVA\" title = \"Cobrar IVA\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->Cobrar_IVA == 'true'?" checked/>":"/>");
					echo "<label class = \"alimentacion_label\">Alimentación</label>";
					echo "<input type = \"checkbox\" class = \"alimentacion_input\" name = \"Alimentacion\" title = \"Alimentación\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->Alimentacion == 'true'?" checked/>":"/>");
					echo "<label class = \"cobrar_impuesto_sobre_nomina_label\">Cobrar impuesto sobre nómina</label>";
					echo "<input type = \"checkbox\" class = \"cobrar_impuesto_sobre_nomina_input\" name = \"Cobrar_impuesto_sobre_nomina\" title = \"Cobrar impuesto sobre nómina\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->Cobrar_impuesto_sobre_nomina == 'true'?" checked/>":"/>");
					echo "<label class = \"habitacion_label\">Habitación</label>";
					echo "<input type = \"checkbox\" class = \"habitacion_input\" name = \"Habitacion\" title = \"Habitación\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->Habitacion == 'true'?" checked/>":"/>");
					echo "<label class = \"quince_anos_label\">Cobrar prima de antigüedad después de 15 años</label>";
					echo "<input type = \"checkbox\" class = \"quince_anos_input\" name = \"Quince_anos\" title = \"Cobrar prima de antigüedad después de 15 años\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->Quince_anos == 'true'?" checked/>":"/>");
					echo "<label class = \"honorarios_pendientes_label\">Honorarios pendientes</label>";
					echo "<input type = \"checkbox\" class = \"honorarios_pendientes_input\" name = \"Honorarios_pendientes\" title = \"Honorarios pendientes\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->Honorarios_pendientes == 'true'?" checked/>":"/>");
					echo "<label class = \"incluir_contribuciones_label\">Incluir Contribuciones y retenciones en honorarios</label>";
					echo "<input type = \"checkbox\" class = \"incluir_contribuciones_input\" name = \"Incluir_contribuciones\" title = \"Incluir Contribuciones y retenciones en honorarios\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->Incluir_contribuciones == 'true'?" checked/>":"/>");
					echo "<label class = \"dcipn_label\">Descontar cuotas IMSS patronales en nómina</label>";
					echo "<input type = \"checkbox\" class = \"dcipn_input\" name = \"dcipn\" title = \"Descontar cuotas IMSS patronales en nómina\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->dcipn == 'true'?" checked/>":"/>");

					if($act == 'DRAW')
					{
						echo "<label class = \"credenciales_label\">Credenciales</label>";
						echo "<img class = 'credenciales_button' onclick = \"cred_menu(this,'{$this->id}','')\" />";//function credenciales at servicio.js
					}

				echo "</fieldset>";
				echo "<fieldset class = \"Configuraciones_adicionales_fieldset\" style = \"visibility:hidden\"\>";
					echo "<label class = \"dcipla_label\">Descontar cuotas IMSS del pago líquido para asalariados</label>";
					echo "<input type = \"checkbox\" class = \"dcia_input\" name = \"dcipla\" title = \"Descontar cuotas IMSS del pago líquido para asalariados\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->dcipla == 'true'?" checked/>":"/>");
					echo "<label class = \"dppla_label\">Descontar prestaciones del pago líquido para asalariados</label>";
					echo "<input type = \"checkbox\" class = \"dppla_input\" name = \"dppla\" title = \"Descontar prestaciones del pago líquido para asalariados\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->dppla == 'true'?" checked/>":"/>");
					echo "<label class = \"dgapla_label\">Descontar gestión administrativa del pago líquido para asalariados</label>";
					echo "<input type = \"checkbox\" class = \"dgapla_input\" name = \"dgapla\" title = \"Descontar gestión administrativa del pago líquido para asalariados\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->dgapla == 'true'?" checked/>":"/>");
					echo "<label class = \"dpn_label\">Descontar prestaciones en nómina</label>";
					echo "<input type = \"checkbox\" class = \"dpn_input\" name = \"dpn\" title = \"Descontar prestaciones en nómina\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->dpn == 'true'?" checked/>":"/>");
					echo "<label class = \"dgan_label\">Descontar gestión administrativa en nómina</label>";
					echo "<input type = \"checkbox\" class = \"dgan_input\" name = \"dgan\" title = \"Descontar gestión administrativa en nómina\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->dgan == 'true'?" checked/>":"/>");
					echo "<label class = \"cgaa_label\">Cobrar gestión administrativa a asimilables</label>";
					echo "<input type = \"checkbox\" class = \"cgaa_input\" name = \"cgaa\" title = \"Cobrar gestión administrativa a asimilables\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->cgaa == 'true'?" checked/>":"/>");
					echo "<label class = \"ivash_label\">IVA solo por honorarios</label>";
					echo "<input type = \"checkbox\" class = \"ivash_input\" name = \"ivash\" title = \"IVA solo por honorarios\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->ivash == 'true'?" checked/>":"/>");
				echo "</fieldset>";
				echo "<fieldset class =  \"Empresa_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Servicio_Empresa',this)\"/>" : "") . "</td><td>RFC</td><td>Nombre</td><td>Fecha de asignación</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Servicio' dbtable2 = 'Servicio_Empresa' _id = '{$this->id}'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Servicio','Servicio_Empresa','{$this->id}',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class =  \"Registro_patronal_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Servicio_Registro_patronal',this)\"/>" : "") . "</td><td>Número</td><td>Empresa</td><td>Sucursal</td><td>Fecha de asignación</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Servicio' dbtable2 = 'Servicio_Registro_patronal' _id = '{$this->id}'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<4; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Servicio','Servicio_Registro_patronal','{$this->id}',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class =  \"Trabajadores_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'></td><td>RFC</td><td>Nombre</td><td>Empresa</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Servicio' dbtable2 = 'Trabajador' _id = '$this->id'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Servicio','Trabajador','$this->id',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class =  \"Servicios_adicionales_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Servicio_adicional',this)\"/>" : "") . "</td><td>id</td><td>Nombre</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Servicio' dbtable2 = 'Servicio_adicional' _id = '$this->id'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<2; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Servicio','Servicio_adicional','$this->id',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Servicio')\" />";//_submit() at common_entities.js
			
			echo "</form>";

		}

		private function updateTrabajadorRelations($trabajadores)//If a relation Servicio<->Trabajador has been deprecated it deletes it from db. $trabajadores is the actual related trabajador array. $this->id has to be set before calling this function
		{
			$result = $this->conn->query("SELECT Trabajador FROM Servicio_Trabajador WHERE Servicio = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			$len = count($trabajadores);

			while($row = $this->conn->fetchRow($result))
			{
				$flag = false;
				list($value) = $row;

				for($i=0; $i<$len; $i++)
				{

					if($value == $trabajadores[$i])
					{
						$flag = true;
						break;
					}

				}

				if(!$flag)
					$this->conn->query("DELETE FROM Servicio_Trabajador WHERE Trabajador = '$value' and Servicio = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			}

		}

	}

?>
