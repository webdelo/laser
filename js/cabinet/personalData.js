$(function(){
	var editLoginForm = new form;

	editLoginForm
		.setSettings({'onBeforeSend':onBeforeSend})
		.setLoader(new loader)
		.setCallback(function(response){
			if (typeof response == 'number') {
				$('.confirmation').fadeIn().delay(4000).fadeOut();
			}
		}).init();
		
	function onBeforeSend(){
		$('.confirmation').fadeOut();
	};
});