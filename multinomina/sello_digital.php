<?php
	include_once('connection.php');
	include_once('actividad.php');

//Class Sello_digital definition

	class Sello_digital
	{
		//class properties
		//data
		private $id;
		private $Certificado;
		private $Certificado_nombre;
		private $Clave_privada;
		private $Clave_privada_nombre;
		private $Contrasena;
		private $Empresa;
		private $Sucursal;
		private $Empresa_sucursal;
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

		private function setID()
		{
			$result = $this->conn->query("SELECT id FROM Sello_digital WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY id DESC LIMIT 1");
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

			if(!isset($this->name))
				$this->setFromDB();

			return $this->$name;
		}

		public function setFromBrowser()//sets properties from superglobals $_FILES & $_POST
		{

			if(isset($_POST['Contrasena']))
				$this->Contrasena = $_POST['Contrasena'];
			else
				return "Es necesaria una contraseña";
			
			if(isset($_FILES['Certificado']) && $_FILES['Certificado']['size'] > 0)
			{
				$this->Certificado_nombre = $_FILES['Certificado']['name'];
				$cer_der = file_get_contents($_FILES['Certificado']['tmp_name']);
				$this->Certificado = base64_encode($cer_der);
				$cer_pem = $this->getCerPem();
				$data = openssl_x509_parse($cer_pem);

				if(gettype($data) == 'boolean')
					return "Archivo de certificado erroneo";

			}
			else
				return "Es necesario un archivo de certificado de sello digital";

			if(isset($_FILES['Clave_privada']) && $_FILES['Clave_privada']['size'] > 0)
			{
				$key_pem = 'temp/' . uniqid() . '.key.pem';
				system("openssl pkcs8 -inform DER -in {$_FILES['Clave_privada']['tmp_name']} -passin pass:{$this->Contrasena} -out $key_pem");
				$key = file_get_contents($key_pem);
				$privateKey = openssl_pkey_get_private($key, $this->Contrasena);
				unlink($key_pem);
				if(gettype($privateKey) != 'boolean')
				{
					$this->Clave_privada_nombre = $_FILES['Clave_privada']['name'];
					$this->Clave_privada = $key;
				}
				else
					return "Error en clave privada o contraseña";

			}
			else
				return "Es necesario un archivo de clave privada";

			if(isset($cer_pem) && isset($privateKey) && ! openssl_x509_check_private_key($cer_pem, $privateKey))
				return "La clave privada no corresponde al certificado";

			if(isset($_POST['Empresa']))
				$this->Empresa = $_POST['Empresa'];

			if(isset($_POST['Sucursal']))
				$this->Sucursal = $_POST['Sucursal'];

			if(isset($_POST['Empresa_sucursal']))
				$this->Empresa_sucursal = $_POST['Empresa_sucursal'];

			return "Listo";
		}

		public function showProperties()//prints properties values
		{

			foreach($this as $key => $value)

				if($key != 'conn' && $key != 'Certificado' && $key != 'Clave_privada')
					echo "$key = $value <br />";

		}

		public function setFromDb()//sets properties from data base, but $id has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Sello_digital WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function dbStore($update)//It always stores a new database register
		{

			if($update == 'false')
			{
				$this->setID();
				$actividad = new Actividad();
				$actividad->set('Dato','Sello digital');
				$result = $this->conn->query("SELECT Nombre FROM Empresa WHERE RFC = '{$this->Empresa}' OR RFC = '{$this->Empresa_sucursal}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($empresa) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$actividad->set('Identificadores',"id: {$this->id} Empresa: $empresa" . ($this->Sucursal != '' ? " Sucursal: {$this->Sucursal}" : ""));

				if($update == 'false')
				{
					$this->conn->query("INSERT INTO Sello_digital(id, Cuenta) VALUES('{$this->id}', '{$_SESSION['cuenta']}')");
					$actividad->set('Operacion','Nuevo');
					$actividad->dbStore();
				}

				foreach($this as $key => $value)

					if(isset($this->$key) && $key != 'conn' && $key != 'id')
						$status = $this->conn->query("UPDATE Sello_digital SET $key  = '$value' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

				return 'Listo';
			}
			else
				return "El certificado no es editable";

		}

		public function dbDelete()//delete this entity from database but id has to be set before
		{

			if(isset($this->id))
			{
				$this->setFromDb();
				$actividad = new Actividad();
				$actividad->set('Operacion','Eliminar');
				$actividad->set('Dato','Sello digital');
				$result = $this->conn->query("SELECT Nombre FROM Empresa WHERE RFC = '{$this->Empresa}' OR RFC = '{$this->Empresa_sucursal}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($empresa) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$actividad->set('Identificadores',"id: {$this->id} Empresa: $empresa" . ($this->Sucursal != '' ? " Sucursal: {$this->Sucursal}" : ""));
				$actividad->dbStore();
				$this->conn->query("DELETE FROM Sello_digital WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

		}

		public function getCerPem()
		{

			if(!isset($this->Certificado))
				$this->setFromDB();

			return "-----BEGIN CERTIFICATE-----\n" . chunk_split($this->Certificado, 64, "\n") . "-----END CERTIFICATE-----\n";
		}

		public function getClavePrivada()
		{
			if(! isset($this->Clave_privada))
				$this->setFromDB();

			$privateKey = openssl_pkey_get_private($this->Clave_privada, $this->Contrasena);
			return $privateKey;
		}

		public function getKeyDes3()
		{
			if(! isset($this->Clave_privada))
				$this->setFromDB();

			$result = $this->conn->query("SELECT Password FROM CFDI_User WHERE Empresa = '{$this->Empresa}' OR Empresa = '{$this->Empresa_sucursal}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($password) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$key_pem = 'temp/' . uniqid() . '.key.pem';
			file_put_contents($key_pem, $this->Clave_privada);
			$key_des3 = 'temp/' . uniqid() . '.enc.key';
			system("openssl rsa -in $key_pem -des3 -out $key_des3 -passout pass:'$password'");
			$key = file_get_contents($key_des3);
			unlink($key_pem);
			unlink($key_des3);
			return $key;
		}

		public function getNoCertificado()
		{

			if(! isset($this->Certificado))
				$this->setFromDB();

			$cer_pem = "-----BEGIN CERTIFICATE-----\n" . chunk_split($this->Certificado, 64, "\n") . "-----END CERTIFICATE-----\n";
			$data = openssl_x509_parse($cer_pem);

			if(gettype($data) != 'boolean')
			{
				$hex = $this->bcdechex($data['serialNumber']);
				$numero_de_certificado = $this->serialFormat($hex);
			}
			else
				$numero_de_certificado = null;

			return $numero_de_certificado;
		}

		private function bcdechex($dec)
		{
			$last = bcmod($dec, 16);
			$remain = bcdiv(bcsub($dec, $last), 16);

			if($remain == 0)
				return dechex($last);
			else
				return $this->bcdechex($remain).dechex($last);

		}

		private function serialFormat($str)
		{
			$_str = str_split($str);
			$len = count($_str);
			$serial = '';

			for($i=0; $i<$len; $i++)

				if($i % 2 != 0)
					$serial .= $_str[$i];

			return $serial;
		}

		public function draw($act,$iframe = 0)//draws $this Sello digital. if $act == 'ADD' or $act == 'EDIT' the fields can be edited and the form is submitted to store_sello_digital.php if $act == 'DRAW' the fields can't be edited and the form is not submitted, $iframe is the name the iframe is gonna have
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";

			if($act == 'ADD')
				$action = 'action = "store_sello_digital.php?update=false"';

			if($act == 'ADD')
				echo "<form enctype = \"multipart/form-data\" $action method = \"post\" target = \"$iframe\" class = \"show_form\" onsubmit = \"return form_val(this)\">";
			else
				echo "<form class = \"show_form\">";

			echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
				echo "<textarea class = \"hidden_textarea\" name = \"iframe\" readonly=true>$iframe</textarea>";
				echo "<textarea class = \"hidden_textarea\" name = \"id\" readonly=true>{$this->id}</textarea>";
				echo "<textarea class = \"hidden_textarea\" name = \"Empresa\" readonly=true>{$this->Empresa}</textarea>";
				echo "<textarea class = \"hidden_textarea\" name = \"Sucursal\" readonly=true>{$this->Sucursal}</textarea>";
				echo "<textarea class = \"hidden_textarea\" name = \"Empresa_sucursal\" readonly=true>{$this->Empresa_sucursal}</textarea>";
				echo "<label class = \"cer_label\">Certificado (.cer)</label>";

				if ($act == 'ADD')
				{
					echo "<input type = \"file\" class = \"file_input\" name = \"Certificado\" id = \"Certificado\" title = \"Archivo .cer\" value = \"{$this->Certificado_nombre}\" onchange = \"copy_file_name(this)\"/>";
					echo "<textarea class = \"Certificado_aux_textarea\"></textarea>";
					echo "<img class = 'aux_button'\>";
				}
				elseif($act == 'DRAW' || $act == 'EDIT')
					echo "<textarea class = \"archivo_textarea\" name = \"Certificado\" title = \"Certificado\" readonly=true>{$this->Certificado_nombre}</textarea>";

				echo "<label class = \"key_label\">Clave privada (.key)</label>";

				if ($act == 'ADD')
				{
					echo "<input type = \"file\" class = \"file_input\" name = \"Clave_privada\" id = \"Clave_privada\" title = \"Archivo .key\" value = \"{$this->Clave_privada_nombre}\" onchange = \"copy_file_name(this)\"/>";
					echo "<textarea class = \"Clave_privada_aux_textarea\"></textarea>";
					echo "<img class = 'aux_button'\>";
				}
				elseif($act == 'DRAW' || $act == 'EDIT')
					echo "<textarea class = \"archivo_textarea\" name = \"Clave_privada\" title = \"Certificado\" readonly=true>{$this->Clave_privada_nombre}</textarea>";

				echo "<label class = \"contrasena_label\">Contraseña</label>";
				echo "<input type = 'password' class = 'contrasena_input' name = 'Contrasena' title = 'Contraseña de la clave privada' value = '{$this->Contrasena}'" . ($act != 'DRAW' ? "required = true />" : "readonly = true />");
			echo "</fieldset>";

			if($act == 'ADD')
				echo '<input type = "submit" title = "Guardar" class = "submit_button" value = "ok" id = "submit_button" />';
			
			echo "</form>";
			echo "<iframe id=\"$iframe\" name=\"$iframe\" src=\"#\" style=\"visibility:hidden\"></iframe>";

		}

	}

?>
