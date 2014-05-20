<?php
	$subject = utf8_encode(html_entity_decode(urldecode($_GET['subject'])));

	if(isset($_GET['_iframe']))
		$_iframe = utf8_encode(html_entity_decode(urldecode($_GET['_iframe'])));

	if($subject == 'Aguinaldo')
	{
		include_once('aguinaldo.php');
		$obj = new Aguinaldo();
	}
	elseif($subject == 'Aportacion_del_trabajador_al_fondo_de_ahorro')
	{
		include_once('aportacion_del_trabajador_al_fondo_de_ahorro.php');
		$obj = new Aportacion_del_trabajador_al_fondo_de_ahorro();
	}
	elseif($subject == 'Apoderado')
	{
		include_once('apoderado.php');
		$obj = new Apoderado();
		$obj->set('Empresa',$_GET['empresa']);
	}
	elseif($subject == 'Archivo_digital')
	{
		include_once('archivo_digital.php');
		$obj = new Archivo_digital();

		if(isset($_GET['empresa']))
			$obj->set('Empresa',$_GET['empresa']);
		elseif(isset($_GET['trabajador']))
			$obj->set('Trabajador',$_GET['trabajador']);

	}
	elseif($subject == 'Baja')
	{
		include_once('baja.php');
		$obj = new Baja();
	}
	elseif($subject == 'Banco')
	{
		include_once('banco.php');
		$obj = new Banco();
	}
	elseif($subject == 'Base')
	{
		include_once('base.php');
		$obj = new Base();
	}
	elseif($subject == 'Empresa')
	{
		include_once('empresa.php');
		$obj = new Empresa();
	}
	elseif($subject == 'Contrato')
	{
		include_once('contrato.php');
		$obj = new Contrato();
	}
	elseif($subject == 'Credito_al_salario_diario')
	{
		include_once('credito_al_salario_diario.php');
		$obj = new Credito_al_salario_diario();
	}
	elseif($subject == 'Credito_al_salario_quincenal')
	{
		include_once('credito_al_salario_quincenal.php');
		$obj = new Credito_al_salario_quincenal();
	}
	elseif($subject == 'Credito_al_salario_mensual')
	{
		include_once('credito_al_salario_mensual.php');
		$obj = new Credito_al_salario_mensual();
	}
	elseif($subject == 'Credito_al_salario_semanal')
	{
		include_once('credito_al_salario_semanal.php');
		$obj = new Credito_al_salario_semanal();
	}
	elseif($subject == 'Factor_de_descuento')
	{
		include_once('factor_de_descuento.php');
		$obj = new Factor_de_descuento();
		$obj->set('Retencion_INFONAVIT',$_GET['retencion_INFONAVIT']);
	}
	elseif($subject == 'Finiquito')
	{
		include_once('finiquito.php');
		$obj = new Finiquito();
	}
	elseif($subject == 'Fondo_de_garantia')
	{
		include_once('fondo_de_garantia.php');
		$obj = new Fondo_de_garantia();
	}
	elseif($subject == 'Incapacidad')
	{
		include_once('incapacidad.php');
		$obj = new Incapacidad();
	}
	elseif($subject == 'ISR_anual')
	{
		include_once('isr_anual.php');
		$obj = new ISR_anual();
	}
	elseif($subject == 'ISR_diario')
	{
		include_once('isr_diario.php');
		$obj = new ISR_diario();
	}
	elseif($subject == 'ISR_mensual')
	{
		include_once('isr_mensual.php');
		$obj = new ISR_mensual();
	}
	elseif($subject == 'ISR_quincenal')
	{
		include_once('isr_quincenal.php');
		$obj = new ISR_quincenal();
	}
	elseif($subject == 'ISR_semanal')
	{
		include_once('isr_semanal.php');
		$obj = new ISR_semanal();
	}
	elseif($subject == 'Instrumento_notarial')
	{
		include_once('instrumento_notarial.php');
		$obj = new Instrumento_notarial();
		$obj->set('Empresa',$_GET['empresa']);
	}
	elseif($subject == 'Logo')
	{
		include_once('logo.php');
		$obj = new Logo();

		if(isset($_GET['sucursal']) &&  $_GET['sucursal'] != '' && $_GET['sucursal'] != 'undefined' && $_GET['sucursal'] != 'null')
		{
			$obj->set('Empresa_sucursal',$_GET['empresa_sucursal']);
			$obj->set('Sucursal',$_GET['sucursal']);
		}
		else
			$obj->set('Empresa',$_GET['empresa']);

	}
	elseif($subject == 'Monto_fijo_mensual')
	{
		include_once('monto_fijo_mensual.php');
		$obj = new Monto_fijo_mensual();
		$obj->set('Retencion_INFONAVIT',$_GET['retencion_INFONAVIT']);
	}
	elseif($subject == 'Nomina')
	{
		include_once('nomina.php');
		$obj = new Nomina();
	}
	elseif($subject == 'Pension_alimenticia')
	{
		include_once('pension_alimenticia.php');
		$obj = new Pension_alimenticia();
	}
	elseif($subject == 'Photo')
	{
		include_once('photo.php');
		$obj = new Photo();
		$obj->set('Trabajador',$_GET['trabajador']);
		$obj->set('Width',$_GET['width']);
		$obj->set('Height',$_GET['height']);
	}
	elseif($subject == 'Porcentaje_de_cuotas_IMSS')
	{
		include_once('porcentaje_de_cuotas_imss.php');
		$obj = new Porcentaje_de_cuotas_IMSS();
	}
	elseif($subject == 'Porcentaje_de_descuento')
	{
		include_once('porcentaje_de_descuento.php');
		$obj = new Porcentaje_de_descuento();
		$obj->set('Retencion_INFONAVIT',$_GET['retencion_INFONAVIT']);
	}
	elseif($subject == 'Prestamo_administradora')
	{
		include_once('prestamo_administradora.php');
		$obj = new Prestamo_administradora();
	}
	elseif($subject == 'Prestamo_caja')
	{
		include_once('prestamo_caja.php');
		$obj = new Prestamo_caja();
	}
	elseif($subject == 'Prestamo_cliente')
	{
		include_once('prestamo_cliente.php');
		$obj = new Prestamo_cliente();
	}
	elseif($subject == 'Prestamo_del_fondo_de_ahorro')
	{
		include_once('prestamo_del_fondo_de_ahorro.php');
		$obj = new Prestamo_del_fondo_de_ahorro();
	}
	elseif($subject == 'Prima')
	{
		include_once('prima.php');
		$obj = new Prima();
		$obj->set('Empresa',$_GET['empresa']);
	}
	elseif($subject == 'Propuesta')
	{
		include_once('propuesta.php');
		$obj = new Propuesta();
	}
	elseif($subject == 'Recibo_de_vacaciones')
	{
		include_once('recibo_de_vacaciones.php');
		$obj = new Recibo_de_vacaciones();
	}
	elseif($subject == 'Regimen_fiscal')
	{
		include_once('regimen_fiscal.php');
		$obj = new Regimen_fiscal();
		$obj->set('Empresa',$_GET['empresa']);
	}
	elseif($subject == 'Registro_patronal')
	{
		include_once('registro_patronal.php');
		$obj = new Registro_patronal();

		if(isset($_GET['sucursal']))
		{
			$obj->set('Empresa_sucursal',$_GET['empresa_sucursal']);
			$obj->set('Sucursal',$_GET['sucursal']);
		}
		else
			$obj->set('Empresa',$_GET['empresa']);

	}
	elseif($subject == 'Representante_legal')
	{
		include_once('representante_legal.php');
		$obj = new Representante_legal();
		$obj->set('Empresa',$_GET['empresa']);
	}
	elseif($subject == 'Retencion_FONACOT')
	{
		include_once('retencion_fonacot.php');
		$obj = new Retencion_FONACOT();
	}
	elseif($subject == 'Retencion_INFONAVIT')
	{
		include_once('retencion_infonavit.php');
		$obj = new Retencion_INFONAVIT();
	}
	elseif($subject == 'Pago_por_seguro_de_vida')
	{
		include_once('pago_por_seguro_de_vida.php');
		$obj = new Pago_por_seguro_de_vida();
	}
	elseif($subject == 'Salario_diario')
	{
		include_once('salario_diario.php');
		$obj = new Salario_diario();
	}
	elseif($subject == 'Salario_minimo')
	{
		include_once('salario_minimo.php');
		$obj = new Salario_minimo();
	}
	elseif($subject == 'Seguro_por_danos_a_la_vivienda')
	{
		include_once('seguro_por_danos_a_la_vivienda.php');
		$obj = new Seguro_por_danos_a_la_vivienda();
	}
	elseif($subject == 'Sello_digital')
	{
		include_once('sello_digital.php');
		$obj = new Sello_digital();

		if(isset($_GET['sucursal']))
		{
			$obj->set('Empresa_sucursal',$_GET['empresa_sucursal']);
			$obj->set('Sucursal',$_GET['sucursal']);
		}
		else
			$obj->set('Empresa',$_GET['empresa']);

	}
	elseif($subject == 'Servicio')
	{
		include_once('servicio.php');
		$obj = new Servicio();
	}
	elseif($subject == 'Servicio_adicional')
	{
		include_once('servicio_adicional.php');
		$obj = new Servicio_adicional();
		$obj->set('Servicio',$_GET['servicio']);
	}
	elseif($subject == 'Servicio_Empresa')
	{
		include_once('servicio_empresa.php');
		$obj = new Servicio_Empresa();
		$obj->set('Servicio',$_GET['servicio']);
	}
	elseif($subject == 'Servicio_Registro_patronal')
	{
		include_once('servicio_registro_patronal.php');
		$obj = new Servicio_Registro_patronal();
		$obj->set('Servicio',$_GET['servicio']);
	}
	elseif($subject == 'Servicio_Trabajador')
	{
		include_once('servicio_trabajador.php');
		$obj = new Servicio_Trabajador();
	}
	elseif($subject == 'Sign')
	{
		include_once('sign.php');
		$obj = new Sign();

		if(isset($_GET['trabajador']))
			$obj->set('Trabajador',$_GET['trabajador']);
		elseif(isset($_GET['usuario']))
			$obj->set('Usuario',$_GET['usuario']);

	}
	elseif($subject == 'Socio')
	{
		include_once('socio.php');
		$obj = new Socio();
		$obj->set('Empresa',$_GET['empresa']);
	}
	elseif($subject == 'Sucursal')
	{
		include_once('sucursal.php');
		$obj = new Sucursal();
		$obj->set('Empresa',$_GET['empresa']);
	}
	elseif($subject == 'Tipo')
	{
		include_once('tipo.php');
		$obj = new Tipo();
	}
	elseif($subject == 'Trabajador')
	{
		include_once('trabajador.php');
		$obj = new Trabajador();
	}
	elseif($subject == 'Trabajador_Salario_minimo')
	{
		include_once('trabajador_salario_minimo.php');
		$obj = new Trabajador_Salario_minimo();
	}
	elseif($subject == 'Trabajador_Sucursal')
	{
		include_once('trabajador_sucursal.php');
		$obj = new Trabajador_Sucursal();
	}
	elseif($subject == 'UMF')
	{
		include_once('umf.php');
		$obj = new UMF();
	}
	elseif($subject == 'Usuario')
	{
		include_once('usuario.php');
		$obj = new Usuario();
	}
	elseif($subject == 'Vacaciones')
	{
		include_once('vacaciones.php');
		$obj = new Vacaciones();
	}

	if($subject == 'Aportacion_del_trabajador_al_fondo_de_ahorro' || $subject == 'Incapacidad' || $subject == 'Vacaciones' || $subject == 'Pension_alimenticia' || $subject == 'Prestamo_administradora' || $subject == 'Prestamo_caja' || $subject == 'Prestamo_cliente' || $subject == 'Prestamo_del_fondo_de_ahorro' || $subject == 'Retencion_FONACOT' || $subject == 'Retencion_INFONAVIT' || $subject == 'Pago_por_seguro_de_vida' || $subject == 'Salario_diario' || $subject == 'Servicio_Trabajador' || $subject == 'Trabajador_Sucursal' || $subject == 'Trabajador_Salario_minimo' || $subject == 'Baja' || $subject == 'Contrato' || $subject == 'Banco' || $subject == 'UMF' || $subject == 'Tipo' || $subject == 'Base' || $subject == 'Fondo_de_garantia')
		$obj->set('Trabajador',utf8_encode(html_entity_decode(urldecode($_GET['trabajador']))));

	if($subject == 'Archivo_digital' || $subject == 'Logo' || $subject == 'Photo' || $subject == 'Propuesta' || $subject == 'Sello_digital' || $subject == 'Sign')
		$obj->draw($_GET['mode'],$_iframe);
	else
		$obj->draw('ADD');

?>
