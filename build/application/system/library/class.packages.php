<?PHP
	class Packages {
		var
		$tmp_root,
		$tmp_sample;
		
		public function __construct() {
			$this->tmp_root = $_SERVER['DOCUMENT_ROOT'] . '/application/system/packages/';
			$this->tmp_root_sample = $_SERVER['DOCUMENT_ROOT'] . '/application/system/packages/sample/';
			$this->tmp_sample = '';
		}
		
		public function Load($_ARRAY = []) {
			$Path = '';
			$Array = [];
			
			foreach($_ARRAY as $_KEY => $_VALUE) {
				$Path = $this->tmp_root . $_VALUE . '.package';
				
				if(file_exists($Path)) {
					$Array[] = $this->Sample('array')->Set(file_get_contents($Path))->Get();
				}
			}
			
			return $Array;
		}

		/*
			Sample
		*/
		public function Sample($_Name) {
			$path = $this->tmp_root_sample . $_Name . '.package';
			
			if(file_exists($path)) {
				$this->tmp_sample = file_get_contents(
					$path
				);
			}
			
			return $this;
		}
		
		public function Set($_Code) {
			if(isset($this->tmp_sample) && $this->tmp_sample) {
				$this->tmp_sample = str_replace(
					'{{ code }}',
					$_Code,
					$this->tmp_sample
				);
			}
			
			return $this;
		}
		
		public function Get() {
			ob_start();
			eval(
				$this->tmp_sample
			);
			ob_clean();
			
			return $_PackageTemp;
		}
	}