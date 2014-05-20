<?php
	//This page is called by the entity form
	include_once('logo.php');

	$logo = new Logo();
	$logo->setFromBrowser();
	$logo->dbStore();
	$aux = rand();
	echo "<script type='text/javascript'>
			var _iframe = window.parent.document.getElementById('{$_POST['iframe']}');
			var _div = _iframe.parentNode;
			var images = _div.previousSibling.getElementsByTagName('img');

			for(var i=0; i<images.length; i++)

				if(images[i].getAttribute('class') == 'logo_image')
				{

					if(images[i].getAttribute('empresa') == '{$logo->get('Empresa')}')
					{
						window.parent.show_logo(images[i].parentNode);
						break;
					}
					else if(images[i].getAttribute('sucursal') == '{$logo->get('Sucursal')}' && images[i].getAttribute('Empresa_sucursal') == '{$logo->get('Empresa_sucursal')}')
					{
						window.parent.show_logo(images[i].parentNode);
						break;
					}

				}

			window.parent.close_div(_iframe);
	</script>";
?>
