$(function(){
	(new domainsInfoHandler())
	.clickDomainsInfoLink();
});

var domainsInfoHandler = function()
{

	this.sources = {
		'domainsInfoLink' : '.domainInfoLink',
		'contentBlock'    : '#contentBlock',
		'objectId'        : '#objectId'
	};

	this.errors = new errors({
		'form'	:	'.form_zakaz',
		'submit'  : '.sendOrder',
		'message' : '#message',
		'error'   : '.hint',
        'showMessage' : 'showMessage'
	});

	this.clickDomainsInfoLink = function()
	{
		var that = this;
		$(that.sources.domainsInfoLink).live('click',function(){
			(new domainsInfo()).loadDomainInfoContent(this);
		});

		return this;
	}


}

