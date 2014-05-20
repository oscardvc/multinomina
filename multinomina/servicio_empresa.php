<?php
	include_once('connection.php');

//Class Servicio_Empresa definition

	class Servicio_Empresa
	{
		//class properties
		//data
		private $Empresa;
		private $_Empresa;//previous
		private $Servicio;
		private $Fecha_de_asignacion;
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

		public function setFromDb()//sets properties from data base, but $Servicio and $Empresa have to be set before
		{
			$result = $this->conn->query("SELECT * FROM Servicio_Empresa WHERE Servicio = '{$this->Servicio}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. if $update is true it updates all database registers (professedly one) with $this' Servicio and Empresa
		{

			if(isset($this->Servicio) && isset($this->Empresa))
			{

				if($update == 'false')
					$this->conn->query("INSERT INTO Servicio_Empresa(Servicio, Empresa, Cuenta) VALUES('{$this->Servicio}', '{$this->Empresa}', '{$_SESSION['cuenta']}')");
				else
					$this->conn->query("UPDATE Servicio_Empresa SET Empresa = '{$this->Empresa}' WHERE Empresa = '{$this->_Empresa}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");

				foreach($this as $key => $value)

					if(isset($this->$key) && $key != 'conn' && $key != 'Empresa' && $key != '_Empresa' && $key != 'Servicio')
						$this->conn->query("UPDATE Servicio_Empresa SET $key = '$value' WHERE Servicio = '{$this->Servicio}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

				return true;
			}

			return false;
		}

		public function dbDelete()//delete this entity from database but Servicio and Empresa have to be set before
		{

			if(isset($this->Servicio) && isset($this->Empresa))
				$this->conn->query("DELETE FROM Servicio_Empresa WHERE Servicio = '{$this->Servicio}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

		}

		public function draw($act)//draws $this Servicio_Empresa relationship. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";
			echo "<div onclick = \"show('Empresa_fieldset',this)\" class = \"empresa_tab\">Empresa</div>";
			echo '<form>';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"Servicio\" class=\"hidden_textarea\" readonly=true>{$this->Servicio}</textarea>";
					echo "<textarea name = \"_Empresa\" class=\"hidden_textarea\" readonly=true>{$this->Empresa}</textarea>";
					echo "<label class = \"fecha_de_asignacion_label\">Fecha de asignación</label>";
					echo "<textarea class = \"fecha_de_asignacion_textarea\" name = \"Fecha_de_asignacion\" title = \"Fecha de asignación\"". ($act == 'EDIT' || $act == 'ADD'?" required=true>":"readonly=true>")."{$this->Fecha_de_asignacion}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)'/>";

				echo "</fieldset>";
				echo "<fieldset class = \"Empresa_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'></td><td>RFC</td><td>Nombre</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Servicio_Empresa' dbtable2 = 'Empresa' _id = '{$this->Servicio},{$this->Empresa}'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<2; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Servicio_Empresa','Empresa','{$this->Empresa}',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Servicio_Empresa')\" />";//_submit() at common_entities.js

			echo "</form>";
		}

	}

?>
