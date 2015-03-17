(function($){
	$.fn.htmlFromServer = function (settings) {
		var settings = $.extend(settings||{});
		var that = this;
		var onBeforeSend = function(){
			if ( typeof settings.loader == 'object' )
				settings.loader.start($(that));
		};
		
		var onComplete = function() {
			settings.source = null;
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
		
		if ( $(that).data('source') == undefined ) {
			alert('Пожалуйста добавьте к атрибут data-source к элементу, в который загружаете информацию!');
			return this;
		}
		
		var source = settings.source || $(that).data('source');
		
		$.ajax({
			url: source,
			beforeSend: onBeforeSend,
			data: $(that).data('post')||'',
			type: 'POST',
			dataType: 'html',
			success: onSuccess,
			complete: onComplete,
			error: onError
		});
		return this;
	}
})(jQuery);
