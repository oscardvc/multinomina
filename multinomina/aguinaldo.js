function get_workers_aguinaldo(mode,obj)
{
//	var user = document.getElementById('user').innerHTML;
	var datos_fieldset = obj.parentNode;
	var textareas = datos_fieldset.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'Fecha_de_pago')
			var fecha_de_pago = textareas[i].value;
		else if(textareas[i].getAttribute('name') == 'id')
			var _id = textareas[i].value;

	var selects = datos_fieldset.getElementsByTagName('select');

	for(var i=0; i<selects.length; i++)

		if(selects[i].getAttribute('name') == 'Servicio')
		{
			var servicio = selects[i].value;
			break;
		}

	if(1)//user == 'nomina'
	{

		if (window.XMLHttpRequest)//Mozilla, Safari...
		{
			var xmlworkers_aguinaldo = new XMLHttpRequest();

			if (xmlworkers_aguinaldo.overrideMimeType)
			{
				xmlworkers_aguinaldo.overrideMimeType('text/xml');
			}

		}
		else if (window.ActiveXObject)// IE
		{

			try
			{
				var xmlworkers_aguinaldo = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{

				try
				{
					var xmlworkers_aguinaldo = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e) {}

			}

		}

		xmlworkers_aguinaldo.onreadystatechange = function()
		{

			if (xmlworkers_aguinaldo.readyState==4 && xmlworkers_aguinaldo.status==200)
			{
				var workers_div = document.createElement('div');
				var _div = obj.parentNode.parentNode.parentNode;
				workers_div.setAttribute('class','workers_div');
				_div.appendChild(workers_div);
				var capsule = document.createElement('div');
				var _options = document.createElement('div');
				workers_div.appendChild(_options);
				_options.setAttribute('class','options');
				capsule.innerHTML = xmlworkers_aguinaldo.responseText;
				var labels = capsule.getElementsByTagName('label');

				if(labels.length > 0)
					_alert('El año que ha seleccionado ya existe en el sistema');
				else
				{
					var tables = capsule.getElementsByTagName('table');
					workers_div.appendChild(tables[0]);//titles_table
					_options.appendChild(tables[0]);//options_table
					workers_div.style.overflow = 'hidden';
					workers_div.style.display = 'block';
					workers_div.style.position = 'absolute';
					workers_div.style.padding = 0;
					workers_div.style.margin = 0;
					workers_div.style.border = 'none';
					workers_div.style.width = _div.offsetWidth - 8 + 'px';
					workers_div.style.height = _div.offsetHeight - 8 + 'px';
					workers_div.style.top = - workers_div.offsetHeight + 'px';
					workers_div.style.left = '0px';
					workers_div.style.background = 'rgba(51, 51, 51, 0.90)';
					workers_div.style.zIndex = 5;
					//go button
					var go_button = document.createElement('img');
					workers_div.appendChild(go_button);
					go_button.setAttribute('onclick','calculate_aguinaldo(this.parentNode.parentNode)');
					go_button.setAttribute('title','Continuar');
					go_button.style.display = 'block';
					go_button.style.position = 'absolute';
					go_button.style.padding = 0;
					go_button.style.margin = 0;
					go_button.style.border = 'none';
					go_button.style.background = 'none';
					go_button.style.top = workers_div.offsetHeight - _height + 'px';
					go_button.style.left = parseInt((workers_div.offsetWidth - _height) / 2) + 'px';
					go_button.style.cursor = 'pointer';
					go_button.src = 'icon.php?subject=play&height=' + _height;
					//hide button
					var hide_button = document.createElement('img');
					workers_div.appendChild(hide_button);
					hide_button.setAttribute('onclick','close_workers(this.parentNode)');
					hide_button.setAttribute('title','Cerrar');
					hide_button.style.display = 'block';
					hide_button.style.position = 'absolute';
					hide_button.style.padding = 0;
					hide_button.style.margin = 0;
					hide_button.style.border = 'none';
					hide_button.style.background = 'none';
					hide_button.style.top = '10px';
					hide_button.style.left = parseInt((workers_div.offsetWidth - _height) / 2) + 'px';
					hide_button.style.cursor = 'pointer';
					hide_button.src = 'icon.php?subject=hide&height=' + _height;
					//import button
					var import_button = document.createElement('img');
					workers_div.appendChild(import_button);
					import_button.setAttribute('onclick',"_new('Archivo_digital','IMPORT')");
					import_button.setAttribute('title','Importar archivo');
					import_button.style.display = 'block';
					import_button.style.position = 'absolute';
					import_button.style.padding = 0;
					import_button.style.margin = 0;
					import_button.style.border = 'none';
					import_button.style.background = 'none';
					import_button.style.top = '10px';
					import_button.style.left = hide_button.offsetLeft + _height + 15 + 'px';
					import_button.style.cursor = 'pointer';
					import_button.src = 'icon.php?subject=import&height=' + _height;
					workers_div.final_top = 0;
					fit_workers(workers_div);//fit_workers() at presentation.js
					show_workers(workers_div);//show_workers() at presentation.js
				}

			}

		}

		if(fecha_de_pago != '' && servicio != '')
		{
			var _div = obj.parentNode.parentNode.parentNode;
			var divs = _div.getElementsByTagName('div');

			for(var i=0; i<divs.length; i++)

				if(divs[i].getAttribute('class') == 'workers_div')
				{
					var _workers_div = divs[i];
					break;
				}

			if(_workers_div)
				show_workers(_workers_div);//show_workers() at presentation.js
			else
			{
				xmlworkers_aguinaldo.open("POST","get_workers_aguinaldo.php?servicio="+servicio+'&fecha_de_pago='+fecha_de_pago+'&id='+_id+'&mode='+mode,true);
				xmlworkers_aguinaldo.setRequestHeader("cache-control","no-cache");
				xmlworkers_aguinaldo.send('');
			}

		}
		else
		{

			if(fecha_de_pago == '')
				_alert('Por favor escriba una fecha de pago');
			else if(servicio == '')
				_alert('Por favor seleccione un servicio');

		}

	}

}

