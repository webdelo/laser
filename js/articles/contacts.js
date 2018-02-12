$(function(){
	$('.cont').gallery({'opacity': 0.8});
	
	var feedback = new form;
	feedback.setSettings({'form':'.feedbackForm'})
			.setLoader(new loaderLight)
			.setCallback(function(response){
				if (typeof response === 'number'){
					feedback.reset();
					$('.messageSuccess').fadeIn('fast').delay(4000).fadeOut();
				}
			}).init();
	
});