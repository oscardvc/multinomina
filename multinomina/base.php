<?php
	include_once('connection.php');
	include_once('servicio.php');
	include_once('actividad.php');

//Class Base definition

	class Base
	{
		//class properties
		//data
		private $id;
		private $Base;
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
			$result = $this->conn->query("SELECT id FROM Base WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY id DESC LIMIT 1");
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
			$result = $this->conn->query("SELECT * FROM Base WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)
					$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties if $update is true it updates all database registers (professedly one) with $this' id
		{
			$actividad = new Actividad();
			$actividad->set('Dato','Base');
			$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($trabajador) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($update == 'false')
			{
				$this->setID();
				$this->conn->query("INSERT INTO Base(id, Cuenta) VALUES('{$this->id}', '{$_SESSION['cuenta']}')");
				$actividad->set('Identificadores',"id: {$this->id} Base: {$this->Base} Fecha: {$this->Fecha} Trabajador: $trabajador");
				$actividad->set('Operacion','Nuevo');
			}
			else
			{
				$servicio = new Servicio();
				$servicio->set('id', $this->Servicio);
				$notation = $servicio->notation();
				$actividad->set('Identificadores',"id: {$this->id} Base: {$this->Base} Fecha: {$this->Fecha} Trabajador: $trabajador Servicio: $notation");
				$actividad->set('Operacion','Editar');
			}

			$actividad->dbStore();

			foreach($this as $key => $value)

				if(isset($this->$key))
				{

					if($key != 'conn' && $key != 'id')
						$this->conn->query("UPDATE Base SET $key = '$value' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

				}
				elseif($key == 'Servicio')
					$this->conn->query("UPDATE Base SET Servicio = NULL WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			return true;
		}

		public function dbDelete()//delete this entity from database but id has to be set before
		{

			if(isset($this->id))
			{
				$this->setFromDb();
				$actividad = new Actividad();
				$actividad->set('Operacion','Eliminar');
				$actividad->set('Dato','Base');
				$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($trabajador) = $this->conn->fetchRow($result);
				$servicio = new Servicio();
				$servicio->set('id', $this->Servicio);
				$notation = $servicio->notation();
				$actividad->set('Identificadores',"id: {$this->id} Base: {$this->Base} Fecha: {$this->Fecha} Trabajador: $trabajador Servicio: $notation");
				$actividad->dbStore();
				$this->conn->query("DELETE FROM Base WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

		}

		public function draw($act)//draws $this Base. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. If $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";

			if($act != 'ADD')
				echo "<div onclick = \"show('Servicio_fieldset',this)\" class = \"servicio_tab\">Servicio</div>";

			echo "<form class = \"show_form\">";
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>{$this->id}</textarea>";
					echo "<textarea name = \"Trabajador\" class=\"hidden_textarea\" readonly=true>{$this->Trabajador}</textarea>";
					echo "<label class = \"base_label\">Base para el cálculo de la nómina</label>";

					if($act != 'DRAW')
					{
						echo '<select title = "Base" class = "base_select" name = "Base" required=true >';
							
						if(isset($this->Base))
						{

							if($this->Base == 'Salario mínimo')
								echo '<option selected>Salario mínimo</option>';
							else
								echo '<option>Salario mínimo</option>';

							if($this->Base == 'Salario diario')
								echo '<option selected>Salario diario</option>';
							else
								echo '<option>Salario diario</option>';

						}
						else
						{
							echo '<option>Salario mínimo</option>';
							echo '<option>Salario diario</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"base_textarea\" name = \"Base\" title = \"Base para el cálculo de la nómina\" readonly=true> $this->Base</textarea>";

					echo "<label class = \"fecha_label\">Fecha</label>";
					echo "<textarea class=\"fecha_textarea\" name = \"Fecha\" title = \"Fecha\"" . ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>") . "$this->Fecha</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";//show_calendar() at calendar.js

				echo "</fieldset>";
				echo "<fieldset class = \"Servicio_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'></td><td>id</td><td>Periodo de la nómina</td><td>Registro patronal</td><td>Empresa</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Base' dbtable2 = 'Servicio' _id = '{$this->id}'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<4; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Base','Servicio','{$this->id}',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Base')\" />";//submit_button_brigth and submit_button_opaque at presentation.js _submit() at common_entities.js
			
			echo "</form>";
		}

	}

?>
