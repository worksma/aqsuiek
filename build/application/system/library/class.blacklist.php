<?PHP
	class BlackList extends Users {
		public function Remove($userid) {
			try {
				$sth = pdo()->prepare('DELETE FROM `users__blacklist` WHERE `userid`=:userid AND `resid`=:resid LIMIT 1');
				$sth->execute([
					':userid' => $_SESSION['id'],
					':resid' => $userid
				]);
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class BlackList]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			throw new Exception(getLang('bl_err_message'));
		}
		
		public function Add($resid) {
			try {
				$sth = pdo()->prepare('INSERT INTO `users__blacklist`(`userid`, `resid`, `date`) VALUES (:userid, :resid, :date)');
				$sth->execute([
					':userid' => $_SESSION['id'],
					':resid' => $resid,
					':date' => date('Y-m-d H:i:s')
				]);
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class BlackList]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			throw new Exception(getLang('bl_err_message'));
		}
		
		public function Is($userid, $resid) {
			if(empty($userid) || empty($resid)) {
				return false;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `users__blacklist` WHERE `userid`=:userid AND `resid`=:resid LIMIT 1');
				$sth->execute([
					':userid' => $userid,
					':resid' => $resid
				]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class BlackList]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			throw new Exception(getLang('bl_err_message'));
		}
		
		public function List($userid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `users__blacklist` WHERE `userid`=:userid ORDER BY `id` DESC');
				$sth->execute([
					':userid' => $userid
				]);
				
				if(!$sth->rowCount()) {
					return '<center>Список пуст</center>';
				}
				
				while($List = $sth->fetch(PDO::FETCH_OBJ)) {
					tpl()->AddCell('ResultBlackList', tpl()->Set([
						'{image}' => GetUserAvatar($List->resid),
						'{name}' => GetUserName($List->resid, [
							'full' => true,
							'link' => true,
							'very' => true
						]),
						'{date}' => GetLastDay($List->date),
						'{userid}' => $List->resid
					], tpl()->Get('elements/settings/blacklist')));
				}
				
				return tpl()->GetCell('ResultBlackList');
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class BlackList]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
	}