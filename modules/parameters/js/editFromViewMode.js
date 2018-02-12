$(function(){
	$('.transformer').transformer();

	(new inputs)
		.setSettings({'element' : '.values'})
		.setCallback(function (response) {
			if ( typeof response !== 'number' )
				alert(response);
			else
				$('.parameterValuesBlock').htmlFromServer();
		})
		.init();
});