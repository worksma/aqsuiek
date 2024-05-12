<?PHP
	require('../start.php');
	
	IsValidActions();
	
	if(empty($_SESSION['id'])) {
		AlertError(
			'Вы не авторизованы'
		);
	}
	
	if(isset($_POST['NewPost'])) {
		$Blog = new Blog;
		
		if(!$Blog->IsValid($_POST['blogid'])) {
			AlertError('Выбранный вами блог не существует');
		}
		
		if(empty($_POST['blogid']) || !$Blog->IsAccess($_POST['blogid'], $_SESSION['id'], 1)) {
			AlertError('Недостаточно прав');
		}
		
		$Upload = new Upload;
		
		try {
			$Upload->Image($_FILES['Image'], 'public/images/blog', function($_ARRAY) {
				$PostId = (new Blog)->AddPost($_POST['blogid'], [
					'image' => $_ARRAY['Document'],
					'title' => htmlspecialchars($_POST['Title']),
					'content' => htmlspecialchars($_POST['Content'])
				]);
				
				Result(['Alert' => 'Success', 'Uri' => '/blog-' . $_POST['blogid'] . '_' . $PostId]);
			});
		}
		catch(Exception $e) {
			AlertError($e->getMessage());
		}
	}
	
	if(isset($_POST['ChangeBlogImage'])) {
		$Blog = new Blog;
		
		if(!$Blog->IsValid($_POST['blogid'])) {
			AlertError('Выбранный вами блог не существует');
		}
		
		if(empty($_POST['blogid']) || !$Blog->IsAccess($_POST['blogid'], $_SESSION['id'], 1)) {
			AlertError('Недостаточно прав');
		}
		
		$Upload = new Upload;
		
		try {
			$Upload->Image($_FILES['image'], 'public/images/blog', function($_ARRAY) {
				try {
					$sth = pdo()->prepare('UPDATE `blog` SET `image`=:image WHERE `id`=:id LIMIT 1');
					$sth->execute([':image' => $_ARRAY['Document'], ':id' => $_POST['blogid']]);
					
					AlertSuccess('Изображение успешно обновлено');
				}
				catch(Exception $e) {
					AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
				}
				
				AlertError(getLang('blog_msg_block_server'));
			});
		}
		catch(Exception $e) {
			AlertError($e->getMessage());
		}
	}
	
	if(isset($_POST['Sub'])) {
		$Blog = new Blog;
		
		if(!$Blog->IsValid($_POST['blogid'])) {
			AlertError('Выбранный вами блог не существует');
		}
		
		if($Blog->IsSub($_POST['blogid'], $_SESSION['id'])) {
			if($Blog->UnSub($_POST['blogid'])) {
				Result(['Alert' => 'Success', 'Content' => '<button class="btn btn-primary btn-sm" onclick="BlogSub(' . $_POST['blogid'] . ');">Подписаться</button>']);
			}
		}
		else {
			if($Blog->Sub($_POST['blogid'])) {
				Result(['Alert' => 'Success', 'Content' => '<button class="btn btn-warning btn-sm" onclick="BlogSub(' . $_POST['blogid'] . ');">Отписаться</button>']);
			}
		}
		
		AlertError(getLang('blog_msg_block_server'));
	}
	
	if(isset($_POST['Create'])) {
		$Blog = new Blog;
		
		$BlogId = $Blog->Create(htmlspecialchars($_POST['Name']), htmlspecialchars($_POST['Description']), $_POST['Theme']);
		
		if(empty($BlogId)) {
			AlertError(getLang('blog_msg_block_server'));
		}
		
		AlertSuccess('/blog' . $BlogId);
	}
	
	/*
		Поиск блогов
	*/
	if(isset($_POST['Search'])) {
		try {
			$Blog = new Blog;
			
			Result([
				'Alert' => 'Success',
				'Content' => $Blog->Search($_POST['q'])
			]);
		}
		catch(Exception $e) {
			AlertError($e->getMessage());
		}
	}