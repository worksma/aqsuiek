<?PHP
	require('../../../start.php');
	
	IsValidActions();
	
	if(isset($_POST['LoadImage'])) {
		Result([
			'Content' => tpl()->Execute(tpl()->Set([
				'{image}' => $_POST['image']
			], tpl()->Get('elements/modal/image')))
		]);
	}