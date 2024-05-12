<?PHP
	class MusicTracks {
		/*
			Добавление трека
		*/
		public function Add($_ARRAY = []) {
			try {
				$sth = pdo()->prepare('INSERT INTO `music__tracks`(`name`, `albumid`, `artistids`, `image`, `file`, `streamings`, `date_create`) VALUES (:name, :albumid, :artistids, :image, :file, :streamings, :date_create)');
				$sth->execute([
					':name' => strip_tags($_ARRAY['name']),
					':albumid' => $_ARRAY['albumid'],
					':artistids' => json_encode($_ARRAY['artistids']),
					':image' => $_ARRAY['image'],
					':file' => $_ARRAY['file'],
					':streamings' => 0,
					':date_create' => time()
				]);
				
				return pdo()->lastInsertId();
			}
			catch(Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		/*
			Получаем данные о треке
		*/
		public function Get($trackid) {
			$key = 'music_track_' . $trackid;
			Memcached()->delete($mem_key);
			$data = Memcached()->get($key);
			
			if($data) {
				return $data;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `music__tracks` WHERE `id`=:trackid LIMIT 1');
				$sth->execute([
					':trackid' => $trackid
				]);
				
				if(!$sth->rowCount()) {
					throw new Exception('Трека нет в базе');
				}
				
				$data = $sth->fetch(PDO::FETCH_OBJ);
				Memcached()->set($key, $data, 3600);
				
				return $data;
			}
			catch(Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
	}