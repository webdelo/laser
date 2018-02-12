$(function(){
	$('.transformer').transformer();
	$('.measurements').transformer();

	(new inputs)
		.setSettings({'element' : '.values'})
		.setCallback(function (response) {
			if ( typeof response !== 'number' )
				alert(response);
			else
				$('.propertyValuesBlock').htmlFromServer();
		})
		.init();
});