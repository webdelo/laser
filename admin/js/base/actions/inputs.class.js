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
	};
	
	this.setSettings = function (sources) {
		this.settings = $.extend(this.settings, sources||{});
		this.errors  = new errors(this.settings);
		return this;
	};

	this.init = function () {
		var that = this;
		
		this.saveStartValue();
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
	};
	
	this.saveStartValue = function() {
		$(this.settings.element).each(function(){
			$(this).attr('data-start-value', $(this).val());
		});
	};
	
	this.onChange = function (that, e) {
		this.element$ = $(that);
		var access = this.fieldIsChanged();
		this.setActive($(that));
		if (access){
			if ( this.element$.data('action') === undefined )
				this.callback({}, this.element$);
			else
				this.start();
		}
	};
	
	this.fieldIsChanged = function () {
		return this.getValue() != this.getObject().attr('data-start-value');
	};

	this.setActive = function (button$) {
		button$.addClass(this.settings.active.replace('.', ''));
		this.settings.element = this.settings.element + this.settings.active;

		return this;
	};

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
	};

	this.setCallback = function (callback) {
		if($.isFunction(callback))
			this.callback = callback;

		return this;
	};

	this.setData = function () {
		this.data = this.getObject().attr('name') + '=' + this.getValue();
//		this.data = this.getObject().serialize();
		
		return this;
	};
	
	this.getValue = function(){
		if ( this.getObject().val() === '' ) {
			if ( this.getObject().context.innerText !== "undefined" ) {
				return this.getObject().context.innerText;
			} else {
				return '';
			}
		}
		return this.getObject().val();
	};
	
	this.setDataFromPost = function () {
		var post = this.getObject().data('post');
		if ( post !== undefined ) {
			this.data += post;
		}
		
		return this;
	};

	this.setAction = function () {
		this.action = this.getObject().data('action');

		return this;
	};

	this.setMethod = function () {
		this.method = this.getObject().data('method');

		return this;
	};
	
	this.setDataType = function () {
		this.type = this.getObject().data('type');

		return this;
	};

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
	};

	this.before = function () {};

	this.error = function () {
		alert('The request has failed. Please contact the developers!');
	};

	this.success = function (response) {
		if (typeof(response) == 'object' && this.settings.showError === true) {
			this.highlightError();
			this.errors.show(response);
		} else if (typeof response == 'number') {
			$(this.settings.message).text('Данные были обновлены!').fadeIn();
			this.highlightSuccess();
			this.getObject().data('start-value', this.getObject().val());
			
			var localCallback = this.getObject().data('callback');
			if ($.isFunction(localCallback))
				window[localCallback](this.getObject());
		}
		
		var borderColor = this.getObject().css('border-color');
		
		if($.isFunction(this.callback))
			this.callback(response, this.element$);
	};
	
	this.highlightSuccess = function () {
		this.highlight('highlightSuccess');
	};
	
	this.highlightError = function () {
		this.highlight('highlightError');
	};
	
	this.highlight = function ($class) {
		this.getObject().addClass($class);
		var that = this;
		setTimeout( function(){
			that.getObject().removeClass($class);
		}, 3000 );
	};

	this.complete = function (response) {
		this.loader.stop();

		if ($.isFunction(this.settings.completeAjax))
			this.settings.completeAjax(response);
	};

	this.resetActive = function () {
		this.getObject().removeClass(this.settings.active.replace('.', ''));
		this.settings.element = this.settings.element.replace(this.settings.active, '') ;

		return this;
	};
	
	this.getObject = function () {
		return this.element$;
	};
};