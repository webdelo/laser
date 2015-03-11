var settings = function()
{
	this.actions = {
		'clearCache' : '/admin/settings/ajaxClearCache/'
	};

	this.handler = new settingsHandler;

	this.clearCache = function()
	{
		var that = this;
		$.ajax({
			before: that.handler.ajaxLoader.setLoader(that.handler.sources.clearCacheButton),
			url: that.actions.clearCache,
			type: 'POST',
			dataType: 'json',
			success: function(data){
				that.handler.ajaxLoader.getElement();
				if(data == 1){
					$(that.handler.sources.clearCacheButton).hide();
					$(that.handler.sources.clearCacheOkButton).show();
				}
				else{
					alert('Erorr while clearing image cash!')
				}
			}
		});
	}
};