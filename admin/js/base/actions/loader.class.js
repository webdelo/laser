// Данный класс должен содержать методы:
// start,stop,title, init
// Таким образом он будет без ошибок работать в классах form, selects, buttons
var loader = function (image) {
	this.image = image || '/admin/images/loaders/ajax-loader.gif';
	this.img$        = $('<div id="ajax_img"><img src="'+this.image+'"/></div>');
	this.title$      = $('<span class="loaderTitle">Data execute...</span>');
	this.bg$         = $('<div id="ajax_bg">&nbsp;</div>');
	this.destination = 'body';

	this.init = function () {
		if ( $('body').find('#ajax_bg').length >= 1 ){
			return this;
		}
		
		if (($.browser.msie && parseInt($.browser.version) <= 8 )) {
			var h   = getBodyScrollTop();
			var s_h = getClientHeight();

			this.img$.css("top", s_h/2 + h);
			this.bg$.css("top", h);
		}
		
		this.bg$
			.append(
				this.img$
					.append(this.title$)
			)
			.appendTo(this.destination)
			.hide();
	}

	this.start = function () {
		$('#ajax_bg').fadeIn();
		
		return this;
	}
	
	this.title = function (title) {
		this.title$.text(title);
		
		return this;
	}
	
	this.stop = function () {
		$('#ajax_bg').fadeOut();
		
		return this;
	}
}