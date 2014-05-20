function enable(obj,_name)//enables a select whose name is _name and disables other selects and checkboxes. It's done for entities which have many html select options but only one can be selected. obj is the checkbox
{
	checkboxes = obj.parentNode.parentNode.getElementsByTagName('input');//parentNode is fieldset, parentNode->parentNode is the form
	selects = obj.parentNode.parentNode.getElementsByTagName('select');
	chklen = checkboxes.length;
	selen = selects.length;

	for(i=0; i<chklen;i++)
	{

		if(checkboxes[i].getAttribute('class') == 'enable_select' && checkboxes[i].value != _name)
			checkboxes[i].checked = false;
		else if(checkboxes[i].getAttribute('class') == 'enable_select')
			checkboxes[i].checked = true;

	}

	for(i=0; i<selen;i++)
	{

		if(selects[i].name == _name)
			selects[i].disabled = false;
		else
			selects[i].disabled = true;

	}

}

function form_val(_form)
{
	//displaying the loading image when storing a file
	var _div = _form.parentNode;
	var spans = _div.getElementsByTagName('span');

	for(var i=0; i<spans.length; i++)

		if(spans[i].getAttribute('class') == '_title' && (spans[i].innerHTML == 'Importar archivo' || spans[i].innerHTML == 'Nuevo archivo digital'))
		{
			var image = document.createElement('IMG');
			_div.appendChild(image);
			image.setAttribute('class','loading_image');
			image.style.display = 'block';
			image.style.position = 'absolute';
			image.style.padding = 0;
			image.style.margin = 0;
			image.style.border = 'none';
			image.style.background = 'none';
			image.style.top = parseInt((_div.offsetHeight - 50) / 2) + 'px';
			image.style.left = parseInt((_div.offsetWidth - 50) / 2) + 'px';
			image.src = 'images/loading.gif';
			image.style.zIndex = 1;
			break;
		}

	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{

		//file field
		var inputs = fieldsets[i].getElementsByTagName('input');

		for(var j=0; j<inputs.length; j++)

			if(inputs[j].getAttribute('type') == 'file')
			{

				if(inputs[j].value == "")
				{
					_alert('Especifique un archivo por favor');
					return false;
				}

			}

		//selects
		var selects = _form.getElementsByTagName('select');

		for(var j=0; j<selects.length; j++)

			if(selects[j].getAttribute('required') && selects[j].selectedIndex == -1)
			{
				_alert('El campo "' + element.getAttribute('title') + '" es necesario');
				return false;
			}

		//radios not implemented yet
		//textareas
		var textareas = fieldsets[i].getElementsByTagName('textarea');

		for(var j=0; j<textareas.length; j++)
		{

			if(!chkRegExp(textareas[j]))
				return false;

		}

		//Checking for ids already stored
		var spans = _form.parentNode.getElementsByTagName('span');

		for(var j=0; j<spans.length; j++)

			if(spans[j].getAttribute('class') == '_title')
			{
				var _title = spans[j];
				break;
			}

		if(_title && _title.innerHTML == 'Nuevo registro patronal')
		{
			var textareas = _form.getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)

				if(textareas[j].getAttribute('name') == 'Numero')
				{
					var _numero = textareas[j].value;
					break;
				}

			chKey(_numero,'Registro_patronal','ADD');
		}
		else if(_title && _title.innerHTML == 'Editar registro patronal')
		{
			var textareas = _form.getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)

				if(textareas[j].getAttribute('name') == 'Numero')
					var _numero = textareas[j].value;
				else if(textareas[j].getAttribute('name') == 'id')
					var _id = textareas[j].value;

			if(_numero != _id)
				chKey(_numero,'Registro_patronal','ADD');

		}
		else if(_title && _title.innerHTML == 'Nueva empresa')
		{
			var textareas = _form.getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)

				if(textareas[j].getAttribute('name') == 'RFC')
				{
					var _rfc = textareas[j].value;
					break;
				}

			chKey(_rfc,'Empresa','ADD');
		}
		else if(_title && _title.innerHTML == 'Editar empresa')
		{
			var textareas = _form.getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)

				if(textareas[j].getAttribute('name') == 'RFC')
					var _rfc = textareas[j].value;
				else if(textareas[j].getAttribute('name') == 'id')
					var _id = textareas[j].value;

			if(_rfc != _id)
				chKey(_rfc,'Empresa','ADD');

		}
		else if(_title && _title.innerHTML == 'Nuevo trabajador')
		{
			var textareas = _form.getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)

				if(textareas[j].getAttribute('name') == 'RFC')
				{
					var _rfc = textareas[j].value;
					break;
				}

			chKey(_rfc,'Trabajador','ADD');
		}
		else if(_title && _title.innerHTML == 'Editar trabajador')
		{
			var textareas = _form.getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)

				if(textareas[j].getAttribute('name') == 'RFC')
					var _rfc = textareas[j].value;
				else if(textareas[j].getAttribute('name') == 'id')
					var _id = textareas[j].value;

			if(_rfc != _id)
				chKey(_rfc,'Trabajador','ADD');

		}
		else if(_title && _title.innerHTML == 'Nuevo salario mínimo')
		{
			var textareas = _form.getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)

				if(textareas[j].getAttribute('name') == 'Codigo')
					var _codigo = textareas[j].value;
				else if(textareas[j].getAttribute('name') == 'Nombre')
					var _nombre = textareas[j].value;

			chKey(_codigo + ',' + _nombre,'Salario_minimo','ADD');
		}

	}

	return true;
}

