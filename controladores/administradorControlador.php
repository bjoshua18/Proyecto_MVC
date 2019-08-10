<?php

$peticionAjax ? require_once "../modelos/administradorModelo.php" : require_once "./modelos/administradorModelo.php";

class administradorControlador extends administradorModelo {

	// Controlador para agregar administrador
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
		$genero = mainModel::limpiar_cadena($_POST['optionsGenero']);

		$privilegio = mainModel::decryption($_POST['optionsPrivilegio']);
		$privilegio = mainModel::limpiar_cadena($privilegio);

		// Foto de perfil por defecto segun genero
		if($genero === 'Masculino')
			$foto = 'Male3Avatar.png';
		else
			$foto = 'Female3Avatar.png';

		if($privilegio < 1 || $privilegio > 3) {
			$alerta = [
				'Alerta' => 'simple',
				'Titulo' => 'Ocurrió un error inesperado',
				'Texto' => 'El nivel de privilegio que intenta asignar es incorrecto',
				'Tipo' => 'error'
			];
		} else {
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
						} else {
							// Procesamos los datos la cuenta de admin
							$consulta4 = mainModel::ejecutar_consulta_simple("SELECT id FROM cuenta");
							$numero = ($consulta4->rowCount())+1;
							$codigo = mainModel::generar_codigo_aleatorio('AC', 7, $numero);
							$clave = mainModel::encryption($password1);

							$dataAC = [
								'Codigo' => $codigo,
								'Privilegio' => $privilegio,
								'Usuario' => $usuario,
								'Clave' => $clave,
								'Email' => $email,
								'Estado' => 'Activo',
								'Tipo' => 'Administrador',
								'Genero' => $genero,
								'Foto' => $foto
							];

							$guardarCuenta = mainModel::agregar_cuenta($dataAC);

							// Comprobamos que se agregó la cuenta correctamente
							if($guardarCuenta->rowCount() >= 1) {
								$dataAD = [
									'DNI' => $dni,
									'Nombre' => $nombre,
									'Apellido' => $apellido,
									'Telefono' => $telefono,
									'Direccion' => $direccion,
									'Codigo' => $codigo
								];

								$guardarAdmin = administradorModelo::agregar_administrador_modelo($dataAD);

								if($guardarAdmin->rowCount() >= 1) {
									$alerta = [
										'Alerta' => 'limpiar',
										'Titulo' => 'Administrador registrado',
										'Texto' => 'El administrador se registró con éxito en el sistema',
										'Tipo' => 'success'
									];
								} else {
									mainModel::eliminar_cuenta($codigo);// Si hay un error al agregar el admin, hay que borrar la cuenta asociada que se agregó previamente
									$alerta = [
										'Alerta' => 'simple',
										'Titulo' => 'Ocurrió un error inesperado',
										'Texto' => 'No se ha podido registrar el administrador',
										'Tipo' => 'error'
									];
								}
							} else {
								$alerta = [
									'Alerta' => 'simple',
									'Titulo' => 'Ocurrió un error inesperado',
									'Texto' => 'No se ha podido registrar el administrador',
									'Tipo' => 'error'
								];
							}
						}
					}
				}
			}
		}

		return mainModel::sweet_alert($alerta);

	}

	// Controlador para paginar administradores
	public function paginador_administrador_controlador($pagina, $registros, $privilegio, $codigo, $busqueda = '') {

		// Limpieza de parametros
		$pagina = mainModel::limpiar_cadena($pagina);
		$registros = mainModel::limpiar_cadena($registros);
		$privilegio = mainModel::limpiar_cadena($privilegio);
		$codigo = mainModel::limpiar_cadena($codigo);
		$busqueda = mainModel::limpiar_cadena($busqueda);

		$tabla = '';

		$pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;	// Obtenemos la pagina
		$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0; // Calculamos el inicio de registros de la pagina

		// Comprobamos si es una busqueda
		if(isset($busqueda) && $busqueda != '') {
			$consulta = "SELECT SQL_CALC_FOUND_ROWS *
			FROM admin 
			WHERE ((CuentaCodigo!='$codigo' AND id!='1')
			  AND (
					AdminNombre LIKE '%$busqueda%' OR 
					AdminApellido LIKE '%$busqueda%' OR 
					AdminDNI LIKE '%$busqueda%' OR 
					AdminTelefono LIKE '%$busqueda%'))  
			ORDER BY AdminNombre ASC LIMIT $inicio, $registros";

			$paginaurl = 'adminsearch';
		} else {
			$consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM admin WHERE CuentaCodigo!='$codigo' AND id!='1' ORDER BY AdminNombre ASC LIMIT $inicio, $registros";
			$paginaurl = 'adminlist';
		}

		$conexion = mainModel::conectar(); // Obtenemos la conexion con la db

		// Creamos la query y obtenemos los datos
		$datos = $conexion->query($consulta);
		$datos = $datos->fetchAll();

		// Obtenemos el número de registros totales
		$total = $conexion->query('SELECT FOUND_ROWS()');
		$total = (int) $total->fetchColumn();

		// Calculamos el número de páginas totales
		$Npaginas = ceil($total / $registros);

		// Creamos la tabla con todos los registros de una página
		$tabla .= '
			<div class="table-responsive">
				<table class="table table-hover text-center">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">DNI</th>
							<th class="text-center">NOMBRES</th>
							<th class="text-center">APELLIDOS</th>
							<th class="text-center">TELÉFONO</th>
		';

		if($privilegio <= 2) {
			$tabla .= '
							<th class="text-center">A. CUENTA</th>
							<th class="text-center">A. DATOS</th>
			';
		}

		if($privilegio == 1) {
			$tabla .= '
							<th class="text-center">ELIMINAR</th>
			';
		}
							
		$tabla .= '
						</tr>
					</thead>
					<tbody>
		';

		if($total >= 1 && $pagina <= $Npaginas) {
			$contador = $inicio + 1;
			foreach($datos as $row) {
				$tabla .= '
							<tr>
								<td>'.$contador.'</td>
								<td>'.$row['AdminDNI'].'</td>
								<td>'.$row['AdminNombre'].'</td>
								<td>'.$row['AdminApellido'].'</td>
								<td>'.$row['AdminTelefono'].'</td>
				';

				if($privilegio <= 2) {
					$tabla .= '
								<td>
									<a href="'.SERVERURL.'myaccount/admin/'.mainModel::encryption($row['CuentaCodigo']).'/" class="btn btn-success btn-raised btn-xs">
										<i class="zmdi zmdi-refresh"></i>
									</a>
								</td>
								<td>
									<a href="'.SERVERURL.'mydata/admin/'.mainModel::encryption($row['CuentaCodigo']).'/" class="btn btn-success btn-raised btn-xs">
										<i class="zmdi zmdi-refresh"></i>
									</a>
								</td>
					';
				}

				if($privilegio == 1) {
					$tabla .= '
								<td>
									<form action="'.SERVERURL.'ajax/administradorAjax.php" method="POST" class="FormularioAjax" data-form="delete" entype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="codigo-del" value="'.mainModel::encryption($row["CuentaCodigo"]).'" />
										<input type="hidden" name="privilegio-admin" value="'.mainModel::encryption($privilegio).'" />
										<button type="submit" class="btn btn-danger btn-raised btn-xs">
											<i class="zmdi zmdi-delete"></i>
										</button>
										<div class="RespuestaAjax"></div>
									</form>
								</td>
					';
				}

				$tabla .= '
							</tr>
				';

				$contador++;
			}
		} else {
			if($total >= 1) {
				$tabla .= '
							<tr>
								<td colspan="5">
									<a href="'.SERVERURL.$paginaurl.'/" class="btn btn-sm btn-info btn-raised">
										Haga click aquí para recargar el listado
									</a>
								</td>
							</tr>
				';
			} else {
				$tabla .= '
							<tr>
								<td colspan="5">No hay registros en el sistema</td>
							</tr>
				';
			}
		}

		$tabla .= '
					</tbody>
				</table>
			</div>
		';

		// Funcionalidad de los botones
		if($total >= 1 && $pagina <= $Npaginas) {
			$tabla .= '
				<!-- Paginacion -->
				<nav class="text-center">
					<ul class="pagination pagination-sm">
			';

			// Boton de pagina anterior
			if($pagina == 1) {
				$tabla .= '
						<li class="disabled"><a><i class="zmdi zmdi-arrow-left"></i></a></li>
				';
			} else {
				$tabla .= '
						<li><a href="'.SERVERURL.$paginaurl.'/'.($pagina - 1).'/"><i class="zmdi zmdi-arrow-left"></i></a></li>
				';
			}

			// Botnones de paginas intermedias
			for($i = 1; $i <= $Npaginas; $i++) {
				if($i == $pagina) {
					$tabla .= '
						<li class="active"><a href="'.SERVERURL.$paginaurl.'/'.$i.'/">'.$i.'</i></a></li>
					';
				} else {
					$tabla .= '
						<li><a href="'.SERVERURL.$paginaurl.'/'.$i.'/">'.$i.'</i></a></li>
					';
				}
			}

			// Boton de pagina siguiente
			if($pagina == $Npaginas) {
				$tabla .= '
						<li class="disabled"><a><i class="zmdi zmdi-arrow-right"></i></a></li>
				';
			} else {
				$tabla .= '
						<li><a href="'.SERVERURL.$paginaurl.'/'.($pagina + 1).'/"><i class="zmdi zmdi-arrow-right"></i></a></li>
				';
			}

			$tabla .= '
					</ul>
				</nav>
			';
		}

		return $tabla;
	}

	// Controlador para eliminar administradores
	public function eliminar_administrador_controlador() {

		// Desencriptamos y limpiamos los parametros que vamos a necesitar
		$codigo = mainModel::decryption($_POST['codigo-del']);
		$adminPrivilegio = mainModel::decryption($_POST['privilegio-admin']);

		$codigo = mainModel::limpiar_cadena($codigo);
		$adminPrivilegio = mainModel::limpiar_cadena($adminPrivilegio);

		// Comprobamos que el privilegio sea el correcto
		if($adminPrivilegio == 1) {
			$query1 = mainModel::ejecutar_consulta_simple("SELECT id FROM admin WHERE CuentaCodigo='$codigo'");
			$datosAdmin = $query1->fetch();

			// Comprobamos que no eliminamos al admin principal
			if($datosAdmin['id'] != 1) {
				$DelAdmin = administradorModelo::eliminar_administrador_modelo($codigo); // Eliminamos el registro de la tabla admin
				mainModel::eliminar_bitacora($codigo); // Eliminamos los registros de la tabla bitacora

				// Comprobamos que se elimino el registro correctamente
				if($DelAdmin->rowCount() >= 1) {
					$DelCuenta = mainModel::eliminar_cuenta($codigo); // Eliminamos el registro de la tabla cuenta

					// Comprobamos que se elimino el registro correctamente
					if($DelCuenta->rowCount() >= 1) {
						$alerta = [
							'Alerta' => 'recargar',
							'Titulo' => 'Administrador eliminado',
							'Texto' => 'El administrador fue eliminado con éxito del sistema',
							'Tipo' => 'success'
						];
					} else {
						$alerta = [
							'Alerta' => 'simple',
							'Titulo' => 'Ocurrió un error inesperado',
							'Texto' => 'No podemos eliminar esta cuenta en este momento',
							'Tipo' => 'error'
						];
					}
				} else {
					$alerta = [
						'Alerta' => 'simple',
						'Titulo' => 'Ocurrió un error inesperado',
						'Texto' => 'No podemos eliminar este administrador en estos momentos',
						'Tipo' => 'error'
					];
				}
			} else {
				$alerta = [
					'Alerta' => 'simple',
					'Titulo' => 'Ocurrió un error inesperado',
					'Texto' => 'No podemos eliminar el administrador principal del sistema',
					'Tipo' => 'error'
				];
			}
		} else {
			$alerta = [
				'Alerta' => 'simple',
				'Titulo' => 'Ocurrió un error inesperado',
				'Texto' => 'Tú no tienes los permisos necesarios para realizar esta operación',
				'Tipo' => 'error'
			];
		}

		return mainModel::sweet_alert($alerta);
	}
}