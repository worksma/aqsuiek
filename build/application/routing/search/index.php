<?PHP
	if(empty($_SESSION['id'])) {
		Redirect('/account/auth');
	}
	
	if(isset($_GET['type'])) {
		switch($_GET['type']) {
			default: {
				$Search = new Search('people');
			}
		}
	}
	else {
		$Search = new Search('people');
	}
	
	if(isset($_GET['city'])) {
		$City = GetCityIndex($_GET['city']);
		
		if(isset($City)) {
			$Search->Params(['name' => '', 'city' => $City->id]);
		}
	}
	
	tpl()
	->Start('sample')
	->Content(tpl()->Get('search/index'))
	->AddScripts('/public/system/search.js')
	->Set([
		'{result}' => $Search->Search(),
		'{SearchNavs}' => tpl()->Set([
			'{countryid}' => isset($City->countryid) ? $City->countryid : 1,
			'{cityid}' => isset($City->id) ? $City->id : NULL
		], tpl()->Get('elements/navigation/search'))
	])
	->Show();