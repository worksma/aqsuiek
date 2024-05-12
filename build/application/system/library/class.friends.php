<?PHP
	class Friends {
		public static function IsSub($recipientid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `users__sub` WHERE (`recipientid`=:recipientid AND `senderid`=:senderid) AND (`senderid`!=:recipientid AND `recipientid`!=:senderid) LIMIT 1');
				
				$sth->execute([
					':senderid'			=> $_SESSION['id'],
					':recipientid'		=> $recipientid
				]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Friends]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public static function IsFriends($recipientid) {
			try {
				$sth = pdo()->prepare('SELECT COUNT(*) AS `friends` FROM `users__sub` WHERE `recipientid`=:senderid AND `senderid`=:recipientid UNION ALL SELECT COUNT(*) AS `friends` FROM `users__sub` WHERE `recipientid`=:recipientid AND `senderid`=:senderid');
				
				$sth->execute([
					':senderid'			=> $_SESSION['id'],
					':recipientid'		=> $recipientid
				]);
				
				$Result = $sth->fetchAll();
				
				if(class_exists('BlackList')) {
					$BL = new BlackList;
					
					if($BL->Is($recipientid, $_SESSION['id']) OR $BL->Is($_SESSION['id'], $recipientid)) {
						return false;
					}
				}
				
				if($Result[0]['friends'] == 0 OR $Result[1]['friends'] == 0) {
					return false;
				}
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Friends]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public static function GetButton($userid) {
			if($userid == $_SESSION['id']) {
				return '<button class="btn btn-secondary" href="/account/settings">' . getLang('friends_edit_profile') . '</button>';
			}
			
			if(class_exists('BlackList')) {
				$BL = new BlackList;
				
				if($BL->Is($userid, $_SESSION['id']) || $BL->Is($_SESSION['id'], $userid)) {
					return '';
				}
			}
			
			if(self::IsSub($userid)) {
				return '<button class="btn btn-outline-danger" data-target="UnSubscribe" data-toggle="' . $userid . '">' . getLang('friends_unsubscribe') . '</button>';
			}
			else if(self::IsFriends($userid)) {
				return '<button class="btn btn-outline-danger" data-target="UnSubscribe" data-toggle="' . $userid . '">' . getLang('friends_unsubscribe') . '</button>';
			}
			
			return '<button class="btn btn-primary" data-target="Subscribe" data-toggle="' . $userid . '">' . getLang('friends_subscribe') . '</button>';
		}
		
		public function Subscribe($userid) {
			try {
				$sth = pdo()->prepare('INSERT INTO `users__sub`(`recipientid`, `senderid`, `date`) VALUES (:recipientid, :senderid, :date)');
				$sth->execute([':recipientid' => $userid, ':senderid' => $_SESSION['id'], ':date' => date('Y-m-d H:i:s')]);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Friends]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return $this->GetButton($userid);
		}
		
		public function UnSubscribe($userid) {
			try {
				$sth = pdo()->prepare('DELETE FROM `users__sub` WHERE `senderid`=:senderid AND `recipientid`=:recipientid LIMIT 1');
				$sth->execute([':recipientid' => $userid, ':senderid' => $_SESSION['id']]);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Friends]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return $this->GetButton($userid);
		}
		
		public function ListRequests($userid) {
			try {
				$sth = pdo()->prepare('SELECT DISTINCT * FROM (SELECT CASE WHEN `recipientid`=:userid THEN `senderid` ELSE `recipientid` END AS `requested`, :userid AS `userid` FROM `users__sub` WHERE `recipientid` = :userid OR `senderid` = :userid) AS subquery ORDER BY `requested` DESC;');
				
				$sth->execute([
					':userid' => $userid
				]);
				
				if(class_exists('BlackList')) {
					$BL = new BlackList;
				}
				
				while($Sub = $sth->fetch(PDO::FETCH_OBJ)) {
					if(isset($BL)) {
						if($BL->Is($Sub->requested, $userid) || $BL->Is($userid, $Sub->requested)) {
							continue;
						}
					}
					
					if($this->IsFriends($Sub->requested) OR $this->IsSub($Sub->requested)) {
						continue;
					}
					
					$User = Users()->Get($Sub->requested);
					
					tpl()->AddCell('Lists', tpl()->Set([
						'{id}' => $User->id,
						'{buttons}' => $this->GetButton($User->id),
						'{image}' => GetUserAvatar($User->id),
						'{name}' => GetUserName($User->id, [
							'link' => true,
							'full' => true,
							'very' => true
						])
					], tpl()->Get('elements/subs/requests')));
				}
				
				if(!tpl()->GetCell('Lists')) {
					return '<li class="text-center d-block">' . getLang('friends_no_app') . '</li>';
				}
				
				return tpl()->GetCell('Lists');
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Friends]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
		
		public function Lists($userid) {
			try {
				$sth = pdo()->prepare('SELECT DISTINCT * FROM (SELECT CASE WHEN `recipientid`=:userid THEN `senderid` ELSE `recipientid` END AS `requested`, :userid AS `userid` FROM `users__sub` WHERE `recipientid` = :userid OR `senderid` = :userid) AS subquery ORDER BY `requested` DESC;');
				
				$sth->execute([
					':userid' => $userid
				]);
				
				if(!$sth->rowCount()) {
					return '<li style="flex-direction: column; text-align: center; gap: 10px; color: #838383;">'. getLang('friends_msg_search') .'</li>';
				}
				
				while($Sub = $sth->fetch(PDO::FETCH_OBJ)) {
					if(!$this->IsFriends($Sub->requested)) {
						continue;
					}
					
					$User = Users()->Get($Sub->requested);
					
					tpl()->AddCell('Lists', tpl()->Set([
						'{id}' => $User->id,
						'{buttons}' => $this->GetButton($User->id),
						'{image}' => GetUserAvatar($User->id),
						'{name}' => GetUserName($User->id, [
							'link' => true,
							'full' => true,
							'very' => true
						])
					], tpl()->Get('elements/subs/friends')));
				}
				
				if(!tpl()->GetCell('Lists')) {
					return '<li class="text-center d-block">' . getLang('friends_msg_list_clear') . '</li>';
				}
				
				return tpl()->GetCell('Lists');
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Friends]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
		
		public function RowRequest($userid) {
			try {
				$sth = pdo()->prepare('SELECT DISTINCT * FROM (SELECT CASE WHEN `recipientid`=:userid THEN `senderid` ELSE `recipientid` END AS `requested`, :userid AS `userid` FROM `users__sub` WHERE `recipientid` = :userid OR `senderid` = :userid) AS subquery ORDER BY `requested` DESC;');
				
				$sth->execute([
					':userid' => $userid
				]);
				
				$Count = 0;
				while($Sub = $sth->fetch(PDO::FETCH_OBJ)) {
					if($this->IsFriends($Sub->requested) OR $this->IsSub($Sub->requested)) {
						continue;
					}
					
					$Count++;
				}
				
				return $Count;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Friends]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
	}