<?php
	include_once('connection.php');
//class Regimen_fiscal

	class Regimen_fiscal
	{
		//class properties
		private $id;
		private $Regimen;
		private $Empresa;
		private $conn;//database connection

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
			$result = $this->conn->query("SELECT id FROM Regimen_fiscal WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY id DESC LIMIT 1");
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
					$this->$key = $_POST["$key"];

		}

		public function showProperties()//prints property values
		{

			foreach($this as $key => $value)

				if($key != 'conn')
					echo "$key = $value <br />";

		}

		public function setFromDb()//sets properties from data base, but $id and $Empresa has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Regimen_fiscal WHERE id = '{$this->id}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. Otherwise it updates all database registers (professedly one) with $this' id and Empresa
		{

			if(isset($this->id) && isset($this->Empresa))
			{

				if($update == 'false')
				{
					$this->setID();
					$this->conn->query("INSERT INTO Regimen_fiscal (id, Empresa, Cuenta) VALUES ('{$this->id}', '{$this->Empresa}', '{$_SESSION['cuenta']}')");
				}

				foreach($this as $key => $value)

					if(isset($this->$key))

						if($key != 'conn' && $key != 'id' && $key != 'Empresa')
							$this->conn->query("UPDATE Regimen_fiscal SET $key  = '$value' WHERE id = '{$this->id}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

				return true;
			}

			return false;
		}

		public function dbDelete()//delete this entity from database but id and Empresa has to be set before
		{

			if(isset($this->id) && isset($this->Empresa))
				$this->conn->query("DELETE FROM Regimen_fiscal WHERE id = '{$this->id}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

		}

		public function draw($act)//draws $this Regimen_fiscal. if $act == 'EDIT' od 'ADD' the fields can be edited and the form is submitted. Otherwise the fields can't be edited and the form is not submitted
		{
			echo "<div class = \"datos_tab\">Datos</div>";

			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\">";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>{$this->id}</textarea>";
					echo "<textarea name = \"Empresa\" class=\"hidden_textarea\" readonly=true>{$this->Empresa}</textarea>";
					echo "<label class = \"regimen_label\">Régimen</label>";

					if($act != 'DRAW')
					{
						echo '<select title = "Régimen" class = "regimen_select" name = "Regimen" required=true >';

						if(isset($this->Regimen))
						{

							if($this->Regimen == 'PERSONAS MORALES DEL REGIMEN GENERAL')
								echo '<option selected>PERSONAS MORALES DEL REGIMEN GENERAL</option>';
							else
								echo '<option>PERSONAS MORALES DEL REGIMEN GENERAL</option>';

							if($this->Regimen == 'PERSONAS MORALES CON FINES NO LUCRATIVOS')
								echo '<option selected>PERSONAS MORALES CON FINES NO LUCRATIVOS</option>';
							else
								echo '<option>PERSONAS MORALES CON FINES NO LUCRATIVOS</option>';

							if($this->Regimen == 'PERSONAS MORALES DEL REGIMEN SIMPLIFICADO')
								echo '<option selected>PERSONAS MORALES DEL REGIMEN SIMPLIFICADO</option>';
							else
								echo '<option>PERSONAS MORALES DEL REGIMEN SIMPLIFICADO</option>';

							if($this->Regimen == 'DE LAS PERSONAS FISICAS CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES')
								echo '<option selected>DE LAS PERSONAS FISICAS CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES</option>';
							else
								echo '<option>DE LAS PERSONAS FISICAS CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES</option>';

							if($this->Regimen == 'DEL REGIMEN INTERMEDIO DE LAS PERSONAS FISICAS CON ACTIVIDADES EMPRESARIALES')
								echo '<option selected>DEL REGIMEN INTERMEDIO DE LAS PERSONAS FISICAS CON ACTIVIDADES EMPRESARIALES</option>';
							else
								echo '<option>DEL REGIMEN INTERMEDIO DE LAS PERSONAS FISICAS CON ACTIVIDADES EMPRESARIALES</option>';

							if($this->Regimen == 'REGIMEN DE ACTIVIDADES AGRICOLAS, GANADERAS, SILVICOLAS Y PESQUERAS')
								echo '<option selected>REGIMEN DE ACTIVIDADES AGRICOLAS, GANADERAS, SILVICOLAS Y PESQUERAS</option>';
							else
								echo '<option>REGIMEN DE ACTIVIDADES AGRICOLAS, GANADERAS, SILVICOLAS Y PESQUERAS</option>';

							if($this->Regimen == 'SOCIEDADES COOPERATIVAS DE PRODUCCION QUE OPTAN POR DIFERIR SUS INGRESOS')
								echo '<option selected>SOCIEDADES COOPERATIVAS DE PRODUCCION QUE OPTAN POR DIFERIR SUS INGRESOS</option>';
							else
								echo '<option>SOCIEDADES COOPERATIVAS DE PRODUCCION QUE OPTAN POR DIFERIR SUS INGRESOS</option>';

							if($this->Regimen == 'REGIMEN DE SUELDOS Y SALARIOS E INGRESOS ASIMILADOS A SALARIOS')
								echo '<option selected>REGIMEN DE SUELDOS Y SALARIOS E INGRESOS ASIMILADOS A SALARIOS</option>';
							else
								echo '<option>REGIMEN DE SUELDOS Y SALARIOS E INGRESOS ASIMILADOS A SALARIOS</option>';

							if($this->Regimen == 'REGIMEN DE INCORPORACION FISCAL')
								echo '<option selected>REGIMEN DE INCORPORACION FISCAL</option>';
							else
								echo '<option>REGIMEN DE INCORPORACION FISCAL</option>';

						}
						else
						{
							echo '<option>PERSONAS MORALES DEL REGIMEN GENERAL</option>';
							echo '<option>PERSONAS MORALES CON FINES NO LUCRATIVOS</option>';
							echo '<option>PERSONAS MORALES DEL REGIMEN SIMPLIFICADO</option>';
							echo '<option>DE LAS PERSONAS FISICAS CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES</option>';
							echo '<option>DEL REGIMEN INTERMEDIO DE LAS PERSONAS FISICAS CON ACTIVIDADES EMPRESARIALES</option>';
							echo '<option>REGIMEN DE ACTIVIDADES AGRICOLAS, GANADERAS, SILVICOLAS Y PESQUERAS</option>';
							echo '<option>SOCIEDADES COOPERATIVAS DE PRODUCCION QUE OPTAN POR DIFERIR SUS INGRESOS</option>';
							echo '<option>REGIMEN DE SUELDOS Y SALARIOS E INGRESOS ASIMILADOS A SALARIOS</option>';
							echo '<option>REGIMEN DE INCORPORACION FISCAL</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"regimen_textarea\" name = \"Regimen\" title = \"Régimen\" readonly=true>{$this->Regimen}</textarea>";

				echo "</fieldset>";

				if($act == 'EDIT' || $act == 'ADD')
					echo "<img title = \"Guardar\" class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Regimen_fiscal')\" />";//_submit() at entities.js
			
			echo "</form>";

		}
	}
?>