function calculate_aguinaldo(obj)//open the window calculate_aguinaldo.php to calculate a aguinaldo and display the results. obj is new_div or edit_div
{
	var divs = obj.getElementsByTagName('div');

	for(var i=0; i<divs.length; i++)

		if(divs[i].getAttribute('class') == 'workers_div')
		{
			var _div = divs[i];
			break;
		}

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

	var tables = _div.getElementsByTagName('table');

	for(var i=0; i<tables.length; i++)

		if(tables[i].getAttribute('class') == 'workers_options')
			var table = tables[i];
		else if(tables[i].getAttribute('class') == 'workers_titles')
			var titles = tables[i];

	var trabajador = '';
	var gratificacion_adicional = '';
	var pago_neto = '';

	for(var i=0; i<table.rows.length; i++)
	{

		if(! table.rows[i].cells[0].firstChild.checked)
			continue;

		trabajador += table.rows[i].cells[2].innerHTML + (i == table.rows.length - 1 ? '' : '>>');
		gratificacion_adicional += table.rows[i].cells[3].getAttribute('value') + (i == table.rows.length - 1 ? '' : '>>');
		pago_neto += table.rows[i].cells[4].getAttribute('value') + (i == table.rows.length - 1 ? '' : '>>');
	}

	var params = 'trabajador=' + trabajador + '&gratificacion_adicional=' + gratificacion_adicional + '&pago_neto=' + pago_neto;
//	var user = document.getElementById('user').innerHTML;
	var fieldsets = obj.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			var datos_fieldset = fieldsets[i];
		else if(fieldsets[i].getAttribute('class') == 'ISRaguinaldo_fieldset')
			var _ISRaguinaldo_fieldset = fieldsets[i];
		else if(fieldsets[i].getAttribute('class') == 'Aguinaldo_asalariados_fieldset')
			var aguinaldo_asalariados_fieldset = fieldsets[i];


	var textareas = datos_fieldset.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'Fecha_de_pago')
			var fecha_de_pago = textareas[i].value;
		else if(textareas[i].getAttribute('name') == 'id')
			var _id = textareas[i].value;

	var selects = datos_fieldset.getElementsByTagName('select');

	for(var i=0; i<selects.length; i++)

		if(selects[i].getAttribute('name') == 'Servicio')
		{
			var servicio = selects[i].value;
			break;
		}

	if(1)//user == 'nomina'
	{

		if (window.XMLHttpRequest)//Mozilla, Safari...
		{
			var xmlhttp0 = new XMLHttpRequest();

			if (xmlhttp0.overrideMimeType)
			{
				xmlhttp0.overrideMimeType('text/xml');
			}

		}
		else if (window.ActiveXObject)// IE
		{

			try
			{
				var xmlhttp0 = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{

				try
				{
					var xmlhttp0 = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e) {}

			}

		}

		xmlhttp0.onreadystatechange = function()
		{

			if (xmlhttp0.readyState==4 && xmlhttp0.status==200)
			{
				image.parentNode.removeChild(image);
				close_workers(_div);
				var capsule = document.createElement('div');
				capsule.innerHTML = xmlhttp0.responseText;
				var _divs = capsule.getElementsByTagName('div');
				datos_fieldset.appendChild(_divs[0]);
				var tables = capsule.getElementsByTagName('table');
				_ISRaguinaldo_fieldset.innerHTML = '';
				_ISRaguinaldo_fieldset.appendChild(tables[0]);//tables[0] is ISRaguinaldo table
				fit_fieldset(_ISRaguinaldo_fieldset);//fit_fieldset() at presentations.js
				aguinaldo_asalariados_fieldset.innerHTML = '';
				aguinaldo_asalariados_fieldset.appendChild(tables[0]);//now tables[0] is aguinaldo asalariados table
				fit_fieldset(aguinaldo_asalariados_fieldset);//fit_fieldset() at presentations.js
				//reviewing every trabajador's saldo
				var _isr_aguinaldo = _ISRaguinaldo_fieldset.firstChild;
				var aguinaldo_asalariados = aguinaldo_asalariados_fieldset.firstChild;
				var rows = aguinaldo_asalariados.getElementsByTagName('tr');
			}

		}

		xmlhttp0.open('POST','calculate_aguinaldo.php?id=' + _id + '&Fecha_de_pago=' + fecha_de_pago + '&Servicio=' + servicio, true);
		xmlhttp0.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
		xmlhttp0.setRequestHeader("cache-control","no-cache");
		xmlhttp0.setRequestHeader("Content-length", params.length);
		xmlhttp0.setRequestHeader("Connection", "close");
		xmlhttp0.send(params);
	}

}

