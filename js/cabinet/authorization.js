$(function(){
	
	function onBeforeSend(){
		$('.confirmation').fadeOut();
	};
	
	var editPasswordForm = new form;
	editPasswordForm
		.setSettings({
			'form':'.two',
			'onBeforeSend':onBeforeSend
		})
		.setLoader(new loader)
		.setCallback(function(response){
			if (typeof response == 'number') {
				successSentPassword();
			}
		}).init();

	function successSentPassword(){
		editPasswordForm.reset();
		$('.confirmation').fadeIn().delay(4000).fadeOut();
	};
	
});