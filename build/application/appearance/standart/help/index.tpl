<div class="row Help">
	<div class="col-lg-3 mobile-hide">
		{grab('/elements/navigation/profile-mini.tpl')}
		{grab('/elements/navigation/menu.tpl')}
	</div>
	
	<div class="col-lg-9 Main-Content">
		<div class="block">
			<ul class="nav nav-pills" id="pills-tab" role="tablist">
				<li class="nav-item" role="presentation">
					<a class="nav-link active" href="javascript:void(0);" data-bs-toggle="pill" data-bs-target="#my-requests" type="button">
						Мои запросы
					</a>
				</li>
				
				<li class="nav-item" role="presentation">
					<a class="nav-link" href="javascript:void(0);" data-bs-toggle="pill" data-bs-target="#request-create" type="button">
						Задать вопрос
					</a>
				</li>
			</ul>
			
			<div class="tab-content" id="pills-tabContent">
				<div class="tab-pane fade show active" id="my-requests">
					<ul>{requests}</ul>
				</div>
				
				<div class="tab-pane fade" id="request-create">
					<label class="Cap">
						Здесь вы можете задать любой вопрос о <b>AQSUIEK</b>.
					</label>
					
					<form data-target="SendRequest" data-toggle="Create">
						<input type="text" class="form-control mb-3" placeholder="Опишите свою проблему в двух словах" name="title" minlength="4" maxlength="64" name="title" required>
						<textarea class="form-control" rows="5" placeholder="Расскажите о проблеме чуть подробнее" minlength="14" maxlength="1024" name="description" required></textarea>
						
						<div class="mt-3">
							<input type="submit" class="btn btn-primary btn-sm" value="Отправить">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>