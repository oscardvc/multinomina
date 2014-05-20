<?php //This page is called by a javascript function at aguinaldo.js?>
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
					else if(textareas[i].getAttribute('name') == 'Fecha_de_pago')
						var _fecha_de_pago = textareas[i].value;

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
						var administradora_rfc = divs[0].innerHTML;
						var registro_patronal = divs[1].innerHTML;

						if(tables.length == 0)
							tables = fieldset.getElementsByTagName('table');//if there are not 'sucursales' tables, the table is at the fieldset

						for(var i=0; i<tables.length; i++)
						{
							var rows = tables[i].rows;

							for(var j=2; j<rows.length; j++)
								set_recibo(_id, _administradora, _fecha_de_pago, _class, rows[0].cells[0].innerHTML, rows[1], rows[j], administradora_rfc, registro_patronal)

						}

					}

				}

				xmltables.open('POST','_get_tables_aguinaldo.php?_class=' + _class + '&aguinaldo=' + _id, true);
				xmltables.setRequestHeader("cache-control","no-cache");
				xmltables.send('');
			}

			function set_recibo(_id, administradora, fecha_de_pago, _class, sucursal, titles, row, administradora_rfc, registro_patronal)
			{
				var cols = row.getElementsByTagName('td');
				var numero = cols[0].innerHTML;
				var trabajador_nombre = cols[1].innerHTML;
				var trabajador_rfc = cols[2].innerHTML;
				var dias_de_aguinaldo = cols[3].innerHTML;
				var salario_diario = cols[4].innerHTML;

				if(_class == 'Aguinaldo_asalariados_fieldset')
					var cantidad = cols[10].innerHTML;

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
				{
//							container.style.background = '#ddd';
					var _break = true;
				}
				else
				{
//							container.style.background = '#aaa';
					var _break = false;
				}

				//"administradora" title
				var _administradora = document.createElement('span');
				container.appendChild(_administradora);

				if(_class == 'Aguinaldo_asalariados_fieldset')
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

				if(_class == 'Aguinaldo_asalariados_fieldset')
				{
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
				}

				//"No. de trabajador"
				var _numero = document.createElement('span');
				container.appendChild(_numero);
				_numero.innerHTML = 'Trabajador número: ' + numero;
				_numero.style.display = 'block';
				_numero.style.position = 'relative';
				_numero.style.padding = '0mm';
				_numero.style.margin = '0mm';
				_numero.style.border = 'none';
				_numero.style.background = '#fff';
				_numero.style.top = sucursal != 'Trabajadores asalariados' ? '10mm' : '13mm';
				_numero.style.left = '5mm';
				_numero.style.width = '205.9mm';
				_numero.style.height = '3mm';
				_numero.style.font = font;
				_numero.style.textAlign = 'right';
				_numero.style.color = '#555';

				if(_class == 'Aguinaldo_asalariados_fieldset')
				{

					if(sucursal != 'Trabajadores asalariados')
					{
						//"Sucursal"
						var _sucursal = document.createElement('span');
						_sucursal.innerHTML = 'Sucursal: ' + sucursal;
						container.appendChild(_sucursal);
						_sucursal.style.display = 'block';
						_sucursal.style.position = 'relative';
						_sucursal.style.padding = '0mm';
						_sucursal.style.margin = '0mm';
						_sucursal.style.border = 'none';
						_sucursal.style.background = '#fff';
						_sucursal.style.top = '10mm';
						_sucursal.style.left = '5mm';
						_sucursal.style.width = '205.9mm';
						_sucursal.style.height = '3mm';
						_sucursal.style.font = font;
						_sucursal.style.textAlign = 'right';
						_sucursal.style.color = '#555';
					}

					//"Titulo"
					var _titulo = document.createElement('span');
					container.appendChild(_titulo);
					_titulo.innerHTML = 'RECIBO DE PAGO DE AGUINALDO';
					_titulo.style.display = 'block';
					_titulo.style.position = 'relative';
					_titulo.style.padding = '0mm';
					_titulo.style.margin = '0mm';
					_titulo.style.border = 'none';
					_titulo.style.background = '#fff';
					_titulo.style.top = sucursal != 'Trabajadores asalariados' ? '10mm' : '13mm';
					_titulo.style.left = '5mm';
					_titulo.style.width = '205.9mm';
					_titulo.style.height = '3mm';
					_titulo.style.font = font;
					_titulo.style.textAlign = 'center';
					_titulo.style.color = '#555';
				}

				//"texto"
				var _text = document.createElement('span');
				container.appendChild(_text);

				if(_class == 'Aguinaldo_asalariados_fieldset')
					_text.innerHTML = 'Recibí de ' + administradora + ' la cantidad de $' + _format(cantidad) + ' ' + covertirNumLetras(cantidad ) + ' por concepto de aguinaldo correspondiente al ejercicio fiscal ' + fecha_de_pago.substr(0,4) + ', de conformidad con el artículo 87 de la ley federal de trabajdo.';

				_text.style.display = 'block';
				_text.style.position = 'relative';
				_text.style.padding = '0mm';
				_text.style.margin = '0mm';
				_text.style.border = 'none';
				_text.style.background = '#fff';
				_text.style.top = sucursal != 'Trabajadores asalariados' ? '15mm' : '18mm';
				_text.style.left = '5mm';
				_text.style.width = '205.9mm';
				_text.style.height = '10mm';
				_text.style.font = font;
				_text.style.textAlign = 'justify';
				_text.style.color = '#555';

				if(_class == 'Aguinaldo_asalariados_fieldset')
				{
					var datos_del_trabajador = document.createElement('table')
					var tr0 = document.createElement('tr');
					datos_del_trabajador.appendChild(tr0);
					var titulo = document.createElement('td');
					tr0.appendChild(titulo);
					titulo.innerHTML = 'Datos del trabajador';
					titulo.setAttribute('colspan',2);
					titulo.style.font = font;
					titulo.style.textAlign = 'center';
					titulo.style.background = '#eee';
					var tr1 = document.createElement('tr');
					datos_del_trabajador.appendChild(tr1);
					var _nombre = document.createElement('td');
					tr1.appendChild(_nombre);
					_nombre.innerHTML = 'Nombre';
					_nombre.style.textAlign = 'left';
					var nombre_ = document.createElement('td');
					tr1.appendChild(nombre_);
					nombre_.innerHTML = trabajador_nombre;
					nombre_.style.textAlign = 'left';
					var tr2 = document.createElement('tr');
					datos_del_trabajador.appendChild(tr2);
					var __rfc = document.createElement('td');
					tr2.appendChild(__rfc);
					__rfc.innerHTML = 'RFC';
					__rfc.style.textAlign = 'left';
					var rfc_ = document.createElement('td');
					tr2.appendChild(rfc_);
					rfc_.innerHTML = trabajador_rfc;
					rfc_.style.textAlign = 'left';
					var tr5 = document.createElement('tr');
					datos_del_trabajador.appendChild(tr5);
					var _salario = document.createElement('td');
					tr5.appendChild(_salario);
					_salario.innerHTML = 'Salario diario';
					_salario.style.textAlign = 'left';
					var salario_ = document.createElement('td');
					tr5.appendChild(salario_);
					salario_.innerHTML = '$' + salario_diario;
					salario_.style.textAlign = 'left';
					var tr6 = document.createElement('tr');
					datos_del_trabajador.appendChild(tr6);
					var _dias = document.createElement('td');
					tr6.appendChild(_dias);
					_dias.innerHTML = 'Dias de aguinaldo ';
					_dias.style.textAlign = 'left';
					var dias_ = document.createElement('td');
					tr6.appendChild(dias_);
					dias_.innerHTML = ' ' + dias_de_aguinaldo;
					dias_.style.textAlign = 'left';
					var tr7 = document.createElement('tr');
					datos_del_trabajador.appendChild(tr7);
					var _fecha = document.createElement('td');
					tr7.appendChild(_fecha);
					_fecha.innerHTML = 'Fecha de pago ';
					_fecha.style.textAlign = 'left';
					var fecha_ = document.createElement('td');
					tr7.appendChild(fecha_);
					fecha_.innerHTML = ' ' + fecha_de_pago;
					fecha_.style.textAlign = 'left';
					datos_del_trabajador.style.font = font;
					datos_del_trabajador.style.color = '#555';
					datos_del_trabajador.style.border = '1px solid #555';
					datos_del_trabajador.style.borderRadius = '10px';
					datos_del_trabajador.style.MozBorderRadius = '10px';
					datos_del_trabajador.style.WebkitBorderRadius = '10px';
					container.appendChild(datos_del_trabajador);
					datos_del_trabajador.style.display = 'block';
					datos_del_trabajador.style.position = 'relative';
					datos_del_trabajador.style.padding = '0mm';
					datos_del_trabajador.style.margin = '0mm';
					datos_del_trabajador.style.top = sucursal != 'Trabajadores asalariados' ? '20mm' : '23mm';
					datos_del_trabajador.style.left = '5mm';
					datos_del_trabajador.style.width = '80mm';
					datos_del_trabajador.style.height = '40mm';
					var aguinaldo = document.createElement('table');
					var tr8 = document.createElement('tr');
					aguinaldo.appendChild(tr8);
					var percepciones = document.createElement('td');
					tr8.appendChild(percepciones);
					percepciones.setAttribute('colspan','2');
					percepciones.style.background = '#eee';
					percepciones.innerHTML = 'Percepciones';
					percepciones.style.textAlign = 'center';
					var _titles = titles.getElementsByTagName('td');
					var i = 5;
					var _pesos = true;

					while(_titles[i].innerHTML != 'ISR')
					{

						if(cols[i].innerHTML != '0.00')
						{
							var tr = document.createElement('tr');
							aguinaldo.appendChild(tr);
							var _td = document.createElement('td');
							tr.appendChild(_td);
//							_td.style.background = '#eee';

							if(_pesos)
							{
								_td.innerHTML = _titles[i].innerHTML + '<span style = "float:right">$</span>';
								_pesos = false;
							}
							else
								_td.innerHTML = _titles[i].innerHTML == 'Total de percepciones' ? (_titles[i].innerHTML + '<span style = "float:right">$</span>') : _titles[i].innerHTML;

							var td_ = document.createElement('td');
							tr.appendChild(td_);
//							td_.style.background = '#eee';
							var val = total(cols[i].innerHTML);
							td_.innerHTML = _format(val);
							td_.style.textAlign = 'right';
							td_.style.borderTop = _titles[i].innerHTML == 'Total de percepciones' ? '1px solid #666' : 'none';
						}

						i++;
					}

					var deducciones = document.createElement('td');
					aguinaldo.rows[0].appendChild(deducciones);
					deducciones.setAttribute('colspan','2');
					deducciones.style.background = '#eee';
					deducciones.innerHTML = 'Deducciones';
					deducciones.style.textAlign = 'center';
					_pesos = true;
					j = 1;//row counter

					while(_titles[i].innerHTML != 'Saldo')
					{

						if(1)//(cols[i].innerHTML != '0.00' && cols[i].innerHTML != '0')
						{

							if(aguinaldo.rows[j])
							{
								var _td = document.createElement('td');

								if(_titles[i].innerHTML == 'Total de deducciones')
									aguinaldo.rows[aguinaldo.rows.length - 1].appendChild(_td);
								else
									aguinaldo.rows[j].appendChild(_td);

								if(_pesos)
								{
									_td.innerHTML = _titles[i].innerHTML + '<span style = "float:right">$</span>';
									_pesos = false;
								}
								else
									_td.innerHTML = _titles[i].innerHTML == 'Total de deducciones' ? (_titles[i].innerHTML + '<span style = "float:right">$</span>') : _titles[i].innerHTML;

								_td.style.textAlign = 'left';
								var td_ = document.createElement('td');

								if(_titles[i].innerHTML == 'Total de deducciones')
									aguinaldo.rows[aguinaldo.rows.length - 1].appendChild(td_);
								else
									aguinaldo.rows[j].appendChild(td_);

								var val = total(cols[i].innerHTML)
								td_.innerHTML = _format(val);
								td_.style.textAlign = 'right';
								td_.style.borderTop = _titles[i].innerHTML == 'Total de deducciones' ? '1px solid #666' : 'none';
							}
							else
							{
								var tr = document.createElement('tr');
								aguinaldo.appendChild(tr);
								var _td = document.createElement('td');
								tr.appendChild(_td);

								if(_titles[i].innerHTML == 'Total de deducciones')
								{
									var k = aguinaldo.rows.length - 1;

									while(aguinaldo.rows[k].cells[0].innerHTML == '')
										k--;

									_td.innerHTML = aguinaldo.rows[k].cells[0].innerHTML;
									aguinaldo.rows[k].cells[0].innerHTML = '';
								}

								var td_ = document.createElement('td');
								tr.appendChild(td_);

								if(_titles[i].innerHTML == 'Total de deducciones')
								{
									var k = aguinaldo.rows.length - 1;

									while(aguinaldo.rows[k].cells[1].innerHTML == '')
										k--;

									td_.innerHTML = aguinaldo.rows[k].cells[1].innerHTML;
									td_.style.borderTop = '1px solid #666';
									aguinaldo.rows[k].cells[1].innerHTML = '';
									aguinaldo.rows[k].cells[1].style.borderTop = 'none';
								}

								var __td = document.createElement('td');
								tr.appendChild(__td);

								if(_pesos)
								{
									__td.innerHTML = _titles[i].innerHTML + '<span style = "float:right">$</span>';
									_pesos = false;
								}
								else
									__td.innerHTML = _titles[i].innerHTML == 'Total de deducciones' ? (_titles[i].innerHTML + '<span style = "float:right">$</span>') : _titles[i].innerHTML;

								__td.style.textAlign = 'left';
								var td__ = document.createElement('td');
								tr.appendChild(td__);
								var val = total(cols[i].innerHTML)
								td__.innerHTML = _format(val);
								td__.style.textAlign = 'right';
								td__.style.borderTop = _titles[i].innerHTML == 'Total de deducciones' ? '1px solid #666' : 'none';
							}

							j++;
						}

						i++;
					}

					aguinaldo.appendChild(document.createElement('br'));
					var tr = document.createElement('tr');
					aguinaldo.appendChild(tr);
					var _td = document.createElement('td');
					tr.appendChild(_td);
					var td_ = document.createElement('td');
					tr.appendChild(td_);
					var __td = document.createElement('td');
					__td.innerHTML = 'Total a percibir';
					__td.style.textAlign = 'left';
					tr.appendChild(__td);
					var td__ = document.createElement('td');
					td__.innerHTML = '$' + _format(cols[i].innerHTML);
					td__.style.textAlign = 'right';
					tr.appendChild(td__);
					aguinaldo.style.font = font;
					aguinaldo.style.color = '#555';
					aguinaldo.style.border = '1px solid #555';
					aguinaldo.style.borderRadius = '10px';
					aguinaldo.style.MozBorderRadius = '10px';
					aguinaldo.style.WebkitBorderRadius = '10px';
					container.appendChild(aguinaldo);
					aguinaldo.style.display = 'block';
					aguinaldo.style.position = 'relative';
					aguinaldo.style.padding = '0mm';
					aguinaldo.style.margin = '0mm';
					aguinaldo.style.top = sucursal != 'Trabajadores asalariados' ? '-20mm' : '-17mm';
					aguinaldo.style.left = '90mm';
					aguinaldo.style.width = '120.9mm';
					aguinaldo.style.height = '40mm';
				}

				var _trabajador = document.createElement('div');
				_trabajador.innerHTML = trabajador_nombre;
				_trabajador.style.display = 'block';
				_trabajador.style.position = 'relative';
				_trabajador.style.padding = '0mm';
				_trabajador.style.margin = '0mm';
				_trabajador.style.background = 'none';
				_trabajador.style.font = font;
				_trabajador.style.top = sucursal != 'Trabajadores asalariados' ? '7mm' : '10mm';
				_trabajador.style.left = '0mm';
				_trabajador.style.width = '215.9mm';
				_trabajador.style.height = '8mm';
				_trabajador.style.color = '#666';
				_trabajador.style.textAlign = 'center';
				_trabajador.style.borderBottom = '1px dotted #666';
				container.appendChild(_trabajador);
			}

			function total(val)
			{
				var total = 0;
				values = val.split(',');

				for(var l=0; l<values.length; l++)
				{
					var data = values[l].split('</span>');

					if(data.length > 1)
						total += parseFloat(data[1].replace('<span>',''));
					else
					{

						if(data[0] != '')
							total += parseFloat(data[0].replace('<span>',''));

					}

				}

				return total.toFixed(2);
			}

		</script>
	</head>

	<body>
		<script type='text/javascript'>window.opener._recibos_aguinaldo();</script>
	</body>
</html>
