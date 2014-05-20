<?php //This page is called from an ajax function named timbrar_nomina at nomina.js
	include_once('nomina.php');
	$nomina = new Nomina();
	$nomina->set('id',$_POST['nomina']);
	echo $nomina->timbrar_nomina();
?>
