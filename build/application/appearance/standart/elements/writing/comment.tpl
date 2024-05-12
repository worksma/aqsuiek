<div class="block Comment">
	<div class="MainContent">
		<div class="Profile">
			<div class="Image">
				<img src="{profile_image}">
			</div>
		</div>
		
		<div class="Data">
			<div class="Name">{profile_name}</div>
			<div class="Date">{date}</div>
			<div class="Content">{content}</div>
		</div>
	</div>
	<div class="SecondaryAttachment">
		{attachment}
	</div>
	<div class="Bottom">
		{if(isset($_SESSION['id']))}
		<button class="btn secondary like icon{if(Writings::IsCommentLiked($_SESSION['id'], '{id}'))} liked{/if}" data-target="Like" data-toggle="Comment" data-index="{id}" data-liked="{echo(Writings::IsCommentLiked($_SESSION['id'], '{id}'))}">
			<i class="bi bi-heart"></i> <span id="likes_comment_{id}">{likeRows}</span>
		</button>
		{/if}
	</div>
</div>

<script>
	$(function() {
		BindLikes();
		PreloadImages();
		
		$('[data-bs-toggle=\'popover\']').popover();
		$('script').remove();
	});
</script>