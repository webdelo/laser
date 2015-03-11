var inputs = function (settings) {
	this.settings = $.extend({
		'element' : '.inputAjax',
		'message' : '.message',
		'active'  : '.active',
		'event'   : 'blur',
		'eventOnce'    : false,
		'showError'    : true,
		'beforeAjax'   : function () {},
		'successAjax'  : function () {},
		'completeAjax' : function () {}
	}, settings||{});

	this.errors  = new errors(this.settings);
	this.loader = new loaderLight();
	
	this.setLoader = function (loader)
	{
		this.loader = loader;
		return this;
	}	
	
	this.setSettings = function (sources) {
		this.settings = $.extend(this.settings, sources||{});
		this.errors  = new errors(this.settings);
		return this;
	}

	this.init = function () {
		var that = this;
		$(this.settings.element).data('startValue', $(this.settings.element).val());
		$(this.settings.element).on(this.settings.event, function(e) { that.onChange(this, e) });
		$(this.settings.element).on("keypress", function(e){
			if ( $(this).is('textarea') ) {
				if (e.ctrlKey && ( e.keyCode===13 || e.keyCode===10 )) {
					that.onChange(this, e);
					e.stopPropagation();
				}
			} else if ( $(this).is('input') ) {
				if (e.keyCode===13) {
					that.onChange(this, e);
					e.stopPropagation();
				}
			}
		});
		
		return this;
	}
	
	this.onChange = function (that, e) {
		var access = true;
		this.setActive($(that));
		this.element$ = $(that);
		if (access){
			if ( this.element$.data('action') === undefined )
				this.callback({}, this.element$);
			else
				this.start();
		}
	};
	
	this.fieldIsChanged = function () {
		var startVal = this.getObject().data('startValue');
		var value    = this.getObject().val();
		return this.getObject().val() != this.getObject().data('startValue');
	}

	this.setActive = function (button$) {
		button$.addClass(this.settings.active.replace('.', ''));
		this.settings.element = this.settings.element + this.settings.active;

		return this;
	}

	this.start = function () {
			this.loader.start();
			this.errors.reset();
			this.setData()
				.setDataFromPost()
				.setMethod()
				.setAction()
				.setDataType()
				.startAction();
		return this;
	}

	this.setCallback = function (callback) {
		if($.isFunction(callback))
			this.callback = callback;

		return this;
	}

	this.setData = function () {
		this.data = this.getObject().serialize();
		
		return this;
	}
	
	this.setDataFromPost = function () {
		var post = this.getObject().data('post');
		if ( post !== undefined ) {
			this.data += post;
		}
		
		return this;
	}

	this.setAction = function () {
		this.action = this.getObject().data('action');

		return this;
	}

	this.setMethod = function () {
		this.method = this.getObject().data('method');

		return this;
	}
	
	this.setDataType = function () {
		this.type = this.getObject().data('type');

		return this;
	}

	this.startAction = function () {
		var that = this;
		if ("errorsCounter" in window) {} else {
			$.ajax({
				beforeSend: $.proxy(that.before, that),
				error: $.proxy(that.error, that),
				url: that.action,
				type: that.method || 'post',
				dataType: that.type || 'json',
				data: that.data,
				success: $.proxy(that.success, that),
				complete: $.proxy(that.complete, that)
			});
		}
		this.resetActive();

		return this;
	}

	this.before = function () {}

	this.error = function () {
		alert('The request has failed. Please contact the developers!');
	}

	this.success = function (response) {
		if (typeof(response) == 'object' && this.settings.showError === true) {
			this.errors.show(response);
		} else if (response == 1) {
			$(this.settings.message).text('Данные были обновлены!').fadeIn();
			var localCallback = this.getObject().data('callback');
			if ($.isFunction(localCallback))
				window[localCallback](this.getObject());
		}
		
		this.getObject()
			.addClass('highlightSuccess')
			.animate({
				"borderBottomColor": "#80cc80",
				"borderTopColor": "#80cc80",
				"borderLeftColor": "#80cc80",
				"borderRightColor": "#80cc80"
			}, "slow")
			.delay(1000)
			.animate({
				"borderBottomColor": "#cccccc",
				"borderTopColor": "#cccccc",
				"borderLeftColor": "#cccccc",
				"borderRightColor": "#cccccc"
			},  "slow", function() {
				  $(this).removeClass('highlightSuccess')
			});
		
		if($.isFunction(this.callback))
			this.callback(response, this.element$);
	}

	this.complete = function (response) {
		this.loader.stop();

		if ($.isFunction(this.settings.completeAjax))
			this.settings.completeAjax(response);
	}

	this.resetActive = function () {
		this.getObject().removeClass(this.settings.active.replace('.', ''));
		this.settings.element = this.settings.element.replace(this.settings.active, '') ;

		return this;
	}
	
	this.getObject = function () {
		return this.element$;
	}
	
	this.getValue = function () {
		return this.getObject().val();
	}
}