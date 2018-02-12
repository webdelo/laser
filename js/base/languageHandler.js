$(function(){
	$('.lang a').on( "click", function(event) {
		var that = $(this);
		event.preventDefault();
		if (that.data('lng') == 'en') {
			window.location = "http://en.run-laser.com/";
		} else
			window.location = "http://run-laser.com/";
	});
	
});