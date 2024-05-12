<?PHP
	require('application/start.php');
	
	if(empty($_SERVER['SERVER_NAME'])) {
		$_SERVER['SERVER_NAME'] = 'aqsuiek.kz';
	}
	
	if(!file_exists('robots.txt')) {
		file_put_contents('robots.txt', "User-agent: *\nDisallow: /acp/\nHost: " . $_SERVER['SERVER_NAME'] . "\nSitemap: https://" . $_SERVER['SERVER_NAME'] . "/sitemap.xml");
	}
	
	/*
		Стандартные страницы
	*/
	tpl()->AddCell('sitemap', tpl()->Set(['{uri}' => 'https://' . $_SERVER['SERVER_NAME'] . '/account/login', '{date}' => date('Y-m-d H:i:s'), '{priority}' => '0.8',], tpl()->Get('elements/sitemap/urlset')));
	tpl()->AddCell('sitemap', tpl()->Set(['{uri}' => 'https://' . $_SERVER['SERVER_NAME'] . '/account/register', '{date}' => date('Y-m-d H:i:s'), '{priority}' => '0.8',], tpl()->Get('elements/sitemap/urlset')));
	tpl()->AddCell('sitemap', tpl()->Set(['{uri}' => 'https://' . $_SERVER['SERVER_NAME'] . '/account/recovery', '{date}' => date('Y-m-d H:i:s'), '{priority}' => '0.8',], tpl()->Get('elements/sitemap/urlset')));
	
	/*
		Пользователи
	*/
	try {
		$sth = pdo()->query('SELECT * FROM `users` WHERE 1');
		
		if($sth->rowCount()) {
			while($User = $sth->fetch(PDO::FETCH_OBJ)) {
				tpl()->AddCell('sitemap', tpl()->Set(['{uri}' => 'https://' . $_SERVER['SERVER_NAME'] . '/id' . $User->id, '{date}' => date('Y-m-d H:i:s', strtotime($User->register)), '{priority}' => '0.8',], tpl()->Get('elements/sitemap/urlset')));
			}
		}
	}
	catch(Exception $e) {
		AddLogs('pdo.txt', "[SiteMap]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
	}
	
	/*
		Записи
	*/
	try {
		$sth = pdo()->query('SELECT * FROM `writing` WHERE `remove`!=\'1\'');
		
		if($sth->rowCount()) {
			while($Write = $sth->fetch(PDO::FETCH_OBJ)) {
				tpl()->AddCell('sitemap', tpl()->Set(['{uri}' => 'https://' . $_SERVER['SERVER_NAME'] . '/write-' . $Write->author . '_' . $Write->id, '{date}' => date('Y-m-d H:i:s', strtotime($Write->date)), '{priority}' => '0.8',], tpl()->Get('elements/sitemap/urlset')));
			}
		}
	}
	catch(Exception $e) {
		AddLogs('pdo.txt', "[SiteMap]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
	}
	
	/*
		Блоги
	*/
	try {
		$sth = pdo()->query('SELECT * FROM `blog`');
		
		if($sth->rowCount()) {
			while($Blog = $sth->fetch(PDO::FETCH_OBJ)) {
				tpl()->AddCell('sitemap', tpl()->Set(['{uri}' => 'https://' . $_SERVER['SERVER_NAME'] . '/blog' . $Blog->id, '{date}' => date('Y-m-d H:i:s', strtotime($Blog->date)), '{priority}' => '0.8',], tpl()->Get('elements/sitemap/urlset')));
			}
		}
	}
	catch(Exception $e) {
		AddLogs('pdo.txt', "[SiteMap]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
	}
	
	/*
		Посты блогов
	*/
	try {
		$sth = pdo()->query('SELECT * FROM `blog__post`');
		
		if($sth->rowCount()) {
			while($BlogPost = $sth->fetch(PDO::FETCH_OBJ)) {
				tpl()->AddCell('sitemap', tpl()->Set(['{uri}' => 'https://' . $_SERVER['SERVER_NAME'] . '/blog-' . $BlogPost->blogid . '_' . $BlogPost->id, '{date}' => date('Y-m-d H:i:s', strtotime($BlogPost->date)), '{priority}' => '0.8',], tpl()->Get('elements/sitemap/urlset')));
			}
		}
	}
	catch(Exception $e) {
		AddLogs('pdo.txt', "[SiteMap]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
	}
	
	file_put_contents('sitemap.xml', tpl()->Set(['{urlset}' => tpl()->GetCell('sitemap')], tpl()->Get('elements/sitemap/sample')));
	
	/*
		Карта сайта RSS
	*/
	$sitemap = new SimpleXMLElement(file_get_contents('sitemap.xml'));
	
	// Создаем XML-документ для RSS-канала
	$rss = new SimpleXMLElement('<rss version="2.0"/>');
	$channel = $rss->addChild('channel');
	$channel->addChild('title', conf()->site_name);
	$channel->addChild('link', 'https://' . $_SERVER['SERVER_NAME']);
	$channel->addChild('description', conf()->description);
	
	foreach($sitemap->url as $url) {
		$item = $channel->addChild('item');
		$item->addChild('title', $url->title);
		$item->addChild('link', $url->loc);
		$item->addChild('pubDate', date('D, d M Y H:i:s O', strtotime($url->lastmod)));
	}
	
	file_put_contents('rss.xml', $rss->asXML());