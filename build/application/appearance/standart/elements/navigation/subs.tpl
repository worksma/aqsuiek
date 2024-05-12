<div class="block">
	<div class="content">
		<ul>
			<li class="{if(empty($_GET['section']) OR $_GET['section'] == 'all')}active{/if}">
				<a href="?section=all">
					Взаимные подписки
				</a>
			</li>
				
			<li class="{if(isset($_GET['section']) AND $_GET['section'] == 'requests')}active{/if}">
				<a href="?section=requests">
					Заявки 
					
					{if($Friends = new Friends)}
						{if($Count = $Friends->RowRequest($_SESSION['id']))}
							<span class="badge">+{{$Count}}</span>
						{/if}
					{/if}
				</a>
			</li>
		</ul>
	</div>
</div>