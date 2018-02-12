$(document).ready(function(){
	var wt = $(".update_info table").width();
	if (wt < $(".update_info").width()) {
		$(".update_info .prev").hide();
		$(".update_info .next").hide();
	};

	$(".update_info .prev").hover(function ()
	{timer = setInterval(leftScroll, 10);},
  		function(){
	  clearInterval(timer);
	});

  $(".update_info .next").hover(function ()
  {timer = setInterval(rightScroll, 10);},
 		function(){
	  clearInterval(timer);
	});

	$('.list_item .title').click(function(){
		$(this).parent().find('table').toggle();
		$(this).parent().toggleClass('active');
		if ($(this).parent().hasClass('active')) {
			$(this).find('span').html('&#9660;')
		} else {
			$(this).find('span').html('&#9658;')
		}
	});

	var wh = parseInt($(window).height());
	var wa = parseInt($('.auth_box').height());
	var delta = (wh - wa)/2;
	if (wh > wa) {
		$('.auth_box').css('top',delta - 50 + 'px');
	}
	$(window).resize(function(){
		var wh = parseInt($(window).height());
		var wa = parseInt($('.auth_box').height());
		var delta = (wh - wa)/2;
		if (wh > wa) {
			$('.auth_box').css('top',delta - 50 + 'px');
		}
	});


	$('.logout a').click(function(){
		$('.pop').show();
		$('.pop .login_box').show();
	});

	$('.pop_bg').click(function(){
		$('.pop').hide();
		$('.pop .login_box').hide();
		$('.check_delete').hide();
	});

	$('.close').click(function(){
		$(this).parent().hide();
		$('.pop').hide();
	});

	$('.table_edit .desc').click(function(){
		$(this).parents('tr').toggleClass('nobr');
		$(this).parents('tr').next('tr.description').toggle();
		return false;
	});

	$('.action_edit .status a').click(function(){
		$('.action_edit .status a').removeClass('on');
		$(this).addClass('on');
		return false;
	});

});

var timer;
function leftScroll(){
	document.getElementById("scr").scrollLeft -= 4;
}
function rightScroll(){
	document.getElementById("scr").scrollLeft += 4;
};