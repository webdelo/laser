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
};