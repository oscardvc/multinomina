<?php
//This page is called by a javascript function named view_cfdi_print at cfdi.js
	include_once('cfdi_trabajador.php');
	$cfdi = new CFDI_Trabajador();
	$cfdi->set('id', $_GET['id']);
	header("Content-Type:text/xml");
	$cfdi->getPrint();
?>
