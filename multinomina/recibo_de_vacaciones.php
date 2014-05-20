<?php
	date_default_timezone_set('America/Mexico_City');
	include_once('cfdi_trabajador.php');
	include_once('connection.php');

//Class Recibo_de_vacaciones definition

	class Recibo_de_vacaciones
	{
		//class properties
		//data
		private $id;
		private $Trabajador;
		private $Fecha;
		private $Anos_de_antiguedad;
		private $Anos;
		private $Servicio;
		private $Dias_de_vacaciones;
		private $Salario_diario;
		private $Vacaciones;
		private $Prima_vacacional;
		private $Compensacion;
		private	$Total_de_percepciones;
		private	$ISR;
		private $Saldo;
		private $Pago_neto;
		private $Vacaciones_retenidas;
		private $Prima_vacacional_retenida;
		private $Diferencia;
		private $Honorarios;
		private $Subtotal_a_facturar;
		private $iva;
		private $Total_a_facturar;
		private $Metodo_de_pago_trabajador = 'DEPOSITO';//will be overwritten
		private $Status;
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

		private function setID()
		{
			$result = $this->conn->query("SELECT id FROM Recibo_de_vacaciones WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY id DESC LIMIT 1");
			$row = $this->conn->fetchRow($result);

			if(isset($row))
			{
				list($this->id) = $row;
				$this->id ++;
			}
			else
				$this->id = 1;
		}

		public function get($name)//gets property named $name
		{
			return $this->$name;
		}

		private function get_empresa()
		{
			$result = $this->conn->query("SELECT Empresa FROM Servicio_Empresa WHERE Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}', Fecha_de_asignacion) >=0 ORDER BY Fecha_de_asignacion DESC LIMIT 1");
			list($empresa) = $this->conn->fetchRow($result);
			return $empresa;
		}

		public function showProperties()//prints properties values
		{

			foreach($this as $key => $value)

				if($key != 'conn')
					echo "$key = $value <br />";

		}

		public function setFromBrowser()//sets properties from superglobal $_POST
		{

			foreach($this as $key => $value)

				if(isset($_POST["$key"]))
				{

					if($key == 'Servicio')
					{
						$data = explode('/',$_POST["Servicio"]);
						$this->Servicio = trim($data[0]);
					}
					else
						$this->$key = trim($_POST["$key"]);

				}

		}

		public function setFromDb()//sets properties from data base, but $id has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Recibo_de_vacaciones WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			$row = $this->conn->fetchRow($result,'ASSOC');

			foreach($row as $key => $value)

				if($key != 'id' && $key != 'Cuenta')
					$this->$key = $value;

			$this->conn->freeResult($result);
		}

		public function dbDelete()//delete this entity from database but id has to be set before
		{

			if(isset($this->id))
				$this->conn->query("DELETE FROM Recibo_de_vacaciones WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. If $update is true it updates all database registers (professedly one) with $this' id
		{

			if(isset($this->id))
			{

				if($update == 'false')
				{
					$this->setID();
					$this->conn->query("INSERT INTO Recibo_de_vacaciones(id, Cuenta) VALUES({$this->id}, '{$_SESSION['cuenta']}')");
				}

				$this->calculate_recibo_de_vacaciones();

				foreach($this as $key => $value)

					if(isset($this->$key) && $key != 'conn' && $key != 'id')
						$this->conn->query("UPDATE Recibo_de_vacaciones SET $key = '$value' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

				return true;
			}

		}

		public function calculate_recibo_de_vacaciones()
		{
			date_default_timezone_set('America/Mexico_City');
			//Días previos al ingreso
			$dias_previos_al_ingreso = '';
			//Días de baja
			$dias_de_baja = '';
			//Años de antigüedad
			$this->calculate_anos_de_antiguedad();
			//Días de vacaciones
			$this->calculate_dias_de_vacaciones($dias_previos_al_ingreso, $dias_de_baja);
			//Salario diario
			$result = $this->conn->query("SELECT Base FROM Base WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}', Fecha) >= 0 LIMIT 1");
			list($base) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$this->calculate_salario_diario($base);
			//Vacaciones
			$this->calculate_vacaciones();
			//Prima vacacional
			$this->calculate_prima_vacacional();
			//Compensación
			$this->calculate_compensacion();
			//Total de percepciones
			$this->calculate_total_de_percepciones();
			//base ISR
			$prima_vacacional = 0;
			$data = explode(',',$this->Prima_vacacional);
			$len = count($data);

			for($i=0; $i<$len; $i++)
				$prima_vacacional += $data[$i];

			$salario_minimo = $this->calculate_salario_minimo_trabajador($this->Trabajador);
			$base_ISR = $this->Compensacion + ($prima_vacacional > (15 * $salario_minimo) ? ($prima_vacacional - 15 * $salario_minimo) : 0);
			//Límite inferior
			$result = $this->conn->query("SELECT Periodicidad_de_la_nomina FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($periodicidad_de_la_nomina) = $this->conn->fetchRow($result);

			if($periodicidad_de_la_nomina == 'Semanal')
				$numero_de_dias_del_periodo = 7;
			elseif($periodicidad_de_la_nomina == 'Quincenal')
				$numero_de_dias_del_periodo = 15;
			else
				$numero_de_dias_del_periodo = 30;

			$limite_inferior = $this->calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
			//Exedente del límite inferior
			$exedente_del_limite_inferior = $base_ISR - $limite_inferior;
			//Porcentaje sobre el exedente del límite inferior
			$porcentaje_sobre_el_exedente_del_limite_inferior = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
			//Impuesto marginal
			$impuesto_marginal = $exedente_del_limite_inferior * $porcentaje_sobre_el_exedente_del_limite_inferior;
			//Cuota fija
			$cuota_fija = $this->calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo);
			//Impuesto determinado
			$impuesto_determinado = $impuesto_marginal + $cuota_fija;
			//Subsidio
			$subsidio = $this->calculate_subsidio($base_ISR,$numero_de_dias_del_periodo);
			//ISR
			$this->ISR = $impuesto_determinado > $subsidio ? ($impuesto_determinado - $subsidio) : 0.00;
			//Saldo
			$this->Saldo = $this->Total_de_percepciones - $this->ISR;

			if($this->Saldo < $this->Pago_neto)
			{
				$this->Compensacion = $this->compensate_compensacion($numero_de_dias_del_periodo);
				//Total de percepciones
				$this->calculate_total_de_percepciones();
				$salario_minimo = $this->calculate_salario_minimo_trabajador($this->Trabajador);
				$base_ISR = $this->Compensacion + ($prima_vacacional > (15 * $salario_minimo) ? ($prima_vacacional - 15 * $salario_minimo) : 0);
				//Límite inferior
				$result = $this->conn->query("SELECT Periodicidad_de_la_nomina FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($periodicidad_de_la_nomina) = $this->conn->fetchRow($result);
				$numero_de_dias_del_periodo = $periodicidad_de_la_nomina == 'Semanal' ? 7 : 15;
				$limite_inferior = $this->calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
				//Exedente del límite inferior
				$exedente_del_limite_inferior = $base_ISR - $limite_inferior;
				//Porcentaje sobre el exedente del límite inferior
				$porcentaje_sobre_el_exedente_del_limite_inferior = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
				//Impuesto marginal
				$impuesto_marginal = $exedente_del_limite_inferior * $porcentaje_sobre_el_exedente_del_limite_inferior;
				//Cuota fija
				$cuota_fija = $this->calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo);
				//Impuesto determinado
				$impuesto_determinado = $impuesto_marginal + $cuota_fija;
				//Subsidio
				$subsidio = $this->calculate_subsidio($base_ISR,$numero_de_dias_del_periodo);
				//ISR
				$this->ISR = $impuesto_determinado > $subsidio ? ($impuesto_determinado - $subsidio) : 0.00;
				//Saldo
				$this->Saldo = $this->Total_de_percepciones - $this->ISR;
			}

			$result = $this->conn->query("SELECT Cobrar_IVA FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($calcular_iva) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$result = $this->conn->query("SELECT Porcentaje_de_honorarios FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($honorarios) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			//Vacaciones retenidas
			$this->calculate_vacaciones_retenidas();
			//Prima vacacional retenida
			$this->calculate_prima_vacacional_retenida();
			//Diferencia
			$vacaciones_retenidas = explode(',',$this->Vacaciones_retenidas);
			$prima_vacacional_retenida = explode(',',$this->Prima_vacacional_retenida);
			$len = count($vacaciones_retenidas);
			$total_retenido = 0.00;

			for($i=0; $i<$len; $i++)
				$total_retenido += $vacaciones_retenidas[$i] + $prima_vacacional_retenida[$i];

			$this->Diferencia = $this->Saldo - $total_retenido;
			$result = $this->conn->query("SELECT Incluir_contribuciones FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($incluir_contribuciones) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			//Honorarios
			$this->calculate_honorarios($incluir_contribuciones,$honorarios);
			//Subtotal a facturar
			$this->Subtotal_a_facturar = $this->Diferencia + $this->ISR + $this->Honorarios;
			//iva
			$this->iva = $this->Subtotal_a_facturar * ($calcular_iva == 'true' ? 0.16 : 0);
			$this->iva = number_format($this->iva,2,'.','');
			//Total a facturar
			$this->Total_a_facturar = $this->Subtotal_a_facturar + $this->iva;
			//Status
			$this->Status = 'Sin timbrar';
		}

		private function calculate_anos_de_antiguedad()
		{
			$ingreso = $this->calculate_ingreso();

			if(isset($ingreso))
			{
				$interval = date_diff(date_create($this->Fecha),date_create($ingreso));
				$dias_de_antiguedad = $interval->days;
				$this->Anos_de_antiguedad = floor(($dias_de_antiguedad) / 365);
			}
			else
				$this->Anos_de_antiguedad = 0;

		}

		private function calculate_ingreso()
		{
			$ingreso = Null;
			$result = $this->conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND Fecha_de_reingreso != '0000-00-00' AND DATEDIFF(Fecha_de_reingreso, '{$this->Fecha}') <= 0 AND DATEDIFF(Fecha_de_reingreso, Fecha_de_baja) > 1 ORDER BY Fecha_de_reingreso DESC LIMIT 1");
			list($ingreso) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if(!isset($ingreso))
			{
				$result = $this->conn->query("SELECT Antiguedad_prestaciones FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($antiguedad) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);

				if($antiguedad == 'Servicio')
					$result = $this->conn->query("SELECT Fecha_de_ingreso_servicio FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
				else
					$result = $this->conn->query("SELECT Fecha_de_ingreso_cliente FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");

				list($ingreso) = $this->conn->fetchRow($result);
			}

			return $ingreso;
		}

		private function calculate_dias_de_vacaciones($dias_previos_al_ingreso, $dias_de_baja)
		{
			$anos = explode(',',$this->Anos);
			$previos = explode(',',$dias_previos_al_ingreso);
			$bajas = explode(',',$dias_de_baja);
			$len = count($anos);
			$this->Dias_de_vacaciones = '';

			for($i=0; $i<$len; $i++)
			{
				//Años de antigüedad
				$result = $this->conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND Fecha_de_reingreso != '0000-00-00' AND DATEDIFF(Fecha_de_reingreso, '{$this->Fecha}') <= 0 AND YEAR(Fecha_de_reingreso) <= '{$anos[$i]}' AND (Fecha_de_reingreso = '0000-00-00' OR DATEDIFF(Fecha_de_reingreso, Fecha_de_baja) > 1) ORDER BY Fecha_de_reingreso DESC LIMIT 1");
				list($ingreso) = $this->conn->fetchRow($result);

				if(!isset($ingreso))
					$ingreso = $this->calculate_ingreso();

				if(isset($ingreso))
				{
					$interval = date_diff(date_create($this->Fecha),date_create($ingreso));
					$dias_de_antiguedad = $interval->days;
					$anos_de_antiguedad = floor(($dias_de_antiguedad) / 365);
				}
				else
					$anos_de_antiguedad = 0;

				//Dias de vacaciones
				if($anos_de_antiguedad < 5)
					$dias_de_vacaciones = ($anos_de_antiguedad * 2 + 4) * (365 - $previos[$i] - $bajas[$i]) / 365;
				else
					$dias_de_vacaciones = (floor($anos_de_antiguedad / 5) * 2 + 12) * (365 - $previos[$i] - $bajas[$i]) / 365;

				$this->Dias_de_vacaciones .= number_format($dias_de_vacaciones, 2, '.', '') . ($i < ($len - 1) ? ',' : '');
			}

		}

		public function calculate_salario_diario($base)
		{
			date_default_timezone_set('America/Mexico_City');
			$fecha = date_create($this->Fecha);
			$year = date('Y', $fecha->format('U'));

			if($base == 'Salario mínimo')
			{
				//cheching if this "Empresa" has any "Sucursal"
				$empresa = $this->get_empresa();
				$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
				if($this->conn->num_rows($result) > 0)
				{
					$this->conn->freeResult($result);
					$result = $this->conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '{$this->Trabajador}' AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}', Trabajador_Sucursal.Fecha_de_ingreso) >= 0 ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");
				}
				else
				{
					$this->conn->freeResult($result);
					$result = $this->conn->query("SELECT Zona_geografica FROM Empresa WHERE RFC = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
				}

				list($zona_geografica) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Salario_minimo.$zona_geografica FROM Trabajador_Salario_minimo LEFT JOIN Salario_minimo ON Trabajador_Salario_minimo.Salario_minimo = Salario_minimo.Codigo WHERE Trabajador_Salario_minimo.Trabajador = '{$this->Trabajador}' AND Trabajador_Salario_minimo.Servicio = '{$this->Servicio}' AND Trabajador_Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Ano = '$year' AND DATEDIFF('{$this->Fecha}',Trabajador_Salario_minimo.Fecha) > 0 ORDER BY Trabajador_Salario_minimo.Fecha DESC LIMIT 1");
				list($salario_diario) = $this->conn->fetchRow($result);
			}
			else
			{
				$result = $this->conn->query("SELECT Cantidad FROM Salario_diario WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}', Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
				list($salario_diario) = $this->conn->fetchRow($result);
			}

			if(isset($salario_diario))
				$this->Salario_diario = $salario_diario;
			else
				$this->Salario_diario = 0;

		}

		private function calculate_salario_minimo_trabajador($_trabajador)
		{
			//cheching if this "Empresa" has any "Sucursal"
			$empresa = $this->get_empresa();
			$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");

			if($this->conn->num_rows($result) > 0)
			{
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '$_trabajador' AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}', Trabajador_Sucursal.Fecha_de_ingreso) >= 0 ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");
			}
			else
			{
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Zona_geografica FROM Empresa WHERE RFC = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

			list($zona) = $this->conn->fetchRow($result);
			$data = explode('-',$this->Fecha);
			$year = $data[0];
			$result = $this->conn->query("SELECT $zona FROM Salario_minimo WHERE Codigo = '1' AND Ano = '$year' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($salario_minimo) = $this->conn->fetchRow($result);

			if(isset($salario_minimo))
				return $salario_minimo;
			else
				return 0;

		}

		private function calculate_vacaciones()
		{
			$dias = explode(',',$this->Dias_de_vacaciones);
			$len = count($dias);
			$this->Vacaciones = '';

			for($i=0; $i<$len; $i++)
				$this->Vacaciones .= $dias[$i] * $this->Salario_diario . ($i < ($len - 1) ? ',' : '');

		}

		private function calculate_prima_vacacional()
		{
			$vacaciones = explode(',',$this->Vacaciones);
			$len = count($vacaciones);
			$this->Prima_vacacional = '';

			for($i=0; $i<$len; $i++)
				$this->Prima_vacacional .= $vacaciones[$i] * 0.25 . ($i < ($len - 1) ? ',' : '');

		}

		private function calculate_compensacion()
		{
			//getting "base para el cálculo de la nómina"
			$result = $this->conn->query("SELECT Base FROM Base WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}', Fecha) >= 0 LIMIT 1");
			list($base_nomina) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			//getting "base para el cálculo de las prestaciones"
			$result = $this->conn->query("SELECT Base_de_prestaciones FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($base_prestaciones) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($base_prestaciones == 'Salario diario' && $base_nomina == 'Salario mínimo')
			{
				//Vacaciones with "Salario diario"
				$result = $this->conn->query("SELECT Cantidad FROM Salario_diario WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}', Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
				list($salario_diario) = $this->conn->fetchRow($result);
				$dias = explode(',',$this->Dias_de_vacaciones);
				$len = count($dias);
				$vacaciones = '';

				for($i=0; $i<$len; $i++)
					$vacaciones .= $dias[$i] * $salario_diario . ($i < ($len - 1) ? ',' : '');

				//Prima vacacional
				$_vacaciones_sd = explode(',',$vacaciones);
				$len = count($_vacaciones_sd);
				$prima_vacacional = '';

				for($i=0; $i<$len; $i++)
					$prima_vacacional .= $_vacaciones_sd[$i] * 0.25 . ($i < ($len - 1) ? ',' : '');

				$_prima_vacacional_sd = explode(',',$prima_vacacional);
				$_vacaciones_sm = explode(',',$this->Vacaciones);
				$_prima_vacacional_sm = explode(',',$this->Prima_vacacional);
				$total_vacaciones_sd = 0;
				$total_prima_sd = 0;
				$total_vacaciones_sm = 0;
				$total_prima_sm = 0;

				for($i=0; $i<$len; $i++)
				{
					$total_vacaciones_sd += $_vacaciones_sd[$i];
					$total_prima_sd += $_prima_vacacional_sd[$i];
					$total_vacaciones_sm += $_vacaciones_sm[$i];
					$total_prima_sm += $_prima_vacacional_sm[$i];
				}

				$diff = $total_vacaciones_sd + $total_prima_sd - $total_vacaciones_sm - $total_prima_sm;
				$this->Compensacion += $diff;
			}

		}

		private function calculate_total_de_percepciones()
		{
			$vacaciones = explode(',',$this->Vacaciones);
			$prima_vacacional = explode(',',$this->Prima_vacacional);
			$len = count($vacaciones);
			$this->Total_de_percepciones = 0.00;

			for($i=0; $i<$len; $i++)
			{
				$this->Total_de_percepciones += $vacaciones[$i] + $prima_vacacional[$i];
			}

			$this->Total_de_percepciones += $this->Compensacion;
		}

		private function calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo)
		{

			if($numero_de_dias_del_periodo == 7)
				$result = $this->conn->query("SELECT Limite_inferior FROM ISR_semanal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL)");
			else
				$result = $this->conn->query("SELECT Limite_inferior FROM ISR_quincenal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL)");

			list($limite_inferior) = $this->conn->fetchRow($result);

			if(isset($limite_inferior))
				return $limite_inferior;

			else
				return 0;

		}

		private function calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo)
		{

			if($numero_de_dias_del_periodo == 7)
				$result = $this->conn->query("SELECT Porcentaje FROM ISR_semanal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL)");
			else
				$result = $this->conn->query("SELECT Porcentaje FROM ISR_quincenal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL)");

			list($porcentaje_sobre_el_exedente_del_limite_inferior) = $this->conn->fetchRow($result);

			if(isset($porcentaje_sobre_el_exedente_del_limite_inferior))
				return $porcentaje_sobre_el_exedente_del_limite_inferior;

			else
				return 0;

		}

		private function calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo)
		{

			if($numero_de_dias_del_periodo == 7)
				$result = $this->conn->query("SELECT Cuota_fija FROM ISR_semanal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL)");
			else
				$result = $this->conn->query("SELECT Cuota_fija FROM ISR_quincenal WHERE Limite_inferior <= $base_ISR AND (Limite_superior >= $base_ISR OR Limite_superior IS NULL)");

			list($cuota_fija) = $this->conn->fetchRow($result);

			if(isset($cuota_fija))
				return $cuota_fija;

			else
				return 0;

		}

		private function calculate_subsidio($base_ISR,$numero_de_dias_del_periodo)
		{

			if($numero_de_dias_del_periodo == 7)
				$result = $this->conn->query("SELECT Subsidio FROM Credito_al_salario_semanal WHERE Desde_ingresos_de <= $base_ISR AND (Hasta_ingresos_de >= $base_ISR OR Hasta_ingresos_de IS NULL)");
			else
				$result = $this->conn->query("SELECT Subsidio FROM Credito_al_salario_quincenal WHERE Desde_ingresos_de <= $base_ISR AND (Hasta_ingresos_de >= $base_ISR OR Hasta_ingresos_de IS NULL)");

			list($subsidio) = $this->conn->fetchRow($result);

			if(isset($subsidio))
				return $subsidio;

			else
				return 0;

		}

		public function calculate_vacaciones_retenidas()
		{
			$result = $this->conn->query("SELECT Vacaciones FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($vacaciones) = $this->conn->fetchRow($result);
			date_default_timezone_set('America/Mexico_City');
			$anos = explode(',',$this->Anos);
			$len = count($anos);
			$this->Vacaciones_retenidas = '';
			$ingreso = $this->calculate_ingreso();

			for($i=0; $i<$len; $i++)
			{
				$li = ($anos[$i] - 1) . substr($ingreso,4);
				$ls = $anos[$i] . substr($ingreso,4);
				$result = $this->conn->query("SELECT Retencion_proporcional_de_vacaciones FROM prestaciones LEFT JOIN Nomina ON prestaciones.Nomina = Nomina.id WHERE Nomina.Servicio = '{$this->Servicio}' AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$li') >= 0 AND DATEDIFF('$ls',Nomina.Limite_superior_del_periodo) >= 0 AND prestaciones.Trabajador = '{$this->Trabajador}' AND prestaciones.Cuenta = '{$_SESSION['cuenta']}' AND Nomina.Cuenta = '{$_SESSION['cuenta']}'");
				$vacaciones_retenidas = 0.00;

				if($vacaciones == 'Cobrado')

					while(list($cantidad) = $this->conn->fetchRow($result))
						$vacaciones_retenidas += $cantidad;

				$this->Vacaciones_retenidas .= number_format($vacaciones_retenidas, 2, '.', '') . ($i < ($len - 1) ? ',' : '');
			}

		}

		private function calculate_prima_vacacional_retenida()
		{
			$result = $this->conn->query("SELECT Prima_vacacional FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($prima_vacacional) = $this->conn->fetchRow($result);
			date_default_timezone_set('America/Mexico_City');
			$anos = explode(',',$this->Anos);
			$len = count($anos);
			$this->Prima_vacacional_retenida = '';
			$ingreso = $this->calculate_ingreso();

			for($i=0; $i<$len; $i++)
			{
				$li = ($anos[$i] - 1) . substr($ingreso,4);
				$ls = $anos[$i] . substr($ingreso,4);
				$result = $this->conn->query("SELECT Retencion_proporcional_de_prima_vacacional FROM prestaciones LEFT JOIN Nomina ON prestaciones.Nomina = Nomina.id WHERE Nomina.Servicio = '{$this->Servicio}' AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$li') >= 0 AND DATEDIFF('$ls',Nomina.Limite_superior_del_periodo) >= 0 AND prestaciones.Trabajador = '{$this->Trabajador}' AND prestaciones.Cuenta = '{$_SESSION['cuenta']}' AND Nomina.Cuenta = '{$_SESSION['cuenta']}'");
				$prima_vacacional_retenida = 0.00;

				if($prima_vacacional == 'Cobrado')

					while(list($cantidad) = $this->conn->fetchRow($result))
						$prima_vacacional_retenida += $cantidad;

				$this->Prima_vacacional_retenida .= number_format($prima_vacacional_retenida, 2, '.', '') . ($i < ($len - 1) ? ',' : '');
			}

		}

		private function calculate_honorarios($incluir_contribuciones,$honorarios)
		{

			if($incluir_contribuciones = 'true')
				$this->Honorarios = number_format(($this->ISR + $this->Diferencia) * $honorarios / 100,2,'.','');
			else
				$this->Honorarios = number_format($this->Diferencia * $honorarios / 100,2,'.','');

		}

		private function compensate_compensacion($numero_de_dias_del_periodo)
		{
			$_vacaciones = explode(',', $this->Vacaciones);
			$len = count($_vacaciones);
			$vacaciones = 0;

			for($i=0; $i<$len; $i++)
				$vacaciones += $_vacaciones[$i];

			$_prima_vacacional = explode(',', $this->Prima_vacacional);
			$len = count($_prima_vacacional);
			$prima_vacacional = 0;

			for($i=0; $i<$len; $i++)
				$prima_vacacional += $_prima_vacacional[$i];

			$compensacion = $this->Pago_neto - $vacaciones - $prima_vacacional;
			$dif_compensacion = $this->dif_compensacion($numero_de_dias_del_periodo,$vacaciones,$prima_vacacional,$compensacion);

			if($dif_compensacion > 0)
			{
				$a = $compensacion;
				$i = 0;

				while($i < 300)
				{
					$compensacion += 100;
					$dif_compensacion = $this->dif_compensacion($numero_de_dias_del_periodo,$vacaciones,$prima_vacacional,$compensacion);

					if($dif_compensacion < 0)//Bisection method
					{
						$b = $compensacion;
						$j = 0;

						while($j < 500)
						{
							$m = ($a + $b) / 2;

							if($this->dif_compensacion($numero_de_dias_del_periodo,$vacaciones,$prima_vacacional,$m) == 0 || ($b - $a) / 2 <0.001)
							{
								$compensacion = $m;
								break;
							}
							else
							{

								if($this->dif_compensacion($numero_de_dias_del_periodo,$vacaciones,$prima_vacacional,$a) * $this->dif_compensacion($numero_de_dias_del_periodo,$vacaciones,$prima_vacacional,$m) < 0)
									$b = $m;
								else
									$a = $m;

							}

							$j++;
						}

						break;
					}

					$i++;
				}

			}

			return $compensacion;
		}

		private function dif_compensacion($numero_de_dias_del_periodo,$vacaciones,$prima_vacacional,$compensacion)
		{
			$salario_minimo = $this->calculate_salario_minimo_trabajador($this->Trabajador);
			$base_ISR = $compensacion + ($prima_vacacional > (15 * $salario_minimo) ? ($prima_vacacional - 15 * $salario_minimo) : 0);
			//Límite inferior
			$limite_inferior = $this->calculate_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
			//Exedente del límite inferior
			$exedente_del_limite_inferior = $base_ISR - $limite_inferior;
			//Porcentaje sobre el exedente del límite inferior
			$porcentaje_sobre_el_exedente_del_limite_inferior = $this->calculate_porcentaje_sobre_el_exedente_del_limite_inferior($base_ISR,$numero_de_dias_del_periodo);
			//Impuesto marginal
			$impuesto_marginal = $exedente_del_limite_inferior * $porcentaje_sobre_el_exedente_del_limite_inferior;
			//Cuota fija
			$cuota_fija = $this->calculate_cuota_fija($base_ISR,$numero_de_dias_del_periodo);
			//Impuesto determinado
			$impuesto_determinado = $impuesto_marginal + $cuota_fija;
			//Subsidio
			$subsidio = $this->calculate_subsidio($base_ISR,$numero_de_dias_del_periodo);
			//ISR and Subsidio al empleo
			if($impuesto_determinado > $subsidio)
			{
				$ISR = $impuesto_determinado - $subsidio;
				$subsidio_al_empleo = 0;
			}
			else
			{
				$subsidio_al_empleo = $subsidio - $impuesto_determinado;
				$ISR = 0;
			}

			$dif = $this->Pago_neto + $ISR - $compensacion - $vacaciones - $prima_vacacional;
			return $dif;
		}

		public function draw($act)//if act == 'EDIT' or act == 'ADD' some fields can be edited and the form is submitted. If act == 'DRAW' no fields can be edited and the form is not submitted
		{
			echo "<div class = 'datos_tab'>Datos</div>";
			echo '<form>';
				echo "<fieldset class = 'Datos_fieldset' style = 'visibility:visible'\>";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>{$this->id}</textarea>";
					echo "<label class = 'trabajador_label'>Trabajador</label>";

					if(isset($this->Trabajador))
					{
						$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($nombre) = $this->conn->fetchRow($result);
					}
					else
						$nombre = '';

					echo "<textarea class = 'trabajador_textarea' name = 'Nombre' " . ($act == 'DRAW' ? "readonly = true" : "onkeyup = \"_autocomplete(this, 'Trabajador', 'Nombre')\" ") . "title = 'Trabajador'>$nombre</textarea>";
					echo "<label class = 'trabajador_rfc_label'>RFC (Trabajador)</label>";

					if($act == 'DRAW')
						echo "<textarea class = 'trabajador_rfc_textarea' name = 'Trabajador' title = 'RFC del trabajador'>{$this->Trabajador}</textarea>";
					else
						echo "<select class = 'trabajador_rfc_select' name = 'Trabajador' title = 'RFC del trabajador'>" . (isset($this->Trabajador) ? "<option>{$this->Trabajador}</option>" : "") . "</select>";

					echo "<label class = 'servicio_label'>Servicio</label>";

					if(isset($this->Servicio))
					{
						$result = $this->conn->query("SELECT Servicio.id, Servicio.Periodicidad_de_la_nomina, Empresa.Nombre FROM Servicio LEFT JOIN Servicio_Empresa ON Servicio.id = Servicio_Empresa.Servicio LEFT JOIN Empresa ON Servicio_Empresa.Empresa = Empresa.RFC WHERE Servicio.id = '{$this->Servicio}' AND Servicio.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}', Servicio_Empresa.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Empresa.Fecha_de_asignacion DESC LIMIT 1");
						list($id,$periodicidad,$empresa) = $this->conn->fetchRow($result);
					}

					if($act == 'DRAW')
						echo "<textarea class = 'servicio_textarea' name = 'Servicio' title = 'Servicio'>$id/$periodicidad/$empresa</textarea>";
					else
						echo "<select class = 'servicio_select' name = 'Servicio' title = 'Servicio'>" . (isset($this->Servicio) ? "<option>$id/$periodicidad/$empresa</option>" : "") . "</select>";

					echo "<label class = 'anos_label'>Años</label>";

					if(!isset($this->Anos))
						$this->Anos = '(Por ejemplo: ' . date('Y') . ',' . (date('Y') - 1) . ')';

					echo "<textarea class = 'anos_textarea' name = 'Anos' title = 'Años'>{$this->Anos}</textarea>";
					echo "<label class = 'compensacion_label'>Compensación</label>";
					echo "<textarea class = 'compensacion_textarea' name = 'Compensacion' title = 'Compensación'  " . ($act == 'DRAW' ? "readonly=true" : "") . ">{$this->Compensacion}</textarea>";
					echo "<label class = 'pago_neto_label'>Pago neto</label>";
					echo "<textarea class = 'pago_neto_textarea' name = 'Pago_neto' title = 'Pago neto'  " . ($act == 'DRAW' ? "readonly=true" : "") . ">{$this->Pago_neto}</textarea>";
					echo "<label class = 'fecha_label'>Fecha</label>";
					echo "<textarea class = 'fecha_textarea' name = 'Fecha' title = 'Fecha'  " . ($act == 'DRAW' ? "readonly=true" : "") . ">{$this->Fecha}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";

					if($act == 'DRAW')
					{
						echo "<label class = 'ver_recibo_label'>Ver recibo</label>";
						echo "<img class = 'view_button' onclick = \"view_recibo_de_vacaciones(this)\"/>";//function view_recibo_de_vacaciones at recibo_de_vacaciones.js
					}

					echo "<label class = 'metodo_de_pago_trabajador_label'>Método de pago al trabajador</label>";
					echo "<textarea class = 'metodo_de_pago_trabajador_textarea' name = 'Metodo_de_pago_trabajador' title = 'Método de pago al trabajador'  " . ($act == 'DRAW' ? "readonly=true" : "required=true") . ">{$this->Metodo_de_pago_trabajador}</textarea>";

					if($act == 'DRAW')
					{
						echo "<label class = \"timbrar_label\">Timbrar</label>";
						echo "<img class = 'timbrar_button' onclick = \"timbrar_vacaciones(this)\" />";//function timbrar_vacaciones at recibo_de_vacaciones.js
					}

					echo "<label class = 'status_label'>Status</label>";
					echo "<textarea class = 'status_textarea' name = 'Status' title = 'Status' readonly=true>{$this->Status}</textarea>";
				echo "</fieldset>";

				if($act != 'DRAW')
				{
					$onclick = $this->Status != 'Comprobante timbrado satisfactoriamente' ? "_submit('$act',this.parentNode,'Recibo_de_vacaciones')" : "_alert('El recibo esta timbrado y no puede ser editado')";
					echo "<img class = 'submit_button' onclick = \"$onclick\" />";
				}

			echo "</form>";
		}

		public function timbrar_recibo()
		{
			$this->setFromDB();
			$result = $this->conn->query("SELECT Registro_patronal.Numero, Registro_patronal.Empresa, Registro_patronal.Sucursal, Registro_patronal.Empresa_sucursal, Registro_patronal.Clase_de_riesgo FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero WHERE Servicio_Registro_patronal.Servicio = '{$this->Servicio}' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
			list($registro_patronal_admin,$rfc_empresa,$sucursal_admin,$rfc_empresa_sucursal,$clase_de_riesgo) = $this->conn->fetchRow($result);

			if(isset($rfc_empresa) && $rfc_empresa != '')
				$rfc_admin = $rfc_empresa;
			else
				$rfc_admin = $rfc_empresa_sucursal;

			$this->conn->freeResult($result);
			$FechaPago = $this->Fecha;
			$matches = array();
			$flag = preg_match('/[0-9,A-Z]{1,20}/' , $registro_patronal_admin, $matches);

			if(!isset($registro_patronal_admin) || !$flag || count($matches) != 1)
				return "El registro patronal $registro_patronal_admin debe tener de 1 a 20 caracteres alfanuméricos";

			//testing data
			$timbrar = false;
			$cn = $this->complemento_vacaciones($registro_patronal_admin, $rfc_admin, $sucursal_admin, $clase_de_riesgo, $FechaPago);

			if(isset($cn) && is_array($cn))//cna = NULL means that Saldo is <= 0.00
			{
				$cfdi = new CFDI_Trabajador();
				$msg = $cfdi->CFDINomina($cn, $timbrar, 'Vacaciones');
			}
			elseif(isset($cn) && is_string($cn))
				$msg = $cn;

			if(isset($msg) && is_string($msg))
				return $msg;//error message

			//timbrando
			$timbrar = true;
			$cn = $this->complemento_vacaciones($registro_patronal_admin, $rfc_admin, $sucursal_admin, $clase_de_riesgo, $FechaPago);

			if(isset($cn) && is_array($cn))//cna = NULL means that Saldo is 0.00
			{
				$cfdi = new CFDI_Trabajador();
				$msg = $cfdi->CFDINomina($cn, $timbrar, 'Vacaciones');

				if(isset($msg))//$msg NULL cuando ya ha sido timbrado anteriormente
				{
					$data = explode(',', $msg);
					$status = $data[0];
					$trabajador = $data[1];
					$this->conn->query("UPDATE Recibo_de_vacaciones SET Status = '$status' WHERE id = '{$this->id}' AND Trabajador = '$trabajador' AND Cuenta = '{$_SESSION['cuenta']}'");
				}

			}

		}

		private function complemento_vacaciones($registro_patronal_admin, $rfc_admin, $sucursal_admin, $clase_de_riesgo, $FechaPago)
		{
			date_default_timezone_set('America/Mexico_City');
			//generando complemento Nomina para vacaciones
			$NominaNS = 'http://www.sat.gob.mx/nomina';
			$SchemaInstanceNS = 'http://www.w3.org/2001/XMLSchema-instance';
			$SATSchemaLocation = 'http://www.sat.gob.mx/nomina http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina11.xsd';
			$XMLDoc = new DOMDocument('1.0', 'UTF-8');
			$ComplementoNomina = $XMLDoc->createElementNS($NominaNS, 'nomina:Nomina');
			$ComplementoNomina->setAttributeNS($SchemaInstanceNS, 'xsi:schemaLocation', $SATSchemaLocation);
			$saldo = $this->Saldo;
			$metodoDePago = trim(preg_replace('/\s+/',' ', $this->Metodo_de_pago_trabajador));

			if($saldo <= 0.00)
				return Null;

			$NumEmpleado = 'N/A';
			$result = $this->conn->query("SELECT Numero_IMSS, CURP FROM Trabajador WHERE RFC = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($NumSeguridadSocial, $CURP) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$NumSeguridadSocial = trim(preg_replace('/\s+/',' ', $NumSeguridadSocial));
			$CURP = trim(preg_replace('/\s+/',' ', $CURP));
			$RFC = trim(preg_replace('/\s+/',' ', $this->Trabajador));
			$NumDiasPagados = 0;
			$DiasDeVacaciones = explode(',', $this->Dias_de_vacaciones);

			for($i=0; $i<count($DiasDeVacaciones); $i++)
				$NumDiasPagados += $DiasDeVacaciones[$i];

			$SalarioBaseCotApor = $this->Salario_diario;

			$result = $this->conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '$RFC' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($NombreTrabajador) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			$NombreTrabajador = trim(preg_replace('/\s+/',' ', $NombreTrabajador));
			$matches = array();
			$flag = preg_match('/[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?/' , $RFC, $matches);

			if(!$flag || count($matches) != 1 || strlen($RFC) > 13 || strlen($RFC) < 12)
				return "Por favor corrija el RFC de $NombreTrabajador";

			//Percepciones
			$Percepciones = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepciones');
			$TotalGravado_percepciones = 0.00;
			$TotalExento_percepciones = 0.00;
			//Vacaciones
			$value = 0.00;
			$values = explode(',', $this->Vacaciones);

			for($i=0; $i<count($values); $i++)
				$value += $values[$i];

			$Vacaciones = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
			$Vacaciones->setAttribute('TipoPercepcion', '016');
			$Vacaciones->setAttribute('Clave', '022');
			$Vacaciones->setAttribute('Concepto', 'Vacaciones');
			$Vacaciones->setAttribute('ImporteGravado', '0.00');
			$Vacaciones->setAttribute('ImporteExento', $value);
			$Percepciones->appendChild($Vacaciones);
			$TotalExento_percepciones += $value;
			//Prima vacacional
			$salario_minimo = $this->calculate_salario_minimo_trabajador($this->Trabajador);
			$value = 0.00;
			$gvalue = 0.00;
			$values = explode(',', $this->Prima_vacacional);

			for($i=0; $i<count($values); $i++)
			{
				$value += $values[$i];
				$gvalue += $values[$i] > (15 * $salario_minimo) ? ($values[$i] - 15 * $salario_minimo) : 0;
			}

			$PrimaVacacional = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
			$PrimaVacacional->setAttribute('TipoPercepcion', '021');
			$PrimaVacacional->setAttribute('Clave', '023');
			$PrimaVacacional->setAttribute('Concepto', 'Prima vacacional');
			$PrimaVacacional->setAttribute('ImporteGravado', round($gvalue, 2));
			$PrimaVacacional->setAttribute('ImporteExento', round($value - $gvalue, 2));
			$Percepciones->appendChild($PrimaVacacional);
			$TotalGravado_percepciones += round($gvalue, 2);
			$TotalExento_percepciones += round($value - $gvalue, 2);

			if($this->Compensacion > 0.00)
			{
				$Compensacion = $XMLDoc->createElementNS($NominaNS, 'nomina:Percepcion');
				$Compensacion->setAttribute('TipoPercepcion', '016');
				$Compensacion->setAttribute('Clave', '016');
				$Compensacion->setAttribute('Concepto', 'Compensacion');
				$Compensacion->setAttribute('ImporteGravado', $this->Compensacion);
				$Compensacion->setAttribute('ImporteExento', '0.00');
				$Percepciones->appendChild($Compensacion);
				$TotalGravado_percepciones += $this->Compensacion;
			}

			if($Percepciones->hasChildNodes())
			{
				$ComplementoNomina->appendChild($Percepciones);
				$Percepciones->setAttribute('TotalGravado', $TotalGravado_percepciones);
				$Percepciones->setAttribute('TotalExento', $TotalExento_percepciones);
			}

			//Deducciones
			$Deducciones = $XMLDoc->createElementNS($NominaNS, 'nomina:Deducciones');
			$TotalGravado_deducciones = 0.00;
			$TotalExento_deducciones = 0.00;
			//ISR
			$ISR = $XMLDoc->createElementNS($NominaNS, 'nomina:Deduccion');
			$ISR->setAttribute('TipoDeduccion', '002');
			$ISR->setAttribute('Clave', '023');
			$ISR->setAttribute('Concepto', 'ISR');
			$ISR->setAttribute('ImporteGravado', 0.00);
			$ISR->setAttribute('ImporteExento', $this->ISR);
			$TotalExento_deducciones += $this->ISR;
			$Deducciones->appendChild($ISR);
			$TotalGravado_deducciones = round($TotalGravado_deducciones, 2);
			$TotalExento_deducciones = round($TotalExento_deducciones, 2);

			if($Deducciones->hasChildNodes())
			{
				$ComplementoNomina->appendChild($Deducciones);
				$Deducciones->setAttribute('TotalGravado', $TotalGravado_deducciones);
				$Deducciones->setAttribute('TotalExento', $TotalExento_deducciones);
			}

			//Atributos Complemento Nomina
			if($ComplementoNomina->hasChildNodes())
			{
				$result = $this->conn->query("SELECT Puesto, Tipo, Tipo_de_jornada FROM Contrato WHERE Trabajador = '$RFC' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}',Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
				list($Puesto, $TipoContrato, $TipoJornada) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Fecha_de_ingreso_servicio FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '$RFC' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF(Fecha_de_ingreso_servicio, '{$this->Fecha}') <= 0 ORDER BY Fecha_de_ingreso_servicio DESC LIMIT 1");
				list($FechaInicioRelLaboral) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '$RFC' AND Fecha_de_reingreso != '0000-00-00' AND DATEDIFF(Fecha_de_reingreso, '$FechaInicioRelLaboral') > 0 AND DATEDIFF(Fecha_de_reingreso, '{$this->Fecha}') <= 0 AND (Fecha_de_reingreso = '0000-00-00' OR DATEDIFF(Fecha_de_reingreso,Fecha_de_baja) > 1) AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_reingreso DESC LIMIT 1");
				list($Reingreso) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);

				if(isset($Reingreso))
					$FechaInicioRelLaboral = $Reingreso;

				$PeriodicidadPago = 'Anual';
				$RiesgoPuesto = array('I' => '1', 'II' => '2', 'III' => '3', 'IV' => '4', 'V' => '5');
				$fecha = date_create("{$this->Fecha}");
				$inicio = date_create("$FechaInicioRelLaboral");
				$interval = $inicio->diff($fecha);
				$semanas = floor(($interval->days + 1) / 7);
				//Version
				$ComplementoNomina->setAttribute('Version', 1.1);
				//Registro patronal
				$ComplementoNomina->setAttribute('RegistroPatronal', $registro_patronal_admin);
				//Numero de empleado
				$ComplementoNomina->setAttribute('NumEmpleado', $NumEmpleado);
				//CURP
				$matches = array();
				$flag = preg_match('/[A-Z][A,E,I,O,U,X][A-Z]{2}[0-9]{2}[0-1][0-9][0-3][0-9][M,H][A-Z]{2}[B,C,D,F,G,H,J,K,L,M,N,Ñ,P,Q,R,S,T,V,W,X,Y,Z]{3}[0-9,A-Z][0-9]/' , $CURP, $matches);

				if(isset($CURP) && $flag && count($matches) == 1 && strlen($CURP) == 18)
					$ComplementoNomina->setAttribute('CURP', $CURP);
				else
					return "Por favor corrija la CURP del trabajador $NombreTrabajador";

				//Tipo de régimen
				$ComplementoNomina->setAttribute('TipoRegimen', 2);//Sueldos y Salarios
				//Numero de seguridad social
				$matches = array();
				$flag = preg_match('/\d{1,20}/' , $NumSeguridadSocial, $matches);

				if(isset($NumSeguridadSocial) && $flag && count($matches) == 1)
					$ComplementoNomina->setAttribute('NumSeguridadSocial', $NumSeguridadSocial);
				else
					return "Por favor corrija el número IMSS del trabajador $NombreTrabajador";

				//Fecha de pago
				$ComplementoNomina->setAttribute('FechaPago', $FechaPago);
				//Fecha inicial de pago
				$ComplementoNomina->setAttribute('FechaInicialPago', $FechaPago);
				//Fecha final de pago
				$ComplementoNomina->setAttribute('FechaFinalPago', $FechaPago);
				//Número de días pagados
				$ComplementoNomina->setAttribute('NumDiasPagados', $NumDiasPagados);

				if(isset($FechaInicioRelLaboral) && $FechaInicioRelLaboral != '')
					$ComplementoNomina->setAttribute('FechaInicioRelLaboral', $FechaInicioRelLaboral);
				else
					return "Por favor corrija la fecha de ingreso del trabajador $NombreTrabajador";

				$ComplementoNomina->setAttribute('Antiguedad', $semanas);

				if(isset($Puesto) && $Puesto != '')
					$ComplementoNomina->setAttribute('Puesto', $Puesto);

				if(isset($TipoContrato) && $TipoContrato != '')
					$ComplementoNomina->setAttribute('TipoContrato', $TipoContrato);

				if(isset($TipoJornada) && $TipoJornada != '')
					$ComplementoNomina->setAttribute('TipoJornada', $TipoJornada);

				$ComplementoNomina->setAttribute('PeriodicidadPago', $PeriodicidadPago);

				if(isset($SalarioBaseCotApor) && $SalarioBaseCotApor != '')
					$ComplementoNomina->setAttribute('SalarioBaseCotApor', $SalarioBaseCotApor);

				if(isset($clase_de_riesgo) && isset($RiesgoPuesto[$clase_de_riesgo]))
					$ComplementoNomina->setAttribute('RiesgoPuesto', $RiesgoPuesto[$clase_de_riesgo]);

				$XMLDoc->appendChild($ComplementoNomina);
			}

			return array($XMLDoc->saveXML(), $FechaPago, $metodoDePago, $rfc_admin, $sucursal_admin, $RFC, $this->id);
		}

	}
?>
