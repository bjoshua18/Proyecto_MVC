<?php

class vistasModelo {

	protected function obtener_vistas_modelo($vistas) {
		$admitidos = ['adminlist', 'adminsearch', 'admin', 'book', 'bookconfig', 'bookinfo', 'catalog', 'category', 'categorylist', 'client', 'clientlist', 'clientsearch', 'company', 'companylist', 'home', 'myaccount', 'mydata', 'provider', 'providerlist', 'search'];

		if(in_array($vistas, $admitidos)) {
			$ruta = "./vistas/contenidos/$vistas-view.php";

			if(is_file($ruta))
				$contenido = $ruta;
			else
				$contenido = 'login';
				
		} else if($vistas == 'login') {
			$contenido = 'login';
		} else if($vistas == 'index') {
			$contenido = 'login';
		} else {
			$contenido = '404';
		}

		return $contenido;
	}
}