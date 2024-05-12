<div class="row">
	<div class="col-lg-3 mobile-hide">
		{grab('/elements/navigation/profile-mini.tpl')}
		{grab('/elements/navigation/menu.tpl')}
		{grab('/elements/navigation/left.tpl')}
	</div>
	
	<div class="col-lg-6 feed">
		<form data-target="NewPost">
			<input type="hidden" name="universe" value="{{$_SESSION['id']}}">
			
			<div class="new_post">
				<div class="content">
					<div class="avatar">
						<img src="{{GetUserAvatar($_SESSION['id'])}}">
					</div>
					
					<textarea class="form-control" rows="2" placeholder="Поделитесь своими мыслями" data-target="Send" data-toggle="NewPost" name="content"></textarea>
				</div>
			</div>
			<div class="bottom_new_post">
				<div class="right">
					<output id="RowFiles"></output>
					
					<button type="button" class="btn icon" data-target="LoadModal" data-toggle="AttachmentImages"><i class="bi bi-camera"></i></button>
					
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
											$('[data-target="Send"][data-toggle="NewPost"]').val($.trim($('[data-target="Send"][data-toggle="NewPost"]').val() + $(this).html()));
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
					
					<input type="submit" class="btn btn-primary btn-sm" value="Опубликовать">
				</div>
			</div>
		</form>
		
		<div class="list mt-4">
			<div class="row">
				<div class="col-lg-12" id="feeds">
					<script>
						SendPost('/application/backstage/Writing.php', {GetWrites: 1}, (Result) => {
							$('#feeds').html(Result.Content);
							
							if(Result.Page == null) {
								$('[data-target="nextpage"]').remove();
							}
							
							$.each($('[data-target=\'Remove\'][data-toggle=\'Post\']'), function(event, handler) {
								$(handler).unbind('click').bind('click', function() {
									SetWaiting();
									
									var Index = $(this).data('index');
									
									SendPost('/application/backstage/Writing.php', {Remove: 1, Index: Index}, (Result) => {
										ClearWaiting();
										
										if(Result.Alert == 'Success') {
											if($('#WriteId_' + Index).length) {
												$('#WriteId_' + Index).remove();
											}
											else {
												setTimeout('location.reload();', 300);
											}
										}
										else {
											ShowToasty(Result.Message, Result.Alert);
										}
									});
								});
							});
							
							$('[href]').bind('click', function(e) {
								e.preventDefault();
								location.href = $(this).attr('href');
							});
							
							BindLikes();
						});
					</script>
				</div>
				
				<div class="consistent">
					<button class="btn" data-target="nextpage" data-toggle="2"><i class="bi bi-chevron-down"></i></button>
					
					<script>
						$(function() {
							$('[data-target="nextpage"]').bind('click', function() {
								var Page = $(this).data('toggle');
								
								if(Page != null) {
									SendPost('/application/backstage/Writing.php', {
										GetWrites:1, Page: Page
									}, (Result) => {
										$('#feeds').append(Result.Content);
										$('[data-target="nextpage"]').data('toggle', Result.Page);
										
										$.each($('[data-target=\'Remove\'][data-toggle=\'Post\']'), function(event, handler) {
											$(handler).unbind('click').bind('click', function() {
												SetWaiting();
												
												var Index = $(this).data('index');
												
												SendPost('/application/backstage/Writing.php', {Remove: 1, Index: Index}, (Result) => {
													ClearWaiting();
													
													if(Result.Alert == 'Success') {
														if($('#WriteId_' + Index).length) {
															$('#WriteId_' + Index).remove();
														}
														else {
															setTimeout('location.reload();', 300);
														}
													}
													else {
														ShowToasty(Result.Message, Result.Alert);
													}
												});
											});
										});
										
										$('[href]').bind('click', function(e) {
											e.preventDefault();
											location.href = $(this).attr('href');
										});
										
										BindLikes();
									});
								}
								else {
									$(this).remove();
								}
							});
						});
						
						$(window).scroll(function() {
							if($(window).scrollTop() + $(window).height() == $(document).height()) {
								$('[data-target="nextpage"]').click();
							}
						});
					</script>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-lg-3 mobile-hide">
		{grab('/elements/navigation/right.tpl')}
	</div>
</div>