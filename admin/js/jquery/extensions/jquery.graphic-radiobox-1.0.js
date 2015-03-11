(function($){
	$.fn.graphicRadioBox = function (options) {
		var settings = $.extend({
			'outputElement'  : '.result',
			'activeClass'    : 'active',
			'callback'       : null
		}, options||{});
		
		
		var that = this;
		$(this).live('click', function(){
			if (!$(this).hasClass('disabled')) {
				var value = $(this).data('value');
				if ( value == undefined || value == null ) {
					throw 'In input element.'+$(that).attr('class')+' is not defined attribute data-value';
					return false;
				}

				if (!$(settings.outputElement).is(':input')) {
					throw 'Element'+settings.outputElement+' must be an input';
					return false;
				}

				$(that).removeClass(settings.activeClass);
				$(this).addClass(settings.activeClass);
				$(settings.outputElement).val(value);

				if($.isFunction(settings.callback))
					settings.callback(this);
			}
		});
		return this;
	}
})(jQuery);