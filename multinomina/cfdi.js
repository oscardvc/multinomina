function send_cfdi(obj)
{
	_alert('Función aún no impletentada');
	return;

	var textareas = obj.parentNode.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'id')
		{
			var id = textareas[i].value;
			break;
		}

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlSendCFDI = new XMLHttpRequest();

		if (xmlSendCFDI.overrideMimeType)
		{
			xmlSendCFDI.overrideMimeType('text/xml');
		}

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlSendCFDI = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlSendCFDI = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlSendCFDI.onreadystatechange = function()
	{

		if (xmlSendCFDI.readyState==4 && xmlSendCFDI.status==200)
		{

			if(xmlSendCFDI.responseText == '')
				_alert('CFDI Enviado');
			else
				_alert(xmlSendCFDI.responseText);

		}

	}

	var params = 'id=' + id;
	xmlSendCFDI.open('POST','send_cfdi.php', true);
	xmlSendCFDI.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmlSendCFDI.setRequestHeader("cache-control","no-cache");
	xmlSendCFDI.setRequestHeader("Content-length", params.length);
	xmlSendCFDI.setRequestHeader("Connection", "close");
	xmlSendCFDI.send(params);
}

function view_xml_cfdi(obj)
{
	var textareas = obj.parentNode.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'id')
		{
			var id = textareas[i].value;
			break;
		}

	if(id)
		window.open('view_cfdi_xml.php?id=' + id, 'Menubar =YES');

}

function view_print_cfdi(obj)
{
	var textareas = obj.parentNode.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'id')
		{
			var id = textareas[i].value;
			break;
		}

	if(id)
		window.open('view_cfdi_print.php?id=' + id, 'Menubar =YES');

}

function view_cancel_cfdi(obj)
{
	var textareas = obj.parentNode.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'id')
		{
			var id = textareas[i].value;
			break;
		}

	if(id)
		window.open('view_cfdi_cancel.php?id=' + id, 'Menubar =YES');

}

function cancel_cfdi(obj)
{
	var alert_box = document.createElement('div');
	obj.parentNode.parentNode.parentNode.appendChild(alert_box);
	alert_box.style.display = 'block';
	alert_box.style.position = 'absolute';
	alert_box.style.padding = 0;
	alert_box.style.margin = 0;
	alert_box.style.border = _border_width + 'px solid #555';
	alert_box.style.background = 'rgba(255, 255, 255, 0.90)';
	alert_box.style.filter = 'filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#CCffffff, endColorstr=#CCffffff)';
	alert_box.style.MozBoxShadow = "0 0 6px 6px #999";
	alert_box.style.WebkitBoxShadow = "0 0 6px 6px #999";
	alert_box.style.boxShadow = "0 0 6px 6px #999";
	alert_box.style.height = _height + "px";
	var image = document.createElement('img');
	alert_box.appendChild(image);
	image.style.display = 'block';
	image.style.position = 'absolute';
	image.style.padding = 0;
	image.style.margin = 0;
	image.style.border = 'none';
	image.style.background = 'none';
	image.style.top = (_height - 40) / 2 + 'px';
	image.style.left = '0px';
	image.style.zIndex = 1;
	image.style.width = '40px';
	image.style.height = '40px';
	image.src = image.src = 'images/loading.gif';
	var msg = document.createElement('span');
	msg.innerHTML = 'Cancelando...';
	alert_box.appendChild(msg);
	msg.style.display = 'block';
	msg.style.position = 'absolute';
	msg.style.padding = 0;
	msg.style.margin = '0px 0px 0px ' + parseInt(_height / 2) + 'px';
	msg.style.border = 'none';
	msg.style.background = 'none';
	msg.style.color = '#555';
	msg.style.font = title_font;
	msg.style.whiteSpace = 'nowrap';
	msg.style.top = parseInt((alert_box.offsetHeight - msg.offsetHeight) / 2) + 'px';
	msg.style.left = _height + 'px';
	alert_box.style.top = parseInt((alert_box.parentNode.offsetHeight - _height) / 2) + 'px';
	alert_box.style.width = image.offsetWidth + msg.offsetWidth + _height * 2 + 'px';
	alert_box.style.left = parseInt((alert_box.parentNode.offsetWidth - alert_box.offsetWidth) / 2) + 'px';
	alert_box.style.zIndex = '200';

	var textareas = obj.parentNode.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'id')
			var id = textareas[i].value;
		else if(textareas[i].getAttribute('name') == 'Status')
			var Status_textarea = textareas[i];

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmlCancelCFDI = new XMLHttpRequest();

		if (xmlCancelCFDI.overrideMimeType)
		{
			xmlCancelCFDI.overrideMimeType('text/xml');
		}

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmlCancelCFDI = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmlCancelCFDI = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmlCancelCFDI.onreadystatechange = function()
	{

		if (xmlCancelCFDI.readyState==4 && xmlCancelCFDI.status==200)
		{

			if(alert_box)
				close_div(alert_box.firstChild);

			_alert(xmlCancelCFDI.responseText);
			Status_textarea.value = 'Cancelado';
			_load('CFDI_Trabajador');
		}

	}

	var params = 'id=' + id;
	xmlCancelCFDI.open('POST','cancel_cfdi.php', true);
	xmlCancelCFDI.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmlCancelCFDI.setRequestHeader("cache-control","no-cache");
	xmlCancelCFDI.setRequestHeader("Content-length", params.length);
	xmlCancelCFDI.setRequestHeader("Connection", "close");
	xmlCancelCFDI.send(params);
}
