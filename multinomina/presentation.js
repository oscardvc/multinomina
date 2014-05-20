minimal_height = 600;
minimal_width = 1024;
color_opaque = '#3399cc';//'#4aac4a';
color_bright = '#33bbff';//'#58bc58';

proportional = false;

if( typeof( window.innerWidth ) == 'number' )
{ 
	//Non-IE
	window_width = window.innerWidth;
	window_height = window.innerHeight;

	if(window_height < minimal_height || window_width < minimal_width)
		proportional = true;//sizes have to be proportional

	if(!proportional && (window.innerHeight < minimal_height || window.innerWidth < minimal_width))
	{
		window.moveTo(0,0);
		window.resizeTo(screen.availWidth, screen.availHeight);
	}

}
else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) )
{
	//IE 6+ in 'standards compliant mode'
	window_width = document.documentElement.clientWidth; 
	window_height = document.documentElement.clientHeight;

	if(window_height < minimal_height || window_width < minimal_width)
		proportional = true;//sizes have to be proportional

	if(!proportional && (document.documentElement.clientHeight < minimal_height || document.documentElement.clientWidth < minimal_width))
	{
		window.moveTo(0,0);
		window.resizeTo(screen.availWidth, screen.availHeight);
	}

}
else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) )
{
	//IE 4 compatible
	window_width = document.body.clientWidth;
	window_height = document.body.clientHeight;

	if(window_height < minimal_height || window_width < minimal_width)
		proportional = true;//sizes have to be proportional

	if(!proportional && (document.body.clientWidth < minimal_height || document.body.clientHeight < minimal_width))
	{
		window.moveTo(0,0);
		window.resizeTo(screen.availWidth, screen.availHeight);
	}

}

if(screen.width > screen.height)
	var font_size = parseInt(screen.width * 0.01);
else
	var font_size = parseInt(screen.height * 0.01);

if(font_size > 12)
	font_size = 12;

var _height = parseInt(screen.height / 20);

if(_height > 33)
	_height = 33;

var font = 'normal normal normal ' + font_size + 'px'  + ' Arial , sans-serif'; //weight, style, variant, size, family name, generic family
var title_font = 'bold normal normal ' + font_size + 'px'  + ' Arial , sans-serif';
var _border_width = 3;

function calendar_day_bright(obj)
{
	obj.style.background = "#777";
}

function calendar_day_opaque(obj)
{
	obj.style.background = "#555";
}

function option_bright(obj)
{
	obj.style.color = color_opaque;
}

function option_opaque(obj)
{
		obj.style.color = '#222';
}

function underscoreOff(txt)
{
	var text = String(txt);
	return text.replace(/_/g," ");
}

function underscoreOn(txt)
{
	var text = String(txt);
	return text.replace(/ /g,"_");
}

function add_entity_button_bright(obj)
{
	obj.style.background = color_bright;
}

function add_entity_button_opaque(obj)
{
	obj.style.background = color_opaque;
}

function menu_opaque(obj)
{
	obj.style.color = '#555';
}

function menu_bright(obj)
{
	obj.style.color = color_opaque;
}

function om_bright(obj)
{
	var image = obj.firstChild;

	if(obj.getAttribute('class') == 'view_cell')
		image.src = 'images/view_bright.png';
	else if(obj.getAttribute('class') == 'edit_cell')
		image.src = 'images/edit_bright.png';
	else if(obj.getAttribute('class') == 'delete_cell')
		image.src = 'images/delete_bright.png';

}

function om_opaque(obj)
{
	var image = obj.firstChild;

	if(obj.getAttribute('class') == 'view_cell')
		image.src = 'images/view.png';
	else if(obj.getAttribute('class') == 'edit_cell')
		image.src = 'images/edit.png';
	else if(obj.getAttribute('class') == 'delete_cell')
		image.src = 'images/delete.png';

}

function add_row_button_bright(obj)
{
	obj.style.background = color_bright;
}

function add_row_button_opaque(obj)
{
	obj.style.background = color_opaque;
}

function sub_row_button_bright(obj)
{
	obj.style.background = color_bright;
}

function sub_row_button_opaque(obj)
{
	obj.style.background = color_opaque;
}

function search_button_bright(obj)
{
	obj.style.background = color_bright;
}

function search_button_opaque(obj)
{
	obj.style.background = color_opaque;
}

function _appear(obj)
{

	if(parseFloat(obj.style.opacity) < 1.0)
		obj.style.opacity = parseFloat(obj.style.opacity) + 0.01;
	else
		window.clearInterval(obj.appear);

}

function lapsus(obj)
{
	window.setTimeout(function(){toDisappear(obj);},3000);
}

function toDisappear(obj)
{
	obj.disappear = window.setInterval(function(){_disappear(obj);},5);
}

function _disappear(obj)
{

	if(parseFloat(obj.style.opacity) > 0)
		obj.style.opacity = parseFloat(obj.style.opacity) - 0.01;
	else
	{
		window.clearInterval(obj.disappear);

		if(obj.del && obj.del == 1)
			obj.parentNode.removeChild(obj);

	}

}

function fit_content(fieldset)
{

	if(fieldset)
		var _container = fieldset;
	else
		var _container = document.getElementById('container');

	var tables = _container.getElementsByTagName('table');

	for(var i=0; i<tables.length; i++)

		if(tables[i].getAttribute('class') == 'titles_table')
			var _titles_table = tables[i];
		else if(tables[i].getAttribute('class') == 'options_table')
			var _options_table = tables[i];
		else if(tables[i].getAttribute('class') == 'search_table')
			var _search_table = tables[i];

	var divs = _container.getElementsByTagName('div');

	for(var i=0; i<divs.length; i++)

		if(divs[i].getAttribute('class') == 'options')
		{
			var _options_div = divs[i];
			break;
		}

	_titles_table.style.display = 'block';
	_titles_table.style.position = 'absolute';
	_titles_table.style.padding = 0;
	_titles_table.style.margin = 0;
	_titles_table.style.border = 'none';
	_titles_table.style.background = 'none';
	_titles_table.style.font = title_font;
	_titles_table.rows[0].cells[0].style.width = parseInt(_height / 2) + 'px';

	for(var i=1; i<_titles_table.rows[0].cells.length; i++)
		_titles_table.rows[0].cells[i].style.background = color_opaque;

	_options_div.style.display = 'block';
	_options_div.style.position = 'absolute';
	_options_div.style.padding = 0;
	_options_div.style.margin = 0;
	_options_div.style.border = 'none';
	_options_div.style.background = 'none';

	if(_search_table)
	{
		_search_table.style.display = 'block';
		_search_table.style.position = 'absolute';
		_search_table.style.padding = 0;
		_search_table.style.margin = 0;
		_search_table.style.border = 'none';
		_search_table.style.background = 'none';
	}

	if(_options_table)
	{
		_options_div.style.overflow = 'auto';
		_options_table  = _options_div.firstChild;
		_options_table.style.textAlign = 'center';
		_options_table.style.left = '0px';
		_options_table.style.top = '0px';
		_options_table.style.display = 'block';
		_options_table.style.position = 'absolute';
		_options_table.style.padding = 0;
		_options_table.style.margin = 0;
		_options_table.style.border = 'none';
		_options_table.style.background = 'none';
		_options_table.style.opacity = 0.90;
		_options_table.style.font = font;
		_options_div.style.width = parseInt(_container.offsetWidth) + 'px';
		_options_table.style.width = _options_div.offsetWidth - 20 + 'px';
		var _offsetWidth = 0;

		for(var i=0; i< _titles_table.rows[0].cells.length; i++)
		{

			if(_titles_table.rows[0].cells[i].offsetWidth > _options_table.rows[0].cells[i].offsetWidth)
				_options_table.rows[0].cells[i].style.width = _titles_table.rows[0].cells[i].offsetWidth + 'px';
			else
				_titles_table.rows[0].cells[i].style.width = _options_table.rows[0].cells[i].offsetWidth - 2 + 'px';

			_titles_table.rows[0].cells[i].style.left = _options_table.rows[0].cells[i].offsetLeft + 'px';
		}

		for(var i=0; i<_options_table.rows[0].cells.length; i++)
			_offsetWidth += _options_table.rows[0].cells[i].offsetWidth;

		if(_offsetWidth < _options_table.offsetWidth)
		{
			_options_table.style.width = _offsetWidth + 20 + 'px';
			_options_div.style.width = _options_table.offsetWidth + 20 + 'px';
		}

		if(parseInt(_container.offsetHeight * 0.80) < _options_table.offsetHeight)
			_options_div.style.height = parseInt(_container.offsetHeight * 0.80) + 'px';
		else
			_options_div.style.height = _options_table.offsetHeight + 'px';

		_options_div.style.top = parseInt((_container.offsetHeight - _options_div.offsetHeight) / 2) + 'px';
		_options_div.style.left = parseInt((_container.offsetWidth - _options_div.offsetWidth) / 2) + 'px';

		for(var i=0; i< _titles_table.rows[0].cells.length; i++)
		{

			if(_titles_table.rows[0].cells[i].offsetWidth > _options_table.rows[0].cells[i].offsetWidth)
				_options_table.rows[0].cells[i].style.width = _titles_table.rows[0].cells[i].offsetWidth + 'px';
			else
				_titles_table.rows[0].cells[i].style.width = _options_table.rows[0].cells[i].offsetWidth - 2 + 'px';

			_titles_table.rows[0].cells[i].style.left = _options_table.rows[0].cells[i].offsetLeft + 'px';
		}

		_titles_table.style.left = _options_div.offsetLeft + 'px';
	}
	else
	{
		_options_div.style.height = '0px';
		_options_div.style.top = parseInt((_container.offsetHeight - _options_div.offsetHeight) / 2) + 'px';
		_titles_table.style.left = parseInt((_container.offsetWidth - _titles_table.offsetWidth) / 2) + 'px';
		_titles_table.rows[0].cells[0].style.width = _titles_table.rows[0].cells[0].offsetHeight + 'px';
	}

	_titles_table.rows[0].cells[0].style.background = 'none';

	var _button = _titles_table.rows[0].cells[0].firstChild;

	if(_button)
	{
		_button.style.display = 'block';
		_button.style.position = 'absolute';
		_button.style.cursor = 'pointer';
		_button.style.top = '0px';
		_button.style.left = '0px';
		_button.style.background = color_opaque;
		_button.style.border = 'none';
		_button.style.borderRadius = '1em'
		_button.style.MozBorderRadius = '1em'
		_button.style.WebkitBorderRadius = '1em';
		_button.style.color = '#fff';
		_button.style.width = _button.offsetHeight + 'px';

		if(_button.getAttribute('class') == 'add_entity_button')
			_button.setAttribute('value','\u271a');

		if(_button.getAttribute('id') == 'click_general_button')
			_button.setAttribute('value','\u2054');

	}

	_titles_table.style.top = _options_div.offsetTop - _titles_table.offsetHeight + 'px';

	if(_search_table)
	{
		_search_table.style.top = _options_div.offsetTop + _options_div.offsetHeight + 'px';
		_search_table.style.left = _titles_table.offsetLeft + 'px';

		for(var i=0; i< _titles_table.rows[0].cells.length; i++)
		{
			_search_table.rows[0].cells[i].style.display = 'block';
			_search_table.rows[0].cells[i].style.position = 'absolute';
			_search_table.rows[0].cells[i].style.padding = 0;
			_search_table.rows[0].cells[i].style.margin = 0;
			_search_table.rows[0].cells[i].style.border = 'none';
			_search_table.rows[0].cells[i].style.background = 'none';
			_search_table.rows[0].cells[i].style.color = '#777';
			_search_table.rows[0].cells[i].style.top = '0px';
			_search_table.rows[0].cells[i].style.left = _titles_table.rows[0].cells[i].offsetLeft + 'px';
			_search_table.rows[0].cells[i].style.width = _titles_table.rows[0].cells[i].offsetWidth + 'px';
			_search_table.rows[0].cells[i].style.height = _titles_table.rows[0].cells[i].offsetHeight + 'px';
		}

		for(var i=0; i<_search_table.rows[0].cells.length; i++)
		{
			_search_table.rows[0].cells[i].firstChild.style.display = 'block';
			_search_table.rows[0].cells[i].firstChild.style.position = 'absolute';
			_search_table.rows[0].cells[i].firstChild.style.padding = 0;
			_search_table.rows[0].cells[i].firstChild.style.margin = 0;
			_search_table.rows[0].cells[i].firstChild.style.background = '#fff';
			_search_table.rows[0].cells[i].firstChild.style.color = '#777';
			_search_table.rows[0].cells[i].firstChild.style.height = _search_table.rows[0].cells[i].offsetHeight + 'px';
			_search_table.rows[0].cells[i].firstChild.style.width = _search_table.rows[0].cells[i].offsetWidth + 'px';
			_search_table.rows[0].cells[i].firstChild.style.top = '0px';
			_search_table.rows[0].cells[i].firstChild.style.left = '0px';
		}

		var _search_button = _search_table.rows[0].cells[0].firstChild;

		if(_search_button)
		{
			_search_button.style.display = 'block';
			_search_button.style.position = 'absolute';
			_search_button.style.cursor = 'pointer';
			_search_button.style.top = '0px';
			_search_button.style.left = '0px';
			_search_button.style.background = color_opaque;
			_search_button.style.border = 'none';
			_search_button.style.borderRadius = '1em';
			_search_button.style.MozBorderRadius = '1em';
			_search_button.style.WebkitBorderRadius = '1em';
			_search_button.style.color = '#fff';
			_search_button.setAttribute('value','?');
			_search_button.style.width = _search_button.offsetHeight + 'px';
		}

	}

	//view button (for "Retencion_FONACOT" by now)
	var buttons = _container.getElementsByTagName('input');

	for(var i=0; i<buttons.length; i++)

		if(buttons[i].getAttribute('class') == 'view_button')
		{
			buttons[i].style.display = 'block';
			buttons[i].style.position = 'absolute';
			buttons[i].style.padding = '1px';
			buttons[i].style.margin = 0;
			buttons[i].style.border = 'none';
			buttons[i].style.background = color_opaque;
			buttons[i].style.color = '#fff';
			buttons[i].style.font = font;
			buttons[i].style.borderRadius = '10px';
			buttons[i].style.MozBorderRadius = '10px';
			buttons[i].style.WebkitBorderRadius = '10px';
			buttons[i].style.top = _titles_table.offsetTop + 'px';
			buttons[i].style.left = _titles_table.offsetLeft + _titles_table.offsetWidth + 3 + 'px';
			buttons[i].style.cursor = 'pointer';
			break;
		}

}

function fit_screen()
{
	document.body.style.overflow = 'hidden';
	document.body.style.background = '#fff';
	//header
	var _header = document.getElementById('header');
	_header.style.display = 'block';
	_header.style.position = 'absolute';
	_header.style.padding = 0;
	_header.style.margin = 0;
	_header.style.border = 'none';
	_header.style.background = '#555';
	_header.style.height = _height + 'px';
	_header.style.width = window_width + 'px';
	_header.style.top = '0px';
	_header.style.left = '0px';
	//cuenta
	var _name = document.getElementById('_name');
/*	_name.style.display = 'block';
	_name.style.position = 'absolute';
	_name.style.top = parseInt((_height - _name.offsetHeight) / 2) + 'px';
	_name.style.left = '10px';
	_name.style.border = 'none';
	_name.style.font = title_font;
	_name.style.color = '#fff';
*/
	_name.style.visibility = 'hidden';
	//user
/*	var _user = document.getElementById('_user');
	_user.style.display = 'block';
	_user.style.position = 'absolute';
	_user.style.top = parseInt((_height - _user.offsetHeight) / 2) + 'px';
	_user.style.left = _name.offsetWidth + _name.offsetLeft + 5 + 'px';
	_user.style.border = 'none';
	_user.style.font = title_font;
	_user.style.color = '#fff';
*/
	_user.style.visibility = 'hidden';
	//container
	var _container = document.getElementById('container');
	_container.style.display = 'block';
	_container.style.position = 'absolute';
	_container.style.border = 'none';
	_container.style.background = 'none';
	_container.style.padding = 0;
	_container.style.margin = 0;
	_container.style.width = parseInt(window_width * 0.95) + 'px';
	_container.style.height = parseInt((window_height) * 0.85) + 'px';
	_container.style.left = parseInt((window_width - _container.offsetWidth) / 2) + 'px';
	_container.style.top = parseInt((window_height - _container.offsetHeight) / 2) + 'px';
	_container.style.overflow = 'hidden';
	//calendar
	var _calendar = document.getElementById('calendar');
	_calendar.style.height = '250px';
	var divs = _calendar.getElementsByTagName('div');

	for(var i=0; i<divs.length; i++)
	{
		divs[i].style.display = 'block';
		divs[i].style.position = 'absolute';
		divs[i].style.padding = 0;
		divs[i].style.margin = 0;
		divs[i].style.border = 'none';

		if(divs[i].getAttribute('class') == 'data')
		{
			divs[i].style.top = '0px';
			divs[i].style.left = '0px';
			divs[i].style.width = divs[i].firstChild.offsetWidth + 'px';
			divs[i].style.height = divs[i].firstChild.offsetWidth + 'px';
			var _data = divs[i];
			break;
		}

	}

	var _width = parseInt(_data.offsetWidth / 6);

	for(var i=0; i<divs.length; i++)
	{

		if(divs[i].getAttribute('class') == 'year')
		{
			divs[i].style.left = _width + 'px';
			divs[i].style.width = _width + 'px';
			divs[i].style.background = '#555';
			divs[i].style.font = font;
			divs[i].style.color = '#fff';
			divs[i].style.textAlign = 'center';
			divs[i].style.top = _calendar.offsetHeight - divs[i].offsetHeight - _border_width * 4 + 'px';
			divs[i].style.padding = _border_width + 'px 0px';
		}
		else if(divs[i].getAttribute('class') == 'month')
		{
			divs[i].style.left = _width * 4 + 'px';
			divs[i].style.width = _width + 'px';
			divs[i].style.background = '#555';
			divs[i].style.font = font;
			divs[i].style.color = '#fff';
			divs[i].style.textAlign = 'center';
			divs[i].style.top = _calendar.offsetHeight - divs[i].offsetHeight - _border_width * 4 + 'px';
			divs[i].style.padding = _border_width + 'px 0px';
		}

	}

	var images = _calendar.getElementsByTagName('img');

	for(var i=0; i<images.length; i++)
	{
		images[i].style.display = 'block';
		images[i].style.position = 'absolute';
		images[i].style.padding = 0;
		images[i].style.margin = 0;
		images[i].style.border = 'none';
		images[i].style.background = '#fff';
		images[i].style.cursor = 'pointer';


		if(images[i].getAttribute('class') == 'decYear')
		{
			images[i].style.top = images[i].nextSibling.offsetTop + 'px';
			images[i].style.left = _width - images[i].nextSibling.offsetHeight + 'px';
			images[i].src = 'icon.php?subject=dec&height=' + images[i].nextSibling.offsetHeight;
		}
		else if(images[i].getAttribute('class') == 'incYear')
		{
			images[i].style.left = _width * 2 + 'px';
			images[i].style.top = images[i-1].offsetTop + 'px';
			images[i].src = 'icon.php?subject=inc&height=' + images[i].previousSibling.offsetHeight;
		}
		else if(images[i].getAttribute('class') == 'decMonth')
		{
			images[i].style.left = images[i].nextSibling.offsetLeft - images[i].nextSibling.offsetHeight + 'px';
			images[i].style.top = images[i-1].offsetTop + 'px';
			images[i].src = 'icon.php?subject=dec&height=' + images[i].nextSibling.offsetHeight;
		}
		else if(images[i].getAttribute('class') == 'incMonth')
		{
			images[i].style.left = images[i].previousSibling.offsetLeft + images[i].previousSibling.offsetWidth + 'px';
			images[i].style.top = images[i-1].offsetTop + 'px';
			images[i].src = 'icon.php?subject=inc&height=' + images[i].previousSibling.offsetHeight;
		}
		else if(images[i].getAttribute('class') == 'close')
		{
			images[i].style.top = '0px';
			images[i].style.left = _data.firstChild.rows[0].cells[0].offsetWidth - _data.firstChild.rows[0].cells[0].offsetHeight + 'px';
			images[i].style.background = 'none';
			images[i].style.zIndex = 1;
			images[i].src = 'icon.php?subject=close&height=' + images[i-1].previousSibling.offsetHeight;
		}

	}

	_calendar.style.display = 'block';
	_calendar.style.position = 'absolute';
	_calendar.style.border = _border_width + 'px solid #555';
	_calendar.style.background = 'rgba(255, 255, 255, 0.70)';
	_calendar.style.padding = 0;
	_calendar.style.margin = 0;
	_calendar.style.zIndex = 500;
	_calendar.style.width = _data.offsetWidth + 'px';
	_calendar.style.left = - _calendar.offsetWidth + 'px';
	_calendar.style.top = parseInt((window_height - _calendar.offsetHeight) / 2) + 'px';
	//logo
	var _logo = document.getElementById('logo');
	_logo.style.display = 'block';
	_logo.style.position = 'absolute';
	_logo.src = 'icon.php?subject=logotipo&height=' + _height;
	_logo.style.top = '0px';
	_logo.style.left = '0px';
	//config
	var _config_img = document.getElementById('config_img');
	_config_img.style.display = 'block';
	_config_img.style.position = 'absolute';
	_config_img.style.top = '0px';
	_config_img.style.left = window_width - _height + 'px';
	_config_img.style.cursor = 'pointer';
	_config_img.src = 'icon.php?subject=config&height=' + _height;
	//empresas
	var _empresas_img = document.getElementById('empresas_img');
	_empresas_img.style.display = 'block';
	_empresas_img.style.position = 'absolute';
	_empresas_img.style.top = '0px';
	_empresas_img.style.left = window_width - _height * 2 + 'px';
	_empresas_img.style.cursor = 'pointer';
	_empresas_img.src = 'icon.php?subject=empresas&height=' + _height;
	//trabajadores
	var _trabajador_img = document.getElementById('trabajador_img');
	_trabajador_img.style.display = 'block';
	_trabajador_img.style.position = 'absolute';
	_trabajador_img.style.top = '0px';
	_trabajador_img.style.left = window_width - _height * 3 + 'px';
	_trabajador_img.style.cursor = 'pointer';
	_trabajador_img.src = 'icon.php?subject=trabajador&height=' + _height;
	//nomina
	var _nomina_img = document.getElementById('nomina_img');
	_nomina_img.style.display = 'block';
	_nomina_img.style.position = 'absolute';
	_nomina_img.style.top = '0px';
	_nomina_img.style.left = window_width - _height * 4 + 'px';
	_nomina_img.style.cursor = 'pointer';
	_nomina_img.src = 'icon.php?subject=nomina&height=' + _height;
	//CFDI
	var _cfdi_img = document.getElementById('cfdi_img');
	_cfdi_img.style.display = 'block';
	_cfdi_img.style.position = 'absolute';
	_cfdi_img.style.top = '0px';
	_cfdi_img.style.left = window_width - _height * 6 + 'px';
	_cfdi_img.style.cursor = 'pointer';
	_cfdi_img.src = 'icon.php?subject=cfdi&height=' + _height;
}

