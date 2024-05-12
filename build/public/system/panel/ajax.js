$(function() {
	/*
		Тёмный режим
	*/
	$('#darkmode').bind('change', function() {
		if($(this).prop('checked')) {
			localStorage.setItem('darkmode', '1');
			$('html').attr('data-bs-theme', 'dark');
		}
		else {
			localStorage.setItem('darkmode', '');
			$('html').attr('data-bs-theme', '');
		}
		
		//ChangeLogotype();
	});
	
	if(localStorage.getItem('darkmode') == '1') {
		$('#darkmode').click();
	}
	else {
		$('html').attr('data-bs-theme', '');
	}
	
	$('[data-bs-toggle=\'tooltip\']').tooltip();
	
	/*
		Картинки
	*/
	$("button[href]").bind('click', function() {
		location.href = $(this).attr('href');
	});
	
	$("[redirect]").bind('click', function() {
		location.href = $(this).attr('redirect');
	});
	
	/*
		Загрузка редактора
	*/
	if(typeof tinymce !== 'undefined') {
		tinymce.init({
			selector:'textarea#editor',
			language: 'ru',
			skin: "oxide-dark",
			content_css: "dark"
		});
		
		/*
			Отключение копирайта
		*/
		$('.tox-promotion').css('display', 'none');
		$('.tox-statusbar__branding').css('display', 'none');
	}
	
	/*
		Смена логотипа
	*/
	$(window).on('resize', function() {
		//ChangeLogotype();
	});
	
	//ChangeLogotype();
	
	/*
		Другие функции панели
	*/
	$('form[data-target="Panel"]').submit(function(e) {
		e.preventDefault();
		
		switch($(this).data('toggle')) {
			case 'SiteName': {
				SendPost('/application/backstage/Panel.php', {
					edit_site_name: 1, this: this
				}, (Result) => {
					ShowToasty(Result.Message, Result.Alert);
				});
				
				break;
			}
			
			case 'SiteDesc': {
				SendPost('/application/backstage/Panel.php', {
					edit_site_desc: 1, this: this
				}, (Result) => {
					ShowToasty(Result.Message, Result.Alert);
				});
				
				break;
			}
			
			case 'SiteKeys': {
				SendPost('/application/backstage/Panel.php', {
					edit_site_keywords: 1, this: this
				}, (Result) => {
					ShowToasty(Result.Message, Result.Alert);
				});
				
				break;
			}
		}
	});
});

(function ($) {
    'use strict';
	/*
		Предзагрузка
	*/
	function inPreloader() {
		$(".loader_inner").fadeOut();
		
		$(".loader").delay(
			500
		).fadeOut("slow");
	}

	$(window).on('load', () => {

		inPreloader();
	});
})(jQuery);

function ChangeLogotype() {
	var addition = $('[data-bs-theme="dark"]').length ? 'white' : 'dark';
	
	if(isMobile.any()) {
		$('.logotype').attr('src', '/public/images/system/logo_' + addition + '.png');
	}
	else {
		$('.logotype').attr('src', '/public/images/system/logo_full_' + addition + '.png');
	}
}