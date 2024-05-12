<div class="row page_write">
	<div class="col-lg-8 order-mobile-2">
		<div class="block Note">
			<div class="Top-Panel-Profile only-mobile-show">
				<div class="Avatar{if(IsUserOnline('{author_id}'))} Online{/if}"><a href="/id{author_id}"><img src="{author_image}"></a></div>
				<div class="Data">
					<div class="Name me-auto"><span>{author_name}</span></div>
					<div class="Date">{post_date}</div>
				</div>
			</div>
			
			<div class="Content">{content}</div>{attachment}
			
			{if(isset($_SESSION['id']))}
			<div class="bottom">
				<button class="btn secondary like icon{if(Writings::IsLiked($_SESSION['id'], '{id}'))} liked{/if}" data-target="Like" data-toggle="Post" data-index="{id}" data-liked="{echo(Writings::IsLiked($_SESSION['id'], '{id}'))}">
					<i class="bi bi-heart"></i> <span id="likes_{id}">{likeRows}</span>
				</button>
				
				<div class="views" title="Просмотры">
					<i class="bi bi-eye"></i> <span class="RowViews">{views}</span>
				</div>
			</div>
			{/if}
		</div>
		
		{if(isset($_SESSION['id']))}
		<form data-target="AddComment">
			<div class="AddComment">
				<input type="hidden" name="id" value="{id}">
				
				<div class="content">
					<div class="avatar{if(IsUserOnline('{author_id}'))} online{/if}">
						<img src="{echo(GetUserAvatar($_SESSION['id']))}">
					</div>
					
					<textarea class="form-control" rows="2" placeholder="Прокомментируйте запись" data-target="Send" data-toggle="Comment" name="content"></textarea>
				</div>
			</div>
			<div class="bottom_comment">
				<div class="right">
					<output id="RowFiles"></output>
				
					<button type="button" class="btn icon" data-target="LoadModal" data-toggle="AttachmentImages">
						<i class="bi bi-camera"></i>
					</button>
					
					<button type="button" class="btn icon" data-target="LoadEmoji">
						<i class="bi bi-emoji-smile"></i>
						
						<div class="emoji-list">
							<div class="emoji-content">
								{if($Emoji = new Emoji)}{{$Emoji->List()}}{/if}
								
								<script>
									$(function() {
										$('[data-target="LoadEmoji"]').hover(function() {
											$('.emoji-list').fadeIn(100, function() {
												$(this).css('display', 'flex');
											});
										}, function() {
											$('.emoji-list').fadeOut(100);
										});
										
										$('[data-targer="emoji"]').bind('click', function() {
											$('[data-target="Send"][data-toggle="Comment"]').val($.trim($('[data-target="Send"][data-toggle="Comment"]').val() + $(this).html()));
										});
									});
								</script>
							</div>
							<div class="emoji-bottom">
								<div class="btn"><i class="bi bi-emoji-smile"></i></div>
							</div>
						</div>
					</button>
					
					<output id="attachments"></output>
					
					<input type="submit" class="btn btn-primary btn-sm" value="Комментировать">
				</div>
			</div>
		</form>
		{/if}
		
		<div id="comments">{comments}</div>
	</div>
	
	<div class="col-lg-4 order-mobile-1 mb-4 mobile-hide">
		<div class="block Profile">
			<div class="Image">
				<img src="{author_image}">
			</div>
			
			<div class="Info">
				<div class="Name">
					{author_name}
				</div>
				
				<div class="Date">
					<small>{post_date}</small>
				</div>
			</div>
			
			<div class="profile-panel-mini">
				<ul>
					<li data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Подписчики">
						<div class="io-io sub">
							<i class="bi bi-people"></i>
							<span>{echo(WidgetStats::Subs('{author_id}'))}</span>
						</div>
					</li>
					<li data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Лайки">
						<div class="io-io likes">
							<i class="bi bi-heart"></i>
							<span>{echo(WidgetStats::Likes('{author_id}'))}</span>
						</div>
					</li>
					<li data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Записи">
						<div class="io-io notes">
							<i class="bi bi-pencil"></i>
							<span>{echo(WidgetStats::Writes('{author_id}'))}</span>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>