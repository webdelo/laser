var authorization = {
	result : null,
	data : null,
	url: null,
	noticeBlock: null,
	loadingBlock: null,
	user: null,
	
	actions: {
		checkAuth:       '?action=ajaxCheckAuthorization',
		checkCaptcha:    '?action=ajaxCheckCaptcha',
		logout:          '?action=ajaxLogout',
		getAuthUser:	 '?action=ajaxGetAuthUser',
		authorization:   '?action=ajaxAuthorization'
	},
	
	setActions: function (actions) {
		if (typeof actions == 'object') {
			$.each(actions, function(key, element){
				if(element)
					if (authorization.actions[key])
						authorization.actions[key] = element;
			});
		}
	},
	
	controls: {
		userFieldHidden: '#hidden_user',
		notEmpty:        '.important',
		form:            '#authorization',
		loginField:      '#login',
		passwordField:   '#password',
		rememberField:   '#remember',
		captchaField:    '#captcha',
		captchaBlock:    '.captchablock',
		captchaImage:    '#captchaImage',
		container:       '#auth_window',
		errorBlock:      '.error_message',
		noticePosition:  'right',
		loadingUrl:      '/admin/images/ajax-loader_transparent.gif',
		sendButton:      '#auth_action'
	},
	
	setControls: function (controls) {
		if (typeof controls == 'object') {
			$.each(controls, function(key, element){
				if (element)
					if (authorization.controls[key])
						authorization.controls[key] = element;
			});
		}
	},
	
	resetControls: function () {
		$.each(authorization.controls, function(key, element){
			if (element)
				if (authorization.controls[key])
					authorization.controls[key] = '';
		});
		return this;
	},
	
	
	start: function () {
		authorization.setControls({
			notEmpty:     '.important',
			container:    '.authback',
			form:         '#authorization',
			loginField:   '#user',
			passwordField:'#pass',
			captchaField: '#captchaField',
			captchaImage: '#captcha',
			errorBlock:    '.error_message',
			loadingUrl:   '/admin/images/ajax-loader_transparent.gif',
			sendButton:   '#submit'
		});
		
		this.checkAuthorization(function(res){
				if (res == false) {
					authorization.checkCaptcha();
				}
			})
			.setLoadingBlock();	
	},
	
	block: function () {
		auth.setControls({
			notEmpty:     '.important',
			container:    '.block',
			form:         '#unblock',
			loginField:   '#hidden_user',
			passwordField:'#password',
			errorBlock:   '.error_message',
			loadingUrl:   '/admin/images/ajax-loader_transparent.gif',
			sendButton:   '#unbloc_action'
		});

		var successFunc = function(res){
			if (res == true) {
				authorization.setUserName();
				authorization.show();
			} else {
				authorization.start();
			}
		};

		this.checkAuthorization(successFunc)
			.setLoadingBlock();
	},
	
	setUserName: function () {
		this.setUrl(this.actions.getAuthUser)
			.setSuccess(function(res){
				 this.user = res.username;
				$(authorization.controls.userFieldHidden+'_label').text(authorization.user);
				$(authorization.controls.userFieldHidden).val(authorization.user);
				authorization.setSuccess(function(){
					authorization.monitoringAuthorization(); 
				}).logout();
			})
			.ajaxStart();
	},

	monitoringLogout: function () {
		$(this.controls.logoutButton).click(function(){
			authorization.setSuccess(function(){
							location.reload();
						 })
						 .logout();
		});

		return this;
	},

	logout: function () {
		this.setUrl(this.actions.logout)
			.ajaxStart();

		return this;
	},
	
	checkAuthorization: function (callback) {
		this.setUrl(this.actions.checkAuth)
			.setSuccess(function(res){
				callback(res);
			})
			.ajaxStart();
		
		return this;
	},
	
	setSuccess: function (func) {
		this.success = func;
		return this;
	},	
	
	checkCaptcha: function () {
		this.setUrl(authorization.actions.checkCaptcha)
			.setSuccess(function(res){
				if (res != true)
					$(authorization.controls.captchaBlock).hide();
				
				authorization.show();
				authorization.monitoringAuthorization();
			})
			.ajaxStart();
		return this;
	},
	
	monitoringAuthorization: function () {
		$(this.controls.sendButton).click(function (){
			try {
				authorization.setUrl(authorization.actions.authorization)
					.hideErrorMessage()
					.fieldsReset()
					.validateData()
					.setData()
					.setSuccess(function(res){
						this.resetData();
						this.showErrorMessage(res.message);
						switch(res.code) {
							case 1:
								authorization.hide();
								break;
							case 2:
								if (res.tries>2)
									$(this.controls.captchaBlock).show();
								break;
							case 3:
								this.blockAllFields();
								break;
							case 4:
								$(this.controls.captchaBlock).show();
								break;
							default:
								break;
						}
						$(this.controls.passwordField).val('');
					})
					.ajaxStart();
			} catch (e){
				authorization.showErrorMessage(e.message);
			}
			return false;
		});
	},
	
	setUrl: function (source) {
		this.url = source;
		return this;
	},
	
	blockAllFields: function () {
		var form = $(this.controls.form);
		$.each(form, function(i, element){
			element.disable = 'true';
		});
	},
	
	resetData: function (data) {
		this.data = '';
		return this;
	},
	
	setData: function (data) {
		this.data = (data) ? data : $(this.controls.form).serialize();
		return this;
	},
	
	fieldsReset: function () {
		var fields = this.controls.notEmpty;
		$(fields).each(function(i, element){
			authorization.unmarkField(element);
			authorization.hideNotices(element);
		});
		return this;
	},

	unmarkField: function (element) {
		$(element).css({'border':'none'});
	},
	
	hideNotices: function (element) {
		$('#notice_'+element.id).remove();
	},
	
	validateData: function () {
		var j = $;
		var messages = [];
		var count_errors = 0;
		$(this.controls.container+' '+this.controls.notEmpty+':visible').each(function(i, element){
			if (element.value==''){
				authorization.markField(this);
				authorization.fieldNotice(this);
				count_errors++;
			}
		});
		if ( count_errors > 0 ) {
			throw { 
				message:'Warning! Important fields are empty.',
				key:10
			};
		}
		return this;
	},
	
	markField: function (element) {
		$(element).css({'border':'1px solid red'});
	},
	
	fieldNotice: function (element) {
		this.getNotice(element);
	},
	
	getNotice: function (element) {
		this.noticeBlock = $('<div>')
					.attr('id', 'notice_' + element.id)
					.addClass('notice_message')
					.html(element.title)
					.appendTo(this.controls.container);
					
		this.setNoticePosition(element);
	},
	
	setNoticePosition: function (element) {
		this.noticeBlock.css({
			'position':'absolute',
			'zIndex':'9999999999', 
			'left':this.getElementPosition(element).right,
			'top':this.getElementPosition(element).top
		});
	},
	
	getElementPosition: function (element) {
		if (this.controls.noticePosition=='right'){
			return {
				'left': $(element).offset().left,
				'top': $(element).offset().top,
				'right': ($(element).width() + $(element).offset().left) + 10,
				'bottom': ($(element).height() + $(element).offset().top) + 5
			};	
		}
	},
	
	ajaxStart:function () {
		$.ajax({
			url: authorization.url,
			type: 'get',
			data: authorization.data,
			dataType: 'json',
			success: function(res) {authorization.success(res)} 
		});
	},
	
	success: function (){},
	
	show: function () {
		this.setContainerPosition();
		$(this.controls.container).fadeIn();
		return this;
	},
	
	setContainerPosition: function () {
		
		var blockSizes = {};
		blockSizes.width = $(this.controls.container+' div').width();
		blockSizes.height = $(this.controls.container+' div').height();
		
		var bodySizes = {};
		bodySizes.width = window.innerWidth;
		bodySizes.height = window.innerHeight;
		
		var resultX = (bodySizes.width/2)-(blockSizes.width/2)-25;
		var resultY = (bodySizes.height/2)-(blockSizes.height/2)-100;
		
		$(this.controls.container+' div').css({
			'left':resultX,
			'top':resultY
		});
	},
	
	hide: function () {
		$(this.controls.container).fadeOut();
		this.hideErrorMessage();
		this.fieldsReset();
	},
	
	getValuesBySelectors: function (source) {
		var data = [];
		var row = '';
		$.each(source, function(i){
			row = authorization.getValueBySelector(source[i]);
			if(row) data[i] = row;
		});
		return data;
	},
	
	getValueBySelector: function (source) {
		if(this._isTextElement(source))
			return $(source).val();
	},
	
	_isTextElement: function (element) {
		if ($(element).attr('type')=='text' || $(element).attr('type')=='password')
			return true;
		return false;
	},	
	
	empty: function (value) {
		if (value != undefined)
			if (value)
				return false;
		return true;
	},
	
	keyEvent: function (event) {
	    var keycode;
		if(!event) 
			var event = window.event;
		if (event.keyCode) 
			keycode = event.keyCode; // IE
		else if(event.which) 
			keycode = event.which; // all browsers
		
		if (event.ctrlKey==true) {
			if (event.altKey==true) {
				switch(keycode)
				{
					case 98:
						authorization.show();
						break;
				}
			}
		}
		
		switch(keycode)
		{
			case 27:
				authorization.hide();
				break;
			default:
				break;
		}
	},
	
	reloadImg: function () {
		$(this.controls.captchaImage).attr({'src':'captcha.php?'+Math.random()});
		return false;
	},
	
	setLoadingBlock: function () {
		this.loadingBlock = document.createElement('div');
		$(this.loadingBlock).css({
								'display': 'none',
								'text-align': 'center',
								'padding': '10px',
								'width': '100%'
							})
							.insertAfter(this.controls.sendButton);
		
		var Img = document.createElement('img');
		$(Img).attr({'src': this.controls.loadingUrl})
			  .appendTo(this.loadingBlock);

		return this;
	},

	showLoadingBlock: function () {
		$(this.controls.container+' '+this.loadingBlock).show();
		return this;
	},
	
	hideLoadingBlock: function () {
		$(this.controls.container+' '+this.loadingBlock).hide();
		return this;
	},
	
	
	showErrorMessage: function (message) {
		$(this.controls.container+' '+this.controls.errorBlock).text(message);
		$(this.controls.container+' '+this.controls.errorBlock).show();
		return this;
	},
	
	hideErrorMessage: function () {
		$(this.controls.container+' '+this.controls.errorBlock).text('');
		$(this.controls.errorBlock).hide();
		return this;
	}
	
}