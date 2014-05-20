function show_submenu(subject)
{
	var _submenu = document.getElementById('submenu');

	if(_submenu)
	{
		document.body.removeChild(_submenu);
		return;
	}

	var _table_selected = document.getElementById('table_selected');

	if(_table_selected)
		document.body.removeChild(_table_selected);

	var _container = document.getElementById('container');
	_tables = _container.getElementsByTagName('table');

	while(_tables.length > 0)
		_tables[0].parentNode.removeChild(_tables[0]);

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlSubmenu = new XMLHttpRequest();

		if (xmlSubmenu.overrideMimeType)
			xmlSubmenu.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlSubmenu = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlSubmenu = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlSubmenu.onreadystatechange = function()
	{

		if (xmlSubmenu.readyState==4 && xmlSubmenu.status==200 && xmlSubmenu.responseText != '')
		{
			var _submenu = document.createElement('ul');
			document.body.appendChild(_submenu);
			_submenu.setAttribute('id','submenu');
			_submenu.innerHTML = xmlSubmenu.responseText;
			_submenu.style.display = 'block';
			_submenu.style.position = 'absolute';
			_submenu.style.padding = '5px 0px';
			_submenu.style.margin = 0;
			_submenu.style.border = '2px solid #555';
			_submenu.style.background = '#eee';
			_submenu.style.width = parseInt(window_width * 0.10) + 'px';
			_submenu.style.top = _height + 10 + 'px';
			_submenu.style.left = window_width - _submenu.offsetWidth - _height + 'px';
			var lis = _submenu.getElementsByTagName('li');

			for(var i=0; i<lis.length; i++)
			{
				lis[i].style.display = 'block';
				lis[i].style.padding = '5px';
				lis[i].style.font = font;
				lis[i].style.textAlign = 'left';
				lis[i].style.color = '#555';
				lis[i].style.cursor = 'pointer';
				lis[i].style.borderBottom = i == (lis.length - 1) ? 'none' : '1px solid #555';
			}

		}

	}

	xmlSubmenu.open('POST','submenus.php?menu=' + subject, true);
	xmlSubmenu.setRequestHeader("cache-control","no-cache");
	xmlSubmenu.send('');
}

function _load(obj)
{

	if(typeof obj == 'object')
	{
		var _table_selected = document.getElementById('table_selected');

		if(_table_selected)
			document.body.removeChild(_table_selected);

		var _submenu = document.getElementById('submenu');
		if(_submenu) document.body.removeChild(_submenu);
		var _table_selected = document.createElement('div');
		document.body.appendChild(_table_selected);
		_table_selected.innerHTML = obj.innerHTML;
		_table_selected.setAttribute('id','table_selected');
		_table_selected.style.display = 'block';
		_table_selected.style.position = 'absolute';
		_table_selected.style.font = title_font;
		_table_selected.style.padding = '5px 10px 5px 50px';
		_table_selected.style.color = '#fff';
		_table_selected.style.top = parseInt((_height - _table_selected.offsetHeight) / 2) + 'px';
		_table_selected.style.left = parseInt((window_width - _table_selected.offsetWidth) / 2) + 'px';
		_table_selected.style.border = 'none';
		_table_selected.style.cursor = 'default';
		var _table = obj.innerHTML;//obj is a menu list element
	}
	else if(obj == 'Trabajador' || obj == 'Empresa')
	{
		var _table = obj;//obj is a string
		var _table_selected = document.getElementById('table_selected');

		if(_table_selected)
			document.body.removeChild(_table_selected);

		var _submenu = document.getElementById('submenu');
		if(_submenu) document.body.removeChild(_submenu);
		var _table_selected = document.createElement('div');
		document.body.appendChild(_table_selected);
		_table_selected.innerHTML = obj == 'Trabajador' ? 'Trabajadores' : 'Empresas';
		_table_selected.setAttribute('id','table_selected');
		_table_selected.style.display = 'block';
		_table_selected.style.position = 'absolute';
		_table_selected.style.font = title_font;
		_table_selected.style.padding = '5px 10px 5px 50px';
		_table_selected.style.color = '#fff';
		_table_selected.style.top = parseInt((_height - _table_selected.offsetHeight) / 2) + 'px';
		_table_selected.style.left = parseInt((window_width - _table_selected.offsetWidth) / 2) + 'px';
		_table_selected.style.border = 'none';
		_table_selected.style.cursor = 'default';
	}
	else
		var _table = obj;//obj is a string

	_table = _table.replace(/\s/g,'_');
	_table = _table.replace(/á/g,'a');
	_table = _table.replace(/é/g,'e');
	_table = _table.replace(/í/g,'i');
	_table = _table.replace(/ó/g,'o');
	_table = _table.replace(/ú/g,'u');
	_table = _table.replace(/ñ/g,'n');

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlload = new XMLHttpRequest();

		if (xmlload.overrideMimeType)
			xmlload.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlload = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlload = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlload.onreadystatechange = function()
	{

		if (xmlload.readyState==4 && xmlload.status==200 && xmlload.responseText != '')
		{
			var _container = document.getElementById('container');
			var divs = _container.getElementsByTagName('div');
			var _options = divs[0];
			var tables = _container.getElementsByTagName('table');

			while(_container.firstChild.tagName != 'DIV')
				_container.removeChild(_container.firstChild);

			_options.innerHTML = '';

			if(_options.getAttribute('dbtable2'))
				_options.removeAttribute('dbtable2');

			_options.setAttribute('dbtable2',_table);
			var capsule = document.createElement('div');
			capsule.innerHTML = xmlload.responseText;
			var tables = capsule.getElementsByTagName('table');

			if(tables.length < 3)
			{
				_container.insertBefore(tables[0],_options);
				_container.insertBefore(tables[0],_options);
			}
			else
			{
				_container.insertBefore(tables[0],_options);
				_options.appendChild(tables[0]);
				_container.insertBefore(tables[0],_options);
			}

			fit_content();//at presentation.js
		}

	}

	var _container = document.getElementById('container');
	var tables = _container.getElementsByTagName('table');
	var len = 0;

	for(var i=0; i<tables.length; i++)

		if(tables[i].getAttribute('class') == 'search_table')
		{
			options_search_columns = tables[i].rows[0].cells;
			len = options_search_columns.length;
			break;
		}

	var values = 'values=';

	for(i=1; i<len; i++)
	{

		if(i == len-1)
			values += options_search_columns[i].childNodes[0].value;
		else
			values += options_search_columns[i].childNodes[0].value + ',';

	}

	values += '&subject=' + _table;
	xmlload.open('POST','load.php', true);
	xmlload.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmlload.setRequestHeader("Cache-control","no-cache");
	xmlload.setRequestHeader("Content-length", values.length);
	xmlload.setRequestHeader("Connection", "close");
	xmlload.send(values);
}

function getID(dbtable)
{

	if(dbtable == 'Empresa_administradora' || dbtable == 'Empresa' || dbtable == 'Trabajador')
		var id = 'RFC';
	else if(dbtable == 'Representante_legal' || dbtable == 'Apoderado' || dbtable == 'Socio' || dbtable == 'Oficina' || dbtable == 'Archivo_digital' || dbtable == 'Tabla' || dbtable == 'Prevision_social' || dbtable == 'Sucursal' || dbtable == 'Usuario')
		var id = 'Nombre';
	else if(dbtable == 'Registro_patronal')
		var id = 'Numero';
	else if(dbtable == 'Establecimiento')
		var id = 'Domicilio';
	else if(dbtable == 'Servicio_adicional' || dbtable == 'Servicio' || dbtable == 'Incapacidad' || dbtable == 'Vacaciones' || dbtable == 'Nomina' || dbtable == 'Nomina_propuesta' || dbtable == 'Aguinaldo' || dbtable == 'Salario_diario' || dbtable == 'Salario_real' || dbtable == 'Horas_extra' || dbtable == 'Dias_de_descanso' || dbtable == 'Premios_de_puntualidad_y_asistencia' || dbtable == 'Bonos_de_productividad' || dbtable == 'Estimulos' || dbtable == 'Compensaciones' || dbtable == 'Despensa' || dbtable == 'Comida' || dbtable == 'Retencion_INFONAVIT' || dbtable == 'Retencion_FONACOT' || dbtable == 'Pago_por_seguro_de_vida' || dbtable == 'Aportacion_del_trabajador_al_fondo_de_ahorro' || dbtable == 'Pension_alimenticia' || dbtable == 'Retardos' || dbtable == 'Pago_neto' || dbtable == 'Baja' || dbtable == 'Salario_minimo' || dbtable == 'Recibo_de_vacaciones' || dbtable == 'Finiquito' || dbtable == 'Contrato' || dbtable == 'Banco' || dbtable == 'UMF' || dbtable == 'Tipo' || dbtable == 'Base' || dbtable == 'Prima' || dbtable == 'Factor_de_descuento' || dbtable == 'Porcentaje_de_descuento' || dbtable == 'Monto_fijo_mensual' || dbtable == 'Fondo_de_garantia' || dbtable == 'Regimen_fiscal' || dbtable == 'CFDI_Trabajador' || dbtable == 'Propuesta' || dbtable == 'Sello_digital')
		var id = 'id';
	else if(dbtable == 'Instrumento_notarial')
		var id = 'Numero_de_instrumento';
	else if(dbtable == 'Faltas' || dbtable == 'Prima_dominical')
		var id = 'Fechas';
	else if(dbtable == 'Prestamo_caja' || dbtable == 'Prestamo_cliente' || dbtable == 'Prestamo_administradora' || dbtable == 'Prestamo_del_fondo_de_ahorro')
		var id = 'Numero_de_prestamo';
	else if(dbtable == 'Credito_al_salario_diario' || dbtable == 'Credito_al_salario_quincenal' || dbtable == 'Credito_al_salario_mensual' || dbtable == 'Credito_al_salario_semanal' || dbtable == 'ISR_anual' || dbtable == 'ISR_diario' || dbtable == 'ISR_quincenal' || dbtable == 'ISR_mensual' || dbtable == 'ISR_semanal' || dbtable == 'Seguro_por_danos_a_la_vivienda')
		var id = 'Ano';

	return id;
}

function getUser()
{
	return	document.getElementById('_user').innerHTML;
}

function chkUser(dbTable)
{
	return true;
/*
	var usr = document.getElementById('usr_img').getAttribute('title');

	if((dbTable == 'Empresa_administradora' || dbTable == 'Representante_legal' || dbTable == 'Apoderado' || dbTable == 'Socio' || dbTable == 'Instrumento_notarial' || dbTable == 'Empresa' || dbTable == 'Sucursal' || dbTable == 'Prima' || dbTable == 'Archivo_digital' || dbTable == 'Servicio' || dbTable == 'Servicio_adicional' || dbTable == 'Registro_patronal') && usr == 'juridico')
		return true;
	else if((dbTable == 'Baja' || dbTable == 'Establecimiento' || dbTable == 'Salario_diario' || dbTable == 'Faltas' || dbTable == 'Incapacidad' || dbTable == 'Vacaciones' || dbTable == 'Horas_extra' || dbTable == 'Prima_dominical' || dbTable == 'Dias_de_descanso' || dbTable == 'Premios_de_puntualidad_y_asistencia' || dbTable == 'Bonos_de_productividad' || dbTable == 'Estimulos' || dbTable == 'Compensaciones' || dbTable == 'Despensa' || dbTable == 'Comida' || dbTable == 'Prevision_social' || dbTable == 'Retencion_INFONAVIT' || dbTable == 'Retencion_FONACOT' || dbTable == 'Pago_por_seguro_de_vida' || dbTable == 'Tabla' || dbTable == 'Nomina' || dbTable == 'Aguinaldo' || dbTable == 'Seguro_por_danos_a_la_vivienda' || dbTable == 'Aportacion_del_trabajador_al_fondo_de_ahorro' || dbTable == 'Pension_alimenticia' || dbTable == 'Retardos' || dbTable == 'Prestamo_caja' || dbTable == 'Prestamo_cliente' || dbTable == 'Prestamo_administradora' || dbTable == 'Pago_neto' || dbTable == 'Porcentaje_de_cuotas_IMSS' || dbTable == 'Salario_minimo' || dbTable == 'Salario_real' || dbTable == 'Descuento_pendiente' || dbTable == 'Recibo_de_vacaciones' || dbTable == 'Finiquito' || dbTable == 'Trabajador_Salario_minimo' || dbTable == 'Servicio_Trabajador' || dbTable == 'Contrato' || dbTable == 'UMF' || dbTable == 'Tipo' || dbTable == 'Base' || dbTable == 'Credito_al_salario_quincenal' || dbTable == 'Credito_al_salario_mensual' || dbTable == 'Credito_al_salario_semanal' || dbTable == 'ISR_anual' || dbTable == 'ISR_mensual' || dbTable == 'ISR_quincenal' || dbTable == 'ISR_semanal') && usr == 'nomina')
		return true;
	else if((dbTable == 'Servicio_adicional' || dbTable == 'Prestacion' || dbTable == 'Oficina' || dbTable == 'Servicio') && usr == 'finanzas')
		return true;
	else if((dbTable == 'Trabajador' || dbTable == 'Archivo_digital') && (usr == 'administrativo' || usr == 'nomina'))
		return true;
	else if((dbTable == 'Trabajador_Sucursal') && usr == 'administrativo')
		return true;
	else
		return false;
*/
}

