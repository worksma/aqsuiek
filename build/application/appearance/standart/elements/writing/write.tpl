<div class="block write" id="WriteId_{id}">
	<div class="top_panel">
		<div class="avatar{if(IsUserOnline('{userid}'))} online{else} offline{/if}"><a href="/id{userid}"><img src="{author_image}"></a></div>
		
		<div class="info">
			<div class="name me-auto">
				<span>{write_author}</span>
			</div>
			<div class="date">{date}</div>
		</div>
		
		<div class="right dropend" data-bs-toggle="dropdown" aria-expanded="false">
			<i class="bi bi-three-dots"></i>
			<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="/write-{userid}_{id}">Перейти</a></li>
				
				{if(isset($_SESSION['id']))}
					{if('{userid}' == $_SESSION['id'])}
						<li><button class="dropdown-item" data-target="Remove" data-toggle="Post" data-index="{id}">Удалить</button></li>
					{else}
						<li><button class="dropdown-item" href="#">Пожаловаться</button></li>
					{/if}
				{/if}
			</ul>
		</div>
	</div>
	
	<a href="/write-{userid}_{id}"><div class="content">{content}</div></a>{attachment}
	
	<div class="content_bottom">
		{if(isset($_SESSION['id']))}
			<button class="btn secondary like icon{if(Writings::IsLiked($_SESSION['id'], '{id}'))} liked{/if}" data-target="Like" data-toggle="Post" data-index="{id}" data-liked="{echo(Writings::IsLiked($_SESSION['id'], '{id}'))}">
				<i class="bi bi-heart"></i> <span id="likes_{id}">{likeRows}</span>
			</button>
		{/if}
		
		<a href="/write-{userid}_{id}" class="btn secondary icon">
			<i class="bi bi-chat-left-dots"></i> <span class="rowComments">{commentRows}</span>
		</a>
		
		<div class="views" title="Просмотры">
			<i class="bi bi-eye"></i> <span class="RowViews">{views}</span>
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
</div>