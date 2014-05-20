<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>.:::.</title>
		<LINK href="style.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="presentation.js"></script>
		<script type = "text/javascript" src="menus.js"></script>
		<script type = "text/javascript" src="general.js"></script>
		<script type = "text/javascript" src = "tabs.js"></script>
		<script type = "text/javascript" src = "entities.js"></script>
		<script type = "text/javascript" src = "calendar.js"></script>
		<script type = "text/javascript" src = "nomina.js"></script>
		<script type = "text/javascript" src = "servicio.js"></script>
		<script type = "text/javascript" src = "aguinaldo.js"></script>
		<script type = "text/javascript" src = "recibo_de_vacaciones.js"></script>
		<script type = "text/javascript" src = "finiquito.js"></script>
		<script type = "text/javascript" src = "cfdi.js"></script>
	</head>

	<?php
		session_start();

		if(isset($_SESSION['usuario']))
		{
			echo '	<body onload = "fit_screen()">
		<div id = "header"><div id = "_name">' . $_SESSION['cuenta'] . '</div><div id = "_user">' . $_SESSION['usuario'] . '</div><img id = "logo" /><img id = "cfdi_img" title = "CFDI" onclick = "show_submenu(\'cfdi\')"/><img id = "nomina_img" title = "Nómina" onclick = "show_submenu(\'nomina\')"/><img id = "trabajador_img" title = "Trabajadores" onclick = "_load(\'Trabajador\')"/><img id = "empresas_img" title = "Empresas" onclick = "_load(\'Empresa\')"/><img id = "config_img" title = "Config" onclick = "show_submenu(\'config\')"/></div>
		<div id = "container">
			<div class = "options"></div>
		</div>
		<div id = "calendar">
			<img onclick = "decYearUpdate()" class = "decYear"><div class = "year">Año</div><img onclick = "incYearUpdate()" class = "incYear"/><img onclick = "decMonthUpdate()" class = "decMonth"/><div class = "month">Mes</div><img onclick = "incMonthUpdate()" class = "incMonth"/><img class = "close" onclick = "close_calendar()" title = "Cerrar calendario"/>
			<div class = "data"> <!-- Here goes the dinamic calendar data --> </div>
		</div>

		<script type = "text/javascript">
			var cal = new Calendar();
			cal.generateHTML();
			var _calendar = document.getElementById("calendar");
			var divs = _calendar.getElementsByTagName("div");

			for(var i=0; i<divs.length; i++)

				if(divs[i].getAttribute("class") == "data")
				{
					var dataArea = divs[i];
					break;
				}

			dataArea.innerHTML = cal.getHTML();

			function decYearUpdate()
			{
				cal.decYear();
				cal.generateHTML();
				dataArea.innerHTML = cal.getHTML();
			}

			function incYearUpdate()
			{
				cal.incYear();
				cal.generateHTML();
				dataArea.innerHTML = cal.getHTML();
			}

			function decMonthUpdate()
			{
				cal.decMonth();
				cal.generateHTML();
				dataArea.innerHTML = cal.getHTML();
			}

			function incMonthUpdate()
			{
				cal.incMonth();
				cal.generateHTML();
				dataArea.innerHTML = cal.getHTML();
			}

			function exit(d)
			{
				if (Number(d)<10)
					d = "0" + d;

				if((cal.getmonth() + 1) < 10)
					var m = "0" + String(cal.getmonth() + 1);
				else
					var m = String(cal.getmonth() + 1);

				var _calendar = document.getElementById("calendar");

				if(_calendar.op.previousSibling.previousSibling.innerHTML == "Faltas" || _calendar.op.previousSibling.previousSibling.innerHTML == "Prima dominical")
				{
					_calendar.op.previousSibling.value = _calendar.op.previousSibling.value.replace(/AAAA-MM-DD/gm,"");
					_calendar.op.previousSibling.value += String(cal.getyear()) + "-" + m + "-" + d + ",";
				}
				else
					_calendar.op.previousSibling.value = String(cal.getyear()) + "-" + m + "-" + d;

				close_calendar();
			}

		</script>';
		}
		else
		{
			echo '<body>Es necesario iniciar sesión';
		}

	?>
	</body>
</html>
