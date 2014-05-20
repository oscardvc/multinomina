<?php
	include_once('connection.php');

//Class Monto_fijo_mensual definition

	class Monto_fijo_mensual
	{
		//class properties
		//data
		private $id;
		private $Monto_fijo_mensual;
		private $Fecha_de_inicio;
		private $Cobrar_diferencia_inicial;
		private $Fecha_de_cobro;
		private $Retencion_INFONAVIT;
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

		private function setID()
		{
			$result = $this->conn->query("SELECT id FROM Monto_fijo_mensual WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY id DESC LIMIT 1");
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
				{

					if($key == 'Cobrar_diferencia_inicial')
						$this->$key = 'true';
					else
						$this->$key = trim($_POST["$key"]);

				}
				elseif($key == 'Cobrar_diferencia_inicial')
					$this->$key = 'false';

		}

		public function showProperties()//prints properties values
		{

			foreach($this as $key => $value)

				if($key != 'conn')
					echo "$key = $value <br />";

		}

		public function setFromDb()//sets properties from data base, but $id has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Monto_fijo_mensual WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties if $update is true it updates all database registers (professedly one) with $this' id
		{

			if($update == 'false')
			{
				$this->setID();
				$this->conn->query("INSERT INTO Monto_fijo_mensual(id, Cuenta) VALUES ('{$this->id}', '{$_SESSION['cuenta']}')");
			}

			foreach($this as $key => $value)

				if(isset($this->$key) && $key != 'conn' && $key != 'id')
					$this->conn->query("UPDATE Monto_fijo_mensual SET $key = '$value' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			return true;
		}

		public function dbDelete()//delete this entity from database but id has to be set before
		{

			if(isset($this->id))
				$this->conn->query("DELETE FROM Monto_fijo_mensual WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

		}

		public function draw($act)//draws $this Monto_fijo_mensual. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div class = \"datos_tab\">Datos</div>";
			echo "<form class = \"show_form\">";
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>{$this->id}</textarea>";
					echo "<textarea name = \"Retencion_INFONAVIT\" class=\"hidden_textarea\" readonly=true>{$this->Retencion_INFONAVIT}</textarea>";
					echo "<label class = \"monto_fijo_mensual_label\">Monto fijo mensual</label>";
					echo "<textarea class=\"monto_fijo_mensual_textarea\" name = \"Monto_fijo_mensual\" title = \"Monto fijo mensual\"  " .  ($act == 'EDIT' || $act == 'ADD'? "required=true>":"readonly=true>"). "{$this->Monto_fijo_mensual}</textarea>";
					echo "<label class = \"fecha_de_inicio_label\">Fecha de inicio</label>";
					echo "<textarea class=\"fecha_de_inicio_textarea\" name = \"Fecha_de_inicio\" title = \"Fecha de inicio\"  " .  ($act == 'EDIT' || $act == 'ADD'? "required=true>":"readonly=true>"). "{$this->Fecha_de_inicio}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";//show_calendar() at calendar.js

					echo "<label class = \"cobrar_diferencia_inicial_label\">Cobrar diferencia inicial</label>";
					echo "<input type = \"checkbox\" class = \"cobrar_diferencia_inicial_input\" name = \"Cobrar_diferencia_inicial\" title = \"Cobrar diferencia inicial\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->Cobrar_diferencia_inicial == 'true'?" checked/>":"/>");
					echo "<label class = \"fecha_de_cobro_label\">Fecha de cobro</label>";
					echo "<textarea class=\"fecha_de_cobro_textarea\" name = \"Fecha_de_cobro\" title = \"Fecha de cobro\"  " .  ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>"). "{$this->Fecha_de_cobro}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";//show_calendar() at calendar.js

				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\" class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Monto_fijo_mensual')\" />";//_submit() at common_entities.js
			
			echo "</form>";
		}

	}

?>
