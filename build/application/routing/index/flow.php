<?PHP
	if(empty($_SESSION['id'])) {
		Redirect('/account/auth');
	}

	tpl()
	->Start('sample')
	->SetTitle($_PAGE['name'])
	->Content(tpl()->Get('index/flow'))
	->Show();