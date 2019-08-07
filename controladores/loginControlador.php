<?php
$peticionAjax ? require_once "../modelos/loginModelo.php" : require_once "./modelos/loginModelo.php";

class loginControlador extends loginModelo {
	
	public function iniciar_sesion_controlador() {
		$usuario = mainModel::limpiar_cadena($_POST['usuario']);
		$clave = mainModel::limpiar_cadena($_POST['clave']);

		$clave = mainModel::encryption($clave);

		$datosLogin = [
			'Usuario' => $usuario,
			'Clave' => $clave
		];

		$datosCuenta = loginModelo::iniciar_sesion_modelo($datosLogin);

		if($datosCuenta->rowCount() == 1) {
			$row = $datosCuenta->fetch();

			$fechaActual = date("Y-m-d");
			$yearActual = date('Y');
			$horaActual = date('h:i:s a');

			$consulta1 = mainModel::ejecutar_consulta_simple("SELECT id FROM bitacora");

			$numero = ($consulta1->rowCount())+1;

			$codigoB = mainModel::generar_codigo_aleatorio("CB", 7, $numero);

			$datosBitacora = [
				'Codigo' => $codigoB,
				'Fecha' => $fechaActual,
				'HoraInicio' => $horaActual,
				'HoraFinal' => "Sin registro",
				'Tipo' => $row['CuentaTipo'],
				'Year' => $yearActual,
				'Cuenta' => $row['CuentaCodigo']
			];

			$insertarBitacora = mainModel::guardar_bitacora($datosBitacora);
			if($insertarBitacora->rowCount() >= 1) {
				session_start(['name' => 'SBP']);
				$_SESSION['usuario_sbp'] = $row['CuentaUsuario'];
				$_SESSION['tipo_sbp'] = $row['CuentaTipo'];
				$_SESSION['privilegio_sbp'] = $row['CuentaPrivilegio'];
				$_SESSION['foto_sbp'] = $row['CuentaFoto'];
				$_SESSION['token_sbp'] = md5(uniqid(mt_rand(), true)); // Creamos un token unico para el usuario
				$_SESSION['codigo_cuenta_sbp'] = $row['CuentaCodigo'];
				$_SESSION['codigo_bitacora_sbp'] = $codigoB;

				$url = $row['CuentaTipo'] == 'Administrador' ? SERVERURL.'home/' : SERVERURL.'catalog/';
				return $urlLocation = "<script> window.location='$url' </script>";
			} else {
				$alerta = [
					'Alerta' => 'simple',
					'Titulo' => 'Ocurrió un error inesperado',
					'Texto' => 'No hemos podido iniciar la sesión por problemas técnicos, por favor, intente nuevamente',
					'Tipo' => 'error'
				];
	
				return mainModel::sweet_alert($alerta);
			}
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

	public function cerrar_sesion_controlador() {
		
	}

	public function forzar_cierre_sesion_controlador() {
		session_destroy();
		return header('Location: '.SERVERURL.'login/');
	}
}