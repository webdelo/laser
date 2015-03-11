$(function(){
	
	var parametersEdit = new form;
	parametersEdit.setSettings({
			'form'    : '.assignParametersToCategory',
			'onBeforeSend' : function () {parametersEdit.loader.start();}
		})
		.setCallback(function (response) {
			parametersEdit.loader.stop();
		})
		.init();
});