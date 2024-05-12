<?PHP
	if(empty($_SESSION['id'])) {
		Redirect('/account/auth');
	}
	
	if(isset($_GET['sel'])) {
		if(Chat()->IsValid($_GET['sel'])) {
			$roomid = $_GET['sel'];
			
			$Room = Chat()->Get($roomid);
			$TriggerUser = Users()->Get(($Room->userid == $_SESSION['id']) ? $Room->createid : $Room->userid);
		}
		else {
			$roomid = null;
		}
	}
	else {
		$roomid = null;
	}
	
	tpl()
	->Start('sample')
	->SetTitle($_PAGE['name'])
	->Content(tpl()->Get(isset($roomid) ? 'messages/dialog' : 'messages/index'))
	->Set([
		'{roomid}' => $roomid,
		'{dialogs}' => Chat()->GetRooms($_SESSION['id'])
	]);
	
	if(isset($roomid)) {
		tpl()->Set([
			'{name}' => GetUserName($TriggerUser->id, ['very' => true, 'full' => true, 'link' => true]),
			'{online}' => (IsUserOnline($TriggerUser->id) ? 'В сети' : 'Был(-а) ' . mb_strtolower(DayToTime($TriggerUser->last_online))),
			'{ProfileImage}' => GetUserAvatar($TriggerUser->id),
			'{ProfileId}' => $TriggerUser->id
		]);
	}

	tpl()->Show();