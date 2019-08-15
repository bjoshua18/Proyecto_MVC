<?php
$peticionAjax ? require_once "../core/mainModel.php" : require_once "./core/mainModel.php";

class cuentaControlador extends mainModel {

	public function datos_cuenta_controlador($codigo) {
		$codigo = mainModel::decryption($codigo);

		return mainModel::datos_cuenta($codigo);
	}

	public function actualizar_cuenta_controlador() {
		
	}
}