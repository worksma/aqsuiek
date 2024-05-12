<?PHP
	if(isset($_SESSION['id'])) {
		Redirect('/');
	}

	tpl()
	->Start('account')
	->Content(tpl()->Get('account/login'))
	->Show();