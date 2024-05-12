<li class="Message">
	{if('{id}' == '')}
		<center>Нет сообщений</center>
	{else}
		{if('{support}' == '')}
			<a href="/id{profile_id}"><img src="{profile_image}"></a>
			
			<div class="Info">
				<div class="Name">
					{profile_name}
				</div>
					
				<div class="Message">
					{message}
				</div>
				
				<div class="Date">
					{date}
				</div>
			</div>
		{else}
			<img src="{profile_image}">
			
			<div class="Info">
				<div class="Name">
					{profile_name}
				</div>
					
				<div class="Message">
					{message}
				</div>
				
				<div class="Date">
					{date}
				</div>
			</div>
		{/if}
	{/if}
</li>