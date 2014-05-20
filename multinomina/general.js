function _alert(txt)
{
	var alert_box = document.createElement('div');
	document.body.appendChild(alert_box);
	alert_box.style.display = 'block';
	alert_box.style.position = 'absolute';
	alert_box.style.padding = 0;
	alert_box.style.margin = 0;
	alert_box.style.border = _border_width + 'px solid #555';
	alert_box.style.background = 'rgba(255, 255, 255, 0.90)';
	alert_box.style.filter = 'filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#CCffffff, endColorstr=#CCffffff)';
	alert_box.style.MozBoxShadow = "0 0 8px 8px #888";
	alert_box.style.WebkitBoxShadow = "0 0 8px 8px #888";
	alert_box.style.boxShadow = "0 0 8px 8px #888";
	alert_box.style.height = _height + "px";
	var image = document.createElement('img');
	alert_box.appendChild(image);
	image.style.display = 'block';
	image.style.position = 'absolute';
	image.style.padding = 0;
	image.style.margin = 0;
	image.style.border = 'none';
	image.style.background = 'none';
	image.style.top = '0px';
	image.style.left = '0px';
	image.style.zIndex = 1;
	image.src = 'icon.php?subject=alert&height=' + _height;
	var msg = document.createElement('span');
	msg.innerHTML = txt;
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
	alert_box.style.top = parseInt((window_height - _height) / 2) + 'px';
	alert_box.style.width = image.offsetWidth + msg.offsetWidth + _height * 2 + 'px';
	alert_box.style.left = parseInt((window_width - alert_box.offsetWidth) / 2) + 'px';
	alert_box.style.zIndex = '200';
	alert_box.appear = window.setInterval(function(){_appear(alert_box);},5);//_appear() at presentation.js
	alert_box.del = 1;
	lapsus(alert_box);
}

function select_option(obj)
{

	if(obj.getAttribute('id') == 'selected_option')
	{
		obj.cells[0].firstChild.checked = false;
		obj.cells[0].style.background = 'none';
		obj.removeAttribute('id');
	}
	else
	{

		for(var i=0; i<obj.parentNode.parentNode.rows.length; i++)

			if(obj.parentNode.parentNode.rows[i].getAttribute('id') == 'selected_option')
			{
				obj.parentNode.parentNode.rows[i].removeAttribute('id');
				obj.parentNode.parentNode.rows[i].cells[0].firstChild.checked = false;
				obj.parentNode.parentNode.rows[i].cells[0].style.background = 'none';
				break;
			}

		obj.cells[0].firstChild.checked = true;
		obj.cells[0].style.background = color_opaque;
		obj.setAttribute('id','selected_option');
	}

}

function select_multiple_option(obj)
{

	if(obj.getAttribute('id') == 'selected_option')
	{
		obj.cells[0].firstChild.checked = false;
		obj.cells[0].style.background = 'none';
		obj.removeAttribute('id');
	}
	else
	{
		obj.cells[0].firstChild.checked = true;
		obj.cells[0].style.background = color_opaque;
		obj.setAttribute('id','selected_option');
	}

}

function _zIndex(act)
{

	if(! _zIndex.index)
		_zIndex.index = 1;

	if(typeof act != 'undefined' && act == '+')
		_zIndex.index++;
	else
		_zIndex.index--;

	return _zIndex.index;
}

function mm2px(n)
{
	var _div = document.createElement('div');
	document.body.appendChild(_div);
	_div.style.display = 'block';
	_div.style.position = 'absolute';
	_div.style.padding = '0';
	_div.style.margin = '0';
	_div.style.border = 'none';
	_div.style.background = '#fff';
	_div.style.top = '0';
	_div.style.left = '0';
	_div.style.width = '1mm';
	_div.style.height = '0mm';
	var val = n * _div.offsetWidth;
	document.body.removeChild(_div);
	return val;
}
