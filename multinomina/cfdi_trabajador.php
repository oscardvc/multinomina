<?php
	include_once('connection.php');
	include_once('sello_digital.php');
	include_once('actividad.php');

	//Class CFDI_Trabajador definition
	class CFDI_Trabajador
	{
		//class properties
		//data
		private $id;
		private $CFDI;//CFDI String
		private $Fecha;
		private $Acuse_cancelacion;//Acuse String
		private $Status;//Activo o Cancelado
		private $Emisor;//Empresa(RFC)
		private $Receptor;//Trabajador(RFC)
		private $Tipo;//Nomina, Aguinaldo, Vacaciones, Finiquito...
		private $Nomina;
		private $Recibo_de_vacaciones;
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

		private function setID()
		{
			$result = $this->conn->query("SELECT id FROM CFDI_Trabajador WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY id DESC LIMIT 1");
			$row = $this->conn->fetchRow($result);

			if(isset($row))
			{
				list($this->id) = $row;
				$this->id ++;
			}
			else
				$this->id = 1;

		}

		public function setFromDB()//sets properties from data base, but $id has to be set before
		{
			$result = $this->conn->query("SELECT * FROM CFDI_Trabajador WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

			while($row = $this->conn->fetchRow($result,'ASSOC'))

				foreach($row as $key => $value)

					if($key != 'Cuenta')
						$this->$key = $value;

		}

		public function get($name)//gets property named $name
		{
			return $this->$name;
		}

		public function getXML()//prints CFDI XML
		{
			$this->setFromDB();
			echo $this->CFDI;
		}

		public function getPrint()//prints CFDI
		{
			$CFDINS = 'http://www.sat.gob.mx/cfd/3';
			$NominaNS = 'http://www.sat.gob.mx/nomina';
			$this->setFromDB();
			$XMLDoc = new DOMDocument('1.0', 'UTF-8');
			$cfdi = new DOMDocument('1.0', 'UTF-8');
			$cfdi->loadXML($this->CFDI);
			$xpath = new DOMXPath($cfdi);
			$xpath->registerNamespace('cfdi', $CFDINS);
			$xpath->registerNamespace('nomina', $NominaNS);
			$emisor = $xpath->query("/cfdi:Comprobante/cfdi:Emisor");
			$comprobante = $xpath->query("/cfdi:Comprobante");
			$nomina = $xpath->query("//nomina:Nomina");
			$RegistroPatronal = $nomina->item(0)->getAttribute('RegistroPatronal');
			$result = $this->conn->query("SELECT Empresa, Sucursal, Empresa_sucursal FROM Registro_patronal WHERE Numero = '$RegistroPatronal' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($empresa, $sucursal, $empresa_sucursal) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if(isset($sucursal))
			{
				$result = $this->conn->query("SELECT Width, Height FROM Logo WHERE Sucursal = '$sucursal' AND Empresa_sucursal = '$empresa_sucursal' AND Cuenta = '{$_SESSION['cuenta']}'");

				if($this->conn->num_rows($result) == 0)
					$result = $this->conn->query("SELECT Width, Height FROM Logo WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");

			}
			else
				$result = $this->conn->query("SELECT Width, Height FROM Logo WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");

			list($width, $height) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Status FROM CFDI_Trabajador WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($status) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$emisor->item(0)->setAttribute('width', $width);
			$emisor->item(0)->setAttribute('height', $height);
			$emisor->item(0)->setAttribute('sucursal', $sucursal);
			$comprobante->item(0)->setAttribute('status', $status);
			$xslt = $XMLDoc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="cfdi_trabajador.xsl"');
			$XMLDoc->appendChild($xslt);
			$Root = $XMLDoc->createElement('Raiz');
			$XMLDoc->appendChild($Root);
			$c = $XMLDoc->importNode($cfdi->firstChild, true);
			$Root->appendChild($c);
			echo $XMLDoc->saveXML();
		}

		public function dbStore($tipo)//Stores a new CFDI_Trabajador at database
		{
			$this->setID();

			if($tipo == 'Nomina')
				$this->conn->query("INSERT INTO CFDI_Trabajador(id, CFDI, Fecha, Status, Emisor, Receptor, Tipo, Nomina, Recibo_de_vacaciones, Cuenta) VALUES ('{$this->id}', '{$this->CFDI}', '{$this->Fecha}', '{$this->Status}', '{$this->Emisor}', '{$this->Receptor}', '{$this->Tipo}', '{$this->Nomina}', NULL, '{$_SESSION['cuenta']}')");
			elseif($tipo == 'Vacaciones')
				$this->conn->query("INSERT INTO CFDI_Trabajador(id, CFDI, Fecha, Status, Emisor, Receptor, Tipo, Nomina, Recibo_de_vacaciones, Cuenta) VALUES ('{$this->id}', '{$this->CFDI}', '{$this->Fecha}', '{$this->Status}', '{$this->Emisor}', '{$this->Receptor}', '{$this->Tipo}', NULL, '{$this->Recibo_de_vacaciones}', '{$_SESSION['cuenta']}')");

		}

		public function CFDINomina($array, $timbrar, $tipo)//tipo: Nomina, Vacaciones...
		{
			date_default_timezone_set('America/Mexico_City');
			$ComplementoNomina = new DOMDocument('1.0', 'UTF-8');
			$ComplementoNomina->loadXML($array[0]);
			$this->Fecha = $array[1];
			$metodoDePago = trim(preg_replace('/\s+/',' ', $array[2]));
			$this->Emisor = trim(preg_replace('/\s+/',' ', $array[3]));
			$Emisor_sucursal = trim(preg_replace('/\s+/',' ', $array[4]));
			$this->Receptor = trim(preg_replace('/\s+/',' ', $array[5]));
			$this->Tipo = $tipo;

			if($tipo == 'Nomina')
			{
				$this->Nomina = $array[6];
				$result = $this->conn->query("SELECT id FROM CFDI_Trabajador WHERE Status = 'Activo' AND Receptor = '{$this->Receptor}' AND Nomina = '{$this->Nomina}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}
			elseif($tipo == 'Vacaciones')
			{
				$this->Recibo_de_vacaciones = $array[6];
				$result = $this->conn->query("SELECT id FROM CFDI_Trabajador WHERE Status = 'Activo' AND Receptor = '{$this->Receptor}' AND Recibo_de_vacaciones = '{$this->Recibo_de_vacaciones}' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

			if($this->conn->num_rows($result) > 0)
				return NULL;

			//generando CFDI
			$CFDINS = 'http://www.sat.gob.mx/cfd/3';
			$NominaNS = 'http://www.sat.gob.mx/nomina';
			$SchemaInstanceNS = 'http://www.w3.org/2001/XMLSchema-instance';
			$SATSchemaLocation = 'http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd';
			$XMLDoc = new DOMDocument('1.0', 'UTF-8');
			$Comprobante = $XMLDoc->createElementNS($CFDINS, 'cfdi:Comprobante');
			$Comprobante->setAttributeNS($SchemaInstanceNS, 'xsi:schemaLocation', $SATSchemaLocation);
			$XMLDoc->appendChild($Comprobante);
			//Cadena original
			$cadenaOriginal = '||';
			//Version
			$version = 3.2;
			$cadenaOriginal .= $version;
			//Fecha
			$date = new DateTime();
			$fechaPago = $ComplementoNomina->firstChild->getAttribute('FechaPago');

			if(1)//$date->format('Y-m-d') == $fechaPago)
			{
				$fecha = $date->format('Y-m-d') . 'T' . $date->format('H:i:s');
				$cadenaOriginal .= "|$fecha";
			}
			else
				return "La fecha de pago de la nómina debe ser la de hoy";

			//Tipo de comprobante
			$tipoDeComprobante = 'egreso';
			$cadenaOriginal .= "|$tipoDeComprobante";
			//Forma de pago
			$formaDePago = 'PAGO EN UNA SOLA EXHIBICION';
			$cadenaOriginal .= "|$formaDePago";
			//subTotal
			$percepciones = $ComplementoNomina->getElementsByTagNameNS($NominaNS, 'Percepciones');
			$percepcionesGrabadas = $percepciones->item(0)->getAttribute('TotalGravado');
			$percepcionesExentas = $percepciones->item(0)->getAttribute('TotalExento');
			$subTotal = $percepcionesGrabadas + $percepcionesExentas;
			$cadenaOriginal .= "|$subTotal";
			//descuento
			$xpath = new DOMXPath($ComplementoNomina);
			$xpath->registerNamespace('nomina', $NominaNS);
			$ISRNodes = $xpath->query("//nomina:Deduccion[@Concepto='ISR']");
			$ISR = $ISRNodes->item(0)->getAttribute('ImporteExento');
			$deducciones = $ComplementoNomina->getElementsByTagNameNS($NominaNS, 'Deducciones');
			$deduccionesExentas = $deducciones->item(0)->getAttribute('TotalExento');
			$descuento = $deduccionesExentas - $ISR;
			$cadenaOriginal .= "|$descuento";
			//motivo del descuento
			$motivoDescuento = 'DEDUCCIONES NOMINA';
			//total
			$total = $subTotal - $deduccionesExentas;
			$cadenaOriginal .= "|$total";
			//Método de pago
			$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->Receptor}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($nombre_receptor) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$nombre_receptor = trim(preg_replace('/\s+/',' ', $nombre_receptor));;

			if(isset($metodoDePago) && $metodoDePago != '')
				$cadenaOriginal .= "|$metodoDePago";
			else
				return "Por favor agrege un método de pago para $nombre_receptor";

			//Lugar de expedición

			if($Emisor_sucursal != '')
				$result = $this->conn->query("SELECT Empresa.Nombre, Sucursal.Calle, Sucursal.Numero_ext, Sucursal.Numero_int, Sucursal.Colonia, Sucursal.Localidad, Sucursal.Referencia, Sucursal.Municipio, Sucursal.Estado, Sucursal.Pais, Sucursal.CP FROM Sucursal LEFT JOIN Empresa ON Sucursal.Empresa = Empresa.RFC WHERE Sucursal.Nombre = '{$Emisor_sucursal}' AND Sucursal.Empresa = '{$this->Emisor}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}'");
			else
				$result = $this->conn->query("SELECT Nombre, Calle, Numero_ext, Numero_int, Colonia, Localidad, Referencia, Municipio, Estado, Pais, CP FROM Empresa WHERE RFC = '{$this->Emisor}' AND Cuenta = '{$_SESSION['cuenta']}'");

			list($nombre_emisor, $calle, $noExterior, $noInterior, $colonia, $localidad, $referencia, $municipio, $estado, $pais, $codigoPostal) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$nombre_emisor = trim(preg_replace('/\s+/',' ', $nombre_emisor));
			$calle = trim(preg_replace('/\s+/',' ', $calle));
			$noExterior = trim(preg_replace('/\s+/',' ', $noExterior));
			$noInterior = trim(preg_replace('/\s+/',' ', $noInterior));
			$colonia = trim(preg_replace('/\s+/',' ', $colonia));
			$localidad = trim(preg_replace('/\s+/',' ', $localidad));
			$referencia = trim(preg_replace('/\s+/',' ', $referencia));
			$municipio = trim(preg_replace('/\s+/',' ', $municipio));
			$estado = trim(preg_replace('/\s+/',' ', $estado));
			$pais = trim(preg_replace('/\s+/',' ', $pais));
			$codigoPostal = trim(preg_replace('/\s+/',' ', $codigoPostal));

			if(isset($localidad) && $localidad != '')
				$cadenaOriginal .= "|$localidad";
			else
				return "Por favor agrege la localidad de $nombre_emisor " . ($Emisor_sucursal != "" ? $Emisor_sucursal : "") . " porque se utiliza como el lugar de expedición de los CFDI";

			//Emisor
			$_nombre_emisor = str_replace('&', '&amp;', $nombre_emisor);
			$_nombre_emisor = str_replace('"','&quot;', $_nombre_emisor);
			$_nombre_emisor = str_replace('<','&lt;', $_nombre_emisor);
			$_nombre_emisor = str_replace('<','&gt;', $_nombre_emisor);
			$_nombre_emisor = str_replace("'",'&apos;', $_nombre_emisor);
			$Emisor = $XMLDoc->createElementNS($CFDINS, 'cfdi:Emisor');
			$Comprobante->appendChild($Emisor);
			//RFC
			$matches = array();
			$flag = preg_match('/[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?/' , $this->Emisor, $matches);

			if($flag && count($matches) == 1 && strlen($this->Emisor) <= 13 && strlen($this->Emisor) >= 12)
			{
				$Emisor->setAttribute('rfc', $this->Emisor);
				$cadenaOriginal .= "|{$this->Emisor}";
			}
			else
				return "Por favor corrija el RFC de $nombre_emisor";

			//Nombre
			$Emisor->setAttribute('nombre', $_nombre_emisor);
			$cadenaOriginal .= "|$_nombre_emisor";
			//Domicilio fiscal
			$DomicilioFiscal = $XMLDoc->createElementNS($CFDINS, 'cfdi:DomicilioFiscal');
			$Emisor->appendChild($DomicilioFiscal);

			//calle
			if(isset($calle) && $calle != '')
			{
				$DomicilioFiscal->setAttribute('calle', $calle);
				$cadenaOriginal .= "|$calle";
			}
			else
				return "Por agregue la calle del domicilio de $nombre_emisor " . ($Emisor_sucursal != "" ? $Emisor_sucursal : "");

			//Número exterior
			if(isset($noExterior) && $noExterior != '')
			{
				$DomicilioFiscal->setAttribute('noExterior', $noExterior);
				$cadenaOriginal .= "|$noExterior";
			}

			//Número interior
			if(isset($noInterior) && $noInterior != '')
			{
				$DomicilioFiscal->setAttribute('noInterior', $noInterior);
				$cadenaOriginal .= "|$noInterior";
			}

			//Colonia
			if(isset($colonia) && $colonia != '')
			{
				$DomicilioFiscal->setAttribute('colonia', $colonia);
				$cadenaOriginal .= "|$colonia";
			}

			//Localidad
			if(isset($localidad) && $localidad != '')
			{
				$DomicilioFiscal->setAttribute('localidad', $localidad);
				$cadenaOriginal .= "|$localidad";
			}

			//Referencia
			if(isset($referencia) && $referencia != '')
			{
				$DomicilioFiscal->setAttribute('referencia', $referencia);
				$cadenaOriginal .= "|$referencia";
			}

			//Municipio
			if(isset($municipio) && $municipio != '')
			{
				$DomicilioFiscal->setAttribute('municipio', $municipio);
				$cadenaOriginal .= "|$municipio";
			}
			else
				return "Por favor corrija el municipio de $nombre_emisor " . ($Emisor_sucursal != "" ? $Emisor_sucursal : "");

			//Estado
			if(isset($estado) && $estado != '')
			{
				$DomicilioFiscal->setAttribute('estado', $estado);
				$cadenaOriginal .= "|$estado";
			}
			else
				return "Por favor corrija el estado de $nombre_emisor " . ($Emisor_sucursal != "" ? $Emisor_sucursal : "");

			//País
			if(isset($pais) && $pais != '')
			{
				$DomicilioFiscal->setAttribute('pais', $pais);
				$cadenaOriginal .= "|$pais";
			}
			else
				return "Por favor corrija el país de $nombre_emisor " . ($Emisor_sucursal != "" ? $Emisor_sucursal : "");

			//Código postal
			$matches = array();
			$flag = preg_match('/\d{5}/' , $codigoPostal, $matches);

			if(isset($codigoPostal) && $flag && count($matches) == 1 && strlen($codigoPostal) == 5)
			{
				$DomicilioFiscal->setAttribute('codigoPostal', $codigoPostal);
				$cadenaOriginal .= "|$codigoPostal";
			}
			else
				return "El código postal de $nombre_emisor " . ($Emisor_sucursal != "" ? $Emisor_sucursal : "") . " debe ser de 5 dígitos";

			//Régimen fiscal
			$result = $this->conn->query("SELECT Regimen FROM Regimen_fiscal WHERE Empresa = '{$this->Emisor}' AND Cuenta = '{$_SESSION['cuenta']}'");

			if($this->conn->num_rows($result) > 0)
			{

				while(list($Regimen) = $this->conn->fetchRow($result))
				{
					$Regimen = trim(preg_replace('/\s+/',' ', $Regimen));
					$RegimenFiscal = $XMLDoc->createElementNS($CFDINS, 'cfdi:RegimenFiscal');
					$Emisor->appendChild($RegimenFiscal);
					$RegimenFiscal->setAttribute('Regimen', $Regimen);
					$cadenaOriginal .= "|$Regimen";
				}

			}
			else
				return "Por favor asigne un régimen fiscal a $nombre_emisor " . ($Emisor_sucursal != "" ? $Emisor_sucursal : "");

			$this->conn->freeResult($result);
			//Receptor
			$Receptor = $XMLDoc->createElementNS($CFDINS, 'cfdi:Receptor');
			$Comprobante->appendChild($Receptor);
			//RFC
			$matches = array();
			$flag = preg_match('/[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?/' , $this->Receptor, $matches);

			if($flag && count($matches) == 1 && strlen($this->Receptor) <= 13)
			{
				$Receptor->setAttribute('rfc', $this->Receptor);
				$cadenaOriginal .= "|{$this->Receptor}";
			}
			else
				return "Por favor corrija el RFC de $nombre_receptor";

			//Nombre
			$Receptor->setAttribute('nombre', $nombre_receptor);
			$cadenaOriginal .= "|$nombre_receptor";
			//Conceptos
			$Conceptos = $XMLDoc->createElementNS($CFDINS, 'cfdi:Conceptos');
			$Comprobante->appendChild($Conceptos);
			//Concepto
			$Concepto = $XMLDoc->createElementNS($CFDINS, 'cfdi:Concepto');
			$Conceptos->appendChild($Concepto);
			//Cantidad
			$Concepto->setAttribute('cantidad', 1);
			$cadenaOriginal .= "|1";
			//Unidad
			$Concepto->setAttribute('unidad', 'Servicio');
			$cadenaOriginal .= "|Servicio";
			//Descripcion
			if($tipo == 'Nomina')
			{
				$Concepto->setAttribute('descripcion', 'PAGO DE NOMINA');
				$cadenaOriginal .= "|PAGO DE NOMINA";
			}
			elseif($tipo == 'Vacaciones')
			{
				$Concepto->setAttribute('descripcion', 'PAGO DE VACACIONES');
				$cadenaOriginal .= "|PAGO DE VACACIONES";
			}
			//Valor unitario
			$Concepto->setAttribute('valorUnitario', $subTotal);
			$cadenaOriginal .= "|$subTotal";
			//Importe
			$Concepto->setAttribute('importe', $subTotal);
			$cadenaOriginal .= "|$subTotal";
			//Impuestos
			$Impuestos = $XMLDoc->createElementNS($CFDINS, 'cfdi:Impuestos');
			$Comprobante->appendChild($Impuestos);
			//Retenciones
			$Retenciones = $XMLDoc->createElementNS($CFDINS, 'cfdi:Retenciones');
			$Impuestos->appendChild($Retenciones);
			//Retencion
			$Retencion = $XMLDoc->createElementNS($CFDINS, 'cfdi:Retencion');
			$Retenciones->appendChild($Retencion);
			//impuesto
			$Retencion->setAttribute('impuesto','ISR');
			$cadenaOriginal .= "|ISR";
			//importe
			$Retencion->setAttribute('importe', $ISR);
			$cadenaOriginal .= "|$ISR";
			//Complemento
			$Complemento = $XMLDoc->createElementNS($CFDINS, 'cfdi:Complemento');
			$Comprobante->appendChild($Complemento);
			//Complemento nomina
			$cN = $XMLDoc->importNode($ComplementoNomina->firstChild, true);
			$Complemento->appendChild($cN);
			//Version
			$versionNomina = $cN->getAttribute('Version');
			$cadenaOriginal .= "|$versionNomina";
			//Registro patronal
			$RegistroPatronal = $cN->getAttribute('RegistroPatronal');

			if(isset($RegistroPatronal) && $RegistroPatronal != '')
				$cadenaOriginal .= "|$RegistroPatronal";

			//Número de empleado
			$NumEmpleado = $cN->getAttribute('NumEmpleado');
			$cadenaOriginal .= "|$NumEmpleado";
			//CURP
			$CURP = $cN->getAttribute('CURP');
			$cadenaOriginal .= "|$CURP";
			//Tipo de régimen
			$TipoRegimen = $cN->getAttribute('TipoRegimen');
			$cadenaOriginal .= "|$TipoRegimen";
			//Número de seguridad social
			$NumSeguridadSocial = $cN->getAttribute('NumSeguridadSocial');

			if(isset($NumSeguridadSocial) && $NumSeguridadSocial != '')
				$cadenaOriginal .= "|$NumSeguridadSocial";

			//Fecha de pago
			$FechaPago = $cN->getAttribute('FechaPago');
			$cadenaOriginal .= "|$FechaPago";
			//Fecha inicial de pago
			$FechaInicialPago = $cN->getAttribute('FechaInicialPago');
			$cadenaOriginal .= "|$FechaInicialPago";
			//Fecha final de pago
			$FechaFinalPago = $cN->getAttribute('FechaFinalPago');
			$cadenaOriginal .= "|$FechaFinalPago";
			//Número de días pagados
			$NumDiasPagados = $cN->getAttribute('NumDiasPagados');
			$cadenaOriginal .= "|$NumDiasPagados";
			//Fecha de inicio de la relación laboral
			$FechaInicioRelLaboral = $cN->getAttribute('FechaInicioRelLaboral');

			if(isset($FechaInicioRelLaboral) && $FechaInicioRelLaboral != '')
				$cadenaOriginal .= "|$FechaInicioRelLaboral";

			//Antigüedad
			$Antiguedad = $cN->getAttribute('Antiguedad');

			if(isset($Antiguedad) && $Antiguedad != '')
				$cadenaOriginal .= "|$Antiguedad";

			//Puesto
			$Puesto = $cN->getAttribute('Puesto');

			if(isset($Puesto) && $Puesto != '')
				$cadenaOriginal .= "|$Puesto";

			//Tipo de contrato
			$TipoContrato = $cN->getAttribute('TipoContrato');

			if(isset($TipoContrato) && $TipoContrato != '')
				$cadenaOriginal .= "|$TipoContrato";

			//Tipo de jornada
			$TipoJornada = $cN->getAttribute('TipoJornada');

			if(isset($TipoJornada) && $TipoJornada != '')
				$cadenaOriginal .= "|$TipoJornada";

			//Periodicidad del pago
			$PeriodicidadPago = $cN->getAttribute('PeriodicidadPago');
			$cadenaOriginal .= "|$PeriodicidadPago";
			//Salario base de cotización de aportaciones
			$SalarioBaseCotApor = $cN->getAttribute('SalarioBaseCotApor');

			if(isset($SalarioBaseCotApor) && $SalarioBaseCotApor != '')
				$cadenaOriginal .= "|$SalarioBaseCotApor";

			//Riesgo del puesto
			$RiesgoPuesto = $cN->getAttribute('RiesgoPuesto');

			if(isset($RiesgoPuesto) && $RiesgoPuesto != '')
				$cadenaOriginal .= "|$RiesgoPuesto";

			//Salario diario integrado
			$SalarioDiarioIntegrado = $cN->getAttribute('SalarioDiarioIntegrado');

			if(isset($SalarioDiarioIntegrado) && $SalarioDiarioIntegrado != '')
				$cadenaOriginal .= "|$SalarioDiarioIntegrado";

			//Percepciones
			$PercepcionesNodes = $xpath->query("/nomina:Nomina/nomina:Percepciones");
			$Percepciones = $PercepcionesNodes->item(0);
			//Total gravado
			$TotalGravado = $Percepciones->getAttribute('TotalGravado');
			$cadenaOriginal .= "|$TotalGravado";
			//Total exento
			$TotalExento = $Percepciones->getAttribute('TotalExento');
			$cadenaOriginal .= "|$TotalExento";
			//Percepcion
			$PercepcionNodes = $xpath->query("/nomina:Nomina/nomina:Percepciones/nomina:Percepcion");

			for($i=0; $i<$PercepcionNodes->length; $i++)
			{
				$Percepcion = $PercepcionNodes->item($i);
				//Tipo de percepción
				$TipoPercepcion = $Percepcion->getAttribute('TipoPercepcion');
				$cadenaOriginal .= "|$TipoPercepcion";
				//Clave
				$Clave = $Percepcion->getAttribute('Clave');
				$cadenaOriginal .= "|$Clave";
				//Concepto
				$Concepto = $Percepcion->getAttribute('Concepto');
				$cadenaOriginal .= "|$Concepto";
				//Importe gravado
				$ImporteGravado = $Percepcion->getAttribute('ImporteGravado');
				$cadenaOriginal .= "|$ImporteGravado";
				//Importe exento
				$ImporteExento = $Percepcion->getAttribute('ImporteExento');
				$cadenaOriginal .= "|$ImporteExento";
			}

			//Deducciones
			$DeduccionesNodes = $xpath->query("/nomina:Nomina/nomina:Deducciones");
			$Deducciones = $DeduccionesNodes->item(0);
			//Total gravado
			$TotalGravado = $Deducciones->getAttribute('TotalGravado');
			$cadenaOriginal .= "|$TotalGravado";
			//Total exento
			$TotalExento = $Deducciones->getAttribute('TotalExento');
			$cadenaOriginal .= "|$TotalExento";
			//Deduccion
			$DeduccionNodes = $xpath->query("/nomina:Nomina/nomina:Deducciones/nomina:Deduccion");

			for($i=0; $i<$DeduccionNodes->length; $i++)
			{
				$Deduccion = $DeduccionNodes->item($i);
				//Tipo de deduccion
				$TipoDeduccion = $Deduccion->getAttribute('TipoDeduccion');
				$cadenaOriginal .= "|$TipoDeduccion";
				//Clave
				$Clave = $Deduccion->getAttribute('Clave');
				$cadenaOriginal .= "|$Clave";
				//Concepto
				$Concepto = $Deduccion->getAttribute('Concepto');
				$cadenaOriginal .= "|$Concepto";
				//Importe gravado
				$ImporteGravado = $Deduccion->getAttribute('ImporteGravado');
				$cadenaOriginal .= "|$ImporteGravado";
				//Importe exento
				$ImporteExento = $Deduccion->getAttribute('ImporteExento');
				$cadenaOriginal .= "|$ImporteExento";
			}

			$cadenaOriginal .= "||";
			//Sello
			if($Emisor_sucursal != '')
				$result = $this->conn->query("SELECT id FROM Sello_digital WHERE Sucursal = '{$Emisor_sucursal}' AND Empresa_sucursal = '{$this->Emisor}' AND Cuenta = '{$_SESSION['cuenta']}'");
			else
				$result = $this->conn->query("SELECT id FROM Sello_digital WHERE Empresa = '{$this->Emisor}' AND Cuenta = '{$_SESSION['cuenta']}'");

			list($id) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$sello_digital = new Sello_digital();
			$sello_digital->set('id', $id);
			$certificado = $sello_digital->get('Certificado');

			if(!isset($certificado))
				return "Falta el certificado de sello digital de $nombre_emisor $Emisor_sucursal";

			$noCertificado = $sello_digital->getNoCertificado();
			$privateKey = $sello_digital->getClavePrivada();
			openssl_sign($cadenaOriginal, $signature, $privateKey, OPENSSL_ALGO_SHA1);
			//Setting attributes in specified order
			$Comprobante->setAttribute('version', $version);
			$Comprobante->setAttribute('fecha', $fecha);
			$Comprobante->setAttribute('sello', base64_encode($signature));
			$Comprobante->setAttribute('formaDePago', $formaDePago);
			$Comprobante->setAttribute('noCertificado', $noCertificado);
			$Comprobante->setAttribute('certificado', $certificado);
			$Comprobante->setAttribute('subTotal', $subTotal);
			$Comprobante->setAttribute('descuento', $descuento);
			$Comprobante->setAttribute('motivoDescuento', $motivoDescuento);
			$Comprobante->setAttribute('total', $total);
			$Comprobante->setAttribute('tipoDeComprobante', $tipoDeComprobante);
			$Comprobante->setAttribute('metodoDePago', $metodoDePago);
			$Comprobante->setAttribute('LugarExpedicion', $localidad);

			if($timbrar)
				return $this->Timbrar($XMLDoc->saveXML(), $tipo);

		}

		private function Timbrar($xml, $tipo)
		{
			$result = $this->conn->query("SELECT User, Password FROM CFDI_User WHERE Empresa = '{$this->Emisor}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($username, $password) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$url = "https://facturacion.finkok.com/servicios/soap/stamp.wsdl";
//			$url = "https://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl";
			$CFDINS = 'http://www.sat.gob.mx/cfd/3';
			$client = new SoapClient($url);
			$params = array("xml" => $xml, "username" => $username, "password" => $password);
			$response = $client->__soapCall("stamp", array($params));
			$XMLDoc = new DOMDocument('1.0', 'UTF-8');
			$XMLDoc->loadXML($xml);
			$Comprobante = $XMLDoc->firstChild;
			$Receptores = $Comprobante->getElementsByTagNameNS($CFDINS, 'Receptor');
			$Receptor = $Receptores->item(0);
			$trabajador = $Receptor->getAttribute('rfc');
			$Incidencias = (array) $response->stampResult->Incidencias;

			if(count($Incidencias) > 0)
			{

				foreach($Incidencias as $Incidencia)
				{
					$errors = '';

					if(isset($Incidencia->CodigoError) && $Incidencia->CodigoError == '501' || $Incidencia->CodigoError == '708')//query pending
					{
						$errors .= 'En espera. ';
					}
					elseif(isset($Incidencia->MensajeIncidencia))
						$errors .= ($errors != '' ? '. ' . $Incidencia->MensajeIncidencia : $Incidencia->MensajeIncidencia);
					else
						$errors .= ($errors != '' ? '. ' . print_r($response) : print_r($response));

				}

//				$XMLDoc->save('temp/' . $this->Receptor . '.xml');
				return "$errors,$trabajador";
			}
			else
			{
				$Complementos = $Comprobante->getElementsByTagNameNS($CFDINS, 'Complemento');
				$Complemento = $Complementos->item(0);
				$tfdNS = 'http://www.sat.gob.mx/TimbreFiscalDigital';
				$SchemaInstanceNS = 'http://www.w3.org/2001/XMLSchema-instance';
				$tfdSchemaLocation = 'http://www.sat.gob.mx/TimbreFiscalDigital http://www.sat.gob.mx/TimbreFiscalDigital/TimbreFiscalDigital.xsd';
				$TimbreFiscalDigital = $XMLDoc->createElementNS($tfdNS, 'tfd:TimbreFiscalDigital');
				$TimbreFiscalDigital->setAttributeNS($SchemaInstanceNS, 'xsi:schemaLocation', $tfdSchemaLocation);
				$TimbreFiscalDigital->setAttribute('version', '1.0');
				$TimbreFiscalDigital->setAttribute('UUID', $response->stampResult->UUID);
				$TimbreFiscalDigital->setAttribute('FechaTimbrado', $response->stampResult->Fecha);
				$TimbreFiscalDigital->setAttribute('selloCFD', $Comprobante->getAttribute('sello'));
				$TimbreFiscalDigital->setAttribute('noCertificadoSAT', $response->stampResult->NoCertificadoSAT);
				$TimbreFiscalDigital->setAttribute('selloSAT', $response->stampResult->SatSeal);
				$Complemento->appendChild($TimbreFiscalDigital);
				$this->CFDI = $XMLDoc->saveXML();
				$this->Status = 'Activo';
				$this->dbStore($tipo);
				return $response->stampResult->CodEstatus . ",$trabajador";
			}

		}

		public function send()
		{
			$this->setFromDB();
			//from
			$result = $this->conn->query("SELECT Correo_electronico FROM Empresa WHERE RFC = '{$this->Emisor}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($from) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$from = 'From: ' . $from . "\r\n";
			$result = $this->conn->query("SELECT Nombre, Correo_electronico FROM Trabajador WHERE RFC = '{$this->Receptor}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($to_name, $to) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$subject = 'Recibo de pago';
			$message = wordwrap('Testing mail',70);//$this->CFDI;

			if(mail($to,$subject,$message,$from))
				return "CFDI enviado a $to";
			else
				return "Error al enviar el CFDI a $to_name";

		}

		public function cancel()
		{
			date_default_timezone_set('America/Mexico_City');
			$this->setFromDB();

			if(isset($this->Acuse_cancelacion))
				return "El comprobante ya ha sido cancelado";

			$result = $this->conn->query("SELECT User, Password FROM CFDI_User WHERE Empresa = '{$this->Emisor}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($username, $password) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$CFDINS = 'http://www.sat.gob.mx/cfd/3';
			$NominaNS = 'http://www.sat.gob.mx/nomina';
			$tfdNS = 'http://www.sat.gob.mx/TimbreFiscalDigital';
			$XMLDoc = new DOMDocument('1.0', 'UTF-8');
			$XMLDoc->loadXML($this->CFDI);
			$xpath = new DOMXPath($XMLDoc);
			$xpath->registerNamespace('cfdi', $CFDINS);
			$xpath->registerNamespace('nomina', $NominaNS);
			$xpath->registerNamespace('tfd', $tfdNS);
			$nominaNodes = $xpath->query("//nomina:Nomina");
			$RegistroPatronal = $nominaNodes->item(0)->getAttribute("RegistroPatronal");
			$ReceptorNodes = $xpath->query("//cfdi:Receptor");
			$Receptor = $ReceptorNodes->item(0)->getAttribute("nombre");
			$timbreNodes = $xpath->query("//tfd:TimbreFiscalDigital");
			$UUID = $timbreNodes->item(0)->getAttribute("UUID");
			$fechaTimbrado = new DateTime($timbreNodes->item(0)->getAttribute("FechaTimbrado"));
			$now = new DateTime();
			$interval = $fechaTimbrado->diff(new DateTime($now->format('Y-m-d') . 'T' .  $now->format('H:i:s')));

			if($interval->days < 1)
			{

				if($interval->h < 5)
					return "Debe esperar 5 horas para cancelar el (los) CFDI";

			}

			$result = $this->conn->query("SELECT Empresa, Sucursal, Empresa_sucursal FROM Registro_patronal WHERE Numero = '$RegistroPatronal' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($empresa, $sucursal, $empresa_sucursal) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($sucursal != '')
			{
				$empresa = $empresa_sucursal;
				$result = $this->conn->query("SELECT id FROM Sello_digital WHERE Sucursal = '$sucursal' AND Empresa_sucursal = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			}
			else
				$result = $this->conn->query("SELECT id FROM Sello_digital WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");

			list($id) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$sello_digital = new Sello_digital();
			$sello_digital->set('id', $id);
			$cer_pem = $sello_digital->getCerPem();
			$key_des3 = $sello_digital->getKeyDes3();
			$taxpayer_id = $empresa;//The RFC of the Emisor
			$invoices = array($UUID);//A list of UUIDs
//			$url = "http://demo-facturacion.finkok.com/servicios/soap/cancel.wsdl";
			$url = "https://facturacion.finkok.com/servicios/soap/cancel.wsdl";
			$client = new SoapClient($url);
			$params = array(  
			  "UUIDS" => array('uuids' => $invoices),
			  "username" => $username,
			  "password" => $password,
			  "taxpayer_id" => $taxpayer_id,
			  "cer" => $cer_pem,
			  "key" => $key_des3
			);
			$response = $client->__soapCall("cancel", array($params));

			if($response->cancelResult->Folios->Folio->EstatusUUID == '201')//Cancelación exitosa. Acuse recuperado
			{
				$Acuse = addslashes($response->cancelResult->Acuse);
				$this->conn->query("UPDATE CFDI_Trabajador SET Acuse_cancelacion = '$Acuse' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
				$this->conn->query("UPDATE CFDI_Trabajador set Status = 'Cancelado' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
				$nominaNodes = $xpath->query("//nomina:Nomina");
				$TipoRegimen = $nominaNodes->item(0)->getAttribute("TipoRegimen");

				if($TipoRegimen == '1')
					$this->conn->query("UPDATE nomina_asimilables set Status = 'Cancelado' WHERE Nomina = '{$this->Nomina}' AND Trabajador = '{$this->Receptor}' AND Cuenta = '{$_SESSION['cuenta']}'");
				elseif($TipoRegimen == '2')
					$this->conn->query("UPDATE nomina_asalariados set Status = 'Cancelado' WHERE Nomina = '{$this->Nomina}' AND Trabajador = '{$this->Receptor}' AND Cuenta = '{$_SESSION['cuenta']}'");

				return "Cancelación exitosa";
			}
			elseif($response->cancelResult->Folios->Folio->EstatusUUID == '202')//CFDI Previamente cancelado
			{
				$this->conn->query("UPDATE CFDI_Trabajador set Status = 'Cancelado' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
				$nominaNodes = $xpath->query("//nomina:Nomina");
				$TipoRegimen = $nominaNodes->item(0)->getAttribute("TipoRegimen");

				if($TipoRegimen == '1')
					$this->conn->query("UPDATE nomina_asimilables set Status = 'Cancelado' WHERE Nomina = '{$this->Nomina}' AND Trabajador = '{$this->Receptor}' AND Cuenta = '{$_SESSION['cuenta']}'");
				elseif($TipoRegimen == '2')
					$this->conn->query("UPDATE nomina_asalariados set Status = 'Cancelado' WHERE Nomina = '{$this->Nomina}' AND Trabajador = '{$this->Receptor}' AND Cuenta = '{$_SESSION['cuenta']}'");

				return "Cancelación exitosa";
			}
			else
			{

				if(isset($response->cancelResult->Folios->Folio->EstatusUUID))
					return "Error {$response->cancelResult->Folios->Folio->EstatusUUID} en $Receptor";
				elseif($response->cancelResult->CodEstatus == '302')
					return "Error {$response->cancelResult->CodEstatus} en $Receptor Por favor intente denuevo";
				else
					return "Error {$response->cancelResult->CodEstatus} en $Receptor";

			}

		}

		public function draw($act)//draws $this CFDI_Trabajador this form is not submitted
		{
			echo "<div class = \"datos_tab\">Datos</div>";

			echo '<form class = "show_form">';
				echo "<fieldset class = \"Datos_fieldset\">";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>{$this->id}</textarea>";
					echo "<label class = \"fecha_label\">Fecha</label>";
					echo "<textarea class = \"fecha_textarea\" name = \"Fecha\" title = \"Fecha\" readonly=true>{$this->Fecha}</textarea>";
					echo "<label class = \"view_xml_label\">Ver XML</label>";
					echo "<img class = 'view_button' onclick = \"view_xml_cfdi(this)\" />";//function view_xml at cfdi.js
					echo "<label class = \"emisor_label\">Emisor</label>";
					$result = $this->conn->query("SELECT Nombre FROM Empresa WHERE RFC = '{$this->Emisor}' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($nombre_emisor) = $this->conn->fetchRow($result);
					$this->conn->freeResult($result);
					echo "<textarea class = \"emisor_textarea\" name = \"Emisor\" title = \"Emisor\" readonly=true>$nombre_emisor</textarea>";
					echo "<label class = \"view_print_label\">Ver representación impresa</label>";
					echo "<img class = 'view_button' onclick = \"view_print_cfdi(this)\" />";//function view_print at cfdi.js
					echo "<label class = \"receptor_label\">Receptor</label>";
					$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->Receptor}' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($nombre_receptor) = $this->conn->fetchRow($result);
					$this->conn->freeResult($result);
					echo "<textarea class = \"receptor_textarea\" name = \"Receptor\" title = \"Receptor\" readonly=true>$nombre_receptor</textarea>";
					echo "<label class = \"send_label\">Enviar</label>";
					echo "<img class = 'send_button' onclick = \"send_cfdi(this)\" />";//function send_cfdi at cfdi.js
					echo "<label class = \"cancel_label\">Cancelar</label>";
					echo "<img class = 'cancel_button' onclick = \"cancel_cfdi(this)\" />";//function cancel_cfdi at cfdi.js
					echo "<label class = \"tipo_label\">Tipo</label>";
					echo "<textarea class = \"tipo_textarea\" name = \"Tipo\" title = \"Tipo\" readonly=true>{$this->Tipo}</textarea>";
					echo "<label class = \"view_cancel_label\">Ver acuse de cancelación</label>";
					echo "<img class = 'view_cancel_button' onclick = \"view_cancel_cfdi(this)\" />";//function view_cancel_cfdi at cfdi.js
					echo "<label class = \"status_label\">Status</label>";
					echo "<textarea class = \"status_textarea\" name = \"Status\" title = \"Status\" readonly=true>{$this->Status}</textarea>";
				echo "</fieldset>";
			echo "</form>";

		}

	}

?>
