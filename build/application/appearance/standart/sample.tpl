<!DOCTYPE HTML>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
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
		<link rel="stylesheet" href="/public/plugins/toasty/toasty.min.css?v=1">
		<link rel="shortcut icon" href="/public/images/system/favicon.png?v=1" type="image/x-icon">
		
		{styles}
		
		<script src="/public/plugins/bootstrap/js/bootstrap.bundle.min.js?v={cache}"></script>
		<script src="/public/plugins/jquery/js/jquery-3.6.4.min.js?v={cache}"></script>
		<script async src="/public/plugins/toasty/toasty.min.js?v={cache}"></script>
		
		<script src="/public/system/functions.js?v={cache}"></script>
		<script src="/public/system/ajax.js?v={cache}"></script>
		<script src="/public/assets/{appearance}/js/ajax.js?v={cache}"></script>
		
		<!--[ jQuery Confirm ]-->
		<link rel="stylesheet" href="/public/plugins/jquery-confirm/css/jquery-confirm.min.css?v={cache}">
		<script src="/public/plugins/jquery-confirm/js/jquery-confirm.min.js?v={cache}"></script>
		
		<!--[ Smartphoto ]-->
		<link rel="stylesheet" href="/public/plugins/lightgallery/css/lightgallery.css?v={cache}">
		<script src="/public/plugins/lightgallery/js/lightgallery-all.min.js?v={cache}"></script>
	</head>
	
	<body>
		<input type="hidden" id="csrf_token" value="{csrf_token}">
		<input type="hidden" id="language" value="{{$_SESSION['lang']}}">
		
		<header>
			<div class="container">
				<div class="row">
					<div class="col-lg-3 only-mobile-menu">
						<a class="brand" href="/">
							<img src="/public/assets/{appearance}/img/logo.png?v={cache}">
							<hr class="vertical">
							<div class="text">
								<p>AQSUIEK</p>
							</div>
						</a>
						
						<div class="only-mobile-show menu">
							<button class="btn" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
								<i class="bi bi-three-dots-vertical"></i>
							</button>
						</div>
					</div>
					{if(isset($_SESSION['id']))}
					<div class="col-lg-6 mobile-hide">
						<ul class="nav" data-target="horizontal">
							<li href="/flow"><a><i class="bi bi-rss"></i></a></li>
							<li href="/id{{$Account->id}}"><a><i class="bi bi-person-circle"></i></a></li>
							<li href="/subscriptions"><a><i class="bi bi-people"></i></a></li>
							<li href="/messages"><a><i class="bi bi-chat-left-text"></i></a></li>
							<!--[<li href="/music"><a><i class="bi bi-music-note-list"></i></a></li>]-->
							<li href="/blogs"><a><i class="bi bi-journal-text"></i></a></li>
						</ul>
					</div>
					
					<div class="col-lg-3 mobile-hide">
						<div class="profile">
							<ul class="nav">
								<li><a href="/search"><i class="bi bi-search"></i></a></li>
								<hr class="vertical">
								
								{if($Noty = new Noty)}
									{{$Noty->Nav()}}
								{/if}
							</ul>
							
							<div class="dropdown">
								<div class="dropdown-toggle d-flex justify-content-center align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
									<div class="avatar" >
										<img src="{{GetUserAvatar($_SESSION['id'])}}">
									</div>
									
									<ul class="dropdown-menu dropdown-menu-end">
										<li class="mini-profile">
											<div class="block">
												<div class="avatar"><img src="{{GetUserAvatar($_SESSION['id'])}}"></div>
												<div class="info">
													<div class="name">{echo(GetUserName($_SESSION['id'], ['very' => true]))}</div>
													<div class="email">{{$Account->email}}</div>
												</div>
											</div>
											
											<div class="block">
												<div class="balance">
													<div class="Info">
														<div>Баланс</div>
														<div>{echo(GetUserMoney($_SESSION['id']))}</div>
													</div>
													
													<i class="bi bi-wallet2"></i>
												</div>
											</div>
										</li>
										<li><a class="dropdown-item" href="/account/settings"><i class="bi bi-gear"></i> Настройки</a></li>
										<li><a class="dropdown-item" href="/help"><i class="bi bi-question-circle"></i> Помощь</a></li>
										<li><a class="dropdown-item" href="/account/logout"><i class="bi bi-box-arrow-right"></i> Выйти</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					{/if}
				</div>
			</div>
		</header>
	
		<main id="Main">
			<div class="container">
				{content}
			</div>
		</main>
		
		{if(isset($_SESSION['id']))}
			<div class="bottom-panel only-mobile-show">
				<ul>
					<li href="/flow"><i class="bi bi-house-door"></i></li>
					<li href="/search"><i class="bi bi-search"></i></li>
					<li href="/messages"><i class="bi bi-chat"></i></li>
					<li href="/notifications"><i class="bi bi-bell"></i></li>
					<li href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu"><i class="bi bi-list"></i></li>
				</ul>
			</div>
		{/if}
		
		<div class="offcanvas offcanvas-end only-mobile-show" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
			<div class="offcanvas-body">
				<a class="Profile" href="/id{{$_SESSION['id']}}">
					<i class="bi bi-person-fill"></i>
				</a>
				
				<button type="button" data-bs-dismiss="offcanvas" aria-label="Close">
					<i class="bi bi-chevron-right"></i>
				</button>
				
				{if(isset($_SESSION['id']))}
					{if($User = Users()->Get($_SESSION['id']))}
						<div class="Profile">
							<img src="{echo(GetUserAvatar($User->id))}">
							
							<ul>
								<li>
									<span>{echo(WidgetStats::Subs($_SESSION['id']))}</span>
									подписок
								</li>
								<li>
									<span>{echo(WidgetStats::Likes($_SESSION['id']))}</span>
									лайки
								</li>
								<li>
									<span>{echo(WidgetStats::Writes($_SESSION['id']))}</span>
									записи
								</li>
							</ul>
						</div>
						
						<div class="Nav">
							<ul>
								<li class="Name">
									<p>{echo(GetUserName($User->id))}</p>
									<p>{{$User->balance}} &#x20bd;</p>
								</li>
								
								<li class="Hr"></li>
								
								<li class="Link"><a href="/flow"><i class="bi bi-rss"></i> Мой Flow</a></li>
								<li class="Link"><a href="/subscriptions"><i class="bi bi-people"></i> Подписки</a></li>
								<li class="Link"><a href="/blogs"><i class="bi bi-journal-text"></i> Блоги</a></li>
								
								<li class="Link"><a href="/account/logout"><i class="bi bi-box-arrow-right"></i> Выйти</a></li>
							</ul>
						</div>
					{/if}
				{/if}
			</div>
		</div>
		
		{scripts}
	</body>
</html>