<div class="row Blog-Main-Page">
	<div class="col-lg-8 Posts order-mobile-2">
		<div class="row">
			{if('{access}' == '1')}
				<div class="input-group mb-3">
					<button class="NewPost" data-bs-toggle="modal" data-bs-target="#NewPost">
						<i class="bi bi-plus"></i>
						Публикация
					</button>
				</div>
			{/if}
		
			{{$Blog->PostsUI('{id}')}}
		</div>
	</div>
	
	<div class="col-lg-4 Panel mb-4 order-mobile-1">
		<div class="block mb-3">
			<div class="BlogDivImage">
				<div class="BlogImage {if('{access}' == '1')}Admin{/if}">
					<img src="/public/images/blog/{image}" {if('{access}' != '1')}data-target="Zoom" data-toggle="Image"{/if}>
					
					{if('{access}' == '1')}
						<button data-bs-toggle="modal" data-bs-target="#ChangeBlogImage"><i class="bi bi-camera"></i></button>
					{/if}
				</div>
				
				{if('{access}' == '1')}
					<div class="modal fade" id="ChangeBlogImage" tabindex="-1" aria-labelledby="ChangeBlogImageLabel" aria-hidden="true">
						<div class="modal-dialog">
							<form class="modal-content" data-target="ChangeBlogImage">
								<input type="hidden" name="blogid" value="{id}">
								
								<div class="modal-header">
									<h1 class="modal-title fs-5" id="ChangeBlogImageLabel">Изменение фотографии</h1>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								
								<div class="modal-body">
									<div class="alert info" id="ResultChangeBlogImage"></div>
									<input type="file" class="form-control m-0" accept="image/jpeg" name="image" required>
								</div>
								
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
									<input type="submit" class="btn btn-primary" value="Изменить">
								</div>
							</form>
						</div>
					</div>
				{/if}
			</div>
			
			<div class="BlogDescription">
				<div class="Title">
					Описание
				</div>
				
				{description}
				
				<div class="Buttons">
				{if(isset($_SESSION['id']))}
					{if('{access}' != NULL)}
						{if('{access}' > 1)}
							<button class="btn btn-warning btn-sm" onclick="BlogSub({id});">Отписаться</button>
						{/if}
					{else}
						<button class="btn btn-primary btn-sm" onclick="BlogSub({id});">Подписаться</button>
					{/if}
				{/if}
				</div>
			</div>
		</div>
		
		<div class="block subs">
			<div class="head">Подписчики</div>
			<div class="content">
				{{$Blog->SubsUI('{id}')}}
			</div>
		</div>
	</div>
</div>

{if('{access}' == '1')}
	<div class="modal fade" id="NewPost" tabindex="-1" aria-labelledby="NewPostLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<form class="modal-content" data-target="Blog" data-toggle="NewPost">
				<input type="hidden" name="blogid" value="{id}">
				
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="NewPostLabel">Новая публикация</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				
				<div class="modal-body">
					<div class="mb-2">
						<label for="Title" class="mb-2">Заголовок</label>
						<input type="text" class="form-control" placeholder="Придумайте заголовок" name="Title" id="Title" autocomplete="off" required>
					</div>
					
					<div class="mb-2">
						<label for="Content" class="mb-2">Контент</label>
						<textarea id="Content"></textarea>
					</div>
					
					<div class="mb-2">
						<label for="Image" class="mb-2">Изображение</label>
						<input type="file" class="form-control" accept="image/*" name="Image" id="Image" autocomplete="off" required>
					</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary">Опубликовать</button>
				</div>
			</form>
		</div>
	</div>
	
	<script>
		$(function() {
			tinyMCE.init({
				selector: 'textarea#Content',
				language: 'ru'
			});
		});
	</script>
{/if}