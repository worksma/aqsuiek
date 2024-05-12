<?PHP
	if(empty($_SESSION['id'])) {
		Redirect('/account/auth');
	}
	
	tpl()->Start('sample');
	
	if(isset($_GET['act'])) {
		switch($_GET['act']) {
			case 'blacklist': {
				if(class_exists('BlackList')) {
					$BlackList = new BlackList;
					
					tpl()->Content(tpl()->Get('settings/blacklist'))
					->Set([
						'{Content}' => $BlackList->List($_SESSION['id'])
					]);
				}
				
				break;
			}
			
			default: {
				tpl()->Content(
					tpl()->Get('settings/index')
				);
			}
		}
	}
	else {
		tpl()->Content(
			tpl()->Get('settings/index')
		);
	}
	
	tpl()->Show();