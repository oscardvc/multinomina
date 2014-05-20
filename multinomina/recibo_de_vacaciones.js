function view_recibo_de_vacaciones(obj)
{
	var _fieldset = obj.parentNode;
	var textareas = _fieldset.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'id')
		{
			var _id = textareas[i].value;
		}

	window.open('view_recibo_de_vacaciones.php?id=' + _id);
	window.open('resumen_vacaciones.php?id=' + _id);
}

function timbrar_vacaciones(obj)
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
	msg.innerHTML = 'Timbrando...';
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
		{
			var recibo = textareas[i].value;
			break;
		}

	if (window.XMLHttpRequest)//Mozilla, Safari...
	{
		var xmltimbre = new XMLHttpRequest();

		if (xmltimbre.overrideMimeType)
		{
			xmltimbre.overrideMimeType('text/xml');
		}

	}
	else if (window.ActiveXObject)// IE
	{

		try
		{
			var xmltimbre = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{

			try
			{
				var xmltimbre = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}

		}

	}

	xmltimbre.onreadystatechange = function()
	{

		if (xmltimbre.readyState==4 && xmltimbre.status==200)
		{

			if(xmltimbre.responseText == '')
			{
				_alert('Listo. Abra una vez más el recibo para revisar los resultados');
				close_div(obj.parentNode.parentNode);
			}
			else
			{

				if(alert_box)
					close_div(alert_box.firstChild);

				_alert(xmltimbre.responseText);
			}

		}

	}

	var params = 'recibo=' + recibo;
	xmltimbre.open('POST','timbrar_vacaciones.php', true);
	xmltimbre.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	xmltimbre.setRequestHeader("cache-control","no-cache");
	xmltimbre.setRequestHeader("Content-length", params.length);
	xmltimbre.setRequestHeader("Connection", "close");
	xmltimbre.send(params);
}
