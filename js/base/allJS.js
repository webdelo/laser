
$(document).ready(function(){
	$('.mainH1,.priceLabel').gradientText({
		colors: ['#6bc85b', '#2f9773']
	});
});

$(document).ready(function(){
	$('.mainH2').gradientText({
		colors: ['#6bc85b', '#2f9773']
	});
});

$(document).ready(function(){
	$('.pAsMainH2').gradientText({
		colors: ['#6bc85b', '#2f9773']
	});
});


$(document).ready(function() { 
	$('.playBottom').click( function(event){ 
		event.preventDefault(); 
		$('.bgVideo').fadeIn(400, 
		 	function(){ 
				$('.videoModal') 
					.css('display', 'block') 
					.animate({opacity: 1, top: '50%'}, 200); 
		});
	});
	$('.bgVideo, .close').click( function(){ 
		$('.videoModal')
			.animate({opacity: 0, top: '45%'}, 200,  
				function(){ 
					$(this).css('display', 'none'); 
					$('.bgVideo').fadeOut(400); 
				}
			);
	});
});


