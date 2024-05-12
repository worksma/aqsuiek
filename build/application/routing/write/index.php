<?PHP
	$userid		= $_PAGE['params'][1];
	
	if(!Users()->IsValid($userid)) {
		ShowPage('ProfileInfo', tpl()->Get('elements/writing/no_write'), 'Информация', 'sample');
	}
	
	$Writings	= new Attachments;
	$writeid	= $_PAGE['params'][2];
	
	if(!$Writings->IsValid($writeid)) {
		ShowPage('ProfileInfo', tpl()->Get('elements/writing/no_write'), 'Информация', 'sample');
	}
	
	if($Writings->IsRemove($writeid)) {
		ShowPage('ProfileInfo', tpl()->Get('elements/writing/remove'), 'Запись удалена', 'sample');
	}
	
	$DataWritings		= $Writings->Get($writeid);
	$UserData			= Users()->Get($userid);
	
	if(!$Writings->IsView($writeid)) {
		$Writings->AddViews($writeid);
	}
	
	tpl()
	->Start('sample')
	->SetTitle(strip_tags(Reduction(htmlspecialchars_decode(htmlspecialchars_decode($DataWritings->content)), 50)) . ' | ' . $UserData->first_name . ' ' . $UserData->last_name)
	->Content(tpl()->Get('index/write'))
	->AddReplace([
		'{meta_description}' => 'Читайте еще больше на ' . conf()->site_name
	])
	->Set([
		'{id}'				=> $DataWritings->id,
		'{author_id}'		=> $userid,
		'{author_name}'		=> GetUserName($UserData->id, ['full' => true, 'very' => true, 'link' => true]),
		'{author_image}'	=> GetUserAvatar($UserData->id),
		'{post_date}'		=> DayToTime($DataWritings->date),
		'{content}'			=> htmlspecialchars_decode(htmlspecialchars_decode($DataWritings->content)),
		'{comments}'		=> $Writings->GetComments($writeid),
		'{likeRows}'		=> $Writings->RowLikes($writeid),
		'{attachment}'		=> $Writings->Visual($writeid),
		'{views}'			=> $Writings->GetViews($writeid)
	])
	->Show();