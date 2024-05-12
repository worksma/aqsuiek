<?PHP
	class Country {
		public function Get($id) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `geo__country` WHERE `id`=:id LIMIT 1');
				$sth->execute([':id' => $id]);
				
				if(!$sth->rowCount()) {
					return null;
				}
				
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Country]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public static function City($id) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `geo__city` WHERE `id`=:id LIMIT 1');
				$sth->execute([':id' => $id]);
				
				if(!$sth->rowCount()) {
					return null;
				}
				
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Country]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public static function GetList($MyCountry = null) {
			try {
				$sth = pdo()->query('SELECT * FROM `geo__country` WHERE 1');
				
				if(!$sth->rowCount()) {
					return '<option selected disabled>' . getLang('country_clear_base') . '</option>';
				}
				
				if(isset($MyCountry)) {
					$List = '<option disabled>' . getLang('country_clear_base') . '</option>';
				}
				else {
					$List = '<option selected disabled>' . getLang('country_select') . '</option>';
				}
				
				
				while($Country = $sth->fetch(PDO::FETCH_OBJ)) {
					$List .= '<option value=\'' . $Country->id . '\' ' . ((isset($MyCountry) && $MyCountry == $Country->id) ? 'selected' : '') . '>' . $Country->name . '</option>';
				}
				
				return $List;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Country]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return '<option selected disabled>' . getLang('country_clear_base') . '</option>';
		}
		
		public static function GetCityList($countryid, $MyCity = null) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `geo__city` WHERE `countryid`=:countryid');
				$sth->execute([':countryid' => $countryid]);
				
				if(!$sth->rowCount()) {
					return '<option selected disabled>' . getLang('country_clear_base') . '</option>';
				}
				
				if(empty($MyCity)) {
					$List = '<option selected disabled>' . getLang('country_select_city') . '</option>';
				}
				else {
					$List = '<option disabled>' . getLang('country_select_city') . '</option>';
				}
				
				while($City = $sth->fetch(PDO::FETCH_OBJ)) {
					$List .= '<option value=\'' . $City->id . '\' ' . ((isset($MyCity) && $MyCity == $City->id) ? 'selected' : '') . '>' . $City->name . '</option>';
				}
				
				return $List;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Country]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return '<option selected disabled>' . getLang('country_clear_base') . '</option>';
		}
		
		public static function IsValid($Country = null, $City = null) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `geo__country` WHERE `id`=:id LIMIT 1');
				$sth->execute([':id' => $Country]);
				
				if(!$sth->rowCount()) {
					return false;
				}
				
				$sth = pdo()->prepare('SELECT * FROM `geo__city` WHERE `id`=:id LIMIT 1');
				$sth->execute([':id' => $City]);
				
				if(!$sth->rowCount()) {
					return false;
				}
				
				if($sth->fetch(PDO::FETCH_OBJ)->countryid != $Country) {
					return false;
				}
				
				return true;
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Country]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return false;
		}
	}