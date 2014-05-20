function _del_row(row)//deletes a row at "Concentrado"
{
	var table = row.parentNode.parentNode;
	var totals_row = table.rows[table.rows.length - 1];

	for(var i=0; i<row.cells.length; i++)
	{
		var partial = row.cells[i].innerHTML.replace(',','');
		var total = totals_row.cells[i].innerHTML.replace(',','');

		if(!isNaN(partial) && !isNaN(total))
		{
			total -= partial;
			totals_row.cells[i].innerHTML = _format(total.toFixed(2));//_format at moneda.js
		}

	}

	row.parentNode.removeChild(row);
}

function _del(obj)//deletes a column at "Concentrado"
{
	var _table = obj.parentNode.parentNode.parentNode;

	if(obj.parentNode.getAttribute('class') == 'subtitles')//if a subtitle was clicked
		_del_subtitle(obj);
	else//if a title was clicked
	{
		var _offset = 0;
		var _obj = obj;

		while(_obj.previousSibling)
		{
			_offset += _obj.previousSibling.colSpan;
			_obj = _obj.previousSibling;
		}

		var _colspan = obj.colSpan;

		for(var i=0; i<_colspan; i++)
			_del_subtitle(_table.rows[1].cells[_offset]);

	}

}

function _del_subtitle(obj)
{
	var _table = obj.parentNode.parentNode.parentNode;

	//getting index for cell to be deleted
	for(var _index=0; _index<obj.parentNode.cells.length; _index++)

		if(obj.parentNode.cells[_index] == obj)
			break;

	for(var i=1; i<_table.rows.length; i++)
		_table.rows[i].removeChild(_table.rows[i].cells[_index]);

	//getting title
	var _title = 0;
	for(var i=0; i<_table.rows[0].cells.length; i++)
	{
		var _colspan = 0;

		for(j=i; j>=0; j--)
			_colspan += _table.rows[0].cells[j].colSpan;

		if(_colspan <= _index)
			_title ++;
		else
			break;

	}

	//decreasing colspan
	if(_table.rows[0].cells[_title].colSpan == 1)
		_table.rows[0].removeChild(_table.rows[0].cells[_title]);
	else
		_table.rows[0].cells[_title].colSpan --;

}

function _mark_row(obj)
{
	obj.style.background = '#ddd';
}

function _unmark_row(obj)
{
	obj.style.background = '#fff';
}

function _marck(obj)
{
	obj.style.background = '#cc1100';
}

function _unmarck(obj)
{

	if(obj.parentNode.getAttribute('class') == 'subtitles')
		obj.style.background = '#3399cc';
	else
		obj.style.background = '#555';

}

function show_totals(obj)
{
	obj.removeAttribute('onclick');
	var tables = document.getElementsByTagName('table');

	for(var i=0; i<tables.length; i++)
	{
		var count = 0;//the count of cols below a header
		//deleting middle rows
		while(tables[i].rows.length > 3)
			tables[i].rows[2].parentNode.removeChild(tables[i].rows[2]);

		//ordering vertically
		for(var j=1; tables[i].rows.length > 2 && j<= tables[i].rows[0].cells.length; j++)
		{
			var tr = document.createElement('tr');
			tables[i].appendChild(tr);
			tr.setAttribute('class','table_header');

			if(tr.previousSibling.getAttribute('class') == 'table_header')
				tr.previousSibling.parentNode.removeChild(tr.previousSibling);

			var td = document.createElement('td');
			tr.appendChild(td);
			td.innerHTML = tables[i].rows[0].cells[j-1].innerHTML;
			td.setAttribute('colspan',2);
			td.style.color = '#fff';
			td.style.background = '#666';

			for(var k=0; tables[i].rows.length > 2 && k < tables[i].rows[0].cells[j-1].getAttribute('colspan'); k++)
			{

				if(tables[i].rows[2].cells[count].innerHTML != '0.00' && tables[i].rows[2].cells[count].innerHTML != '0' && tables[i].rows[2].cells[count].innerHTML != '' && tables[i].rows[2].cells[count].innerHTML != 'Total')
				{
					var _tr = document.createElement('tr');
					tables[i].appendChild(_tr);
					var _td = document.createElement('td');
					_tr.appendChild(_td);
					_td.innerHTML = tables[i].rows[1].cells[count].innerHTML;
					_td.style.border = '1px solid #666';
					var td_ = document.createElement('td');
					_tr.appendChild(td_);
					td_.innerHTML = tables[i].rows[2].cells[count].innerHTML;
					td_.style.textAlign = 'right';
					td_.style.border = '1px solid #666';
				}

				count++;
			}
		}

		//deleting rows 0,1 & 2
		for(var j=0; j<= 2; j++)

			if(tables[i].rows[0])
				tables[i].rows[0].parentNode.removeChild(tables[i].rows[0]);

		if(tables[i].rows.length < 2)
		{
			tables[i].parentNode.removeChild(tables[i]);
			i--;
		}

	}


}
