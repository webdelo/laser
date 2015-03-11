var cabinet = function()
{
	this.actions = {
		'getCabinetBlock'	:   '/authorization/ajaxGetCabinetBlock/',
		'ajaxLogout'	:   '/authorization/logout/',
		'ajaxChangeLogin' : '/clients/ajaxChangeLogin/',
		'ajaxChangePassword' : '/clients/ajaxChangePassword/',
		'ajaxGetPersonalDataBlockContent' : '/cabinet/ajaxGetPersonalDataBlockContent/',
		'ajaxSavePersonalData' : '/clients/ajaxSavePersonalData/',
		'saveDeliveryData' : '/clients/ajaxEditDeliveryAddress/'
	}

	this.errors = new errors({
		   'form'	:	'.changeLoginForm',
		   'submit'  : '.changeLoginButton',
		   'message' : '#message',
		   'error'   : '.hint',
		   'showMessage' : 'showMessage'
	});

	this.errorsChangePassword = new errors({
		   'form'	:	'.changePasswordForm',
		   'submit'  : '.changePasswordButton',
		   'message' : '#message',
		   'error'   : '.hint',
		   'showMessage' : 'showMessage'
	});

	this.errorsSavePersonalData = new errors({
		   'form'	:	'.personal',
		   'submit'  : '.savePersonalDataButton',
		   'message' : '#message',
		   'error'   : '.hint',
		   'showMessage' : 'showMessage'
	});

	this.loader = new ajaxLoader();

	this.getCabinetBlock = function()
	{
		var that = this;
		$.ajax({
			before: (new cabinetHandler()).ajaxLoader.innerLoader((new cabinetHandler()).sources.loginForm),
			url: that.actions.getCabinetBlock,
			type: 'POST',
			success: function(data){
				$((new cabinetHandler()).sources.loginForm).html(data).show();
			}
		});
	}

	this.logoutAction = function()
	{
		var that = this;
		var data = {
			'client_logout' : $('#form1 .authorization_client_submit').val(),
		};
		$.ajax({
			before: (new cabinetHandler()).ajaxLoader.innerLoader((new cabinetHandler()).sources.loginForm),
			url: that.actions.ajaxLogout,
			type: 'POST',
			data: data,
			success: function(data){
				$( (new cabinetHandler()).sources.cabinetAuthorizated ).removeClass( (new cabinetHandler()).sources.cabinetAuthorizatedClassName )
														.addClass( (new cabinetHandler()).sources.cabinetClassName );
				$( (new cabinetHandler()).sources.loginForm ).hide();
				(new cabinet()).redirectFromCabinetToIndex();
			}
		});
	}

	this.redirectFromCabinetToIndex = function()
	{
		var adresArray = window.location.href.split('/');
		if( adresArray[3] == 'cabinet'   ||   adresArray[3] == 'shopcart')
			window.location.href = 'http://' + adresArray[2] + '/';
	}

	this.getSuccessAuthorizatedCabinetBlock = function()
	{
		this.getCabinetBlock();
		$( (new cabinetHandler()).sources.cabinet ).addClass( (new cabinetHandler()).sources.cabinetAuthorizatedClassName )
												.removeClass( (new cabinetHandler()).sources.cabinetClassName );
	}

	this.processChangeLogin = function()
	{
		var that = this;
		var cabinet = new cabinetHandler();
		var data = {
			'login' : $(cabinet.sources.changeLoginForm).find(cabinet.sources.login).val(),
			'password' : $(cabinet.sources.changeLoginForm).find(cabinet.sources.password).val(),
		};
		that.loader.setLoader(cabinet.sources.changeLoginButton);

		$.ajax({
			url: that.actions.ajaxChangeLogin,
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function(data){
				that.loader.getElement();
				if(data == 1){
					that.errors.reset();
					$(cabinet.sources.changeLoginForm).find(cabinet.sources.login).val('');
					$(cabinet.sources.changeLoginForm).find(cabinet.sources.password).val('');
					that.getCabinetBlock();
				}
				else
					that.errors.show(data);
			}
		});
	}

	this.processChangePassword = function()
	{
		var that = this;
		var cabinet = new cabinetHandler();
		var data = {
			'password' : $(cabinet.sources.changePasswordForm).find(cabinet.sources.password).val(),
			'newPassword' : $(cabinet.sources.changePasswordForm).find(cabinet.sources.newPassword).val(),
			'newPasswordConfirm' : $(cabinet.sources.changePasswordForm).find(cabinet.sources.newPasswordConfirm).val(),
		};
		that.loader.setLoader(cabinet.sources.changePasswordButton);

		$.ajax({
			url: that.actions.ajaxChangePassword,
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function(data){
				that.loader.getElement();
				if(data == 1){
					that.errorsChangePassword.reset();
					$(cabinet.sources.changePasswordForm).find(cabinet.sources.password).val('');
					$(cabinet.sources.changePasswordForm).find(cabinet.sources.newPassword).val('');
					$(cabinet.sources.changePasswordForm).find(cabinet.sources.newPasswordConfirm).val('');
					that.getCabinetBlock();
				}
				else
					that.errorsChangePassword.show(data);
			}
		});
	}

	this.getPersonalDataBlockContent = function(clientType)
	{
		var that = this;
		var data = {
			'clientType' : clientType
		};
		$.ajax({
			before: (new cabinetHandler()).ajaxLoader.innerLoader((new cabinetHandler()).sources.personalDataBlock),
			url: that.actions.ajaxGetPersonalDataBlockContent,
			type: 'POST',
			data: data,
			dataType: 'html',
			success: function(data){
				that.errors.reset();
				$(new cabinetHandler().sources.personalDataBlock).html(data);
			}
		});
	}

	this.processSavePersonalDataButton = function()
	{
		var that = this;
		var cabinet = new cabinetHandler();
		var data = {
			'name' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.name).val(),
			'patronimic' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.patronimic).val(),
			'surname' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.surname).val(),
			'phone' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.phone).val(),
			'mobile' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.mobile).val(),
			'region' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.region).val(),
			'index' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.index).val(),
			'city' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.city).val(),
			'street' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.street).val(),
			'home' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.home).val(),
			'block' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.block).val(),
			'flat' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.flat).val(),
			'company' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.company).val(),
			'inn' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.inn).val(),
			'kpp' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.kpp).val(),
			'ogrn' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.ogrn).val(),
			'birthday' : $('input[name="birthday"]').val(),
			'birthDate' : $('select[name="birthDate"]').val(),
			'birthMonth' : $('select[name="birthMonth"]').val(),
			'birthYear' : $('select[name="birthYear"]').val(),

			'deliveryRegion' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.deliveryRegion).val(),
			'deliveryCity' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.deliveryCity).val(),
			'deliveryStreet' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.deliveryStreet).val(),
			'deliveryHome' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.deliveryHome).val(),
			'deliveryBlock' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.deliveryBlock).val(),
			'deliveryFlat' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.deliveryFlat).val(),
			'deliveryPerson' : $(cabinet.sources.savePersonalDataForm).find(cabinet.sources.deliveryPerson).val(),

			'clientType' : $(cabinet.sources.selectClientTypeRadio + ':checked', cabinet.sources.savePersonalDataForm).val(),
		};
		that.loader.setLoader(cabinet.sources.savePersonalDataButton);

		$.ajax({
			url: that.actions.ajaxSavePersonalData,
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function(data){
				that.loader.getElement();
				that.errors.reset();
				if(typeof data == 'number'  &&  data > 0){
					if(data.name)
						data.name = 'Укажите Ваше имя';
					that.errorsSavePersonalData.show(data);
				}
				else
					$(cabinet.sources.radioBlock).hide();
			}
		});
	}




}

