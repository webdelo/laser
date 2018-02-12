(function($){
	$.fn.autoScroll = function (options) {
		var settings = $.extend({
			'duration'   : '400',
			'paddingTop' : '0',
			'callback'   : null
		}, options||{});
		
		
		var dest = $(this).offset();
		if (dest) {
			dest = parseInt(dest.top) - parseInt(settings.paddingTop);
			$('html,body').animate({scrollTop: dest}, settings.duration );
			if($.isFunction(settings.callback))
				settings.callback(this);
		}
		
		return this;
	}
})(jQuery);