<div class="row">
	<div class="col-lg-3 mobile-hide">
		{grab('/elements/navigation/profile-mini.tpl')}
		{grab('/elements/navigation/menu.tpl')}
	</div>
	
	<div class="col-lg-6 SearchResult order-mobile-2">
		<div class="block">
			<div class="mb-4 input-group">
				<input type="text" class="form-control" placeholder="Кого или что ищем?" id="SearchText" autocomplete="off">
				
				<button class="btn btn-primary" style="z-index: auto;" id="GoSearch">
					Искать
				</button>
			</div>
			
			<ul id="Result">
				{result}
			</ul>
		</diV>
	</div>
	
	<div class="col-lg-3 ChangeTypeSearch order-mobile-1">
		{SearchNavs}
	</div>
</div>