<?PHP
	require(
		'../start.php'
	);
	
	IsValidActions();
	
	if(empty($_SESSION['id'])) {
		AlertError('Сначала пройдите этап авторизации');
	}
	
	if(isset($_POST['Create'])) {
		$Help = new Help;
		
		try {
			Result([
				'Alert' => 'Success',
				'LinkId' => $Help->Create([
					'title' => $_POST['title'],
					'description' => $_POST['description']
				])
			]);
		}
		catch(Exception $e) {
			AlertError($e->getMessage());
		}
	}
	
	if(isset($_POST['Send'])) {
		$Help = new Help;
		
		if(!$Help->IsUserValid($_POST['ticketid'])) {
			AlertError('Недостаточно прав');
		}
		
		try {
			$Help->Send($_POST['ticketid'], $_POST['message']);
			
			Result([
				'Alert' => 'Success',
				'Content' => $Help->GetLastMessage($_POST['ticketid'])
			]);
		}
		catch(Exception $e) {
			AlertError($e->getMessage());
		}
	}