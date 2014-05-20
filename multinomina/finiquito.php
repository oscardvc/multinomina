<?php
	include_once('connection.php');
	include_once('servicio.php');

//Class Finiquito definition

	class Finiquito
	{
		//class properties
		//data
		private $id;
		private $Trabajador;
		private $Fecha;
		private $Servicio;
		private $Anos;
		private $Fecha_de_ingreso;
		private $Anos_de_antiguedad;
		private $Dias_de_vacaciones;
		private $Salario_diario;
		private $Vacaciones;
		private $Prima_vacacional;
		private $Dias_de_aguinaldo;
		private $Aguinaldo;
		private $Dias_de_prima_de_antiguedad;
		private $Pagar_prima_de_antiguedad;
		private $Prima_de_antiguedad;
		private $Gratificacion;
		private $Forma_de_pago;
		private	$Total_de_percepciones;
		private	$ISR;
		private $Saldo;
		private $Pago_neto;
		private $Vacaciones_retenidas;
		private $Prima_vacacional_retenida;
		private $Aguinaldo_retenido;
		private $Prima_de_antiguedad_retenida;
		private $Diferencia;
		private $Honorarios;
		private $Subtotal_a_facturar;
		private $iva;
		private $Total_a_facturar;
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
			$result = $this->conn->query("SELECT id FROM Finiquito WHERE Cuenta = '{$_SESSION['cuenta']}' ORDER BY id DESC LIMIT 1");
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
					elseif($key == 'Pagar_prima_de_antiguedad')
						$this->$key = 'true';
					else
						$this->$key = trim($_POST["$key"]);

				}
				elseif($key == 'Pagar_prima_de_antiguedad')
					$this->$key = 'false';

		}

		public function setFromDb()//sets properties from data base, but $id has to be set before
		{
			$result = $this->conn->query("SELECT * FROM Finiquito WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");
			$row = $this->conn->fetchRow($result,'ASSOC');

			foreach($row as $key => $value)

				if($key != 'id' && $key != 'Cuenta')
					$this->$key = $value;

			$this->conn->freeResult($result);
		}

		public function dbDelete()//delete this entity from database but id has to be set before
		{

			if(isset($this->id))
				$this->conn->query("DELETE FROM Finiquito WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

		}

		public function dbStore($update)//if $update is false it stores a new database register with $this' properties. If $update is true it updates all database registers (professedly one) with $this' id
		{

			if(isset($this->id))
			{

				if($update == 'false')
				{
					$this->setID();
					$this->conn->query("INSERT INTO Finiquito(id, Cuenta) VALUES ({$this->id}, '{$_SESSION['cuenta']}')");
				}

				$this->calculate_finiquito();

				foreach($this as $key => $value)

					if(isset($this->$key) && $key != 'conn' && $key != 'id')
						$this->conn->query("UPDATE Finiquito SET $key  = '$value' WHERE id = '{$this->id}' AND Cuenta = '{$_SESSION['cuenta']}'");

				return true;
			}

		}

		public function calculate_finiquito()
		{
			date_default_timezone_set('America/Mexico_City');
			//Años de antigüedad
			$this->calculate_anos_de_antiguedad();
			$date = date_create($this->Fecha);
			$year = date('Y', $date->format('U'));
			$year_minus = $year - 1;
			//setting "Anos" to calculate
			$result = $this->conn->query("SELECT id FROM Recibo_de_vacaciones WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND YEAR(Fecha) = '$year_minus'");
			list($_id) = $this->conn->fetchRow($result);
			$result = $this->conn->query("SELECT id FROM Finiquito WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND YEAR(Fecha) = '$year_minus'");
			list($id_) = $this->conn->fetchRow($result);

			if(!isset($_id) && !isset($id_) && $this->Anos_de_antiguedad > 1)//if there are not "Recibo_de_vacaciones" and "Finiquito" for last year and Anos_de_antiguedad > 1
				$this->Anos = $year_minus . ',' . $year;
			else
				$this->Anos = $year;

			//Días de vacaciones
			$this->calculate_dias_de_vacaciones();
			//Salario diario
			$result = $this->conn->query("SELECT Base FROM Base WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}',Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
			list($base) = $this->conn->fetchRow($result);
			$usar_salario_minimo = ($base == 'Salario diario' ? 'false' : 'true');
			$this->calculate_salario_diario($usar_salario_minimo);
			//Vacaciones
			$this->calculate_vacaciones();
			//Prima vacacional
			$this->calculate_prima_vacacional();
			//Dias previos al ingreso
			$dias_previos_al_ingreso = $this->calculate_dias_previos_al_ingreso();
			//Dias de baja
			$dias_de_baja = $this->calculate_dias_de_baja();
			//Aguinaldo
			$this->calculate_aguinaldo($dias_previos_al_ingreso,$dias_de_baja);
			//Días de prima de antiguedad
			$this->calculate_dias_de_prima_de_antiguedad();
			//Prima de antiguedad
			$this->calculate_prima_de_antiguedad();
			//Gratificación
			$this->calculate_gratificacion();
			//Total de percepciones
			$this->calculate_total_de_percepciones();
			//base ISR
			$prima_vacacional = 0;
			$data = explode(',',$this->Prima_vacacional);
			$len = count($data);

			for($i=0; $i<$len; $i++)
				$prima_vacacional += $data[$i];

			$salario_minimo = $this->calculate_salario_minimo();
			$base_ISR = $this->Gratificacion + ($prima_vacacional > ($salario_minimo * 15) ? ($prima_vacacional - 300) : 0) + ($this->Aguinaldo <= ($salario_minimo * 30) ? 0 : ($this->Aguinaldo - $salario_minimo * 30)) + ($this->Prima_de_antiguedad <= ($salario_minimo * 90) ? 0 : ($this->Prima_de_antiguedad - $salario_minimo * 90));
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

			if($this->Saldo < $this->Pago_neto)
			{
				$this->Gratificacion = $this->compensate_gratificacion($numero_de_dias_del_periodo);
				//Total de percepciones
				$this->calculate_total_de_percepciones();
				$base_ISR = $this->Gratificacion + ($prima_vacacional > ($salario_minimo * 15) ? ($prima_vacacional - 300) : 0) + ($this->Aguinaldo <= ($salario_minimo * 30) ? 0 : ($this->Aguinaldo - $salario_minimo * 30)) + ($this->Prima_de_antiguedad <= ($salario_minimo * 90) ? 0 : ($this->Prima_de_antiguedad - $salario_minimo * 90));
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
			//Aguinaldo retenida
			$this->calculate_aguinaldo_retenido();
			//Prima de antiguedad retenida
			$this->calculate_prima_de_antiguedad_retenida();
			//Diferencia
			$vacaciones_retenidas = explode(',',$this->Vacaciones_retenidas);
			$prima_vacacional_retenida = explode(',',$this->Prima_vacacional_retenida);
			$len = count($vacaciones_retenidas);
			$total_retenido = 0.00;

			for($i=0; $i<$len; $i++)
				$total_retenido += $vacaciones_retenidas[$i] + $prima_vacacional_retenida[$i];

			$this->Diferencia = $this->Saldo - $total_retenido - $this->Aguinaldo_retenido - $this->Prima_de_antiguedad_retenida;
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
		}

		private function calculate_dias_previos_al_ingreso()
		{
			date_default_timezone_set('America/Mexico_City');
			$anos = explode(',',$this->Anos);
			$len = count($anos);
			$txt = '';

			for($i=0; $i<$len; $i++)
			{
				$fecha = date_create($anos[$i]);
				$firstday = date('Y', $fecha->format('U')) . '-01-01';//YYYY-01-01
				$ingreso = $this->calculate_ingreso();

				if(isset($ingreso))
				{
					$interval = date_diff(date_create($firstday),date_create($ingreso));
					$dias_previos_al_ingreso = $interval->days;
				}
				else
					$dias_previos_al_ingreso = 0;

				$txt .= $dias_previos_al_ingreso . ($i < ($len - 1) ? ',' : '');
			}

			return $txt;
		}

		public function calculate_dias_de_baja()
		{
			date_default_timezone_set('America/Mexico_City');
			$anos = explode(',',$this->Anos);
			$len = count($anos);
			$txt = '';

			for($i=0; $i<$len; $i++)
			{
				$fecha = date_create($anos[$i]);
				$numero_de_dias_de_baja = 0;
				$result = $this->conn->query("SELECT Fecha_de_baja, Fecha_de_reingreso FROM Baja WHERE Trabajador = '{$this->Trabajador}' AND YEAR(Fecha_de_baja) = '{$anos[$i]}' AND (Fecha_de_reingreso = '0000-00-00' OR DATEDIFF(Fecha_de_reingreso,Fecha_de_baja) > 1) AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
				$j = 0;
				$dias_de_baja = 0;

				while(list($fecha_de_baja,$fecha_de_reingreso) = $this->conn->fetchRow($result))
				{
					$baja = date_create($fecha_de_baja);

					if($fecha_de_reingreso != '0000-00-00')
						$reingreso = date_create($fecha_de_reingreso);
					else
						$reingreso = date_create(date('Y', $fecha->format('U')) . '-12-31');//YYYY-12-31

					$interval = date_diff($baja,$reingreso);
					$dias_de_baja += $interval->days;// - 1
				}

				$txt .= $dias_de_baja . ($i < ($len - 1) ? ',' : '');
			}

			return $txt;
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

		private function calculate_anos_de_antiguedad()
		{
			$ingreso = $this->calculate_ingreso();

			if(isset($ingreso))
			{
				$this->Fecha_de_ingreso = $ingreso;
				$interval = date_diff(date_create($this->Fecha),date_create($ingreso));
				$dias_de_antiguedad = $interval->days + 1;
				$this->Anos_de_antiguedad = $dias_de_antiguedad / 365;
			}
			else
				$this->Anos_de_antiguedad = 0;

		}

		private function calculate_dias_de_vacaciones()
		{
			$anos = explode(',',$this->Anos);
			$len = count($anos);
			$this->Dias_de_vacaciones = '';

			for($i=$len-1; $i>=0; $i--)
			{

				if($i == 1)
				{

					if(is_int($this->Anos_de_antiguedad))
						$anos_de_antiguedad = $this->Anos_de_antiguedad - 1;
					else
						$anos_de_antiguedad = floor($this->Anos_de_antiguedad);

					if($anos_de_antiguedad < 5)
						$dias_de_vacaciones = ($anos_de_antiguedad * 2 + 4);
					else
						$dias_de_vacaciones = (floor($anos_de_antiguedad / 5) * 2 + 12);

				}
				else
				{
					$anos_de_antiguedad = ceil($this->Anos_de_antiguedad);
					$dias = ($this->Anos_de_antiguedad - floor($this->Anos_de_antiguedad));

					if($anos_de_antiguedad < 5)
						$dias_de_vacaciones = ($anos_de_antiguedad * 2 + 4) * $dias;
					else
						$dias_de_vacaciones = (floor($anos_de_antiguedad / 5) * 2 + 12) * $dias;

				}

				$this->Dias_de_vacaciones .= number_format($dias_de_vacaciones, 2, '.', '') . ($i > 0 ? ',' : '');
			}

		}

		public function calculate_salario_diario($usar_salario_minimo)
		{
			date_default_timezone_set('America/Mexico_City');
			$fecha = date_create($this->Fecha);
			$year = date('Y', $fecha->format('U'));

			if($usar_salario_minimo == 'true')
			{
				//cheching if this "Empresa" has any "Sucursal"
				$empresa = $this->get_empresa();
				$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");

				if($this->conn->num_rows($result) > 0)
				{
					$this->conn->freeResult($result);
					$result = $this->conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '{$this->Trabajador}' AND DATEDIFF('{$this->Fecha}', Trabajador_Sucursal.Fecha_de_ingreso) >= 0 AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");
				}
				else
				{
					$this->conn->freeResult($result);
					$result = $this->conn->query("SELECT Zona_geografica FROM Empresa WHERE RFC = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
				}

				list($zona_geografica) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Salario_minimo.$zona_geografica FROM Trabajador_Salario_minimo LEFT JOIN Salario_minimo ON Trabajador_Salario_minimo.Salario_minimo = Salario_minimo.Codigo WHERE Trabajador_Salario_minimo.Trabajador = '{$this->Trabajador}' AND Salario_minimo.Ano = '$year' AND Trabajador_Salario_minimo.Servicio = '{$this->Servicio}' AND Trabajador_Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND Salario_minimo.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}', Trabajador_Salario_minimo.Fecha) >= 0 ORDER BY Trabajador_Salario_minimo.Fecha DESC LIMIT 1");
				list($salario_diario) = $this->conn->fetchRow($result);
			}
			else
			{
				$result = $this->conn->query("SELECT Cantidad FROM Salario_diario WHERE Trabajador = '{$this->Trabajador}' AND DATEDIFF('$this->Fecha',Fecha) >= 0 AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha DESC LIMIT 1");
				list($salario_diario) = $this->conn->fetchRow($result);
			}

			if(isset($salario_diario))
				$this->Salario_diario = $salario_diario;
			else
				$this->Salario_diario = 0;

		}

		private function calculate_salario_minimo()
		{
			date_default_timezone_set('America/Mexico_City');
			//cheching if this "Empresa" has any "Sucursal"
			$empresa = $this->get_empresa();
			$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			if($this->conn->num_rows($result) > 0)
			{
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '{$this->Trabajador}' AND DATEDIFF('{$this->Fecha}', Trabajador_Sucursal.Fecha_de_ingreso) >= 0 AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");
			}
			else
			{
				$this->conn->freeResult($result);
				$result = $this->conn->query("SELECT Zona_geografica FROM Empresa WHERE RFC = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
			}

			list($zona) = $this->conn->fetchRow($result);
			$fecha = date_create($this->Fecha);
			$year = date('Y', $fecha->format('U'));
			$result = $this->conn->query("SELECT $zona FROM Salario_minimo WHERE Codigo = '1' AND Ano = '$year' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($salario_minimo) = $this->conn->fetchRow($result);
			return $salario_minimo;
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

		private function calculate_aguinaldo($dias_previos_al_ingreso, $dias_de_baja)
		{
			$result = $this->conn->query("SELECT Dias_de_aguinaldo FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($dias_de_aguinaldo) = $this->conn->fetchRow($result);
			$previos = explode(',',$dias_previos_al_ingreso);
			$bajas = explode(',',$dias_de_baja);
			$this->Dias_de_aguinaldo = (365 - $previos[count($previos) - 1] - $bajas[count($bajas) - 1]) * $dias_de_aguinaldo / 365;
			$this->Aguinaldo = $this->Dias_de_aguinaldo * $this->Salario_diario;
		}

		private function calculate_dias_de_prima_de_antiguedad()
		{

			if($this->Pagar_prima_de_antiguedad == 'true')
			{
				date_default_timezone_set('America/Mexico_City');
				$periodo = substr($this->Fecha,0,4) . substr($this->Fecha_de_ingreso,4);
				$_periodo = date_create($periodo);
				$_fecha = date_create($this->Fecha);

				if($_fecha < $_periodo)
				{
					$interval = $_periodo->diff($_fecha);
					$this->Dias_de_prima_de_antiguedad = (ceil($this->Anos_de_antiguedad) * 12) * (365 - $interval->days) / 365;
				}
				else
				{
					$interval = $_fecha->diff($_periodo);
					$this->Dias_de_prima_de_antiguedad = (ceil($this->Anos_de_antiguedad) * 12) * ($interval->days) / 365;
				}

			}
			else
					$this->Dias_de_prima_de_antiguedad = 0;

		}

		private function calculate_prima_de_antiguedad()
		{
			//getting last date for worker's income
			$result = $this->conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '$this->Trabajador' AND Fecha_de_reingreso != '0000-00-00' AND DATEDIFF(Fecha_de_reingreso, '{$this->Fecha}') <= 0 AND (Fecha_de_reingreso = '0000-00-00' OR DATEDIFF(Fecha_de_reingreso,Fecha_de_baja) > 1) AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_reingreso DESC LIMIT 1");

			if($this->conn->num_rows($result) == 0)
				$result = $this->conn->query("SELECT Fecha_de_ingreso FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");

			list($ingreso) = $this->conn->fetchRow($result);
			//getting every "Sucursal" for this "Empresa"
			$empresa = $this->get_empresa();
			$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");

			if($this->conn->num_rows($result) > 0)
			{
				$this->conn->freeResult($result);
				//getting every "Zona geografica" and "Fecha_de_ingreso" related to every "Sucursal" between "Ingreso" and "Fecha"
				$result = $this->conn->query("SELECT Sucursal.Zona_geografica, Trabajador_Sucursal.Fecha_de_ingreso FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '$this->Trabajador' AND DATEDIFF(Trabajador_Sucursal.Fecha_de_ingreso,'$ingreso') >= 0 AND DATEDIFF('{$this->Fecha}', Trabajador_Sucursal.Fecha_de_ingreso) >= 0 AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Trabajador_Sucursal.Fecha_de_ingreso ASC");
				$i = 0;
				$zonas = array();

				while(list($zona, $fecha) = $this->conn->fetchRow($result))
				{

					if($i == 0 && $fecha > $ingreso)
					{
						$result1 = $this->conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '$this->Trabajador' AND DATEDIFF('$ingreso',Trabajador_Sucursal.Fecha_de_ingreso) > 0 AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");
						list($zona1) = $this->conn->fetchRow($result1);
						$zonas[$i] = array($zona1,$ingreso);
						$i++;
					}

					if(($i > 0 && $zona != $zonas[$i - 1][0]) || $i == 0)//recording only distinct "Zonas geograficas"
					{
						$zonas[$i] = array($zona,$fecha);
						$i++;
					}

				}

				if(count($zonas) == 0)
				{
					$result = $this->conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '$this->Trabajador' AND DATEDIFF('$ingreso',Trabajador_Sucursal.Fecha_de_ingreso) > 0 AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");
					list($zona) = $this->conn->fetchRow($result);
					$zonas[$i] = array($zona,$ingreso);
				}

				//getting every "Salario minimo" for every "Zona geografica"
				$salarios = array();
				$len = count($zonas);
				$j = 0;

				for($i=0; $i<$len; $i++)

					if($zonas[$i][0] != '')
					{
						$year = substr($this->Fecha, 0, 4);
						$result = $this->conn->query("SELECT {$zonas[$i][0]} FROM Salario_minimo WHERE Codigo = '1' AND Ano = '$year' AND Cuenta = '{$_SESSION['cuenta']}'");
						list($cantidad) = $this->conn->fetchRow($result);
						$salarios[$j] = $cantidad;
						$j++;
					}

				$len = count($salarios);

				if($len > 1)
				{
					$total = 0.00;

					for($i=0; $i<$len; $i++)
						$total += $salarios[$i];

					$salario_minimo = $total / $len;
				}
				else
					$salario_minimo = $salarios[0];

			}
			else
			{
				$result = $this->conn->query("SELECT Zona_geografica FROM Empresa WHERE RFC = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($zona_geografica) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				$year = substr($this->Fecha, 0, 4);
				$result = $this->conn->query("SELECT $zona_geografica FROM Salario_minimo WHERE Codigo = '1' AND Ano = '$year' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($salario_minimo) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
			}


			$salario = ($this->Salario_diario > (2 * $salario_minimo)) ? (2 * $salario_minimo) : $this->Salario_diario;

			if($this->Pagar_prima_de_antiguedad == 'true')
				$this->Prima_de_antiguedad = $this->Dias_de_prima_de_antiguedad * $salario;
			else
				$this->Prima_de_antiguedad = 0.00;

		}

		private function calculate_gratificacion()
		{
			//getting base de prestaciones
			$result = $this->conn->query("SELECT Base_de_prestaciones FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($base_prestaciones) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);
			//getting base para el cáculo de la nomina
			$result = $this->conn->query("SELECT Base FROM Base WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}',Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
			list($base_nomina) = $this->conn->fetchRow($result);
			$this->conn->freeResult($result);

			if($base_prestaciones == 'Salario diario' && $base_nomina == 'Salario mínimo')
			{
				//salario diario
				$result = $this->conn->query("SELECT Cantidad FROM Salario_diario WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('{$this->Fecha}', Fecha) >= 0 ORDER BY Fecha DESC LIMIT 1");
				list($salario_diario) = $this->conn->fetchRow($result);
				$this->conn->freeResult($result);
				//vacaciones
				$dias = explode(',',$this->Dias_de_vacaciones);
				$len = count($dias);
				$vacaciones = '';

				for($i=0; $i<$len; $i++)
					$vacaciones .= $dias[$i] * $salario_diario . ($i < ($len - 1) ? ',' : '');

				//prima vacacional
				$_vacaciones = explode(',',$vacaciones);
				$len = count($_vacaciones);
				$prima_vacacional = '';

				for($i=0; $i<$len; $i++)
					$prima_vacacional .= $_vacaciones[$i] * 0.25 . ($i < ($len - 1) ? ',' : '');

				//aguinaldo
				$aguinaldo = $this->Dias_de_aguinaldo * $salario_diario;
				//prima de antiguedad

				//getting last date for worker's income
				$result = $this->conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}' AND Fecha_de_reingreso != '0000-00-00' AND DATEDIFF(Fecha_de_reingreso, '{$this->Fecha}') <= 0 AND (Fecha_de_reingreso = '0000-00-00' OR DATEDIFF(Fecha_de_reingreso,Fecha_de_baja) > 1) AND Servicio = '{$this->Servicio}' ORDER BY Fecha_de_reingreso DESC LIMIT 1");

				if($this->conn->num_rows($result) == 0)
					$result = $this->conn->query("SELECT Fecha_de_ingreso FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");

				list($ingreso) = $this->conn->fetchRow($result);
				//getting every "Sucursal" for this "Empresa"
				$empresa = $this->get_empresa();
				$result = $this->conn->query("SELECT Nombre FROM Sucursal WHERE Empresa = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
				if($this->conn->num_rows($result) > 0)
				{
					$this->conn->freeResult($result);
					//getting every "Zona geografica" and "Fecha_de_ingreso" related to every "Sucursal" between "Ingreso" and "Fecha"
					$result = $this->conn->query("SELECT Sucursal.Zona_geografica, Trabajador_Sucursal.Fecha_de_ingreso FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '{$this->Trabajador}' AND DATEDIFF(Trabajador_Sucursal.Fecha_de_ingreso,'$ingreso') >= 0 AND DATEDIFF('{$this->Fecha}', Trabajador_Sucursal.Fecha_de_ingreso) >= 0 AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Trabajador_Sucursal.Fecha_de_ingreso ASC");
					$i = 0;
					$zonas = array();

					while(list($zona, $fecha) = $this->conn->fetchRow($result))
					{

						if($i == 0 && $fecha > $ingreso)
						{
							$result1 = $this->conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '{$this->Trabajador}' AND DATEDIFF('$ingreso',Trabajador_Sucursal.Fecha_de_ingreso) > 0 AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");
							list($zona1) = $this->conn->fetchRow($result1);
							$zonas[$i] = array($zona1,$ingreso);
							$i++;
						}

						if(($i > 0 && $zona != $zonas[$i - 1][0]) || $i == 0)//recording only distinct "Zonas geograficas"
						{
							$zonas[$i] = array($zona,$fecha);
							$i++;
						}

					}

					if(count($zonas) == 0)
					{
						$result = $this->conn->query("SELECT Sucursal.Zona_geografica FROM Trabajador_Sucursal LEFT JOIN Sucursal ON Trabajador_Sucursal.Nombre = Sucursal.Nombre AND Trabajador_Sucursal.Empresa = Sucursal.Empresa WHERE Trabajador_Sucursal.Trabajador = '{$this->Trabajador}' AND DATEDIFF('$ingreso',Trabajador_Sucursal.Fecha_de_ingreso) > 0 AND Trabajador_Sucursal.Servicio = '{$this->Servicio}' AND Trabajador_Sucursal.Cuenta = '{$_SESSION['cuenta']}' AND Sucursal.Cuenta = '{$_SESSION['cuenta']}' ORDER BY Trabajador_Sucursal.Fecha_de_ingreso DESC LIMIT 1");
						list($zona) = $this->conn->fetchRow($result);
						$zonas[$i] = array($zona,$ingreso);
					}

					//getting every "Salario minimo" for every "Zona geografica"
					$salarios = array();
					$len = count($zonas);
					$j = 0;

					for($i=0; $i<$len; $i++)

						if($zonas[$i][0] != '')
						{
							$year = substr($this->Fecha, 0, 4);
							$result = $this->conn->query("SELECT {$zonas[$i][0]} FROM Salario_minimo WHERE Codigo = '1' AND Ano = '$year' AND Cuenta = '{$_SESSION['cuenta']}'");
							list($cantidad) = $this->conn->fetchRow($result);
							$salarios[$j] = $cantidad;
							$j++;
						}

					$len = count($salarios);

					if($len > 1)
					{
						$total = 0.00;

						for($i=0; $i<$len; $i++)
							$total += $salarios[$i];

						$salario_minimo = $total / $len;
					}
					else
						$salario_minimo = $salarios[0];

				}
				else
				{
					$result = $this->conn->query("SELECT Zona_geografica FROM Empresa WHERE RFC = '$empresa' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($zona_geografica) = $this->conn->fetchRow($result);
					$this->conn->freeResult($result);
					$year = substr($this->Fecha, 0, 4);
					$result = $this->conn->query("SELECT $zona_geografica FROM Salario_minimo WHERE Codigo = '1' AND Ano = '$year' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($salario_minimo) = $this->conn->fetchRow($result);
					$this->conn->freeResult($result);
				}


				$salario = ($salario_diario > (2 * $salario_minimo)) ? (2 * $salario_minimo) : $salario_diario;

				if($this->Pagar_prima_de_antiguedad == 'true')
					$prima_de_antiguedad = $this->Dias_de_prima_de_antiguedad * $salario;
				else
					$prima_de_antiguedad = 0.00;


				$_vacaciones = explode(',',$vacaciones);
				$_prima_vacacional = explode(',',$prima_vacacional);
				$vacaciones_ = explode(',',$this->Vacaciones);
				$prima_vacacional_ = explode(',',$this->Prima_vacacional);
				$len = count($vacaciones_);
				$percepciones_sd = 0.00;
				$percepciones_sm = 0.00;

				for($i=0; $i<$len; $i++)
				{
					$percepciones_sd += $_vacaciones[$i] + $_prima_vacacional[$i];
					$percepciones_sm += $vacaciones_[$i] + $prima_vacacional_[$i];
				}

				$percepciones_sd += $aguinaldo + $prima_de_antiguedad;
				$percepciones_sm += $this->Aguinaldo + $this->Prima_de_antiguedad;
				$diferencia = $percepciones_sd - $percepciones_sm;
				$this->Gratificacion += $diferencia;
			}

		}

		private function calculate_total_de_percepciones()
		{
			$vacaciones = explode(',',$this->Vacaciones);
			$prima_vacacional = explode(',',$this->Prima_vacacional);
			$len = count($vacaciones);
			$this->Total_de_percepciones = 0.00;

			for($i=0; $i<$len; $i++)
				$this->Total_de_percepciones += $vacaciones[$i] + $prima_vacacional[$i];

			$this->Total_de_percepciones += $this->Aguinaldo + $this->Prima_de_antiguedad + $this->Gratificacion;
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
			$result = $this->conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND Fecha_de_reingreso != '0000-00-00' AND DATEDIFF(Fecha_de_reingreso, '{$this->Fecha}') <= 0 AND (Fecha_de_reingreso = '0000-00-00' OR DATEDIFF(Fecha_de_reingreso, Fecha_de_baja) > 1) ORDER BY Fecha_de_reingreso DESC LIMIT 1");
			list($ingreso) = $this->conn->fetchRow($result);

			if(!isset($ingreso))
			{
				$result = $this->conn->query("SELECT Fecha_de_ingreso FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($ingreso) = $this->conn->fetchRow($result);
			}

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
			$result = $this->conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '{$this->Trabajador}' AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' AND Fecha_de_reingreso != '0000-00-00' AND DATEDIFF(Fecha_de_reingreso, '{$this->Fecha}') <= 0 AND (Fecha_de_reingreso = '0000-00-00' OR DATEDIFF(Fecha_de_reingreso, Fecha_de_baja) > 1) ORDER BY Fecha_de_reingreso DESC LIMIT 1");
			list($ingreso) = $this->conn->fetchRow($result);

			if(!isset($ingreso))
			{
				$result = $this->conn->query("SELECT Fecha_de_ingreso FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($ingreso) = $this->conn->fetchRow($result);
			}

			for($i=0; $i<$len; $i++)
			{
				$li = ($anos[$i] + 1) . substr($ingreso,4);
				$ls = $anos[$i] . substr($ingreso,4);
				$result = $this->conn->query("SELECT Retencion_proporcional_de_prima_vacacional FROM prestaciones LEFT JOIN Nomina ON prestaciones.Nomina = Nomina.id WHERE Nomina.Servicio = '{$this->Servicio}' AND DATEDIFF(Nomina.Limite_inferior_del_periodo, '$li') >= 0 AND DATEDIFF('$ls',Nomina.Limite_superior_del_periodo) >= 0 AND prestaciones.Trabajador = '{$this->Trabajador}' AND prestaciones.Cuenta = '{$_SESSION['cuenta']}' AND Nomina.Cuenta = '{$_SESSION['cuenta']}'");
				$prima_vacacional_retenida = 0.00;

				if($prima_vacacional == 'Cobrado')

					while(list($cantidad) = $this->conn->fetchRow($result))
						$prima_vacacional_retenida += $cantidad;

				$this->Prima_vacacional_retenida .= number_format($prima_vacacional_retenida, 2, '.', '') . ($i < ($len - 1) ? ',' : '');
			}

		}

		private function calculate_aguinaldo_retenido()
		{
			$result = $this->conn->query("SELECT Aguinaldo FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($aguinaldo) = $this->conn->fetchRow($result);
			date_default_timezone_set('America/Mexico_City');
			$this->Aguinaldo_retenido = 0.00;
			$fecha = date_create($this->Fecha);
			$year = date('Y', $fecha->format('U'));
			$result = $this->conn->query("SELECT Retencion_proporcional_de_aguinaldo FROM prestaciones LEFT JOIN Nomina ON prestaciones.Nomina = Nomina.id WHERE Nomina.Servicio = '{$this->Servicio}' AND YEAR(Nomina.Limite_superior_del_periodo) = '$year' AND prestaciones.Trabajador = '{$this->Trabajador}' AND prestaciones.Cuenta = '{$_SESSION['cuenta']}' AND Nomina.Cuenta = '{$_SESSION['cuenta']}'");

			if($aguinaldo == 'Cobrado')

				while(list($cantidad) = $this->conn->fetchRow($result))
					$this->Aguinaldo_retenido += $cantidad;

		}

		private function calculate_prima_de_antiguedad_retenida()
		{
			$result = $this->conn->query("SELECT Antiguedad FROM Servicio WHERE id = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($antiguedad) = $this->conn->fetchRow($result);
			date_default_timezone_set('America/Mexico_City');
			$result = $this->conn->query("SELECT Fecha_de_reingreso FROM Baja WHERE Trabajador = '{$this->Trabajador}' AND Fecha_de_reingreso != '0000-00-00' AND DATEDIFF(Fecha_de_reingreso, '{$this->Fecha}') <= 0 AND (Fecha_de_reingreso = '0000-00-00' OR DATEDIFF(Fecha_de_reingreso, Fecha_de_baja) > 1) AND Servicio = '{$this->Servicio}' AND Cuenta = '{$_SESSION['cuenta']}' ORDER BY Fecha_de_reingreso DESC LIMIT 1");
			list($ingreso) = $this->conn->fetchRow($result);

			if(!isset($ingreso))
			{
				$result = $this->conn->query("SELECT Fecha_de_ingreso FROM Servicio_Trabajador WHERE Servicio = '{$this->Servicio}' AND Trabajador = '{$this->Trabajador}' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($ingreso) = $this->conn->fetchRow($result);
			}

			$result = $this->conn->query("SELECT Retencion_proporcional_de_prima_de_antiguedad FROM prestaciones LEFT JOIN Nomina ON prestaciones.Nomina = Nomina.id WHERE Nomina.Servicio = '{$this->Servicio}' AND DATEDIFF(Nomina.Limite_inferior_del_periodo,'$ingreso') >= 0 AND DATEDIFF('$this->Fecha',Nomina.Limite_superior_del_periodo) >= 0 AND prestaciones.Trabajador = '{$this->Trabajador}' AND prestaciones.Cuenta = '{$_SESSION['cuenta']}' AND Nomina.Cuenta = '{$_SESSION['cuenta']}'");
			$this->Prima_de_antiguedad_retenida .=  0.00;

			if($antiguedad == 'Cobrado')

				while(list($cantidad) = $this->conn->fetchRow($result))
					$this->Prima_de_antiguedad_retenida += $cantidad;

			$this->Prima_de_antiguedad_retenida .=  number_format($this->Prima_de_antiguedad_retenida, 2, '.', '');
		}

		private function calculate_honorarios($incluir_contribuciones,$honorarios)
		{

			if($incluir_contribuciones = 'true')
				$this->Honorarios = number_format(($this->ISR + $this->Diferencia) * $honorarios / 100,2,'.','');
			else
				$this->Honorarios = number_format($this->Diferencia * $honorarios / 100,2,'.','');

		}

		private function compensate_gratificacion($numero_de_dias_del_periodo)
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

			$_prima_de_antiguedad = explode(',', $this->Prima_de_antiguedad);
			$len = count($_prima_de_antiguedad);
			$prima_de_antiguedad = 0;

			for($i=0; $i<$len; $i++)
				$prima_de_antiguedad += $_prima_de_antiguedad[$i];

			$gratificacion = $this->Pago_neto - $vacaciones - $prima_vacacional - $this->Aguinaldo - $prima_de_antiguedad;
			$dif_gratificacion = $this->dif_gratificacion($numero_de_dias_del_periodo,$vacaciones,$prima_vacacional,$this->Aguinaldo,$prima_de_antiguedad,$gratificacion);

			if($dif_gratificacion > 0)
			{
				$a = $gratificacion;
				$i = 0;

				while($i < 300)
				{
					$gratificacion += 100;
					$dif_gratificacion = $this->dif_gratificacion($numero_de_dias_del_periodo,$vacaciones,$prima_vacacional,$this->Aguinaldo,$prima_de_antiguedad,$gratificacion);

					if($dif_gratificacion < 0)//Bisection method
					{
						$b = $gratificacion;
						$j = 0;

						while($j < 500)
						{
							$m = ($a + $b) / 2;

							if($this->dif_gratificacion($numero_de_dias_del_periodo,$vacaciones,$prima_vacacional,$this->Aguinaldo,$prima_de_antiguedad,$m) == 0 || ($b - $a) / 2 <0.001)
							{
								$gratificacion = $m;
								break;
							}
							else
							{

								if($this->dif_gratificacion($numero_de_dias_del_periodo,$vacaciones,$prima_vacacional,$this->Aguinaldo,$prima_de_antiguedad,$a) * $this->dif_gratificacion($numero_de_dias_del_periodo,$vacaciones,$prima_vacacional,$this->Aguinaldo,$prima_de_antiguedad,$m) < 0)
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

			return $gratificacion;
		}

		private function dif_gratificacion($numero_de_dias_del_periodo,$vacaciones,$prima_vacacional,$aguinaldo,$prima_de_antiguedad,$gratificacion)
		{
			$salario_minimo = $this->calculate_salario_minimo();
			$base_ISR = $gratificacion + ($prima_vacacional > ($salario_minimo * 15) ? ($prima_vacacional - 300) : 0) + ($aguinaldo <= ($salario_minimo * 30) ? 0 : ($aguinaldo - $salario_minimo * 30)) + ($prima_de_antiguedad <= ($salario_minimo * 90) ? 0 : ($prima_de_antiguedad - $salario_minimo * 90));
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

			$dif = $this->Pago_neto + $ISR - $gratificacion - $vacaciones - $prima_vacacional - $aguinaldo - $prima_de_antiguedad;
			return $dif;
		}

		public function draw($act)//if act == 'EDIT' or act == 'ADD' some fields can be edited and the form is submitted. If act == 'DRAW' no fields can be edited and the form is not submitted
		{
			echo "<div class = 'datos_tab'>Datos</div>";
			echo '<form>';
				echo "<fieldset class = 'Datos_fieldset' style = 'visibility:visible'\>";
					echo "<textarea name = \"id\" class=\"hidden_textarea\" readonly=true>$this->id</textarea>";
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
						echo "<textarea class = 'trabajador_rfc_textarea' name = 'Trabajador' title = 'RFC del trabajador'>$this->Trabajador</textarea>";
					else
						echo "<select class = 'trabajador_rfc_select' name = 'Trabajador' title = 'RFC del trabajador'>" . (isset($this->Trabajador) ? "<option>$this->Trabajador</option>" : "") . "</select>";

					echo "<label class = 'servicio_label'>Servicio</label>";

					if(isset($this->Servicio))
					{
						$servicio = new Servicio();
						$servicio->set('id', $this->Servicio);
						$notation = $servicio->notation();
					}

					if($act == 'DRAW')
						echo "<textarea class = 'servicio_textarea' name = 'Servicio' title = 'Servicio'>$notation</textarea>";
					else
						echo "<select class = 'servicio_select' name = 'Servicio' title = 'Servicio'>" . (isset($this->Servicio) ? "<option>$notation</option>" : "") . "</select>";

					echo "<label class = 'gratificacion_label'>Gratificación</label>";
					echo "<textarea class = 'gratificacion_textarea' name = 'Gratificacion' title = 'Gratificación'  " . ($act == 'DRAW' ? "readonly=true" : "") . ">{$this->Gratificacion}</textarea>";
					echo "<label class = 'pago_neto_label'>Pago neto</label>";
					echo "<textarea class = 'pago_neto_textarea' name = 'Pago_neto' title = 'Pago neto'  " . ($act == 'DRAW' ? "readonly=true" : "") . ">{$this->Pago_neto}</textarea>";
					echo "<label class = 'fecha_label'>Fecha</label>";
					echo "<textarea class = 'fecha_textarea' name = 'Fecha' title = 'Fecha'  " . ($act == 'DRAW' ? "readonly=true" : "") . ">{$this->Fecha}</textarea>";

					if($act != 'DRAW')
						echo "<img class = 'calendar_button' onclick = 'show_cal(this)' \>";

					echo "<label class = \"pagar_prima_label\">Pagar prima de antigüedad</label>";
					echo "<input type = \"checkbox\" class = \"pagar_prima_input\" name = \"Pagar_prima_de_antiguedad\" title = \"Pagar prima de antigüedad\"". ($act == 'EDIT' || $act == 'ADD'?"":"readonly=true") . ($this->Pagar_prima_de_antiguedad == 'true'?" checked/>":"/>");
					echo "<label class = 'pago_label' name = 'Forma_de_pago'>Forma de pago</label>";

					if($act != 'DRAW')
					{
						echo '<select title = "Forma de pago" class = "pago_select" name = "Forma_de_pago">';
							
						if(isset($this->Forma_de_pago))
							{

								if($this->Forma_de_pago == 'Efectivo')
									echo '<option selected>Efectivo</option>';
								else
									echo '<option>Efectivo</option>';

								if($this->Forma_de_pago == 'Cheque')
									echo '<option selected>Cheque</option>';
								else
									echo '<option>Cheque</option>';

								if($this->Forma_de_pago == 'Transferencia bancaria')
									echo '<option selected>Transferencia bancaria</option>';
								else
									echo '<option>Transferencia bancaria</option>';

							}
							else
							{
								echo '<option>Efectivo</option>';
								echo '<option>Cheque</option>';
								echo '<option>Transferencia bancaria</option>';
							}

						echo '</select>';
					}
					else
						echo "<textarea class = \"pago_textarea\" name = \"Forma_de_pago\" title = \"Forma de pago\" readonly=true> $this->Forma_de_pago</textarea>";

					if($act == 'DRAW')
					{
						echo "<label class = 'ver_recibo_label'>Ver recibo</label>";
						echo "<input type='button' value='⌖' class = 'view_button' onclick = \"view_finiquito(this)\" onmouseover = \"view_button_bright(this)\" onmouseout = \"view_button_opaque(this)\"/>";//function view_finiquito at finiquito.js & functions view_button_opaque and bright at presentation.js
						echo "<label class = 'finiquitador_label'>Finiquitador</label>";
						echo "<select class = 'finiquitador_select'><option>Empresa administradora</option><option>Empresa</option></select>";
						echo "<label class = 'ver_carta_label'>Carta de renuncia</label>";
						echo "<input type='button' value='⌖' class = 'view_button' onclick = \"view_carta(this)\" onmouseover = \"view_button_bright(this)\" onmouseout = \"view_button_opaque(this)\"/>";
						echo "<label class = 'lugar_label'>Lugar</label>";
						echo "<textarea class = 'lugar_textarea' name = 'Lugar' title = 'Lugar'>{$this->Lugar}</textarea>";
					}

				echo "</fieldset>";

				if($act != 'DRAW')
					echo "<img class = 'submit_button' onclick = \"_submit('$act',this.parentNode,'Finiquito')\" />";

			echo "</form>";
		}

	}
?>
