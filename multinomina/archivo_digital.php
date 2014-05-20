<?php
	include_once('connection.php');
	include_once('trabajador.php');
	include_once('servicio_trabajador.php');
	include_once('tipo.php');
	include_once('base.php');
	include_once('salario_diario.php');
	include_once('trabajador_salario_minimo.php');
	include_once('contrato.php');
	include_once('banco.php');
	include_once('umf.php');
	include_once('aportacion_del_trabajador_al_fondo_de_ahorro.php');
	include_once('pension_alimenticia.php');
	include_once('fondo_de_garantia.php');
	include_once('retencion_fonacot.php');
	include_once('retencion_infonavit.php');
	include_once('factor_de_descuento.php');
	include_once('porcentaje_de_descuento.php');
	include_once('monto_fijo_mensual.php');
	include_once('pago_por_seguro_de_vida.php');
	include_once('trabajador_sucursal.php');
	include_once('actividad.php');

//Class Archivo_digital definition

	class Archivo_digital
	{
		//class properties
		//data
		private $Nombre;
		private $tmpNombre;//file temporal name (is not gonna be stored at database)
		private $fp;//file pointer (is not gonna be stored at database)
		private $Tipo;
		private $Tamanio;
		private $Contenido;
		private $Trabajador;
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
			}
			elseif(isset($_POST['Nombre']))
				$this->Nombre = $_POST['Nombre'];

			if(!get_magic_quotes_gpc())
				$this->Nombre = addslashes($this->Nombre);

			if(isset($_POST['Trabajador']))
				$this->Trabajador = $_POST['Trabajador'];

			if(isset($_POST['Empresa']))
				$this->Empresa = $_POST['Empresa'];

		}

		public function _import()
		{
			$ext = substr($this->Nombre,strlen($this->Nombre) - 3);

			if(isset($this->Contenido) && $ext == 'csv')
			{
				$txt = '<table>';
				$rows = preg_split ('/\r\n|\n/m', $this->Contenido);
				$rows_len = count($rows);

				for($i=0; $i<$rows_len; $i++)
				{
					$rows[$i] = utf8_encode($rows[$i]);
					$rows[$i] = '<tr><td>' . $rows[$i];
					$rows[$i] = str_replace ('\\"' , '', $rows[$i]);
					$rows[$i] = str_replace (';' , '</td><td>', $rows[$i]);
					$rows[$i] .= '</td></tr>';
					$txt .= $rows[$i];
				}

				$txt .= '</table>';
			}
			elseif($ext != 'csv')
				$txt = "El archivo debe tener la extención csv";

			return $txt;
		}

		public function _import_()
		{
			$ext = substr($this->Nombre,strlen($this->Nombre) - 3);

			if(isset($this->Contenido) && $ext == 'csv')
			{
				$rows = preg_split ('/\r\n|\n/m', $this->Contenido);
				$rows_len = count($rows);
				$somethings_missing = false;

				if(preg_match('/Solicitud de empleo/', $rows[0]) == 0)
				{

					//file check
					for($i=0; $i<$rows_len - 1; $i++)//rows_len -1 'cause the last line ends with \n
					{
						$rows[$i] = utf8_encode($rows[$i]);
						$rows[$i] = str_replace ('\\"' , '', $rows[$i]);
						$cols = preg_split ('/;/', $rows[$i]);
						$cols_len = count($cols);

						if($i == 0)//checking headers
						{

							for($j=0; $j<$cols_len; $j++)

								if($j == 0 && $cols[$j] != 'Nombre')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Nombre';
									break 2;
								}
								elseif($j == 1 && $cols[$j] != 'Teléfono')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Teléfono';
									break 2;
								}
								elseif($j == 2 && $cols[$j] != 'Celular')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Celular';
									break 2;
								}
								elseif($j == 3 && $cols[$j] != 'Correo electrónico')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Correo electrónico';
									break 2;
								}
								elseif($j == 4 && $cols[$j] != 'Domicilio')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Domicilio';
									break 2;
								}
								elseif($j == 5 && $cols[$j] != 'Nacionalidad')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Nacionalidad';
									break 2;
								}
								elseif($j == 6 && $cols[$j] != 'Lugar de nacimiento')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Lugar de nacimiento';
									break 2;
								}
								elseif($j == 7 && $cols[$j] != 'Fecha de nacimiento')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Fecha de nacimiento';
									break 2;
								}
								elseif($j == 8 && $cols[$j] != 'Estado civil')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Estad civil';
									break 2;
								}
								elseif($j == 9 && $cols[$j] != 'Sexo')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Sexo';
									break 2;
								}
								elseif($j == 10 && $cols[$j] != 'CURP')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: CURP';
									break 2;
								}
								elseif($j == 11 && $cols[$j] != 'Número IFE')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Número IFE';
									break 2;
								}
								elseif($j == 12 && $cols[$j] != 'RFC')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: RFC';
									break 2;
								}
								elseif($j == 13 && $cols[$j] != 'NSS')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: NSS';
									break 2;
								}
								elseif($j == 14 && $cols[$j] != 'Jornada')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Jornada';
									break 2;
								}
								elseif($j == 15 && $cols[$j] != 'Tipo de sangre')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Tipo de sangre';
									break 2;
								}
								elseif($j == 16 && $cols[$j] != 'Horario')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Horario';
									break 2;
								}
								elseif($j == 17 && $cols[$j] != 'Avisar a')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Avisar a';
									break 2;
								}
								elseif($j == 18 && $cols[$j] != 'Servicio')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Servicio';
									break 2;
								}
								elseif($j == 19 && $cols[$j] != 'Tipo')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Tipo';
									break 2;
								}
								elseif($j == 20 && $cols[$j] != 'Base')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Base';
									break 2;
								}
								elseif($j == 21 && $cols[$j] != 'Salario')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Salario';
									break 2;
								}
								elseif($j == 22 && $cols[$j] != 'Contrato')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Contrato';
									break 2;
								}
								elseif($j == 23 && $cols[$j] != 'Banco')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Banco';
									break 2;
								}
								elseif($j == 24 && $cols[$j] != 'UMF')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: UMF';
									break 2;
								}
								elseif($j == 25 && $cols[$j] != 'Aportación al fondo de ahorro')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Aportación al fondo de ahorro';
									break 2;
								}
								elseif($j == 26 && $cols[$j] != 'Pensión alimenticia')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Pensión alimenticia';
									break 2;
								}
								elseif($j == 27 && $cols[$j] != 'Fondo de garantía')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Fondo de garantía';
									break 2;
								}
								elseif($j == 28 && $cols[$j] != 'FONACOT')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: FONACOT';
									break 2;
								}
								elseif($j == 29 && $cols[$j] != 'INFONAVIT')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: INFONAVIT';
									break 2;
								}
								elseif($j == 30 && $cols[$j] != 'Pago por seguro de vida')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Pago por seguro de vida';
									break 2;
								}
								elseif($j == 31 && $cols[$j] != 'Sucursal')
								{
									$somethings_missing = true;
									$msg = 'El encabezado de la columna ' . ($j + 1) . ' debe ser: Sucursal';
									break 2;
								}

						}
						else//i > 0
						{
							$matches = array();

							for($j=0; $j<$cols_len; $j++)
							{
								$value = trim(preg_replace('/\s+/',' ', $cols[$j]));

								if($value == '' && ($j == 0 || $j == 10 || $j == 12))
								{
									$somethings_missing = true;
									$msg = 'La columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' esta vacía y es obligatoria';
									break 2;
								}
								elseif($j == 7 && $value != '' && (preg_match('/\d{4}-\d{2}-\d{2}/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value))))//Fecha de nacimiento
								{
									$somethings_missing = true;
									$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' esta mal escrita la fecha de nacimiento, el formato debe ser Año(4 dígitos)-Mes(2 dígitos)-Dia(2 dígitos)';
									break 2;
								}
								elseif($j == 9 && $value != ''  && (preg_match('/[H|M]/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value))))//Sexo
								{
									$somethings_missing = true;
									$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' esta mal escrito el sexo, debe ser H o M';
									break 2;
								}
								elseif($j == 10 && (preg_match('/[A-Z][A,E,I,O,U,X][A-Z]{2}[0-9]{2}[0-1][0-9][0-3][0-9][M,H][A-Z]{2}[B,C,D,F,G,H,J,K,L,M,N,Ñ,P,Q,R,S,T,V,W,X,Y,Z]{3}[0-9,A-Z][0-9]/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value))))//CURP
								{
									$somethings_missing = true;
									$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' esta mal escrita la CURP';
									break 2;
								}
								elseif($j == 12)//RFC
								{

									if(preg_match('/[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value)))
									{
										$somethings_missing = true;
										$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' esta mal escrito el RFC';
										break 2;
									}
									else
									{
										$result = $this->conn->query("SELECT RFC FROM Trabajador WHERE RFC = '{$matches[0]}' AND Cuenta = '{$_SESSION['cuenta']}'");

										if($this->conn->num_rows($result) > 0)
										{
											$somethings_missing = true;
											$msg = "El RFC $value actualmente se encuentra en la base de datos";
											break 2;
										}

									}

								}
								elseif($j == 14 && (preg_match('/\d*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value))))//jornada
								{
									$somethings_missing = true;
									$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' esta mal escrita la jornada, debe ser un número';
									break 2;
								}
								elseif($j == 18 && $value != '')//Servicio
								{

									if(preg_match('/(?:\d{4}-\d{2}-\d{2}\/\d{4}-\d{2}-\d{2}\/\d+)*(?:\|\d{4}-\d{2}-\d{2}\/\d{4}-\d{2}-\d{2}\/\d+)*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value)))
									{
										$somethings_missing = true;
										$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la descripción del servicio es incorrecta';
										break 2;
									}
									else
									{
										$servicios = explode('|', $matches[0]);

										for($k=0; $k<count($servicios); $k++)
										{
											$data = explode('/', $servicios[$k]);

											if($data[0] > $data[1])
											{
												$somethings_missing = true;
												$msg = 'Error: En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la fecha de ingreso al servicio es anterior a la fecha de ingreso con el cliente';
												break 3;
											}

										}

									}

								}
								elseif($j == 19 && $value != '')//Tipo
								{

									if(preg_match('/(?:Asalariado|Asimilable)*(?:\|Asalariado|\|Asimilable)*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value)))
									{
										$somethings_missing = true;
										$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la descripción del tipo de trabajador es incorrecta';
										break 2;
									}

								}
								elseif($j == 20 && $value != '' && (preg_match('/(?:Salario diario|Salario mínimo)*(?:\|Salario diario|\|Salario mínimo)*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value))))//Base
								{
									$somethings_missing = true;
									$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la descripción del salario es incorrecta';
									break 2;
								}
								elseif($j == 21 && $value != '')//Salario
								{

									if(preg_match('/(?:\d+\.\d{2}|\d+)*(?:\|\d+\.\d{2}|\|\d+)*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value)))
									{
										$somethings_missing = true;
										$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la descripción del salario es incorrecta';
										break 2;
									}
									else
									{
										$base = explode('|', trim(preg_replace('/\s+/',' ', $cols[20])));
										$salario = explode('|', $value);

										if(count($base) != count($salario))
										{
											$somethings_missing = true;
											$msg = 'En la fila ' . ($i + 1) . ' la cantidad de Salarios descrita debe ser igual a la cantidad de Bases descritas';
											break 2;
										}

										for($k=0; $k<count($base); $k++)
										{

											if($base[$k] == 'Salario mínimo' && (preg_match('/\d+/', $salario[$k], $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($salario[$k]))))
											{
												$somethings_missing = true;
												$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' el salario no coincide con la base';
												break 3;
											}
											elseif($base[$k] == 'Salario mínimo')
											{
												$result = $this->conn->query("SELECT Codigo FROM Salario_minimo WHERE Codigo = '{$salario[$k]}' AND Cuenta = '{$_SESSION['cuenta']}'");

												if($this->conn->num_rows($result) == 0)
												{
													$somethings_missing = true;
													$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . " el salario mínimo con código {$salario[$k]} no existe en la base de datos";
													break 3;
												}

											}

										}

									}

								}
								elseif($j == 22 && $value != '' && (preg_match('/(?:[a-zA-Z0-9., áéíóúÁÉÍÓÚ]+\/(?:Base|Eventual|Confianza|Sindicalizado|A prueba)\/(?:Diurna|Nocturna|Mixta|Por hora|Reducida|Continuada|Partida|Por turnos))*(?:\|[a-zA-Z0-9., áéíóúÁÉÍÓÚ]+\/(?:Base|Eventual|Confianza|Sindicalizado|A prueba)\/(?:Diurna|Nocturna|Mixta|Por hora|Reducida|Continuada|Partida|Por turnos))*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value))))//Contrato
								{
									$somethings_missing = true;
									$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la descripción del contrato debe ser Puesto/Tipo de contrato/Tipo de jornada. Revice los tipos de contrato y tipos de jornada permitidos';
									break 2;
								}
								elseif($j == 23 && $value != '' && (preg_match('/(?:[a-zA-Z0-9., áéíóúÁÉÍÓÚ&]+\/[a-zA-Z0-9., áéíóúÁÉÍÓÚ&]+\/\d+)*(?:\|[a-zA-Z0-9., áéíóúÁÉÍÓÚ&]+\/[a-zA-Z0-9., áéíóúÁÉÍÓÚ&]+\/\d+)*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value))))//Banco
								{
									$somethings_missing = true;
									$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la descripción del banco debe ser Nombre del banco/Nombre de la sucursal/Número de cuenta';
									break 2;
								}
								elseif($j == 24 && $value != '' && (preg_match('/(?:\d{4})*(?:\|\d{4})*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value))))//UMF
								{
									$somethings_missing = true;
									$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la descripción de la UMF debe ser 4 dígitos';
									break 2;
								}
								elseif($j == 25 && $value != '')//Aportación al fondo de ahorro
								{

									if(preg_match('/(?:\d{1,3})*(?:\|\d{1,3})*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value)))
									{
										$somethings_missing = true;
										$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la aportación al fondo de ahorro debe ser de 1 a 3 dígitos';
										break 2;
									}
									else
									{
										$data = explode('|', $value);

										for($k=0; $k<count($data); $k++)

											if($data[$k] > 100)
											{
												$somethings_missing = true;
												$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la aportación al fondo de ahorro esta fuera de rango';
												break 3;
											}

									}

								}
								elseif($j == 26 && $value != '' && (preg_match('/(?:(?:\d+.?\d*)\/\d{4}-\d{2}-\d{2}\/\d{4}-\d{2}-\d{2}\/[a-zA-Z áéíóúÁÉÍÓÚ&]+\/\d+\/\d+\/\d+\/\d{1,3})*(?:\|(?:\d+.?\d*)\/\d{4}-\d{2}-\d{2}\/\d{4}-\d{2}-\d{2}\/[a-zA-Z áéíóúÁÉÍÓÚ&]+\/\d+\/\d+\/\d+\/\d{1,3})*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value))))//Pensión alimenticia
								{
									$somethings_missing = true;
									$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la descripción de la pensión alimenticia debe ser Cantidad/Fecha de inicio/Fecha de término/Beneficiario/Folio IFE/No. de expediente/No. de oficio/Porcentaje del salario';
									break 2;
								}
								elseif($j == 27 && $value != '')//Fondo de garantía
								{

									if(preg_match('/(?:\d{1,3})*(?:\|\d{1,3})*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value)))
									{
										$somethings_missing = true;
										$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' el porcentaje para el fondo de garantía debe ser de 1 a 3 dígitos';
										break 2;
									}
									else
									{
										$data = explode('|', $value);

										for($k=0; $k<count($data); $k++)

											if($data[$k] > 100)
											{
												$somethings_missing = true;
												$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' el porcentaje del fondo de garantía esta fuera de rango';
												break 3;
											}

									}

								}
								elseif($j == 28 && $value != '' && (preg_match('/(?:\d+\/\d+.?\d*\/\d{4}-\d{2}-\d{2}\/\d{4}-\d{2}-\d{2}\/\d+\/(?:Si|No))*(?:\|\d+\/\d+.?\d*\/\d{4}-\d{2}-\d{2}\/\d{4}-\d{2}-\d{2}\/\d+\/(?:Si|No))*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value))))//FONACOT
								{
									$somethings_missing = true;
									$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la descripción de la retención FONACOT debe ser Número de crédito/Importe total/Fecha de inicio/Fecha de termino/Numero de descuentos/Cobrar un mes anterior(Si|No)';
									break 2;
								}
								elseif($j == 29 && $value != '' && (preg_match('/(?:\d{4}-\d{2}-\d{2}\/\d{4}-\d{2}-\d{2}\/(?:VSM|Porcentual|Monto fijo mensual)\/\d+\/\d+.?\d*\/(?:Si|No))*(?:\|\d{4}-\d{2}-\d{2}\/\d{4}-\d{2}-\d{2}\/(?:VSM|Porcentual|Monto fijo mensual)\/\d+\/\d+.?\d*\/(?:Si|No))*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value))))//INFONAVIT
								{
									$somethings_missing = true;
									$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la descripción de la retención INFONAVIT debe ser Fecha de inicio/Fecha de termino/Tipo/Número de crédito/Valor/Dias exactos(Si|No)';
									break 2;
								}
								elseif($j == 30 && $value != '' && (preg_match('/(?:\d+.?\d*)*(?:\|\d+.?\d*)*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value))))//Pago por seguro de vida
								{
									$somethings_missing = true;
									$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' el pago por seguro de vida debe ser una cantidad';
									break 2;
								}
								elseif($j == 31)//Sucursal
								{

									if(preg_match('/(?:[a-zA-Z0-9., áéíóúÁÉÍÓÚ&]+)*(?:\|[a-zA-Z0-9., áéíóúÁÉÍÓÚ&]+)*/', $value, $matches) == 0 || (isset($matches[0]) && strlen($matches[0]) < strlen($value)))
									{
										$somethings_missing = true;
										$msg = 'En la columna ' . ($j + 1) . ' de la fila ' . ($i + 1) . ' la descripción de la sucursal es incorrecta';
										break 2;
									}
									else
									{
										$sucursal = explode('|', trim(preg_replace('/\s+/',' ', $cols[$j])));
										$servicio = explode('|', trim(preg_replace('/\s+/',' ', $cols[18])));

										for($k=0; $k<count($sucursal); $k++)
										{
											$data = explode('/', $servicio[$k]);
											$result = $this->conn->query("SELECT Sucursal.Nombre FROM Sucursal LEFT JOIN Servicio_Empresa ON Sucursal.Empresa = Servicio_Empresa.Empresa WHERE Servicio_Empresa.Servicio = '{$data[2]}' AND Sucursal.Nombre LIKE BINARY '{$sucursal[$k]}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}'");

											if($this->conn->num_rows($result) == 0)
											{
												$somethings_missing = true;
												$msg = "La sucursal {$cols[$j]} de la columna " . ($j + 1) . ' fila ' . ($i + 1) . " no esta relacionada a la empresa asignada al servicio {$data[2]}";
												break 3;
											}

										}

									}

								}

							}

						}

					}

					if(! $somethings_missing)
					{

						for($i=1; $i<$rows_len - 1; $i++)//rows_len -1 'cause the last line ends with \n
						{
							$cols = preg_split ('/;/', $rows[$i]);
							//Trabajador
							$trabajador = new Trabajador();
							$trabajador_nombre = trim(preg_replace('/\s+/',' ', $cols[0]));
							$trabajador->set('Nombre',$trabajador_nombre);
							$trabajador_telefono = trim(preg_replace('/\s+/',' ', $cols[1]));
							$trabajador->set('Telefono',$trabajador_telefono);
							$trabajador_celular = trim(preg_replace('/\s+/',' ', $cols[2]));
							$trabajador->set('Celular',$trabajador_celular);
							$trabajador_correo = trim(preg_replace('/\s+/',' ', $cols[3]));
							$trabajador->set('Correo_electronico',$trabajador_correo);
							$trabajador_domicilio = trim(preg_replace('/\s+/',' ', $cols[4]));
							$trabajador->set('Domicilio_particular',$trabajador_domicilio);
							$trabajador_nacionalidad = trim(preg_replace('/\s+/',' ', $cols[5]));
							$trabajador->set('Nacionalidad',$trabajador_nacionalidad);
							$trabajador_lugar_de_nacimiento = trim(preg_replace('/\s+/',' ', $cols[6]));
							$trabajador->set('Lugar_de_nacimiento',$trabajador_lugar_de_nacimiento);
							$trabajador_fecha_de_nacimiento = trim(preg_replace('/\s+/',' ', $cols[7]));
							$trabajador->set('Fecha_de_nacimiento',$trabajador_fecha_de_nacimiento);
							$trabajador_estado_civil = trim(preg_replace('/\s+/',' ', $cols[8]));
							$trabajador->set('Estado_civil',$trabajador_estado_civil);
							$trabajador_sexo = trim(preg_replace('/\s+/','', $cols[9]));
							$trabajador->set('Sexo',$trabajador_sexo);
							$trabajador_curp = trim(preg_replace('/\s+/','', $cols[10]));
							$trabajador->set('CURP',$trabajador_curp);
							$trabajador_ife = trim(preg_replace('/\s+/','', $cols[11]));
							$trabajador->set('Numero_IFE',$trabajador_ife);
							$trabajador_rfc = trim(preg_replace('/\s+/','', $cols[12]));
							$trabajador->set('RFC',$trabajador_rfc);
							$trabajador_nss = trim(preg_replace('/\s+/','', $cols[13]));
							$trabajador->set('Numero_IMSS',$trabajador_nss);
							$trabajador_jornada = trim(preg_replace('/\s+/','', $cols[14]));
							$trabajador->set('Jornada',$trabajador_jornada);
							$trabajador_sangre = trim(preg_replace('/\s+/','', $cols[15]));
							$trabajador->set('Tipo_de_sangre',$trabajador_sangre);
							$trabajador_horario = trim(preg_replace('/\s+/',' ', $cols[16]));
							$trabajador->set('Horario',$trabajador_horario);
							$trabajador_avisar = trim(preg_replace('/\s+/',' ', $cols[17]));
							$trabajador->set('Avisar_a',$trabajador_avisar);
							$trabajador->dbStore('false');

							//Servicio_Trabajador
							if($cols[18] != '')
							{
								$servicio = explode('|', trim(preg_replace('/\s+/',' ', $cols[18])));

								for($j=0; $j<count($servicio); $j++)

									if($servicio[$j] != '')
									{
										$servicio_trabajador = new Servicio_Trabajador();
										$servicio_data = explode('/', $servicio[$j]);
										$servicio_trabajador->set('Fecha_de_ingreso_cliente', $servicio_data[0]);
										$servicio_trabajador->set('Fecha_de_ingreso_servicio', $servicio_data[1]);
										$servicio_trabajador->set('Servicio', $servicio_data[2]);
										$servicio_trabajador->set('Trabajador', $trabajador_rfc);
										$servicio_trabajador->dbStore('false');
									}

							}

							//Tipo
							if($cols[19] != '')
							{
								$value = explode('|', trim(preg_replace('/\s+/',' ', $cols[19])));

								for($j=0; $j<count($value); $j++)

									if($value[$j] != '')
									{
										$tipo = new Tipo();

										if(count($servicio) > $j)
										{
											$servicio_data = explode('/', $servicio[$j]);
											$tipo->set('Fecha', $servicio_data[0]);
											$tipo->set('Servicio', $servicio_data[2]);
										}

										$tipo->set('Tipo', $value[$j]);
										$tipo->set('Trabajador', $trabajador_rfc);
										$tipo->dbStore('false');
									}

							}

							//Base
							if($cols[20] != '')
							{
								$bases = explode('|', trim(preg_replace('/\s+/',' ', $cols[20])));

								for($j=0; $j<count($bases); $j++)

									if($bases[$j] != '')
									{
										$base = new Base();

										if(count($servicio) > $j)
										{
											$servicio_data = explode('/', $servicio[$j]);
											$base->set('Fecha', $servicio_data[0]);
											$base->set('Servicio', $servicio_data[2]);
										}

										$base->set('Base', $bases[$j]);
										$base->set('Trabajador', $trabajador_rfc);
										$base->dbStore('false');
									}

							}

							//Salario
							if($cols[21] != '')
							{
								$value = explode('|', trim(preg_replace('/\s+/',' ', $cols[21])));

								for($j=0; $j<count($value); $j++)

									if($value[$j] != '')
									{

										if($bases[$j] == 'Salario mínimo')
										{
											$salario = new Trabajador_Salario_minimo();
											$salario->set('Salario_minimo', $value[$j]);
										}
										else
										{
											$salario = new Salario_diario();
											$salario->set('Cantidad', $value[$j]);
										}

										if(count($servicio) > $j)
										{
											$servicio_data = explode('/', $servicio[$j]);
											$salario->set('Fecha', $servicio_data[0]);
											$salario->set('Servicio', $servicio_data[2]);
										}

										$salario->set('Trabajador', $trabajador_rfc);
										$salario->dbStore('false');
									}

							}

							//Contrato
							if($cols[22] != '')
							{
								$value = explode('|', trim(preg_replace('/\s+/',' ', $cols[22])));

								for($j=0; $j<count($value); $j++)

									if($value[$j] != '')
									{
										$contrato = new Contrato();

										if(count($servicio) > $j)
										{
											$servicio_data = explode('/', $servicio[$j]);
											$contrato->set('Fecha', $servicio_data[0]);
											$contrato->set('Servicio', $servicio_data[2]);
										}

										$contrato->set('Trabajador', $trabajador_rfc);
										$data = explode('/', $value[$j]);
										$contrato->set('Puesto', $data[0]);
										$contrato->set('Tipo', $data[1]);
										$contrato->set('Tipo_de_jornada', $data[2]);
										$contrato->dbStore('false');
									}

							}

							//Banco
							if($cols[23] != '')
							{
								$value = explode('|', trim(preg_replace('/\s+/',' ', $cols[23])));

								for($j=0; $j<count($value); $j++)

									if($value[$j] != '')
									{
										$banco = new Banco();

										if(count($servicio) > $j)
										{
											$servicio_data = explode('/', $servicio[$j]);
											$banco->set('Fecha', $servicio_data[0]);
											$banco->set('Servicio', $servicio_data[2]);
										}

										$banco->set('Trabajador', $trabajador_rfc);
										$data = explode('/', $value[$j]);
										$banco->set('Nombre', $data[0]);
										$banco->set('Sucursal', $data[1]);
										$banco->set('Numero_de_cuenta', $data[2]);
										$banco->dbStore('false');
									}

							}

							//UMF
							if($cols[24] != '')
							{
								$value = explode('|', trim(preg_replace('/\s+/',' ', $cols[24])));

								for($j=0; $j<count($value); $j++)

									if($value[$j] != '')
									{
										$umf = new UMF();

										if(count($servicio) > $j)
										{
											$servicio_data = explode('/', $servicio[$j]);
											$umf->set('Fecha', $servicio_data[0]);
											$umf->set('Servicio', $servicio_data[2]);
										}

										$umf->set('Trabajador', $trabajador_rfc);
										$umf->set('Numero', $value[$j]);
										$umf->dbStore('false');
									}

							}

							//Aportación al fondo de ahorro
							if($cols[25] != '')
							{
								$value = explode('|', trim(preg_replace('/\s+/',' ', $cols[25])));

								for($j=0; $j<count($value); $j++)

									if($value[$j] != '')
									{
										$aportacion = new Aportacion_del_trabajador_al_fondo_de_ahorro();

										if(count($servicio) > $j)
										{
											$servicio_data = explode('/', $servicio[$j]);
											$aportacion->set('Fecha_de_inicio', $servicio_data[0]);
											$aportacion->set('Servicio', $servicio_data[2]);
										}

										$aportacion->set('Trabajador', $trabajador_rfc);
										$aportacion->set('Porcentaje_del_salario', $value[$j]);
										$aportacion->dbStore('false');
									}

							}

							//Pensión alimenticia
							if($cols[26] != '')
							{
								$value = explode('|', trim(preg_replace('/\s+/',' ', $cols[26])));

								for($j=0; $j<count($value); $j++)

									if($value[$j] != '')
									{
										$pension = new Pension_alimenticia();

										if(count($servicio) > $j)
										{
											$servicio_data = explode('/', $servicio[$j]);
											$pension->set('Servicio', $servicio_data[2]);
										}

										$pension->set('Trabajador', $trabajador_rfc);
										$data = explode('/', $value[$j]);
										$pension->set('Cantidad', $data[0]);
										$pension->set('Fecha_de_inicio', $data[1]);
										$pension->set('Fecha_de_termino', $data[2]);
										$pension->set('Beneficiario', $data[3]);
										$pension->set('Folio_IFE', $data[4]);
										$pension->set('No_de_expediente', $data[5]);
										$pension->set('No_de_oficio', $data[6]);
										$pension->set('Porcentaje_del_salario', $data[7]);
										$pension->dbStore('false');
									}

							}

							//Fondo de garantía
							if($cols[27] != '')
							{
								$value = explode('|', trim(preg_replace('/\s+/',' ', $cols[27])));

								for($j=0; $j<count($value); $j++)

									if($value[$j] != '')
									{
										$fondo = new Fondo_de_garantia();

										if(count($servicio) > $j)
										{
											$servicio_data = explode('/', $servicio[$j]);
											$fondo->set('Fecha_de_inicio', $servicio_data[0]);
											$fondo->set('Servicio', $servicio_data[2]);
										}

										$fondo->set('Trabajador', $trabajador_rfc);
										$fondo->set('Porcentaje_del_pago_neto', $value[$j]);
										$fondo->dbStore('false');
									}

							}

							//FONACOT
							if($cols[28] != '')
							{
								$value = explode('|', trim(preg_replace('/\s+/',' ', $cols[28])));

								for($j=0; $j<count($value); $j++)

									if($value[$j] != '')
									{
										$fonacot = new Retencion_FONACOT();

										if(count($servicio) > $j)
										{
											$servicio_data = explode('/', $servicio[$j]);
											$fonacot->set('Servicio', $servicio_data[2]);
										}

										$fonacot->set('Trabajador', $trabajador_rfc);
										$data = explode('/', $value[$j]);
										$fonacot->set('Numero_de_credito', $data[0]);
										$fonacot->set('Importe_total', $data[1]);
										$fonacot->set('Fecha_de_inicio', $data[2]);
										$fonacot->set('Fecha_de_termino', $data[3]);
										$fonacot->set('Numero_de_descuentos', $data[4]);
										$fonacot->set('Cobrar_un_mes_anterior', $data[5] == 'Si' ? 'true' : 'false');
										$fonacot->dbStore('false');
									}

							}

							//INFONAVIT
							if($cols[29] != '')
							{
								$value = explode('|', trim(preg_replace('/\s+/',' ', $cols[29])));

								for($j=0; $j<count($value); $j++)

									if($value[$j] != '')
									{
										$infonavit = new Retencion_INFONAVIT();

										if(count($servicio) > $j)
										{
											$servicio_data = explode('/', $servicio[$j]);
											$infonavit->set('Servicio', $servicio_data[2]);
										}

										$infonavit->set('Trabajador', $trabajador_rfc);
										$data = explode('/', $value[$j]);
										$infonavit->set('Fecha_de_inicio', $data[0]);
										$infonavit->set('Fecha_de_termino', $data[1]);
										$infonavit->set('Tipo', $data[2]);
										$infonavit->set('Numero_de_credito', $data[3]);
										$infonavit->set('Dias_exactos', $data[5] == 'Si' ? 'true' : 'false');
										$infonavit->dbStore('false');

										if($data[2] == 'VSM')
										{
											$obj = new Factor_de_descuento();
											$obj->set('Factor_de_descuento', $data[4]);
										}
										elseif($data[2] == 'Porcentual')
										{
											$obj = new Porcentaje_de_descuento();
											$obj->set('Porcentaje_de_descuento', $data[4]);
										}
										elseif($data[2] == 'Monto fijo mensual')
										{
											$obj = new Monto_fijo_mensual();
											$obj->set('Monto_fijo_mensual', $data[4]);
										}

										$obj->set('Fecha_de_inicio', $data[0]);
										$obj->set('Cobrar_diferencia_inicial', 'false');
										$obj->set('Retencion_INFONAVIT', $infonavit->get('id'));
										$obj->dbStore('false');
									}

							}

							//Pago por seguro de vida
							if($cols[30] != '')
							{
								$value = explode('|', trim(preg_replace('/\s+/',' ', $cols[30])));

								for($j=0; $j<count($value); $j++)

									if($value[$j] != '')
									{
										$pago = new Pago_por_seguro_de_vida();

										if(count($servicio) > $j)
										{
											$servicio_data = explode('/', $servicio[$j]);
											$pago->set('Fecha_de_inicio', $servicio_data[0]);
											$pago->set('Servicio', $servicio_data[2]);
										}

										$pago->set('Trabajador', $trabajador_rfc);
										$pago->set('Cantidad', $value[$j]);
										$pago->dbStore('false');
									}

							}

							//Sucursal
							if($cols[31] != '')
							{
								$value = explode('|', trim(preg_replace('/\s+/',' ', $cols[31])));

								for($j=0; $j<count($value); $j++)

									if($value[$j] != '')
									{
										$trabajador_sucursal = new Trabajador_Sucursal();

										if(count($servicio) > $j)
										{
											$servicio_data = explode('/', $servicio[$j]);
											$trabajador_sucursal->set('Fecha_de_ingreso', $servicio_data[0]);
											$trabajador_sucursal->set('Servicio', $servicio_data[2]);
											$result = $this->conn->query("SELECT Sucursal.Empresa FROM Sucursal LEFT JOIN Servicio_Empresa ON Sucursal.Empresa = Servicio_Empresa.Empresa WHERE Servicio_Empresa.Servicio = '{$servicio_data[2]}' AND Sucursal.Nombre LIKE BINARY '{$value[$j]}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}'");
											list($empresa) = $this->conn->fetchRow($result);
											$trabajador_sucursal->set('Empresa', $empresa);
										}

										$trabajador_sucursal->set('Trabajador', $trabajador_rfc);
										$trabajador_sucursal->set('Nombre', $value[$j]);
										$trabajador_sucursal->dbStore('false');
									}

							}

						}

						if($rows_len == 1)
							$msg = '1 Trabajador importado';
						else
							$msg = ($rows_len - 2) . ' Trabajadores importados';//rows_len - 2 (title row and last \n)

					}

				}
				else//Solicitud de empleo
				{

					//file check
					for($i=0; $i<$rows_len - 1; $i++)//rows_len -1 'cause the last line ends with \n
					{
						$rows[$i] = utf8_encode($rows[$i]);
						$rows[$i] = str_replace ('\\"' , '', $rows[$i]);
						$cols = preg_split ('/;/', $rows[$i]);
						$cols_len = count($cols);

						if($i == 4)//checking "Puesto"
						{

							if($cols[0] == '')
							{
								$somethings_missing = true;
								$msg = 'El puesto es necesario';
								break;
							}
							else
								$_puesto = $cols[0];

						}
						elseif($i == 8)//checking "Fecha de contratación"
						{

							if($cols[29] == '' || preg_match('/\d{4}-\d{2}-\d{2}/', $cols[29]) == 0)
							{
								$somethings_missing = true;
								$msg = 'La fecha de contratación está mal escrita, debe ser Año(4 dígitos)-Mes(2 dígitos)-Día(2 dígitos)';
								break;
							}
							else
								$fecha_de_contratacion = str_replace (' ' , '', $cols[29]);//deleting spaces

						}
						elseif($i == 12)
						{

							if($cols[0] == '')//checking "Apellido paterno"
							{
								$somethings_missing = true;
								$msg = 'El apellido paterno es necesario';
								break;
							}
							else
								$apellido_paterno = $cols[0];

							if($cols[8] == '')//checking "Apellido materno"
							{
								$somethings_missing = true;
								$msg = 'El apellido materno es necesario';
								break;
							}
							else
								$apellido_materno = $cols[8];

							if($cols[16] == '')//checking Nombre
							{
								$somethings_missing = true;
								$msg = 'El nombre es necesario';
								break;
							}
							else
								$nombre = $cols[16];

							if($cols[29] == '')//checking "Edad"
							{
								$somethings_missing = true;
								$msg = 'La edad es nacesaria';
								break;
							}
							else
								$edad = $cols[29];

						}
						elseif($i == 14)
						{

							if($cols[0] == '')//checking "Domicilio"
							{
								$somethings_missing = true;
								$msg = 'El domicilio es necesario';
								break;
							}
							else
								$domicilio = $cols[0];

							if($cols[9] == '')//checking "Colonia"
							{
								$somethings_missing = true;
								$msg = 'La colonia es necesaria';
								break;
							}
							else
								$colonia = $cols[9];

							if($cols[29] == '' && $cols[33] == '')//checking "Sexo"
							{
								$somethings_missing = true;
								$msg = 'Indique un sexo';
								break;
							}
							elseif($cols[29] != '')
								$sexo = 'M';
							else
								$sexo = 'F';

						}
						elseif($i == 16)
						{

							if($cols[0] == '')//checking "Delegación o Municipio"
							{
								$somethings_missing = true;
								$msg = 'La delegación o municipio son necesarios';
								break;
							}
							else
								$delegacion_o_municipio = $cols[0];

							if($cols[12] == '')//checking "Lugar de nacimiento"
							{
								$somethings_missing = true;
								$msg = 'El lugar de nacimiento es necesario';
								break;
							}
							else
								$lugar_de_nacimiento = $cols[12];

							if($cols[21] == '' || preg_match('/\d{4}-\d{2}-\d{2}/', $cols[21]) == 0)//checking "Fecha de nacimiento"
							{
								$somethings_missing = true;
								$msg = 'La fecha de nacimiento está mal escrita, debe ser Año(4 dígitos)-Mes(2 dígitos)-Día(2 dígitos)';
								break;
							}
							else
								$fecha_de_nacimiento = str_replace (' ' , '', $cols[21]);//deleting spaces

							if($cols[29] == '')//checking "Nacionalidad"
							{
								$somethings_missing = true;
								$msg = 'La nacionalidad es necesaria';
								break;
							}
							else
								$nacionalidad = $cols[29];

						}
						elseif($i == 20)//checking "Estado civil"
						{

							if($cols[21] == '' && $cols[27] == '' && $cols[32] == '')
							{
								$somethings_missing = true;
								$msg = 'Indique un estado civil';
								break;
							}
							elseif($cols[21] != '')
								$estado_civil = 'Soltero';
							elseif($cols[27] != '')
								$estado_civil = 'Casado';
							else
								$estado_civil = 'Otro';

						}
						elseif($i == 24)//"CURP"
						{
							$curp = '';

							for($j=0; $j<20; $j++)
								$curp .= $cols[$j];

						}
						elseif($i == 27)
						{

							if(preg_match('/[A-Z0-9]{3,4}[0-9]{6}[A-Z0-9]{3,5}/', $cols[0]) == 0)//checking "RFC"
							{
								$somethings_missing = true;
								$msg = 'El RFC está mal escrito';
								break;
							}
							else
								$rfc = str_replace (' ' , '', $cols[0]);//deleting spaces;

							$numero_imss = $cols[11];
						}

					}

					if(! $somethings_missing)
					{
						$result = $this->conn->query("SELECT RFC FROM Trabajador WHERE RFC = '$rfc' AND Cuenta = '{$_SESSION['cuenta']}'");

						if($this->conn->num_rows($result) == 0)
						{
							//storing trabajador
							$trabajador = new Trabajador();
							$trabajador->set('Nombre',$apellido_paterno . ' ' . $apellido_materno . ' ' . $nombre);
							$trabajador->set('Domicilio_particular',$domicilio . ' ' . $colonia . ' ' . $delegacion_o_municipio . ' ' . $lugar_de_nacimiento);
							$trabajador->set('Nacionalidad',$nacionalidad);
							$trabajador->set('Estado_civil',$estado_civil);
							$trabajador->set('Fecha_de_nacimiento',$fecha_de_nacimiento);
							$trabajador->set('Lugar_de_nacimiento',$lugar_de_nacimiento);
							$trabajador->set('Sexo',$sexo);
							$trabajador->set('CURP',$curp);
							$trabajador->set('Edad',$edad);
							$trabajador->set('RFC',$rfc);
							$trabajador->set('Numero_IMSS',$numero_imss);
							$trabajador->set('Fecha_de_alta_IMSS',$fecha_de_contratacion);
							$trabajador->set('Jornada',8);
							$trabajador->dbStore('false');
							//storing tipo
							$tipo = new Tipo();
							$tipo->set('Tipo','Asalariado');
							$tipo->set('Fecha',$fecha_de_contratacion);
							$tipo->set('Trabajador',$rfc);
							$tipo->dbStore('false');
							//storing contrato
							$contrato = new Contrato();
							$contrato->set('Puesto',$_puesto);
							$contrato->set('Fecha',$fecha_de_contratacion);
							$contrato->set('Trabajador',$rfc);
							$contrato->dbStore('false');
							$msg = 'Trabajador importado';
						}
						else
							$msg = 'El trabajador actualmente se encuentra en la base de datos';

					}

				}

			}
			elseif($ext != 'csv')
				$msg = "El archivo debe tener la extención csv";

			return $msg;
		}

		public function showProperties()//prints properties values
		{

			foreach($this as $key => $value)

				if($key != 'conn' && $key != 'Contenido')
					echo "$key = $value <br />";

		}

		public function setFromDb()//sets properties from data base, but $Nombre has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Archivo_digital WHERE Nombre = '{$this->Nombre}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties if $update is true it updates all database registers (professedly one) with $this' Nombre
		{

			if(isset($this->Nombre))
			{
				$actividad = new Actividad();
				$actividad->set('Dato','Archivo digital');
				$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($trabajador) = $this->conn->fetchRow($result);
				$result = $this->conn->query("SELECT Nombre FROM Empresa WHERE RFC = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($empresa) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$actividad->set('Identificadores',"Nombre: {$this->Nombre} " . ($trabajador != '' ?"Trabajador: $trabajador" : "Empresa: $empresa"));

				if($update == 'false')
				{
					$result = $this->conn->query("SELECT Nombre FROM Archivo_digital WHERE Cuenta = '{$_SESSION['cuenta']}'");

					while($row = $this->conn->fetchRow($result, 'ASSOC'))

						foreach($row as $key => $value)

							if($value == $this->Nombre)
								return 'Nombre de archivo repetido';

					$this->conn->query("INSERT INTO Archivo_digital (Nombre, Cuenta) VALUES ('{$this->Nombre}', '{$_SESSION['cuenta']}')");
					$actividad->set('Operacion','Nuevo');
					$actividad->dbStore();
				}

				foreach($this as $key => $value)

					if(isset($this->$key))
					{

						if($key != 'conn' && $key != 'Nombre' && $key != 'tmpNombre' && $key != 'fp' && $key != 'id')
							$status = $this->conn->query("UPDATE Archivo_digital SET $key  = '$value' WHERE Nombre = '{$this->Nombre}' AND Cuenta = '{$_SESSION['cuenta']}'");

					}
					elseif($key == 'Trabajador' || $key == 'Empresa')
							$this->conn->query("UPDATE Archivo_digital SET $key = NULL WHERE Nombre = '{$this->Nombre}' AND Cuenta = '{$_SESSION['cuenta']}'");

				return 'Listo';
			}

			return 'Error';
		}

		public function dbDelete()//delete this entity from database but Nombre has to be set before
		{

			if(isset($this->Nombre))
			{
				$this->setFromDb();
				$actividad = new Actividad();
				$actividad->set('Operacion','Eliminar');
				$actividad->set('Dato','Archivo digital');
				$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($trabajador) = $this->conn->fetchRow($result);
				$result = $this->conn->query("SELECT Nombre FROM Empresa WHERE RFC = '{$this->Empresa}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($empresa) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$actividad->set('Identificadores',"Nombre: {$this->Nombre} " . ($trabajador != '' ?"Trabajador: $trabajador" : "Empresa: $empresa"));
				$actividad->dbStore();
				$this->conn->query("DELETE FROM Archivo_digital WHERE Nombre = '{$this->Nombre}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

		}

		public function draw($act,$iframe = 0)//draws $this Archivo digital. if $act == 'ADD' or $act == 'EDIT' the fields can be edited and the form is submitted to store_archivo_digital.php. if $act == 'DRAW' the fields can't be edited and the form is not submitted, $iframe is the name the iframe is gonna have
		{
			echo "<div onclick = \"show('Datos_fieldset',this)\" class = \"datos_tab\">Datos</div>";

			if($act == 'ADD')
				$action = 'action = "store_archivo_digital.php?update=false"';
			elseif($act == 'IMPORT')
				$action = 'action = "store_archivo_digital.php?update=import"';
			elseif($act == '_IMPORT')
				$action = 'action = "store_archivo_digital.php?update=_import"';

			if($act == 'ADD' || $act == 'IMPORT' || $act == '_IMPORT')
				echo "<form enctype = \"multipart/form-data\" $action method = \"post\" target = \"$iframe\" class = \"show_form\" onsubmit = \"return form_val(this)\">";
			else
				echo "<form class = \"show_form\">";

				echo "<fieldset class = \"Datos_fieldset\" style = \"visibility:visible\"\>";
					echo "<textarea class = \"hidden_textarea\" name = \"iframe\" readonly=true>$iframe</textarea>";
					echo "<textarea class = \"hidden_textarea\" name = \"Trabajador\" readonly=true>{$this->Trabajador}</textarea>";
					echo "<textarea class = \"hidden_textarea\" name = \"Empresa\" readonly=true>{$this->Empresa}</textarea>";

					echo "<label class = \"archivo_label\">Archivo</label>";

					if ($act == 'ADD' || $act == 'IMPORT' || $act == '_IMPORT')
					{
						echo "<input type = \"file\" class = \"file_input\" name = \"datafile\" id = \"datafile\" title = \"Archivo\" value = \"{$this->Nombre}\" onchange = \"copy_file_name(this)\"/>";
						echo "<textarea class = \"datafile_aux_textarea\"></textarea>";
						echo "<img class = 'aux_button'\>";
					}
					elseif($act == 'DRAW' || $act == 'EDIT')
					{
						echo "<textarea class = \"archivo_textarea\" name = \"Nombre\" title = \"Nombre\" readonly=true>$this->Nombre</textarea>";
						echo "<label class = \"descargar_label\">Descargar</label>";
						echo "<img class = 'download_button' onclick=\"open_file('{$this->Nombre}')\" />";//function open_file() at entities.js
					}

				echo "</fieldset>";

			if($act == 'ADD' || $act == 'IMPORT' || $act == '_IMPORT')
				echo '<input type = "submit" title = "Guardar" class = "submit_button" value = "ok" id = "submit_button" />';
			else
				echo "<img title = \"Guardar\"class = \"submit_button\" onclick = \"_submit('$act',this.parentNode,'Archivo_digital')\" />";//_submit() at common_entities.js
			
			echo "</form>";
			echo "<iframe id=\"$iframe\" name=\"$iframe\" src=\"#\" style=\"visibility:hidden\"></iframe>";

		}

	}

?>
