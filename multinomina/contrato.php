<?php
	include_once('connection.php');
	include_once('servicio.php');
	include_once('actividad.php');

//Class Contrato definition

	class Contrato
	{
		//class properties
		//data
		private $id;
		private $Puesto;
		private $Tipo;
		private $Tipo_de_jornada;
		private $Fecha = 'AAAA-MM-DD';
		private $Trabajador;
		private $Servicio;
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
			$result = $this->conn->query("SELECT id FROM Contrato WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY id DESC LIMIT 1");
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
					$this->$key = trim($_POST["$key"]);

		}

		public function showProperties()//prints properties values
		{

			foreach($this as $key => $value)

				if($key != 'conn')
					echo "$key = $value <br />";

		}

		public function setFromDb()//sets properties from data base, but $id has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Contrato WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties if $update is true it updates all database registers (professedly one) with $this' id
		{
			$actividad = new Actividad();
			$actividad->set('Dato','Contrato');
			$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($trabajador) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($update == 'false')
			{
				$this->setID();
				$this->conn->query("INSERT INTO Contrato(id, Cuenta) VALUES('{$this->id}', '{$_SESSION['cuenta']}')");
				$actividad->set('Identificadores',"id: {$this->id} Tipo: {$this->Tipo} Fecha: {$this->Fecha} Trabajador: $trabajador");
				$actividad->set('Operacion','Nuevo');
			}
			else
			{
				$servicio = new Servicio();
				$servicio->set('id', $this->Servicio);
				$notation = $servicio->notation();
				$actividad->set('Identificadores',"id: {$this->id} Tipo: {$this->Tipo} Fecha: {$this->Fecha} Trabajador: $trabajador Servicio: $notation");
				$actividad->set('Operacion','Editar');
			}

			$actividad->dbStore();

			foreach($this as $key => $value)

				if(isset($this->$key))
				{

					if($key != 'conn' && $key != 'id')
						$this->conn->query("UPDATE Contrato SET $key  = '$value' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

				}
				elseif($key == 'Servicio')
					$this->conn->query("UPDATE Contrato SET Servicio = NULL WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			return true;
		}

		public function dbDelete()//delete this entity from database but id has to be set before
		{

			if(isset($this->id))
			{
				$this->setFromDb();
				$actividad = new Actividad();
				$actividad->set('Operacion','Eliminar');
				$actividad->set('Dato','Contrato');
				$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($trabajador) = $this->conn->fetchRow($result);
				$servicio = new Servicio();
				$servicio->set('id', $this->Servicio);
				$notation = $servicio->notation();
				$actividad->set('Identificadores',"id: {$this->id} Tipo: {$this->Tipo} Fecha: {$this->Fecha} Trabajador: $trabajador Servicio: $notation");
				$actividad->dbStore();
				$this->conn->query("DELETE FROM Contrato WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

		}

		public function draw($act)//draws $this Contrato. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. If $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";

			if($act != 'ADD')
				echo "<div onclick = \"show('Servicio_fieldset',this)\" class = \"servicio_tab\">Servicio</div>";

			echo "<form class = \"show_form\">";
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>{$this->id}</textarea>";
					echo "<textarea name = \"Trabajador\" class=\"hidden_textarea\" readonly=true>{$this->Trabajador}</textarea>";
					echo "<label class = \"puesto_label\">Puesto</label>";
					echo "<textarea class=\"puesto_textarea\" name = \"Puesto\" title = \"Puesto\"" . ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>") . "{$this->Puesto}</textarea>";
					echo "<label class = \"tipo_label\">Tipo</label>";

					if($act != 'DRAW')
					{
						echo '<select title = "Tipo" class = "tipo_select" name = "Tipo" required=true >';

						if(isset($this->Tipo))
						{

							if($this->Tipo == 'Base')
								echo '<option selected>Base</option>';
							else
								echo '<option>Base</option>';

							if($this->Tipo == 'Eventual')
								echo '<option selected>Eventual</option>';
							else
								echo '<option>Eventual</option>';

							if($this->Tipo == 'Confianza')
								echo '<option selected>Confianza</option>';
							else
								echo '<option>Confianza</option>';

							if($this->Tipo == 'Sindicalizado')
								echo '<option selected>Sindicalizado</option>';
							else
								echo '<option>Sindicalizado</option>';

							if($this->Tipo == 'A prueba')
								echo '<option selected>A prueba</option>';
							else
								echo '<option>A prueba</option>';

						}
						else
						{
							echo '<option>Base</option>';
							echo '<option>Eventual</option>';
							echo '<option>Confianza</option>';
							echo '<option>Sindicalizado</option>';
							echo '<option>A prueba</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"tipo_textarea\" name = \"Tipo\" title = \"Tipo\" readonly=true>{$this->Tipo}</textarea>";

					echo "<label class = \"jornada_label\">Tipo de jornada</label>";

					if($act != 'DRAW')
					{
						echo '<select title = "Tipo de jornada" class = "jornada_select" name = "Tipo_de_jornada" required=true >';
							
						if(isset($this->Tipo_de_jornada))
						{

							if($this->Tipo_de_jornada == 'Diurna')
								echo '<option selected>Diurna</option>';
							else
								echo '<option>Diurna</option>';

							if($this->Tipo_de_jornada == 'Nocturna')
								echo '<option selected>Nocturna</option>';
							else
								echo '<option>Nocturna</option>';

							if($this->Tipo_de_jornada == 'Mixta')
								echo '<option selected>Mixta</option>';
							else
								echo '<option>Mixta</option>';

							if($this->Tipo_de_jornada == 'Por hora')
								echo '<option selected>Por hora</option>';
							else
								echo '<option>Por hora</option>';

							if($this->Tipo_de_jornada == 'Reducida')
								echo '<option selected>Reducida</option>';
							else
								echo '<option>Reducida</option>';

							if($this->Tipo_de_jornada == 'Continuada')
								echo '<option selected>Continuada</option>';
							else
								echo '<option>Continuada</option>';

							if($this->Tipo_de_jornada == 'Partida')
								echo '<option selected>Partida</option>';
							else
								echo '<option>Partida</option>';

							if($this->Tipo_de_jornada == 'Por turnos')
								echo '<option selected>Por turnos</option>';
							else
								echo '<option>Por turnos</option>';

						}
						else
						{
							echo '<option>Diurna</option>';
							echo '<option>Nocturna</option>';
							echo '<option>Mixta</option>';
							echo '<option>Por hora</option>';
							echo '<option>Reducida</option>';
							echo '<option>Continuada</option>';
							echo '<option>Partida</option>';
							echo '<option>Por turnos</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"jornada_textarea\" name = \"Tipo_de_jornada\" title = \"Tipo de jornada\" readonly=true>{$this->Tipo_de_jornada}</textarea>";

					echo "<label class = \"fecha_label\">Fecha</label>";
					echo "<textarea class=\"fecha_textarea\" name = \"Fecha\" title = \"Fecha\"" . ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>") . "{$this->Fecha}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";//show_calendar() at calendar.js

				echo "</fieldset>";
				echo "<fieldset class = \"Servicio_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'></td><td>id</td><td>Periodo de la nómina</td><td>Registro patronal</td><td>Empresa</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Contrato' dbtable2 = 'Servicio' _id = '{$this->id}'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<4; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Contrato','Servicio','{$this->id}',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Contrato')\" />";//submit_button_brigth and submit_button_opaque at presentation.js _submit() at common_entities.js
			
			echo "</form>";
		}

	}

?>
