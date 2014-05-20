function cred_menu(obj,_id,_rfc)
{
	var _div  = obj.parentNode.parentNode.parentNode;
	var _menu_div = document.createElement('div');
	_div.appendChild(_menu_div);
	_menu_div.style.overflow = 'hidden';
	_menu_div.style.display = 'block';
	_menu_div.style.position = 'absolute';
	_menu_div.style.padding = 0;
	_menu_div.style.margin = 0;
	_menu_div.style.height = (_div.offsetHeight < 170 ? parseInt(_div.offsetHeight * 0.96) : 170) + 'px';
	_menu_div.style.width = (_div.offsetWidth < 300 ? parseInt(_div.offsetWidth * 0.98) : 300) + 'px';
	_menu_div.style.border = _border_width + 'px solid #555';
	_menu_div.style.top = parseInt((_div.offsetHeight - _menu_div.offsetHeight) / 2) + 'px';
	_menu_div.style.left = parseInt((_div.offsetWidth - _menu_div.offsetWidth) / 2) + 'px';
	_menu_div.style.zIndex = 200;
	_menu_div.style.background = 'rgba(255, 255, 255, 0.90)';
	_menu_div.style.filter = 'filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#CCffffff, endColorstr=#CCffffff)';
	_menu_div.style.MozBoxShadow = "0 0 8px 8px #888";
	_menu_div.style.WebkitBoxShadow = "0 0 8px 8px#888";
	_menu_div.style.boxShadow = "0 0 8px 8px #888";
	//close button
	var close_button = document.createElement('div');
	_menu_div.appendChild(close_button);
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
	close_button.style.left = _menu_div.offsetWidth - close_button.offsetWidth - _border_width * 2 + 'px';
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
	_menu_div.appendChild(_title);
	_title.style.display = 'block';
	_title.style.position = 'absolute';
	_title.style.padding = 0;
	_title.style.margin = 0;
	_title.style.border = 'none';
	_title.style.background = '#555';
	_title.style.top = '0px';
	_title.style.left = '0px';
	_title.style.color = '#fff';
	_title.style.width = _menu_div.offsetWidth - _height + 'px';
	_title.style.height = close_button.offsetHeight + 'px';
	//date
	var _date_label = document.createElement('label');
	_menu_div.appendChild(_date_label);
	_date_label.innerHTML = 'Fecha';
	_date_label.style.display = 'block';
	_date_label.style.position = 'absolute';
	_date_label.style.padding = '4px';
	_date_label.style.margin = 0;
	_date_label.style.border = 'none';
	_date_label.style.background = '#555';
	_date_label.style.font = font;
	_date_label.style.color = '#fff';
	_date_label.style.borderTopLeftRadius = '10px';
	_date_label.style.borderBottomLeftRadius = '10px';
	_date_label.style.MozBorderRadiusTopleft = '10px';
	_date_label.style.MozBorderRadiusBottomleft = '10px';
	_date_label.style.WebkitBorderTopLeftRadius = '10px';
	_date_label.style.WebkitBorderBottomLeftRadius = '10px';
	_date_label.style.top = _height + _border_width * 3 + 'px';
	_date_label.style.left = parseInt(_menu_div.offsetWidth * 0.20) + 'px';
	var _date_textarea = document.createElement('textarea');
	_menu_div.appendChild(_date_textarea);
	_date_textarea.style.display = 'block';
	_date_textarea.style.position = 'absolute';
	_date_textarea.style.padding = 0;
	_date_textarea.style.margin = 0;
	_date_textarea.style.border = _border_width + 'px solid #555';
	_date_textarea.style.background = '#fff';
	_date_textarea.style.font = font;
	_date_textarea.style.color = '#555';
	_date_textarea.style.borderTopRightRadius = '10px';
	_date_textarea.style.borderBottomRightRadius = '10px';
	_date_textarea.style.MozBorderRadiusTopright = '10px';
	_date_textarea.style.MozBorderRadiusBottomright = '10px';
	_date_textarea.style.WebkitBorderTopRightRadius = '10px';
	_date_textarea.style.WebkitBorderBottomRightRadius = '10px';
	_date_textarea.style.top = _date_label.offsetTop + _border_width + 'px';
	_date_textarea.style.left = _date_label.offsetLeft + _date_label.offsetWidth + 'px';
	_date_textarea.style.width = parseInt(_menu_div.offsetWidth * 0.40) + 'px';
	_date_textarea.style.height = _date_label.offsetHeight - _border_width * 2 + 'px';
	var image = document.createElement('img');
	_menu_div.appendChild(image);
	image.style.display = 'block';
	image.style.position = 'absolute';
	image.style.padding = 0;
	image.style.margin = 0;
	image.style.border = 'none';
	image.style.background = 'none';
	image.style.zIndex = 1;
	image.style.cursor = 'pointer';
	image.setAttribute('onclick','show_cal(this)');
	image.src = 'icon.php?subject=calendar&height=' + _date_label.offsetHeight;
	image.style.top = _date_textarea.offsetTop + _border_width + 'px';
	image.style.left = _date_textarea.offsetLeft + _date_textarea.offsetWidth + _border_width + 'px';
	//anverso
	var image = document.createElement('img');
	_menu_div.appendChild(image);
	image.style.display = 'block';
	image.style.position = 'absolute';
	image.style.padding = 0;
	image.style.margin = 0;
	image.style.border = 'none';
	image.style.background = 'none';
	image.style.zIndex = 1;
	image.style.cursor = 'pointer';
	image.setAttribute('title','Anverso');
	image.setAttribute('onclick','cred(this,' + _id + ",'" + _rfc + "','front')");
	image.src = 'icon.php?subject=front&height=' + (_height * 2);
	image.style.top = _date_label.offsetTop + _date_label.offsetHeight + _border_width * 3 + 'px';
	image.style.left = _date_label.offsetLeft + 'px';
	//reverso
	var image = document.createElement('img');
	_menu_div.appendChild(image);
	image.style.display = 'block';
	image.style.position = 'absolute';
	image.style.padding = 0;
	image.style.margin = 0;
	image.style.border = 'none';
	image.style.background = 'none';
	image.style.zIndex = 1;
	image.style.cursor = 'pointer';
	image.setAttribute('title','Reverso');
	image.setAttribute('onclick','cred(this,' + _id + ",'" + _rfc + "','back')");
	image.src = 'icon.php?subject=back&height=' + (_height * 2);
	image.style.top = _date_label.offsetTop + _date_label.offsetHeight + _border_width * 3 + 'px';
	image.style.left = _date_textarea.offsetLeft + _date_textarea.offsetWidth - _height + 'px';
	//colors
	var colors = new Array('be2126','4daab7','ee781b','555555');

	for(var i=0; i<colors.length; i++)
	{
		var label = document.createElement('label');
		_menu_div.appendChild(label);
		label.innerHTML = '';
		label.style.display = 'block';
		label.style.position = 'absolute';
		label.style.padding = '0px';
		label.style.margin = '0px';
		label.style.border = 'none';
		label.style.background = '#' + colors[i];
		label.style.width = '15px';
		label.style.height = '15px';
		label.style.top = image.offsetTop + _height * 2 + 5 + 'px';
		label.style.left = (_menu_div.offsetWidth - colors.length * label.offsetWidth) / 2 + (i * label.offsetWidth) + 'px';
		var chk = document.createElement('input');
		_menu_div.appendChild(chk);
		chk.setAttribute('type','checkbox');
		chk.setAttribute('name','color');
		chk.setAttribute('value',colors[i]);
		chk.style.display = 'block';
		chk.style.position = 'absolute';
		chk.style.padding = '0px';
		chk.style.margin = '0px';
		chk.style.border = 'none';
		chk.style.width = '15px';
		chk.style.height = '15px';
		chk.style.top = label.offsetTop + label.offsetHeight + 5 + 'px';
		chk.style.left = label.offsetLeft + 3 + 'px';
	}
}