function _new(_table,obj)
{
	var _user = getUser();

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlnew = new XMLHttpRequest();

		if (xmlnew.overrideMimeType)
			xmlnew.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlnew = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlnew = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlnew.onreadystatechange = function()
	{

		if (xmlnew.readyState==4 && xmlnew.status==200 && xmlnew.responseText != '')
		{
			var params = window_params(_table);//window_params at presentation.js
			var new_div = document.createElement('div');
			document.body.appendChild(new_div);
			new_div.innerHTML = xmlnew.responseText;
			new_div.style.overflow = 'hidden';
			new_div.style.display = 'block';
			new_div.style.position = 'absolute';
			new_div.style.padding = 0;
			new_div.style.margin = 0;
			new_div.style.width = params[0] + 'px';
			new_div.style.height = params[1] + 'px';
			new_div.style.background = 'rgba(255, 255, 255, 0.90)';
			new_div.style.border = _border_width + 'px solid #555';
			new_div.style.top = parseInt((window_height  - new_div.offsetHeight) / 2) + 'px';//window_height set at presentation.js
			new_div.style.left = parseInt((window_width - new_div.offsetWidth) / 2) + 'px';//window_width set at presentation.js
			new_div.style.zIndex = _zIndex('+');//_zIndex() at general.js
			new_div.style.MozBoxShadow = "0 0 8px 8px #888";
			new_div.style.WebkitBoxShadow = "0 0 8px 8px #888";
			new_div.style.boxShadow = "0 0 8px 8px #888";
			var forms = new_div.getElementsByTagName('form');
			var form = forms[0];
			//close button
			var close_button = document.createElement('img');
			new_div.appendChild(close_button);
			close_button.src = 'icon.php?subject=close&height=' + _height;
			close_button.setAttribute('onclick','close_div(this)');//close_div() at presentation.js 
			close_button.setAttribute('title','cerrar');
			close_button.style.display = 'block';
			close_button.style.position = 'absolute';
			close_button.style.padding = 0;
			close_button.style.margin = 0;
			close_button.style.border = 'none';
			close_button.style.background = 'none';
			close_button.style.top = '0px';
			close_button.style.left = new_div.offsetWidth - _height - _border_width * 2 + 'px';
			close_button.style.cursor = 'pointer';
			close_button.style.zIndex = 1;
			//title
			var _title = document.createElement('span');
			new_div.appendChild(_title);
			_title.setAttribute('class','_title');
			_title.style.display = 'block';
			_title.style.position = 'absolute';
			_title.style.padding = 0;
			_title.style.margin = 0;
			_title.style.border = 'none';
			_title.style.background = '#555';
			_title.style.textAlign = 'left';
			_title.style.font = title_font;
			_title.style.color = '#fff';
			_title.style.top = '0px';
			_title.style.left = '0px';
			_title.style.height = _height + 'px';
			_title.style.width = new_div.offsetWidth - 2 * _border_width + 'px';

			if(_table == 'Aguinaldo')
			{
				_title.innerHTML = 'Nuevo aguinaldo';
				fit_aguinaldo(form);//fit_aguinaldo() at presentation.js
			}
			else if(_table == 'Aportacion_del_trabajador_al_fondo_de_ahorro')
			{
				_title.innerHTML = 'Nueva aportación del trabajador al fondo de ahorro';
				fit_aportacion_del_trabajador_al_fondo_de_ahorro(form);//fit_aportacion_del_trabajador_al_fondo_de_ahorro() at presentation.js
			}
			else if(_table == 'Apoderado')
			{
				_title.innerHTML = 'Nuevo apoderado';
				fit_apoderado(form);//fit_apoderado() at presentation.js
			}
			else if(_table == 'Archivo_digital')
			{

				if(obj == 'IMPORT' || obj == '_IMPORT')
					_title.innerHTML = 'Importar archivo';
				else
					_title.innerHTML = 'Nuevo archivo digital';

				fit_archivo_digital(form);//fit_archivo_digital() at presentation.js
			}
			else if(_table == 'Baja')
			{
				_title.innerHTML = 'Nueva baja';
				fit_baja(form);//fit_baja() at presentation.js
			}
			else if(_table == 'Banco')
			{
				_title.innerHTML = 'Nuevo banco';
				fit_banco(form);//fit_banco() at presentation.js
			}
			else if(_table == 'Base')
			{
				_title.innerHTML = 'Nueva base para el cálculo de la nómina';
				fit_base(form);//fit_base() at presentation.js
			}
			else if(_table == 'Empresa')
			{
				_title.innerHTML = 'Nueva empresa';
				fit_empresa(form);//fit_empresa() at presentation.js
			}
			else if(_table == 'Contrato')
			{
				_title.innerHTML = 'Nuevo contrato';
				fit_contrato(form);//fit_contrato() at presentation.js
			}
			else if(_table == 'Credito_al_salario_diario')
			{
				_title.innerHTML = 'Nuevo crédito al salario diario';
				fit_credito_al_salario(form);
			}
			else if(_table == 'Credito_al_salario_quincenal')
			{
				_title.innerHTML = 'Nuevo crédito al salario quincenal';
				fit_credito_al_salario(form);//fit_credito_al_salario() at presentation.js
			}
			else if(_table == 'Credito_al_salario_mensual')
			{
				_title.innerHTML = 'Nuevo crédito al salario mensual';
				fit_credito_al_salario(form);
			}
			else if(_table == 'Credito_al_salario_semanal')
			{
				_title.innerHTML = 'Nuevo crédito al salario semanal';
				fit_credito_al_salario(form);
			}
			else if(_table == 'Empresa_administradora')
			{
				_title.innerHTML = 'Nueva empresa administradora';
				fit_administradora(form);//fit_administradora() at presentation.js
			}
			else if(_table == 'Establecimiento')
			{
				_title.innerHTML = 'Nuevo establecimiento';
				fit_establecimiento(form);//fit_establecimiento() at presentation.js
			}
			else if(_table == 'Factor_de_descuento')
			{
				_title.innerHTML = 'Nuevo factor de descuento';
				fit_factor_de_descuento(form);//fit_factor_de_descuento() at presentation.js
			}
			else if(_table == 'Finiquito')
			{
				_title.innerHTML = 'Nuevo finiquito';
				fit_finiquito(form);//fit_finiquito() at presentation.js
			}
			else if(_table == 'Fondo_de_garantia')
			{
				_title.innerHTML = 'Nuevo fondo de garantía';
				fit_fondo_de_garantia(form);//presentation.js
			}
			else if(_table == 'Incapacidad')
			{
				_title.innerHTML = 'Nueva incapacidad';
				fit_incapacidad(form);//fit_incapacidad() at presentation.js
			}
			else if(_table == 'Instrumento_notarial')
			{
				_title.innerHTML = 'Nuevo instrumento notarial';
				fit_instrumento_notarial(form);//fit_instrumento_notarial() at presentation.js
			}
			else if(_table == 'ISR_anual')
			{
				_title.innerHTML = 'Nuevo ISR anual';
				fit_isr(form);//fit_isr() at presentation.js
			}
			else if(_table == 'ISR_diario')
			{
				_title.innerHTML = 'Nuevo ISR diario';
				fit_isr(form);//fit_isr() at presentation.js
			}
			else if(_table == 'ISR_mensual')
			{
				_title.innerHTML = 'Nuevo ISR mensual';
				fit_isr(form);//fit_isr() at presentation.js
			}
			else if(_table == 'ISR_quincenal')
			{
				_title.innerHTML = 'Nuevo ISR quincenal';
				fit_isr(form);
			}
			else if(_table == 'ISR_semanal')
			{
				_title.innerHTML = 'Nuevo ISR semanal';
				fit_isr_semanal(form);//fit_isr_semanal() at presentation.js
			}
			else if(_table == 'Logo')
			{
				_title.innerHTML = 'Editar logotipo';
				fit_logo(form);//fit_logo() at presentation.js
			}
			else if(_table == 'Monto_fijo_mensual')
			{
				_title.innerHTML = 'Nuevo monto fijo mensual';
				fit_monto_fijo_mensual(form);//fit_monto_fijo_mensual() at presentation.js
			}
			else if(_table == 'Nomina')
			{
				_title.innerHTML = 'Nueva nómina';
				fit_nomina(form);//fit_nomina() at presentation.js
			}
			else if(_table == 'Oficina')
			{
				_title.innerHTML = 'Nueva oficina';
				fit_oficina(form);//fit_oficina() at presentation.js
			}
			else if(_table == 'Pension_alimenticia')
			{
				_title.innerHTML = 'Nueva pensión alimenticia';
				fit_pension_alimenticia(form);//fit_pension_alimenticia() at presentation.js
			}
			else if(_table == 'Photo')
			{
				_title.innerHTML = 'Editar fotografía';
				fit_photo(form);//fit_photo() at presentation.js
			}
			else if(_table == 'Porcentaje_de_cuotas_IMSS')
			{
				_title.innerHTML = 'Nuevo porcentaje de cuotas IMSS';
				fit_porcentaje_de_cuotas_imss(form);//fit_porcentaje_de_cuotas_imss() at presentation.js
			}
			else if(_table == 'Porcentaje_de_descuento')
			{
				_title.innerHTML = 'Nuevo porcentaje de descuento';
				fit_porcentaje_de_descuento(form);//fit_porcentaje_de_descuento() at presentation.js
			}
			else if(_table == 'Prestamo_administradora')
			{
				_title.innerHTML = 'Nuevo préstamo de administradora';
				fit_prestamo(form);//fit_prestamo() at presentation.js
			}
			else if(_table == 'Prestamo_caja')
			{
				_title.innerHTML = 'Nuevo préstamo de caja';
				fit_prestamo(form);//fit_prestamo() at presentation.js
			}
			else if(_table == 'Prestamo_cliente')
			{
				_title.innerHTML = 'Nuevo préstamo de cliente';
				fit_prestamo(form);//fit_prestamo() at presentation.js
			}
			else if(_table == 'Prestamo_del_fondo_de_ahorro')
			{
				_title.innerHTML = 'Nuevo préstamo de del fondo de ahorro';
				fit_prestamo(form);//fit_prestamo() at presentation.js
			}
			else if(_table == 'Prima')
			{
				_title.innerHTML = 'Nueva prima';
				fit_prima(form);//fit_prima() at presentation.js
			}
			else if(_table == 'Propuesta')
			{
				_title.innerHTML = 'Nueva propuesta';
				fit_propuesta(form);//fit_propuesta() at presentation.js
			}
			else if(_table == 'Recibo_de_vacaciones')
			{
				_title.innerHTML = 'Nuevo recibo de vacaciones';
				fit_recibo_de_vacaciones(form);//fit_recibo_de_vacaciones() at presentation.js
			}
			else if(_table == 'Regimen_fiscal')
			{
				_title.innerHTML = 'Nuevo régimen fiscal';
				fit_regimen_fiscal(form);//fit_regimen_fiscal() at presentation.js
			}
			else if(_table == 'Registro_patronal')
			{
				_title.innerHTML = 'Nuevo registro patronal';
				fit_registro_patronal(form);//fit_registro_patronal() at presentation.js
			}
			else if(_table == 'Representante_legal')
			{
				_title.innerHTML = 'Nuevo representante legal';
				fit_representante_legal(form);//fit_representante_legal() at presentation.js
			}
			else if(_table == 'Retencion_FONACOT')
			{
				_title.innerHTML = 'Nueva retención FONACOT';
				fit_retencion_fonacot(form);//fit_retencion_fonacot() at presentation.js
			}
			else if(_table == 'Retencion_INFONAVIT')
			{
				_title.innerHTML = 'Nueva retención INFONAVIT';
				fit_retencion_infonavit(form);//fit_retencion_infonavit() at presentation.js
			}
			else if(_table == 'Pago_por_seguro_de_vida')
			{
				_title.innerHTML = 'Nuevo pago por seguro de vida';
				fit_pago_por_seguro_de_vida(form);//fit_pago_por_seguro_de_vida() at presentation.js
			}
			else if(_table == 'Salario_diario')
			{
				_title.innerHTML = 'Nuevo salario diario';
				fit_salario_diario(form);//fit_salario_diario() at presentation.js
			}
			else if(_table == 'Salario_minimo')
			{
				_title.innerHTML = 'Nuevo salario mínimo';
				fit_salario_minimo(form);//fit_salario_minimo() at presentation.js
			}
			else if(_table == 'Seguro_por_danos_a_la_vivienda')
			{
				_title.innerHTML = 'Nuevo Seguro por danos a la vivienda';
				fit_seguro_por_danos_a_la_vivienda(form);//fit_seguro_por_danos_a_la_vivienda() at presentation.js
			}
			else if(_table == 'Sello_digital')
			{
				_title.innerHTML = 'Nuevo sello digital';
				fit_sello_digital(form);//fit_sello_digital() at presentation.js
			}
			else if(_table == 'Servicio')
			{
				_title.innerHTML = 'Nuevo servicio';
				fit_servicio(form);//fit_servicio() at presentation.js
			}
			else if(_table == 'Servicio_adicional')
			{
				_title.innerHTML = 'Nuevo servicio adicional';
				fit_servicio_adicional(form);//fit_servicio_adicional() at presentation.js
			}
			else if(_table == 'Servicio_Empresa')
			{
				_title.innerHTML = 'Nueva asignación de empresa';
				fit_servicio_empresa(form);//fit_servicio_empresa() at presentation.js
			}
			else if(_table == 'Servicio_Registro_patronal')
			{
				_title.innerHTML = 'Nueva asignación de registro patronal';
				fit_servicio_registro_patronal(form);//fit_servicio_registro_patronal() at presentation.js
			}
			else if(_table == 'Servicio_Trabajador')
			{
				_title.innerHTML = 'Nuevo servicio para el trabajador';
				fit_servicio_trabajador(form);//fit_servicio_trabajador() at presentation.js
			}
			else if(_table == 'Sign')
			{
				_title.innerHTML = 'Editar firma';
				fit_sign(form);//fit_sign() at presentation.js
			}
			else if(_table == 'Socio')
			{
				_title.innerHTML = 'Nuevo socio';
				fit_socio(form);//fit_socio() at presentation.js
			}
			else if(_table == 'Sucursal')
			{
				_title.innerHTML = 'Nueva sucursal';
				fit_sucursal(form);//fit_sucursal() at presentation.js
			}
			else if(_table == 'Tipo')
			{
				_title.innerHTML = 'Nuevo tipo de trabajador';
				fit_tipo(form);//fit_tipo() at presentation.js
			}
			else if(_table == 'Trabajador')
			{
				_title.innerHTML = 'Nuevo trabajador';
				fit_trabajador(form);//fit_trabajador() at presentation.js
			}
			else if(_table == 'Trabajador_Sucursal')
			{
				_title.innerHTML = 'Nueva sucursal para el trabajador';
				fit_trabajador_sucursal(form);//fit_trabajador_sucursal() at presentation.js
			}
			else if(_table == 'Trabajador_Salario_minimo')
			{
				_title.innerHTML = 'Nuevo salario mínimo para el trabajador';
				fit_trabajador_salario_minimo(form);//fit_trabajador_salario_minimo() at presentation.js
			}
			else if(_table == 'UMF')
			{
				_title.innerHTML = 'Nueva UMF';
				fit_umf(form);//fit_umf() at presentation.js
			}
			else if(_table == 'Usuario')
			{
				_title.innerHTML = 'Nuevo usuario';
				fit_usuario(form);//fit_usuario() at presentation.js
			}
			else if(_table == 'Vacaciones')
			{
				_title.innerHTML = 'Nuevas vacaciones';
				fit_vacaciones(form);//fit_vacaciones() at presentation.js
			}

			//images
			var images = new_div.getElementsByTagName('IMG');

			for(var i=0; i<images.length; i++)

				if(images[i].getAttribute('class') == 'submit_button')
				{
					var submit_button = images[i];
					submit_button.src = 'icon.php?subject=submit&height=' + _height;
					submit_button.style.display = 'block';
					submit_button.style.position = 'absolute';
					submit_button.style.padding = 0;
					submit_button.style.margin = 0;
					submit_button.style.border = 'none';
					submit_button.style.background = 'none';
					submit_button.style.top = new_div.offsetHeight - form.offsetTop - _height - _border_width * 4 + 'px';
					submit_button.style.zIndex = 1;
					submit_button.style.cursor = 'pointer';
					submit_button.style.left = new_div.offsetWidth - form.offsetLeft - _height - _border_width * 4 + 'px';
				}
				else if(images[i].getAttribute('class') == 'calendar_button')
				{
					images[i].style.display = 'block';
					images[i].style.position = 'absolute';
					images[i].style.padding = 0;
					images[i].style.margin = 0;
					images[i].style.border = 'none';
					images[i].style.background = 'none';
					images[i].style.top = images[i].previousSibling.offsetTop + 'px';
					images[i].style.left = images[i].previousSibling.offsetLeft + images[i].previousSibling.offsetWidth + 2 + 'px';
					images[i].style.cursor = 'pointer';
					images[i].src = 'icon.php?subject=calendar&height=' + images[i].previousSibling.offsetHeight;
				}

		}

	}

	var _iframe = getIframe();

	if(_table == 'Archivo_digital')
	{

		if(typeof obj != 'string')
		{
			var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
			var fieldsets = form.getElementsByTagName('fieldset');

			for(var i=0; i<fieldsets.length; i++)

				if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
				{
					var fieldset = fieldsets[i];
					break;
				}

			var textareas = fieldset.getElementsByTagName('textarea');

			for(var i=0; i<textareas.length; i++)

				if(textareas[i].getAttribute('class') == 'rfc_textarea')
				{
					var textarea = textareas[i];
					break;
				}

			var _owner = textarea.getAttribute('owner');
			xmlnew.open('POST','new.php?subject=' + escape(_table) + '&_iframe=' + _iframe + '&mode=ADD' + (_owner == 'Empresa' ? '&empresa=' : '&trabajador=') + textarea.value, true);
		}
		else
			xmlnew.open('POST','new.php?subject=' + escape(_table) + '&_iframe=' + _iframe + '&mode=' + obj, true);

	}
	else if(_table == 'Sello_digital')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var _type = 'Empresa';
				var textarea = textareas[i];
				break;
			}

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('name') == 'Empresa')
			{
				var _type = 'Sucursal';
				var textarea = textareas[i];
				break;
			}

		if(_type == 'Empresa')
			xmlnew.open('POST','new.php?subject=' + escape(_table) + '&_iframe=' + _iframe + '&mode=ADD' + '&empresa=' + textarea.value, true);
		else
		{
			var _empresa = textarea.value;

			for(var i=0; i<textareas.length; i++)

				if(textareas[i].getAttribute('class') == 'nombre_textarea')
				{
					var textarea = textareas[i];
					break;
				}

			xmlnew.open('POST','new.php?subject=' + escape(_table) + '&_iframe=' + _iframe + '&mode=ADD' + '&empresa_sucursal=' + _empresa + '&sucursal=' + textarea.value, true);
		}

	}
	else if(_table == 'Logo')
	{
		var form = obj.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var _type = 'Empresa';
				var textarea = textareas[i];
				break;
			}

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('name') == 'Empresa')
			{
				var _type = 'Sucursal';
				var textarea = textareas[i];
				break;
			}

		if(_type == 'Empresa')
		{
			xmlnew.open('POST','new.php?subject=' + escape(_table) + '&_iframe=' + _iframe + '&mode=ADD' + '&empresa=' + textarea.value, true);
		}
		else
		{
			var _empresa = textarea.value;

			for(var i=0; i<textareas.length; i++)

				if(textareas[i].getAttribute('class') == 'nombre_textarea')
				{
					var textarea = textareas[i];
					break;
				}

			xmlnew.open('POST','new.php?subject=' + escape(_table) + '&_iframe=' + _iframe + '&mode=ADD' + '&empresa_sucursal=' + _empresa + '&sucursal=' + textarea.value, true);
		}

	}
	else if(_table == 'Propuesta')
	{
		xmlnew.open('POST','new.php?subject=' + escape(_table) + '&_iframe=' + _iframe + '&mode=ADD', true);
	}
	else if(_table == 'Photo')
	{
		var _width_ = mm2px(25);//mm2px at general.js
		var _height_ = mm2px(30);
		xmlnew.open('POST','new.php?subject=' + escape(_table) + '&_iframe=' + _iframe + '&mode=ADD' + '&trabajador=' + obj.getAttribute('trabajador') + '&width=' + _width_ + '&height=' + _height_, true);
	}
	else if(_table == 'Sign')
	{

		if(obj.getAttribute('trabajador'))
			xmlnew.open('POST','new.php?subject=' + escape(_table) + '&_iframe=' + _iframe + '&mode=ADD' + '&trabajador=' + obj.getAttribute('trabajador'), true);
		else
			xmlnew.open('POST','new.php?subject=' + escape(_table) + '&_iframe=' + _iframe + '&mode=ADD' + '&usuario=' + obj.getAttribute('usuario'), true);

	}
	else if(_table == 'Aportacion_del_trabajador_al_fondo_de_ahorro' || _table == 'Incapacidad' || _table == 'Vacaciones' || _table == 'Pension_alimenticia' || _table == 'Prestamo_administradora' || _table == 'Prestamo_caja' || _table == 'Prestamo_cliente' || _table == 'Prestamo_del_fondo_de_ahorro' || _table == 'Retencion_FONACOT' || _table == 'Retencion_INFONAVIT' || _table == 'Pago_por_seguro_de_vida' || _table == 'Salario_diario' || _table == 'Servicio_Trabajador' || _table == 'Trabajador_Sucursal' || _table == 'Trabajador_Salario_minimo' || _table == 'Baja' || _table == 'Contrato' || _table == 'Banco' || _table == 'UMF' || _table == 'Tipo' || _table == 'Base' || _table == 'Fondo_de_garantia')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		xmlnew.open('POST','new.php?subject=' + escape(_table) + '&trabajador=' + escape(textarea.value), true);
	}
	else if(_table == 'Factor_de_descuento' || _table == 'Porcentaje_de_descuento' || _table == 'Monto_fijo_mensual')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('name') == 'id')
			{
				var textarea = textareas[i];
				break;
			}

		xmlnew.open('POST','new.php?subject=' + _table + '&retencion_INFONAVIT=' + textarea.value, true);
	}
	else if(_table == 'Prima' || _table == 'Representante_legal' || _table == 'Socio' || _table == 'Apoderado' || _table == 'Instrumento_notarial' || _table == 'Regimen_fiscal' || _table == 'Registro_patronal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var _type = 'Empresa';
				var textarea = textareas[i];
				break;
			}

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('name') == 'Empresa')
			{
				var _type = 'Sucursal';
				var textarea = textareas[i];
				break;
			}

		if(_type == 'Empresa')
			xmlnew.open('POST','new.php?subject=' + escape(_table) + '&empresa=' + textarea.value, true);
		else
		{
			var _empresa = textarea.value;

			for(var i=0; i<textareas.length; i++)

				if(textareas[i].getAttribute('class') == 'nombre_textarea')
				{
					var textarea = textareas[i];
					break;
				}

			xmlnew.open('POST','new.php?subject=' + escape(_table) + '&empresa_sucursal=' + _empresa + '&sucursal=' + textarea.value, true);
		}

	}
	else if(_table == 'Servicio_adicional')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('name') == 'id')
			{
				var textarea = textareas[i];
				break;
			}

		xmlnew.open('POST','new.php?subject=' + escape(_table) + '&servicio=' + textarea.value, true);
	}
	else if(_table == 'Servicio_Empresa' || _table == 'Servicio_Registro_patronal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('name') == 'id')
			{
				var textarea = textareas[i];
				break;
			}

		xmlnew.open('POST','new.php?subject=' + escape(_table) + '&servicio=' + textarea.value, true);
	}
	else if(_table == 'Sucursal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		xmlnew.open('POST','new.php?subject=' + escape(_table) + '&empresa=' + textarea.value, true);
	}
	else if(_table == 'Usuario' && _user != 'jonathan')
	{
		_alert('Permiso denegado');
		return;
	}
	else
		xmlnew.open('POST','new.php?subject=' + escape(_table) , true);

	xmlnew.setRequestHeader("cache-control","no-cache");

	if(_table == 'Prevision_social')
	{
		_alert('No puede crear otra previsión social');
		return;
	}

	xmlnew.send('');
}

