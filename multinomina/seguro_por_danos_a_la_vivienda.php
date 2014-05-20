<?php
	include_once('connection.php');

//Class Seguro_por_danos_a_la_vivienda definition

	class Seguro_por_danos_a_la_vivienda
	{
		//class properties
		//data
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

		public function connect()//connects to data base
		{
			$this->conn = new Connection('multinomina');
		}

		public function setFromDb()//sets properties from data base, but $Ano has to be set before
		{

			if(!isset($this->conn))
				$this->connect();
			
			$result = $this->conn->query("SELECT * FROM Seguro_por_danos_a_la_vivienda WHERE Ano = {$this->Ano}");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)
					$this->$key = $value;

			$this->conn->freeResult($result);
		}

		public function dbDelete()//delete this entity from database but Ano has to be set before
		{
			if(!isset($this->conn))
				$this->connect();

			if(isset($this->Ano))
				$this->conn->query("DELETE FROM Seguro_por_danos_a_la_vivienda WHERE Ano = '{$this->Ano}'");

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. if $update is true it updates all database registers (professedly one) with $this' Ano
		{
			if (!isset($this->conn))
				$this->connect();

			if(isset($this->Ano))
			{
				if($update == 'false')
					$this->conn->query("INSERT INTO Seguro_por_danos_a_la_vivienda(Ano) VALUES ('{$this->Ano}')");
				else
					$this->conn->query("UPDATE Seguro_por_danos_a_la_vivienda SET Ano  = '{$this->Ano}' WHERE Ano = '{$this->_Ano}'");
				
				foreach($this as $key => $value)
					
					if(isset($this->$key))
					{

						if($key != 'conn' && $key != 'Ano' && $key != '_Ano')
							$this->conn->query("UPDATE Seguro_por_danos_a_la_vivienda SET $key  = '$value' WHERE Ano = '{$this->Ano}'");

					}

				return true;
			}

			return false;
		}

		public function draw($act)//draws $this Seguro por danos a la vivienda. if $act == 'EDIT'  or if $act == 'ADD', the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div class = \"datos_tab\">Datos</div>";
			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"_Ano\" class=\"hidden_textarea\" readonly=true>"."{$this->Ano}</textarea>";
					echo "<label class = \"ano_label\">Año</label>";
					echo "<textarea class = \"ano_textarea\" name = \"Ano\" title = \"Año\" ". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Ano}</textarea>";
					echo "<label class = \"valor_label\">Valor</label>";
					echo "<textarea class = \"valor_textarea\" name = \"Valor\" title = \"Valor\" ". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Valor}</textarea>";
				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Seguro_por_danos_a_la_vivienda')\" />";//_submit() at common_entities.js
			
			echo "</form>";

		}

	}

?>
