<?PHP
	if(empty($_SESSION['id'])) {
		Redirect('/account/auth');
	}
	
	global $Help;
	$Help = new Help;
	
	if(!$Help->IsUserValid($_PAGE['params']['1'])) {
		Redirect('/help');
	}
	
	$Data = $Help->Get($_PAGE['params']['1']);
	
	tpl()
	->Start('sample')
	->Content(tpl()->Get('help/request'))
	->Set([
		'{id}' => $_PAGE['params']['1'],
		'{request_title}' => $Data->title,
		'{request_status}' => ($Data->status == 1) ? 'Есть ответ' : 'Ожидание',
		'{messages}' => $Help->GetMessages($_PAGE['params']['1'])
	])
	->Show();