<div class="block">
	<ul class="unset">
		<li class="{if(empty($_GET['act']))}active{/if}"><a href="/account/settings">Личные данные и внешний вид</a></li>
		<li class="{if(isset($_GET['act']) AND $_GET['act'] == 'blacklist')}active{/if}"><a href="/account/settings?act=blacklist">Чёрный список</a></li>
	</ul>
</div>