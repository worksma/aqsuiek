<?PHP
	if(empty($_SESSION['id'])) {
		Redirect('/account/auth');
	}
	
	$Friends = new Friends;
	
	if(isset($_GET['section'])) {
		switch($_GET['section']) {
			case 'requests': {
				$Data = $Friends->ListRequests($_SESSION['id']);
				
				break;
			}
			
			default: {
				$Data = $Friends->Lists($_SESSION['id']);
			}
		}
	}
	else {
		$Data = $Friends->Lists($_SESSION['id']);
	}
	
	tpl()
	->Start('sample')
	->Content(tpl()->Get('subscriptions/index'))
	->Set([
		'{Lists}' => $Data
	])
	->Show();