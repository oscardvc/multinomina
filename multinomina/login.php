<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>.:::.</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<script type="text/javascript" src="presentation.js"></script>
		<script type="text/javascript" src="menus.js"></script>
		<script type="text/javascript" src="general.js"></script>
	</head>
	<body onload = "fit_login()">
		<form class = "show_form">
			<div></div>
			<label class = "usuario_label">Usuario</label><input type = "text" class = "usuario_input" name = "Usuario" title = "Usuario" required = true/>
			<label class = "cuenta_label">Cuenta</label><input type = "text" class = "cuenta_input" name = "Cuenta" title = "Cuenta" required = true/>
			<label class = "contrasena_label">Contraseña</label><input type = "password" class = "contrasena_input" name = "Contrasena" title = "Contraseña" required = true />
			<img class = "submit_button" onclick = "_login()"/> <!-- _login() at menu.js -->
		</form>

	</body>
</html>
