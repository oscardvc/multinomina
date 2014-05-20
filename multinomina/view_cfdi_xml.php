<?php
//This page is called by a javascript function named view_cfdi_xml at cfdi.js
	include_once('cfdi_trabajador.php');

	if(!isset($_SESSION))
		session_start();

	$cfdi = new CFDI_Trabajador();
	$cfdi->set('id', $_GET['id']);
	header("Content-Type:text/xml");
	$cfdi->getXML();
?>
