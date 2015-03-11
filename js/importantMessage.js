$(function(){
	$('.closeMessage').click(function(){
		$('.top_line').animate({'padding-top':0}, 600);
		$('.important-message').slideUp();
	});
});