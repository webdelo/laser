var complect = function()
{
	this.errors = new errors({
		'form' : '.addGoodBlock',
		'submit' : '.addGoodToComplect',
		'message' : '#message',
		'error' : '.hint',
	});

	this.editErrors = new errors({
		'form' : '.goodsList',
		'submit' : '.editComplectGoodAction',
		'message' : '#message',
		'error' : '.hint',
	});

	this.handler = new complectHandler;
	this.complectId = $(this.handler.sources.complectId).val();

	this.addGood = function()
	{
		var that = this;
		var data ={
			'complectId':   that.complectId,
			'goodId':   $(that.handler.sources.goodId).val(),
			'quantity':   $(that.handler.sources.quantity).val(),
			'discount':   $(that.handler.sources.discountField).val(),
			'mainGood':   $(that.handler.sources.mainGood+':checked:visible').val(),
			'goodDescription':   $(that.handler.sources.goodDescription).val()
		};
		$.ajax({
			before: that.handler.ajaxLoader.setLoader(that.handler.sources.addGoodButton),
			url: that.handler.actions.addGood,
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(data){
				that.handler.ajaxLoader.getElement();
				if($.isNumeric(data)){
					that.errors.reset();
					that.resetComplectGoodsTableBlock();
				}
				else{
					that.errors.show(data);
				}
			}
		});
	}

	this.resetComplectGoodsTableBlock = function()
	{
		var that = this;
		var data ={
			'complectId':   that.complectId,
		};

		$.ajax({
			url: that.handler.actions.getComplectGoodsTableBlock,
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
				url: that.handler.actions.deleteGood,
				type: 'POST',
				dataType: 'json',
				data: data,
				success: function(data){
					that.handler.ajaxLoader.getElement();
					if($.isNumeric(data)){
						that.errors.reset();
						that.resetComplectGoodsTableBlock();
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
	}

	this.editGood = function(goodId)
	{
		var that = this;
		var data ={
			'id' : goodId,
			'discount' : $('[data-id=' + goodId + ']').find($(that.handler.sources.discountInput)).val(),
			'quantity' : $('[data-id=' + goodId + ']').find($(that.handler.sources.quantityInput)).val(),
			'mainGood' : $('[data-id=' + goodId + ']').find($(that.handler.sources.mainGoodInput+':checked')).val(),
			'goodDescription' : $('[data-id=' + goodId + ']').find($(that.handler.sources.goodDescriptionHidden)).val(),
		};

		var editButton = $('[data-id=' + goodId + ']').find($(that.handler.sources.editGoodActionButton));
		$.ajax({
			before: that.handler.ajaxLoader.setLoader(editButton),
			url: that.handler.actions.editGood,
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(data){
				that.handler.ajaxLoader.getElement();
				if((data)){
					that.resetComplectGoodsTableBlock();
				}
				else{
					alert('Ошибка при попытке изменить данные заказа. Проверьте правильность заполнения полей.');
				}
			}
		});
	};

	this.changePrice = function () {
		var discount      = parseInt($('.goodDiscount').val());
		var goodPrice     = parseInt($('.addedGoodPrice').text());
		var goodBasePrice = parseInt($('.addedGoodBasePrice').text());
		
		if ( discount )
			$('.goodIncome').html(goodPrice - goodBasePrice - discount);
		else
			$('.goodIncome').html(goodPrice - goodBasePrice);
	}

	this.changeQuantity = function () {
	    var that = this;
	    var priceField$ = $((new complectHandler).sources.priceField);
	    var data ={
		    'id' : $((new complectHandler).sources.quantityField).attr('data-goodid'),
		    'quantity' : $((new complectHandler).sources.quantityField).val(),
	    };

	    $.ajax({
		url: '/admin/complectGoods/ajaxGetPriceByQuantity/',
		type: 'POST',
		dataType: 'json',
		data: data,
		success: function(response){

			if(response){
			    priceField$.val(response).attr('data-startPrice', response);
			}
			else{
				alert('Ошибка при попытке изменить данные заказа. Проверьте правильность заполнения полей.');
			}
		}
	    });
	}

}