function window_params(dbTable)//this function is called from scripts at titles.js and option_menu.js to know parameters to new windows to open
{

	if(dbTable == 'Descuento_pendiente')
	{
		var height = window_height < 350 ? parseInt(window_height * 0.96) : 350;
		var width = window_width < 800 ? parseInt(window_width * 0.98) : 800;
	}
	else if(dbTable == 'Aportacion_del_trabajador_al_fondo_de_ahorro' || dbTable == 'Incapacidad' || dbTable == 'Vacaciones' || dbTable == 'Pension_alimenticia' || dbTable == 'Retencion_INFONAVIT' || dbTable == 'Retencion_FONACOT' || dbTable == 'Pago_por_seguro_de_vida' || dbTable == 'Salario_diario' || dbTable == 'Salario_minimo' || dbTable == 'Salario_real' || dbTable == 'Regimen_fiscal' || dbTable == 'Servicio_adicional' || dbTable == 'Servicio_Empresa' || dbTable == 'Servicio_Registro_patronal' || dbTable == 'Servicio_Trabajador' || dbTable == 'Baja' || dbTable == 'Trabajador_Sucursal' || dbTable == 'Trabajador_Salario_minimo' || dbTable == 'Contrato' || dbTable == 'UMF' || dbTable == 'Tipo' || dbTable == 'Banco' || dbTable == 'Base' || dbTable == 'Prevision_social' || dbTable == 'Prima' || dbTable == 'Usuario' || dbTable == 'Fondo_de_garantia')
	{
		var height = window_height < 370 ? parseInt(window_height * 0.96) : 370;
		var width = window_width < 870 ? parseInt(window_width * 0.98) : 870;
	}
	else if(dbTable == 'Registro_patronal' || dbTable == 'Salario_minimo' || dbTable == 'Factor_de_descuento' || dbTable == 'Porcentaje_de_descuento' || dbTable == 'Monto_fijo_mensual')
	{
		var height = window_height < 270 ? parseInt(window_height * 0.96) : 270;
		var width = window_width < 750 ? parseInt(window_width * 0.98) : 750;
	}
	else if(dbTable == 'Sucursal')
	{
		var height = window_height <420 ? parseInt(window_height * 0.96) : 420;
		var width = window_width < 820 ? parseInt(window_width * 0.98) : 820;
	}
	else if(dbTable == 'Socio' || dbTable == 'Oficina' || dbTable == 'Apoderado' || dbTable == 'Seguro_por_danos_a_la_vivienda' || dbTable == 'Porcentaje_de_cuotas_IMSS')
	{
		var height = window_height < 250 ? parseInt(window_height * 0.96) : 250;
		var width = window_width < 700 ? parseInt(window_width * 0.98) : 700;
	}
	else if(dbTable == 'Instrumento_notarial')
	{
		var height = window_height < 420 ? parseInt(window_height * 0.96) : 420;
		var width = window_width < 900 ? parseInt(window_width * 0.98) : 900;
	}
	else if(dbTable == 'Prestamo_administradora' || dbTable == 'Prestamo_caja' || dbTable == 'Prestamo_cliente' || dbTable == 'Prestamo_del_fondo_de_ahorro')
	{
		var height = window_height < 400 ? parseInt(window_height * 0.96) : 400;
		var width = window_width < 900 ? parseInt(window_width * 0.98) : 900;
	}
	else if(dbTable == 'Empresa')
	{
		var height = window_height < 530 ? parseInt(window_height * 0.96) : 530;
		var width = window_width < 900 ? parseInt(window_width * 0.98) : 900;
	}
	else if(dbTable == 'Propuesta')
	{
		var height = window_height < 535 ? parseInt(window_height * 0.96) : 535;
		var width = window_width < 900 ? parseInt(window_width * 0.98) : 900;
	}
	else if(dbTable == 'Archivo_digital' || dbTable == 'Logo' || dbTable == 'Photo' || dbTable == 'Representante_legal' || dbTable == 'Sign' || dbTable == 'Sello_digital' || dbTable == 'CFDI_Trabajador')
	{
		var height = window_height < 300 ? parseInt(window_height * 0.96) : 300;
		var width = window_width < 880 ? parseInt(window_width * 0.98) : 880;
	}
	else if(dbTable == 'Credito_al_salario_diario' || dbTable == 'Credito_al_salario_quincenal' || dbTable == 'Credito_al_salario_semanal' || dbTable == 'Credito_al_salario_mensual' || dbTable == 'ISR_anual' || dbTable == 'ISR_diario' || dbTable == 'ISR_mensual' || dbTable == 'ISR_quincenal' || dbTable == 'ISR_semanal')
	{
		var height = window_height < 450 ? parseInt(window_height * 0.96) : 450;
		var width = window_width < 800 ? parseInt(window_width * 0.98) : 800;
	}
	else if(dbTable == 'Trabajador')
	{
		var height = window_height < 480 ? parseInt(window_height * 0.96) : 480;
		var width = window_width < 1240 ? parseInt(window_width * 0.98) : 1240;
	}
	else if(dbTable == 'Servicio')
	{
		var height = window_height < 570 ? parseInt(window_height * 0.96) : 570;
		var width = window_width < 910 ? parseInt(window_width * 0.98) : 910;
	}
	else if(dbTable == 'Nomina' || dbTable == 'Nomina_propuesta' || dbTable == 'Aguinaldo')
	{
		var height = window_height < 1100 ? parseInt(window_height * 0.96) : 1100;
		var width = window_width < 1600 ? parseInt(window_width * 0.99) : 1600;
	}
	else if(dbTable == 'Recibo_de_vacaciones')
	{
		var height = window_height < 360 ? parseInt(window_height * 0.96) : 360;
		var width = window_width < 800 ? parseInt(window_width * 0.98) : 800;
	}
	else if(dbTable == 'Finiquito')
	{
		var height = window_height < 374 ? parseInt(window_height * 0.96) : 374;
		var width = window_width < 800 ? parseInt(window_width * 0.98) : 800;
	}
	else if(dbTable == 'Concentrado_trabajador' || dbTable == 'Calculo_anual')
	{
		var height = window_height < 240 ? parseInt(window_height * 0.96) : 240;
		var width = window_width < 800 ? parseInt(window_width * 0.98) : 800;
	}
	else if(dbTable == 'Concentrado_empresa' || dbTable == 'Concentrado_administradora')
	{
		var height = window_height < 260 ? parseInt(window_height * 0.96) : 260;
		var width = window_width < 800 ? parseInt(window_width * 0.98) : 800;
	}
	else if(dbTable == 'Concentrado_registro_patronal')
	{
		var height = window_height < 320 ? parseInt(window_height * 0.96) : 320;
		var width = window_width < 820 ? parseInt(window_width * 0.98) : 820;
	}

	var top = parseInt((screen.availHeight - height)/2);
	var left = parseInt((screen.availWidth - width)/2);
	return Array(width,height,top,left);
}

function fit_fieldset(fieldset)
{
	var form_height = fieldset.parentNode.offsetHeight;
	var form_width = fieldset.parentNode.offsetWidth;
	fieldset.style.zIndex = '0';
	fieldset.style.display = 'block';
	fieldset.style.position = 'absolute';
	fieldset.style.top = 0;
	fieldset.style.left = 0;
	fieldset.style.border = 'none';
	fieldset.style.background = 'none';
	fieldset.style.padding = 0;
	fieldset.style.margin = 0;
	fieldset.style.width = form_width + 'px';
	fieldset.style.height = form_height + 'px';
	//table
	var _table = fieldset.getElementsByTagName('table');

	if(_table.length > 0 && (_table[0].getAttribute('id') == 'ISRasalariados' || _table[0].getAttribute('id') == 'ISRasimilables' || _table[0].getAttribute('id') == 'cuotas_IMSS' || _table[0].getAttribute('id') == 'nomina_asalariados' || _table[0].getAttribute('id') == 'nomina_asimilables' || _table[0].getAttribute('id') == 'prestaciones' || _table[0].getAttribute('id') == 'ISRaguinaldo' || _table[0].getAttribute('id') == 'aguinaldo_asalariados'))
	{
		var trs = _table[0].getElementsByTagName('tr');
		var trs_len = trs.length;

		//tr
		for(j=0; j<trs_len; j++)
		{
			trs[j].style.zIndex = '-1';
			var tds = trs[j].getElementsByTagName('td');
			var tds_len = tds.length;

			//td
			for(k=0; k<tds_len; k++)
			{

				if(tds[k].getAttribute('class') == 'title')
				{
					tds[k].style.font = title_font;
					tds[k].style.color = '#fff';
					tds[k].style.background = '#555';
					tds[k].style.textAlign = 'left';
				}
				else if(tds[k].getAttribute('class') == 'column_title')
				{
					tds[k].style.font = title_font;
					tds[k].style.color = '#fff';
					tds[k].style.background = color_opaque;
					tds[k].style.textAlign = 'center';
				}
				else
				{
					tds[k].style.font = font;
					tds[k].style.color = '#000';
					tds[k].style.background = '#fff';
					tds[k].style.textAlign = 'center';
					tds[k].style.border = '1px solid #555';
				}

			}

		}

		_table[0].style.display = 'block';
		_table[0].style.position = 'absolute';
		_table[0].style.overflow = 'auto';

		if(_table[0].offsetWidth > parseInt(form_width * 0.95))
			_table[0].style.width = parseInt(form_width * 0.95) + 'px';

		if(_table[0].offsetHeight > parseInt(form_height * 0.95))
			_table[0].style.height = parseInt(form_height * 0.95) + 'px';

		_table[0].style.background = 'none';
		_table[0].style.zIndex = '0';
		_table[0].style.top = parseInt((form_height - _table[0].offsetHeight)/2) + 'px';
		_table[0].style.left = parseInt((form_width - _table[0].offsetWidth)/2)+ 'px';
		_table[0].style.padding = 0;
		_table[0].style.margin = 0;
		//buttons
		var images = fieldset.getElementsByTagName('img');

		for(var i=0; i<images.length; i++)
		{
			images[i].style.display = 'block';
			images[i].style.position = 'absolute';
			images[i].style.padding = 0;
			images[i].style.margin = 0;
			images[i].style.border = 'none';
			images[i].style.background = 'none';
			images[i].style.cursor = 'pointer';
			images[i].style.left = images[i].parentNode.offsetWidth + 3 + 'px';

			if(images[i].getAttribute('class') == 'view_button' || images[i].getAttribute('class') == 'deposit_button')
			{

				if(images[i].getAttribute('class') == 'view_button')
					images[i].style.top = _table[0].offsetTop + 'px';
				else
					images[i].style.top = _table[0].offsetTop + _height + 'px';

				images[i].src = 'icon.php?subject=_view&height=' + _height;
			}
			else//recibos
			{
				images[i].style.top = images[i-1].offsetTop + _height + 3 + 'px';
				images[i].src = 'icon.php?subject=recibos&height=' + _height;
			}

		}

	}

	return;
}

function fit_fieldset_propuesta(fieldset)
{
	var form_height = fieldset.parentNode.offsetHeight;
	var form_width = fieldset.parentNode.offsetWidth;
	fieldset.style.zIndex = '0';
	fieldset.style.display = 'block';
	fieldset.style.position = 'absolute';
	fieldset.style.top = 0;
	fieldset.style.left = 0;
	fieldset.style.border = 'none';
	fieldset.style.background = 'none';
	fieldset.style.padding = 0;
	fieldset.style.margin = 0;
	fieldset.style.width = form_width + 'px';
	fieldset.style.height = form_height + 'px';
	//table
	var _table = fieldset.getElementsByTagName('table');
	var trs = _table[0].getElementsByTagName('tr');
	var trs_len = trs.length;

	//tr
	for(j=0; j<trs_len; j++)
	{
		trs[j].style.zIndex = '-1';
		var tds = trs[j].getElementsByTagName('td');
		var tds_len = tds.length;

		//td
		for(k=0; k<tds_len; k++)
		{

			if(tds[k].getAttribute('class') == 'column_title')
			{
				tds[k].style.font = title_font;
				tds[k].style.color = '#fff';
				tds[k].style.background = color_opaque;
				tds[k].style.textAlign = 'center';
			}
			else
			{
				tds[k].style.font = font;
				tds[k].style.color = '#000';
				tds[k].style.background = '#fff';
				tds[k].style.textAlign = 'center';
				tds[k].style.border = '1px solid #555';
			}

		}

	}

	_table[0].style.display = 'block';
	_table[0].style.position = 'absolute';
	_table[0].style.overflow = 'auto';

	if(_table[0].offsetWidth > parseInt(form_width * 0.95))
		_table[0].style.width = parseInt(form_width * 0.95) + 'px';

	if(_table[0].offsetHeight > parseInt(form_height * 0.95))
		_table[0].style.height = parseInt(form_height * 0.95) + 'px';

	_table[0].style.background = 'none';
	_table[0].style.zIndex = '0';
	_table[0].style.top = parseInt((form_height - _table[0].offsetHeight)/2) + 'px';
	_table[0].style.left = parseInt((form_width - _table[0].offsetWidth)/2)+ 'px';
	_table[0].style.padding = 0;
	_table[0].style.margin = 0;
	//buttons
	var images = fieldset.getElementsByTagName('img');

	for(var i=0; i<images.length; i++)
	{
		images[i].style.display = 'block';
		images[i].style.position = 'absolute';
		images[i].style.padding = 0;
		images[i].style.margin = 0;
		images[i].style.border = 'none';
		images[i].style.background = 'none';
		images[i].style.cursor = 'pointer';
		images[i].style.left = images[i].parentNode.offsetWidth + 3 + 'px';

		if(images[i].getAttribute('class') == 'view_button')
		{

			if(images[i].getAttribute('class') == 'view_button')
				images[i].style.top = _table[0].offsetTop + 'px';
			else
				images[i].style.top = _table[0].offsetTop + _height + 'px';

			images[i].src = 'icon.php?subject=_view&height=' + _height;
		}

	}

}

function fit_aguinaldo(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.70) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'isr_aguinaldo_tab' || _divs[i].getAttribute('class') == 'aguinaldo_asalariados_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + 5 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.22);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.57);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'fecha_de_pago_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'servicio_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'calcular_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'resumen_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'fecha_de_pago_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';
				else if(textareas[j].getAttribute('class') == 'servicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.40) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'servicio_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.40) + 'px';

			}

			//images
			var images = fieldsets[i].getElementsByTagName('img');

			for(var j=0; j<images.length; j++)
			{
				images[j].style.display = 'block';
				images[j].style.position = 'absolute';
				images[j].style.padding = '0';
				images[j].style.margin = 0;
				images[j].style.border = 'none';
				images[j].style.background = 'none';
				images[j].style.top = images[j].previousSibling.offsetTop + 'px';
				images[j].style.left = images[j].previousSibling.offsetLeft + images[j].previousSibling.offsetWidth + 'px';
				images[j].style.cursor = 'pointer';

				if(images[j].getAttribute('class') == 'calculate_button')
					images[j].src = 'icon.php?subject=calcular&height=' + images[j].previousSibling.offsetHeight;
				else if(images[j].getAttribute('class') == 'resumen_button')
					images[j].src = 'icon.php?subject=resumen&height=' + images[j].previousSibling.offsetHeight;

			}

		}
		else
			fit_fieldset(fieldsets[i]);

	}

}

function fit_aportacion_del_trabajador_al_fondo_de_ahorro(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + 1 + _offset + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.20);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.60);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';//'2px solid #fff';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'porcentaje_del_salario_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_inicio_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_termino_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'porcentaje_del_salario_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_inicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_termino_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_apoderado(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.50) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + 5 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var hld = parseInt(fieldsets[i].offsetWidth * 0.30);

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = parseInt((fieldsets[i].offsetHeight - labels[j].offsetHeight) / 2) + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.50) + 'px';

			}

		}

	}

}

function fit_archivo_digital(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs and submit button
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'trabajador_tab' || _divs[i].getAttribute('class') == 'instrumento_notarial_tab' || _divs[i].getAttribute('class') == 'empresa_adminsitradora_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft  + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + 5 + 'px';

		}

		var inputs = _form.getElementsByTagName('input');

		for(var i=0; i<inputs.length; i++)

			if(inputs[i].getAttribute('class') == 'submit_button')
			{
				inputs[i].style.display = 'block';
				inputs[i].style.position = 'absolute';
				inputs[i].style.padding = '4px';
				inputs[i].style.margin = 0;
				inputs[i].style.border = _border_width + 'px solid #555';
				inputs[i].style.background = '#fff';
				inputs[i].style.borderRadius = '10px';
				inputs[i].style.MozBorderRadius = '10px';
				inputs[i].style.WebkitBorderRadius = '10px';
				inputs[i].style.color = '#555';
				inputs[i].style.top = _form.parentNode.offsetHeight - _form.offsetTop - inputs[i].offsetHeight - _offset * 4 + 'px';
				inputs[i].style.font = title_font;
				inputs[i].style.zIndex = 1;
				inputs[i].style.cursor = 'pointer';
				inputs[i].style.left = _form.parentNode.offsetWidth - _form.offsetLeft - inputs[i].offsetWidth - _offset * 5 + 'px';
			}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 20;//vertical distance
			var hld = parseInt(fieldsets[i].offsetWidth * 0.27);//horizontal left directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'archivo_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				if(labels[j].getAttribute('class') == 'descargar_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{

				if(textareas[j].getAttribute('class') != 'hidden_textarea' && textareas[j].getAttribute('className') != 'hidden_textarea')
				{
					textareas[j].style.display = 'block';
					textareas[j].style.position = 'absolute';
					textareas[j].style.padding = 0;
					textareas[j].style.margin = 0;
					textareas[j].style.border = 'none';
					textareas[j].style.background = '#fff';
					textareas[j].style.font = font;
					textareas[j].style.color = '#555';
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';

					if(textareas[j].getAttribute('class') == 'archivo_textarea')
						textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.50) + 'px';
					else if(textareas[j].getAttribute('class') == 'datafile_aux_textarea')
					{
						textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.50) - labels[0].offsetHeight + 'px';
						textareas[j].style.top = labels[0].offsetTop + 'px';
						textareas[j].style.left = labels[0].offsetLeft + labels[0].offsetWidth + 'px';
						textareas[j].style.height = labels[0].offsetHeight - _border_width * 2 + 'px';
						textareas[j].style.zIndex = -1;
					}

				}
				else
					textareas[j].style.visibility = 'hidden';

			}

			//file input
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)
			{
				inputs[j].style.display = 'block';
				inputs[j].style.position = 'absolute';
				inputs[j].style.padding = 0;
				inputs[j].style.margin = 0;
				inputs[j].style.border = 'none';
				inputs[j].style.background = '#fff';
				inputs[j].style.font = font;
				inputs[j].style.color = '#555';
				inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
				inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 'px';
				inputs[j].style.borderTop = _border_width + 'px solid #555';
				inputs[j].style.borderRight = _border_width + 'px solid #555';
				inputs[j].style.borderBottom = _border_width + 'px solid #555';
				inputs[j].style.borderTopRightRadius = '10px';
				inputs[j].style.borderBottomRightRadius = '10px';
				inputs[j].style.MozBorderRadiusTopright = '10px';
				inputs[j].style.MozBorderRadiusBottomright = '10px';
				inputs[j].style.WebkitBorderTopRightRadius = '10px';
				inputs[j].style.WebkitBorderBottomRightRadius = '10px';
				inputs[j].style.height = inputs[j].previousSibling.offsetHeight - _border_width * 2 + 'px';

				if(inputs[j].getAttribute('class') == 'file_input')
				{
					inputs[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.50) + 'px';
					inputs[j].style.opacity = 0;
				}

			}

			//images
			var images = fieldsets[i].getElementsByTagName('img');

			for(var j=0; j<images.length; j++)
			{
				images[j].style.display = 'block';
				images[j].style.position = 'absolute';
				images[j].style.padding = '0';
				images[j].style.margin = 0;
				images[j].style.border = 'none';
				images[j].style.background = 'none';
				images[j].style.top = images[j].previousSibling.offsetTop + 'px';
				images[j].style.left = images[j].previousSibling.offsetLeft + images[j].previousSibling.offsetWidth + 'px';
				images[j].style.cursor = 'pointer';

				if(images[j].getAttribute('class') == 'aux_button')
				{
					images[j].src = 'icon.php?subject=_import&height=' + labels[0].offsetHeight;
					images[j].style.zIndex = -1;
				}
				else if(images[j].getAttribute('class') == 'download_button')
					images[j].src = 'icon.php?subject=download&height=' + labels[0].offsetHeight;

			}

		}

	}

}

