$(function(){
	$('.parameterInput').live('click', function() {
		$('.formEditSubmit').click();
	});
	
	var parameters = new form;
	parameters.setSettings({'form':'.parametersForm'}).init();
	
	var inputsMonitoring = function () {
		var editParameterValue = new inputs;
		editParameterValue.setSettings({'element' : '.editParameterValue', 'showError': false})
			.setCallback(function (response) {
				if ( typeof response !== 'number' ) {
					alert('Error '+response);
				}
			})
			.init();

		(new inputs)
			.setSettings({'element' : '.newParameter', 'showError': false})
			.setCallback(function (response) {
				if ( typeof response === 'number' ) {
					reloadParametersBlock();
				}
			})
			.init();

		(new inputs)
			.setSettings({'element' : '.newParameterPart', 'showError': false})
			.setCallback(function (response) {
				if ( typeof response === 'number' ) {
					reloadParametersBlock();
				}
			})
			.init();

		(new inputs)
			.setSettings({'element' : '.editParameterPart', 'showError': false})
			.setCallback(function (response) {
				if ( typeof response === 'number' ) {
					reloadParametersBlock();
				}
			})
			.init();

		(new selects)
			.setSettings({'element' : '.editParameterPartChooseMode', 'showError': false})
			.setCallback(function (response) {
				if ( typeof response === 'number' ) {
					reloadParametersBlock();
				}
			})
			.init();
	}
	inputsMonitoring();
	
	var reloadParametersBlock = function(){
		$('.parameterBlocks').htmlFromServer({
			'callback':inputsMonitoring
		});
	};
});