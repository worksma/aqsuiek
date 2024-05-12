<!DOCTYPE HTML>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<meta name="title" content="{title}">
		<meta name="description" content="{meta_description}">
		<meta name="keywords" content="{meta_keywords}">
		<meta name="document-state" content="Dynamic">
		<meta name="author"content="aqsuiek.kz">
		<meta name="revisit" content="1">
		<meta name="robots" content="all">
		<meta property="og:url" content="https://{{$_PAGE['url']}}">
		<meta property="og:type" content="website">
		<meta property="og:title" content="{title}">
		<meta property="og:description" content="{meta_description}">
		<meta property="og:site_name" content="{title}">
		<meta property="og:image" content="{meta_image}">
		<meta property="og:locale" content="alternate">
		<meta name="dc.title" content="{title}">
		<meta name="dc.rights" content="Copyright 2023, Aqsuiek. Все права защищены.">
		<meta name="dc.creator" content="aqsuiek.kz">
		<meta name="dc.language" content="RU">
		
		<title>
			{title}
		</title>
		
		<link rel="stylesheet" href="/public/assets/{appearance}/css/start.css?v={cache}">
		<link rel="stylesheet" href="/public/assets/{appearance}/css/custom/all.css?v={cache}">
		<link rel="stylesheet" href="/public/plugins/toasty/toasty.min.css?v={cache}">
		<link rel="shortcut icon" href="/public/images/system/favicon.png?v={cache}" type="image/x-icon">
		
		{styles}
		
		<script src="/public/plugins/bootstrap/js/bootstrap.bundle.min.js?v={cache}"></script>
		<script src="/public/plugins/jquery/js/jquery-3.6.4.min.js?v={cache}"></script>
		<script async src="/public/plugins/toasty/toasty.min.js?v={cache}"></script>
		
		<script src="/public/system/functions.js?v={cache}"></script>
	</head>
	
	<body>
		<input type="hidden" id="csrf_token" value="{csrf_token}">
		<input type="hidden" id="language" value="{{$_SESSION['lang']}}">
		
		<main>
			{content}
		</main>
		
		<script src="/public/system/ajax.js?v={cache}"></script>
		{scripts}
	</body>
</html>