function fit_baja(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.15);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.70);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'fecha_de_baja_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_reingreso_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'fecha_de_baja_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_reingreso_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_banco(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.10);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.60);//horizontal left directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';//'2px solid #fff';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'sucursal_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'numero_de_cuenta_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';


				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.30) + 'px';
				else if(textareas[j].getAttribute('class') == 'sucursal_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.25) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'numero_de_cuenta_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_base(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.40);//horizontal left directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';//'2px solid #fff';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'base_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';


				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'base_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_ingreso_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'base_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_empresa(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.75) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs and submit button
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'persona_fisica_tab' || _divs[i].getAttribute('class') == 'registro_patronal_tab' || _divs[i].getAttribute('class') == 'prima_tab' || _divs[i].getAttribute('class') == 'representante_legal_tab' || _divs[i].getAttribute('class') == 'socios_tab' || _divs[i].getAttribute('class') == 'apoderados_tab' || _divs[i].getAttribute('class') == 'instrumento_notarial_tab' || _divs[i].getAttribute('class') == 'regimen_fiscal_tab' || _divs[i].getAttribute('class') == 'sucursales_tab' || _divs[i].getAttribute('class') == 'logo_tab' || _divs[i].getAttribute('class') == 'archivo_digital_tab' || _divs[i].getAttribute('class') == 'sello_digital_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.background = '#fff';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
				_divs[i].style.borderTopRightRadius = '10px';
				_divs[i].style.borderTopLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusTopright = '10px';
				_divs[i].style.MozBorderRadiusTopleft = '10px';
				_divs[i].style.WebkitBorderTopRightRadius = '10px';
				_divs[i].style.WebkitBorderTopLeftRadius = '10px';
				_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			}
			else if(_divs[i].getAttribute('class') == 'sucursales_tab' || _divs[i].getAttribute('class') == 'logo_tab' || _divs[i].getAttribute('class') == 'archivo_digital_tab' || _divs[i].getAttribute('class') == 'sello_digital_tab')
			{
				_divs[i].style.borderBottomRightRadius = '10px';
				_divs[i].style.borderBottomLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusBottomright = '10px';
				_divs[i].style.MozBorderRadiusBottomleft = '10px';
				_divs[i].style.WebkitBorderBottomRightRadius = '10px';
				_divs[i].style.WebkitBorderBottomLeftRadius = '10px';
				_divs[i].style.top = _form.offsetTop + _form.offsetHeight + 'px';

				if(_divs[i].getAttribute('class') == 'sucursales_tab')
					_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				else
					_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + _offset + 1 + 'px';


			}
			else
			{
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + _offset + 1 + 'px';
				_divs[i].style.borderTopRightRadius = '10px';
				_divs[i].style.borderTopLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusTopright = '10px';
				_divs[i].style.MozBorderRadiusTopleft = '10px';
				_divs[i].style.WebkitBorderTopRightRadius = '10px';
				_divs[i].style.WebkitBorderTopLeftRadius = '10px';
				_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			}

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.18);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';

				if(labels[j].getAttribute('class') == 'domicilio_fiscal_label')
				{
					labels[j].style.borderRadius = '10px';
				}
				else
				{
					labels[j].style.padding = '4px';
					labels[j].style.margin = 0;
					labels[j].style.border = 'none';//'2px solid #fff';
					labels[j].style.background = '#555';
					labels[j].style.font = font;
					labels[j].style.color = '#fff';

					if(labels[j].getAttribute('class') == 'objeto_social_label')
					{
						labels[j].style.borderTopLeftRadius = '10px';
						labels[j].style.borderTopRightRadius = '10px';
						labels[j].style.MozBorderRadiusTopleft = '10px';
						labels[j].style.MozBorderRadiusTopright = '10px';
						labels[j].style.WebkitBorderTopLeftRadius = '10px';
						labels[j].style.WebkitBorderTopRightRadius = '10px';
					}
					else
					{
						labels[j].style.borderTopLeftRadius = '10px';
						labels[j].style.borderBottomLeftRadius = '10px';
						labels[j].style.MozBorderRadiusTopleft = '10px';
						labels[j].style.MozBorderRadiusBottomleft = '10px';
						labels[j].style.WebkitBorderTopLeftRadius = '10px';
						labels[j].style.WebkitBorderBottomLeftRadius = '10px';
					}

				}

				if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'rfc_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'celular_label')
				{
					labels[j].style.top = labels[j].previousSibling.offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'telefono_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'correo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'nacionalidad_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'tipo_de_sociedad_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_ingreso_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_inicio_de_operaciones_label')
				{
					labels[j].style.top = labels[j-1].offsetTop  + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_constitucion_label')
				{
					labels[j].style.top = labels[j-1].offsetTop  + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'objeto_social_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = '5px';
					labels[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.56) + 'px';
					labels[j].style.textAlign = 'center';
				}
				else if(labels[j].getAttribute('class') == 'zona_geografica_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') == 'objeto_social_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + textareas[j].previousSibling.offsetHeight + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + 'px';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderLeft = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottomLeftRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusBottomleft = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderBottomLeftRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = vd * 5 + 'px';
				}
				else if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.50) + 'px';
				else if(textareas[j].getAttribute('class') == 'rfc_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'celular_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'telefono_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'correo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';
				else if(textareas[j].getAttribute('class') == 'objeto_social_textarea')
					textareas[j].style.width = textareas[j].previousSibling.offsetWidth - _border_width * 2 + 'px';
				else if(textareas[j].getAttribute('class') == 'nacionalidad_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'tipo_de_sociedad_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_ingreso_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_inicio_de_operaciones_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_constitucion_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'zona_geografica_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'zona_geografica_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';

			}

			var divs = fieldsets[i].getElementsByTagName('div');

			if(divs[0].getAttribute('class') == 'domicilio_fiscal_div')
			{
				divs[0].style.display = 'block';
				divs[0].style.position = 'absolute';
				divs[0].style.padding = '0';
				divs[0].style.margin = '0';
				divs[0].style.border = _border_width + 'px solid #555';
				divs[0].style.background = '#eee';
				divs[0].style.top = vd * 4 + 'px';
				divs[0].style.left = '5px';
				divs[0].style.width = parseInt(fieldsets[i].offsetWidth * 0.59) + 'px';
				divs[0].style.height = parseInt(fieldsets[i].offsetWidth * 0.30) + 'px';
				divs[0].style.borderRadius = '10px';
				var spans = divs[0].getElementsByTagName('span');
				spans[0].style.display = 'block';
				spans[0].style.position = 'absolute';
				spans[0].style.padding = '4px';
				spans[0].style.margin = '0';
				spans[0].style.border = 'none';
				spans[0].style.background = '#555';
				spans[0].style.color = '#fff';
				spans[0].style.textAlign = 'center';
				spans[0].style.font = title_font;
				spans[0].style.top = '0px';
				spans[0].style.left = ((spans[0].parentNode.offsetWidth - spans[0].offsetWidth) / 2) + 'px';
				spans[0].style.borderBottomLeftRadius = '10px';
				spans[0].style.borderBottomRightRadius = '10px';
				spans[0].style.MozBorderRadiusBottomleft = '10px';
				spans[0].style.MozBorderRadiusBottomRigth = '10px';
				spans[0].style.WebkitBorderBottomLeftRadius = '10px';
				spans[0].style.WebkitBorderBottomRigthRadius = '10px';
				var _hld = parseInt(divs[0].parentNode.offsetWidth * 0.10);
				var _hrd = parseInt(divs[0].parentNode.offsetWidth * 0.40);
				//labels
				var _labels = divs[0].getElementsByTagName('label');

				for(var k=0; k<_labels.length; k++)
				{
					_labels[k].style.display = 'block';
					_labels[k].style.position = 'absolute';
					_labels[k].style.padding = '4px';
					_labels[k].style.margin = 0;
					_labels[k].style.border = 'none';
					_labels[k].style.background = '#555';
					_labels[k].style.font = font;
					_labels[k].style.color = '#fff';
					_labels[k].style.borderTopLeftRadius = '10px';
					_labels[k].style.borderBottomLeftRadius = '10px';
					_labels[k].style.MozBorderRadiusTopleft = '10px';
					_labels[k].style.MozBorderRadiusBottomleft = '10px';
					_labels[k].style.WebkitBorderTopLeftRadius = '10px';
					_labels[k].style.WebkitBorderBottomLeftRadius = '10px';

					if(_labels[k].getAttribute('class') == 'calle_label')
					{
						_labels[k].style.top = _labels[k].previousSibling.offsetHeight + vd / 2 + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'numero_ext_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + _labels[k-1].offsetHeight + vd + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'numero_int_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + 'px';
						_labels[k].style.left = _hrd - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'colonia_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + _labels[k-1].offsetHeight + vd + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'localidad_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + _labels[k-1].offsetHeight + vd + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'referencia_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + _labels[k-1].offsetHeight + vd + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'municipio_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + _labels[k-1].offsetHeight + vd + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'estado_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + 'px';
						_labels[k].style.left = _hrd - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'pais_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + _labels[k-1].offsetHeight + vd + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'cp_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + 'px';
						_labels[k].style.left = _hrd - _labels[k].offsetWidth + 'px';
					}

				}

				//textareas
				var textareas = divs[0].getElementsByTagName('textarea');

				for(var k=0; k<textareas.length; k++)
				{
					textareas[k].style.display = 'block';
					textareas[k].style.position = 'absolute';
					textareas[k].style.padding = 0;
					textareas[k].style.margin = 0;
					textareas[k].style.border = 'none';
					textareas[k].style.background = '#fff';
					textareas[k].style.font = font;
					textareas[k].style.color = '#555';
					textareas[k].style.top = textareas[k].previousSibling.offsetTop + 'px';
					textareas[k].style.left = textareas[k].previousSibling.offsetLeft + textareas[k].previousSibling.offsetWidth + 'px';
					textareas[k].style.borderTop = _border_width + 'px solid #555';
					textareas[k].style.borderRight = _border_width + 'px solid #555';
					textareas[k].style.borderBottom = _border_width + 'px solid #555';
					textareas[k].style.borderTopRightRadius = '10px';
					textareas[k].style.borderBottomRightRadius = '10px';
					textareas[k].style.MozBorderRadiusTopright = '10px';
					textareas[k].style.MozBorderRadiusBottomright = '10px';
					textareas[k].style.WebkitBorderTopRightRadius = '10px';
					textareas[k].style.WebkitBorderBottomRightRadius = '10px';
					textareas[k].style.height = textareas[k].previousSibling.offsetHeight - _border_width * 2 + 'px';

					if(textareas[k].getAttribute('class') == 'calle_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.80) + 'px';
					else if(textareas[k].getAttribute('class') == 'numero_ext_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.12) + 'px';
					else if(textareas[k].getAttribute('class') == 'numero_int_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.12) + 'px';
					else if(textareas[k].getAttribute('class') == 'colonia_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.80) + 'px';
					else if(textareas[k].getAttribute('class') == 'localidad_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.80) + 'px';
					else if(textareas[k].getAttribute('class') == 'referencia_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.80) + 'px';
					else if(textareas[k].getAttribute('class') == 'municipio_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.35) + 'px';
					else if(textareas[k].getAttribute('class') == 'estado_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.30) + 'px';
					else if(textareas[k].getAttribute('class') == 'pais_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.30) + 'px';
					else if(textareas[k].getAttribute('class') == 'cp_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.12) + 'px';

				}

			}

		}
		else if(fieldsets[i].getAttribute('class') == 'Persona_fisica_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.18);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.80);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';//'2px solid #fff';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';

				if(labels[j].getAttribute('class') == 'domicilio_particular_label')
				{
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderTopRightRadius = '10px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusTopright = '10px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderTopRightRadius = '10px';
				}
				else
				{
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderBottomLeftRadius = '10px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusBottomleft = '10px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderBottomLeftRadius = '10px';
				}

				if(labels[j].getAttribute('class') == 'lugar_de_nacimiento_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'estado_civil_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'domicilio_particular_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = '5px';
					labels[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.56) + 'px';
					labels[j].style.textAlign = 'center';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_nacimiento_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'ocupacion_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') == 'domicilio_particular_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + textareas[j].previousSibling.offsetHeight + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + 'px';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderLeft = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottomLeftRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusBottomleft = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderBottomLeftRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = vd * 5 + 'px';
				}
				else if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'lugar_de_nacimiento_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.50) + 'px';
				else if(textareas[j].getAttribute('class') == 'estado_civil_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'domicilio_particular_textarea')
					textareas[j].style.width = textareas[j].previousSibling.offsetWidth - _border_width * 2 + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_nacimiento_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'ocupacion_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

		}
		else if(fieldsets[i].getAttribute('class') == 'Logo_fieldset')
			show_logo(fieldsets[i]);//at menu.js
		else
			fit_content(fieldsets[i]);

	}

}

function fit_calculo_anual(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.50) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.12);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'trabajador_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'trabajador_rfc_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'servicio_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'ano_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'trabajador_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.45) + 'px';
				else if(textareas[j].getAttribute('class') == 'ano_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'trabajador_rfc_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';
				else if(selects[j].getAttribute('class') == 'servicio_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.85) + 'px';

			}

		}

	}

}

function fit_cfdi(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.55) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.20);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.82);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'fecha_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'view_xml_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'emisor_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'view_print_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'receptor_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'send_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cancel_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
					labels[j].style.background = '#cc0033';
					labels[j].style.font = title_font;
				}
				else if(labels[j].getAttribute('class') == 'tipo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'view_cancel_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'status_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'fecha_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.13) + 'px';
				else if(textareas[j].getAttribute('class') == 'emisor_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.40) + 'px';
				else if(textareas[j].getAttribute('class') == 'receptor_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.40) + 'px';
				else if(textareas[j].getAttribute('class') == 'tipo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.12) + 'px';
				else if(textareas[j].getAttribute('class') == 'status_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';

			}

			//images
			var images = fieldsets[i].getElementsByTagName('img');

			for(var j=0; j<images.length; j++)
			{
				images[j].style.display = 'block';
				images[j].style.position = 'absolute';
				images[j].style.padding = '0';
				images[j].style.margin = 0;
				images[j].style.border = 'none';
				images[j].style.background = 'none';
				images[j].style.top = images[j].previousSibling.offsetTop + 'px';
				images[j].style.left = images[j].previousSibling.offsetLeft + images[j].previousSibling.offsetWidth + 'px';
				images[j].style.cursor = 'pointer';

				if(images[j].getAttribute('class') == 'send_button')
					images[j].src = 'icon.php?subject=calcular&height=' + images[j].previousSibling.offsetHeight;
				else if(images[j].getAttribute('class') == 'view_button')
					images[j].src = 'icon.php?subject=resumen&height=' + images[j].previousSibling.offsetHeight;
				else if(images[j].getAttribute('class') == 'cancel_button')
					images[j].src = 'icon.php?subject=cancel&height=' + images[j].previousSibling.offsetHeight;
				else if(images[j].getAttribute('class') == 'view_cancel_button')
					images[j].src = 'icon.php?subject=resumen&height=' + images[j].previousSibling.offsetHeight;

			}

		}

	}

}

function fit_concentrado_administradora(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.50) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.14);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'administradora_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'administradora_rfc_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'limite_inferior_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'limite_superior_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'administradora_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.40) + 'px';
				else if(textareas[j].getAttribute('class') == 'limite_inferior_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'limite_superior_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'administradora_rfc_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';

			}

		}

	}

}

function fit_concentrado_empresa(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.50) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.12);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'empresa_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'empresa_rfc_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'servicio_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'limite_inferior_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'limite_superior_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'empresa_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.50) + 'px';
				else if(textareas[j].getAttribute('class') == 'limite_inferior_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'limite_superior_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'empresa_rfc_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';
				else if(selects[j].getAttribute('class') == 'servicio_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.85) + 'px';

			}

		}

	}

}

function fit_concentrado_registro(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.92) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.62) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.14);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.56);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';

				if(labels[j].getAttribute('class') == 'registro_label')
				{
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderTopRightRadius = '10px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusTopright = '10px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderTopRightRadius = '10px';
					labels[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.40) + 'px';
					labels[j].style.textAlign = 'center';
				}
				else
				{
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderBottomLeftRadius = '10px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusBottomleft = '10px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderBottomLeftRadius = '10px';
				}

				if(labels[j].getAttribute('class') == 'empresa_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'registro_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd + 'px';
				}
				else if(labels[j].getAttribute('class') == 'limite_inferior_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'limite_superior_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'solo_asalariados_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'solo_asimilados_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';
				textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
				textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
				textareas[j].style.borderTop = _border_width + 'px solid #555';
				textareas[j].style.borderRight = _border_width + 'px solid #555';
				textareas[j].style.borderBottom = _border_width + 'px solid #555';
				textareas[j].style.borderTopRightRadius = '10px';
				textareas[j].style.borderBottomRightRadius = '10px';
				textareas[j].style.MozBorderRadiusTopright = '10px';
				textareas[j].style.MozBorderRadiusBottomright = '10px';
				textareas[j].style.WebkitBorderTopRightRadius = '10px';
				textareas[j].style.WebkitBorderBottomRightRadius = '10px';
				textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';

				if(textareas[j].getAttribute('class') == 'enterprice_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.40) + 'px';
				else if(textareas[j].getAttribute('class') == 'limite_inferior_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'limite_superior_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'registro_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.26) + 'px';

			}

			//registro div
			var divs = fieldsets[i].getElementsByTagName('div');

			for(var j=0; j<divs.length; j++)

				if(divs[j].getAttribute('class') == 'registro_div')
				{
					divs[j].style.display = 'block';
					divs[j].style.position = 'absolute';
					divs[j].style.padding = 0;
					divs[j].style.margin = 0;
					divs[j].style.border = 'none';
					divs[j].style.background = '#fff';
					divs[j].style.font = font;
					divs[j].style.color = '#555';
					divs[j].style.top = divs[j].previousSibling.offsetTop + divs[j].previousSibling.offsetHeight + 'px';
					divs[j].style.left = divs[j].previousSibling.offsetLeft + 'px';
					divs[j].style.borderRight = _border_width + 'px solid #555';
					divs[j].style.borderBottom = _border_width + 'px solid #555';
					divs[j].style.borderLeft = _border_width + 'px solid #555';
					divs[j].style.borderBottomRightRadius = '10px';
					divs[j].style.borderBottomLeftRadius = '10px';
					divs[j].style.MozBorderRadiusBottomright = '10px';
					divs[j].style.MozBorderRadiusBottomleft = '10px';
					divs[j].style.WebkitBorderBottomRightRadius = '10px';
					divs[j].style.WebkitBorderBottomLeftRadius = '10px';
					divs[j].style.height = parseInt(fieldsets[i].offsetHeight * 0.70) + 'px';
					divs[j].style.width = divs[j].previousSibling.offsetWidth - _border_width * 2 + 'px';
					divs[j].style.overflowY = 'auto';
					divs[j].style.overflowX = 'hidden';
				}

			//checkboxes
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)

				if(inputs[j].getAttribute('type') == 'checkbox')
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = 0;
					inputs[j].style.margin = 0;
					inputs[j].style.border = 'none';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 2 + 'px';
					inputs[j].style.borderRadius = '10px';
					inputs[j].style.MozBorderRadius = '10px';
					inputs[j].style.WebkitBorderRadius = '10px';
				}
		}

	}

}

