<?php
	include_once('connection.php');
	include_once('actividad.php');

//Class Photo definition

	class Photo
	{
		//class properties
		private $Nombre;
		private $tmpNombre;//file temporal name (is not gonna be stored at database)
		private $Tipo;
		private $Tamanio;
		private $Contenido;
		private $Width;
		private $Height;
		private $Trabajador;
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

		public function set_photo()//store a worker's photo
		{

			//setting from browser
			if(isset($_FILES['datafile']) && $_FILES['datafile']['size'] > 0)//datafile is the html file input field's identifier
			{
				$this->Nombre = $_FILES['datafile']['name'];
				$this->tmpNombre = $_FILES['datafile']['tmp_name'];
				$this->Tipo = $_FILES['datafile']['type'];
				list($this->Width, $this->Height, $image_type) = getimagesize($this->tmpNombre);
			}

			$this->Trabajador= $_POST['Trabajador'];

			if (!get_magic_quotes_gpc())
				$this->Nombre = addslashes($this->Nombre);

			$actividad = new Actividad();
			$actividad->set('Dato','Fotografía');
			$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($trabajador) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$actividad->set('Identificadores',"Trabajador: $trabajador");
			$this->conn->query("DELETE FROM Photo WHERE Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
			//changing dimensions

			if($image_type == IMAGETYPE_JPEG)
				$image = imagecreatefromjpeg($this->tmpNombre);
			elseif($image_type == IMAGETYPE_GIF )
				$image = imagecreatefromgif($this->tmpNombre);
			elseif($image_type == IMAGETYPE_PNG)
				$image = imagecreatefrompng($this->tmpNombre);

			$new_width = imagesx($image);
			$width = imagesx($image);
			$new_height = imagesy($image);
			$height = imagesy($image);
/*
			if($width > $height)
			{
				$new_width = $_POST['Width'];
				$ratio = $new_width / $width;
				$new_height = $height * $ratio;

				if($new_height > $_POST['Height'])
				{
					$ratio = $_POST['Height'] / $new_height;
					$new_height = $_POST['Height'];
					$new_width = $new_width * $ratio;
				}

			}
			else
			{
				$new_height = $height > $_POST['Height'] ? $_POST['Height'] : $height;
				$ratio = $new_height / $height;
				$new_width = $width * $ratio;

				if($new_width > $_POST['Width'])
				{
					$ratio = $_POST['Width'] / $new_width;
					$new_width = $_POST['Width'];
					$new_height = $new_height * $ratio;
				}

			}
*/
			$new_image = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			if($image_type == IMAGETYPE_JPEG)
			{
				$filename = 'temp/' . uniqid() . '.jpg';
				imagejpeg($new_image,$filename);
			}
			elseif($image_type == IMAGETYPE_GIF)
			{
				$filename = 'temp/' . uniqid() . '.gif';
				imagegif($new_image,$filename);
			}
			elseif($image_type == IMAGETYPE_PNG)
			{
				$filename = 'temp/' . uniqid() . '.png';
				imagepng($new_image,$filename);
			}

			$new_fp = fopen($filename,'r');
			$this->Contenido = fread($new_fp,filesize($filename));
			$this->Contenido = addslashes($this->Contenido);
			$this->Tamanio = filesize($filename);
			$this->Width = $new_width;
			$this->Height = $new_height;
			fclose($new_fp);
			unlink($filename);
			//storing new image
			$this->conn->query("INSERT INTO Photo(Trabajador, Cuenta) VALUES ('{$this->Trabajador}', '{$_SESSION['cuenta']}')");
			$actividad->set('Operacion','Nuevo');
			$actividad->dbStore();

			foreach($this as $key => $value)

				if(isset($this->$key))
				{

					if($key != 'conn' && $key != 'Trabajador' && $key != 'tmpNombre' && $key != 'fp')
						$this->conn->query("UPDATE Photo SET $key  = '$value' WHERE Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");

				}

			return true;
		}

		public function showProperties()//prints properties values
		{

			foreach($this as $key => $value)

				if($key != 'conn' && $key != 'Contenido')
					echo "$key = $value <br />";

		}

		public function setFromDb()//sets properties from data base, but Trabajador has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Photo WHERE Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					$this->$key = $value;

		}

		public function dbDelete()//delete this entity from database but "Trabajador" has to be set before
		{

			if(isset($this->Trabajador))
				$this->conn->query("DELETE FROM Photo WHERE Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");

		}

		public function draw($act,$iframe)//draws $this Photo. if $act == 'ADD' or $act == 'EDIT' the fields can be edited and the form is submitted to store_photo.php, $iframe is the name the iframe is gonna have
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";
			echo "<form enctype = \"multipart/form-data\" action = \"store_photo.php?update=false\" method = \"post\" target = \"$iframe\" class = \"show_form\" onsubmit = \"return form_val(this)\">";
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea class = \"hidden_textarea\" name = \"iframe\" readonly=true>$iframe</textarea>";
					echo "<textarea class = \"hidden_textarea\" name = \"Trabajador\" readonly=true>{$this->Trabajador}</textarea>";
					echo "<textarea class = \"hidden_textarea\" name = \"Width\" readonly=true>{$this->Width}</textarea>";
					echo "<textarea class = \"hidden_textarea\" name = \"Height\" readonly=true>{$this->Height}</textarea>";
					echo "<label class = \"archivo_label\">Archivo</label>";
					echo "<input type = \"file\" class = \"file_input\" name = \"datafile\" id = \"datafile\" title = \"Archivo\" value = \"$this->Nombre\"/>";
				echo "</fieldset>";

				echo '<input type = "submit" title = "Guardar" class = "submit_button" value = "ok" id = "submit_button" />';
			echo "</form>";
			echo "<iframe id=\"$iframe\" name=\"$iframe\" src=\"#\" style=\"visibility:hidden\"></iframe>";
		}

	}

?>