function _view(_td,_table)
{
	var _user = getUser();

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlview = new XMLHttpRequest();

		if (xmlview.overrideMimeType)
			xmlview.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlview = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlview = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlview.onreadystatechange = function()
	{

		if (xmlview.readyState==4 && xmlview.status==200 && xmlview.responseText != '')
		{
			var params = window_params(_table);//window_params at presentation.js
			var view_div = document.createElement('div');
			document.body.appendChild(view_div);
			view_div.innerHTML = xmlview.responseText;
			view_div.style.overflow = 'hidden';
			view_div.style.width = params[0] + 'px';
			view_div.style.height = params[1] + 'px';
			view_div.style.display = 'block';
			view_div.style.position = 'absolute';
			view_div.style.padding = 0;
			view_div.style.margin = 0;
			view_div.style.background = 'rgba(255, 255, 255, 0.90)';
			view_div.style.border = _border_width + 'px solid #555';
			view_div.style.top = parseInt((window_height - view_div.offsetHeight) / 2) + 'px';//window_height set at presentation.js
			view_div.style.left = parseInt((window_width - view_div.offsetWidth) / 2) + 'px';//window_width set at presentation.js
			view_div.style.zIndex = _zIndex('+');//_zIndex() at general.js
			view_div.style.MozBoxShadow = "0 0 8px 8px #888";
			view_div.style.WebkitBoxShadow = "0 0 5px 5px #888";
			view_div.style.boxShadow = "0 0 5px 5px #888";
			//close button
			var close_button = document.createElement('img');
			view_div.appendChild(close_button);
			close_button.src = 'icon.php?subject=close&height=' + _height;
			close_button.setAttribute('onclick','close_div(this)');//close_div() at presentation.js 
			close_button.setAttribute('title','cerrar');
			close_button.style.display = 'block';
			close_button.style.position = 'absolute';
			close_button.style.padding = 0;
			close_button.style.margin = 0;
			close_button.style.border = 'none';
			close_button.style.background = 'none';
			close_button.style.top = '0px';
			close_button.style.left = view_div.offsetWidth - _height - _border_width * 2 + 'px';
			close_button.style.cursor = 'pointer';
			close_button.style.zIndex = 1;
			//title
			var _title = document.createElement('span');
			view_div.appendChild(_title);
			_title.style.display = 'block';
			_title.style.position = 'absolute';
			_title.style.padding = 0;
			_title.style.margin = 0;
			_title.style.border = 'none';
			_title.style.background = '#555';
			_title.style.textAlign = 'left';
			_title.style.font = title_font;
			_title.style.color = '#fff';
			_title.style.top = '0px';
			_title.style.left = '0px';
			_title.style.height = _height + 'px';
			_title.style.width = view_div.offsetWidth - 2 * _border_width + 'px';
			var forms = view_div.getElementsByTagName('form');
			var form = forms[0];

			if(_table == 'Aguinaldo')
			{
				_title.innerHTML = 'Ver aguinaldo';
				fit_aguinaldo(form);//fit_aguinaldo() at presentation.js
			}
			else if(_table == 'Aportacion_del_trabajador_al_fondo_de_ahorro')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Aportacion_del_trabajador_al_fondo_de_ahorro','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver aportación del trabajador al fondo de ahorro';
				fit_aportacion_del_trabajador_al_fondo_de_ahorro(form);//fit_aportacion_del_trabajador_al_fondo_de_ahorro() at presentation.js
			}
			else if(_table == 'Apoderado')
			{
				_title.innerHTML = 'Ver apoderado';
				fit_apoderado(form);//fit_apoderado() at presentation.js
			}
			else if(_table == 'Archivo_digital')
			{
				_title.innerHTML = 'Ver archivo digital';
				fit_archivo_digital(form);//fit_archivo_digital() at presentation.js
			}
			else if(_table == 'Baja')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Baja','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver baja';
				fit_baja(form);//fit_baja() at presentation.js
			}
			else if(_table == 'Banco')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Banco','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver banco';
				fit_banco(form);//fit_banco() at presentation.js
			}
			else if(_table == 'Base')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Base','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver base para el cálculo de la nómina';
				fit_base(form);//fit_base() at presentation.js
			}
			else if(_table == 'Credito_al_salario_diario')
			{
				_title.innerHTML = 'Ver crédito al salario diario';
				fit_credito_al_salario(form);//fit_credito_al_salario() at presentation.js
			}
			else if(_table == 'CFDI_Trabajador')
			{
				_title.innerHTML = 'Ver CFDI';
				fit_cfdi(form);//fit_cfdi() at presentation.js
			}
			else if(_table == 'Credito_al_salario_quincenal')
			{
				_title.innerHTML = 'Ver crédito al salario quincenal';
				fit_credito_al_salario(form);//fit_credito_al_salario() at presentation.js
			}
			else if(_table == 'Credito_al_salario_semanal')
			{
				_title.innerHTML = 'Ver crédito al salario semanal';
				fit_credito_al_salario(form);
			}
			else if(_table == 'Credito_al_salario_mensual')
			{
				_title.innerHTML = 'Ver crédito al salario mensual';
				fit_credito_al_salario(form);
			}
			else if(_table == 'Descuento_pendiente')
			{
				_title.innerHTML = 'Ver descuento pendiente';
				fit_descuento_pendiente(form);//fit_descuento_pendiente() at presentation.js
			}
			else if(_table == 'Contrato')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Contrato','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver contrato';
				fit_contrato(form);//fit_contrato() at presentation.js
			}
			else if(_table == 'Empresa')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Registro_patronal_fieldset')
						get_options('DRAW','Empresa','Registro_patronal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Prima_fieldset')
						get_options('DRAW','Empresa','Prima',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Representante_legal_fieldset')
						get_options('DRAW','Empresa','Representante_legal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Socios_fieldset')
						get_options('DRAW','Empresa','Socio',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Apoderados_fieldset')
						get_options('DRAW','Empresa','Apoderado',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Instrumento_notarial_fieldset')
						get_options('DRAW','Empresa','Instrumento_notarial',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Regimen_fiscal_fieldset')
						get_options('DRAW','Empresa','Regimen_fiscal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Sucursales_fieldset')
						get_options('DRAW','Empresa','Sucursal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Archivo_digital_fieldset')
						get_options('DRAW','Empresa','Archivo_digital',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Sello_digital_fieldset')
						get_options('DRAW','Empresa','Sello_digital',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver empresa';
				fit_empresa(form);//fit_empresa() at presentation.js
			}
			else if(_table == 'Establecimiento')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)

					if(fieldsets[i].getAttribute('class') == 'Empresa_administradora_fieldset')
						get_options('DRAW','Establecimiento','Empresa_administradora',obj.cells[1].innerHTML,'First',fieldsets[i]);

				_title.innerHTML = 'Ver establecimiento';
				fit_establecimiento(form);//fit_establecimiento() at presentation.js
			}
			else if(_table == 'Factor_de_descuento')
			{
				_title.innerHTML = 'Ver factor de descuento';
				fit_factor_de_descuento(form);//fit_factor_de_descuento() at presentation.js
			}
			else if(_table == 'Finiquito')
			{
				_title.innerHTML = 'Ver finiquito';
				fit_finiquito(form);//fit_finiquito() at presentation.js
			}
			else if(_table == 'Fondo_de_garantia')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Fondo_de_garantia','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver fondo de garantia';
				fit_fondo_de_garantia(form);//presentation.js
			}
			else if(_table == 'Incapacidad')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Incapacidad','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver incapacidad';
				fit_incapacidad(form);//fit_incapacidad() at presentation.js
			}
			else if(_table == 'Instrumento_notarial')
			{
				_title.innerHTML = 'Ver instrumento notarial';
				fit_instrumento_notarial(form);//fit_instrumento_notarial() at presentation.js
			}
			else if(_table == 'ISR_anual')
			{
				_title.innerHTML = 'Ver ISR anual';
				fit_isr(form);//fit_isr() at presentation.js
			}
			else if(_table == 'ISR_diario')
			{
				_title.innerHTML = 'Ver ISR diario';
				fit_isr(form);//fit_isr() at presentation.js
			}
			else if(_table == 'ISR_mensual')
			{
				_title.innerHTML = 'Ver ISR mensual';
				fit_isr(form);//fit_isr() at presentation.js
			}
			else if(_table == 'ISR_quincenal')
			{
				_title.innerHTML = 'Ver ISR quincenal';
				fit_isr(form);
			}
			else if(_table == 'ISR_semanal')
			{
				_title.innerHTML = 'Ver ISR semanal';
				fit_isr(form);
			}
			else if(_table == 'Monto_fijo_mensual')
			{
				_title.innerHTML = 'Ver monto fijo mensual';
				fit_monto_fijo_mensual(form);//fit_monto_fijo_mensual() at presentation.js
			}
			else if(_table == 'Nomina')
			{
				_title.innerHTML = 'Ver nómina';
				fit_nomina(form);//fit_nomina() at presentation.js
			}
			else if(_table == 'Nomina_propuesta')
			{
				_title.innerHTML = 'Ver nómina';
				fit_nomina_propuesta(form);//fit_nomina_propuesta() at presentation.js
			}
			else if(_table == 'Oficina')
			{
				_title.innerHTML = 'Ver oficina';
				fit_oficina(form);//fit_oficina() at presentation.js
			}
			else if(_table == 'Pago_por_seguro_de_vida')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Pago_por_seguro_de_vida','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver pago por seguro de vida';
				fit_pago_por_seguro_de_vida(form);//fit_pago_por_seguro_de_vida() at presentation.js
			}
			else if(_table == 'Pension_alimenticia')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Pension_alimenticia','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver pensión alimenticia';
				fit_pension_alimenticia(form);//fit_pension_alimenticia() at presentation.js
			}
			else if(_table == 'Porcentaje_de_descuento')
			{
				_title.innerHTML = 'Ver porcentaje de descuento';
				fit_porcentaje_de_descuento(form);//fit_porcentaje_de_descuento() at presentation.js
			}
			else if(_table == 'Porcentaje_de_cuotas_IMSS')
			{
				_title.innerHTML = 'Ver porcentaje de cuotas IMSS';
				fit_porcentaje_de_cuotas_imss(form);//fit_porcentaje_de_cuotas_imss() at presentation.js
			}
			else if(_table == 'Prestamo_administradora')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Prestamo_administradora','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver préstamo de administradora';
				fit_prestamo(form);//fit_prestamo() at presentation.js
			}
			else if(_table == 'Prestamo_caja')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Prestamo_caja','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver préstamo de caja';
				fit_prestamo(form);//fit_prestamo() at presentation.js
			}
			else if(_table == 'Prestamo_cliente')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Prestamo_cliente','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver préstamo de cliente';
				fit_prestamo(form);//fit_prestamo() at presentation.js
			}
			else if(_table == 'Prestamo_del_fondo_de_ahorro')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Prestamo_del_fondo_de_ahorro','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver préstamo del fondo de ahorro';
				fit_prestamo(form);//fit_prestamo() at presentation.js
			}
			else if(_table == 'Prevision_social')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicios_fieldset')
						get_options('DRAW','Prevision_social','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver previsión social';
				fit_prevision_social(form);//fit_prevision_social() at presentation.js
			}
			else if(_table == 'Prima')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Registro_patronal_fieldset')
						get_options('DRAW','Prima','Registro_patronal',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}
				_title.innerHTML = 'Ver prima';
				fit_prima(form);//fit_prima() at presentation.js
			}
			else if(_table == 'Propuesta')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Registro_patronal_fieldset')
						get_options('DRAW','Propuesta','Registro_patronal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Nomina_propuesta_fieldset')
						get_options('DRAW','Propuesta','Nomina_propuesta',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver propuesta';
				fit_propuesta(form);//fit_propuesta() at presentation.js
			}
			else if(_table == 'Recibo_de_vacaciones')
			{
				_title.innerHTML = 'Ver recibo de vacaciones';
				fit_recibo_de_vacaciones(form);//fit_recibo_de_vacaciones() at presentation.js
			}
			else if(_table == 'Regimen_fiscal')
			{
				_title.innerHTML = 'Ver régimen fiscal';
				fit_regimen_fiscal(form);//fit_regimen_fiscal() at presentation.js
			}
			else if(_table == 'Registro_patronal')
			{
				_title.innerHTML = 'Ver registro patronal';
				fit_registro_patronal(form);//fit_registro_patronal() at presentation.js
			}
			else if(_table == 'Representante_legal')
			{
				_title.innerHTML = 'Ver representante legal';
				fit_representante_legal(form);//fit_representante_legal() at presentation.js
			}
			else if(_table == 'Retencion_FONACOT')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Retencion_FONACOT','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver retención FONACOT';
				fit_retencion_fonacot(form);//fit_retencion_fonacot() at presentation.js
			}
			else if(_table == 'Retencion_INFONAVIT')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Factor_de_descuento_fieldset')
						get_options('DRAW','Retencion_INFONAVIT','Factor_de_descuento',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Porcentaje_de_descuento_fieldset')
						get_options('DRAW','Retencion_INFONAVIT','Porcentaje_de_descuento',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Monto_fijo_mensual_fieldset')
						get_options('DRAW','Retencion_INFONAVIT','Monto_fijo_mensual',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Retencion_INFONAVIT','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver retención INFONAVIT';
				fit_retencion_infonavit(form);//fit_retencion_infonavit() at presentation.js
			}
			else if(_table == 'Salario_diario')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Salario_diario','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver salario diario';
				fit_salario_diario(form);//fit_salario_diario() at presentation.js
			}
			else if(_table == 'Salario_minimo')
			{
				_title.innerHTML = 'Ver salario mínimo';
				fit_salario_minimo(form);//fit_salario_minimo() at presentation.js
			}
			else if(_table == 'Salario_real')
			{
				_title.innerHTML = 'Ver salario real';
				fit_salario_real(form);//fit_salario_real() at presentation.js
			}
			else if(_table == 'Seguro_por_danos_a_la_vivienda')
			{
				_title.innerHTML = 'Ver seguro por danos a la vivienda';
				fit_seguro_por_danos_a_la_vivienda(form);//fit_seguro_por_danos_a_la_vivienda() at presentation.js
			}
			else if(_table == 'Sello_digital')
			{
				_title.innerHTML = 'Ver sello digital';
				fit_sello_digital(form);//fit_sello_digital() at presentation.js
			}
			else if(_table == 'Servicio')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Empresa_fieldset')
						get_options('DRAW','Servicio','Servicio_Empresa',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Registro_patronal_fieldset')
						get_options('DRAW','Servicio','Servicio_Registro_patronal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Trabajadores_fieldset')
						get_options('DRAW','Servicio','Trabajador',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Servicios_adicionales_fieldset')
						get_options('DRAW','Servicio','Servicio_adicional',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver servicio';
				fit_servicio(form);//fit_servicio() at presentation.js
			}
			else if(_table == 'Servicio_adicional')
			{
				_title.innerHTML = 'Ver servicio adicional';
				fit_servicio_adicional(form);//fit_servicio_adicional() at presentation.js
			}
			else if(_table == 'Servicio_Empresa')
			{
				var obj = _td.parentNode;
				var fieldsets = form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Empresa_fieldset')
						get_options('DRAW','Servicio_Empresa','Empresa', obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver asignación de empresa';
				fit_servicio_empresa(form);//fit_servicio_empresa() at presentation.js
			}
			else if(_table == 'Servicio_Registro_patronal')
			{
				var obj = _td.parentNode;
				var fieldsets = form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Registro_patronal_fieldset')
						get_options('DRAW','Servicio_Registro_patronal','Registro_patronal', obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver asignación de registro patronal';
				fit_servicio_registro_patronal(form);//fit_servicio_registro_patronal() at presentation.js
			}
			else if(_table == 'Servicio_Trabajador')
			{
				var obj = _td.parentNode;
				var _form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
				var fieldsets = _form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)

					if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
					{
						var _datos = fieldsets[i];
						break;
					}

				var textareas = _datos.getElementsByTagName('textarea');

				for(var i=0; i<textareas.length; i++)

					if(textareas[i].getAttribute('class') == 'rfc_textarea')
					{
						var _trabajador = textareas[i].value;
						break;
					}

				var fieldsets = form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Servicio_Trabajador','Servicio',obj.cells[1].innerHTML +','+_trabajador,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver servicio del trabajador';
				fit_servicio_trabajador(form);//fit_servicio_trabajador() at presentation.js
			}
			else if(_table == 'Socio')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;
				_title.innerHTML = 'Ver socio';
				fit_socio(form);//fit_socio() at presentation.js
			}
			else if(_table == 'Sucursal')
			{
				var fieldsets = form.getElementsByTagName('fieldset');

				//Getting Empresa to fully identify Registro patronal
				for(var i=0; i<fieldsets.length; i++)

					if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
					{
						var fieldset = fieldsets[i];
						break;
					}

				var textareas = fieldset.getElementsByTagName('textarea');

				for(var i=0; i<textareas.length; i++)

					if(textareas[i].getAttribute('name') == 'Empresa')
					{
						var textarea = textareas[i];
						break;
					}

				var _empresa = textarea.value;
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Registro_patronal_fieldset')
						get_options('DRAW','Sucursal','Registro_patronal',obj.cells[1].innerHTML + '>>' + _empresa,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Sello_digital_fieldset')
						get_options('DRAW','Sucursal','Sello_digital',obj.cells[1].innerHTML + '>>' + _empresa,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver sucursal';
				fit_sucursal(form);//fit_sucursal() at presentation.js
			}
			else if(_table == 'Tabla')
			{
				_title.innerHTML = 'Ver tabla';
				fit_tabla(form);//fit_tabla() at presentation.js
			}
			else if(_table == 'Tipo')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Tipo','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver tipo de trabajador';
				fit_tipo(form);//fit_tipo() at presentation.js
			}
			else if(_table == 'Trabajador')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Tipo_fieldset')
						get_options('DRAW','Trabajador','Tipo',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Base_fieldset')
						get_options('DRAW','Trabajador','Base',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Contrato_fieldset')
						get_options('DRAW','Trabajador','Contrato',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Banco_fieldset')
						get_options('DRAW','Trabajador','Banco',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'UMF_fieldset')
						get_options('DRAW','Trabajador','UMF',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Aportacion_del_trabajador_al_fondo_de_ahorro_fieldset')
						get_options('DRAW','Trabajador','Aportacion_del_trabajador_al_fondo_de_ahorro',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Incapacidad_fieldset')
						get_options('DRAW','Trabajador','Incapacidad',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Vacaciones_fieldset')
						get_options('DRAW','Trabajador','Vacaciones',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Pension_alimenticia_fieldset')
						get_options('DRAW','Trabajador','Pension_alimenticia',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Prestamo_de_administradora_fieldset')
						get_options('DRAW','Trabajador','Prestamo_administradora',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Prestamo_de_caja_fieldset')
						get_options('DRAW','Trabajador','Prestamo_caja',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Prestamo_de_cliente_fieldset')
						get_options('DRAW','Trabajador','Prestamo_cliente',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Prestamo_del_fondo_de_ahorro_fieldset')
						get_options('DRAW','Trabajador','Prestamo_del_fondo_de_ahorro',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Fondo_de_garantia_fieldset')
						get_options('DRAW','Trabajador','Fondo_de_garantia',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'FONACOT_fieldset')
						get_options('DRAW','Trabajador','Retencion_FONACOT',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'INFONAVIT_fieldset')
						get_options('DRAW','Trabajador','Retencion_INFONAVIT',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Pago_por_seguro_de_vida_fieldset')
						get_options('DRAW','Trabajador','Pago_por_seguro_de_vida',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Salario_diario_fieldset')
						get_options('DRAW','Trabajador','Salario_diario',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Salario_minimo_fieldset')
						get_options('DRAW','Trabajador','Trabajador_Salario_minimo',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Archivo_digital_fieldset')
						get_options('DRAW','Trabajador','Archivo_digital',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Servicios_fieldset')
						get_options('DRAW','Trabajador','Servicio_Trabajador',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Sucursales_fieldset')
						get_options('DRAW','Trabajador','Trabajador_Sucursal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Bajas_fieldset')
						get_options('DRAW','Trabajador','Baja',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Descuentos_pendientes_fieldset')
						get_options('DRAW','Trabajador','Descuento_pendiente',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver trabajador';
				fit_trabajador(form);//fit_trabajador() at presentation.js
			}
			else if(_table == 'Trabajador_Salario_minimo')
			{
				var obj = _td.parentNode;
				var _form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
				var fieldsets = _form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)

					if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
					{
						var _datos = fieldsets[i];
						break;
					}

				var textareas = _datos.getElementsByTagName('textarea');

				for(var i=0; i<textareas.length; i++)

					if(textareas[i].getAttribute('class') == 'rfc_textarea')
					{
						var _trabajador = textareas[i].value;
						break;
					}

				var fieldsets = form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Trabajador_Salario_minimo','Servicio',_trabajador+','+obj.cells[3].innerHTML+','+obj.cells[4].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver salario mínimo del trabajador';
				fit_trabajador_salario_minimo(form);//fit_trabajador_salario_minimo() at presentation.js
			}
			else if(_table == 'Trabajador_Sucursal')
			{
				var obj = _td.parentNode;
				var _form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
				var fieldsets = _form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)

					if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
					{
						var _datos = fieldsets[i];
						break;
					}

				var textareas = _datos.getElementsByTagName('textarea');

				for(var i=0; i<textareas.length; i++)

					if(textareas[i].getAttribute('class') == 'rfc_textarea')
					{
						var _trabajador = textareas[i].value;
						break;
					}

				var fieldsets = form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Trabajador_Sucursal','Servicio',_trabajador+','+obj.cells[1].innerHTML+','+obj.cells[2].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver sucursal del trabajador';
				fit_trabajador_sucursal(form);//fit_trabajador_sucursal() at presentation.js
			}
			else if(_table == 'UMF')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','UMF','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver UMF';
				fit_umf(form);//fit_umf() at presentation.js
			}
			else if(_table == 'Usuario')
			{
				_title.innerHTML = 'Ver usuario';
				fit_usuario(form);//fit_usuario() at presentation.js
			}
			else if(_table == 'Vacaciones')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('DRAW','Vacaciones','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver vacaciones';
				fit_vacaciones(form);//fit_vacaciones() at presentation.js
			}

		}

	}

	var obj = _td.parentNode;
	var id = getID(_table);

	if(_table == 'Servicio_adicional')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('name') == 'id')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&' + id + '=' + obj.cells[1].innerHTML + '&servicio=' + textarea.value;
	}
	else if(_table == 'Servicio_Empresa' || _table == 'Servicio_Registro_patronal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('name') == 'id')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + textarea.value + ',' + obj.cells[1].innerHTML;
	}
	else if(_table == 'Servicio_Trabajador')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + obj.cells[1].innerHTML + ',' + textarea.value;
	}
	else if(_table == 'Representante_legal' || _table == 'Socio' || _table == 'Apoderado' || _table == 'Archivo_digital' || _table == 'Instrumento_notarial' || _table == 'Regimen_fiscal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var _owner = textarea.getAttribute('owner');
		var params = 'subject=' + _table + '&' + id + '=' + obj.cells[1].innerHTML + (_owner == 'Empresa' ? '&empresa=' : '&trabajador=') + textarea.value;
	}
	else if(_table == 'Sucursal')
	{
		var tables = obj.parentNode.parentNode.parentNode.parentNode.getElementsByTagName('table');

		if(tables[0].rows[0].cells.length > 2)
			var _empresa = obj.cells[2].innerHTML;
		else
		{
			var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
			var fieldsets = form.getElementsByTagName('fieldset');

			for(var i=0; i<fieldsets.length; i++)

				if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
				{
					var fieldset = fieldsets[i];
					break;
				}

			var textareas = fieldset.getElementsByTagName('textarea');

			for(var i=0; i<textareas.length; i++)

				if(textareas[i].getAttribute('class') == 'rfc_textarea')
				{
					var textarea = textareas[i];
					break;
				}

			var _empresa = textarea.value;
		}

		var params = 'subject=' + _table + '&' + id + '=' + obj.cells[1].innerHTML + '&empresa=' + _empresa;
	}
	else if(_table == 'Trabajador_Salario_minimo')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + textarea.value + ',' + obj.cells[3].innerHTML + ',' + obj.cells[4].innerHTML;
	}
	else if(_table == 'Trabajador_Sucursal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + textarea.value + ',' + obj.cells[1].innerHTML + ',' + obj.cells[2].innerHTML + ',' + obj.cells[4].innerHTML;
	}
	else if(_table == 'Descuento_pendiente')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + obj.cells[1].innerHTML + ',' + obj.cells[2].innerHTML + ',' + obj.cells[3].innerHTML + ',' + textarea.value;
	}
	else if(_table == 'Porcentaje_de_cuotas_IMSS')
		var params = 'subject=' + _table + '&id=' + obj.cells[1].innerHTML + ',' + obj.cells[2].innerHTML;
	else
		var params = 'subject=' + _table + '&' + id + '=' + obj.cells[1].innerHTML;

	xmlview.open('POST','view_entity.php',true);
	xmlview.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmlview.setRequestHeader("Content-length", params.length);
	xmlview.setRequestHeader("Connection", "close");
	xmlview.setRequestHeader("cache-control","no-cache");
	xmlview.send(params);
}

function _edit(_td,_table)
{
	var _user = getUser();

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmledit = new XMLHttpRequest();

		if (xmledit.overrideMimeType)
			xmledit.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmledit = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmledit = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmledit.onreadystatechange = function()
	{

		if (xmledit.readyState==4 && xmledit.status==200 && xmledit.responseText != '')
		{
			var params = window_params(_table);//window_params at presentation.js
			var edit_div = document.createElement('div');
			document.body.appendChild(edit_div);
			edit_div.innerHTML = xmledit.responseText;
			var forms = edit_div.getElementsByTagName('form');
			var form = forms[0];
			edit_div.style.overflow = 'hidden';
			edit_div.style.display = 'block';
			edit_div.style.position = 'absolute';
			edit_div.style.padding = 0;
			edit_div.style.margin = 0;
			edit_div.style.width = params[0] + 'px';
			edit_div.style.height = params[1] + 'px';
			edit_div.style.background = 'rgba(255, 255, 255, 0.90)';
			edit_div.style.border = _border_width + 'px solid #555';
			edit_div.style.top = parseInt((window_height - edit_div.offsetHeight) / 2) + 'px';//window_height set at presentation.js
			edit_div.style.left = parseInt((window_width - edit_div.offsetWidth) / 2) + 'px';//window_width set at presentation.js
			edit_div.style.zIndex = _zIndex('+');//_zIndex() at general.js
			edit_div.style.MozBoxShadow = "0 0 8px 8px #888";
			edit_div.style.WebkitBoxShadow = "0 0 8px 8px#888";
			edit_div.style.boxShadow = "0 0 8px 8px #888";
			//close button
			var close_button = document.createElement('img');
			edit_div.appendChild(close_button);
			close_button.src = 'icon.php?subject=close&height=' + _height;
			close_button.setAttribute('onclick','close_div(this)');//close_div() at presentation.js 
			close_button.setAttribute('title','cerrar');
			close_button.style.display = 'block';
			close_button.style.position = 'absolute';
			close_button.style.padding = 0;
			close_button.style.margin = 0;
			close_button.style.border = 'none';
			close_button.style.background = 'none';
			close_button.style.top = '0px';
			close_button.style.left = edit_div.offsetWidth - _height - _border_width * 2 + 'px';
			close_button.style.cursor = 'pointer';
			close_button.style.zIndex = 1;
			//title
			var _title = document.createElement('span');
			edit_div.appendChild(_title);
			_title.setAttribute('class','_title');
			_title.style.display = 'block';
			_title.style.position = 'absolute';
			_title.style.padding = 0;
			_title.style.margin = 0;
			_title.style.border = 'none';
			_title.style.background = '#555';
			_title.style.textAlign = 'left';
			_title.style.font = title_font;
			_title.style.color = '#fff';
			_title.style.top = '0px';
			_title.style.left = '0px';
			_title.style.height = _height + 'px';
			_title.style.width = edit_div.offsetWidth - 2 * _border_width + 'px';

			if(_table == 'Aguinaldo')
			{
				_title.innerHTML = 'Editar aguinaldo';
				fit_aguinaldo(form);//fit_aguinaldo() at presentation.js
			}
			else if(_table == 'Aportacion_del_trabajador_al_fondo_de_ahorro')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Aportacion_del_trabajador_al_fondo_de_ahorro','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar aportación del trabajador al fondo de ahorro';
				fit_aportacion_del_trabajador_al_fondo_de_ahorro(form);//fit_aportacion_del_trabajador_al_fondo_de_ahorro() at presentation.js
			}
			else if(_table == 'Apoderado')
			{
				_title.innerHTML = 'Editar apoderado';
				fit_apoderado(form);//fit_apoderado() at presentation.js
			}
			else if(_table == 'Archivo_digital')
			{
				_title.innerHTML = 'Ver archivo digital';
				fit_archivo_digital(form);//fit_archivo_digital() at presentation.js
			}
			else if(_table == 'Baja')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Baja','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar baja';
				fit_baja(form);//fit_baja() at presentation.js
			}
			else if(_table == 'Banco')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Banco','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar banco';
				fit_banco(form);//fit_banco() at presentation.js
			}
			else if(_table == 'Base')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Base','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar base para el cálculo de la nómina';
				fit_base(form);//fit_base() at presentation.js
			}
			else if(_table == 'Contrato')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Contrato','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar contrato';
				fit_contrato(form);//fit_contrato() at presentation.js
			}
			else if(_table == 'Credito_al_salario_diario')
			{
				_title.innerHTML = 'Editar crédito al salario diario';
				fit_credito_al_salario(form);//fit_credito_al_salario() at presentation.js
			}
			else if(_table == 'Credito_al_salario_quincenal')
			{
				_title.innerHTML = 'Editar crédito al salario quincenal';
				fit_credito_al_salario(form);//fit_credito_al_salario() at presentation.js
			}
			else if(_table == 'Credito_al_salario_mensual')
			{
				_title.innerHTML = 'Editar crédito al salario mensual';
				fit_credito_al_salario(form);
			}
			else if(_table == 'Credito_al_salario_semanal')
			{
				_title.innerHTML = 'Editar crédito al salario semanal';
				fit_credito_al_salario(form);
			}
			else if(_table == 'Descuento_pendiente')
			{
				_title.innerHTML = 'Editar descuento pendiente';
				fit_descuento_pendiente(form);//fit_descuento_pendiente() at presentation.js
			}
			else if(_table == 'Empresa')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Registro_patronal_fieldset')
						get_options('EDIT','Empresa','Registro_patronal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Prima_fieldset')
						get_options('EDIT','Empresa','Prima',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Representante_legal_fieldset')
						get_options('EDIT','Empresa','Representante_legal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Socios_fieldset')
						get_options('EDIT','Empresa','Socio',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Apoderados_fieldset')
						get_options('EDIT','Empresa','Apoderado',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Instrumento_notarial_fieldset')
						get_options('EDIT','Empresa','Instrumento_notarial',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Regimen_fiscal_fieldset')
						get_options('EDIT','Empresa','Regimen_fiscal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Sucursales_fieldset')
						get_options('EDIT','Empresa','Sucursal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Archivo_digital_fieldset')
						get_options('EDIT','Empresa','Archivo_digital',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Sello_digital_fieldset')
						get_options('EDIT','Empresa','Sello_digital',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar empresa';
				fit_empresa(form);//fit_empresa() at presentation.js
			}
			else if(_table == 'Empresa_administradora')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Representante_legal_fieldset')
						get_options('EDIT','Empresa_administradora','Representante_legal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Archivo_digital_fieldset')
						get_options('EDIT','Empresa_administradora','Archivo_digital',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar empresa administradora';
				fit_administradora(form);//fit_administradora() at presentation.js
			}
			else if(_table == 'Establecimiento')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)

					if(fieldsets[i].getAttribute('class') == 'Empresa_administradora_fieldset')
						get_options('EDIT','Establecimiento','Empresa_administradora',obj.cells[1].innerHTML,'First',fieldsets[i]);

				_title.innerHTML = 'Editar establecimiento';
				fit_establecimiento(form);//fit_establecimiento() at presentation.js
			}
			else if(_table == 'Factor_de_descuento')
			{
				_title.innerHTML = 'Editar factor de descuento';
				fit_factor_de_descuento(form);//fit_factor_de_descuento() at presentation.js
			}
			else if(_table == 'Finiquito')
			{
				_title.innerHTML = 'Editar finiquito';
				fit_finiquito(form);//fit_finiquito() at presentation.js
			}
			else if(_table == 'Fondo_de_garantia')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Fondo_de_garantia','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar fondo de garantia';
				fit_fondo_de_garantia(form);//presentation.js
			}
			else if(_table == 'Incapacidad')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Incapacidad','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar incapacidad';
				fit_incapacidad(form);//fit_incapacidad() at presentation.js
			}
			else if(_table == 'Instrumento_notarial')
			{
				_title.innerHTML = 'Editar instrumento notarial';
				fit_instrumento_notarial(form);//fit_instrumento_notarial() at presentation.js
			}
			else if(_table == 'ISR_anual')
			{
				_title.innerHTML = 'Editar ISR anual';
				fit_isr(form);//fit_isr() at presentation.js
			}
			else if(_table == 'ISR_diario')
			{
				_title.innerHTML = 'Editar ISR diario';
				fit_isr(form);//fit_isr() at presentation.js
			}
			else if(_table == 'ISR_mensual')
			{
				_title.innerHTML = 'Editar ISR mensual';
				fit_isr(form);//fit_isr() at presentation.js
			}
			else if(_table == 'ISR_quincenal')
			{
				_title.innerHTML = 'Editar ISR quincenal';
				fit_isr(form);
			}
			else if(_table == 'ISR_semanal')
			{
				_title.innerHTML = 'Editar ISR semanal';
				fit_isr(form);
			}
			else if(_table == 'Monto_fijo_mensual')
			{
				_title.innerHTML = 'Editar monto fijo mensual';
				fit_monto_fijo_mensual(form);//fit_monto_fijo_mensual() at presentation.js
			}
			else if(_table == 'Nomina')
			{
				_title.innerHTML = 'Editar nómina';
				fit_nomina(form);//fit_nomina() at presentation.js
			}
			else if(_table == 'Oficina')
			{
				_title.innerHTML = 'Editar oficina';
				fit_oficina(form);//fit_oficina() at presentation.js
			}
			else if(_table == 'Pension_alimenticia')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Pension_alimenticia','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar pension alimenticia';
				fit_pension_alimenticia(form);//fit_pension_alimenticia() at presentation.js
			}
			else if(_table == 'Porcentaje_de_cuotas_IMSS')
			{
				_title.innerHTML = 'Editar porcentaje de cuotas IMSS';
				fit_porcentaje_de_cuotas_imss(form);//fit_porcentaje_de_cuotas_imss() at presentation.js
			}
			else if(_table == 'Porcentaje_de_descuento')
			{
				_title.innerHTML = 'Editar porcentaje de descuento';
				fit_porcentaje_de_descuento(form);//fit_porcentaje_de_descuento() at presentation.js
			}
			else if(_table == 'Prestamo_administradora')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Prestamo_administradora','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar préstamo de administradora';
				fit_prestamo(form);//fit_prestamo() at presentation.js
			}
			else if(_table == 'Prestamo_caja')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Prestamo_caja','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar préstamo de caja';
				fit_prestamo(form);//fit_prestamo() at presentation.js
			}
			else if(_table == 'Prestamo_cliente')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Prestamo_cliente','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar préstamo de cliente';
				fit_prestamo(form);//fit_prestamo() at presentation.js
			}
			else if(_table == 'Prestamo_del_fondo_de_ahorro')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Prestamo_del_fondo_de_ahorro','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar préstamo del fondo de ahorro';
				fit_prestamo(form);//fit_prestamo() at presentation.js
			}
			else if(_table == 'Prevision_social')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicios_fieldset')
						get_options('EDIT','Prevision_social','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar previsión social';
				fit_prevision_social(form);//fit_prevision_social() at presentation.js
			}
			else if(_table == 'Prima')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Registro_patronal_fieldset')
						get_options('EDIT','Prima','Registro_patronal',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}
				_title.innerHTML = 'Editar prima';
				fit_prima(form);//fit_prima() at presentation.js
			}
			else if(_table == 'Propuesta')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Registro_patronal_fieldset')
						get_options('EDIT','Propuesta','Registro_patronal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Nomina_propuesta_fieldset')
						get_options('EDIT','Propuesta','Nomina_propuesta',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}
				_title.innerHTML = 'Editar propuesta';
				fit_propuesta(form);//fit_propuesta() at presentation.js
			}
			else if(_table == 'Recibo_de_vacaciones')
			{
				_title.innerHTML = 'Editar recibo de vacaciones';
				fit_recibo_de_vacaciones(form);//fit_recibo_de_vacaciones() at presentation.js
			}
			else if(_table == 'Regimen_fiscal')
			{
				_title.innerHTML = 'Editar régimen fiscal';
				fit_regimen_fiscal(form);//fit_regimen_fiscal() at presentation.js
			}
			else if(_table == 'Registro_patronal')
			{
				_title.innerHTML = 'Editar registro patronal';
				fit_registro_patronal(form);//fit_registro_patronal() at presentation.js
			}
			else if(_table == 'Representante_legal')
			{
				_title.innerHTML = 'Editar representante legal';
				fit_representante_legal(form);//fit_representante_legal() at presentation.js
			}
			else if(_table == 'Retencion_FONACOT')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Retencion_FONACOT','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar retención FONACOT';
				fit_retencion_fonacot(form);//fit_retencion_fonacot() at presentation.js
			}
			else if(_table == 'Retencion_INFONAVIT')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Factor_de_descuento_fieldset')
						get_options('EDIT','Retencion_INFONAVIT','Factor_de_descuento',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Porcentaje_de_descuento_fieldset')
						get_options('EDIT','Retencion_INFONAVIT','Porcentaje_de_descuento',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Monto_fijo_mensual_fieldset')
						get_options('EDIT','Retencion_INFONAVIT','Monto_fijo_mensual',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Retencion_INFONAVIT','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar retención INFONAVIT';
				fit_retencion_infonavit(form);//fit_retencion_infonavit() at presentation.js
			}
			else if(_table == 'Pago_por_seguro_de_vida')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Pago_por_seguro_de_vida','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar pago por seguro de vida';
				fit_pago_por_seguro_de_vida(form);//fit_pago_por_seguro_de_vida() at presentation.js
			}
			else if(_table == 'Salario_diario')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Salario_diario','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar salario diario';
				fit_salario_diario(form);//fit_salario_diario() at presentation.js
			}
			else if(_table == 'Salario_minimo')
			{
				_title.innerHTML = 'Editar salario mínimo';
				fit_salario_minimo(form);//fit_salario_minimo() at presentation.js
			}
			else if(_table == 'Salario_real')
			{
				_title.innerHTML = 'Editar salario real';
				fit_salario_real(form);//fit_salario_real() at presentation.js
			}
			else if(_table == 'Seguro_por_danos_a_la_vivienda')
			{
				_title.innerHTML = 'Editar seguro por danos a la vivienda';
				fit_seguro_por_danos_a_la_vivienda(form);//fit_seguro_por_danos_a_la_vivienda() at presentation.js
			}
			else if(_table == 'Servicio')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Empresa_fieldset')
						get_options('EDIT','Servicio','Servicio_Empresa',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Registro_patronal_fieldset')
						get_options('EDIT','Servicio','Servicio_Registro_patronal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Trabajadores_fieldset')
						get_options('EDIT','Servicio','Trabajador',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Servicios_adicionales_fieldset')
						get_options('EDIT','Servicio','Servicio_adicional',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar servicio';
				fit_servicio(form);//fit_servicio() at presentation.js
			}
			else if(_table == 'Servicio_adicional')
			{
				_title.innerHTML = 'Editar servicio adicional';
				fit_servicio_adicional(form);//fit_servicio_adicional() at presentation.js
			}
			else if(_table == 'Servicio_Empresa')
			{
				var obj = _td.parentNode;
				var fieldsets = form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Empresa_fieldset')
						get_options('EDIT','Servicio_Empresa','Empresa', obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar asignación de empresa';
				fit_servicio_empresa(form);//fit_servicio_empresa() at presentation.js
			}
			else if(_table == 'Servicio_Registro_patronal')
			{
				var obj = _td.parentNode;
				var fieldsets = form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Registro_patronal_fieldset')
						get_options('EDIT','Servicio_Registro_patronal','Registro_patronal', obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar asignación de registro patronal';
				fit_servicio_registro_patronal(form);//fit_servicio_registro_patronal() at presentation.js
			}
			else if(_table == 'Servicio_Trabajador')
			{
				var obj = _td.parentNode;
				var _form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
				var fieldsets = _form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)

					if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
					{
						var _datos = fieldsets[i];
						break;
					}

				var textareas = _datos.getElementsByTagName('textarea');

				for(var i=0; i<textareas.length; i++)

					if(textareas[i].getAttribute('class') == 'rfc_textarea')
					{
						var _trabajador = textareas[i].value;
						break;
					}

				var fieldsets = form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Servicio_Trabajador','Servicio',obj.cells[1].innerHTML +','+_trabajador,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Ver servicio del trabajador';
				fit_servicio_trabajador(form);//fit_servicio_trabajador() at presentation.js
			}
			else if(_table == 'Socio')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Empresas_administradoras_fieldset')
						get_options('EDIT','Socio','Empresa_administradora',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar socio';
				fit_socio(form);//fit_socio() at presentation.js
			}
			else if(_table == 'Sucursal')
			{
				var fieldsets = form.getElementsByTagName('fieldset');

				//Getting Empresa to fully identify Registro patronal
				for(var i=0; i<fieldsets.length; i++)

					if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
					{
						var fieldset = fieldsets[i];
						break;
					}

				var textareas = fieldset.getElementsByTagName('textarea');

				for(var i=0; i<textareas.length; i++)

					if(textareas[i].getAttribute('name') == 'Empresa')
					{
						var textarea = textareas[i];
						break;
					}

				var _empresa = textarea.value;
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Registro_patronal_fieldset')
						get_options('EDIT','Sucursal','Registro_patronal',obj.cells[1].innerHTML + '>>' + _empresa,'First',fieldsets[i]);
					if(fieldsets[i].getAttribute('class') == 'Sello_digital_fieldset')
						get_options('EDIT','Sucursal','Sello_digital',obj.cells[1].innerHTML + '>>' + _empresa,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar sucursal';
				fit_sucursal(form);//fit_sucursal() at presentation.js
			}
			else if(_table == 'Tabla')
			{
				_title.innerHTML = 'Editar tabla';
				fit_tabla(form);//fit_tabla() at presentation.js
			}
			else if(_table == 'Tipo')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Tipo','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar tipo de trabajador';
				fit_tipo(form);//fit_tipo() at presentation.js
			}
			else if(_table == 'Trabajador')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Tipo_fieldset')
						get_options('EDIT','Trabajador','Tipo',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Base_fieldset')
						get_options('EDIT','Trabajador','Base',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Contrato_fieldset')
						get_options('EDIT','Trabajador','Contrato',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Banco_fieldset')
						get_options('EDIT','Trabajador','Banco',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'UMF_fieldset')
						get_options('EDIT','Trabajador','UMF',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Aportacion_del_trabajador_al_fondo_de_ahorro_fieldset')
						get_options('EDIT','Trabajador','Aportacion_del_trabajador_al_fondo_de_ahorro',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Incapacidad_fieldset')
						get_options('EDIT','Trabajador','Incapacidad',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Vacaciones_fieldset')
						get_options('EDIT','Trabajador','Vacaciones',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Pension_alimenticia_fieldset')
						get_options('EDIT','Trabajador','Pension_alimenticia',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Prestamo_de_administradora_fieldset')
						get_options('EDIT','Trabajador','Prestamo_administradora',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Prestamo_de_caja_fieldset')
						get_options('EDIT','Trabajador','Prestamo_caja',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Prestamo_de_cliente_fieldset')
						get_options('EDIT','Trabajador','Prestamo_cliente',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Prestamo_del_fondo_de_ahorro_fieldset')
						get_options('EDIT','Trabajador','Prestamo_del_fondo_de_ahorro',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Fondo_de_garantia_fieldset')
						get_options('EDIT','Trabajador','Fondo_de_garantia',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'FONACOT_fieldset')
						get_options('EDIT','Trabajador','Retencion_FONACOT',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'INFONAVIT_fieldset')
						get_options('EDIT','Trabajador','Retencion_INFONAVIT',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Pago_por_seguro_de_vida_fieldset')
						get_options('EDIT','Trabajador','Pago_por_seguro_de_vida',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Salario_diario_fieldset')
						get_options('EDIT','Trabajador','Salario_diario',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Salario_minimo_fieldset')
						get_options('EDIT','Trabajador','Trabajador_Salario_minimo',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Archivo_digital_fieldset')
						get_options('EDIT','Trabajador','Archivo_digital',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Servicios_fieldset')
						get_options('EDIT','Trabajador','Servicio_Trabajador',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Sucursales_fieldset')
						get_options('EDIT','Trabajador','Trabajador_Sucursal',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Bajas_fieldset')
						get_options('EDIT','Trabajador','Baja',obj.cells[1].innerHTML,'First',fieldsets[i]);
					else if(fieldsets[i].getAttribute('class') == 'Descuentos_pendientes_fieldset')
						get_options('EDIT','Trabajador','Descuento_pendiente',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar trabajador';
				fit_trabajador(form);//fit_trabajador() at presentation.js
			}
			else if(_table == 'Trabajador_Salario_minimo')
			{
				var obj = _td.parentNode;
				var _form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
				var fieldsets = _form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)

					if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
					{
						var _datos = fieldsets[i];
						break;
					}

				var textareas = _datos.getElementsByTagName('textarea');

				for(var i=0; i<textareas.length; i++)

					if(textareas[i].getAttribute('class') == 'rfc_textarea')
					{
						var _trabajador = textareas[i].value;
						break;
					}

				var fieldsets = form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Trabajador_Salario_minimo','Servicio',_trabajador+','+obj.cells[3].innerHTML+','+obj.cells[4].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar salario mínimo del trabajador';
				fit_trabajador_salario_minimo(form);//fit_trabajador_salario_minimo() at presentation.js
			}
			else if(_table == 'Trabajador_Sucursal')
			{
				var obj = _td.parentNode;
				var _form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
				var fieldsets = _form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)

					if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
					{
						var _datos = fieldsets[i];
						break;
					}

				var textareas = _datos.getElementsByTagName('textarea');

				for(var i=0; i<textareas.length; i++)

					if(textareas[i].getAttribute('class') == 'rfc_textarea')
					{
						var _trabajador = textareas[i].value;
						break;
					}

				var fieldsets = form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Trabajador_Sucursal','Servicio',_trabajador+','+obj.cells[1].innerHTML+','+obj.cells[2].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar sucursal del trabajador';
				fit_trabajador_sucursal(form);//fit_trabajador_sucursal() at presentation.js
			}
			else if(_table == 'UMF')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','UMF','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar UMF';
				fit_umf(form);//fit_umf() at presentation.js
			}
			else if(_table == 'Usuario')
			{
				_title.innerHTML = 'Editar usuario';
				fit_usuario(form);//fit_umf() at presentation.js
			}
			else if(_table == 'Vacaciones')
			{
				var fieldsets = form.getElementsByTagName('fieldset');
				var obj = _td.parentNode;

				for(var i=0; i<fieldsets.length; i++)
				{

					if(fieldsets[i].getAttribute('class') == 'Servicio_fieldset')
						get_options('EDIT','Vacaciones','Servicio',obj.cells[1].innerHTML,'First',fieldsets[i]);

				}

				_title.innerHTML = 'Editar vacaciones';
				fit_vacaciones(form);//fit_vacaciones() at presentation.js
			}

			//images
			var images = edit_div.getElementsByTagName('IMG');

			for(var i=0; i<images.length; i++)

				if(images[i].getAttribute('class') == 'submit_button')
				{
					var submit_button = images[i];
					submit_button.src = 'icon.php?subject=submit&height=' + _height;
					submit_button.style.display = 'block';
					submit_button.style.position = 'absolute';
					submit_button.style.padding = 0;
					submit_button.style.margin = 0;
					submit_button.style.border = 'none';
					submit_button.style.background = 'none';
					submit_button.style.top = edit_div.offsetHeight - form.offsetTop - _height - _border_width * 4 + 'px';
					submit_button.style.zIndex = 1;
					submit_button.style.cursor = 'pointer';
					submit_button.style.left = edit_div.offsetWidth - form.offsetLeft - _height - _border_width * 4 + 'px';
				}
				else if(images[i].getAttribute('class') == 'calendar_button')
				{
					images[i].style.display = 'block';
					images[i].style.position = 'absolute';
					images[i].style.padding = 0;
					images[i].style.margin = 0;
					images[i].style.border = 'none';
					images[i].style.background = 'none';
					images[i].style.top = images[i].previousSibling.offsetTop + 'px';
					images[i].style.left = images[i].previousSibling.offsetLeft + images[i].previousSibling.offsetWidth + 2 + 'px';
					images[i].style.cursor = 'pointer';
					images[i].src = 'icon.php?subject=calendar&height=' + images[i].previousSibling.offsetHeight;
				}

		}

	}

	var obj = _td.parentNode;
	var id = getID(_table);

	if(_table == 'Representante_legal' || _table == 'Socio' || _table == 'Apoderado' || _table == 'Instrumento_notarial' || _table == 'Regimen_fiscal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var _owner = textarea.getAttribute('owner');
		var params = 'subject=' + _table + '&' + id + '=' + obj.cells[1].innerHTML + '&empresa=' + textarea.value;
	}
	else if(_table == 'Servicio_adicional')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('name') == 'id')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&' + id + '=' + obj.cells[1].innerHTML + '&servicio=' + textarea.value;
	}
	else if(_table == 'Servicio_Empresa' || _table == 'Servicio_Registro_patronal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('name') == 'id')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + textarea.value + ',' + obj.cells[1].innerHTML;
	}
	else if(_table == 'Servicio_Trabajador')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + obj.cells[1].innerHTML + ',' + textarea.value;
	}
	else if(_table == 'Sucursal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&' + id + '=' + obj.cells[1].innerHTML + '&empresa=' + textarea.value;
	}
	else if(_table == 'Trabajador_Salario_minimo')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + textarea.value + ',' + obj.cells[3].innerHTML + ',' + obj.cells[4].innerHTML;
	}
	else if(_table == 'Trabajador_Sucursal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + textarea.value + ',' + obj.cells[1].innerHTML + ',' + obj.cells[2].innerHTML + ',' + obj.cells[4].innerHTML;
	}
	else if(_table == 'Descuento_pendiente')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + obj.cells[1].innerHTML + ',' + obj.cells[2].innerHTML + ',' + obj.cells[3].innerHTML + ',' + textarea.value;
	}
	else if(_table == 'Porcentaje_de_cuotas_IMSS')
		var params = 'subject=' + _table + '&id=' + obj.cells[1].innerHTML + ',' + obj.cells[2].innerHTML;
	else
		var params = 'subject=' + _table + '&' + id + '=' + obj.cells[1].innerHTML;

	xmledit.open('POST','edit.php',true);
	xmledit.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmledit.setRequestHeader("Content-length", params.length);
	xmledit.setRequestHeader("Connection", "close");
	xmledit.setRequestHeader("cache-control","no-cache");
	xmledit.send(params);
}

function _delete(obj,_table)
{
	_delete.item = obj;
	_delete.table = _table;
	var delete_div = document.createElement('div');
	document.body.appendChild(delete_div);
	delete_div.style.overflow = 'hidden';
	delete_div.style.display = 'block';
	delete_div.style.position = 'absolute';
	delete_div.style.padding = 0;
	delete_div.style.margin = 0;
	delete_div.style.height = (window_height < 150 ? parseInt(window_height * 0.96) : 150) + 'px';
	delete_div.style.width = (window_width < 300 ? parseInt(window_width * 0.98) : 300) + 'px';
	delete_div.style.border = _border_width + 'px solid #555';
	delete_div.style.top = parseInt((window_height - delete_div.offsetHeight) / 2) + 'px';
	delete_div.style.left = parseInt((window_width - delete_div.offsetWidth) / 2) + 'px';
	delete_div.style.zIndex = 200;
	delete_div.style.background = 'rgba(255, 255, 255, 0.90)';
	delete_div.style.filter = 'filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#CCffffff, endColorstr=#CCffffff)';
	delete_div.style.MozBoxShadow = "0 0 8px 8px #888";
	delete_div.style.WebkitBoxShadow = "0 0 8px 8px#888";
	delete_div.style.boxShadow = "0 0 8px 8px #888";
	//close button
	var close_button = document.createElement('div');
	delete_div.appendChild(close_button);
	close_button.setAttribute('onclick','close_div(this)');//close_div() at presentation.js
	close_button.style.display = 'block';
	close_button.style.position = 'absolute';
	close_button.style.padding = 0;
	close_button.style.margin = 0;
	close_button.style.border = 'none';
	close_button.style.background = '#555';
	close_button.style.width = _height + 'px';
	close_button.style.height = _height + 'px';
	close_button.style.top = '0px';
	close_button.style.left = delete_div.offsetWidth - close_button.offsetWidth - _border_width * 2 + 'px';
	close_button.style.cursor = 'pointer';
	var image = document.createElement('img');
	close_button.appendChild(image);
	image.style.display = 'block';
	image.style.position = 'absolute';
	image.style.padding = 0;
	image.style.margin = 0;
	image.style.border = 'none';
	image.style.background = 'none';
	image.style.zIndex = 1;
	image.src = 'icon.php?subject=close&height=' + _height;
	//title
	var _title = document.createElement('span');
	delete_div.appendChild(_title);
	_title.style.display = 'block';
	_title.style.position = 'absolute';
	_title.style.padding = 0;
	_title.style.margin = 0;
	_title.style.border = 'none';
	_title.style.background = '#555';
	_title.style.top = '0px';
	_title.style.left = '0px';
	_title.style.color = '#fff';
	_title.style.width = delete_div.offsetWidth - _height + 'px';
	_title.style.height = close_button.offsetHeight + 'px';
	//cuestion
	var _cuestion = document.createElement('span');
	delete_div.appendChild(_cuestion);
	_cuestion.style.display = 'block';
	_cuestion.style.position = 'absolute';
	_cuestion.style.padding = 0;
	_cuestion.style.margin = 0;
	_cuestion.style.border = 'none';
	_cuestion.style.background = 'none';
	_cuestion.style.color = '#555';
	_cuestion.style.font = font;
	_cuestion.innerHTML = '¿Realmente desea eliminarlo(a)?';
	_cuestion.style.top = parseInt((delete_div.offsetHeight - _cuestion.offsetHeight) / 2) + 'px';
	_cuestion.style.left = parseInt((delete_div.offsetWidth - _cuestion.offsetWidth) / 2) + 'px';
	var image = document.createElement('img');
	delete_div.appendChild(image);
	image.style.display = 'block';
	image.style.position = 'absolute';
	image.style.padding = 0;
	image.style.margin = 0;
	image.style.border = 'none';
	image.style.background = 'none';
	image.style.zIndex = 1;
	image.style.cursor = 'pointer';
	image.setAttribute('onclick','_del(this)');
	image.src = 'icon.php?subject=yes&height=' + _height;
	image.style.top = _cuestion.offsetTop + _cuestion.offsetHeight + _border_width + 'px';
	image.style.left = _cuestion.offsetLeft + 'px';
	var image = document.createElement('img');
	delete_div.appendChild(image);
	image.style.display = 'block';
	image.style.position = 'absolute';
	image.style.padding = 0;
	image.style.margin = 0;
	image.style.border = 'none';
	image.style.background = 'none';
	image.style.zIndex = 1;
	image.style.cursor = 'pointer';
	image.setAttribute('onclick','close_div(this)');//close_div() at presentation.js
	image.src = 'icon.php?subject=no&height=' + _height;
	image.style.top = _cuestion.offsetTop + _cuestion.offsetHeight + _border_width + 'px';
	image.style.left = _cuestion.offsetLeft + _cuestion.offsetWidth - _height + 'px';
}

function _del(_obj)
{
	var _user = getUser();
	close_div(_obj);//closing delete cuestion
	_td = _delete.item;
	_table = _delete.table;

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmldelete = new XMLHttpRequest();

		if (xmldelete.overrideMimeType)
			xmldelete.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmldelete = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmldelete = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmldelete.onreadystatechange = function()
	{

		if (xmldelete.readyState==4 && xmldelete.status==200)
		{
			var divs = document.getElementsByTagName('div');

			for(var i=0; i<divs.length; i++)

				if(divs[i].getAttribute('class') == 'options' && divs[i].getAttribute('dbtable2') == _table)
				{

					if(divs[i].parentNode.tagName == 'FIELDSET')
					{
						get_options(divs[i].getAttribute('mode'),divs[i].getAttribute('dbtable1'),_table,divs[i].getAttribute('_id'),'First',divs[i].parentNode);

						if(_table == 'Retencion_FONACOT' || _table == 'Retencion_INFONAVIT' || _table == 'Pension_alimenticia' || _table == 'Prestamo_caja' || _table == 'Prestamo_administradora' || _table == 'Prestamo_cliente')
						{
							var fieldsets = divs[i].parentNode.parentNode.getElementsByTagName('fieldset');

							for(var j=0; j<fieldsets.length; j++)

								if(fieldsets[j].getAttribute('class') == 'Descuentos_pendientes_fieldset')
								{
									var _divs = fieldsets[j].getElementsByTagName('div');

									for(var k=0; k<_divs.length; k++)

										if(_divs[k].getAttribute('class') == 'options')
										{
											get_options(_divs[k].getAttribute('mode'),_divs[k].getAttribute('dbtable1'),'Descuento_pendiente',_divs[k].getAttribute('_id'),'First',_divs[k].parentNode);
											break;
										}

									break;
								}

						}
						else
							get_options(divs[i].getAttribute('mode'),divs[i].getAttribute('dbtable1'),_table,divs[i].getAttribute('_id'),'First',divs[i].parentNode);

					}
					else if(divs[i].parentNode.tagName == 'DIV')
						_load(_table);

				}

		}

	}

	var obj = _td.parentNode;
	var id = getID(_table);

	if(_table == 'Representante_legal' || _table == 'Socio' || _table == 'Apoderado' || _table == 'Archivo_digital' || _table == 'Instrumento_notarial' || _table == 'Regimen_fiscal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var _owner = textarea.getAttribute('owner');
		var params = 'subject=' + _table + '&' + id + '=' + obj.cells[1].innerHTML + (_owner == 'Empresa' ? '&empresa=' : '&trabajador=') + textarea.value;
	}
	else if(_table == 'Servicio_adicional')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('name') == 'id')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&' + id + '=' + obj.cells[1].innerHTML + '&servicio=' + textarea.value;
	}
	else if(_table == 'Servicio_Empresa' || _table == 'Servicio_Registro_patronal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('name') == 'id')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + textarea.value + ',' + obj.cells[1].innerHTML;
	}
	else if(_table == 'Servicio_Trabajador')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + obj.cells[1].innerHTML + ',' + textarea.value;
	}
	else if(_table == 'Sucursal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&' + id + '=' + obj.cells[1].innerHTML + '&empresa=' + textarea.value;
	}
	else if(_table == 'Trabajador_Salario_minimo')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + textarea.value + ',' + obj.cells[3].innerHTML + ',' + obj.cells[4].innerHTML;
	}
	else if(_table == 'Trabajador_Sucursal')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + textarea.value + ',' + obj.cells[1].innerHTML + ',' + obj.cells[2].innerHTML + ',' + obj.cells[4].innerHTML;
	}
	else if(_table == 'Descuento_pendiente')
	{
		var form = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var fieldsets = form.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			{
				var fieldset = fieldsets[i];
				break;
			}

		var textareas = fieldset.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'rfc_textarea')
			{
				var textarea = textareas[i];
				break;
			}

		var params = 'subject=' + _table + '&id=' + obj.cells[1].innerHTML + ',' + obj.cells[2].innerHTML + ',' + obj.cells[3].innerHTML + ',' + textarea.value;
	}
	else if(_table == 'Porcentaje_de_cuotas_IMSS')
		var params = 'subject=' + _table + '&id=' + obj.cells[1].innerHTML + ',' + obj.cells[2].innerHTML;
	else if(_table == 'Usuario' && _user != 'jonathan' )
	{
		_alert('Permiso denegado');
		return;
	}
	else
		var params = 'subject=' + _table + '&' + id + '=' + obj.cells[1].innerHTML;

	xmldelete.open('POST','delete.php?',true);
	xmldelete.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmldelete.setRequestHeader("Content-length", params.length);
	xmldelete.setRequestHeader("Connection", "close");
	xmldelete.setRequestHeader("cache-control","no-cache");
	xmldelete.send(params);
}

function _aguinaldo()
{
	var params = window_params('Aguinaldo');
	var aguinaldo_div = document.createElement('div');
	document.body.appendChild(aguinaldo_div);
	aguinaldo_div.innerHTML = '<div class = "datos_tab">Datos</div><form><fieldset class = "Datos_fieldset" style = "visibility:visible"\><label class = "trabajador_label">Trabajador</label><textarea rows = 5 cols = 20 class = "trabajador_textarea" name = "Trabajador" onkeyup = "_autocomplete(this, \'Trabajador\', \'Nombre\')\" title = "Trabajador"></textarea><label class = "trabajador_rfc_label">RFC (Trabajador)</label><select class = "trabajador_rfc_select" name = "Trabajador_RFC" title = "RFC del trabajador"></select><label class = "servicio_label">Servicio</label><select class = "servicio_select" name = "Servicio" title = "Servicio"></select><label class = "fecha_label">Fecha</label><textarea rows = 5 cols = 20 class = "fecha_textarea" name = "Fecha" title = "Fecha"></textarea><input type="button" value = "▦" class = "calendar_button" onmouseover = "calendar_button_bright(this)" onmouseout = "calendar_button_opaque(this)" onclick = "show_cal(this)" \></fieldset><div class = "submit_button" onmouseover = "submit_button_bright(this)" onmouseout = "submit_button_opaque(this)" onclick = "open_aguinaldo(this.parentNode)">✔</div></form>';
	aguinaldo_div.style.overflow = 'hidden';
	aguinaldo_div.style.width = params[0] + 'px';
	aguinaldo_div.style.height = params[1] + 'px';
	aguinaldo_div.style.display = 'block';
	aguinaldo_div.style.position = 'absolute';
	aguinaldo_div.style.padding = 0;
	aguinaldo_div.style.margin = 0;
	aguinaldo_div.style.background = '#fff';
	aguinaldo_div.style.border = '4px solid #555';
	aguinaldo_div.style.top = parseInt((window_height - aguinaldo_div.offsetHeight) / 2) + 'px';//window_height set at presentation.js
	aguinaldo_div.style.left = parseInt((window_width - aguinaldo_div.offsetWidth) / 2) + 'px';//window_width set at presentation.js
	aguinaldo_div.style.zIndex = _zIndex('+');//_zIndex() at general.js
	//close button
	var close_button = document.createElement('div');
	aguinaldo_div.appendChild(close_button);
	close_button.innerHTML = '\u2716';
	close_button.setAttribute('onclick','close_div(this)');//close_div() at presentation.js 
	close_button.setAttribute('title','cerrar');
	close_button.style.display = 'block';
	close_button.style.position = 'absolute';
	close_button.style.padding = '2px 6px';
	close_button.style.margin = '2px 0px 0px 0px';
	close_button.style.font = font;
	close_button.style.top = '0px';
	close_button.style.border = 'none';
	close_button.style.borderRadius = '1em';
	close_button.style.MozBorderRadius = '1em';
	close_button.style.WebkitBorderRadius = '1em';
	close_button.style.background = '#ff0000';
	close_button.style.color = '#fff';
	close_button.style.left = aguinaldo_div.offsetWidth - close_button.offsetWidth - 10 + 'px';
	close_button.style.cursor = 'pointer';
	close_button.style.textAlign = 'center';
	aguinaldo_div.style.visibility = 'visible';
	//title
	var _title = document.createElement('span');
	aguinaldo_div.appendChild(_title);
	var forms = aguinaldo_div.getElementsByTagName('form');
	var form = forms[0];
	_title.innerHTML = 'Aguinaldo';
	fit_aguinaldo(form);//fit_aguinaldo() at presentation.js
	_title.style.display = 'block';
	_title.style.position = 'absolute';
	_title.style.padding = 0;
	_title.style.margin = 0;
	_title.style.border = 'none';
	_title.style.background = 'none';
	_title.style.textAlign = 'center';
	_title.style.font = font;
	_title.style.color = '#333';
	_title.style.top = aguinaldo_div.offsetHeight - _title.offsetHeight - 10 + 'px';
	_title.style.left = '4px';
}

function open_aguinaldo(_form)
{
	var selects = _form.getElementsByTagName('select');

	for(var i=0; i<selects.length; i++)

		if(selects[i].getAttribute('class') == 'trabajador_rfc_select')
			var trabajador = selects[i].options[selects[i].selectedIndex].value;
		else if(selects[i].getAttribute('class') == 'servicio_select')
		{
			var data = selects[i].options[selects[i].selectedIndex].value.split('/');
			var servicio = data[0];
		}

	var textareas = _form.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('class') == 'fecha_textarea')
		{
			var fecha = textareas[i].value;
			break;
		}

	close_div(_form);
	window.open("aguinaldo.php?trabajador=" + trabajador + "&servicio=" + servicio + "&fecha=" + fecha);
}

function open_vacaciones(_form)
{
	var selects = _form.getElementsByTagName('select');

	for(var i=0; i<selects.length; i++)

		if(selects[i].getAttribute('class') == 'trabajador_rfc_select')
			var trabajador = selects[i].options[selects[i].selectedIndex].value;
		else if(selects[i].getAttribute('class') == 'servicio_select')
		{
			var data = selects[i].options[selects[i].selectedIndex].value.split('/');
			var servicio = data[0];
		}

	var textareas = _form.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('class') == 'fecha_textarea')
			var fecha = textareas[i].value;
		else if(textareas[i].getAttribute('class') == 'anos_textarea')
			var anos = textareas[i].value;
		else if(textareas[i].getAttribute('class') == 'compensacion_textarea')
			var compensacion = textareas[i].value;

	close_div(_form);
	window.open("_vacaciones.php?trabajador=" + trabajador + "&servicio=" + servicio + "&anos=" + anos + "&compensacion=" + compensacion + "&fecha=" + fecha);
}

function concentrado_trabajador()
{
	var _submenu = document.getElementById('submenu');
	document.body.removeChild(_submenu);
	var params = window_params('Concentrado_trabajador');//window_params at presentation.js
	var _div = document.createElement('div');
	document.body.appendChild(_div);
	_div.innerHTML = "<div class = 'datos_tab'>Datos</div><form><fieldset class = 'Datos_fieldset' style = 'visibility:visible'\><label class = 'trabajador_label'>Trabajador</label><textarea class = 'trabajador_textarea' name = 'Nombre' onkeyup = \"_autocomplete(this, 'Trabajador', 'Nombre')\" title = 'Trabajador'></textarea><label class = 'trabajador_rfc_label'>RFC (Trabajador)</label><select class = 'trabajador_rfc_select' name = 'Trabajador' title = 'RFC del trabajador'></select><label class = 'servicio_label'>Servicio</label><select class = 'servicio_select' name = 'Servicio' title = 'Servicio'></select><label class = 'limite_inferior_label'>Límite inferior</label><textarea class = 'limite_inferior_textarea' name = 'Limite_inferior' title = 'Límite inferior'></textarea><img class = 'calendar_button' onclick = 'show_cal(this)' \><label class = 'limite_superior_label'>Límite superior</label><textarea class = 'limite_superior_textarea' name = 'Limite_superior' title = 'Límite superior'></textarea><img class = 'calendar_button' onclick = 'show_cal(this)' \></fieldset><IMG class = 'submit_button' onclick = \"show_concentrado_trabajador(this)\" /></form>";
	_div.style.overflow = 'hidden';
	_div.style.display = 'block';
	_div.style.position = 'absolute';
	_div.style.padding = 0;
	_div.style.margin = 0;
	_div.style.width = params[0] + 'px';
	_div.style.height = params[1] + 'px';
	_div.style.background = 'rgba(255, 255, 255, 0.90)';
	_div.style.MozBoxShadow = "0 0 8px 8px #888";
	_div.style.WebkitBoxShadow = "0 0 8px 8px #888";
	_div.style.boxShadow = "0 0 8px 8px #888";
	_div.style.border = _border_width + 'px solid #555';
	_div.style.top = parseInt((window_height  - _div.offsetHeight) / 2) + 'px';//window_height set at presentation.js
	_div.style.left = parseInt((window_width - _div.offsetWidth) / 2) + 'px';//window_width set at presentation.js
	_div.style.zIndex = _zIndex('+');//_zIndex() at general.js
	var forms = _div.getElementsByTagName('form');
	var form = forms[0];
	//close button
	var close_button = document.createElement('img');
	_div.appendChild(close_button);
	close_button.src = 'icon.php?subject=close&height=' + _height;
	close_button.setAttribute('onclick','close_div(this)');//close_div() at presentation.js 
	close_button.setAttribute('title','cerrar');
	close_button.style.display = 'block';
	close_button.style.position = 'absolute';
	close_button.style.padding = 0;
	close_button.style.margin = 0;
	close_button.style.border = 'none';
	close_button.style.background = 'none';
	close_button.style.top = '0px';
	close_button.style.left = _div.offsetWidth - _height - _border_width * 2 + 'px';
	close_button.style.cursor = 'pointer';
	close_button.style.zIndex = 1;
	//title
	var _title = document.createElement('span');
	_div.appendChild(_title);
	_title.innerHTML = 'Concentrado de datos del trabajador';
	_title.style.display = 'block';
	_title.style.position = 'absolute';
	_title.style.padding = 0;
	_title.style.margin = 0;
	_title.style.border = 'none';
	_title.style.background = '#555';
	_title.style.textAlign = 'left';
	_title.style.font = title_font;
	_title.style.color = '#fff';
	_title.style.top = '0px';
	_title.style.left = '0px';
	_title.style.height = _height + 'px';
	_title.style.width = _div.offsetWidth - 2 * _border_width + 'px';
	fit_concentrado_trabajador(form);//fit_concentrado_trabajador() at presentation.js
	//submit button
	var images = _div.getElementsByTagName('IMG');

	for(var i=0; i<images.length; i++)

		if(images[i].getAttribute('class') == 'submit_button')
		{
			var submit_button = images[i];
			submit_button.src = 'icon.php?subject=submit&height=' + _height;
			submit_button.style.display = 'block';
			submit_button.style.position = 'absolute';
			submit_button.style.padding = 0;
			submit_button.style.margin = 0;
			submit_button.style.border = 'none';
			submit_button.style.background = 'none';
			submit_button.style.top = _div.offsetHeight - form.offsetTop - _height - _border_width * 4 + 'px';
			submit_button.style.zIndex = 1;
			submit_button.style.cursor = 'pointer';
			submit_button.style.left = _div.offsetWidth - form.offsetLeft - _height - _border_width * 4 + 'px';
		}
		else if(images[i].getAttribute('class') == 'calendar_button')
		{
			images[i].src = 'icon.php?subject=calendar&height=' + images[i].previousSibling.offsetHeight;
			images[i].style.display = 'block';
			images[i].style.position = 'absolute';
			images[i].style.padding = 0;
			images[i].style.margin = 0;
			images[i].style.border = 'none';
			images[i].style.background = 'none';
			images[i].style.top = images[i].previousSibling.offsetTop + 'px';
			images[i].style.zIndex = 1;
			images[i].style.cursor = 'pointer';
			images[i].style.left = images[i].previousSibling.offsetLeft + images[i].previousSibling.offsetWidth + 'px';
		}

}

function calculo_anual()
{
	var _submenu = document.getElementById('submenu');
	document.body.removeChild(_submenu);
	var params = window_params('Calculo_anual');//window_params at presentation.js
	var _div = document.createElement('div');
	document.body.appendChild(_div);
	_div.innerHTML = "<div class = 'datos_tab'>Datos</div><form><fieldset class = 'Datos_fieldset' style = 'visibility:visible'\><label class = 'trabajador_label'>Trabajador</label><textarea class = 'trabajador_textarea' name = 'Nombre' onkeyup = \"_autocomplete(this, 'Trabajador', 'Nombre')\" title = 'Trabajador'></textarea><label class = 'trabajador_rfc_label'>RFC (Trabajador)</label><select class = 'trabajador_rfc_select' name = 'Trabajador' title = 'RFC del trabajador'></select><label class = 'servicio_label'>Servicio</label><select class = 'servicio_select' name = 'Servicio' title = 'Servicio'></select><label class = 'ano_label'>Año</label><textarea class = 'ano_textarea' name = 'Ano' title = 'Año'></textarea></fieldset><IMG class = 'submit_button' onclick = \"show_calculo_anual(this)\" /></form>";
	_div.style.overflow = 'hidden';
	_div.style.display = 'block';
	_div.style.position = 'absolute';
	_div.style.padding = 0;
	_div.style.margin = 0;
	_div.style.width = params[0] + 'px';
	_div.style.height = params[1] + 'px';
	_div.style.background = 'rgba(255, 255, 255, 0.90)';
	_div.style.MozBoxShadow = "0 0 8px 8px #888";
	_div.style.WebkitBoxShadow = "0 0 8px 8px#888";
	_div.style.boxShadow = "0 0 8px 8px #888";
	_div.style.border = _border_width + 'px solid #555';
	_div.style.top = parseInt((window_height  - _div.offsetHeight) / 2) + 'px';//window_height set at presentation.js
	_div.style.left = parseInt((window_width - _div.offsetWidth) / 2) + 'px';//window_width set at presentation.js
	_div.style.zIndex = _zIndex('+');//_zIndex() at general.js
	var forms = _div.getElementsByTagName('form');
	var form = forms[0];
	//close button
	var close_button = document.createElement('img');
	_div.appendChild(close_button);
	close_button.src = 'icon.php?subject=close&height=' + _height;
	close_button.setAttribute('onclick','close_div(this)');//close_div() at presentation.js 
	close_button.setAttribute('title','cerrar');
	close_button.style.display = 'block';
	close_button.style.position = 'absolute';
	close_button.style.padding = 0;
	close_button.style.margin = 0;
	close_button.style.border = 'none';
	close_button.style.background = 'none';
	close_button.style.top = '0px';
	close_button.style.left = _div.offsetWidth - _height - _border_width * 2 + 'px';
	close_button.style.cursor = 'pointer';
	close_button.style.zIndex = 1;
	//title
	var _title = document.createElement('span');
	_div.appendChild(_title);
	_title.innerHTML = 'Calculo anual del ISR para el trabajador';
	_title.style.display = 'block';
	_title.style.position = 'absolute';
	_title.style.padding = 0;
	_title.style.margin = 0;
	_title.style.border = 'none';
	_title.style.background = '#555';
	_title.style.textAlign = 'left';
	_title.style.font = title_font;
	_title.style.color = '#fff';
	_title.style.top = '0px';
	_title.style.left = '0px';
	_title.style.height = _height + 'px';
	_title.style.width = _div.offsetWidth - 2 * _border_width + 'px';
	fit_calculo_anual(form);//fit_calculo_anual() at presentation.js
	//submit button
	var images = _div.getElementsByTagName('IMG');

	for(var i=0; i<images.length; i++)

		if(images[i].getAttribute('class') == 'submit_button')
		{
			var submit_button = images[i];
			submit_button.src = 'icon.php?subject=submit&height=' + _height;
			submit_button.style.display = 'block';
			submit_button.style.position = 'absolute';
			submit_button.style.padding = 0;
			submit_button.style.margin = 0;
			submit_button.style.border = 'none';
			submit_button.style.background = 'none';
			submit_button.style.top = _div.offsetHeight - form.offsetTop - _height - _border_width * 4 + 'px';
			submit_button.style.zIndex = 1;
			submit_button.style.cursor = 'pointer';
			submit_button.style.left = _div.offsetWidth - form.offsetLeft - _height - _border_width * 4 + 'px';
		}
		else if(images[i].getAttribute('class') == 'calendar_button')
		{
			images[i].src = 'icon.php?subject=calendar&height=' + images[i].previousSibling.offsetHeight;
			images[i].style.display = 'block';
			images[i].style.position = 'absolute';
			images[i].style.padding = 0;
			images[i].style.margin = 0;
			images[i].style.border = 'none';
			images[i].style.background = 'none';
			images[i].style.top = images[i].previousSibling.offsetTop + 'px';
			images[i].style.zIndex = 1;
			images[i].style.cursor = 'pointer';
			images[i].style.left = images[i].previousSibling.offsetLeft + images[i].previousSibling.offsetWidth + 'px';
		}

}

function concentrado_empresa_nomina()
{
	var _submenu = document.getElementById('submenu');
	document.body.removeChild(_submenu);
	var params = window_params('Concentrado_empresa');//window_params at presentation.js
	var _div = document.createElement('div');
	document.body.appendChild(_div);
	_div.innerHTML = "<div class = 'datos_tab'>Datos</div><form><fieldset class = 'Datos_fieldset' style = 'visibility:visible'\><label class = 'empresa_label'>Empresa</label><textarea class = 'empresa_textarea' name = 'Nombre' onkeyup = \"_autocomplete(this, 'Empresa', 'Nombre')\" title = 'Empresa'></textarea><label class = 'empresa_rfc_label'>RFC (Empresa)</label><select class = 'empresa_rfc_select' name = 'Empresa' title = 'RFC de la empresa'></select><label class = 'servicio_label'>Servicio</label><select class = 'servicio_select' name = 'Servicio' title = 'Servicio'></select><label class = 'limite_inferior_label'>Límite inferior</label><textarea class = 'limite_inferior_textarea' name = 'Limite_inferior' title = 'Límite inferior'></textarea><img class = 'calendar_button' onclick = 'show_cal(this)' \><label class = 'limite_superior_label'>Límite superior</label><textarea class = 'limite_superior_textarea' name = 'Limite_superior' title = 'Límite superior'></textarea><img class = 'calendar_button' onclick = 'show_cal(this)' \></fieldset><img class = 'submit_button' onclick = \"show_concentrado_empresa_nomina(this)\" /></form>";
	_div.style.overflow = 'hidden';
	_div.style.display = 'block';
	_div.style.position = 'absolute';
	_div.style.padding = 0;
	_div.style.margin = 0;
	_div.style.width = params[0] + 'px';
	_div.style.height = params[1] + 'px';
	_div.style.background = 'rgba(255, 255, 255, 0.90)';
	_div.style.MozBoxShadow = "0 0 8px 8px #888";
	_div.style.WebkitBoxShadow = "0 0 8px 8px#888";
	_div.style.boxShadow = "0 0 8px 8px #888";
	_div.style.border = _border_width + 'px solid #555';
	_div.style.top = parseInt((window_height  - _div.offsetHeight) / 2) + 'px';//window_height set at presentation.js
	_div.style.left = parseInt((window_width - _div.offsetWidth) / 2) + 'px';//window_width set at presentation.js
	_div.style.zIndex = _zIndex('+');//_zIndex() at general.js
	var forms = _div.getElementsByTagName('form');
	var form = forms[0];
	//close button
	var close_button = document.createElement('img');
	_div.appendChild(close_button);
	close_button.src = 'icon.php?subject=close&height=' + _height;
	close_button.setAttribute('onclick','close_div(this)');//close_div() at presentation.js 
	close_button.setAttribute('title','cerrar');
	close_button.style.display = 'block';
	close_button.style.position = 'absolute';
	close_button.style.padding = 0;
	close_button.style.margin = 0;
	close_button.style.border = 'none';
	close_button.style.background = 'none';
	close_button.style.top = '0px';
	close_button.style.left = _div.offsetWidth - _height - _border_width * 2 + 'px';
	close_button.style.cursor = 'pointer';
	close_button.style.zIndex = 1;
	//title
	var _title = document.createElement('span');
	_div.appendChild(_title);
	_title.innerHTML = 'Concentrado de datos de trabajadores por empresa';
	_title.style.display = 'block';
	_title.style.position = 'absolute';
	_title.style.padding = 0;
	_title.style.margin = 0;
	_title.style.border = 'none';
	_title.style.background = '#555';
	_title.style.textAlign = 'left';
	_title.style.font = title_font;
	_title.style.color = '#fff';
	_title.style.top = '0px';
	_title.style.left = '0px';
	_title.style.height = _height + 'px';
	_title.style.width = _div.offsetWidth - 2 * _border_width + 'px';
	fit_concentrado_empresa(form);//fit_concentrado_empresa() at presentation.js
	//submit button
	var images = _div.getElementsByTagName('IMG');

	for(var i=0; i<images.length; i++)

		if(images[i].getAttribute('class') == 'submit_button')
		{
			var submit_button = images[i];
			submit_button.src = 'icon.php?subject=submit&height=' + _height;
			submit_button.style.display = 'block';
			submit_button.style.position = 'absolute';
			submit_button.style.padding = 0;
			submit_button.style.margin = 0;
			submit_button.style.border = 'none';
			submit_button.style.background = 'none';
			submit_button.style.top = _div.offsetHeight - form.offsetTop - _height - _border_width * 4 + 'px';
			submit_button.style.zIndex = 1;
			submit_button.style.cursor = 'pointer';
			submit_button.style.left = _div.offsetWidth - form.offsetLeft - _height - _border_width * 4 + 'px';
		}
		else if(images[i].getAttribute('class') == 'calendar_button')
		{
			images[i].src = 'icon.php?subject=calendar&height=' + images[i].previousSibling.offsetHeight;
			images[i].style.display = 'block';
			images[i].style.position = 'absolute';
			images[i].style.padding = 0;
			images[i].style.margin = 0;
			images[i].style.border = 'none';
			images[i].style.background = 'none';
			images[i].style.top = images[i].previousSibling.offsetTop + 'px';
			images[i].style.zIndex = 1;
			images[i].style.cursor = 'pointer';
			images[i].style.left = images[i].previousSibling.offsetLeft + images[i].previousSibling.offsetWidth + 'px';
		}

}

function concentrado_registro_patronal_nomina()
{
	var _submenu = document.getElementById('submenu');
	document.body.removeChild(_submenu);
	var params = window_params('Concentrado_registro_patronal');//window_params at presentation.js
	var _div = document.createElement('div');
	document.body.appendChild(_div);
	_div.innerHTML = "<div class = 'datos_tab'>Datos</div><form><fieldset class = 'Datos_fieldset' style = 'visibility:visible'><label class = 'empresa_label'>Empresa</label><textarea class = 'enterprice_textarea' name = 'Empresa' onkeyup = \"_autocomplete(this, 'Empresa', 'Nombre')\" title = 'Empresa'></textarea><label class = 'registro_label'>Registro patronal</label><div class = 'registro_div' title = 'Registro patronal'></div><label class = 'limite_inferior_label'>Límite inferior</label><textarea class = 'limite_inferior_textarea' name = 'Limite_inferior' title = 'Límite inferior'></textarea><img class = 'calendar_button' onclick = 'show_cal(this)'/><label class = 'limite_superior_label'>Límite superior</label><textarea class = 'limite_superior_textarea' name = 'Limite_superior' title = 'Límite superior'></textarea><img class = 'calendar_button' onclick = 'show_cal(this)' /><label class = 'solo_asalariados_label'>Solo asalariados</label><input type = 'checkbox' class = 'solo_asalariados_input' name = 'Solo_asalariados' title = 'Solo asalariados' /><label class = 'solo_asimilados_label'>Solo asimilados</label><input type = 'checkbox' class = 'solo_asimilados_input' name = 'Solo_asimilados' title = 'Solo asimilados' /></fieldset><img class = 'submit_button' onclick = \"show_concentrado_registro_patronal_nomina(this)\" /></form>";
	_div.style.overflow = 'hidden';
	_div.style.display = 'block';
	_div.style.position = 'absolute';
	_div.style.padding = 0;
	_div.style.margin = 0;
	_div.style.width = params[0] + 'px';
	_div.style.height = params[1] + 'px';
	_div.style.background = 'rgba(255, 255, 255, 0.90)';
	_div.style.MozBoxShadow = "0 0 8px 8px #888";
	_div.style.WebkitBoxShadow = "0 0 8px 8px#888";
	_div.style.boxShadow = "0 0 8px 8px #888";
	_div.style.border = _border_width + 'px solid #555';
	_div.style.top = parseInt((window_height  - _div.offsetHeight) / 2) + 'px';//window_height set at presentation.js
	_div.style.left = parseInt((window_width - _div.offsetWidth) / 2) + 'px';//window_width set at presentation.js
	_div.style.zIndex = _zIndex('+');//_zIndex() at general.js
	var forms = _div.getElementsByTagName('form');
	var form = forms[0];
	//close button
	var close_button = document.createElement('img');
	_div.appendChild(close_button);
	close_button.src = 'icon.php?subject=close&height=' + _height;
	close_button.setAttribute('onclick','close_div(this)');//close_div() at presentation.js 
	close_button.setAttribute('title','cerrar');
	close_button.style.display = 'block';
	close_button.style.position = 'absolute';
	close_button.style.padding = 0;
	close_button.style.margin = 0;
	close_button.style.border = 'none';
	close_button.style.background = 'none';
	close_button.style.top = '0px';
	close_button.style.left = _div.offsetWidth - _height - _border_width * 2 + 'px';
	close_button.style.cursor = 'pointer';
	close_button.style.zIndex = 1;
	//title
	var _title = document.createElement('span');
	_div.appendChild(_title);
	_title.innerHTML = 'Concentrado de datos de trabajadores por registro patronal';
	_title.style.display = 'block';
	_title.style.position = 'absolute';
	_title.style.padding = 0;
	_title.style.margin = 0;
	_title.style.border = 'none';
	_title.style.background = '#555';
	_title.style.textAlign = 'left';
	_title.style.font = title_font;
	_title.style.color = '#fff';
	_title.style.top = '0px';
	_title.style.left = '0px';
	_title.style.height = _height + 'px';
	_title.style.width = _div.offsetWidth - 2 * _border_width + 'px';
	fit_concentrado_registro(form);//fit_concentrado_registro() at presentation.js
	//submit button
	var images = _div.getElementsByTagName('IMG');

	for(var i=0; i<images.length; i++)

		if(images[i].getAttribute('class') == 'submit_button')
		{
			var submit_button = images[i];
			submit_button.src = 'icon.php?subject=submit&height=' + _height;
			submit_button.style.display = 'block';
			submit_button.style.position = 'absolute';
			submit_button.style.padding = 0;
			submit_button.style.margin = 0;
			submit_button.style.border = 'none';
			submit_button.style.background = 'none';
			submit_button.style.top = _div.offsetHeight - form.offsetTop - _height - _border_width * 4 + 'px';
			submit_button.style.zIndex = 1;
			submit_button.style.cursor = 'pointer';
			submit_button.style.left = _div.offsetWidth - form.offsetLeft - _height - _border_width * 4 + 'px';
		}
		else if(images[i].getAttribute('class') == 'calendar_button')
		{
			images[i].src = 'icon.php?subject=calendar&height=' + images[i].previousSibling.offsetHeight;
			images[i].style.display = 'block';
			images[i].style.position = 'absolute';
			images[i].style.padding = 0;
			images[i].style.margin = 0;
			images[i].style.border = 'none';
			images[i].style.background = 'none';
			images[i].style.top = images[i].previousSibling.offsetTop + 'px';
			images[i].style.zIndex = 1;
			images[i].style.cursor = 'pointer';
			images[i].style.left = images[i].previousSibling.offsetLeft + images[i].previousSibling.offsetWidth + 'px';
		}

}

function concentrado_empresa_imss()
{
	var _submenu = document.getElementById('submenu');
	document.body.removeChild(_submenu);
	var params = window_params('Concentrado_empresa');//window_params at presentation.js
	var _div = document.createElement('div');
	document.body.appendChild(_div);
	_div.innerHTML = "<div class = 'datos_tab'>Datos</div><form><fieldset class = 'Datos_fieldset' style = 'visibility:visible'\><label class = 'empresa_label'>Empresa</label><textarea class = 'empresa_textarea' name = 'Nombre' onkeyup = \"_autocomplete(this, 'Empresa', 'Nombre')\" title = 'Empresa'></textarea><label class = 'empresa_rfc_label'>RFC (Empresa)</label><select class = 'empresa_rfc_select' name = 'Empresa' title = 'RFC de la empresa'></select><label class = 'servicio_label'>Servicio</label><select class = 'servicio_select' name = 'Servicio' title = 'Servicio'></select><label class = 'limite_inferior_label'>Límite inferior</label><textarea class = 'limite_inferior_textarea' name = 'Limite_inferior' title = 'Límite inferior'></textarea><img class = 'calendar_button' onclick = 'show_cal(this)' \><label class = 'limite_superior_label'>Límite superior</label><textarea class = 'limite_superior_textarea' name = 'Limite_superior' title = 'Límite superior'></textarea><img class = 'calendar_button' onclick = 'show_cal(this)' \></fieldset><img class = 'submit_button' onclick = \"show_concentrado_empresa_imss(this)\" /></form>";
	_div.style.overflow = 'hidden';
	_div.style.display = 'block';
	_div.style.position = 'absolute';
	_div.style.padding = 0;
	_div.style.margin = 0;
	_div.style.width = params[0] + 'px';
	_div.style.height = params[1] + 'px';
	_div.style.background = 'rgba(255, 255, 255, 0.90)';
	_div.style.MozBoxShadow = "0 0 8px 8px #888";
	_div.style.WebkitBoxShadow = "0 0 8px 8px#888";
	_div.style.boxShadow = "0 0 8px 8px #888";
	_div.style.border = _border_width + 'px solid #555';
	_div.style.top = parseInt((window_height  - _div.offsetHeight) / 2) + 'px';//window_height set at presentation.js
	_div.style.left = parseInt((window_width - _div.offsetWidth) / 2) + 'px';//window_width set at presentation.js
	_div.style.zIndex = _zIndex('+');//_zIndex() at general.js
	var forms = _div.getElementsByTagName('form');
	var form = forms[0];
	//close button
	var close_button = document.createElement('img');
	_div.appendChild(close_button);
	close_button.src = 'icon.php?subject=close&height=' + _height;
	close_button.setAttribute('onclick','close_div(this)');//close_div() at presentation.js 
	close_button.setAttribute('title','cerrar');
	close_button.style.display = 'block';
	close_button.style.position = 'absolute';
	close_button.style.padding = 0;
	close_button.style.margin = 0;
	close_button.style.border = 'none';
	close_button.style.background = 'none';
	close_button.style.top = '0px';
	close_button.style.left = _div.offsetWidth - _height - _border_width * 2 + 'px';
	close_button.style.cursor = 'pointer';
	close_button.style.zIndex = 1;
	//title
	var _title = document.createElement('span');
	_div.appendChild(_title);
	_title.innerHTML = 'Concentrado de IMSS por empresa';
	_title.style.display = 'block';
	_title.style.position = 'absolute';
	_title.style.padding = 0;
	_title.style.margin = 0;
	_title.style.border = 'none';
	_title.style.background = '#555';
	_title.style.textAlign = 'left';
	_title.style.font = title_font;
	_title.style.color = '#fff';
	_title.style.top = '0px';
	_title.style.left = '0px';
	_title.style.height = _height + 'px';
	_title.style.width = _div.offsetWidth - 2 * _border_width + 'px';
	fit_concentrado_empresa(form);//fit_concentrado_empresa() at presentation.js
	//submit button
	var images = _div.getElementsByTagName('IMG');

	for(var i=0; i<images.length; i++)

		if(images[i].getAttribute('class') == 'submit_button')
		{
			var submit_button = images[i];
			submit_button.src = 'icon.php?subject=submit&height=' + _height;
			submit_button.style.display = 'block';
			submit_button.style.position = 'absolute';
			submit_button.style.padding = 0;
			submit_button.style.margin = 0;
			submit_button.style.border = 'none';
			submit_button.style.background = 'none';
			submit_button.style.top = _div.offsetHeight - form.offsetTop - _height - _border_width * 4 + 'px';
			submit_button.style.zIndex = 1;
			submit_button.style.cursor = 'pointer';
			submit_button.style.left = _div.offsetWidth - form.offsetLeft - _height - _border_width * 4 + 'px';
		}
		else if(images[i].getAttribute('class') == 'calendar_button')
		{
			images[i].src = 'icon.php?subject=calendar&height=' + images[i].previousSibling.offsetHeight;
			images[i].style.display = 'block';
			images[i].style.position = 'absolute';
			images[i].style.padding = 0;
			images[i].style.margin = 0;
			images[i].style.border = 'none';
			images[i].style.background = 'none';
			images[i].style.top = images[i].previousSibling.offsetTop + 'px';
			images[i].style.zIndex = 1;
			images[i].style.cursor = 'pointer';
			images[i].style.left = images[i].previousSibling.offsetLeft + images[i].previousSibling.offsetWidth + 'px';
		}

}

function concentrado_registro_patronal_imss()
{
	var _submenu = document.getElementById('submenu');
	document.body.removeChild(_submenu);
	var params = window_params('Concentrado_registro_patronal');//window_params at presentation.js
	var _div = document.createElement('div');
	document.body.appendChild(_div);
	_div.innerHTML = "<div class = 'datos_tab'>Datos</div><form><fieldset class = 'Datos_fieldset' style = 'visibility:visible'\><label class = 'empresa_label'>Empresa</label><textarea class = 'enterprice_textarea' name = 'Empresa' onkeyup = \"_autocomplete(this, 'Empresa', 'Nombre')\" title = 'Empresa'></textarea><label class = 'registro_label'>Registro patronal</label><div class = 'registro_div' title = 'Registro patronal'></div><label class = 'limite_inferior_label'>Límite inferior</label><textarea class = 'limite_inferior_textarea' name = 'Limite_inferior' title = 'Límite inferior'></textarea><img class = 'calendar_button' onclick = 'show_cal(this)' \><label class = 'limite_superior_label'>Límite superior</label><textarea class = 'limite_superior_textarea' name = 'Limite_superior' title = 'Límite superior'></textarea><img class = 'calendar_button' onclick = 'show_cal(this)' \></fieldset><img class = 'submit_button' onclick = \"show_concentrado_registro_patronal_imss(this)\" /></form>";
	_div.style.overflow = 'hidden';
	_div.style.display = 'block';
	_div.style.position = 'absolute';
	_div.style.padding = 0;
	_div.style.margin = 0;
	_div.style.width = params[0] + 'px';
	_div.style.height = params[1] + 'px';
	_div.style.background = 'rgba(255, 255, 255, 0.90)';
	_div.style.MozBoxShadow = "0 0 8px 8px #888";
	_div.style.WebkitBoxShadow = "0 0 8px 8px#888";
	_div.style.boxShadow = "0 0 8px 8px #888";
	_div.style.border = _border_width + 'px solid #555';
	_div.style.top = parseInt((window_height  - _div.offsetHeight) / 2) + 'px';//window_height set at presentation.js
	_div.style.left = parseInt((window_width - _div.offsetWidth) / 2) + 'px';//window_width set at presentation.js
	_div.style.zIndex = _zIndex('+');//_zIndex() at general.js
	var forms = _div.getElementsByTagName('form');
	var form = forms[0];
	//close button
	var close_button = document.createElement('img');
	_div.appendChild(close_button);
	close_button.src = 'icon.php?subject=close&height=' + _height;
	close_button.setAttribute('onclick','close_div(this)');//close_div() at presentation.js 
	close_button.setAttribute('title','cerrar');
	close_button.style.display = 'block';
	close_button.style.position = 'absolute';
	close_button.style.padding = 0;
	close_button.style.margin = 0;
	close_button.style.border = 'none';
	close_button.style.background = 'none';
	close_button.style.top = '0px';
	close_button.style.left = _div.offsetWidth - _height - _border_width * 2 + 'px';
	close_button.style.cursor = 'pointer';
	close_button.style.zIndex = 1;
	//title
	var _title = document.createElement('span');
	_div.appendChild(_title);
	_title.innerHTML = 'Concentrado de IMSS por registro patronal';
	_title.style.display = 'block';
	_title.style.position = 'absolute';
	_title.style.padding = 0;
	_title.style.margin = 0;
	_title.style.border = 'none';
	_title.style.background = '#555';
	_title.style.textAlign = 'left';
	_title.style.font = title_font;
	_title.style.color = '#fff';
	_title.style.top = '0px';
	_title.style.left = '0px';
	_title.style.height = _height + 'px';
	_title.style.width = _div.offsetWidth - 2 * _border_width + 'px';
	fit_concentrado_registro(form);//fit_concentrado_registro() at presentation.js
	//submit button
	var images = _div.getElementsByTagName('IMG');

	for(var i=0; i<images.length; i++)

		if(images[i].getAttribute('class') == 'submit_button')
		{
			var submit_button = images[i];
			submit_button.src = 'icon.php?subject=submit&height=' + _height;
			submit_button.style.display = 'block';
			submit_button.style.position = 'absolute';
			submit_button.style.padding = 0;
			submit_button.style.margin = 0;
			submit_button.style.border = 'none';
			submit_button.style.background = 'none';
			submit_button.style.top = _div.offsetHeight - form.offsetTop - _height - _border_width * 4 + 'px';
			submit_button.style.zIndex = 1;
			submit_button.style.cursor = 'pointer';
			submit_button.style.left = _div.offsetWidth - form.offsetLeft - _height - _border_width * 4 + 'px';
		}
		else if(images[i].getAttribute('class') == 'calendar_button')
		{
			images[i].src = 'icon.php?subject=calendar&height=' + images[i].previousSibling.offsetHeight;
			images[i].style.display = 'block';
			images[i].style.position = 'absolute';
			images[i].style.padding = 0;
			images[i].style.margin = 0;
			images[i].style.border = 'none';
			images[i].style.background = 'none';
			images[i].style.top = images[i].previousSibling.offsetTop + 'px';
			images[i].style.zIndex = 1;
			images[i].style.cursor = 'pointer';
			images[i].style.left = images[i].previousSibling.offsetLeft + images[i].previousSibling.offsetWidth + 'px';
		}

}

function reporte_prestaciones()
{
	var _submenu = document.getElementById('submenu');
	document.body.removeChild(_submenu);
	var params = window_params('Concentrado_registro_patronal');//window_params at presentation.js
	var _div = document.createElement('div');
	document.body.appendChild(_div);
	_div.innerHTML = "<div class = 'datos_tab'>Datos</div><form><fieldset class = 'Datos_fieldset' style = 'visibility:visible'\><label class = 'limite_inferior_label'>Límite inferior</label><textarea class = 'limite_inferior_textarea' name = 'Limite_inferior' title = 'Límite inferior'></textarea><img class = 'calendar_button' onclick = 'show_cal(this)' \><label class = 'registro_label'>Registro patronal</label><div class = 'registro_div' title = 'Registro patronal'></div><label class = 'limite_superior_label'>Límite superior</label><textarea class = 'limite_superior_textarea' name = 'Limite_superior' title = 'Límite superior'></textarea><img class = 'calendar_button' onclick = 'show_cal(this)' \></fieldset><img class = 'submit_button' onclick = \"show_reporte_prestaciones(this)\" /></form>";
	_div.style.overflow = 'hidden';
	_div.style.display = 'block';
	_div.style.position = 'absolute';
	_div.style.padding = 0;
	_div.style.margin = 0;
	_div.style.width = params[0] + 'px';
	_div.style.height = params[1] + 'px';
	_div.style.background = 'rgba(255, 255, 255, 0.90)';
	_div.style.MozBoxShadow = "0 0 8px 8px #888";
	_div.style.WebkitBoxShadow = "0 0 8px 8px#888";
	_div.style.boxShadow = "0 0 8px 8px #888";
	_div.style.border = _border_width + 'px solid #555';
	_div.style.top = parseInt((window_height  - _div.offsetHeight) / 2) + 'px';//window_height set at presentation.js
	_div.style.left = parseInt((window_width - _div.offsetWidth) / 2) + 'px';//window_width set at presentation.js
	_div.style.zIndex = _zIndex('+');//_zIndex() at general.js
	var forms = _div.getElementsByTagName('form');
	var form = forms[0];
	//close button
	var close_button = document.createElement('img');
	_div.appendChild(close_button);
	close_button.src = 'icon.php?subject=close&height=' + _height;
	close_button.setAttribute('onclick','close_div(this)');//close_div() at presentation.js 
	close_button.setAttribute('title','cerrar');
	close_button.style.display = 'block';
	close_button.style.position = 'absolute';
	close_button.style.padding = 0;
	close_button.style.margin = 0;
	close_button.style.border = 'none';
	close_button.style.background = 'none';
	close_button.style.top = '0px';
	close_button.style.left = _div.offsetWidth - _height - _border_width * 2 + 'px';
	close_button.style.cursor = 'pointer';
	close_button.style.zIndex = 1;
	//title
	var _title = document.createElement('span');
	_div.appendChild(_title);
	_title.innerHTML = 'Reporte de prestaciones';
	_title.style.display = 'block';
	_title.style.position = 'absolute';
	_title.style.padding = 0;
	_title.style.margin = 0;
	_title.style.border = 'none';
	_title.style.background = '#555';
	_title.style.textAlign = 'left';
	_title.style.font = title_font;
	_title.style.color = '#fff';
	_title.style.top = '0px';
	_title.style.left = '0px';
	_title.style.height = _height + 'px';
	_title.style.width = _div.offsetWidth - 2 * _border_width + 'px';
	fit_reporte_prestaciones(form);//fit_reporte_prestaciones() at presentation.js
	//submit button
	var images = _div.getElementsByTagName('IMG');

	for(var i=0; i<images.length; i++)

		if(images[i].getAttribute('class') == 'submit_button')
		{
			var submit_button = images[i];
			submit_button.src = 'icon.php?subject=submit&height=' + _height;
			submit_button.style.display = 'block';
			submit_button.style.position = 'absolute';
			submit_button.style.padding = 0;
			submit_button.style.margin = 0;
			submit_button.style.border = 'none';
			submit_button.style.background = 'none';
			submit_button.style.top = _div.offsetHeight - form.offsetTop - _height - _border_width * 4 + 'px';
			submit_button.style.zIndex = 1;
			submit_button.style.cursor = 'pointer';
			submit_button.style.left = _div.offsetWidth - form.offsetLeft - _height - _border_width * 4 + 'px';
		}
		else if(images[i].getAttribute('class') == 'calendar_button')
		{
			images[i].src = 'icon.php?subject=calendar&height=' + images[i].previousSibling.offsetHeight;
			images[i].style.display = 'block';
			images[i].style.position = 'absolute';
			images[i].style.padding = 0;
			images[i].style.margin = 0;
			images[i].style.border = 'none';
			images[i].style.background = 'none';
			images[i].style.top = images[i].previousSibling.offsetTop + 'px';
			images[i].style.zIndex = 1;
			images[i].style.cursor = 'pointer';
			images[i].style.left = images[i].previousSibling.offsetLeft + images[i].previousSibling.offsetWidth + 'px';
		}

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlRegData = new XMLHttpRequest();

		if (xmlRegData.overrideMimeType)
			xmlRegData.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlRegData = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlRegData = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlRegData.onreadystatechange = function()
	{

		if (xmlRegData.readyState==4 && xmlRegData.status==200 && xmlRegData.responseText != '')
		{
			var div_ = document.createElement('div');
			div_.innerHTML = xmlRegData.responseText;
			var divs = div_.getElementsByTagName('div');

			for(var i=0; i<divs.length; i++)

				if(divs[i].getAttribute('class') == 'registro_div')
					var registro_chkboxes = divs[i].innerHTML;

			var divs = _div.getElementsByTagName('div');

			for(var i=0; i<divs.length; i++)

				if(divs[i].getAttribute('class') == 'registro_div')
					divs[i].innerHTML = registro_chkboxes;

		}

	}

	var params = '';
	xmlRegData.open('POST','get_enterprice_data.php',true);
	xmlRegData.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmlRegData.setRequestHeader("Cache-control","no-cache");
	xmlRegData.setRequestHeader("Content-length", params.length);
	xmlRegData.setRequestHeader("Connection", "close");
	xmlRegData.setRequestHeader("cache-control","no-cache");
	xmlRegData.send(params);
}

function show_concentrado_trabajador(obj)
{
	var _form = obj.parentNode;
	var selects = _form.getElementsByTagName('select');

	for(var i=0; i<selects.length; i++)

		if(selects[i].getAttribute('name') == 'Trabajador' && selects[i].options.length > 0)
			var _trabajador = selects[i].options[selects[i].selectedIndex].value;
		else if(selects[i].getAttribute('name') == 'Servicio' && selects[i].options.length > 0)
			var _servicio = selects[i].options[selects[i].selectedIndex].value;

	var textareas = _form.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'Limite_inferior')
			var _limite_inferior = textareas[i].value;
		else if(textareas[i].getAttribute('name') == 'Limite_superior')
			var _limite_superior = textareas[i].value;

	if(!_trabajador)
	{
		_alert('Elija un trabajador');
		return;
	}
	else if(_limite_inferior == '')
	{
		_alert('Elija un límite inferior');
		return;
	}
	else if(_limite_superior == '')
	{
		_alert('Elija un límite superior');
		return;
	}
	else
	{
		var data = _servicio.split('/');
		_servicio = data[0];
		window.open('concentrado_trabajador.php?trabajador=' + _trabajador + '&servicio=' + _servicio + '&limite_inferior=' + _limite_inferior + '&limite_superior=' + _limite_superior);
	}

}

function show_calculo_anual(obj)
{
	var _form = obj.parentNode;
	var selects = _form.getElementsByTagName('select');

	for(var i=0; i<selects.length; i++)

		if(selects[i].getAttribute('name') == 'Trabajador' && selects[i].options.length > 0)
			var _trabajador = selects[i].options[selects[i].selectedIndex].value;
		else if(selects[i].getAttribute('name') == 'Servicio' && selects[i].options.length > 0)
			var _servicio = selects[i].options[selects[i].selectedIndex].value;

	var textareas = _form.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'Ano')
			var _ano = textareas[i].value;

	if(!_trabajador)
	{
		_alert('Elija un trabajador');
		return;
	}
	else if(_ano == '')
	{
		_alert('Elija un año');
		return;
	}
	else
	{
		var data = _servicio.split('/');
		_servicio = data[0];
		window.open('calculo_anual.php?trabajador=' + _trabajador + '&servicio=' + _servicio + '&ano=' + _ano);
	}

}

function show_concentrado_empresa_nomina(obj)
{
	var _form = obj.parentNode;
	var selects = _form.getElementsByTagName('select');

	for(var i=0; i<selects.length; i++)

		if(selects[i].getAttribute('name') == 'Servicio' && selects[i].options.length > 0)
			var _servicio = selects[i].options[selects[i].selectedIndex].value;
		else if(selects[i].getAttribute('name') == 'Empresa' && selects[i].options.length > 0)
			var _rfc = selects[i].options[selects[i].selectedIndex].value;

	var textareas = _form.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'Limite_inferior')
			var _limite_inferior = textareas[i].value;
		else if(textareas[i].getAttribute('name') == 'Limite_superior')
			var _limite_superior = textareas[i].value;
		else if(textareas[i].getAttribute('name') == 'Nombre')
			var _empresa = textareas[i].value;

	if(!_empresa)
	{
		_alert('Elija una empresa');
		return;
	}
	else if(_limite_inferior == '')
	{
		_alert('Elija un límite inferior');
		return;
	}
	else if(_limite_superior == '')
	{
		_alert('Elija un límite superior');
		return;
	}
	else
	{
		var data = _servicio.split('/');
		_servicio = data[0];
		window.open('concentrado_empresa_nomina.php?empresa=' + _empresa + '&rfc=' + _rfc + '&servicio=' + _servicio + '&limite_inferior=' + _limite_inferior + '&limite_superior=' + _limite_superior);
	}

}

function show_concentrado_registro_patronal_nomina(obj)
{
	var _form = obj.parentNode;
	var textareas = _form.getElementsByTagName('textarea');
	var _registro_patronal = '';

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'Limite_inferior')
			var _limite_inferior = textareas[i].value;
		else if(textareas[i].getAttribute('name') == 'Limite_superior')
			var _limite_superior = textareas[i].value;
		else if(textareas[i].getAttribute('name') == 'Empresa')
			var _empresa = textareas[i].value;

	var inputs = _form.getElementsByTagName('input');

	for(var i=0; i<inputs.length; i++)

		if(inputs[i].getAttribute('name') == 'Solo_asalariados')
			var _solo_asalariados = inputs[i].checked ? 'yes' : 'no';
		else if(inputs[i].getAttribute('name') == 'Solo_asimilados')
			var _solo_asimilados = inputs[i].checked ? 'yes' : 'no';
		else if((inputs[i].getAttribute('type') == 'checkbox' && inputs[i].checked))
			_registro_patronal += '&' + inputs[i].getAttribute('name') + '=' + inputs[i].value ;

	if(_registro_patronal == '')
	{
		_alert('Elija un registro patronal');
		return;
	}
	else if(_limite_inferior == '')
	{
		_alert('Elija un límite inferior');
		return;
	}
	else if(_limite_superior == '')
	{
		_alert('Elija un límite superior');
		return;
	}
	else
	{
		window.open('concentrado_registro_patronal_nomina.php?empresa=' + _empresa + _registro_patronal + '&limite_inferior=' + _limite_inferior + '&limite_superior=' + _limite_superior + '&solo_asalariados=' + _solo_asalariados + '&solo_asimilados=' + _solo_asimilados);
	}

}

function show_concentrado_empresa_imss(obj)
{
	var _form = obj.parentNode;
	var selects = _form.getElementsByTagName('select');

	for(var i=0; i<selects.length; i++)

		if(selects[i].getAttribute('name') == 'Servicio' && selects[i].options.length > 0)
			var _servicio = selects[i].options[selects[i].selectedIndex].value;
		else if(selects[i].getAttribute('name') == 'Empresa' && selects[i].options.length > 0)
			var _empresa = selects[i].options[selects[i].selectedIndex].value;

	var textareas = _form.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'Limite_inferior')
			var _limite_inferior = textareas[i].value;
		else if(textareas[i].getAttribute('name') == 'Limite_superior')
			var _limite_superior = textareas[i].value;

	if(!_empresa)
	{
		_alert('Elija una empresa');
		return;
	}
	else if(_limite_inferior == '')
	{
		_alert('Elija un límite inferior');
		return;
	}
	else if(_limite_superior == '')
	{
		_alert('Elija un límite superior');
		return;
	}
	else
	{
		var data = _servicio.split('/');
		_servicio = data[0];
		window.open('concentrado_empresa_imss.php?empresa=' + _empresa + '&servicio=' + _servicio + '&limite_inferior=' + _limite_inferior + '&limite_superior=' + _limite_superior);
	}

}

function show_concentrado_registro_patronal_imss(obj)
{
	var _form = obj.parentNode;
	var textareas = _form.getElementsByTagName('textarea');
	var _registro_patronal = '';

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'Limite_inferior')
			var _limite_inferior = textareas[i].value;
		else if(textareas[i].getAttribute('name') == 'Limite_superior')
			var _limite_superior = textareas[i].value;
		else if(textareas[i].getAttribute('name') == 'Empresa')
			var _empresa = textareas[i].value;

	var inputs = _form.getElementsByTagName('input');

	for(var i=0; i<inputs.length; i++)

		if((inputs[i].getAttribute('type') == 'checkbox' && inputs[i].checked))
			_registro_patronal += '&' + inputs[i].getAttribute('name') + '=' + inputs[i].value ;

	if(_registro_patronal == '')
	{
		_alert('Elija un registro patronal');
		return;
	}
	else if(_limite_inferior == '')
	{
		_alert('Elija un límite inferior');
		return;
	}
	else if(_limite_superior == '')
	{
		_alert('Elija un límite superior');
		return;
	}
	else
		window.open('concentrado_registro_patronal_imss.php?empresa=' + _empresa + _registro_patronal + '&limite_inferior=' + _limite_inferior + '&limite_superior=' + _limite_superior);

}

