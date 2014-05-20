<?php //This page is called by a javascript function named view at nomina.js?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

	<head>
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
					else if(textareas[i].getAttribute('name') == 'Empresa')
						var _empresa = textareas[i].value;
					else if(textareas[i].getAttribute('name') == 'Expedidor')
						var _expedidor = textareas[i].value;
					else if(textareas[i].getAttribute('name') == 'Limite_inferior_del_periodo')
						var _limite_inferior = textareas[i].value;
					else if(textareas[i].getAttribute('name') == 'Limite_superior_del_periodo')
						var _limite_superior = textareas[i].value;

				if(_expedidor == 'Cliente')
					_administradora = _empresa;

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
						var fecha_de_pago = fdp(_limite_superior);

						if(tables.length == 0)
							tables = fieldset.getElementsByTagName('table');//if there are not 'sucursales' tables, the table is at the fieldset

						for(var i=0; i<tables.length; i++)
						{
							var rows = tables[i].rows;

							for(var j=2; j<rows.length; j++)

								if((rows[j].cells.length > 20 && parseFloat(rows[j].cells[34].innerHTML) > 0.00) || (rows[j].cells.length <= 20 && parseFloat(rows[j].cells[10].innerHTML) > 0.00))//if saldo > 0.00
								{
									set_recibo(_id, _administradora, _limite_inferior, _limite_superior, _class, rows[0].cells[0].innerHTML, rows[1], rows[j], administradora_rfc, registro_patronal, fecha_de_pago)
								}

						}

					}

				}

				xmltables.open('POST','_get_tables.php?nomina=' + _id + '&_class=' + _class, true);
				xmltables.setRequestHeader("cache-control","no-cache");
				xmltables.send('');
			}

			function set_recibo(_id, administradora, limite_inferior, limite_superior, _class, sucursal, titles, row, administradora_rfc, registro_patronal, fecha_de_pago)
			{
				var cols = row.getElementsByTagName('td');

				if(_class == 'Nomina_asalariados')
					var salario_diario = cols[6].innerHTML;

				var numero = cols[0].innerHTML;
				var trabajador_nombre = cols[1].innerHTML;
				var numero_imss = cols[2].innerHTML;
				var trabajador_rfc = _class == 'Nomina_asalariados' ? cols[4].innerHTML : cols[3].innerHTML;
				var cantidad = _class == 'Nomina_asalariados' ? cols[34].innerHTML : cols[10].innerHTML;
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
//					container.style.background = '#ddd';
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

				if(_class == 'Nomina_asalariados')
					_administradora.innerHTML = administradora;
				else
					_administradora.innerHTML = 'RECIBO POR PAGO DE HONORARIOS ASIMILADOS';

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

				if(_class == 'Nomina_asalariados')
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

				if(_class == 'Nomina_asimilables')
				{
					//"Fecha de pago"
					var _fecha = document.createElement('span');
					container.appendChild(_fecha);
					_fecha.innerHTML = 'Fecha de pago: ' + fecha_de_pago;
					_fecha.style.display = 'block';
					_fecha.style.position = 'relative';
					_fecha.style.padding = '0mm';
					_fecha.style.margin = '0mm';
					_fecha.style.border = 'none';
					_fecha.style.background = '#fff';
					_fecha.style.font = font;
					_fecha.style.textAlign = 'right';
					_fecha.style.color = '#555';
					_fecha.style.width = '205.9mm';
					_fecha.style.height = '3mm';
					_fecha.style.top = '10mm';
					_fecha.style.left = '5mm';
				}

				//"No. de trabajador"
				var _numero = document.createElement('span');
				container.appendChild(_numero);
				_numero.innerHTML = 'Número: ' + numero;
				_numero.style.display = 'block';
				_numero.style.position = 'relative';
				_numero.style.padding = '0mm';
				_numero.style.margin = '0mm';
				_numero.style.border = 'none';
				_numero.style.background = '#fff';

				if(_class == 'Nomina_asalariados')
					_numero.style.top = sucursal != 'Trabajadores asalariados' ? '10mm' : '13mm';
				else
					_numero.style.top = '10mm';

				_numero.style.left = '5mm';
				_numero.style.width = '205.9mm';
				_numero.style.height = '3mm';
				_numero.style.font = font;
				_numero.style.textAlign = 'right';
				_numero.style.color = '#555';

				if(_class == 'Nomina_asalariados')
				{

					if(sucursal != 'Trabajadores asalariados')
					{
						//"Sucursal"
						var _sucursal = document.createElement('span');
						container.appendChild(_sucursal);
						_sucursal.innerHTML = 'Sucursal: ' + sucursal;
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
					_titulo.innerHTML = 'RECIBO DE PAGO DE SUELDOS';
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
					_limites.style.top = sucursal != 'Trabajadores asalariados' ? '10mm' : '13mm';
					_limites.style.left = '5mm';
					_limites.style.width = '205.9mm';
					_limites.style.height = '3mm';
					_limites.style.font = font;
					_limites.style.textAlign = 'center';
					_limites.style.color = '#555';
				}

				//"texto"
				var _text = document.createElement('span');
				container.appendChild(_text);

				if(_class == 'Nomina_asalariados')
					_text.innerHTML = 'Recibí de ' + administradora + ' la cantidad de $' + _format(cantidad) + ' ' + covertirNumLetras(cantidad ) + ' por servicios prestados durante el periodo ' + limite_inferior + ' / ' + limite_superior + '. Además reconozco que no se me adeuda a la fecha cantidad alguna por tiempo extra, séptimos días o por cualquier otro concepto nacido de la ley que por derecho me corresponda.';
				else
					_text.innerHTML = 'Recibí de ' + administradora + ' la cantidad de $' + _format(cantidad) + ' ' + covertirNumLetras(cantidad ) + ' importe neto, es decir, después de la retención por concepto de pago de servicios profesionales y/o técnicos brindados a la empresa durante el periodo ' + limite_inferior + ' / ' + limite_superior + ' en los términos del artículo 94 fracción IV de la Ley de impuesto sobre la renta, por la prestación de servicios personales independientes.';

				_text.style.display = 'block';
				_text.style.position = 'relative';
				_text.style.padding = '0mm';
				_text.style.margin = '0mm';
				_text.style.border = 'none';
				_text.style.background = '#fff';

				if(_class == 'Nomina_asalariados')
					_text.style.top = sucursal != 'Trabajadores asalariados' ? '15mm' : '18mm';
				else
					_text.style.top = '15mm';

				_text.style.left = '5mm';
				_text.style.width = '205.9mm';
				_text.style.height = _class == 'Nomina_asalariados' ? '10mm' : '20mm';
				_text.style.font = font;
				_text.style.textAlign = 'justify';
				_text.style.color = '#555';


				if(_class == 'Nomina_asalariados')
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
					var tr3 = document.createElement('tr');
					datos_del_trabajador.appendChild(tr3);
					var _nss = document.createElement('td');
					tr3.appendChild(_nss);
					_nss.innerHTML = 'NSS';
					_nss.style.textAlign = 'left';
					var nss_ = document.createElement('td');
					tr3.appendChild(nss_);
					nss_.innerHTML = numero_imss;
					nss_.style.textAlign = 'left';
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
					_dias.innerHTML = 'Días laborados';
					_dias.style.textAlign = 'left';
					var dias_ = document.createElement('td');
					tr6.appendChild(dias_);
					dias_.innerHTML = cols[5].innerHTML;
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
					datos_del_trabajador.style.height = '50mm';
					var nomina = document.createElement('table');
					var tr8 = document.createElement('tr');
					nomina.appendChild(tr8);
					var percepciones = document.createElement('td');
					tr8.appendChild(percepciones);
					percepciones.setAttribute('colspan','2');
					percepciones.style.background = '#eee';
					percepciones.innerHTML = 'Percepciones';
					percepciones.style.textAlign = 'center';
					var _titles = titles.getElementsByTagName('td');
					var i = 7;
					var _pesos = true;

					while(_titles[i].innerHTML != 'ISR')
					{

						if(cols[i].innerHTML != '0.00' && cols[i].innerHTML != '0' && cols[i].innerHTML != '')
						{
							var tr = document.createElement('tr');
							nomina.appendChild(tr);
							var _td = document.createElement('td');
							tr.appendChild(_td);
//								_td.style.background = '#eee';

							if(_pesos)
							{
								_td.innerHTML = _titles[i].innerHTML + '<span style = "float:right">$</span>';
								_pesos = false;
							}
							else
								_td.innerHTML = _titles[i].innerHTML == 'Total de percepciones' ? (_titles[i].innerHTML + '<span style = "float:right">$</span>') : _titles[i].innerHTML;

							var td_ = document.createElement('td');
							tr.appendChild(td_);
//								td_.style.background = '#eee';
							var val = total(cols[i].innerHTML);
							td_.innerHTML = _format(val);
							td_.style.textAlign = 'right';
							td_.style.borderTop = _titles[i].innerHTML == 'Total de percepciones' ? '1px solid #666' : 'none';
						}

						i++;
					}

					var deducciones = document.createElement('td');
					nomina.rows[0].appendChild(deducciones);
					deducciones.setAttribute('colspan','2');
					deducciones.style.background = '#eee';
					deducciones.innerHTML = 'Deducciones';
					deducciones.style.textAlign = 'center';
					_pesos = true;
					j = 1;//row counter

					while(_titles[i].innerHTML != 'Saldo')
					{

						if(cols[i].innerHTML != '0.00' && cols[i].innerHTML != '0' && cols[i].innerHTML != '')
						{

							if(nomina.rows[j])
							{
								var _td = document.createElement('td');

								if(_titles[i].innerHTML == 'Total de deducciones')
									nomina.rows[nomina.rows.length - 1].appendChild(_td);
								else
									nomina.rows[j].appendChild(_td);

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
									nomina.rows[nomina.rows.length - 1].appendChild(td_);
								else
									nomina.rows[j].appendChild(td_);

								var val = total(cols[i].innerHTML)
								td_.innerHTML = _format(val);
								td_.style.textAlign = 'right';
								td_.style.borderTop = _titles[i].innerHTML == 'Total de deducciones' ? '1px solid #666' : 'none';
							}
							else
							{
								var tr = document.createElement('tr');
								nomina.appendChild(tr);
								var _td = document.createElement('td');
								tr.appendChild(_td);

								if(_titles[i].innerHTML == 'Total de deducciones')
								{
									var k = nomina.rows.length - 1;

									while(nomina.rows[k].cells[0].innerHTML == '')
										k--;

									_td.innerHTML = nomina.rows[k].cells[0].innerHTML;
									nomina.rows[k].cells[0].innerHTML = '';
								}

								var td_ = document.createElement('td');
								tr.appendChild(td_);

								if(_titles[i].innerHTML == 'Total de deducciones')
								{
									var k = nomina.rows.length - 1;

									while(nomina.rows[k].cells[1].innerHTML == '')
										k--;

									td_.innerHTML = nomina.rows[k].cells[1].innerHTML;
									td_.style.borderTop = '1px solid #666';
									nomina.rows[k].cells[1].innerHTML = '';
									nomina.rows[k].cells[1].style.borderTop = 'none';
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

					nomina.appendChild(document.createElement('br'));
					var tr = document.createElement('tr');
					nomina.appendChild(tr);
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
					nomina.style.font = font;
					nomina.style.color = '#555';
					nomina.style.border = '1px solid #555';
					nomina.style.borderRadius = '10px';
					nomina.style.MozBorderRadius = '10px';
					nomina.style.WebkitBorderRadius = '10px';
					container.appendChild(nomina);
					nomina.style.display = 'block';
					nomina.style.position = 'relative';
					nomina.style.padding = '0mm';
					nomina.style.margin = '0mm';
					nomina.style.top = sucursal != 'Trabajadores asalariados' ? '-30mm' : '-27mm';
					nomina.style.left = '90mm';
					nomina.style.width = '120.9mm';
					nomina.style.height = '50mm';
				}
				else
				{
/*					var nomina = document.createElement('table');
					var tr1 = document.createElement('tr');
					nomina.appendChild(tr1);
					var percepciones = document.createElement('td');
					tr1.appendChild(percepciones);
					percepciones.style.background = '#eee';
					percepciones.innerHTML = 'Honorarios por servicios técnicos';
					percepciones.style.textAlign = 'center';
					var tr2 = document.createElement('tr');
					nomina.appendChild(tr2);
					var td_ = document.createElement('td');
					tr2.appendChild(td_);
					var val = total(cols[6].innerHTML);
					td_.innerHTML = '$' + _format(val);
					td_.style.textAlign = 'right';
					var deducciones = document.createElement('td');
					tr1.appendChild(deducciones);
					deducciones.style.background = '#eee';
					deducciones.innerHTML = 'ISR (a)';
					deducciones.style.textAlign = 'center';
					var td_ = document.createElement('td');
					tr2.appendChild(td_);
					var val = total(cols[8].innerHTML)
					td_.innerHTML = '$' + _format(val);
					td_.style.textAlign = 'right';
					nomina.appendChild(document.createElement('br'));
					var tr3 = document.createElement('tr');
					nomina.appendChild(tr3);
					var __td = document.createElement('td');
					__td.innerHTML = 'Total a percibir';
					__td.style.textAlign = 'left';
					tr3.appendChild(__td);
					var td__ = document.createElement('td');
					td__.innerHTML = '$' + _format(cols[9].innerHTML);
					td__.style.textAlign = 'right';
					tr3.appendChild(td__);
*/
					var nomina = document.createElement('table');
					var tr8 = document.createElement('tr');
					nomina.appendChild(tr8);
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

						if(cols[i].innerHTML != '0.00' && cols[i].innerHTML != '0' && cols[i].innerHTML != '')
						{
							var tr = document.createElement('tr');
							nomina.appendChild(tr);
							var _td = document.createElement('td');
							tr.appendChild(_td);

							if(_pesos)
							{
								_td.innerHTML = _titles[i].innerHTML + '<span style = "float:right">$</span>';
								_pesos = false;
							}
							else
								_td.innerHTML = _titles[i].innerHTML == 'Total de percepciones' ? (_titles[i].innerHTML + '<span style = "float:right">$</span>') : _titles[i].innerHTML;

							var td_ = document.createElement('td');
							tr.appendChild(td_);
//								td_.style.background = '#eee';
							var val = total(cols[i].innerHTML);
							td_.innerHTML = _format(val);
							td_.style.textAlign = 'right';
							td_.style.borderTop = _titles[i].innerHTML == 'Total de percepciones' ? '1px solid #666' : 'none';
						}

						i++;
					}

					var deducciones = document.createElement('td');
					nomina.rows[0].appendChild(deducciones);
					deducciones.setAttribute('colspan','2');
					deducciones.style.background = '#eee';
					deducciones.innerHTML = 'Deducciones';
					deducciones.style.textAlign = 'center';
					_pesos = true;
					j = 1;//row counter

					while(_titles[i].innerHTML != 'Saldo')
					{

						if(cols[i].innerHTML != '0.00' && cols[i].innerHTML != '0' && cols[i].innerHTML != '')
						{

							if(nomina.rows[j])
							{
								var _td = document.createElement('td');

								if(_titles[i].innerHTML == 'Total de deducciones')
									nomina.rows[nomina.rows.length - 1].appendChild(_td);
								else
									nomina.rows[j].appendChild(_td);

								if(_pesos)
								{
									_td.innerHTML = _titles[i].innerHTML + '(a)' + '<span style = "float:right">$</span>';
									_pesos = false;
								}
								else
									_td.innerHTML = _titles[i].innerHTML == 'Total de deducciones' ? (_titles[i].innerHTML + '<span style = "float:right">$</span>') : _titles[i].innerHTML;

								_td.style.textAlign = 'left';
								var td_ = document.createElement('td');

								if(_titles[i].innerHTML == 'Total de deducciones')
									nomina.rows[nomina.rows.length - 1].appendChild(td_);
								else
									nomina.rows[j].appendChild(td_);

								var val = total(cols[i].innerHTML)
								td_.innerHTML = _format(val);
								td_.style.textAlign = 'right';
								td_.style.borderTop = _titles[i].innerHTML == 'Total de deducciones' ? '1px solid #666' : 'none';
							}
							else
							{
								var tr = document.createElement('tr');
								nomina.appendChild(tr);
								var _td = document.createElement('td');
								tr.appendChild(_td);

								if(_titles[i].innerHTML == 'Total de deducciones')
								{
									var k = nomina.rows.length - 1;

									while(nomina.rows[k].cells[0].innerHTML == '')
										k--;

									_td.innerHTML = nomina.rows[k].cells[0].innerHTML;
									nomina.rows[k].cells[0].innerHTML = '';
								}

								var td_ = document.createElement('td');
								tr.appendChild(td_);

								if(_titles[i].innerHTML == 'Total de deducciones')
								{
									var k = nomina.rows.length - 1;

									while(nomina.rows[k].cells[1].innerHTML == '')
										k--;

									td_.innerHTML = nomina.rows[k].cells[1].innerHTML;
									td_.style.borderTop = '1px solid #666';
									nomina.rows[k].cells[1].innerHTML = '';
									nomina.rows[k].cells[1].style.borderTop = 'none';
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

					nomina.appendChild(document.createElement('br'));
					var tr = document.createElement('tr');
					nomina.appendChild(tr);
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
					nomina.style.display = 'block';
					nomina.style.position = 'relative';
					nomina.style.padding = '0mm';
					nomina.style.margin = '0mm';
					nomina.style.font = font;
					nomina.style.color = '#555';
					nomina.style.border = '1px solid #555';
					nomina.style.background = '#fff';
					nomina.style.borderRadius = '10px';
					nomina.style.MozBorderRadius = '10px';
					nomina.style.WebkitBorderRadius = '10px';
					nomina.style.width = '100mm';
					nomina.style.height = '30mm';
					nomina.style.top = '20mm';
					nomina.style.left = '5mm';
					container.appendChild(nomina);
				}

				if(_class == 'Nomina_asimilables')
				{
					//art 96
					var _art_96 = document.createElement('span');
					container.appendChild(_art_96);
					_art_96.innerHTML = '(a) Cálculo de impuesto sobre la renta retenido.<br/ >Retención de ISR de acuerdo al Art. 96';
					_art_96.style.display = 'block';
					_art_96.style.position = 'relative';
					_art_96.style.padding = '0mm';
					_art_96.style.margin = '0mm';
					_art_96.style.border = 'none';
					_art_96.style.background = '#fff';
					_art_96.style.width = '80mm';
					_art_96.style.height = '6mm';
					_art_96.style.top = '25mm';
					_art_96.style.left = '5mm';
					_art_96.style.font = font;
					_art_96.style.textAlign = 'left';
					_art_96.style.color = '#555';
				}

				var _trabajador = document.createElement('span');
				_trabajador.innerHTML = trabajador_nombre;
				_trabajador.style.display = 'block';
				_trabajador.style.position = 'relative';
				_trabajador.style.padding = '0mm';
				_trabajador.style.margin = '0mm';
				_trabajador.style.background = 'none';
				_trabajador.style.font = font;

				if(_class == 'Nomina_asalariados')
					_trabajador.style.top = sucursal != 'Trabajadores asalariados' ? '-16mm' : '-13mm';
				else
					_trabajador.style.top = '50mm';

				_trabajador.style.left = '0mm';
				_trabajador.style.width = '215.9mm';
				_trabajador.style.height = '8mm';
				_trabajador.style.color = '#666';
				_trabajador.style.textAlign = 'center';
				_trabajador.style.borderBottom = '1px dotted #666';
				container.appendChild(_trabajador);
			}

			function fdp(limite_superior)
			{
				var fecha_de_pago = limite_superior;
				var year = limite_superior.substring(0,4);
				var month = limite_superior.substring(5,8);
				var day = limite_superior.substring(8);
				var _day = parseInt(day,10);
				var _cal = new Calendar(parseInt(month,10) - 1,parseInt(year,10),_day);
				_cal.generateHTML();
				var _div = document.createElement('div');
				_div.innerHTML = _cal.getHTML();
				var tables = _div.getElementsByTagName('table');
				var table = tables[0];
				var n = 0;

				dance:
				for(var i=1; i<table.rows.length; i++)//i=0 : year row

					for(var j=0; j<table.rows[i].cells.length; j++)

						if(parseInt(table.rows[i].cells[j].innerHTML) == _day)
						{
							n = j;
							break dance;
						}

				if(table.rows[1].cells[n].innerHTML == 'D')//D:Domingo
					fecha_de_pago = _cal.decDay();

				return fecha_de_pago;
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
		<script type='text/javascript'>window.opener._recibos();</script>
	</body>
</html>
