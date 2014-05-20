<?php //This page is called from an ajax function named calculate at nomina.js?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

	<head>
	</head>

	<body>
		<?php
			include_once('connection.php');
			include_once('nomina.php');

			if(!isset($_SESSION))
				session_start();

			$nomina = new Nomina();
			$nomina->set('id',$_GET['id']);
			$nomina->set('Limite_inferior_del_periodo',$_GET['Limite_inferior_del_periodo']);
			$nomina->set('Limite_superior_del_periodo',$_GET['Limite_superior_del_periodo']);
			$nomina->set('Servicio',$_GET['Servicio']);
			$nomina->set('incidencias',$_POST['incidencias']);
			$trabajador = explode('>>',$_POST['trabajador']);
			$nomina->set('trabajador',$trabajador);
			//$nomina->showProperties();
			//Calculation order:
			$nomina->calculate_ISR_trabajadores_asalariados();
			$nomina->calculate_ISR_trabajadores_asimilables();
			$nomina->calculate_cuotas_IMSS_trabajadores_asalariados();
			$nomina->calculate_prestaciones_trabajadores_asalariados();
			$nomina->calculate_nomina_trabajadores_asalariados();
			$nomina->calculate_nomina_trabajadores_asimilables();
			$nomina->chk_saldo_trabajadores_asalariados();
			$nomina->calculate_neto_a_recibir_asalariados();
			$nomina->calculate_neto_a_recibir_asimilables();
			$nomina->calculate_incidencias_trabajadores();
			//Presentation order:
			$conn = new Connection();
			$result = $conn->query("SELECT Porcentaje_de_honorarios FROM Servicio WHERE id = '{$_GET['Servicio']}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($honorarios) = $conn->fetchRow($result);
			echo "<div id = 'honorarios' style = 'visibility:hidden'>$honorarios</div>";
			echo "{$nomina->get('ISRasalariados')}";
			echo "{$nomina->get('ISRasimilables')}";
			echo "{$nomina->get('cuotas_IMSS')}";
			echo "{$nomina->get('prestaciones')}";
			echo "{$nomina->get('nomina_asalariados')}";
			echo "{$nomina->get('nomina_asimilables')}";
			echo "{$nomina->get('incidencias')}";
		?>
	</body>
</html>
