<?PHP
	if(empty($_SESSION['id'])) {
		Redirect('/account/auth');
	}
	
	$Help = new Help;
	
	tpl()
	->Start('sample')
	->SetTitle($_PAGE['name'], true)
	->Content(tpl()->Get('help/index'))
	->Set([
		'{requests}' => $Help->GetList($_SESSION['id'])
	])
	->Show();