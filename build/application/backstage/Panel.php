<?PHP
	require(
		'../start.php'
	);
	
	IsValidActions();
	
	if(empty($_SESSION['id'])) {
		AlertError('Профиль не авторизован');
	}
	
	/*
		Права уровня Администратор
	*/
	if(!IsRights($_SESSION['id'], 1)) {
		AlertError('Недостаточно прав');
	}
	
	if(isset($_POST['edit_site_name'])) {
		try {
			$sth = pdo()->prepare('UPDATE `config` SET `site_name`=:site_name WHERE 1');
			$sth->execute([
				':site_name' => $_POST['site_name']
			]);
			
			AlertSuccess(
				'Данные были обновлены!'
			);
		}
		catch(Exception $e) {
			AlertError(
				$e->getMessage()
			);
		}
	}
	
	if(isset($_POST['edit_site_desc'])) {
		try {
			$sth = pdo()->prepare('UPDATE `config` SET `description`=:description WHERE 1');
			$sth->execute([
				':description' => $_POST['site_description']
			]);
			
			AlertSuccess(
				'Данные были обновлены!'
			);
		}
		catch(Exception $e) {
			AlertError(
				$e->getMessage()
			);
		}
	}
	
	if(isset($_POST['site_keywords'])) {
		try {
			$sth = pdo()->prepare('UPDATE `config` SET `keywords`=:keywords WHERE 1');
			$sth->execute([
				':keywords' => $_POST['site_keywords']
			]);
			
			AlertSuccess(
				'Данные были обновлены!'
			);
		}
		catch(Exception $e) {
			AlertError(
				$e->getMessage()
			);
		}
	}