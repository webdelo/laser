$(function () {
	var properties = new propertyValues;
	var newPropertyValue = new form();
	newPropertyValue.setSettings({
		'form'    : '.newPropertyForm'
	})
	.setCallback(function (response) {
		if (typeof response === "number") {
			properties.reload();
			newPropertyValue.getObject().find('input[name=value]').val('').focus();
			$('.propertyDescription').hide().find('textarea').val('');
		}
	})
	.setLoader(new loaderLight)
	.init();
	
	(new selects)
		.setSettings({'element' : '.measurements'})
		.setCallback(function (response) {
			if ( typeof response !== 'number' )
				alert(response);
			else
				$('.propertyValuesBlock').htmlFromServer();
		})
		.init();
	
	var removePropertyValue = new buttons();
	removePropertyValue.setSettings({
			'element'    : '.deletePropertyValue'
		})
		.setCallback(function (response) {
			if (typeof response === "number") {
				properties.reload();
			}
 		})
		.setLoader(new loaderLight)
		.init();


	$('.showDescription').click(function(){
		$('.propertyDescription').show().find('textarea').focus();
		$('.hideDescription').show();
		$(this).hide();
	});
	
	$('.hideDescription').click(function(){
		$('.propertyDescription').hide()
		$('.showDescription').show();
		$(this).hide();
	});
});

var propertyValues = function (settings) {
	this.settings = $.extend({
		'element' : '.propertyValuesBlock',
	}, settings||{});
	
	this.getObject = function () {
		return $(this.settings.element);
	};
	
	this.reload = function(){
		$(this.settings.element).htmlFromServer();
	};
};