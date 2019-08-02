<?php

$peticionAjax ? require_once "../core/configAPP.php" : require_once "./core/configAPP.php";
class mainModel {

	protected function conectar() {
		return new PDO(SGBD, USER, PASS);
	}

	protected function ejecutar_consulta_simple($consulta) {
		$respuesta = self::conectar()->prepare($consulta);
		$respuesta->execute();
		return $respuesta;
	}
}