function show_reporte_prestaciones(obj)
{
	var _form = obj.parentNode;
	var textareas = _form.getElementsByTagName('textarea');
	var _registro_patronal = '';

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'Limite_inferior')
			var _limite_inferior = textareas[i].value;
		else if(textareas[i].getAttribute('name') == 'Limite_superior')
			var _limite_superior = textareas[i].value;

	var inputs = _form.getElementsByTagName('input');

	for(var i=0; i<inputs.length; i++)

		if((inputs[i].getAttribute('type') == 'checkbox' && inputs[i].checked))
			_registro_patronal += '&' + inputs[i].getAttribute('name') + '=' + inputs[i].value ;

	if(_registro_patronal == '')
	{
		_alert('Elija un registro patronal');
		return;
	}
	else if(_limite_inferior == '')
	{
		_alert('Elija un límite inferior');
		return;
	}
	else if(_limite_superior == '')
	{
		_alert('Elija un límite superior');
		return;
	}
	else
		window.open('reporte_prestaciones.php?limite_inferior=' + _limite_inferior + '&limite_superior=' + _limite_superior + _registro_patronal);

}

function getIframe()
{

	if(getIframe._iframe)
		getIframe._iframe ++;
	else
		getIframe._iframe = 1;

	return getIframe._iframe;
}

