(function($){
	$.fn.htmlFromServer = function (settings) {
		var settings = $.extend(settings||{});
		var that = this;
		var onBeforeSend = function(){
			if ( typeof settings.loader == 'object' )
				settings.loader.start($(that));
		};
		
		var onComplete = function() {
			if ( typeof settings.loader == 'object' )
				settings.loader.stop($(that));
			
			if ($.isFunction(settings.callback))
				settings.callback();
		};
		
		var onSuccess = function(data){
			$(that).html(data);
		};
		
		var onError = function(xhr, status, error){
			alert(status);
		};
		
		$.ajax({
			url: $(that).data('source'),
			beforeSend: onBeforeSend,
			type: 'POST',
			dataType: 'html',
			success: onSuccess,
			complete: onComplete,
			error: onError
		});
		return this;
	}
})(jQuery);
