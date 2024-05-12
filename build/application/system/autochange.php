<?PHP
	if(isset($_SESSION['id'])) {
		$this->AddReplace([
			'{profile_image}' => GetUserAvatar($_SESSION['id']),
			'{profile_id}' => $_SESSION['id'],
			'{profile_name}' => GetUserName($_SESSION['id'], ['full' => true, 'very' => true]),
			'{profile_group}' => GetUserGroup($_SESSION['id']),
		]);
	}
	else {
		$this->AddReplace([
			'{profile_image}' => 'no_image.jpg',
			'{profile_id}' => null,
			'{profile_name}' => getLang('sys_unknown'),
			'{profile_group}' => getLang('sys_unknown'),
		]);
	}
	
	$this->AddScripts('/public/plugins/tinymce/tinymce.min.js');