function fit_concentrado_trabajador(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.50) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.12);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'trabajador_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'trabajador_rfc_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'servicio_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'limite_inferior_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'limite_superior_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'trabajador_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.45) + 'px';
				else if(textareas[j].getAttribute('class') == 'limite_inferior_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'limite_superior_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'trabajador_rfc_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';
				else if(selects[j].getAttribute('class') == 'servicio_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.85) + 'px';

			}

		}

	}

}

function fit_contrato(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs and submit button
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.15);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.60);//horizontal left directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';//'2px solid #fff';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'puesto_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'tipo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'jornada_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'puesto_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.30) + 'px';
				else if(textareas[j].getAttribute('class') == 'tipo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.30) + 'px';
				else if(textareas[j].getAttribute('class') == 'jornada_label')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.16) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_label')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'tipo_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.30) + 'px';
				else if(selects[j].getAttribute('class') == 'jornada_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.16) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_credito_al_salario(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.70) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{

				if(textareas[j].getAttribute('class') == 'hidden_textarea')
					textareas[j].style.visibility = 'hidden';

			}

			fit_tabla(fieldsets[i]);
		}

	}

}

function fit_descuento_pendiente(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.55) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.20);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.58);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'cantidad_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'motivo_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'numero_de_descuentos_label')
				{
					labels[j].style.top = labels[j - 1].offsetTop + labels[j - 1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_inicio_label')
				{
					labels[j].style.top = labels[j - 1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_termino_label')
				{
					labels[j].style.top = labels[j - 1].offsetTop + labels[j - 1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'nomina_label')
				{
					labels[j].style.top = labels[j - 1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'retencion_label')
				{
					labels[j].style.top = labels[j - 1].offsetTop + labels[j - 1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'id_label')
				{
					labels[j].style.top = labels[j - 1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
					textareas[j].style.visibility = 'hidden';

				if(textareas[j].getAttribute('class') == 'cantidad_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';
				else if(textareas[j].getAttribute('class') == 'motivo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.40) + 'px';
				else if(textareas[j].getAttribute('class') == 'numero_de_descuentos_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.07) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_inicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_termino_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'nomina_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';
				else if(textareas[j].getAttribute('class') == 'retencion_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.30) + 'px';
				else if(textareas[j].getAttribute('class') == 'id_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';

			}

		}

	}

}

function fit_establecimiento(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.95) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'empresa_administradora_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + 5 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.22);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';

				if(labels[j].getAttribute('class') == 'tipo_de_establecimiento_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderBottomLeftRadius = '10px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusBottomleft = '10px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderBottomLeftRadius = '10px';
				}
				else if(labels[j].getAttribute('class') == 'domicilio_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = parseInt(fieldsets[i].offsetWidth * 0.50) + 'px';
					labels[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.45) + 'px';
					labels[j].style.textAlign = 'center';
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderTopRightRadius = '10px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusTopright = '10px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderTopRightRadius = '10px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') == 'domicilio_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + textareas[j].previousSibling.offsetHeight + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + 'px';
					textareas[j].style.borderLeft = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderBottomLeftRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusBottomleft = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderBottomLeftRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = vd * 5 + 'px';
				}
				else if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'tipo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'domicilio_textarea')
					textareas[j].style.width = textareas[j].previousSibling.offsetWidth - _border_width * 2 + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'tipo_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_factor_de_descuento(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.50) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
	var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.30);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.65);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'factor_de_descuento_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_inicio_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cobrar_diferencia_inicial_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_cobro_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'factor_de_descuento_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_inicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_cobro_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';

			}

			//checkboxes
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)

				if(inputs[j].getAttribute('type') == 'checkbox')
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = 0;
					inputs[j].style.margin = 0;
					inputs[j].style.border = 'none';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 2 + 'px';
					inputs[j].style.borderRadius = '10px';
					inputs[j].style.MozBorderRadius = '10px';
					inputs[j].style.WebkitBorderRadius = '10px';
				}

			//images
			var images = fieldsets[i].getElementsByTagName('img');

			for(var j=0; j<images.length; j++)
			{
				images[j].style.display = 'block';
				images[j].style.position = 'absolute';
				images[j].style.padding = '0';
				images[j].style.margin = 0;
				images[j].style.border = 'none';
				images[j].style.background = 'none';
				images[j].style.top = images[j].previousSibling.offsetTop + 'px';
				images[j].style.left = images[j].previousSibling.offsetLeft + images[j].previousSibling.offsetWidth + 'px';
				images[j].style.cursor = 'pointer';

				if(images[j].getAttribute('class') == 'calculate_button')
					images[j].src = 'icon.php?subject=calcular&height=' + images[j].previousSibling.offsetHeight;
				else if(images[j].getAttribute('class') == 'resumen_button')
					images[j].src = 'icon.php?subject=resumen&height=' + images[j].previousSibling.offsetHeight;

			}

		}

	}

}

function fit_finiquito(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(_form.offsetWidth * 0.15);//horizontal left directriz
			var hrd = parseInt(_form.offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'trabajador_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'trabajador_rfc_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'servicio_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'gratificacion_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'pago_neto_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'pagar_prima_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'pago_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-2].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'ver_recibo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'finiquitador_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-2].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'ver_carta_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'lugar_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-2].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'trabajador_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.45) + 'px';
				else if(textareas[j].getAttribute('class') == 'anos_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'gratificacion_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'pago_neto_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'trabajador_rfc_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.20) + 'px';
				else if(textareas[j].getAttribute('class') == 'servicio_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.82) + 'px';
				else if(textareas[j].getAttribute('class') == 'pago_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.20) + 'px';
				else if(textareas[j].getAttribute('class') == 'lugar_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.40) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'trabajador_rfc_select')
					selects[j].style.width = parseInt(_form.offsetWidth * 0.20) + 'px';
				else if(selects[j].getAttribute('class') == 'servicio_select')
					selects[j].style.width = parseInt(_form.offsetWidth * 0.82) + 'px';
				else if(selects[j].getAttribute('class') == 'pago_select')
					selects[j].style.width = parseInt(_form.offsetWidth * 0.24) + 'px';
				else if(selects[j].getAttribute('class') == 'finiquitador_select')
					selects[j].style.width = parseInt(_form.offsetWidth * 0.24) + 'px';

			}

			//checkboxes
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)

				if(inputs[j].getAttribute('type') == 'checkbox')
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = 0;
					inputs[j].style.margin = 0;
					inputs[j].style.border = 'none';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 2 + 'px';
					inputs[j].style.borderRadius = '10px';
					inputs[j].style.MozBorderRadius = '10px';
					inputs[j].style.WebkitBorderRadius = '10px';
				}

			//buttons
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)
			{
				inputs[j].style.display = 'block';
				inputs[j].style.position = 'absolute';
				inputs[j].style.padding = '2px';
				inputs[j].style.margin = 0;
				inputs[j].style.border = 'none';
				inputs[j].style.background = color_opaque;
				inputs[j].style.font = font;
				inputs[j].style.color = '#fff';

				if(inputs[j].getAttribute('class') == 'view_button')
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = '1px';
					inputs[j].style.margin = 0;
					inputs[j].style.border = 'none';
					inputs[j].style.background = color_opaque;
					inputs[j].style.color = '#fff';
					inputs[j].style.font = font;
					inputs[j].style.borderTopRightRadius = '10px';
					inputs[j].style.borderBottomRightRadius = '10px';
					inputs[j].style.MozBorderRadiusTopright = '10px';
					inputs[j].style.MozBorderRadiusBottomright = '10px';
					inputs[j].style.WebkitBorderTopRightRadius = '10px';
					inputs[j].style.WebkitBorderBottomRightRadius = '10px';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
					inputs[j].style.height = inputs[j].previousSibling.offsetHeight + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 'px';
					inputs[j].style.cursor = 'pointer';
				}

			}

		}

	}

}

function fit_fondo_de_garantia(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.30);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.72);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';//'2px solid #fff';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'porcentaje_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_inicio_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'porcentaje_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.05) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_inicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_incapacidad(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.20);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.65);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'fecha_de_inicio_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_termino_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'fecha_de_inicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_termino_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_login()
{
	document.body.style.background = '#fff';
	var forms = document.getElementsByTagName('form');
	var _form = forms[0];
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.MozBoxShadow = "0 0 8px 8px #888";
	_form.style.WebkitBoxShadow = "0 0 8px 8px#888";
	_form.style.boxShadow = "0 0 8px 8px #888";
	_form.style.background = '#fff';
	_form.style.width = '350px';
	_form.style.height = _height + 150 + 'px';
	_form.style.top = parseInt((window_height - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((window_width - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
	var _divs = document.getElementsByTagName('div');
	_div = _divs[0];
	_div.style.display = 'block';
	_div.style.position = 'absolute';
	_div.style.padding = '0px';
	_div.style.margin = 0;
	_div.style.zIndex = 1;
	_div.style.border = 'none';
	_div.style.background = '#555';
	_div.style.width = _form.offsetWidth - _border_width * 2 + 'px';
	_div.style.height = _height + 'px';
	_div.style.top = '0px';
	_div.style.left = '0px';
	//ok button
	var images = _form.getElementsByTagName('IMG');

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
			submit_button.style.top = _form.offsetHeight - _height - _border_width * 4 + 'px';
			submit_button.style.zIndex = 1;
			submit_button.style.cursor = 'pointer';
			submit_button.style.left = _form.offsetWidth - _height - _border_width * 4 + 'px';
		}
	//logo
	var image = document.createElement('img');
	_div.appendChild(image);
	image.style.display = 'block';
	image.style.position = 'absolute';
	image.src = 'icon.php?subject=logotipo&height=' + _height;
	//labels
	var labels = _form.getElementsByTagName('label');
	var vd = 15;//vertical distance between elements
	var hld = parseInt(_form.offsetWidth * 0.35);//horizontal left directriz
	var hrd = parseInt(_form.offsetWidth * 0.65);//horizontal right directriz

	for(var j=0; j<labels.length; j++)
	{
		labels[j].style.display = 'block';
		labels[j].style.position = 'absolute';
		labels[j].style.padding = '4px';
		labels[j].style.margin = 0;
		labels[j].style.border = 'none';
		labels[j].style.background = '#555';
		labels[j].style.font = font;
		labels[j].style.color = '#fff';
		labels[j].style.borderTopLeftRadius = '10px';
		labels[j].style.borderBottomLeftRadius = '10px';
		labels[j].style.MozBorderRadiusTopleft = '10px';
		labels[j].style.MozBorderRadiusBottomleft = '10px';
		labels[j].style.WebkitBorderTopLeftRadius = '10px';
		labels[j].style.WebkitBorderBottomLeftRadius = '10px';

		if(labels[j].getAttribute('class') == 'usuario_label')
		{
			labels[j].style.top = _height + vd + 'px';
			labels[j].style.left = hld - labels[j].offsetWidth + 'px';
		}
		else if(labels[j].getAttribute('class') == 'cuenta_label')
		{
			labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
			labels[j].style.left = hld - labels[j].offsetWidth + 'px';
		}
		else if(labels[j].getAttribute('class') == 'contrasena_label')
		{
			labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
			labels[j].style.left = hld - labels[j].offsetWidth + 'px';
		}

	}

	//inputs
	var inputs = _form.getElementsByTagName('input');

	for(var j=0; j<inputs.length; j++)
	{
		inputs[j].style.display = 'block';
		inputs[j].style.position = 'absolute';
		inputs[j].style.padding = 0;
		inputs[j].style.margin = 0;
		inputs[j].style.border = 'none';
		inputs[j].style.background = '#fff';
		inputs[j].style.font = font;
		inputs[j].style.color = '#555';
		inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
		inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 'px';
		inputs[j].style.borderTop = _border_width + 'px solid #555';
		inputs[j].style.borderRight = _border_width + 'px solid #555';
		inputs[j].style.borderBottom = _border_width + 'px solid #555';
		inputs[j].style.borderTopRightRadius = '10px';
		inputs[j].style.borderBottomRightRadius = '10px';
		inputs[j].style.MozBorderRadiusTopright = '10px';
		inputs[j].style.MozBorderRadiusBottomright = '10px';
		inputs[j].style.WebkitBorderTopRightRadius = '10px';
		inputs[j].style.WebkitBorderBottomRightRadius = '10px';
		inputs[j].style.height = inputs[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
		inputs[j].style.width = parseInt(_form.offsetWidth * 0.45) + 'px';
	}

}

function fit_logo(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs and submit button
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.left = _form.offsetLeft  + _offset + 'px';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
		}

		var inputs = _form.getElementsByTagName('input');

		for(var i=0; i<inputs.length; i++)

			if(inputs[i].getAttribute('class') == 'submit_button')
			{
				inputs[i].style.display = 'block';
				inputs[i].style.position = 'absolute';
				inputs[i].style.padding = '4px';
				inputs[i].style.margin = 0;
				inputs[i].style.border = _border_width + 'px solid #555';
				inputs[i].style.background = '#fff';
				inputs[i].style.borderRadius = '10px';
				inputs[i].style.MozBorderRadius = '10px';
				inputs[i].style.WebkitBorderRadius = '10px';
				inputs[i].style.color = '#555';
				inputs[i].style.top = _form.parentNode.offsetHeight - _form.offsetTop - inputs[i].offsetHeight - _offset * 4 + 'px';
				inputs[i].style.font = title_font;
				inputs[i].style.zIndex = 1;
				inputs[i].style.cursor = 'pointer';
				inputs[i].style.left = _form.parentNode.offsetWidth - _form.offsetLeft - inputs[i].offsetWidth - _offset * 5 + 'px';
			}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var hld = parseInt(fieldsets[i].offsetWidth * 0.30);//horizontal left directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'archivo_label')
				{
					labels[j].style.top = parseInt((fieldsets[i].offsetHeight - labels[j].offsetHeight) / 2) + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{

				if(textareas[j].getAttribute('class') != 'hidden_textarea' && textareas[j].getAttribute('className') != 'hidden_textarea')
				{
					textareas[j].style.display = 'block';
					textareas[j].style.position = 'absolute';
					textareas[j].style.padding = 0;
					textareas[j].style.margin = 0;
					textareas[j].style.border = 'none';
					textareas[j].style.background = '#fff';
					textareas[j].style.font = font;
					textareas[j].style.color = '#555';
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';

					if(textareas[j].getAttribute('class') == 'archivo_textarea')
						textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.60) + 'px';

				}
				else
					textareas[j].style.visibility = 'hidden';

			}

			//file input
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)
			{
				inputs[j].style.display = 'block';
				inputs[j].style.position = 'absolute';
				inputs[j].style.padding = 0;
				inputs[j].style.margin = 0;
				inputs[j].style.border = 'none';
				inputs[j].style.background = '#fff';
				inputs[j].style.font = font;
				inputs[j].style.color = '#555';
				inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
				inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 'px';
				inputs[j].style.borderTop = _border_width + 'px solid #555';
				inputs[j].style.borderRight = _border_width + 'px solid #555';
				inputs[j].style.borderBottom = _border_width + 'px solid #555';
				inputs[j].style.borderTopRightRadius = '10px';
				inputs[j].style.borderBottomRightRadius = '10px';
				inputs[j].style.MozBorderRadiusTopright = '10px';
				inputs[j].style.MozBorderRadiusBottomright = '10px';
				inputs[j].style.WebkitBorderTopRightRadius = '10px';
				inputs[j].style.WebkitBorderBottomRightRadius = '10px';
				inputs[j].style.height = inputs[j].previousSibling.offsetHeight + 'px';

				if(inputs[j].getAttribute('class') == 'file_input')
					inputs[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.60) + 'px';

			}

		}

	}

}

function fit_isr(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.70) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{

				if(textareas[j].getAttribute('class') == 'hidden_textarea')
					textareas[j].style.visibility = 'hidden';

			}

			fit_tabla(fieldsets[i]);
		}

	}

}

function fit_vacaciones(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.20);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.65);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'fecha_de_inicio_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_termino_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'fecha_de_inicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_termino_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_instrumento_notarial(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.70) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
				_divs[i].style.borderTopRightRadius = '10px';
				_divs[i].style.borderTopLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusTopright = '10px';
				_divs[i].style.MozBorderRadiusTopleft = '10px';
				_divs[i].style.WebkitBorderTopRightRadius = '10px';
				_divs[i].style.WebkitBorderTopLeftRadius = '10px';
				_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			}
			else if(_divs[i].getAttribute('class') == 'archivo_digital_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottomRightRadius = '10px';
				_divs[i].style.borderBottomLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusBottomright = '10px';
				_divs[i].style.MozBorderRadiusBottomleft = '10px';
				_divs[i].style.WebkitBorderBottomRightRadius = '10px';
				_divs[i].style.WebkitBorderBottomLeftRadius = '10px';
				_divs[i].style.top = _form.offsetTop + _form.offsetHeight + 'px';
			}
			else
			{
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + _offset + 1 + 'px';
				_divs[i].style.borderTopRightRadius = '10px';
				_divs[i].style.borderTopLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusTopright = '10px';
				_divs[i].style.MozBorderRadiusTopleft = '10px';
				_divs[i].style.WebkitBorderTopRightRadius = '10px';
				_divs[i].style.WebkitBorderTopLeftRadius = '10px';
				_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			}

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.20);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.70);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = 'none';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.background = '#555';

				if(labels[j].getAttribute('class') == 'extracto_label')
				{
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderTopRightRadius = '10px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusTopright = '10px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderTopRightRadius = '10px';
				}
				else
				{
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderBottomLeftRadius = '10px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusBottomleft = '10px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderBottomLeftRadius = '10px';
				}

				if(labels[j].getAttribute('class') == 'tipo_de_documento_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'numero_de_instrumento_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'nombre_del_notario_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'numero_de_notario_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'seccion_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'volumen_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'libro_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'partida_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'extracto_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = '5px';
					labels[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.56) + 'px';
					labels[j].style.textAlign = 'center';
				}
				else if(labels[j].getAttribute('class') == 'tomo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'numero_de_folio_mercantil_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd * 3 + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'rpp_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_celebracion_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_inscripcion_rpp_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') == 'extracto_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + textareas[j].previousSibling.offsetHeight + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + 'px';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderLeft = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottomLeftRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusBottomleft = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderBottomLeftRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = vd * 5 + 'px';
				}
				else if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'tipo_de_documento_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.25) + 'px';
				else if(textareas[j].getAttribute('class') == 'numero_de_instrumento_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'nombre_del_notario_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.35) + 'px';
				else if(textareas[j].getAttribute('class') == 'numero_de_notario_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'seccion_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'volumen_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'libro_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'partida_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'extracto_textarea')
					textareas[j].style.width = textareas[j].previousSibling.offsetWidth - _border_width * 2 + 'px';
				else if(textareas[j].getAttribute('class') == 'tomo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'rpp_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'numero_de_folio_mercantil_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_inscripcion_rpp_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_celebracion_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

		}

	}

}

function fit_monto_fijo_mensual(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.50) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
	var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.30);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.65);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'monto_fijo_mensual_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_inicio_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cobrar_diferencia_inicial_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_cobro_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'monto_fijo_mensual_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_inicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_cobro_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';

			}

			//checkboxes
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)

				if(inputs[j].getAttribute('type') == 'checkbox')
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = 0;
					inputs[j].style.margin = 0;
					inputs[j].style.border = 'none';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 2 + 'px';
					inputs[j].style.borderRadius = '10px';
					inputs[j].style.MozBorderRadius = '10px';
					inputs[j].style.WebkitBorderRadius = '10px';
				}

			//images
			var images = fieldsets[i].getElementsByTagName('img');

			for(var j=0; j<images.length; j++)
			{
				images[j].style.display = 'block';
				images[j].style.position = 'absolute';
				images[j].style.padding = '0';
				images[j].style.margin = 0;
				images[j].style.border = 'none';
				images[j].style.background = 'none';
				images[j].style.top = images[j].previousSibling.offsetTop + 'px';
				images[j].style.left = images[j].previousSibling.offsetLeft + images[j].previousSibling.offsetWidth + 'px';
				images[j].style.cursor = 'pointer';

				if(images[j].getAttribute('class') == 'calculate_button')
					images[j].src = 'icon.php?subject=calcular&height=' + images[j].previousSibling.offsetHeight;
				else if(images[j].getAttribute('class') == 'resumen_button')
					images[j].src = 'icon.php?subject=resumen&height=' + images[j].previousSibling.offsetHeight;

			}

		}

	}

}

