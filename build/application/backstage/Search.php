<?PHP
	require(
		'../start.php'
	);
	
	IsValidActions();
	
	if(empty($_SESSION['id'])) {
		AlertError('Сначала пройдите этап авторизации');
	}
	
	if(isset($_POST['Search'])) {
		$Params = json_decode($_POST['Params'], true);
		
		try {
			$Search = new Search($_POST['Type']);
			
			$Search->Params([
				'name' => isset($Params['Text']) ? $Params['Text'] : '',
				'city' => isset($Params['City']) ? $Params['City'] : ''
			]);
			
			Result(['Alert' => 'Success', 'Content' => $Search->Search()]);
		}
		catch(Exception $e) {
			AlertError($e->getMessage());
		}
	}