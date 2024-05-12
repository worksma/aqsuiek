<div class="row Help">
	<div class="col-lg-3 mobile-hide">
		{grab('/elements/navigation/back.tpl')}
		{grab('/elements/navigation/menu.tpl')}
	</div>
	
	<div class="col-lg-9 Messages">
		<div class="block">
			<div class="mb-4 request_title">
				<div>{request_title}</div>
				<div class="Status">{request_status}</div>
			</div>
			
			<ul id="Messages">
				{messages}
			</ul>
			
			<form class="mt-4" data-target="Help" data-toggle="Send">
				<div class="d-flex gap-2">
					<input type="hidden" name="ticketid" value="{id}">
					<input type="text" name="message" class="form-control" placeholder="Напишите сообщение" minlenght="2" maxlenght="1024" autocomplete="off" required>
					<input type="submit" class="btn btn-primary" value="Отправить">
				</div>
			</form>
		</div>
	</div>
</div>