function _login()
{

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmllogin = new XMLHttpRequest();

		if (xmllogin.overrideMimeType)
			xmllogin.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmllogin = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmllogin = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmllogin.onreadystatechange = function()
	{

		if (xmllogin.readyState==4 && xmllogin.status==200 && xmllogin.responseText != '')
		{

			if(xmllogin.responseText == 'access')
				document.location.href = 'index_.php';
			else
				_alert('Nombre o contraseña incorrectos');

		}

	}

	var inputs = document.getElementsByTagName('input');

	for(var i=0; i<inputs.length; i++)

		if(inputs[i].getAttribute('name') == 'Usuario')
			var _usuario = inputs[i].value;
		else if(inputs[i].getAttribute('name') == 'Cuenta')
			var _cuenta = inputs[i].value;
		else if(inputs[i].getAttribute('name') == 'Contrasena')
			var _contrasena = inputs[i].value;

	var params = 'usuario=' + _usuario + '&cuenta=' + _cuenta + '&contrasena=' + _contrasena;
	xmllogin.open('POST','_login.php',true);
	xmllogin.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmllogin.setRequestHeader("Content-length", params.length);
	xmllogin.setRequestHeader("Connection", "close");
	xmllogin.setRequestHeader("cache-control","no-cache");
	xmllogin.send(params);
}

