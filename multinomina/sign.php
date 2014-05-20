<?php
	include_once('connection.php');
	include_once('actividad.php');

//Class Sign definition

	class Sign
	{
		//class properties
		private $Nombre;
		private $tmpNombre;//file temporal name (is not gonna be stored at database)
		private $fp;//file pointer (is not gonna be stored at database)
		private $Tipo;
		private $Tamanio;
		private $Contenido;
		private $Width;
		private $Height;
		private $Trabajador;
		private $Usuario;
		private $Cuenta;
		private $conn;//database connection

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

		public function dbStore()
		{
			if (!isset($this->conn))
				$this->connect();

			$actividad = new Actividad();
			$actividad->set('Dato','Firma');
			$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->Trabajador}'");
			list($trabajador) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$actividad->set('Identificadores',"Trabajador: $trabajador, Usuario: {$this->Usuario}");

			if(isset($this->Trabajador))
			{
				$this->conn->query("DELETE FROM Sign WHERE Trabajador = '{$this->Trabajador}'");
				$this->conn->query("INSERT INTO Sign(Trabajador) VALUES('{$this->Trabajador}')");
			}
			elseif(isset($this->Usuario))
			{
				$this->conn->query("DELETE FROM Sign WHERE Usuario = '{$this->Usuario}' AND Cuenta = '{$this->Cuenta}'");
				$this->conn->query("INSERT INTO Sign(Usuario, Cuenta) VALUES('{$this->Usuario}','{$this->Cuenta}')");
			}

			$actividad->set('Operacion','Nuevo');
			$actividad->dbStore();

			foreach($this as $key => $value)

				if(isset($this->$key))
				{

					if($key != 'conn' && $key != 'Trabajador' && $key != 'Usuario' && $key != 'Cuenta' && $key != 'tmpNombre' && $key != 'fp')
					{

						if(isset($this->Trabajador))
							$this->conn->query("UPDATE Sign SET $key  = '$value' WHERE Trabajador = '{$this->Trabajador}'");
						elseif(isset($this->Usuario))
							$this->conn->query("UPDATE Sign SET $key  = '$value' WHERE Usuario = '{$this->Usuario}' AND Cuenta = '{$this->Cuenta}'");

					}

				}

			return true;
		}

		public function setFromBrowser()//sets properties from superglobals $_FILES & $_POST
		{
			
			if(isset($_FILES['datafile']) && $_FILES['datafile']['size'] > 0)//datafile is the html file input field's identifier
			{
				$this->Nombre = $_FILES['datafile']['name'];
				$this->tmpNombre = $_FILES['datafile']['tmp_name'];
				$this->Tamanio = $_FILES['datafile']['size'];
				$this->Tipo = $_FILES['datafile']['type'];
				$this->fp = fopen($this->tmpNombre,'r');
				$this->Contenido = fread($this->fp,filesize($this->tmpNombre));
				$this->Contenido = addslashes($this->Contenido);
				fclose($this->fp);
				list($this->Width, $this->Height) = getimagesize($this->tmpNombre);
			}

			if(isset($_POST['Trabajador']) && $_POST['Trabajador'] != '')
				$this->Trabajador= $_POST['Trabajador'];

			if(isset($_POST['Usuario']))
				$this->Usuario= $_POST['Usuario'];

			if (!get_magic_quotes_gpc())
				$this->Nombre = addslashes($this->Nombre);

		}

		public function showProperties()//prints properties values
		{
			foreach($this as $key => $value)
				if($key != 'conn' && $key != 'Contenido')
					echo "$key = $value <br />";
		}

		private function connect()//connects to data base
		{
			$this->conn = new Connection('multinomina');
		}

		public function setFromDb()//sets properties from data base, but Trabajador, Usuario and Cuenta have to be set before
		{

			if(!isset($this->conn))
				$this->connect();

			if(isset($this->Trabajador))
				$result = $this->conn->query("SELECT * FROM Sign WHERE Trabajador = '{$this->Trabajador}'");
			elseif(isset($this->Usuario))
				$result = $this->conn->query("SELECT * FROM Sign WHERE Usuario = '{$this->Usuario}' AND Cuenta = '{$this->Cuenta}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					$this->$key = $value;

		}

		public function dbDelete()//delete this entity from database but Trabajador, Usuario and Cuenta have to be set before
		{

			if(!isset($this->conn))
				$this->connect();

			if(isset($this->Trabajador))
				$this->conn->query("DELETE FROM Sign WHERE Trabajador = '{$this->Trabajador}'");
			elseif(isset($this->Usuario))
				$this->conn->query("DELETE FROM Sign WHERE Usuario = '{$this->Usuario}' AND Cuenta = '{$this->Cuenta}'");

		}

		public function draw($act,$iframe)//draws $this Sign. if $act == 'ADD' or $act == 'EDIT' the fields can be edited and the form is submitted to store_Sign.php, $iframe is the name the iframe is gonna have
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";
			echo "<form enctype = \"multipart/form-data\" action = \"store_sign.php?update=false\" method = \"post\" target = \"$iframe\" class = \"show_form\" onsubmit = \"return form_val(this)\">";
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea class = \"hidden_textarea\" name = \"iframe\" readonly=true>$iframe</textarea>";
					echo "<textarea class = \"hidden_textarea\" name = \"Trabajador\" readonly=true>{$this->Trabajador}</textarea>";
					echo "<textarea class = \"hidden_textarea\" name = \"Usuario\" readonly=true>{$this->Usuario}</textarea>";
					echo "<label class = \"archivo_label\">Archivo</label>";
					echo "<input type = \"file\" class = \"file_input\" name = \"datafile\" id = \"datafile\" title = \"Archivo\" value = \"{$this->Nombre}\"/>";
				echo "</fieldset>";

				echo '<input type = "submit" title = "Guardar" class = "submit_button" value = "ok" id = "submit_button" />';
			echo "</form>";
			echo "<iframe id=\"$iframe\" name=\"$iframe\" src=\"#\" style=\"visibility:hidden\"></iframe>";
		}

	}

?>
