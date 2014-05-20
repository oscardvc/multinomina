<?php //This page is called from an ajax function named timbrar_vacaciones at recibo_de_vacaciones.js
	include_once('recibo_de_vacaciones.php');
	$recibo = new Recibo_de_vacaciones();
	$recibo->set('id',$_POST['recibo']);
	echo $recibo->timbrar_recibo();
?>
