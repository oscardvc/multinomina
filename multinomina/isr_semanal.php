<?php
	include_once('connection.php');

//Class ISR_semanal definition

	class ISR_semanal
	{
		//class properties
		//data
		private $Ano;//used to identify a table but is not the primary key cause it's repeated at every row
		private $_Ano;//to be able to edit Ano
		private $Limite_inferior;
		private $Limite_superior;
		private $Cuota_fija;
		private $Porcentaje;
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

					if($key == 'Limite_inferior')
					{
						$len = count($_POST['Limite_inferior']);

						for($i=0; $i<$len; $i++)
						{

							if(!isset($this->Limite_inferior))
								$this->Limite_inferior = trim($_POST['Limite_inferior'][$i]);
							else
								$this->Limite_inferior .= ',' . trim($_POST['Limite_inferior'][$i]);

						}

					}
					elseif($key == 'Limite_superior')
					{
						$len = count($_POST['Limite_superior']);

						for($i=0; $i<$len; $i++)
						{

							if(!isset($this->Limite_superior))
								$this->Limite_superior = trim($_POST['Limite_superior'][$i]);
							else
								$this->Limite_superior .= ',' . trim($_POST['Limite_superior'][$i]);

						}

					}
					elseif($key == 'Cuota_fija')
					{
						$len = count($_POST['Cuota_fija']);

						for($i=0; $i<$len; $i++)
						{

							if(!isset($this->Cuota_fija))
								$this->Cuota_fija = trim($_POST['Cuota_fija'][$i]);
							else
								$this->Cuota_fija .= ',' . trim($_POST['Cuota_fija'][$i]);

						}

					}
					elseif($key == 'Porcentaje')
					{
						$len = count($_POST['Porcentaje']);

						for($i=0; $i<$len; $i++)
						{

							if(!isset($this->Porcentaje))
								$this->Porcentaje = trim($_POST['Porcentaje'][$i]);
							else
								$this->Porcentaje .= ',' . trim($_POST['Porcentaje'][$i]);

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
			$result = $this->conn->query("SELECT Limite_inferior, Limite_superior, Cuota_fija, Porcentaje FROM ISR_semanal WHERE Ano = '{$this->Ano}' ORDER BY Limite_inferior ASC");

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
				$this->conn->query("DELETE FROM ISR_semanal WHERE Ano = '{$this->_Ano}'");
			else
			{
				$result = $this->conn->query("SELECT Ano FROM ISR_semanal WHERE Ano = '{$this->Ano}'");

				if($this->conn->num_rows($result) > 0)
					return false;

			}

			$limites_inferiores = explode(',',$this->Limite_inferior);
			$limites_superiores = explode(',',$this->Limite_superior);
			$cuotas_fijas = explode(',',$this->Cuota_fija);
			$porcentajes = explode(',',$this->Porcentaje);
			$len = count($limites_inferiores);

			for($i=0; $i<$len; $i++)
				$this->conn->query("INSERT INTO ISR_semanal(Ano, Limite_inferior, Limite_superior, Cuota_fija, Porcentaje) VALUES('{$this->Ano}', {$limites_inferiores[$i]}, " . ($limites_superiores[$i] == '' ? 'NULL' : $limites_superiores[$i]) . ", {$cuotas_fijas[$i]}, {$porcentajes[$i]})");

			return true;
		}

		public function dbDelete()//delete this entity from database but Ano has to be set before
		{

			if(isset($this->Ano))
				$this->conn->query("DELETE FROM ISR_semanal WHERE Ano = '{$this->Ano}'");

		}

		public function draw($act)//draws $this ISR_semanal. if $act == 'EDIT' or $act == 'ADD' the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";
			echo "<form class = \"show_form\">";
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\">";
					echo "<textarea name = \"_Ano\" class=\"hidden_textarea\" readonly=true>{$this->Ano}</textarea>";
					echo "<table class = \"tabla\">";
					echo "<tr><td class = 'title' colspan = '4'>ISR semanal del año:<textarea title = \"Año\" name = \"Ano\" class=\"ano_textarea\" required = true>{$this->Ano}</textarea></td></tr>";
					echo "<tr>";
					echo "<td class = \"column_title\">Límite inferior</td>";
					echo "<td class = \"column_title\">Límite superior</td>";
					echo "<td class = \"column_title\">Cuota fija</td>";
					echo "<td class = \"column_title\">Porcentaje</td>";

					if($act != 'DRAW')
					{
						echo '<td class = "button" onmouseover = "add_row_button_bright(this)" onmouseout = "add_row_button_opaque(this)" onclick = "add_row_isr(this)">✚</td>';//add_row_button_brigth and add_row_button_opaque at presentation.js. add_row_isr() at entities.js
						echo '<td class = "button" onmouseover = "sub_row_button_bright(this)" onmouseout = "sub_row_button_opaque(this)" onclick = "_sub_row(this)">━</td>';//_sub_row() at entities.js
					}

					echo "</tr></table><div><table class = \"tabla\">";

					if(isset($this->Limite_inferior) && isset($this->Limite_superior) && isset($this->Cuota_fija) && isset($this->Porcentaje))
					{
						$limites_inferiores = explode(',',$this->Limite_inferior);
						$limites_superiores = explode(',',$this->Limite_superior);
						$cuotas_fijas = explode(',',$this->Cuota_fija);
						$porcentajes = explode(',',$this->Porcentaje);
						$len = count($limites_inferiores);

						for($i=0; $i<$len; $i++)
						{
							echo "<tr>";
							echo "<td><textarea name = \"Limite_inferior[]\"" . ($act == 'EDIT' ? "required=true>" : "readonly=true>") . "{$limites_inferiores[$i]}</textarea></td>";
							echo "<td><textarea name = \"Limite_superior[]\"" . ($act == 'EDIT' ? "required=true>" : "readonly=true>") . "{$limites_superiores[$i]}</textarea></td>";
							echo "<td><textarea name = \"Cuota_fija[]\"" . ($act == 'EDIT' ? "required=true>" : "readonly=true>") . "{$cuotas_fijas[$i]}</textarea></td>";
							echo "<td><textarea name = \"Porcentaje[]\"" . ($act == 'EDIT' ? "required=true>" : "readonly=true>") . "{$porcentajes[$i]}</textarea></td>";
							echo "</tr>";
						}

					}

					echo "</table></div>";

				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'ISR_semanal')\" />";//_submit() at common_entities.js

			echo "</form>";
		}

	}

?>
