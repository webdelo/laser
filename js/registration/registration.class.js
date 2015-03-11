var registration = function()
{

	this.actions = {
		'ajaxRegistration' : '/clients/ajaxAdd/'
	}

	this.errors = new errors({
		   'form'	:	'#form2',
		   'submit'  : '.registrate',
		   'message' : '#message',
		   'error'   : '.hint',
		   'showMessage' : 'showMessage'
	});

	this.loader = new ajaxLoader();

	this.processRegistration = function()
	{
		var that = this;
		var data = {
			'login' : $('#form2 input[name="login"]').val(),
			'password' : $('#form2 input[name="password"]').val(),
			'passwordConfirm' : $('#form2 input[name="passwordConfirm"]').val(),
			'authorization_client_submit' : $('#form1 .authorization_client_submit').val(),
		};
		$.ajax({
			before: that.loader.setLoader((new registrationHandler).sources.registrationButton),
			url: that.actions.ajaxRegistration,
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function(data){
				that.loader.getElement();
				if( $.isNumeric(data) ){
					that.errors.reset();
					(new cabinet()).getSuccessAuthorizatedCabinetBlock();
				}
				else
					that.errors.show(data);
			}
		});
	}

}

