<?PHP
	class MusicArtists {
		/*
			Создаем новых артистов
		*/
		public function Add($_ARRAY = []) {
			try {
				foreach($_ARRAY as $key => $artist) {
					if(empty($artist['nickname'])) {
						$artist['nickname'] = RandomString(12);
					}
					
					if($this->isNickname($artist['nickname'])) {
						continue;
					}
					
					$sth = pdo()->prepare('INSERT INTO `music__artist`(`name`, `nickname`, `date_create`) VALUES (:name, :nickname, :date_create)');
					$sth->execute([
						':name' => strip_tags(trim($artist['name'])),
						':nickname' => strip_tags(trim($artist['nickname'])),
						':date_create' => time()
					]);
				}
				
				return true;
			}
			catch(Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		/*
			Проверяем артиста на занятость
		*/
		public function isNickname($nickname) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `music__artist` WHERE `nickname`=:nickname LIMIT 1');
				$sth->execute([
					':nickname' => $nickname
				]);
				
				return $sth->rowCount();
			}
			catch(Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		/*
			Получение сведений об артисте
		*/
		public function Get($artistid) {
			$mem_key = 'music_artist_' . $artistid;
			Memcached()->delete($mem_key);
			
			$mem_data = Memcached()->get($mem_key);
			
			if($mem_data) {
				return $mem_data;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `music__artist` WHERE `id`=:id LIMIT 1');
				$sth->execute([
					':id' => $artistid
				]);
				
				if(!$sth->rowCount()) {
					return null;
				}
				
				$mem_data = $sth->fetch(PDO::FETCH_OBJ);
				Memcached()->set($mem_key, $mem_data, 3600);
				
				return $mem_data;
			}
			catch(Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		/*
			Формирование артистов
		*/
		public function Formate($ids = []) {
			$dataFormate = '';
			$sizeCount = count($ids) - 1;
			
			foreach($ids as $key => $artistid) {
				$dataArtist = $this->Get($artistid);
				
				if(empty($dataArtist)) {
					$dataFormate .= 'Неизвестный' . (($sizeCount == $key) ? '' : ', ');
				}
				else {
					$dataFormate .= '<a href="/artist/' . $dataArtist->nickname . '">' . $dataArtist->name . (($sizeCount == $key) ? '' : ', ') . '</a>';
				}
			}
			
			return $dataFormate;
		}
	}