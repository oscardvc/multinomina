<?php //This page is called by a javascript function named view at nomina.js?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

	<head>
		<script type = "text/javascript" src = "presentation.js"></script>
		<script type = "text/javascript" src = "resumen.js"></script>
		<script type = "text/javascript" src = "report.js"></script>
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

			function styleThis(){}

			var Summary = function (_Branches)
			{
				this.Branches = _Branches;

				this.addTitle = function(_title, _erasable_col, _erasable_row, _table)
				{
					var tr1 = document.createElement('tr');
					_table.appendChild(tr1);
					var concepts = document.createElement('td');
					tr1.appendChild(concepts);
					concepts.setAttribute('rowspan',2);
					concepts.innerHTML = _title;

					if(_erasable_row)
					{
						concepts.setAttribute('onclick','_del_field(this)');
						concepts.setAttribute('onmouseover','_marck(this)');
						concepts.setAttribute('onmouseout','_unmarck(this)');
						concepts.style.cursor = 'pointer';
					}

					var salaried = document.createElement('td');
					tr1.appendChild(salaried);
					salaried.setAttribute('colspan', this.Branches.length);
					salaried.setAttribute('class','column_title');

					if(_erasable_col)
					{
						salaried.setAttribute('onclick','_del(this)');
						salaried.setAttribute('onmouseover','_marck(this)');
						salaried.setAttribute('onmouseout','_unmarck(this)');
						salaried.style.cursor = 'pointer';
					}

					salaried.innerHTML = 'Asalariados';
					var total_salaried = document.createElement('td');
					tr1.appendChild(total_salaried);
					total_salaried.setAttribute('rowspan',2);

					if(_erasable_col)
					{
						total_salaried.setAttribute('onclick','_del(this)');
						total_salaried.setAttribute('onmouseover','_marck(this)');
						total_salaried.setAttribute('onmouseout','_unmarck(this)');
						total_salaried.style.cursor = 'pointer';
					}

					total_salaried.innerHTML = 'Total asalariados';
					var assimilable = document.createElement('td');
					tr1.appendChild(assimilable);
					assimilable.setAttribute('colspan', this.Branches.length);
					assimilable.setAttribute('class','column_title');

					if(_erasable_col)
					{
						assimilable.setAttribute('onclick','_del(this)');
						assimilable.setAttribute('onmouseover','_marck(this)');
						assimilable.setAttribute('onmouseout','_unmarck(this)');
						assimilable.style.cursor = 'pointer';
					}

					assimilable.innerHTML = 'Asimilables';
					var total_assimilable = document.createElement('td');
					tr1.appendChild(total_assimilable);
					total_assimilable.setAttribute('rowspan',2);

					if(_erasable_col)
					{
						total_assimilable.setAttribute('onclick','_del(this)');
						total_assimilable.setAttribute('onmouseover','_marck(this)');
						total_assimilable.setAttribute('onmouseout','_unmarck(this)');
						total_assimilable.style.cursor = 'pointer';
					}

					total_assimilable.innerHTML = 'Total asimilables';
					var total = document.createElement('td');
					tr1.appendChild(total);
					total.setAttribute('colspan', this.Branches.length);

					if(_erasable_col)
					{
						total.setAttribute('onclick','_del(this)');
						total.setAttribute('onmouseover','_marck(this)');
						total.setAttribute('onmouseout','_unmarck(this)');
						total.style.cursor = 'pointer';
					}

					total.innerHTML = 'Totales';
					var sum = document.createElement('td');
					tr1.appendChild(sum);
					sum.innerHTML = 'Total';
					sum.setAttribute('rowspan',2);

					if(_erasable_col)
					{
						sum.setAttribute('onclick','_del(this)');
						sum.setAttribute('onmouseover','_marck(this)');
						sum.setAttribute('onmouseout','_unmarck(this)');
						sum.style.cursor = 'pointer';
					}

					tr1.setAttribute('class','titles_row');
					tr1.style.font = title_font;
					tr1.style.color = '#fff';
					tr1.style.background = '#555';
					tr1.style.textAlign = 'center';
					var tr2 = document.createElement('tr');
					_table.appendChild(tr2);

					for(var i=0; i<this.Branches.length; i++)
					{
						var branch = document.createElement('td');
						tr2.appendChild(branch);
						branch.innerHTML = this.Branches[i];

						if(_erasable_col)
						{
							branch.setAttribute('onclick','_del(this)');
							branch.setAttribute('owner','Asalariados');
							branch.setAttribute('onmouseover','_marck(this)');
							branch.setAttribute('onmouseout','_unmarck(this)');
							branch.style.cursor = 'pointer';
						}

					}

					for(var i=0; i<this.Branches.length; i++)
					{
						var branch = document.createElement('td');
						tr2.appendChild(branch);
						branch.innerHTML = this.Branches[i];

						if(_erasable_col)
						{
							branch.setAttribute('onclick','_del(this)');
							branch.setAttribute('owner','Asimilables');
							branch.setAttribute('onmouseover','_marck(this)');
							branch.setAttribute('onmouseout','_unmarck(this)');
							branch.style.cursor = 'pointer';
						}

					}

					for(var i=0; i<this.Branches.length; i++)
					{
						var branch = document.createElement('td');
						tr2.appendChild(branch);
						branch.innerHTML = this.Branches[i];

						if(_erasable_col)
						{
							branch.setAttribute('onclick','_del(this)');
							branch.setAttribute('owner','Totales');
							branch.setAttribute('onmouseover','_marck(this)');
							branch.setAttribute('onmouseout','_unmarck(this)');
							branch.style.cursor = 'pointer';
						}

					}

					tr2.style.color = '#fff';
					tr2.style.background = color_opaque;
					tr2.style.textAlign = 'center';
					tr2.setAttribute('class','sucursales_row');
				}

				this.addRow = function(_title, _salaried, _assimilable, _total, _borderTop, _background, _table)
				{
					var tr = document.createElement('tr');
					_table.appendChild(tr);
					var title = document.createElement('td');
					title.style.textAlign = 'left';
					tr.appendChild(title);
					title.innerHTML = _title;
					var salaried = _salaried.split('/');
					var total_salaried = 0;

					for(var i=0; i<this.Branches.length; i++)
					{
						var td_salaried = document.createElement('td');
						tr.appendChild(td_salaried);
						td_salaried.innerHTML = salaried[i];
						total_salaried += parseFloat(salaried[i]);
						td_salaried.style.borderTop = _borderTop;
						td_salaried.style.background = _background;
					}

					var td_total_salaried = document.createElement('td');
					tr.appendChild(td_total_salaried);
					td_total_salaried.innerHTML = total_salaried.toFixed(2);
					td_total_salaried.style.borderTop = _borderTop;
					td_total_salaried.style.background = _background;
					var assimilable = _assimilable.split('/');
					var total_assimilable = 0;

					for(var i=0; i<this.Branches.length; i++)
					{
						var td_assimilable = document.createElement('td');
						tr.appendChild(td_assimilable);
						td_assimilable.innerHTML = _assimilable != '' ? assimilable[i] : 0.00;
						total_assimilable += _assimilable != '' ? parseFloat(assimilable[i]) : 0.00;
						td_assimilable.style.borderTop = _borderTop;
						td_assimilable.style.background = _background;
					}

					var td_total_assimilable = document.createElement('td');
					tr.appendChild(td_total_assimilable);
					td_total_assimilable.innerHTML = total_assimilable.toFixed(2);
					td_total_assimilable.style.borderTop = _borderTop;
					td_total_assimilable.style.background = _background;
					var total = _total.split('/');

					for(var i=0; i<this.Branches.length; i++)
					{
						var td_total = document.createElement('td');
						tr.appendChild(td_total);
						td_total.innerHTML = total[i];
						td_total.style.borderTop = _borderTop;
						td_total.style.background = _background;
					}

					var sum = 0;

					for(var i=0; i<total.length; i++)
						sum += parseFloat(total[i]);

					var td_sum = document.createElement('td');
					tr.appendChild(td_sum);
					td_sum.innerHTML = sum.toFixed(2);
					td_sum.style.borderTop = _borderTop;
					td_sum.style.background = _background;
				}

				this.mul = function(_data, _m)
				{
					var values = _data.split('/');

					for (var i=0; i<values.length; i++)
					{
						values[i] = parseFloat(values[i]) * _m;
						values[i] = values[i].toFixed(2);
					}

					return values.join('/');
				}

				this.sum = function(_a, _b)
				{
					var values_a = _a.split('/');
					var values_b = _b.split('/');
					var total = new Array();

					for (var i=0; i<values_a.length; i++)
					{
						total[i] = parseFloat(values_a[i]) + parseFloat(values_b[i]);
						total[i] = total[i].toFixed(2);
					}

					return total.join('/');
				}

			}

			function _load(obj)
			{
				var fieldset = obj.parentNode;
				var _type = obj.getAttribute('class');//used to difference between nomina report and deposit report
				var _class = fieldset.getAttribute('class');
				var _classify = obj.getAttribute('classify');
				var form = fieldset.parentNode;
				var header_col = document.createElement('td');
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
					else if(textareas[i].getAttribute('name') == 'Honorarios_pendientes')
						var __honorarios_pendientes = textareas[i].value;
					else if(textareas[i].getAttribute('name') == 'Cobrar_IVA')
						var _cobrar_iva = textareas[i].value;
					else if(textareas[i].getAttribute('name') == 'ivash')
						var _ivash = textareas[i].value;
					else if(textareas[i].getAttribute('name') == 'Limite_inferior_del_periodo')
						var _limite_inferior = textareas[i].value;
					else if(textareas[i].getAttribute('name') == 'Limite_superior_del_periodo')
						var _limite_superior = textareas[i].value;
					else if(textareas[i].getAttribute('name') == 'Resumen')
						var _resumen = textareas[i].value;

				header_col.innerHTML = _expedidor == 'Empresa administradora' ? _administradora : _empresa;
				var info = "";

				if(_class == 'ISRasalariados_fieldset')
				{
					info = (_expedidor == 'Empresa administradora' ? '<br/ >Calculo del ISR del personal asignado a la empresa ' + _empresa : '<br/ >Calculo del ISR') + ' correspondiente al periodo ' + _limite_inferior + '/' + _limite_superior;
					var _colspan = 15;
				}
				else if(_class == 'IMSS')
				{
					info = (_expedidor == 'Empresa administradora' ? '<br/ >Cédula de determinación de cuotas IMSS del personal asignado a la empresa ' + _empresa : '<br/ >Cédula de determinación de cuotas IMSS') + ' correspondiente al periodo ' + _limite_inferior + '/' + _limite_superior;
					var _colspan = 26;
				}
				else if(_class == 'Nomina_asalariados')
				{

					if(_type == 'deposit_button')
					{
						info = (_expedidor == 'Empresa administradora' ? '<br/ >Pagos del personal asignado a la empresa ' + _empresa : '<br/ >Depósitos bancarios') + ' correspondiente al periodo ' + _limite_inferior + '/' + _limite_superior;
						var _colspan = 9;
					}
					else
					{
						info = (_expedidor == 'Empresa administradora' ? '<br/ >Reporte de nómina del personal asignado a la empresa ' + _empresa : '<br/ >Reporte de nómina') + ' correspondiente al periodo ' + _limite_inferior + '/' + _limite_superior;
						var _colspan = 40;
					}

				}
				else if(_class == 'Prestaciones')
				{
					info = (_expedidor == 'Empresa administradora' ? '<br/ >Reporte de prestaciones del personal asignado a la empresa ' + _empresa : '<br/ >Reporte de prestaciones') + ' correspondiente al periodo ' + _limite_inferior + '/' + _limite_superior;
					var _colspan = 10;
				}
				else if(_class == 'ISRasimilables_fieldset')
				{
					info = (_expedidor == 'Empresa administradora' ? '<br/ >Calculo del ISR de la empresa ' + _empresa : '<br/ >Calculo del ISR') + ' correspondiente al periodo ' + _limite_inferior + '/' + _limite_superior;
					var _colspan = 14;
				}
				else if(_class == 'Nomina_asimilables')
				{

					if(_type == 'deposit_button')
					{
						info = (_expedidor == 'Empresa administradora' ? '<br/ >Pagos del personal asignado a la empresa ' + _empresa : '<br/ >Depósitos bancarios') + ' correspondiente al periodo ' + _limite_inferior + '/' + _limite_superior;
						var _colspan = 9;
					}
					else
					{
						info = (_expedidor == 'Empresa administradora' ? '<br/ >Concentrado de honorarios asimilados de la empresa ' + _empresa : '<br/ >Concentrado de honorarios asimilados') + ' correspondiente al periodo ' + _limite_inferior + '/' + _limite_superior;
						var _colspan = 16;
					}

				}
				else if(_class == 'Datos_fieldset')
				{
					info = (_expedidor == 'Empresa administradora' ? '<br/ >Resumen de erogaciones del personal asignado a la empresa ' + _empresa : '<br/ >Resumen de erogaciones') + ' correspondiente al periodo ' + _limite_inferior + '/' + _limite_superior;
				}

				if(_class != 'Datos_fieldset')
					header_col.setAttribute('colspan',_colspan);

				var header_row = document.createElement('tr');
				header_row.appendChild(header_col);
				header_col.style.textAlign = 'center';
				header_col.style.font = title_font;
				header_col.style.background = 'none';
				header_col.style.color = '#555';
				header_col.style.cursor = 'pointer';
				header_col.setAttribute('class','header');

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

							var divs = _div.getElementsByTagName('div');
							var empresa_rfc = divs[0].innerHTML;
							var registro_patronal = divs[1].innerHTML;

							for(var i=0; i<tables.length; i++)
							{
								var image = document.createElement('img');
								document.body.appendChild(image);
								image.src = 'images/logo_blanco.jpg';
								var table = document.createElement('table');
								var _header_row = header_row.cloneNode(true);
								table.appendChild(_header_row);
								_header_row.firstChild.innerHTML += '<br/ >RFC ' + empresa_rfc;
								_header_row.firstChild.innerHTML += _class != 'Nomina_asimilables' && _class != 'ISRasimilables_fieldset' ? ('<br/ >Registro patronal ' + registro_patronal) : '';
								_header_row.firstChild.innerHTML += info;
								_header_row.firstChild.setAttribute('onmouseover','mark_report(this)');
								_header_row.firstChild.setAttribute('onmouseout','unmark_report(this)');
								_header_row.firstChild.setAttribute('onclick','del_report(this)');
								var rows = tables[i].rows;

								for(var j=0; j<rows.length; j++)
								{
									var row = document.createElement('tr');
									row.style.textAlign = 'right';
									var cols = rows[j].getElementsByTagName('td');

									for(var k=0; k<cols.length; k++)
									{
										var col = document.createElement('td');

										if(j == 0)
										{
											col.style.font = title_font;
											col.style.textAlign = 'center';
										}
										else if(j == 1)
										{
											col.setAttribute('onmouseover','mark_report(this)');
											col.setAttribute('onmouseout','unmark_report(this)');
											col.setAttribute('onclick','del_report(this)');
											col.style.cursor = 'pointer';
											col.style.font = title_font;
											col.style.textAlign = 'center';
										}
										else
										{
											col.style.font = font;
											col.style.textAlign = 'right';
										}

										if(_class == 'ISRasalariados_fieldset' && (k > 29 || k == 0 || k == 1 || k == 2 || k == 3 || k == 4))
										{

											if(j == 0)
												col.setAttribute('colspan',15);

											if((k == 0 || k == 1 || k == 2 || k == 3 || k == 4) && j > 1)
											{
												col.style.textAlign = 'left';
												col.style.whiteSpace = 'nowrap';
												col.innerHTML = cols[k].innerHTML;
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
										else if(_class == 'IMSS' && (k == 0 || k == 1 || k == 2 || k > 11))
										{

											if(j == 0)
												col.setAttribute('colspan',24);

											if((k == 0 ||k == 1 || k == 2) && j > 1)
											{
												col.style.textAlign = 'left';
												col.style.whiteSpace = 'nowrap';
												col.innerHTML = cols[k].innerHTML;
											}
											else if(j > 1)
											{
												var data = cols[k].innerHTML.split(',');

												if(data.length > 1)
												{
													var data1 = data[0].split('/');
													var data2 = data[1].split('/');
													var val1 = parseFloat(data1[0]);
													var val2 = parseFloat(data2[0]);
													col.innerHTML = val1.toFixed(2) +  '/' + data1[1] + ',' + val2.toFixed(2) +  '/' + data2[1];
												}
												else
												{
													var values = cols[k].innerHTML.split('/');
													var val = parseFloat(values[0]);
													col.innerHTML = k == 12 ? val.toFixed(4) : val.toFixed(2);
												}

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
										else if(_class == 'Nomina_asalariados')
										{

											if(_type == 'deposit_button')
											{

												if(j == 0)
													col.setAttribute('colspan',11);

												if(k < 4 && j > 1)
												{
													col.style.textAlign = k == 1 ? 'right' : 'left';
													col.style.whiteSpace = 'nowrap';
													col.innerHTML = cols[k].innerHTML;
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
											else if(k != 35 && k != 36 && k != 44 && k != 45)
											{

												if(j == 0)
													col.setAttribute('colspan',42);

												if((k == 0 || k == 1 || k == 2 || k == 3 || k == 4 || k == 5) && j > 1)
												{
													col.style.textAlign = 'left';
													col.style.whiteSpace = 'nowrap';
													col.innerHTML = cols[k].innerHTML;
												}
												else if(j > 1)
												{

													if(k == 6)
														var total = cols[k].innerHTML;
													else
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

													}

													if(k != 1 && k != 6)
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
										else if(_class == 'Prestaciones' && k != 5 && k != 6 && k != 7 && k != 8 && k != 10 && k != 12 && k != 14 && k != 16)
										{

											if(j == 0)
												col.setAttribute('colspan',10);

											if((k == 0 || k == 1 || k == 2 || k == 3 || k == 4) && j > 1)
											{
												col.style.textAlign = 'left';
												col.style.whiteSpace = 'nowrap';
												col.innerHTML = cols[k].innerHTML;
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
										else if(_class == 'ISRasimilables_fieldset' && k != 4 && k != 5 && k != 6 && k != 7 && k != 8 && k != 9)
										{

											if(j == 0)
												col.setAttribute('colspan',14);

											if((k == 0 || k == 1 || k == 2 || k == 3) && j > 1)
											{
												col.style.textAlign = 'left';
												col.style.whiteSpace = 'nowrap';
												col.innerHTML = cols[k].innerHTML;
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
										else if(_class == 'Nomina_asimilables')
										{

											if(_type == 'deposit_button')
											{

												if(j == 0)
													col.setAttribute('colspan',9);

												if(k < 4 && j > 1)
												{
													col.style.textAlign = k == 1 ? 'right' : 'left';
													col.style.whiteSpace = 'nowrap';
													col.innerHTML = cols[k].innerHTML;
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
											else if(k != 4 && k != 11 && k != 12 && k != 18 && k != 19)
											{

												if(j == 0)
													col.setAttribute('colspan',14);

												if((k == 0 || k == 1 || k == 2 || k == 3) && j > 1)
												{
													col.style.textAlign = 'left';
													col.style.whiteSpace = 'nowrap';
													col.innerHTML = cols[k].innerHTML;
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

													col.innerHTML = total.toFixed(2);
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

										}

									}//cols iterator

									table.appendChild(row);
								}//rows iterator

								table.rows[1].cells[0].style.background = '#555';
								table.rows[1].cells[0].style.color = '#fff';
								table.rows[1].cells[0].style.MozBorderRadiusTopleft = '10px';
								table.rows[1].cells[0].style.MozBorderRadiusTopright = '10px';
								table.rows[1].cells[0].style.WebkitBorderTopLeftRadius = '10px';
								table.rows[1].cells[0].style.WebkitBorderTopRightRadius = '10px';
								table.rows[1].cells[0].style.borderTopLeftRadius = '10px';
								table.rows[1].cells[0].style.borderTopRightRadius = '10px';

								for(var j=0; j<table.rows[2].cells.length; j++)
								{
									table.rows[2].cells[j].style.background = color_opaque;
									table.rows[2].cells[j].style.color = '#fff';
								}

								document.body.appendChild(table);
								table.style.color = '#555';
								table.style.pageBreakAfter = _type != 'deposit_button' ? 'always' : 'auto';
								//totals
								if(_type != 'deposit_button')
								{
									var _tr = document.createElement('tr');
									var _td = document.createElement('td');
									_tr.appendChild(_td);
									_td.innerHTML = 'Total';
									_td.style.textAlign = 'right';
									var j=0;

									if(_class == 'ISRasalariados_fieldset')
										j = 9;
									else if(_class == 'IMSS')
										j = 6;
									else if(_class == 'Nomina_asalariados')
										j = 7;
									else if(_class == 'Prestaciones')
										j = 5;
									else if(_class == 'ISRasimilables_fieldset')
										j = 7;
									else if(_class == 'Nomina_asimilables')
										j = 4;

									for(var k=1; k<table.rows[2].cells.length; k++)
									{
										var total = 0;
										var _td = document.createElement('td');
										_tr.appendChild(_td);
										_td.style.textAlign = 'right';

										for(var l=3; l<table.rows.length; l++)

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

								}

								//thousands separator
								for(var j=0; j<table.rows.length; j++)
								{
									var k = _type == 'deposit_button' ? 1 : 3
									for(;k<table.rows[j].cells.length; k++)
										table.rows[j].cells[k].innerHTML = _format(table.rows[j].cells[k].innerHTML);

								}

								if(_type == 'deposit_button')
								{
									var _table = tables[i+1];
									_table.style.font = font;
									_table.rows[0].cells[0].style.font = title_font;
									_table.rows[0].cells[0].style.padding = '2px 5px';
									_table.rows[0].cells[0].style.textAlign = 'center';
									_table.rows[0].cells[0].style.color = '#fff';
									_table.rows[0].cells[0].style.background = '#555';
									_table.rows[0].cells[0].style.MozBorderRadiusTopleft = '10px';
									_table.rows[0].cells[0].style.MozBorderRadiusTopright = '10px';
									_table.rows[0].cells[0].style.WebkitBorderTopLeftRadius = '10px';
									_table.rows[0].cells[0].style.WebkitBorderTopRightRadius = '10px';
									_table.rows[0].cells[0].style.borderTopLeftRadius = '10px';
									_table.rows[0].cells[0].style.borderTopRightRadius = '10px';
									document.body.appendChild(_table);

									if(i == tables.length - 2)
									{
										var _table = tables[i+1];
										_table.style.font = font;
										_table.rows[0].cells[0].style.font = title_font;
										_table.rows[0].cells[0].style.padding = '2px 5px';
										_table.rows[0].cells[0].style.textAlign = 'center';
										_table.rows[0].cells[0].style.color = '#fff';
										_table.rows[0].cells[0].style.background = '#555';
										_table.rows[0].cells[0].style.MozBorderRadiusTopleft = '10px';
										_table.rows[0].cells[0].style.MozBorderRadiusTopright = '10px';
										_table.rows[0].cells[0].style.WebkitBorderTopLeftRadius = '10px';
										_table.rows[0].cells[0].style.WebkitBorderTopRightRadius = '10px';
										_table.rows[0].cells[0].style.borderTopLeftRadius = '10px';
										_table.rows[0].cells[0].style.borderTopRightRadius = '10px';
										document.body.appendChild(_table);
										break;
									}
									else
									{
										var _br = document.createElement('br');
										_br.setAttribute('class','saltopagina');
										document.body.appendChild(_br);
									}

								}

							}//tables iterator

						}
						else
						{
							var image = document.createElement('img');
							document.body.appendChild(image);
							image.src = 'images/logo_blanco.jpg';
							document.body.appendChild(document.createElement('br'));
							var sucursales = xmltables.responseText.split(',');
							var tabla = document.createElement('table');
							header_col.setAttribute('colspan',sucursales.length * 3 + 4);
							header_col.innerHTML += info;
							tabla.appendChild(header_row);
							tabla.appendChild(document.createElement('br'));
							var _data = _resumen.split(',');
							var Resumen = new Summary(sucursales);
							//Nomina******************************************************************************
							Resumen.addTitle('Nómina', true, false, tabla);
							//sueldo
							Resumen.addRow('Sueldo', _data[0], _data[1], _data[2], 'none', 'none', tabla);
							//subsidio al empleo
							Resumen.addRow('Subsidio al empleo', _data[3], '', _data[3], 'none', 'none', tabla);
							//horas extra
							var _totales_horas_extra = _data[4].split('/');
							var total_horas_extra = 0;

							for(var i=0; i<_totales_horas_extra.length; i++)
								total_horas_extra += parseFloat(_totales_horas_extra[i]);

							if(total_horas_extra.toFixed(2) != 0.00)
								Resumen.addRow('Horas extra', _data[4], '', _data[4], 'none', 'none', tabla);

							//prima dominical
							var _totales_prima_dominical = _data[5].split('/');
							var total_prima_dominical = 0;

							for(var i=0; i<_totales_prima_dominical.length; i++)
								total_prima_dominical += parseFloat(_totales_prima_dominical[i]);

							if(total_prima_dominical.toFixed(2) != 0.00)
								Resumen.addRow('Prima dominical', _data[5], '', _data[5], 'none', 'none', tabla);

							//dias de descanso
							var _totales_dias_de_descanso = _data[6].split('/');
							var total_dias_de_descanso = 0;

							for(var i=0; i<_totales_dias_de_descanso.length; i++)
								total_dias_de_descanso += parseFloat(_totales_dias_de_descanso[i]);

							if(total_dias_de_descanso.toFixed(2) != 0.00)//dias de descanso
								Resumen.addRow('Días de descanso', _data[6], '', _data[6], 'none', 'none', tabla);

							//Premios de puntualidad y asistencia
							var _totales_premios_de_puntualidad_y_asistencia = _data[7].split('/');
							var total_premios_de_puntualidad_y_asistencia = 0;

							for(var i=0; i<_totales_premios_de_puntualidad_y_asistencia.length; i++)
								total_premios_de_puntualidad_y_asistencia += parseFloat(_totales_premios_de_puntualidad_y_asistencia[i]);

							if(total_premios_de_puntualidad_y_asistencia.toFixed(2) != 0.00)
								Resumen.addRow('Premios de puntualidad y asistencia', _data[7], '', _data[7], 'none', 'none', tabla);
							//Bonos de productividad
							var _totales_bonos_de_productividad = _data[8].split('/');
							var total_bonos_de_productividad = 0;

							for(var i=0; i<_totales_bonos_de_productividad.length; i++)
								total_bonos_de_productividad += parseFloat(_totales_bonos_de_productividad[i]);

							if(total_premios_de_puntualidad_y_asistencia.toFixed(2) != 0.00)
								Resumen.addRow('Bonos de productividad', _data[8], '', _data[8], 'none', 'none', tabla);

							//Estimulos
							var _totales_estimulos = _data[9].split('/');
							var total_estimulos = 0;

							for(var i=0; i<_totales_estimulos.length; i++)
								total_estimulos += parseFloat(_totales_estimulos[i]);

							if(total_estimulos.toFixed(2) != 0.00)
								Resumen.addRow('Estímulos', _data[9], '', _data[9], 'none', 'none', tabla);

							//Compensaciones
							var _totales_compensaciones = _data[10].split('/');
							var total_compensaciones = 0;

							for(var i=0; i<_totales_compensaciones.length; i++)
								total_compensaciones += parseFloat(_totales_compensaciones[i]);

							if(total_compensaciones.toFixed(2) != 0.00)
								Resumen.addRow('Compensaciones', _data[10], '', _data[10], 'none', 'none', tabla);

							//Despensa
							var _totales_despensa = _data[11].split('/');
							var total_despensa = 0;

							for(var i=0; i<_totales_despensa.length; i++)
								total_despensa += parseFloat(_totales_despensa[i]);

							if(total_despensa.toFixed(2) != 0.00)
								Resumen.addRow('Despensa', _data[11], '', _data[11], 'none', 'none', tabla);

							//Comida
							var _totales_comida = _data[12].split('/');
							var total_comida = 0;

							for(var i=0; i<_totales_comida.length; i++)
								total_comida += parseFloat(_totales_comida[i]);

							if(total_despensa.toFixed(2) != 0.00)
								Resumen.addRow('Comida', _data[12], '', _data[12], 'none', 'none', tabla);

							//Alimentacion
							var _totales_alimentacion = _data[13].split('/');
							var total_alimentacion = 0;

							for(var i=0; i<_totales_alimentacion.length; i++)
								total_alimentacion += parseFloat(_totales_alimentacion[i]);

							if(total_alimentacion.toFixed(2) != 0.00)
								Resumen.addRow('Alimentación', _data[13], '', _data[13], 'none', 'none', tabla);

							//Habitacion
							var _totales_habitacion = _data[14].split('/');
							var total_habitacion = 0;

							for(var i=0; i<_totales_habitacion.length; i++)
								total_habitacion += parseFloat(_totales_habitacion[i]);

							if(total_habitacion.toFixed(2) != 0.00)
								Resumen.addRow('Habitación', _data[14], '', _data[14], 'none', 'none', tabla);

							//Aportacion patronal al fondo de ahorro
							var _totales_aportacion_patronal_al_fondo_de_ahorro = _data[15].split('/');
							var total_aportacion_patronal_al_fondo_de_ahorro = 0;

							for(var i=0; i<_totales_aportacion_patronal_al_fondo_de_ahorro.length; i++)
								total_aportacion_patronal_al_fondo_de_ahorro += parseFloat(_totales_aportacion_patronal_al_fondo_de_ahorro[i]);

							if(total_aportacion_patronal_al_fondo_de_ahorro.toFixed(2) != 0.00)//Aportacion patronal al fondo de ahorro
								Resumen.addRow('Aportación patronal al fondo de ahorro', _data[15], '', _data[15], 'none', 'none', tabla);
							//Total de percepciones
							Resumen.addRow('Total de percepciones', _data[16], _data[17], _data[18], '1px solid #555', 'none', tabla);
							tabla.appendChild(document.createElement('br'));
							//ISR
							Resumen.addRow('ISR', _data[19], _data[21], _data[22], 'none', 'none', tabla);
							//Cuotas IMSS obreras
							var _totales_cuotas_imss = _data[23].split('/');
							var total_cuotas_imss = 0;

							for(var i=0; i<_totales_cuotas_imss.length; i++)
								total_cuotas_imss += parseFloat(_totales_cuotas_imss[i]);

							if(total_cuotas_imss.toFixed(2) != 0.00)
								Resumen.addRow('Cuotas IMSS obreras', _data[23], '', _data[23], 'none', 'none', tabla);
							//Cesantia y vejez obrera
							var _totales_cesantia_y_vejez = _data[24].split('/');
							var total_cesantia_y_vejez = 0;

							for(var i=0; i<_totales_cesantia_y_vejez.length; i++)
								total_cesantia_y_vejez += parseFloat(_totales_cesantia_y_vejez[i]);

							if(total_cesantia_y_vejez.toFixed(2) != 0.00)
								Resumen.addRow('Cesantía y vejez obrera', _data[24], '', _data[24], 'none', 'none', tabla);
							//Retencion por alimentacion
							var _totales_retencion_por_alimentacion = _data[25].split('/');
							var total_retencion_por_alimentacion = 0;

							for(var i=0; i<_totales_retencion_por_alimentacion.length; i++)
								total_retencion_por_alimentacion += parseFloat(_totales_retencion_por_alimentacion[i]);

							if(total_retencion_por_alimentacion.toFixed(2) != 0.00)
								Resumen.addRow('Retención por alimentación', _data[25], '', _data[25], 'none', 'none', tabla);
							//Retencion por habitacion
							var _totales_retencion_por_habitacion = _data[26].split('/');
							var total_retencion_por_habitacion = 0;

							for(var i=0; i<_totales_retencion_por_habitacion.length; i++)
								total_retencion_por_habitacion += parseFloat(_totales_retencion_por_habitacion[i]);

							if(total_retencion_por_habitacion.toFixed(2) != 0.00)
								Resumen.addRow('Retención por habitación', _data[26], '', _data[26], 'none', 'none', tabla);
							//Retencion INFONAVIT
							var _totales_retencion_infonavit = _data[27].split('/');
							var total_retencion_infonavit = 0;

							for(var i=0; i<_totales_retencion_infonavit.length; i++)
								total_retencion_infonavit += parseFloat(_totales_retencion_infonavit[i]);

							if(total_retencion_infonavit.toFixed(2) != 0.00)
								Resumen.addRow('Retención INFONAVIT', _data[27], '', _data[27], 'none', 'none', tabla);
							//Retencion FONACOT
							var _totales_retencion_fonacot = _data[28].split('/');
							var total_retencion_fonacot = 0;

							for(var i=0; i<_totales_retencion_fonacot.length; i++)
								total_retencion_fonacot += parseFloat(_totales_retencion_fonacot[i]);

							if(total_retencion_fonacot.toFixed(2) != 0.00)
								Resumen.addRow('Retención FONACOT', _data[28], '', _data[28], 'none', 'none', tabla);
							//Aportacion del trabajador al fondo de ahorro
							var _totales_ahorro = _data[29].split('/');
							var total_ahorro = 0;

							for(var i=0; i<_totales_ahorro.length; i++)
								total_ahorro += parseFloat(_totales_ahorro[i]);

							if(total_ahorro.toFixed(2) != 0.00)
								Resumen.addRow('Aportación del trabajador al fondo de ahorro', _data[29], '', _data[29], 'none', 'none', tabla);
							//Pensión alimenticia
							var _totales_pension_alimenticia = _data[30].split('/');
							var total_pension_alimenticia = 0;

							for(var i=0; i<_totales_pension_alimenticia.length; i++)
								total_pension_alimenticia += parseFloat(_totales_pension_alimenticia[i]);

							if(total_pension_alimenticia.toFixed(2) != 0.00)
								Resumen.addRow('Pensión alimenticia', _data[30], '', _data[30], 'none', 'none', tabla);
							//Retardos
							var _totales_retardos = _data[31].split('/');
							var total_retardos = 0;

							for(var i=0; i<_totales_retardos.length; i++)
								total_retardos += parseFloat(_totales_retardos[i]);

							if(total_retardos.toFixed(2) != 0.00)
								Resumen.addRow('Retardos', _data[31], '', _data[31], 'none', 'none', tabla);

							//Prestaciones
							var _totales_prestaciones = _data[32].split('/');
							var total_prestaciones = 0;

							for(var i=0; i<_totales_prestaciones.length; i++)
								total_prestaciones += parseFloat(_totales_prestaciones[i]);

							if(total_prestaciones.toFixed(2) != 0.00)
								Resumen.addRow('Prestaciones', _data[32], '', _data[32], 'none', 'none', tabla);

							//Gestión administrativa
							var _totales_ga = _data[35].split('/');
							var total_ga = 0;

							for(var i=0; i<_totales_ga.length; i++)
								total_ga += parseFloat(_totales_ga[i]);

							if(total_ga.toFixed(2) != 0.00)
							{
								Resumen.addRow('Gestión administrativa', _data[33], _data[34], _data[35], 'none', 'none', tabla);
							}

							Resumen.addRow('Total de deducciones', _data[36], _data[37], _data[38], '1px solid #555', 'none', tabla);
							tabla.appendChild(document.createElement('br'));
							//saldo
							Resumen.addRow('Total nómina', _data[39], _data[40], _data[41], 'none', '#ddd', tabla);
							tabla.appendChild(document.createElement('br'));

							//Contribuciones y retenciones**************************************************************
							Resumen.addTitle('Contribuciones y retenciones', false, false, tabla);
							//ISR
							var contribuciones_isr_asalariados = _data[20].split('/');
							var contribuciones_isr_asimilables = _data[21].split('/');
							var total_contribuciones_isr =  new Array(contribuciones_isr_asalariados.length);

							for(var i=0; i<contribuciones_isr_asalariados.length; i++)
							{
								var sal = parseFloat(contribuciones_isr_asalariados[i]);
								var ass = parseFloat(contribuciones_isr_asimilables[i]);
								total_contribuciones_isr[i] =  sal+ ass;
								total_contribuciones_isr[i] =  total_contribuciones_isr[i].toFixed(2);
							}

							Resumen.addRow('ISR', _data[20], _data[21], total_contribuciones_isr.join('/'), 'none', 'none', tabla);
							//CIO
							var _totales_cio = _data[67].split('/');
							var total_cio = 0;

							for(var i=0; i<_totales_cio.length; i++)
								total_cio += parseFloat(_totales_cio[i]);

							if(total_cio.toFixed(2) != 0.00)
								Resumen.addRow('Cuotas IMSS obreras', _data[67], '', _data[67], 'none', 'none', tabla);

							//CISM
							var _totales_cism = _data[68].split('/');
							var total_cism = 0;

							for(var i=0; i<_totales_cism.length; i++)
								total_cism += parseFloat(_totales_cism[i]);

							if(total_cism.toFixed(2) != 0.00)
								Resumen.addRow('Cuotas IMSS por salarios mínimos', _data[68], '', _data[68], 'none', 'none', tabla);
							//Cuotas IMSS patronales
							var _totales_cip = _data[69].split('/');
							var total_cip = 0;

							for(var i=0; i<_totales_cip.length; i++)
								total_cip += parseFloat(_totales_cip[i]);

							if(total_cip.toFixed(2) != 0.00)
								Resumen.addRow('Cuotas IMSS patronales', _data[69], '', _data[69], 'none', 'none', tabla);
							//Adeudo cuotas IMSS
							var _totales_adeudo_imss = _data[70].split('/');
							var total_adeudo_imss = 0;

							for(var i=0; i<_totales_adeudo_imss.length; i++)
								total_adeudo_imss += parseFloat(_totales_adeudo_imss[i]);

							if(total_adeudo_imss.toFixed(2) != 0.00)
								Resumen.addRow('Adeudo cuotas IMSS', _data[70], '', _data[70], 'none', 'none', tabla);
							//Retencion INFONAVIT
							if(total_retencion_infonavit.toFixed(2) != 0.00)
								Resumen.addRow('Retención INFONAVIT', _data[27], '', _data[27], 'none', 'none', tabla);
							//Retencion FONACOT
							if(total_retencion_fonacot.toFixed(2) != 0.00)
								Resumen.addRow('Retención FONACOT', _data[28], '', _data[28], 'none', 'none', tabla);
							//Aportacion del trabajador al fondo de ahorro y aportación patronal(mismos valores)
							if(total_ahorro.toFixed(2) != 0.00)
							{
								Resumen.addRow('Aportación patronal al fondo de ahorro', _data[29], '', _data[29], 'none', 'none', tabla);
								Resumen.addRow('Aportación del trabajador al fondo de ahorro', _data[29], '', _data[29], 'none', 'none', tabla);
							}

							//Impuesto sobre nomina
							var _totales_impuesto_sobre_nomina = _data[73].split('/');
							var total_impuesto_sobre_nomina = 0;

							for(var i=0; i<_totales_impuesto_sobre_nomina.length; i++)
								total_impuesto_sobre_nomina += parseFloat(_totales_impuesto_sobre_nomina[i]);

							if(total_impuesto_sobre_nomina.toFixed(2) != 0.00)
								Resumen.addRow('Impuesto sobre nómina', _data[71], _data[72], _data[73], 'none', 'none', tabla);
							//5% INFONAVIT
							var _totales_infonavit = _data[74].split('/');
							var total_infonavit = 0;

							for(var i=0; i<_totales_infonavit.length; i++)
								total_infonavit += parseFloat(_totales_infonavit[i]);

							if(total_infonavit.toFixed(2) != 0.00)
								Resumen.addRow('5% INFONAVIT', _data[74], '', _data[74], 'none', 'none', tabla);

							//Retiro
							var _retiros = _data[75].split('/');
							var retiro = 0;

							for(var i=0; i<_retiros.length; i++)
								retiro += parseFloat(_retiros[i]);

							if(retiro.toFixed(2) != 0.00)
								Resumen.addRow('Retiro', _data[75], '', _data[75], 'none', 'none', tabla);

							//CAV obrera
							var _cav_obreras = _data[76].split('/');
							var cav_obrera = 0;

							for(var i=0; i<_cav_obreras.length; i++)
								cav_obrera += parseFloat(_cav_obreras[i]);

							if(cav_obrera.toFixed(2) != 0.00)
								Resumen.addRow('Cesantía y vejez obrera', _data[76], '', _data[76], 'none', 'none', tabla);
							//CAVSM
							var _cavsms = _data[77].split('/');
							var cavsm = 0;

							for(var i=0; i<_cavsms.length; i++)
								cavsm += parseFloat(_cavsms[i]);

							if(cavsm.toFixed(2) != 0.00)
								Resumen.addRow('Cesantía y vejez por salarios mínimos', _data[77], '', _data[77], 'none', 'none', tabla);
							//CAV patronal
							var _cav_patronales = _data[78].split('/');
							var cav_patronal = 0;

							for(var i=0; i<_cav_patronales.length; i++)
								cav_patronal += parseFloat(_cav_patronales[i]);

							if(cav_patronal.toFixed(2) != 0.00)
								Resumen.addRow('Cesantía y vejez patronal', _data[78], '', _data[78], 'none', 'none', tabla);
							//Pensión alimenticia
							if(total_pension_alimenticia.toFixed(2) != 0.00)
								Resumen.addRow('Pensión alimenticia', _data[30], '', _data[30], 'none', 'none', tabla);

							//Total contribuciones y retenciones
							Resumen.addRow('Total contribuciones y retenciones', _data[79], _data[80], _data[81], 'none', '#ddd', tabla);
							tabla.appendChild(document.createElement('br'));
							//prestaciones*****************************************************************************
							Resumen.addTitle('Prestaciones', false, false, tabla);
							//Vacaciones
							var _totales_vacaciones = _data[42].split('/');
							var total_vacaciones = 0;

							for(var i=0; i<_totales_vacaciones.length; i++)
								total_vacaciones += parseFloat(_totales_vacaciones[i]);

							if(total_vacaciones.toFixed(2) != 0.00)
								Resumen.addRow('Vacaciones', _data[42], '', _data[42], 'none', 'none', tabla);

							//Prima vacacional
							var _totales_prima_vacacional = _data[43].split('/');
							var total_prima_vacacional = 0;

							for(var i=0; i<_totales_prima_vacacional.length; i++)
								total_prima_vacacional += parseFloat(_totales_prima_vacacional[i]);

							if(total_prima_vacacional.toFixed(2) != 0.00)
								Resumen.addRow('Prima vacacional', _data[43], '', _data[43], 'none', 'none', tabla);

							//Aguinaldo
							var _totales_aguinaldo = _data[44].split('/');
							var total_aguinaldo = 0;

							for(var i=0; i<_totales_aguinaldo.length; i++)
								total_aguinaldo += parseFloat(_totales_aguinaldo[i]);

							if(total_aguinaldo.toFixed(2) != 0.00)
								Resumen.addRow('Aguinaldo', _data[44], '', _data[44], 'none', 'none', tabla);

							//Prima de antiguedad
							var _totales_prima_de_antiguedad = _data[45].split('/');
							var total_prima_de_antiguedad = 0;

							for(var i=0; i<_totales_prima_de_antiguedad.length; i++)
								total_prima_de_antiguedad += parseFloat(_totales_prima_de_antiguedad[i]);

							if(total_prima_de_antiguedad.toFixed(2) != 0.00)
								Resumen.addRow('Prima de antigüedad', _data[45], '', _data[45], 'none', 'none', tabla);

							//Total prestaciones
							Resumen.addRow('Total prestaciones', _data[46], '', _data[46], 'none', '#ddd', tabla);
							tabla.appendChild(document.createElement('br'));
							//Gestión administrativa*********************************************************************
							if(total_ga.toFixed(2) != 0.00)
							{
								Resumen.addTitle('Gestión administrativa', false, false, tabla);
								//Total gestión adminsitrativa
								Resumen.addRow('Total gestión administrativa', _data[33], _data[34], _data[35], 'none', '#ddd', tabla);
								tabla.appendChild(document.createElement('br'));
							}

							//otros**************************************************************************************
							var _totales_otros = _data[63].split('/');
							var total_otros = 0;

							for(var i=0; i<_totales_otros.length; i++)
								total_otros += parseFloat(_totales_otros[i]);

							if(total_otros.toFixed(2) != 0.00)
							{
								Resumen.addTitle('Otros', false, false, tabla);
								//Fondo de garantía
								var _fondo_de_garantia = _data[47].split('/');
								var total_fondo_de_garantia = 0;

								for(var i=0; i<_fondo_de_garantia.length; i++)
									total_fondo_de_garantia += parseFloat(_fondo_de_garantia[i]);

								if(total_fondo_de_garantia.toFixed(2) != 0.00)
									Resumen.addRow('Fondo de garantía', _data[47], '', _data[47], 'none', 'none', tabla);

								//Pago por seguro de vida
								var _totales_pago_por_seguro_de_vida = _data[50].split('/');
								var total_pago_por_seguro_de_vida = 0;

								for(var i=0; i<_totales_pago_por_seguro_de_vida.length; i++)
									total_pago_por_seguro_de_vida += parseFloat(_totales_pago_por_seguro_de_vida[i]);

								if(total_pago_por_seguro_de_vida.toFixed(2) != 0.00)
									Resumen.addRow('Pago por seguro de vida', _data[48], _data[49], _data[50], 'none', 'none', tabla);
								//Prestamo del fondo de ahorro
								var _totales_prestamo_del_fondo_de_ahorro = _data[51].split('/');
								var total_prestamo_del_fondo_de_ahorro = 0;

								for(var i=0; i<_totales_prestamo_del_fondo_de_ahorro.length; i++)
									total_prestamo_del_fondo_de_ahorro += parseFloat(_totales_prestamo_del_fondo_de_ahorro[i]);

								if(total_prestamo_del_fondo_de_ahorro.toFixed(2) != 0.00)
									Resumen.addRow('Préstamo del fondo de ahorro', _data[51], '', _data[51], 'none', 'none', tabla);
								//Prestamo de caja
								var _totales_prestamo_de_caja = _data[54].split('/');
								var total_prestamo_de_caja = 0;

								for(var i=0; i<_totales_prestamo_de_caja.length; i++)
									total_prestamo_de_caja += parseFloat(_totales_prestamo_de_caja[i]);

								if(total_prestamo_de_caja.toFixed(2) != 0.00)
									Resumen.addRow('Préstamo de caja', _data[52], _data[53], _data[54], 'none', 'none', tabla);
								//Prestamo de cliente
								var _totales_prestamo_de_cliente = _data[57].split('/');
								var total_prestamo_de_cliente = 0;

								for(var i=0; i<_totales_prestamo_de_cliente.length; i++)
									total_prestamo_de_cliente += parseFloat(_totales_prestamo_de_cliente[i]);

								if(total_prestamo_de_cliente.toFixed(2) != 0.00)
									Resumen.addRow('Préstamo de cliente', _data[55], _data[56], _data[57], 'none', 'none', tabla);
								//Prestamo de administradora
								var _totales_prestamo_de_administradora = _data[60].split('/');
								var total_prestamo_de_administradora = 0;

								for(var i=0; i<_totales_prestamo_de_administradora.length; i++)
									total_prestamo_de_administradora += parseFloat(_totales_prestamo_de_administradora[i]);

								if(total_prestamo_de_administradora.toFixed(2) != 0.00)
									Resumen.addRow('Préstamo de administradora', _data[58], _data[59], _data[60], 'none', 'none', tabla);
								//Total otros
								Resumen.addRow('Total otros', _data[61], _data[62], _data[63], 'none', '#ddd', tabla);
								tabla.appendChild(document.createElement('br'));
							}

							//Facturacion************************************************************
							var _title = 'Facturación';

							if(__honorarios_pendientes != 'true' && _ivash == 'true')
								var _title = 'Resumen';

							Resumen.addTitle(_title, false, true, tabla);
							//Total nómina
							Resumen.addRow('Nómina', _data[39], _data[40], _data[41], 'none', 'none', tabla);
							//Total contribuciones y retenciones
							Resumen.addRow('Contribuciones y retenciones', _data[79], _data[80], _data[81], 'none', 'none', tabla);
							//Prestaciones
							Resumen.addRow('Prestaciones', _data[46], '', _data[46], 'none', 'none', tabla);
							//Diferencias
							var _total_diferencias = _data[82].split('/');
							var total_diferencias = 0;

							for(var i=0; i<_total_diferencias.length; i++)
								total_diferencias += parseFloat(_total_diferencias[i]);

							if(total_diferencias.toFixed(2) != 0.00)
								Resumen.addRow('Diferencias por vacaciones y finiquito', _data[82], '', _data[82], 'none', 'none', tabla);

							//Honorarios
							if(__honorarios_pendientes != 'true' && _ivash != 'true')
							{

								if(total_ga.toFixed(2) != 0.00)
									Resumen.addRow('Gestión administrativa', _data[83], _data[84], _data[85], 'none', 'none', tabla);
								else
									Resumen.addRow('Honorarios', _data[83], _data[84], _data[85], 'none', 'none', tabla);

							}

							if(_cobrar_iva == 'true' && _ivash != 'true')
							{
								//Subtotal a facturar
								Resumen.addRow('Subtotal a facturar', _data[86], _data[87], _data[88], '1px solid #555', 'none', tabla);
								//IVA
								Resumen.addRow('IVA', _data[89], _data[90], _data[91], 'none', 'none', tabla);
							}

							//Total a facturar
							if(__honorarios_pendientes != 'true' && _ivash == 'true')
								Resumen.addRow('Total', _data[92], _data[93], _data[94], 'none', '#ddd', tabla);
							else
								Resumen.addRow('Total a facturar', _data[92], _data[93], _data[94], 'none', '#ddd', tabla);

							//Honorarios pendientes********************************************************************
							if(__honorarios_pendientes == 'true')
							{
								tabla.appendChild(document.createElement('br'));
								Resumen.addTitle('Pendiente', false, true, tabla);
								Resumen.addRow('Honorarios', _data[83], _data[84], _data[85], 'none', 'none', tabla);

								if(_cobrar_iva == 'true')
								{
									//IVA pendiente
									var iva_pen_sal = Resumen.mul(_data[83], 0.16);
									var iva_pen_ass = Resumen.mul(_data[84], 0.16);
									var iva_pending = Resumen.mul(_data[85], 0.16);
									Resumen.addRow('IVA', iva_pen_sal, iva_pen_ass, iva_pending, 'none', 'none', tabla);
									//Total pendiente
									var total_pen_sal = Resumen.sum(_data[83],iva_pen_sal);
									var total_pen_ass = Resumen.sum(_data[84],iva_pen_ass);
									var total_pending = Resumen.sum(_data[85],iva_pending);
									Resumen.addRow('Total pendiente', total_pen_sal, total_pen_ass, total_pending, 'none', '#ddd', tabla);
								}

							}

							//IVA solo por honorarios********************************************************************
							if(__honorarios_pendientes != 'true' && _ivash == 'true')
							{
								tabla.appendChild(document.createElement('br'));
								Resumen.addTitle('Honorarios e IVA', false, true, tabla);
								Resumen.addRow('Honorarios', _data[83], _data[84], _data[85], 'none', 'none', tabla);

								if(_cobrar_iva == 'true')
								{
									//IVA
									Resumen.addRow('IVA', _data[89], _data[90], _data[91], 'none', 'none', tabla);									//Total
									var total_sal = Resumen.sum(_data[83],_data[89]);
									var total_ass = Resumen.sum(_data[84],_data[90]);
									var total = Resumen.sum(_data[85],_data[91]);
									Resumen.addRow('Total', total_sal, total_ass, total, 'none', '#ddd', tabla);
								}

							}

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

						if(_class == 'Nomina_asalariados' || _class == 'Nomina_asimilables' || _class == 'IMSS')//deleting 0.00 column
						{
							var tables = document.getElementsByTagName('table');
							var i = _type == 'deposit_button' ? 4 : 6;

							for(; i<tables[0].rows[2].cells.length; i++)
							{
								var flag = true;

								for(var j=0; j<tables.length; j++)
								{

									for(var k=3; k<tables[j].rows.length; k++)
									{

										if(tables[j].rows[k].cells[i] && tables[j].rows[k].cells[i].innerHTML != '0.00')
											flag = false;

									}

								}

								if(flag && tables[0].rows[2].cells[i].innerHTML != 'ISR' && tables[0].rows[2].cells[i].innerHTML != 'Neto a recibir')
								{

									for(var j=0; j<tables.length; j++)
									{

										for(var k=2; k<tables[j].rows.length; k++)

											if(tables[j].rows[k].cells[i])
												tables[j].rows[k].removeChild(tables[j].rows[k].cells[i]);

									}

									i--;
								}

							}

						}

					}

				}

				xmltables.open('POST','get_tables.php?_class=' + _class + '&nomina=' + _id + '&_type=' + _type + '&_classify=' + _classify, true);
				xmltables.setRequestHeader("cache-control","no-cache");
				xmltables.send('');
			}

			function _format(data)
			{
				//adapted only for "salario diario"
				var _data = data.split(',');

				for(var i=0; i<_data.length; i++)
				{
					var _data_ = _data[i].split('/');
					_data_[0] = _format_(_data_[0]);
					_data[i] = _data_.join('/');
				}

				return _data.join(';  ');
			}

			function _format_(data)
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
		<script type='text/javascript'>window.opener._preview();</script>
	</body>
</html>
