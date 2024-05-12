function doActionUnBlackList(userid) {
	SetWaiting();
	
	SendPost('/application/backstage/Settings.php', {
		UnBlackList: 1, userid: userid
	}, (Result) => {
		ClearWaiting();
		
		if(Result.Alert == 'Success') {
			$('#UnBlackList' + userid).remove();
			
			if(!$("ul.BlackList").html().trim().length) {
				$("ul.BlackList").html('<center>Список пуст</center>');
			}
		}
		
		ShowToasty(Result.Message, Result.Alert);
	});
}

function BindSubscribe() {
	$.each($('[data-target=\'Subscribe\']'), function(event, handler) {
		$(handler).unbind('click').bind('click', function() {
			SetWaiting();
			var UserId = $(this).data('toggle');
			
			SendPost('/application/backstage/Friends.php', {
				Subscribe: 1,
				userid: UserId
			}, (Result) => {
				ClearWaiting();
				$('output[data-target=\'Panel\'][data-toggle=\'Friend_' + UserId + '\']').html(Result.Button);
				BindSubscribe();
			});
		});
	});
	
	$.each($('[data-target=\'UnSubscribe\']'), function(event, handler) {
		$(handler).unbind('click').bind('click', function() {
			SetWaiting();
			var UserId = $(this).data('toggle');
			
			SendPost('/application/backstage/Friends.php', {
				UnSubscribe: 1,
				userid: UserId
			}, (Result) => {
				ClearWaiting();
				$('output[data-target=\'Panel\'][data-toggle=\'Friend_' + UserId + '\']').html(Result.Button);
				BindSubscribe();
			});
		});
	});
}

function BindLikes() {
	$.each($('[data-target=\'Like\'][data-toggle=\'Post\']'), function(event, handler) {
		$(handler).unbind('click').bind('click', function() {
			if($(handler).data('liked') == '0') {
				SetWaiting();
				SendPost('/application/backstage/Writing.php', {
					SetLike: 1, WriteId: $(handler).data('index'), Liked: $(handler).data('liked')
				}, (Result) => {
					ClearWaiting();
					
					switch(Result.Alert) {
						case 'Success': {
							$('[data-target=\'Like\'][data-toggle=\'Post\'][data-index=\'' + Result.WriteId + '\']').addClass('liked');
							$(handler).data('liked', '1');
							break;
						}
						default: {
							ShowToasty(Result.Message, Result.Alert);
							break;
						}
					}
					
					$('#likes_' + Result.WriteId).html(Result.Likes);
				});
			}
			else {
				SetWaiting();
				SendPost('/application/backstage/Writing.php', {
					SetLike: 1, WriteId: $(handler).data('index'), Liked: $(handler).data('liked')
				}, (Result) => {
					ClearWaiting();
					
					switch(Result.Alert) {
						case 'Success': {
							$('[data-target=\'Like\'][data-toggle=\'Post\'][data-index=\'' + Result.WriteId + '\']').removeClass('liked');
							$(handler).data('liked', '0');
							break;
						}
						default: {
							ShowToasty(Result.Message, Result.Alert);
							break;
						}
					}
					
					$('#likes_' + Result.WriteId).html(Result.Likes);
				});
			}
		});
	});
	
	$.each($('[data-target=\'Like\'][data-toggle=\'Comment\']'), function(event, handler) {
		$(handler).unbind('click').bind('click', function() {
			if($(handler).data('liked') == '0') {
				SetWaiting();
				SendPost('/application/backstage/Writing.php', {
					SetCommentLike: 1, CommentId: $(handler).data('index'), Liked: $(handler).data('liked')
				}, (Result) => {
					ClearWaiting();
					
					switch(Result.Alert) {
						case 'Success': {
							$('[data-target=\'Like\'][data-toggle=\'Comment\'][data-index=\'' + Result.CommentId + '\']').addClass('liked');
							$(handler).data('liked', '1');
							break;
						}
						default: {
							ShowToasty(Result.Message, Result.Alert);
							break;
						}
					}
					
					$('#likes_comment_' + Result.CommentId).html(Result.Likes);
				});
			}
			else {
				SetWaiting();
				SendPost('/application/backstage/Writing.php', {
					SetCommentLike: 1, CommentId: $(handler).data('index'), Liked: $(handler).data('liked')
				}, (Result) => {
					ClearWaiting();
					
					switch(Result.Alert) {
						case 'Success': {
							$('[data-target=\'Like\'][data-toggle=\'Comment\'][data-index=\'' + Result.CommentId + '\']').removeClass('liked');
							$(handler).data('liked', '0');
							break;
						}
						default: {
							ShowToasty(Result.Message, Result.Alert);
							break;
						}
					}
					
					$('#likes_comment_' + Result.CommentId).html(Result.Likes);
				});
			}
		});
	});
}

