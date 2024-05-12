<div class="modal fade" id="zoomImage" tabindex="-1" aria-labelledby="zoomImageLabel" aria-hidden="true">
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="Object">
					<img src="{image}">
				</div>
				
				<div class="Info">
					<div class="Head">
						<img src="{profile_image}"> {profile_name}
					</div>
					<div class="Comments">
						<div class="Content"></div>
						
						<form>
							<input type="text" class="form-control" placeholder="Напишите своё впечатление">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script>
		$('#zoomImage').modal('show').on('hidden.bs.modal', function() {
			$(this).remove();
		});
	</script>
</div>