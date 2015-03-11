$(function(){
	try {
		(new shopcartHandler)
		.addGoodInShopcart()
		.removeGoodFromShopcart()
		.onChangeQuantity()
		.onGetAuthorizationContent()
		.onGetMyOrderContent()
		.onGetDeliveryContent()
		.authorizateInShopcart()
		.registrateInShopcart()
		.saveClientDataContinue()
		.saveDeliveryDataContinue()
		.getConfirmationContent()
		.getPayerBlock()
		.sendOrderGetSuccessBlock()
		.birthdaySelectChange()
		.closeAskModal()
		.shopcartAction()
	} catch (e) {
		alert(e.message);
	}
});

var shopcartHandler = function () {

	this.shopcartObject = new shopcart;

	this.sources = {
		'addToShopcartButton'      : '.addToShopcart',
		'catalogQuantity' : '.catalogQuantity',
		'shopcartBar' : '.cart',
		'removeFromShopcartButton' : '.removeFromShopcart',
		'changeQuantity' : '.changeQuantityButton',
		'getAuthorizationContentButton' : '.getAuthorizationContent',
		'getMyOrderContentButton' : '.getMyOrderContent',
		'getDeliveryContentButton' : '.getDeliveryContent',
		'authorizateInShopcartButton' : '.authorizateInShopcart',
		'registrateInShopcartButton' : '.registrateInShopcart',
		'saveDeliveryGetPayerContentButton' : '.saveDeliveryGetPayerContent',
		'savePayerGetTotalContentButton' : '.savePayerGetTotalContent',
		'saveClientDataContinue' : '.saveClientDataContinue',
		'saveDeliveryDataContinue' : '.saveDeliveryDataContinue',
		'getConfirmationContent' : '.getConfirmationContent',
		'getPayerBlockButton' : '.getPayerBlock',
		'sendOrderGetSuccessBlockButton' : '.sendOrderGetSuccessBlock',
		'birthdaySelect' : '.birthdaySelect',
		'closeAskModalButton' : '.closeAskModal',
		'shopcartActionButton' : '.shopcartAction',
	};

	this.ajaxLoader = new ajaxLoader();

	this.addGoodInShopcart = function () {
		var that = this;
		$(that.sources.addToShopcartButton).live('click', function(){
			that.shopcartObject.addToShopcart($(this));
		});
		return this;
	};
	
	this.removeGoodFromShopcart = function () {
		var that = this;
		$(that.sources.removeFromShopcartButton).live('click', function(){
			that.shopcartObject.removeFromShopcart($(this));
		});
		return this;
	};

	this.onChangeQuantity = function () {
		var that = this;
		$(that.sources.changeQuantity).live('click', function(){
			that.shopcartObject.changeQuantity($(this));
		});
		return this;
	};

	this.onGetAuthorizationContent = function () {
		var that = this;
		$(that.sources.getAuthorizationContentButton).live('click', function(){
			that.shopcartObject.getTemplateContent($(this), 'authorization');
		});
		return this;
	};

	this.onGetMyOrderContent = function () {
		var that = this;
		$(that.sources.getMyOrderContentButton).live('click', function(){
			that.shopcartObject.getTemplateContent($(this), 'myOrder');
		});
		return this;
	};

	this.onGetDeliveryContent = function () {
		var that = this;
		$(that.sources.getDeliveryContentButton).live('click', function(){
			that.shopcartObject.getTemplateContent($(this), 'delivery');
		});
		return this;
	};

	this.authorizateInShopcart = function () {
		var that = this;
		$(that.sources.authorizateInShopcartButton).live('click', function(){
			that.shopcartObject.authorizateInShopcart($(this));
		});
		return this;
	};

	this.registrateInShopcart = function () {
		var that = this;
		$(that.sources.registrateInShopcartButton).live('click', function(){
			that.shopcartObject.registrateInShopcart($(this));
		});
		return this;
	};

	this.saveClientDataContinue = function () {
		var that = this;
		$(that.sources.saveClientDataContinue).live('click', function(){
			that.shopcartObject.saveClientDataContinue($(this));
		});
		return this;
	};
	
	this.saveDeliveryDataContinue = function () {
		var that = this;
		$(that.sources.saveDeliveryDataContinue).live('click', function(){
			that.shopcartObject.saveDeliveryDataContinue($(this));
		});
		return this;
	};
	
	this.getConfirmationContent = function () {
		var that = this;
		$(that.sources.getConfirmationContent).live('click', function(){
			that.shopcartObject.getConfirmationContent($(this));
		});
		return this;
	};

	this.getPayerBlock = function () {
		var that = this;
		$(that.sources.getPayerBlockButton).live('click', function(){
			that.shopcartObject.getTemplateContent($(this), 'payer');
		});
		return this;
	};

	this.sendOrderGetSuccessBlock = function () {
		var that = this;
		$(that.sources.sendOrderGetSuccessBlockButton).live('click', function(){
			that.shopcartObject.sendOrderGetSuccessBlock($(this));
		});
		return this;
	};

	this.birthdaySelectChange = function () {
		var that = this;
		$(that.sources.birthdaySelect).live('change', function(){
			if( $('select[name=birthDate] :selected"').val() == 'Дата' )
				$('select[name=birthDate]').css('color', '#B0B0B4');
			else
				$('select[name=birthDate]').css('color', 'black');

			if( $('select[name=birthMonth] :selected"').val() == 'Месяц' )
				$('select[name=birthMonth]').css('color', '#B0B0B4');
			else
				$('select[name=birthMonth]').css('color', 'black');

			if( $('select[name=birthYear] :selected"').val() == 'Год' )
				$('select[name=birthYear]').css('color', '#B0B0B4');
			else
				$('select[name=birthYear]').css('color', 'black');
		});
		return this;
	};

	this.closeAskModal = function () {
		var that = this;
		$(that.sources.closeAskModalButton).live('click', function(){
			that.shopcartObject.closeAskModal();
		});
		return this;
	};

	this.shopcartAction = function () {
		var that = this;
		$(that.sources.shopcartActionButton).live('click', function(){
			that.shopcartObject.shopcartAction($(this));
		});
		return this;
	};
};





var initDeliveriesMethods = function()
{
	var deliveryCategories = new selects;
	deliveryCategories
		.setSettings({'element' : '.deliveryCategories', 'showError':false})
		.setCallback(function (response) {
			if (response != 1) {
				var delivery$ = $('.deliveries');
				delivery$.children().not(':first').remove();
				$.each(response, function(){
					delivery$.append($('<option></option>').val(this.value).text(this.name));
				});
				delivery$.fadeIn()
						.removeAttr('disabled')
						.children('option:first')
						.text(
							deliveryCategories.getChoosedObject()
											.data('next_step_name')
						);
			} else {
				$('.deliveries').html('').attr('disabled', 'disabled');
			}
			$('.deliveryAddressBlock').html('');
		})
		.init();

	var deliveries = new selects;
	deliveries
		.setSettings({'element' : '.deliveries'})
		.setCallback(function (response) {
			if (response != 1) {
				$('.deliveryAddressBlock').html(response);
			} else {
				$('.deliveryAddressBlock').fadeOut().html();
			}
		})
		.init();
}