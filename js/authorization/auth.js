$(function(){
	
	$('.button-e-t-s-1-show').click(function(){
		$(this).hide();
		$('.e-t-s-2').fadeIn('fast');
		
		$('.r-b-2').hide();
		$('.r-b-1').fadeIn('fast');
		
		$('.button-r-b-show').fadeIn('fast');
		location.hash = 'auth';
	});
	
	$('.button-r-b-show').click(function(){
		$(this).hide();
		$('.r-b-2').fadeIn('fast');
		
		$('.e-t-s-2').hide();
		$('.e-t-s-1').fadeIn('fast');
		$('.button-e-t-s-1-show').fadeIn('fast');
		
		location.hash = 'reg';
	});
	
	if( location.hash === '#auth' ) {
		$('.button-e-t-s-1-show').click();
	}
	if( location.hash === '#reg' ) {
		$('.button-r-b-show').click();
	}
	
	var authInContent = new form();
	authInContent.setSettings({'form':'.authorizationFormInContent', 'showError': false})
			.setLoader(new loader)
			.setCallback(function(response){
				if ( typeof response === 'number' )
					location.reload();
				else 
					$('.errorMessage').text(response.errorMessage).fadeIn(100).delay(1500).fadeOut(100);
			}).init();
			
	var regInContent = new form();
	regInContent.setSettings({'form':'.registrationFormInContent'})
			.setCallback(function(response){
				if ( typeof response === 'number' )
					location.reload();
			}).init();
	
	var logoutButton = new buttons;
	logoutButton.setSettings({'element':'.logout'})
			    .setCallback(function(response){
					if ( typeof response === 'number' ) {
						location.reload();
					}
				}).init();
	
});