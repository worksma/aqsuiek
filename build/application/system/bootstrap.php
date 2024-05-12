<?PHP
	require_once(
		__DIR__ . '/library/class.bootstrap.php'
	);
	
	$Bootstrap = new Bootstrap;
	
	$Bootstrap->AddNamespace(
		Bootstrap::CORE_NAMESPACE, [
			__DIR__ . '/library/',
			__DIR__ . '/library/widgets/',
			__DIR__ . '/library/phpmailer/',
			__DIR__ . '/library/music/'
		]
	);
	
	$Bootstrap->Register();