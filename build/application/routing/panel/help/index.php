<?PHP
	tpl()
	->SetAppearance('panel')
	->Start('sample')
	->Content(tpl()->Get('help/index'));
	
	try {
		$sth = pdo()->query('SELECT * FROM `help` ORDER BY `id` DESC');
		
		if(!$sth->rowCount()) {
			tpl()->AddCell('Tickets', '<tr colspan="4" class="text-center">Тикетов нет</tr>');
		}
		
		$Help = new Help;
		while($Row = $sth->fetch(PDO::FETCH_OBJ)) {
			tpl()->AddCell('Tickets', tpl()->Set([
				'{id}' => $Row->id,
				'{status}' => $Help->StatusText($Row->status),
				'{title}' => $Row->title,
				'{author}' => GetUserName($Row->userid, ['link' => true, 'full' => true])
			], tpl()->Get('elements/help/ticket')));
		}
	}
	catch(Exception $e) {
		AddLogs('panel.txt', "[Routing Help]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
		tpl()->AddCell('Tickets', '<tr colspan="4" class="text-center">Произошла ошибка при загрузке</tr>');
	}
	
	tpl()->Set([
		'{tickets}' => tpl()->GetCell('Tickets')
	]);
	tpl()->Show();