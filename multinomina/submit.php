<?php
	$tabla = utf8_encode(html_entity_decode(urldecode($_GET['tabla'])));
	$mode = $_GET['mode'];

	if($tabla == 'Aguinaldo')
	{
		include_once('aguinaldo.php');
		$obj = new Aguinaldo();
	}
	elseif($tabla == 'Aportacion_del_trabajador_al_fondo_de_ahorro')
	{
		include_once('aportacion_del_trabajador_al_fondo_de_ahorro.php');
		$obj = new Aportacion_del_trabajador_al_fondo_de_ahorro();
	}
	elseif($tabla == 'Apoderado')
	{
		include_once('apoderado.php');
		$obj = new Apoderado();
	}
	elseif($tabla == 'Archivo_digital')
	{
		include_once('archivo_digital.php');
		$obj = new Archivo_digital();
	}
	elseif($tabla == 'Baja')
	{
		include_once('baja.php');
		$obj = new Baja();
	}
	elseif($tabla == 'Banco')
	{
		include_once('banco.php');
		$obj = new Banco();
	}
	elseif($tabla == 'Base')
	{
		include_once('base.php');
		$obj = new Base();
	}
	elseif($tabla == 'Empresa')
	{
		include_once('empresa.php');
		$obj = new Empresa();
	}
	elseif($tabla == 'Contrato')
	{
		include_once('contrato.php');
		$obj = new Contrato();
	}
	elseif($tabla == 'Credito_al_salario_diario')
	{
		include_once('credito_al_salario_diario.php');
		$obj = new Credito_al_salario_diario();
	}
	elseif($tabla == 'Credito_al_salario_quincenal')
	{
		include_once('credito_al_salario_quincenal.php');
		$obj = new Credito_al_salario_quincenal();
	}
	elseif($tabla == 'Credito_al_salario_mensual')
	{
		include_once('credito_al_salario_mensual.php');
		$obj = new Credito_al_salario_mensual();
	}
	elseif($tabla == 'Credito_al_salario_semanal')
	{
		include_once('credito_al_salario_semanal.php');
		$obj = new Credito_al_salario_semanal();
	}
	elseif($tabla == 'Delegacion_IMSS')
	{
		include_once('delegacion_imss.php');
		$obj = new Delegacion_IMSS();
	}
	elseif($tabla == 'Descuento_pendiente')
	{
		include_once('descuento_pendiente.php');
		$obj = new Descuento_pendiente();
	}
	elseif($tabla == 'Empresa_administradora')
	{
		include_once('administradora.php');
		$obj = new Administradora();
	}
	elseif($tabla == 'Establecimiento')
	{
		include_once('establecimiento.php');
		$obj = new Establecimiento();
	}
	elseif($tabla == 'Factor_de_descuento')
	{
		include_once('factor_de_descuento.php');
		$obj = new Factor_de_descuento();
	}
	elseif($tabla == 'Finiquito')
	{
		include_once('finiquito.php');
		$obj = new Finiquito();
	}
	elseif($tabla == 'Fondo_de_garantia')
	{
		include_once('fondo_de_garantia.php');
		$obj = new Fondo_de_garantia();
	}
	elseif($tabla == 'Incapacidad')
	{
		include_once('incapacidad.php');
		$obj = new Incapacidad();
	}
	elseif($tabla == 'Instrumento_notarial')
	{
		include_once('instrumento_notarial.php');
		$obj = new Instrumento_notarial();
	}
	elseif($tabla == 'ISR_anual')
	{
		include_once('isr_anual.php');
		$obj = new ISR_anual();
	}
	elseif($tabla == 'ISR_diario')
	{
		include_once('isr_diario.php');
		$obj = new ISR_diario();
	}
	elseif($tabla == 'ISR_mensual')
	{
		include_once('isr_mensual.php');
		$obj = new ISR_mensual();
	}
	elseif($tabla == 'ISR_quincenal')
	{
		include_once('isr_quincenal.php');
		$obj = new ISR_quincenal();
	}
	elseif($tabla == 'ISR_semanal')
	{
		include_once('isr_semanal.php');
		$obj = new ISR_semanal();
	}
	elseif($tabla == 'Monto_fijo_mensual')
	{
		include_once('monto_fijo_mensual.php');
		$obj = new Monto_fijo_mensual();
	}
	elseif($tabla == 'Nomina')
	{
		include_once('nomina.php');
		$obj = new Nomina();
	}
	elseif($tabla == 'Oficina')
	{
		include_once('oficina.php');
		$obj = new Oficina();
	}
	elseif($tabla == 'Pension_alimenticia')
	{
		include_once('pension_alimenticia.php');
		$obj = new Pension_alimenticia();
	}
	elseif($tabla == 'Porcentaje_de_cuotas_IMSS')
	{
		include_once('porcentaje_de_cuotas_imss.php');
		$obj = new Porcentaje_de_cuotas_IMSS();
	}
	elseif($tabla == 'Porcentaje_de_descuento')
	{
		include_once('porcentaje_de_descuento.php');
		$obj = new Porcentaje_de_descuento();
	}
	elseif($tabla == 'Prestamo_administradora')
	{
		include_once('prestamo_administradora.php');
		$obj = new Prestamo_administradora();
	}
	elseif($tabla == 'Prestamo_caja')
	{
		include_once('prestamo_caja.php');
		$obj = new Prestamo_caja();
	}
	elseif($tabla == 'Prestamo_cliente')
	{
		include_once('prestamo_cliente.php');
		$obj = new Prestamo_cliente();
	}
	elseif($tabla == 'Prestamo_del_fondo_de_ahorro')
	{
		include_once('prestamo_del_fondo_de_ahorro.php');
		$obj = new Prestamo_del_fondo_de_ahorro();
	}
	elseif($tabla == 'Prevision_social')
	{
		include_once('prevision_social.php');
		$obj = new Prevision_social();
	}
	elseif($tabla == 'Prima')
	{
		include_once('prima.php');
		$obj = new Prima();
	}
	elseif($tabla == 'Recibo_de_vacaciones')
	{
		include_once('recibo_de_vacaciones.php');
		$obj = new Recibo_de_vacaciones();
	}
	elseif($tabla == 'Regimen_fiscal')
	{
		include_once('regimen_fiscal.php');
		$obj = new Regimen_fiscal();
	}
	elseif($tabla == 'Registro_patronal')
	{
		include_once('registro_patronal.php');
		$obj = new Registro_patronal();
	}
	elseif($tabla == 'Representante_legal')
	{
		include_once('representante_legal.php');
		$obj = new Representante_legal();
	}
	elseif($tabla == 'Retencion_FONACOT')
	{
		include_once('retencion_fonacot.php');
		$obj = new Retencion_FONACOT();
	}
	elseif($tabla == 'Retencion_INFONAVIT')
	{
		include_once('retencion_infonavit.php');
		$obj = new Retencion_INFONAVIT();
	}
	elseif($tabla == 'Pago_por_seguro_de_vida')
	{
		include_once('pago_por_seguro_de_vida.php');
		$obj = new Pago_por_seguro_de_vida();
	}
	elseif($tabla == 'Salario_diario')
	{
		include_once('salario_diario.php');
		$obj = new Salario_diario();
	}
	elseif($tabla == 'Salario_minimo')
	{
		include_once('salario_minimo.php');
		$obj = new Salario_minimo();
	}
	elseif($tabla == 'Salario_real')
	{
		include_once('salario_real.php');
		$obj = new Salario_real();
	}
	elseif($tabla == 'Servicio')
	{
		include_once('servicio.php');
		$obj = new Servicio();
	}
	elseif($tabla == 'Servicio_adicional')
	{
		include_once('servicio_adicional.php');
		$obj = new Servicio_adicional();
	}
	elseif($tabla == 'Seguro_por_danos_a_la_vivienda')
	{
		include_once('seguro_por_danos_a_la_vivienda.php');
		$obj = new Seguro_por_danos_a_la_vivienda();
	}
	elseif($tabla == 'Servicio_Empresa')
	{
		include_once('servicio_empresa.php');
		$obj = new Servicio_Empresa();
	}
	elseif($tabla == 'Servicio_Trabajador')
	{
		include_once('servicio_trabajador.php');
		$obj = new Servicio_Trabajador();
	}
	elseif($tabla == 'Servicio_Registro_patronal')
	{
		include_once('servicio_registro_patronal.php');
		$obj = new Servicio_Registro_patronal();
	}
	elseif($tabla == 'Socio')
	{
		include_once('socio.php');
		$obj = new Socio();
	}
	elseif($tabla == 'Subdelegacion_IMSS')
	{
		include_once('subdelegacion_imss.php');
		$obj = new Subdelegacion_IMSS();
	}
	elseif($tabla == 'Sucursal')
	{
		include_once('sucursal.php');
		$obj = new Sucursal();
	}
	elseif($tabla == 'Tabla')
	{
		include_once('tabla.php');
		$obj = new Tabla();
	}
	elseif($tabla == 'Tipo')
	{
		include_once('tipo.php');
		$obj = new Tipo();
	}
	elseif($tabla == 'Trabajador')
	{
		include_once('trabajador.php');
		$obj = new Trabajador();
	}
	elseif($tabla == 'Trabajador_Salario_minimo')
	{
		include_once('trabajador_salario_minimo.php');
		$obj = new Trabajador_Salario_minimo();
	}
	elseif($tabla == 'Trabajador_Sucursal')
	{
		include_once('trabajador_sucursal.php');
		$obj = new Trabajador_Sucursal();
	}
	elseif($tabla == 'UMF')
	{
		include_once('umf.php');
		$obj = new UMF();
	}
	elseif($tabla == 'Usuario')
	{
		include_once('usuario.php');
		$obj = new Usuario();
	}
	elseif($tabla == 'Vacaciones')
	{
		include_once('vacaciones.php');
		$obj = new Vacaciones();
	}

	$obj->setFromBrowser();
	//$obj->showProperties();

	if($mode == 'ADD')
		$obj->dbStore('false');
	elseif($mode == 'EDIT')
		$obj->dbStore('true');

?>
