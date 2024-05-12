<?PHP
	if(!isset($_REQUEST)) {
		return;
	}
	
	$requestData = $_POST;
	
	if(!isset($requestData['data'])) {
		AlertError('There are no parameters, read the documentation');
	}
	
	$requestData = json_decode($requestData['data'], true);
	
	if(!$requestData) {
		AlertError('There are no parameters, read the documentation');
	}
	
	try {
		$Api = new Api;
		
		if(!$Api->isBlogFromToken($requestData['token'])) {
			AlertError('The token you specified was not found');
		}
		
		global $dataBlog, $dataParams;
		$dataBlog = $Api->getBlogFromToken($requestData['token']);
		$dataParams = $requestData;
		
		switch($requestData['action']) {
			case 'addPost': {
				$Upload = new Upload;
				
				$Upload->Image($_FILES['image'], 'public/images/blog', function($_ARRAY) {
					global $dataBlog, $dataParams;
					
					$sth = pdo()->prepare('INSERT INTO `blog__post`(`blogid`, `userid`, `image`, `title`, `content`, `data`, `date`) VALUES (:blogid, :userid, :image, :title, :content, :data, :date)');
					$sth->execute([
						':blogid' => $dataBlog->id,
						':userid' => NULL,
						':image' => $_ARRAY['Document'],
						':title' => htmlspecialchars($dataParams['attached']['title']),
						':content' => htmlspecialchars($dataParams['attached']['content']),
						':data' => json_encode(['likes' => [], 'views' => []]),
						':date' => date('Y-m-d H:i:s')
					]);
					
					$lastId = pdo()->lastInsertId();
					$fullUri = 'https://' . $_SERVER['SERVER_NAME'] . '/blog-' . $dataBlog->id . '_' . $lastId;
					
					Result([
						'Alert' => 'Success',
						'Message' => 'The post has been successfully published',
						'PostId' => $lastId,
						'FullUri' => $fullUri
					]);
				});
				
				break;
			}
			
			default: {
				AlertError('Invalid parameter, read the documentation');
			}
		}
	}
	catch(Exception $e) {
		AlertError($e->getMessage());
	}