<?php
	include_once('cfdi_trabajador.php');
	$cfdi = new CFDI_Trabajador();
	$cfdi->set('id',$_POST['id']);
	$msg = $cfdi->cancel();
	echo $msg;
?>
