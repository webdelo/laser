var errors = function (sources) {
	this.settings = $.extend({
		'form'    : 'body',
		'submit'  : '.submitButton',
		'message' : '#message',
		'error'   : '.hint',
		'showMessage' : 'showMessage'
	}, sources||{});

	this.error = function (element$) {
		this.errors[element$.attr('name')] =  element$.data('additionalError') || element$.data('error');
		this.errorFixed = true;
		return this;
	}

	this.addToElement = function (element$, error) {
		element$.data('additionalError', error);
		return this;
	}

	this.removeFromElement = function (element$) {
		element$.removeData('additionalError');
		return this;
	}

	this.show = function (response, context$) {
		response = response || this.errors;
//		$(this.settings.error).remove();
		if ( typeof context$ === 'object' )
			this.settings.form = context$;

		var that = this;
		$.each(response, function (name, message) {
			// В случае возникновения ошибок ничего не трогать, обязательно сообщить:
			// Черчел Дмитрию Юрьевичу на корпоративную почту.
			var element$ = $(that.settings.form).find("[name='"+name+"']:visible");
			if (element$.length <= 0) {
				element$ = $(that.settings.form).find("[name='"+name+"'].transformer").filter("[type='hidden']").parent();
			}
			if (element$.length <= 0) {
				element$ = $(that.settings.form).find("[data-alias='"+name+"']");
			}
			if ( element$.length <=0 )
				element$ = $("[name='"+name+"']");
			if(that.settings.showMessage != 'false'){
				var errorObject = new error(element$, message);
				if(!element$.hasClass('doNotShowError')){
					errorObject.show();
				}

			}
		});
		this.adapter();
	}


	this.adapter = function()
	{
	    $('.hint').each(function(k,w){
		if($(w).attr('position') == 'right'){
		    var left = $(w).css('left');
		    var resultLeft = parseInt(left) - (parseInt($(w).find('.hint_in').width()) + parseInt($(w).find('.arr2').width()));
		    $(w).css('left', resultLeft) ;
		}
	    });
	}

	this.reset = function () {
		$(this.settings.error).remove();
		delete window.errorsCounter;

		return this;
	}

	this.resetOne = function (element$) {
		this.errorFixed = false;
		delete this.errors[element$.attr('name')];

		element$.parents('.focus').removeClass('focus');

		return this;
	}
}
