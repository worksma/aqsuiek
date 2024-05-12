<?PHP
	class MusicPlayer {
		/*
			Добавляем трек пользователю в базу
		*/
		public function addTrack($userid, $trackid) {
			Memcached()->delete('music_list_' . $userid);		/* Список музыки */
			Memcached()->delete('is_tracks_' . $userid);		/* Наличие треков */
			Memcached()->delete('music_json_' . $userid);		/* Удаляем JSON данные */
			
			try {
				if($this->isTracks($userid)) {
					$dataTracks = $this->getTracks($userid);
					$dataTracks[] = $trackid;
					
					$sth = pdo()->prepare('UPDATE `users__music` SET `data`=:data WHERE `userid`=:userid LIMIT 1');
				}
				else {
					$dataTracks = [
						$trackid
					];
					
					$sth = pdo()->prepare('INSERT INTO `users__music`(`userid`, `data`) VALUES (:userid, :data)');
				}
				
				$sth->execute([
					':data' => json_encode($dataTracks),
					':userid' => $userid
				]);
				
				return true;
			}
			catch(Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		/*
			Проверяем наличие треков у пользователя
		*/
		public function isTracks($userid) {
			$mem_key = 'is_tracks_' . $userid;
			Memcached()->delete($mem_key);
			$mem_data = Memcached()->get($mem_key);
			
			if($mem_data) {
				return $mem_data;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `users__music` WHERE `userid`=:userid LIMIT 1');
				$sth->execute([
					':userid' => $userid
				]);
				
				$mem_data = $sth->rowCount();
				Memcached()->set($mem_key, $mem_data, 3600);
				
				return $mem_data;
			}
			catch(Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		/*
			Получаем треки пользователя
		*/
		public function getTracks($userid) {
			$mem_key = 'music_tracks_' . $userid;
			Memcached()->delete($mem_key);
			$mem_data = Memcached()->get($mem_key);
			
			if($mem_data) {
				return $mem_data;
			}
			
			try {
				$sth = pdo()->prepare('SELECT * FROM `users__music` WHERE `userid`=:userid LIMIT 1');
				$sth->execute([
					':userid' => $userid
				]);
				
				if(!$sth->rowCount()) {
					throw new Exception('Треков нет');
				}
				
				$mem_data = json_decode($sth->fetch(PDO::FETCH_OBJ)->data, true);
				Memcached()->set($mem_key, $mem_data, 3600);
				
				return $mem_data;
			}
			catch(Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		/*
			Получение графического списка треков пользователя
		*/
		public function getList($userid) {
			$mem_key = 'music_list_' . $userid;
			Memcached()->delete($mem_key);
			$mem_data = Memcached()->get($mem_key);
			
			if($mem_data) {
				return $mem_data;
			}
			
			try {
				$dataTracks = $this->getTracks($userid);
				$tpl = new Template;
				$classMusicTracks = new MusicTracks;
				$classMusicArtists = new MusicArtists;
				
				foreach($dataTracks as $key => $trackid) {
					$track = $classMusicTracks->Get($trackid);
					
					$tpl->AddCell('trackList', $tpl->Set([
						'{id}' => $trackid,
						'{image}' => $track->image,
						'{name}' => $track->name,
						'{artist}' => $classMusicArtists->Formate(json_decode($track->artistids, true))
					], $tpl->Get('elements/music/track')));
				}
				
				$mem_data = $tpl->Execute($tpl->GetCell('trackList'));
				Memcached()->set($mem_key, $mem_data, 3600);
				
				return $mem_data;
			}
			catch(Exception $e) {
				return $e->getMessage();
			}
		}
		
		/*
			Получение треков пользователя в JSON
		*/
		public function getJson($userid) {
			$mem_key = 'music_json_' . $userid;
			Memcached()->delete($mem_key);
			$mem_data = Memcached()->Get($mem_key);
			
			if($mem_data) {
				return $mem_data;
			}
			
			try {
				$classMusicTracks = new MusicTracks;
				$classMusicArtists = new MusicArtists;
				
				$dataTracks = $this->getTracks($userid);
				$formateJson = [];
				
				foreach($dataTracks as $key => $trackid) {
					$dataTrack = $classMusicTracks->Get($trackid);
					$dataArtists = $classMusicArtists->Formate(
						json_decode(
							$dataTrack->artistids, true
						)
					);
					
					$formateJson[$trackid] = [
						'name' => $dataTrack->name,
						'image' => file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/images/music/' . $dataTrack->image) ? ('/public/images/music/' . $dataTrack->image) : $dataTrack->image,
						'file' => file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/uploads/music/' . $dataTrack->file) ? ('/public/uploads/music/' . $dataTrack->file) : $dataTrack->file,
						'artist' => $dataArtists
					];
				}
				
				$mem_data = json_encode($formateJson);
				Memcached()->set($mem_key, $mem_data, 3600);
				
				return $mem_data;
			}
			catch(Exception $e) {
				return $e->getMessage();
			}
		}
	}