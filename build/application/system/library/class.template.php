<?PHP
	class Template {
		var
		$Template,
		$PageName,
		$Path,
		$Scripts = '',
		$Styles = '',
		$Temp = '';
		
		private
		$cacheTemp,
		$Appearance,
		$Cells = [],
		$Replace = [];
		
		protected $_DataPreg = [
			'/{\*.*?\*}/is' => '',
			'/{ ?if ?\( ?([^;<].[^;<]{1,250}?) ?\) ?}/' => '<?if(${1}):?>',
			'/{ ?else ?}/' => '<?else:?>',
			'/{ ?elseif ?\( ?([^;<].[^;<]{1,250}?) ?\) ?}/' => '<?elseif(${1}):?>',
			'/{ ?for ?\( ?([^;<].[^;<]{1,50}?) ?; ?([^;].[^;]{1,50}?) ?; ?([^;<].[^;<]{1,50}?) ?\) ?}/' => '<?for(${1}; ${2}; ${3}):?>',
			'/{ ?while ?\( ?([^;<].[^;<]{1,250}?) ?\) ?}/' => '<?while(${1}):?>',
			'/{ ?foreach ?\( ?([^;<].[^;<]{1,250}?) ?\) ?}/' => '<?foreach(${1}):?>',
			'/{ ?\/(for|if|while|foreach) ?}/' => '<?end${1};?>',
			'/{{ ?([a-zA-Z0-9>,\(\)_\-\]\[\'"$]{1,50}) ?}}/' => '<?=${1};?>',
			'/{ ?echo ?([^;<].[^;<]{1,250}?) ?}/' => '<?=(${1});?>',
			'/{ ?grab ?([^;<].[^;<]{1,250}?) ?}/' => '<?=$this->grabCache(${1});?>',
			'/{ ?func ([a-zA-Z0-9_]{1,30}):([a-zA-Z0-9_]{1,50})\( ?([^;<].[^;<]{0,150}?)? ?\) ?}/' => '<?php if (class_exists("${1}")) { $CE = new ${1}($pdo, $tpl); if(method_exists($CE, "${2}")) { echo $CE->${2}(${3}); } unset($CE); } ?>'
		];
		
		public function grabCache($cacheGrab) {
			if(!file_exists($this->Path . $cacheGrab)) {
				return 'Не найден файл ' . $this->Path . '/' . $cacheGrab;
			}
			
			$cacheFileName = $this->getCacheFileName($this->replaceSymbols($cacheGrab), 'grabs');
			
			if(file_exists($cacheFileName) && !$this->isCacheExpired($cacheFileName)) {
				return $this->Execute(file_get_contents($cacheFileName));
			}
			
			$Temp = file_get_contents($this->Path . $cacheGrab);
			$this->saveToCache($Temp, $cacheFileName);
			
			return $this->Execute($Temp);
		}
		
		public function __construct($Appearance = null) {
			foreach($GLOBALS as $key => $value){
				global $$key;
			}
			
			if(empty($Appearance)) {
				$this->Appearance = conf()->appearance;
			}
			else {
				$this->Appearance = $Appearance;
			}
			
			$this->cacheTemp = $this->Appearance;
			$this->Path = $_SERVER['DOCUMENT_ROOT'] . '/application/appearance/' . $this->Appearance;
			
			if(isset($_PAGE['name'])) {
				$this->PageName = $_PAGE['name'];
			}
			else {
				$this->PageName = conf()->site_name;
			}
		}
		
		public function SetAppearance($Appearance = null) {
			if(empty($Appearance)) {
				$this->Appearance = conf()->appearance;
			}
			else {
				$this->Appearance = $Appearance;
			}
			
			$this->cacheTemp = $this->Appearance;
			$this->Path = $_SERVER['DOCUMENT_ROOT'] . '/application/appearance/' . $this->Appearance;
			return $this;
		}
		
		public function AddScripts($_File) {
			$this->Scripts .= '<script src=\'' . $_File . '\'></script>';
			return $this;
		}
		
		public function AddCss($_File) {
			$this->Styles .= '<link rel=\'stylesheet\' href=\'' . $_File . '\'>';
			return $this;
		}
		
		public function Start($Sample = 'sample') {
			$Path = $this->Path . '/' . $Sample . '.tpl';
			
			if(!file_exists($Path)) {
				die($Path);
			}
			
			$cacheFileName = $this->getCacheFileName($this->replaceSymbols($Sample), 'samples');
			
			if(file_exists($cacheFileName) && !$this->isCacheExpired($cacheFileName)) {
				$this->Temp = file_get_contents($Path);
			}
			
			$this->saveToCache($this->Temp, $cacheFileName);
			$this->Temp = file_get_contents($Path);
			
			return $this;
		}
		
		public function Set($_ATTR = [], $_Temp = null) {
			$Temp = isset($_Temp) ? $_Temp : $this->Temp;
			
			foreach($_ATTR as $Search => $Replace) {
				$Temp = str_replace(isset($Search) ? $Search : '', isset($Replace) ? $Replace : '', isset($Temp) ? $Temp : '');
			}
			
			if(isset($_Temp)) {
				return $Temp;
			}
			
			$this->Temp = $Temp;
			return $this;
		}
		
		public function Content($Temp) {
			$this->Set([
				'{content}' => $Temp
			]);
			
			return $this;
		}
		
		public function AddReplace($_ARRAY = []) {
			foreach($_ARRAY as $Key => $Value) {
				$this->Replace += [
					$Key => $Value
				];
			}
			
			return $this;
		}
		
		public function GetReplaces() {
			return $this->Replace;
		}
		
		public function GetAppearance() {
			return $this->Appearance;
		}
		
		public function Execute($Temp) {
			foreach($GLOBALS as $Key => $Value){
				global $$Key;
			}
			
			if(file_exists(	__DIR__ . '/../autochange.php')) {
				require_once(
					__DIR__ . '/../autochange.php'
				);
			}
			
			$this->AddReplace([
				'{cache}' => conf()->cache,
				'{scripts}' => $this->Scripts,
				'{styles}' => $this->Styles,
				'{appearance}' => $this->Appearance,
				'{site_name}' => conf()->site_name,
				'{csrf_token}' => csrf()->Get()
			])->MetaTags();
			
			foreach($this->Replace as $Key => $Value) {
				$Temp = str_replace(
					isset($Key) ? $Key : '',
					isset($Value) ? $Value : '',
					isset($Temp) ? $Temp : ''
				);
			}
			
			foreach($this->_DataPreg as $Key => $Value) {
				$Temp = preg_replace(
					isset($Key) ? $Key : '',
					isset($Value) ? $Value : '',
					isset($Temp) ? $Temp : ''
				);
			}
			
			ob_start();
			
			try {
				eval('?>' . $Temp . '<?');
			}
			catch(Exception $e) {
				AddLogs('templates/' . date('Y-m-d') . '.log', print_r(['Error' => $p,'Temp' => $Temp], true));
			}
			catch(ParseError $p) {
				AddLogs('templates/' . date('Y-m-d') . '.log', print_r(['Error' => $p,'Temp' => $Temp], true));
			}
			
			return ob_get_clean();
		}
		
		public function MetaTags() {
			global $_PAGE;
			
			if(!$this->IsReplace('{meta_name}')) {
				$this->AddReplace([
					'{meta_name}' => conf()->site_name
				]);
			}
			
			if(!$this->IsReplace('{meta_description}')) {
				$this->AddReplace([
					'{meta_description}' => isset($_PAGE['description']) ? $_PAGE['description'] : conf()->description
				]);
			}
			
			if(!$this->IsReplace('{meta_keywords}')) {
				$this->AddReplace([
					'{meta_keywords}' => isset($_PAGE['keywords']) ? $_PAGE['keywords'] : conf()->keywords
				]);
			}
			
			if(!$this->IsReplace('{meta_image}')) {
				$this->AddReplace([
					'{meta_image}' => isset($_PAGE['image']) ? $_PAGE['image'] : '/public/images/system/meta.jpg?v=' . conf()->cache
				]);
			}
		}
		
		public function SetCell($_ATTR = []) {
			foreach($_ATTR as $_NAME => $_TEMP) {
				$this->Cells[$_NAME] = $_TEMP;
			}
			
			return $this;
		}
		
		public function GetCell($_NAME) {
			if($this->IsCell($_NAME)) {
				return $this->Cells[$_NAME];
			}
			
			return '';
		}
		
		public function AddCell($_NAME, $_TEMP) {
			if($this->IsCell($_NAME)) {
				$this->Cells[$_NAME] .= $_TEMP;
			}
			else {
				$this->SetCell([
					$_NAME => $_TEMP
				]);
			}
			
			return $this;
		}
		
		public function IsCell($_NAME) {
			foreach($this->Cells as $_KEY => $_VALUE) {
				if($_NAME == $_KEY) {
					return true;
				}
			}
			
			return false;
		}
		
		public function ClearCell($_NAME) {
			if($this->IsCell($_NAME)) {
				unset($this->Cells[$_NAME]);
			}
			
			return $this;
		}
		
		public function ClearCells() {
			foreach($this->Cells as $_KEY => $_VALUE) {
				unset($this->Cells[$_KEY]);
			}
			
			return $this;
		}
		
		public function Clear() {
			$this->Temp = '';
			
			return $this;
		}
		
		public function Get($_NAME) {
			$Path = $this->Path . '/' . $_NAME . '.tpl';
			
			if(!file_exists($Path)) {
				return 'Файл ' . $Path . ' не найден!';
			}
			
			$cacheFileName = $this->getCacheFileName($this->replaceSymbols($_NAME), 'gets');
			
			if(file_exists($cacheFileName) && !$this->isCacheExpired($cacheFileName)) {
				return file_get_contents($cacheFileName);
			}
			
			$Temp = file_get_contents($Path);
			$this->saveToCache($Temp, $cacheFileName);
			
			return $Temp;
		}
		
		public function IsReplace($_NAME) {
			foreach($this->Replace as $_KEY => $_VALUE) {
				if($_NAME == $_KEY) {
					return true;
				}
			}
			
			return false;
		}
		
		public function SetTitle($_NAME) {
			if($this->IsReplace('{title}')) {
				foreach($this->Replace as $_KEY => $_VALUE) {
					if($_KEY == '{title}') {
						unset(
							$this->Replace[$_KEY]
						);
					}
				}
			}
			
			$this->AddReplace([
				'{title}' => $_NAME
			]);
			
			return $this;
		}
		
		public function Show() {
			if(!$this->IsReplace('{title}')) {
				global $_PAGE;
				
				$this->AddReplace([
					'{title}' => isset($_PAGE['name']) ? ($_PAGE['name'] . ' - ' . $this->PageName) : $this->PageName
				]);
			}
			
			die($this->Execute($this->Temp));
		}
		
		public function getTemp() {
			return $this->Temp;
		}
		
		/*
			Получаем имя кэша
		*/
		private function getCacheFileName($templateName, $templateCore = 'others') {
			$cachePath = $_SERVER['DOCUMENT_ROOT'] . '/application/system/cache/' . $this->Appearance;
			
			if(!is_dir($cachePath)) {
				mkdir($cachePath);
			}
			
			$cachePath = $_SERVER['DOCUMENT_ROOT'] . '/application/system/cache/' . $this->Appearance . '/' . $templateCore;
			
			if(!is_dir($cachePath)) {
				mkdir($cachePath);
			}
			
			$this->clearExpiredCacheFiles($cachePath);
			$Path = $cachePath . '/' . $templateName . '.cache';
			
			return $Path;
		}
		
		/*
			Проверяем кэш
		*/
		private function isCacheExpired($cacheFileName) {
			$expirationTime = time() - 3600;
			return filemtime($cacheFileName) < $expirationTime;
		}
		
		/*
			Сохраняем кэш
		*/
		private function saveToCache($content, $cacheFileName) {
			if(!file_exists($cacheFileName)) {
				file_put_contents($cacheFileName, $this->compressHtml($content));
			}
		}
		
		/*
			Чистим старый кэш
		*/
		public function clearExpiredCacheFiles($cacheDirectory) {
			$cacheFiles = glob($cacheDirectory . '/*.cache');
			
			foreach($cacheFiles as $cacheFile) {
				if($this->isCacheExpired($cacheFile)) {
					unlink($cacheFile);
				}
			}
		}
		
		/*
			Компрессия кода
		*/
		public function compressHtml($html) {
			$html = preg_replace('/<!--.*?-->/', '', $html);
			$html = preg_replace('/\s+/', ' ', $html);
			
			return $html;
		}
		
		/*
			Изменение имени
		*/
		public function replaceSymbols($string) {
			$symbols = [
				'/', '\\', '.'
			];
			
			return str_replace($symbols, '_', $string);
		}
	}