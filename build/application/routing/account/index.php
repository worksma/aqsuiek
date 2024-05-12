<?PHP
	if(isset($_PAGE['params'][1])) {
		if(!Users()->IsValid($_PAGE['params'][1])) {
			ShowPage('ProfileInfo', tpl()->Get('elements/account/no_profile'), 'Информация', 'sample');
		}
	}
	
	global $TriggerAccount;
	$TriggerAccount = Users()->Get($_PAGE['params'][1]);
	
	if($TriggerAccount->rights == 3) {
		ShowPage('ProfileInfo', tpl()->Get('elements/account/freezy'), 'Профиль заблокирован', 'sample');
	}
	
	if(class_exists('BlackList')) {
		$BL = new BlackList;
	}
	
	$profileName = strip_tags(
		htmlspecialchars_decode(
			GetUserName($TriggerAccount->id, ['full' => true])
		)
	);
	
	tpl()
	->Start('sample')
	->AddReplace([
		'{meta_name}' => 'Профиль пользователя ' . $profileName,
		'{meta_description}' => 'Профиль пользователя ' . $profileName . ' на сайте ' . conf()->site_name,
		'{meta_keywords}' => $profileName . ', искать, профиль, профиль пользователя',
		'{meta_image}' => GetUserAvatar($TriggerAccount->id)
	])
	->SetTitle($TriggerAccount->first_name . ' ' . $TriggerAccount->last_name)
	->Content(tpl()->Get('account/index'))
	->Set([
		'{userid}' => $TriggerAccount->id,
		'{is_blacklist}' => isset($BL) ? ($BL->Is($TriggerAccount->id, $_SESSION['id'])) : NULL
	])
	->Show();