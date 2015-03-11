var availability = function()
{
	this.ajax = {
		'action' : '/admin/availability/ajaxSaveAvailabilityForPartner/',
		'updateAvailabilityTable' : '/admin/availability/ajaxPrintAvailabilityBlock/'
	};
	
	this.sources = {
		'availabilityTableBlock'    : '#availablePrices',
	};
	
	this.loader = new ajaxLoader();
	
	this.errorsObject  = new errors();
	
	this.updateAvailability = function(postData, callback)
	{
		this.setCallback(callback);
		var that = this;

		$.ajax({
			url: that.ajax.action,
			type: 'POST',
			data: postData,
			dataType: 'json',
			success: $.proxy(that.addAvailabilitySuccess, that)
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
	
	this.addAvailabilitySuccess = function (response) {
		//TODO
		this.callback(response);
		this.updateAvailabilityTable();
	};
	
	// TODO
	this.updateAvailabilityTable = function()
	{
		var that = this;
		
		var postData = {
                        'controller' : window.controller,
			'objectId' : $((new pricesHandler).sources.objectId).val()
		};

		$.ajax({
			before: (new ajaxLoader()).innerLoader(that.sources.availabilityTableBlock),
			url: that.ajax.updateAvailabilityTable,
			type: 'POST',
			data: postData,
			dataType: 'html',
			success: $.proxy(that.updateAvailabilityTableSuccess, that)
		});
	};
	
	this.updateAvailabilityTableSuccess = function (response) {
		$(this.sources.availabilityTableBlock).html(response);
	};

};