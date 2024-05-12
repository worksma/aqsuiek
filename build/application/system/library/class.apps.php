<?PHP
	class Apps {
		protected static $_Actions = [];
		
		public static function Load() {
			$sth = pdo()->query("SELECT * FROM `apps` WHERE `enable`='1'");
			
			if(!$sth->rowCount()) {
				return false;
			}
			
			while($App = $sth->fetch(PDO::FETCH_OBJ)) {
				if(!self::IsAction($App->id)) {
					array_push(self::$_Actions, $App->id);
					
					if(isset($App->executor)) {
						$dir = $_SERVER['DOCUMENT_ROOT'] . '/application/apps/' . $App->patch . '/';
						
						if(file_exists($dir . $App->executor . '.php')) {
							require_once($dir . $App->executor . '.php');
						}
					}
				}
			}
			
			return true;
		}
		
		public static function IsAction($id) {
			foreach(self::$_Actions as $name => $index) {
				if($index == $id) {
					return true;
				}
			}
			
			return false;
		}
		
		public static function GetActions() {
			return self::$_Actions;
		}
		
		public static function IsInstall($name) {
			$name = clean($name);
			
			if(empty($name)) {
				result(['alert' => 'error', 'message' => 'Неверное наименование']);
			}
			
			$sth = pdo()->prepare("SELECT * FROM `apps` WHERE `patch`=:patch LIMIT 1");
			$sth->execute([':patch' => $name]);
			
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		
		public static function Parse($data) {
			eval('$t = ' . $data . ';');
			return (object)$t;
		}
	}