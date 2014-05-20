<?php
//This page is called by a javascript function named view_cfdi_cancel at cfdi.js
	include_once('cfdi_trabajador.php');
	$cfdi = new CFDI_Trabajador();
	$cfdi->set('id', $_GET['id']);
	$cfdi->setFromDB();
	header("Content-Type:text/xml");
	echo $cfdi->get('Acuse_cancelacion');
?>
