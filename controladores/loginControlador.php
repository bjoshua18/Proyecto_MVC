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
}