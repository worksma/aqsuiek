<?PHP
	class Search {
		private $type, $types = [
			'people' => [
				'name' => '', 'city' => '',
				'age' => ['start' => '14', 'end' => '20']
			]
		];
		
		public function __construct($type = 'people') {
			if(empty($this->types[$type])) {
				throw new Exception(getLang('search_no_type'));
			}
			
			$this->type = $type;
		}
		
		public function Params($Params) {
			$this->types[$this->type] = $Params;
		}
		
		public function GetParams() {
			return $this->types[$this->type];
		}
		
		public function Search() {
			try {
				switch($type) {
					default: {
						$Params = $this->types[$this->type];
						$searchTerm = $Params['name'];
						
						$Name = explode(' ', $this->types[$this->type]['name']);
						$Request = 'SELECT * FROM `users` WHERE';
							
						switch(count($Name)) {
							case 1: {
								$Request .= ' (`first_name` LIKE :Name OR `last_name` LIKE :Name)';
								break;
							}
							case 2: {
								$Request .= ' (`first_name`=:first_name AND `last_name`=:last_name)';
								break;
							}
							default: {
								$Request .= '';
							}
						}
							
						if(!count($Name)) {
							if($this->types[$this->type]['city'] != '') {
								$Request .= ' AND `city`=:city';
							}
						}
						else {
							if($this->types[$this->type]['city'] != '') {
								$Request .= ' AND `city`=:city';
							}
						}
							
						$sth = pdo()->prepare($Request);
						
						if(count($Name) == 1) {
							$sth->bindValue(':Name', '%' . $this->types[$this->type]['name'] . '%', PDO::PARAM_STR);
						}
						elseif(count($Name) == 2) {
							$sth->bindParam(':first_name', $Name[0], PDO::PARAM_STR);
							$sth->bindParam(':last_name', $Name[1], PDO::PARAM_STR);
						}
							
						if($this->types[$this->type]['city'] != '') {
							$sth->bindParam(':city', $this->types[$this->type]['city'], PDO::PARAM_STR);
						}
							
						$sth->execute();
							
						if(!$sth->rowCount()) {
							return '<center>' . getLang('search_no_request') . '</center>';
						}
						
						while($User = $sth->fetch(PDO::FETCH_OBJ)) {
							tpl()->AddCell('SearchResult', tpl()->Set([
								'{id}' => $User->id,
								'{name}' => GetUserName($User->id, [
									'full' => true,
									'very' => true,
									'link' => true
								]),
								'{image}' => GetUserAvatar($User->id),
								'{city}' => Users::City($User->id)
							], tpl()->Get('elements/search/people')));
						}
					}
				}
				
				return tpl()->GetCell('SearchResult');
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Search]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return '<center>' . getLang('search_no_request') . '</center>';
		}
	}