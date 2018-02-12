var errorHandler = function () {
	this.url = '/ErrorHandler/jsErrorHandler/';
	this.data = {};
	this.resolution = {};
	this.browserInfo = {};
	
	this.setData = function (msg, url, line) {
		this.data = {
			'message'    : msg,
			'url'        : url,
			'line'       : line,
			'resolution' : this.getResolution(),
			'navigator'  : this.getBrowserInfo()
		};
		return this;
	};
	
	this.setResolution = function () {
		this.resolution = this.getResolution();
	};
	
	this.getResolution = function () {
		return {
			'width'  : screen.width,
			'height' : screen.height
		};
	};
	
	this.setBrowserInfo = function () {
		this.browserInfo = this.getBrowserInfo();
	};
	
	this.getBrowserInfo = function () {
		return {
			'appCodeName' : navigator.appCodeName,
			'appName'     : navigator.appName,
			'appVersion'  : navigator.appVersion,
			'language'    : navigator.language,
			'platform'    : navigator.platform,
			'userAgent'   : navigator.userAgent
		};
	};
	
	this.sendError = function () {
		$.ajax({
			url: this.url,
			type: 'post',
			data: this.data,
			dataType: 'json',
			success: function (){ return true; }
		});
	};	
	
	this.start = function (msg, url, line) {
		this.setData(msg, url, line)
			.sendError();
	}
	
}

window.onerror = function(msg, url, line) { 
	handlerObj = new errorHandler();
	handlerObj.start(msg, url, line); 
};