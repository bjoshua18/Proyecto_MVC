<?php
require_once('./modelos/vistasModelo.php');

class vistasControlador extends vistasModelo {
	
	public function obtener_plantilla_controlador() {
		require_once('./vistas/plantilla.php');
	}
}