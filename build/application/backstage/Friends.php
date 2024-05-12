<?PHP
	require(
		'../start.php'
	);
	
	IsValidActions();
	
	if(empty($_SESSION['id'])) {
		AlertError('Сначала пройдите этап авторизации');
	}
	
	if(isset($_POST['Subscribe'])) {
		$Friends = new Friends;
		
		if($Friends->IsSub($_POST['userid'])) {
			Result(['Button' => $Friends->GetButton($_POST['userid'])]);
		}
		else if($Friends->IsFriends($_POST['userid'])) {
			Result(['Button' => $Friends->GetButton($_POST['userid'])]);
		}
		
		Result(['Button' => $Friends->Subscribe($_POST['userid'])]);
	}
	
	if(isset($_POST['UnSubscribe'])) {
		$Friends = new Friends;
		
		if($Friends->IsSub($_POST['userid'])) {
			Result(['Button' => $Friends->UnSubscribe($_POST['userid'])]);
		}
		else if($Friends->IsFriends($_POST['userid'])) {
			Result(['Button' => $Friends->UnSubscribe($_POST['userid'])]);
		}
		
		Result(['Button' => $Friends->GetButton($_POST['userid'])]);
	}