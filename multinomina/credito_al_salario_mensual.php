<?php
	include_once('connection.php');

//Class Credito_al_salario_mensual definition

	class Credito_al_salario_mensual
	{
		//class properties
		//data
		private $Ano;//used to identify a table but is not the primary key cause it's repeated at every row
		private $_Ano;//to be able to edit Ano
		private $Desde_ingresos_de;
		private $Hasta_ingresos_de;
		private $Subsidio;
		//database connection
		private $conn;

		//class methods
		public function __construct()
		{
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
				{

					if($key == 'Desde_ingresos_de')
					{
						$len = count($_POST['Desde_ingresos_de']);

						for($i=0; $i<$len; $i++)
						{

							if(!isset($this->Desde_ingresos_de))
								$this->Desde_ingresos_de = trim($_POST['Desde_ingresos_de'][$i]);
							else
								$this->Desde_ingresos_de .= ',' . trim($_POST['Desde_ingresos_de'][$i]);

						}

					}
					elseif($key == 'Hasta_ingresos_de')
					{
						$len = count($_POST['Hasta_ingresos_de']);

						for($i=0; $i<$len; $i++)
						{

							if(!isset($this->Hasta_ingresos_de))
								$this->Hasta_ingresos_de = trim($_POST['Hasta_ingresos_de'][$i]);
							else
								$this->Hasta_ingresos_de .= ',' . trim($_POST['Hasta_ingresos_de'][$i]);

						}

					}
					elseif($key == 'Subsidio')
					{
						$len = count($_POST['Subsidio']);

						for($i=0; $i<$len; $i++)
						{

							if(!isset($this->Subsidio))
								$this->Subsidio = trim($_POST['Subsidio'][$i]);
							else
								$this->Subsidio .= ',' . trim($_POST['Subsidio'][$i]);

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

		public function setFromDb()//sets properties from data base, but $Ano has to be set before
		{
			$result = $this->conn->query("SELECT Desde_ingresos_de, Hasta_ingresos_de, Subsidio FROM Credito_al_salario_mensual WHERE Ano = '$this->Ano' ORDER BY Desde_ingresos_de ASC");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if(!isset($this->$key))
						$this->$key = $value;
					else
						$this->$key .= ',' . $value;

		}

		public function dbStore($update)//if $update is false it stores new database registers with $this' properties if $update is true it updates all database registers with $this' Ano
		{

			if($update == 'true')
				$this->conn->query("DELETE FROM Credito_al_salario_mensual WHERE Ano = '{$this->_Ano}'");
			else
			{
				$result = $this->conn->query("SELECT Ano FROM Credito_al_salario_mensual WHERE Ano = '{$this->Ano}'");

				if($this->conn->num_rows($result) > 0)
					return false;

			}

			$desdes = explode(',',$this->Desde_ingresos_de);
			$hastas = explode(',',$this->Hasta_ingresos_de);
			$subsidios = explode(',',$this->Subsidio);
			$len = count($desdes);

			for($i=0; $i<$len; $i++)
				$this->conn->query("INSERT INTO Credito_al_salario_mensual(Ano, Desde_ingresos_de, Hasta_ingresos_de, Subsidio) VALUES('{$this->Ano}', {$desdes[$i]}, " . ($hastas[$i] == '' ? "NULL" : $hastas[$i]) . ", {$subsidios[$i]})");

			return true;
		}

		public function dbDelete()//delete this entity from database but Ano has to be set before
		{

			if(isset($this->Ano))
				$this->conn->query("DELETE FROM Credito_al_salario_mensual WHERE Ano = '{$this->Ano}'");

		}

		public function draw($act)//draws $this Credito_al_salario_mensual. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";
			echo "<form class = \"show_form\">";
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\">";
					echo "<textarea name = \"_Ano\" class=\"hidden_textarea\" readonly=true>{$this->Ano}</textarea>";
					echo "<table class = \"tabla\">";
					echo "<tr><td class = 'title' colspan = '3'>Crédito al salario mensual del año:<textarea title = \"Año\" name = \"Ano\" class=\"ano_textarea\" required = true>{$this->Ano}</textarea></td></tr>";
					echo "<tr>";
					echo "<td class = \"column_title\">Desde ingresos de</td>";
					echo "<td class = \"column_title\">Hasta ingresos de</td>";
					echo "<td class = \"column_title\">Subsidio</td>";

					if($act != 'DRAW')
					{
						echo '<td class = "button" onmouseover = "add_row_button_bright(this)" onmouseout = "add_row_button_opaque(this)" onclick = "add_row_subsidio(this)">✚</td>';//add_row_button_brigth and add_row_button_opaque at presentation.js. _add_row_subsidio() at entities.js
						echo '<td class = "button" onmouseover = "sub_row_button_bright(this)" onmouseout = "sub_row_button_opaque(this)" onclick = "_sub_row(this)">━</td>';//_sub_row() at entities.js
					}

					echo "</tr></table><div><table class = \"tabla\">";

					if(isset($this->Desde_ingresos_de) && isset($this->Hasta_ingresos_de) && isset($this->Subsidio))
					{
						$desdes = explode(',',$this->Desde_ingresos_de);
						$hastas = explode(',',$this->Hasta_ingresos_de);
						$subsidios = explode(',',$this->Subsidio);
						$len = count($desdes);

						for($i=0; $i<$len; $i++)
						{
							echo "<tr>";
							echo "<td><textarea name = \"Desde_ingresos_de[]\"" . ($act == 'EDIT'?"required=true>":"readonly=true>")."{$desdes[$i]}</textarea></td>";
							echo "<td><textarea name = \"Hasta_ingresos_de[]\"" . ($act == 'EDIT'?">":"readonly=true>")."{$hastas[$i]}</textarea></td>";
							echo "<td><textarea name = \"Subsidio[]\"" . ($act == 'EDIT'?"required=true>":"readonly=true>")."{$subsidios[$i]}</textarea></td>";
							echo "</tr>";
						}

					}

					echo "</table></div>";

				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Credito_al_salario_mensual')\" />";//_submit() at common_entities.js

			echo "</form>";
		}

	}

?>
