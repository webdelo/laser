$(function(){
	(new registrationHandler())
		.clickRegistrationButton();
});

var registrationHandler = function()
{
	this.sources = {
		'registrationButton'	:   '.registrate',
		'loginForm'		:   '.panel .box_log',
		'box_log'			:   '.box_log',
	}

	this.ajaxLoader = new ajaxLoader();

	this.clickRegistrationButton = function()
	{
		var that = this;
		$(that.sources.registrationButton).live('click',function(){
			(new registration).processRegistration();
		});
		return this;
	}
}


