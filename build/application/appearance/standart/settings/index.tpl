<div class="row Settings">
	<div class="col-lg-3 Menu mb-4">
		{grab('/elements/navigation/back.tpl')}
		{grab('/elements/navigation/settings.tpl')}
	</div>
	
	<div class="col-lg-9 Content">
		<div class="block Cover mb-4">
			<div class="Content">
				<div class="image" data-src="/public/images/cover/{{$Account->cover}}"></div>
				<button class="btn" data-bs-toggle="modal" data-bs-target="#ChangeCover"><i class="bi bi-pencil"></i></button>
			</div>
		</div>
		
		<div class="block">
			<div class="head">Персональный данные</div>
			
			<label>Имя и фамилия</label>
			<div class="mb-3 d-flex">
				<form data-target="ChangeName" class="input-group">
					<input type="text" class="form-control" placeholder="Имя" value="{{$Account->first_name}}" name="first_name" autocomplete="off" required>
					<input type="text" class="form-control" placeholder="Фамилия" value="{{$Account->last_name}}" name="last_name" autocomplete="off" required>
					<input type="submit" class="btn btn-primary" value="Изменить">
				</form>
			</div>
			
			<label>Дата рождения</label>
			<div class="mb-3 d-flex">
				<form data-target="ChangeBirthday" class="input-group">
					<input type="date" class="form-control" placeholder="Дата рождения" name="birthday" value="{if($Account->birthday != '0000-00-00')}{{$Account->birthday}}{else}{echo(date('Y-m-d'))}{/if}">
					
					<input type="submit" class="btn btn-primary" value="Изменить">
				</form>
			</div>
			
			<label>Адрес проживания</label>
			<div class="mb-3 d-flex">
				<form data-target="ChangeCity" class="input-group">
					<select class="form-select" name="Country">
						{{echo(Country::GetList($Account->country))}}
					</select>
					
					<select class="form-select" name="City">
						{{echo(Country::GetCityList($Account->country, $Account->city))}}
					</select>
					
					<input type="submit" class="btn btn-primary" value="Изменить">
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="ChangeCover" tabindex="-1" aria-labelledby="ChangeCoverLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form class="modal-content" data-target="ChangeCover">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="ChangeCoverLabel">Изменение обложки</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			
			<div class="modal-body">
				<div class="alert info" id="ResultChangeCover"></div>
				<input type="file" class="form-control m-0" accept="image/*" name="image" required>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
				<input type="submit" class="btn btn-primary" value="Изменить">
			</div>
		</form>
	</div>
</div>