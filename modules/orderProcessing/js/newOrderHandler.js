$(function(){
	var order = new orderProcessing;
	$.each( order.actions, function (method) {
		order.actions[method].call(order);
	});
	var orderMenu = new rightMenu();
	$.each( orderMenu.actions, function (method) {
		orderMenu.actions[method].call(orderMenu);
	});
});

var orderProcessing = function () {
	var that = this;

	this.actions= {
		'getParentObject' : function(){
			return that;
		},
		'deliveryDateInit' : function(){
			$('.dateEdit').datepicker({
				dateFormat: "dd-mm-yy",
				onSelect: function(){
					$(this).blur();
				}
			});

			return this;
		},
		'deliveryTimeInit' : function(){
			$('.timeEdit').inputmask("mask", {"mask": "с 99 до 99"});

			return this;
		},
		'buyerTelephoneInit' : function(){

			$('.clientDataDetails').on('focus',".editClientDetails.teledit", function() {
				$(this).inputmask("mask", {"mask": "+9 (999) 999-99-99 доб. 99999"});
			}).on('blur',".editClientDetails.teledit", function() {
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
			return this;
		},
		'addressBlock' : function(){
			$('.showAddressBlock').live('click', function (){
				$(this).hide();
				$('.hideAddressBlock').show();
				$('.addressBlock').fadeIn();
			});
			$('.hideAddressBlock').live('click', function (){
				$(this).hide();
				$('.showAddressBlock').show();
				$('.addressBlock').hide();
			});

			return that;
		},
		'initTabs' : function(){
			$('#tabs').tabs({
				activate: function( event, ui ) {
					var delivery$ = $($(this).attr('href')).find('input[name=deliveryPrice]');
					if ( delivery$.length > 0 )
						delivery$.trigger('textchange');
				}
			});
			return that;
		},
		'inputClick' : function(){
			$('.catalogClientDataBlock input[type=text]').focus(function(){
				if ( $(this).data('clicktext') && $(this).val() === '' )
					$(this).val($(this).data('clicktext'));
			}).blur(function(){
				if ( $(this).data('clicktext') === $(this).val() )
					$(this).val('');
			});
			return that;
		},
		'cancelConfirmation' : function(){
			$('.cancelConfirmation').live('click', function(){
				$('.orderSummary').html('');
			});
			return that;
		},
		'countryChoose' : function(){
			var that = this;
			var countries = new selects;
			countries
				.setSettings({'element' : 'select[name=country]'})
				.setCallback(function (response) {
					if ( typeof response !== 'number' )
						alert(response);
				})
				.init();
		},
		'deliveriesChoose' : function(){
			var that = this;
			var deliveries = new selects;
			deliveries
				.setSettings({'element' : '.deliveries'})
				.setCallback(function (response) {
					$('.deliveryDetailsBlock:visible').html(response);
					(new orderProcessing).reloadResultPriceBlock()
						.reloadGoodsTable()
						.reloadClientDataBlock()
						.reloadOrderSummaryBlock()
						.reloadDeliveryTable();
				})
				.init();
		},
		'autosugest' : function(){
			var that = this;
			$('.catalogGood').autoSuggest('/admin/orderProcessing/ajaxGetGoodsByName/', {
				selectedItemProp   : "name",
				searchObjProps     : "name",
				targetInputName    : 'catalogGoodId',//
				retrieveLimit: 20,
				resultClick: function(choosedRow){
					that.goodChoosed(choosedRow);
					$('.as-selections .as-close').click();
				}
			})
		},
		'deleteGood' : function () {
			var that = this;
			var deleteGood = new buttons;
			deleteGood.setSettings({
				'element' : '.deleteOrderGood'
			})
			.setLoader(new loaderLight)
			.setCallback(function (response) {
				if (typeof response === 'number')
					that.reloadGoodsTable();
				else
					alert(response);
			})
			.init();
		},
		'addPromo' : function () {
			var that = this;
			var addPromo = new form;
			addPromo.setSettings({'form'    : '.addPromo'})
					.setLoader(new loaderLight)
					.setCallback(function (response) {
						if (typeof response === 'number')
							that.reloadGoodsTable()
								.reloadOrderSummaryBlock();
						else
							alert(response);
					})
					.init();
		},
		'deletePromo' : function () {
			var that = this;
			var deleteClient = new buttons;
			deleteClient.setSettings({
				'element'    : '.deletePromoCode'
			})
			.setLoader(new loaderLight)
			.setCallback(function (response) {
				if (typeof response === 'number')
					that.reloadGoodsTable()
						.reloadOrderSummaryBlock();
				else
					alert(response);
			})
			.init();
		},
		'deleteClient' : function () {
			var that = this;
			var deleteClient = new buttons;
			deleteClient.setSettings({
				'element'    : '.deleteClient'
			})
			.setLoader(new loaderLight)
			.setCallback(function (response) {
				if (typeof response === 'number') {
					that.reloadClientDataBlock();
					$('.orderSummary').html('');
				} else
					alert(response);
			})
			.init();
		},
		'deleteOrder' : function () {
			var that = this;
			var deleteOrder = new buttons;
			deleteOrder.setSettings({
				'element'    : '.removeOrder'
			})
			.setLoader(new loaderLight)
			.setCallback(function (response) {
				if (typeof response === 'number') {
					location.href = '/admin/orders/';
				} else
					alert(response);
			})
			.init();
		},
		'checkoutMonitoring' : function () {
			var that = this;
			var checkoutOrder = new buttons;
			checkoutOrder.setSettings({
				'element'    : '.checkoutOrder'
			})
			.setCallback(function(response){
				if ( typeof response === "number" ){
					location.href = '/admin/orders/order/'+response+'/';
				}
			})
			.init();
		},
		'getSummary' : function () {
			var that = this;

			var summary = new buttons;
			summary.setSettings({
				'element'    : '.getSummary'
			})
			.setLoader(new loaderLight)
			.setCallback(function (response) {
				if (typeof response === 'number') {
					that.reloadClientDataBlock()
						.reloadOrderSummaryBlock();
					$('.orderSummary').autoScroll();
					if($('.getSummary').is(':hidden'))
						$('.orderSummary').hide();
				}
			})
			.init();

		},
		'priceWorking' : function () {
			$('input[name=price]').live({
				'focus': function(){
					$('.priceAdditionalInfo').stop( true, false ).fadeIn();
				},
				'blur': function(){
					$('.priceAdditionalInfo').stop( true, false ).fadeOut();
				}
			});
		}
	};

	this.radioMonitoring = function () {
		var radioBox = new inputs;
		radioBox.setSettings({
			'element' : '.radioBox',
			'event'   : 'click'
		}).setCallback(function (response) {
			if (typeof response !== 'number')
				alert(response);
		})
		.init();
	}

	this.searchClient = function() {
			var that = this;
			$('.clientSearch').autoSuggest('/admin/orderProcessing/ajaxSearchClient/', {
				selectedItemProp   : "name",
				searchObjProps     : "name",
				targetInputName    : 'clientId',
				secondItemAtribute : 'code',
				thirdItemAtribute  : 'price',
				fourthItemAtribute : 'basePrice',
				retrieveLimit: 20,
				resultClick: function(choosedRow){
					that.clientChoosed(choosedRow);
					$('.as-selections .as-close').click();
				}
			});
		};

	this.editInput = function(element$, callback){
		var that = this;
		var editInput = new inputs;
		editInput.setSettings({
			'element' : element$,
			'event'   : 'blur'
		}).setCallback($.proxy(callback, that))
		.init();


		return this;
	};

	this.setParametersAndProperties = function (orderProcessingGoodId, modalTitle) {
		var element = '<span href="/admin/orderProcessing/getOrderProcessingGoodDetails/?objectId='+ orderProcessingGoodId +' " target="blank" class="goodLink orderGoodDetailsModal" modalTitle="' + modalTitle + '"></span>';
		showOrderGoodDetailsModal($(element));
	};

	this.goodChoosed = function (choosedRow) {
		var goodId = $('input[name=catalogGoodId]').val();
		var action = ( this.isOrderStarted() ) ? '/admin/orderProcessing/secondAddingGoods/' : '/admin/orderProcessing/firstAddingGoods/' ;
		var that = this;
		$.ajax({
			url: action,
			data: {
				'orderId' : that.getOrderId(),
				'goodId'  : goodId,
				'class'   : choosedRow.attributes.class
			},
			type: 'post',
			dataType: 'json',
			async: false,
			success: function (data) {
				if ( !that.isOrderStarted() ) {
					$('#orderId').val(data.orderId);
					$('.catalogGoods').data('source', $('.catalogGoods').data('source') + data.orderId );
					$('.domainsTable').data('source', $('.domainsTable').data('source') + data.orderId );
					$('.catalogDelivery').data('source', $('.catalogDelivery').data('source') + data.orderId );
					$('.catalogResultPriceBlock').data('source', $('.catalogResultPriceBlock').data('source') + data.orderId );
					$('.catalogClientDataBlock').data('source', $('.catalogClientDataBlock').data('source') + data.orderId );
					$('.orderSummary').data('source', $('.orderSummary').data('source') + data.orderId );
				}
				that.reloadGoodsTable()
					.reloadDomainsTable()
					.reloadDeliveryTable()
					.reloadResultPriceBlock();

				if(data.isHasParametersOrProperties)
					that.setParametersAndProperties(data.goodId, data.modalTitle);
			},
			error: function(){
				alert('System has a problem with choose the good!');
			}
		});
	};

	this.clientChoosed = function (choosedRow) {
		var clientId = $('input[name=clientId]').val();
		var that = this;
		var orderData = {
			'orderId'  : that.getOrderId(),
			'clientId' : clientId
		};
		var onSuccess = function (response) {
			if ( typeof response !== "number" )
				alert(response);
			else
				that.reloadClientDataBlock();
		};
		var onError = function(){
			alert('System has a problem with choose the good!');
		};
		$.ajax({
			url: '/admin/orderProcessing/saveClientInOrder/',
			data: orderData,
			type: 'post',
			dataType: 'json',
			success: onSuccess,
			error: onError
		});
	};

	this.getOrderId = function() {
		return $('#orderId').val();
	};

	this.isOrderStarted = function () {
		var value = this.getOrderId();
		return ( value !== undefined && value !== '' );
	};

	this.reloadGoodsTable = function ( callback ) {
		$('.catalogGoods').htmlFromServer({ 'callback' : callback, 'loader': new loaderBlock });
		return this;
	};

	this.reloadDomainsTable = function (  ) {
		$('.domainsTable').htmlFromServer({ 'loader': new loaderBlock });

		return this;
	};

	this.reloadDeliveryTable = function (  ) {
		$('.catalogDelivery').htmlFromServer({ 'loader': new loaderBlock });
		return this;
	};

	this.reloadResultPriceBlock = function (  ) {
		$('.catalogResultPriceBlock').htmlFromServer({ 'loader': new loaderBlock });
		return this;
	};
	this.reloadClientDataBlock = function ( callback ) {
		$('.catalogClientDataBlock').htmlFromServer({ 'callback' : callback, 'loader': new loaderBlock });
		return this;
	};
	this.reloadClientDetailsBlock = function (  ) {
		$('.clientDataDetails').htmlFromServer({ 'loader': new loaderBlock });
		return this;
	};
	this.reloadOrderSummaryBlock = function (  ) {
		$('.orderSummary').htmlFromServer({ 'loader': new loaderBlock });
		return this;
	};

};

var rightMenu = function (settings) {
	this.settings = $.extend({
		'headers' : '.rightMenuRow',
		'menu'    : '.rightMenu'
	}, settings||{});

	this.actions = {
		'scrolling' : function () {
			var that = this;
			$(window).scroll(function () {
				that.changeMenuBlockProperties()
						.checkActiveRow();
			});
		},
		'navigate' : function(){
			$('.rightMenu li a').live('click',function(){
				$($(this).data('target')).autoScroll({'paddingTop': '5'});
			});
		},
		'generateMenu' : function () {
			var that = this;
			$(that.settings.menu + ' ul').html('');
			$(this.settings.headers).each(function(i){
				var link$ = $('<a>').attr('data-target', that.settings.headers + ':eq(' +i+ ')' )
								  .text($(this).data('menu'));
				var row$ = $('<li>').append(link$);
				$(that.settings.menu + ' ul').append(row$);
			});
		}
	};

	this.changeMenuBlockProperties = function () {
		if ($(window).scrollTop() > $('.domainsTable').offset().top) {
			$('.rightMenu').addClass('fixed');
		} else {
			$('.rightMenu').removeClass('fixed');
		}
		return this;
	};

	this.checkActiveRow = function() {

	}
};

$(function(){
	$(".clientDataDetails").on('keydown', '.editClientDetails', function(e) { 
		var keyCode = e.keyCode || e.which; 
		if (keyCode == 9) { 
			e.preventDefault(); 
			var index = $('.editClientDetails').index(this);
			$('.editClientDetails').eq(index + 1).focus().val($('.editClientDetails').eq(index + 1).val()); 
  } 
});
});

$(function(){
	$('select[name=country]').val("Россия");
});