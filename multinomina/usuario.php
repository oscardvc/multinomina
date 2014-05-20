<?php
	include_once('connection.php');

//Class Usuario definition

	class Usuario
	{
		//class properties
		//data
		private $Nombre;
		private $id;//used to store the last Nombre to be able to edit it
		private $Cuenta;
		private $Contrasena;
		//database connection
		private $conn;

		//class methods
		public function __construct()
		{

			if(!isset($_SESSION))
				session_start();

			$this->conn = new Connection();
			$this->Cuenta = $_SESSION['cuenta'];
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

		public function setFromDb()//sets properties from data base, but $Nombre and $Cuenta has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Usuario WHERE Nombre = '{$this->Nombre}' AND Cuenta = '{$this->Cuenta}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)
					$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. if $update is true it updates all database registers (professedly one) with $this' Nombre and Cuenta
		{

			if(isset($this->Nombre) && isset($this->Cuenta))
			{

				if($update == 'false')
					$this->conn->query("INSERT INTO Usuario(Nombre, Cuenta) VALUES ('{$this->Nombre}', '{$this->Cuenta}')");
				else
					$this->conn->query("UPDATE Usuario SET Nombre  = '{$this->Nombre}' WHERE Nombre = '{$this->id}' AND Cuenta = '{$this->Cuenta}'");

				foreach($this as $key => $value)

					if(isset($this->$key))

						if($key != 'conn' && $key != 'Nombre' && $key != 'id' && $key != 'Cuenta')
							$this->conn->query("UPDATE Usuario SET $key  = '$value' WHERE Nombre = '{$this->Nombre}' AND Cuenta = '{$this->Cuenta}'");

				return true;
			}

			return false;
		}

		public function dbDelete()//delete this entity from database but Nombre and Cuenta has to be set before
		{

			if(isset($this->Nombre) && isset($this->Cuenta))
				$this->conn->query("DELETE FROM Usuario WHERE Nombre = '{$this->Nombre}' AND Cuenta = '{$this->Cuenta}'");

		}

		public function draw($act)//draws $this Usuario. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div class = \"datos_tab\">Datos</div>";
			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>{$this->Nombre}</textarea>";
					echo "<textarea name = \"Cuenta\" class=\"hidden_textarea\" readonly=true>{$this->Cuenta}</textarea>";
					echo "<label class = \"nombre_label\">Nombre</label>";
					echo "<textarea class = \"nombre_textarea\" name = \"Nombre\" title = \"Nombre\" onblur = \"chKey(this.value,'Usuario','$act')\" " . ($act == 'ADD'?"required=true>":"readonly=true>") . "{$this->Nombre}</textarea>";
					echo "<label class = \"contrasena_label\">Contraseña</label>";
					echo "<input type = \"password\"class = \"contrasena_input\" name = \"Contrasena\" title = \"Contraseña\" value = \"{$this->Contrasena}\" required = true/>";
					echo "<img class = 'sign_image' title = 'Firma' " . ($act == 'EDIT' ? "onclick = \"_new('Sign',this)\"" : "") . " usuario = {$this->Nombre} />";
					echo "<label class = \"firma_label\">Firma</label>";
				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Usuario')\" />";//_submit() at common_entities.js

			echo "</form>";

		}

	}

?>
