<?php
$peticionAjax ? require_once "../modelos/loginModelo.php" : require_once "./modelos/loginModelo.php";

class loginControlador extends loginModelo {
	
	public function inciar_sesion_controlador() {
		$usuario = mainModel::limpiar_cadena($_POST['usuario']);
		$clave = mainModel::limpiar_cadena($_POST['clave']);

		$clave = mainModel::encryption($clave);

		$datosLogin = [
			'Usuario' => $usuario,
			'Clave' => $clave
		];

		$datosCuenta = loginModelo::inciar_sesion_modelo($datosLogin);

		if($datosCuenta->rowCount() == 1) {
			
		} else {
			$alerta = [
				'Alerta' => 'simple',
				'Titulo' => 'Ocurrió un error inesperado',
				'Texto' => 'El nombre de usuario o contraseña no son correctos o su cuenta puede estar deshabilitada',
				'Tipo' => 'error'
			];

			return mainModel::sweet_alert($alerta);
		}
	}
}