var validators = function () {
	this.error = function (element$) {
		new error(element$);
	}
	
	this.validateLogin = function (element$) {
		if (this.isEmpty(element$)) {
			this.error(element$);
		} else if (this.isEmail(element$.val())) {
			this.addErrorToElement(element$, 'Please enter your email')
				.error(element$)
				.removeErrorFromElement(element$);
		} else {
			this.isExistsLogin(element$);
		}
	}
	
	this.isEmail = function (email) {
		var reg_mail = /[0-9a-z_]+@[0-9a-z_^.]+.[a-z]{2,3}/i;
		return (reg_mail.exec(email) == null);
		return false;
	}
	
	this.isExistsLogin = function (element$) {
		var that = this;
		if (!this.isEmpty(element$)) {
			$.ajax({
				url: that.ajax.existsLogin,
				type: 'post',
				dataType: 'json',
				data: {'login': element$.val()},
				success: function (response) {
					if (response == 1) {
						that.addErrorToElement(element$, 'The entered login is already exists')
							.error(element$)
							.removeErrorFromElement(element$);
					}
				}
			});
		}
		
		return this;
	}
	
	this.validatePassword = function (element$) {
		this.notEmpty(element$);
	}
	
	this.validatePasswordConfirm = function (element$) {
		var name = element$.attr('name');
		var main$ = $(this.settings.form).find('input[name='+name.replace('_confirmation', '')+']');
		this.notEmpty(element$).errorShow();
		if ( main$.val() !== element$.val() ){
			this.addErrorToElement(element$, 'Passwords do not match')
				.error(element$)
				.removeErrorFromElement(element$);
		}		
	}

	this.notEmpty = function (element$) {
		var that = this;
		if( element$.length > 1 ) {
			element$.each(function() {
				if( that.isEmpty($(this)) ) {
					that.error($(this));
				}
			});
		} else if ( element$.length == 1 )  {
			if( that.isEmpty(element$) ) {
				that.error(element$);
			}
		}
		return this;
	}

	this.isEmpty = function (that$) {
		return ( that$.val() == '' || that$.val() == undefined );
	}
	
	this.isDiv = function (element) {
		return $(element).is('div');
	}

	this.isForm = function (element) {
		return $(element).is('form');
	}

}