<?php
	include_once('connection.php');

//Class Trabajador_Salario_minimo definition

	class Trabajador_Salario_minimo
	{
		//class properties
		//data
		private $Trabajador;
		private $Servicio;
		private $_Servicio;//previous
		private $Salario_minimo;
		private $Fecha;
		private $_Fecha;//previous
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

		public function setFromDb()//sets properties from data base, but $Trabajador, $Servicio and $Fecha have to be set before
		{
			$result = $this->conn->query("SELECT * FROM Trabajador_Salario_minimo WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Fecha = '{$this->Fecha}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. if $update is true it updates all database registers (professedly one) with $this' Trabajador, Servicio and Fecha
		{

			if(isset($this->Trabajador) && isset($this->Servicio) && isset($this->Fecha))
			{
				if($update == 'false')
					$this->conn->query("INSERT INTO Trabajador_Salario_minimo(Trabajador, Servicio, Fecha, Cuenta) VALUES('{$this->Trabajador}', '{$this->Servicio}', '{$this->Fecha}', '{$_SESSION['cuenta']}')");
				else
					$this->conn->query("UPDATE Trabajador_Salario_minimo SET Servicio = '{$this->Servicio}', Fecha = '{$this->Fecha}' WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->_Servicio}' AND Fecha = '{$this->_Fecha}' AND Cuenta = '{$_SESSION['cuenta']}'");

				foreach($this as $key => $value)

					if(isset($this->$key) && $key != 'conn' && $key != 'Trabajador' && $key != 'Servicio' && $key != 'Fecha' && $key != '_Servicio' && $key != '_Fecha')
						$this->conn->query("UPDATE Trabajador_Salario_minimo SET $key = '$value' WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Fecha = '{$this->Fecha}' AND Cuenta = '{$_SESSION['cuenta']}'");

				return true;
			}

			return false;
		}

		public function dbDelete()//delete this entity from database but Trabajador, Servicio and Fecha have to be set before
		{

			if(isset($this->Trabajador) && isset($this->Servicio) && isset($this->Fecha))
				$this->conn->query("DELETE FROM Trabajador_Salario_minimo WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Fecha = '{$this->Fecha}' AND Cuenta = '{$_SESSION['cuenta']}'");

		}

		public function draw($act)//draws $this Salario_minimo. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";
			echo "<div onclick = \"show('Servicio_fieldset',this)\" class = \"servicio_tab\">Servicio</div>";
			echo '<form>';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"Trabajador\" class=\"hidden_textarea\" readonly=true>{$this->Trabajador}</textarea>";
					echo "<textarea name = \"_Servicio\" class=\"hidden_textarea\" readonly=true>{$this->Servicio}</textarea>";
					echo "<textarea name = \"_Fecha\" class=\"hidden_textarea\" readonly=true>{$this->Fecha}</textarea>";
					echo "<label class = \"codigo_label\">Código</label>";
					echo "<textarea class = \"codigo_textarea\" name = \"Salario_minimo\" title = \"Código\"". ($act == 'EDIT' || $act == 'ADD'?" required=true>":"readonly=true>")."$this->Salario_minimo</textarea>";
					echo "<label class = \"nombre_label\">Nombre</label>";

					if(isset($this->Salario_minimo))
					{
						$result = $this->conn->query("SELECT Nombre FROM Salario_minimo WHERE Codigo = '{$this->Salario_minimo}' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($nombre) = $this->conn->fetchRow($result);
					}
					else
						$nombre = '';

					echo "<textarea class = \"nombre_textarea\" name = \"Nombre\" onkeyup = \"_autocomplete(this, 'Salario_minimo', 'Nombre')\" title = \"Nombre\"". ($act == 'EDIT' || $act == 'ADD'?" required=true>":"readonly=true>")."$nombre</textarea>";
					echo "<label class = \"fecha_label\">Fecha</label>";
					echo "<textarea class = \"fecha_textarea\" name = \"Fecha\" title = \"Fecha\"". ($act == 'EDIT' || $act == 'ADD'?" required=true>":"readonly=true>")."$this->Fecha</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";//show_cal() at calendar.js

				echo "</fieldset>";
				echo "<fieldset class = \"Servicio_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'></td><td>id</td><td>Periodo de la nómina</td><td>Registro patronal</td><td>Empresa</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador_Salario_minimo' dbtable2 = 'Servicio' _id = '{$this->Trabajador},{$this->Servicio}'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<4; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador_Salario_minimo','Servicio','{$this->Trabajador},{$this->Servicio},{$this->Fecha}',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Trabajador_Salario_minimo')\" />";//_submit() at common_entities.js

			echo "</form>";
		}

	}

?>
