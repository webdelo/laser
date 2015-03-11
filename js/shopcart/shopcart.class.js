var shopcart = function (sources) {

	this.ajax = {
		'addToShopcart' : '/shopcart/ajaxAddGood/',
		'getShopcartBar' : '/shopcart/ajaxGetShopcartBar/',
		'removeFromShopcart' : '/shopcart/ajaxRemoveGood/',
		'getShopcartGoodsTable' : '/shopcart/ajaxGetShopcartGoodsTableContent/',
		'changeQuantity' : '/shopcart/ajaxChangeQuantity/',
		'validateQuantity' : '/shopcart/ajaxValidateQuantity/',
		'getTemplateContent' : '/shopcart/ajaxGetTemplateContent/',
		'saveGuestShopcart' : '/shopcart/ajaxDelAuthorizatedShopcartSaveGuestShopcart/',
		'sendOrder' : '/order/add/',
		
		'saveGuestShopcartToAuthorizatedShopcart' : '/shopcart/ajaxSaveGuestShopcartToAuthorizatedShopcart/',
		'checkShopcartStatusAskSaving' : '/shopcart/ajaxCheckShopcartStatusAskSaving/',
		'saveStep2' : '/shopcart/saveStep2/',
		'controller' : '/shopcart/',
		
		'addDelivery' : '/order/ajaxAddDeliveryToOrder/'
	};

	this.loader = new ajaxLoader();

	this.errors  = new errors({
		'form' : '.main',
		'error'   : '.hint',
		'showMessage' : 'showMessage'
	});

	this.errorsChangeQuantity  = new errors({
		'form'	:	'.basket',
		'error'   : '.hint',
		'showMessage' : 'showMessage'
	});

	this.errorsRegistration  = new errors({
		'form'	:	'.r-b-2',
		'error'   : '.hint',
		'showMessage' : 'showMessage'
	});
	
	this.errorsLogin  = new errors({
		'element'	:	'.e-t-s-2',
		'error'   : '.hint',
		'showMessage' : 'showMessage'
	});

	this.errorsSavePersonalData  = new errors({
		'form'	:	'.dostavka',
		'error'   : '.hint',
		'showMessage' : 'showMessage'
	});

	this.errorsSendOrder  = new errors({
		'form'	:	'.basket_in',
		'error'   : '.hint',
		'showMessage' : 'showMessage'
	});

	this.addToShopcart = function (object) {
		var that = this;
		that.loader.setLoader( object );
		var quantity = 1;
		$.ajax({
			url: that.ajax.addToShopcart,
			type: 'POST',
			data: {
				'objectId' : $(object).attr('data-objectId'),
				'objectClass' : $(object).attr('data-objectClass'),
				'quantity' : quantity,
			},
			dataType: 'json',
			success: function(data){
				that.loader.getElement();
				if(data == 1){
					that.errors.reset();
					that.updateShopcartBar();
				}
				else
					that.errors.show(data);
			}
		});
	};

	this.updateShopcartBar = function()
	{
		var that = this;
		shopcartBar$ = $((new shopcartHandler()).sources.shopcartBar);
		$.ajax({
			url: that.ajax.getShopcartBar,
			type: 'POST',
			success: function(data){
				if(data)
					shopcartBar$.replaceWith(data);
				else
					alert('Error while trying to update shopcart bar');
			}
		});

	}

	this.removeFromShopcart = function (object) {
		var that = this;
		that.loader.setLoader( object );
		$.ajax({
			url: that.ajax.removeFromShopcart,
			type: 'POST',
			data: {
				'goodId' : $(object).attr('data-goodId'),
				'goodClass' : $(object).attr('data-goodClass'),
				'goodCode' : $(object).attr('data-goodCode'),
			},
			success: function(data){
				that.loader.getElement();
				if(data) {
					that.updateShopcartGoodsTable();
					that.updateShopcartBar();
				}
				else
					alert('Error while trying to delete good from shopcart');
			}
		});
	};

	this.updateShopcartGoodsTable = function () {
		var that = this;
		$.ajax({
			url: that.ajax.getShopcartGoodsTable,
			type: 'POST',
			data: {},
			dataType: 'html',
			success: function(data){
				if(data){
					$('.shopcartContent').html(data);
				}
				else
					alert('Error while trying to get shopcart goods table');
			}
		});
	};

	this.changeQuantity = function (object) {
		var that = this;
		that.loader.setLoader( object );
		$.ajax({
			url: that.ajax.validateQuantity,
			type: 'POST',
			dataType: 'json',
			data: {
				'goodId' : $(object).attr('data-goodId'),
				'goodClass' : $(object).attr('data-goodClass'),
				'goodCode' : $(object).attr('data-goodCode'),
				'quantity' : $(object).attr('data-quantity'),
			},
			success: function(data){
				that.loader.getElement();
				if(data == 1){
					that.errorsChangeQuantity.reset();
					that.changeQuantityAction(object);
				}
				else
					that.errorsChangeQuantity.show(data);
			}
		});
	};

	this.changeQuantityAction = function (object) {
		var that = this;
		$.ajax({
			url: that.ajax.changeQuantity,
			type: 'POST',
			data: {
				'goodId' : $(object).attr('data-goodId'),
				'goodClass' : $(object).attr('data-goodClass'),
				'goodCode' : $(object).attr('data-goodCode'),
				'quantity' : $(object).attr('data-quantity'),
			},
			success: function(data){
				if(data == 1) {
					that.updateShopcartGoodsTable();
					that.updateShopcartBar();
				}
				else
					alert('Error while trying to change quantity in shopcart');

			}
		});
	};

	this.getTemplateContent = function (object, template, block) {
		var that = this;
		that.loader.setLoader(object);
		$.ajax({
			url: that.ajax.getTemplateContent,
			type: 'POST',
			dataType: 'json',
			data: {
				'template' : template,
			},
			success: function(data){
				that.loader.getElement();
				if(data){
					$(block).html(data);
					that.errors.reset();
				}
				else
					alert('Error while trying to get Content in shopcart');
			}
		});
	};

	this.authorizateInShopcart = function(object)
	{
		var that = this;
		var data = {
			'login' : $('.e-t-s-2 input[name="login"]').val(),
			'password' : $('.e-t-s-2 input[name="password"]').val(),
			'authorization_client_submit' : $('.e-t-s-2 .authfield').val(),
			'cookie' : $('.e-t-s-2 .rememberfield').val(),
		};

		$.ajax({
			url: '/authorization/ajaxAuthorization/',
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function(data){
				if($.isNumeric(data)){
					that.errorsLogin.reset();
					$('.aut_cart').hide('fast');
					that.getTemplateContent(object, 'personalData','.personal_data_main');
					$('.personal_data_main').show('fast');
					that.saveGuestShopcart();
				}
				else
					//that.errorsLogin.show(data);
					$('.errorMessage').text(data.errorMessage).fadeIn(100).delay(1500).fadeOut(100);
			}
		});
	}

	this.getRegistrationBlock = function(object)
	{
		object.next().show();
		object.hide();
		$('.zaglushka').show();
	}

	this.registrateInShopcart = function(object)
	{
		var that = this;
		var data = {
			'login' : $('.r-b-2 input[name="login"]').val(),
			'password' : $('.r-b-2 input[name="password"]').val(),
			'passwordConfirm' : $('.r-b-2 input[name="passwordConfirm"]').val(),
			'authorization_client_submit' : $('.r-b-2 .registratefield').val(),
			//'cookie' : $('.r-b-2 .rememberfield').val(),
		};
		$.ajax({
			url: '/clients/ajaxAdd/',
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function(data){
				if( $.isNumeric(data) ){
					that.errorsRegistration.reset();
					$('.aut_cart').hide('fast');
					that.getTemplateContent(object, 'personalData','.personal_data_main');
					$('.personal_data_main').show('fast');
					that.saveGuestShopcart();
				}
				else
					that.errorsRegistration.show(data);
			}
		});
	}

	this.getConfirmationContent = function(object){
		var that = this;
		that.getTemplateContent(object, 'confirmation','.ShopcartConfirmContent');
	}
	
	this.saveClientDataContinue = function(object){
		var that = this;
		var data = {
			'name' : $('.personal_data_main input[name="name"]').val(),
			'patronimic' : $('.personal_data_main input[name="patronimic"]').val(),
			'surname' : $('.personal_data_main input[name="surname"]').val(),
			'phone' : $('.personal_data_main input[name="phone"]').val(),
			'mobile' : $('.personal_data_main input[name="mobile"]').val()
		};
		$.ajax({
			url: (new cabinet()).actions.ajaxSavePersonalData,
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function(data){
				that.loader.getElement();
				that.errors.reset();
				if(typeof data == 'number'  &&  data > 0){
					that.errorsSavePersonalData.reset();
					//that.getTemplateContent(object, 'total');
					Step1ClickFunc();
				}
				else{
					if(data.name)
						data.name = 'Укажите Ваше имя';
					that.errorsSavePersonalData.show(data);
				}
			}
		});
	}
	
	this.addDeliveryToOrder = function(){
		var that = this;
		var data = {
			'orderId' : $('.orderId').val(),
			'flexibleAddress' : '1',
			'deliveryCategoryId' : '18',
			'deliveryId' : '234',
			'country' : $('.personal_data_delivery input[name="country"]').val(),
			'region' : $('.personal_data_delivery input[name="region"]').val(),
			'city' : $('.personal_data_delivery input[name="city"]').val(),
			'street' : $('.personal_data_delivery input[name="street"]').val(),
			'home' : $('.personal_data_delivery input[name="home"]').val(),
			'block' : $('.personal_data_delivery input[name="block"]').val(),
			'flat' : $('.personal_data_delivery input[name="flat"]').val(),
			'index' : $('.personal_data_delivery input[name="index"]').val(),
		};
		$.ajax({
			url: that.ajax.addDelivery,
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function(data){
				that.loader.getElement();
				that.errors.reset();
				if(typeof data == 'number'  &&  data > 0){
					that.errorsSavePersonalData.reset();
				}
				else{
					if(data.name)
						data.name = 'Укажите Ваше имя';
				}
			}
		});
	}
	
	this.saveDeliveryDataContinue = function(object){
		var that = this;
		var data = {
			'country' : $('.personal_data_delivery input[name="country"]').val(),
			'region' : $('.personal_data_delivery input[name="region"]').val(),
			'city' : $('.personal_data_delivery input[name="city"]').val(),
			'street' : $('.personal_data_delivery input[name="street"]').val(),
			'home' : $('.personal_data_delivery input[name="home"]').val(),
			'flat' : $('.personal_data_delivery input[name="flat"]').val(),
			'index' : $('.personal_data_delivery input[name="index"]').val()
		};
		$.ajax({
			url: (new cabinet()).actions.saveDeliveryData,
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function(data){
				that.loader.getElement();
				that.errors.reset();
				if(typeof data == 'number'  &&  data > 0){
					that.errorsSavePersonalData.reset();
					//that.getTemplateContent(object, 'total');
					Step2ClickFunc();
				}
				else{
					if(data.name)
						data.name = 'Укажите Ваше имя';
					that.errorsSavePersonalData.show(data);
				}
			}
		});
	}
	
	this.saveGuestShopcart = function(){
		var that = this;
		$.ajax({
			url: that.ajax.saveGuestShopcart,
			type: 'POST',
			dataType: 'json',
			success: function(data){
				that.loader.getElement();
			}
		});
	}

	this.sendOrderGetSuccessBlock = function(object){
		var that = this;
		that.loader.setLoader(object);
		var data = {
			'conditions' : '',
			'cashPayment' : $("select[name='PaymentType']").val(),
		};
		$.ajax({
			url: that.ajax.sendOrder,
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function(data){
				that.loader.getElement();
				if(data){
					if( $.isNumeric(data.result) ){
						that.errorsSendOrder.reset();
						$('.ShopcartConfirmContent').hide('fast');
						$('.ShopcartConfirmContent').html(data.content);
						$('.ShopcartConfirmContent').show('fast');
						that.addDeliveryToOrder();
					}
					else
						that.errorsSendOrder.show(data.content);
				}
				else
					alert('Error while trying to send order');
			}
		});
	}

	this.saveGuestShopcartToAuthorizatedShopcart = function(){
		var that = this;
		var data = {};
		$.ajax({
			url: that.ajax.saveGuestShopcartToAuthorizatedShopcart,
			type: 'POST',
			dataType: 'json',
			success: function(data){}
		});
	}


	this.checkShopcartStatusAskSaving = function()
	{
		var that = this;
		$.ajax({
			url: that.ajax.checkShopcartStatusAskSaving,
			type: 'POST',
			dataType: 'json',
			success: function(data){
				if(data){
					$('.pop').show();
					$('.placeForShopcart').after(data);
				}
			}
		});
	}

	this.shopcartAction = function(action)
	{
		var that = this;
		$.ajax({
			url: that.ajax.controller + action.attr('data-action') + '/',
			type: 'POST',
			dataType: 'json',
			success: function(data){}
		});
		that.closeAskModal();
	}

	this.closeAskModal = function()
	{
		$('.pop').hide();
		$('.modal2').remove();
	}
	
	Step1ClickFunc = function()
	{
		$('.Step1Content').hide(400);
		setTimeout(function() { 
			$('.ShopcartStep1').removeClass('active').addClass('travel');
			$('.ShopcartStep2').removeClass('travel').addClass('active');
			$('.Step1Content').fadeIn(500);
		}, 450); 
		setActiveStep(2);
	}
	
	Step2ClickFunc = function()
	{
		$('.Step2Content').hide(400);
		setTimeout(function() { 
			$('.ShopcartStep2').removeClass('active').addClass('travel');
			$('.ShopcartStep3').removeClass('travel').addClass('active');
			$('.Step2Content').fadeIn(500);
		}, 450); 
		setActiveStep(3);
	}
	
	setActiveStep = function (step) {
		
		switch (step) {
		  case 1:
			$('.first-step').removeClass('active').addClass('travel');
			if ($('.first-step').hasClass('active')) { $('.second-step').removeClass('active'); }
			if ($('.first-step').hasClass('travel')) { $('.second-step').removeClass('travel'); }
			$('.first-step').addClass('active');
			break
		  case 2:
			$('.first-step').removeClass('active').addClass('travel');
			$('.second-step').addClass('active');
			$('.CurrentStep').text('2');
			break
		  case 3:
			$('.second-step').removeClass('active').addClass('travel');
			$('.third-step').addClass('active');
			$('.CurrentStep').text('3');
			break
		}

	};
	
	$('.teledit').focus(function() {
		$(this).inputmask("mask", {"mask": "+9 (999) 999-99-99 доб. 99999"});
		}).focusout(function() {
		$(this).inputmask('remove');
		var targ = $(this).val();

		if (targ.length > 11)
			targ = targ.replace(/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})(\d{1})/, "+$1 ($2) $3-$4-$5 доб. $6");
		else if (targ.length === 11)
			targ = targ.replace(/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/, "+$1 ($2) $3-$4-$5");
		else if (targ.length > 9 && targ.length < 11)
			targ = targ.replace(/(\d{1})(\d{3})(\d{3})(\d{2})(\d{1})/, "+$1 ($2) $3-$4-$5");
		else if (targ.length > 7 && targ.length <= 9)
			targ = targ.replace(/(\d{1})(\d{3})(\d{3})(\d{1})/, "+$1 ($2) $3-$4");
		else if (targ.length > 4 && targ.length < 8)
			targ = targ.replace(/(\d{1})(\d{3})(\d{1})/, "+$1 ($2) $3");

		$(this).val(targ);
	});
	
}