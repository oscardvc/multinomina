function _del(obj)//deletes a column at table "Resumen"
{
	var tabla = obj.parentNode.parentNode;

	if(! tabla.colspans)
	{

		if(obj.parentNode.getAttribute('class') == 'sucursales_row')
		{
			var n = obj.parentNode.previousSibling.cells.length - 4;//number of cells at first row - title - total asalariados - total asimilables - total
			var m = obj.parentNode.cells.length;//number of cells at second row
		}
		else
		{
			var n = obj.parentNode.cells.length - 4;//number of cells at first row - title - total asalariados - total asimilables - total
			var m = obj.parentNode.nextSibling.cells.length;//number of cells at second row
		}

		var s = m/n;//number of branches
		tabla.colspans = Array(s, s, s);//"asalariados" branches, "asimilables" branches, "totales" branches
	}

	if(obj.parentNode.getAttribute('class') == 'sucursales_row')//if a sucursal was clicked
		_del_sucursal(obj);
	else if(obj.innerHTML == 'Asalariados' || obj.innerHTML == 'Asimilables' || obj.innerHTML == 'Totales')//if Asalariados or Asimilables or Totales was clicked
	{

		for(var i=0; obj.parentNode && i<obj.parentNode.nextSibling.cells.length; i++)
		{

			if(obj.parentNode.nextSibling.cells[i].getAttribute('owner') == obj.innerHTML)
			{
				_del_sucursal(obj.parentNode.nextSibling.cells[i]);
				i --;
			}

		}

	}
	else if(obj.innerHTML == 'Nómina')
	{
		//getting index
		var _index = 0;
		var tabla = obj.parentNode.parentNode;

		for(var i=1; i<tabla.rows.length; i++)

			if(tabla.rows[i].getAttribute('class') != 'sucursales_row')
				tabla.rows[i].removeChild(tabla.rows[i].cells[_index]);

	}
	else if(obj.innerHTML == 'Total asalariados')
	{
		//getting index
		var _index = 0;

		for(var i=0; i<obj.parentNode.cells.length; i++)

			if(obj.parentNode.cells[i].innerHTML == 'Nómina')
				_index ++;

		for(var i=0; i<obj.parentNode.nextSibling.cells.length; i++)

			if(obj.parentNode.nextSibling.cells[i].getAttribute('owner') == 'Asalariados')
				_index ++;

		var tabla = obj.parentNode.parentNode;

		for(var i=1; i<tabla.rows.length; i++)

			if(tabla.rows[i].getAttribute('class') != 'sucursales_row' && tabla.rows[i].getAttribute('class') != 'titles_row')
				tabla.rows[i].removeChild(tabla.rows[i].cells[_index]);

		for(var i=1; i<tabla.rows.length; i++)

			if(tabla.rows[i].getAttribute('class') == 'titles_row')
			{

				for(var j=0; j<tabla.rows[i].cells.length; j++)

					if(tabla.rows[i].cells[j].innerHTML == 'Total asalariados')
					{
						tabla.rows[i].removeChild(tabla.rows[i].cells[j]);
						break;
					}

			}

	}
	else if(obj.innerHTML == 'Total asimilables')
	{
		//getting index
		var _index = 0;

		for(var i=0; i<obj.parentNode.cells.length; i++)

			if(obj.parentNode.cells[i].innerHTML == 'Nómina' || obj.parentNode.cells[i].innerHTML == 'Total asalariados')
				_index ++;

		for(var i=0; i<obj.parentNode.nextSibling.cells.length; i++)

			if(obj.parentNode.nextSibling.cells[i].getAttribute('owner') == 'Asalariados' || obj.parentNode.nextSibling.cells[i].getAttribute('owner') == 'Asimilables')
				_index ++;

		var tabla = obj.parentNode.parentNode;

		for(var i=1; i<tabla.rows.length; i++)

			if(tabla.rows[i].getAttribute('class') != 'sucursales_row' && tabla.rows[i].getAttribute('class') != 'titles_row')
				tabla.rows[i].removeChild(tabla.rows[i].cells[_index]);

		for(var i=1; i<tabla.rows.length; i++)

			if(tabla.rows[i].getAttribute('class') == 'titles_row')
			{

				for(var j=0; j<tabla.rows[i].cells.length; j++)

					if(tabla.rows[i].cells[j].innerHTML == 'Total asimilables')
					{
						tabla.rows[i].removeChild(tabla.rows[i].cells[j]);
						break;
					}

			}

	}
	else if(obj.innerHTML == 'Total')
	{
		//getting index
		var _index = 0;

		for(var i=0; i<obj.parentNode.cells.length; i++)

			if(obj.parentNode.cells[i].innerHTML == 'Nómina' || obj.parentNode.cells[i].innerHTML == 'Total asalariados' || obj.parentNode.cells[i].innerHTML == 'Total asimilables')
				_index ++;

		for(var i=0; i<obj.parentNode.nextSibling.cells.length; i++)

			if(obj.parentNode.nextSibling.cells[i].getAttribute('owner') == 'Asalariados' || obj.parentNode.nextSibling.cells[i].getAttribute('owner') == 'Asimilables' || obj.parentNode.nextSibling.cells[i].getAttribute('owner') == 'Totales')
				_index ++;

		var tabla = obj.parentNode.parentNode;

		for(var i=1; i<tabla.rows.length; i++)

			if(tabla.rows[i].getAttribute('class') != 'sucursales_row' && tabla.rows[i].getAttribute('class') != 'titles_row')
				tabla.rows[i].removeChild(tabla.rows[i].cells[_index]);

		for(var i=1; i<tabla.rows.length; i++)

			if(tabla.rows[i].getAttribute('class') == 'titles_row')
			{

				for(var j=0; j<tabla.rows[i].cells.length; j++)

					if(tabla.rows[i].cells[j].innerHTML == 'Total')
					{
						tabla.rows[i].removeChild(tabla.rows[i].cells[j]);
						break;
					}

			}

	}

	styleThis();
}

