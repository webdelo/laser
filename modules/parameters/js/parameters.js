$(function () {
	var parameters = new parameterValues;
	var newParameterValue = new form();
	newParameterValue.setSettings({
		'form'    : '.newParameterForm'
	})
	.setCallback(function (response) {
		if (typeof response === "number") {
			parameters.reload();
			newParameterValue.getObject().find('input[name=value]').val('').focus();
			$('.parameterDescription').hide().find('textarea').val('');
		}
	})
	.setLoader(new loaderLight)
	.init();
	
	var removeParameterValue = new buttons();
	removeParameterValue.setSettings({
			'element'    : '.deleteParameterValue'
		})
		.setCallback(function (response) {
			if (typeof response === "number") {
				parameters.reload();
			}
 		})
		.setLoader(new loaderLight)
		.init();


	$('.showDescription').click(function(){
		$('.parameterDescription').show().find('textarea').focus();
		$('.hideDescription').show();
		$(this).hide();
	});
	
	$('.hideDescription').click(function(){
		$('.parameterDescription').hide()
		$('.showDescription').show();
		$(this).hide();
	});
});

var parameterValues = function (settings) {
	this.settings = $.extend({
		'element' : '.parameterValuesBlock',
	}, settings||{});
	
	this.getObject = function () {
		return $(this.settings.element);
	};
	
	this.reload = function(){
		$(this.settings.element).htmlFromServer();
	};
};