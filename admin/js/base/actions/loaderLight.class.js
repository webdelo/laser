// Данный класс должен содержать методы:
// start,stop,title, init
// Таким образом он будет без ошибок работать в классах form, selects, buttons
var loaderLight = function (image) {
	this.init = function () {
		return this;
	};

	this.start = function () {
		$('body').css('cursor', 'wait');
		return this;
	};

	this.title = function (title) {
		title = 'This method is the noop function';
		return this;
	};

	this.stop = function () {
		$('body').css('cursor', 'auto');
		return this;
	};

//	this.start = function () {
//		this.cursor = $(document.activeElement).css('cursor');
//		$(document.activeElement).css('cursor', 'wait');
//		$('body').append('<div class="loaderLightBg" style="top: 0;position: fixed;width: 100%;height: 100%;z-index: 999999;"></div>');
//		$('.loaderLightBg').focus().css('cursor', 'wait');
//		return this;
//	};
//
//	this.title = function (title) {
//		title = 'This method is the noop function';
//		return this;
//	};
//
//	this.stop = function () {
//		$('.loaderLightBg').remove();
//		$(document.activeElement).css('cursor', this.cursor);
//		return this;
//	};
};