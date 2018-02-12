(function($){
	$.fn.ajaxForm = function (options) {
		var settings = $.extend({
			'callback'   : function(){},
			'beforeSend' : function(){},
			'submit'     : '.submit',
			'cancel'     : '.cancel'
		}, options||{});
		
		$(this).find(settings.submit).click(function () {
			
		});
		
		$(this).find(settings.cancel).click(function () {
			
		});		
		function start () {
			
		}
		
		return this;
	}
})(jQuery);

