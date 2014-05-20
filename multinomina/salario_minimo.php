<?php
	include_once('connection.php');
	include_once('actividad.php');

//Class Salario_minimo definition

	class Salario_minimo
	{
		//class properties
		//data
		private $id;
		private $Codigo;
		private $Nombre;
		private $A;
		private $B;
		private $C;
		private $Ano;
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

		public function setID()
		{
			$result = $this->conn->query("SELECT id FROM Salario_minimo WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY id DESC LIMIT 1");
			$row = $this->conn->fetchRow($result);

			if(isset($row))
			{
				list($this->id) = $row;
				$this->id ++;
			}
			else
				$this->id = 1;

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

		public function setFromDb()//sets properties from data base, but $id has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Salario_minimo WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. if $update is true it updates all database registers (professedly one) with $this' id
		{
			$actividad = new Actividad();
			$actividad->set('Dato','Salario mínimo');

			if($update == 'false')
			{
				$this->setID();
				$this->conn->query("INSERT INTO Salario_minimo(id, Cuenta) VALUES ('{$this->id}', '{$_SESSION['cuenta']}')");
				$actividad->set('Identificadores',"id: {$this->id} Nombre: {$this->Nombre} Código: {$this->id} Año: {$this->Ano}");
				$actividad->set('Operacion','Nuevo');
			}
			else
			{
				$actividad->set('Identificadores',"id: {$this->id} Nombre: {$this->Nombre} Código: {$this->Codigo} Año: {$this->Ano}");
				$actividad->set('Operacion','Editar');
			}

			$actividad->dbStore();

			foreach($this as $key => $value)

				if(isset($this->$key) && $this->$key != '')
				{

					if($key != 'conn' && $key != 'id')
						$this->conn->query("UPDATE Salario_minimo SET $key = '$value' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

				}
				elseif($key == 'Codigo')
					$this->conn->query("UPDATE Salario_minimo SET Codigo = '{$this->id}' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			return true;
		}

		public function dbDelete()//delete this entity from database but id has to be set before
		{

			if(isset($this->id))
			{
				$this->setFromDb();
				$actividad = new Actividad();
				$actividad->set('Operacion','Eliminar');
				$actividad->set('Dato','Salario mínimo');
				$actividad->set('Identificadores',"id: {$this->id} Nombre: {$this->Nombre} Código: {$this->Codigo} Año: {$this->Ano}");
				$actividad->dbStore();
				$this->conn->query("DELETE FROM Salario_minimo WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

		}

		public function draw($act)//draws $this Salario_minimo. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";
			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>{$this->id}</textarea>";
					echo "<label class = \"codigo_label\">Código</label>";
					echo "<textarea class = \"codigo_textarea\" name = \"Codigo\" title = \"El código se calcula automáticamente\" readonly=true>{$this->Codigo}</textarea>";
					echo "<label class = \"nombre_label\">Nombre</label>";
					echo "<textarea class = \"nombre_textarea\" name = \"Nombre\" onkeyup = \"_autocomplete(this, 'Salario_minimo', 'Nombre')\" title = \"Nombre\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->Nombre}</textarea>";
					echo "<label class = \"a_label\">A</label>";
					echo "<textarea class = \"a_textarea\" name = \"A\" title = \"Zona A\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->A}</textarea>";
					echo "<label class = \"b_label\">B</label>";
					echo "<textarea class = \"b_textarea\" name = \"B\" title = \"Zona B\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->B}</textarea>";
					echo "<label class = \"c_label\">C</label>";
					echo "<textarea class = \"c_textarea\" name = \"C\" title = \"Zona C\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->C}</textarea>";
					echo "<label class = \"ano_label\">Año</label>";
					echo "<textarea class = \"ano_textarea\" name = \"Ano\" title = \"Año\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->Ano}</textarea>";
				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Salario_minimo')\" />";//_submit() at common_entities.js
			
			echo "</form>";

		}

	}

?>
