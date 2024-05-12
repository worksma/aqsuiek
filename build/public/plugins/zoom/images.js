function BindZoomImages() {
	$.each($('[data-target=\'Zoom\'][data-toggle=\'Image\']'), function(event, handler) {
		$(handler).unbind('click').bind('click', function() {
			SendPost('/application/backstage/plugins/zoom/images.php', {
				LoadImage: 1, image: $(this).attr('src') ? $(this).attr('src') : $(this).data('src')
			}, (Result) => {
				$('body').append(
					Result.Content
				);
			});
		});
	});
}

$(function() {
	$('head').append(
		'<link rel="stylesheet" href="/public/plugins/zoom/images.css">'
	);
	
	BindZoomImages();
});