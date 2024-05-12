<?PHP
	$blogid = $_PAGE['params'][1];
	
	global $Blog;
	$Blog = new Blog;
	
	if(!$Blog->IsValid($blogid)) {
		ShowPage('BlogInfo', tpl()->Get('elements/blog/not_valid'), 'Информация', 'sample');
	}
	
	$postid = $_PAGE['params'][2];
	
	if(!$Blog->IsPost($postid)) {
		ShowPage('BlogInfo', tpl()->Get('elements/blog/post_not_valid'), 'Информация', 'sample');
	}
	
	// Установка просмотров
	if(!$Blog->IsPostDataKey($postid, 'views', Users()->Ip())) {
		$Blog->AddPostData($postid, 'views', [
			Users()->Ip() => [
				'date' => date('Y-m-d H:i:s')
			]
		]);
	}
	
	// Дальнейшие действия
	$Data = $Blog->Get($blogid);
	$Post = $Blog->GetPost($postid);
	$PostData = $Blog->PostData($postid);
	
	tpl()
	->AddReplace([
		'{meta_name}' => strip_tags(htmlspecialchars_decode($Post->title)),
		'{meta_description}' => str_replace(PHP_EOL, ' ', strip_tags(htmlspecialchars_decode($Post->content))),
		'{meta_keywords}' => str_replace(PHP_EOL, ' ', str_replace(' ', ', ', strip_tags(htmlspecialchars_decode($Post->content)))),
		'{meta_image}' => '/public/images/blog/' . $Post->image
	])
	->Start('sample')
	->SetTitle(strip_tags(Reduction(htmlspecialchars_decode(htmlspecialchars_decode($Post->title)), 70)) . ' | ' . $Data->name)
	->Content(tpl()->Get('blog/post'))
	->Set([
		'{author_id}' => $Post->userid,
		'{post_name}' => strip_tags(htmlspecialchars_decode($Post->title)),
		'{post_image}' => $Post->image,
		'{post_content}' => htmlspecialchars_decode($Post->content),
		'{post_date}' => $Post->date,
		'{blog_id}' => $Data->id,
		'{blog_image}' => $Data->image,
		'{blog_name}' => $Data->name,
		'{likes}' => count($PostData['likes']),
		'{views}' => count($PostData['views'])
	])
	->Show();