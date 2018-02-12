var quickOrder = function () {

	this.ajax = {
		'getQuickOrderBlock' : '/order/ajaxGetQuickOrderBlock/',
		'sendQuickOrderForm' : '/order/ajaxSendQuickOrderForm/'
	};

	this.loader = new ajaxLoader();
	this.errors  = new errors({
			'form'	:	'#quickOrderForm',
			'error'   : '.hint',
			'showMessage' : 'showMessage'
	});

	this.setCallback = function (callback) {
		if($.isFunction(callback)) {
			this.callback = callback;
		}
		return this;
	};

	this.callCallback = function (response) {
		if($.isFunction(this.callback))
			this.callback(response);
		return this;
	};

	this.openForm = function (object, callback) {
		var that = this;
		var data = {'goodId' : object.data('good_id')};
		that.setCallback(callback);

		$.ajax({
			url: that.ajax.getQuickOrderBlock,
			type: 'POST',
			data: data,
			dataType: 'html',
			success: function(response){
				(new simpleModal).open(response);
				that.callCallback();
			}
		});
	};

	this.sendForm = function (object, callback) {
		var that = this;
		var data = $((new quickOrderHandler).sources.quickOrderFormId).serialize();
		that.loader.setLoader((new quickOrderHandler).sources.sendQuickOrderFormButton);
		that.setCallback(callback);

		$.ajax({
			url: that.ajax.sendQuickOrderForm,
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function(response){
				that.loader.getElement();
				if(response == 1){
					that.resetErrors();
					that.viewSuccessBlock();
					that.callCallback();
				} else {
					that.errors.show(response);
				}
			}
		});
	};

	this.viewSuccessBlock = function (){
		$((new quickOrderHandler).sources.quickOrderFormBlock).hide();
		$((new quickOrderHandler).sources.quickOrderSuccess).show();
	};

	this.resetErrors = function (){
		this.errors.reset();
	};
};