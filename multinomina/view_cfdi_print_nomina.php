<?php
//This page is called by a javascript function named view_cfdi_print_nomina at nomina.js
	include_once('nomina.php');
	$nomina = new Nomina();
	$nomina->set('id', $_GET['id']);
	header("Content-Type:text/xml");
	$nomina->getCFDIPrint();
?>
