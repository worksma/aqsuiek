<!DOCTYPE HTML>
<html lang="ru" data-bs-theme="dark">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>
			{title}
		</title>
		
		<link rel="stylesheet" href="/public/assets/{appearance}/css/start.css?v={cache}">
		<link rel="stylesheet" href="/public/plugins/toasty/toasty.min.css?v={cache}">
		<link rel="shortcut icon" href="/public/images/system/favicon.png?v={cache}" type="image/x-icon">
		
		<script src="/public/plugins/bootstrap/js/bootstrap.bundle.min.js?v={cache}"></script>
		<script src="/public/plugins/jquery/js/jquery-3.6.4.min.js?v={cache}"></script>
		<script async src="/public/plugins/toasty/toasty.min.js?v={cache}"></script>
		
		<script src="/public/system/functions.js?v={cache}"></script>
		<script src="/public/system/panel/ajax.js?v={cache}"></script>
	</head>
	
	<body>
		<input type="hidden" id="csrf_token" value="{csrf_token}">
		
		<div class="loader">
			<div class="loader_inner"></div>
		</div>
		
		<!--[ НАВИГАЦИЯ ]-->
		<nav class="navbar navbar-expand-lg bg-body-tertiary">
			<div class="container">
				<a class="navbar-brand" href="/">
					<img class="logotype" src="/public/assets/{appearance}/img/logo.png?v={cache}" alt="AQSUIEK">
				</a>
				
				<div class="d-contents">
					<div class="d-flex justify-content-end w-100">
						{grab('/elements/darkmode.tpl')}
						
						<div class="btn-group">
							<li class="nav-link profile_name">
								{profile_name}
							</li>
								
							<button type="button" class="btn ms-2 dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
								<img class="profile_image me-1" src="{profile_image}?v={cache}">
							
								<span class="visually-hidden">
									Toggle Dropdown
								</span>
							</button>
								
							<ul class="dropdown-menu dropdown-menu-end">
								<li><a class="dropdown-item" href="/id{profile_id}">Мой профиль</a></li>
								<li><hr class="dropdown-divider"></li>
								<li><a class="dropdown-item" href="/account/logout">Выйти</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</nav>
		
		<div class="navbar-bottom">
			<div class="container">
				<ul class="justify-content-end">
					<li>
						<a class="nav-link" aria-current="page" href="/">Вернуться на сайт</a>
					</li>
				</ul>
			</div>
		</div>
		
		<!--[ КОНТЕНТ ]-->
		<main class="container">
			<div class="row">
				<div class="col-lg-3 left-menu">
					<ul>
						<li><i class="bi bi-sliders"></i><a href="/panel">Основные настройки</a></li>
						<li class="horizontal"><hr></li>
						<li><i class="bi bi-clipboard-heart"></i><a href="/panel/help/tickets">Тикеты пользователей</a></li>
					</ul>
				</div>
				
				<div class="col-lg-9">
					<div class="row">
						{content}
					</div>
				</div>
			</div>
		</main>
	</body>
</html>