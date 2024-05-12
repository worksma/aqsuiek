<?PHP
	class Help {
		var $LenMin = [
			'title' => 4,
			'description' => 14
		], $LenMax = [
			'title' => 64,
			'description' => 1024
		];
		
		public function Get($ticketid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `help` WHERE `id`=:id LIMIT 1');
				$sth->execute([
					':id' => $ticketid
				]);
				
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Help]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function Create($_ARRAY = []) {
			if($this->LenMin['title'] > mb_strlen(trim($_ARRAY['title']))) {
				throw new Exception(getLang('help_msg_title_lenght_min'));
			}
			
			if($this->LenMax['title'] < mb_strlen(trim($_ARRAY['title']))) {
				throw new Exception(getLang('help_msg_title_lenght_max'));
			}
			
			if($this->LenMin['description'] > mb_strlen(trim($_ARRAY['description']))) {
				throw new Exception(getLang('help_msg_desc_lenght_min'));
			}
			
			try {
				$sth = pdo()->prepare('INSERT INTO `help`(`title`, `userid`, `date`) VALUES (:title, :userid, :date)');
				$sth->execute([
					':title' => htmlspecialchars(strip_tags(trim($_ARRAY['title']))),
					':userid' => $_SESSION['id'],
					':date' => date('Y-m-d H:i:s')
				]);
				
				$requestid = pdo()->lastInsertId();
				
				try {
					$this->Send($requestid, $_POST['description']);
				}
				catch(Exception $e) {
					throw new Exception($e->getMessage());
				}
				
				return $requestid;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Help]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
		
		public function IsValidTicket($ticketid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `help` WHERE `id`=:id LIMIT 1');
				$sth->execute([
					':id' => $ticketid
				]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Help]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function Send($ticketid, $message) {
			$message = trim(htmlspecialchars(strip_tags(trim($message))));
			
			if(2 > mb_strlen($message)) {
				throw new Exception(getLang('help_msg_lenght_min'));
			}
			
			if($this->LenMax['description'] < mb_strlen($message)) {
				throw new Exception(getLang('help_msg_lenght_max'));
			}
			
			try {
				$sth = pdo()->prepare('INSERT INTO `help__chat`(`ticketid`, `userid`, `message`, `date`) VALUES (:ticketid, :userid, :message, :date)');
				$sth->execute([
					':ticketid' => $ticketid,
					':userid' => $_SESSION['id'],
					':message' => $message,
					':date' => date('Y-m-d H:i:s')
				]);
				
				$Ticket = $this->Get($ticketid);
				$User = Users()->Get($_SESSION['id']);
				
				switch($User->rights) {
					case '1': {
						try {
							$this->SetStatus($ticketid, 1);
							
							Noty()->Send($Ticket->userid, [
								'link' => '/help/request/id' . $ticketid,
								'image' => 'support.svg',
								'content' => getLang('help_msg_event_request'),
								'type' => '2'
							]);
						}
						catch(Exception $e) {
							AddLogs('pdo.txt', "[Class Help]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
						}
						
						break;
					}
					
					default: {
						$this->SetStatus($ticketid, 2);
					}
				}
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Help]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			throw new Exception(getLang('help_exception'));
		}
		
		public function SetStatus($id, $status) {
			try {
				$sth = pdo()->prepare('UPDATE `help` SET `status`=:status WHERE `id`=:id LIMIT 1');
				$sth->execute([
					':id' => $id,
					':status' => $status
				]);
				
				return true;
			}
			catch(Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function GetMessages($ticketid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `help__chat` WHERE `ticketid`=:ticketid ORDER BY `id` ASC');
				$sth->execute([
					':ticketid' => $ticketid
				]);
				
				$tpl = new Template;
				
				if(!$sth->rowCount()) {
					return $tpl->Execute($tpl->Set(['{id}' => ''], $tpl->Get('elements/help/message')));
				}
				
				while($Message = $sth->fetch(PDO::FETCH_OBJ)) {
					$tpl->AddCell('Messages', $tpl->Set([
						'{id}' => $Message->id,
						'{message}' => $Message->message,
						'{date}' => date('d.m.Y в H:i', strtotime($Message->date)),
						'{support}' => $this->IsUserSupport($Message->userid) ? '1' : '',
						'{profile_id}' => $Message->userid,
						'{profile_image}' => $this->IsUserSupport($Message->userid) ? '/public/images/system/support/s1.jpg' : GetUserAvatar($Message->userid),
						'{profile_name}' => $this->IsUserSupport($Message->userid) ? ('<span style="color: rgb(239, 59, 59);">' . getLang('help_agent', [($Message->userid * 209)]) . '</span>') : GetUserName($Message->userid, ['full' => true, 'link' => true]),
					], $tpl->Get('elements/help/message')));
				}
				
				return $tpl->Execute($tpl->GetCell('Messages'));
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Help]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
		
		public function IsUserSupport($userid) {
			$User = Users()->Get($userid);
			
			switch($User->rights) {
				case 1: return true;
				default: return false;
			}
		}
		
		public function GetLastMessage($ticketid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `help__chat` WHERE `ticketid`=:ticketid ORDER BY `id` DESC LIMIT 1');
				$sth->execute([
					':ticketid' => $ticketid
				]);
				
				$tpl = new Template;
				
				if(!$sth->rowCount()) {
					return $tpl->Execute($tpl->Set(['{id}' => ''], $tpl->Get('elements/help/message')));
				}
				
				while($Message = $sth->fetch(PDO::FETCH_OBJ)) {
					$tpl->AddCell('Messages', $tpl->Set([
						'{id}' => $Message->id,
						'{message}' => $Message->message,
						'{date}' => date('d.m.Y в H:i', strtotime($Message->date)),
						'{support}' => $this->IsUserSupport($Message->userid) ? '1' : '',
						'{profile_id}' => $Message->userid,
						'{profile_image}' => $this->IsUserSupport($Message->userid) ? '/public/images/system/support/s1.jpg' : GetUserAvatar($Message->userid),
						'{profile_name}' => $this->IsUserSupport($Message->userid) ? ('<span style="color: rgb(239, 59, 59);">' . getLang('help_agent', [($Message->userid * 209)]) . '</span>') : GetUserName($Message->userid, ['full' => true, 'link' => true]),
					], $tpl->Get('elements/help/message')));
				}
				
				return $tpl->Execute($tpl->GetCell('Messages'));
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Help]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
		
		public function GetList($userid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `help` WHERE `userid`=:userid ORDER BY `id` DESC');
				$sth->execute([
					':userid' => $userid
				]);
				
				if(!$sth->rowCount()) {
					return '<center>Запросов нет</center>';
				}
				
				while($Request = $sth->fetch(PDO::FETCH_OBJ)) {
					tpl()->AddCell('Requests', tpl()->Set([
						'{id}' => $Request->id,
						'{status}' => $this->Status($Request->status),
						'{title}' => htmlspecialchars_decode($Request->title),
						'{message}' => ($Request->status == 1) ? getLang('help_request_yes') : getLang('help_request_wait')
					], tpl()->Get('elements/help/request')));
				}
				
				return tpl()->GetCell('Requests');
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Help]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
		
		public function Status($id) {
			switch($id) {
				case 1: {
					$image = '/public/assets/' . tpl()->GetAppearance() . '/img/icons/complete.png';
					break;
				}
				
				case 2: {
					$image = '/public/assets/' . tpl()->GetAppearance() . '/img/icons/waiting.png';
					break;
				}
				
				default: {
					$image = '';
				}
			}
			
			return $image;
		}
		
		public function StatusText($id) {
			switch($id) {
				case 1: {
					$result = '<span style="border-radius: 15px; border: 2px solid #4caf50; width: max-content; padding: 2px 8px 2px 8px;font-size:12px;">' . getLang('help_request_yes') . '</span>';
					break;
				}
				
				case 2: {
					$result = '<span style="border-radius: 15px; border: 2px solid #FFEB3B; width: max-content; padding: 2px 8px 2px 8px;font-size:12px;">' . getLang('help_request_no') . '</span>';
					break;
				}
				
				default: {
					$result = '';
				}
			}
			
			return $result;
		}
		
		public function IsUserValid($ticketid) {
			if(!$this->IsValidTicket($ticketid)) {
				return false;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `help` WHERE `id`=:id LIMIT 1');
				$sth->execute([
					':id' => $ticketid
				]);
				
				if(!$sth->rowCount()) {
					return false;
				}
				
				$Ticket = $sth->fetch(PDO::FETCH_OBJ);
				
				if($Ticket->userid == $_SESSION['id']) {
					return true;
				}
				
				if($this->IsUserSupport($_SESSION['id'])) {
					return true;
				}
				
				return false;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Help]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
	}