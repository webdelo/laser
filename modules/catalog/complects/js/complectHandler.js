$(function(){
	(new complectHandler())
		.clickAddGoodButton()
		.clickDeleteGoodButton()
		.clickEditGoodButton()
		.changePriceField()
		.changeQuantityField()
		.clickEditGoodActionButton();
});

var complectHandler = function()
{
	this.sources = {
		'addGoodButton' : '#addGoodToComplect',
		'complectId' : '.objectId',
		'goodId' : 'input[name=goodId]',
		'quantity' : 'input[name=quantity]',
		'price' : 'input[name=price]',
		'basePrice' : 'input[name=basePrice]',
		'mainGood' : 'input[name=mainGood]',
		'goodDescription' : 'textarea[name=goodDescription]',
		'goodsTableBlock' : '.goodsTable',
		'deleteGoodButton' : '.deleteComplectGood',
		'editGoodButton' : '.editComplectGood',
		'discountInput' : 'input[name=editDiscount]',
		'basePriceInput' : 'input[name=editBasePrice]',
		'quantityInput' : 'input[name=editQuantity]',
		'mainGoodInput' : 'input[name=mainGood]',
		'goodDescriptionHidden' : 'textarea[name=goodDescriptionHidden]',
		'normalView' : '.normalView',
		'editView' : '.editView',
		'editGoodActionButton' : '.editComplectGoodAction',
		'priceField' : '.addedGoodPrice',
		'incomeField' : '.goodIncome',
		'discountField' : '.goodDiscount',
		'basePriceField' : '.addedGoodBasePrice',
		'quantityField' : '.addedGoodQuantity'
	};

	this.ajaxLoader = new ajaxLoader();

	this.actions = {
		'addGood' : '/admin/complectGoods/ajaxAddComplectGood/',
		'getComplectGoodsTableBlock' : '/admin/complectGoods/getComplectGoodsTableByComplectId/',
		'deleteGood' : '/admin/complectGoods/ajaxDeleteComplectGood/',
		'editGood' : '/admin/complectGoods/ajaxEditComplectGood/',
	};

	this.clickAddGoodButton = function()
	{
		var that = this;
		$(that.sources.addGoodButton).live('click',function(){
		    if (!$('#addGoodToComplect').hasClass('blockedButton'))
			(new complect).addGood();
		});
		return this;
	};

	this.clickDeleteGoodButton = function()
	{
		var that = this;
		$(that.sources.deleteGoodButton).live('click',function(){
			(new complect).deleteGood( getGoodIdByDomObject( this ) );
		});
		return this;
	};

	this.clickEditGoodButton = function()
	{
		var that = this;
		$(that.sources.editGoodButton).live('click',function(){
			(new complect).showEditGoodDomElements( getGoodIdByDomObject( this ) );
		});
		return this;
	};

	this.clickEditGoodActionButton = function()
	{
		var that = this;
		$(that.sources.editGoodActionButton).live('click',function(){
			(new complect).editGood( getGoodIdByDomObject( this ) );
		});
		return this;
	};
        
	this.changePriceField = function()
	{
		$(this.sources.discountField).on('textchange',function(){
		    (new complect).changePrice();
		});
		return this;
	};
        
	this.changeQuantityField = function()
	{
		$(this.sources.quantityField).on('textchange',function(){
		    (new complect).changeQuantity();
		});
		return this;
	};
}

var getGoodIdByDomObject = function(object)
{
	return $(object).parent().parent().parent().attr('data-id')
}