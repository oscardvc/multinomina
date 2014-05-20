<?php
	include_once('connection.php');
	include_once('actividad.php');

//Class Registro_patronal definition

	class Registro_patronal
	{
		//class properties
		//data
		private $Numero;
		private $id;//used to store the last Numero to be able to edit it
		private $Clase_de_riesgo;
		private $Folio_IMSS;
		private $Fraccion;
		private $Empresa;
		private $Sucursal;
		private $Empresa_sucursal;
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

		public function get($name)//gets property named $name
		{
			return $this->$name;
		}

		public function setFromBrowser()//sets properties from superglobal $_POST
		{
			foreach($this as $key => $value)

				if(isset($_POST["$key"]) && $_POST["$key"] != '')
					$this->$key = trim($_POST["$key"]);
		}

		public function showProperties()//prints properties values
		{

			foreach($this as $key => $value)

				if($key != 'conn' && isset($this->$key) && $this->$key != '')
					echo "$key = $value <br />";

		}

		public function setFromDb()//sets properties from data base, but $Numero has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Registro_patronal WHERE Numero = '{$this->Numero}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)
					$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties
		{				   //if $update is true it updates all database registers (professedly one) with $this' Numero

			if(isset($this->Numero))
			{
				$actividad = new Actividad();
				$actividad->set('Dato','Registro patronal');

				$result = $this->conn->query("SELECT Nombre FROM Empresa WHERE (RFC = '{$this->Empresa}' OR RFC = '{$this->Empresa_sucursal}') AND Cuenta = '{$_SESSION['cuenta']}' LIMIT 1");
				list($empresa) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$actividad->set('Identificadores',"Numero: {$this->Numero} Empresa: $empresa" . ($this->Sucursal != '' ? " Sucursal: {$this->Sucursal}" : ""));

				if($update == 'false')
				{
					$result = $this->conn->query("SELECT Numero FROM Registro_patronal WHERE Numero = '{$this->Numero}' AND Cuenta = '{$_SESSION['cuenta']}'");

					if($this->conn->num_rows($result) > 0)
						return false;

					$this->conn->query("INSERT INTO Registro_patronal(Numero, Cuenta) VALUES('{$this->Numero}', '{$_SESSION['cuenta']}')");
					$actividad->set('Operacion','Nuevo');
				}
				else
				{

					if($this->Numero != $this->id)
					{
						$result = $this->conn->query("SELECT Numero FROM Registro_patronal WHERE Numero = '{$this->Numero}' AND Cuenta = '{$_SESSION['cuenta']}'");

						if($this->conn->num_rows($result) > 0)
							return false;

					}

					$this->conn->query("UPDATE Registro_patronal SET Numero  = '{$this->Numero}' WHERE Numero = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
					$actividad->set('Operacion','Editar');
				}

				$actividad->dbStore();

				foreach($this as $key => $value)

					if(isset($this->$key))

						if($key != 'conn' && $key != 'Numero' && $key != 'id')
							$this->conn->query("UPDATE Registro_patronal SET $key = '$value' WHERE Numero = '{$this->Numero}' AND Cuenta = '{$_SESSION['cuenta']}'");

				return true;
			}

			return false;
		}

		public function dbDelete()//delete this entity from database but Numero has to be set before
		{

			if(isset($this->Numero))
			{
				$this->setFromDb();
				$actividad = new Actividad();
				$actividad->set('Operacion','Eliminar');
				$actividad->set('Dato','Registro patronal');
				$result = $this->conn->query("SELECT Nombre FROM Empresa WHERE (RFC = '{$this->Empresa}' OR RFC = '{$this->Empresa_sucursal}') AND Cuenta = '{$_SESSION['cuenta']}' LIMIT 1");
				list($empresa) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$actividad->set('Identificadores',"Numero: {$this->Numero} Empresa: $empresa" . ($this->Sucursal != '' ? " Sucursal: {$this->Sucursal}" : ""));
				$actividad->dbStore();
				$this->conn->query("DELETE FROM Registro_patronal WHERE Numero = '{$this->Numero}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

		}

		public function draw($act)//draws $this administradora. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";
			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>{$this->Numero}</textarea>";
					echo "<textarea name = \"Empresa\" class=\"hidden_textarea\" readonly=true>{$this->Empresa}</textarea>";
					echo "<textarea name = \"Sucursal\" class=\"hidden_textarea\" readonly=true>{$this->Sucursal}</textarea>";
					echo "<textarea name = \"Empresa_sucursal\" class=\"hidden_textarea\" readonly=true>{$this->Empresa_sucursal}</textarea>";
					echo "<label class = \"fraccion_label\">Fracción</label>";
					echo "<textarea class = \"fraccion_textarea\" name = \"Fraccion\" title = \"Fraccion\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Fraccion}</textarea>";
					echo "<label class = \"numero_label\">Número</label>";
					echo "<textarea class = \"numero_textarea\" name = \"Numero\" title = \"Numero\" onblur = \"chKey(this.value,'Registro_patronal','$act')\" ". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Numero}</textarea>";
					echo "<label class = \"clase_de_riesgo_label\">Clase de riesgo</label>";

					if($act != 'DRAW')
					{
						echo '<select title = "Clase de riesgo" class = "clase_de_riesgo_select" name = "Clase_de_riesgo" required=true >';
							
						if(isset($this->Clase_de_riesgo))
						{

							if($this->Clase_de_riesgo == 'I')
								echo '<option selected>I</option>';
							else
								echo '<option>I</option>';

							if($this->Clase_de_riesgo == 'II')
								echo '<option selected>II</option>';
							else
								echo '<option>II</option>';

							if($this->Clase_de_riesgo == 'III')
								echo '<option selected>III</option>';
							else
								echo '<option>III</option>';

							if($this->Clase_de_riesgo == 'IV')
								echo '<option selected>IV</option>';
							else
								echo '<option>IV</option>';

							if($this->Clase_de_riesgo == 'V')
								echo '<option selected>V</option>';
							else
								echo '<option>V</option>';

						}
						else
						{
							echo '<option>I</option>';
							echo '<option>II</option>';
							echo '<option>III</option>';
							echo '<option>IV</option>';
							echo '<option>V</option>';
						}

						echo '</select>';
					}
					else
						echo "<textarea class = \"clase_de_riesgo_textarea\" name = \"Clase_de_riesgo\" title = \"Clase de riesgo\"readonly=true>{$this->Clase_de_riesgo}</textarea>";

					echo "<label class = \"folio_imss_label\">Folio IMSS</label>";
					echo "<textarea class = \"folio_imss_textarea\" name = \"Folio_IMSS\" title = \"Folio IMSS\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->Folio_IMSS}</textarea>";
					echo '</fieldset>';

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Registro_patronal')\" />";//_submit() at common_entities.js
			
			echo "</form>";

		}

	}

?>
