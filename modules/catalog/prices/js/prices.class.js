var prices = function()
{
	this.ajax = {
		'addPrice'           : '/admin/prices/addPrice/',
		'deletePrice'        : '/admin/prices/ajaxRemovePrice/',
		'updatePricesTable'  : '/admin/prices/ajaxGetPricesTableBlock/',
		'getBasePricesBlock' : '/admin/prices/ajaxGetBasePricesBlock/',
		'saveBasePrices'     : '/admin/prices/saveBasePrices/',
		'savePrice'          : '/admin/prices/savePrice/'
	};

	this.sources = {
		'addPricePriceField'    : '#addPricePriceField',
		'addPriceOldPriceField' : '#addPriceOldPriceField',
		'addPriceQuantityField' : '#addPriceQuantityField',
		'pricesTableBlock'      : '#pricesTableBlock',
		'basePricesBlock'       : '#basePrices',
		'saveBasePricesForm'    : '#saveBasePrices'
	};

	this.loader = new ajaxLoader();

	this.errorsObject  = new errors();

	this.addPrice = function(callback)
	{
		this.setCallback(callback);
		var that = this;

		var postData = {
			'objectId' : $((new pricesHandler).sources.objectId).val(),
			'price'    : $(this.sources.addPricePriceField).val(),
			'oldPrice' : $(this.sources.addPriceOldPriceField).val(),
			'quantity' : $(this.sources.addPriceQuantityField).val()
		};

		$.ajax({
			before: $.proxy(that.loader.setLoader((new pricesHandler()).sources.addPriceButton), that),
			url: that.ajax.addPrice,
			type: 'POST',
			data: postData,
			dataType: 'json',
			success: $.proxy(that.addPriceSuccess, that)
		});
	};

	this.setCallback = function (callback) {
		if($.isFunction(callback)) {
			this.callback = callback;
		}
		return this;
	};

	this.callCallback = function (response) {
		if($.isFunction(this.callback))
			this.callback(response);
		return this;
	};

	this.addPriceSuccess = function (response) {
		this.loader.getElement();
		// TODO: Hide Loader
		if (typeof(response) === 'object') {
			this.errorsObject.show(response);
			return this;
		}
		this.errorsObject.reset();
		this.callCallback(response);
	};

	this.updatePricesTable = function(callback)
	{
		this.setCallback(callback);
		var that = this;

		var postData = {
			'objectId' : $((new pricesHandler).sources.objectId).val()
		};

		$.ajax({
			before: (new ajaxLoader()).innerLoader(that.sources.pricesTableBlock),
			url: that.ajax.updatePricesTable,
			type: 'POST',
			data: postData,
			dataType: 'html',
			success: $.proxy(that.updatePricesTableSuccess, that)
		});
	};

	this.updatePricesTableSuccess = function (response) {
		$(this.sources.pricesTableBlock).html(response);
		this.callCallback();
	};

	this.deletePrice = function(priceLink, callback)
	{
		this.setCallback(callback);
		var that = this;

		var postData = {
			'objectId' : $((new pricesHandler).sources.objectId).val(),
			'priceId'  : $(priceLink).data('id')
		};

		$.ajax({
			before: $.proxy(that.deletePriceBefore, that),
			url: that.ajax.deletePrice,
			type: 'POST',
			data: postData,
			dataType: 'json',
			success: $.proxy(that.deletePriceSuccess, that)
		});
	};

	this.deletePriceBefore = function () {
		// TODO: View Loader (new ajaxLoader()).innerLoader((new domainsInfoHandler()).sources.contentBlock)
		return;
	};

	this.deletePriceSuccess = function (response) {
		this.callCallback();
	};

	this.viewBasePrices = function(link, callback)
	{
		this.setCallback(callback);
		var that = this;

		var postData = {
			'objectId' : $((new pricesHandler).sources.objectId).val(),
			'priceId'  : $(link).data('id')
		};

		$.ajax({
			before: (new ajaxLoader()).innerLoader(that.sources.basePricesBlock),
			url: that.ajax.getBasePricesBlock,
			type: 'POST',
			data: postData,
			dataType: 'html',
			success: $.proxy(that.viewBasePricesSuccess, that)
		});
	};

	this.viewBasePricesSuccess = function (response) {
		$(this.sources.pricesTableBlock).before($(this.sources.basePricesBlock));
		$(this.sources.basePricesBlock).html(response).removeClass('hide');
		this.callCallback();
	};

	this.saveBasePrices = function(callback)
	{
		this.setCallback(callback);
		var postData = $(this.sources.saveBasePricesForm + " :input[value != '']").serialize()+'&objectId='+$((new pricesHandler).sources.objectId).val();
		var that = this;
		$.ajax({
			before: $.proxy(that.loader.setLoader((new pricesHandler()).sources.saveBasePricesButton), that),
			url: that.ajax.saveBasePrices,
			type: 'POST',
			data: postData,
			dataType: 'json',
			success: $.proxy(that.addPricesSuccess, that)
		});
	};

	this.addPricesSuccess = function (response) {
		this.loader.getElement();
		this.callCallback();
		this.closeBasePrices();
	};

	this.closeBasePrices = function () {
		$(this.sources.basePricesBlock).addClass('hide').html();
	};

	this.savePrice = function(object, callback)
	{
		this.setCallback(callback);
		var priceId = $(object).data('priceid');
		var postData = {
			'objectId' : $((new pricesHandler).sources.objectId).val(),
			'priceId'  : priceId,
			'price'    : $('#price_'+priceId).val(),
			'oldPrice' : $('#oldPrice_'+priceId).val()
		};

		var that = this;
		$.ajax({
			before: $.proxy(that.loader.setLoader((new pricesHandler()).sources.saveBasePricesButton), that),
			url: that.ajax.savePrice,
			type: 'POST',
			data: postData,
			dataType: 'json',
			success: function(){that.callback();}
		});
	};
};