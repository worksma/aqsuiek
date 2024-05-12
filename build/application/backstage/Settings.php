<?PHP
	require(
		'../start.php'
	);
	
	IsValidActions();
	
	if(empty($_SESSION['id'])) {
		AlertError('Сначала пройдите этап авторизации');
	}
	
	if(isset($_POST['ChangeCover'])) {
		if(0 < $_FILES['image']['error'] || !IsTypeImage($_FILES['image']['type'])) {
			AlertError('Тип изображения не соответствует требованиям');
		}
		
		if(stripos(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION), 'php') !== false) {
			AlertError('Формат изображения не соответствует требованиям');
		}
		
		if(!IsExtension(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
			AlertError('Формат изображения не соответствует требованиям');
		}
		
		if(GetFileVolume($_FILES['image']['size'], 'KB') < 100) {
			AlertError('Малый размер файла');
		}
		
		$Name = GetNameString($_FILES['image']['name'], false, $_FILES['image']['type']);
		
		if(move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/public/images/cover/' . $Name)) {
			try {
				$LastData = Users()->Get($_SESSION['id']);
				
				$sth = pdo()->prepare('UPDATE `users` SET `cover`=:cover WHERE `id`=:id LIMIT 1');
				$sth->execute([':cover' => $Name, ':id' => $_SESSION['id']]);
				
				if($LastData->cover != 'no_image.jpg') {
					if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/images/cover/' . $LastData->cover)) {
						unlink($_SERVER['DOCUMENT_ROOT'] . '/public/images/cover/' . $LastData->cover);
					}
				}
				
				Result(['Alert'	=> 'Success', 'File' => $Name]);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Backstage Settings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
		
		AlertError('Сервер отклонил запрос');
	}
	
	if(isset($_POST['ChangeAvatar'])) {
		if(0 < $_FILES['image']['error'] || !IsTypeImage($_FILES['image']['type'])) {
			AlertError('Тип изображения не соответствует требованиям');
		}
		
		if(stripos(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION), 'php') !== false) {
			AlertError('Формат изображения не соответствует требованиям');
		}
		
		if(!IsExtension(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
			AlertError('Формат изображения не соответствует требованиям');
		}
		
		if(GetFileVolume($_FILES['image']['size'], 'KB') < 100) {
			AlertError('Малый размер файла');
		}
		
		$Name = GetNameString($_FILES['image']['name'], false, $_FILES['image']['type']);
		
		if(move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/public/images/avatars/' . $Name)) {
			try {
				$LastData = Users()->Get($_SESSION['id']);
				
				$sth = pdo()->prepare('UPDATE `users` SET `image`=:image WHERE `id`=:id LIMIT 1');
				$sth->execute([':image' => $Name, ':id' => $_SESSION['id']]);
				
				if($LastData->image != 'no_image.jpg') {
					if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/images/avatars/' . $LastData->image)) {
						unlink($_SERVER['DOCUMENT_ROOT'] . '/public/images/avatars/' . $LastData->image);
					}
				}
				
				(new Events)->Add($_SESSION['id'], '<span>' . $LastData->first_name . '</span> обновил фотографию в своём профиле.', '/id' . $_SESSION['id']);
				
				Result(['Alert'	=> 'Success', 'File' => $Name]);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Backstage Settings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
		
		AlertError('Сервер отклонил запрос');
	}
	
	if(isset($_POST['GetCityList'])) {
		Result(['List' => Country::GetCityList($_POST['CountryId'])]);
	}
	
	if(isset($_POST['ChangeCity'])) {
		if(!Country::IsValid($_POST['Country'], $_POST['City'])) {
			AlertError('Неверно выбран город или страна');
		}
		
		try {
			$sth = pdo()->prepare('UPDATE `users` SET `country`=:country, `city`=:city WHERE `id`=:id LIMIT 1');
			$sth->execute([':country' => $_POST['Country'], ':city' => $_POST['City'], ':id' => $_SESSION['id']]);
			
			AlertSuccess('Данные обновлены');
		}
		catch(Exception $e) {
			AddLogs('pdo.txt', "[Backstage Settings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
		}
		
		AlertError('Сервер отклонил запрос');
	}
	
	if(isset($_POST['ChangeBirthday'])) {
		$birth = strtotime($_POST['birthday']);
		
		if((time() - 441504000) < $birth) {
			AlertError('Минимальный возраст для регистрации составляет 14 лет.');
		}
		
		try {
			$sth = pdo()->prepare('UPDATE `users` SET `birthday`=:birthday WHERE `id`=:id LIMIT 1');
			$sth->execute([':birthday' => $_POST['birthday'], ':id' => $_SESSION['id']]);
			
			AlertSuccess('Данные обновлены');
		}
		catch(Exception $e) {
			AddLogs('pdo.txt', "[Backstage Settings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
		}
		
		AlertError('Сервер отклонил запрос');
	}
	
	/*
		Чёрный список
	*/
	if(isset($_POST['UnBlackList'])) {
		if(class_exists('BlackList')) {
			$BlackList = new BlackList;
			
			try {
				$BlackList->Remove($_POST['userid']);
				AlertSuccess('Пользователь убран из списка');
			}
			catch(Exception $e) {
				AlertError($e->getMessage());
			}
		}
	}