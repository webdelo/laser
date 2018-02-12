$(function(){
	
	$('.i18n.activator').focus(showI18nBlock);
	
	$('body').click(hideActiveI18nBlock);
	
	generateTags();
	
	function showI18nBlock(e){
		hideBlock($('.i18nBlock.active').not($(this).parents('.i18nBlock')));
		
		var height = $(this).parent('.wrapI18n').outerHeight(true);
		$(this).parent('.wrapI18n')
			   .siblings('.wrapI18n')
			   .each(function(){
				   height += $(this).outerHeight(true);
			   });
			   
		$(this).parents('.i18nBlock')
			   .addClass('active');
//			   .animate({'height': height}, 200);
	   $('.i18nBlock.active').find('span').css({'display':'inline-block'});
	
		e.stopPropagation();
	}
	function generateTags()
	{
		var that$ = $('.i18n.activator');
		
		that$.each(function(){
			var elements$ = $(this).parent().children('.i18n');
			var i18nBlock$ = $('<div class="i18nBlock">').click(function(e){e.stopPropagation()}).height($(this).outerHeight(true) +10);
			var wrap$      = $('<div class="wrapI18n">').css({'position':'relative'});
			var title$     = $('<span>');

			$(this).parents('td').css({'position':'relative', 'height':i18nBlock$.outerHeight(true)}).append( i18nBlock$ );

			elements$.each(function(){
				$(this).css({'display':'inline-block'}).wrap(wrap$);
				$(this).before( title$.text($(this).data('title')).clone() );
				i18nBlock$.append($(this).parent(wrap$));
			});
		});
	}
	function hideActiveI18nBlock()
	{
		hideBlock($('.i18nBlock.active'));
	}
	function hideBlock(block$)
	{
		var height = block$.parent('td').outerHeight(true);
		block$.find('span').fadeOut(50, function(){
			block$.removeClass('active');
		});
	}
	
});
