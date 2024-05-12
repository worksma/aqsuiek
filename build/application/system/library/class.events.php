<?PHP
	class Events {
		public function Add($userid, $message, $uri) {
			try {
				$sth = pdo()->prepare('INSERT INTO `noty__events`(`userid`, `message`, `uri`, `date`) VALUES (:userid, :message, :uri, :date)');
				$sth->execute([
					':userid' => $userid,
					':message' => $message,
					':uri' => $uri,
					':date' => date('Y-m-d H:i:s')
				]);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
		}
		
		public function List($limit = 10) {
			try {
				$sth = pdo()->query('SELECT * FROM `noty__events` ORDER BY `id` DESC LIMIT ' . $limit);
				
				if(!$sth->rowCount()) {
					return '<center style="margin-bottom: 24px;">' . getLang('events_no') . '</center>';
				}
				
				$tpl = new Template;
				$tpl->SetCell(['Events' => '']);
				
				while($row = $sth->fetch(PDO::FETCH_OBJ)) {
					$tpl->SetCell([
						'Events' => $tpl->GetCell('Events') . $tpl->Set([
							'{id}' => $row->id,
							'{userid}' => $row->userid,
							'{message}' => $row->message,
							'{uri}' => $row->uri,
							'{date}' => $row->date
						], $tpl->Get('elements/events/list'))
					]);
				}
				
				return $tpl->Execute($tpl->GetCell('Events'));
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return '<center style="margin-bottom: 24px;">' . getLang('events_no') . '</center>';
		}
	}