<?php

$peticionAjax ? require_once "../modelos/administradorModelo" : require_once "./modelos/administradorModelo";

class administradorControlador extends administradorModelo {
	public function agregar_administrador_controlador() {
		$dni = mainModel::limpiar_cadena($_POST['dni-reg']);
		$nombre = mainModel::limpiar_cadena($_POST['nombre-reg']);
		$apellido = mainModel::limpiar_cadena($_POST['apellido-reg']);
		$telefono = mainModel::limpiar_cadena($_POST['telefono-reg']);
		$direccion = mainModel::limpiar_cadena($_POST['direccion-reg']);

		$usuario = mainModel::limpiar_cadena($_POST['usuario-reg']);
		$password1 = mainModel::limpiar_cadena($_POST['password1-reg']);
		$password2 = mainModel::limpiar_cadena($_POST['password2-reg']);
		$email = mainModel::limpiar_cadena($_POST['email-reg']);
		$genero = mainModel::limpiar_cadena($_POST['genero-reg']);
		$privilegio = mainModel::limpiar_cadena($_POST['privilegio-reg']);

		// Foto de perfil por defecto segun genero
		if($genero === 'Masculino')
			$foto = 'Male3Avatar.png';
		else
			$foto = 'Female3Avatar.png';

		// Comprobacion del password
		if($password1 != $password2) {
			$alerta = [
				'Alerta' => 'simple',
				'Titulo' => 'Ocurrió un error inesperado',
				'Texto' => 'Las contraseñas que acabas de ingresar no coinciden, por favor, intenta nuevamente',
				'Tipo' => 'error'
			];
		} else {
			// Comprobación de DNI
			$consulta1 = mainModel::ejecutar_consulta_simple("SELECT AdminDNI FROM admin WHERE AdminDNI='$dni'");
			if($consulta1->rowCount() >= 1) {
				$alerta = [
					'Alerta' => 'simple',
					'Titulo' => 'Ocurrió un error inesperado',
					'Texto' => 'El DNI que acaba de ingresar ya se encuentra registrado en el sistema',
					'Tipo' => 'error'
				];
			} else {
				// Comprobacion de email
				if($email != '') {
					$consulta2 = mainModel::ejecutar_consulta_simple("SELECT CuentaEmail FROM cuenta WHERE CuentaEmail='$email'");
					$ec = $consulta2->rowCount();
				} else {
					$ec = 0;
				}

				if($ec >= 1) {
					$alerta = [
						'Alerta' => 'simple',
						'Titulo' => 'Ocurrió un error inesperado',
						'Texto' => 'El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema',
						'Tipo' => 'error'
					];
				} else {
					// Comprobacion de usuario
					$consulta3 = mainModel::ejecutar_consulta_simple("SELECT CuentaUsuario FROM cuenta WHERE CuentaUsuario='$usuario'");
					if($consulta3->rowCount() >= 1) {
						$alerta = [
							'Alerta' => 'simple',
							'Titulo' => 'Ocurrió un error inesperado',
							'Texto' => 'El USUARIO que acaba de ingresar ya se encuentra registrado en el sistema',
							'Tipo' => 'error'
						];
					}
				}
			}
		}

		return mainModel::sweet_alert($alerta);

	}
}