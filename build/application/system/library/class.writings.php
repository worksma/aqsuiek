<?PHP
	class Writings {
		public function Write($universe, $_ARRAY = []) {
			try {
				$sth = pdo()->prepare(
					'INSERT INTO `writing`(`author`, `universe`, `entity`, `date`, `content`) VALUES (:author, :universe, :entity, :date, :content)'
				);
				
				$Emoji = new Emoji;
				
				$sth->execute([
					':author'			=> $_SESSION['id'],
					':universe'			=> $universe,
					':entity'			=> 'write',
					':date'				=> date('Y-m-d H:i:s'),
					':content'			=> htmlspecialchars(trim($Emoji->Convert($_ARRAY['content'])))
				]);
				
				return pdo()->lastInsertId();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public static function Get($id) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing` WHERE `id`=:id LIMIT 1');
				$sth->execute([':id' => $id]);
				
				if(!$sth->rowCount()) {
					return null;
				}
				
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public static function GetComment($id) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing__comments` WHERE `id`=:id LIMIT 1');
				$sth->execute([':id' => $id]);
				
				if(!$sth->rowCount()) {
					return null;
				}
				
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public function IsValid($id) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing` WHERE `id`=:id LIMIT 1');
				$sth->execute([':id' => $id]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function AddComment($userid, $writeid, $content) {
			try {
				$Emoji = new Emoji;
				
				$sth = pdo()->prepare('INSERT INTO `writing__comments`(`userid`, `writeid`, `content`, `date`) VALUES (:userid, :writeid, :content, :date)');
				$sth->execute([
					':userid'		=> $userid,
					':writeid'		=> $writeid,
					':content'		=> htmlspecialchars(trim($Emoji->Convert($content))),
					':date'			=> date('Y-m-d H:i:s')
				]);
				
				return pdo()->lastInsertId();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public function GetCommentUI($id) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing__comments` WHERE `id`=:id LIMIT 1');
				$sth->execute([':id' => $id]);
				
				if(!$sth->rowCount()) {
					return null;
				}
				
				$Write = $sth->fetch(PDO::FETCH_OBJ);
				
				$tpl = new Template;
				$tpl->SetCell(['WriteComment' => '']);
				
				$UserData = Users()->Get($Write->userid);
				$Writings = new Attachments;
				
				$tpl->SetCell([
					'WriteComment' => $tpl->GetCell('WriteComment') . $tpl->Set([
						'{id}'				=> $Write->id,
						'{userid}'			=> $Write->userid,
						'{content}'			=> htmlspecialchars_decode(htmlspecialchars_decode($Write->content)),
						'{profile_name}'	=> GetUserName($Write->userid, ['full' => true, 'very' => true, 'link' => true]),
						'{profile_image}'	=> GetUserAvatar($Write->userid),
						'{date}'			=> DayToTime($Write->date),
						'{attachment}'		=> $Writings->VisualComment($Write->id),
						'{likeRows}'		=> $this->RowCommentLikes($Write->id),
						'{commentRows}'		=> $this->RowComments($Write->writeid)
					], $tpl->Get('elements/writing/comment'))
				]);
				
				return $tpl->Execute($tpl->GetCell('WriteComment'));
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public function GetComments($writeid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing__comments` WHERE `writeid`=:writeid ORDER BY `id` DESC');
				$sth->execute([':writeid' => $writeid]);
				
				if(!$sth->rowCount()) {
					return '';
				}
				
				$tpl = new Template;
				$tpl->SetCell(['WriteComments' => '']);
				
				$Writings = new Attachments;
				
				while($Write = $sth->fetch(PDO::FETCH_OBJ)) {
					$UserData = Users()->Get($Write->userid);
					
					$tpl->SetCell([
						'WriteComments' => $tpl->GetCell('WriteComments') . $tpl->Set([
							'{id}'				=> $Write->id,
							'{userid}'			=> $Write->userid,
							'{content}'			=> htmlspecialchars_decode(htmlspecialchars_decode($Write->content)),
							'{profile_name}'	=> GetUserName($Write->userid, ['full' => true, 'very' => true, 'link' => true]),
							'{profile_image}'	=> GetUserAvatar($Write->userid),
							'{date}'			=> DayToTime($Write->date),
							'{attachment}'		=> $Writings->VisualComment($Write->id),
							'{likeRows}'		=> $this->RowCommentLikes($Write->id),
							'{commentRows}'		=> $this->RowComments($writeid)
						], $tpl->Get('elements/writing/comment'))
					]);
				}
				
				return $tpl->Execute($tpl->GetCell('WriteComments'));
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return '';
		}
		
		public static function IsLiked($userid, $writeid) {
			$Writing = self::Get($writeid);
			
			if(empty($Writing)) {
				return false;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing__likes` WHERE `userid`=:userid AND `writeid`=:writeid LIMIT 1');
				$sth->execute([
					':userid'		=> $userid,
					':writeid'		=> $writeid
				]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public static function IsCommentLiked($userid, $commentid) {
			$Writing = self::GetComment($commentid);
			
			if(empty($Writing)) {
				return false;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing__comments-likes` WHERE `userid`=:userid AND `commentid`=:commentid LIMIT 1');
				$sth->execute([
					':userid'		=> $userid,
					':commentid'		=> $commentid
				]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function SetLike($writeid, $liked) {
			$Writing = $this->Get($writeid);
			
			if(empty($Writing)) {
				return false;
			}
			
			try {
				if($liked) {
					$sth = pdo()->prepare('DELETE FROM `writing__likes` WHERE `userid`=:userid AND `writeid`=:writeid LIMIT 1');
					$sth->execute([
						':userid'		=> $_SESSION['id'],
						':writeid'		=> $writeid
					]);
				}
				else {
					$sth = pdo()->prepare('INSERT INTO `writing__likes`(`userid`, `writeid`, `authorid`, `date`) VALUES (:userid, :writeid, :authorid, :date)');
					$sth->execute([
						':userid'		=> $_SESSION['id'],
						':writeid'		=> $writeid,
						':authorid'		=> $Writing->author,
						':date'			=> date('Y-m-d H:i:s')
					]);
				}
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function SetCommentLike($commentid, $liked) {
			$Comment = $this->GetComment($commentid);
			
			if(empty($Comment)) {
				return false;
			}
			
			try {
				if($liked) {
					$sth = pdo()->prepare('DELETE FROM `writing__comments-likes` WHERE `userid`=:userid AND `commentid`=:commentid LIMIT 1');
					$sth->execute([
						':userid'		=> $_SESSION['id'],
						':commentid'	=> $commentid
					]);
				}
				else {
					$sth = pdo()->prepare('INSERT INTO `writing__comments-likes`(`userid`, `commentid`, `authorid`, `date`) VALUES (:userid, :commentid, :authorid, :date)');
					$sth->execute([
						':userid'		=> $_SESSION['id'],
						':commentid'	=> $commentid,
						':authorid'		=> $Comment->userid,
						':date'			=> date('Y-m-d H:i:s')
					]);
				}
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function RowLikes($writeid) {
			$Writing = $this->Get($writeid);
			
			if(empty($Writing)) {
				return 0;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing__likes` WHERE `writeid`=:writeid');
				$sth->execute([':writeid' => $writeid]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return 0;
		}
		
		public function RowCommentLikes($commentid) {
			$Comment = $this->GetComment($commentid);
			
			if(empty($Comment)) {
				return 0;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing__comments-likes` WHERE `commentid`=:commentid');
				$sth->execute([':commentid' => $commentid]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return 0;
		}
		
		public function RowComments($writeid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing__comments` WHERE `writeid`=:writeid');
				$sth->execute([':writeid' => $writeid]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return 0;
		}
		
		public function IsView($writeid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing__views` WHERE `writeid`=:writeid AND `ip`=:ip LIMIT 1');
				$sth->execute([':writeid' => $writeid, ':ip' => Users()->Ip()]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function AddViews($writeid) {
			try {
				$sth = pdo()->prepare('INSERT INTO `writing__views`(`writeid`, `ip`, `date`) VALUES (:writeid, :ip, :date)');
				$sth->execute([':writeid' => $writeid, ':ip' => Users()->Ip(), ':date' => date('Y-m-d H:i:s')]);
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function GetViews($writeid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing__views` WHERE `writeid`=:writeid');
				$sth->execute([':writeid' => $writeid]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return 0;
		}
		
		public function IsHeritage($writeid, $userid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing` WHERE `author`=:userid AND `id`=:id LIMIT 1');
				$sth->execute([
					':userid'	=> $userid,
					':id'		=> $writeid
				]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function IsRemove($id) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing` WHERE `id`=:id AND `remove`=\'1\' LIMIT 1');
				$sth->execute([':id' => $id]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function Remove($id, $reason) {
			try {
				$sth = pdo()->prepare('UPDATE `writing` SET `remove`=:remove, `remove_reason`=:reason, `remove_date`=:date WHERE `id`=:id LIMIT 1');
				$sth->execute([
					':remove' 	=> '1',
					':reason'	=> $reason,
					':date'		=> date('Y-m-d H:i:s'),
					':id'		=> $id
				]);
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Writings]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
	}