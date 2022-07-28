// script for mobile nav
$(document).ready(function(){
    $('.mobile-nav').click(function () {
        $(".mobile-nav, .naw").toggleClass('active');
        $("body, header").toggleClass('lock');
    });
});

$(document).on('mouseup', function(e){
  let s = $('.mobile-nav, .naw');
  if(!s.is(e.target) && s.has(e.target).length === 0) {
    s.removeClass('active');
    $("body, header").removeClass('lock');
  }
});



// script for header fixed
$(function(){
	$(window).scroll(function() {
		if($(this).scrollTop() >= 100) {
		$('header, body').addClass('fixed');
		}
		else{
		$('header, body').removeClass('fixed');
		}
	});
});