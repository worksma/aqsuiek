<div class="row">
	<div class="col-lg-12 order-mobile-1">
		<div class="block account {if($TriggerAccount->cover == 'no_image.jpg')}no_cover{/if}">
			<div class="cover">
				<div class="image" data-src="/public/images/cover/{{$TriggerAccount->cover}}"></div>
				
				{if(isset($_SESSION['id']))}
					{if($TriggerAccount->id == $_SESSION['id'])}
						<button class="btn" data-bs-toggle="modal" data-bs-target="#ChangeCover"><i class="bi bi-pencil"></i></button>
						
						<div class="modal fade" id="ChangeCover" tabindex="-1" aria-labelledby="ChangeCoverLabel" aria-hidden="true">
							<div class="modal-dialog">
								<form class="modal-content" data-target="ChangeCover">
									<div class="modal-header">
										<h1 class="modal-title fs-5" id="ChangeCoverLabel">Изменение обложки</h1>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									
									<div class="modal-body">
										<div class="alert info" id="ResultChangeCover"></div>
										<input type="file" class="form-control m-0" accept="image/*" name="image" required>
									</div>
									
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
										<input type="submit" class="btn btn-primary" value="Изменить">
									</div>
								</form>
							</div>
						</div>
					{/if}
				{/if}
			</div>
			
			<div class="content">
				<div class="avatar{if(IsUserOnline($TriggerAccount->id))} online{/if}">
					<div class="image" data-src="{{GetUserAvatar($TriggerAccount->id)}}" data-target="Zoom" data-toggle="Image"></div>
					{if(isset($_SESSION['id']))}
						{if($TriggerAccount->id == $_SESSION['id'])}
							<button class="btn" data-bs-toggle="modal" data-bs-target="#ChangeAvatar"><i class="bi bi-camera"></i></button>
							
							<div class="modal fade" id="ChangeAvatar" tabindex="-1" aria-labelledby="ChangeAvatarLabel" aria-hidden="true">
								<div class="modal-dialog">
									<form class="modal-content" data-target="ChangeAvatar">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="ChangeAvatarLabel">Изменение фотографии</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										
										<div class="modal-body">
											<div class="alert info" id="ResultChangeAvatar"></div>
											<input type="file" class="form-control m-0" accept="image/jpeg" name="image" required>
										</div>
										
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
											<input type="submit" class="btn btn-primary" value="Изменить">
										</div>
									</form>
								</div>
							</div>
						{/if}
					{/if}
				</div>
				
				<div class="info">
					<div class="name">
						{echo(GetUserName($TriggerAccount->id, [
							'full' => true,
							'very' => true
						]))}
						
						{if(!IsUserOnline($TriggerAccount->id) AND '{is_blacklist}' != '1')}
							<div class="LastOnline">Был {echo(mb_strtolower(DayToTime($TriggerAccount->last_online)))}</div>
						{/if}
					</div>
					
					{if(isset($TriggerAccount->status))}
						<div class="status">{echo($TriggerAccount->status)}</div>
					{/if}
					
					{if('{is_blacklist}' != '1')}
						<div class="bar">
							<div class="city">
								<i class="bi bi-geo-alt"></i> <a href="/search?city={echo(Users::City($TriggerAccount->id))}">{echo(Users::City($TriggerAccount->id))}</a>
							</div>
							<div class="city">
								<i class="bi bi-info-circle"></i> <a data-bs-toggle="modal" data-bs-target="#GetDetails" href="javascript:void(0);">Подробнее</a>
								
								<div class="modal fade" id="GetDetails" tabindex="-1" aria-labelledby="GetDetailsLabel" aria-hidden="true">
									<div class="modal-dialog">
										<form class="modal-content" data-target="GetDetails">
											<div class="modal-header">
												<h1 class="modal-title fs-5" id="GetDetailsLabel">Подробная информация</h1>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											
											<div class="modal-body Detail-Profile">
												<ul>
													{if(isset($TriggerAccount->status))}
													<li>
														<div class="Detail-Icon"><i class="bi bi-text-left"></i></div>
														<div class="Detail-Icon-Text">{echo($TriggerAccount->status)}</div>
													</li>
													{/if}
													
													<li>
														<div class="Detail-Icon"><i class="bi bi-link-45deg"></i></div>
														<div class="Detail-Icon-Text"><a href="/id{{$TriggerAccount->id}}">id{{$TriggerAccount->id}}</a></div>
													</li>
													
													<li><hr></li>
													
													<li>
														<div class="Detail-Icon"><i class="bi bi-gift"></i> День рождения: </div>
														<div class="Detail-Icon-Text">{echo(date('j', strtotime($TriggerAccount->birthday)) . ' ' . GetMonth($TriggerAccount->birthday, 1))}</div>
													</li>
													
													<li>
														<div class="Detail-Icon"><i class="bi bi-moon-stars"></i> Знак зодиака: </div>
														<div class="Detail-Icon-Text" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="{echo(GetZodiacInfo(GetZodiac($TriggerAccount->birthday)))}">{echo(GetZodiac($TriggerAccount->birthday))}</div>
													</li>
													
													<li>
														<div class="Detail-Icon"><i class="bi bi-house"></i> Город: </div>
														<div class="Detail-Icon-Text"><a href="/search?city={echo(Users::City($TriggerAccount->id))}">{echo(Users::City($TriggerAccount->id))}</a></div>
													</li>
												</ul>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					{/if}
				</div>
				
				{if(isset($_SESSION['id']))}
					<div class="Panel">
						{if($TriggerAccount->id != $_SESSION['id'] AND '{is_blacklist}' != '1')}
							<output class="me-2">
								<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#CreateRoom">Сообщение</button>
								
								<div class="modal fade" id="CreateRoom" tabindex="-1" aria-labelledby="CreateRoomLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<form class="modal-content" data-action="StartDialog">
											<input type="hidden" name="userid" value="{{$TriggerAccount->id}}">
											
											<div class="modal-header">
												<h1 class="modal-title fs-5" id="CreateRoomLabel">Новое сообщение</h1>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											
											<div class="modal-body">
												<div style="display: flex; margin-bottom: 14px; font-size: 13px;">
													<img style="width: 48px; height: 48px; border-radius: 50%; margin-right: 14px;" src="{{GetUserAvatar($TriggerAccount->id)}}">
													
													<div class="Info" style="display: flex; flex-direction: column; justify-content: center; align-items: start;">
														<div class="Name">
															{echo(GetUserName($TriggerAccount->id, ['full' => true, 'very' => true]))}
														</div>
														
														<div class="Online" style="font-size: 12px;">
															{if(!IsUserOnline($TriggerAccount->id))}
																Был(-а) {echo(mb_strtolower(DayToTime($TriggerAccount->last_online)))}
															{else}
																В сети
															{/if}
														</div>
													</div>
												</div>
												
												<textarea class="form-control" rows="5" name="message" autocompolete="off" required></textarea>
											</div>
											
											<div class="modal-footer">
												<input type="submit" class="btn btn-primary" value="Отправить">
											</div>
										</form>
									</div>
								</div>
							</output>
						{/if}

						<output data-target="Panel" data-toggle="Friend_{userid}">{echo(Friends::GetButton('{userid}'))}</output>
					</div>
				{/if}
			</div>
		</div>
	</div>
	
	{if('{is_blacklist}' == '1')}
		<div class="col-lg-12 text-center mt-4">
			Пользователь ограничил доступ к своей странице
		</div>
	{else}
		<div class="col-lg-8 account wall feed order-mobile-3">
			{if(isset($_SESSION['id']))}
			<form data-target="NewPost">
				<input type="hidden" name="universe" value="{{$TriggerAccount->id}}">
				
				<div class="new_post">
					<div class="content">
						<div class="avatar">
							<img src="{{GetUserAvatar($_SESSION['id'])}}">
						</div>
						
						<textarea class="form-control" rows="2" placeholder="Поделитесь своими мыслями" data-target="Send" data-toggle="NewPost" name="content"></textarea>
					</div>
				</div>
				<div class="bottom_new_post">
					<div class="right ac-p">
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
			{/if}
			
			<div class="list mt-4">
				<div id="wall">
					<script>
						SendPost('/application/backstage/Writing.php', {GetWrites: 1, UniverseId: {{$TriggerAccount->id}}}, (Result) => {
							$('#wall').html(Result.Content);
							
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
			</div>
		</div>
	
		<div class="col-lg-4 account widgets order-mobile-2">
			<div class="block">
				<div class="profile-panel-mini">
					<ul>
						<li data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Подписчики">
							<div class="io-io sub">
								<i class="bi bi-people"></i>
								<span>{echo(WidgetStats::Subs($TriggerAccount->id))}</span>
							</div>
						</li>
						<li data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Лайки">
							<div class="io-io likes">
								<i class="bi bi-heart"></i>
								<span>{echo(WidgetStats::Likes($TriggerAccount->id))}</span>
							</div>
						</li>
						<li data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Записи">
							<div class="io-io notes">
								<i class="bi bi-pencil"></i>
								<span>{echo(WidgetStats::Writes($TriggerAccount->id))}</span>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	{/if}
</div>