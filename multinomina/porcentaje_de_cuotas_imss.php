<?php
	include_once('connection.php');

//Class Porcentaje_de_cuotas_IMSS definition

	class Porcentaje_de_cuotas_IMSS
	{
		//class properties
		//data
		private $Nombre;
		private $_Nombre;//to be able to edit Nombre
		private $Ano;
		private $_Ano;//to be able to edit Ano
		private $Valor;
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
					$this->$key = trim($_POST["$key"]);

		}

		public function showProperties()//prints properties values
		{

			foreach($this as $key => $value)

				if($key != 'conn')
					echo "$key = $value <br />";

		}

		private function connect()//connects to data base
		{
			$this->conn = new Connection('multinomina');
		}

		public function setFromDb()//sets properties from data base, but $Nombre and $Ano has to be set before
		{

			if(!isset($this->conn))
				$this->connect();
			
			$result = $this->conn->query("SELECT * FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = '{$this->Nombre}' AND Ano = '{$this->Ano}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)
					$this->$key = $value;

			$this->conn->freeResult($result);
		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties.if $update is true it updates all database registers (professedly one) with $this' Nombre and Ano
		{				   
			if (!isset($this->conn))
				$this->connect();

			if(isset($this->Nombre) && isset($this->Ano))
			{
				if($update == 'false')
					$this->conn->query("INSERT INTO Porcentaje_de_cuotas_IMSS(Nombre, Ano) VALUES ('{$this->Nombre}','{$this->Ano}')");
				else
					$this->conn->query("UPDATE Porcentaje_de_cuotas_IMSS SET Nombre  = '{$this->Nombre}', Ano = '{$this->Ano}' WHERE Nombre = '{$this->_Nombre}' AND Ano = '{$this->_Ano}'");
				
				foreach($this as $key => $value)

					if(isset($this->$key))

						if($key != 'conn' && $key != 'Nombre' && $key != '_Nombre' && $key != 'Ano' && $key != '_Ano')
							$this->conn->query("UPDATE Porcentaje_de_cuotas_IMSS SET $key  = '$value' WHERE Nombre = '{$this->Nombre}' AND Ano = '{$this->Ano}'");

				return true;
			}

			return false;
		}

		public function dbDelete()//delete this entity from database but Nombre and Ano has to be set before
		{

			if(!isset($this->conn))
				$this->connect();

			if(isset($this->Nombre) && isset($this->Ano))
				$this->conn->query("DELETE FROM Porcentaje_de_cuotas_IMSS WHERE Nombre = '{$this->Nombre}' AND Ano = '{$this->Ano}'");

		}

		public function draw($act)//draws $this Porcentaje de cuotas IMSS. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. If $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div class = \"datos_tab\">Datos</div>";
			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\">";
					echo "<textarea name = \"_Nombre\" class=\"hidden_textarea\" readonly=true>{$this->Nombre}</textarea>";
					echo "<textarea name = \"_Ano\" class=\"hidden_textarea\" readonly=true>{$this->Ano}</textarea>";
					echo "<label class = \"nombre_label\">Nombre</label>";
					echo "<textarea class = \"nombre_textarea\" name = \"Nombre\" title = \"Nombre\" ". ($act == 'EDIT' || $act == 'ADD'?"required=true onkeyup = \"_autocomplete(this, 'Porcentaje_de_cuotas_IMSS', 'Nombre')\">":"readonly=true>") . "{$this->Nombre}</textarea>";
					echo "<label class = \"ano_label\">Año</label>";
					echo "<textarea class = \"ano_textarea\" name = \"Ano\" title = \"Año\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->Ano}</textarea>";
					echo "<label class = \"valor_label\">Valor</label>";
					echo "<textarea class = \"valor_textarea\" name = \"Valor\" title = \"Valor\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->Valor}</textarea>";
				echo "</fieldset>";
					
			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Porcentaje_de_cuotas_IMSS')\" />";//_submit() at common_entities.js
			
			echo "</form>";
		}

	}

?>
