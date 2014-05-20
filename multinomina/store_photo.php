<?php
	//This page is called by the entity form
	include_once('photo.php');

	$photo = new Photo();
	$photo->set_photo();
	$aux = rand();
	echo "<script type='text/javascript'>
			var _iframe = window.parent.document.getElementById('{$_POST['iframe']}');
			var _div = _iframe.parentNode;
			var images = _div.previousSibling.getElementsByTagName('img');

			for(var i=0; i<images.length; i++)

				if(images[i].getAttribute('class') == 'photo_image' && images[i].getAttribute('trabajador') == '{$photo->get('Trabajador')}')
				{
					images[i].src = 'get_photo.php?rfc={$photo->get('Trabajador')}&aux=$aux';
					break;
				}

			window.parent.close_div(_iframe);
	</script>";
?>
