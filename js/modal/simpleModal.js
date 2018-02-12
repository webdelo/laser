var simpleModal = function()
{
	this.modalId = '#modal';
	
	this.open = function(content)
	{
		$('body').append(content);
	};
	
	this.close = function(id)
	{
		id = (id === null) ? this.modalId : id;
		$(id).remove();
		return true;
	};
};