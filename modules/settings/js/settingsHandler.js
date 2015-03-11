$(function(){
	(new settingsHandler())
		.clickClearCacheButton()
});

var settingsHandler = function()
{
	this.sources = {
		'clearCacheButton' : '.clearCache',
		'clearCacheOkButton' : '.clearCacheOk'
	};

	this.ajaxLoader = new ajaxLoader();

	this.clickClearCacheButton = function()
	{
		var that = this;
		$(that.sources.clearCacheButton).live('click',function(){
			(new settings).clearCache();
		});
		return this;
	};
};