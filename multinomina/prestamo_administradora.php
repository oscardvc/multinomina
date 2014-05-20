<?php
	include_once('connection.php');

//Class Prestamo_administradora definition

	class Prestamo_administradora
	{
		//class properties
		//data
		private $Numero_de_prestamo;//ineditable
		private $Numero_de_descuento;//ineditable
		private $Fecha_de_descuento;
		private $Cantidad_a_descontar;
		private $Trabajador;
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

		private function setNumero_de_prestamo()
		{
			$result = $this->conn->query("SELECT Numero_de_prestamo FROM Prestamo_administradora WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY Numero_de_prestamo DESC LIMIT 1");
			$row = $this->conn->fetchRow($result);

			if(isset($row))
			{
				list($this->Numero_de_prestamo) = $row;
				$this->Numero_de_prestamo ++;
			}
			else
				$this->Numero_de_prestamo = 1;
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

					if($key == 'Numero_de_descuento')
					{
						$len = count($_POST['Numero_de_descuento']);

						for($i=0; $i<$len; $i++)
						{

							if(!isset($this->Numero_de_descuento))
								$this->Numero_de_descuento = trim($_POST['Numero_de_descuento'][$i]);
							else
								$this->Numero_de_descuento .= ',' . trim($_POST['Numero_de_descuento'][$i]);

						}

					}
					elseif($key == 'Fecha_de_descuento')
					{
						$len = count($_POST['Fecha_de_descuento']);

						for($i=0; $i<$len; $i++)
						{

							if(!isset($this->Fecha_de_descuento))
								$this->Fecha_de_descuento = trim($_POST['Fecha_de_descuento'][$i]);
							else
								$this->Fecha_de_descuento .= ',' . trim($_POST['Fecha_de_descuento'][$i]);

						}

					}
					elseif($key == 'Cantidad_a_descontar')
					{
						$len = count($_POST['Cantidad_a_descontar']);

						for($i=0; $i<$len; $i++)
						{

							if(!isset($this->Cantidad_a_descontar))
								$this->Cantidad_a_descontar = trim($_POST['Cantidad_a_descontar'][$i]);
							else
								$this->Cantidad_a_descontar .= ',' . trim($_POST['Cantidad_a_descontar'][$i]);

						}

					}
					else
						$this->$key = trim($_POST["$key"]);

				}

		}

		public function showProperties()//prints properties values
		{

			foreach($this as $key => $value)

				if($key != 'conn')
					echo "$key = $value <br />";

		}

		public function setFromDb()//sets properties from data base, but $Numero_de_prestamo has to be set before
		{
			$result = $this->conn->query("SELECT Numero_de_descuento, Fecha_de_descuento, Cantidad_a_descontar FROM Prestamo_administradora WHERE Numero_de_prestamo = '{$this->Numero_de_prestamo}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if(!isset($this->$key))
						$this->$key = $value;
					else
						$this->$key .= ',' . $value;

			$result = $this->conn->query("SELECT Trabajador, Servicio FROM Trabajador_Prestamo_administradora WHERE Prestamo_administradora = '{$this->Numero_de_prestamo}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)
					$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores new database registers with $this' properties if $update is true it updates all database registers with $this' Numero_de_prestamo
		{

			if($update == 'false')
				$this->setNumero_de_prestamo();
			else
				$this->dbDelete(false);

			$numeros_de_descuento = explode(',',$this->Numero_de_descuento);
			$len = count($numeros_de_descuento);

			for($i=0; $i<$len; $i++)
			{
				$this->conn->query("INSERT INTO Prestamo_administradora(Numero_de_prestamo, Numero_de_descuento, Cuenta)  VALUES({$this->Numero_de_prestamo}, {$numeros_de_descuento[$i]}, '{$_SESSION['cuenta']}')");
			}

			$fechas_de_descuento = explode(',',$this->Fecha_de_descuento);
			$len = count($fechas_de_descuento);

			for($i=1; $i<=$len; $i++)
				$this->conn->query("UPDATE Prestamo_administradora SET Fecha_de_descuento = '{$fechas_de_descuento[$i-1]}' WHERE Numero_de_prestamo = {$this->Numero_de_prestamo} AND Numero_de_descuento = $i AND Cuenta = '{$_SESSION['cuenta']}'");

			$cantidades_a_descontar = explode(',',$this->Cantidad_a_descontar);
			$len = count($cantidades_a_descontar);

			for($i=1; $i<=$len; $i++)
				$this->conn->query("UPDATE Prestamo_administradora SET Cantidad_a_descontar = {$cantidades_a_descontar[$i-1]} WHERE Numero_de_prestamo = {$this->Numero_de_prestamo} AND Numero_de_descuento = $i AND Cuenta = '{$_SESSION['cuenta']}'");

			$this->conn->query("INSERT INTO Trabajador_Prestamo_administradora(Trabajador, Prestamo_administradora, Cuenta) VALUES('{$this->Trabajador}', '{$this->Numero_de_prestamo}', '{$_SESSION['cuenta']}')");

			if(isset($this->Servicio))
				$this->conn->query("UPDATE Trabajador_Prestamo_administradora SET Servicio = '{$this->Servicio}' WHERE Prestamo_administradora = {$this->Numero_de_prestamo} AND Cuenta = '{$_SESSION['cuenta']}'");
			else
				$this->conn->query("UPDATE Trabajador_Prestamo_administradora SET Servicio = NULL WHERE Prestamo_administradora = {$this->Numero_de_prestamo} AND Cuenta = '{$_SESSION['cuenta']}'");

			return true;
		}

		public function dbDelete($drop = true)//delete this entity from database but Numero_de_prestamo has to be set before
		{

			if(isset($this->Numero_de_prestamo))
			{
				$this->conn->query("DELETE FROM Prestamo_administradora WHERE Numero_de_prestamo = {$this->Numero_de_prestamo} AND Cuenta = '{$_SESSION['cuenta']}'");
				$this->conn->query("DELETE FROM Trabajador_Prestamo_administradora WHERE Prestamo_administradora = {$this->Numero_de_prestamo} AND Cuenta = '{$_SESSION['cuenta']}'");

				if($drop)
					$this->conn->query("DELETE FROM Descuento_pendiente WHERE Retencion = 'Préstamo administradora' AND id = '{$this->Numero_de_prestamo}' AND Cuenta = '{$_SESSION['cuenta']}'");

			}

		}

		public function draw($act)//draws $this Prestamo_administradora. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";

			if($act != 'ADD')
				echo "<div onclick = \"show('Servicio_fieldset',this)\" class = \"servicio_tab\">Servicio</div>";

			echo "<form class = \"show_form\">";
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\">";
					echo "<textarea name = \"Trabajador\" class=\"hidden_textarea\" readonly=true>$this->Trabajador</textarea>";
					echo "<textarea name = \"Numero_de_prestamo\" class=\"hidden_textarea\" readonly=true>"."$this->Numero_de_prestamo</textarea>";
					echo "<table class = \"tabla_de_prestamo\">";
					echo "<tr><td class = 'title' colspan = '3'>Tabla de prestamo</td></tr>";
					echo "<tr>";
					echo "<td class = \"column_title\">Número de descuento</td>";
					echo "<td class = \"column_title\">Fecha(aaaa-mm-dd)</td>";
					echo "<td class = \"column_title\">Cantidad a descontar</td>";

					if($act != 'DRAW')
					{
						echo '<td class = "button" onmouseover = "add_row_button_bright(this)" onmouseout = "add_row_button_opaque(this)" onclick = "add_row(this)">✚</td>';//add_row_button_brigth and add_row_button_opaque at presentation.js. add_row() at entities.js
						echo '<td class = "button" onmouseover = "sub_row_button_bright(this)" onmouseout = "sub_row_button_opaque(this)" onclick = "sub_row(this)">━</td>';//sub_row_button_brigth and sub_row_button_opaque at presentation.js. sub_row() at entities.js
					}

					echo "</tr></table><div><table class = \"tabla_de_prestamo\">";

					if(isset($this->Numero_de_descuento) && isset($this->Fecha_de_descuento) && isset($this->Cantidad_a_descontar))
					{
						$numeros_de_descuentos = explode(',',$this->Numero_de_descuento);
						$fechas_de_descuentos = explode(',',$this->Fecha_de_descuento);
						$cantidades_a_descontar = explode(',',$this->Cantidad_a_descontar);
						$len = count($numeros_de_descuentos);

						for($i = 0; $i < $len; $i++)
						{
							echo "<tr>";
							echo "<td><textarea name = \"Numero_de_descuento[]\" readonly=true>$numeros_de_descuentos[$i]</textarea></td>";
							echo "<td><textarea name = \"Fecha_de_descuento[]\"" . ($act == 'EDIT'?"required=true>":"readonly=true>")."$fechas_de_descuentos[$i]</textarea></td>";
							echo "<td><textarea name = \"Cantidad_a_descontar[]\"" . ($act == 'EDIT'?"required=true>":"readonly=true>")."$cantidades_a_descontar[$i]</textarea></td>";
							echo "</tr>";
						}

					}

					echo "</table></div>";

				echo "</fieldset>";
				echo "<fieldset class = \"Servicio_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'></td><td>id</td><td>Periodo de la nómina</td><td>Registro patronal</td><td>Empresa</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Prestamo_administradora' dbtable2 = 'Servicio' _id = '$this->Numero_de_prestamo'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<4; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Prestamo_administradora','Servicio','$this->Numero_de_prestamo',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Prestamo_administradora')\" />";//_submit() at common_entities.js
			
			echo "</form>";
		}

	}

?>