function fit_nomina(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.70) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
	var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'isr_asalariados_tab' || _divs[i].getAttribute('class') == 'isr_asimilables_tab' || _divs[i].getAttribute('class') == 'imss_tab' || _divs[i].getAttribute('class') == 'prestaciones_tab' || _divs[i].getAttribute('class') == 'nomina_asalariados_tab' || _divs[i].getAttribute('class') == 'nomina_asimilables_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + 5 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.22);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.57);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'limite_inferior_del_periodo_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'limite_superior_del_periodo_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'etapa_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'servicio_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_pago_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'calcular_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'resumen_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'timbrar_label')
				{
					labels[j].style.background = color_opaque;
					labels[j].style.font = title_font;
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'view_cfdi_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cancel_label')
				{
					labels[j].style.background = '#cc0033';
					labels[j].style.font = title_font;
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'descargar_cfdi_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'limite_inferior_del_periodo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';
				else if(textareas[j].getAttribute('class') == 'limite_superior_del_periodo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';
				else if(textareas[j].getAttribute('class') == 'etapa_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';
				else if(textareas[j].getAttribute('class') == 'servicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.40) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_elaboracion_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'servicio_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.40) + 'px';

			}

			//images
			var images = fieldsets[i].getElementsByTagName('img');

			for(var j=0; j<images.length; j++)
			{
				images[j].style.display = 'block';
				images[j].style.position = 'absolute';
				images[j].style.padding = '0';
				images[j].style.margin = 0;
				images[j].style.border = 'none';
				images[j].style.background = 'none';
				images[j].style.top = images[j].previousSibling.offsetTop + 'px';
				images[j].style.left = images[j].previousSibling.offsetLeft + images[j].previousSibling.offsetWidth + 'px';
				images[j].style.cursor = 'pointer';

				if(images[j].getAttribute('class') == 'calculate_button')
					images[j].src = 'icon.php?subject=calcular&height=' + images[j].previousSibling.offsetHeight;
				else if(images[j].getAttribute('class') == 'resumen_button')
					images[j].src = 'icon.php?subject=resumen&height=' + images[j].previousSibling.offsetHeight;
				else if(images[j].getAttribute('class') == 'timbrar_button')
					images[j].src = 'icon.php?subject=timbrar&height=' + images[j].previousSibling.offsetHeight;
				else if(images[j].getAttribute('class') == 'view_cfdi_button')
					images[j].src = 'icon.php?subject=resumen&height=' + images[j].previousSibling.offsetHeight;
				else if(images[j].getAttribute('class') == 'cancel_button')
					images[j].src = 'icon.php?subject=cancel&height=' + images[j].previousSibling.offsetHeight;
				else if(images[j].getAttribute('class') == 'download_button')
					images[j].src = 'icon.php?subject=download&height=' + images[j].previousSibling.offsetHeight;

			}

		}
		else
			fit_fieldset(fieldsets[i]);

	}

}

function fit_nomina_propuesta(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.70) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
	var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'isr_asalariados_tab' || _divs[i].getAttribute('class') == 'isr_asimilables_tab' || _divs[i].getAttribute('class') == 'imss_tab' || _divs[i].getAttribute('class') == 'prestaciones_tab' || _divs[i].getAttribute('class') == 'nomina_asalariados_tab' || _divs[i].getAttribute('class') == 'nomina_asimilables_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + 5 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.22);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.57);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'tipo_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'resumen_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'tipo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';

			}

			//images
			var images = fieldsets[i].getElementsByTagName('img');

			for(var j=0; j<images.length; j++)
			{
				images[j].style.display = 'block';
				images[j].style.position = 'absolute';
				images[j].style.padding = '0';
				images[j].style.margin = 0;
				images[j].style.border = 'none';
				images[j].style.background = 'none';
				images[j].style.top = images[j].previousSibling.offsetTop + 'px';
				images[j].style.left = images[j].previousSibling.offsetLeft + images[j].previousSibling.offsetWidth + 'px';
				images[j].style.cursor = 'pointer';

				if(images[j].getAttribute('class') == 'resumen_button')
					images[j].src = 'icon.php?subject=resumen&height=' + images[j].previousSibling.offsetHeight;

			}

		}
		else
			fit_fieldset_propuesta(fieldsets[i]);

	}

}

function fit_pension_alimenticia(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.12);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.72);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';//'2px solid #fff';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'cantidad_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_inicio_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'beneficiario_label')
				{
					labels[j].style.top = labels[j - 1].offsetTop + labels[j - 1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_termino_label')
				{
					labels[j].style.top = labels[j - 1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'folio_ife_label')
				{
					labels[j].style.top = labels[j - 1].offsetTop + labels[j - 1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'no_de_expediente_label')
				{
					labels[j].style.top = labels[j - 1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'no_de_oficio_label')
				{
					labels[j].style.top = labels[j - 1].offsetTop + labels[j - 1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'porcentaje_del_salario_label')
				{
					labels[j].style.top = labels[j - 1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'cantidad_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_inicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'beneficiario_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.45) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_termino_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'folio_ife_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';
				else if(textareas[j].getAttribute('class') == 'no_de_expediente_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';
				else if(textareas[j].getAttribute('class') == 'no_de_oficio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';
				else if(textareas[j].getAttribute('class') == 'porcentaje_del_salario_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_photo(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs and submit button
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.left = _form.offsetLeft  + _offset + 'px';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
		}

		var inputs = _form.getElementsByTagName('input');

		for(var i=0; i<inputs.length; i++)

			if(inputs[i].getAttribute('class') == 'submit_button')
			{
				inputs[i].style.display = 'block';
				inputs[i].style.position = 'absolute';
				inputs[i].style.padding = '4px';
				inputs[i].style.margin = 0;
				inputs[i].style.border = _border_width + 'px solid #555';
				inputs[i].style.background = '#fff';
				inputs[i].style.borderRadius = '10px';
				inputs[i].style.MozBorderRadius = '10px';
				inputs[i].style.WebkitBorderRadius = '10px';
				inputs[i].style.color = '#555';
				inputs[i].style.top = _form.parentNode.offsetHeight - _form.offsetTop - inputs[i].offsetHeight - _offset * 4 + 'px';
				inputs[i].style.font = title_font;
				inputs[i].style.zIndex = 1;
				inputs[i].style.cursor = 'pointer';
				inputs[i].style.left = _form.parentNode.offsetWidth - _form.offsetLeft - inputs[i].offsetWidth - _offset * 5 + 'px';
			}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var hld = parseInt(fieldsets[i].offsetWidth * 0.30);//horizontal left directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'archivo_label')
				{
					labels[j].style.top = parseInt((fieldsets[i].offsetHeight - labels[j].offsetHeight) / 2) + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{

				if(textareas[j].getAttribute('class') != 'hidden_textarea' && textareas[j].getAttribute('className') != 'hidden_textarea')
				{
					textareas[j].style.display = 'block';
					textareas[j].style.position = 'absolute';
					textareas[j].style.padding = 0;
					textareas[j].style.margin = 0;
					textareas[j].style.border = 'none';
					textareas[j].style.background = '#fff';
					textareas[j].style.font = font;
					textareas[j].style.color = '#555';
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';

					if(textareas[j].getAttribute('class') == 'archivo_textarea')
						textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.60) + 'px';

				}
				else
					textareas[j].style.visibility = 'hidden';

			}

			//file input
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)
			{
				inputs[j].style.display = 'block';
				inputs[j].style.position = 'absolute';
				inputs[j].style.padding = 0;
				inputs[j].style.margin = 0;
				inputs[j].style.border = 'none';
				inputs[j].style.background = '#fff';
				inputs[j].style.font = font;
				inputs[j].style.color = '#555';
				inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
				inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 'px';
				inputs[j].style.borderTop = _border_width + 'px solid #555';
				inputs[j].style.borderRight = _border_width + 'px solid #555';
				inputs[j].style.borderBottom = _border_width + 'px solid #555';
				inputs[j].style.borderTopRightRadius = '10px';
				inputs[j].style.borderBottomRightRadius = '10px';
				inputs[j].style.MozBorderRadiusTopright = '10px';
				inputs[j].style.MozBorderRadiusBottomright = '10px';
				inputs[j].style.WebkitBorderTopRightRadius = '10px';
				inputs[j].style.WebkitBorderBottomRightRadius = '10px';
				inputs[j].style.height = inputs[j].previousSibling.offsetHeight + 'px';

				if(inputs[j].getAttribute('class') == 'file_input')
					inputs[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.60) + 'px';

			}

		}

	}

}

function fit_porcentaje_de_descuento(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.50) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
	var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.30);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.65);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'porcentaje_de_descuento_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_inicio_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cobrar_diferencia_inicial_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_cobro_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'porcentaje_de_descuento_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_inicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_cobro_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';

			}

			//checkboxes
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)

				if(inputs[j].getAttribute('type') == 'checkbox')
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = 0;
					inputs[j].style.margin = 0;
					inputs[j].style.border = 'none';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 2 + 'px';
					inputs[j].style.borderRadius = '10px';
					inputs[j].style.MozBorderRadius = '10px';
					inputs[j].style.WebkitBorderRadius = '10px';
				}

			//images
			var images = fieldsets[i].getElementsByTagName('img');

			for(var j=0; j<images.length; j++)
			{
				images[j].style.display = 'block';
				images[j].style.position = 'absolute';
				images[j].style.padding = '0';
				images[j].style.margin = 0;
				images[j].style.border = 'none';
				images[j].style.background = 'none';
				images[j].style.top = images[j].previousSibling.offsetTop + 'px';
				images[j].style.left = images[j].previousSibling.offsetLeft + images[j].previousSibling.offsetWidth + 'px';
				images[j].style.cursor = 'pointer';

				if(images[j].getAttribute('class') == 'calculate_button')
					images[j].src = 'icon.php?subject=calcular&height=' + images[j].previousSibling.offsetHeight;
				else if(images[j].getAttribute('class') == 'resumen_button')
					images[j].src = 'icon.php?subject=resumen&height=' + images[j].previousSibling.offsetHeight;

			}

		}

	}

}

function fit_porcentaje_de_cuotas_imss(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.95) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.40) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.10);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.70);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'ano_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'valor_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.45) + 'px';
				else if(textareas[j].getAttribute('class') == 'valor_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.13) + 'px';
				else if(textareas[j].getAttribute('class') == 'valor_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

		}

	}

}

function fit_prestamo(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.70) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{

				if(textareas[j].getAttribute('class') == 'hidden_textarea')
					textareas[j].style.visibility = 'hidden';

			}

			fit_tabla_de_prestamo(fieldsets[i]);
		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_prevision_social(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.95) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicios_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.borderTopRightRadius = '10px';
				_divs[i].style.borderTopLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusTopright = '10px';
				_divs[i].style.MozBorderRadiusTopleft = '10px';
				_divs[i].style.WebkitBorderTopRightRadius = '10px';
				_divs[i].style.WebkitBorderTopLeftRadius = '10px';
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
				_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			}
			else
			{
				_divs[i].style.borderBottomRightRadius = '10px';
				_divs[i].style.borderBottomLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusBottomright = '10px';
				_divs[i].style.MozBorderRadiusBottomleft = '10px';
				_divs[i].style.WebkitBorderBottomRightRadius = '10px';
				_divs[i].style.WebkitBorderBottomLeftRadius = '10px';
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.top = _form.offsetTop + _form.offsetHeight + 'px';
			}

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.22);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.30) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_prima(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'registro_patronal_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.22);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.70);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'valor_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'valor_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_propuesta(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.95) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.73) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'configuraciones_adicionales_tab' || _divs[i].getAttribute('class') == 'registro_patronal_tab' || _divs[i].getAttribute('class') == 'nomina_propuesta_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + 5 + 'px';

		}

		var inputs = _form.getElementsByTagName('input');

		for(var i=0; i<inputs.length; i++)

			if(inputs[i].getAttribute('class') == 'submit_button')
			{
				inputs[i].style.display = 'block';
				inputs[i].style.position = 'absolute';
				inputs[i].style.padding = '4px';
				inputs[i].style.margin = 0;
				inputs[i].style.border = _border_width + 'px solid #555';
				inputs[i].style.background = '#fff';
				inputs[i].style.borderRadius = '10px';
				inputs[i].style.MozBorderRadius = '10px';
				inputs[i].style.WebkitBorderRadius = '10px';
				inputs[i].style.color = '#555';
				inputs[i].style.top = _form.parentNode.offsetHeight - _form.offsetTop - inputs[i].offsetHeight - _offset * 4 + 'px';
				inputs[i].style.font = title_font;
				inputs[i].style.zIndex = 1;
				inputs[i].style.cursor = 'pointer';
				inputs[i].style.left = _form.parentNode.offsetWidth - _form.offsetLeft - inputs[i].offsetWidth - _offset * 5 + 'px';
			}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.33);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.73);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'empresa_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'vacaciones_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'prima_vacacional_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'antiguedad_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'aguinaldo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cuotas_imss_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == '_5_infonavit_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dias_del_periodo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dias_de_aguinaldo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'base_de_prestaciones_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'prima_de_riesgo_cliente_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'porcentaje_de_honorarios_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'prima_de_riesgo_administradora_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cobrar_iva_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'alimentacion_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cobrar_impuesto_sobre_nomina_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'habitacion_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'quince_anos_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'incluir_contribuciones_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dcipn_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'incidencias_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cuadro_comparativo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//inputs
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)
			{
				inputs[j].style.display = 'block';
				inputs[j].style.position = 'absolute';
				inputs[j].style.padding = 0;
				inputs[j].style.margin = 0;
				inputs[j].style.border = 'none';
				inputs[j].style.background = '#fff';
				inputs[j].style.font = font;
				inputs[j].style.color = '#555';
				inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
				inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 'px';
				inputs[j].style.borderTop = _border_width + 'px solid #555';
				inputs[j].style.borderRight = _border_width + 'px solid #555';
				inputs[j].style.borderBottom = _border_width + 'px solid #555';
				inputs[j].style.borderTopRightRadius = '10px';
				inputs[j].style.borderBottomRightRadius = '10px';
				inputs[j].style.MozBorderRadiusTopright = '10px';
				inputs[j].style.MozBorderRadiusBottomright = '10px';
				inputs[j].style.WebkitBorderTopRightRadius = '10px';
				inputs[j].style.WebkitBorderBottomRightRadius = '10px';
				inputs[j].style.height = inputs[j].previousSibling.offsetHeight - _border_width * 2 + 'px';

				if(inputs[j].getAttribute('class') == 'file_input')
				{
					inputs[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.25) + 'px';
					inputs[j].style.opacity = 0;
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight + 'px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
					textareas[j].style.visibility = 'hidden';

				if(textareas[j].getAttribute('class') == 'empresa_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.30) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'vacaciones_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'prima_vacacional_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'antiguedad_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'aguinaldo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'cuotas_imss_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == '_5_infonavit_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'dias_del_periodo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.16) + 'px';
				else if(textareas[j].getAttribute('class') == 'dias_de_aguinaldo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';
				else if(textareas[j].getAttribute('class') == 'base_de_prestaciones_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.13) + 'px';
				else if(textareas[j].getAttribute('class') == 'prima_de_riesgo_cliente_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';
				else if(textareas[j].getAttribute('class') == 'prima_de_riesgo_administradora_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';
				else if(textareas[j].getAttribute('class') == 'porcentaje_de_honorarios_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';
				else if(textareas[j].getAttribute('class') != 'hidden_textarea')//aux
				{
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.25) - textareas[j].previousSibling.offsetHeight + 'px';
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + 'px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
					textareas[j].style.zIndex = -1;
				}

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'vacaciones_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == 'prima_vacacional_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == 'antiguedad_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == 'aguinaldo_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == 'cuotas_imss_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == '_5_infonavit_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == 'base_de_prestaciones_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

			//checkboxes
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)

				if(inputs[j].getAttribute('type') == 'checkbox')
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = 0;
					inputs[j].style.margin = 0;
					inputs[j].style.border = 'none';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 2 + 'px';
					inputs[j].style.borderRadius = '10px';
					inputs[j].style.MozBorderRadius = '10px';
					inputs[j].style.WebkitBorderRadius = '10px';
				}

			//images
			var images = fieldsets[i].getElementsByTagName('img');

			for(var j=0; j<images.length; j++)
			{
				images[j].style.display = 'block';
				images[j].style.position = 'absolute';
				images[j].style.padding = '0';
				images[j].style.margin = 0;
				images[j].style.border = 'none';
				images[j].style.background = 'none';
				images[j].style.top = images[j].previousSibling.offsetTop + 'px';
				images[j].style.left = images[j].previousSibling.offsetLeft + images[j].previousSibling.offsetWidth + 'px';
				images[j].style.cursor = 'pointer';

				if(images[j].getAttribute('class') == 'aux_button')
				{
					images[j].src = 'icon.php?subject=_import&height=' + labels[0].offsetHeight;
					images[j].style.zIndex = -1;
				}
				else if(images[j].getAttribute('class') == 'cuadro_comparativo_button')
					images[j].src = 'icon.php?subject=calcular&height=' + labels[0].offsetHeight;

			}

		}
		else if(fieldsets[i].getAttribute('class') == 'Configuraciones_adicionales_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.44);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.86);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'dcipla_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dppla_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dgapla_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dpn_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dgan_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cgaa_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'ivash_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//checkboxes
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)

				if(inputs[j].getAttribute('type') == 'checkbox')
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = 0;
					inputs[j].style.margin = 0;
					inputs[j].style.border = 'none';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 2 + 'px';
					inputs[j].style.borderRadius = '10px';
					inputs[j].style.MozBorderRadius = '10px';
					inputs[j].style.WebkitBorderRadius = '10px';
				}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_regimen_fiscal(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.12);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.80);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'regimen_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'regimen_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.70) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'regimen_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.70) + 'px';

			}

		}

	}

}

function fit_registro_patronal(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.50) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.15);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.60);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'fraccion_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'numero_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'clase_de_riesgo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'folio_imss_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'fraccion_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.25) + 'px';
				else if(textareas[j].getAttribute('class') == 'numero_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.25) + 'px';
				else if(textareas[j].getAttribute('class') == 'clase_de_riesgo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.25) + 'px';
				else if(textareas[j].getAttribute('class') == 'folio_imss_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.25) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'clase_de_riesgo_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.25) + 'px';

			}

		}

	}

}

function fit_reporte_prestaciones(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.92) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.62) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
	var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.14);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.40);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';

				if(labels[j].getAttribute('class') == 'registro_label')
				{
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderTopRightRadius = '10px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusTopright = '10px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderTopRightRadius = '10px';
					labels[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.50) + 'px';
					labels[j].style.textAlign = 'center';
				}
				else
				{
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderBottomLeftRadius = '10px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusBottomleft = '10px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderBottomLeftRadius = '10px';
				}

				if(labels[j].getAttribute('class') == 'limite_inferior_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'registro_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd + 'px';
				}
				else if(labels[j].getAttribute('class') == 'limite_superior_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';
				textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
				textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
				textareas[j].style.borderTop = _border_width + 'px solid #555';
				textareas[j].style.borderRight = _border_width + 'px solid #555';
				textareas[j].style.borderBottom = _border_width + 'px solid #555';
				textareas[j].style.borderTopRightRadius = '10px';
				textareas[j].style.borderBottomRightRadius = '10px';
				textareas[j].style.MozBorderRadiusTopright = '10px';
				textareas[j].style.MozBorderRadiusBottomright = '10px';
				textareas[j].style.WebkitBorderTopRightRadius = '10px';
				textareas[j].style.WebkitBorderBottomRightRadius = '10px';
				textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';

				if(textareas[j].getAttribute('class') == 'limite_inferior_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'limite_superior_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

			//registro div
			var divs = fieldsets[i].getElementsByTagName('div');

			for(var j=0; j<divs.length; j++)

				if(divs[j].getAttribute('class') == 'registro_div')
				{
					divs[j].style.display = 'block';
					divs[j].style.position = 'absolute';
					divs[j].style.padding = 0;
					divs[j].style.margin = 0;
					divs[j].style.border = 'none';
					divs[j].style.background = '#fff';
					divs[j].style.font = font;
					divs[j].style.color = '#555';
					divs[j].style.top = divs[j].previousSibling.offsetTop + divs[j].previousSibling.offsetHeight + 'px';
					divs[j].style.left = divs[j].previousSibling.offsetLeft + 'px';
					divs[j].style.borderRight = _border_width + 'px solid #555';
					divs[j].style.borderBottom = _border_width + 'px solid #555';
					divs[j].style.borderLeft = _border_width + 'px solid #555';
					divs[j].style.borderBottomRightRadius = '10px';
					divs[j].style.borderBottomLeftRadius = '10px';
					divs[j].style.MozBorderRadiusBottomright = '10px';
					divs[j].style.MozBorderRadiusBottomleft = '10px';
					divs[j].style.WebkitBorderBottomRightRadius = '10px';
					divs[j].style.WebkitBorderBottomLeftRadius = '10px';
					divs[j].style.height = parseInt(fieldsets[i].offsetHeight * 0.70) + 'px';
					divs[j].style.width = divs[j].previousSibling.offsetWidth - _border_width * 2 + 'px';
					divs[j].style.overflowY = 'auto';
					divs[j].style.overflowX = 'hidden';
				}

		}

	}

}

