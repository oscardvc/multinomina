<html>
	<head>
		<style type="text/css">
			table
			{
				font:normal normal normal 13px Arial , sans-serif;
			}

			.report_titles .title td
			{
				color: #fff;
				background:#333;
				text-align: center;
				border:none;
			}

			.report_titles .titles td
			{
				color: #fff;
				background: #3399cc;
				text-align: center;
				border:none;
			}

			table tr td
			{
				border:1px solid #333;
				text-align:center;
			}
		 </style>

		<script type = "text/javascript">
			if( typeof( window.innerWidth ) == 'number' )
			{ 
				//Non-IE
				window_width = window.innerWidth;
				window_height = window.innerHeight;
				window.moveTo(0,0);
				window.resizeTo(screen.availWidth, screen.availHeight);
			}
			else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) )
			{
				//IE 6+ in 'standards compliant mode'
				window_width = document.documentElement.clientWidth; 
				window_height = document.documentElement.clientHeight;
				window.moveTo(0,0);
				window.resizeTo(screen.availWidth, screen.availHeight);
			}
			else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) )
			{
				//IE 4 compatible
				window_width = document.body.clientWidth;
				window_height = document.body.clientHeight;
				window.moveTo(0,0);
				window.resizeTo(screen.availWidth, screen.availHeight);
			}

			var font = 'normal normal normal 13px Arial , sans-serif';

			function fit_screen()
			{
				var forms = document.getElementsByTagName('form');
				var _form = forms[0];
				_form.style.display = 'block';
				_form.style.position = 'absolute';
				_form.style.padding = 0;
				_form.style.margin = 0;
				_form.style.border = '4px double #333';
				_form.style.MozBorderRadiusBottomright = '10px';
				_form.style.WebkitBorderBottomRightRadius = '10px';
				_form.style.background = '#333';
				_form.style.display = 'block';
				_form.style.width = (parseInt(window_width * 0.50) > 520 ? 520 : parseInt(window_width * 0.50)) + 'px';
				_form.style.height = (parseInt(window_height * 0.50) > 135 ? 135 : parseInt(window_height * 0.50)) + 'px';
				_form.style.top = '0px';
				_form.style.left = '0px';
				var _divs = _form.getElementsByTagName('div');
				var _offset = navigator.userAgent.toLowerCase().match(/firefox/g) ? 0 : 4;

				for(var i=0; i<_divs.length; i++)

					if(_divs[i].getAttribute('class') == 'ok_button')
					{
						_divs[i].style.display = 'block';
						_divs[i].style.position = 'absolute';
						_divs[i].style.padding = '3px';
						_divs[i].style.margin = 0;
						_divs[i].style.border = 'none';
						_divs[i].style.background = '#00aa44';
						_divs[i].style.MozBorderRadius = '10px';
						_divs[i].style.WebkitBorderRadius = '10px';
						_divs[i].style.color = '#fff';
						_divs[i].style.top = _form.offsetHeight - _divs[i].offsetHeight - 10 + 'px';
						_divs[i].style.font = font;
						_divs[i].style.zIndex = 1;
						_divs[i].style.cursor = 'pointer';
						_divs[i].style.left = _form.offsetWidth - _divs[i].offsetWidth - 10 + 'px';
					}

				//labels
				var labels = _form.getElementsByTagName('label');
				var vd = 10;//vertical distance between elements
				var hld = parseInt(_form.offsetWidth * 0.40);//horizontal left directriz
				var hrd = parseInt(_form.offsetWidth * 0.70);//horizontal right directriz

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

					if(labels[j].getAttribute('class') == 'limite_inferior_del_periodo_label')
					{
						labels[j].style.top = vd + 'px';
						labels[j].style.left = hld - labels[j].offsetWidth + 'px';
					}
					else if(labels[j].getAttribute('class') == 'limite_superior_del_periodo_label')
					{
						labels[j].style.top = labels[j - 1].offsetTop + labels[j].offsetHeight + vd + 'px';
						labels[j].style.left = hld - labels[j].offsetWidth + 'px';
					}
					else if(labels[j].getAttribute('class') == 'registro_patronal_label')
					{
						labels[j].style.top = labels[j - 1].offsetTop + labels[j].offsetHeight + vd + 'px';
						labels[j].style.left = hld - labels[j].offsetWidth + 'px';
					}
					else if(labels[j].getAttribute('class') == 'solo_trabajadores_con_incidencias_label')
					{
						labels[j].style.top = labels[j - 1].offsetTop + labels[j].offsetHeight + vd + 'px';
						labels[j].style.left = hld - labels[j].offsetWidth + 'px';
					}

				}

				//textareas
				var textareas = _form.getElementsByTagName('textarea');

				for(var j=0; j<textareas.length; j++)
				{
					textareas[j].style.display = 'block';
					textareas[j].style.position = 'absolute';
					textareas[j].style.padding = 0;
					textareas[j].style.margin = 0;
					textareas[j].style.background = '#fff';
					textareas[j].style.font = font;
					textareas[j].style.color = '#555';
					textareas[j].style.top = textareas[j].previousSibling.offsetTop + 'px';
					textareas[j].style.left = textareas[j].previousSibling.offsetLeft + textareas[j].previousSibling.offsetWidth + 'px';
					textareas[j].style.MozBorderRadiusTopright = '10px';
					textareas[j].style.MozBorderRadiusBottomright = '10px';
					textareas[j].style.WebkitBorderTopRightRadius = '10px';
					textareas[j].style.WebkitBorderBottomRightRadius = '10px';
					textareas[j].style.borderLeft = 'none';
					textareas[j].style.borderTop = '4px solid #555';
					textareas[j].style.borderRight = '4px solid #555';
					textareas[j].style.borderBottom = '4px solid #555';
					textareas[j].style.height = textareas[j].previousSibling.offsetHeight - _offset * 2 + 'px';

					if(textareas[j].getAttribute('class') == 'limite_inferior_del_periodo_textarea')
						textareas[j].style.width = parseInt(_form.offsetWidth * 0.25) + 'px';
					else if(textareas[j].getAttribute('class') == 'limite_superior_del_periodo_textarea')
						textareas[j].style.width = parseInt(_form.offsetWidth * 0.25) + 'px';

				}

				//selects
				var selects = _form.getElementsByTagName('select');

				for(var j=0; j<selects.length; j++)
				{
					selects[j].style.display = 'block';
					selects[j].style.position = 'absolute';
					selects[j].style.padding = 0;
					selects[j].style.margin = 0;
					selects[j].style.background = '#fff';
					selects[j].style.font = font;
					selects[j].style.color = '#555';
					selects[j].style.top = selects[j].previousSibling.offsetTop + 'px';
					selects[j].style.left = selects[j].previousSibling.offsetLeft + selects[j].previousSibling.offsetWidth + 'px';
					selects[j].style.MozBorderRadiusTopright = '10px';
					selects[j].style.MozBorderRadiusBottomright = '10px';
					selects[j].style.WebkitBorderTopRightRadius = '10px';
					selects[j].style.WebkitBorderBottomRightRadius = '10px';
					selects[j].style.borderLeft = 'none';
					selects[j].style.borderTop = '4px solid #555';
					selects[j].style.borderRight = '4px solid #555';
					selects[j].style.borderBottom = '4px solid #555';
					selects[j].style.height = selects[j].previousSibling.offsetHeight - _offset * 2 + 'px';

					if(selects[j].getAttribute('class') == 'registro_patronal_select')
						selects[j].style.width = parseInt(_form.offsetWidth * 0.30) + 'px';

				}

				//inputs
				var inputs = _form.getElementsByTagName('input');

				for(var j=0; j<inputs.length; j++)
				{
					inputs[j].style.display = 'block';
					inputs[j].style.position = 'absolute';
					inputs[j].style.padding = 0;
					inputs[j].style.margin = 0;
					inputs[j].style.background = '#fff';
					inputs[j].style.font = font;
					inputs[j].style.color = '#555';
					inputs[j].style.top = inputs[j].previousSibling.offsetTop + 5 + 'px';
					inputs[j].style.left = inputs[j].previousSibling.offsetLeft + inputs[j].previousSibling.offsetWidth + 'px';
				}

				var _content = document.getElementById('content');
				_content.style.display = 'block';
				_content.style.position = 'absolute';
				_content.style.top = _form.offsetHeight + 30 + 'px';
				_content.style.left = '0px';
				_content.style.width = window_width - 30 + 'px';
				_content.style.height = window_height - _form.offsetHeight - 40 + 'px';
				_content.style.border = 'none';
				_content.style.background = 'none';
			}

			function get_dias_cotizados()
			{

				if (window.XMLHttpRequest)//Mozilla, Safari...
				{
					var xmldias = new XMLHttpRequest();

					if (xmldias.overrideMimeType)
						xmldias.overrideMimeType('text/xml');

				}
				else if (window.ActiveXObject)// IE
				{

					try
					{
						var xmldias = new ActiveXObject("Msxml2.XMLHTTP");
					}
					catch (e)
					{

						try
						{
							var xmldias = new ActiveXObject("Microsoft.XMLHTTP");
						}
						catch (e) {}

					}

				}

				xmldias.onreadystatechange = function()
				{

					if (xmldias.readyState==4 && xmldias.status==200 && xmldias.responseText != '')
					{
						var _content = document.getElementById('content');
						_content.innerHTML = xmldias.responseText;
						fit_report(_content);
					}

				}

				var params = '';
				var forms = document.getElementsByTagName('form');
				var _form = forms[0];
				var textareas = _form.getElementsByTagName('textarea');

				for(var i=0; i<textareas.length; i++)
					params += params == '' ? (textareas[i].getAttribute('name') + '=' + textareas[i].value) : ('&' + textareas[i].getAttribute('name') + '=' + textareas[i].value);

				var selects = _form.getElementsByTagName('select');

				for(var i=0; i<selects.length; i++)
					params += params == '' ? (selects[i].getAttribute('name') + '=' + selects[i].value) : ('&' + selects[i].getAttribute('name') + '=' + selects[i].value);

				var inputs = _form.getElementsByTagName('input');

				for(var i=0; i<inputs.length; i++)

					if(inputs[i].checked)
						params += params == '' ? (inputs[i].getAttribute('name') + '=true') : ('&' + inputs[i].getAttribute('name') + '=true');

				xmldias.open('POST','get_dias_cotizados.php', true);
				xmldias.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
				xmldias.setRequestHeader("Cache-control","no-cache");
				xmldias.setRequestHeader("Content-length", params.length);
				xmldias.setRequestHeader("Connection", "close");
				xmldias.send(params);
			}

			function fit_report(div)
			{
				var tables = div.getElementsByTagName('table');

				for(var i=0; i<tables.length; i++)

					if(tables[i].getAttribute('class') == 'report_titles')
						var _titles_table = tables[i];
					else if(tables[i].getAttribute('class') == 'report_options')
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
					_options_div.style.overflowX = 'hidden';
					_options_div.style.overflowY = 'auto';
					_options_div.style.left = parseInt((div.offsetWidth - _options_div.offsetWidth) / 2) + 'px';

					if(_options_table.offsetHeight + _options_table.offsetHeight + _options_table.rows.length * 4 > parseInt(div.offsetHeight * 0.80))
						_options_div.style.height = parseInt(div.offsetHeight * 0.80) + 'px';
					else
						_options_div.style.height = _options_table.offsetHeight + _options_table.rows.length * 4 + 'px';

					for(var i=0; i<_options_table.rows[0].cells.length; i++)

						if(_options_table.rows[0].cells[i].offsetWidth > _titles_table.rows[1].cells[i].offsetWidth)
						{
							_titles_table.rows[1].cells[i].style.width = _options_table.rows[0].cells[i].offsetWidth - 4 + 'px';
						}
						else
						{
							_options_table.rows[0].cells[i].style.width = _titles_table.rows[1].cells[i].offsetWidth - 4 + 'px';
						}

					_options_div.style.top = parseInt((div.offsetHeight - _options_div.offsetHeight) / 2) + 'px';
				}
				else
				{
					_options_div.style.height = '0px';
					_options_div.style.top = parseInt((div.offsetHeight - _options_div.offsetHeight) / 2) + 'px';
				}

				_titles_table.style.left = parseInt((div.offsetWidth - _titles_table.offsetWidth) / 2) + 'px';
				_options_table.style.left = _titles_table.offsetLeft + 'px';
				_titles_table.style.top = _options_div.offsetTop - _titles_table.offsetHeight + 'px';
			}

		</script>

	</head>
	<body onload = "fit_screen()">
		<form>
			<label class = "limite_inferior_del_periodo_label">Límite inferior del periodo</label><textarea class = "limite_inferior_del_periodo_textarea" name = "limite_inferior_del_periodo">AAAA-MM-DD</textarea>
			<label class = "limite_superior_del_periodo_label">Límite superior del periodo</label><textarea class = "limite_superior_del_periodo_textarea" name = "limite_superior_del_periodo">AAAA-MM-DD</textarea>
			<label class = "registro_patronal_label">Registro patronal</label><select title = "Registro patronal" class = "registro_patronal_select" name = "Registro_patronal">
			<option>Todos</option>
			<?php

				if(!isset($_SESSION))
					session_start();

				include_once('connection.php');
				$conn = new Connection();
				$result = $conn->query("SELECT Numero FROM Registro_patronal WHERE Cuenta = '{$_SESSION['cuenta']}'");

				while(list($numero) = $conn->fetchRow($result))
					echo "<option>$numero</option>";

				$conn->freeResult($result);
			?>
			</select>
			<label class = "solo_trabajadores_con_incidencias_label">Solo trabajadores con incidencias</label><input type = "checkbox" class = "solo_trabajadores_con_incidencias_input" name = "Solo_trabajadores_con_incidencias"/>
			<div class = "ok_button" onclick = "get_dias_cotizados()">✔</div>
		<form>
		<div id = "content"></div>
	</body>
</html>
