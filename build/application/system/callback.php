<?PHP
	class CallBack {
		static public $Calls = [];
		
		static function Register() {
			spl_autoload_register(function() {
				$folders = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../callbacks/'));
				
				foreach($folders as $file) {
					if(!$file->isDir()) {
						require_once($file->getPathname());
					}
				}
			});
		}
		
		static function Add($_ACTION, Closure $_DATA) {
			self::$Calls[] = [
				$_ACTION => $_DATA
			];
		}
		
		static function Call($_NAME, $_ARGS = []) {
			foreach(self::$Calls as $_CALL) {
				if(array_key_exists($_NAME, $_CALL)) {
					if(!is_callable($_CALL[$_NAME])) {
						throw new Exception('Функция обратного вызова - не вызываемая!');
					}
					
					call_user_func($_CALL[$_NAME], $_ARGS);
				}
			}
		}
		
		static function GetList() {
			return self::$Calls;
		}
	}
	
	CallBack::Register();