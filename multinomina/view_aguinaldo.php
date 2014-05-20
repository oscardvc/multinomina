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
		<script type = "text/javascript" src = "resumen_aguinaldo.js"></script>
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

			var font = 'normal normal normal 12px Arial , sans-serif'; //weight, style, variant, size, family name, generic family
			var title_font = 'bold normal normal 12px Arial , sans-serif';

			function _load(obj)
			{
				var fieldset = obj.parentNode;
				var _class = fieldset.getAttribute('class');
				var form = fieldset.parentNode;
				var empresa_col = document.createElement('td');
				var administradora_col = document.createElement('td');
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
					else if(textareas[i].getAttribute('name') == 'Fecha_de_pago')
						var _fecha_de_pago = textareas[i].value;
					else if(textareas[i].getAttribute('name') == 'Resumen_aguinaldo')
						var _resumen_aguinaldo = textareas[i].value;

				administradora_col.innerHTML = _administradora;

				if(_class == 'ISRaguinaldo_fieldset')
				{
					empresa_col.innerHTML = _administradora == _empresa ? ('Calculo del ISR para el aguinaldo') : ('Calculo del ISR para el aguinaldo del personal asignado a la empresa ' + _empresa);
					empresa_col.setAttribute('colspan',15);
					administradora_col.setAttribute('colspan',15);
				}
				else if(_class == 'Aguinaldo_asalariados_fieldset')
				{
					empresa_col.innerHTML = _administradora == _empresa ? ('Reporte de aguinaldo') : ('Reporte de aguinaldo del personal asignado a la empresa ' + _empresa);
					empresa_col.setAttribute('colspan',34);
					administradora_col.setAttribute('colspan',34);
				}
				else if(_class == 'Datos_fieldset')
				{
					empresa_col.innerHTML = _administradora == _empresa ? ('Resumen de aguinaldo') : ('Resumen de aguinaldo del personal asignado a la empresa ' + _empresa);
				}

				var administradora_row = document.createElement('tr');
				var empresa_row = document.createElement('tr');
				administradora_row.appendChild(administradora_col);
				empresa_row.appendChild(empresa_col);
				administradora_col.style.textAlign = 'center';
				administradora_col.style.font = title_font;
				administradora_col.style.background = 'none';
				administradora_col.style.color = '#555';
				empresa_col.style.font = title_font;
				empresa_col.style.background = 'none';
				empresa_col.style.color = '#555';
				empresa_col.style.textAlign = 'center';

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

						if(_class != 'Datos_fieldset')
						{
							var _div = document.createElement('div');
							_div.innerHTML = xmltables.responseText;
							var tables = _div.getElementsByTagName('table');

							if(tables.length == 0)
								tables = fieldset.getElementsByTagName('table');//if there are not 'sucursales' tables, the table is at the fieldset

							for(var i=0; i<tables.length; i++)
							{
								var image = document.createElement('img');
								document.body.appendChild(image);
								image.src = 'images/logo_blanco.jpg';
								var table = document.createElement('table');
								var _administradora_row = administradora_row.cloneNode(true);
								var _empresa_row = empresa_row.cloneNode(true);
								table.appendChild(_administradora_row);
								table.appendChild(_empresa_row);
								table.appendChild(document.createElement('br'));
								var rows = tables[i].rows;

								for(var j=0; j<rows.length; j++)
								{
									var row = document.createElement('tr');
									row.style.textAlign = 'right';
									var cols = rows[j].getElementsByTagName('td');

									for(var k=0; k<cols.length; k++)
									{

										if(_class == 'ISRaguinaldo_fieldset' && (k == 0 || k == 1 || k == 2 || k > 4))
										{
											var col = document.createElement('td');

											if(j == 0)
											{
												col.setAttribute('colspan',15);
												col.style.font = title_font;
												col.style.textAlign = 'center';
											}
											else if(j == 1)
											{
												col.style.font = title_font;
												col.style.textAlign = 'center';
											}
											else
											{
												col.style.font = font;
												col.style.textAlign = 'right';
											}

											if((k == 0 || k == 1 || k == 2) && j > 1)
											{
												col.innerHTML = cols[k].innerHTML;
												col.style.textAlign = 'left';
												col.style.whiteSpace = 'nowrap';
											}
											else if(j > 3)
											{
												var val = parseFloat(cols[k].innerHTML);
												col.innerHTML = val.toFixed(2);
											}
											else
												col.innerHTML = cols[k].innerHTML;

											col.style.padding = '2px';
											row.appendChild(col);

											if(j % 2 == 0)
												col.style.background = '#eee';

											if(j == 1)
												var _titles = row;

										}
										else if(_class == 'Aguinaldo_asalariados_fieldset' && k != 11)
										{
											var col = document.createElement('td');

											if(j == 0)
											{
												col.setAttribute('colspan',35);
												col.style.font = title_font;
												col.style.textAlign = 'center';
											}
											else if(j == 1)
											{
												col.style.font = title_font;
												col.style.textAlign = 'center';
											}
											else
											{
												col.style.font = font;
												col.style.textAlign = 'right';
											}

											if((k == 0 || k == 1 || k == 2) && j > 1)
											{
												col.innerHTML = cols[k].innerHTML;
												col.style.textAlign = 'left';
												col.style.whiteSpace = 'nowrap';
											}
											else if(j > 1)
											{
												var total = 0;
												values = cols[k].innerHTML.split(',');

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

												if(k != 1)
													col.innerHTML = total.toFixed(2);
												else
													col.innerHTML = total;

											}
											else
											{
												if(cols[k].innerHTML == 'Saldo')
													col.innerHTML = 'Total';
												else
													col.innerHTML = cols[k].innerHTML;

											}

											col.style.padding = '2px';
											row.appendChild(col);

											if(j % 2 == 0)
												col.style.background = '#eee';

											if(j == 1)
												var _titles = row;

										}

									}

									table.appendChild(row);
								}

								table.rows[2].cells[0].style.background = '#555';
								table.rows[2].cells[0].style.color = '#fff';
								table.rows[2].cells[0].style.MozBorderRadiusTopleft = '10px';
								table.rows[2].cells[0].style.MozBorderRadiusTopright = '10px';
								table.rows[2].cells[0].style.WebkitBorderTopLeftRadius = '10px';
								table.rows[2].cells[0].style.WebkitBorderTopRightRadius = '10px';
								table.rows[2].cells[0].style.borderTopLeftRadius = '10px';
								table.rows[2].cells[0].style.borderTopRightRadius = '10px';

								for(var j=0; j<table.rows[3].cells.length; j++)
								{
									table.rows[3].cells[j].style.background = '#3399cc';
									table.rows[3].cells[j].style.color = '#fff';
								}

								document.body.appendChild(table);
								var _br = document.createElement('br');
								_br.setAttribute('class','saltopagina');
								document.body.appendChild(_br);
								table.style.color = '#555';
								//totals
								var _tr = document.createElement('tr');
								var _td = document.createElement('td');
								_tr.appendChild(_td);
								_td.innerHTML = 'Total';
								_td.style.textAlign = 'right';
								var j=0;

								if(_class == 'ISRaguinaldo_fieldset')
									j = 7;
								else if(_class == 'Aguinaldo_asalariados_fieldset')
									j = 5;

								for(var k=1; k<table.rows[3].cells.length; k++)
								{
									var total = 0;
									var _td = document.createElement('td');
									_tr.appendChild(_td);
									_td.style.textAlign = 'right';

									for(var l=4; l<table.rows.length; l++)

										if(k >= j)
											total += parseFloat(table.rows[l].cells[k].innerHTML);

									if(k >= j)
									{
										_td.innerHTML = total.toFixed(2);
										_td.style.borderTop = '1px solid #555';
									}

								}

								if(i == 0)
									var totals = document.createElement('table');

								var _total = document.createElement('tr');
								totals.appendChild(_total);

								for(var k=0; k<_tr.cells.length; k++)
								{
									var _td = document.createElement('td');
									_total.appendChild(_td);
									_td.innerHTML = _tr.cells[k].innerHTML;
								}

								_tr.style.font = title_font;
								table.appendChild(_tr);

								if(i == tables.length - 1)
								{
									var _tr = document.createElement('tr');
									var _td = document.createElement('td');
									_tr.appendChild(_td);
									_td.innerHTML = 'Total';
									_td.style.textAlign = 'right';

									for(var k=1; k<totals.rows[0].cells.length; k++)
									{
										var total = 0;
										var _td = document.createElement('td');
										_tr.appendChild(_td);
										_td.style.textAlign = 'right';

										for(var l=0; l<totals.rows.length; l++)

											if(k >= j)
												total += parseFloat(totals.rows[l].cells[k].innerHTML);

										if(k >= j)
										{
											_td.innerHTML = total.toFixed(2);
											_td.style.borderTop = '4px double #555';
											_td.style.borderBottom = '4px double #555';
										}

									}

									_tr.style.font = title_font;
									_tr.style.background = '#eee';
									table.appendChild(document.createElement('br'));
									table.appendChild(document.createElement('br'));
									table.appendChild(_tr);
								}

								//reinserting titles
								if(table.rows.length > 40)
								{
									var _row = document.createElement('tr');

									if(i==0)
										_row.innerHTML = table.rows[3].innerHTML;
									else
										_row.innerHTML = table.rows[1].innerHTML;

									_row.style.background = '#3399cc';
									table.insertBefore(_row,table.rows[41]);
								}

								//thousands separator
								for(var j=0; j<table.rows.length; j++)
								{

									for(var k=1; k<table.rows[j].cells.length; k++)
										table.rows[j].cells[k].innerHTML = _format(table.rows[j].cells[k].innerHTML);

								}

							}

						}
						else
						{
							var image = document.createElement('img');
							document.body.appendChild(image);
							image.src = 'images/logo_blanco.jpg';
							var _br = document.createElement('br');
							document.body.appendChild(_br);
							var _br = document.createElement('br');
							document.body.appendChild(_br);
							var sucursales = xmltables.responseText.split(',');
							var tabla = document.createElement('table');
							empresa_col.setAttribute('colspan',sucursales.length + 2);
							administradora_col.setAttribute('colspan',sucursales.length + 2);
							tabla.appendChild(administradora_row);
							tabla.appendChild(empresa_row);
							tabla.appendChild(document.createElement('br'));
							//titulos
							var tr1 = document.createElement('tr');
							tabla.appendChild(tr1);
							var _conceptos = document.createElement('td');
							tr1.appendChild(_conceptos);
							_conceptos.setAttribute('rowspan',2);
							_conceptos.innerHTML = 'Aguinaldo';
							var _asalariados = document.createElement('td');
							tr1.appendChild(_asalariados);
							_asalariados.setAttribute('colspan',sucursales.length);
							_asalariados.setAttribute('class','column_title');
							_asalariados.setAttribute('onclick','_del(this)');
							_asalariados.setAttribute('onmouseover','_marck(this)');
							_asalariados.setAttribute('onmouseout','_unmarck(this)');
							_asalariados.style.cursor = 'pointer';
							_asalariados.innerHTML = 'Asalariados';
							var _total_asalariados = document.createElement('td');
							tr1.appendChild(_total_asalariados);
							_total_asalariados.setAttribute('rowspan',2);
							_total_asalariados.setAttribute('onclick','_del(this)');
							_total_asalariados.setAttribute('onmouseover','_marck(this)');
							_total_asalariados.setAttribute('onmouseout','_unmarck(this)');
							_total_asalariados.style.cursor = 'pointer';
							_total_asalariados.innerHTML = 'Total asalariados';
							tr1.setAttribute('class','titles_row');
							tr1.style.font = title_font;
							tr1.style.color = '#fff';
							tr1.style.background = '#555';
							tr1.style.textAlign = 'center';
							var tr2 = document.createElement('tr');
							tabla.appendChild(tr2);

							for(var i=0; i<sucursales.length; i++)
							{
								var _sucursal = document.createElement('td');
								tr2.appendChild(_sucursal);
								_sucursal.innerHTML = sucursales[i];
								_sucursal.setAttribute('onclick','_del(this)');
								_sucursal.setAttribute('owner','Asalariados');
								_sucursal.setAttribute('onmouseover','_marck(this)');
								_sucursal.setAttribute('onmouseout','_unmarck(this)');
								_sucursal.style.cursor = 'pointer';
							}

							tr2.style.color = '#fff';
							tr2.style.background = '#3399cc';
							tr2.style.textAlign = 'center';
							tr2.setAttribute('class','sucursales_row');
							var _data = _resumen_aguinaldo.split(',');
							//aguinaldo ordinario
							var tr3 = document.createElement('tr');
							tabla.appendChild(tr3);
							var _aguinaldo_ordinario = document.createElement('td');
							_aguinaldo_ordinario.style.textAlign = 'left';
							tr3.appendChild(_aguinaldo_ordinario);
							_aguinaldo_ordinario.innerHTML = 'Aguinaldo ordinario';
							var totales_aguinaldo_ordinario_asalariados = _data[0].split('/');
							var total_aguinaldo_ordinario_asalariados = 0;

							for(var i=0; i<totales_aguinaldo_ordinario_asalariados.length; i++)
							{
								var _aguinaldo_ordinario_asalariados = document.createElement('td');
								tr3.appendChild(_aguinaldo_ordinario_asalariados);
								_aguinaldo_ordinario_asalariados.innerHTML = totales_aguinaldo_ordinario_asalariados[i];
								total_aguinaldo_ordinario_asalariados += parseFloat(totales_aguinaldo_ordinario_asalariados[i]);
							}

							var _total_aguinaldo_ordinario_asalariados = document.createElement('td');
							tr3.appendChild(_total_aguinaldo_ordinario_asalariados);
							_total_aguinaldo_ordinario_asalariados.innerHTML = total_aguinaldo_ordinario_asalariados.toFixed(2);
							//gratificacion adicional
							var tr4 = document.createElement('tr');
							tabla.appendChild(tr4);
							var _gratificacion_adicional = document.createElement('td');
							_gratificacion_adicional.style.textAlign = 'left';
							tr4.appendChild(_gratificacion_adicional);
							_gratificacion_adicional.innerHTML = 'Gratificación adicional';
							var totales_gratificacion_adicional_asalariados = _data[1].split('/');
							var total_gratificacion_adicional_asalariados = 0;

							for(var i=0; i<totales_gratificacion_adicional_asalariados.length; i++)
							{
								var _gratificacion_adicional_asalariados = document.createElement('td');
								tr4.appendChild(_gratificacion_adicional_asalariados);
								_gratificacion_adicional_asalariados.innerHTML = totales_gratificacion_adicional_asalariados[i];
								total_gratificacion_adicional_asalariados += parseFloat(totales_gratificacion_adicional_asalariados[i]);
							}

							var _total_gratificacion_adicional_asalariados = document.createElement('td');
							tr4.appendChild(_total_gratificacion_adicional_asalariados);
							_total_gratificacion_adicional_asalariados.innerHTML = total_gratificacion_adicional_asalariados.toFixed(2);
							//Total de percepciones
							var tr5 = document.createElement('tr');
							tabla.appendChild(tr5);
							var _total_de_percepciones = document.createElement('td');
							_total_de_percepciones.style.textAlign = 'left';
							tr5.appendChild(_total_de_percepciones);
							_total_de_percepciones.innerHTML = 'Total de percepciones';
							var totales_de_percepciones_asalariados = _data[2].split('/');
							var total_de_percepciones_asalariados = 0;

							for(var i=0; i<totales_de_percepciones_asalariados.length; i++)
							{
								var _total_de_percepciones_asalariados = document.createElement('td');
								tr5.appendChild(_total_de_percepciones_asalariados);
								_total_de_percepciones_asalariados.innerHTML = totales_de_percepciones_asalariados[i];
								_total_de_percepciones_asalariados.style.borderTop = '1px solid #555';
								total_de_percepciones_asalariados += parseFloat(totales_de_percepciones_asalariados[i]);
							}

							var _total_de_percepciones_asalariados = document.createElement('td');
							tr5.appendChild(_total_de_percepciones_asalariados);
							_total_de_percepciones_asalariados.innerHTML = total_de_percepciones_asalariados.toFixed(2);
							_total_de_percepciones_asalariados.style.borderTop = '1px solid #555';
							tabla.appendChild(document.createElement('br'));
							//ISR
							var tr6 = document.createElement('tr');
							tabla.appendChild(tr6);
							var _isr = document.createElement('td');
							_isr.style.textAlign = 'left';
							tr6.appendChild(_isr);
							_isr.innerHTML = 'ISR';
							var _isrs_asalariados = _data[3].split('/');
							var _total_isrs_asalariados = 0;

							for(var i=0; i<_isrs_asalariados.length; i++)
							{
								var _isr_asalariados = document.createElement('td');
								tr6.appendChild(_isr_asalariados);
								_isr_asalariados.innerHTML = _isrs_asalariados[i];
								_total_isrs_asalariados += parseFloat(_isrs_asalariados[i]);
							}

							var _total_isr_asalariados = document.createElement('td');
							tr6.appendChild(_total_isr_asalariados);
							_total_isr_asalariados.innerHTML = _total_isrs_asalariados.toFixed(2);
							//Total de deducciones
							var tr7 = document.createElement('tr');
							tabla.appendChild(tr7);
							var _total_de_deducciones = document.createElement('td');
							_total_de_deducciones.style.textAlign = 'left';
							tr7.appendChild(_total_de_deducciones);
							_total_de_deducciones.innerHTML = 'Total de deducciones';
							var _totales_de_deducciones_asalariados = _data[3].split('/');
							var _total_totales_de_deducciones_asalariados = 0;

							for(var i=0; i<_totales_de_deducciones_asalariados.length; i++)
							{
								var _total_de_deducciones_asalariados = document.createElement('td');
								tr7.appendChild(_total_de_deducciones_asalariados);
								_total_de_deducciones_asalariados.innerHTML = _totales_de_deducciones_asalariados[i];
								_total_de_deducciones_asalariados.style.borderTop = '1px solid #555';
								_total_totales_de_deducciones_asalariados += parseFloat(_totales_de_deducciones_asalariados[i]);
							}

							var _total_total_de_deducciones_asalariados = document.createElement('td');
							tr7.appendChild(_total_total_de_deducciones_asalariados);
							_total_total_de_deducciones_asalariados.innerHTML = _total_totales_de_deducciones_asalariados.toFixed(2);
							_total_total_de_deducciones_asalariados.style.borderTop = '1px solid #555';
							//saldo
							var tr8 = document.createElement('tr');
							tabla.appendChild(tr8);
							var _saldo = document.createElement('td');
							_saldo.style.textAlign = 'left';
							tr8.appendChild(_saldo);
							_saldo.innerHTML = 'Total aguinaldo';
							var _saldos_asalariados = _data[4].split('/');
							var _total_saldos_asalariados = 0;

							for(var i=0; i<_saldos_asalariados.length; i++)
							{
								var _saldo_asalariados = document.createElement('td');
								tr8.appendChild(_saldo_asalariados);
								_saldo_asalariados.innerHTML = _saldos_asalariados[i];
								_total_saldos_asalariados += parseFloat(_saldos_asalariados[i]);
							}

							var _total_saldo_asalariados = document.createElement('td');
							tr8.appendChild(_total_saldo_asalariados);
							_total_saldo_asalariados.innerHTML = _total_saldos_asalariados.toFixed(2);
							tr8.style.background = '#ddd';
							tabla.appendChild(document.createElement('br'));

							//Retenciones
							var tr9 = document.createElement('tr');
							tabla.appendChild(tr9);
							var _impuestos = document.createElement('td');
							tr9.appendChild(_impuestos);
							_impuestos.innerHTML = 'Retenciones';
							_impuestos.setAttribute('rowspan',2);
							var _impuestos_asalariados = document.createElement('td');
							tr9.appendChild(_impuestos_asalariados);
							_impuestos_asalariados.setAttribute('colspan',sucursales.length);
							_impuestos_asalariados.setAttribute('class','column_title');
							_impuestos_asalariados.innerHTML = 'Asalariados';
							var _total_impuestos_asalariados = document.createElement('td');
							tr9.appendChild(_total_impuestos_asalariados);
							_total_impuestos_asalariados.innerHTML = 'Total asalariados';
							_total_impuestos_asalariados.setAttribute('rowspan',2);
							tr9.setAttribute('class','titles_row');
							tr9.style.font = title_font;
							tr9.style.background = '#555';
							tr9.style.color = '#fff';
							tr9.style.textAlign = 'center';
							var tr10 = document.createElement('tr');
							tabla.appendChild(tr10);

							for(var i=0; i<sucursales.length; i++)
							{
								var _sucursal = document.createElement('td');
								tr10.appendChild(_sucursal);
								_sucursal.innerHTML = sucursales[i];
							}

							tr10.style.color = '#fff';
							tr10.style.background = '#3399cc';
							tr10.style.textAlign = 'center';
							tr10.setAttribute('class','sucursales_row');
							//ISR
							var tr11 = document.createElement('tr');
							tabla.appendChild(tr11);
							var _isr_ = document.createElement('td');
							_isr_.style.textAlign = 'left';
							tr11.appendChild(_isr_);
							_isr_.innerHTML = 'ISR';
							var _contribuciones_isr_asalariados = _data[3].split('/');
							var _total_contribuciones_isr_asalariados = 0;

							for(var i=0; i<_contribuciones_isr_asalariados.length; i++)
							{
								var contribuciones_isr_asalariados = document.createElement('td');
								tr11.appendChild(contribuciones_isr_asalariados);
								contribuciones_isr_asalariados.innerHTML = _contribuciones_isr_asalariados[i];
								_total_contribuciones_isr_asalariados += parseFloat(_contribuciones_isr_asalariados[i]);
							}

							var total_contribuciones_isr_asalariados = document.createElement('td');
							tr11.appendChild(total_contribuciones_isr_asalariados);
							total_contribuciones_isr_asalariados.innerHTML = _total_contribuciones_isr_asalariados.toFixed(2);
							//Total retenciones
							var tr12 = document.createElement('tr');
							tabla.appendChild(tr12);
							var _total_impuestos = document.createElement('td');
							_total_impuestos.style.textAlign = 'left';
							tr12.appendChild(_total_impuestos);
							_total_impuestos.innerHTML = 'Total retenciones';
							var _totales_impuestos_asalariados = _data[3].split('/');
							var _total_totales_impuestos_asalariados = 0;

							for(var i=0; i<_totales_impuestos_asalariados.length; i++)
							{
								var _total_impuestos_asalariados = document.createElement('td');
								tr12.appendChild(_total_impuestos_asalariados);
								_total_impuestos_asalariados.innerHTML = _totales_impuestos_asalariados[i];
								_total_totales_impuestos_asalariados += parseFloat(_totales_impuestos_asalariados[i]);
							}

							var _total_total_impuestos_asalariados = document.createElement('td');
							tr12.appendChild(_total_total_impuestos_asalariados);
							_total_total_impuestos_asalariados.innerHTML = _total_totales_impuestos_asalariados.toFixed(2);
							tr12.style.background = '#ddd';
							tabla.appendChild(document.createElement('br'));
							//diferencias
							var tr13 = document.createElement('tr');
							tabla.appendChild(tr13);
							var _diferencias = document.createElement('td');
							tr13.appendChild(_diferencias);
							_diferencias.innerHTML = 'Diferencias';
							_diferencias.setAttribute('rowspan',2);
							var _diferencias_asalariados = document.createElement('td');
							tr13.appendChild(_diferencias_asalariados);
							_diferencias_asalariados.setAttribute('colspan',sucursales.length);
							_diferencias_asalariados.setAttribute('class','column_title');
							_diferencias_asalariados.innerHTML = 'Asalariados';
							var _total_diferencias_asalariados = document.createElement('td');
							tr13.appendChild(_total_diferencias_asalariados);
							_total_diferencias_asalariados.innerHTML = 'Total asalariados';
							_total_diferencias_asalariados.setAttribute('rowspan',2);
							tr13.setAttribute('class','titles_row');
							tr13.style.font = title_font;
							tr13.style.background = '#555';
							tr13.style.color = '#fff';
							tr13.style.textAlign = 'center';
							var tr14 = document.createElement('tr');
							tabla.appendChild(tr14);

							for(var i=0; i<sucursales.length; i++)
							{
								var _sucursal = document.createElement('td');
								tr14.appendChild(_sucursal);
								_sucursal.innerHTML = sucursales[i];
							}

							tr14.style.color = '#fff';
							tr14.style.background = '#3399cc';
							tr14.style.textAlign = 'center';
							tr14.setAttribute('class','sucursales_row');
							//Aguinaldo a pagar
							var _totales_aguinaldo_a_pagar = _data[4].split('/');
							var total_aguinaldo_a_pagar = 0;

							for(var i=0; i<_totales_aguinaldo_a_pagar.length; i++)
								total_aguinaldo_a_pagar += parseFloat(_totales_aguinaldo_a_pagar[i]);

							var tr16 = document.createElement('tr');
							tabla.appendChild(tr16);
							var _aguinaldo_a_pagar = document.createElement('td');
							_aguinaldo_a_pagar.style.textAlign = 'left';
							tr16.appendChild(_aguinaldo_a_pagar);
							_aguinaldo_a_pagar.innerHTML = 'Aguinaldo a pagar';

							for(var i=0; i<_totales_aguinaldo_a_pagar.length; i++)
							{
								var _aguinaldo_a_pagar_asalariados = document.createElement('td');
								tr16.appendChild(_aguinaldo_a_pagar_asalariados);
								_aguinaldo_a_pagar_asalariados.innerHTML = _totales_aguinaldo_a_pagar[i];
							}

							var total_aguinaldo_a_pagar_asalariados = document.createElement('td');
							tr16.appendChild(total_aguinaldo_a_pagar_asalariados);
							total_aguinaldo_a_pagar_asalariados.innerHTML = total_aguinaldo_a_pagar.toFixed(2);
							//Aguinaldo retenido
							var tr15 = document.createElement('tr');
							tabla.appendChild(tr15);
							var _aguinaldo_pagado = document.createElement('td');
							_aguinaldo_pagado.style.textAlign = 'left';
							tr15.appendChild(_aguinaldo_pagado);
							_aguinaldo_pagado.innerHTML = 'Aguinaldo retenido';
							var totales_aguinaldo_pagado = _data[5].split('/');
							var total_aguinaldo_pagado = 0;

							for(var i=0; i<totales_aguinaldo_pagado.length; i++)
							{
								var _aguinaldo_pagado_asalariados = document.createElement('td');
								tr15.appendChild(_aguinaldo_pagado_asalariados);
								_aguinaldo_pagado_asalariados.innerHTML = totales_aguinaldo_pagado[i];
								total_aguinaldo_pagado += parseFloat(totales_aguinaldo_pagado[i]);
							}

							var total_aguinaldo_pagado_asalariados = document.createElement('td');
							tr15.appendChild(total_aguinaldo_pagado_asalariados);
							total_aguinaldo_pagado_asalariados.innerHTML = total_aguinaldo_pagado.toFixed(2);
							//diferencia
							var totales_diferencia_asalariados = _data[6].split('/');
							var total_diferencia_asalariados = 0;

							for(var i=0; i<totales_diferencia_asalariados.length; i++)
								total_diferencia_asalariados += parseFloat(totales_diferencia_asalariados[i]);

							var tr17 = document.createElement('tr');
							tabla.appendChild(tr17);
							var _diferencia = document.createElement('td');
							_diferencia.style.textAlign = 'left';
							tr17.appendChild(_diferencia);
							_diferencia.innerHTML = 'Diferencia';

							for(var i=0; i<totales_diferencia_asalariados.length; i++)
							{
								var _diferencia_asalariados = document.createElement('td');
								tr17.appendChild(_diferencia_asalariados);
								_diferencia_asalariados.innerHTML = totales_diferencia_asalariados[i];
							}

							var _total_diferencia_asalariados = document.createElement('td');
							tr17.appendChild(_total_diferencia_asalariados);
							tr17.style.background = '#ddd';
							_total_diferencia_asalariados.innerHTML = total_diferencia_asalariados.toFixed(2);
							tabla.appendChild(document.createElement('br'));
							//integracion de la facturacion
							var tr54 = document.createElement('tr');
							tabla.appendChild(tr54);
							var _integracion = document.createElement('td');
							tr54.appendChild(_integracion);
							_integracion.innerHTML = 'Integración de la facturación';
							_integracion.setAttribute('rowspan',2);
							var _integracion_asalariados = document.createElement('td');
							tr54.appendChild(_integracion_asalariados);
							_integracion_asalariados.innerHTML = 'Asalariados';
							_integracion_asalariados.setAttribute('colspan',sucursales.length);
							_integracion_asalariados.setAttribute('class','column_title');
							var _total_integracion_asalariados = document.createElement('td');
							tr54.appendChild(_total_integracion_asalariados);
							_total_integracion_asalariados.innerHTML = 'Total asalariados';
							_total_integracion_asalariados.setAttribute('rowspan',2);
							tr54.setAttribute('class','titles_row');
							tr54.style.font = title_font;
							tr54.style.background = '#555';
							tr54.style.color = '#fff';
							tr54.style.font = title_font;
							tr54.style.textAlign = 'center';
							var tr55 = document.createElement('tr');
							tabla.appendChild(tr55);

							for(var i=0; i<sucursales.length; i++)
							{
								var _sucursal = document.createElement('td');
								tr55.appendChild(_sucursal);
								_sucursal.innerHTML = sucursales[i];
							}

							tr55.style.color = '#fff';
							tr55.style.background = '#3399cc';
							tr55.style.textAlign = 'center';
							tr55.setAttribute('class','sucursales_row');
							//Total retenciones
							var tr57 = document.createElement('tr');
							tabla.appendChild(tr57);
							var _total_impuestos_ = document.createElement('td');
							_total_impuestos_.style.textAlign = 'left';
							tr57.appendChild(_total_impuestos_);
							_total_impuestos_.innerHTML = 'Retenciones';

							for(var i=0; i<_totales_impuestos_asalariados.length; i++)
							{
								var _total_impuestos_asalariados_ = document.createElement('td');
								tr57.appendChild(_total_impuestos_asalariados_);
								_total_impuestos_asalariados_.innerHTML = _totales_impuestos_asalariados[i];
							}

							var _total_total_impuestos_asalariados_ = document.createElement('td');
							tr57.appendChild(_total_total_impuestos_asalariados_);
							_total_total_impuestos_asalariados_.innerHTML = _total_totales_impuestos_asalariados.toFixed(2);

							//Diferencias
							var tr58 = document.createElement('tr');
							tabla.appendChild(tr58);
							var _diferencias_ = document.createElement('td');
							_diferencias_.style.textAlign = 'left';
							tr58.appendChild(_diferencias_);
							_diferencias_.innerHTML = 'Diferencias';

							for(var i=0; i<totales_diferencia_asalariados.length; i++)
							{
								var _diferencias_asalariados_ = document.createElement('td');
								tr58.appendChild(_diferencias_asalariados_);
								_diferencias_asalariados_.innerHTML = totales_diferencia_asalariados[i];
							}

							var _total_diferencia_asalariados_ = document.createElement('td');
							tr58.appendChild(_total_diferencia_asalariados_);
							_total_diferencia_asalariados_.innerHTML = total_diferencia_asalariados.toFixed(2);

							//Honorarios
							var tr59 = document.createElement('tr');
							tabla.appendChild(tr59);
							var _honorarios_ = document.createElement('td');
							_honorarios_.style.textAlign = 'left';
							tr59.appendChild(_honorarios_);
							_honorarios_.innerHTML = 'Honorarios';
							var _totales_honorarios_asalariados = _data[7].split('/');
							var _total_totales_honorarios_asalariados = 0;

							for(var i=0; i<_totales_honorarios_asalariados.length; i++)
							{
								var _honorarios_asalariados_ = document.createElement('td');
								tr59.appendChild(_honorarios_asalariados_);
								_honorarios_asalariados_.innerHTML = _totales_honorarios_asalariados[i];
								_total_totales_honorarios_asalariados += parseFloat(_totales_honorarios_asalariados[i]);
							}

							var _total_honorarios_asalariados_ = document.createElement('td');
							tr59.appendChild(_total_honorarios_asalariados_);
							_total_honorarios_asalariados_.innerHTML = _total_totales_honorarios_asalariados.toFixed(2);

							//Subtotal a facturar
							var tr60 = document.createElement('tr');
							tabla.appendChild(tr60);
							var _subtotal_a_facturar = document.createElement('td');
							_subtotal_a_facturar.style.textAlign = 'left';
							tr60.appendChild(_subtotal_a_facturar);
							_subtotal_a_facturar.innerHTML = 'Subtotal a facturar';
							var _subtotales_a_facturar_asalariados = _data[8].split('/');
							var _total_subtotales_a_facturar_asalariados = 0;

							for(var i=0; i<_subtotales_a_facturar_asalariados.length; i++)
							{
								var _subtotal_a_facturar_asalariados = document.createElement('td');
								tr60.appendChild(_subtotal_a_facturar_asalariados);
								_subtotal_a_facturar_asalariados.innerHTML = _subtotales_a_facturar_asalariados[i];
								_subtotal_a_facturar_asalariados.style.borderTop = '1px solid #555';
								_total_subtotales_a_facturar_asalariados += parseFloat(_subtotales_a_facturar_asalariados[i]);
							}

							var _total_subtotal_a_facturar_asalariados = document.createElement('td');
							tr60.appendChild(_total_subtotal_a_facturar_asalariados);
							_total_subtotal_a_facturar_asalariados.innerHTML = _total_subtotales_a_facturar_asalariados.toFixed(2);
							_total_subtotal_a_facturar_asalariados.style.borderTop = '1px solid #555';

							//IVA
							var tr62 = document.createElement('tr');
							tabla.appendChild(tr62);
							var _iva = document.createElement('td');
							_iva.style.textAlign = 'left';
							tr62.appendChild(_iva);
							_iva.innerHTML = 'IVA';
							var _ivas_asalariados = _data[9].split('/');
							var _total_ivas_asalariados = 0;

							for(var i=0; i<_ivas_asalariados.length; i++)
							{
								var _iva_asalariados = document.createElement('td');
								tr62.appendChild(_iva_asalariados);
								_iva_asalariados.innerHTML = _ivas_asalariados[i];
								_total_ivas_asalariados += parseFloat(_ivas_asalariados[i]);
							}

							var _total_iva_asalariados = document.createElement('td');
							tr62.appendChild(_total_iva_asalariados);
							_total_iva_asalariados.innerHTML = _total_ivas_asalariados.toFixed(2);

							//Total a facturar
							var tr63 = document.createElement('tr');
							tabla.appendChild(tr63);
							var _total_a_facturar = document.createElement('td');
							_total_a_facturar.style.textAlign = 'left';
							tr63.appendChild(_total_a_facturar);
							_total_a_facturar.innerHTML = 'Total a facturar';
							var _totales_a_facturar_asalariados = _data[10].split('/');
							var _total_totales_a_facturar_asalariados = 0;

							for(var i=0; i<_totales_a_facturar_asalariados.length; i++)
							{
								var _total_a_facturar_asalariados = document.createElement('td');
								tr63.appendChild(_total_a_facturar_asalariados);
								_total_a_facturar_asalariados.innerHTML = _totales_a_facturar_asalariados[i];
								_total_totales_a_facturar_asalariados += parseFloat(_totales_a_facturar_asalariados[i]);
							}

							var _total_total_a_facturar_asalariados = document.createElement('td');
							tr63.appendChild(_total_total_a_facturar_asalariados);
							tr63.style.background = '#ddd';
							_total_total_a_facturar_asalariados.innerHTML = _total_totales_a_facturar_asalariados.toFixed(2);

							//thousands separator
							for(var j=0; j<tabla.rows.length; j++)
							{

								if(tabla.rows[j].getAttribute('class') != 'sucursales_row')

									for(var k=1; k<tabla.rows[j].cells.length; k++)
										tabla.rows[j].cells[k].innerHTML = _format(tabla.rows[j].cells[k].innerHTML);

							}

							tabla.style.color = '#555';
							tabla.style.font = font;
							tabla.style.textAlign = 'right';
							tabla.setAttribute('align','center');
							document.body.appendChild(tabla);
						}

						if(_class == 'Aguinaldo_asalariados')//deleting 0.00 column from aguinaldo asalariados tables
						{
							var tables = document.getElementsByTagName('table');

							for(var i=2; i<tables[0].rows[3].cells.length; i++)
							{
								var flag = true;

								for(var j=0; j<tables.length; j++)
								{

									for(var k=4; k<tables[j].rows.length; k++)
									{

										if(tables[j].rows[k].cells[i].innerHTML != '0.00')
											flag = false;

									}

								}

								if(flag && tables[0].rows[3].cells[i].innerHTML != 'ISR')
								{

									for(var j=0; j<tables.length; j++)
									{

										for(var k=3; k<tables[j].rows.length; k++)
											tables[j].rows[k].removeChild(tables[j].rows[k].cells[i]);

									}

									i--;
								}

							}

						}

					}

				}

				xmltables.open('POST','get_tables_aguinaldo.php?_class=' + _class + '&aguinaldo=' + _id, true);
				xmltables.setRequestHeader("cache-control","no-cache");
				xmltables.send('');
			}

			function _format(data)
			{
				_data = parseFloat(data);

				if(!isNaN(_data) && _data != 'Infinity')
				{
					var parts = data.split('.');
					var _int = parts[0];
					var _dec = parts[1];
					var digits = _int.split('');
					var new_int = '';

					for(var i=digits.length - 1, j=1; i>=0; i--, j++)

						if(j % 4 == 0)
							new_int = digits[i] + ',' + new_int;
						else
							new_int = digits[i] + new_int;

					if(_dec)
						return (new_int + '.' + _dec);
					else
						return new_int;
				}
				else
					return data;

			}
		</script>
	</head>

	<body>
		<script type='text/javascript'>window.opener._preview_aguinaldo();</script>
	</body>
</html>
