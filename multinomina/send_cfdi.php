<?php
	include_once('cfdi_trabajador.php');
	$cfdi = new CFDI_Trabajador();
	$cfdi->set('id','1');//$_POST['id']);
	$msg = $cfdi->send();
	echo $msg;
?>
