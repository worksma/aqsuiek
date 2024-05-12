<?PHP
	class MusicAlbums {
		/*
			Добавляем альбом
		*/
		public function Add($_ARRAY = []) {
			try {
				$sth = pdo()->prepare('INSERT INTO `music__albums`(`name`, `artistids`, `image`, `date_create`) VALUES (:name, :artistids, :image, :date_create)');
				$sth->execute([
					':name' => $_ARRAY['name'],
					':artistids' => json_encode($_ARRAY['artistids']),
					':image' => $_ARRAY['image'],
					':date_create' => time()
				]);
				
				return pdo()->lastInsertId();
			}
			catch(Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		/*
			Удаляем альбом
		*/
		public function Remove($albumid) {
			try {
				$sth = pdo()->prepare('DELETE FROM `music__albums` WHERE `id`=:albumid LIMIT 1');
				$sth->execute([
					':albumid' => $albumid
				]);
				
				return true;
			}
			catch(Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
	}