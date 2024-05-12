<?PHP
	if(empty($_SESSION['id'])) {
		Redirect('/account/auth');
	}

	tpl()
	->SetAppearance('panel')
	->Start('sample')
	->Content(tpl()->Get('index/index'))
	->Set([
		'{site_name}' => conf()->site_name,
		'{site_description}' => conf()->description,
		'{site_keywords}' => conf()->keywords
	])
	->Show();