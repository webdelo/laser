
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
		$('.videoModal, .YouTubeModal')
			.animate({opacity: 0, top: '45%'}, 200,  
				function(){
                    $('.YouTubeModal iframe').attr('src', '');
					$(this).css('display', 'none');
					$('.bgVideo').fadeOut(400); 
				}
			);
	});
    
	$('.playYouTube').click( function(event){ 
		event.preventDefault();
        var playYouTube = $(this).attr('href');
		$('.bgVideo').fadeIn(400, 
		 	function(){
				$('.YouTubeModal')
					.css('display', 'block')
					.animate({opacity: 1, top: '50%'}, 200);
                    
                $('.YouTubeModal iframe').attr('src', playYouTube);
		});
	});
    //$(a).each(function(){cnt++;$(this).attr('data-img-id',cnt);});
});


