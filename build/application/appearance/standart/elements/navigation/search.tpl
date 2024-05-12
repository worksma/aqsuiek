<div class="block mb-4">
	<div class="content">
		<ul>
			<li class="{if(empty($_GET['type']) OR $_GET['type'] == 'people')}active{/if}">
				<a href="?type=people">
					Люди
				</a>
			</li>
		</ul>
	</div>
</div>

{if(empty($_GET['type']) OR $_GET['type'] == 'people')}
	<input type="hidden" value="people" id="SearchType">

	<div class="block">
		<div class="content">
			<div class="caption">Параметры поиска</div>
			
			<div class="mb-3">
				<div class="qualifier">Страна</div>
				
				<select class="form-select" name="Country">
					{{echo(Country::GetList({countryid}))}}
				</select>
			</div>
			
			<div class="mb-3">
				<div class="qualifier">Город</div>
				
				<select class="form-select" name="City" id="SearchCity">
					{{echo(Country::GetCityList({countryid}, {cityid}))}}
				</select>
			</div>
			
			<div class="mb-3">
				<div class="qualifier">Возраст</div>
				
				<div class="input-group">
					<input type="number" class="form-control" placeholder="от" min="14" id="AgeStart">
					<input type="number" class="form-control" placeholder="до" min="14" id="AgeEnd">
				</div>
			</div>
		</div>
		
		<button class="btn btn-primary w-100" onclick="$('#GoSearch').click();">Сортировать</button>
	</div>
{/if}