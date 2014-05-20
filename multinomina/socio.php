<?php
	include_once('connection.php');

//Class Socio definition

	class Socio
	{
		//class properties
		//data
		private $Nombre;
		private $id;//used to store the last Nombre to be able to edit it
		private $Empresa;
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

				if(isset($_POST["$key"]))
					$this->$key = trim($_POST["$key"]);

		}

		public function showProperties()//prints properties values
		{

			foreach($this as $key => $value)

				if($key != 'conn')
					echo "$key = $value <br />";

		}

		public function setFromDb()//sets properties from data base, but $Nombre and $Empresa has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Socio WHERE Nombre = '{$this->Nombre}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)
					$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. Otherwise it updates all database registers (professedly one) with $this' Nombre and Empresa
		{

			if(isset($this->Nombre) && isset($this->Empresa))
			{

				if($update == 'false')
					$this->conn->query("INSERT INTO Socio (Nombre, Empresa, Cuenta) VALUES ('{$this->Nombre}', '{$this->Empresa}', '{$_SESSION['cuenta']}')");
				else
					$this->conn->query("UPDATE Socio SET Nombre = '{$this->Nombre}' WHERE Nombre = '{$this->id}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

				foreach($this as $key => $value)

					if(isset($this->$key))
					{
						if($key != 'conn' && $key != 'Nombre' && $key != 'id' && $key != 'Empresa')
							$this->conn->query("UPDATE Socio SET $key = '$value' WHERE Nombre = '{$this->Nombre}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

					}

				return true;
			}

			return false;
		}

		public function dbDelete()//delete this entity from database but Nombre and Empresa has to be set before
		{

			if(isset($this->Nombre) && isset($this->Empresa))
				$this->conn->query("DELETE FROM Socio WHERE Nombre = '{$this->Nombre}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

		}

		public function draw($act)//draws $this Socio. if $act == 'EDIT' or 'ADD' the fields can be edited and the form is submitted. Otherwise the fields can't be edited and the form is not submitted.
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";//show at tabs.js
			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>"."$this->Nombre</textarea>";
					echo "<textarea name = \"Empresa\" class=\"hidden_textarea\" readonly=true>"."$this->Empresa</textarea>";
					echo "<label class = \"nombre_label\">Nombre</label>";
					echo "<textarea class = \"nombre_textarea\" name = \"Nombre\" title = \"Nombre\" onblur = \"chKey(this.value,'Socio','$act')\" ". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."$this->Nombre</textarea>";
				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Socio')\" />";//_submit() at common_entities.js
			
			echo "</form>";

		}

	}

?>
