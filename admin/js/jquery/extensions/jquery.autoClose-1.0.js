(function($){
	$.fn.autoClose = function (options) {
		var settings = $.extend({
			'duration'   : '400',
			'paddingTop' : '0',
			'callback'   : null
		}, options||{});
		
		var this$ = $(this);
		
		$("body").click(function(click){
			var left_top = this$.offset();
			if(left_top) {
				var block_position = {
					'left': left_top.left,
					'top': left_top.top,
					'right': this$.width() + left_top.left,
					'bottom': this$.height() + left_top.top
				};
				if(!checkClickPosition(click, block_position))
					this$.hide();
			}
		});
		
		function checkClickPosition (click_position, block_position) {
			if (checkPageInX(click_position, block_position) && checkPageInY(click_position, block_position))
				return true;
			return false;
		}

		function checkPageInX(click_position, block_position) {
			if ((block_position.bottom > click_position.pageY) && (click_position.pageY > block_position.top))
				return true;
			return false;
		}
		
		function checkPageInY(click_position, block_position) {
			if ((block_position.right > click_position.pageX) && (click_position.pageX > block_position.left))
				return true;
			return false;
		}
		
		return this;
	}
})(jQuery);

