<?php

$peticionAjax = true;
require_once '../core/configGeneral.php';

if(isset($_GET['Token'])) {
	require_once '../controladores/loginControlador.php';
	$logout = new loginCOntrolador();
	echo $logout->cerrar_sesion_controlador();
} else {
	session_start();
	session_destroy();
	echo '<script> window.location.href = "'.SERVERURL.'login/" </script>';
}