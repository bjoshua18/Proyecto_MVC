<!DOCTYPE html>
<html lang="es">

<head>
	<title>Inicio</title>
	<meta charset="UTF-8">
	<meta name="viewport"
		content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="<?=SERVERURL?>vistas/css/main.css">
	<!--====== Scripts -->
	<?php include('vistas/modulos/scripts.php'); ?>
</head>

<body>
	<?php
		$peticionAjax = false;

		require_once './controladores/vistasControlador.php';

		$vt = new vistasControlador();
		$vistasR = $vt->obtener_vistas_controlador();

		if($vistasR == 'login' || $vistasR == '404'):
			if($vistasR == 'login') {
				require_once('./vistas/contenidos/login-view.php');
			} else {
				require_once('./vistas/contenidos/404-view.php');
			}
		else:
			session_start(['name' => 'SBP']);

			require_once './controladores/loginControlador.php';

			$lc = new loginControlador();
			if(!isset($_SESSION['token_sbp']) || !isset($_SESSION['usuario_sbp']))
				$lc->forzar_cierre_sesion_controlador();
	?>
		<!-- SideBar -->
		<?php include('vistas/modulos/sidebar.php'); ?>

		<!-- Content page-->
		<section class="full-box dashboard-contentPage">
			<!-- NavBar -->
			<?php include('vistas/modulos/navbar.php'); ?>

			<!-- Content page -->
			<?php require_once($vistasR) ?>
		</section>

	<?php endif; ?>

</body>

</html>