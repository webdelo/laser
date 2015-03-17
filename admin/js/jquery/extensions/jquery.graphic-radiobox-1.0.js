(function($){
	$.fn.graphicRadioBox = function (options) {
		var settings = $.extend({
			'outputElement'  : '.result',
			'activeClass'    : 'active',
			'callback'       : null,
			'callbacks'      : {}
		}, options||{});
		
		
		var that = this;
		$(this).live('click', function(){
			if (!$(this).hasClass('disabled') && !$(this).hasClass(settings.activeClass)) {
				var value = $(this).data('value');
				if ( value == undefined || value == null ) {
//					throw 'In input element.'+$(that).attr('class')+' is not defined attribute data-value';
					return false;
				}

				if (!$(settings.outputElement).is(':input')) {
					throw 'Element'+settings.outputElement+' must be an input';
					return false;
				}

				$(that).removeClass(settings.activeClass);
				$(this).addClass(settings.activeClass);
				if (settings.passiveClass) {
					$(that).addClass(settings.passiveClass);
					$(this).removeClass(settings.passiveClass);
				}
				
				$(settings.outputElement).val(value);

				if($.isFunction(settings.callback))
					settings.callback(this);
				
				var callbackName = $(this).data('callback');
				if($.isFunction(settings.callbacks[callbackName]))
					settings.callbacks[callbackName].call(this);
			}
		});
		return this;
	};
})(jQuery);