function _del_sucursal(obj)
{
	var tabla = obj.parentNode.parentNode;

	//getting index for cell to be deleted
	for(var i=0; i<obj.parentNode.cells.length; i++)

		if(obj.parentNode.cells[i] == obj)
			break;

	if(obj.getAttribute('owner') == 'Asalariados')
	{

		if(tabla.colspans[0] == 1)
		{

			for(var j=1; j<tabla.rows.length; j++)

				if(tabla.rows[j].getAttribute('class') == 'sucursales_row')
					tabla.rows[j].removeChild(tabla.rows[j].cells[i]);
				else if(tabla.rows[j].getAttribute('class') != 'titles_row')
					tabla.rows[j].removeChild(tabla.rows[j].cells[i + 1]);

			//deleting Asalariados column
			for(var j=1; j<tabla.rows.length; j++)
			{

				if(tabla.rows[j].getAttribute('class') == 'titles_row')
				{

					for(var k=0; k<tabla.rows[j].cells.length; k++)
					{

						if(tabla.rows[j].cells[k].innerHTML == 'Asalariados')
						{
							tabla.rows[j].removeChild(tabla.rows[j].cells[k]);
							break;
						}

					}

				}

			}

		}
		else
		{

			for(var j=1; j<tabla.rows.length; j++)

				if(tabla.rows[j].getAttribute('class') == 'sucursales_row')
					tabla.rows[j].removeChild(tabla.rows[j].cells[i]);
				else if(tabla.rows[j].getAttribute('class') != 'titles_row')
					tabla.rows[j].removeChild(tabla.rows[j].cells[i + 1]);

			//decreasing colspan
			for(var j=1; j<tabla.rows.length; j++)
			{

				if(tabla.rows[j].getAttribute('class') == 'titles_row')
				{

					for(var k=0; k<tabla.rows[j].cells.length; k++)
					{

						if(tabla.rows[j].cells[k].innerHTML == 'Asalariados')
						{
							tabla.rows[j].cells[k].setAttribute('colspan',tabla.colspans[0] - 1);
							break;
						}

					}

				}

			}

		}

		tabla.colspans[0] --;
	}
	else if(obj.getAttribute('owner') == 'Asimilables')
	{
		//getting offset
		var _offset = 1;

		for(var j=0; j<obj.parentNode.previousSibling.cells.length; j++)

			if(obj.parentNode.previousSibling.cells[j].innerHTML == 'Total asalariados')
				_offset ++;

		if(tabla.colspans[1] == 1)
		{

			for(var j=1; j<tabla.rows.length; j++)

				if(tabla.rows[j].getAttribute('class') == 'sucursales_row')
					tabla.rows[j].removeChild(tabla.rows[j].cells[i]);
				else if(tabla.rows[j].getAttribute('class') != 'titles_row')
					tabla.rows[j].removeChild(tabla.rows[j].cells[i + _offset]);

			//deleting Asimilables column
			for(var j=1; j<tabla.rows.length; j++)
			{

				if(tabla.rows[j].getAttribute('class') == 'titles_row')
				{

					for(var k=0; k<tabla.rows[j].cells.length; k++)
					{

						if(tabla.rows[j].cells[k].innerHTML == 'Asimilables')
						{
							tabla.rows[j].removeChild(tabla.rows[j].cells[k]);
							break;
						}

					}

				}

			}

		}
		else
		{

			for(var j=1; j<tabla.rows.length; j++)

				if(tabla.rows[j].getAttribute('class') == 'sucursales_row')
					tabla.rows[j].removeChild(tabla.rows[j].cells[i]);
				else if(tabla.rows[j].getAttribute('class') != 'titles_row')
					tabla.rows[j].removeChild(tabla.rows[j].cells[i + _offset]);

			//decreasing colspan
			for(var j=1; j<tabla.rows.length; j++)
			{

				if(tabla.rows[j].getAttribute('class') == 'titles_row')
				{

					for(var k=0; k<tabla.rows[j].cells.length; k++)
					{

						if(tabla.rows[j].cells[k].innerHTML == 'Asimilables')
						{
							tabla.rows[j].cells[k].setAttribute('colspan',tabla.colspans[1] - 1);
							break;
						}

					}

				}

			}

		}

		tabla.colspans[1] --;
	}
	else if(obj.getAttribute('owner') == 'Totales')
	{
		//getting offset
		var _offset = 1;

		for(var j=0; j<obj.parentNode.previousSibling.cells.length; j++)

			if(obj.parentNode.previousSibling.cells[j].innerHTML == 'Total asalariados' || obj.parentNode.previousSibling.cells[j].innerHTML == 'Total asimilables')
				_offset ++;

		if(tabla.colspans[2] == 1)
		{

			for(var j=1; j<tabla.rows.length; j++)

				if(tabla.rows[j].getAttribute('class') == 'sucursales_row')
					tabla.rows[j].removeChild(tabla.rows[j].cells[i]);
				else if(tabla.rows[j].getAttribute('class') != 'titles_row')
					tabla.rows[j].removeChild(tabla.rows[j].cells[i + _offset]);

			//deleting Totales column
			for(var j=1; j<tabla.rows.length; j++)
			{

				if(tabla.rows[j].getAttribute('class') == 'titles_row')
				{

					for(var k=0; k<tabla.rows[j].cells.length; k++)
					{

						if(tabla.rows[j].cells[k].innerHTML == 'Totales')
						{
							tabla.rows[j].removeChild(tabla.rows[j].cells[k]);
							break;
						}

					}

				}

			}

		}
		else
		{

			for(var j=1; j<tabla.rows.length; j++)

				if(tabla.rows[j].getAttribute('class') == 'sucursales_row')
					tabla.rows[j].removeChild(tabla.rows[j].cells[i]);
				else if(tabla.rows[j].getAttribute('class') != 'titles_row')
					tabla.rows[j].removeChild(tabla.rows[j].cells[i + _offset]);

			//decreasing colspan
			for(var j=1; j<tabla.rows.length; j++)
			{

				if(tabla.rows[j].getAttribute('class') == 'titles_row')
				{

					for(var k=0; k<tabla.rows[j].cells.length; k++)
					{

						if(tabla.rows[j].cells[k].innerHTML == 'Totales')
						{
							tabla.rows[j].cells[k].setAttribute('colspan',tabla.colspans[2] - 1);
							break;
						}

					}

				}

			}

		}

		tabla.colspans[2] --;
	}

}

function _del_field(obj)
{
	var row = obj.parentNode;

	while(row.nextSibling && row.nextSibling.getAttribute('class') != 'titles_row')
		row.parentNode.removeChild(row.nextSibling);

	row.parentNode.removeChild(row);
}

function _marck(obj)
{

	if(obj.parentNode.getAttribute('class') == 'sucursales_row')
		obj.style.background = '#cc1100';
	else
		obj.style.background = '#cc1100';

}

function _unmarck(obj)
{

	if(obj.parentNode.getAttribute('class') == 'sucursales_row')
		obj.style.background = color_opaque;
	else
		obj.style.background = '#555';

}
