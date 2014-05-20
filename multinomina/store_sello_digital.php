<?php
	//This page is called by the entitys' forms drawn by their methods named draw
	include_once('sello_digital.php');

	$sello = new Sello_digital();
	$msg = $sello->setFromBrowser();

	if($msg == "Listo")
		$msg = $sello->dbStore($_GET['update']);

	echo "<script type='text/javascript'>
			var _iframe = window.parent.document.getElementById('{$_POST['iframe']}');
			var _div = _iframe.parentNode;
			//_iframe.style.visibility = 'visible';
			//_iframe.style.width = _div.offsetWidth + 'px';
			//_iframe.style.height = parseInt(_div.offsetHeight * 0.5) + 'px';
			//_iframe.style.display = 'block';
			//_iframe.style.position = 'absolute';
			//_iframe.style.top = '50px';
			//_iframe.style.left = '0px';
			var fieldsets = _div.previousSibling.getElementsByTagName('fieldset');

			for(var i=0; i<fieldsets.length; i++)

				if(fieldsets[i].getAttribute('class') == 'Datos_fieldset')
					var data_fieldset = fieldsets[i];
				else if(fieldsets[i].getAttribute('class') == 'Sello_digital_fieldset')
					var sello_fieldset = fieldsets[i];

			var textareas = data_fieldset.getElementsByTagName('textarea');

			for(var i=0; i<textareas.length; i++)

				if(textareas[i].getAttribute('class') == 'rfc_textarea')
				{
					var _type = 'Empresa';
					var _empresa = textareas[i].value;
					break;
				}

			for(var i=0; i<textareas.length; i++)

				if(textareas[i].getAttribute('name') == 'Empresa')
				{
					var _type = 'Sucursal';
					var _empresa = textareas[i].value;
					break;
				}

			window.parent._alert('$msg');

			if('$msg' == 'Listo')
			{

				if(_type == 'Empresa')
				{
					window.parent.get_options('EDIT', 'Empresa', 'Sello_digital', _empresa, 'First', sello_fieldset);
					window.parent._alert('$msg');
					window.parent.close_div(_iframe);
				}
				else
				{

					for(var i=0; i<textareas.length; i++)

						if(textareas[i].getAttribute('class') == 'nombre_textarea')
						{
							var _sucursal = textareas[i].value;
							break;
						}

					window.parent.get_options('EDIT', 'Sucursal', 'Sello_digital', _sucursal + '>>' + _empresa, 'First', sello_fieldset);
					window.parent._alert('$msg');
					window.parent.close_div(_iframe);
				}

			}

	</script>";
?>
