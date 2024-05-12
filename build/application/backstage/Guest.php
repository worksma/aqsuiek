<?PHP
	require(
		'../start.php'
	);
	
	IsValidActions();
	
	if(isset($_SESSION['id'])) {
		AlertWarning(
			'Сначала покиньте учётную запись'
		);
	}
	
	if(isset($_POST['Register'])) {
		$birthStart = strtotime($_POST['birthday']);
		$birthEnd = time();
		$birthLimit = 16 * 365 * 24 * 60 * 60;
		
		if(($birthEnd - $birthStart) < $birthLimit) {
			AlertError('Для регистрации Вам должно быть не менее 16 лет');
		}
		
		if(Users()->IsValidEmail(htmlspecialchars(strip_tags($_POST['email'])))) {
			AlertWarning('Указанный вами почтовый адрес уже занят');
		}
		
		$_POST['nickname'] = preg_replace('/[^a-zA-Z0-9_\s]/', '', $_POST['nickname']);
		
		if(Users()->IsValidNickname(htmlspecialchars(strip_tags($_POST['nickname'])))) {
			AlertWarning('Выбранный вами псевдоним уже занят');
		}
		
		$ResultId = Users()->Register([
			'email'			=> htmlspecialchars(strip_tags($_POST['email'])),
			'password'		=> htmlspecialchars(strip_tags($_POST['password'])),
			'first_name'	=> htmlspecialchars(strip_tags($_POST['first_name'])),
			'last_name'		=> htmlspecialchars(strip_tags($_POST['last_name'])),
			'nickname'		=> htmlspecialchars(strip_tags($_POST['nickname'])),
			'birthday'		=> date('Y-m-d', $birthStart),
			'sex'			=> ($_POST['sex'] == '1') ? 1 : 2,
			'balance'		=> '0',
			'beta'			=> isset($_POST['beta']) ? htmlspecialchars(strip_tags($_POST['beta'])) : NULL
		]);
		
		if(isset($ResultId)) {
			$_SESSION['id'] = $ResultId;
		}
		else {
			AlertWarning('Сервер отклонил запрос');
		}
		
		AlertSuccess('Успешная регистрация');
	}
	
	if(isset($_POST['Authorization'])) {
		if(!Users()->IsValidEmail(htmlspecialchars(strip_tags($_POST['email'])))) {
			AlertError('Учётная запись не существует');
		}
		
		$Result = Users()->Auth([
			'email'			=> htmlspecialchars(strip_tags($_POST['email'])),
			'password'		=> htmlspecialchars(strip_tags($_POST['password']))
		]);
		
		if($Result) {
			AlertSuccess('Успешная авторизация');
		}
		else {
			AlertWarning('Неверный пароль');
		}
	}
	
	if(isset($_POST['Recovery'])) {
		try {
			Users()->Recovery($_POST['email']);
			AlertSuccess('Проверьте почтовый ящик');
		}
		catch(Exception $e) {
			AlertError($e->getMessage());
		}
	}
	
	if(isset($_POST['RecoveryEnd'])) {
		if(!Users()->IsRecoveryHash($_POST['hash'])) {
			AlertError('Срок действия ключа закончился');
		}
		
		$Recovery = Users()->GetRecovery($_POST['hash']);
		$User = Users()->GetForEmail($Recovery->email);
		
		try {
			Users()->ChangePassword($User->id, $_POST['password']);
			
			Noty()->Send($User->id, [
				'link' => '/settings?act=security',
				'image' => 'cyber.svg',
				'content' => 'Ваш пароль был изменён',
				'type' => '1'
			]);
			
			try {
				Users()->RecoveryRemoveHash($_POST['hash']);
			}
			catch(Exception $e) {}
			
			AlertSuccess('Данные обновлены');
		}
		catch(Exception $e) {
			AlertError($e->getMessage());
		}
	}