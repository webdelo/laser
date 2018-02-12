$(function(){
	try {
		(new quickOrderHandler)
		.openQiuckOrderForm()
		.closeQiuckOrderForm()
		.sendQiuckOrderForm();
	} catch (e) {
		alert(e.message);
	}
});

var quickOrderHandler = function () {
	this.quickOrderObject = new quickOrder;
	this.loader = new ajaxLoader();

	this.sources = {
		'openQuickOrderFormButton'  : '.openQuickOrderForm',
		'closeQuickOrderFormButton' : '#closeQuickOrderFormButton, .body-main',
		'quickOrderBlock'           : '.main-modal, .body-main',
		'sendQuickOrderFormButton'  : '#sendQuickOrderFormButton',
		'quickOrderFormId'          : '#quickOrderForm',
		'quickOrderFormBlock'       : '#quickOrderFormBlock',
		'quickOrderSuccess'         : '#quickOrderSuccess'
	};

	this.openQiuckOrderForm = function () {
	var that = this;
	$(this.sources.openQuickOrderFormButton).live('click', function(){
		that.quickOrderObject.openForm($(this), that.hideLoader);
	});
	return this;
	};

	this.hideLoader = function () {
		this.loader.getElement();
	};

	this.closeQiuckOrderForm = function () {
		var that = this;
		$(this.sources.closeQuickOrderFormButton).live('click', function(){
			$(that.sources.quickOrderBlock).remove();
			that.quickOrderObject.resetErrors();
		});
		return this;
	};

	this.sendQiuckOrderForm = function () {
		var that = this;
		$(this.sources.sendQuickOrderFormButton).live('click', function(){
			that.quickOrderObject.sendForm();
		});
		return this;
	};

};