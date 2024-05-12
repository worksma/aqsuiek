<?PHP
	class Bootstrap {
		const CORE_NAMESPACE = 'SocialNetwork';
		private $namespaces = [];
		
		public function Register() {
			spl_autoload_register(
				[
					$this, 'Loader'
				],
				true,
				true
			);
		}
		
		public function Loader($className) {
			$classNameParts = explode('\\', $className);
			
			if (stripos($className, '\\') !== false) {
				$className = array_pop($classNameParts);
				$namespace = implode('\\', $classNameParts);
			}
			else {
				$namespace = self::CORE_NAMESPACE;
			}

			if(array_key_exists($namespace, $this->namespaces)) {
				$directories = $this->namespaces[$namespace];

				$classFileName = 'class.' . strtolower($className) . '.php';

				foreach($directories as $directory) {
					if(file_exists($directory . $classFileName)) {
						require_once $directory . $classFileName;
						return true;
					}
				}

				throw new Exception(
					"Class $className not found!"
				);
			}
		}
		
		public function addNamespace($namespace, $directories) {
			if(is_array($directories)) {
				$this->namespaces[
					$namespace
				] = $directories;
			}
			else {
				$this->namespaces[
					$namespace
				] = [$directories];
			}
		}
	}