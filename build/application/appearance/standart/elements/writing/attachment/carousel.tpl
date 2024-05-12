<div id="Attachment{id}" class="carousel slide">
	{if('{rows}' > 1)}
		<div class="carousel-indicators">
			{buttons}
		</div>
	{/if}
	
	<div class="carousel-inner" data-id="lightgallery-{id}">
		{images}
	</div>
	
	<script>
		$('[data-id="lightgallery-{id}"]').lightGallery();
	</script>
	
	{if('{rows}' > 1)}
		<button class="carousel-control-prev" type="button" data-bs-target="#Attachment{id}" data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Previous</span>
		</button>
		
		<button class="carousel-control-next" type="button" data-bs-target="#Attachment{id}" data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Next</span>
		</button>
	{/if}
</div>