<?PHP
	if(empty($_SESSION['id'])) {
		require($_SERVER['DOCUMENT_ROOT'] . '/application/routing/account/login.php');
		die;
	}
	
	Redirect('/flow');