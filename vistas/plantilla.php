<?php
	session_start();
	$peticionAjax = false;
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<title>Inicio</title>
	<meta charset="UTF-8">
	<meta name="viewport"
		content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="./vistas/css/main.css">
</head>

<body>
	<?php
		$vt = new vistasControlador();
		$vistasR = $vt->obtener_vistas_controlador();

		if($vistasR == 'login'):
			require_once('./vistas/contenidos/login-view.php');
		else:
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

	<!--====== Scripts -->
	<?php include('vistas/modulos/scripts.php'); ?>
</body>

</html>