function cred(obj, _service, _worker, _position)
{

	if(! _worker)
		_worker = '';

	var textareas = obj.parentNode.getElementsByTagName('textarea');
	var _date = textareas[0].value;
	var inputs = obj.parentNode.getElementsByTagName('input');

	for(var i=0; i<inputs.length; i++)

		if(inputs[i].getAttribute('type') == 'checkbox' && inputs[i].checked)
			var _color = inputs[i].value;

	if(! _color)
	{
		_alert('Por favor seleccione un color');
		return;
	}
	if(_date == '')
	{
		_alert('Por favor seleccione una fecha');
		return;
	}
	else
	{
		var fieldsets = obj.parentNode.parentNode.getElementsByTagName('fieldset');

		for(var i=0; i<fieldsets.length; i++)

			if(fieldsets[i].getAttribute('class') == 'Trabajadores_fieldset')
			{
				var _workers_fieldset = fieldsets[i];
				break;
			}

			if(_workers_fieldset)
			{
				var _workers_div = document.createElement('div');
				document.body.appendChild(_workers_div);
				var _workers_list = document.createElement('div');
				_workers_div.appendChild(_workers_list);
				_workers_div.style.overflow = 'hidden';
				_workers_div.style.display = 'block';
				_workers_div.style.position = 'absolute';
				_workers_div.style.padding = 0;
				_workers_div.style.margin = 0;
				_workers_div.style.height = _workers_fieldset.offsetHeight + 'px';
				_workers_div.style.width = _workers_fieldset.offsetWidth + 'px';
				_workers_div.style.border = _border_width + 'px solid #555';
				_workers_div.style.top = parseInt((window_height - _workers_div.offsetHeight) / 2) + 'px';
				_workers_div.style.left = parseInt((window_width - _workers_div.offsetWidth) / 2) + 'px';
				_workers_div.style.zIndex = 300;
				_workers_div.style.background = 'rgba(255, 255, 255, 0.90)';
				_workers_div.style.filter = 'filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#CCffffff, endColorstr=#CCffffff)';
				_workers_div.style.MozBoxShadow = "0 0 8px 8px #888";
				_workers_div.style.WebkitBoxShadow = "0 0 8px 8px#888";
				_workers_div.style.boxShadow = "0 0 8px 8px #888";
				_workers_list.style.display = 'block';
				_workers_list.style.position = 'absolute';
				_workers_list.style.padding = 0;
				_workers_list.style.margin = 0;
				_workers_list.style.height = parseInt(_workers_div.offsetHeight * 0.90) + 'px';
				_workers_list.style.width = parseInt(_workers_div.offsetWidth * 0.90) + 'px';
				_workers_list.style.border = 'none';
				_workers_list.style.top = parseInt((_workers_div.offsetHeight - _workers_list.offsetHeight) / 2) + 'px';
				_workers_list.style.left = parseInt((_workers_div.offsetWidth - _workers_list.offsetWidth) / 2) + 'px';
				_workers_list.innerHTML = _workers_fieldset.innerHTML;
				var tables = _workers_list.getElementsByTagName('table');
				var opt_table = tables[1];

				for(var i=0; i<opt_table.rows.length; i++)
				{
					var chkbox = document.createElement('input');
					chkbox.setAttribute('type','checkbox');
					chkbox.setAttribute('class','checkbox_input');
					chkbox.setAttribute('name','Trabajador[]');
					chkbox.setAttribute('value',opt_table.rows[i].cells[1].innerHTML);
					opt_table.rows[i].cells[0].appendChild(chkbox);
					opt_table.rows[i].setAttribute('onclick','select_multiple_option(this)');
//					select_multiple_option(opt_table.rows[i]);
				}

				fit_content(_workers_list);//fit_content at presentations.js;
				//close button
				var close_button = document.createElement('div');
				_workers_div.appendChild(close_button);
				close_button.setAttribute('onclick',"close_div(this)");
				close_button.style.display = 'block';
				close_button.style.position = 'absolute';
				close_button.style.padding = 0;
				close_button.style.margin = 0;
				close_button.style.border = 'none';
				close_button.style.background = '#555';
				close_button.style.width = _height + 'px';
				close_button.style.height = _height + 'px';
				close_button.style.top = '0px';
				close_button.style.left = '0px';
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
				_workers_div.appendChild(_title);
				_title.innerHTML = 'Seleccione los trabajadores';
				_title.style.display = 'block';
				_title.style.position = 'absolute';
				_title.style.padding = 0;
				_title.style.margin = 0;
				_title.style.border = 'none';
				_title.style.background = '#555';
				_title.style.top = _workers_div.offsetHeight - _border_width * 2 + 'px';
				_title.style.left = '0px';
				_title.style.color = '#fff';
				_title.style.font = title_font;
				_title.style.width = _workers_div.offsetHeight - _height + 'px';
				_title.style.height = close_button.offsetHeight + 'px';
				_title.style.transform = 'rotate(270deg)';
				_title.style.transformOrigin = 'left top';
				//ok button
				var go_button = document.createElement('div');
				_workers_div.appendChild(go_button);
				go_button.setAttribute('onclick',"carnets(this," + _service +  ",'" + _position + "','" + _date + "','" + _color + "','')");
				go_button.style.display = 'block';
				go_button.style.position = 'absolute';
				go_button.style.padding = 0;
				go_button.style.margin = 0;
				go_button.style.border = 'none';
				go_button.style.background = 'none';
				go_button.style.width = _height + 'px';
				go_button.style.height = _height + 'px';
				go_button.style.top = '0px';
				go_button.style.left = _workers_div.offsetWidth - go_button.offsetWidth - _border_width * 2 + 'px';
				go_button.style.cursor = 'pointer';
				var image = document.createElement('img');
				go_button.appendChild(image);
				image.style.display = 'block';
				image.style.position = 'absolute';
				image.style.padding = 0;
				image.style.margin = 0;
				image.style.border = 'none';
				image.style.background = 'none';
				image.style.zIndex = 1;
				image.src = 'icon.php?subject=submit&height=' + _height;
			}
			else
				carnets('',_service,_position,_date,_color,_worker);
		close_div(obj);
	}

}

function carnets(obj, _service, _position, _date, _color,_worker)
{

	if(_worker == '')
	{
		var tables = obj.parentNode.getElementsByTagName('table');
		var opt_table = tables[1];

		for(var i=0; i<opt_table.rows.length; i++)

			if(opt_table.rows[i].cells[0].firstChild.checked)
				_worker += (_worker == '') ? (opt_table.rows[i].cells[0].firstChild.value) : (',' + opt_table.rows[i].cells[0].firstChild.value);

	}

	window.open('credenciales.php?service=' + _service + '&position=' + _position + '&date=' + _date + '&worker=' + _worker + '&color=' + _color);
}
