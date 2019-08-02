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

	protected function limpiar_cadena($cadena) {
		$cadena = trim($cadena); // Elimina los espacios en blanco en los extremos
		$cadena = stripslashes($cadena); // Elimina las barras invertidas
		$cadena = str_ireplace('<script>', '', $cadena); // Busca en la string del tercer parámetro la string del primer parámetro
		$cadena = str_ireplace('</script>', '', $cadena); // y la reemplaza por la del segundo
		$cadena = str_ireplace('<script src', '', $cadena);
		$cadena = str_ireplace('<script type=', '', $cadena);
		$cadena = str_ireplace('SELECT * FROM', '', $cadena);
		$cadena = str_ireplace('DELETE FROM', '', $cadena);
		$cadena = str_ireplace('INSERT INTO', '', $cadena);
		$cadena = str_ireplace('--', '', $cadena);
		$cadena = str_ireplace('^', '', $cadena);
		$cadena = str_ireplace('[', '', $cadena);
		$cadena = str_ireplace(']', '', $cadena);
		$cadena = str_ireplace('==', '', $cadena);
		$cadena = str_ireplace(';', '', $cadena);

		return $cadena;
	}

	protected function sweet_alert($datos) { // Usamos un plugin llamado sweetalert2
		if($datos['Alerta'] == 'simple') {
			$alerta = "
				<script>
					swal(
						'{$datos["Titulo"]}',
						'{$datos["Texto"]}',
						'{$datos["Tipo"]}'
					)
				</script>
			";
		} elseif ($datos['Alerta'] == 'recargar') {
			$alerta = "
				<script>
					swal({
						title: '{$datos["Titulo"]}',
						text: '{$datos["Texto"]}',
						type: '{$datos["Tipo"]}',
						confirmButtonText: 'Aceptar'
					}).then(() => {
							location.reload();
						}
					})
				</script>
			";
		} elseif ($datos['Alerta'] == 'limpiar') {
			$alerta = "
				<script>
					swal({
						title: '{$datos["Titulo"]}',
						text: '{$datos["Texto"]}',
						type: '{$datos["Tipo"]}',
						confirmButtonText: 'Aceptar'
					}).then(() => {
							$('.FormularioAjax')[0].reset();
						}
					})
				</script>
			";
		}
	}
}