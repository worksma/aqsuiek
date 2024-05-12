<?PHP
	function CreatePDO($base) {
		if(empty($base)) {
			return false;
		}
		
		try {
			$pdo = new PDO(
				'mysql:host=' . $base['hostname'] . ';dbname=' . $base['dataname'],
				$base['username'],
				$base['password']
			);
			
			$pdo->setAttribute(
				PDO::ATTR_ERRMODE,
				PDO::ERRMODE_EXCEPTION
			);
			
			$pdo->exec(
				'SET NAMES UTF8'
			);
			
			return $pdo;
		}
		catch(PDOException $e) {
			die(
				'Error: ' . $e->getMessage()
			);
		}
	}
	
	function pdo() {
		global $pdo;
		
		if(empty($pdo)) {
			return null;
		}
		
		return $pdo;
	}
	
	function conf() {
		global $conf;
		
		if(empty($conf)) {
			return null;
		}
		
		return $conf;
	}
	
	function pkg() {
		global $pkg;
		
		if(empty($pkg)) {
			return new Packages;
		}
		
		return $pkg;
	}
	
	function Users() {
		global $usr;
		
		if(empty($usr)) {
			return new Users;
		}
		
		return $usr;
	}
	
	function Chat() {
		global $ClassChat;
		
		if(empty($ClassChat)) {
			return new Chat;
		}
		
		return $ClassChat;
	}
	
	function Memcached() {
		global $memcached;
		
		if(empty($memcached)) {
			$memcached = new Memcached();
			$memcached->addServer('127.0.0.1', 11211);
		}
		
		return $memcached;
	}
	
	function tpl() {
		global $tpl;
		
		if(empty($tpl)) {
			return null;
		}
		
		return $tpl;
	}
	
	function ShowPage($Page, $Content, $Title = 'Ошибка', $Sample = 'custom') {
		$tpl = new Template;
		
		$tpl->SetCell([
			$Page => $Content
		]);
		
		$tpl
		->Start($Sample)
		->Content(
			$tpl->GetCell(
				$Page
			)
		)
		->SetTitle(
			$Title
		)
		->Show();
	}
	
	function RandomString($Len) {
		return substr(
			str_shuffle(
				'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
			), 0, $Len
		);
	}
	
	function Result($_ARRAY = []) {
		die(
			json_encode(
				$_ARRAY
			)
		);
	}
	
	function AlertSuccess($_MESSAGE = '') {
		Result([
			'Alert' => 'Success',
			'Message' => $_MESSAGE
		]);
	}
	
	function AlertError($_MESSAGE = '') {
		Result([
			'Alert' => 'Error',
			'Message' => $_MESSAGE
		]);
	}
	
	function AlertWarning($_MESSAGE = '') {
		Result([
			'Alert' => 'Warning',
			'Message' => $_MESSAGE
		]);
	}
	
	function AlertInfo($_MESSAGE = '') {
		Result([
			'Alert' => 'Info',
			'Message' => $_MESSAGE
		]);
	}
	
	function IsValidActions() {
		if(empty($_POST['phpaction'])) {
			AlertError(getLang('sys_phpaction'));
		}
		
		if(!csrf()->Is($_POST['csrf_token'])) {
			AlertError(getLang('sys_csrf_token'));
		}
	}
	
	/*
		CSRF защита
	*/
	function csrf() {
		global $csrf;
		
		if(isset($csrf)) {
			return $csrf;
		}
		
		return new CSRF;
	}
	
	function Redirect($uri = '/', $time = 300, $js = true) {
		if($js) {
			die('<script>setTimeout(\'location.href = "' . $uri . '";\', ' . $time . ');</script>');
		}
		else {
			return header('Location: ' . $uri);
		}
	}
	
	function GetUserName($id, $args = []) {
		if(empty($id)) {
			return getLang('sys_unknown');
		}
		
		$user = Users()->Get($id);
		
		if(empty($user)) {
			return getLang('sys_unknown');
		}
		
		$StyleRights = Users()->GetStyleRights(
			$user->rights
		);
		
		$name = '';
		
		if(isset($args['link'])) {
			$name .= '<a href=\'/id' . $id . '\'>';
		}
		
		$name .= '<font style=\'color: rgb(' . $StyleRights->rgba . '); ' . $StyleRights->style . '\'>';
		$name .= $user->first_name;
		
		if(isset($args['full'])) {
			$name .= ' ' . $user->last_name;
		}
		else {
			if(isset($user->last_name)) {
				$name .= ' ' . mb_substr($user->last_name, 0, 1) . '.';
			}
		}
		
		$name .= '</font>';
		
		if(isset($args['link'])) {
			$name .= '</a>';
		}
		
		if($StyleRights->rights != 0) {
			if(isset($args['very']) && $user->very) {
				$name .= ' <i class="bi bi-check text-primary" data-bs-toggle="popover" data-bs-placement="top"
				data-bs-custom-class="very"
				data-bs-trigger="hover focus"
				data-bs-title="Верифицированная страница"
				data-bs-html="true"
				data-bs-content="Страница подтверждена командой <a href=\'/\'>AQSUIEK</a>. Он действительно принадлежит известной личности, проекту или бренду."></i>';
			}
		
			if(empty($args['block_icons'])) {
				if(Users()->IsBeta($user->id)) {
					$Beta = Users()->GetBeta($user->id);
					
					$name .= ' <i class="bi bi-code-slash text-primary" data-bs-toggle="popover" data-bs-placement="top"
					data-bs-custom-class="beta"
					data-bs-trigger="hover focus"
					data-bs-title="Бета тестер"
					data-bs-html="true"
					data-bs-content="Участник Бета-тестирования с ' . date('Y', strtotime($Beta->date)) . ' года"></i>';
				}
			}
		}
		
		return $name;
	}
	
	function IsUserOnline($id) {
		if(!Users()->IsValid($id)) {
			return false;
		}
		
		$_Online = strtotime(Users()->Get($id)->last_online, false) + strtotime('+15 minutes',false);
		
		if($_Online > time()) {
			return true;
		}
		
		return false;
	}
	
	function DayToTime($_Date) {
		$_Time_1 = strtotime(
			$_Date
		);
		
		$_Time_2 = time();
		
		$Time = ($_Time_2 - $_Time_1) - 10800;
		
		$_Time = [
			'h' => date('H', $Time), 'i' => date('i', $Time), 's' => date('s', $Time),
			'd' => date('d', $Time), 'm' => date('m', $Time), 'y' => date('Y', $Time)
		];
		
		if($_Time['d'] > 01) {
			return GetLastDay($_Date);
		}
		
		if($_Time['h'] > '00' && $_Time['h'] < 24) {
			return getLang('sys_time_4', ltrim($_Time['h'], '0'));
		}
		
		if($_Time['i'] > '00' && $_Time['i'] < 60) {
			return getLang('sys_time_5', ltrim($_Time['i'], '0'));
		}
		
		if($_Time['s'] > '00' && $_Time['s'] < 60) {
			return getLang('sys_time_6', ltrim($_Time['s'], '0'));
		}
		else if($_Time['s'] == '00') {
			return getLang('sys_time_7');
		}
		
		return GetLastDay($_Date);
	}
	
	function GetLastDay($_Date) {
		$_Count = round((time() - strtotime($_Date)) / (60 * 60 * 24));
		
		switch($_Count) {
			case 0: {
				return getLang('sys_time_0', [date('H:i', strtotime($_Date))]);
				break;
			}
			case 1: {
				return getLang('sys_time_1', [date('H:i', strtotime($_Date))]);
				break;
			}
			case 2: {
				return getLang('sys_time_2', [date('H:i', strtotime($_Date))]);
				break;
			}
		}
		
		return getLang('sys_time_3', [$_Count]);
	}
	
	function Clean($_Text = null, $_Params = null) {
		if(empty($_Text)) {
			return $_Text;
		}
		
		$_Text = stripslashes(
			$_Text
		);
		
		$_Text = htmlspecialchars(
			$_Text, ENT_QUOTES
		);
		
		$_Text = trim(
			$_Text
		);
		
		switch($_Params) {
			case 'int': {
				$_Text = preg_replace("/[^0-9]+/", "", $_Text);
				
				break;
			}
			
			case 'float': {
				$_Text = str_replace(",", ".", $_Text);
				$_Text = preg_replace("/[^0-9.]/", "", $_Text);
				$_Text = (float)$_Text;
				$_Text = round($_Text, 2);
				
				break;
			}
			
			default: {
				$_Text = preg_replace('/{{ ?([a-zA-Z0-9>,\(\)_\-\]\[\'"$]{1,50}) ?}}/', '${1}', $_Text);
				$_Text = str_replace('{', '&#123;', $_Text);
				$_Text = str_replace('}', '&#125;', $_Text);
				
				break;
			}
		}
		
		return $_Text;
	}
	
	function AddLogs($File, $Message) {
		return file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/application/logs/' . $File, $Message . "\n", FILE_APPEND);
	}
	
	function GetUserAvatar($id) {
		if(!Users()->IsValid($id)) {
			return '/public/assets/' . tpl()->GetAppearance() . '/img/no_avatar.jpg';
		}
		
		$User = Users()->Get($id);
		
		if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/images/avatars/' . $User->image)) {
			return '/public/images/avatars/' . $User->image;
		}
		
		return '/public/assets/' . tpl()->GetAppearance() . '/img/no_avatar.jpg';
	}
	
	function IsRights($userid, $right = 1) {
		if(!Users()->IsValid($userid)) {
			return false;
		}
		
		if(Users()->Get($userid)->rights == $right) {
			return true;
		}
		
		return false;
	}
	
	function SendPost($site, $PostFields) {
		$ch = curl_init($site);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $PostFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}
	
	function ValidLenText($Text = null) {
		if(empty($Text)) {
			return false;
		}
		
		$Text = htmlspecialchars($Text);
		$Text = trim($Text);
		
		if(!strlen($Text)) {
			return false;
		}
		
		return true;
	}
	
	function Reduction($text, $length = 70) {
		if (mb_strlen($text, 'UTF-8') > $length) {
			$substr = mb_substr($text, 0, $length, 'UTF-8');

			$text = strpos($substr, ' ') !== false 
				? preg_replace('~(\s)?(?(1)\S+$|\s$)~', '', $substr) 
				: strstr($text, ' ', true);

			$text .= ' ... ';
		}

		return $text;
	}
	
	function IsTypeImage($Expansion) {
		$Allowed = ['image/png', 'image/gif', 'image/jpeg', 'image/svg+xml'];
		$Validation = false;
		
		for($i = 0; $i < sizeof($Allowed); $i++) {
			if($Expansion == $Allowed[$i]) {
				$Validation = true;
				break;
			}
		}
		
		return $Validation;
	}
	
	function GetNameString($File = null, $Main = true, $Type = '') {
		if(empty($File)) {
			return RandomString(12);
		}
		
		$Allowed = [
			'image/png', 'image/gif', 'image/jpeg', 'image/svg+xml',
			'text/css', 'text/plain',
			'application/zip', 'application/x-rar-compressed', 'application/pdf',
			'audio/mpeg'
		];
		
		$Validation = false;
		
		for($i = 0; $i < sizeof($Allowed); $i++) {
			if(!$Main) {
				if($Type == $Allowed[$i]) {
					$Validation = true;
					break;
				}
			}
			else {
				if($File['type'] == $Allowed[$i]) {
					$Validation = true;
					break;
				}
			}
		}
		
		if(!$Validation) {
			return RandomString(12);
		}
		
		$Pathinfo = pathinfo($Main ? $File['name'] : $File, PATHINFO_EXTENSION);
		
		if($Pathinfo == 'php') {
			$Pathinfo = 'txt';
		}
		
		return RandomString(12) . ".$Pathinfo";
	}
	
	function FileVolume($bytes) {
		if($bytes >= 1073741824){
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		}
		elseif($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		}
		elseif($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		}
		elseif($bytes > 1) {
			$bytes = $bytes . ' bytes';
		}
		elseif($bytes == 1) {
			$bytes = $bytes . ' byte';
		}
		else {
			$bytes = '0 bytes';
		}

		return $bytes;
	}
	
	function GetFileVolume($size = 0, $type = "MB") {
		switch($type):
			case "KB":
				return $size * 1024; break;
			case "MB":
				return $size * 1048576; break;
			case "GB":
				return $size * 1073741824; break;
			case "TB":
				return $size * 1099511627776; break;
		endswitch;
		
		return 0;
	}
	
	function IsExtension($Extension, $Allowed = []) {
		$Validation = false;
		
		for($i = 0; $i < sizeof($Allowed); $i++) {
			if($Extension == $Allowed[$i]) {
				$Validation = true;
				break;
			}
		}
		
		return $Validation;
	}
	
	function GetZodiac($date) {
		$day = date('d', strtotime($date));
		$month = date('m', strtotime($date));
		
		$signs = [
			getLang('zodiac_01_title'), getLang('zodiac_02_title'), getLang('zodiac_03_title'), getLang('zodiac_04_title'), getLang('zodiac_05_title'), getLang('zodiac_06_title'),
			getLang('zodiac_07_title'), getLang('zodiac_08_title'), getLang('zodiac_09_title'), getLang('zodiac_10_title'), getLang('zodiac_11_title'), getLang('zodiac_12_title')
		];
		
		$signsstart = [
			1 => 21, 2 => 20, 3 => 20, 4 => 20, 5 => 20, 6 => 20, 7 => 21, 8 => 22, 9 => 23, 10 => 23, 11 => 23, 12 => 23
		];
		
		return $day < $signsstart[$month + 1] ? $signs[$month - 1] : $signs[$month % 12];
	}
	
	function GetZodiacInfo($name) {
		$signs = [
			getLang('zodiac_01_title') => getLang('zodiac_01_desc'),
			getLang('zodiac_02_title') => getLang('zodiac_02_desc'),
			getLang('zodiac_03_title') => getLang('zodiac_03_desc'),
			getLang('zodiac_04_title') => getLang('zodiac_04_desc'),
			getLang('zodiac_05_title') => getLang('zodiac_05_desc'),
			getLang('zodiac_06_title') => getLang('zodiac_06_desc'),
			getLang('zodiac_07_title') => getLang('zodiac_07_desc'),
			getLang('zodiac_08_title') => getLang('zodiac_08_desc'),
			getLang('zodiac_09_title') => getLang('zodiac_09_desc'),
			getLang('zodiac_10_title') => getLang('zodiac_10_desc'),
			getLang('zodiac_11_title') => getLang('zodiac_11_desc'),
			getLang('zodiac_12_title') => getLang('zodiac_12_desc')
		];
		
		return $signs[$name];
	}
	
	function GetMonth($date, $declination = 0) {
		$month = date('m', strtotime($date));
		
		$signs = [
			'01' => [getLang('month_01_1'), getLang('month_01_2')],
			'02' => [getLang('month_02_1'), getLang('month_02_2')],
			'03' => [getLang('month_03_1'), getLang('month_03_2')],
			'04' => [getLang('month_04_1'), getLang('month_04_2')],
			'05' => [getLang('month_05_1'), getLang('month_05_2')],
			'06' => [getLang('month_06_1'), getLang('month_06_2')],
			'07' => [getLang('month_07_1'), getLang('month_07_2')],
			'08' => [getLang('month_08_1'), getLang('month_08_2')],
			'09' => [getLang('month_09_1'), getLang('month_09_2')],
			'10' => [getLang('month_10_1'), getLang('month_10_2')],
			'11' => [getLang('month_11_1'), getLang('month_11_2')],
			'12' => [getLang('month_12_1'), getLang('month_12_2')]
		];
		
		return $signs[$month][$declination];
	}
	
	function Noty() {
		global $Noty;
		
		return isset($Noty) ? $Noty : new Noty;
	}
	
	function GetCityIndex($name) {
		try {
			$sth = pdo()->prepare('SELECT * FROM `geo__city` WHERE `name`=:name LIMIT 1');
			$sth->execute([
				':name' => $name
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
	
	function GetUserMoney($userid, $Format = '{balance} &#8376;') {
		$Data = Users()->Get($userid);
		
		return str_replace('{balance}', number_format((isset($Data) ? $Data->balance : 0), 0, '', ' '), $Format);
	}
	
	function GetUserGroup($userid) {
		try {
			$UserData = Users()->Get($userid);
			
			$sth = pdo()->prepare('SELECT * FROM `users__group` WHERE `id`=:id LIMIT 1');
			$sth->execute([
				':id' => $UserData->rights
			]);
			
			if($sth->rowCount()) {
				$row = $sth->fetch(PDO::FETCH_OBJ);
				return '<font style="color:rgba(' . $row->rgba . ', .7);">' . $row->name . '</font>';
			}
			
			return getLang('sys_unknown');
		}
		catch(Exception $e) {
			AddLogs('pdo.txt', "[Class Blog]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
		}
	}
	
	/*
		Языковая система
	*/
	function getLang($key, $placeholders = []) {
		global $lang;
		
		if(empty($lang)) {
			$lang = new Language;
			$lang->loadLanguageFromFile('ru', 'ru.json');
			$lang->setLanguage('ru');
		}
		
		return $lang->getText($key, $placeholders);
	}