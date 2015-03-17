var form = function (settings) {
	this.settings = $.extend({
		'form'    : '.form',
		'active'  : '.active',
		'submit'  : '.submit',
		'message' : '.message',
		'showError' : true,
		'disabled' : '.disabled',
		'onBeforeSend' : function () {},
		'onSuccess' : function () {},
		'onComplete' : function () {}
	}, settings||{});

	this.loader = new loader;
	this.errors  = new errors(this.settings);
	this.fieldSelector = 'input[type=text]:not('+this.settings.form+'Exclude):visible,input[type=password]:not('+this.settings.form+'Exclude):visible,input[type=hidden]:not('+this.settings.form+'Exclude),input:not('+this.settings.form+'Exclude):checkbox:checked:visible,textarea:not('+this.settings.form+'Exclude):visible,select:not('+this.settings.form+'Exclude):visible,textarea.transformer:not('+this.settings.form+'Exclude):hidden,input.transformer:not('+this.settings.form+'Exclude):hidden,select.transformer:not('+this.settings.form+'Exclude):hidden,'+this.settings.form+'Important:hidden';

	this.setSettings = function (sources) {
		this.settings = $.extend(this.settings, sources||{});
		this.errors  = new errors(this.settings);
		this.fieldSelector = 'input[type=text]:not('+this.settings.form+'Exclude):visible,input[type=password]:not('+this.settings.form+'Exclude):visible,input[type=hidden]:not('+this.settings.form+'Exclude),input:not('+this.settings.form+'Exclude):checkbox:checked:visible,textarea:not('+this.settings.form+'Exclude):visible,select:not('+this.settings.form+'Exclude):visible,textarea.transformer:not('+this.settings.form+'Exclude):hidden,input.transformer:not('+this.settings.form+'Exclude):hidden,select.transformer:not('+this.settings.form+'Exclude):hidden,'+this.settings.form+'Important:hidden';
		
		return this;
	};
	
	this.setLoader = function (newLoader) {
		this.loader = newLoader;
		return this;
	};

	this.init = function () {
		this.loader.init($(this.settings.form));
		var that = this;
		$(this.settings.form + 'Submit').on("click", function () {
			if ( !$(this).hasClass(that.settings.disabled.replace('.', '')) ) {
				that.setObject($(this));
				that.submit$ = $(this);
				that.setActive($(this))
					.start();
			}
			return false;
		});
		$(this.settings.form  + ' input, ' + this.settings.form  + ' textarea').on("keypress", function(e){
			if (e.ctrlKey && ( e.keyCode===83 || e.keyCode===1067 )) {
				var submit$ = $(this).parents(that.settings.form).find(that.settings.form + 'Submit');
				if (submit$.length === 0)
					submit$ = $(that.settings.form + 'Submit');
				submit$.click();
				return false;
			}
		});
		$(this.settings.form + ' input').on("keypress", function(e){
			if (e.keyCode===13) {
				var submit$ = $(this).parents(that.settings.form).find(that.settings.form + 'Submit');
				if (submit$.length === 0)
					submit$ = $(that.settings.form + 'Submit');
				submit$.click();
				return false;
			}
		});
		$(this.settings.form + ' textarea').live("keypress", function(e){
			if (e.ctrlKey && ( e.keyCode===13 || e.keyCode===10 )) {
				var submit$ = $(this).parents(that.settings.form).find(that.settings.form + 'Submit');
				if (submit$.length === 0)
					submit$ = $(that.settings.form + 'Submit');
				submit$.click();
				return false;
			}
		});
	};
	
	this.setObject = function (submit$) {
		var form$ = submit$.parents(this.settings.form);
		if (form$.length==0)
			form$ = $(this.settings.form);
		
		this.element$ = form$;
	};

	this.setActive = function (submit$) {
		if ($(this.settings.form).length === 1) {
			$(this.settings.form).addClass(this.settings.active.replace('.',''));
			return this;
		}		
		var form$ = submit$.parents(this.settings.form);
		form$.addClass(this.settings.active.replace('.',''));
		return this;
	};

	this.start = function (callback) {
			this.loader.start();
			this.errors.reset();
			if ($.isFunction(this.settings.onBeforeSend))
				this.settings.onBeforeSend(this);
			
			this.useElementBeforeSend();
			this.setCallback(callback)
				.setData()
				.setDataFromPost()
				.setDataFromSubmit()
				.setMethod()
				.setAction()
				.startAction(); 
		return this;
	};
	
	this.useElementBeforeSend = function(){
		if (this.hasElementCallback()) {
			var callback = window[this.getObject().data('callback')];
			if ( $.isFunction(callback) ) {
				callback.call(this);
			} else {
				alert('BeforeSend-функция указанная к форме - "'+this.getObject().selector+'" не найдена, либо определена не правильно.');
			}
		}
	};
	
	this.hasElementBeforeSend = function () {
		return ( typeof this.getObject().data('callback') != "undefined" );
	};
	
	this.addDataToPost = function (newPost) {
		var currentPost = $(this.settings.form).data('post') ? $.deparam($(this.settings.form).data('post')) : {};
		var dataPost    = $.extend(currentPost, newPost);
		var form$		= $(this.settings.form);
		form$.attr('data-post', '&'+$.param(dataPost));
	};

	this.setCallback = function (callback) {
		if($.isFunction(callback)) {
			this.callback = callback;
		}
		return this;
	};

	this.getData = function () {
		return this.data;
	};

	this.setData = function () {
		this.data = this.serialize();
		return this;
	};
	
	this.setDataFromPost = function () {
		var post = this.getObject().data('post');
		if ( post !== undefined ) {
			this.data += post;
		}
		
		return this;
	}
	
	this.setDataFromSubmit = function () {
		var post = this.getSubmitObject().data('post');
		if ( post !== undefined ) {
			this.data += post;
		}
		
		return this;
	}
	
	this.getActiveForm = function(){
		return this.getObject();
	};

	this.serialize = function () {
		var data;
		this.excludeFields();
		if (this.isForm(this.getActiveForm()))
			data = this.getActiveForm().serialize();
		else if (this.isDiv(this.getActiveForm())) {
			data = $.param(this.getActiveForm().find(this.fieldSelector));
		}
		this.includeFields();
		return data;
	};
	
	this.excludeFields = function () {
		this.getActiveForm().find(this.settings.form+'Exclude').attr('disabled', true);
		return this;
	};
	
	this.includeFields = function () {
		this.getActiveForm().find(this.settings.form+'Exclude').removeAttr('disabled');
		return this;
	};

	this.setAction = function () {
		if (this.isForm(this.getActiveForm()))
			this.action = this.getActiveForm().attr('action');
		else if (this.isDiv(this.getActiveForm()))
			this.action = this.getActiveForm().data('action');

		return this;
	};

	this.setMethod = function () {
		if (this.isForm(this.getActiveForm()))
			this.method = this.getActiveForm().attr('method');
		else if (this.isDiv(this.getActiveForm()))
			this.method = this.getActiveForm().data('method');

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
				dataType: 'json',
				data: that.data,
				success: $.proxy(that.success, that),
				complete: $.proxy(that.complete, that)
			});
		}

		return this;
	};

	this.before = function () {
		this.loader.title('Send to server...');
		$(this.settings.message).hide().html($(this.loading)).fadeIn();
		if ( $.isFunction(this.settings.onBeforeSend) ) {
			this.settings.onBeforeSend();
		}
		
	};

	this.error = function () {
		alert('Обратитесь к разработчикам. Операция '+this.action+' вызвала сбой.');
	};

	this.success = function (response) {
		$(this.settings.message).hide();
		
		if (typeof(response) === 'object'  && this.settings.showError === true) {
			this.errors.show(response, this.getObject());
		} else if (response == 1) {
			$(this.settings.message).text('Данные были обновлены!').fadeIn();
		}

		if($.isFunction(this.callback))
			this.callback(response);

		if ($.isFunction(this.settings.onSuccess))
			this.settings.onSuccess(response, this);
	};

	this.complete = function (response) {
		this.loader
			.stop()
			.title('Result received!');
		this.resetActive();
		if ($.isFunction(this.settings.onComplete))
			this.settings.onComplete(response, this);
	
		this.useElementCallback();
	};
	
	this.useElementCallback = function(){
		if (this.hasElementCallback()) {
			var callback = window[this.getObject().data('callback')];
			if ( $.isFunction(callback) ) {
				callback.call(this);
			} else {
				alert('Callback-функция указанная к форме - "'+this.getObject().selector+'" не найдена, либо определена не правильно.');
			}
		}
	};
	
	this.hasElementCallback = function () {
		return ( typeof this.getObject().data('callback') != "undefined" );
	};

	this.resetActive = function () {
		this.getActiveForm().removeClass(this.settings.active.replace('.',''));
	};

	this.isDiv = function (element) {
		var el = $(element);
		return $(element).is('div') || $(element).is('span');
	};
	
	this.isForm = function (element) {
		return $(element).is('form');
	};

	this.reset = function () {
		this.data = {};
		$(this.settings.form).find(this.fieldSelector).each(function(i){
			if ($(this).is('select'))
				$(this).find('option').each(function(){
					$(this).removeAttr('selected');
				});
			else if ($(this).is('checkbox'))
				$(this).removeAttr('checked');
			else
				$(this).val('');
		});
		return this;
	};
	
	this.getObject = function () {
		return this.element$;
	}
	
	this.getSubmitObject = function () {
		return this.submit$;
	};
	
	this.submit = function() {
		$(this.settings.form+'Submit').click();
	}

};
//window.form = new form();
//$(function () {
//window.form.init();
//});