function RowFiles(o) {
	var Div = $("input:file", o);
	let Count = 0;
	
	for(i = 0; i < Div.length; i++) {
		Count += Div[i].files.length;
	}
	
	return Count;
}

function BlogSub(blogid) {
	SetWaiting();
	
	SendPost('/application/backstage/Blog.php', {Sub: 1, blogid: blogid}, (Result) => {
		ClearWaiting();
		
		if(Result.Alert == 'Success') {
			$('.BlogDescription .Buttons').html(Result.Content);
		}
	});
}

$(function() {
	$UniverseId = $('input[name=\'universe\']').val();
	$('input[name=\'universe\']').remove();
	
	$('form[data-action]').submit(function(Event) {
		Event.preventDefault();
		
		switch($(this).data('action')) {
			case 'Send': {
				var Form = $(this);
				
				SendPost('/application/backstage/Messages.php', {Send: 1, this: this, roomid: $RoomId}, (Result) => {
					if(Result.Alert == 'Success') {
						Form.trigger('reset');
						GetMessages($RoomId, $RoomLastMessageId);
					}
					else {
						ShowToasty(Result.Message, Result.Alert);
					}
				});
				
				break;
			}
			
			case 'StartDialog': {
				SendPost('/application/backstage/Messages.php', {StartDialog: 1, this: this}, (Result) => {
					if(Result.Alert == 'Success') {
						location.href = '/messages?sel=' + Result.Id;
					}
					else {
						ShowToasty(Result.Message, Result.Alert);
					}
				});
				
				break;
			}
			
			default: {
				ShowToasty('Действите временно недоступно', 'warning');
			}
		}
	});
	
	$('form[data-target]').submit(function(e) {
		e.preventDefault();
		
		switch($(this).data('target')) {
			case 'Register': {
				SendPost('/application/backstage/Guest.php', {
					Register: 1, this: this
				}, (Result) => {
					if(Result.Alert == 'Success') {
						Redirect('/', 500);
					}
					
					ShowToasty(Result.Message, Result.Alert);
				});
				
				break;
			}
			
			case 'Authorization': {
				SendPost('/application/backstage/Guest.php', {
					Authorization: 1, this: this
				}, (Result) => {
					if(Result.Alert == 'Success') {
						Redirect('/', 500);
					}
					
					ShowToasty(Result.Message, Result.Alert);
				});
				
				break;
			}
			
			case 'Recovery': {
				SendPost('/application/backstage/Guest.php', {
					Recovery: 1, this: this
				}, (Result) => {
					var Message = '';
					
					switch(Result.Alert) {
						default: {
							Message = '<div class="alert info mb-3">' + Result.Message + '</div>';
							break;
						}
					}
					
					$('#Result').html(Message);
				});
				
				break;
			}
			
			case 'RecoveryEnd': {
				SendPost('/application/backstage/Guest.php', {
					RecoveryEnd: 1, this: this
				}, (Result) => {
					if(Result.Alert == 'Success') {
						Redirect('/', 300);
					}
					
					ShowToasty(Result.Message, Result.Alert);
				});
				
				break;
			}
			
			case 'NewPost': {
				if(IsValid($('[name=\'content\']', this).val())) {
					SetWaiting();
					SendPost('/application/backstage/Writing.php', {
						Write: 1, UniverseId: $UniverseId, this: this
					}, (Result) => {
						ClearWaiting();
						
						if($("#wall").length !== 0) {
							$('#wall').prepend(Result.Content);
						}
						else if($("#feeds").length !== 0) {
							$('#feeds').prepend(Result.Content);
						}
						
						switch(Result.Alert) {
							case 'Success': {
								$('#attachments').html('');
								$('[data-toggle="PreviewImages"]').html('');
								$('form[data-target=\'NewPost\']').trigger("reset");
								break;
							}
							case 'Info': {
								$('#attachments').html('');
								$('[data-toggle="PreviewImages"]').html('');
								$('form[data-target=\'NewPost\']').trigger("reset");
								break;
							}
						}
						
						ShowToasty(Result.Message, Result.Alert);
					});
				}
				
				break;
			}
			
			case 'AddComment': {
				if(IsValid($('[name=\'content\']', this).val())) {
					SetWaiting();
					SendPost('/application/backstage/Writing.php', {
						AddComment: 1, this: this
					}, (Result) => {
						ClearWaiting();
						
						if($("#comments").length !== 0) {
							$('#comments').prepend(Result.Content);
						}
						
						switch(Result.Alert) {
							case 'Success': {
								$('form[data-target=\'AddComment\']').trigger("reset");
								break;
							}
							case 'Info': {
								$('form[data-target=\'AddComment\']').trigger("reset");
								break;
							}
							default: {
								ShowToasty(Result.Message, Result.Alert);
								break;
							}
						}
					});
				}
				
				break;
			}
			
			case 'ChangeCover': {
				SetWaiting();
				
				SendPost('/application/backstage/Settings.php', {ChangeCover: 1, this: this}, (Result) => {
					ClearWaiting();
					
					if(Result.Alert == 'Success') {
						$('form[data-target=\'ChangeCover\']').trigger("reset");
						$('#ChangeCover').modal('hide');
						
						if($('.block.account .cover .image').length) {
							$('.block.account .cover .image').css('background-image', 'url(/public/images/cover/' + Result.File + ')');
						}
						
						if($('.Content .image').length) {
							$('.Content .image').css('background-image', 'url(/public/images/cover/' + Result.File + ')');
						}
					}
					else {
						$('#ResultChangeCover').css('display', 'block');
						$('#ResultChangeCover').html(Result.Message);
					}
				});
				
				break;
			}
			
			case 'ChangeAvatar': {
				SetWaiting();
				
				SendPost('/application/backstage/Settings.php', {ChangeAvatar: 1, this: this}, (Result) => {
					ClearWaiting();
					
					if(Result.Alert == 'Success') {
						$('form[data-target=\'ChangeAvatar\']').trigger("reset");
						$('#ChangeAvatar').modal('hide');
						
						setTimeout('location.reload();', 300);
					}
					else {
						$('#ResultChangeAvatar').css('display', 'block');
						$('#ResultChangeAvatar').html(Result.Message);
					}
				});
				
				break;
			}
			
			case 'ChangeBirthday': {
				SetWaiting();
				
				SendPost('/application/backstage/Settings.php', {ChangeBirthday: 1, this: this}, (Result) => {
					ClearWaiting();
					ShowToasty(Result.Message, Result.Alert);
				});
				
				break;
			}
			
			case 'ChangeCity': {
				SetWaiting();
				
				SendPost('/application/backstage/Settings.php', {ChangeCity: 1, this: this}, (Result) => {
					ClearWaiting();
					ShowToasty(Result.Message, Result.Alert);
				});
				
				break;
			}
			
			case 'Blog': {
				switch($(this).data('toggle')) {
					case 'NewPost': {
						SetWaiting();
						
						SendPost('/application/backstage/Blog.php', {NewPost: 1, this: this, Content: tinyMCE.get('Content').getContent()}, (Result) => {
							ClearWaiting();
							
							if(Result.Alert == 'Success') {
								setTimeout('location.href = \'' + Result.Uri + '\';', 300);
							}
							else {
								ShowToasty(Result.Message, Result.Alert);
							}
						});
						
						break;
					}
					
					case 'Create': {
						SetWaiting();
						
						SendPost('/application/backstage/Blog.php', {Create: 1, this: this}, (Result) => {
							ClearWaiting();
							
							if(Result.Alert == 'Success') {
								setTimeout('location.href = \'' + Result.Message + '\';', 300);
							}
							else {
								ShowToasty(Result.Message, Result.Alert);
							}
						});
						
						break;
					}
				}
				
				break;
			}
			
			case 'ChangeBlogImage': {
				SetWaiting();
				
				SendPost('/application/backstage/Blog.php', {ChangeBlogImage: 1, this: this}, (Result) => {
					ClearWaiting();
					
					if(Result.Alert == 'Success') {
						$('form[data-target=\'ChangeBlogImage\']').trigger("reset");
						$('#ChangeBlogImage').modal('hide');
						
						setTimeout('location.reload();', 300);
					}
					else {
						$('#ResultChangeBlogImage').css('display', 'block');
						$('#ResultChangeBlogImage').html(Result.Message);
					}
				});
				
				break;
			}
			
			case 'SendRequest': {
				switch($(this).data('toggle')) {
					case 'Create': {
						SendPost('/application/backstage/Help.php', {
							Create: 1, this: this
						}, (Result) => {
							if(Result.Alert == 'Success') {
								location.href = '/help/request/id' + Result.LinkId;
							}
							
							ShowToasty(Result.Message, Result.Alert);
						});
						
						break;
					}
				}
				
				break;
			}
			
			case 'Help': {
				switch($(this).data('toggle')) {
					case 'Send': {
						SendPost('/application/backstage/Help.php', {
							Send: 1, this: this
						}, (Result) => {
							if(Result.Alert == 'Success') {
								$('ul#Messages').append(
									Result.Content
								);
								
								$('form[data-target=\'Help\'][data-toggle=\'Send\']').trigger("reset");
								
								return;
							}
							
							ShowToasty(Result.Message, Result.Alert);
						});
						
						break;
					}
				}
				
				break;
			}
			
			/* Поиск блога */
			case 'SearchBlogs': {
				SetWaiting();
				
				SendPost('/application/backstage/Blog.php', {
					Search: 1, this: this
				}, (Result) => {
					ClearWaiting();
					
					if(Result.Alert == 'Success') {
						$('.Blogs .List').html(Result.Content);
					}
					else {
						$('.Blogs .List').html(Result.Message);
					}
				});
				
				break;
			}
			
			default: {
				ShowToasty('Действите временно недоступно', 'warning');
			}
		}
	});
	
	$('form[data-target="ChangeCity"] select[name="Country"]').on('change', function() {
		SetWaiting();
				
		SendPost('/application/backstage/Settings.php', {GetCityList: 1, CountryId: $(this).val()}, (Result) => {
			ClearWaiting();
			$('form[data-target="ChangeCity"] select[name="City"]').html(Result.List);
		});
	});
	
	$.each($('[data-target="LoadModal"][data-toggle="AttachmentImages"]'), function(event, handler) {
		$(handler).unbind('click').bind('click', function() {
			if(!$('#AttachmentImages').length) {
				$('body').append(`<div class="modal fade" id="AttachmentImages" tabindex="-1" aria-labelledby="AttachmentImages" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<label class="modal-title" id="AttachmentImagesLabel">Прикрепление фотографии</label>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<label data-target="PreviewLoad">Загрузить фотографию</label>
					<div class="content" data-toggle="PreviewImages"></div>
					<div class="alert w-100 mb-0">Чтобы удалить изображение, нажмите на него.</div>
				</div>
			</div>
		</div>
	</div>`);
	
			$('[data-target=\'PreviewLoad\']').bind('click', function() {
				let Index = 1 + Math.floor(Math.random() * 1000);
				var Image = $('<input>', {id: 'image_' + Index, type: 'file', name: 'images[]', accept: 'image/*', multiple: 'multiple', class: 'd-none'}).click();
				
				Image.change(function() {
					$(this).appendTo('#attachments');
					CreatePreviewImage(this, $('[data-toggle=\'PreviewImages\']'), Index);
					$('#RowFiles').html('Выбрано ' + RowFiles($('#attachments')) + ' файл(-ов).');
				});
			});
			}
			
			$('#AttachmentImages').modal('show');
		});
	});
	
	PreloadImages();
	BindLikes();
	BindSubscribe();
	
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
	
	$('.dropdown.noty').bind('click', function() {
		if($('header .bell .badge').length != '0') {
			SendPost('/application/backstage/Noty.php', {ReadyAll: 1}, (Result) => {
				$('header .bell .badge').remove();
			});
		}
	});
	
	$('[href]').bind('click', function(e) {
		e.preventDefault();
		location.href = $(this).attr('href');
	});
	
	$('[data-bs-toggle=\'popover\']').popover();
	$('[data-bs-toggle=\'tooltip\']').tooltip();
	
	$('script').remove();
});