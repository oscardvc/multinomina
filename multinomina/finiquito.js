function view_finiquito(obj)
{
	var _fieldset = obj.parentNode;
	var textareas = _fieldset.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'id')
		{
			var _id = textareas[i].value;
			break;
		}

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('class') == 'lugar_textarea')
		{
			var _lugar = textareas[i].value;
			break;
		}

	var selects = _fieldset.getElementsByTagName('select');

	for(var i=0; i<selects.length; i++)

		if(selects[i].getAttribute('class') == 'finiquitador_select')
		{
			var _finiquitador = selects[i].options[selects[i].selectedIndex].value;
			break;
		}

	window.open('view_finiquito.php?id=' + _id + '&finiquitador=' + _finiquitador + '&lugar=' + _lugar);
}

function view_carta(obj)
{
	var _fieldset = obj.parentNode;
	var textareas = _fieldset.getElementsByTagName('textarea');

	for(var i=0; i<textareas.length; i++)

		if(textareas[i].getAttribute('name') == 'id')
		{
			var _id = textareas[i].value;
			break;
		}

	var selects = _fieldset.getElementsByTagName('select');

	for(var i=0; i<selects.length; i++)

		if(selects[i].getAttribute('class') == 'finiquitador_select')
		{
			var _finiquitador = selects[i].options[selects[i].selectedIndex].value;
			break;
		}

	window.open('view_carta.php?id=' + _id + '&finiquitador=' + _finiquitador);
}
