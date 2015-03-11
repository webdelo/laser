var filterClass = function()
{
	this.sendFilter = function(page)
	{
		var that = this;
		var filterHandlerSources = (new filterHandler()).sources;
		var data = {
			'minPrice' : $(filterHandlerSources.minPrice).val(),
			'maxPrice' : $(filterHandlerSources.maxPrice).val(),
			'objectsType' : $(filterHandlerSources.objectsType).val(),
			'categoryId' : $(filterHandlerSources.categoryId).val()
		}
		var get = '';
		get = (page != undefined) ? '?'+page : '';
		sendController = (controller == 'Calculator') ? 'calculator' : 'catalog';
		$.ajax({
			before: (new filterHandler()).ajaxLoader.innerLoader(filterHandlerSources.productsList),
			url: '/'+sendController+'/ajaxGetCatalog/'+get,
			type: 'POST',
			data: data,
			success: function(data){
				that.successFilterPrice(data);
				(new htmlCatalogPlugin()).resizeBlocks();
			}
		});
	};
	
	this.successFilterPrice = function(data)
	{
		$((new filterHandler()).sources.productsList).html(data);
	};
};