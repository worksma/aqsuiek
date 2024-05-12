<div class="row Messages">
	<div class="col-lg-3 col-md-12">
		<div class="block" id="Dialogs">
			{dialogs}
		</div>
	</div>
	
	<div class="col-lg-9 NotSelectDialog mobile-hide">
		<div class="block">
			Выберите диалог, чтобы<br>начать переписку
		</div>
	</div>
</div>

<script>
	$('main').css({'margin-bottom': '0'});
	
	$LastDialogs = 0;
	
	function GetDialogs() {
		SendPost('/application/backstage/Messages.php', {
			GetDialogs: 1
		}, (Result) => {
			if(Result.Dialogs) {
				if(typeof $InterValIdDialogs == 'undefined') {
					$InterValIdDialogs = setInterval(GetDialogs, 2000);
				}
				
				if($LastDialogs != Result.Dialogs.length) {
					$LastDialogs = Result.Dialogs.length;
					$('#Dialogs').html(Result.Dialogs);
				}
			}
		});
	}
	
	GetDialogs();
</script>