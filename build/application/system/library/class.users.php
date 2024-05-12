<?PHP
	class Users {
		public function Rows() {
			$sth = pdo()->query(
				'SELECT COUNT(*) AS COUNT FROM `users`'
			);
			
			return $sth->fetch(
				PDO::FETCH_OBJ
			)->COUNT;
		}
		
		public function Register($_ARRAY = []) {
			try {
				if(conf()->beta) {
					if(!$this->IsBetaKey($_ARRAY['beta'])) {
						return null;
					}
				}
				
				$sth = pdo()->prepare(
					'INSERT INTO `users`(`email`, `password`, `first_name`, `last_name`, `nickname`, `sex`, `rights`, `very`, `balance`, `birthday`, `register`, `hash`) VALUES (:email, :password, :first_name, :last_name, :nickname, :sex, :rights, :very, :balance, :birthday, :register, :hash)'
				);
				
				$Hash = md5(RandomString(12));
				
				$sth->execute([
					':email' => $_ARRAY['email'],
					':password' => password_hash($_ARRAY['password'], PASSWORD_DEFAULT),
					':first_name' => $_ARRAY['first_name'],
					':last_name' => $_ARRAY['last_name'],
					':nickname' => $_ARRAY['nickname'],
					':sex' => $_ARRAY['sex'],
					':rights' => $this->Rows() ? 2 : 1,
					':very' => $this->Rows() ? 0 : 1,
					':balance' => $_ARRAY['balance'],
					':birthday' => $_ARRAY['birthday'],
					':register' => date('Y-m-d H:i:s'),
					'hash' => $Hash
				]);
				
				$userid = pdo()->lastInsertId();
				
				if(conf()->beta) {
					$this->AddBeta($userid, $_ARRAY['beta']);
				}
				
				return $userid;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public function Auth($_ARRAY = []) {
			$sth = pdo()->prepare(
				'SELECT * FROM `users` WHERE `email`=:email LIMIT 1'
			);
			
			$sth->execute([
				':email' => $_ARRAY['email']
			]);
			
			if(!$sth->rowCount()) {
				return false;
			}
			
			$user = $sth->fetch(
				PDO::FETCH_OBJ
			);
			
			if(password_verify($_ARRAY['password'], $user->password)) {
				$_SESSION['id'] = $user->id;
				
				Noty()->Send($_SESSION['id'], [
					'link' => '/account/settings?act=security',
					'image' => 'cyber.svg',
					'content' => getLang('users_noty_auth'),
					'type' => '1'
				]);
				
				return true;
			}
			
			return false;
		}
		
		public function FastAuth($email) {
			$sth = pdo()->prepare(
				'SELECT * FROM `users` WHERE `email`=:email LIMIT 1'
			);
			
			$sth->execute([
				':email' => $email
			]);
			
			if(!$sth->rowCount()) {
				return false;
			}
			
			$user = $sth->fetch(
				PDO::FETCH_OBJ
			);
			
			$_SESSION['id'] = $user->id;
			
			return true;
		}
		
		public function IsValid($id) {
			$sth = pdo()->prepare(
				'SELECT * FROM `users` WHERE `id`=:id LIMIT 1'
			);
			
			$sth->execute([
				':id' => $id
			]);
			
			return $sth->rowCount();
		}
		
		public function IsValidEmail($email) {
			$sth = pdo()->prepare(
				'SELECT * FROM `users` WHERE `email`=:email LIMIT 1'
			);
			
			$sth->execute([
				':email' => $email
			]);
			
			return $sth->rowCount();
		}
		
		public function IsValidNickname($nickname) {
			$sth = pdo()->prepare(
				'SELECT * FROM `users` WHERE `nickname`=:nickname LIMIT 1'
			);
			
			$sth->execute([
				':nickname' => $nickname
			]);
			
			return $sth->rowCount();
		}
		
		public static function Get($id) {
			$sth = pdo()->prepare(
				'SELECT * FROM `users` WHERE `id`=:id LIMIT 1'
			);
			
			$sth->execute([
				':id' => $id
			]);
			
			if(!$sth->rowCount()) {
				return null;
			}
			
			return $sth->fetch(
				PDO::FETCH_OBJ
			);
		}
		
		public static function GetForEmail($email) {
			$sth = pdo()->prepare(
				'SELECT * FROM `users` WHERE `email`=:email LIMIT 1'
			);
			
			$sth->execute([
				':email' => $email
			]);
			
			if(!$sth->rowCount()) {
				return null;
			}
			
			return $sth->fetch(
				PDO::FETCH_OBJ
			);
		}
		
		public function Logout() {
			unset(
				$_SESSION['id']
			);
			
			unset(
				$_SESSION['NextSetOnline']
			);
			
			return true;
		}
		
		public function SetOnline($userid) {
			try{
				$sth = pdo()->prepare(
					'UPDATE `users` SET `last_online`=:last_online WHERE `id`=:id LIMIT 1'
				);
				
				$sth->execute([
					':id' => $userid,
					':last_online' => date('Y-m-d H:i:s')
				]);
				
				$_SESSION['NextSetOnline'] = date("Y-m-d H:i:s", strtotime("+15 minutes"));
				
				return true;
			}
			catch(Exception $e) {
				return false;
			}
		}
		
		public function GetStyleRights($id) {
			try {
				$sth = pdo()->prepare(
					'SELECT * FROM `users__group` WHERE `id`=:id LIMIT 1'
				);
				
				$sth->execute([
					':id' => $id
				]);
				
				return $sth->fetch(
					PDO::FETCH_OBJ
				);
			}
			catch(Exception $e) {
				return null;
			}
		}
		
		public function AddBalance($UserId, $Amount) {
			$_OldAmount = $this->Get($UserId)->balance;
			
			try {
				$sth = pdo()->prepare(
					'UPDATE `users` SET `balance`=:balance WHERE `id`=:userid LIMIT 1'
				);
				
				$sth->execute([
					':balance' => $_OldAmount + $Amount,
					':userid' => $UserId
				]);
				
				return $this;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return $this;
		}
		
		public function IsBetaKey($Code) {
			if(empty($Code)) {
				return false;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `beta__keys` WHERE `code`=:code LIMIT 1');
				$sth->execute([
					':code' => $Code
				]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function IsBeta($UserId) {
			if(empty($UserId)) {
				return false;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `beta` WHERE `userid`=:userid LIMIT 1');
				$sth->execute([
					':userid' => $UserId
				]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function GetBeta($UserId) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `beta` WHERE `userid`=:userid LIMIT 1');
				$sth->execute([':userid' => $UserId]);
				
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public function AddBeta($UserId, $Key) {
			try {
				$sth = pdo()->prepare('INSERT INTO `beta` (`userid`, `date`) VALUES (:userid, :date)');
				$sth->execute([':userid' => $UserId, ':date' => date('Y-m-d H:i:s')]);
				
				$sth = pdo()->prepare('DELETE FROM `beta__keys` WHERE `code`=:code LIMIT 1');
				$sth->execute([':code' => $Key]);
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function Ip() {
			return isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
		}
		
		public static function City($UserId = null) {
			if(empty($UserId)) {
				return getLang('sys_unknown');
			}
			
			$_User = self::Get($UserId);
			
			if(Country::IsValid($_User->country, $_User->city)) {
				return Country::City($_User->city)->name;
			}
			
			return getLang('sys_unknown');
		}
		
		public function IsRecovery($email) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `users__recovery` WHERE `email`=:email OR `ip`=:ip ORDER BY `id` DESC LIMIT 1');
				$sth->execute([':email' => $email, ':ip' => $this->Ip()]);
				
				if($sth->rowCount()) {
					$row = $sth->fetch(PDO::FETCH_OBJ);
					
					if((strtotime($row->date) + 600) > time()) {
						return true;
					}
				}
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function IsRecoveryHash($hash) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `users__recovery` WHERE `hash`=:hash ORDER BY `id` DESC LIMIT 1');
				$sth->execute([':hash' => $hash]);
				
				if($sth->rowCount()) {
					$row = $sth->fetch(PDO::FETCH_OBJ);
					
					if((strtotime($row->date) + 86400) > time()) {
						return true;
					}
				}
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function GetRecovery($hash) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `users__recovery` WHERE `hash`=:hash ORDER BY `id` DESC LIMIT 1');
				$sth->execute([':hash' => $hash]);
				
				if(!$sth->rowCount()) {
					throw new Exception(getLang('users_exception_key'));
				}
				
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			throw new Exception(getLang('users_exception_err'));
		}
		
		public function Recovery($email) {
			if(!$this->IsValidEmail($email)) {
				throw new Exception(getLang('users_exception_not_user'));
			}
			
			if($this->IsRecovery($email)) {
				throw new Exception(getLang('users_exception_wait10'));
			}
			
			$Hash = md5(RandomString(12) . $email);
			
			try {
				$sth = pdo()->prepare('INSERT INTO `users__recovery`(`email`, `ip`, `hash`, `date`) VALUES (:email, :ip, :hash, :date)');
				$sth->execute([
					':email' => $email,
					':ip' => $this->Ip(),
					':hash' => $Hash,
					':date' => date('Y-m-d H:i:s')
				]);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
				throw new Exception(getLang('users_exception_err'));
			}
			
			$Message = tpl()->Set(['{link}' => 'https://' . $_SERVER['SERVER_NAME'] . '/account/recovery?hash=' . $Hash], tpl()->Get('elements/mailer/recovery'));
			$Mail = new Mailer(true);
			
			try {
				$Mail
				->connect()
				->add($email)
				->form(getLang('users_request_recovery'), $Message)
				->send();
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			throw new Exception(getLang('users_exception_err'));
		}
		
		public function ChangePassword($userid, $password) {
			try {
				$sth = pdo()->prepare('UPDATE `users` SET `password`=:password WHERE `id`=:userid LIMIT 1');
				$sth->execute([
					':userid' => $userid,
					':password' => password_hash($password, PASSWORD_DEFAULT)
				]);
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			throw new Exception(getLang('users_exception_err'));
		}
		
		public function RecoveryRemoveHash($hash) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `users__recovery` WHERE `hash`=:hash LIMIT 1');
				$sth->execute([
					':hash' => $hash
				]);
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Users]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
	}