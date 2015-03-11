$('[data-clickText]').live('focus', function(){
	var object = $(this);
	if(object.val() == ''){
		object.val(object.attr('data-clickText'));
	}
});

$('input').live('focus', function(){
	var object = $(this);
	setTimeout(function(){
		try {
			window.getSelection().removeAllRanges();
		  } catch(e) {
			document.selection.empty();
		  }
		object.setCursorPosition(object.val().length);
	}, 1);
});

$('[data-clickText]').live('focusout', function(){
	if($(this).val() == $(this).attr('data-clickText'))
		$(this).val('');
});

$.fn.setCursorPosition = function(pos) {
	this.each(function(index, elem) {
		if (elem.setSelectionRange) {
			elem.setSelectionRange(pos, pos);
		} else if (elem.createTextRange) {
			var range = elem.createTextRange();
			range.collapse(true);
			range.moveEnd('character', pos);
			range.moveStart('character', pos);
			range.select();
		}
	});
	return this;
};


