<div class="col-lg-12">
	<div class="card mb-4">
		<div class="card-body">
			<div class="card-title">Основные настройки</div>
			
			<div class="mb-2">
				<label for="site_name">Название сайта</label>
				<form data-target="Panel" data-toggle="SiteName" class="input-group">
					<button type="submit" class="btn btn-primary">Изменить</button>
					<input type="text" class="form-control" value="{site_name}" id="site_name" name="site_name">
				</form>
			</div>
			
			<div class="mb-2">
				<label for="site_description">Описание сайта</label>
				<form data-target="Panel" data-toggle="SiteDesc" class="input-group">
					<button type="submit" class="btn btn-primary">Изменить</button>
					<textarea class="form-control" rows="2" id="site_description" name="site_description">{site_description}</textarea>
				</form>
			</div>
			
			<div class="mb-2">
				<label for="site_keywords">Поисковые теги</label>
				<form data-target="Panel" data-toggle="SiteKeys" class="input-group">
					<button type="submit" class="btn btn-primary">Изменить</button>
					<input type="text" class="form-control" value="{site_keywords}" id="site_keywords" name="site_keywords">
				</form>
			</div>
		</div>
	</div>
</div>