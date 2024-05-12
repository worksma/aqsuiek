<?PHP
	require(
		'../start.php'
	);
	
	IsValidActions();
	
	if(empty($_SESSION['id'])) {
		AlertError('Сначала пройдите этап авторизации');
	}
	
	if(isset($_POST['Get'])) {
		try {
			Result([
				'Messages' => Chat()->GetMessages($_POST['roomid'], $_POST['lastid'] + 1),
				'Dialogs' => Chat()->GetRooms($_SESSION['id']),
				'LastId' => Chat()->GetLastMessageId($_POST['roomid'])
			]);
		}
		catch(Exception $e) {
			
		}
	}
	
	if(isset($_POST['GetDialogs'])) {
		try {
			Result(['Dialogs' => Chat()->GetRooms($_SESSION['id'])]);
		}
		catch(Exception $e) {
			
		}
	}
	
	if(isset($_POST['Send'])) {
		try {
			Result([
				'Alert' => 'Success',
				'LastId' => Chat()->Send($_POST['roomid'], $_POST['message'])
			]);
		}
		catch(Exception $e) {
			AlertError($e->getMessage());
		}
	}
	
	if(isset($_POST['StartDialog'])) {
		if(Chat()->IsCreateDialog($_SESSION['id'], $_POST['userid'])) {
			$Roomid = Chat()->GetRoomUsers($_SESSION['id'], $_POST['userid']);
			
			try {
				Chat()->Send($Roomid->id, $_POST['message']);
			}
			catch(Exception $e) {
				AlertError($e->getMessage());
			}
			
			Result(['Alert' => 'Success', 'Id' => $Roomid->id]);
		}
		else {
			try {
				$Roomid = Chat()->Create('Пользовательский диалог', $_SESSION['id'] . ':' . $_POST['userid'], $_POST['userid']);
				
				try {
					Chat()->Send($Roomid, $_POST['message']);
				}
				catch(Exception $e) {
					AlertError($e->getMessage());
				}
				
				Result(['Alert' => 'Success', 'Id' => $Roomid]);
			}
			catch(Exception $e) {
				AlertError($e->getMessage());
			}
		}
	}