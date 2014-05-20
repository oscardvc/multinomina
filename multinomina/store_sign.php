<?php
	//This page is called by the entity form
	include_once('sign.php');

	$sign = new Sign();
	$sign->setFromBrowser();
	$sign->dbStore();
	$aux = rand();
	echo "<script type='text/javascript'>
			var _iframe = window.parent.document.getElementById('{$_POST['iframe']}');
			var _div = _iframe.parentNode;
			var images = _div.previousSibling.getElementsByTagName('img');

			for(var i=0; i<images.length; i++)

				if(images[i].getAttribute('class') == 'sign_image')
				{

					if(images[i].getAttribute('trabajador') && images[i].getAttribute('trabajador') == '{$sign->get('Trabajador')}')
					{
						window.parent.show_sign(images[i]);//at menu.js
						break;
					}
					else if(images[i].getAttribute('usuario') && images[i].getAttribute('usuario') == '{$sign->get('Usuario')}')//user sign
					{
						window.parent.show_sign(images[i]);//at menu.js
						break;
					}

				}

			window.parent.close_div(_iframe);
	</script>";
?>