function fit_representante_legal(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.12);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.74);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'rfc_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'domicilio_label')
				{
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderTopRightRadius = '10px';
					labels[j].style.borderBottomLeftRadius = '0px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusTopright = '10px';
					labels[j].style.MozBorderRadiusBottomleft = '0px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderTopRightRadius = '10px';
					labels[j].style.WebkitBorderBottomLeftRadius = '0px';
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = '5px';
					labels[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.56) + 'px';
					labels[j].style.textAlign = 'center';
				}
				else if(labels[j].getAttribute('class') == 'estado_civil_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'lugar_de_nacimiento_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_nacimiento_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') == 'domicilio_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + textareas[j].previousSibling.offsetHeight + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + 'px';
					textareas[j].style.borderLeft = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderBottomLeftRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusBottomleft = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderBottomLeftRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = vd * 5 + 'px';
				}
				else if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.50) + 'px';
				else if(textareas[j].getAttribute('class') == 'rfc_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'domicilio_textarea')
					textareas[j].style.width = textareas[j].previousSibling.offsetWidth - _border_width * 2 + 'px';
				else if(textareas[j].getAttribute('class') == 'estado_civil_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'lugar_de_nacimiento_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.25) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_nacimiento_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';

			}

		}

	}

}

function fit_retencion_fonacot(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.20);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.66);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';//'2px solid #fff';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'numero_de_credito_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'importe_total_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_inicio_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_termino_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'numero_de_descuentos_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cobrar_un_mes_anterior_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'numero_de_credito_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';
				else if(textareas[j].getAttribute('class') == 'importe_total_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_inicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_termino_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'numero_de_descuentos_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';

			}

			//checkboxes
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)

				if(inputs[j].getAttribute('type') == 'checkbox')
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = 0;
					inputs[j].style.margin = 0;
					inputs[j].style.border = 'none';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 2 + 'px';
					inputs[j].style.borderRadius = '10px';
					inputs[j].style.MozBorderRadius = '10px';
					inputs[j].style.WebkitBorderRadius = '10px';
				}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_retencion_infonavit(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.95) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab' || _divs[i].getAttribute('class') == 'factor_de_descuento_tab' || _divs[i].getAttribute('class') == 'porcentaje_de_descuento_tab' || _divs[i].getAttribute('class') == 'monto_fijo_mensual_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab' || _divs[i].getAttribute('class') == 'factor_de_descuento_tab' || _divs[i].getAttribute('class') == 'porcentaje_de_descuento_tab' || _divs[i].getAttribute('class') == 'monto_fijo_mensual_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.22);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'fecha_de_inicio_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_termino_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'tipo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'numero_de_credito_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dias_exactos_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'fecha_de_inicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_termino_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'tipo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'numero_de_credito_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'tipo_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.17) + 'px';

			}

			//checkboxes
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)

				if(inputs[j].getAttribute('type') == 'checkbox')
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = 0;
					inputs[j].style.margin = 0;
					inputs[j].style.border = 'none';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 2 + 'px';
					inputs[j].style.borderRadius = '10px';
					inputs[j].style.MozBorderRadius = '10px';
					inputs[j].style.WebkitBorderRadius = '10px';
				}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_pago_por_seguro_de_vida(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.95) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.22);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'cantidad_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_inicio_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'cantidad_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_inicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

		}

	}

}

function fit_recibo_de_vacaciones(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.64) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(_form.offsetWidth * 0.12);//horizontal left directriz
			var hrd = parseInt(_form.offsetWidth * 0.72);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'trabajador_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'trabajador_rfc_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'servicio_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'anos_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'compensacion_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'pago_neto_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'ver_recibo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'metodo_de_pago_trabajador_label')
				{

					if(labels[j-1].innerHTML == 'Ver recibo')
					{
						labels[j].style.top = labels[j-1].offsetTop + 'px';
						labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
					}
					else
					{
						labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
						labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
					}

				}
				else if(labels[j].getAttribute('class') == 'timbrar_label')
				{
					labels[j].style.background = color_opaque;
					labels[j].style.font = title_font;
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'status_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'trabajador_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.43) + 'px';
				else if(textareas[j].getAttribute('class') == 'anos_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.30) + 'px';
				else if(textareas[j].getAttribute('class') == 'compensacion_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'trabajador_rfc_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.20) + 'px';
				else if(textareas[j].getAttribute('class') == 'servicio_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.85) + 'px';
				else if(textareas[j].getAttribute('class') == 'metodo_de_pago_trabajador_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.26) + 'px';
				else if(textareas[j].getAttribute('class') == 'status_textarea')
					textareas[j].style.width = parseInt(_form.offsetWidth * 0.85) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'trabajador_rfc_select')
					selects[j].style.width = parseInt(_form.offsetWidth * 0.20) + 'px';
				else if(selects[j].getAttribute('class') == 'servicio_select')
					selects[j].style.width = parseInt(_form.offsetWidth * 0.85) + 'px';

			}

			//images
			var images = fieldsets[i].getElementsByTagName('img');

			for(var j=0; j<images.length; j++)
			{
				images[j].style.display = 'block';
				images[j].style.position = 'absolute';
				images[j].style.padding = '0';
				images[j].style.margin = 0;
				images[j].style.border = 'none';
				images[j].style.background = 'none';
				images[j].style.top = images[j].previousSibling.offsetTop + 'px';
				images[j].style.left = images[j].previousSibling.offsetLeft + images[j].previousSibling.offsetWidth + 'px';
				images[j].style.cursor = 'pointer';

				if(images[j].getAttribute('class') == 'view_button')
					images[j].src = 'icon.php?subject=resumen&height=' + labels[0].offsetHeight;
				else if(images[j].getAttribute('class') == 'timbrar_button')
					images[j].src = 'icon.php?subject=timbrar&height=' + images[j].previousSibling.offsetHeight;

			}

		}

	}

}

function fit_salario_diario(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.95) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.22);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'cantidad_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'cantidad_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_salario_minimo(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.80) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.12);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.40);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'codigo_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'a_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'b_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'c_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'ano_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'codigo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';
				else if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.50) + 'px';
				else if(textareas[j].getAttribute('class') == 'a_textarea' || textareas[j].getAttribute('class') == 'b_textarea' || textareas[j].getAttribute('class') == 'c_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'ano_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';

			}

		}

	}

}

function fit_sello_digital(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.55) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs and submit button
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft  + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + 5 + 'px';

		}

		var inputs = _form.getElementsByTagName('input');

		for(var i=0; i<inputs.length; i++)

			if(inputs[i].getAttribute('class') == 'submit_button')
			{
				inputs[i].style.display = 'block';
				inputs[i].style.position = 'absolute';
				inputs[i].style.padding = '4px';
				inputs[i].style.margin = 0;
				inputs[i].style.border = _border_width + 'px solid #555';
				inputs[i].style.background = '#fff';
				inputs[i].style.borderRadius = '10px';
				inputs[i].style.MozBorderRadius = '10px';
				inputs[i].style.WebkitBorderRadius = '10px';
				inputs[i].style.color = '#555';
				inputs[i].style.top = _form.parentNode.offsetHeight - _form.offsetTop - inputs[i].offsetHeight - _offset * 4 + 'px';
				inputs[i].style.font = title_font;
				inputs[i].style.zIndex = 1;
				inputs[i].style.cursor = 'pointer';
				inputs[i].style.left = _form.parentNode.offsetWidth - _form.offsetLeft - inputs[i].offsetWidth - _offset * 5 + 'px';
			}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;
			var hld = parseInt(fieldsets[i].offsetWidth * 0.30);//horizontal left directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'cer_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'key_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'contrasena_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//inputs
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)
			{
				inputs[j].style.display = 'block';
				inputs[j].style.position = 'absolute';
				inputs[j].style.padding = 0;
				inputs[j].style.margin = 0;
				inputs[j].style.border = 'none';
				inputs[j].style.background = '#fff';
				inputs[j].style.font = font;
				inputs[j].style.color = '#555';
				inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
				inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 'px';
				inputs[j].style.borderTop = _border_width + 'px solid #555';
				inputs[j].style.borderRight = _border_width + 'px solid #555';
				inputs[j].style.borderBottom = _border_width + 'px solid #555';
				inputs[j].style.borderTopRightRadius = '10px';
				inputs[j].style.borderBottomRightRadius = '10px';
				inputs[j].style.MozBorderRadiusTopright = '10px';
				inputs[j].style.MozBorderRadiusBottomright = '10px';
				inputs[j].style.WebkitBorderTopRightRadius = '10px';
				inputs[j].style.WebkitBorderBottomRightRadius = '10px';
				inputs[j].style.height = inputs[j].previousSibling.offsetHeight - _border_width * 2 + 'px';

				if(inputs[j].getAttribute('class') == 'file_input')
				{
					inputs[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.50) + 'px';
					inputs[j].style.opacity = 0;
				}
				else if(inputs[j].getAttribute('class') == 'contrasena_input')
					inputs[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{

				if(textareas[j].getAttribute('class') != 'hidden_textarea' && textareas[j].getAttribute('className') != 'hidden_textarea')
				{
					textareas[j].style.display = 'block';
					textareas[j].style.position = 'absolute';
					textareas[j].style.padding = 0;
					textareas[j].style.margin = 0;
					textareas[j].style.border = 'none';
					textareas[j].style.background = '#fff';
					textareas[j].style.font = font;
					textareas[j].style.color = '#555';
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';

					if(textareas[j].getAttribute('class') == 'archivo_textarea')
						textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.60) + 'px';
					else//aux
					{
						textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.50) - textareas[j].previousSibling.offsetHeight + 'px';
						textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
						textareas[j].style.left = textareas[j].previousSibling.offsetLeft + 'px';
						textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
						textareas[j].style.zIndex = -1;
					}

				}
				else
					textareas[j].style.visibility = 'hidden';

			}

			//images
			var images = fieldsets[i].getElementsByTagName('img');

			for(var j=0; j<images.length; j++)
			{
				images[j].style.display = 'block';
				images[j].style.position = 'absolute';
				images[j].style.padding = '0';
				images[j].style.margin = 0;
				images[j].style.border = 'none';
				images[j].style.background = 'none';
				images[j].style.top = images[j].previousSibling.offsetTop + 'px';
				images[j].style.left = images[j].previousSibling.offsetLeft + images[j].previousSibling.offsetWidth + 'px';
				images[j].style.cursor = 'pointer';

				if(images[j].getAttribute('class') == 'aux_button')
				{
					images[j].src = 'icon.php?subject=_import&height=' + labels[0].offsetHeight;
					images[j].style.zIndex = -1;
				}

			}

		}

	}

}

function fit_servicio(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.95) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.75) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'configuraciones_adicionales_tab' || _divs[i].getAttribute('class') == 'empresa_tab' || _divs[i].getAttribute('class') == 'registro_patronal_tab' || _divs[i].getAttribute('class') == 'trabajadores_tab' || _divs[i].getAttribute('class') == 'servicios_adicionales_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + 5 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.30);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'antiguedad_prestaciones_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'antiguedad_imss_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'porcentaje_de_honorarios_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_inicio_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'porcentaje_de_comision_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'vacaciones_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'prima_vacacional_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'prima_de_antiguedad_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'aguinaldo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cuotas_imss_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == '_5_infonavit_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'estado_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'periodicidad_de_la_nomina_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'limite_del_prestamo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'prestamo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dias_de_aguinaldo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'base_de_prestaciones_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cobrar_iva_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'alimentacion_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cobrar_impuesto_sobre_nomina_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'habitacion_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'quince_anos_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'honorarios_pendientes_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'incluir_contribuciones_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dcipn_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'credenciales_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight + 'px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
					textareas[j].style.visibility = 'hidden';

				if(textareas[j].getAttribute('class') == 'antiguedad_prestaciones_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.13) + 'px';
				else if(textareas[j].getAttribute('class') == 'antiguedad_imss_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.13) + 'px';
				else if(textareas[j].getAttribute('class') == 'porcentaje_de_honorarios_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_inicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'porcentaje_de_comision_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'vacaciones_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'prima_vacacional_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'prima_de_antiguedad_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'aguinaldo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'cuotas_imss_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == '_5_infonavit_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'estado_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'periodicidad_de_la_nomina_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.16) + 'px';
				else if(textareas[j].getAttribute('class') == 'limite_del_prestamo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'prestamo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';
				else if(textareas[j].getAttribute('class') == 'dias_de_aguinaldo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';
				else if(textareas[j].getAttribute('class') == 'base_de_prestaciones_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'antiguedad_prestaciones_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.13) + 'px';
				else if(selects[j].getAttribute('class') == 'antiguedad_imss_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.13) + 'px';
				else if(selects[j].getAttribute('class') == 'vacaciones_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == 'prima_vacacional_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == 'prima_de_antiguedad_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == 'aguinaldo_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == 'cuotas_imss_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == '_5_infonavit_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == 'estado_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == 'periodicidad_de_la_nomina_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(selects[j].getAttribute('class') == 'base_de_prestaciones_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

			//checkboxes
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)

				if(inputs[j].getAttribute('type') == 'checkbox')
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = 0;
					inputs[j].style.margin = 0;
					inputs[j].style.border = 'none';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 2 + 'px';
					inputs[j].style.borderRadius = '10px';
					inputs[j].style.MozBorderRadius = '10px';
					inputs[j].style.WebkitBorderRadius = '10px';
				}

			//images
			var images = fieldsets[i].getElementsByTagName('img');

			for(var j=0; j<images.length; j++)
			{
				images[j].style.display = 'block';
				images[j].style.position = 'absolute';
				images[j].style.padding = '0';
				images[j].style.margin = 0;
				images[j].style.border = 'none';
				images[j].style.background = 'none';
				images[j].style.top = images[j].previousSibling.offsetTop + 'px';
				images[j].style.left = images[j].previousSibling.offsetLeft + images[j].previousSibling.offsetWidth + 'px';
				images[j].style.cursor = 'pointer';

				if(images[j].getAttribute('class') == 'credenciales_button')
					images[j].src = 'icon.php?subject=credenciales&height=' + images[j].previousSibling.offsetHeight;

			}

		}
		else if(fieldsets[i].getAttribute('class') == 'Configuraciones_adicionales_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.44);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.86);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'dcipla_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dppla_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dgapla_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dpn_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'dgan_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'cgaa_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'ivash_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//checkboxes
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)

				if(inputs[j].getAttribute('type') == 'checkbox')
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = 0;
					inputs[j].style.margin = 0;
					inputs[j].style.border = 'none';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 2 + 'px';
					inputs[j].style.borderRadius = '10px';
					inputs[j].style.MozBorderRadius = '10px';
					inputs[j].style.WebkitBorderRadius = '10px';
				}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_servicio_adicional(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.95) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' ||_divs[i].getAttribute('class') == 'servicios_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.borderTopRightRadius = '10px';
				_divs[i].style.borderTopLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusTopright = '10px';
				_divs[i].style.MozBorderRadiusTopleft = '10px';
				_divs[i].style.WebkitBorderTopRightRadius = '10px';
				_divs[i].style.WebkitBorderTopLeftRadius = '10px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
				_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			}
			else
			{
				_divs[i].style.borderBottomRightRadius = '10px';
				_divs[i].style.borderBottomLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusBottomright = '10px';
				_divs[i].style.MozBorderRadiusBottomleft = '10px';
				_divs[i].style.WebkitBorderBottomRightRadius = '10px';
				_divs[i].style.WebkitBorderBottomLeftRadius = '10px';
				_divs[i].style.top = _form.offsetTop + _form.offsetHeight + 'px';
			}

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.22);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'monto_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'condicion_label')
				{
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderTopRightRadius = '10px';
					labels[j].style.borderBottomLeftRadius = '0px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusTopright = '10px';
					labels[j].style.MozBorderRadiusBottomleft = '0px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderTopRightRadius = '10px';
					labels[j].style.WebkitBorderBottomLeftRadius = '0px';
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = '5px';
					labels[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.45) + 'px';
					labels[j].style.textAlign = 'center';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') == 'condicion_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + textareas[j].previousSibling.offsetHeight + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + 'px';
					textareas[j].style.borderLeft = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderBottomLeftRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusBottomleft = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderBottomLeftRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = vd * 5 + 'px';
				}
				else if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.40) + 'px';
				else if(textareas[j].getAttribute('class') == 'monto_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'condicion_textarea')
					textareas[j].style.width = textareas[j].previousSibling.offsetWidth - _border_width * 2 + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_seguro_por_danos_a_la_vivienda(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.95) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.40) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.12);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.70);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'ano_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'valor_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'valor_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				if(textareas[j].getAttribute('class') == 'ano_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.12) + 'px';

			}

		}

	}

}

function fit_servicio_empresa(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
	var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'empresa_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'empresa_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.35);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.50);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'fecha_de_asignacion_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'fecha_de_asignacion_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_servicio_registro_patronal(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
	var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'registro_patronal_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'registro_patronal_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.35);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.50);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'fecha_de_asignacion_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'fecha_de_asignacion_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_servicio_trabajador(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.25);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.70);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'fecha_de_ingreso_servicio_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_ingreso_cliente_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'credencial_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'fecha_de_ingreso_servicio_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.13) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_ingreso_cliente_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.13) + 'px';

			}

			//images
			var images = fieldsets[i].getElementsByTagName('img');

			for(var j=0; j<images.length; j++)
			{
				images[j].style.display = 'block';
				images[j].style.position = 'absolute';
				images[j].style.padding = '0';
				images[j].style.margin = 0;
				images[j].style.border = 'none';
				images[j].style.background = 'none';
				images[j].style.top = images[j].previousSibling.offsetTop + 'px';
				images[j].style.left = images[j].previousSibling.offsetLeft + images[j].previousSibling.offsetWidth + 'px';
				images[j].style.cursor = 'pointer';

				if(images[j].getAttribute('class') == 'credencial_button')
					images[j].src = 'icon.php?subject=credenciales&height=' + images[j].previousSibling.offsetHeight;

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_sign(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs and submit button
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.left = _form.offsetLeft  + _offset + 'px';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
		}

		var inputs = _form.getElementsByTagName('input');

		for(var i=0; i<inputs.length; i++)

			if(inputs[i].getAttribute('class') == 'submit_button')
			{
				inputs[i].style.display = 'block';
				inputs[i].style.position = 'absolute';
				inputs[i].style.padding = '4px';
				inputs[i].style.margin = 0;
				inputs[i].style.border = _border_width + 'px solid #555';
				inputs[i].style.background = '#fff';
				inputs[i].style.borderRadius = '10px';
				inputs[i].style.MozBorderRadius = '10px';
				inputs[i].style.WebkitBorderRadius = '10px';
				inputs[i].style.color = '#555';
				inputs[i].style.top = _form.parentNode.offsetHeight - _form.offsetTop - inputs[i].offsetHeight - _offset * 4 + 'px';
				inputs[i].style.font = title_font;
				inputs[i].style.zIndex = 1;
				inputs[i].style.cursor = 'pointer';
				inputs[i].style.left = _form.parentNode.offsetWidth - _form.offsetLeft - inputs[i].offsetWidth - _offset * 5 + 'px';
			}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var hld = parseInt(fieldsets[i].offsetWidth * 0.30);//horizontal left directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'archivo_label')
				{
					labels[j].style.top = parseInt((fieldsets[i].offsetHeight - labels[j].offsetHeight) / 2) + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{

				if(textareas[j].getAttribute('class') != 'hidden_textarea' && textareas[j].getAttribute('className') != 'hidden_textarea')
				{
					textareas[j].style.display = 'block';
					textareas[j].style.position = 'absolute';
					textareas[j].style.padding = 0;
					textareas[j].style.margin = 0;
					textareas[j].style.border = 'none';
					textareas[j].style.background = '#fff';
					textareas[j].style.font = font;
					textareas[j].style.color = '#555';
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';

					if(textareas[j].getAttribute('class') == 'archivo_textarea')
						textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.60) + 'px';

				}
				else
					textareas[j].style.visibility = 'hidden';

			}

			//file input
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)
			{
				inputs[j].style.display = 'block';
				inputs[j].style.position = 'absolute';
				inputs[j].style.padding = 0;
				inputs[j].style.margin = 0;
				inputs[j].style.border = 'none';
				inputs[j].style.background = '#fff';
				inputs[j].style.font = font;
				inputs[j].style.color = '#555';
				inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
				inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 'px';
				inputs[j].style.borderTop = _border_width + 'px solid #555';
				inputs[j].style.borderRight = _border_width + 'px solid #555';
				inputs[j].style.borderBottom = _border_width + 'px solid #555';
				inputs[j].style.borderTopRightRadius = '10px';
				inputs[j].style.borderBottomRightRadius = '10px';
				inputs[j].style.MozBorderRadiusTopright = '10px';
				inputs[j].style.MozBorderRadiusBottomright = '10px';
				inputs[j].style.WebkitBorderTopRightRadius = '10px';
				inputs[j].style.WebkitBorderBottomRightRadius = '10px';
				inputs[j].style.height = inputs[j].previousSibling.offsetHeight + 'px';

				if(inputs[j].getAttribute('class') == 'file_input')
					inputs[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.60) + 'px';

			}

		}

	}

}

function fit_socio(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.90) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.50) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var hld = parseInt(fieldsets[i].offsetWidth * 0.30);

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = parseInt((fieldsets[i].offsetHeight - labels[j].offsetHeight) / 2) + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.50) + 'px';

			}

		}

	}

}