function store_aguinaldo(obj)//obj is the form
{
	var fieldsets = obj.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
			var datos_fieldset = fieldsets[i];
		else if(fieldsets[i].getAttribute('class') == 'ISRaguinaldo_fieldset')
			var isr_aguinaldo_fieldset = fieldsets[i];
		else if(fieldsets[i].getAttribute('class') == 'Aguinaldo_asalariados_fieldset')
			var aguinaldo_asalariados_fieldset = fieldsets[i];

	//getting isr aguinaldo table
	var isr_aguinaldo_table = isr_aguinaldo_fieldset.firstChild;
	//removing isr aguinaldo table style (because style wont be needed and adds hundreds of characters)
	isr_aguinaldo_table.removeAttribute('style');
	//getting isr aguinaldo table rows
	var rows = isr_aguinaldo_table.getElementsByTagName('tr');
	//removing rows style
	for(var i=0; i<rows.length; i++)
	{
		rows[i].removeAttribute('style');
		//getting isr aguinaldo table row columns
		var cols = rows[i].getElementsByTagName('td');
		//removing columns style
		for(var j=0; j<cols.length; j++)
			cols[j].removeAttribute('style');

	}

	var aux = document.createElement('div');
	aux.appendChild(isr_aguinaldo_table);
	var isr_aguinaldo = document.createElement('textarea');
	isr_aguinaldo.setAttribute('name','ISRaguinaldo');
	isr_aguinaldo.style.visibility = 'hidden';
	isr_aguinaldo.value = aux.innerHTML;
	isr_aguinaldo_fieldset.appendChild(isr_aguinaldo);
	//getting aguinaldo asalariados table
	var aguinaldo_asalariados_table = aguinaldo_asalariados_fieldset.firstChild;
	//removing aguinaldo asalariados table style (because style wont be needed and adds hundreds of characters)
	aguinaldo_asalariados_table.removeAttribute('style');
	//getting aguinaldo asalariados table rows
	rows = aguinaldo_asalariados_table.getElementsByTagName('tr');
	//removing rows style
	for(var i=0; i<rows.length; i++)
	{
		rows[i].removeAttribute('style');
		//getting aguinaldo asalariados table row columns
		cols = rows[i].getElementsByTagName('td');
		//removing columns style
		for(var j=0; j<cols.length; j++)
			cols[j].removeAttribute('style');

	}

	aux.innerHTML = '';
	aux.appendChild(aguinaldo_asalariados_table);
	var aguinaldo_asalariados = document.createElement('textarea');
	aguinaldo_asalariados.setAttribute('name','aguinaldo_asalariados');
	aguinaldo_asalariados.style.visibility = 'hidden';
	aguinaldo_asalariados.value = aux.innerHTML;
	aguinaldo_asalariados_fieldset.appendChild(aguinaldo_asalariados);
}

function view_aguinaldo(obj)
{
	view_aguinaldo.button = obj;
	params = window_params('Aguinaldo');//window_params at presentation.js
	view_aguinaldo_window = window.open('view_aguinaldo.php', 'view_aguinaldo_window', 'Menubar =YES');
}

function _preview_aguinaldo()
{
	view_aguinaldo_window._load(view_aguinaldo.button);
}

function recibos_aguinaldo(obj)
{
	recibos_aguinaldo.button = obj;
	params = window_params('Aguinaldo');//window_params at presentation.js
	recibos_aguinaldo_window = window.open('recibos_aguinaldo.php', 'recibos_aguinaldo_window', 'Menubar =YES');
}

function _recibos_aguinaldo()
{
	recibos_aguinaldo_window._load(recibos_aguinaldo.button);
}
