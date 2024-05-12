<?PHP
	class CSRF {
		/*
			Создаем Токен
		*/
		public function __construct() {
			if(empty($_SESSION['csrf_token'])) {
				$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
			}
		}
		
		/*
			Получаем Токен
		*/
		public function Get() {
			if(isset($_SESSION['csrf_token'])) {
				return $_SESSION['csrf_token'];
			}
			
			return null;
		}
		
		/*
			Проверяем наличие Токена
		*/
		public function Is($token) {
			return isset($_SESSION['csrf_token']) && is_string($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
		}
		
		/*
			Очищаем токен
		*/
		public function Clear() {
			if(isset($_SESSION['csrf_token'])) {
				unset($_SESSION['csrf_token']);
			}
		}
	}