<div class="row Messages">
	<div class="col-lg-3 mobile-hide">
		<div class="block" id="Dialogs">
			{dialogs}
		</div>
	</div>
	
	<div class="col-lg-9 col-md-12">
		<div class="block Messages">
			<div class="Page-Messages Profile">
				<div class="Info">
					<div class="Name">
						{name}
					</div>
					<div class="Online">
						{online}
					</div>
				</div>
				
				<div class="Menu">
					<a href="/id{ProfileId}" target="_blank">
						<img class="Avatar" src="{ProfileImage}">
					</a>
				</div>
			</div>
		
			<output id="Chat"></output>
			
			<form data-action="Send">
				<div class="input-group w-100">
					<input type="text" class="form-control" name="message" placeholder="Напишите сообщение..." autocomplete="off" required>
					<input type="submit" class="btn btn-primary" value="Отправить">
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$('main').css({'margin-bottom': '0'});
	
	$RoomLastMessageId = 0;
	$RoomId = '{roomid}';
	$LastDialogs = 0;
	
	function GetMessages(roomid, lastid) {
		if(typeof lastid == 'undefined') {
			lastid = $RoomLastMessageId;
		}
		
		SendPost('/application/backstage/Messages.php', {Get: 1, roomid: roomid, lastid: lastid}, (Result) => {
			if(Result.Messages) {
				if(typeof $InterValId == 'undefined') {
					$InterValId = setInterval(GetMessages, 2000, $RoomId);
				}
				
				if($RoomLastMessageId < Result.LastId) {
					var Container = $('#Chat');
					var StartMaxScroll = Container.prop("scrollHeight") - Container.height();
					
					$RoomLastMessageId = Result.LastId;
					$('#Chat').append(Result.Messages);
					
					if(StartMaxScroll == Container.scrollTop()) {
						Container.scrollTop(Container.prop("scrollHeight"));
					}
				}
			}
			
			if(Result.Dialogs) {
				if($LastDialogs != Result.Dialogs.length) {
					$LastDialogs = Result.Dialogs.length;
					$('#Dialogs').html(Result.Dialogs);
				}
			}
		});
	}
	
	GetMessages($RoomId, $RoomLastMessageId);
</script>