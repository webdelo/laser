$(function(){

	var changePartner = new selects;
	changePartner
		.setSettings({'element' : '.changePartner', 'event': 'change' })
		.setCallback(function (response) {
			if ( typeof response === 'number' ){
				$('[name=cashRate]').val(response);

				$('[name=partnerConfirmed] [value="0"]').attr('selected', 'selected');
				$((new orderHandler).sources.showOrderHistoryButton).addClass('hide');
				$('.partnerConfirmedLi').addClass('orderNotConfirmedLi');
				$('#partnerConfirmed0').html('не принял');
				$('.alertPartner').css('display', 'inline-block');
				$('.messagePartner').hide();
			}
			else
				alert(response);
		})
		.init();


	$('.deliveryPrice').live('blur',function(){
		var income = $(this).val() - $('.deliveryBasePrice').val();
		$('.deliveryIncome').text(income);
	});
	$('.deliveryBasePrice').live('blur',function(){
		var income = $('.deliveryPrice').val() - $(this).val();
		$('.deliveryIncome').text(income);
	});

	//$('.teledit').inputmask("mask", {"mask": "+9 (999) 999[-99-99 доб. 99999]",greedy: false});

	$('.transformer.teledit').focus(function() {
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

	var deleteDelivery = new buttons();
	deleteDelivery
		.setSettings({
			'element' : '.deleteOrderDelivery'
		})
		.setCallback(function (response) {
//			if (response === 1)
			if(typeof response == 'number'  &&  data > 0)
				return (new order).resetOrderGoodsTableBlock();

			alert('Delete delivery Error!');
 		})
		.init();

	(new orderHandler())
		.clickAddGoodButton()
		.clickDeleteGoodButton()
		.clickEditGoodButton()
		.clickEditGoodActionButton()
		.addPromoCodeToOrder()
		.removePromoCodeToOrder()
		.initAjaxHistoryModal()
		.managerConfirmOrder();

	$('[name=partnerConfirmed]').change(function (){
		$((new orderHandler).sources.showOrderHistoryButton).removeClass('hide');
		if( $('[name=partnerConfirmed] :selected').val() == 0 )
			$('.partnerConfirmedLi').addClass('orderNotConfirmedLi');
		else
			$('.partnerConfirmedLi').removeClass('orderNotConfirmedLi');
	});

	if( $('[name=partnerConfirmed] :selected').val() == 0 )
		if( $('.isAuthorisatedUserAnManager').val() == 'true' ){
			if ($('.partnerNotifyConfirm').val() == 'partnerNotifyConfirm')
				(new orderHandler()).showOrderNotConfirmedNotifyModal();
			else
				(new orderHandler()).showOrderNotConfirmedModal();
		}

});

var orderHandler = function()
{
	this.sources = {
		'addGoodButton' : '#addGoodToOrder',
		'orderId' : '.objectId',
		'goodId' : 'input[name=goodId]',
		'quantity' : 'input[name=quantity]',
		'price' : 'input[name=price]',
		'basePrice' : 'input[name=basePrice]',
		'goodDescription' : 'textarea[name=goodDescription]',
		'goodsTableBlock' : '.goodsTable',
		'deleteGoodButton' : '.deleteOrderGood',
		'editGoodButton' : '.editOrderGood',
		'priceInput' : 'input[name=editPrice]',
		'basePriceInput' : 'input[name=editBasePrice]',
		'quantityInput' : 'input[name=editQuantity]',
		'goodDescriptionHidden' : 'textarea[name=goodDescriptionHidden]',
		'normalView' : '.normalView',
		'editView' : '.editView',
		'editGoodActionButton' : '.editOrderGoodAction',
		'addPromoCodeButton' : '#addPromoCode',
		'promoCodeInput' : '#promoCodeInput',
		'removePromoCodeButton' : '#deleteOrderPromoCode',
		'showOrderHistoryButton' : '.showOrderHistory',
		'managerConfirmOrderButton' : '.managerConfirmOrder'
	};

	this.ajaxLoader = new ajaxLoader();

	this.clickAddGoodButton = function()
	{
		var that = this;
		$(that.sources.addGoodButton).live('click',function(){
			(new order).addGood();
		});
		return this;
	};

	this.clickDeleteGoodButton = function()
	{
		var that = this;
		$(that.sources.deleteGoodButton).live('click',function(){
			(new order).deleteGood( getGoodIdByDomObject( this ) );
		});
		return this;
	};

	this.clickEditGoodButton = function()
	{
		var that = this;
		$(that.sources.editGoodButton).live('click',function(){
			(new order).showEditGoodDomElements( getGoodIdByDomObject( this ) );
		});
		return this;
	};

	this.clickEditGoodActionButton = function()
	{
		var that = this;
		$(that.sources.editGoodActionButton).live('click',function(){
			(new order).editGood( getGoodIdByDomObject(this) );
		});
		return this;
	};

	this.addPromoCodeToOrder = function()
	{
		var that = this;
		$(that.sources.addPromoCodeButton).live('click',function(){
			(new order).addPromoCodeToOrder($(that.sources.promoCodeInput).val(), that.resetPromoCodeInput);
		});
		return this;
	};

	this.resetPromoCodeInput = function()
	{
		$((new orderHandler).sources.promoCodeInput).attr('value', '');
		return this;
	};

	this.removePromoCodeToOrder = function()
	{
		var that = this;
		$(that.sources.removePromoCodeButton).live('click',function(){
			(new order).removePromoCodeFromOrder();
		});
		return this;
	};

	this.initAjaxHistoryModal = function()
	{
		var that = this;
		(new ajaxModal).init({
			'button': that.sources.showOrderHistoryButton,
			'dialog' : {
				'title' : 'История оповещений партнера',
				'modal' : true,
				'zIndex': 400,
				'width' : 'auto',
				'height' : '650',
				'buttons' : {
					"Закрыть" : function () {
						$(this).dialog('close');
					}
				}
			}
		});

		$('.ui-dialog').live("dialogclose", function(){
			$('.modalContainer').remove();
		});

		return this;
	};

	this.showOrderNotConfirmedNotifyModal = function()
	{
		var that = this;
		var content = '<div>\n\
						Спасибо!<br /><br />\n\
						Заказ подтвержден.<br /><br />\n\
						Приятной дальнейшей работы.\n\
					</div>';

		$(content).dialog({
			'title' : 'Подтверждение заказа',
			'modal' : true,
			'zIndex': 400,
			'width' : 'auto',
			'height' : 'auto',
			'buttons' : {
				"Закрыть" : function () {
					(new order).managerConfirmOrder();
					$(this).dialog('close');
				}
			}
		});
	};

	this.showOrderNotConfirmedModal = function()
	{
		var that = this;
		var content = '<div>\n\
						Внимание!<br /><br />\n\
						Заказ не подтвержден.<br /><br />\n\
						Пожалуйста, подтвердите заказ.\n\
					</div>';

		$(content).dialog({
			'title' : 'Подтверждение заказа',
			'modal' : true,
			'zIndex': 400,
			'width' : 'auto',
			'height' : 'auto',
			'buttons' : {
				"Подтвердить сейчас" : function () {
					(new order).managerConfirmOrder();
					$(this).dialog('close');
				},
				"Подтвердить позднее" : function () {
					$(this).dialog('close');
				}
			}
		});
	};

	this.managerConfirmOrder = function()
	{
		var that = this;
		$(that.sources.managerConfirmOrderButton).live('click',function(){
			(new order).managerConfirmOrder();
		});
		return this;
	};
};

var getGoodIdByDomObject = function(object)
{
	return $(object).parent().parent().parent().attr('data-id');
};