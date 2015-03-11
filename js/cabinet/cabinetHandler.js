$(function(){
	(new cabinetHandler())
		.clickCabinetAuthorizatedButton()
		.clickRollUpButton()
		.clickChangeLoginButton()
		.clickChangePasswordButton()
		.clickSelectClientTypeRadio()
		.clickSavePersonalDataButton();
});

var cabinetHandler = function()
{
	this.sources = {
		'cabinet' : '.mycab',
		'cabinetClassName'		:   'mycab',
		'cabinetAuthorizatedClassName'		:   'mycabAuthorizated',
		'cabinetAuthorizated'		:   '.mycabAuthorizated',
		'loginForm'		:   '.panel .box_log',
		'rollUpButton' : '.rollUp',

		'changeLoginForm' : '.changeLoginForm',
		'changeLoginButton' : '.changeLoginButton',
		'login' : 'input[name=login]',
		'password' : 'input[name=password]',
		'changePasswordForm' : '.changePasswordForm',
		'changePasswordButton' : '.changePasswordButton',
		'newPassword' : 'input[name=newPassword]',
		'newPasswordConfirm' : 'input[name=newPasswordConfirm]',

		'savePersonalDataForm' : '.savePersonalDataForm',
		'savePersonalDataButton' : '.savePersonalDataButton',
		'name' : 'input[name=name]',
		'patronimic' : 'input[name=patronimic]',
		'surname' : 'input[name=surname]',
		'phone' : 'input[name=phone]',
		'mobile' : 'input[name=mobile]',
		'region' : 'input[name=region]',
		'index' : 'input[name=index]',
		'city' : 'input[name=city]',
		'street' : 'input[name=street]',
		'home' : 'input[name=home]',
		'block' : 'input[name=block]',
		'flat' : 'input[name=flat]',
		'company' : 'input[name=company]',
		'inn' : 'input[name=inn]',
		'kpp' : 'input[name=kpp]',
		'ogrn' : 'input[name=ogrn]',

		'deliveryRegion' : 'input[name=deliveryRegion]',
		'deliveryCity' : 'input[name=deliveryCity]',
		'deliveryStreet' : 'input[name=deliveryStreet]',
		'deliveryHome' : 'input[name=deliveryHome]',
		'deliveryBlock' : 'input[name=deliveryBlock]',
		'deliveryFlat' : 'input[name=deliveryFlat]',
		'deliveryPerson' : 'input[name=deliveryPerson]',

		'selectClientTypeRadio' : 'input[name=selectClientType]',
		'radioBlock' : '.radioBlock',
		'personalDataBlock' : '.personal',
	}

	this.ajaxLoader = new ajaxLoader();

	this.clickCabinetAuthorizatedButton = function()
	{
		var that = this;
		$(that.sources.cabinetAuthorizated).live('click', function(){
			if($(that.sources.loginForm).css('display') != 'block')
				(new cabinet).getCabinetBlock();
			$(that.sources.loginForm).toggle();
		});
		return this;
	}

	this.clickRollUpButton = function()
	{
		var that = this;
		$(that.sources.rollUpButton).live('click', function(){
			$(that.sources.loginForm).css('display', 'none');
		});
		return this;
	}

	this.clickChangeLoginButton = function()
	{
		var that = this;
		$(that.sources.changeLoginButton).live('click',function(){
			(new cabinet).processChangeLogin();
		});
		return this;
	}

	this.clickChangePasswordButton = function()
	{
		var that = this;
		$(that.sources.changePasswordButton).live('click',function(){
			(new cabinet).processChangePassword();
		});
		return this;
	}

	this.clickSelectClientTypeRadio = function()
	{
		var that = this;
		$(that.sources.selectClientTypeRadio).live('change', function(){
			(new cabinet).getPersonalDataBlockContent(this.value);
		});
		return this;
	}

	this.clickSavePersonalDataButton = function()
	{
		var that = this;
		$(that.sources.savePersonalDataButton).live('click',function(){
			(new cabinet).processSavePersonalDataButton();
		});
		return this;
	}
}


