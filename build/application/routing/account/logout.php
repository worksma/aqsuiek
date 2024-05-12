<?PHP
	if(empty($_SESSION['id'])) {
		Redirect('/account/auth');
	}

	Users()->Logout();
	Redirect('/account/auth');