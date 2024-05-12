<?PHP
	require('../start.php');
	
	IsValidActions();
	
	if(isset($_POST['ShowAccount'])) {
		$user = Users()->Get(
			$_POST['Index']
		);
		
		tpl()->SetCell([
			'account' => tpl()->Set([
				'{id}' => $_POST['Index'],
				'{profile_image}' => $user->image,
				'{steam_id}' => $user->steamid,
				'{username}' => GetUserName($_POST['Index']),
				'{cover}' => $user->cover
			], tpl()->Get('elements/modal/account'))
		]);
		
		Result([
			'content' => tpl()->Execute(
				tpl()->GetCell(
					'account'
				)
			)
		]);
	}
	
	if(empty($_SESSION['id'])) {
		AlertError(
			'Вы не авторизованы'
		);
	}
	
	if(isset($_POST['Deposit'])) {
		$_POST['amount'] = clean($_POST['amount'], 'int');
		
		if(empty($_POST['amount'])) {
			AlertError('Укажите сумму пополнения');
		}
		
		$user = Users()->Get($_SESSION['id']);
		
		$_Result = SendPost('https://oplata.awscode.ru/creation', [
			'price'				=> $_POST['amount'] * 1.00,
			'shop'				=> Merchants('onlinebank')->password1,
			'secret'			=>  Merchants('onlinebank')->password2,
			'comment'			=> "Пополнение профиля ID: {$user->id} на сайте {$_SERVER['SERVER_NAME']}",
			'attributes'		=> $_SESSION['id']
		]);
		
		Result(['uri' => json_decode($_Result)->uri]);
	}
	
	if(isset($_POST['ShowModalDeposit'])) {
		$user = Users()->Get(
			$_POST['Index']
		);
		
		tpl()->SetCell([
			'deposit' => tpl()->Set([
				'' => ''
			], tpl()->Get('elements/modal/deposit'))
		]);
		
		Result([
			'content' => tpl()->Execute(
				tpl()->GetCell(
					'deposit'
				)
			)
		]);
	}