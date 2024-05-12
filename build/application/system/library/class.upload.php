<?PHP
	class Upload {
		protected $FormateImages = [
			'type' => ['image/png', 'image/gif', 'image/jpeg', 'image/svg+xml'],
			'expansion' => ['png', 'jpg', 'jpeg', 'gif', 'svg']
		];
		
		var $MinSize = 200, $MinVolume = 100;
		
		public function Image($_FILE, $Dir, $CallBack = null) {
			if(!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $Dir) || !is_dir($_SERVER['DOCUMENT_ROOT'] . '/' . $Dir)) {
				throw new Exception(getLang('upload_msg_err_dir'));
			}
			
			if(0 < $_FILE['error']) {
				throw new Exception(getLang('upload_msg_err_file', [$_FILE['error']]));
			}
			
			if(!$this->IsTypeImage($_FILE['type'])) {
				throw new Exception(getLang('upload_msg_err_formate'));
			}
			
			if(stripos(pathinfo($_FILE['name'], PATHINFO_EXTENSION), 'php') !== false) {
				throw new Exception(getLang('upload_msg_err_formate_s'));
			}
			
			if(!$this->IsExtension(pathinfo($_FILE['name'], PATHINFO_EXTENSION), $this->FormateImages['expansion'])) {
				throw new Exception(getLang('upload_msg_err_formate_s'));
			}
			
			list($Width, $Height) = getimagesize($_FILE['tmp_name']);
			
			if($Width < $this->MinSize || $Height < $this->MinSize) {
				throw new Exception(getLang('upload_msg_err_formate_s'));
			}
			
			if($this->FileVolume($_FILE['size'], 'KB') < $this->MinVolume) {
				throw new Exception(getLang('upload_msg_image_min_size', [$this->MinVolume]));
			}
			
			$Name = $this->NameString($_FILE['name'], false);
			
			if(move_uploaded_file($_FILE['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/' . $Dir . '/' . $Name)) {
				if(isset($CallBack)) {
					call_user_func($CallBack, [
						'Name' => $_FILE['name'],
						'Size' => $this->GetFileVolume($_FILE['size']),
						'Expansion' => $_FILE['type'],
						'Path' => $Dir . '/' . $Name,
						'Document' => $Name
					]);
				}
				else {
					return [
						'Name' => $_FILE['name'],
						'Size' => $this->GetFileVolume($_FILE['size']),
						'Expansion' => $_FILE['type'],
						'Path' => $Dir . '/' . $Name,
						'Document' => $Name
					];
				}
			}
		}
		
		public function IsTypeImage($Expansion) {
			$Validation = false;
			
			for($i = 0; $i < sizeof($this->FormateImages['type']); $i++) {
				if($Expansion == $this->FormateImages['type'][$i]) {
					$Validation = true;
					break;
				}
			}
			
			return $Validation;
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
		
		function GetFileVolume($bytes) {
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
		
		function FileVolume($size = 0, $type = "MB") {
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
		
		function NameString($File = null, $Main = true) {
			if(empty($File)) {
				return $this->RandomString(12);
			}
			
			$Pathinfo = pathinfo($Main ? $File['name'] : $File, PATHINFO_EXTENSION);
			
			if($Pathinfo == 'php') {
				$Pathinfo = 'txt';
			}
			
			return $this->RandomString(12) . ".$Pathinfo";
		}
		
		function RandomString($Len) {
			return substr(
				str_shuffle(
					'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
				), 0, $Len
			);
		}
	}