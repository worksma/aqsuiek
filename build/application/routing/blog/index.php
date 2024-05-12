<?PHP
	$id = $_PAGE['params'][1];
	
	global $Blog;
	$Blog = new Blog;
	
	if(!$Blog->IsValid($id)) {
		ShowPage('ProfileInfo', tpl()->Get('elements/blog/not_valid'), 'Информация', 'sample');
	}
	
	$Data = $Blog->Get($id);
	
	if(isset($_SESSION['id'])) {
		$MyData = $Blog->Data($id)['subscribers'][$_SESSION['id']];
	}
	
	tpl()
	->Start('sample')
	->SetTitle(strip_tags(Reduction(htmlspecialchars_decode(htmlspecialchars_decode($Data->name)), 50)))
	->Content(tpl()->Get('blog/index'))
	->AddReplace([
		'{meta_description}' => strip_tags(htmlspecialchars_decode($Data->description)),
		'{meta_image}' => '/public/images/blog/' . $Data->image
	])
	->Set([
		'{id}'		=> $Data->id,
		'{name}'	=> $Data->name,
		'{description}'	=> $Data->description,
		'{image}'	=> $Data->image,
		'{access}'	=> isset($MyData['access']) ? $MyData['access'] : NULL
	])
	->Show();