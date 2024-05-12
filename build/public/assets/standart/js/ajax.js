$(function() {
	$('[data-target=\'Send\'][data-toggle=\'NewPost\']').keypress(function(e) {
		if(e.which == 13 && e.shiftKey) {
			e.preventDefault();
			
			console.log(
				$(this).val()
			);
		}
	});
	
	$('li[href="' + WindowPathname() + '"]').addClass('active');
});