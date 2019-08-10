<?php

$peticionAjax ? require_once "../core/mainModel.php" : require_once "./core/mainModel.php";

class administradorModelo extends mainModel {
	protected function agregar_administrador_modelo($datos) {
		$sql = mainModel::conectar()->prepare('INSERT INTO admin (AdminDNI, AdminNombre, AdminApellido, AdminTelefono, AdminDireccion, CuentaCodigo) VALUES (:DNI, :Nombre, :Apellido, :Telefono, :Direccion, :Codigo)');
		$sql->bindParam(':DNI', $datos['DNI']);
		$sql->bindParam(':Nombre', $datos['Nombre']);
		$sql->bindParam(':Apellido', $datos['Apellido']);
		$sql->bindParam(':Telefono', $datos['Telefono']);
		$sql->bindParam(':Direccion', $datos['Direccion']);
		$sql->bindParam(':Codigo', $datos['Codigo']);

		$sql->execute();
		return $sql;
	}

	protected function eliminar_administrador_modelo($codigo) {
		$query = mainModel::conectar()->prepare('DELETE FROM admin WHERE CuentaCodigo=:Codigo');
		$query->bindParam(':Codigo', $codigo);

		$query->execute();
		return $query;
	}
}