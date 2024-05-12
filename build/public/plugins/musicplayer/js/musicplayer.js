/*
	Регистрируем плеер
*/
var playId;

function playTrack(id) {
	let audioPlayer = $('#Player');
	
	if(audioPlayer.length) {
		playId = id;
		
		$('.Music-Player > .Info > .Image > img').css({
			'display': 'block'
		});
		
		$('.Music-Player > .Info > .Image > img').attr('src', playList[id]['image']);
		$('.Music-Player > .Info > .Data > .Artist').html(playList[id]['artist']);
		$('.Music-Player > .Info > .Data > .Track-Name').html(playList[id]['name']);
		
		audioPlayer.attr('src', playList[id]['file']);
		audioPlayer[0].play();
	}
}

var lastVolume = 1;

$(function() {
	if($('#Player').length && $('#PlayerVolume').length) {
		/*
			Управление уровнем звука
		*/
		$('#PlayerVolume').bind('change', function() {
			let volume = $(this).val();
			let icon = $('.Volume-Control > button > i.bi');
			
			if(volume == 0) {
				if(icon.hasClass('bi-volume-up-fill')) {
					icon.removeClass('bi-volume-up-fill');
					icon.addClass('bi-volume-mute-fill');
				}
			}
			else {
				if(icon.hasClass('bi-volume-mute-fill')) {
					icon.removeClass('bi-volume-mute-fill');
					icon.addClass('bi-volume-up-fill');
				}
			}
			
			$('#Player')[0].volume = volume;
		});
		
		$('#PlayerVolume').on('input', function() {
			var value = $(this).val();
			var percentage = value * 100 + '%';
			$('.Music-Player > .Control-End > .Volume-Control > .RangeContainer > .Fill').css('width', percentage);
		});
		
		/*
			Кнопка мута
		*/
		$('.Volume-Control > button').on('click', function() {
			let volume = $('#PlayerVolume').val();
			
			if(volume == 0) {
				$('#PlayerVolume').val(lastVolume).trigger('change');
				$('.Music-Player > .Control-End > .Volume-Control > .RangeContainer > .Fill').css({'width': lastVolume * 100 + '%'});
			}
			else {
				lastVolume = volume;
				$('#PlayerVolume').val(0).trigger('change');
				$('.Music-Player > .Control-End > .Volume-Control > .RangeContainer > .Fill').css({'width': '0%'});
			}
		});
		
		/*
			Запуск и пауза песни
		*/
		$('.Control > .Pause-And-Play').on('click', function() {
			let audioPlayer = $('#Player');
			
			if(audioPlayer.prop('src')) {
				let icon = $(this).find('.bi');
				
				if(icon.hasClass('bi-play-circle-fill')) {
					audioPlayer[0].play();
				}
				else {
					audioPlayer[0].pause();
				}
			}
			else {
				for(var key in playList) {
					if(playList.hasOwnProperty(key)) {
						playTrack(key);
						break;
					}
				}
			}
		});
		
		/*
			Предыдущий трек
		*/
		$('.Control > .Skip-Backward').on('click', function() {
			var keys = Object.keys(playList);
			
			var backKey = null;
			for(var i = 0; i < keys.length; i++) {
				if(parseInt(keys[i]) < playId) {
					backKey = parseInt(keys[i]);
					break;
				}
			}
			
			if(backKey !== null) {
				playTrack(backKey);
			}
			else {
				$('#Player')[0].currentTime = 0;
			}
		});
		
		
		/*
			Следующий трек
		*/
		$('.Control > .Skip-Forward').on('click', function() {
			var keys = Object.keys(playList);
			
			var nextKey = null;
			for(var i = 0; i < keys.length; i++) {
				if(parseInt(keys[i]) > playId) {
					nextKey = parseInt(keys[i]);
					break;
				}
			}
			
			if(nextKey !== null) {
				playTrack(nextKey);
			}
			else {
				playTrack(keys[0]);
			}
		});
		
		/*
			Событие воспроизведения и паузы
		*/
		$('#Player').on('play', function() {
			let icon = $('.Pause-And-Play > .bi');
			
			if(icon.hasClass('bi-play-circle-fill')) {
				icon.removeClass('bi-play-circle-fill').addClass('bi-pause-circle-fill');
			}
			
			let tpos = $('#Music-List > li[data-trackid="' + playId + '"]');
			
			if(!tpos.hasClass('Active')) {
				tpos.addClass('Active');
			}
			
			$('#Music-List > li.Active').each(function() {
				if($(this).data('trackid') != playId) {
					$(this).removeClass('Active');
				}
			});
		});
		
		$('#Player').on('pause', function() {
			let icon = $('.Pause-And-Play > .bi');
			
			if(icon.hasClass('bi-pause-circle-fill')) {
				icon.removeClass('bi-pause-circle-fill').addClass('bi-play-circle-fill');
			}
		});
		
		/*
			Событие окончания песни
		*/
		$('#Player').on('ended', function() {
			var keys = Object.keys(playList);
			
			var nextKey = null;
			for(var i = 0; i < keys.length; i++) {
				if(parseInt(keys[i]) > playId) {
					nextKey = parseInt(keys[i]);
					break;
				}
			}
			
			if(nextKey !== null) {
				playTrack(nextKey);
			}
			else {
				playTrack(keys[0]);
			}
		});
		
		/*
			Событие нажатия на трек
		*/
		$('ul#Music-List > li').on('click', function() {
			let id = $(this).data('trackid');
			
			if(playList[id] === undefined) {
				console.log('Выбранный трек не найден в базе');
			}
			else {
				playTrack(id);
			}
		});
		
		/*
			Позиция дорожки
		*/
		$('#PlayerPosition').on('input', function() {
			let audioPlayer = $('#Player');
			let value = $(this).val();
			
			var time = audioPlayer[0].duration * (value / 100);
			audioPlayer[0].currentTime = time;
		});
		
		$('#Player').on('timeupdate', function() {
			let audioPlayer = $(this);
			var currentTime = audioPlayer[0].currentTime;
			var duration = audioPlayer[0].duration;
			var newPosition = (currentTime / duration) * 100;
			
			$('.Music-Player > .Info > .Data > .RangeContainer').css({'display': 'block'});
			$('.Music-Player > .Info > .Data > .RangeContainer > .Fill').css({'width': newPosition + '%'});
		});
	}
});