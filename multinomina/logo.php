<?php
	include_once('connection.php');
	include_once('actividad.php');

//Class Logo definition

	class Logo
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
		private $Empresa;
		private $Sucursal;
		private $Empresa_sucursal;
		private $conn;//database connection

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

		public function dbStore()//store a worker's photo
		{
			$actividad = new Actividad();
			$actividad->set('Dato','Logotipo');
			$result = $this->conn->query("SELECT Nombre FROM Empresa WHERE (RFC = '{$this->Empresa}' OR RFC = '{$this->Empresa_sucursal}')AND Cuenta = '{$_SESSION['cuenta']}'");
			list($empresa) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$actividad->set('Identificadores',"Empresa: $empresa" . ($this->Sucursal != '' ? " Sucursal: {$this->Sucursal}" : ""));

			if(isset($this->Sucursal) && $this->Sucursal != '')
			{
				$this->conn->query("DELETE FROM Logo WHERE Sucursal = '{$this->Sucursal}' AND Empresa_sucursal = '{$this->Empresa_sucursal}' AND Cuenta = '{$_SESSION['cuenta']}'");
				$this->conn->query("INSERT INTO Logo(Sucursal, Empresa_sucursal, Cuenta) VALUES ('{$this->Sucursal}', '{$this->Empresa_sucursal}', '{$_SESSION['cuenta']}')");
			}
			else
			{
				$this->conn->query("DELETE FROM Logo WHERE Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");
				$this->conn->query("INSERT INTO Logo(Empresa, Cuenta) VALUES ('{$this->Empresa}', '{$_SESSION['cuenta']}')");
			}

			$actividad->set('Operacion','Nuevo');
			$actividad->dbStore();

			foreach($this as $key => $value)

				if(isset($this->$key))
				{

					if($key != 'conn' && $key != 'Empresa' && $key != 'Sucursal' && $key != 'Empresa_sucursal' && $key != 'tmpNombre' && $key != 'fp')
					{

						if(isset($this->Sucursal) && $this->Sucursal != '')
							$this->conn->query("UPDATE Logo SET $key  = '$value' WHERE Sucursal = '{$this->Sucursal}' AND Empresa_sucursal = '{$this->Empresa_sucursal}' AND Cuenta = '{$_SESSION['cuenta']}'");
						else
							$this->conn->query("UPDATE Logo SET $key  = '$value' WHERE Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

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

			if(isset($_POST['Empresa']))
				$this->Empresa= $_POST['Empresa'];

			if(isset($_POST['Sucursal']))
				$this->Sucursal= $_POST['Sucursal'];

			if(isset($_POST['Empresa_sucursal']))
				$this->Empresa_sucursal= $_POST['Empresa_sucursal'];

			if (!get_magic_quotes_gpc())
				$this->Nombre = addslashes($this->Nombre);

		}

		public function showProperties()//prints properties values
		{

			foreach($this as $key => $value)

				if($key != 'conn' && $key != 'Contenido')
					echo "$key = $value <br />";

		}

		public function setFromDb()//sets properties from data base, but Empresa, Sucursal and Empresa_sucursal have to be set before
		{

			if(isset($this->Sucursal) && $this->Sucursal != '')
				$result = $this->conn->query("SELECT * FROM Logo WHERE Sucursal = '{$this->Sucursal}' AND Empresa_sucursal = '{$this->Empresa_sucursal}' AND Cuenta = '{$_SESSION['cuenta']}'");
			else
				$result = $this->conn->query("SELECT * FROM Logo WHERE Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function dbDelete()//delete this entity from database but Empresa, Sucursal and Empresa_sucursal has to be set before
		{

			if(isset($this->Sucursal) && $this->Sucursal != '')
				$this->conn->query("DELETE FROM Logo WHERE Sucursal = '{$this->Sucursal}' AND Empresa_sucursal = '{$this->Empresa_sucursal}' AND Cuenta = '{$_SESSION['cuenta']}'");
			elseif(isset($this->Empresa))
				$this->conn->query("DELETE FROM Logo WHERE Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

		}

		public function draw($act,$iframe)//draws $this Logo. if $act == 'ADD' or $act == 'EDIT' the fields can be edited and the form is submitted to store_Logo.php, $iframe is the name the iframe is gonna have
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";
			echo "<form enctype = \"multipart/form-data\" action = \"store_logo.php?update=false\" method = \"post\" target = \"$iframe\" class = \"show_form\" onsubmit = \"return form_val(this)\">";
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea class = \"hidden_textarea\" name = \"iframe\" readonly=true>$iframe</textarea>";
					echo "<textarea class = \"hidden_textarea\" name = \"Empresa\" readonly=true>{$this->Empresa}</textarea>";
					echo "<textarea class = \"hidden_textarea\" name = \"Sucursal\" readonly=true>{$this->Sucursal}</textarea>";
					echo "<textarea class = \"hidden_textarea\" name = \"Empresa_sucursal\" readonly=true>{$this->Empresa_sucursal}</textarea>";
					echo "<label class = \"archivo_label\">Archivo</label>";
					echo "<input type = \"file\" class = \"file_input\" name = \"datafile\" id = \"datafile\" title = \"Archivo\" value = \"$this->Nombre\"/>";
				echo "</fieldset>";

				echo '<input type = "submit" title = "Guardar" class = "submit_button" value = "ok" id = "submit_button" />';
			echo "</form>";
			echo "<iframe id=\"$iframe\" name=\"$iframe\" src=\"#\" style=\"visibility:hidden\"></iframe>";
		}

	}

?>
