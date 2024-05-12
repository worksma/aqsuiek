<?PHP
	require(
		'../start.php'
	);
	
	IsValidActions();
	
	if(empty($_SESSION['id'])) {
		AlertError('Сначала пройдите этап авторизации');
	}
	
	if(isset($_POST['Smiles'])) {
		$Emoji = new Emoji;
		Result(['List' => $Emoji->List()]);
	}