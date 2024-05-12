<?PHP
	class Attachments extends Writings {
		public function Upload($Path, $File) {
			if(empty($Path)) {
				return [false => getLang('attach_upload_path')];
			}
			
			if(empty($File)) {
				return [false => getLang('attach_upload_file')];
			}
			
			if($File['error'] > 0) {
				return [false => getLang('attach_upload_err_0', [$File['error']])];
			}
			
			$Name = GetNameString($File);
			$FullPath = $Path . '/' . $Name;
			
			if(!move_uploaded_file($File['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $FullPath)) {
				return [false => getLang('attach_upload_err_1')];
			}
			
			return [true => [
				'Name' => $Name,
				'Path' => $FullPath
			]];
		}
		
		public function Visual($writeid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing__attachments` WHERE `writeid`=:writeid ORDER BY `id` ASC');
				$sth->execute([':writeid' => $writeid]);
				
				if(!$sth->rowCount()) {
					return null;
				}
				
				$tpl = new Template;
				$tpl->SetCell(['Attachment' => $tpl->Get('elements/writing/attachment'), 'Item' => '']);
				
				$i = 0;
				while($Attach = $sth->fetch(PDO::FETCH_OBJ)) {
					if(IsTypeImage($Attach->expansion)) {
						$tpl->SetCell([
							'CarouselButton' => ($tpl->IsCell('CarouselButton') ? $tpl->GetCell('CarouselButton') : '') . $tpl->Set([
								'{id}'	=> $writeid,
								'{i}'	=> $i,
								'{active}' => ($i == 0) ? 'active' : ''
							], $tpl->Get('elements/writing/attachment/carousel_button')),
							'CarouselImages' => ($tpl->IsCell('CarouselImages') ? $tpl->GetCell('CarouselImages') : '') . $tpl->Set([
								'{document}' => $Attach->document,
								'{index}' => $Attach->id,
								'{writeid}' => $Attach->writeid,
								'{userid}' => $Attach->userid,
								'{name}' => $Attach->name,
								'{active}' => ($i == 0) ? 'active' : ''
							], $tpl->Get('elements/writing/attachment/carousel_images'))
						]);
						
						$i++;
						continue;
					}
				}
				
				if($tpl->IsCell('CarouselButton') && $tpl->IsCell('CarouselImages')) {
					$tpl->SetCell(['Carousel' => $tpl->Set(['{rows}' => $i, '{buttons}' => $tpl->GetCell('CarouselButton'), '{images}' => $tpl->GetCell('CarouselImages')], $tpl->Get('elements/writing/attachment/carousel'))]);
					
					$tpl->SetCell(['Attachment' => $tpl->Set(['{attachment}' => $tpl->GetCell('Carousel'), '{id}' => $writeid], $tpl->GetCell('Attachment'))]);
				}
				
				return $tpl->Execute($tpl->GetCell('Attachment'));
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Attachments]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
		
		public function VisualComment($commentid) {
			try {
				$sth = pdo()->prepare('SELECT * FROM `writing__comments-attachments` WHERE `commentid`=:commentid ORDER BY `id` ASC');
				$sth->execute([':commentid' => $commentid]);
				
				if(!$sth->rowCount()) {
					return null;
				}
				
				$tpl = new Template;
				$tpl->SetCell(['Attachment' => $tpl->Get('elements/writing/attachment'), 'Item' => '']);
				
				$i = 0;
				while($Attach = $sth->fetch(PDO::FETCH_OBJ)) {
					if(IsTypeImage($Attach->expansion)) {
						$tpl->SetCell([
							'CarouselButton' => ($tpl->IsCell('CarouselButton') ? $tpl->GetCell('CarouselButton') : '') . $tpl->Set([
								'{id}'	=> $commentid,
								'{i}'	=> $i,
								'{active}' => ($i == 0) ? 'active' : ''
							], $tpl->Get('elements/writing/attachment/carousel_button')),
							'CarouselImages' => ($tpl->IsCell('CarouselImages') ? $tpl->GetCell('CarouselImages') : '') . $tpl->Set([
								'{document}' => $Attach->document,
								'{active}' => ($i == 0) ? 'active' : ''
							], $tpl->Get('elements/writing/attachment/carousel_images'))
						]);
						
						$i++;
						continue;
					}
				}
				
				if($tpl->IsCell('CarouselButton') && $tpl->IsCell('CarouselImages')) {
					$tpl->SetCell(['Carousel' => $tpl->Set(['{rows}' => $i, '{buttons}' => $tpl->GetCell('CarouselButton'), '{images}' => $tpl->GetCell('CarouselImages')], $tpl->Get('elements/writing/attachment/carousel'))]);
					
					$tpl->SetCell(['Attachment' => $tpl->Set(['{attachment}' => $tpl->GetCell('Carousel'), '{id}' => $commentid], $tpl->GetCell('Attachment'))]);
				}
				
				return $tpl->Execute($tpl->GetCell('Attachment'));
			}
			catch(Exception $e) {
				AddLogs('pdo.txt', "[Class Attachments]\nLine: " . $e->getLine() . "\nCode: " . $e->getCode() . "\nMessage: " . $e->getMessage());
			}
			
			return null;
		}
	}