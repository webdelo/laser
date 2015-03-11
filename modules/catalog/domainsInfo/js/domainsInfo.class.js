var domainsInfo = function()
{
	this.ajax = {
		'loadDomainInfo' : '/admin/domainsInfo/ajaxGetDomainBlock/'
	};
	
	this.loadDomainInfoContent = function(domainInfoLink)
	{
		this.changeActiveClassByDomainInfoLink(domainInfoLink);
		var ajaxUrl = this.getAjaxUrlByDomainAlias(domainInfoLink);
		postData = {
			'objectId'    : $((new domainsInfoHandler()).sources.objectId).val(),
			'domainAlias' : $(domainInfoLink).data('domainalias')
		};

		$.ajax({
			before: (new ajaxLoader()).innerLoader((new domainsInfoHandler()).sources.contentBlock),
			url: ajaxUrl,
			type: 'POST',
			data: postData,
			dataType: 'html',
			success: function(data){
				$((new domainsInfoHandler()).sources.contentBlock).html(data);
			}
		});
	};
	
	this.changeActiveClassByDomainInfoLink = function(domainInfoLink)
	{
		$((new domainsInfoHandler()).sources.domainsInfoLink).removeClass('active');
		$(domainInfoLink).addClass('active');
	};
	
	this.getAjaxUrlByDomainAlias = function(domainInfoLink)
	{
		if ($(domainInfoLink).data('domainalias') == 'main')
			return '/admin/'+$(domainInfoLink).data('controller')+'/ajaxGetMainContentBlock/'
		else
			return this.ajax.loadDomainInfo;
	};
};