function show_logo(_container)//_container is the logo parent node
{

	if(show_logo._val)
		show_logo._val ++;
	else
		show_logo._val = 1;

	var images = _container.getElementsByTagName('IMG');

	for(var i=0; i<images.length; i++)

		if(images[i].getAttribute('class') == 'logo_image')
		{
			var _logo = images[i];
			_logo.style.display = 'block';
			_logo.style.position = 'absolute';
			_logo.style.padding = 0;
			_logo.style.margin = 0;
			_logo.style.border = 'none';
			_logo.style.background = 'none';
			_logo.style.cursor = 'pointer';
			var _empresa = _logo.getAttribute('empresa');
			var _sucursal = _logo.getAttribute('sucursal');
			var _empresa_sucursal = _logo.getAttribute('empresa_sucursal');
			break;
		}

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlLogo = new XMLHttpRequest();

		if (xmlLogo.overrideMimeType)
			xmlLogo.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlLogo = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlLogo = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlLogo.onreadystatechange = function()
	{

		if (xmlLogo.readyState==4 && xmlLogo.status==200 && xmlLogo.responseText != '')
		{
			var div = document.createElement('div');
			div.innerHTML = xmlLogo.responseText;
			var spans = div.getElementsByTagName('span');
			var _width_ = parseInt(spans[0].innerHTML);
			var _height_ = parseInt(spans[1].innerHTML);
			_logo.style.top = parseInt((_container.offsetHeight - _height_) / 2) + 'px';
			_logo.style.left = parseInt((_container.offsetWidth - _width_) / 2) + 'px';
			_logo.style.zIndex = 1;
			_logo.src = 'get_logo.php?height=' + parseInt(_container.offsetHeight * 0.95) + '&width=' + parseInt(_container.offsetWidth * 0.95) + '&empresa=' + _empresa +  '&sucursal=' + _sucursal +  '&empresa_sucursal=' + _empresa_sucursal + '&val=' + show_logo._val;//val is used only to generate a new response
		}

	}

	var params = 'empresa=' + _empresa + '&sucursal=' + _sucursal + '&empresa_sucursal=' + _empresa_sucursal + '&height=' + parseInt(_container.offsetHeight * 0.95) + '&width=' + parseInt(_container.offsetWidth * 0.95) + '&val=' + show_logo._val;//val is used only to generate a new response;
	xmlLogo.open('POST','logo_sizes.php', true);
	xmlLogo.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmlLogo.setRequestHeader("Cache-control","no-cache");
	xmlLogo.setRequestHeader("Content-length", params.length);
	xmlLogo.setRequestHeader("Connection", "close");
	xmlLogo.send(params);
}

