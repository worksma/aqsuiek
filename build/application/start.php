<?PHP
	if(!file_exists(__DIR__ . '/configs/database.php')) {
		die(
			'No Database'
		);
	}
	
	/*
		Открытие Сессий
	*/
	if(empty(session_id())) {
		session_start();
	}
	
	/*
		Различные Функции
	*/
	require_once(
		'system/functions.php'
	);
	
	/*
		Функции Отлова событий
	*/
	require_once(
		'system/callback.php'
	);
	
	/*
		Загрузка Библиотек
	*/
	require_once(
		'system/bootstrap.php'
	);
	
	/*
		Пользовательские
	*/
	require_once(
		'system/custom.php'
	);
	
	
	/* Класс CSRF защита */
	$csrf = new CSRF;
	
	/*
		Создание переменных
	*/
	/* Шаблонизатор */
	$pdo = CreatePDO(
		include(
			'configs/database.php'
		)
	);
	
	/* Конфиги */
	$conf = $pdo->query(
		'SELECT * FROM `config` LIMIT 1'
	)->fetch(
		PDO::FETCH_OBJ
	);
	
	$memcached = new Memcached();
	$memcached->addServer('127.0.0.1', 11211);
	
	/* Пакеты */
	$pkg = new Packages;
	
	/* Пользователи */
	$usr = new Users;
	
	if(isset($_SESSION['id']) && !$usr->IsValid($_SESSION['id'])) {
		require_once('routing/account/logout.php');
		
		die;
	}
	
	/* Подключение языка */
	$lang = new Language;
	$lang->loadLanguageFromFile('ru', 'ru.json');
	
	/* Данные профиля */
	if(isset($_SESSION['id'])) {
		$Account = $usr->Get($_SESSION['id']);
		$_SESSION['lang'] = $Account->language;
	}
	elseif(empty($_SESSION['lang'])) {
		$_SESSION['lang'] = 'ru';
	}
	
	$lang->setLanguage($_SESSION['lang']);
	
	/* Шаблонизатор */
	$tpl = new Template;
	
	/*
		Подгрузка дополнений
	*/
	Apps::Load();