<?PHP
	class Noty {
		public function Nav() {
			try {
				$sth = pdo()->prepare('SELECT * FROM `noty__users` WHERE `userid`=:userid ORDER BY `id` DESC');
				$sth->execute([
					':userid' => $_SESSION['id']
				]);
				
				if(!$sth->rowCount()) {
					tpl()->AddCell('List', '<center class="mt-4"><label>' . getLang('noty_no_events') . '</label></center>');
				}
				
				while($Not = $sth->fetch(PDO::FETCH_OBJ)) {
					tpl()->AddCell('List', tpl()->Set([
						'{link}' => $Not->link,
						'{image}' => $Not->image,
						'{content}' => $Not->content,
						'{date}' => DayToTime($Not->date),
					], tpl()->Get('elements/notys/standart')));
				}
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Noty]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			finally {
				tpl()->AddCell('Noty', tpl()->Set([
					'{bells}' => $this->Row($_SESSION['id']),
					'{list}' => tpl()->GetCell('List')
				], tpl()->Get('elements/navigation/noty')));
				
				return tpl()->Execute(tpl()->GetCell('Noty'));
			}
		}
		
		public function Row($userid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `noty__users` WHERE `userid`=:userid AND `ready`!=\'1\'');
				$sth->execute([
					':userid' => $userid
				]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Noty]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
		
		public function Send($userid, $args = ['content' => '', 'link' => null, 'image' => 'no_image.svg', 'type' => '1', 'attached' => null]) {
			try {
				$sth = pdo()->prepare('INSERT INTO `noty__users`(`userid`, `content`, `link`, `image`, `type`, `attached`, `ready`, `date`) VALUES (:userid, :content, :link, :image, :type, :attached, :ready, :date)');
				$sth->execute([
					':userid' => $userid,
					':content' => $args['content'],
					':link' => $args['link'],
					':image' => $args['image'],
					':type' => $args['type'],
					':attached' => isset($args['attached']) ? $args['attached'] : NULL,
					':ready' => '2',
					':date' => date('Y-m-d H:i:s')
				]);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Noty]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
		
		public function Get($attached) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `noty__users` WHERE `attached`=:attached');
				$sth->execute([
					':attached' => $attached
				]);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Noty]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			finally {
				if(!$sth->rowCount()) {
					return null;
				}
				
				return $sth;
			}
		}
		
		public function ReadyAll($userid) {
			try {
				$sth = pdo()->prepare('UPDATE `noty__users` SET `ready`=\'1\' WHERE `userid`=:userid AND `ready`=\'2\'');
				$sth->execute([
					':userid' => $userid
				]);
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Noty]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
	}