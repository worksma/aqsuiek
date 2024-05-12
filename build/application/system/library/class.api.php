<?PHP
	class Api {
		/*
			Поиск токена Блога
		*/
		public function isBlogFromToken($token) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `blog` WHERE `token`=:token LIMIT 1');
				$sth->execute([
					':token' => $token
				]);
				
				if(!$sth->rowCount()) {
					return false;
				}
				
				return true;
			}
			catch(Exception $e) {
				return false;
			}
		}
		
		/*
			Получаем блог по Токену
		*/
		public function getBlogFromToken($token) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `blog` WHERE `token`=:token LIMIT 1');
				$sth->execute([
					':token' => $token
				]);
				
				if(!$sth->rowCount()) {
					return null;
				}
				
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(Exception $e) {
				return null;
			}
		}
	}