function chKey(val,tabla,mode)//check if val is already at database
{
	if(chKey._val)
		chKey._val ++;
	else
		chKey._val = 1;

	if(tabla == 'Salario_minimo')
		var id = 'Codigo';//not need to check id but "Codigo"
	else
		var id = getID(tabla);

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlhttp4 = new XMLHttpRequest();

		if (xmlhttp4.overrideMimeType)
			xmlhttp4.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlhttp4 = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlhttp4 = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlhttp4.onreadystatechange = function()
	{

		if (xmlhttp4.readyState==4 && xmlhttp4.status==200)
		{

			if(xmlhttp4.responseText == 'false')
				_alert('El campo "' + underscoreOff(id) + '" contiene un valor que ya ha sido usado y no se guardara');//underscoreOff() at presentation.js

		}

	}

	tabla = underscoreOn(tabla);//underscoreOn() at presentation.js

	if(tabla == 'Salario_minimo')
	{
		var data = val.split(',');
		xmlhttp4.open('POST','get_authorization.php?tabla=' + tabla + '&Codigo=' + data[0] + '&Nombre=' + data[1] + '&mode=' + mode + '&_val=' + chKey._val,true);//"Codigo" is gonna be checked, not id
	}
	else
		xmlhttp4.open('POST','get_authorization.php?tabla=' + tabla + '&id=' + id + '&val=' + val + '&mode=' + mode + '&_val=' + chKey._val,true);

	xmlhttp4.setRequestHeader("cache-control","no-cache");
	xmlhttp4.send('');
}

function chkRegExp(element)//compares an element value  with its regular expresion
{
	var _title = element.getAttribute('title');
	var _name = element.name;
	var value = element.value;
	var required = element.getAttribute('required');
	re = new Object();
	re['RFC'] = /[A-Z,�,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?/g;//anteriormente [A-Z0-9]{3,4}[0-9]{6}[A-Z0-9]{3,5}
	re['CP'] = /\d{5}/g;
	re['Fechas'] = /([\d]{4}-[\d]{2}-[\d]{2},)+/g;
	re['Fecha'] = /[\d]{4}-[\d]{2}-[\d]{2}/g;
	re['Numero_de_registro_patronal'] = /[A-Z][\d]{2}-[\d]{5}-[\d]{2}-[\d]/g; 
	re['Fraccion'] = /[\d]{4}/g;
	re['Prima'] = /[\d]\.[\d]{2,5}/g;
	re['Folio_imss'] = /[a-zA-Z\d\u00A0]{0,20}/g;
	re['Numero_de_instrumento'] = /\d{0,10}/g; 
	re['Volumen'] = /\d{0,10}/g;
	re['RPP'] = /\d{0,10}/g;
	re['Tomo'] = /[a-zA-Z\d\u00A0]{0,10}/g;
	re['Libro'] = /[a-zA-Z\d\u00A0]{0,20}/g;
	re['Partida'] = /\d{0,10}/g;
	re['Numero_de_notario'] = /\d{0,5}/g;
	re['Numero_de_folio_mercantil'] = /\d{0,10}/g;
	//re['Correo_electronico'] = /(([\w-.])+@\1+\.\1+)|\2\.\1/g;
	re['Monto'] = /[\d]{1,5}\.[\d]{2}/g;
	re['CURP'] = /[A-Z][A,E,I,O,U,X][A-Z]{2}[0-9]{2}[0-1][0-9][0-3][0-9][M,H][A-Z]{2}[B,C,D,F,G,H,J,K,L,M,N,�,P,Q,R,S,T,V,W,X,Y,Z]{3}[0-9,A-Z][0-9]/g; // /[A-Z]{4}-\d{6}-[A-Z0-9]{6}-[A-Z0-9]{2}/g;
	re['Numero_ife'] = /\d{13}/g;
	re['Numero_imss'] = /\d{2}-\d{2}-\d{2}-\d{4}-\d/g;
	re['UMF'] = /\d{2}/g;
	re['Numero_de_credito_infonavit'] = /\d{10}/g;
	re['Numero_fonacot'] = /\d{0,15}/g;
	//re['Numero_de_cuenta_bancaria'] = /\d{0,18}/g;
	re['Salario_diario_integrado'] = /\d{1,4}\.\d{2}/g;
	re['_Restrictions_'] = /\c[a-zA-Z0-9]|\u2028|\u2029/gm;//General restrictions(carriage return,linefeed,horizontal tab,vertical tab,control combinations, line separator, paragraph separator) for fields not covered above

	re.look4property = function(val)
	{

		for(var prop in this)

			if(prop == val)
				return true;

		return false;
	}

	if(value.match(re['_Restrictions_']))
	{
		_alert('Existen caracteres no validos en el campo ' + _title);
		return false;
	}
	else if(re.look4property(_name))
	{
		values = value.match(re[_name]);
	}
	else if(required && (value == ''))
	{

		if(_title)
			_alert('El campo ' + _title + ' es necesario');
		else
			_alert('Por favor escriba toda la información necesaria');

		return false;
	}
	else
	{
		var values = new Array();
		values[0] = 1;
	}

	if(values == null)
	{
		_alert('El campo ' + _title + ' no está bien escrito');
		return false;
	}
	else if(values.length > 2)
	{
		_alert('Por favor no escriba el caracter \'' + value.charAt(values[0].length) + '\' en el campo ' + _title);
		return false;
	}
	else if(values[0] == '')
	{
		_alert('El campo ' + _title + ' no está bien escrito');
		return false;
	}

	return true;
}

function _submit(mode,_form,_table)
{

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlSubmit = new XMLHttpRequest();

		if (xmlSubmit.overrideMimeType)
			xmlSubmit.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlSubmit = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlSubmit = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlSubmit.onreadystatechange = function()
	{

		if (xmlSubmit.readyState==4 && xmlSubmit.status==200)
		{
			close_div(_form);
			_form.parentNode.innerHTML = xmlSubmit.responseText;
			var divs = document.getElementsByTagName('div');

			for(var i=0; i<divs.length; i++)

				if(divs[i].getAttribute('class') == 'options' && divs[i].getAttribute('dbtable2') == _table)
				{

					if(divs[i].parentNode.tagName == 'FIELDSET')
						get_options(divs[i].getAttribute('mode'),divs[i].getAttribute('dbtable1'),_table,divs[i].getAttribute('_id'),'First',divs[i].parentNode);
					else if(divs[i].parentNode.tagName == 'DIV')
						_load(_table);

				}

		}

	}

	if(_table == 'Nomina')
		store_nomina(_form);
	else if(_table == 'Aguinaldo')
		store_aguinaldo(_form);

	var params = getParams(_form);
	xmlSubmit.open('POST','submit.php?tabla=' + escape(_table) + '&mode=' + mode,true);
	xmlSubmit.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmlSubmit.setRequestHeader("Cache-control","no-cache");
	xmlSubmit.setRequestHeader("Content-length", params.length);
	xmlSubmit.setRequestHeader("Connection", "close");

	if((_table !='Tabla' && form_val(_form)) || _table =='Tabla')
	{
		var _div = _form.parentNode;
		var image = document.createElement('img');
		_div.appendChild(image);
		image.src = 'images/loading.gif';
		image.style.display = 'block';
		image.style.position = 'absolute';
		image.style.padding = 0;
		image.style.margin = 0;
		image.style.border = 'none';
		image.style.background = 'none';
		image.style.width = '50px';
		image.style.height = '50px';
		image.style.top = parseInt((_div.offsetHeight - image.offsetHeight) / 2) + 'px';
		image.style.left = parseInt((_div.offsetWidth - image.offsetWidth) / 2) + 'px';
		image.style.zIndex = 2;
		xmlSubmit.send(params);
	}

}

function getParams(_form)
{
	var params = '';

	//textareas
	var textareas = _form.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)
	{
		var _value = textareas[i].value;
		_value = _value.replace('+','%2B');
		params += (params == '' ? '' : '&') + textareas[i].getAttribute('name') + '=' + _value;
	}

	//selects
	var selects = _form.getElementsByTagName('select');

	for(var i=0; i<selects.length; i++)
		params += (params == '' ? '' : '&') + selects[i].getAttribute('name') + '=' + selects[i].options[selects[i].selectedIndex].value ;

	//checkboxes & password
	var inputs = _form.getElementsByTagName('input');

	for(var i=0; i<inputs.length; i++)

		if((inputs[i].getAttribute('type') == 'checkbox' && inputs[i].checked) || inputs[i].getAttribute('type') == 'password')
					params += (params == '' ? '' : '&') + inputs[i].getAttribute('name') + '=' + inputs[i].value ;

	return params;
}

