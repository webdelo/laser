var subGoods = function()
{
	this.errors = new errors({
		'form' : '.addGoodBlock',
		'submit' : '#addSubGood',
		'message' : '#message',
		'error' : '.hint',
		'showMessage' : true
	});

	this.actions = {
		'addSubGood' : '/admin/subGoods/ajaxAddSubGood/',
		'getSubGoodsTable' : '/admin/subGoods/ajaxGetSubGoodsTable/',
		'deleteSubGood' : '/admin/subGoods/ajaxDeleteSubGood/'
	};

	this.handler = new subGoodsHandler;

	this.addSubGood = function(button)
	{
		var that = this;
		var goodId = $(button).attr('data-mainGoodId');
		var data ={
			'goodId' : goodId,
			'subGoodId' : $(that.handler.sources.subGoodId).val(),
			'subGoodQuantity':   $(that.handler.sources.subGoodQuantity).val()
		};
		$.ajax({
			before: that.handler.ajaxLoader.setLoader(that.handler.sources.addSubGoodButton),
			url: that.actions.addSubGood,
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(data){
				that.handler.ajaxLoader.getElement();
				if($.isNumeric(data)){
					that.errors.reset();
					that.resetSubGoodsTable(goodId);
				}
				else{
					that.errors.show(data);
				}
			}
		});
	}

	this.resetSubGoodsTable = function(goodId)
	{
		var that = this;
		var data ={
			'goodId' : goodId
		};

		$.ajax({
			url: that.actions.getSubGoodsTable,
			type: 'POST',
			dataType: 'html',
			data: data,
			success: function(data){
				$(that.handler.sources.goodsTableBlock).replaceWith(data);
				that.handler.initSubGoodAutosuggest();
			}
		});
	}



	this.deleteSubGood = function(deleteButton)
	{
		if (confirm('Удалить подтовар?')){
			var that = this;
			var data ={
				'subGoodId' : $(deleteButton).attr('data-id')
			};

			$.ajax({
				before: that.handler.ajaxLoader.setLoader(deleteButton),
				url: that.actions.deleteSubGood,
				type: 'POST',
				dataType: 'json',
				data: data,
				success: function(data){
					that.handler.ajaxLoader.getElement();
					if($.isNumeric(data)){
						that.errors.reset();
						that.resetSubGoodsTable($(that.handler.sources.addSubGoodButton).attr('data-mainGoodId'));
					}
					else{
						that.errors.show(data);
					}
				}
			});
		}
	};
};