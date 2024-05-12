<?PHP
	class Chat {
		protected $Limits = [
			'name' => 128, 'desc' => 512
		];
		
		public function Create($name = null, $desc = null, $userid = null) {
			if(empty($_SESSION['id'])) {
				throw new Exception(getLang('chat_msg_auth'));
			}
			
			$name = htmlspecialchars(strip_tags($name));
			
			if(empty($name) || mb_strlen($name, 'UTF-8') < 3) {
				throw new Exception(getLang('chat_msg_lenght'));
			}
			
			if(mb_strlen($name, 'UTF-8') > $this->Limits['name']) {
				throw new Exception(getLang('chat_msg_lenght_char'));
			}
			
			$desc = htmlspecialchars(strip_tags($desc));
			
			if(empty($desc) || mb_strlen($desc, 'UTF-8') < 3) {
				throw new Exception(getLang('chat_msg_desc'));
			}
			
			if(mb_strlen($desc, 'UTF-8') > $this->Limits['desc']) {
				throw new Exception(getLang('chat_msg_desc_len'));
			}
			
			try {
				$sth = pdo()->prepare('INSERT INTO `chat__rooms`(`createid`, `userid`, `name`, `description`, `participants`, `date`, `last_activity`) VALUES (:createid, :userid, :name, :description, :participants, :date, :last_activity)');
				
				if(isset($userid)) {
					$participants = json_encode([
						$_SESSION['id'] => [
							'access' => '1',
							'date' => time()
						],
						$userid => [
							'access' => '1',
							'date' => time()
						]
					]);
				}
				else {
					$participants = json_encode([
						$_SESSION['id'] => [
							'access' => '1',
							'date' => time()
						]
					]);
				}
				
				$sth->execute([
					':createid' => $_SESSION['id'],
					':userid' => $userid,
					':name' => $name,
					':description' => htmlspecialchars(strip_tags($desc)),
					':participants' => $participants,
					':date' => time(),
					':last_activity' => time()
				]);
				
				return pdo()->lastInsertId();
			}
			catch(Exception $e) {
				throw new Exception(getLang('chat_msg_err_pdo', [$e->getMessage()]));
			}
		}
		
		public function Send($roomid, $message) {
			if(!$this->IsValid($roomid)) {
				throw new Exception(getLang('chat_msg_no_room'));
			}
			
			if(empty($_SESSION['id'])) {
				throw new Exception(getLang('chat_msg_auth'));
			}
			
			if($this->IsRoomJoined($_SESSION['id'], $roomid) != true) {
				throw new Exception(getLang('chat_msg_no_access'));
			}
			
			if(empty($message)) {
				throw new Exception(getLang('chat_msg_no_send'));
			}
			
			$message = htmlspecialchars(
				strip_tags(
					$message
				)
			);
			
			if(mb_strlen($message, 'UTF-8') < 1) {
				throw new Exception(getLang('chat_msg_write'));
			}
			
			try {
				$sth = pdo()->prepare('INSERT INTO `chat__messages`(`userid`, `roomid`, `message`, `ready`, `date`) VALUES (:userid, :roomid, :message, :ready, :date)');
				$sth->execute([
					':userid' => $_SESSION['id'],
					':roomid' => $roomid,
					':message' => $message,
					':ready' => json_encode([
						$_SESSION['id'] => [
							'date' => time()
						]
					]),
					':date' => time()
				]);
				
				$this->SetActivity($roomid);
				
				return pdo()->lastInsertId();
			}
			catch(Exception $e) {
				throw new Exception(getLang('chat_msg_err_pdo', [$e->getMessage()]));
			}
		}
		
		public function IsRoomJoined($userid, $roomid) {
			try {
				$Room = $this->Get($roomid);
				
				if(empty($Room)) {
					return false;
				}
				
				$Participants = json_decode($Room->participants, true);
				
				foreach($Participants as $id => $data) {
					if($id == $userid) {
						return true;
					}
				}
				
				return false;
			}
			catch(Exception $e) {
				throw new Exception(getLang('chat_msg_err_pdo', [$e->getMessage()]));
			}
		}
		
		public function Get($roomid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `chat__rooms` WHERE `id`=:id LIMIT 1');
				$sth->execute([
					':id' => $roomid
				]);
				
				if(!$sth->rowCount()) {
					return null;
				}
				
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(Exception $e) {
				return null;
			}
		}
		
		public function IsValid($id) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `chat__rooms` WHERE `id`=:id LIMIT 1');
				$sth->execute([
					':id' => $id
				]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				return false;
			}
		}
		
		public function GetMessages($roomid, $start = 1) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `chat__messages` WHERE `roomid`=:roomid AND `id` BETWEEN :start AND (SELECT MAX(`id`) FROM `chat__messages` WHERE `roomid`=:roomid) ORDER BY `id` ASC');
				$sth->execute([':roomid' => $roomid, ':start' => $start]);
				
				if(!$sth->rowCount()) {
					return getLang('chat_msg_no_writes');
				}
				
				while($Message = $sth->fetch(PDO::FETCH_OBJ)) {
					tpl()->AddCell('Messages', tpl()->Set([
						'{name}' => GetUserName($Message->userid, [
							'link' => true, 'very' => true
						]),
						'{image}' => GetUserAvatar($Message->userid),
						'{userid}' => $Message->userid,
						'{message}' => strip_tags(htmlspecialchars_decode($Message->message))
					], tpl()->Get('elements/messages/message')));
				}
				
				if(isset($_SESSION['id'])) {
					$this->SetReady($roomid, $_SESSION['id']);
				}
				
				return tpl()->Execute(tpl()->GetCell('Messages'));
			}
			catch(Exception $e) {
				return $e->getMessage();
			}
		}
		
		public function GetLastMessageId($roomid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `chat__messages` WHERE `roomid`=:roomid ORDER BY `id` DESC LIMIT 1');
				$sth->execute([
					':roomid' => $roomid
				]);
				
				if($sth->rowCount()) {
					return $sth->fetch(PDO::FETCH_OBJ)->id;
				}
				
				return 1;
			}
			catch(Exception $e) {
				return 1;
			}
		}
		
		public function GetRooms($userid) {
			try {
				$sth = pdo()->query('SELECT * FROM `chat__rooms` ORDER BY `last_activity` DESC');
				
				if(!$sth->rowCount()) {
					return '<div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">' . getLang('chat_msg_no_dialogs') . '</div>';
				}
				
				while($Room = $sth->fetch(PDO::FETCH_OBJ)) {
					$Participants = json_decode($Room->participants, true);
					
					if(isset($Participants[$userid])) {
						$LastMessage = $this->GetLastMessage($Room->id);
						
						if(isset($LastMessage)) {
							if($LastMessage->userid == $userid) {
								$LastMessage = 'Вы: ' . $LastMessage->message;
							}
							else {
								$LastMessage = Users()->Get($LastMessage->userid)->first_name . ': ' . $LastMessage->message;
							}
						}
						else {
							$LastMessage = '';
						}
						
						if(isset($Room->userid)) {
							if($Room->userid == $userid) {
								tpl()->AddCell('Dialogs', tpl()->Set([
									'{name}' => GetUserName($Room->createid),
									'{image}' => GetUserAvatar($Room->createid),
									'{roomid}' => $Room->id,
									'{date}' => $this->DayToTime($Room->last_activity),
									'{message}' => $LastMessage,
									'{noty}' => $this->GetNotReady($Room->id, $userid)
								], tpl()->Get('elements/messages/dialog')));
							}
							else {
								tpl()->AddCell('Dialogs', tpl()->Set([
									'{name}' => GetUserName($Room->userid),
									'{image}' => GetUserAvatar($Room->userid),
									'{roomid}' => $Room->id,
									'{date}' => $this->DayToTime($Room->last_activity),
									'{message}' => $LastMessage,
									'{noty}' => $this->GetNotReady($Room->id, $userid)
								], tpl()->Get('elements/messages/dialog')));
							}
							
							continue;
						}
						
						tpl()->AddCell('Dialogs', tpl()->Set([
							'{name}' => $Room->name,
							'{image}' => 'no_image.jpg',
							'{roomid}' => $Room->id,
							'{date}' => $this->DayToTime($Room->last_activity),
							'{message}' => $LastMessage,
							'{noty}' => $this->GetNotReady($Room->id, $userid)
						], tpl()->Get('elements/messages/dialog')));
					}
				}
				
				return tpl()->IsCell('Dialogs') ? tpl()->Execute(tpl()->GetCell('Dialogs')) : '<div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">' . getLang('chat_msg_no_dialogs') . '</div>';
			}
			catch(Exception $e){
				throw new Exception(getLang('chat_msg_err_pdo', [$e->getMessage()]));
			}
		}
		
		public function GetLastMessage($roomid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `chat__messages` WHERE `roomid`=:roomid ORDER BY `id` DESC LIMIT 1');
				$sth->execute([
					':roomid' => $roomid
				]);
				
				if(!$sth->rowCount()) {
					return null;
				}
				
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(Exception $e){
				throw new Exception(getLang('chat_msg_err_pdo', [$e->getMessage()]));
			}
		}
		
		public function IsCreateDialog($createid, $userid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `chat__rooms` WHERE (`createid`=:createid AND `userid`=:userid) OR (`createid`=:userid AND `userid`=:createid) LIMIT 1');
				$sth->execute([
					':createid' => $createid,
					':userid' => $userid
				]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				throw new Exception(getLang('chat_msg_err_pdo', [$e->getMessage()]));
			}
		}
		
		public function GetRoomUsers($createid, $userid) {
			if($this->IsCreateDialog($createid, $userid)) {
				$sth = pdo()->prepare('SELECT * FROM `chat__rooms` WHERE (`createid`=:createid AND `userid`=:userid) OR (`createid`=:userid AND `userid`=:createid) LIMIT 1');
				$sth->execute([
					':createid' => $createid,
					':userid' => $userid
				]);
				
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			
			return null;
		}
		
		public function DayToTime($_Date) {
			switch(round((time() - $_Date) / 86400)) {
				case 0: {
					return date('H:i', $_Date);
				}
				case 1: {
					return 'Вчера';
				}
				default: {
					return date('n ', $_Date) . mb_strimwidth(GetMonth(date('Y-m-d H:i:s', $_Date)), 0, 3);
				}
			}
		}
		
		public function SetActivity($roomid) {
			try {
				$sth = pdo()->prepare('UPDATE `chat__rooms` SET `last_activity`=:last_activity WHERE `id`=:id LIMIT 1');
				$sth->execute([
					':id' => $roomid,
					':last_activity' => time()
				]);
				
				return true;
			}
			catch(Exception $e) {
				return false;
			}
		}
		
		public function GetNotReady($roomid, $userid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `chat__messages` WHERE `roomid`=:roomid');
				$sth->execute([
					':roomid' => $roomid
				]);
				
				if(!$sth->rowCount()) {
					return '';
				}
				
				$NotReady = 0;
				while($Message = $sth->fetch(PDO::FETCH_OBJ)) {
					$Ready = json_decode($Message->ready, true);
					
					if(empty($Ready[$userid])) {
						$NotReady++;
					}
				}
				
				return ($NotReady != 0) ? '<span class="badge">' . $NotReady . '</span>' : '';
			}
			catch(Exception $e) {
				throw new Exception(getLang('chat_msg_err_pdo', [$e->getMessage()]));
			}
		}
		
		public function SetReady($roomid, $userid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `chat__messages` WHERE `roomid`=:roomid');
				$sth->execute([
					':roomid' => $roomid
				]);
				
				if(!$sth->rowCount()) {
					return '';
				}
				
				while($Message = $sth->fetch(PDO::FETCH_OBJ)) {
					$Ready = json_decode($Message->ready, true);
					
					if(empty($Ready[$userid])) {
						$Ready += [$userid => ['date' => time()]];
						
						try {
							$ath = pdo()->prepare('UPDATE `chat__messages` SET `ready`=:ready WHERE `id`=:id');
							$ath->execute([':ready' => json_encode($Ready), ':id' => $Message->id]);
						}
						catch(Exception $e) {
							
						}
					}
				}
				
				return true;
			}
			catch(Exception $e) {
				throw new Exception(getLang('chat_msg_err_pdo', [$e->getMessage()]));
			}
		}
	}