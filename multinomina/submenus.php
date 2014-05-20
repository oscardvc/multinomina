<?php

	if(!isset($_SESSION))
		session_start();

	$menu = $_GET['menu'];

	if($menu == 'cfdi')
		echo '  <li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">CFDI Trabajador</li>';
	elseif($menu == 'nomina')
	{

		if($_SESSION['cuenta'] == 'multiasesoria')
		echo '  <li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Propuesta</li>';

		echo '  <li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Servicio</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Nomina</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Aguinaldo</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Recibo de vacaciones</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Finiquito</li>
			<li onclick = "concentrado_trabajador()" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Concentrado de datos del trabajador</li>
			<li onclick = "concentrado_empresa_nomina()" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Concentrado de datos de trabajadores por empresa</li>
			<li onclick = "concentrado_registro_patronal_nomina()" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Concentrado de datos de trabajadores por registro patronal</li>
			<li onclick = "concentrado_empresa_imss()" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Concentrado de IMSS por empresa</li>
			<li onclick = "concentrado_registro_patronal_imss()" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Concentrado de IMSS por regristro patronal</li>
			<li onclick = "reporte_prestaciones()" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Reporte de prestaciones</li>
			<li onclick = "calculo_anual()" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Calculo anual de ISR</li>';
	}
	else//config
	{

		echo '	<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Salario mínimo</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Usuario</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Actividad</li>';

		if($_SESSION['cuenta'] == 'multiasesoria')
			echo '<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Porcentaje de cuotas IMSS</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Seguro por daños a la vivienda</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Crédito al salario diario</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Crédito al salario semanal</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Crédito al salario quincenal</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">Crédito al salario mensual</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">ISR diario</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">ISR semanal</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">ISR quincenal</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">ISR mensual</li>
			<li onclick = "_load(this)" onmouseover = "menu_bright(this)" onmouseout = "menu_opaque(this)">ISR anual</li>';

	}


?>
