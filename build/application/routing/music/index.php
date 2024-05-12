<?PHP
	if(empty($_SESSION['id'])) {
		Redirect('/account/auth');
	}
	
	$classMusicPlayer = new MusicPlayer;
	
	tpl()
	->Start('sample')
	->AddScripts('/public/plugins/musicplayer/js/musicplayer.js')
	->SetTitle($_PAGE['name'])
	->Content(tpl()->Get('music/index'))
	->Set([
		'{userid}' => $_SESSION['id'],
		'{musicjson}' => $classMusicPlayer->getJson($_SESSION['id'])
	])
	->Show();