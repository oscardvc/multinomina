function mark_report(obj)
{
	obj.style.background = '#f00';
}

function unmark_report(obj)
{

	if(obj.getAttribute('class') != 'header')
		obj.style.background = color_opaque;
	else
		obj.style.background = 'none';

}

function del_report(obj)
{

	if(obj.getAttribute('class') != 'header')
	{
		var cols = obj.parentNode.getElementsByTagName('td');

		for(var i=0; i<cols.length; i++)

			if(cols[i] == obj)
				break;

		var tables = document.getElementsByTagName('table');

		for(var j=0; j<tables.length; j++)

			for(var k=2; k<tables[j].rows.length; k++)

				if(tables[j].rows[k].cells[i])
					tables[j].rows[k].cells[i].parentNode.removeChild(tables[j].rows[k].cells[i]);

	}
	else
	{
		obj.innerHTML = '';
		var tables = document.getElementsByTagName('table');

		for(var i=0; i<tables.length; i++)

			if(tables[i] == obj.parentNode.parentNode)
				break;

		if(i-1 >= 0)
			tables[i-1].style.pageBreakAfter = 'auto';

	}

}