function open_file(name)
{
	window.open("open_file.php?name=" + name);
}

//This functions are called by concepto's forms
function get_options(mode,dbtable1,dbtable2,id,_time,fieldset)//gets options list. If mode == 'DRAW' it gets only the related options to dbtable1 with id and the list is ineditable. Otherwise, if _time is == 'First' it gets all related options otherwise it gets all options but marks the releted ones.
{

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlhttp = new XMLHttpRequest();

		if (xmlhttp.overrideMimeType)
			xmlhttp.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlhttp.onreadystatechange = function()
	{

		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{

			var divs = fieldset.getElementsByTagName('div');

			for(var i=0; i<divs.length; i++)

				if(divs[i].getAttribute('class') == 'options')
				{
					var _options = divs[i];
					_options.innerHTML = xmlhttp.responseText;
					break;
				}

			if(mode == 'EDIT' && dbtable2 != 'Aportacion_del_trabajador_al_fondo_de_ahorro' && dbtable2 != 'Incapacidad' && dbtable2 != 'Vacaciones' && dbtable2 != 'Pension_alimenticia' && dbtable2 != 'Prestamo_administradora' && dbtable2 != 'Prestamo_caja' && dbtable2 != 'Prestamo_cliente' && dbtable2 != 'Prestamo_del_fondo_de_ahorro' && dbtable2 != 'Fondo_de_garantia' && dbtable2 != 'Retencion_FONACOT' && dbtable2 != 'Retencion_INFONAVIT' && dbtable2 != 'Pago_por_seguro_de_vida' && dbtable2 != 'Salario_diario' && dbtable2 != 'Baja' && dbtable2 != 'Descuento_pendiente' && dbtable2 != 'Sucursal' && dbtable2 != 'Trabajador_Sucursal' && dbtable2 != 'Trabajador_Salario_minimo' && dbtable2 != 'Servicio_Trabajador' && dbtable2 != 'Contrato' && dbtable2 != 'Banco' && dbtable2 != 'UMF' && dbtable2 != 'Tipo' && dbtable2 != 'Base' && dbtable2 != 'Prima' && dbtable2 != 'Representante_legal' && dbtable2 != 'Socio' && dbtable2 != 'Apoderado' && dbtable2 != 'Archivo_digital' && dbtable2 != 'Sello_digital' && dbtable2 != 'Instrumento_notarial' && dbtable2 != 'Regimen_fiscal' && dbtable2 != 'Servicio_adicional' && dbtable2 != 'Servicio_Registro_patronal' && dbtable2 != 'Monto_fijo_mensual' && dbtable2 != 'Porcentaje_de_descuento' && dbtable2 != 'Factor_de_descuento' && dbtable2 != 'Servicio_Empresa' && dbtable2 != 'Nomina_propuesta' && dbtable1 != 'Empresa' && dbtable1 != 'Sucursal')
				set_values(dbtable1,dbtable2,id,fieldset);

			fit_content(fieldset);//fit_content at presentation.js
		}

	}

	//getting values from inputs at options_search div to search for
	var tables = fieldset.getElementsByTagName('table');

	for(var i=0; i<tables.length; i++)

		if(tables[i].getAttribute('class') == 'search_table')
		{
			var options_search_columns = tables[i].rows[0].cells;
			break;
		}

	var len = options_search_columns.length;
	var values = 'values=';

	for(i=1; i<len; i++)
	{

		if(i == len-1)
			values += options_search_columns[i].childNodes[0].value;
		else
			values += options_search_columns[i].childNodes[0].value + ',';

	}

	if(!id)
		var id = null;

	if(!_time)
		var _time = null;

	if(dbtable2 == 'Apoderado')
		xmlhttp.open("POST","get_Apoderado.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Aportacion_del_trabajador_al_fondo_de_ahorro')
		xmlhttp.open("POST","get_Aportacion_del_trabajador_al_fondo_de_ahorro.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Archivo_digital')
		xmlhttp.open("POST","get_Archivo_digital.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Baja')
		xmlhttp.open("POST","get_Baja.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Banco')
		xmlhttp.open("POST","get_Banco.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Base')
		xmlhttp.open("POST","get_Base.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Contrato')
		xmlhttp.open("POST","get_Contrato.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Descuento_pendiente')
		xmlhttp.open("POST","get_Descuento_pendiente.php?mode="+mode+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Empresa')
		xmlhttp.open("POST","get_Empresa.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Factor_de_descuento')
		xmlhttp.open("POST","get_Factor_de_descuento.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Fondo_de_garantia')
		xmlhttp.open("POST","get_Fondo_de_garantia.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Incapacidad')
		xmlhttp.open("POST","get_Incapacidad.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Instrumento_notarial')
		xmlhttp.open("POST","get_Instrumento_notarial.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Monto_fijo_mensual')
		xmlhttp.open("POST","get_Monto_fijo_mensual.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Nomina_propuesta')
		xmlhttp.open("POST","get_Nomina_propuesta.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Pago_por_seguro_de_vida')
		xmlhttp.open("POST","get_Pago_por_seguro_de_vida.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Pension_alimenticia')
		xmlhttp.open("POST","get_Pension_alimenticia.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Porcentaje_de_descuento')
		xmlhttp.open("POST","get_Porcentaje_de_descuento.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Prima')
		xmlhttp.open("POST","get_Prima.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Regimen_fiscal')
		xmlhttp.open("POST","get_Regimen_fiscal.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Registro_patronal')
		xmlhttp.open("POST","get_Registro_patronal.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Representante_legal')
		xmlhttp.open("POST","get_Representante_legal.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Retencion_FONACOT')
		xmlhttp.open("POST","get_Retencion_FONACOT.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Retencion_INFONAVIT')
		xmlhttp.open("POST","get_Retencion_INFONAVIT.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Salario_diario')
		xmlhttp.open("POST","get_Salario_diario.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Salario_minimo')
		xmlhttp.open("POST","get_Salario_minimo.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Sello_digital')
		xmlhttp.open("POST","get_Sello_digital.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Servicio')
		xmlhttp.open("POST","get_Servicio.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Servicio_adicional')
		xmlhttp.open("POST","get_Servicio_adicional.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Servicio_Empresa')
		xmlhttp.open("POST","get_Servicio_Empresa.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Servicio_Registro_patronal')
		xmlhttp.open("POST","get_Servicio_Registro_patronal.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Servicio_Trabajador')
		xmlhttp.open("POST","get_Servicio_Trabajador.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Socio')
		xmlhttp.open("POST","get_Socio.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Sucursal')
		xmlhttp.open("POST","get_Sucursal.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Tipo')
		xmlhttp.open("POST","get_Tipo.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Trabajador')
		xmlhttp.open("POST","get_Trabajador.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Trabajador_Salario_minimo')
		xmlhttp.open("POST","get_Trabajador_Salario_minimo.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Trabajador_Sucursal')
		xmlhttp.open("POST","get_Trabajador_Sucursal.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'UMF')
		xmlhttp.open("POST","get_UMF.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Vacaciones')
		xmlhttp.open("POST","get_Vacaciones.php?mode="+mode+"&values="+values+'&dbtable='+dbtable1+'&id='+id+'&time='+_time,true);
	else if(dbtable2 == 'Prestamo_administradora' || dbtable2 == 'Prestamo_caja' || dbtable2 == 'Prestamo_cliente' || dbtable2 == 'Prestamo_del_fondo_de_ahorro')
		xmlhttp.open("POST","get_Concept.php?mode="+mode+"&values="+values+'&dbtable='+dbtable2+'&id='+id+'&time='+_time,true);//get_Concept.php works different

	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmlhttp.setRequestHeader("Cache-control","no-cache");
	xmlhttp.setRequestHeader("Content-length", values.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(values);
}

function set_values(dbtable1,dbtable2,id,fieldset)//if there are options related to this dbtable it sets them checked
{

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlhttp1 = new XMLHttpRequest();

		if (xmlhttp1.overrideMimeType)
			xmlhttp1.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlhttp1 = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlhttp1.onreadystatechange = function()
	{

		if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
		{
			//getting options div
			var divs = fieldset.getElementsByTagName('div');
			var txt = xmlhttp1.responseText;

			for(var i=0; i<divs.length; i++)

				if(divs[i].getAttribute('class') == 'options')
				{
					var _options = divs[i];
					break;
				}

			if(_options.firstChild)
				chkOptions(txt,fieldset);

		}

	}

	if(dbtable2 == 'Trabajador')
		xmlhttp1.open("POST","get_actual_Trabajador.php?dbtable="+dbtable1+"&id="+id,true);
	else if(dbtable2 == 'Apoderado')
		xmlhttp1.open("POST","get_actual_Apoderado.php?dbtable="+dbtable1+"&id="+id,true);
	else if(dbtable2 == 'Registro_patronal')
		xmlhttp1.open("POST","get_actual_Registro_patronal.php?dbtable="+dbtable1+"&id="+id,true);
	else if(dbtable2 == 'Empresa_administradora')
		xmlhttp1.open("POST","get_actual_Empresa_administradora.php?dbtable="+dbtable1+"&id="+id,true);
	else if(dbtable2 == 'Empresa')
		xmlhttp1.open("POST","get_actual_Empresa.php?dbtable="+dbtable1+"&id="+id,true);
	else if(dbtable2 == 'Comisionista')
		xmlhttp1.open("POST","get_actual_Comisionista.php?dbtable="+dbtable1+"&id="+id,true);
	else if(dbtable2 == 'Instrumento_notarial')
		xmlhttp1.open("POST","get_actual_Instrumento_notarial.php?dbtable="+dbtable1+"&id="+id,true);
	else if(dbtable2 == 'Servicio')
		xmlhttp1.open("POST","get_actual_Servicio.php?dbtable="+dbtable1+"&id="+escape(id),true);
	else if(dbtable2 == 'Establecimiento')
		xmlhttp1.open("POST","get_actual_Establecimiento.php?dbtable="+dbtable1+"&id="+id,true);
	else if(dbtable2 == 'Oficina')
		xmlhttp1.open("POST","get_actual_Oficina.php?dbtable="+dbtable1+"&id="+id,true);
	else if(dbtable2 == 'Servicio_adicional')
		xmlhttp1.open("POST","get_actual_Servicio_adicional.php?dbtable="+dbtable1+"&id="+id,true);
	else if(dbtable2 == 'Sucursal')
		xmlhttp1.open("POST","get_actual_Sucursal.php?dbtable="+dbtable1+"&id="+id,true);
	else if(dbtable2 == 'Salario_minimo')
		xmlhttp1.open("POST","get_actual_Salario_minimo.php?dbtable="+dbtable1+"&id="+id,true);

	xmlhttp1.setRequestHeader("cache-control","no-cache");
	xmlhttp1.send('');
}

function chkOptions(txt,fieldset)//When editting.. set checked options related this dbtable
{
	var ids = txt.split('>>');
	//getting options data
	var divs = fieldset.getElementsByTagName('div');

	for(var i=0; i<divs.length; i++)

		if(divs[i].getAttribute('class') == 'options')
		{
			var _options = divs[i];
			break;
		}

	//getting options_table data
	var _options_table = _options.childNodes[0];

	if(_options_table)
	{

		for(var i=0; i<ids.length - 1; i++)//-1 because of the last comma id1,id2,
		{

			for(var j=0; j<_options_table.rows.length; j++)
			{
				var _checkbox = _options_table.rows[j].cells[0].firstChild;

				if(_checkbox.getAttribute('value')  == ids[i])
				{

					if(_options_table.rows[j].getAttribute('onclick').match(/multiple/g))
						select_multiple_option(_options_table.rows[j]);
					else
						select_option(_options_table.rows[j]);

					break;
				}

			}

		}

	}

}

function click_all(fieldset)
{
	var divs = fieldset.getElementsByTagName('div');

	for(var i=0; i<divs.length; i++)

		if(divs[i].getAttribute('id') == 'options')
		{
			_options = divs[i];
			break;
		}

	//getting options_table data
	_options_table = _options.childNodes[0];

	if(_options_table)
	{
		var _options_table_rows = _options_table.getElementsByTagName('tr');

		for(i=0; i<_options_table_rows.length; i++)
		{
			//checkbox
			_checkbox = _options_table_rows[i].childNodes[0].childNodes[0];

			if(_checkbox.checked)
				_checkbox.checked = false;
			else
				_checkbox.checked = true;

		}

	}

}

function add_row(obj)//adds a row to "tabla de prestamo"
{
	var fieldset = obj.parentNode.parentNode.parentNode.parentNode;
	var tablas = fieldset.getElementsByTagName('table');
	var tabla = tablas[1];
	var rows = tabla.getElementsByTagName('tr');
	//getting last numero de descuento
	var n = rows.length + 1;
	//tr
	var _tr = document.createElement('tr');
	//Numero de descuento
	var td_numero_de_descuento = document.createElement('td');
	var textarea_numero_de_descuento = document.createElement('textarea');
	textarea_numero_de_descuento.setAttribute('rows',5);
	textarea_numero_de_descuento.setAttribute('cols',20);
	textarea_numero_de_descuento.setAttribute('name','Numero_de_descuento[]');
	textarea_numero_de_descuento.setAttribute('readonly',true);
	textarea_numero_de_descuento.innerHTML= n;
	td_numero_de_descuento.appendChild(textarea_numero_de_descuento);
	_tr.appendChild(td_numero_de_descuento);
	//Fecha de descuento
	var td_fecha_de_descuento = document.createElement('td');
	var textarea_fecha_de_descuento = document.createElement('textarea');
	textarea_fecha_de_descuento.setAttribute('rows',5);
	textarea_fecha_de_descuento.setAttribute('cols',20);
	textarea_fecha_de_descuento.setAttribute('name','Fecha_de_descuento[]');
	textarea_fecha_de_descuento.setAttribute('required',true);
	td_fecha_de_descuento.appendChild(textarea_fecha_de_descuento);
	_tr.appendChild(td_fecha_de_descuento);
	//Cantidad a descontar
	var td_cantidad_a_descontar = document.createElement('td');
	var textarea_cantidad_a_descontar = document.createElement('textarea');
	textarea_cantidad_a_descontar.setAttribute('rows',5);
	textarea_cantidad_a_descontar.setAttribute('cols',20);
	textarea_cantidad_a_descontar.setAttribute('name','Cantidad_a_descontar[]');
	textarea_cantidad_a_descontar.setAttribute('required',true);
	td_cantidad_a_descontar.appendChild(textarea_cantidad_a_descontar);
	_tr.appendChild(td_cantidad_a_descontar);
	tabla.appendChild(_tr);
	fit_tabla_de_prestamo(fieldset);
}

function add_row_subsidio(obj)//adds a row to "tabla subsidio"
{
	var fieldset = obj.parentNode.parentNode.parentNode.parentNode;
	var tablas = fieldset.getElementsByTagName('table');
	var tabla = tablas[1];
	//tr
	var _tr = document.createElement('tr');
	//Desde_ingresos_de
	var td_desde_ingresos_de = document.createElement('td');
	var textarea_desde_ingresos_de = document.createElement('textarea');
	textarea_desde_ingresos_de.setAttribute('name','Desde_ingresos_de[]');
	td_desde_ingresos_de.appendChild(textarea_desde_ingresos_de);
	_tr.appendChild(td_desde_ingresos_de);
	//Hasta_ingresos_de
	var td_hasta_ingresos_de = document.createElement('td');
	var textarea_hasta_ingresos_de = document.createElement('textarea');
	textarea_hasta_ingresos_de.setAttribute('name','Hasta_ingresos_de[]');
	textarea_hasta_ingresos_de.setAttribute('required',true);
	td_hasta_ingresos_de.appendChild(textarea_hasta_ingresos_de);
	_tr.appendChild(td_hasta_ingresos_de);
	//Subsidio
	var td_subsidio = document.createElement('td');
	var textarea_subsidio = document.createElement('textarea');
	textarea_subsidio.setAttribute('name','Subsidio[]');
	textarea_subsidio.setAttribute('required',true);
	td_subsidio.appendChild(textarea_subsidio);
	_tr.appendChild(td_subsidio);
	tabla.appendChild(_tr);
	fit_tabla(fieldset);
}

function add_row_isr(obj)//adds a row to "tabla isr"
{
	var fieldset = obj.parentNode.parentNode.parentNode.parentNode;
	var tablas = fieldset.getElementsByTagName('table');
	var tabla = tablas[1];
	//tr
	var _tr = document.createElement('tr');
	//Limite_inferior
	var td_limite_inferior = document.createElement('td');
	var textarea_limite_inferior = document.createElement('textarea');
	textarea_limite_inferior.setAttribute('name','Limite_inferior[]');
	td_limite_inferior.appendChild(textarea_limite_inferior);
	_tr.appendChild(td_limite_inferior);
	//Limite_superior
	var td_limite_superior = document.createElement('td');
	var textarea_limite_superior = document.createElement('textarea');
	textarea_limite_superior.setAttribute('name','Limite_superior[]');
	textarea_limite_superior.setAttribute('required',true);
	td_limite_superior.appendChild(textarea_limite_superior);
	_tr.appendChild(td_limite_superior);
	//Cuota_fija
	var td_cuota_fija = document.createElement('td');
	var textarea_cuota_fija = document.createElement('textarea');
	textarea_cuota_fija.setAttribute('name','Cuota_fija[]');
	textarea_cuota_fija.setAttribute('required',true);
	td_cuota_fija.appendChild(textarea_cuota_fija);
	_tr.appendChild(td_cuota_fija);
	//Porcentaje
	var td_porcentaje = document.createElement('td');
	var textarea_porcentaje = document.createElement('textarea');
	textarea_porcentaje.setAttribute('name','Porcentaje[]');
	textarea_porcentaje.setAttribute('required',true);
	td_porcentaje.appendChild(textarea_porcentaje);
	_tr.appendChild(td_porcentaje);
	tabla.appendChild(_tr);
	fit_tabla(fieldset);
}

function sub_row(obj)//substract a row to "tabla de prestamo"
{
	var fieldset = obj.parentNode.parentNode.parentNode.parentNode;
	var tablas = fieldset.getElementsByTagName('table');
	var tabla = tablas[1];
	var rows = tabla.getElementsByTagName('tr');

	if(rows.length > 0)
	{
		var _tr = rows[rows.length - 1];
		_tr.parentNode.removeChild(_tr);
		fit_tabla_de_prestamo(fieldset);
	}

}

function _sub_row(obj)//substract a row to "tabla subsidio" and "tabla ISR"
{
	var fieldset = obj.parentNode.parentNode.parentNode.parentNode;
	var tablas = fieldset.getElementsByTagName('table');
	var tabla = tablas[1];
	var rows = tabla.getElementsByTagName('tr');

	if(rows.length > 0)
	{
		var _tr = rows[rows.length - 1];
		_tr.parentNode.removeChild(_tr);
		fit_tabla(fieldset);
	}

}

function _autocomplete(obj,table,column)
{
	_autocomplete.obj = obj;

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlcomplete = new XMLHttpRequest();

		if (xmlcomplete.overrideMimeType)
			xmlcomplete.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlcomplete = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlcomplete = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlcomplete.onreadystatechange = function()
	{

		if (xmlcomplete.readyState==4 && xmlcomplete.status==200 && xmlcomplete.responseText != '')
		{
			var divs = obj.parentNode.getElementsByTagName('div');

			for(var i=0; i<divs.length; i++)

				if(divs[i].getAttribute('class') == 'autocomplete_div' || divs[i].getAttribute('className') == 'autocomplete_div')
				{
					divs[i].parentNode.removeChild(divs[i]);
					break;
				}

			var _div = document.createElement('div');
			obj.parentNode.appendChild(_div);
			_div.innerHTML = xmlcomplete.responseText;
			_div.style.display = 'block';
			_div.style.position = 'absolute';
			_div.style.padding = 0;
			_div.style.margin = 0;
			_div.style.border = _border_width + 'px solid #555';
			_div.style.borderTop = _border_width + 'px solid #fff';
			_div.style.borderBottomLeftRadius = '10px';
			_div.style.borderBottomRightRadius = '10px';
			_div.style.MozBorderRadiusBottomleft = '10px';
			_div.style.MozBorderRadiusBottomright = '10px';
			_div.style.WebkitBorderBottomLeftRadius = '10px';
			_div.style.WebkitBorderBottomRightRadius = '10px';
			_div.style.background = '#fff';
			_div.style.font = font;
			_div.style.color = '#333';
			_div.style.top = obj.offsetTop + obj.offsetHeight + 1 + 'px';
			_div.style.left = obj.offsetLeft + 'px';
			_div.style.width = obj.offsetWidth + 'px';
			_div.style.overflow = 'scroll';
			_div.style.zIndex = 15;
			_div.setAttribute('class','autocomplete_div');
			_div.setAttribute('onblur','close_div(this)');
			var spans = _div.getElementsByTagName('span');

			for(var i=0; i<spans.length; i++)
			{
				spans[i].style.display = 'block';
				spans[i].setAttribute('onclick','copyvalue(this)');
			}

			if(_div.offsetHeight > 100)
				_div.style.height = '100px';

		}

	}

	var params = 'val=' + obj.value;
	xmlcomplete.open('POST','autocomplete.php?table=' + escape(table) + '&column=' + escape(column),true);
	xmlcomplete.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmlcomplete.setRequestHeader("Cache-control","no-cache");
	xmlcomplete.setRequestHeader("Content-length", params.length);
	xmlcomplete.setRequestHeader("Connection", "close");
	xmlcomplete.setRequestHeader("cache-control","no-cache");
	xmlcomplete.send(params);
}

function copyvalue(obj)
{
	_autocomplete.obj.value = obj.innerHTML;

	if(_autocomplete.obj.getAttribute('class') == 'nombre_textarea' || _autocomplete.obj.getAttribute('className') == 'nombre_textarea')
	{
		var textareas = _autocomplete.obj.parentNode.getElementsByTagName('textarea');

		for(var i=0; i<textareas.length; i++)

			if(textareas[i].getAttribute('class') == 'codigo_textarea' || textareas[i].getAttribute('className') == 'codigo_textarea')
			{
				get_code(textareas[i]);
				break;
			}

	}
	else if(_autocomplete.obj.getAttribute('class') == 'trabajador_textarea' || _autocomplete.obj.getAttribute('className') == 'trabajador_textarea')
		get_trabajador_data(_autocomplete.obj.parentNode);
	else if(_autocomplete.obj.getAttribute('class') == 'empresa_nombre_textarea' || _autocomplete.obj.getAttribute('className') == 'empresa_nombre_textarea')
		get_empresa_data(_autocomplete.obj.parentNode);//to assign branches to workers
	else if(_autocomplete.obj.getAttribute('class') == 'empresa_textarea' || _autocomplete.obj.getAttribute('className') == 'empresa_textarea')
		get_enter_data(_autocomplete.obj.parentNode);//to generate concentrate of workers info
	else if(_autocomplete.obj.getAttribute('class') == 'enterprice_textarea' || _autocomplete.obj.getAttribute('className') == 'enterprice_textarea')
		get_enterprice_data(_autocomplete.obj.parentNode);//to generate concentrate of workers info

	var _div = obj.parentNode;
	_div.parentNode.removeChild(_div);
}

function get_code(obj)//sets the "Codigo" for "Salario m�nimo" obj is textarea
{

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlcode = new XMLHttpRequest();

		if (xmlcode.overrideMimeType)
			xmlcode.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlcode = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlcode = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlcode.onreadystatechange = function()
	{

		if (xmlcode.readyState==4 && xmlcode.status==200 && xmlcode.responseText != '')
			obj.value = xmlcode.responseText;

	}

	var textareas = obj.parentNode.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'Nombre')
		{
			var nombre = textareas[i].value;
			break;
		}

	if(nombre != '')
	{
		var params = 'val=' + nombre;
		xmlcode.open('POST','get_code.php',true);
		xmlcode.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
		xmlcode.setRequestHeader("Cache-control","no-cache");
		xmlcode.setRequestHeader("Content-length", params.length);
		xmlcode.setRequestHeader("Connection", "close");
		xmlcode.setRequestHeader("cache-control","no-cache");
		xmlcode.send(params);
	}

}

function get_trabajador_data(obj)//sets data for "Trabajador". obj is div
{

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlTrabajadorData = new XMLHttpRequest();

		if (xmlTrabajadorData.overrideMimeType)
			xmlTrabajadorData.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlTrabajadorData = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlTrabajadorData = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlTrabajadorData.onreadystatechange = function()
	{

		if (xmlTrabajadorData.readyState==4 && xmlTrabajadorData.status==200 && xmlTrabajadorData.responseText != '')
		{
			var _div = document.createElement('div');
			_div.innerHTML = xmlTrabajadorData.responseText;
			var selects = _div.getElementsByTagName('select');

			for(var i=0; i<selects.length; i++)

				if(selects[i].getAttribute('class') == 'trabajador_rfc_select')
					var trabajador_rfc_options = selects[i].innerHTML;
				else if(selects[i].getAttribute('class') == 'servicio_select')
					var servicio_options = selects[i].innerHTML;

			var selects = obj.getElementsByTagName('select');

			for(var i=0; i<selects.length; i++)

				if(selects[i].getAttribute('class') == 'trabajador_rfc_select')
					selects[i].innerHTML = trabajador_rfc_options;
				else if(selects[i].getAttribute('class') == 'servicio_select')
					selects[i].innerHTML = servicio_options;

		}

	}

	var textareas = obj.parentNode.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('class') == 'trabajador_textarea')
		{
			var trabajador = textareas[i].value;
			break;
		}

	if(trabajador != '')
	{
		var params = 'val=' + trabajador;
		xmlTrabajadorData.open('POST','get_trabajador_data.php',true);
		xmlTrabajadorData.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
		xmlTrabajadorData.setRequestHeader("Cache-control","no-cache");
		xmlTrabajadorData.setRequestHeader("Content-length", params.length);
		xmlTrabajadorData.setRequestHeader("Connection", "close");
		xmlTrabajadorData.setRequestHeader("cache-control","no-cache");
		xmlTrabajadorData.send(params);
	}

}

function get_empresa_data(obj)//sets data for "Empresa". obj is div while assigning branches to workers
{

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlEmpresaData = new XMLHttpRequest();

		if (xmlEmpresaData.overrideMimeType)
			xmlEmpresaData.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlEmpresaData = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlEmpresaData = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlEmpresaData.onreadystatechange = function()
	{

		if (xmlEmpresaData.readyState==4 && xmlEmpresaData.status==200 && xmlEmpresaData.responseText != '')
		{
			var _div = document.createElement('div');
			_div.innerHTML = xmlEmpresaData.responseText;
			var selects = _div.getElementsByTagName('select');

			for(var i=0; i<selects.length; i++)

				if(selects[i].getAttribute('class') == 'empresa_rfc_select')
					var empresa_rfc_options = selects[i].innerHTML;
				else if(selects[i].getAttribute('class') == 'nombre_select')
					var nombre_options = selects[i].innerHTML;

			var selects = obj.getElementsByTagName('select');

			for(var i=0; i<selects.length; i++)

				if(selects[i].getAttribute('class') == 'empresa_rfc_select')
					selects[i].innerHTML = empresa_rfc_options;
				else if(selects[i].getAttribute('class') == 'nombre_select')
					selects[i].innerHTML = nombre_options;

		}

	}

	var textareas = obj.parentNode.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('class') == 'empresa_nombre_textarea')
		{
			var empresa = textareas[i].value;
			break;
		}

	if(empresa != '')
	{
		var params = 'val=' + empresa;
		xmlEmpresaData.open('POST','get_empresa_data.php',true);
		xmlEmpresaData.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
		xmlEmpresaData.setRequestHeader("Cache-control","no-cache");
		xmlEmpresaData.setRequestHeader("Content-length", params.length);
		xmlEmpresaData.setRequestHeader("Connection", "close");
		xmlEmpresaData.setRequestHeader("cache-control","no-cache");
		xmlEmpresaData.send(params);
	}

}

function get_enterprice_data(obj)//Gets "registros patronales" for "Empresa"
{

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlEnterpriceData = new XMLHttpRequest();

		if (xmlEnterpriceData.overrideMimeType)
			xmlEnterpriceData.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlEnterpriceData = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlEnterpriceData = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlEnterpriceData.onreadystatechange = function()
	{

		if (xmlEnterpriceData.readyState==4 && xmlEnterpriceData.status==200 && xmlEnterpriceData.responseText != '')
		{
			var _div = document.createElement('div');
			_div.innerHTML = xmlEnterpriceData.responseText;
			var divs = _div.getElementsByTagName('div');

			for(var i=0; i<divs.length; i++)

				if(divs[i].getAttribute('class') == 'registro_div')
					var registro_chkboxes = divs[i].innerHTML;

			var divs = obj.getElementsByTagName('div');

			for(var i=0; i<divs.length; i++)

				if(divs[i].getAttribute('class') == 'registro_div')
					divs[i].innerHTML = registro_chkboxes;

		}

	}

	var textareas = obj.parentNode.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('class') == 'enterprice_textarea')
		{
			var empresa = textareas[i].value;
			break;
		}

	if(empresa != '')
	{
		var params = 'val=' + empresa;
		xmlEnterpriceData.open('POST','get_enterprice_data.php',true);
		xmlEnterpriceData.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
		xmlEnterpriceData.setRequestHeader("Cache-control","no-cache");
		xmlEnterpriceData.setRequestHeader("Content-length", params.length);
		xmlEnterpriceData.setRequestHeader("Connection", "close");
		xmlEnterpriceData.setRequestHeader("cache-control","no-cache");
		xmlEnterpriceData.send(params);
	}

}

function get_enter_data(obj)//sets data for "Empresa". obj is div while generating concentrate of workers info
{

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlEnterData = new XMLHttpRequest();

		if (xmlEnterData.overrideMimeType)
			xmlEnterData.overrideMimeType('text/xml');

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlEnterData = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlEnterData = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlEnterData.onreadystatechange = function()
	{

		if (xmlEnterData.readyState==4 && xmlEnterData.status==200 && xmlEnterData.responseText != '')
		{
			var _div = document.createElement('div');
			_div.innerHTML = xmlEnterData.responseText;
			var selects = _div.getElementsByTagName('select');

			for(var i=0; i<selects.length; i++)

				if(selects[i].getAttribute('class') == 'empresa_rfc_select')
					var empresa_rfc_options = selects[i].innerHTML;
				else if(selects[i].getAttribute('class') == 'servicio_select')
					var servicio_options = selects[i].innerHTML;

			var selects = obj.getElementsByTagName('select');

			for(var i=0; i<selects.length; i++)

				if(selects[i].getAttribute('class') == 'empresa_rfc_select')
					selects[i].innerHTML = empresa_rfc_options;
				else if(selects[i].getAttribute('class') == 'servicio_select')
					selects[i].innerHTML = servicio_options;

		}

	}

	var textareas = obj.parentNode.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('class') == 'empresa_textarea')
		{
			var empresa = textareas[i].value;
			break;
		}

	if(empresa != '')
	{
		var params = 'val=' + empresa;
		xmlEnterData.open('POST','get_enter_data.php',true);
		xmlEnterData.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
		xmlEnterData.setRequestHeader("Cache-control","no-cache");
		xmlEnterData.setRequestHeader("Content-length", params.length);
		xmlEnterData.setRequestHeader("Connection", "close");
		xmlEnterData.setRequestHeader("cache-control","no-cache");
		xmlEnterData.send(params);
	}

}

function status_FONACOT(rfc)
{
	window.open('status_FONACOT.php?trabajador=' + rfc);
}

function solicitud_de_prestamo(obj)
{
	var textareas = obj.parentNode.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'Numero_de_prestamo')
		{
			var n = textareas[i].value;
			break;
		}

	window.open('solicitud_prestamo.php?numero=' + n);
}

function estado_de_cuenta(obj)
{
	var textareas = obj.parentNode.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'Numero_de_prestamo')
		{
			var n = textareas[i].value;
			break;
		}

	window.open('estado_de_cuenta.php?numero=' + n);
}

function copy_file_name(obj)
{

	var textareas = obj.parentNode.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('class') == obj.getAttribute('name') + '_aux_textarea')
		{
			var _aux = textareas[i];
			break;
		}

	if(_aux)
		_aux.value = obj.value;

}

function view_proposal_comparison(obj)
{
	var fieldsets = obj.parentNode.parentNode.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			var datosFieldset = fieldsets[i];
			break;
		}

	var textareas = datosFieldset.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'id')
		{
			var id = textareas[i].value;
			break;
		}

	window.open('view_proposal_comparison.php?id=' + id, 'Menubar =YES');
}
