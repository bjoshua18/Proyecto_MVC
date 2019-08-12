<section class="full-box cover dashboard-sideBar">
	<div class="full-box dashboard-sideBar-bg btn-menu-dashboard"></div>
	<div class="full-box dashboard-sideBar-ct">
		<!--SideBar Title -->
		<div class="full-box text-uppercase text-center text-titles dashboard-sideBar-title">
			<?= COMPANY ?> <i class="zmdi zmdi-close btn-menu-dashboard visible-xs"></i>
		</div>
		<!-- SideBar User info -->
		<div class="full-box dashboard-sideBar-UserInfo">
			<figure class="full-box">
				<img src="<?= SERVERURL?>vistas/assets/avatars/<?= $_SESSION['foto_sbp']?>" alt="UserIcon">
				<figcaption class="text-center text-titles">User Name</figcaption>
			</figure>

			<?php
				if($_SESSION['tipo_sbp'] == 'Administrador')
					$tipo = 'admin';
				else
					$tipo = 'user'
			?>

			<ul class="full-box list-unstyled text-center">
				<li>
					<a href="<?= SERVERURL?>mydata/<?= $tipo.'/'.$lc->encryption($_SESSION['codigo_cuenta_sbp']) ?>/" title="Mis datos">
						<i class="zmdi zmdi-account-circle"></i>
					</a>
				</li>
				<li>
					<a href="<?= SERVERURL?>myaccount/<?= $tipo.'/'.$lc->encryption($_SESSION['codigo_cuenta_sbp']) ?>/" title="Mi cuenta">
						<i class="zmdi zmdi-settings"></i>
					</a>
				</li>
				<li>
					<a href="<?= $lc->encryption($_SESSION['token_sbp']) ?>" title="Salir del sistema" class="btn-exit-system">
						<i class="zmdi zmdi-power"></i>
					</a>
				</li>
			</ul>
		</div>
		<!-- SideBar Menu -->
		<ul class="list-unstyled full-box dashboard-sideBar-Menu">
			<li>
				<a href="./home">
					<i class="zmdi zmdi-view-dashboard zmdi-hc-fw"></i> Dashboard
				</a>
			</li>
			<li>
				<a href="#!" class="btn-sideBar-SubMenu">
					<i class="zmdi zmdi-case zmdi-hc-fw"></i> Administraci�n <i class="zmdi zmdi-caret-down pull-right"></i>
				</a>
				<ul class="list-unstyled full-box">
					<li>
						<a href="./company"><i class="zmdi zmdi-balance zmdi-hc-fw"></i> Empresa</a>
					</li>
					<li>
						<a href="./category"><i class="zmdi zmdi-labels zmdi-hc-fw"></i> Categor��as</a>
					</li>
					<li>
						<a href="./provider"><i class="zmdi zmdi-truck zmdi-hc-fw"></i> Proveedores</a>
					</li>
					<li>
						<a href="./book"><i class="zmdi zmdi-book zmdi-hc-fw"></i> Nuevo libro</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#!" class="btn-sideBar-SubMenu">
					<i class="zmdi zmdi-account-add zmdi-hc-fw"></i> Usuarios <i class="zmdi zmdi-caret-down pull-right"></i>
				</a>
				<ul class="list-unstyled full-box">
					<li>
						<a href="./admin"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Administradores</a>
					</li>
					<li>
						<a href="./client"><i class="zmdi zmdi-male-female zmdi-hc-fw"></i> Clientes</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="./catalog">
					<i class="zmdi zmdi-book-image zmdi-hc-fw"></i> Catalogo
				</a>
			</li>
		</ul>
	</div>
</section>