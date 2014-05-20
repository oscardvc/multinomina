<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

	<head>
		<LINK href="style.css" rel="stylesheet" type="text/css" />
		<script type = "text/javascript" src = "calendar.js"></script>
		<script type = "text/javascript" src = "style.css"></script>
		<script type = "text/javascript" src = "presentation.js"></script>
	</head>

	<body>

		<div class = 'year'></div>
		<div class = 'month'></div>
		<a href = '#' onmouseover = 'dec_bright(this)' onmouseout = 'dec_opaque(this)' onclick = 'decYearUpdate()' class = 'decYear'></a>
		<a href = '#' onmouseover = 'inc_bright(this)' onmouseout = 'inc_opaque(this)' onclick = 'incYearUpdate()' class = 'incYear'></a>
		<a href = '#' onmouseover = 'dec_bright(this)' onmouseout = 'dec_opaque(this)' onclick = 'decMonthUpdate()' class = 'decMonth'></a>
		<a href = '#' onmouseover = 'inc_bright(this)' onmouseout = 'inc_opaque(this)' onclick = 'incMonthUpdate()' class = 'incMonth'></a>

		<div id = 'calendar'> <!-- Here goes the dinamic calendar data --> </div>

		<script type = "text/javascript">
			var cal = new Calendar();
			cal.generateHTML();
			var dataArea = document.getElementById('calendar');				
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
					d = '0' + d;

				if((cal.getmonth() + 1) < 10)
					var m = '0' + String(cal.getmonth() + 1);
				else
					var m = String(cal.getmonth() + 1);

				if(window.name == 'Fechas')
				{
					opener.document.getElementById(window.name).value = opener.document.getElementById(window.name).value.replace(/AAAA-MM-DD/gm,'');
					opener.document.getElementById(window.name).value += String(cal.getyear()) + '-' + m + '-' + d + ',';
				}
				else
					opener.document.getElementById(window.name).value = String(cal.getyear()) + '-' + m + '-' + d;

				window.close();
			}
		</script>
	</body>

</html>
