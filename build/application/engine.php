<?PHP
	require(
		'start.php'
	);
	
	/*
		Авторизованный юзер, действия
	*/
	if(isset($_SESSION['id'])) {
		$user = Users()->Get($_SESSION['id']);
		
		if(empty($_SESSION['NextSetOnline'])) {
			Users()->SetOnline(
				$_SESSION['id']
			);
		}
		else {
			if((strtotime($_SESSION['NextSetOnline'])) < time()) {
				Users()->SetOnline(
					$_SESSION['id']
				);
			}
		}
		
		if($user->rights == '3') {
			ShowPage('ProfileInfo', tpl()->Get('elements/account/freezy'), 'Профиль заблокирован', 'sample');
		}
	}
	
	/*
		Роутинг
	*/
	class cae314fe1e548f4dc56c04b6b864298 {
		private
		$Packages = [],
		$Routes = [];
		
		public function __construct() {
			$this->ReadPackages()->ReadMysql()->Execute(
				$_SERVER['REQUEST_URI']
			);
		}
		
		public function ReadPackages() {
			$this->Packages = pkg()->Load([
				'pages', 'panel', 'api'
			]);
			
			foreach($this->Packages as $Key => $Value) {
				foreach($Value as $_KEY => $_VALUE) {
					$this->Routes['/^' . str_replace('/', '\/', $_VALUE['uri']) . '$/'] = [
						$_KEY => $Key
					];
				}
			}
			
			return $this;
		}
		
		public function ReadMysql() {
			try {
				$sth = pdo()->query(
					'SELECT * FROM `pages` WHERE `enable`=\'1\''
				);
				
				if(!$sth->rowCount()) {
					return $this;
				}
				
				while($page = $sth->fetch(PDO::FETCH_OBJ)) {
					$this->Routes['/^' . str_replace('/', '\/', $page->uri) . '$/'] = $page->id;
				}
				
				return $this;
			}
			catch(Exception $e) {
				return $this;
			}
			
			return $this;
		}
		
		public function Get($Page) {
			if(is_array($Page) || is_object($Page)) {
				foreach($Page as $_KEY => $_VALUE) {
					return $this->Packages[$_VALUE][$_KEY];
				}
			}
			
			try {
				$sth = pdo()->prepare(
					'SELECT * FROM `pages` WHERE `id`=:id LIMIT 1'
				);
				
				$sth->execute([
					':id' => $Page
				]);
				
				if($sth->rowCount()) {
					return $sth->fetch(PDO::FETCH_ASSOC);
				}
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', $e->getMessage());
			}
		}
		
		public function Execute($Uri) {
			foreach($this->Routes as $Pattern => $Page) {
				if(preg_match($Pattern, $Uri, $Params)) {
					global $_PAGE;
					
					$_PAGE = $this->Get(
						$Page
					);
					
					$_PAGE += [
						'params' => $Params,
						'url' => $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
					];
					
					if(!file_exists('application/routing/' . $_PAGE['module'] . '.php')) {
						ShowPage('error', 'Не найден модуль: application/routing/' . $_PAGE['module'] . '.php');
					}
					
					array_shift($Params);
					return require(
						'application/routing/' . $_PAGE['module'] . '.php'
					);
				}
			}
			
			ShowPage('error', tpl()->Get('errors/404'));
		}
	}
	
	new cae314fe1e548f4dc56c04b6b864298;