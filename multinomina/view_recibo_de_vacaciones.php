<?php //This page is called by a javascript function named view at recibo_de_vacaciones.js?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

	<head>
		<script type="text/javascript" src="moneda.js"></script>
		<script type = "text/javascript">
			var font = 'normal normal normal 2.6mm Arial , sans-serif'; //weight, style, variant, size, family name, generic family
			var title_font = 'bold normal normal 2.6mm Arial , sans-serif';
		</script>
	</head>
	<body>
		<?php
			include_once('connection.php');
			include_once('recibo_de_vacaciones.php');

			if(!isset($_SESSION))
				session_start();

			$recibo = new Recibo_de_vacaciones();
			$conn = new Connection();
			$recibo->set('id',$_GET['id']);
			$recibo->setFromDb();
			$result = $conn->query("SELECT Nombre FROM Trabajador WHERE RFC = '" . $recibo->get('Trabajador') . "' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($nombre_trabajador) = $conn->fetchRow($result);
			$result = $conn->query("SELECT Registro_patronal.Numero, Empresa.Nombre, Empresa.RFC FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero LEFT JOIN Empresa ON (Registro_patronal.Empresa = Empresa.RFC OR Registro_patronal.Empresa_sucursal = Empresa.RFC) WHERE Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Empresa.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Registro_patronal.Servicio = '". $recibo->get('Servicio') . "' AND DATEDIFF('" . $recibo->get('Fecha') . "', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
			list($registro, $nombre_administradora, $administradora) = $conn->fetchRow($result);
			echo "	<script type = 'text/javascript'>
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
//				container.style.background = '#aaa';
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
				//administradora title
				var _administradora = document.createElement('span');
				container.appendChild(_administradora);
				_administradora.innerHTML = '$nombre_administradora';
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
				//RFC
				var _rfc = document.createElement('span');
				container.appendChild(_rfc);
				_rfc.innerHTML = 'R.F.C. $administradora';
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
				//titulo
				var _titulo = document.createElement('span');
				container.appendChild(_titulo);
				_titulo.innerHTML = 'RECIBO DE VACACIONES Al " . $recibo->get('Fecha') . "';
				_titulo.style.display = 'block';
				_titulo.style.position = 'relative';
				_titulo.style.padding = '0mm';
				_titulo.style.margin = '0mm';
				_titulo.style.border = 'none';
				_titulo.style.background = '#fff';
				_titulo.style.font = font;
				_titulo.style.textAlign = 'center';
				_titulo.style.color = '#555';
				_titulo.style.width = '205.9mm';
				_titulo.style.height = '3mm';
				_titulo.style.top = '10mm';
				_titulo.style.left = '5mm';
				//texto
				var _text = document.createElement('span');
				container.appendChild(_text);
				_text.innerHTML = 'Recibí de $nombre_administradora la cantidad de $" . number_format($recibo->get('Saldo'), 2, '.', ',') . " ' + covertirNumLetras(\"" . $recibo->get('Saldo') . "\") + ' por concepto de vacaciones y prima vacacional, correspondientes a " . $recibo->get('Anos_de_antiguedad') . " año(s) de servicio prestados en la empresa, de acuerdo a lo que establecen los articulos 76, 77, 80 y 81 de la ley federal del trabajo.';
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
				var datos_del_trabajador = document.createElement('table');
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
				nombre_.innerHTML = '$nombre_trabajador';
				nombre_.style.textAlign = 'left';
				var tr2 = document.createElement('tr');
				datos_del_trabajador.appendChild(tr2);
				var __rfc = document.createElement('td');
				tr2.appendChild(__rfc);
				__rfc.innerHTML = 'RFC';
				__rfc.style.textAlign = 'left';
				var rfc_ = document.createElement('td');
				tr2.appendChild(rfc_);
				rfc_.innerHTML = '" . $recibo->get('Trabajador') . "';
				rfc_.style.textAlign = 'left';
				var tr3 = document.createElement('tr');
				datos_del_trabajador.appendChild(tr3);
				var _salario = document.createElement('td');
				tr3.appendChild(_salario);
				_salario.innerHTML = 'Salario diario';
				_salario.style.textAlign = 'left';
				var salario_ = document.createElement('td');
				tr3.appendChild(salario_);
				salario_.innerHTML = '$" . number_format($recibo->get('Salario_diario'), 2, '.', ',') . "';
				salario_.style.textAlign = 'left';";
				$anos = explode(',',$recibo->get('Anos'));
				$len = count($anos);

				for($i=0; $i<$len; $i++)
					echo "var tr" . (4+$i) . " = document.createElement('tr');
				datos_del_trabajador.appendChild(tr" . (4+$i) . ");
				var _anos" . (4+$i) . " = document.createElement('td');
				tr" . (4+$i) . ".appendChild(_anos" . (4+$i) . ");
				_anos" . (4+$i) . ".innerHTML = 'Años de antigüedad (" . $anos[$i] . ")';
				_anos" . (4+$i) . ".style.textAlign = 'left';
				var anos" . (4+$i) . "_ = document.createElement('td');
				tr" . (4+$i) . ".appendChild(anos" . (4+$i) . "_);
				anos" . (4+$i) . "_.innerHTML = '" . $recibo->get('Anos_de_antiguedad') . "';
				anos" . (4+$i) . "_.style.textAlign = 'left';";

				$dias_de_vacaciones = explode(',',$recibo->get('Dias_de_vacaciones'));

				for($i=0; $i<$len; $i++)
					echo "var tr" . (5+$i+$len) . " = document.createElement('tr');
				datos_del_trabajador.appendChild(tr" . (5+$i+$len) . ");
				var _dias" . (5+$i+$len) . " = document.createElement('td');
				tr" . (5+$i+$len) . ".appendChild(_dias" . (5+$i+$len) . ");
				_dias" . (5+$i+$len) . ".innerHTML = 'Dias de vacaciones (" . $anos[$i] . ")';
				_dias" . (5+$i+$len) . ".style.textAlign = 'left';
				var dias" . (5+$i+$len) . "_ = document.createElement('td');
				tr" . (5+$i+$len) . ".appendChild(dias" . (5+$i+$len) . "_);
				dias" . (5+$i+$len) . "_.innerHTML = ' ' + " . $dias_de_vacaciones[$i] . ";
				dias" . (5+$i+$len) . "_.style.textAlign = 'left';";

				echo "var trx = document.createElement('tr');
				datos_del_trabajador.appendChild(trx);
				var _fecha = document.createElement('td');
				trx.appendChild(_fecha);
				_fecha.innerHTML = 'Fecha de pago ';
				_fecha.style.textAlign = 'left';
				var fecha_ = document.createElement('td');
				trx.appendChild(fecha_);
				fecha_.innerHTML = '" . $recibo->get('Fecha') . "';
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
				datos_del_trabajador.style.top = '20mm';
				datos_del_trabajador.style.left = '5mm';
				datos_del_trabajador.style.width = '90mm';
				datos_del_trabajador.style.height = '50mm';
				//tabla de vacaciones
				var vacaciones = document.createElement('table');
				var tr_titulo = document.createElement('tr');
				vacaciones.appendChild(tr_titulo);
				var td_percepciones = document.createElement('td');
				tr_titulo.appendChild(td_percepciones);
				td_percepciones.style.background = '#eee';
				td_percepciones.setAttribute('colspan',2);
				td_percepciones.innerHTML = 'Percepciones';
				var td_deducciones = document.createElement('td');
				tr_titulo.appendChild(td_deducciones);
				td_deducciones.style.background = '#eee';
				td_deducciones.setAttribute('colspan',2);
				td_deducciones.innerHTML = 'Deducciones';
				var _anios = '" . $recibo->get('Anos') . "';
				var anios_ = _anios.split(',');
				var _vacaciones = '" . $recibo->get('Vacaciones') . "';
				var vacaciones_ = _vacaciones.split(',');

				for(var i=0; i<vacaciones_.length; i++)
				{
					var tr_vacaciones = document.createElement('tr');
					vacaciones.appendChild(tr_vacaciones);
					var td_vacaciones = document.createElement('td');
					tr_vacaciones.appendChild(td_vacaciones);
					td_vacaciones.innerHTML = 'Vacaciones (' + anios_[i] + ')';
					var td_vacaciones_ = document.createElement('td');
					tr_vacaciones.appendChild(td_vacaciones_);
					var _aux = Math.round(vacaciones_[i] * 100) / 100;
					var aux_ = _aux.toFixed(2);
					td_vacaciones_.innerHTML = (i == 0 ? '$' : '') + _format(aux_.toString());
					td_vacaciones_.style.textAlign = 'right';

					if(i == 0)
					{
						var td_isr = document.createElement('td');
						tr_vacaciones.appendChild(td_isr);
						td_isr.innerHTML = 'ISR';
						var td_isr_ = document.createElement('td');
						tr_vacaciones.appendChild(td_isr_);
						td_isr_.innerHTML = '$' + '" . number_format($recibo->get('ISR'), 2, '.', ',') . "';
						td_isr_.style.textAlign = 'right';
					}

				}

				var _prima = '" . $recibo->get('Prima_vacacional') . "';
				var prima_ = _prima.split(',');

				for(var i=0; i<prima_.length; i++)
				{
					var tr_prima = document.createElement('tr');
					vacaciones.appendChild(tr_prima);
					var td_prima = document.createElement('td');
					tr_prima.appendChild(td_prima);
					td_prima.innerHTML = 'Prima vacacional (' + anios_[i] + ')';
					var td_prima_ = document.createElement('td');
					tr_prima.appendChild(td_prima_);
					var _aux = Math.round(prima_[i] * 100) / 100;
					var aux_ = _aux.toFixed(2);
					td_prima_.innerHTML = _format(aux_.toString());
					td_prima_.style.textAlign = 'right';
				}

				var _compensacion = '" . $recibo->get('Compensacion') . "';

				if(_compensacion > 0)
				{
					var tr_compensacion = document.createElement('tr');
					vacaciones.appendChild(tr_compensacion);
					var td_compensacion = document.createElement('td');
					tr_compensacion.appendChild(td_compensacion);
					td_compensacion.innerHTML = 'Compensación';
					var td_compensacion_ = document.createElement('td');
					tr_compensacion.appendChild(td_compensacion_);
					td_compensacion_.innerHTML = _format(_compensacion);
					td_compensacion_.style.textAlign = 'right';
				}

				var tr_total = document.createElement('tr');
				vacaciones.appendChild(tr_total);
				var td_total_percepciones = document.createElement('td');
				tr_total.appendChild(td_total_percepciones);
				td_total_percepciones.innerHTML = 'Total de percepciones';
				var td_total_percepciones_ = document.createElement('td');
				tr_total.appendChild(td_total_percepciones_);
				td_total_percepciones_.innerHTML = '$" . number_format($recibo->get('Total_de_percepciones'), 2, '.', ',') . "';
				td_total_percepciones_.style.textAlign = 'right';
				td_total_percepciones_.style.borderTop = '1px solid #666';
				var td_total_deducciones = document.createElement('td');
				tr_total.appendChild(td_total_deducciones);
				td_total_deducciones.innerHTML = 'Total de deducciones';
				var td_total_deducciones_ = document.createElement('td');
				tr_total.appendChild(td_total_deducciones_);
				td_total_deducciones_.innerHTML = '$" . number_format($recibo->get('ISR'), 2, '.', ',') . "';
				td_total_deducciones_.style.textAlign = 'right';
				td_total_deducciones_.style.borderTop = '1px solid #666';
				vacaciones.appendChild(document.createElement('br'));
				vacaciones.appendChild(document.createElement('br'));
				var tr_saldo = document.createElement('tr');
				vacaciones.appendChild(tr_saldo);
				var td_saldo = document.createElement('td');
				tr_saldo.appendChild(td_saldo);
				td_saldo.innerHTML = 'Total a percibir';
				var td_saldo_ = document.createElement('td');
				tr_saldo.appendChild(td_saldo_);
				td_saldo_.innerHTML = '$" . number_format($recibo->get('Saldo'), 2, '.', ',') . "';
				td_saldo_.style.textAlign = 'right';
				vacaciones.style.font = font;
				vacaciones.style.color = '#555';
				vacaciones.style.border = '1px solid #555';
				vacaciones.style.borderRadius = '10px';
				vacaciones.style.MozBorderRadius = '10px';
				vacaciones.style.WebkitBorderRadius = '10px';
				container.appendChild(vacaciones);
				vacaciones.style.display = 'block';
				vacaciones.style.position = 'relative';
				vacaciones.style.padding = '0mm';
				vacaciones.style.margin = '0mm';
				vacaciones.style.top = '-30mm';
				vacaciones.style.left = '100mm';
				vacaciones.style.width = '110.9mm';
				vacaciones.style.height = '50mm';
				var _trabajador = document.createElement('span');
				_trabajador.innerHTML = '$nombre_trabajador';
				_trabajador.style.display = 'block';
				_trabajador.style.position = 'relative';
				_trabajador.style.padding = '0mm';
				_trabajador.style.margin = '0mm';
				_trabajador.style.background = 'none';
				_trabajador.style.font = font;
				_trabajador.style.top = '-14mm';
				_trabajador.style.left = '0mm';
				_trabajador.style.width = '215.9mm';
				_trabajador.style.height = '8mm';
				_trabajador.style.color = '#666';
				_trabajador.style.textAlign = 'center';
				_trabajador.style.borderBottom = '1px dotted #666';
				container.appendChild(_trabajador);
				</script>
				";
		?>
	</body>
</html>
