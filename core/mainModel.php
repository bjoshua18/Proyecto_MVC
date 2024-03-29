<?php

$peticionAjax ? require_once "../core/configAPP.php" : require_once "./core/configAPP.php";
class mainModel {

	// FUNCIONES DB

	protected function conectar() {
		return new PDO(SGBD, USER, PASS);
	}

	protected function ejecutar_consulta_simple($consulta) {
		$respuesta = self::conectar()->prepare($consulta);
		$respuesta->execute();
		return $respuesta;
	}

	// FUNCIONES CUENTA

	protected function agregar_cuenta($datos) {
		$sql = self::conectar()->prepare('INSERT INTO cuenta (CuentaCodigo, CuentaPrivilegio, CuentaUsuario, CuentaClave, CuentaEmail, CuentaEstado, CuentaTipo, CuentaGenero, CuentaFoto) VALUES (:Codigo, :Privilegio, :Usuario, :Clave, :Email, :Estado, :Tipo, :Genero, :Foto)');
		$sql->bindParam(':Codigo', $datos['Codigo']);
		$sql->bindParam(':Privilegio', $datos['Privilegio']);
		$sql->bindParam(':Usuario', $datos['Usuario']);
		$sql->bindParam(':Clave', $datos['Clave']);
		$sql->bindParam(':Email', $datos['Email']);
		$sql->bindParam(':Estado', $datos['Estado']);
		$sql->bindParam(':Tipo', $datos['Tipo']);
		$sql->bindParam(':Genero', $datos['Genero']);
		$sql->bindParam(':Foto', $datos['Foto']);

		$sql->execute();
		return $sql;
	}

	protected function eliminar_cuenta($codigo) {
		$sql = self::conectar()->prepare("DELETE FROM cuenta WHERE CuentaCodigo=:Codigo");
		$sql->bindParam(":Codigo", $codigo);

		$sql->execute();
		return $sql;
	}

	protected function datos_cuenta($codigo) {
		$query = self::conectar()->prepare('SELECT * FROM cuenta WHERE CuentaCodigo=:Codigo');
		$query->bindParam(":Codigo", $codigo);

		$query->execute();
		return $query;
	}

	protected function actualizar_cuenta($datos) {
		$query = self::conectar()->prepare('UPDATE cuenta SET CuentaPrivilegio=:Privilegio, CuentaUsuario=:Usuario, CuentaClave=:Clave, CuentaEmail=:Email, CuentaEstado=:Estado, CuentaGenero=:Genero, CuentaFoto=:Foto WHERE CuentaCodigo=:Codigo');
		$query->bindParam(':Privilegio', $datos['CuentaPrivilegio']);
		$query->bindParam(':Usuario', $datos['CuentaUsuario']);
		$query->bindParam(':Clave', $datos['CuentaClave']);
		$query->bindParam(':Email', $datos['CuentaEmail']);
		$query->bindParam(':Estado', $datos['CuentaEstado']);
		$query->bindParam(':Genero', $datos['CuentaGenero']);
		$query->bindParam(':Foto', $datos['CuentaFoto']);
		$query->bindParam(':Codigo', $datos['CuentaCodigo']);

		$query->execute();
		return $query;
	}

	// FUNCIONES BITACORA

	protected function guardar_bitacora($datos) {
		$sql = self::conectar()->prepare("INSERT INTO bitacora(BitacoraCodigo, BitacoraFecha, BitacoraHoraInicio, BitacoraHoraFinal, BitacoraTipo, BitacoraYear, CuentaCodigo) VALUES (:Codigo, :Fecha, :HoraInicio, :HoraFinal, :Tipo, :Year, :Cuenta)");
		$sql->bindParam(":Codigo", $datos['Codigo']);
		$sql->bindParam(":Fecha", $datos['Fecha']);
		$sql->bindParam(":HoraInicio", $datos['HoraInicio']);
		$sql->bindParam(":HoraFinal", $datos['HoraFinal']);
		$sql->bindParam(":Tipo", $datos['Tipo']);
		$sql->bindParam(":Year", $datos['Year']);
		$sql->bindParam(":Cuenta", $datos['Cuenta']);

		$sql->execute();
		return $sql;
	}

	protected function actualizar_bitacora($codigo, $hora) {
		$sql = self::conectar()->prepare("UPDATE bitacora SET BitacoraHoraFinal=:Hora WHERE BitacoraCodigo=:Codigo");
		$sql->bindParam(":Hora", $hora);
		$sql->bindParam(":Codigo", $codigo);

		$sql->execute();
		return $sql;
	}

	protected function eliminar_bitacora($codigo) {
		$sql = self::conectar()->prepare("DELETE FROM bitacora WHERE CuentaCodigo=:Codigo");
		$sql->bindParam(":Codigo", $codigo);

		$sql->execute();
		return $sql;
	}

	// FUNCIONES GENERALES

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
					)
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
					}).then((result) => {
							$('.FormularioAjax')[0].reset();
						}
					)
				</script>
			";
		}

		return $alerta;
	}
}