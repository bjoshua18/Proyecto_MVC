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

	public function encryption($string) {
		$output = false;
		$key = hash('sha256', SECRET_KEY);
		$iv = substr(hash('sha256', SECRET_IV), 0, 16);
		$output = openssl_encrypt($string, METHOD, $key, 0, $iv);
		$output = base64_encode($output);
		return $output;
	}

	protected function decryption($string) {
		$key = hash('sha256', SECRET_KEY);
		$iv = substr(hash('sha256', SECRET_IV), 0, 16);
		$output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
		return $output;
	}

	protected function generar_codigo_aleatorio($letra, $longitud, $num) {
		for($i = 1; $i < $longitud; $i++) {
			$numero = rand(0,9);
			$letra .= $numero;
		}

		return $letra . $num;
	}
}