function fit_sucursal(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.95) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.63) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs and submit button
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'registro_patronal_tab' || _divs[i].getAttribute('class') == 'logo_tab' || _divs[i].getAttribute('class') == 'sello_digital_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.background = '#fff';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
				_divs[i].style.borderTopRightRadius = '10px';
				_divs[i].style.borderTopLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusTopright = '10px';
				_divs[i].style.MozBorderRadiusTopleft = '10px';
				_divs[i].style.WebkitBorderTopRightRadius = '10px';
				_divs[i].style.WebkitBorderTopLeftRadius = '10px';
				_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			}
			else if(_divs[i].getAttribute('class') == 'logo_tab' || _divs[i].getAttribute('class') == 'sello_digital_tab')
			{
				_divs[i].style.borderBottomRightRadius = '10px';
				_divs[i].style.borderBottomLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusBottomright = '10px';
				_divs[i].style.MozBorderRadiusBottomleft = '10px';
				_divs[i].style.WebkitBorderBottomRightRadius = '10px';
				_divs[i].style.WebkitBorderBottomLeftRadius = '10px';
				_divs[i].style.top = _form.offsetTop + _form.offsetHeight + 'px';

				if(_divs[i].getAttribute('class') == 'logo_tab')
					_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				else
					_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + _offset + 1 + 'px';


			}
			else
			{
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + _offset + 1 + 'px';
				_divs[i].style.borderTopRightRadius = '10px';
				_divs[i].style.borderTopLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusTopright = '10px';
				_divs[i].style.MozBorderRadiusTopleft = '10px';
				_divs[i].style.WebkitBorderTopRightRadius = '10px';
				_divs[i].style.WebkitBorderTopLeftRadius = '10px';
				_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			}

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.13);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.60);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';//'2px solid #fff';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'zona_geografica_label')
				{
					labels[j].style.top = labels[j].previousSibling.offsetTop + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'telefono_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.25) + 'px';
				else if(textareas[j].getAttribute('class') == 'zona_geografica_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';
				else if(textareas[j].getAttribute('class') == 'telefono_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'zona_geografica_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

			var divs = fieldsets[i].getElementsByTagName('div');

			if(divs[0].getAttribute('class') == 'domicilio_fiscal_div')
			{
				divs[0].style.display = 'block';
				divs[0].style.position = 'absolute';
				divs[0].style.padding = '0';
				divs[0].style.margin = '0';
				divs[0].style.border = _border_width + 'px solid #555';
				divs[0].style.background = '#eee';
				divs[0].style.top = vd + 'px';
				divs[0].style.left = divs[0].previousSibling.offsetLeft + divs[0].previousSibling.offsetWidth + 5 + 'px';
				divs[0].style.width = parseInt(fieldsets[i].offsetWidth * 0.59) + 'px';
				divs[0].style.height = parseInt(fieldsets[i].offsetWidth * 0.31) + 'px';
				divs[0].style.borderRadius = '10px';
				var spans = divs[0].getElementsByTagName('span');
				spans[0].style.display = 'block';
				spans[0].style.position = 'absolute';
				spans[0].style.padding = '4px';
				spans[0].style.margin = '0';
				spans[0].style.border = 'none';
				spans[0].style.background = '#555';
				spans[0].style.color = '#fff';
				spans[0].style.textAlign = 'center';
				spans[0].style.font = title_font;
				spans[0].style.top = '0px';
				spans[0].style.left = ((spans[0].parentNode.offsetWidth - spans[0].offsetWidth) / 2) + 'px';
				spans[0].style.borderBottomLeftRadius = '10px';
				spans[0].style.borderBottomRightRadius = '10px';
				spans[0].style.MozBorderRadiusBottomleft = '10px';
				spans[0].style.MozBorderRadiusBottomRigth = '10px';
				spans[0].style.WebkitBorderBottomLeftRadius = '10px';
				spans[0].style.WebkitBorderBottomRigthRadius = '10px';
				var _hld = parseInt(divs[0].parentNode.offsetWidth * 0.10);
				var _hrd = parseInt(divs[0].parentNode.offsetWidth * 0.40);
				//labels
				var _labels = divs[0].getElementsByTagName('label');

				for(var k=0; k<_labels.length; k++)
				{
					_labels[k].style.display = 'block';
					_labels[k].style.position = 'absolute';
					_labels[k].style.padding = '4px';
					_labels[k].style.margin = 0;
					_labels[k].style.border = 'none';
					_labels[k].style.background = '#555';
					_labels[k].style.font = font;
					_labels[k].style.color = '#fff';
					_labels[k].style.borderTopLeftRadius = '10px';
					_labels[k].style.borderBottomLeftRadius = '10px';
					_labels[k].style.MozBorderRadiusTopleft = '10px';
					_labels[k].style.MozBorderRadiusBottomleft = '10px';
					_labels[k].style.WebkitBorderTopLeftRadius = '10px';
					_labels[k].style.WebkitBorderBottomLeftRadius = '10px';

					if(_labels[k].getAttribute('class') == 'calle_label')
					{
						_labels[k].style.top = _labels[k].previousSibling.offsetHeight + vd / 2 + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'numero_ext_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + _labels[k-1].offsetHeight + vd + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'numero_int_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + 'px';
						_labels[k].style.left = _hrd - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'colonia_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + _labels[k-1].offsetHeight + vd + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'localidad_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + _labels[k-1].offsetHeight + vd + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'referencia_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + _labels[k-1].offsetHeight + vd + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'municipio_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + _labels[k-1].offsetHeight + vd + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'estado_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + 'px';
						_labels[k].style.left = _hrd - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'pais_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + _labels[k-1].offsetHeight + vd + 'px';
						_labels[k].style.left = _hld - _labels[k].offsetWidth + 'px';
					}
					else if(_labels[k].getAttribute('class') == 'cp_label')
					{
						_labels[k].style.top = _labels[k-1].offsetTop + 'px';
						_labels[k].style.left = _hrd - _labels[k].offsetWidth + 'px';
					}

				}

				//textareas
				var textareas = divs[0].getElementsByTagName('textarea');

				for(var k=0; k<textareas.length; k++)
				{
					textareas[k].style.display = 'block';
					textareas[k].style.position = 'absolute';
					textareas[k].style.padding = 0;
					textareas[k].style.margin = 0;
					textareas[k].style.border = 'none';
					textareas[k].style.background = '#fff';
					textareas[k].style.font = font;
					textareas[k].style.color = '#555';
					textareas[k].style.top = textareas[k].previousSibling.offsetTop + 'px';
					textareas[k].style.left = textareas[k].previousSibling.offsetLeft + textareas[k].previousSibling.offsetWidth + 'px';
					textareas[k].style.borderTop = _border_width + 'px solid #555';
					textareas[k].style.borderRight = _border_width + 'px solid #555';
					textareas[k].style.borderBottom = _border_width + 'px solid #555';
					textareas[k].style.borderTopRightRadius = '10px';
					textareas[k].style.borderBottomRightRadius = '10px';
					textareas[k].style.MozBorderRadiusTopright = '10px';
					textareas[k].style.MozBorderRadiusBottomright = '10px';
					textareas[k].style.WebkitBorderTopRightRadius = '10px';
					textareas[k].style.WebkitBorderBottomRightRadius = '10px';
					textareas[k].style.height = textareas[k].previousSibling.offsetHeight - _border_width * 2 + 'px';

					if(textareas[k].getAttribute('class') == 'calle_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.80) + 'px';
					else if(textareas[k].getAttribute('class') == 'numero_ext_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.12) + 'px';
					else if(textareas[k].getAttribute('class') == 'numero_int_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.12) + 'px';
					else if(textareas[k].getAttribute('class') == 'colonia_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.80) + 'px';
					else if(textareas[k].getAttribute('class') == 'localidad_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.80) + 'px';
					else if(textareas[k].getAttribute('class') == 'referencia_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.80) + 'px';
					else if(textareas[k].getAttribute('class') == 'municipio_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.35) + 'px';
					else if(textareas[k].getAttribute('class') == 'estado_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.30) + 'px';
					else if(textareas[k].getAttribute('class') == 'pais_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.30) + 'px';
					else if(textareas[k].getAttribute('class') == 'cp_textarea')
						textareas[k].style.width = parseInt(textareas[k].parentNode.offsetWidth * 0.12) + 'px';

				}

			}

		}
		else if(fieldsets[i].getAttribute('class') == 'Logo_fieldset')
			show_logo(fieldsets[i]);//at menu.js
		else
			fit_content(fieldsets[i]);

	}

}

function fit_tabla(fieldset)//ISR and Credito al salario
{
	var tables = fieldset.getElementsByTagName('table');
	var titles_table = tables[0];
	var data_table = tables[1];
	var divs = fieldset.getElementsByTagName('div');
	var _div = divs[0];
	data_table.style.display = 'block';
	data_table.style.position = 'absolute';
	data_table.style.font = font;
	data_table.style.top = '0px';
	data_table.style.left = '0px';
	titles_table.style.display = 'block';
	titles_table.style.position = 'absolute';
	titles_table.style.font = font;

	for(var i=0; i<titles_table.rows[1].cells.length; i++)
		titles_table.rows[1].cells[i].style.background = color_opaque;

	_div.style.display = 'block';
	_div.style.position = 'absolute';
	_div.style.padding = 0;
	_div.style.margin = 0;
	_div.style.border = 'none';
	_div.style.background = 'none';
	_div.style.width = data_table.rows.length > 0 ? (data_table.offsetWidth + 20 + 'px') : (titles_table.offsetWidth + 'px');
	_div.style.height = data_table.offsetHeight > parseInt(fieldset.offsetHeight * 0.70) ? (parseInt(fieldset.offsetHeight * 0.70) + 'px') : (data_table.offsetHeight + 'px');
	_div.style.top = parseInt((fieldset.offsetHeight - _div.offsetHeight) / 2) + 'px';
	_div.style.left = parseInt((fieldset.offsetWidth - _div.offsetWidth) / 2) + 'px';
	_div.style.overflowY = 'auto';
	_div.style.overflowX = 'hidden';
	titles_table.style.top = _div.offsetTop - titles_table.offsetHeight + 'px';
	titles_table.style.left = _div.offsetLeft + 'px';

	if(data_table.rows.length > 0)

		for(var i=0; i<data_table.rows[0].cells.length; i++)
			titles_table.rows[1].cells[i].style.width = data_table.rows[0].cells[i].firstChild.offsetWidth + 'px';

}

function fit_tabla_de_prestamo(fieldset)
{
	var tables = fieldset.getElementsByTagName('table');
	var titles_table = tables[0];
	var data_table = tables[1];
	var divs = fieldset.getElementsByTagName('div');
	var _div = divs[0];
	data_table.style.display = 'block';
	data_table.style.position = 'absolute';
	data_table.style.font = font;
	data_table.style.top = '0px';
	data_table.style.left = '0px';
	titles_table.style.display = 'block';
	titles_table.style.position = 'absolute';
	titles_table.style.font = font;

	for(var i=0; i<titles_table.rows[1].cells.length; i++)
		titles_table.rows[1].cells[i].style.background = color_opaque;

	var textareas = titles_table.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)
		textareas[i].style.width = _height * 3 + 'px';

	_div.style.display = 'block';
	_div.style.position = 'absolute';
	_div.style.padding = 0;
	_div.style.margin = 0;
	_div.style.border = 'none';
	_div.style.background = 'none';
	_div.style.width = data_table.rows.length > 0 ? (data_table.offsetWidth + 20 + 'px') : (titles_table.offsetWidth + 'px');
	_div.style.height = data_table.offsetHeight > parseInt(fieldset.offsetHeight * 0.70) ? (parseInt(fieldset.offsetHeight * 0.70) + 'px') : (data_table.offsetHeight + 'px');
	_div.style.top = parseInt((fieldset.offsetHeight - _div.offsetHeight) / 2) + 'px';
	_div.style.left = parseInt((fieldset.offsetWidth - _div.offsetWidth) / 2) + 'px';
	_div.style.overflowY = 'auto';
	_div.style.overflowX = 'hidden';
	titles_table.style.top = _div.offsetTop - titles_table.offsetHeight + 'px';
	titles_table.style.left = _div.offsetLeft + 'px';

	if(data_table.rows.length > 0)

		for(var i=0; i<data_table.rows[0].cells.length; i++)
			titles_table.rows[1].cells[i].style.width = data_table.rows[0].cells[i].firstChild.offsetWidth + 'px';

	var images = fieldset.getElementsByTagName('IMG');

	for(var i=0; i<images.length; i++)

		if(images[i].getAttribute('class') == 'request_button' || 'status_button')
		{
			images[i].style.display = 'block';
			images[i].style.position = 'absolute';
			images[i].style.padding = '0';
			images[i].style.margin = 0;
			images[i].style.border = 'none';
			images[i].style.background = 'none';
			images[i].style.top = images[i].previousSibling.offsetTop + 'px';
			images[i].style.left = images[i].previousSibling.offsetLeft - _height + 'px';
			images[i].style.cursor = 'pointer';
			images[i].src = 'icon.php?subject=_view&height=' + _height;
		}

}

function fit_tipo(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
	var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.20);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.60);//horizontal left directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';//'2px solid #fff';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'tipo_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'tipo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_ingreso_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.16) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'tipo_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_trabajador(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.95) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.72) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
	var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'tipo_tab' || _divs[i].getAttribute('class') == 'base_tab' || _divs[i].getAttribute('class') == 'contrato_tab' || _divs[i].getAttribute('class') == 'banco_tab' || _divs[i].getAttribute('class') == 'umf_tab' || _divs[i].getAttribute('class') == 'descuentos_pendientes_tab' || _divs[i].getAttribute('class') == 'aportacion_del_trabajador_al_fondo_de_ahorro_tab' || _divs[i].getAttribute('class') == 'incapacidad_tab' || _divs[i].getAttribute('class') == 'vacaciones_tab' || _divs[i].getAttribute('class') == 'pension_alimenticia_tab' || _divs[i].getAttribute('class') == 'prestamo_de_administradora_tab' || _divs[i].getAttribute('class') == 'prestamo_de_caja_tab' || _divs[i].getAttribute('class') == 'prestamo_de_cliente_tab' || _divs[i].getAttribute('class') == 'prestamo_del_fondo_de_ahorro_tab' || _divs[i].getAttribute('class') == 'fondo_de_garantia_tab' || _divs[i].getAttribute('class') == 'fonacot_tab' || _divs[i].getAttribute('class') == 'infonavit_tab' || _divs[i].getAttribute('class') == 'pago_por_seguro_de_vida_tab' || _divs[i].getAttribute('class') == 'salario_diario_tab' || _divs[i].getAttribute('class') == 'salario_minimo_tab' || _divs[i].getAttribute('class') == 'archivo_digital_tab' || _divs[i].getAttribute('class') == 'servicios_tab' || _divs[i].getAttribute('class') == 'sucursales_tab'  || _divs[i].getAttribute('class') == 'bajas_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
				_divs[i].style.borderTopRightRadius = '10px';
				_divs[i].style.borderTopLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusTopright = '10px';
				_divs[i].style.MozBorderRadiusTopleft = '10px';
				_divs[i].style.WebkitBorderTopRightRadius = '10px';
				_divs[i].style.WebkitBorderTopLeftRadius = '10px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'prestamo_del_fondo_de_ahorro_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.background = 'none';
				_divs[i].style.top = _form.offsetTop + _form.offsetHeight + 'px';
				_divs[i].style.borderBottomRightRadius = '10px';
				_divs[i].style.borderBottomLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusBottomright = '10px';
				_divs[i].style.MozBorderRadiusBottomleft = '10px';
				_divs[i].style.WebkitBorderBottomRightRadius = '10px';
				_divs[i].style.WebkitBorderBottomLeftRadius = '10px';
			}
			else if(_divs[i].getAttribute('class') == 'fondo_de_garantia_tab' || _divs[i].getAttribute('class') == 'fonacot_tab' || _divs[i].getAttribute('class') == 'infonavit_tab' || _divs[i].getAttribute('class') == 'pago_por_seguro_de_vida_tab' || _divs[i].getAttribute('class') == 'salario_diario_tab' || _divs[i].getAttribute('class') == 'salario_minimo_tab' || _divs[i].getAttribute('class') == 'archivo_digital_tab' || _divs[i].getAttribute('class') == 'servicios_tab'  || _divs[i].getAttribute('class') == 'sucursales_tab' || _divs[i].getAttribute('class') == 'bajas_tab' || _divs[i].getAttribute('class') == 'descuentos_pendientes_tab')
			{
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + _offset + 1 + 'px';
				_divs[i].style.top = _form.offsetTop + _form.offsetHeight + 'px';
				_divs[i].style.borderBottomRightRadius = '10px';
				_divs[i].style.borderBottomLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusBottomright = '10px';
				_divs[i].style.MozBorderRadiusBottomleft = '10px';
				_divs[i].style.WebkitBorderBottomRightRadius = '10px';
				_divs[i].style.WebkitBorderBottomLeftRadius = '10px';
			}
			else
			{
				_divs[i].style.left = _divs[i].previousSibling.offsetLeft + _divs[i].previousSibling.offsetWidth + _offset + 1 + 'px';
				_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
				_divs[i].style.borderTopRightRadius = '10px';
				_divs[i].style.borderTopLeftRadius = '10px';
				_divs[i].style.MozBorderRadiusTopright = '10px';
				_divs[i].style.MozBorderRadiusTopleft = '10px';
				_divs[i].style.WebkitBorderTopRightRadius = '10px';
				_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			}

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.25);//horizontal left directriz
			var hmd = parseInt(fieldsets[i].offsetWidth * 0.15);//horizontal middle directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.73);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';//'2px solid #fff';
				labels[j].style.font = font;

				if(labels[j].getAttribute('class') != 'firma_label')
				{
					labels[j].style.background = '#555';
					labels[j].style.color = '#fff';
				}

				if(labels[j].getAttribute('class') == 'domicilio_label')
				{
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderTopRightRadius = '10px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusTopright = '10px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderTopRightRadius = '10px';
				}
				else if(labels[j].getAttribute('class') != 'firma_label')
				{
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderBottomLeftRadius = '10px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusBottomleft = '10px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderBottomLeftRadius = '10px';
				}

				if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'telefono_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'domicilio_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hmd + 'px';
					labels[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.46) + 'px';
					labels[j].style.textAlign = 'center';
				}
				else if(labels[j].getAttribute('class') == 'celular_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'correo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'nacionalidad_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd * 3 + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'lugar_de_nacimiento_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_nacimiento_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'estado_civil_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'firma_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = '20px';
					labels[j].style.color = '#555';
					labels[j].style.borderTop = _border_width + 'px solid #555';
					labels[j].style.width = '25mm';
					labels[j].style.textAlign = 'center';
				}
				else if(labels[j].getAttribute('class') == 'sexo_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'curp_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'numero_ife_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'rfc_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'numero_imss_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'jornada_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'tipo_de_sangre_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'horario_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'avisar_a_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}
			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') == 'domicilio_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + textareas[j].previousSibling.offsetHeight + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + 'px';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderLeft = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottomLeftRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusBottomleft = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderBottomLeftRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = vd * 5 + 'px';
				}
				else if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.40) + 'px';
				else if(textareas[j].getAttribute('class') == 'telefono_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'domicilio_textarea')
					textareas[j].style.width = textareas[j].previousSibling.offsetWidth - _border_width * 2 + 'px';
				else if(textareas[j].getAttribute('class') == 'celular_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';
				else if(textareas[j].getAttribute('class') == 'correo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'nacionalidad_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';
				else if(textareas[j].getAttribute('class') == 'lugar_de_nacimiento_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_nacimiento_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';
				else if(textareas[j].getAttribute('class') == 'estado_civil_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'sexo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.06) + 'px';
				else if(textareas[j].getAttribute('class') == 'curp_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'numero_ife_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'rfc_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'numero_imss_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';
				else if(textareas[j].getAttribute('class') == 'jornada_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';
				else if(textareas[j].getAttribute('class') == 'tipo_de_sangre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.08) + 'px';
				else if(textareas[j].getAttribute('class') == 'horario_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.23) + 'px';
				else if(textareas[j].getAttribute('class') == 'avisar_a_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.35) + 'px';

			}

			//checkboxes
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)

				if(inputs[j].getAttribute('type') == 'checkbox')
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = 0;
					inputs[j].style.margin = 0;
					inputs[j].style.border = 'none';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 2 + 'px';
					inputs[j].style.borderRadius = '10px';
					inputs[j].style.MozBorderRadius = '10px';
					inputs[j].style.WebkitBorderRadius = '10px';
				}

			//import file button
			var images = fieldsets[i].getElementsByTagName('IMG');

			for(var j=0; j<images.length; j++)

				if(images[j].getAttribute('class') == 'import_button')
				{
					images[j].style.display = 'block';
					images[j].style.position = 'absolute';
					images[j].style.padding = 0;
					images[j].style.margin = 0;
					images[j].style.border = 'none';
					images[j].style.background = 'none';
					images[j].style.zIndex = 1;
					images[j].style.top = images[j].previousSibling.offsetTop - vd / 2 + 'px';
					images[j].style.left = '10px';
					images[j].style.cursor = 'pointer';
					images[j].src = 'icon.php?subject=_import&height=' + _height;
				}

			//photo and sign images
			var images = fieldsets[i].getElementsByTagName('IMG');

			for(var j=0; j<images.length; j++)

				if(images[j].getAttribute('class') == 'photo_image')
				{
					images[j].style.display = 'block';
					images[j].style.position = 'absolute';
					images[j].style.padding = 0;
					images[j].style.margin = 0;
					images[j].style.border = _border_width + 'px dotted #555';
					images[j].style.background = 'none';
					images[j].style.zIndex = 1;
					images[j].style.top = '20px';
					images[j].style.left = '20px';
					images[j].style.cursor = 'pointer';
					images[j].style.width = '25mm';
					images[j].style.height = '30mm';
				}
				else if(images[j].getAttribute('class') == 'sign_image')
					show_sign(images[j]);//at menu.js

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_trabajador_salario_minimo(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.15);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.50);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'codigo_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'codigo_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.12) + 'px';
				else if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.40) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_trabajador_sucursal(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.15);//horizontal left directriz
			var hrd = parseInt(fieldsets[i].offsetWidth * 0.77);//horizontal right directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = _border_width + 'px solid #555';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'empresa_nombre_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'empresa_rfc_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_de_ingreso_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + 'px';
					labels[j].style.left = hrd - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
				{
					textareas[j].style.visibility = 'hidden';
				}

				if(textareas[j].getAttribute('class') == 'empresa_nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.47) + 'px';
				else if(textareas[j].getAttribute('class') == 'empresa_rfc_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';
				else if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_ingreso_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.15) + 'px';

			}

			//selects
			var selects = fieldsets[i].getElementsByTagName('select');

			for(var j=0; j<selects.length; j++)
			{
				selects[j].style.display = 'block';
				selects[j].style.position = 'absolute';
				selects[j].style.padding = 0;
				selects[j].style.margin = 0;
				selects[j].style.border = 'none';
				selects[j].style.background = '#fff';
				selects[j].style.font = font;
				selects[j].style.color = '#555';
				selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
				selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
				selects[j].style.borderTop = _border_width + 'px solid #555';
				selects[j].style.borderRight = _border_width + 'px solid #555';
				selects[j].style.borderBottom = _border_width + 'px solid #555';
				selects[j].style.borderTopRightRadius = '10px';
				selects[j].style.borderBottomRightRadius = '10px';
				selects[j].style.MozBorderRadiusTopright = '10px';
				selects[j].style.MozBorderRadiusBottomright = '10px';
				selects[j].style.WebkitBorderTopRightRadius = '10px';
				selects[j].style.WebkitBorderBottomRightRadius = '10px';
				selects[j].style.height = selects[j].previousSibling.offsetHeight + 'px';

				if(selects[j].getAttribute('class') == 'empresa_rfc_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';
				else if(selects[j].getAttribute('class') == 'nombre_select')
					selects[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.20) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_umf(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs and submit button
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab' || _divs[i].getAttribute('class') == 'servicio_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';

			if(_divs[i].getAttribute('class') == 'datos_tab')
			{
				_divs[i].style.left = _form.offsetLeft + _offset + 'px';
				_divs[i].style.borderBottom = _border_width + 'px solid #fff';
			}
			else if(_divs[i].getAttribute('class') == 'servicio_tab')
				_divs[i].style.left = _divs[i-1].offsetLeft + _divs[i-1].offsetWidth + _offset + 1 + 'px';

		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.40);//horizontal left directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';//'2px solid #fff';
				labels[j].style.background = '#555';
				labels[j].style.font = font;
				labels[j].style.color = '#fff';
				labels[j].style.borderTopLeftRadius = '10px';
				labels[j].style.borderBottomLeftRadius = '10px';
				labels[j].style.MozBorderRadiusTopleft = '10px';
				labels[j].style.MozBorderRadiusBottomleft = '10px';
				labels[j].style.WebkitBorderTopLeftRadius = '10px';
				labels[j].style.WebkitBorderBottomLeftRadius = '10px';

				if(labels[j].getAttribute('class') == 'numero_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'fecha_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
					textareas[j].style.visibility = 'hidden';

				if(textareas[j].getAttribute('class') == 'numero_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.10) + 'px';
				else if(textareas[j].getAttribute('class') == 'fecha_de_ingreso_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.18) + 'px';

			}

		}
		else
			fit_content(fieldsets[i]);

	}

}

function fit_usuario(_form)
{
	_form.style.display = 'block';
	_form.style.position = 'absolute';
	_form.style.padding = 0;
	_form.style.margin = 0;
	_form.style.border = _border_width + 'px solid #555';
	_form.style.background = '#fff';
	_form.style.width = parseInt(_form.parentNode.offsetWidth * 0.85) + 'px';
	_form.style.height = parseInt(_form.parentNode.offsetHeight * 0.60) + 'px';
	_form.style.top = parseInt((_form.parentNode.offsetHeight - _form.offsetHeight) / 2) + 'px';
	_form.style.left = parseInt((_form.parentNode.offsetWidth - _form.offsetWidth) / 2) + 'px';
	_form.style.zIndex = 0;
		var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? _border_width : (navigator.userAgent.toLowerCase().match(/chrome/g) ? (_border_width/2) : 0);
	//tabs and submit button
	var _divs = _form.parentNode.getElementsByTagName('div');

	for(var i=0; i<_divs.length; i++)

		if(_divs[i].getAttribute('class') == 'datos_tab')
		{
			_divs[i].style.display = 'block';
			_divs[i].style.position = 'absolute';
			_divs[i].style.padding = '3px';
			_divs[i].style.margin = 0;
			_divs[i].style.font = font;
			_divs[i].style.zIndex = 1;
			_divs[i].style.cursor = 'pointer';
			_divs[i].style.border = _border_width + 'px solid #555';
			_divs[i].style.top = _form.offsetTop - _divs[i].offsetHeight + _offset * 2 + 'px';
			_divs[i].style.borderTopRightRadius = '10px';
			_divs[i].style.borderTopLeftRadius = '10px';
			_divs[i].style.MozBorderRadiusTopright = '10px';
			_divs[i].style.MozBorderRadiusTopleft = '10px';
			_divs[i].style.WebkitBorderTopRightRadius = '10px';
			_divs[i].style.WebkitBorderTopLeftRadius = '10px';
			_divs[i].style.background = '#fff';
			_divs[i].style.color = '#555';
			_divs[i].style.left = _form.offsetLeft + _offset + 'px';
			_divs[i].style.borderBottom = _border_width + 'px solid #fff';
		}

	//fieldsets
	var fieldsets = _form.getElementsByTagName('fieldset');

	for(var i=0; i<fieldsets.length; i++)
	{
		fieldsets[i].style.display = 'block';
		fieldsets[i].style.position = 'absolute';
		fieldsets[i].style.border = 'none';
		fieldsets[i].style.background = 'none';
		fieldsets[i].style.padding = 0;
		fieldsets[i].style.margin = 0;
		fieldsets[i].style.width = _form.offsetWidth + 'px';
		fieldsets[i].style.height = _form.offsetHeight + 'px';
		fieldsets[i].style.top = '0px';
		fieldsets[i].style.left = '0px';

		if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
		{
			//labels
			var labels = fieldsets[i].getElementsByTagName('label');
			var vd = 10;//vertical distance between elements
			var hld = parseInt(fieldsets[i].offsetWidth * 0.40);//horizontal left directriz

			for(var j=0; j<labels.length; j++)
			{
				labels[j].style.display = 'block';
				labels[j].style.position = 'absolute';
				labels[j].style.padding = '4px';
				labels[j].style.margin = 0;
				labels[j].style.border = 'none';//'2px solid #fff';
				labels[j].style.font = font;

				if(labels[j].getAttribute('class') != 'firma_label')
				{
					labels[j].style.background = '#555';
					labels[j].style.color = '#fff';
					labels[j].style.borderTopLeftRadius = '10px';
					labels[j].style.borderBottomLeftRadius = '10px';
					labels[j].style.MozBorderRadiusTopleft = '10px';
					labels[j].style.MozBorderRadiusBottomleft = '10px';
					labels[j].style.WebkitBorderTopLeftRadius = '10px';
					labels[j].style.WebkitBorderBottomLeftRadius = '10px';
				}

				if(labels[j].getAttribute('class') == 'nombre_label')
				{
					labels[j].style.top = vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'contrasena_label')
				{
					labels[j].style.top = labels[j-1].offsetTop + labels[j-1].offsetHeight + vd + 'px';
					labels[j].style.left = hld - labels[j].offsetWidth + 'px';
				}
				else if(labels[j].getAttribute('class') == 'firma_label')
				{
					labels[j].style.top = vd * 15 + 'px';
					labels[j].style.width = fieldsets[i].offsetWidth * 0.25 + 'px';
					labels[j].style.left = (fieldsets[i].offsetWidth - labels[j].offsetWidth) / 2 + 'px';
					labels[j].style.color = '#555';
					labels[j].style.borderTop = _border_width + 'px solid #555';
					labels[j].style.textAlign = 'center';
				}

			}

			//textareas
			var textareas = fieldsets[i].getElementsByTagName('textarea');

			for(var j=0; j<textareas.length; j++)
			{
				textareas[j].style.display = 'block';
				textareas[j].style.position = 'absolute';
				textareas[j].style.padding = 0;
				textareas[j].style.margin = 0;
				textareas[j].style.border = 'none';
				textareas[j].style.background = '#fff';
				textareas[j].style.font = font;
				textareas[j].style.color = '#555';

				if(textareas[j].getAttribute('class') != 'hidden_textarea')
				{
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.borderTop = _border_width + 'px solid #555';
					textareas[j].style.borderRight = _border_width + 'px solid #555';
					textareas[j].style.borderBottom = _border_width + 'px solid #555';
					textareas[j].style.borderTopRightRadius = '10px';
					textareas[j].style.borderBottomRightRadius = '10px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _border_width * 2 + 'px';
				}
				else if(textareas[j].getAttribute('class') == 'hidden_textarea')
					textareas[j].style.visibility = 'hidden';

				if(textareas[j].getAttribute('class') == 'nombre_textarea')
					textareas[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.30) + 'px';

			}

			//inputs
			var inputs = fieldsets[i].getElementsByTagName('input');

			for(var j=0; j<inputs.length; j++)
			{
				inputs[j].style.display = 'block';
				inputs[j].style.position = 'absolute';
				inputs[j].style.padding = 0;
				inputs[j].style.margin = 0;
				inputs[j].style.border = 'none';
				inputs[j].style.background = '#fff';
				inputs[j].style.font = font;
				inputs[j].style.color = '#555';
				inputs[j].style.top = inputs[j].previousSibling.offsetTop + 'px';
				inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 'px';
				inputs[j].style.borderTop = _border_width + 'px solid #555';
				inputs[j].style.borderRight = _border_width + 'px solid #555';
				inputs[j].style.borderBottom = _border_width + 'px solid #555';
				inputs[j].style.borderTopRightRadius = '10px';
				inputs[j].style.borderBottomRightRadius = '10px';
				inputs[j].style.MozBorderRadiusTopright = '10px';
				inputs[j].style.MozBorderRadiusBottomright = '10px';
				inputs[j].style.WebkitBorderTopRightRadius = '10px';
				inputs[j].style.WebkitBorderBottomRightRadius = '10px';
				inputs[j].style.height = inputs[j].previousSibling.offsetHeight - _border_width * 2 + 'px';

				if(inputs[j].getAttribute('class') == 'contrasena_input')
					inputs[j].style.width = parseInt(fieldsets[i].offsetWidth * 0.30) + 'px';

			//sign image
			var images = fieldsets[i].getElementsByTagName('IMG');

			for(var j=0; j<images.length; j++)

				if(images[j].getAttribute('class') == 'sign_image')
					show_sign(images[j]);//at menu.js

			}

		}

	}

}

function fit_workers(div)
{
	var tables = div.getElementsByTagName('table');

	for(var i=0; i<tables.length; i++)

		if(tables[i].getAttribute('class') == 'workers_titles')
			var _titles_table = tables[i];
		else if(tables[i].getAttribute('class') == 'workers_options')
			var _options_table = tables[i];

	var divs = div.getElementsByTagName('div');

	for(var i=0; i<divs.length; i++)

		if(divs[i].getAttribute('class') == 'options')
		{
			var _options_div = divs[i];
			break;
		}

	_titles_table.style.display = 'block';
	_titles_table.style.position = 'absolute';
	_titles_table.style.padding = 0;
	_titles_table.style.margin = 0;
	_titles_table.style.border = 'none';
	_titles_table.style.background = 'none';
	_titles_table.style.font = font;

	for(var i=0; i<_titles_table.rows[0].cells.length; i++)
	{
		_titles_table.rows[0].cells[i].style.background = color_opaque;
		_titles_table.rows[0].cells[i].style.font = title_font;
	}

	_options_div.style.display = 'block';
	_options_div.style.position = 'absolute';
	_options_div.style.padding = 0;
	_options_div.style.margin = 0;
	_options_div.style.border = 'none';
	_options_div.style.background = 'none';

	if(_options_table)
	{
		_options_div.style.overflow = 'auto';
		_options_table.style.textAlign = 'center';
		_options_table.style.top = '0px';
		_options_table.style.display = 'block';
		_options_table.style.position = 'absolute';
		_options_table.style.padding = 0;
		_options_table.style.margin = 0;
		_options_table.style.border = 'none';
		_options_table.style.background = 'none';
		_options_table.style.font = font;
		_options_div.style.width = div.offsetWidth + 'px';
		_options_table.style.width = _options_div.offsetWidth - 15 + 'px';
		_options_div.style.left = parseInt((div.offsetWidth - _options_div.offsetWidth) / 2) + 'px';
		var _width = parseInt((div.offsetWidth - _options_table.rows[0].cells[0].offsetWidth - _options_table.rows[0].cells[1].offsetWidth - _options_table.rows[0].cells[2].offsetWidth) / 15) + 'px';

		for(var i=3; i< _options_table.rows[0].cells.length; i++)
			_options_table.rows[0].cells[i].style.width = _width;

		if(_options_table.offsetHeight > parseInt(div.offsetHeight * 0.70))
			_options_div.style.height = parseInt(div.offsetHeight * 0.70) + 'px';
		else
			_options_div.style.height = _options_table.offsetHeight + 'px';

		for(var i=1; i<_options_table.rows[0].cells.length; i++)
			_titles_table.rows[0].cells[i-1].style.width = _options_table.rows[0].cells[i].offsetWidth - 2 + 'px';

		_options_div.style.top = parseInt((div.offsetHeight - _options_div.offsetHeight) / 2) + 'px';
	}
	else
	{
		_options_div.style.height = '0px';
		_options_div.style.top = parseInt((div.offsetHeight - _options_div.offsetHeight) / 2) + 'px';
	}

	_titles_table.style.left = parseInt((div.offsetWidth - _titles_table.offsetWidth) / 2) + 'px';
	_options_table.style.left = _titles_table.offsetLeft - _options_table.rows[0].cells[0].offsetWidth - 2 + 'px';
	_titles_table.style.top = _options_div.offsetTop - _titles_table.offsetHeight + 'px';
	_options_table.style.width = _titles_table.offsetWidth + _options_table.rows[0].cells[0].offsetWidth + 2 + 'px';
}

function show_div(obj)
{
	//obj is load div

	if(typeof obj.moving == 'undefined' || obj.moving == 0)
		obj.vertical_centered_expand = window.setInterval(function(){_vertical_centered_expand(obj);},5);

}

function show_cal(obj)
{
	var _calendar = document.getElementById('calendar');
	_calendar.op = obj;

	if(typeof _calendar.moving == 'undefined' || _calendar.moving == 0)
		_calendar.move_right = window.setInterval(function(){_move_right(_calendar,0);},5);

}

function show_workers(obj)
{
	//obj is workers div
	obj.style.top = '0px';

//	if(typeof obj.moving == 'undefined' || obj.moving == 0)
//		obj.move_down = window.setInterval(function(){_move_down(obj,0);},5);

}

function show_capture(obj)
{
	//obj is capture div

	if(typeof obj.moving == 'undefined' || obj.moving == 0)
		obj.show = window.setInterval(function(){_show(obj,0.9);},5);

}

function close_capture(obj)
{
	//obj is close button
	var _div = obj.parentNode;

	if(typeof _div.moving == 'undefined' || _div.moving == 0)
	{
		_div.del = 1;
		_div.hide = window.setInterval(function(){_hide(_div,0);},5);
	}

}

function close_workers(obj)
{
	//obj is workers div
	obj.style.top = -obj.offsetHeight + 'px';

//	if(typeof obj.moving == 'undefined' || obj.moving == 0)
//		obj.move_up = window.setInterval(function(){_move_up(obj,-obj.offsetHeight);},5);

}


function _show(obj,val)
{
	obj.moving = 1;

	if(parseFloat(obj.style.opacity) < val)
		obj.style.opacity = parseFloat(obj.style.opacity) + 0.01;
	else
	{
		window.clearInterval(obj.show);
		document.body.scrollLeft = obj.offsetLeft + obj.offsetWidth;
		obj.moving = 0;
	}

}

function _move_up(obj,val)
{
	obj.moving = 1;

	if(obj.offsetTop > val)
		obj.style.top = obj.offsetTop - 4 + 'px';
	else
	{
		window.clearInterval(obj.move_up);
		obj.moving = 0;

		if(typeof obj.del != 'undefined' && obj.del == 1)
		{
			obj.parentNode.removeChild(obj);
			_zIndex('-');//_zIndex() at general.js
		}

	}

}

function _hide(obj,val)
{
	obj.moving = 1;

	if(parseFloat(obj.style.opacity) > val)
		obj.style.opacity = parseFloat(obj.style.opacity) - 0.01;
	else
	{
		obj.style.visibility = 'hidden';
		obj.innerHTML = '';
		window.clearInterval(obj.hide);

		if(obj.del)
			obj.parentNode.removeChild(obj);

		obj.moving = 0;
	}

}

function close_calendar()
{
	var _calendar = document.getElementById('calendar');

	if(typeof _calendar.moving == 'undefined' || _calendar.moving == 0)
		_calendar.move_left = window.setInterval(function(){_move_left(_calendar,- _calendar.offsetWidth);},5);

}

function _move_down(obj,val)
{
	obj.moving = 1;

	if(obj.offsetTop < val)
	{
		obj.style.top = obj.offsetTop + 4 + 'px';
	}
	else
	{
		window.clearInterval(obj.move_down);
		obj.moving = 0;
	}

}

function _move_left(obj,val)
{
	obj.moving = 1;

	if(obj.offsetLeft > val)
		obj.style.left = obj.offsetLeft - 4 + 'px';
	else
	{
		window.clearInterval(obj.move_left);
		obj.moving = 0;
	}

}

function _move_right(obj,val)
{
	obj.moving = 1;

	if(obj.offsetLeft < val)
		obj.style.left = obj.offsetLeft + 4 + 'px';
	else
	{
		window.clearInterval(obj.move_right);
		obj.moving = 0;
	}

}

function close_div(obj)
{
	//obj is close button
	var _div = obj.parentNode;
	close_calendar();
	_div.parentNode.removeChild(_div);
	_zIndex('-');//_zIndex() at general.js


/*	if(typeof _div.moving == 'undefined' || _div.moving == 0)
	{
		_div.del = 1;
		_div.vertical_centered_contract = window.setInterval(function(){_vertical_centered_contract(_div);},5);
	}
*/
}

function _vertical_centered_expand(obj)//expands an object vertically
{
	obj.moving = 1;

	if(obj.offsetTop > obj.final_top)
	{
		obj.style.top = obj.offsetTop - 8 + 'px';
		obj.style.height = obj.offsetHeight + 8 + 'px';
	}
	else
	{
		window.clearInterval(obj.vertical_centered_expand);
		obj.moving = 0;
	}

}

function _vertical_centered_contract(obj)//contracts an object vertically
{
	obj.moving = 1;

	if(obj.offsetHeight > 8)
	{
		obj.style.top = obj.offsetTop + 6 + 'px';

		if(obj.offsetHeight - 20 < 0)
			obj.style.height = '0px';
		else
			obj.style.height = obj.offsetHeight - 20 + 'px';

	}
	else
	{
		window.clearInterval(obj.vertical_centered_contract);
		obj.moving = 0;

		if(typeof obj.del != 'undefined' && obj.del == 1)
		{
			obj.parentNode.removeChild(obj);
			_zIndex('-');//_zIndex() at general.js
		}

	}

}

function _mark(obj)
{
	obj.style.color = '#fff';
	obj.style.background = color_opaque;
}

function _unmark(obj)
{
	obj.style.color = '#333';
	obj.style.background = '#fff';
}
