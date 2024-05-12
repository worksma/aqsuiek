<?PHP
	require(
		'../start.php'
	);
	
	IsValidActions();
	
	if(isset($_POST['GetWrites'])) {
		if(isset($_POST['UniverseId'])) {
			if(!Users()->IsValid($_POST['UniverseId'])) {
				AlertWarning('Конечный пользователь не найден!');
			}
			
			$sth = pdo()->prepare('SELECT * FROM `writing` WHERE `universe`=:universe AND `remove`=\'0\' ORDER BY `id` DESC');
			$sth->execute([
				':universe' => $_POST['UniverseId']
			]);
		}
		else {
			$Page = isset($_POST['Page']) ? $_POST['Page'] : 1;
			$Start = ($Page - 1) * 10;
			
			$sth = pdo()->query('SELECT * FROM `writing` WHERE `remove`=\'0\' ORDER BY `id` DESC LIMIT ' . $Start . ', 10;');
			
			if(!$sth->rowCount()) {
				$Page = null;
			}
			else {
				$Page++;
			}
		}
		
		$tpl = new Template;
		$tpl->SetCell(['Writes' => '']);
		$Writings = new Attachments;
		
		while($Write = $sth->fetch(PDO::FETCH_OBJ)) {
			$UserData = Users()->Get($Write->author);
			
			$tpl->SetCell([
				'Writes' => $tpl->GetCell('Writes') . $tpl->Set([
					'{id}'				=> $Write->id,
					'{userid}'			=> $Write->author,
					'{content}'			=> htmlspecialchars_decode(htmlspecialchars_decode($Write->content)),
					'{write_author}'	=> GetUserName($Write->author, [
						'full' => true,
						'very' => true,
						'link' => true
					]),
					'{author_image}'	=> GetUserAvatar($Write->author),
					'{date}'			=> DayToTime($Write->date),
					'{likeRows}'		=> $Writings->RowLikes($Write->id),
					'{commentRows}'		=> $Writings->RowComments($Write->id),
					'{views}'			=> $Writings->GetViews($Write->id),
					'{attachment}'		=> $Writings->Visual($Write->id)
				], $tpl->Get('elements/writing/write'))
			]);
		}
		
		Result(['Content' => $tpl->Execute($tpl->GetCell('Writes')), 'Page' => isset($Page) ? $Page : NULL]);
	}
	
	if(empty($_SESSION['id'])) {
		AlertError('Сначала пройдите этап авторизации');
	}
	
	if(isset($_POST['Write'])) {
		if(!Users()->IsValid($_POST['UniverseId'])) {
			AlertWarning('Конечный пользователь не найден!');
		}
		
		if(!ValidLenText($_POST['content'])) {
			AlertWarning('Текст не может быть пустым');
		}
		
		$WG = new Attachments;
		$Result = $WG->Write($_POST['UniverseId'], ['content' => $_POST['content']]);
		
		if(empty($Result)) {
			AlertError('Сервер отклонил запрос');
		}
		
		/* Загрузка изображений: START */
		if(isset($_FILES['images'])) {
			for($i = 0; $i < count($_FILES['images']['name']); $i++) {
				list($width, $height) = getimagesize($_FILES['images']['tmp_name'][$i]);
				
				if($width < 200 || $height < 200) {
					continue;
				}
				
				if(0 < $_FILES['images']['error'][$i] || !IsTypeImage($_FILES['images']['type'][$i])) {
					continue;
				}
				
				if(stripos(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION), 'php') !== false) {
					continue;
				}
				
				if(!IsExtension(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'svg'])) {
					continue;
				}
				
				if(GetFileVolume($_FILES['images']['size'][$i], 'KB') < 100) {
					continue;
				}
				
				$Name = GetNameString($_FILES['images']['name'][$i], false, $_FILES['images']['type'][$i]);
				
				if(move_uploaded_file($_FILES['images']['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/attachments/' . $Name)) {
					try {
						$sth = pdo()->prepare(
							'INSERT INTO `writing__attachments`(`userid`, `writeid`, `name`, `size`, `expansion`, `document`, `date`) VALUES (:userid, :writeid, :name, :size, :expansion, :document, :date)'
						);
							
						$sth->execute([
							':userid'		=> $_SESSION['id'],
							':writeid'		=> $Result,
							':name'			=> $_FILES['images']['name'][$i],
							':size'			=> FileVolume($_FILES['images']['size'][$i]),
							':expansion'	=> $_FILES['images']['type'][$i],
							':document'		=> $Name,
							':date'			=> date('Y-m-d H:i:s')
						]);
					}
					catch(Exception $e) {
						AddLogs('pdo.txt', "[Writing Upload Images]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
					}
				}
			}
		}
		/* Загрузка изображений: END */
		
		$tpl = new Template;
		$tpl->SetCell(['WritePost' => '']);
		
		$Write		= $WG->Get($Result);
		$UserData	= Users()->Get($Write->author);
		
		$tpl->SetCell([
			'WritePost' => $tpl->GetCell('WritePost') . $tpl->Set([
				'{id}'				=> $Write->id,
				'{userid}'			=> $Write->author,
				'{content}'			=> htmlspecialchars_decode(htmlspecialchars_decode($Write->content)),
				'{write_author}'	=> GetUserName($Write->author, ['full' => true, 'very' => true, 'link' => true]),
				'{author_image}'	=> GetUserAvatar($Write->author),
				'{date}'			=> DayToTime($Write->date),
				'{likeRows}'		=> $WG->RowLikes($Write->id),
				'{attachment}'		=> $WG->Visual($Write->id),
				'{views}'			=> $WG->GetViews($Write->id),
				'{commentRows}'		=> $WG->RowComments($Write->id)
			], $tpl->Get('elements/writing/write'))
		]);
		
		(new Events)->Add($_SESSION['id'], '<span>' . $UserData->first_name . '</span> опубликовал запись на своей странице.', '/write-' . $_SESSION['id'] . '_' . $Write->id);
		
		Result([
			'Alert'		=> 'Success',
			'Message'	=> 'Пост опубликован',
			'Content'	=> tpl()->Execute($tpl->GetCell('WritePost'))
		]);
	}
	
	if(isset($_POST['AddComment'])) {
		if(!ValidLenText($_POST['content'])) {
			AlertWarning('Текст не может быть пустым');
		}
		
		$Writings = new Writings;
		$Result = $Writings->AddComment(
			$_SESSION['id'], $_POST['id'], $_POST['content']
		);
		
		if(empty($Result)) {
			AlertError('Сервер отклонил запрос');
		}
		
		/* Загрузка изображений: START */
		if(isset($_FILES['images'])) {
			for($i = 0; $i < count($_FILES['images']['name']); $i++) {
				if(0 < $_FILES['images']['error'][$i] || !IsTypeImage($_FILES['images']['type'][$i])) {
					continue;
				}
				
				if(stripos(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION), 'php') !== false) {
					continue;
				}
				
				if(!IsExtension(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'svg'])) {
					continue;
				}
				
				if(GetFileVolume($_FILES['images']['size'][$i], 'KB') < 100) {
					continue;
				}
				
				$Name = GetNameString($_FILES['images']['name'][$i], false, $_FILES['images']['type'][$i]);
				
				if(move_uploaded_file($_FILES['images']['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/attachments/' . $Name)) {
					try {
						$sth = pdo()->prepare(
							'INSERT INTO `writing__comments-attachments`(`userid`, `commentid`, `name`, `size`, `expansion`, `document`, `date`) VALUES (:userid, :commentid, :name, :size, :expansion, :document, :date)'
						);
							
						$sth->execute([
							':userid'		=> $_SESSION['id'],
							':commentid'	=> $Result,
							':name'			=> $_FILES['images']['name'][$i],
							':size'			=> FileVolume($_FILES['images']['size'][$i]),
							':expansion'	=> $_FILES['images']['type'][$i],
							':document'		=> $Name,
							':date'			=> date('Y-m-d H:i:s')
						]);
					}
					catch(Exception $e) {
						AddLogs('pdo.txt', "[Comment Upload Images]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
					}
				}
			}
		}
		/* Загрузка изображений: END */
		
		Result([
			'Alert'		=> 'Success',
			'Content'	=> $Writings->GetCommentUI($Result)
		]);
	}
	
	if(isset($_POST['SetLike'])) {
		$Writings = new Writings;
		$Result = $Writings->SetLike($_POST['WriteId'], $_POST['Liked']);
		
		if($Result) {
			Result(['Alert' => 'Success', 'WriteId' => $_POST['WriteId'], 'Likes' => $Writings->RowLikes($_POST['WriteId'])]);
		}
		
		AlertError('Сервер отклонил запрос');
	}
	
	if(isset($_POST['SetCommentLike'])) {
		$Writings = new Writings;
		$Result = $Writings->SetCommentLike($_POST['CommentId'], $_POST['Liked']);
		
		if($Result) {
			Result(['Alert' => 'Success', 'CommentId' => $_POST['CommentId'], 'Likes' => $Writings->RowCommentLikes($_POST['CommentId'])]);
		}
		
		AlertError('Сервер отклонил запрос');
	}
	
	if(isset($_POST['Remove'])) {
		$Writings = new Writings;
		
		if(!$Writings->IsHeritage($_POST['Index'], $_SESSION['id'])) {
			AlertError('Недостаточно прав');
		}
		
		if($Writings->Remove($_POST['Index'], 'По запросу пользователя')) {
			AlertSuccess('Запись удалена по запросу пользователя');
		}
		
		AlertError('Сервер отклонил запрос');
	}