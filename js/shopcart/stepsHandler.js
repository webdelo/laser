$(function(){
	
	$('.FirstEditButton').live('click', function(){
		$('.ShopcartConfirmContent').hide('fast');
		$('.shopcartContent').show('fast');
		$('.ShopcartStep0').removeClass('travel').addClass('active');
		return false; 
	});
	
	$('.SecondEditButton').live('click', function(){
		$('.ShopcartConfirmContent').hide('fast');
		$('.shopcartContent').show('fast');
		$('.ShopcartStep1').removeClass('travel').addClass('active');
		return false; 
	});
	
	$('.ThirdEditButton').live('click', function(){
		$('.ShopcartConfirmContent').hide('fast');
		$('.shopcartContent').show('fast');
		$('.ShopcartStep2').removeClass('travel').addClass('active');
		return false; 
	});
	
	$('.FourthEditButton').live('click', function(){
		$('.ShopcartConfirmContent').hide('fast');
		$('.shopcartContent').show('fast');
		$('.ShopcartStep3').removeClass('travel').addClass('active');
		return false; 
	});
	
	$('.printPageButton').live('click', function(){
		window.print();  
		return false; 
	});
	
	$('.shopcartAuthButton').live('click', function(){
		$(this).hide();
		$('.e-t-s-2').fadeIn('fast');
		
		$('.r-b-2').hide();
		$('.r-b-1').fadeIn('fast');
		
		$('.button-r-b-shopcart').fadeIn('fast');
	});
	
	$('.shopcartRegButton').live('click', function(){
		$(this).hide();
		$('.r-b-2').fadeIn('fast');
		
		$('.e-t-s-2').hide();
		$('.e-t-s-1').fadeIn('fast');
		$('.button-e-t-s-1-shopcart').fadeIn('fast');
	});
	
	$('.Step0Button').live('click', function(){
		$('.Step0Content').hide(400);
		setTimeout(function() { 
			$('.ShopcartStep0').removeClass('active').addClass('travel');
			$('.ShopcartStep1').removeClass('travel').addClass('active');
			$('.Step0Content').fadeIn(500);
		}, 450); 
	});
	
//	$('.Step1Button').live('click', function(){
//		$('.Step1Content').hide(400);
//		setTimeout(function() { 
//			$('.ShopcartStep1').removeClass('active').addClass('travel');
//			$('.ShopcartStep2').removeClass('travel').addClass('active');
//			$('.Step1Content').fadeIn(500);
//		}, 450); 
//		setActiveStep(2);
//	});
	
//	$('.Step2Button').live('click', function(){
//		$('.Step2Content').hide(400);
//		setTimeout(function() { 
//			$('.ShopcartStep2').removeClass('active').addClass('travel');
//			$('.ShopcartStep3').removeClass('travel').addClass('active');
//			$('.Step2Content').fadeIn(500);
//		}, 450); 
//		setActiveStep(3);
//	});
	
	$('.Step3Button').live('click', function(){
		$('.Step3Content').hide(400);
		$('.ShopcartStep3').removeClass('active').addClass('travel');
		$('.Step3Content').fadeIn(500);
		
		$('.shopcartContent').hide(800);
		$('.ShopcartConfirmContent').show(700);
	});
	
//	$('.name-cart').on('click', function(){
//		if ($(this).parent().parent().hasClass("active")) {
//			//$('.Step1Content').fadeIn(500);
//			$(this).parent().parent().removeClass('active').addClass('travel');
//		} else {
//			//$('.Step1Content').fadeOut(500);
//			$(this).parent().parent().removeClass('travel').addClass('active');
//			$('.Step1Content').css('height','');
//		}
//	});
	
	setActiveStep = function (step) {
		
		switch (step) {
		  case 1:
			$('.first-step').removeClass('active').addClass('travel');
			if ($('.first-step').hasClass('active')) { $('.second-step').removeClass('active'); }
			if ($('.first-step').hasClass('travel')) { $('.second-step').removeClass('travel'); }
			$('.first-step').addClass('active');
			break
		  case 2:
			$('.first-step').removeClass('active').addClass('travel');
			$('.second-step').addClass('active');
			$('.CurrentStep').text('2');
			break
		  case 3:
			$('.second-step').removeClass('active').addClass('travel');
			$('.third-step').addClass('active');
			$('.CurrentStep').text('3');
			break
		}

	};
	
});