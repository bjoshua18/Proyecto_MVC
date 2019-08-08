<script>
	$(document).ready(function() {
		$('.btn-exit-system').on('click', function(e){
			e.preventDefault();
			var Token = $(this).attr('href');
			swal({
					title: '¿Estás seguro?',
					text: "La sesión actual se cerrará y deberás cerrar sesion nuevamente",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#03A9F4',
					cancelButtonColor: '#F44336',
					confirmButtonText: '<i class="zmdi zmdi-run"></i> Sí, cerrar!',
					cancelButtonText: '<i class="zmdi zmdi-close-circle"></i> No, cancelar!'
			}).then(function () {
				$.ajax({
					url: "<?= SERVERURL ?>ajax/loginAjax.php?Token="+Token,
					success: function(data) {
						if(data == "true") {
							window.location.href="<?= SERVERURL ?>login/"
						}else {
							swal(
								'Ocurrió un error',
								'No se pudo cerrar la sesion',
								'error'
							)
						}
					}
				})
			});
		});
	})
</script>