$(function(){
	(new filterHandler())
		.clickSuccessButton()
		.clickPageButton();
});

var filterHandler = function()
{
	this.sources = {
		'sort' : '.sort_price button',
		'minPrice' : '#min',
		'maxPrice' : '#max',
		'objectsType' : '#objectsType',
		'categoryId' : '#categoryId',
		'productsList' : '#productsList',
		'pager' : '.pager'
	};
	
	this.ajaxArray = {
	  'filterLoader'  : '#filterLoader'
    };
	
	this.ajaxLoader = new ajaxLoader(this.ajaxArray);
	
	this.clickSuccessButton = function()
	{
		var that = this;
		$(that.sources.sort).click(function(){
			var page = undefined;
			(new filterClass()).sendFilter(page);
		});
		
		return this;
	};
	
	this.clickPageButton = function()
	{
		var that = this;
		$(this.sources.pager).live('click',function(evt){
			evt.preventDefault();
			var page = $(this).attr("href").split('?');
			(new filterClass()).sendFilter(page[1]);
			that.pageUp();
		});			
	};
	
	this.pageUp = function()
	{
		var curPos=$(document).scrollTop();
		var scrollTime=curPos/1.73;
		$("body,html").animate({"scrollTop":0},scrollTime);
	};
};
