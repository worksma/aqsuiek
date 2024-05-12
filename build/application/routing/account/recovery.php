<?PHP
	if(isset($_SESSION['id'])) {
		Redirect('/');
	}
	
	tpl()->Start('account');
	
	if(isset($_GET['hash'])) {
		if(!Users()->IsRecoveryHash($_GET['hash'])) {
			tpl()->Content(tpl()->Get('account/recovery_error'));
		}
		else {
			tpl()
			->Content(tpl()->Get('account/recovery_end'))
			->Set(['{hash}' => $_GET['hash']]);
		}
	}
	else {
		tpl()->Content(tpl()->Get('account/recovery'));
	}
	
	tpl()->Show();