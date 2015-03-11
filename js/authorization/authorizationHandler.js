$(function(){
	(new authorizationHandler())
		.clickCabinetButton()
		.clickAuthorizationButton();
});

var authorizationHandler = function()
{
    this.sources = {
	'cabinet'		:   '.mycab',
	'loginForm'		:   '.panel .box_log',
	'authorizationButton'	:   '#form1 button',
	'authorizationResultBlock' : '.authorizationResult',
    }

    this.ajaxLoader = new ajaxLoader();

    this.clickCabinetButton = function()
    {
	var that = this;
	$(that.sources.cabinet).live('click', function(){
	    if($(that.sources.loginForm).css('display') != 'block')
		(new authorization).getAuthorizationBlock();

	    $(that.sources.loginForm).toggle();
	});

	return this;
    }

    this.clickAuthorizationButton = function()
    {
	var that = this;
	$(that.sources.authorizationButton).live('click',function(){
	    (new authorization).processAuthorization();
	});

	return this;
    }
}


