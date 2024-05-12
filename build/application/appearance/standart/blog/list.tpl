<div class="row">
	<div class="col-lg-3 mobile-hide">
		{grab('/elements/navigation/profile-mini.tpl')}
		{grab('/elements/navigation/menu.tpl')}
		{grab('/elements/navigation/left.tpl')}
	</div>
	
	<div class="col-lg-6 Blogs">
		<div class="block">
			<div class="mb-3 Panel">
				<span>
					Все блоги
				</span>
				
				<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#CreateBlog">
					Создать блог
				</button>
				
				<div class="modal fade" id="CreateBlog" tabindex="-1" aria-labelledby="CreateBlogLabel" aria-hidden="true">
					<div class="modal-dialog">
						<form class="modal-content" data-target="Blog" data-toggle="Create">
							<div class="modal-header">
								<h1 class="modal-title fs-5" id="CreateBlogLabel">Создание блога</h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							
							<div class="modal-body">
								<div class="mb-2">
									<label for="Name" class="mb-2">Название</label>
									<input type="text" class="form-control" placeholder="Придумайте название" name="Name" id="Name" autocomplete="off" required>
								</div>
								
								<div class="mb-2">
									<label for="Description" class="mb-2">Описание</label>
									<textarea class="form-control" name="Description" id="Description" placeholder="Расскажите о своём блоге" autocomplete="off" required></textarea>
								</div>
								
								<div class="mb-2">
									<label for="Theme" class="mb-2">Тематика</label>
									<select class="form-select" name="Theme" id="Theme">
										{if($sth = pdo()->query('SELECT * FROM `blog__theme` WHERE 1'))}
											{if($sth->rowCount())}
												{while($row = $sth->fetch(PDO::FETCH_OBJ))}
													<option value="{{$row->id}}">{{$row->name}}</option>
												{/while}
											{else}
												<option selected disabled>Выберите тематику</option>
											{/if}
										{else}
											<option selected disabled>Выберите тематику</option>
										{/if}
									</select>
								</div>
							</div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
								<button type="submit" class="btn btn-primary">Создать</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			
			<form data-target="SearchBlogs">
				<div class="input-group">
					<input type="text" class="form-control" name="q" placeholder="Поиск блогов" minlength="4" autocomplete="off" required>
					<button tyle="submit" class="btn btn-secondary">
						<i class="bi bi-search"></i>
					</button>
				</div>
			</form>
			
			<div class="List">
				{echo((new Blog)->ListSubs($_SESSION['id']))}
			</div>
		</div>
	</div>
	
	<div class="col-lg-3 mobile-hide">
		{grab('/elements/navigation/right.tpl')}
	</div>
</div>