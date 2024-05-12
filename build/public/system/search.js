$(function() {
	$('#GoSearch').bind('click', function() {
		SetWaiting();
		
		var searchParams = {
			Text: $('#SearchText').val(),
			AgeStart: $('#AgeStart').val(),
			AgeEnd: $('#AgeEnd').val(),
			City: $('#SearchCity').val()
		};
		
		SendPost('/application/backstage/Search.php', {
			Search: 1,
			Type: $('#SearchType').val(),
			Params: JSON.stringify(searchParams)
		}, (Result) => {
			ClearWaiting();
			$('#Result').html(Result.Content);
		});
	});
});