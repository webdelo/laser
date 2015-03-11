ajaxModal = function (settings) {
	var that = this;
	
	this.cancel = function () {
		$('.'+that.settings.containerClass).remove();
		$( this ).dialog( "close" );
	}
	
	this.settings = $.extend({
		'button'    : '.getModalDialog',
		'containerClass'  : 'modalContainer',
		'containerParent' : 'body',
		'dialog'          : {
			'close' : this.cancel,
			'modal' : true,
			'width' : '750px',
			'buttons' : {
				Cancel: this.cancel,
				'Save' : function () {
					$('.formAddSubmit').click();
				}
			}
		}
	} , settings||{});
	
	this.setSettings = function (settings) {
		this.settings = $.extend(this.settings, settings||{});
	}
	
	this.loader = new loader();
	
	this.init = function (settings) {
		this.setSettings(settings);
		var that = this;
		$(this.settings.button).click(function () {
			that.loader.start();
			that.setButton($(this))
				.getContainer();
			return false;
		});
		
		return this;
	}
	
	this.setButton = function (button$) {
		this.button$ = button$;
		return this;
	}
	
	this.getContainer = function () {
		var that = this;
		var action = that.settings.containerSource
		if ( this.button$.data('id') != undefined ) 
			action += '?order_id=' + this.button$.data('id');
		else if ( this.button$.data('action') != undefined )
			action = this.button$.data('action');
		
		$.ajax({
			beforeSend: $.proxy(that.beforeSend, that),
			error: $.proxy(that.error, that),
			url: action,
			type: 'get',
			success: $.proxy(that.success, that),
			complete: $.proxy(that.complete, that) 
		});
		
		return this;
	}
	
	this.beforeSend = function () {
		this.buttonText = this.button$.text();
		this.button$.find('.ui-button-text').html($('<img src="/admin/images/loaders/horizontal-loader.gif" width="80px" style="margin: 4px 0">'));
	}
	
	this.error = function () {
		alert("ERROR!\r\nInvalid Operation: "+this.action+".\r\nCall developers!");
	}
	
	this.success = function (response) {
		this.createContainer()
			.appendContainer()
			.initContainer(response);
		
		if ($.isFunction(this.settings.callback))
			this.settings.callback();
		
		return this;
	}
	
	this.createContainer = function () {
		this.container$ = $('<div class="'+this.settings.containerClass+'">').css({'display':'none'});	
		return this;
	}
	
	this.appendContainer = function () {
		$(this.settings.containerParent).append(this.container$);
		return this;
	}
	
	this.initContainer = function (contents) {
		this.container$
			.append(contents)
			.dialog(this.settings.dialog)
			.show();
			
		this.container$.find('.formAddSubmit').css({'display' : 'none'});
			
		return this;
	}
	
	this.complete = function (response) {
		this.loader
			.title('')
			.stop();
			
		this.resetButton();
		
		return this;
	}
	
	this.resetButton = function () {
		this.button$.find('.ui-button-text').text(this.buttonText);
	}
	
}