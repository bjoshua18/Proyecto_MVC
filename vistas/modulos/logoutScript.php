<script>
	$(document).ready(function() {
		$('.btn-exit-system').on('click', function(e){
			e.preventDefault();
			swal({
					title: 'Are you sure?',
					text: "The current session will be closed",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#03A9F4',
					cancelButtonColor: '#F44336',
					confirmButtonText: '<i class="zmdi zmdi-run"></i> Yes, Exit!',
					cancelButtonText: '<i class="zmdi zmdi-close-circle"></i> No, Cancel!'
			}).then(function () {
				window.location.href="index.html";
			});
		});
	})
</script>