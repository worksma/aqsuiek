<?PHP
	class Blog {
		public function Create($Name, $Description, $ThemeId) {
			try {
				$sth = pdo()->prepare('INSERT INTO `blog`(`userid`, `name`, `themeid`, `description`, `data`, `date`) VALUES (:userid, :name, :themeid, :description, :data, :date)');
				
				$sth->execute([
					':userid'		=> $_SESSION['id'],
					':name'			=> $Name,
					':themeid'		=> $ThemeId,
					':description'	=> $Description,
					':data'			=> json_encode([
						'subscribers' => [
							$_SESSION['id'] => [
								'access'		=> '1',
								'date'			=> date('Y-m-d H:i:s')
							]
						]
					]),
					':date'			=> date('Y-m-d H:i:s')
				]);
				
				return pdo()->lastInsertId();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public function IsValid($id) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `blog` WHERE `id`=:id LIMIT 1');
				$sth->execute([':id' => $id]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function Get($id) {
			if(!$this->IsValid($id)) {
				return null;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `blog` WHERE `id`=:id LIMIT 1');
				$sth->execute([':id' => $id]);
				
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public function Data($id) {
			$Data = $this->Get($id);
			
			if(empty($Data)) {
				return null;
			}
			
			return json_decode($Data->data, true);
		}
		
		public function AddData($id, $key, $value = []) {
			$Data = $this->Data($id);
			
			if(empty($Data)) {
				return false;
			}
			
			$Data[$key] += $value;
			
			try {
				$sth = pdo()->prepare('UPDATE `blog` SET `data`=:data WHERE `id`=:id LIMIT 1');
				$sth->execute([':data' => json_encode($Data), ':id' => $id]);
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function Sub($id) {
			if($this->IsSub($id, $_SESSION['id'])) {
				return true;
			}
			
			if($this->AddData($id, 'subscribers', [$_SESSION['id'] => ['access' => '2','date' => date('Y-m-d H:i:s')]])) {
				return true;
			}
			
			return false;
		}
		
		public function UnSub($id) {
			if(!$this->IsSub($id, $_SESSION['id'])) {
				return true;
			}
			
			$Data = $this->Data($id);
			
			if(empty($Data)) {
				return false;
			}
			
			if(isset($Data['subscribers'][$_SESSION['id']])) {
				unset($Data['subscribers'][$_SESSION['id']]);
				
				try {
					$sth = pdo()->prepare('UPDATE `blog` SET `data`=:data WHERE `id`=:id LIMIT 1');
					$sth->execute([':data' => json_encode($Data), ':id' => $id]);
					
					return true;
				}
				catch(Exception $e) {
					AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
				}
				
				return false;
			}
			
			return false;
		}
		
		public function IsSub($id, $userid) {
			$Data = $this->Data($id);
			
			if(empty($Data)) {
				return false;
			}
			
			return isset($Data['subscribers'][$userid]);
		}
		
		public static function LastPosts($limit = 5) {
			try {
				$sth = pdo()->query('SELECT * FROM `blog__post` ORDER BY `id` DESC LIMIT ' . $limit);
				
				if(!$sth->rowCount()) {
					return getLang('blog_msg_no_writes');
				}
				
				$tpl = new Template;
				$tpl->SetCell(['BlogPost' => '']);
				
				while($Post = $sth->fetch(PDO::FETCH_OBJ)) {
					$tpl->SetCell([
						'BlogPost' => $tpl->GetCell('BlogPost') . $tpl->Set([
							'{id}'				=> $Post->id,
							'{blogid}'			=> $Post->blogid,
							'{userid}'			=> $Post->userid,
							'{title}'			=> strip_tags(htmlspecialchars_decode($Post->title)),
							'{content}'			=> strip_tags(htmlspecialchars_decode($Post->content)),
							'{date}'			=> DayToTime($Post->date),
							'{image}'			=> $Post->image
						], $tpl->Get('elements/widgets/blog'))
					]);
				}
				
				return $tpl->Execute($tpl->GetCell('BlogPost'));
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return getLang('blog_msg_no_writes');
		}
		
		public function SubsUI($id, $limit = 12) {
			if(!$this->IsValid($id)) {
				return getLang('blog_msg_no_subs');
			}
			
			$Data = $this->Data($id);
			
			if(empty($Data)) {
				return getLang('blog_msg_no_subs');
			}
			
			$Subs = '';
			$Flow = 1;
			
			foreach($Data['subscribers'] as $UserId => $Info) {
				if($Flow <= 12) {
					$UserData = Users()->Get($UserId);
					$Subs .= '<a href="/id' . $UserId . '"><img src="' . GetUserAvatar($UserId) . '" title="' . $UserData->first_name . '"></a>';
					
					$Flow++;
				}
				else {
					break;
				}
			}
			
			return $Subs;
		}
		
		public function PostsUI($id) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `blog__post` WHERE `blogid`=:blogid ORDER BY `id` DESC');
				$sth->execute([':blogid' => $id]);
				
				if(!$sth->rowCount()) {
					return getLang('blog_msg_no_posts');
				}
				
				$tpl = new Template;
				$tpl->SetCell(['Posts' => '']);
				
				while($Post = $sth->fetch(PDO::FETCH_OBJ)) {
					$tpl->SetCell([
						'Posts' => $tpl->GetCell('Posts') . $tpl->Set([
							'{id}'				=> $Post->id,
							'{blogid}'			=> $Post->blogid,
							'{userid}'			=> $Post->userid,
							'{title}'			=> strip_tags(htmlspecialchars_decode($Post->title)),
							'{text}'			=> strip_tags(htmlspecialchars_decode($Post->content)),
							'{date}'			=> DayToTime($Post->date),
							'{image}'			=> $Post->image
						], $tpl->Get('elements/blog/post'))
					]);
				}
				
				return $tpl->Execute($tpl->GetCell('Posts'));
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return getLang('blog_msg_no_posts');
		}
		
		public function IsPost($id) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `blog__post` WHERE `id`=:id LIMIT 1');
				$sth->execute([':id' => $id]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function GetPost($id) {
			if(!$this->IsPost($id)) {
				return null;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `blog__post` WHERE `id`=:id LIMIT 1');
				$sth->execute([':id' => $id]);
				
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public function AddPost($blogid, $_ARRAY = []) {
			try {
				$sth = pdo()->prepare('INSERT INTO `blog__post`(`blogid`, `userid`, `image`, `title`, `content`, `data`, `date`) VALUES (:blogid, :userid, :image, :title, :content, :data, :date)');
				$sth->execute([
					':blogid' => $blogid,
					':userid' => $_SESSION['id'],
					':image' => isset($_ARRAY['image']) ? $_ARRAY['image'] : 'no_image.jpg',
					':title' => $_ARRAY['title'],
					':content' => $_ARRAY['content'],
					':data' => json_encode(['likes' => [], 'views' => []]),
					':date' => date('Y-m-d H:i:s')
				]);
				
				return pdo()->lastInsertId();
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public function IsAccess($blogid, $userid, $access) {
			$Data = $this->Data($blogid)['subscribers'][$userid];
			return ($Data['access'] == $access);
		}
		
		public function PostData($id) {
			$Data = $this->GetPost($id);
			
			if(empty($Data)) {
				return null;
			}
			
			return json_decode($Data->data, true);
		}
		
		public function AddPostData($id, $key, $value = []) {
			$Data = $this->PostData($id);
			
			if(empty($Data)) {
				return false;
			}
			
			$Data[$key] += $value;
			
			try {
				$sth = pdo()->prepare('UPDATE `blog__post` SET `data`=:data WHERE `id`=:id LIMIT 1');
				$sth->execute([':data' => json_encode($Data), ':id' => $id]);
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
		
		public function IsPostDataKey($postid, $item, $key) {
			$Data = $this->PostData($postid);
			return isset($Data[$item][$key]);
		}
		
		public function ListSubs($userid) {
			try {
				$sth = pdo()->query('SELECT * FROM `blog` WHERE 1');
				
				$tpl = new Template;
				$tpl->SetCell(['ListSubs' => '']);
				
				while($row = $sth->fetch(PDO::FETCH_OBJ)) {
					$Data = $this->Data($row->id);
					
					if(isset($Data['subscribers'][$userid])) {
						$tpl->SetCell([
							'ListSubs' => $tpl->GetCell('ListSubs') . $tpl->Set([
								'{id}' => $row->id,
								'{image}' => $row->image,
								'{name}' => strip_tags(htmlspecialchars_decode($row->name)),
								'{subs}' => count($Data['subscribers'])
							], $tpl->Get('elements/blog/list'))
						]);
					}
				}
				
				if($tpl->GetCell('ListSubs') == '') {
					return getLang('blog_msg_no_list');
				}
				
				return $tpl->Execute($tpl->GetCell('ListSubs'));
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return getLang('blog_msg_no_list');
		}
		
		/*
			Поиск блогов
		*/
		public function Search($args) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `blog` WHERE `name` LIKE :args OR `description` LIKE :args');
				$sth->execute([
					':args' => '%' . $args . '%'
				]);
				
				if(!$sth->rowCount()) {
					return getLang('blog_msg_no_list');
				}
				
				$tpl = new Template;
				while($Blog = $sth->fetch(PDO::FETCH_OBJ)) {
					$dataBlog = json_decode($Blog->data, true);
					
					$tpl->AddCell('listBlogs', $tpl->Set([
						'{id}' => $Blog->id,
						'{image}' => $Blog->image,
						'{name}' => strip_tags(htmlspecialchars_decode($Blog->name)),
						'{subs}' => count($dataBlog['subscribers'])
					], $tpl->Get('elements/blog/list')));
				}
				
				return $tpl->Execute($tpl->GetCell('listBlogs'));
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return getLang('blog_msg_no_list');
		}
	}