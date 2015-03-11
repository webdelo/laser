$(function(){
	(new pricesHandler())
	.addPrice()
	.deletePrice()
	.viewBasePrices()
	.closeBasePrices()
	.saveBasePrices()
	.editPrice()
	.savePrice();
});

var pricesHandler = function()
{

	this.sources = {
		'addPriceButton'       : '#addPriceButton',
		'objectId'             : '#objectId',
		'deletePriceLink'      : '.deletePriceLink',
		'viewBasePricesLink'   : '.viewBasePriceLink',
		'saveBasePricesButton' : '#saveBasePricesButton',
		'closeBasePriceButton' : '#closeBasePricesButton',
		'editPriceButton'      : '.editPriceButton',
		'savePriceButton'      : '.savePriceButton'
	};

	this.addPrice = function()
	{
		var that = this;
		$(that.sources.addPriceButton).live('click',function(){
			(new prices()).addPrice(that.addPriceCallback);
		});
		return this;
	};
	
	this.addPriceCallback = function()
	{
		(new prices).updatePricesTable();
	};

	this.deletePrice = function()
	{
		var that = this;
		$(that.sources.deletePriceLink).live('click',function(){
			(new prices()).deletePrice(this, that.deletePriceCallback);
		});
		return this;
	};
	
	this.deletePriceCallback = function()
	{
		(new prices).updatePricesTable();
	};
	
	this.viewBasePrices = function()
	{
		var that = this;
		$(that.sources.viewBasePricesLink).live('click',function(){
			(new prices()).viewBasePrices(this);
		});
		return this;
	};
	
	this.saveBasePrices = function()
	{
		var that = this;
		$(that.sources.saveBasePricesButton).live('click',function(){
			(new prices()).saveBasePrices(that.saveBasePricesCallback);
		});
		return this;
	};
	
	this.saveBasePricesCallback = function()
	{
		(new prices).updatePricesTable();
	};
	
	this.closeBasePrices = function()
	{
		var that = this;
		$(that.sources.closeBasePriceButton).live('click',function(){
			(new prices()).closeBasePrices();
		});
		return this;
	};
	
	this.editPrice = function()
	{
		var that = this;
		$(that.sources.editPriceButton).live('click',function(){
			var priceId = $(this).data('priceid');
			$('#viewPrice_'+priceId).addClass('hide');
			$('#editPrice_'+priceId).removeClass('hide');
		});
		return this;
	};
	
	this.savePrice = function()
	{
		var that = this;
		$(that.sources.savePriceButton).live('click',function(){
			(new prices()).savePrice(this, that.deletePriceCallback);
		});
		return this;
	};
};