<button class="Dialog" dialog="{roomid}">
	<img src="{image}">
	
	<div class="Info">
		<div class="Top">
			<span>{name}</span>
			<span>{date}</span>
		</div>
		
		<div class="Bottom">
			<span>{message}</span>
			{noty}
		</div>
	</div>
</button>

<script>
	$('button[dialog="{roomid}"]').bind('click', function() {
		location.href = '?sel=' + $(this).attr('dialog');
	});
</script>