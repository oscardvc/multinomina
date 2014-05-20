<?php
	include_once('connection.php');
	include_once('actividad.php');

//Class empresa definition

	class Empresa
	{
		//class properties
		private $Nombre;
		private $RFC;
		private $id;//used to store the last RFC to be able to edit it
		private $Calle;
		private $Numero_ext;
		private $Numero_int;
		private $Colonia;
		private $Localidad;
		private $Referencia;
		private $Municipio;
		private $Estado;
		private $Pais;
		private $CP;
		private $Telefono;
		private $Correo_electronico;
		private $Objeto_social;
		private $Fecha_de_inicio_de_operaciones = 'AAAA-MM-DD';
		private $Tipo_de_sociedad;
		private $Zona_geografica;
		private $Celular;
		private $Fecha_de_constitucion = 'AAAA-MM-DD';
		private $Lugar_de_nacimiento;
		private $Domicilio_particular;
		private $Estado_civil;
		private $Nacionalidad;
		private $Ocupacion;
		private $Fecha_de_ingreso = 'AAAA-MM-DD';
		private $Fecha_de_nacimiento = 'AAAA-MM-DD';
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
				{

					if($key == 'RFC')
						$this->$key = str_replace (' ' , '', trim($_POST["$key"]));//deleting spaces
					else
						$this->$key = trim($_POST["$key"]);

				}
					
		}

		public function showProperties()//prints property values
		{

			foreach($this as $key => $value)

				if($key != 'conn')
					echo "$key = $value <br />";

		}

		public function setFromDb()//sets properties from data base, but $RFC has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Empresa WHERE RFC = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. If $update is true it updates all database registers (professedly one) with $this' RFC
		{

			if(isset($this->RFC))
			{
				$actividad = new Actividad();
				$actividad->set('Dato','Empresa');
				$actividad->set('Identificadores',"Nombre: {$this->Nombre} RFC: {$this->RFC}");

				if($update == 'false')
				{
					$result = $this->conn->query("SELECT RFC FROM Empresa WHERE RFC = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");

					if($this->conn->num_rows($result) > 0)
						return false;

					$this->conn->query("INSERT INTO Empresa(RFC, Cuenta) VALUES('{$this->RFC}', '{$_SESSION['cuenta']}')");
					$actividad->set('Operacion','Nuevo');
				}
				else
				{

					if($this->RFC != $this->id)
					{
						$result = $this->conn->query("SELECT RFC FROM Empresa WHERE RFC = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");

						if($this->conn->num_rows($result) > 0)
							return false;

					}

					$this->conn->query("UPDATE Empresa SET RFC  = '{$this->RFC}' WHERE RFC = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
					$actividad->set('Operacion','Editar');
				}

				$actividad->dbStore();
				
				foreach($this as $key => $value)

					if($key != 'conn' && $key != 'RFC' && $key != 'id')
					{

						if(isset($this->$key))
							$this->conn->query("UPDATE Empresa SET $key = '$value' WHERE RFC = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");
						else
							$this->conn->query("UPDATE Empresa SET $key = NULL WHERE RFC = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");

					}

				return true;
			}

			return false;
		}

		public function dbDelete()//delete this entity from database but RFC has to be set before
		{

			if(isset($this->RFC))
			{
				$actividad = new Actividad();
				$actividad->set('Operacion','Eliminar');
				$actividad->set('Dato','Empresa');
				$result = $this->conn->query("SELECT Nombre FROM Empresa WHERE RFC = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($nombre) = $this->conn->fetchRow($result);
				$actividad->set('Identificadores',"Nombre: $nombre RFC: {$this->RFC}");
				$actividad->dbStore();
				$this->conn->query("DELETE FROM Empresa WHERE RFC = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

		}

		public function draw($act)//draws $this empresa. if $act == 'EDIT' or 'ADD' the fields can be edited and the form is submitted. otherwise the fields can't be edited and the form is not submitted
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";//show at tabs.js
			echo "<div onclick = \"show('Persona_fisica_fieldset',this)\" class = \"persona_fisica_tab\">Persona física</div>";//show at tabs.js

			if($act != 'ADD')
			{
				echo "<div onclick = \"show('Registro_patronal_fieldset',this)\" class = \"registro_patronal_tab\">Registro patronal</div>";
				echo "<div onclick = \"show('Prima_fieldset',this)\" class = \"prima_tab\">Prima</div>";
				echo "<div onclick = \"show('Representante_legal_fieldset',this)\" class = \"representante_legal_tab\">Representante legal</div>";
				echo "<div onclick = \"show('Socios_fieldset',this)\" class = \"socios_tab\">Socios</div>";
				echo "<div onclick = \"show('Apoderados_fieldset',this)\" class = \"apoderados_tab\">Apoderados</div>";
				echo "<div onclick = \"show('Instrumento_notarial_fieldset',this)\" class = \"instrumento_notarial_tab\">Instrumento notarial</div>";
				echo "<div onclick = \"show('Regimen_fiscal_fieldset',this)\" class = \"regimen_fiscal_tab\">Régimen fiscal</div>";
				echo "<div onclick = \"show('Sucursales_fieldset',this)\" class = \"sucursales_tab\">Sucursales</div>";
				echo "<div onclick = \"show('Logo_fieldset',this)\" class = \"logo_tab\">Logotipo</div>";
				echo "<div onclick = \"show('Archivo_digital_fieldset',this)\" class = \"archivo_digital_tab\">Archivo digital</div>";
				echo "<div onclick = \"show('Sello_digital_fieldset',this)\" class = \"sello_digital_tab\">Sello digital</div>";

			}

			echo '<form class = "show_form">';
				echo '<fieldset class = "Datos_fieldset" style = "visibility:visible">';
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>{$this->RFC}</textarea>";
					echo "<label class = \"nombre_label\">Nombre</label>";
					echo "<textarea class = \"nombre_textarea\" name = \"Nombre\" title = \"Nombre\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Nombre}</textarea>";
					echo "<label class = \"rfc_label\">RFC</label>";
					echo "<textarea owner = 'Empresa' class = \"rfc_textarea\" name = \"RFC\" title = \"RFC\" onblur = \"chKey(this.value,'Empresa','$act')\" ". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->RFC}</textarea>";
					echo "<div class = 'domicilio_fiscal_div'>";
					echo		"<span>Domicilio fiscal</span>";
					echo		"<label class = \"calle_label\">Calle</label>";
					echo		"<textarea class = \"calle_textarea\" name = \"Calle\" title = \"Calle\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Calle}</textarea>";
					echo		"<label class = \"numero_ext_label\">Número Ext.</label>";
					echo		"<textarea class = \"numero_ext_textarea\" name = \"Numero_ext\" title = \"Número exterior\"". ($act != 'DRAW'?"required=true>":"readonly=true>")."{$this->Numero_ext}</textarea>";
					echo		"<label class = \"numero_int_label\">Número Int.</label>";
					echo		"<textarea class = \"numero_int_textarea\" name = \"Numero_int\" title = \"Número interior\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->Numero_int}</textarea>";
					echo		"<label class = \"colonia_label\">Colonia</label>";
					echo		"<textarea class = \"colonia_textarea\" name = \"Colonia\" title = \"Colonia\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->Colonia}</textarea>";
					echo		"<label class = \"localidad_label\">Localidad</label>";
					echo		"<textarea class = \"localidad_textarea\" name = \"Localidad\" title = \"Localidad\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Localidad}</textarea>";
					echo		"<label class = \"referencia_label\">Referencia</label>";
					echo		"<textarea class = \"referencia_textarea\" name = \"Referencia\" title = \"Referencia\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->Referencia}</textarea>";
					echo		"<label class = \"municipio_label\">Municipio</label>";
					echo		"<textarea class = \"municipio_textarea\" name = \"Municipio\" title = \"Municipio/Delegación\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Municipio}</textarea>";
					echo		"<label class = \"estado_label\">Estado</label>";
					echo		"<textarea class = \"estado_textarea\" name = \"Estado\" title = \"Estado\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Estado}</textarea>";
					echo		"<label class = \"pais_label\">País</label>";
					echo		"<textarea class = \"pais_textarea\" name = \"Pais\" title = \"País\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Pais}</textarea>";
					echo		"<label class = \"cp_label\">C.P.</label>";
					echo		"<textarea class = \"cp_textarea\" name = \"CP\" title = \"Código postal\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->CP}</textarea>";
					echo "</div>";
					echo "<label class = \"celular_label\">Celular</label>";
					echo "<textarea class = \"celular_textarea\" name = \"Celular\" title = \"Celular\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->Celular}</textarea>";
					echo "<label class = \"telefono_label\">Teléfono</label>";
					echo "<textarea class = \"telefono_textarea\" name = \"Telefono\" title = \"Telefono\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->Telefono}</textarea>";
					echo "<label class = \"correo_label\">Correo electrónico</label>";
					echo "<textarea class = \"correo_textarea\" name = \"Correo_electronico\" title = \"Correo electronico\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Correo_electronico</textarea>";
					echo "<label class = \"nacionalidad_label\">Nacionalidad</label>";
					echo "<textarea class = \"nacionalidad_textarea\" name = \"Nacionalidad\" title = \"Nacionalidad\"". ($act == 'EDIT' || $act == 'ADD'?" required=true >":"readonly=true>")."$this->Nacionalidad</textarea>";
					echo "<label class = \"tipo_de_sociedad_label\">Tipo de sociedad</label>";
					echo "<textarea class = \"tipo_de_sociedad_textarea\" name = \"Tipo_de_sociedad\" title = \"Tipo de sociedad\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Tipo_de_sociedad</textarea>";
					echo "<label class = \"fecha_de_ingreso_label\">Fecha de ingreso</label>";
					echo "<textarea class=\"fecha_de_ingreso_textarea\" type = \"text\" id= \"Fecha_de_ingreso\" name = \"Fecha_de_ingreso\" title = \"Fecha de ingreso\"". ($act == 'EDIT' || $act == 'ADD'? ">":" readonly=true>")."$this->Fecha_de_ingreso</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)'/>";

					echo "<label class = \"fecha_de_inicio_de_operaciones_label\">Inicio de operaciones</label>";
					echo "<textarea class=\"fecha_de_inicio_de_operaciones_textarea\" id= \"Fecha_de_inicio_de_operaciones\" name = \"Fecha_de_inicio_de_operaciones\" title = \"Fecha de inicio de operaciones\"". ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>")."$this->Fecha_de_inicio_de_operaciones</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)'/>";

					echo "<label class = \"fecha_de_constitucion_label\">Fecha de constitución</label>";
					echo "<textarea class=\"fecha_de_constitucion_textarea\" id= \"Fecha_de_constitucion\" name = \"Fecha_de_constitucion\" title = \"Fecha de constitucion\"". ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>")."$this->Fecha_de_constitucion</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)'/>";

					echo "<label class = \"objeto_social_label\">Objeto social</label>";
					echo "<textarea class = \"objeto_social_textarea\" name = \"Objeto_social\" title = \"Objeto social\"". ($act == 'EDIT' || $act == 'ADD'?"required=true>":"readonly=true>")."{$this->Objeto_social}</textarea>";
					echo "<label class = \"zona_geografica_label\">Zona geográfica</label>";
				
					if($act != 'DRAW')
					{
						echo '<select title = "Zona geográfica" id = "trabajador_select_zona_geografica" class = "zona_geografica_select" name = "Zona_geografica" required=true >';
							
						if(isset($this->Zona_geografica))
							{

								if($this->Zona_geografica == 'A')
									echo '<option selected>A</option>';
								else
									echo '<option>A</option>';

								if($this->Zona_geografica == 'B')
									echo '<option selected>B</option>';
								else
									echo '<option>B</option>';

								if($this->Zona_geografica == 'C')
									echo '<option selected>C</option>';
								else
									echo '<option>C</option>';

							}
							else
							{
								echo '<option>A</option>';
								echo '<option>B</option>';
								echo '<option>C</option>';
							}

						echo '</select>';
					}
					else
						echo "<textarea class = \"zona_geografica_textarea\" name = \"Zona_geografica\" title = \"Zona geográfica\" readonly=true> $this->Zona_geografica</textarea>";

				echo "</fieldset>";
				echo '<fieldset class = "Persona_fisica_fieldset" style = "visibility:hidden">';
					echo '<label class = "lugar_de_nacimiento_label">Lugar de nacimiento</label>';
					echo "<textarea class = \"lugar_de_nacimiento_textarea\" name = \"Lugar_de_nacimiento\" title = \"Lugar de nacimiento\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Lugar_de_nacimiento</textarea>";
					echo '<label class = "estado_civil_label">Estado civil</label>';
					echo "<textarea class = \"estado_civil_textarea\" name = \"Estado_civil\" title = \"Estado_civil\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Estado_civil</textarea>";
					echo '<label class = "domicilio_particular_label">Domicilio particular</label>';
					echo "<textarea class = \"domicilio_particular_textarea\" name = \"Domicilio_particular\" title = \"Domicilio_particular\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Domicilio_particular</textarea>";
					echo "<label class = \"fecha_de_nacimiento_label\">Fecha de nacimiento</label>";
					echo "<textarea class=\"fecha_de_nacimiento_textarea\" id= \"Fecha_de_nacimiento\" name = \"Fecha_de_nacimiento\" title = \"Fecha de nacimiento\"". ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>")."$this->Fecha_de_nacimiento</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)'/>";

					echo "<label class = \"ocupacion_label\">Ocupación</label>";
					echo "<textarea class = \"ocupacion_textarea\" name = \"Ocupacion\" title = \"Ocupacion\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Ocupacion</textarea>";
				echo '</fieldset>';
				echo "<fieldset class =  \"Registro_patronal_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Registro_patronal',this)\"/>" : "") . "</td><td>Número</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Empresa' dbtable2 = 'Registro_patronal' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<1; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Empresa','Registro_patronal','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class =  \"Prima_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Prima',this)\"/>" : "") . "</td><td>id</td><td>Valor</td><td>Fecha</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Empresa' dbtable2 = 'Prima' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Empresa','Prima','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class =  \"Representante_legal_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Representante_legal',this)\"/>" : "") . "</td><td>Nombre</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Empresa' dbtable2 = 'Representante_legal' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<1; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Empresa','Representante_legal','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class =  \"Socios_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Socio',this)\"/>" : "") . "</td><td>Nombre</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Empresa' dbtable2 = 'Socio' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<1; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Empresa','Socio','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class =  \"Apoderados_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Apoderado',this)\"/>" : "") . "</td><td>Nombre</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Empresa' dbtable2 = 'Apoderado' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<1; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Empresa','Apoderado','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class =  \"Instrumento_notarial_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Instrumento_notarial',this)\"/>" : "") . "</td><td>Número de instrumento</td><td>Tipo de documento</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Empresa' dbtable2 = 'Instrumento_notarial' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<2; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Empresa','Instrumento_notarial','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class =  \"Regimen_fiscal_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Regimen_fiscal',this)\"/>" : "") . "</td><td>id</td><td>Régimen</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Empresa' dbtable2 = 'Regimen_fiscal' _id = '{$this->RFC}'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<2; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Empresa','Regimen_fiscal','{$this->RFC}',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class =  \"Sucursales_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Sucursal',this)\"/>" : "") . "</td><td>Nombre</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Empresa' dbtable2 = 'Sucursal' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<1; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Empresa','Sucursal','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Logo_fieldset\" style = \"visibility:hidden\"\>";
					echo "<img class = 'logo_image' title = 'Logotipo' " . ($act == 'EDIT' ? "onclick = \"_new('Logo',this)\"" : "") . " empresa = {$this->RFC} />";
				echo "</fieldset>";
				echo "<fieldset class = \"Archivo_digital_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Archivo_digital',this)\"/>" : "") . "</td><td>Nombre</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Empresa' dbtable2 = 'Archivo_digital' _id = '{$this->RFC}'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<1; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Empresa','Archivo_digital','{$this->RFC}',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Sello_digital_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Sello_digital',this)\"/>" : "") . "</td><td>id</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Empresa' dbtable2 = 'Sello_digital' _id = '{$this->RFC}'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<1; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Empresa','Sello_digital','{$this->RFC}',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";

				if($act == 'EDIT' || $act == 'ADD')
					echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Empresa')\"/>";//_submit() at entities.js
			
			echo "</form>";
			
		}

	}

?>
