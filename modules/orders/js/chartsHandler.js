$(function(){
	(new chartsHandler())
		.clickGoodSalesChartButton()
		.clickCatalogType();
});

var chartsHandler = function()
{
	this.sources = {
		'goodSalesChartButton' : '.goodSalesChartButton',
		'catalogType' : '.catalogType',
		'start_date' : '.start_date',
		'end_date' : '.end_date',
		'type' : '.type'
	};

	this.clickGoodSalesChartButton = function()
	{
		var that = this;
		$(that.sources.goodSalesChartButton).live('click',function(){
			var url = '/admin/orderGoods/';
			url = url + $(that.sources.type).val() + '?';
			url = url + 'catalogType=' + $(that.sources.catalogType).val();
			url = url + '&start_date=' + $(that.sources.start_date).val();
			url = url + '&end_date=' + $(that.sources.end_date).val();
			url = url + '&onlyPaydOrders=' + $('.onlyPaydOrders').is(':checked');
			url = url + '&' +  $(that.sources.type + ' option:selected').attr('id') + '=';
			$( '.' + $(that.sources.type + ' option:selected').attr('name') + ':checked' ).each(function(key, value){
				if( $(this).closest('.change').is(':visible') )
					url += $(this).val() + ',';
			});
			url = url.substring(0, url.length - 1);
			window.open(url);
		});
		return this;
	};

	this.clickCatalogType = function()
	{
		var that = this;
		$(that.sources.catalogType).live('change',function(){
			$('.change').fadeOut('slow');
			$('.' + $(this).val()).fadeIn('slow');
		});
		return this;
	};
};