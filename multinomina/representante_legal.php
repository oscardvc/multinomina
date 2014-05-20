<?php
	include_once('connection.php');
//class Representante definition

	class Representante_legal
	{
		//class properties
		private $Nombre;
		private $id;//used to store the last Nombre to be able to edit it
		private $RFC;
		private $Estado_civil;
		private $Fecha_de_nacimiento = 'AAAA-MM-DD';
		private $Domicilio;
		private $Lugar_de_nacimiento;
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

		public function setFromDb()//sets properties from data base, but $Nombre and $Empresa has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Representante_legal WHERE Nombre = '{$this->Nombre}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)
					$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. Otherwise it updates all database registers (professedly one) with $this' Nombre and Empresa
		{

			if(isset($this->Nombre) && isset($this->Empresa))
			{

				if($update == 'false')
					$this->conn->query("INSERT INTO Representante_legal (Nombre, Empresa, Cuenta) VALUES ('{$this->Nombre}', '{$this->Empresa}', '{$_SESSION['cuenta']}')");
				else
					$this->conn->query("UPDATE Representante_legal SET Nombre = '{$this->Nombre}' WHERE Nombre = '{$this->id}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");
				
				foreach($this as $key => $value)

					if(isset($this->$key))

						if($key != 'conn' && $key != 'Nombre' && $key != 'id' && $key != 'Empresa')
							$this->conn->query("UPDATE Representante_legal SET $key  = '$value' WHERE Nombre = '{$this->Nombre}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

				return true;
			}

			return false;
		}

		public function dbDelete()//delete this entity from database but Nombre and Empresa has to be set before
		{

			if(isset($this->Nombre) && isset($this->Empresa))
				$this->conn->query("DELETE FROM Representante_legal WHERE Nombre = '{$this->Nombre}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

		}

		public function draw($act)//draws $this representante. if $act == 'EDIT' od 'ADD' the fields can be edited and the form is submitted. Otherwise the fields can't be edited and the form is not submitted
		{
			echo "<div class = \"datos_tab\">Datos</div>";

			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\">";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>$this->Nombre</textarea>";
					echo "<textarea name = \"Empresa\" class=\"hidden_textarea\" readonly=true>$this->Empresa</textarea>";
					echo "<label class = \"nombre_label\">Nombre</label>";
					echo "<textarea class = \"nombre_textarea\" name = \"Nombre\" title = \"Nombre\" onblur = \"chKey(this.value,'Representante_legal','$act')\" ". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."$this->Nombre</textarea>";
					echo "<label class = \"rfc_label\">RFC</label>";
					echo "<textarea class = \"rfc_textarea\" name = \"RFC\" title = \"RFC\" ". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->RFC</textarea>";
					echo "<label class = \"domicilio_label\">Domicilio</label>";
					echo "<textarea class = \"domicilio_textarea\" name = \"Domicilio\" title = \"Domicilio\" ". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Domicilio</textarea>";
					echo "<label class = \"estado_civil_label\">Estado civil</label>";
					echo "<textarea class = \"estado_civil_textarea\" name = \"Estado_civil\" title = \"Estado civil\" ". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Estado_civil</textarea>";
					echo "<label class = \"lugar_de_nacimiento_label\">Lugar de nacimiento</label>";
					echo "<textarea class = \"lugar_de_nacimiento_textarea\" name = \"Lugar_de_nacimiento\" title = \"Lugar de nacimiento\" ". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Lugar_de_nacimiento</textarea>";
					echo "<label class = \"fecha_de_nacimiento_label\">Fecha de nacimiento</label>";
					echo "<textarea class=\"fecha_de_nacimiento_textarea\" id= \"Fecha_de_nacimiento\" name = \"Fecha_de_nacimiento\" title = \"Fecha de nacimiento\"". ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>")."$this->Fecha_de_nacimiento</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)'/>";

				echo "</fieldset>";

				if($act == 'EDIT' || $act == 'ADD')
					echo "<img title = \"Guardar\" class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Representante_legal')\" />";//_submit() at common_entities.js
			
			echo "</form>";

		}
	}
?>