function show_sign(_sign)//_sign is the sign image
{

	if(show_sign._val)
		show_sign._val ++;
	else
		show_sign._val = 1;

	if(_sign.getAttribute('trabajador'))
	{
		//getting photo because the sign image goes below photo image 
		var images = _sign.parentNode.getElementsByTagName('IMG');

		for(var i=0; i<images.length; i++)

			if(images[i].getAttribute('class') == 'photo_image')
			{
				var _photo = images[i];
				break;
			}

		var _height = _sign.previousSibling.offsetTop - _photo.offsetTop - _photo.offsetHeight;
		var _width = _photo.offsetWidth;
		var _trabajador = _sign.getAttribute('trabajador');
		var params = 'trabajador=' + _trabajador + '&height=' + _height + '&width=' + _width + '&val=' + show_sign._val;//val is used only to generate a new response;
	}
	else//user sign
	{
		var _height =  _sign.nextSibling.offsetTop - _sign.previousSibling.offsetTop - _sign.previousSibling.offsetHeight;
		var _width = _sign.nextSibling.offsetWidth;
		var _usuario = _sign.getAttribute('usuario');
		var params = 'usuario=' + _usuario + '&height=' + _height + '&width=' + _width + '&val=' + show_sign._val;//val is used only to generate a new response;
	}

	_sign.style.display = 'block';
	_sign.style.position = 'absolute';
	_sign.style.padding = 0;
	_sign.style.margin = 0;
	_sign.style.border = 'none';
	_sign.style.background = 'none';
	_sign.style.cursor = 'pointer';

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlSign = new XMLHttpRequest();

		if (xmlSign.overrideMimeType)
			xmlSign.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlSign = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlSign = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlSign.onreadystatechange = function()
	{

		if (xmlSign.readyState==4 && xmlSign.status==200 && xmlSign.responseText != '')
		{
			var div = document.createElement('div');
			div.innerHTML = xmlSign.responseText;
			var spans = div.getElementsByTagName('span');
			var _width_ = parseInt(spans[0].innerHTML);
			var _height_ = parseInt(spans[1].innerHTML);
			_sign.style.zIndex = 1;

			if(_sign.getAttribute('trabajador'))
			{
				_sign.style.top = _sign.previousSibling.offsetTop - _height_ + 'px';
				_sign.style.left = parseInt((_sign.previousSibling.offsetWidth - _width_) / 2) + _sign.previousSibling.offsetLeft + 'px';
				_sign.src = 'get_sign.php?height=' + _height + '&width=' + _width + '&trabajador=' + _trabajador + '&val=' + show_sign._val;//val is used only to generate a new response
			}
			else
			{
				_sign.style.top = _sign.nextSibling.offsetTop - _height_ + 'px';
				_sign.style.left = (_sign.parentNode.offsetWidth - _width_) / 2 + 'px';
				_sign.src = 'get_sign.php?height=' + _height + '&width=' + _width + '&usuario=' + _usuario + '&val=' + show_sign._val;
			}

		}

	}

	xmlSign.open('POST','sign_sizes.php', true);
	xmlSign.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmlSign.setRequestHeader("Cache-control","no-cache");
	xmlSign.setRequestHeader("Content-length", params.length);
	xmlSign.setRequestHeader("Connection", "close");
	xmlSign.send(params);
}
