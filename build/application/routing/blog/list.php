<?PHP
	if(empty($_SESSION['id'])) {
		Redirect('/account/auth');
	}
	
	tpl()
	->Start('sample')
	->Content(tpl()->Get('blog/list'))
	->Show();