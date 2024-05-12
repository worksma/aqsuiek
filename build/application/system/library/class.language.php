<?PHP
	class Language {
		private $languages;
		private $currentLanguage;

		public function __construct() {
			$this->languages = [];
			$this->currentLanguage = 'ru';
		}
		
		public function loadLanguageFromFile($langCode, $filePath) {
			$json = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/application/system/languages/' . $filePath);
			$texts = json_decode($json, true);
			
			if ($texts !== null) {
				$this->languages[$langCode] = $texts;
			}
			else {
				die('Ошибка загрузки ' . $langCode . ' языка!');
			}
		}
		
		public function setLanguage($langCode) {
			if (array_key_exists($langCode, $this->languages)) {
				$this->currentLanguage = $langCode;
			} else {
				die('Язык не поддерживается');
			}
		}
		
		public function getText($key, $placeholders = []) {
			if (isset($this->languages[$this->currentLanguage][$key])) {
				$text = $this->languages[$this->currentLanguage][$key];
				return vsprintf($text, $placeholders);
			} else {
				return "Перевод недоступен для ключа: $key";
			}
		}
	}