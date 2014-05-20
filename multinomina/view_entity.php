<?php
	$subject = $_POST['subject'];


	if($subject == 'Aguinaldo')
	{
		include_once('aguinaldo.php');

		$obj = new Aguinaldo();
		$obj->set('id',$_POST['id']);
	}
	elseif($subject == 'Apoderado')
	{
		include_once('apoderado.php');

		$obj = new Apoderado();
		$obj->set('Nombre',$_POST['Nombre']);
		$obj->set('Empresa',$_POST['empresa']);
	}
	else if($subject == 'Aportacion_del_trabajador_al_fondo_de_ahorro')
	{
		include_once('aportacion_del_trabajador_al_fondo_de_ahorro.php');

		$obj = new Aportacion_del_trabajador_al_fondo_de_ahorro();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Archivo_digital')
	{
		include_once('archivo_digital.php');

		$obj = new Archivo_digital();
		$obj->set('Nombre',$_POST['Nombre']);
	}
	else if($subject == 'Baja')
	{
		include_once('baja.php');

		$obj = new Baja();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Banco')
	{
		include_once('banco.php');

		$obj = new Banco();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Base')
	{
		include_once('base.php');

		$obj = new Base();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Bonos_de_productividad')
	{
		include_once('bonos_de_productividad.php');

		$obj = new Bonos_de_productividad();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'CFDI_Trabajador')
	{
		include_once('cfdi_trabajador.php');

		$obj = new CFDI_Trabajador();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Comida')
	{
		include_once('comida.php');

		$obj = new Comida();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Compensaciones')
	{
		include_once('compensaciones.php');

		$obj = new Compensaciones();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Contrato')
	{
		include_once('contrato.php');

		$obj = new Contrato();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Credito_al_salario_diario')
	{
		include_once('credito_al_salario_diario.php');

		$obj = new Credito_al_salario_diario();
		$obj->set('Ano',$_POST['Ano']);
	}
	else if($subject == 'Credito_al_salario_quincenal')
	{
		include_once('credito_al_salario_quincenal.php');

		$obj = new Credito_al_salario_quincenal();
		$obj->set('Ano',$_POST['Ano']);
	}
	else if($subject == 'Credito_al_salario_mensual')
	{
		include_once('credito_al_salario_mensual.php');

		$obj = new Credito_al_salario_mensual();
		$obj->set('Ano',$_POST['Ano']);
	}
	else if($subject == 'Credito_al_salario_semanal')
	{
		include_once('credito_al_salario_semanal.php');

		$obj = new Credito_al_salario_semanal();
		$obj->set('Ano',$_POST['Ano']);
	}
	else if($subject == 'Delegacion_IMSS')
	{
		include_once('delegacion_imss.php');

		$obj = new Delegacion_IMSS();
		$obj->set('Nombre',$_POST['Nombre']);
	}
	else if($subject == 'Descuento_pendiente')
	{
		include_once('descuento_pendiente.php');

		$obj = new Descuento_pendiente();
		$data = explode(',',$_POST['id']);
		$obj->set('Nomina',$data[0]);
		$obj->set('Retencion',$data[1]);
		$obj->set('id',$data[2]);
		$obj->set('Trabajador',$data[3]);
	}
	else if($subject == 'Despensa')
	{
		include_once('despensa.php');

		$obj = new Despensa();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Dias_de_descanso')
	{
		include_once('dias_de_descanso.php');

		$obj = new Dias_de_descanso();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Empresa')
	{
		include_once('empresa.php');

		$obj = new Empresa();
		$obj->set('RFC',$_POST['RFC']);
	}
	else if($subject == 'Estimulos')
	{
		include_once('estimulos.php');

		$obj = new Estimulos();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Factor_de_descuento')
	{
		include_once('factor_de_descuento.php');

		$obj = new Factor_de_descuento();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Finiquito')
	{
		include_once('finiquito.php');

		$obj = new Finiquito();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Fondo_de_garantia')
	{
		include_once('fondo_de_garantia.php');

		$obj = new Fondo_de_garantia();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Incapacidad')
	{
		include_once('incapacidad.php');

		$obj = new Incapacidad();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Instrumento_notarial')
	{
		include_once('instrumento_notarial.php');

		$obj = new Instrumento_notarial();
		$obj->set('Numero_de_instrumento',$_POST['Numero_de_instrumento']);
		$obj->set('Empresa',$_POST['empresa']);
	}
	else if($subject == 'ISR_anual')
	{
		include_once('isr_anual.php');

		$obj = new ISR_anual();
		$obj->set('Ano',$_POST['Ano']);
	}
	else if($subject == 'ISR_diario')
	{
		include_once('isr_diario.php');

		$obj = new ISR_diario();
		$obj->set('Ano',$_POST['Ano']);
	}
	else if($subject == 'ISR_quincenal')
	{
		include_once('isr_quincenal.php');

		$obj = new ISR_quincenal();
		$obj->set('Ano',$_POST['Ano']);
	}
	else if($subject == 'ISR_mensual')
	{
		include_once('isr_mensual.php');

		$obj = new ISR_mensual();
		$obj->set('Ano',$_POST['Ano']);
	}
	else if($subject == 'ISR_semanal')
	{
		include_once('isr_semanal.php');

		$obj = new ISR_semanal();
		$obj->set('Ano',$_POST['Ano']);
	}
	else if($subject == 'Monto_fijo_mensual')
	{
		include_once('monto_fijo_mensual.php');

		$obj = new Monto_fijo_mensual();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Nomina')
	{
		include_once('nomina.php');

		$obj = new Nomina();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Nomina_propuesta')
	{
		include_once('nomina_propuesta.php');

		$obj = new Nomina_propuesta();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Oficina')
	{
		include_once('oficina.php');

		$obj = new Oficina();
		$obj->set('Nombre',$_POST['Nombre']);
	}
	else if($subject == 'Pago_neto')
	{
		include_once('pago_neto.php');

		$obj = new Pago_neto();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Pago_por_seguro_de_vida')
	{
		include_once('pago_por_seguro_de_vida.php');

		$obj = new Pago_por_seguro_de_vida();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Pension_alimenticia')
	{
		include_once('pension_alimenticia.php');

		$obj = new Pension_alimenticia();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Porcentaje_de_cuotas_IMSS')
	{
		include_once('porcentaje_de_cuotas_imss.php');

		$obj = new Porcentaje_de_cuotas_IMSS();
		$data = explode(',',$_POST['id']);
		$obj->set('Nombre',$data[0]);
		$obj->set('Ano',$data[1]);
	}
	else if($subject == 'Porcentaje_de_descuento')
	{
		include_once('porcentaje_de_descuento.php');

		$obj = new Porcentaje_de_descuento();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Premios_de_puntualidad_y_asistencia')
	{
		include_once('premios_de_puntualidad_y_asistencia.php');

		$obj = new Premios_de_puntualidad_y_asistencia();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Prestamo_administradora')
	{
		include_once('prestamo_administradora.php');

		$obj = new Prestamo_administradora();
		$obj->set('Numero_de_prestamo',$_POST['Numero_de_prestamo']);
	}
	else if($subject == 'Prestamo_caja')
	{
		include_once('prestamo_caja.php');

		$obj = new Prestamo_caja();
		$obj->set('Numero_de_prestamo',$_POST['Numero_de_prestamo']);
	}
	else if($subject == 'Prestamo_cliente')
	{
		include_once('prestamo_cliente.php');

		$obj = new Prestamo_cliente();
		$obj->set('Numero_de_prestamo',$_POST['Numero_de_prestamo']);
	}
	else if($subject == 'Prestamo_del_fondo_de_ahorro')
	{
		include_once('prestamo_del_fondo_de_ahorro.php');

		$obj = new Prestamo_del_fondo_de_ahorro();
		$obj->set('Numero_de_prestamo',$_POST['Numero_de_prestamo']);
	}
	else if($subject == 'Prevision_social')
	{
		include_once('prevision_social.php');

		$obj = new Prevision_social();
		$obj->set('Nombre',$_POST['Nombre']);
	}
	else if($subject == 'Prima')
	{
		include_once('prima.php');

		$obj = new Prima();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Propuesta')
	{
		include_once('propuesta.php');

		$obj = new Propuesta();
		$obj->set('id',$_POST['id']);
	}
	elseif($subject == 'Recibo_de_vacaciones')
	{
		include_once('recibo_de_vacaciones.php');

		$obj = new Recibo_de_vacaciones();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Regimen_fiscal')
	{
		include_once('regimen_fiscal.php');

		$obj = new Regimen_fiscal();
		$obj->set('id',$_POST['id']);
		$obj->set('Empresa',$_POST['empresa']);
	}
	else if($subject == 'Registro_patronal')
	{
		include_once('registro_patronal.php');

		$obj = new Registro_patronal();
		$obj->set('Numero',$_POST['Numero']);
	}
	else if($subject == 'Representante_legal')
	{
		include_once('representante_legal.php');

		$obj = new Representante_legal();
		$obj->set('Nombre',$_POST['Nombre']);
		$obj->set('Empresa',$_POST['empresa']);
	}
	else if($subject == 'Retardos')
	{
		include_once('retardos.php');

		$obj = new Retardos();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Retencion_FONACOT')
	{
		include_once('retencion_fonacot.php');

		$obj = new Retencion_FONACOT();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Retencion_INFONAVIT')
	{
		include_once('retencion_infonavit.php');

		$obj = new Retencion_INFONAVIT();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Salario_diario')
	{
		include_once('salario_diario.php');

		$obj = new Salario_diario();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Salario_minimo')
	{
		include_once('salario_minimo.php');

		$obj = new Salario_minimo();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Seguro_por_danos_a_la_vivienda')
	{
		include_once('seguro_por_danos_a_la_vivienda.php');

		$obj = new Seguro_por_danos_a_la_vivienda();
		$obj->set('Ano',$_POST['Ano']);
	}
	else if($subject == 'Sello_digital')
	{
		include_once('sello_digital.php');

		$obj = new Sello_digital();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Servicio')
	{
		include_once('servicio.php');

		$obj = new Servicio();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Servicio_adicional')
	{
		include_once('servicio_adicional.php');

		$obj = new Servicio_adicional();
		$obj->set('id',$_POST['id']);
		$obj->set('Servicio',$_POST['servicio']);
	}
	else if($subject == 'Servicio_Empresa')
	{
		include_once('servicio_empresa.php');

		$obj = new Servicio_Empresa();
		$data = explode(',',$_POST['id']);
		$obj->set('Servicio',$data[0]);
		$obj->set('Empresa',$data[1]);
	}
	else if($subject == 'Servicio_Registro_patronal')
	{
		include_once('servicio_registro_patronal.php');

		$obj = new Servicio_Registro_patronal();
		$data = explode(',',$_POST['id']);
		$obj->set('Servicio',$data[0]);
		$obj->set('Registro_patronal',$data[1]);
	}
	else if($subject == 'Servicio_Trabajador')
	{
		include_once('servicio_trabajador.php');

		$obj = new Servicio_Trabajador();
		$data = explode(',',$_POST['id']);
		$obj->set('Servicio',$data[0]);
		$obj->set('Trabajador',$data[1]);
	}
	else if($subject == 'Socio')
	{
		include_once('socio.php');

		$obj = new Socio();
		$obj->set('Nombre',$_POST['Nombre']);
		$obj->set('Empresa',$_POST['empresa']);
	}
	else if($subject == 'Subdelegacion_IMSS')
	{
		include_once('subdelegacion_imss.php');

		$obj = new Subdelegacion_IMSS();
		$obj->set('Nombre',$_POST['Nombre']);
	}
	else if($subject == 'Sucursal')
	{
		include_once('sucursal.php');

		$obj = new Sucursal();
		$obj->set('Nombre',$_POST['Nombre']);
		$obj->set('Empresa',$_POST['empresa']);
	}
	else if($subject == 'Tabla')
	{
		include_once('tabla.php');

		$obj = new Tabla();
		$obj->set('Nombre',$_POST['Nombre']);
	}
	else if($subject == 'Tipo')
	{
		include_once('tipo.php');

		$obj = new Tipo();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Trabajador')
	{
		include_once('trabajador.php');

		$obj = new Trabajador();
		$obj->set('RFC',$_POST['RFC']);
	}
	else if($subject == 'Trabajador_Sucursal')
	{
		include_once('trabajador_sucursal.php');

		$obj = new Trabajador_Sucursal();
		$data = explode(',',$_POST['id']);
		$obj->set('Trabajador',$data[0]);
		$obj->set('Nombre',$data[1]);
		$obj->set('Empresa',$data[2]);
		$obj->set('Fecha_de_ingreso',$data[3]);
	}
	else if($subject == 'Trabajador_Salario_minimo')
	{
		include_once('trabajador_salario_minimo.php');

		$obj = new Trabajador_Salario_minimo();
		$data = explode(',',$_POST['id']);
		$obj->set('Trabajador',$data[0]);
		$obj->set('Servicio',$data[1]);
		$obj->set('Fecha',$data[2]);
	}
	else if($subject == 'UMF')
	{
		include_once('umf.php');

		$obj = new UMF();
		$obj->set('id',$_POST['id']);
	}
	else if($subject == 'Usuario')
	{
		include_once('usuario.php');

		$obj = new Usuario();
		$obj->set('Nombre', $_POST['Nombre']);
	}
	else if($subject == 'Vacaciones')
	{
		include_once('vacaciones.php');

		$obj = new Vacaciones();
		$obj->set('id',$_POST['id']);
	}

	$obj->setFromDb();
	$obj->draw('DRAW');
?>
