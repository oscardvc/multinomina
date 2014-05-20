<?php
	//This page is called by a java script at nomina.js to download cfdi related to the owner nomina
	include_once('nomina.php');
	$nomina = new Nomina();
	$nomina->set('id', $_GET['id']);
	$nomina->download_cfdi();
?>
