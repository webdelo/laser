var authorization = function()
{

    this.actions = {
	'getAuthorizationBlock'	:   '/authorization/ajaxGetAuthorizationBlock/',
	'ajaxAuthorization'	:   '/authorization/ajaxAuthorization/',
    }


    this.getAuthorizationBlock = function()
    {
	var that = this;
	$.ajax({
		before: (new authorizationHandler()).ajaxLoader.innerLoader((new authorizationHandler()).sources.loginForm),
		url: that.actions.getAuthorizationBlock,
		type: 'POST',
		success: function(data){
			$((new authorizationHandler()).sources.loginForm).html(data);
		}
	});
    }

	this.processAuthorization = function()
	{
		var handlerAuthorization = new authorizationHandler();
		var that = this;
		var data = {
			'login' : $('#form1 input[name="login"]').val(),
			'password' : $('#form1 input[name="password"]').val(),
			'cookie' : $('#form1 input[name="cookie"]').val(),
			'authorization_client_submit' : $('#form1 .authorization_client_submit').val(),
		};

		$.ajax({
			before:handlerAuthorization.ajaxLoader.setLoader(handlerAuthorization.sources.loginForm),
			url: that.actions.ajaxAuthorization,
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function(data){
				handlerAuthorization.ajaxLoader.getElement();
				if(data == 1){
					(new cabinet()).getSuccessAuthorizatedCabinetBlock();
					(new shopcart()).checkShopcartStatusAskSaving();
					if( window.location.href.replace('http://', '').split("/")[1] == 'cabinet' )
						location.reload();
				}
				else
					$( handlerAuthorization.sources.authorizationResultBlock ).html(data.errorMessage);
			}
		});
	}
}