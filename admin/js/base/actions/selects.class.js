var selects = function (settings) {
	this.settings = $.extend({
		'element' : '.select',
		'message' : '.message',
		'active'  : '.active',
		'showError'    : true,
		'beforeAjax'   : function () {},
		'successAjax'  : function () {},
		'completeAjax' : function () {}
	}, settings||{});

	this.errors  = new errors(this.settings);

	this.setSettings = function (sources) {
		this.settings = $.extend(this.settings, sources||{});
		this.errors  = new errors(this.settings);
		return this;
	};

	this.init = function () {
		var that = this;
		var element$ = this.settings.element;
		if ( typeof this.settings.element == 'string' ) {
			element$ = $(this.settings.element);
		}
		
		element$.on("change", function (e) {
			if ( that.doNeedConfirm() )
				that.element$ = $(this);
				if ( that.element$.data('action') === undefined )
					that.callback({}, that.element$);
				else
					that.start();
				
					
					
				return false;
			});
		return this;
	};
	
	this.setLoader = function (newLoader) {
		this.loader = newLoader;
		return this;
	};
	
	this.doNeedConfirm = function() {
		if ($(this).hasClass('confirm'))
			if (!confirm($(this).data('confirm')||'Do you sure?'))
				return false;
		
		return true;
	};

	this.setActive = function (button$) {
		button$.addClass(this.settings.active.replace('.', ''));
		this.settings.element = this.settings.element + this.settings.active;

		return this;
	};

	this.start = function () {
			$('body').css('cursor', 'wait');
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
		this.data = this.getObject().serialize();
		if (this.data=='') {
			this.data = this.getObject().attr('name')+'='+this.getObject().children(':selected').val();
		}

		return this;
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

		return this;
	};

	this.before = function () {};

	this.error = function () {
		alert('The request has failed. Please contact the developers!');
	};

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
	};

	this.complete = function (response) {
		$('body').css('cursor', 'auto');

		if ($.isFunction(this.settings.completeAjax))
			this.settings.completeAjax(response);
	};
	
	this.getObject = function () {
		return this.element$;
	};
	
	this.getChoosedObject = function () {
		return this.getObject().children(':selected');
	};
	
	this.getValue = function () {
		return this.getChoosedObject().val();
	};
	this.getText = function () {
		return this.getChoosedObject().text();
	};
};