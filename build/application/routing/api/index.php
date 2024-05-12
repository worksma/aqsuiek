<?PHP
	$rootDir = __DIR__ . '/../../system/api';
	
	/*
		Проверяем доступность версии
	*/
	$versionDir = $rootDir . '/v' . $_PAGE['params'][1];
	
	if(!file_exists($versionDir)) {
		AlertError('Incorrect version');
	}
	
	/*
		Проверяем доступность модуля обращения
	*/
	$moduleDir = $versionDir . '/' . $_PAGE['params'][2];
	
	if(!file_exists($moduleDir)) {
		AlertError('The referral module was not found');
	}
	
	/*
		Проверяем доступность скрипта обращения
	*/
	$scriptFile = $moduleDir . '/' . $_PAGE['params'][3] . '.php';
	
	if(!file_exists($scriptFile)) {
		AlertError('The request script was not found');
	}
	
	require($scriptFile);