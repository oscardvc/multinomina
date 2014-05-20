<?php
	include_once('connection.php');

//Class Servicio_Trabajador definition

	class Servicio_Trabajador
	{
		//class properties
		//data
		private $Servicio;
		private $_Servicio;//previous
		private $Trabajador;
		private $Fecha_de_ingreso_servicio;
		private $Fecha_de_ingreso_cliente;
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

		public function setFromDb()//sets properties from data base, but $Servicio and $Trabajador has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. if $update is true it updates all database registers (professedly one) with $this' Trabajador and Servicio
		{

			if(isset($this->Servicio) && isset($this->Trabajador))
			{
				if($update == 'false')
					$this->conn->query("INSERT INTO Servicio_Trabajador(Servicio, Trabajador, Cuenta) VALUES('{$this->Servicio}', '{$this->Trabajador}', '{$_SESSION['cuenta']}')");
				else
					$this->conn->query("UPDATE Servicio_Trabajador SET Servicio = '{$this->Servicio}' WHERE Servicio = '{$this->_Servicio}' AND Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");

				foreach($this as $key => $value)

					if(isset($this->$key) && $key != 'conn' && $key != 'Servicio' && $key != '_Servicio' && $key != 'Trabajador')
						$this->conn->query("UPDATE Servicio_Trabajador SET $key = '$value' WHERE Servicio = '{$this->Servicio}' AND Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");

				return true;
			}

			return false;
		}

		public function dbDelete()//delete this entity from database but Servicio and Trabajador have to be set before
		{

			if(isset($this->Servicio) && isset($this->Trabajador))
				$this->conn->query("DELETE FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");

		}

		public function draw($act)//draws $this Servicio_Trabajador relationship. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";
			echo "<div onclick = \"show('Servicio_fieldset',this)\" class = \"servicio_tab\">Servicio</div>";
			echo '<form>';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"Trabajador\" class=\"hidden_textarea\" readonly=true>{$this->Trabajador}</textarea>";
					echo "<textarea name = \"_Servicio\" class=\"hidden_textarea\" readonly=true>{$this->Servicio}</textarea>";
					echo "<label class = \"fecha_de_ingreso_servicio_label\">Fecha de ingreso al servicio</label>";
					echo "<textarea class = \"fecha_de_ingreso_servicio_textarea\" name = \"Fecha_de_ingreso_servicio\" title = \"Fecha de ingreso al servicio\"". ($act == 'EDIT' || $act == 'ADD'?" required=true>":"readonly=true>") . "{$this->Fecha_de_ingreso_servicio}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)'/>";//show_cal() at calendar.js

					echo "<label class = \"fecha_de_ingreso_cliente_label\">Fecha de ingreso con el cliente</label>";
					echo "<textarea class = \"fecha_de_ingreso_cliente_textarea\" name = \"Fecha_de_ingreso_cliente\" title = \"Fecha de ingreso con el cliente\"". ($act == 'EDIT' || $act == 'ADD'?" required=true>":"readonly=true>") . "{$this->Fecha_de_ingreso_cliente}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)'/>";//show_cal() at calendar.js

					if($act == 'DRAW')
					{
						echo "<label class = \"credencial_label\">Credencial</label>";
						echo "<img class = 'credencial_button' onclick = \"cred_menu(this,'{$this->Servicio}','{$this->Trabajador}')\" />";//function credenciales at servicio.js
					}

				echo "</fieldset>";
				echo "<fieldset class = \"Servicio_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'></td><td>id</td><td>Periodo de la nómina</td><td>Registro patronal</td><td>Empresa</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Servicio_Trabajador' dbtable2 = 'Servicio' _id = '{$this->Trabajador},{$this->Servicio}'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<4; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Servicio_Trabajador','Servicio','{$this->Servicio},{$this->Trabajador}',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Servicio_Trabajador')\" />";//_submit() at common_entities.js

			echo "</form>";
		}

	}

?>
