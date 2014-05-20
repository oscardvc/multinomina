<?php
	include_once('connection.php');

//Class instrumento_notarial definition

	class Instrumento_notarial
	{
		//class properties
		//data
		private $Tipo_de_documento;
		private $Numero_de_instrumento;
		private $id;//used to store the last Numero_de_instrumento to be able to edit it
		private $Volumen;
		private $Nombre_del_notario;
		private $Numero_de_notario;
		private $Fecha_de_celebracion = 'AAAA-MM-DD';
		private $RPP;
		private $Fecha_de_inscripcion_RPP = 'AAAA-MM-DD';
		private $Seccion;
		private $Tomo;
		private $Libro;
		private $Partida;
		private $Extracto;
		private $Numero_de_folio_mercantil;
		private $Empresa;
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

		public function setFromDb()//sets properties from data base, but $Numero_de_instrumento and $Empresa have to be set before
		{
			$result = $this->conn->query("SELECT * FROM Instrumento_notarial WHERE Numero_de_instrumento = '{$this->Numero_de_instrumento}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. if $update is true it updates all database registers (professedly one) with $this' Numero_de_instrumento and Empresa
		{

			if(isset($this->Numero_de_instrumento) && isset($this->Empresa))
			{

				if($update == 'false')
					$this->conn->query("INSERT INTO Instrumento_notarial (Numero_de_instrumento, Empresa, Cuenta) VALUES ('{$this->Numero_de_instrumento}', '{$this->Empresa}', '{$_SESSION['cuenta']}')");
				else
					$this->conn->query("UPDATE Instrumento_notarial SET Numero_de_instrumento  = '{$this->Numero_de_instrumento}' WHERE Numero_de_instrumento = '{$this->id}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

				foreach($this as $key => $value)

					if($key != 'conn' && $key != 'Numero_de_instrumento' && $key != 'id' && $key != 'Empresa')
					{

						if(isset($this->$key))
							$this->conn->query("UPDATE Instrumento_notarial SET $key = '$value' WHERE Numero_de_instrumento = '{$this->Numero_de_instrumento}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");
						else
							$this->conn->query("UPDATE Instrumento_notarial SET $key = NULL WHERE Numero_de_instrumento = '{$this->Numero_de_instrumento}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

					}

				return true;
			}

			return false;
		}

		public function dbDelete()//delete this entity from database but Numero_de_instrumento and Empresa have to be set before
		{

			if(isset($this->Numero_de_instrumento) && isset($this->Empresa))
				$this->conn->query("DELETE FROM Instrumento_notarial WHERE Numero_de_instrumento = '{$this->Numero_de_instrumento}' AND Empresa = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");

		}

		public function draw($act)//draws $this instrumento_notarial. if $act == 'EDIT' or 'ADD' the fields can be edited and the form is submitted. Otherwise the fields can't be edited and the form is not submitted
		{
			echo "<div class = \"datos_tab\">Datos</div>";
			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>"."$this->Numero_de_instrumento</textarea>";
					echo "<textarea name = \"Empresa\" class=\"hidden_textarea\" readonly=true>"."$this->Empresa</textarea>";
					echo "<label class = \"tipo_de_documento_label\">Tipo de documento</label>";
					echo "<textarea class = \"tipo_de_documento_textarea\" name = \"Tipo_de_documento\" title = \"Tipo de documento\" ". ($act == 'EDIT' || $act == 'ADD'?" required=true>":"readonly=true>")."$this->Tipo_de_documento</textarea>";
					echo "<label class = \"numero_de_instrumento_label\">Numero de instrumento</label>";
					echo "<textarea class = \"numero_de_instrumento_textarea\" name = \"Numero_de_instrumento\" title = \"Numero de instrumento\" onblur = \"chKey(this.value,'Instrumento_notarial','$act')\" ". ($act == 'EDIT' || $act == 'ADD'?" required=true>":"readonly=true>")."$this->Numero_de_instrumento</textarea>";
					echo "<label class = \"nombre_del_notario_label\">Nombre del notario</label>";
					echo "<textarea class = \"nombre_del_notario_textarea\" name = \"Nombre_del_notario\" title = \"Nombre del notario\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Nombre_del_notario</textarea>";
					echo "<label class = \"numero_de_notario_label\">Numero de notario</label>";
					echo "<textarea class = \"numero_de_notario_textarea\" name = \"Numero_de_notario\" title = \"Numero de notario\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Numero_de_notario</textarea>";
					echo "<label class = \"seccion_label\">Sección</label>";
					echo "<textarea class = \"seccion_textarea\" name = \"Seccion\" title = \"Seccion\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Seccion</textarea>";
					echo "<label class = \"volumen_label\">Volumen</label>";
					echo "<textarea class = \"volumen_textarea\" name = \"Volumen\" title = \"Volumen\"". ($act == 'EDIT' || $act == 'ADD'?" required=true>":"readonly=true>")."$this->Volumen</textarea>";
					echo "<label class = \"libro_label\">Libro</label>";
					echo "<textarea class = \"libro_textarea\" name = \"Libro\" title = \"Libro\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Libro</textarea>";
					echo "<label class = \"partida_label\">Partida</label>";
					echo "<textarea class = \"partida_textarea\" name = \"Partida\" title = \"Partida\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Partida</textarea>";
					echo "<label class = \"extracto_label\">Extracto</label>";
					echo "<textarea class = \"extracto_textarea\" name = \"Extracto\" title = \"Extracto\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Extracto</textarea>";
					echo "<label class = \"tomo_label\">Tomo</label>";
					echo "<textarea class = \"tomo_textarea\" name = \"Tomo\" title = \"Tomo\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Tomo</textarea>";
					echo "<label class = \"rpp_label\">RPP</label>";
					echo "<textarea class = \"rpp_textarea\" name = \"RPP\" title = \"RPP\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->RPP</textarea>";
					echo "<label class = \"numero_de_folio_mercantil_label\">Número de folio mercantil</label>";
					echo "<textarea class = \"numero_de_folio_mercantil_textarea\" name = \"Numero_de_folio_mercantil\" title = \"Numero de folio mercantil\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Numero_de_folio_mercantil</textarea>";
					echo "<label class = \"fecha_de_inscripcion_rpp_label\">Fecha de inscripcion (RPP)</label>";
					echo "<textarea class=\"fecha_de_inscripcion_rpp_textarea\" id= \"Fecha_de_inscripcion_RPP\" name = \"Fecha_de_inscripcion_RPP\" title = \"Fecha de inscripcion(RPP)\" readonly=true". ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>")."$this->Fecha_de_inscripcion_RPP</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)'/>";

					echo "<label class = \"fecha_de_celebracion_label\">Fecha de celebracion</label>";
					echo "<textarea class=\"fecha_de_celebracion_textarea\" id= \"Fecha_de_celebracion\" name = \"Fecha_de_celebracion\" title = \"Fecha de celebracion\"". ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>")."$this->Fecha_de_celebracion</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)'/>";

				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Instrumento_notarial')\"/>";// _submit() at common_entities.js
			
			echo "</form>";

		}

	}

?>
