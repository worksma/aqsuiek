const isMobile = {
	Android: function() {
		return navigator.userAgent.match(/Android/i);
	},
	BlackBerry: function() {
		return navigator.userAgent.match(/BlackBerry/i);
	},
	iOS: function() {
		return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	},
	Opera: function() {
		return navigator.userAgent.match(/Opera Mini/i);
	},
	Windows: function() {
		return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
	},
	any: function() {
		return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
	}
};

function Lang(lang, callback) {
	$.getJSON('/application/system/languages/' + lang + '.json', function(data) {
		callback(data);
	});
}

function SendPost(website, data, callback, method = "POST") {
	var form;
	
	if(data['this']) {
		form = new FormData(data['this']);
		delete data['this'];
	}
	else {
		form = new FormData;
	}
	
	for(var i in data) {
		form.append(i, data[i]);
	}
	
	form.append("phpaction", "1");
	
	if($('#csrf_token').length) {
		form.append("csrf_token", $('#csrf_token').val());
	}
	
	$.ajax({
		type: method,
		url: website,
		processData: false,
		contentType: false,
		data: form,
		dataType: "json",
		success: function(result) {
			callback(result);
		}
	});
}

function ShowToasty(message, type = "Info", sound = true) {
	var toast = new Toasty({
		classname: "toast",
		transition: "pinItDown",
		insertBefore: false,
		progressBar: true,
		enableSounds: sound,
		autoClose: true,
		sounds: {
            info: "/public/plugins/toasty/sounds/other/pop.mp3",
            success: "/public/plugins/toasty/sounds/other/pop.mp3",
            warning: "/public/plugins/toasty/sounds/other/pop.mp3",
            error: "/public/plugins/toasty/sounds/other/pop.mp3"
		}
	});

	switch(type) {
		case "Success": toast.success(message); break;
		case "Error": toast.error(message); break;
		case "Danger": toast.error(message); break;
		case "Warning": toast.warning(message); break;
		default: toast.info(message); break;
	}
}

function SetPlaceholder(o, enable = true) {
	if(enable) {
		$(o).addClass("div-block");
		$(o + " :input").addClass("disabled");
		progress(1);
	}
	else {
		$(o).removeClass("div-block");
		$(o + " :input").removeClass("disabled");
		progress(100);
	}
}

function progress(pos = 1) {
	switch(pos) {
		case 0, 100:
			$(".progress").remove();
			return;
		break;
		case 1:
			$("body").prepend('<div class="progress"><span class="progress-bar" style="width: 0%;"></span></div>');
		break;
		default:
			$(".progress-bar").width(pos + "%");
		break;
	}
	
	if(pos < 99) {
		pos = pos + 1;
		setTimeout("progress(" + pos + ");", 70);
	}
	else {
		$(".progress-bar").width("99.9%");
	}
}

function Clean(text = '') {
	text = $.trim(text);
	
	return text;
}

function IsValid(text = '') {
	text = $.trim(text);
	
	if(text == '' || !text || text.length < 1) {
		return false;
	}
	
	return true;
}

function IsValidRemove(url) {
	$.get(url).done(function() {
		return true;
    }).fail(function() { 
		return false;
    });
	
	return false;
}

function WindowUri() {
	var uri = location.href.split('/')[3];

	if(uri.indexOf('#') != -1) {
		return uri.substring(0, uri.indexOf('#'));
	}

	return uri;
}

function WindowPathname() {
	var uri = location.pathname;
	
	if(uri.indexOf('#') != -1) {
		return uri.substring(0, uri.indexOf('#'));
	}

	return uri;
}

function Redirect(Link = '/', Time = 1) {
	setTimeout('location.href = \'' + Link + '\';', Time);
}

function PreloadImages() {
	const Images = document.getElementsByClassName('image');
	
	Array.from(Images).map((item) => {
		const image = new Image();
		image.src = item.dataset.src;
		
		image.onload = () => {
			return item.nodeName === 'IMG' ? item.src = item.dataset.src : item.style.backgroundImage = `url('${item.dataset.src}')`;
		}
	});
}

$WaitingTaskId = 0;

function SetWaiting(Time = 30000) {
	if(IsWaiting()) {
		return;
	}
	
	$('body').prepend('<div class="event-loading"></div>').css('pointer-events', 'none');
	$WaitingTaskId = setTimeout(ClearWaiting, Time);
}

function IsWaiting() {
	return $('.event-loading').length;
}

function ClearWaiting() {
	$('.event-loading').animate({
		opacity: "-=1"
	}, 1000, function() {
		clearTimeout($WaitingTaskId);
		
		$('.event-loading').remove();
		$('body').css('pointer-events', '');
	});
}

function CreatePreviewImage(input, output, index) {
	for(var i = 0; i < input.files.length; i++) {
		if(input.files[i]) {
			var reader = new FileReader;
			
			reader.onload = function(e) {
				var Div = $('<div>', {class: 'col-lg-4'}).bind('click', function() {
					$('#image_' + index).remove();
					$('#RowFiles').html('Выбрано ' + RowFiles($('#attachments')) + ' файл(-ов).');
					
					$(this).remove();
				});
				
				var Image = $('<div>', {src: e.target.result, class: 'preview-image'});
				Image.appendTo(Div);
				output.append(Div);
				Image.css('background-image', 'url(' + Image.attr('src') + ')');
			}
			
			reader.readAsDataURL(input.files[i]);
		}
	}
}