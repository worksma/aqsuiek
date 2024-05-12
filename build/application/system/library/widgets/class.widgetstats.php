<?PHP
	class WidgetStats {
		public static function Likes($userid) {
			$likes = 0;
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing__likes` WHERE `authorid`=:authorid');
				$sth->execute([':authorid' => $userid]);
				
				$likes += $sth->rowCount();
				
				$sth = pdo()->prepare('SELECT * FROM `writing__comments-likes` WHERE `authorid`=:authorid');
				$sth->execute([':authorid' => $userid]);
				
				$likes += $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Widget Stats]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return $likes;
		}
		
		public static function Writes($userid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing` WHERE `author`=:author AND `entity`=\'write\'');
				$sth->execute([':author' => $userid]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Widget Stats]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return 0;
		}
		
		public static function Subs($userid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `users__sub` WHERE `recipientid`=:recipientid');
				$sth->execute([':recipientid' => $userid]);
				
				if(!$sth->rowCount()) {
					return '0';
				}
				
				if(class_exists('BlackList')) {
					$BL = new BlackList;
					$Count = 0;
					
					while($Sub = $sth->fetch(PDO::FETCH_OBJ)) {
						if($BL->Is($Sub->recipientid, $Sub->senderid) OR $BL->Is($Sub->senderid, $Sub->recipientid)) {
							continue;
						}
						
						$Count++;
					}
					
					return $Count;
				}
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Widget Stats]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return 0;
		}
	}