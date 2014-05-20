<?php
	include_once('connection.php');
	include_once('actividad.php');

//Class Trabajador definition

	class Trabajador
	{
		//class properties
		//data
		private $Nombre;
		private $Domicilio_particular;
		private $Telefono;
		private $Celular;
		private $Correo_electronico;
		private $Nacionalidad;
		private $Estado_civil;
		private $Fecha_de_nacimiento = 'AAAA-MM-DD';
		private $Lugar_de_nacimiento;
		private $Sexo;
		private $CURP;
		private $Numero_IFE;
		private $RFC;
		private $id;//used to store the last RFC to be able to edit it
		private $Numero_IMSS;
		private $Jornada;
		private $Tipo_de_sangre;
		private $Horario;
		private $Avisar_a;
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

		public function showProperties()//prints properties values
		{
			foreach($this as $key => $value)
				if($key != 'conn')
					echo "$key = $value \t";
		}

		public function setFromDb()//sets properties from data base, but $RFC has to be set before
		{

			$result = $this->conn->query("SELECT * FROM Trabajador WHERE RFC = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

			$this->conn->freeResult($result);
		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties
		{				   //if $update is true it updates all database registers (professedly one) with $this' Nombre

			if(isset($this->RFC))
			{
				$actividad = new Actividad();
				$actividad->set('Dato','Trabajador');
				$actividad->set('Identificadores',"Nombre: {$this->Nombre} RFC: {$this->RFC}");

				if($update == 'false')
				{
					$result = $this->conn->query("SELECT RFC FROM Trabajador WHERE RFC = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");

					if($this->conn->num_rows($result) > 0)
						return false;

					$this->conn->query("INSERT INTO Trabajador(RFC, Cuenta) VALUES('{$this->RFC}', '{$_SESSION['cuenta']}')");
					$actividad->set('Operacion','Nuevo');
				}
				else
				{

					if($this->RFC != $this->id)
					{
						$result = $this->conn->query("SELECT RFC FROM Trabajador WHERE RFC = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");

						if($this->conn->num_rows($result) > 0)
							return false;

					}

					$this->conn->query("UPDATE Trabajador SET RFC  = '{$this->RFC}' WHERE RFC = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
					$actividad->set('Operacion','Editar');
				}

				$actividad->dbStore();

				foreach($this as $key => $value)

					if(isset($this->$key))
					{

						if($key != 'conn' && $key != 'RFC' && $key != 'id')
							$this->conn->query("UPDATE Trabajador SET $key = '$value' WHERE RFC = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");

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
				$actividad->set('Dato','Trabajador');
				$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($nombre) = $this->conn->fetchRow($result);
				$actividad->set('Identificadores',"Nombre: $nombre RFC: {$this->RFC}");
				$actividad->dbStore();
				$this->conn->query("DELETE FROM Trabajador WHERE RFC = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");
//				$this->conn->query("DELETE FROM Sign WHERE Trabajador = '{$this->RFC}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

		}

		public function draw($act)//draws $this trabajador. if $act == 'EDIT' or $act == EDIT the fields can be edited and the form is submitted. if $act == 'DRAW' the fields can't be edited and the form is not submitted.
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";

			if($act != 'ADD')
			{
				echo "<div onclick = \"show('Tipo_fieldset',this)\" class = \"tipo_tab\">Tipo</div>";//show at tabs.js
				echo "<div onclick = \"show('Base_fieldset',this)\" class = \"base_tab\">Base</div>";
				echo "<div onclick = \"show('Contrato_fieldset',this)\" class = \"contrato_tab\">Contrato</div>";//
				echo "<div onclick = \"show('Banco_fieldset',this)\" class = \"banco_tab\">Banco</div>";
				echo "<div onclick = \"show('UMF_fieldset',this)\" class = \"umf_tab\">UMF</div>";
				echo "<div onclick = \"show('Aportacion_del_trabajador_al_fondo_de_ahorro_fieldset',this)\" class = \"aportacion_del_trabajador_al_fondo_de_ahorro_tab\">Aportación al fondo de ahorro</div>";
				echo "<div onclick = \"show('Incapacidad_fieldset',this)\" class = \"incapacidad_tab\">Incapacidad</div>";
				echo "<div onclick = \"show('Vacaciones_fieldset',this)\" class = \"vacaciones_tab\">Vacaciones</div>";
				echo "<div onclick = \"show('Pension_alimenticia_fieldset',this)\" class = \"pension_alimenticia_tab\">Pensión alimenticia</div>";
				echo "<div onclick = \"show('Prestamo_de_administradora_fieldset',this)\" class = \"prestamo_de_administradora_tab\">Préstamo de administradora</div>";
				echo "<div onclick = \"show('Prestamo_de_caja_fieldset',this)\" class = \"prestamo_de_caja_tab\">Préstamo de caja</div>";
				echo "<div onclick = \"show('Prestamo_de_cliente_fieldset',this)\" class = \"prestamo_de_cliente_tab\">Préstamo de cliente</div>";
				echo "<div onclick = \"show('Prestamo_del_fondo_de_ahorro_fieldset',this)\" class = \"prestamo_del_fondo_de_ahorro_tab\">Préstamo del fondo de ahorro</div>";
				echo "<div onclick = \"show('Fondo_de_garantia_fieldset',this)\" class = \"fondo_de_garantia_tab\">Fondo de garantía</div>";
				echo "<div onclick = \"show('FONACOT_fieldset',this)\" class = \"fonacot_tab\">FONACOT</div>";
				echo "<div onclick = \"show('INFONAVIT_fieldset',this)\" class = \"infonavit_tab\">INFONAVIT</div>";
				echo "<div onclick = \"show('Pago_por_seguro_de_vida_fieldset',this)\" class = \"pago_por_seguro_de_vida_tab\">Pago por seguro de vida</div>";
				echo "<div onclick = \"show('Salario_diario_fieldset',this)\" class = \"salario_diario_tab\">Salario diario</div>";
				echo "<div onclick = \"show('Salario_minimo_fieldset',this)\" class = \"salario_minimo_tab\">Salario mínimo</div>";
				echo "<div onclick = \"show('Archivo_digital_fieldset',this)\" class = \"archivo_digital_tab\">Archivo digital</div>";
				echo "<div onclick = \"show('Servicios_fieldset',this)\" class = \"servicios_tab\">Servicios</div>";
				echo "<div onclick = \"show('Sucursales_fieldset',this)\" class = \"sucursales_tab\">Sucursales</div>";
				echo "<div onclick = \"show('Bajas_fieldset',this)\" class = \"bajas_tab\">Bajas</div>";
				echo "<div onclick = \"show('Descuentos_pendientes_fieldset',this)\" class = \"descuentos_pendientes_tab\">Descuentos pendientes</div>";
			}

			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					$aux = rand();
					echo "<img class = 'photo_image' src = 'get_photo.php?rfc={$this->RFC}&aux=$aux' title = 'Fotografía' " . ($act == 'EDIT' ? "onclick = \"_new('Photo',this)\"" : "") . " trabajador = {$this->RFC} />";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>$this->RFC</textarea>";
					echo "<label class = \"nombre_label\">Nombre</label>";
					echo "<textarea class = \"nombre_textarea\" name = \"Nombre\" title = \"Nombre\"". (($act == 'EDIT' || $act == 'ADD')?"required=true>":"readonly=true>")."{$this->Nombre}</textarea>";
					echo "<label class = \"telefono_label\">Teléfono</label>";
					echo "<textarea class = \"telefono_textarea\" name = \"Telefono\" title = \"Telefono\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Telefono</textarea>";
					echo "<label class = \"domicilio_label\">Domicilio particular</label>";
					echo "<textarea class = \"domicilio_textarea\" name = \"Domicilio_particular\" title = \"Domicilio particular\"". ($act == 'EDIT' || $act == 'ADD'?" >":"readonly=true>")."{$this->Domicilio_particular}</textarea>";
					echo "<label class = \"celular_label\">Celular</label>";
					echo "<textarea class = \"celular_textarea\" name = \"Celular\" title = \"Celular\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Celular</textarea>";
					echo "<label class = \"correo_label\">Correo electrónico</label>";
					echo "<textarea class = \"correo_textarea\" name = \"Correo_electronico\" title = \"Correo electronico\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."{$this->Correo_electronico}</textarea>";
					echo "<label class = \"nacionalidad_label\">Nacionalidad</label>";
					echo "<textarea class = \"nacionalidad_textarea\" name = \"Nacionalidad\" title = \"Nacionalidad\"". ($act == 'EDIT' || $act == 'ADD'?" >":"readonly=true>")."$this->Nacionalidad</textarea>";
					echo "<label class = \"lugar_de_nacimiento_label\">Lugar de nacimiento</label>";
					echo "<textarea class = \"lugar_de_nacimiento_textarea\" name = \"Lugar_de_nacimiento\" title = \"Lugar de nacimiento\"". ($act == 'EDIT' || $act == 'ADD'?" >":"readonly=true>")."{$this->Lugar_de_nacimiento}</textarea>";
					echo "<label class = \"fecha_de_nacimiento_label\">Fecha de nacimiento</label>";
					echo "<textarea class=\"fecha_de_nacimiento_textarea\" id= \"Fecha_de_nacimiento\" name = \"Fecha_de_nacimiento\" title = \"Fecha de nacimiento\"". ($act == 'EDIT' || $act == 'ADD'? " >":"readonly=true>")."{$this->Fecha_de_nacimiento}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";//show_calendar() at calendar.js

					echo "<label class = \"estado_civil_label\">Estado civil</label>";
					echo "<textarea class = \"estado_civil_textarea\" name = \"Estado_civil\" title = \"Estado civil\"". ($act == 'EDIT' || $act == 'ADD'?" >":"readonly=true>")."{$this->Estado_civil}</textarea>";
					echo "<label class = \"firma_label\">Firma</label>";
					echo "<img class = 'sign_image' title = 'Firma' " . ($act == 'EDIT' ? "onclick = \"_new('Sign',this)\"" : "") . " trabajador = {$this->RFC} />";
					echo "<label class = \"sexo_label\">Sexo</label>";
					echo "<textarea class = \"sexo_textarea\" name = \"Sexo\" title = \"Sexo\"". ($act == 'EDIT' || $act == 'ADD'?" >":"readonly=true>")."$this->Sexo</textarea>";
					echo "<label class = \"curp_label\">CURP</label>";
					echo "<textarea class = \"curp_textarea\" name = \"CURP\" title = \"CURP\"". ($act == 'EDIT' || $act == 'ADD'?" >":"readonly=true>")."$this->CURP</textarea>";
					echo "<label class = \"numero_ife_label\">Numero IFE</label>";
					echo "<textarea class = \"numero_ife_textarea\" name = \"Numero_IFE\" title = \"Numero IFE\"". (($act == 'EDIT' || $act == 'ADD')?" >":"readonly=true>")."{$this->Numero_IFE}</textarea>";
					echo "<label class = \"rfc_label\">RFC</label>";
					echo "<textarea owner = 'trabajador' class = \"rfc_textarea\" name = \"RFC\" title = \"RFC\" onblur = \"chKey(this.value,'Trabajador','$act')\" ". (($act == 'EDIT' || $act == 'ADD')?" required=true >":"readonly=true>")."{$this->RFC}</textarea>";
					echo "<label class = \"numero_imss_label\">Numero IMSS</label>";
					echo "<textarea class = \"numero_imss_textarea\" name = \"Numero_IMSS\" title = \"Numero IMSS\"". ($act == 'EDIT' || $act == 'ADD'?">":"readonly=true>")."$this->Numero_IMSS</textarea>";
					echo "<label class = \"jornada_label\">Jornada</label>";
					echo "<textarea class=\"jornada_textarea\" id= \"Jornada\" name = \"Jornada\" title = \"Jornada\"". ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>") . "$this->Jornada</textarea>";
					echo "<label class = \"tipo_de_sangre_label\">Tipo de sangre</label>";
					echo "<textarea class=\"tipo_de_sangre_textarea\" id= \"Tipo_de_sangre\" name = \"Tipo_de_sangre\" title = \"Tipo de sangre\"". ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>") . "{$this->Tipo_de_sangre}</textarea>";
					echo "<label class = \"horario_label\">Horario</label>";
					echo "<textarea class=\"horario_textarea\" name = \"Horario\" title = \"Horario\"". ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>") . "{$this->Horario}</textarea>";
					echo "<label class = \"avisar_a_label\">Avisar a</label>";
					echo "<textarea class=\"avisar_a_textarea\" name = \"Avisar_a\" title = \"Avisar a\"". ($act == 'EDIT' || $act == 'ADD'? ">":"readonly=true>") . "{$this->Avisar_a}</textarea>";

					if($act == 'ADD')
						echo "<img class = 'import_button' onclick = \"_new('Archivo_digital','_IMPORT')\" title = 'Importar archivo'\>";//_new() at menu.js

				echo "</fieldset>";
				echo "<fieldset class = \"Tipo_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Tipo',this)\"/>" : "") . "</td><td>id</td><td>Tipo de trabajador</td><td>Fecha</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Tipo' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Tipo','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Base_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Base',this)\"/>" : "") . "</td><td>id</td><td>Base para el cálculo de la nómina</td><td>Fecha</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Base' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Base','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Contrato_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Contrato',this)\"/>" : "") . "</td><td>id</td><td>Puesto</td><td>Fecha</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Contrato' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Contrato','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Banco_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Banco',this)\"/>" : "") . "</td><td>id</td><td>Nombre</td><td>Sucursal</td><td>Fecha</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Banco' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<4; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Banco','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"UMF_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('UMF',this)\"/>" : "") . "</td><td>id</td><td>Número</td><td>Fecha</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'UMF' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','UMF','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Aportacion_del_trabajador_al_fondo_de_ahorro_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Aportacion_del_trabajador_al_fondo_de_ahorro',this)\"/>" : "") . "</td><td>id</td><td>Porcentaje del salario</td><td>Fecha de inicio</td><td>Fecha de término</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Aportacion_del_trabajador_al_fondo_de_ahorro' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<4; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Aportacion_del_trabajador_al_fondo_de_ahorro','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Incapacidad_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Incapacidad',this)\"/>" : "") . "</td><td>id</td><td>Fecha de inicio</td><td>Fecha de término</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Incapacidad' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Incapacidad','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Vacaciones_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Vacaciones',this)\"/>" : "") . "</td><td>id</td><td>Fecha de inicio</td><td>Fecha de término</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Vacaciones' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Vacaciones','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Pension_alimenticia_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Pension_alimenticia',this)\"/>" : "") . "</td><td>id</td><td>Cantidad</td><td>Fecha de inicio</td><td>Fecha de término</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Pension_alimenticia' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<4; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Pension_alimenticia','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Prestamo_de_administradora_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Prestamo_administradora',this)\"/>" : "") . "</td><td>id</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Prestamo_administradora' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<1; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Prestamo_administradora','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Prestamo_de_caja_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Prestamo_caja',this)\"/>" : "") . "</td><td>id</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Prestamo_caja' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<1; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Prestamo_caja','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Prestamo_de_cliente_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Prestamo_cliente',this)\"/>" : "") . "</td><td>id</td><td>Fecha</td><td>Monto</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Prestamo_cliente' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Prestamo_cliente','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Prestamo_del_fondo_de_ahorro_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Prestamo_del_fondo_de_ahorro',this)\"/>" : "") . "</td><td>id</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Prestamo_del_fondo_de_ahorro' _id = '{$this->RFC}'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<1; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Prestamo_del_fondo_de_ahorro','{$this->RFC}',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Fondo_de_garantia_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Fondo_de_garantia',this)\"/>" : "") . "</td><td>id</td><td>Porcentaje del pago neto</td><td>Fecha de inicio</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Fondo_de_garantia' _id = '{$this->RFC}'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Fondo_de_garantia','{$this->RFC}',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"FONACOT_fieldset\" style = \"visibility:hidden\"\>";

					if($act == 'DRAW')
						echo "<input type='button' value='⌖' class = 'view_button' onclick = \"status_FONACOT('$this->RFC')\" onmouseover = \"view_button_bright(this)\" onmouseout = \"view_button_opaque(this)\"/>";//function status_FONACOT at entities.js & functions view_button_opaque and bright at presentation.js

					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Retencion_FONACOT',this)\"/>" : "") . "</td><td>id</td><td>Número de crédito</td><td>Importe total</td><td>Fecha de inicio</td><td>Fecha de término</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Retencion_FONACOT' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<5; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Retencion_FONACOT','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"INFONAVIT_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Retencion_INFONAVIT',this)\"/>" : "") . "</td><td>id</td><td>Fecha de inicio</td><td>Fecha de término</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Retencion_INFONAVIT' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Retencion_INFONAVIT','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Pago_por_seguro_de_vida_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Pago_por_seguro_de_vida',this)\"/>" : "") . "</td><td>id</td><td>Cantidad</td><td>Fecha de inicio</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Pago_por_seguro_de_vida' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Pago_por_seguro_de_vida','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Salario_diario_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Salario_diario',this)\"/>" : "") . "</td><td>id</td><td>Cantidad</td><td>Fecha</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Salario_diario' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Salario_diario','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Salario_minimo_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Trabajador_Salario_minimo',this)\"/>" : "") . "</td><td>Codigo</td><td>Nombre</td><td>Servicio</td><td>Fecha</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Trabajador_Salario_minimo' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<4; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Trabajador_Salario_minimo','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Archivo_digital_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Archivo_digital',this)\"/>" : "") . "</td><td>Nombre</td></tr></table>";

					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Archivo_digital' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<1; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Archivo_digital','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Servicios_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Servicio_Trabajador',this)\"/>" : "") . "</td><td>id</td><td>Periodicidad de la nómina</td><td>Registro patronal</td><td>Empresa</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Servicio_Trabajador' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<4; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Servicio_Trabajador','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class =  \"Sucursales_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Trabajador_Sucursal',this)\"/>" : "") . "</td><td>Nombre</td><td>Empresa(RFC)</td><td>Empresa(Nombre)</td><td>Fecha de ingreso</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Trabajador_Sucursal' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<4; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Sucursal','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Bajas_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'>" . ($act == 'EDIT' ? "<input type=\"button\" value=\"\" class = \"add_entity_button\" onmouseover = \"add_entity_button_bright(this)\" onmouseout = \"add_entity_button_opaque(this)\" onclick = \"_new('Baja',this)\"/>" : "") . "</td><td>id</td><td>Fecha de baja</td><td>Fecha de reingreso</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Baja' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<3; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Baja','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";
				echo "<fieldset class = \"Descuentos_pendientes_fieldset\" style = \"visibility:hidden\"\>";
					echo "<table class = 'titles_table'><tr><td class = 'button_cell'></td><td>Nómina</td><td>Retención</td><td>id</td><td>Cantidad</td><td>Número de descuentos</td><td>Fecha de inicio</td><td>Fecha de término</td></tr></table>";
					echo "<div class = 'options' mode = '$act' dbtable1 = 'Trabajador' dbtable2 = 'Descuento_pendiente' _id = '$this->RFC'></div>";
					echo "<table class = \"search_table\"><tr>";

					for($i=0; $i<7; $i++)

						if($i == 0)
							echo "<td class = 'search_button_cell'><input type='button' value='' class = 'search_button' onmouseover = \"search_button_bright(this)\" onmouseout = \"search_button_opaque(this)\" onclick = \"get_options('$act','Trabajador','Descuento_pendiente','$this->RFC',null,this.parentNode.parentNode.parentNode.parentNode.parentNode)\"/></td><td><input type = 'text' class = 'search_field'  id = 'search_field" . $i . "'/></td>";
						else
							echo "<td><input type = 'text' class = 'search_field'" . $i . "'/></td>";

					echo "</tr></table>";
				echo "</fieldset>";

			if($act == 'EDIT' || $act == 'ADD')
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Trabajador')\" />";//submit_button_brigth and submit_button_opaque at presentation.js _submit() at common_entities.js
			
			echo "</form>";

		}

	}

?>
