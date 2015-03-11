var order = function()
{
	this.errors = new errors({
		'form' : '.addGoodBlock',
		'submit' : '.addGoodToOrder',
		'message' : '#message',
		'error' : '.hint',
		'showMessage' : true
	});

	this.editErrors = new errors({
		'form' : '.goodsList',
		'submit' : '.editOrderGoodAction',
		'message' : '#message',
		'error' : '.hint'
	});

	this.actions = {
		'addGood' : '/admin/orderGoods/ajaxAddOrderGood/',
		'getOrderGoodsTableBlock' : '/admin/orderGoods/getOrderGoodsTableByOrderId/',
		'deleteGood' : '/admin/orderGoods/ajaxDeleteOrderGood/',
		'editGood' : '/admin/orderGoods/ajaxEditOrderGood/',
		'addPromoCode' : '/admin/orders/ajaxAddPromoCodeToOrder/',
		'removePromoCode' : '/admin/orders/ajaxRemovedPromoCodeFromOrder/',
		'ajaxManagerConfirmOrder' : '/admin/orders/ajaxManagerConfirmOrder'
	};

	this.handler = new orderHandler;
	this.orderId = $(this.handler.sources.orderId).val();

	this.addGood = function()
	{
		var that = this;
		var data ={
			'orderId':   that.orderId,
			'goodId':   $(that.handler.sources.goodId).val(),
			'quantity':   $(that.handler.sources.quantity).val(),
			'price':   $(that.handler.sources.price).val(),
			'basePrice':   $(that.handler.sources.basePrice).val(),
			'goodDescription':   $(that.handler.sources.goodDescription).val()
		};
		$.ajax({
			before: that.handler.ajaxLoader.setLoader(that.handler.sources.addGoodButton),
			url: that.actions.addGood,
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(data){
				that.handler.ajaxLoader.getElement();
				if($.isNumeric(data)){
					that.errors.reset();
					that.resetOrderGoodsTableBlock();
				}
				else{
					that.errors.show(data);
				}
			}
		});
	}

	this.resetOrderGoodsTableBlock = function()
	{
		var that = this;
		var data ={
			'orderId':   that.orderId,
		};

		$.ajax({
			url: that.actions.getOrderGoodsTableBlock,
			type: 'POST',
			dataType: 'html',
			data: data,
			success: function(data){
				$(that.handler.sources.goodsTableBlock).replaceWith(data);
				initGoodAutosuggest();
			}
		});
	}

	this.deleteGood = function(goodId)
	{
		if (confirm('Удалить товар?')){
			var that = this;
			var data ={
				'goodId' : goodId
			};
			var deleteButton = $('[data-id=' + goodId + ']').find($(that.handler.sources.deleteGoodButton));

			$.ajax({
				before: that.handler.ajaxLoader.setLoader(deleteButton),
				url: that.actions.deleteGood,
				type: 'POST',
				dataType: 'json',
				data: data,
				success: function(data){
					that.handler.ajaxLoader.getElement();
					if($.isNumeric(data)){
						that.errors.reset();
						that.resetOrderGoodsTableBlock();
					}
					else{
						that.errors.show(data);
					}
				}
			});
		}
	};

	this.showEditGoodDomElements = function(goodId)
	{
		var that = this;
		var normalView = that.handler.sources.normalView;
		var editView = that.handler.sources.editView;
		$(normalView).show();
		$(editView).hide();
		$('[data-id=' + goodId + ']').find( $(normalView) ).hide();
		$('[data-id=' + goodId + ']').find( $(editView) ).show();
	};

	this.editGood = function(goodId)
	{
		var that = this;
		var data ={
			'id' : goodId,
			'price' : $('[data-id=' + goodId + ']').find($(that.handler.sources.priceInput)).val(),
			'basePrice' : $('[data-id=' + goodId + ']').find($(that.handler.sources.basePriceInput)).val(),
			'quantity' : $('[data-id=' + goodId + ']').find($(that.handler.sources.quantityInput)).val(),
			'goodDescription' : $('[data-id=' + goodId + ']').find($(that.handler.sources.goodDescriptionHidden)).val(),
		};

		var editButton = $('[data-id=' + goodId + ']').find($(that.handler.sources.editGoodActionButton));
		$.ajax({
			before: that.handler.ajaxLoader.setLoader(editButton),
			url: that.actions.editGood,
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(data){
				that.handler.ajaxLoader.getElement();
				if((data)){
					that.resetOrderGoodsTableBlock();
				}
				else{
					alert('Ошибка при попытке изменить данные заказа. Проверьте правильность заполнения полей.');
				}
			}
		});
	};

	this.addPromoCodeToOrder = function(promoCode, callback)
	{
		var that = this;
		var data ={
			'orderId' : that.orderId,
			'promoCode' : promoCode
		};

		$.ajax({
			before: that.handler.ajaxLoader.setLoader(that.handler.sources.addPromoCodeButton),
			url: that.actions.addPromoCode,
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(response){
				that.handler.ajaxLoader.getElement();
				if (typeof(response) == 'object') {
					that.errors.show(response);
				} else {
					that.resetOrderGoodsTableBlock();
					callback();
				}
			}
		});
	};

	this.removePromoCodeFromOrder = function()
	{
		var that = this;
		var data ={
			'orderId' : that.orderId
		};

		$.ajax({
			before: that.handler.ajaxLoader.setLoader(that.handler.sources.removePromoCodeButton),
			url: that.actions.removePromoCode,
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(response){
				that.handler.ajaxLoader.getElement();
					that.resetOrderGoodsTableBlock();
			}
		});
	};

	this.managerConfirmOrder = function()
	{
		var that = this;
		var data ={
			'orderId' : that.orderId
		};

		$.ajax({
			before: that.handler.ajaxLoader.setLoader(that.handler.sources.managerConfirmOrderButton),
			url: that.actions.ajaxManagerConfirmOrder,
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(data){
				that.handler.ajaxLoader.getElement();
				if($.isNumeric(data)){
					$('.partnerConfirmedLi').css('backgroundColor', '#76D3F7');
					$('.partnerConfirmedLi').stop().animate({ backgroundColor: "#FFFFFF"}, 2000);
					setTimeout(function() {
						$('.partnerConfirmedLi').css('border', '0px');

						$(that.handler.sources.managerConfirmOrderButton).hide();
						$(that.handler.sources.showOrderHistoryButton).removeClass('hide');
						$('[name=partnerConfirmed] [value="1"]').attr("selected", "selected");
						$('#partnerConfirmed0').html( $('[name=partnerConfirmed] :selected').text() );
						$('.partnerConfirmedLi').removeClass('orderNotConfirmedLi');
					  }, 2000);
				}
			}
		});
	};

};