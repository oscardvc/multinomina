//this file implements the tabs (at tabs html div) behavior

function show(_id,obj)//id is the html fieldset identifier to be visible and obj is the tab to be highlighted
{
	var forms = obj.parentNode.getElementsByTagName('form');//obj is 'DIV' element, parentNode is 'Capsule'
	var fieldsets = forms[0].getElementsByTagName('fieldset');

	for (var i=0; i < fieldsets.length; i++)

		if(fieldsets[i].getAttribute('class') == _id)
			fieldsets[i].style.visibility = 'visible';
		else
			fieldsets[i].style.visibility = 'hidden';

	highlight(obj);
}

function highlight(obj)
{
	var divs = obj.parentNode.getElementsByTagName('div');

	for(var i=0; i<divs.length; i++)

		if(divs[i].getAttribute('class') && divs[i].getAttribute('class').match(/tab/g))
		{
			var b = (divs[i] == obj);

			if(b)
			{

				if(divs[i].innerHTML == 'Préstamo del fondo de ahorro' || divs[i].innerHTML == 'FONACOT' || divs[i].innerHTML == 'INFONAVIT' || divs[i].innerHTML == 'Pago por seguro de vida' || divs[i].innerHTML == 'Salario diario' || divs[i].innerHTML == 'Salario mínimo' || divs[i].innerHTML == 'Logotipo' || divs[i].innerHTML == 'Archivo digital' || divs[i].innerHTML == 'Sello digital' || divs[i].innerHTML == 'Servicios' || divs[i].innerHTML == 'Sucursales' || divs[i].innerHTML == 'Bajas' || divs[i].innerHTML == 'Descuentos pendientes' || divs[i].innerHTML == 'Fondo de garantía')
					divs[i].style.borderTop = _border_width + 'px solid #fff';
				else
					divs[i].style.borderBottom = _border_width + 'px solid #fff';

			}
			else
			{

				if(divs[i].innerHTML == 'Préstamo del fondo de ahorro' || divs[i].innerHTML == 'FONACOT' || divs[i].innerHTML == 'INFONAVIT' || divs[i].innerHTML == 'Pago por seguro de vida' || divs[i].innerHTML == 'Salario diario' || divs[i].innerHTML == 'Salario mínimo' || divs[i].innerHTML == 'Logotipo' || divs[i].innerHTML == 'Archivo digital' || divs[i].innerHTML == 'Sello digital' || divs[i].innerHTML == 'Servicios' || divs[i].innerHTML == 'Sucursales' || divs[i].innerHTML == 'Bajas' || divs[i].innerHTML == 'Descuentos pendientes' || divs[i].innerHTML == 'Fondo de garantía')
					divs[i].style.borderTop = _border_width + 'px solid #555';
				else
					divs[i].style.borderBottom = _border_width + 'px solid #555';

			}

		}

}
