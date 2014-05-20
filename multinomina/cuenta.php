<?php
	include_once('connection.php');

//Class cuenta definition

	class Cuenta
	{
		//class properties
		private $Nombre;
		private $id;//used to store the last Nombre to be able to edit it
		private $Contrasena;
		//database connection
		private $conn;

		//class methods
		public function __construct()
		{
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

		public function connect()//connects to data base
		{
			$this->conn = new Connection('multinomina');
		}

		public function setFromDb()//sets properties from data base, but $Nombre has to be set before
		{

			if(!isset($this->conn))
				$this->connect();
			
			$result = $this->conn->query("SELECT * FROM Cuenta WHERE Nombre = '{$this->Nombre}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)
					$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties
		{				   //if $update is true it updates all database registers (professedly one) with $this' Cuenta

			if (!isset($this->conn))
				$this->connect();

			if(isset($this->Nombre))
			{

				if($update == 'false')
					$this->conn->query("INSERT INTO Cuenta(Nombre) VALUES('{$this->Nombre}')");
				else
					$this->conn->query("UPDATE Cuenta SET Nombre  = '{$this->Nombre}' WHERE Nombre = '{$this->id}'");
				
				foreach($this as $key => $value)

					if($key != 'conn' && $key != 'Nombre' && $key != 'id')
					{

						if(isset($this->$key))
							$this->conn->query("UPDATE Cuenta SET $key  = '$value' WHERE Nombre = '{$this->Nombre}'");

					}

				return true;
			}

			return false;
		}

		public function dbDelete()//delete this entity from database but Nombre has to be set before
		{

			if(!isset($this->conn))
				$this->connect();

			if(isset($this->Nombre))
				$this->conn->query("DELETE FROM Cuenta WHERE Nombre = '{$this->Nombre}'");

		}

		public function draw($act)//draws $this cuenta. if $act == 'EDIT' or 'ADD' the fields can be edited and the form is submitted. otherwise the fields can't be edited and the form is not submitted
		{
			echo "<div class = \"datos_tab\">Datos</div>";//show at tabs.js

			echo '<form class = "show_form">';
				echo '<fieldset class = "Datos_fieldset" style = "visibility:visible">';
					echo "<input type = \"text\" name = \"id\" class=\"hidden_input\" readonly=true value = \"{$this->Nombre}\" />";
					echo "<label class = \"nombre_label\">Nombre</label>";
					echo "<input type = \"text\" class = \"nombre_input\" name = \"Nombre\" title = \"Nombre\" value = \"{$this->Nombre}\" ". ($act == 'EDIT' || $act == 'ADD'?"required=true/>":"readonly=true/>");
					echo "<label class = \"contrasena_label\">Contraseña</label>";
					echo "<input type = \"password\" class = \"contrasena_input\" name = \"Contrasena\" title = \"Contraseña\" value = \"{$this->Contrasena}\"". ($act == 'EDIT' || $act == 'ADD'?"required=true/>":"readonly=true/>");
				echo "</fieldset>";			
			echo "</form>";
			
		}

	}

?>
