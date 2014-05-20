<?php
	//This page is called by the entitys' forms drawn by their methods named draw or by the add button at index.php
	include_once('archivo_digital.php');

	$archivo = new Archivo_digital();
	$archivo->setFromBrowser();

	if($_GET['update'] == 'import')
	{
		$txt = $archivo->_import();
		echo "<script type='text/javascript'>
				var _iframe = window.parent.document.getElementById('{$_POST['iframe']}');
				var _div = _iframe.parentNode;
				var div_ = document.createElement('div');
				div_.innerHTML = '$txt';
				var tables = div_.getElementsByTagName('table');
				var _table = tables[0];
				var _rows = _table.getElementsByTagName('tr');

				for(var i=1; i<_rows.length; i++)
				{
					var _cols = _rows[i].getElementsByTagName('td');
					var _trabajador = _cols[0].innerHTML;

					for(var j=1; j<_cols.length; j++)
					{
						var _title = _rows[0].cells[j].innerHTML;
						set_val(_title,_trabajador.trim(),_cols[j].innerHTML);
					}


				}

				window.parent.close_div(_iframe);

				function set_val(_title,_trabajador,_value)
				{
					var tables = _div.previousSibling.getElementsByTagName('table');

					for(var i=0; i<tables.length; i++)

						if(tables[i].getAttribute('class') == 'workers_options')
							var options_table = tables[i];
						else if(tables[i].getAttribute('class') == 'workers_titles')
							var titles_table = tables[i];

					for (var j=0; j<titles_table.rows[0].cells.length; j++)

						if(titles_table.rows[0].cells[j].innerHTML == _title)
							break;

					for (var i=0; i<options_table.rows.length; i++)

						if(options_table.rows[i].cells[1].innerHTML == _trabajador && ! options_table.rows[i].cells[1].getAttribute('disabled'))
						{
							options_table.rows[i].cells[j+1].innerHTML = _value.length > 6 ? _value.substring(0, 6) + '...' : _value;
							options_table.rows[i].cells[j+1].setAttribute('value',_value);
							break;
						}

				}

		</script>";
	}
	elseif($_GET['update'] == '_import')
	{
		$msg = $archivo->_import_();
		echo "<script type='text/javascript'>
				var _iframe = window.parent.document.getElementById('{$_POST['iframe']}');
				var _div = _iframe.parentNode;
				var message = '$msg';
				window.parent._alert('$msg');
				var images = _div.getElementsByTagName('img');

				for(var i=0; i<images.length; i++)

					if(images[i].getAttribute('class') == 'loading_image')
					{
						_div.removeChild(images[i]);
						break;
					}

				if(message.match(/importado/g))
					window.setInterval(function(){window.parent.close_div(_iframe);},3000);

		</script>";
	}
	else
	{
		$msg = $archivo->dbStore($_GET['update']);
		echo "<script type='text/javascript'>
				var _iframe = window.parent.document.getElementById('{$_POST['iframe']}');
				var _div = _iframe.parentNode;
				//_iframe.style.visibility = 'visible';
				//_iframe.style.width = _div.offsetWidth + 'px';
				//_iframe.style.height = parseInt(_div.offsetHeight * 0.5) + 'px';
				//_iframe.style.display = 'block';
				//_iframe.style.position = 'absolute';
				//_iframe.style.top = '50px';
				//_iframe.style.left = '0px';
				var fieldsets = _div.previousSibling.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)

					if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
						var data_fieldset = fieldsets[i];
					else if(fieldsets[i].getAttribute('class') == 'Archivo_digital_fieldset')
						var file_fieldset = fieldsets[i];

				var textareas = data_fieldset.getElementsByTagName('textarea');

				for(var i=0; i<textareas.length; i++)

					if(textareas[i].getAttribute('class') == 'rfc_textarea')
					{
						var rfc = textareas[i].value;
						break;
					}

				window.parent.get_options('EDIT','Trabajador','Archivo_digital',rfc,'First',file_fieldset);
				window.parent._alert('$msg');
				window.parent.close_div(_iframe);
		</script>";
	}
?>
