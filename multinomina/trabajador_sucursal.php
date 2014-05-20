<?php
	include_once('connection.php');
	include_once('servicio.php');
	include_once('actividad.php');

//Class Trabajador_Sucursal definition

	class Trabajador_Sucursal
	{
		//class properties
		//data
		private $Trabajador;
		private $Nombre;
		private $_Nombre;//previous
		private $Empresa;
		private $_Empresa;//previous
		private $Fecha_de_ingreso;
		private $_Fecha_de_ingreso;//previous
		private $Servicio;
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

		public function setFromDb()//sets properties from data base, but $Trabajador, $Nombre, $Empresa and $Fecha_de_ingreso has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Trabajador_Sucursal WHERE Trabajador = '{$this->Trabajador}' AND Nombre = '{$this->Nombre}' AND Empresa = '{$this->Empresa}' AND Fecha_de_ingreso = '{$this->Fecha_de_ingreso}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. if $update is true it updates all database registers (professedly one) with $this' Trabajador, Nombre, Empresa and Fecha_de_ingreso
		{
			$actividad = new Actividad();
			$actividad->set('Dato','Relación Trabajador Sucursal');
			$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($trabajador) = $this->conn->fetchRow($result);
			$result = $this->conn->query("SELECT Nombre FROM Empresa WHERE RFC = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($_empresa) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if(isset($this->Trabajador) && isset($this->Nombre) && isset($this->Empresa) && isset($this->Fecha_de_ingreso))
			{
				if($update == 'false')
				{
					$actividad->set('Identificadores',"Trabajador: $trabajador Sucursal: {$this->Nombre} Empresa: $_empresa Fecha de ingreso: {$this->Fecha_de_ingreso}");
					$actividad->set('Operacion','Nuevo');
					$this->conn->query("INSERT INTO Trabajador_Sucursal(Trabajador, Nombre, Empresa, Fecha_de_ingreso, Cuenta) VALUES ('{$this->Trabajador}', '{$this->Nombre}', '{$this->Empresa}', '{$this->Fecha_de_ingreso}', '{$_SESSION['cuenta']}')");
				}
				else
				{
					$servicio = new Servicio();
					$servicio->set('id', $this->Servicio);
					$notation = $servicio->notation();
					$actividad->set('Identificadores',"Trabajador: $trabajador Sucursal: {$this->Nombre} Empresa: $_empresa Fecha de ingreso: {$this->Fecha_de_ingreso} Servicio: $notation");
					$actividad->set('Operacion','Editar');
					$this->conn->query("UPDATE Trabajador_Sucursal SET Nombre  = '{$this->Nombre}', Empresa  = '{$this->Empresa}', Fecha_de_ingreso  = '{$this->Fecha_de_ingreso}' WHERE Trabajador  = '{$this->Trabajador}' AND Nombre = '{$this->_Nombre}' AND Empresa  = '{$this->_Empresa}' AND Fecha_de_ingreso  = '{$this->_Fecha_de_ingreso}' AND Cuenta = '{$_SESSION['cuenta']}'");
				}

				$actividad->dbStore();

				foreach($this as $key => $value)

					if(isset($this->$key) && $key != 'conn' && $key != 'Nombre' && $key != 'Empresa' && $key != 'Fecha_de_ingreso' && $key != '_Nombre' && $key != '_Empresa' && $key != '_Fecha_de_ingreso')
						$this->conn->query("UPDATE Trabajador_Sucursal SET $key = '$value' WHERE Trabajador = '{$this->Trabajador}' AND Nombre = '{$this->Nombre}' AND Empresa = '{$this->Empresa}' AND Fecha_de_ingreso = '{$this->Fecha_de_ingreso}' AND Cuenta = '{$_SESSION['cuenta']}'");
					elseif(!isset($this->$key) && $key == 'Servicio')
						$this->conn->query("UPDATE Trabajador_Sucursal SET Servicio = NULL WHERE Trabajador = '{$this->Trabajador}' AND Nombre = '{$this->Nombre}' AND Empresa = '{$this->Empresa}' AND Fecha_de_ingreso = '{$this->Fecha_de_ingreso}' AND Cuenta = '{$_SESSION['cuenta']}'");

				return true;
			}

			return false;
		}

		public function dbDelete()//delete this entity from database but Trabajador,Nombre,Empresa and Fecha_de_ingreso has to be set before
		{

			if(isset($this->Trabajador) && isset($this->Nombre) && isset($this->Empresa) && isset($this->Fecha_de_ingreso))
			{
				$this->setFromDb();
				$actividad = new Actividad();
				$actividad->set('Operacion','Eliminar');
				$actividad->set('Dato','Relación Trabajador Sucursal');
				$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($trabajador) = $this->conn->fetchRow($result);
				$result = $this->conn->query("SELECT Nombre FROM Empresa WHERE RFC = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($_empresa) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$servicio = new Servicio();
				$servicio->set('id', $this->Servicio);
				$notation = $servicio->notation();
				$actividad->set('Identificadores',"Trabajador: $trabajador Sucursal: {$this->Nombre} Empresa: $_empresa Fecha de ingreso: {$this->Fecha_de_ingreso} Servicio: $notation");
				$actividad->dbStore();
				$this->conn->query("DELETE FROM Trabajador_Sucursal WHERE Trabajador = '{$this->Trabajador}' AND Nombre = '{$this->Nombre}' AND Empresa = '{$this->Empresa}'AND Fecha_de_ingreso = '{$this->Fecha_de_ingreso}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

		}

		public function draw($act)//draws $this Sucursal. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";

			if($act != 'ADD')
				echo "<div onclick = \"show('Servicio_fieldset',this)\" class = \"servicio_tab\">Servicio</div>";

			echo '<form>';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"Trabajador\" class=\"hidden_textarea\" readonly=true>{$this->Trabajador}</textarea>";
					echo "<textarea name = \"_Nombre\" class=\"hidden_textarea\" readonly=true>{$this->Nombre}</textarea>";
					echo "<textarea name = \"_Empresa\" class=\"hidden_textarea\" readonly=true>{$this->Empresa}</textarea>";
					echo "<textarea name = \"_Fecha_de_ingreso\" class=\"hidden_textarea\" readonly=true>{$this->Fecha_de_ingreso}</textarea>";
					echo "<label class = \"empresa_nombre_label\">Empresa</label>";

					if(isset($this->Empresa))
					{
						$result = $this->conn->query("SELECT Nombre FROM Empresa WHERE RFC = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($nombre) = $this->conn->fetchRow($result);
					}
					else
						$nombre = '';

					echo "<textarea class = \"empresa_nombre_textarea\" name = \"empresa_nombre\" title = \"Empresa\" ". ($act == 'EDIT' || $act == 'ADD'?"required=true onkeyup = \"_autocomplete(this, 'Empresa', 'Nombre')\">":"readonly=true>") . "$nombre</textarea>";
					echo "<label class = \"empresa_rfc_label\">RFC</label>";

					if($act == 'DRAW')
						echo "<textarea class = 'empresa_rfc_textarea' name = 'Empresa' title = 'RFC de la empresa'>{$this->Empresa}</textarea>";
					else
						echo "<select class = 'empresa_rfc_select' name = 'Empresa' title = 'RFC de la empresa'>" . (isset($this->Empresa) ? "<option>{$this->Empresa}</option>" : "") . "</select>";

					echo "<label class = \"nombre_label\">Sucursal</label>";

					if(isset($this->Empresa))
						$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

					if($act == 'DRAW')
						echo "<textarea class = 'nombre_textarea' name = 'Nombre' title = 'Sucursal'>{$this->Nombre}</textarea>";
					else
					{
						echo "<select class = 'nombre_select' name = 'Nombre' title = 'Nombre de la sucursal'>";
						$options = "";

						while(isset($result) && list($sucursal) = $this->conn->fetchRow($result))
							$options .= (isset($this->Nombre) && $this->Nombre == $sucursal) ? "<option selected>$sucursal</option>" : "<option>$sucursal</option>";

						echo "$options</select>";
					}

					echo "<label class = \"fecha_de_ingreso_label\">Fecha de ingreso</label>";
					echo "<textarea class = 'fecha_de_ingreso_textarea' name = 'Fecha_de_ingreso' title = 'Fecha de ingreso'>{$this->Fecha_de_ingreso}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";

				echo "</fieldset>";
				echo "<fieldset class = \"Servicio_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'></td><td>id</td><td>Periodo de la nómina</td><td>Registro patronal</td><td>Empresa</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador_Sucursal' dbtable2 = 'Servicio' _id = '{$this->Trabajador},{$this->Nombre},{$this->Empresa}'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<4; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador_Sucursal','Servicio','{$this->Trabajador},{$this->Nombre},{$this->Empresa}',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Trabajador_Sucursal')\" />";//_submit() at common_entities.js

			echo "</form>";
		}

	}

?>
