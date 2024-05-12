<div class="modal fade" id="writeModal" tabindex="-1" aria-labelledby="writeModal" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<div class="Profile">
					<div class="Image">
						<img src="{author_image}">
					</div>
					<div class="Info">
						<div class="Name">{author_name}</div>
						<div class="Date">{post_date}</div>
					</div>
				</div>
				<div class="Content">
					{content}
				</div>
			</div>
		</div>
	</div>
	
	<script>
		$(function() {
			$("#writeModal").on("hidden.bs.modal", function() {
				$(this).remove();
			});
			
			PreloadModalContent();
			PreloadImages();
			$('[data-bs-toggle=\'popover\']').popover();
			$('script').remove();
			
			$('#writeModal').modal('show');
		});
	</script>
</div>