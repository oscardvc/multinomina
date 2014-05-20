<?php //This page is called by a javascript function named view at nomina.js?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

	<head>
		<style type="text/css">
			.saltopagina
			{
				page-break-after: always;
			}

		</style>
		<script type="text/javascript" src="moneda.js"></script>
		<script type="text/javascript" src="calendar.js"></script>
		<script type="text/javascript">
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

			var font = 'normal normal normal 2.6mm Arial , sans-serif'; //weight, style, variant, size, family name, generic family
			var title_font = 'bold normal normal 2.6mm Arial , sans-serif';

			function _load(obj)
			{
				var fieldset = obj.parentNode;
				var _class = fieldset.getAttribute('class');
				var form = fieldset.parentNode;
				var fieldsets = form.getElementsByTagName('fieldset');

				for(var i=0; i<fieldsets.length; i++)

					if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
					{
						var datos_fieldset = fieldsets[i];
						break;
					}

				var textareas = datos_fieldset.getElementsByTagName('textarea');

				for(var i=0; i<textareas.length; i++)

					if(textareas[i].getAttribute('name') == 'id')
						var _id = textareas[i].value;
					else if(textareas[i].getAttribute('name') == 'Administradora')
						var _administradora = textareas[i].value;
					else if(textareas[i].getAttribute('name') == 'Limite_inferior_del_periodo')
						var _limite_inferior = textareas[i].value;
					else if(textareas[i].getAttribute('name') == 'Limite_superior_del_periodo')
						var _limite_superior = textareas[i].value;

				if (window.XMLHttpRequest)//Mozilla, Safari...
				{
					var xmltables = new XMLHttpRequest();

					if (xmltables.overrideMimeType)
						xmltables.overrideMimeType('text/xml');

				}
				else if (window.ActiveXObject)// IE
				{

					try
					{
						var xmltables = new ActiveXObject("Msxml2.XMLHTTP");
					}
					catch (e)
					{

						try
						{
							var xmltables = new ActiveXObject("Microsoft.XMLHTTP");
						}
						catch (e) {}

					}

				}

				xmltables.onreadystatechange = function()
				{

					if (xmltables.readyState==4 && xmltables.status==200)
					{

						var _div = document.createElement('div');
						_div.innerHTML = xmltables.responseText;
						var tables = _div.getElementsByTagName('table');
						var divs = _div.getElementsByTagName('div');
						var periodo = divs[0].innerHTML;
						var administradora_rfc = divs[1].innerHTML;
						var registro_patronal = divs[2].innerHTML;

						for(var i=0; i<tables.length; i++)
						{
							var rows = tables[i].rows;

							for(var j=1; j<rows.length; j++)
								set_recibo(_administradora, _limite_inferior, _limite_superior, rows[j], administradora_rfc, registro_patronal);

						}

					}

				}

				xmltables.open('POST','_get_tables.php?_class=Pensiones' + '&nomina=' + _id, true);
				xmltables.setRequestHeader("cache-control","no-cache");
				xmltables.send('');
			}

			function set_recibo(administradora, limite_inferior, limite_superior, row, administradora_rfc, registro_patronal)
			{
				var cols = row.getElementsByTagName('td');
				var trabajador_nombre = cols[0].innerHTML;
				var cantidad = cols[1].innerHTML;
				var beneficiario = cols[2].innerHTML;
				var folio = cols[3].innerHTML;
				var expediente = cols[4].innerHTML;
				var oficio = cols[5].innerHTML;
				var porcentaje = cols[6].innerHTML;
				//body settings
				document.body.style.padding = '0mm';
				document.body.style.margin = '0mm';
				document.body.style.border = 'none';
				//container
				var container = document.createElement('div');
				container.style.display = 'block';
				container.style.position = 'relative';
				container.style.padding = '0mm';
				container.style.margin = '0mm';
				container.style.border = 'none';
				container.style.width = '215.9mm';
				container.style.height = '139.7mm';
				container.style.overflow = 'hidden';
				document.body.appendChild(container);
				//logo
				var image = document.createElement('img');
				container.appendChild(image);
				image.style.display = 'block';
				image.style.position = 'relative';
				image.style.top = '5mm';
				image.style.left = '5mm';
				image.style.width = '56mm';
				image.style.height = '10mm';
				image.style.padding = '0mm';
				image.style.margin = '0mm';
				image.style.border = 'none';
				image.style.background = 'none';
				image.src = 'images/logo_blanco.jpg';
				var images = document.getElementsByTagName('IMG');

				if(images.length % 2 == 0)
					var _break = true;
				else
					var _break = false;

				//"administradora" title
				var _administradora = document.createElement('span');
				container.appendChild(_administradora);
				_administradora.innerHTML = administradora;
				_administradora.style.display = 'block';
				_administradora.style.position = 'relative';
				_administradora.style.width = '205.9mm';
				_administradora.style.height = '9mm';
				_administradora.style.top = '10mm';
				_administradora.style.left = '5mm';
				_administradora.style.padding = '0mm';
				_administradora.style.margin = '0mm';
				_administradora.style.border = 'none';
				_administradora.style.background = '#fff';
				_administradora.style.font = title_font;
				_administradora.style.textAlign = 'center';
				_administradora.style.color = '#555';
				//"RFC"
				var _rfc = document.createElement('span');
				container.appendChild(_rfc);
				_rfc.innerHTML = 'R.F.C. ' + administradora_rfc;
				_rfc.style.display = 'block';
				_rfc.style.position = 'relative';
				_rfc.style.padding = '0mm';
				_rfc.style.margin = '0mm';
				_rfc.style.border = 'none';
				_rfc.style.background = '#fff';
				_rfc.style.top = '10mm';
				_rfc.style.left = '5mm';
				_rfc.style.width = '205.9mm';
				_rfc.style.height = '3mm';
				_rfc.style.font = font;
				_rfc.style.textAlign = 'center';
				_rfc.style.color = '#555';
				//"Registro patronal"
				var _registro = document.createElement('span');
				container.appendChild(_registro);
				_registro.innerHTML = 'Registro patronal ' + registro_patronal;
				_registro.style.display = 'block';
				_registro.style.position = 'relative';
				_registro.style.padding = '0mm';
				_registro.style.margin = '0mm';
				_registro.style.border = 'none';
				_registro.style.background = '#fff';
				_registro.style.top = '10mm';
				_registro.style.left = '5mm';
				_registro.style.width = '205.9mm';
				_registro.style.height = '3mm';
				_registro.style.font = font;
				_registro.style.textAlign = 'center';
				_registro.style.color = '#555';
				//"trabajador"
				var _trabajador = document.createElement('span');
				container.appendChild(_trabajador);
				_trabajador.innerHTML = 'Trabajador: ' + trabajador_nombre;
				_trabajador.style.display = 'block';
				_trabajador.style.position = 'relative';
				_trabajador.style.padding = '0mm';
				_trabajador.style.margin = '0mm';
				_trabajador.style.border = 'none';
				_trabajador.style.background = '#fff';
				_trabajador.style.top = '10mm';
				_trabajador.style.left = '5mm';
				_trabajador.style.width = '205.9mm';
				_trabajador.style.height = '3mm';
				_trabajador.style.font = font;
				_trabajador.style.textAlign = 'right';
				_trabajador.style.color = '#555';
				//"No. de expediente"
				var _expediente = document.createElement('span');
				container.appendChild(_expediente);
				_expediente.innerHTML = 'No. de expediente: ' + expediente;
				_expediente.style.display = 'block';
				_expediente.style.position = 'relative';
				_expediente.style.padding = '0mm';
				_expediente.style.margin = '0mm';
				_expediente.style.border = 'none';
				_expediente.style.background = '#fff';
				_expediente.style.top = '10mm';
				_expediente.style.left = '5mm';
				_expediente.style.width = '205.9mm';
				_expediente.style.height = '3mm';
				_expediente.style.font = font;
				_expediente.style.textAlign = 'right';
				_expediente.style.color = '#555';
				//"No. de oficio"
				var _oficio = document.createElement('span');
				container.appendChild(_oficio);
				_oficio.innerHTML = 'No. de oficio: ' + oficio;
				_oficio.style.display = 'block';
				_oficio.style.position = 'relative';
				_oficio.style.padding = '0mm';
				_oficio.style.margin = '0mm';
				_oficio.style.border = 'none';
				_oficio.style.background = '#fff';
				_oficio.style.top = '10mm';
				_oficio.style.left = '5mm';
				_oficio.style.width = '205.9mm';
				_oficio.style.height = '3mm';
				_oficio.style.font = font;
				_oficio.style.textAlign = 'right';
				_oficio.style.color = '#555';
				//"Porcentaje del salario"
				var _porcentaje = document.createElement('span');
				container.appendChild(_porcentaje);
				_porcentaje.innerHTML = 'Porcentaje del salario: ' + porcentaje + '%';
				_porcentaje.style.display = 'block';
				_porcentaje.style.position = 'relative';
				_porcentaje.style.padding = '0mm';
				_porcentaje.style.margin = '0mm';
				_porcentaje.style.border = 'none';
				_porcentaje.style.background = '#fff';
				_porcentaje.style.top = '10mm';
				_porcentaje.style.left = '5mm';
				_porcentaje.style.width = '205.9mm';
				_porcentaje.style.height = '3mm';
				_porcentaje.style.font = font;
				_porcentaje.style.textAlign = 'right';
				_porcentaje.style.color = '#555';
				//"Titulo"
				var _titulo = document.createElement('span');
				container.appendChild(_titulo);
				_titulo.innerHTML = 'RECIBO DE PAGO DE PENSIÓN ALIMENTICIA';
				_titulo.style.display = 'block';
				_titulo.style.position = 'relative';
				_titulo.style.padding = '0mm';
				_titulo.style.margin = '0mm';
				_titulo.style.border = 'none';
				_titulo.style.background = '#fff';
				_titulo.style.top = '10mm';
				_titulo.style.left = '5mm';
				_titulo.style.width = '205.9mm';
				_titulo.style.height = '3mm';
				_titulo.style.font = font;
				_titulo.style.textAlign = 'center';
				_titulo.style.color = '#555';
				//"limites"
				var _limites = document.createElement('span');
				container.appendChild(_limites);
				_limites.innerHTML = 'Del ' + limite_inferior + ' al ' + limite_superior;
				_limites.style.display = 'block';
				_limites.style.position = 'relative';
				_limites.style.padding = '0mm';
				_limites.style.margin = '0mm';
				_limites.style.border = 'none';
				_limites.style.background = '#fff';
				_limites.style.top = '10mm';
				_limites.style.left = '5mm';
				_limites.style.width = '205.9mm';
				_limites.style.height = '3mm';
				_limites.style.font = font;
				_limites.style.textAlign = 'center';
				_limites.style.color = '#555';
				//"texto"
				var _text = document.createElement('span');
				container.appendChild(_text);
				_text.innerHTML = 'Recibí de ' + administradora + ' la cantidad de $' + _format(cantidad) + ' ' + covertirNumLetras(cantidad) + ' por concepto de pensión alimenticia otorgada por el juzgado tercero de lo familiar.<br/ >Por lo que me identifico con credencial de elector folio número ' + folio;
				_text.style.display = 'block';
				_text.style.position = 'relative';
				_text.style.padding = '0mm';
				_text.style.margin = '0mm';
				_text.style.border = 'none';
				_text.style.background = '#fff';
				_text.style.top = '15mm';
				_text.style.left = '5mm';
				_text.style.width = '205.9mm';
				_text.style.height = '20mm';
				_text.style.font = font;
				_text.style.textAlign = 'justify';
				_text.style.color = '#555';
				var _beneficiario = document.createElement('div');
				_beneficiario.innerHTML = beneficiario;
				_beneficiario.style.display = 'block';
				_beneficiario.style.position = 'relative';
				_beneficiario.style.padding = '0mm';
				_beneficiario.style.margin = '0mm';
				_beneficiario.style.background = 'none';
				_beneficiario.style.font = font;
				_beneficiario.style.top = '67.7mm';
				_beneficiario.style.left = '0mm';
				_beneficiario.style.width = '215.9mm';
				_beneficiario.style.height = '8mm';
				_beneficiario.style.color = '#666';
				_beneficiario.style.textAlign = 'center';
				_beneficiario.style.borderBottom = '1px dotted #666';
				container.appendChild(_beneficiario);
			}

		</script>
	</head>

	<body>
		<script type='text/javascript'>window.opener._pensiones();</script>
	</body>
</html>
