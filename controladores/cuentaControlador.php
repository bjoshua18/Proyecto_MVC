<?php
$peticionAjax ? require_once "../core/mainModel.php" : require_once "./core/mainModel.php";

class cuentaControlador extends mainModel {

	public function datos_cuenta_controlador($codigo) {
		$codigo = mainModel::decryption($codigo);

		return mainModel::datos_cuenta($codigo);
	}

	public function actualizar_cuenta_controlador() {
		$CuentaCodigo = mainModel::decryption($_POST['CodigoCuenta-up']);
		$CuentaTipo = mainModel::decryption($_POST['tipoCuenta-up']);

		$query1 = mainModel::ejecutar_consulta_simple("SELECT * FROM cuenta WHERE CuentaCodigo='$CuentaCodigo'");
		$DatosCuenta = $query1->fetch();

		$user = mainModel::limpiar_cadena($_POST['user-log']);
		$password = mainModel::limpiar_cadena($_POST['password-log']);
		$password = mainModel::encryption($password);

		// Comprobamos que los datos de ingreso no esten vacios
		if($user != '' && $password != '') {
			if(isset($_POST['privilegio-up'])) { // Caso en que el administrador no es el dueño de la cuenta a actualizar
				$login = mainModel::ejecutar_consulta_simple("SELECT id FROM cuenta WHERE CuentaUsuario='$user' AND CuentaClave='$password'");
			} else { // Caso en que el administrador es el dueño de la cuenta a actualizar
				$login = mainModel::ejecutar_consulta_simple("SELECT id FROM cuenta WHERE CuentaUsuario='$user' AND CuentaClave='$password' AND CuentaCodigo='$CuentaCodigo'");
			}
			
			// Caso en que los datos de ingreso son erroneos
			if($login->rowCount() == 0) {
				$alerta = [
					'Alerta' => 'simple',
					'Titulo' => 'Ocurrió un error inesperado',
					'Texto' => 'El nombre de usuario o clave que acaba de ingresar no coinciden con los datos de su cuenta',
					'Tipo' => 'error'
				];
				return mainModel::sweet_alert($alerta);
				exit();
			}
		} else {
			$alerta = [
				'Alerta' => 'simple',
				'Titulo' => 'Ocurrió un error inesperado',
				'Texto' => 'Para actualizar los datos de la cuenta, debe ingresar el nombre de usuario y clave. Por favor, ingrese los datos e intente nuevamente',
				'Tipo' => 'error'
			];
			return mainModel::sweet_alert($alerta);
			exit();
		}

		// USUARIO
		$CuentaUsuario = mainModel::limpiar_cadena($_POST['usuario-up']);

		// Comprobamos si el nuevo nombre de usuario es distinto al de la db
		if($CuentaUsuario != $DatosCuenta['CuentaUsuario']) {
			$query2 = mainModel::ejecutar_consulta_simple("SELECT CuentaUsuario FROM cuenta WHERE CuentaUsuario='$CuentaUsuario'");

			// Comprobamos si el nuevo nombre de usuario ya esta en la db
			if($query2->rowCount() >= 1) {
				$alerta = [
					'Alerta' => 'simple',
					'Titulo' => 'Ocurrió un error inesperado',
					'Texto' => 'El nombre de usuario que acaba de ingresar ya se encuentra registrado en el sistema',
					'Tipo' => 'error'
				];
				return mainModel::sweet_alert($alerta);
				exit();
			}
		}

		// EMAIL
		$CuentaEmail = mainModel::limpiar_cadena($_POST['email-up']);

		// Comprobamos si el nuevo email es distinto al de la db
		if($CuentaEmail != $DatosCuenta['CuentaEmail']) {
			$query3 = mainModel::ejecutar_consulta_simple("SELECT CuentaEmail FROM cuenta WHERE CuentaEmail='$CuentaEmail'");

			// Comprobamos si el nuevo email ya esta en la db
			if($query3->rowCount() >= 1) {
				$alerta = [
					'Alerta' => 'simple',
					'Titulo' => 'Ocurrió un error inesperado',
					'Texto' => 'El email que acaba de ingresar ya se encuentra registrado en el sistema',
					'Tipo' => 'error'
				];
				return mainModel::sweet_alert($alerta);
				exit();
			}
		}

		// GENERO
		$CuentaGenero = mainModel::limpiar_cadena($_POST['optionsGenero-up']);

		// ESTADO
		if(isset($_POST['optionsEstado-up'])) { // Comprobamos que el estado este definido
			$CuentaEstado = mainModel::limpiar_cadena($_POST['optionsEstado-up']);
		} else {
			$CuentaEstado = $DatosCuenta['CuentaEstado'];
		}

		// PRIVILEGIO

		// Comprobamos que el usuario es administrador
		if($CuentaTipo == 'admin') {
			if(isset($_POST['optionsPrivilegio-up'])) { // Comprobamos que el privilegio este definido
				$CuentaPrivilegio = mainModel::decryption($_POST['optionsPrivilegio-up']);
			} else {
				$CuentaPrivilegio = $DatosCuenta['CuentaPrivilegio'];
			}

			// Configuramos la imagen segun el genero
			if($CuentaGenero == 'Masculino') {
				$CuentaFoto = 'Male3Avatar.png';
			} else {
				$CuentaFoto = 'Female3Avatar.png';
			}
		} else {
			$CuentaPrivilegio = $DatosCuenta['CuentaPrivilegio'];
			// Configuramos la imagen segun el genero
			if($CuentaGenero == 'Masculino') {
				$CuentaFoto = 'Male2Avatar.png';
			} else {
				$CuentaFoto = 'Female2Avatar.png';
			}
		}

		// PASSWORD
		$passwordN1 = mainModel::limpiar_cadena($_POST['newPassword1-up']);
		$passwordN2 = mainModel::limpiar_cadena($_POST['newPassword2-up']);

		if($passwordN1 != '' || $passwordN2 != '') {
			if($passwordN1 == $passwordN2) {
				$CuentaClave = mainModel::encryption($passwordN1);
			} else {
				$alerta = [
					'Alerta' => 'simple',
					'Titulo' => 'Ocurrió un error inesperado',
					'Texto' => 'Las nuevas contraseñas no coinciden, por favor, verifique los datos e intente nuevamente',
					'Tipo' => 'error'
				];
				return mainModel::sweet_alert($alerta);
				exit();
			}
		} else {
			$CuentaClave = $DatosCuenta['CuentaClave'];
		}

		// ENVIANDO DATOS AL MODELO
		$datosUpdate = [
			'CuentaPrivilegio' => $CuentaPrivilegio,
			'CuentaCodigo' => $CuentaCodigo,
			'CuentaUsuario' => $CuentaUsuario,
			'CuentaClave' => $CuentaClave,
			'CuentaEmail' => $CuentaEmail,
			'CuentaEstado' => $CuentaEstado,
			'CuentaGenero' => $CuentaGenero,
			'CuentaFoto' => $CuentaFoto
		];

		if(mainModel::actualizar_cuenta($datosUpdate)) {
			if(!isset($_POST['privilegio-up'])) {
				session_start(['name' => 'SBP']);
				$_SESSION['usuario_sbp'] = $CuentaUsuario;
				$_SESSION['foto_sbp'] = $CuentaFoto;
			}

			$alerta = [
				'Alerta' => 'recargar',
				'Titulo' => 'Cuenta actualizada',
				'Texto' => 'Los datos de la cuenta se actualizaron con éxito',
				'Tipo' => 'success'
			];
		} else {
			$alerta = [
				'Alerta' => 'simple',
				'Titulo' => 'Ocurrió un error inesperado',
				'Texto' => 'Lo sentimos, no hemos podido actualizar los datos de la cuenta',
				'Tipo' => 'error'
			];
		}

		return mainModel::sweet_alert($alerta);
	}
}