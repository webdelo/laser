var error = function (element$, message) {

	if(message == null)
		message = 'Заполните пожалуйста поле!';

	this.position = {
		'position' : 'absolute',
		'top'      : element$.offset().top,
		'z-index'  : '151'
	};

	this.error$      = $('<div class="hint"><div class="hint_in"><div class="arr2"></div><div class="hintxt"><p>'+message+'</p></div></div></div>');
	this.element$    = element$;

	this.destination = 'body';
	this.message     = message ;

	this.setBlock = function (block$) {
		this.error$ = block$;
		return this;
	};

	this.setPosition = function (position) {
		this.position = position;
		return this;
	};

	this.setDestination = function (destination) {
		this.destination = destination;
		return this;
	}

	this.adaptPosition = function()
	{
	    var position;
	    if(element$.data('error-position') === undefined)
			position = 'left';
	    else
			position = element$.data('error-position');

	    this.error$.attr('position', position);

	    this.setDisplay(position);
	};

	this.setDisplay = function(position)
	{
	    if(position == 'right') {
			this.position.left = element$.offset().left;
			this.position.top = element$.offset().top;
			this.error$.find('.arr2').css({'right' : '-10px','top' : '5px','background' : 'url("/admin/js/base/actions/images/right.png") no-repeat scroll center center transparent'});
	    }
	    if(position == 'left') {
			this.position.left = element$.offset().left + element$.width() + 20 ;
			this.position.top = element$.offset().top;
			this.error$.find('.arr2').css({'left' : '-10px',  'top' : '5px'});
	    }
	    if(position == 'top') {
			this.position.left = element$.offset().left + (element$.width() / 2) ;
			this.position.top = element$.offset().top - element$.height() - 10;
			this.error$.find('.arr2').css({'right' : '50%', 'bottom' : '-10px', 'background' : 'url("/admin/js/base/actions/images/bottom.png") no-repeat scroll center center transparent'});
	    }
	    if(position == 'bottom') {
			this.position.left = element$.offset().left + (element$.width() / 2) ;
			this.position.top = element$.offset().top + element$.height() + 10;
			this.error$.find('.arr2').css({'right' : '50%', 'top' : '-10px', 'background' : 'url("/admin/js/base/actions/images/top.png") no-repeat scroll center center transparent'});
	    }
	};

	this.show = function () {
	    this.adaptPosition();

		this.beforeShow();
		$(this.destination).append(this.error$);

		$(this.error$)
			.css(this.position)
			.fadeIn('slow');

		if ( $(window).scrollTop() > this.position.top ) {
			$(this.error$).autoScroll({ 'paddingTop': '50' });
		}

		if ( 'errors' in window) {
			window.errorsCounter = 1;
		}
	};

	this.beforeShow = function () {
		var that = this;
		this.element$.blur(function(){
			that.error$.stop(true, true).fadeOut('fast', function(){
				$(this).hide();
			});
		});
		
		this.error$
			.click(this.remove)
			.click($.proxy(this.resetInput, that))
			.mouseenter(this.mouseenter)
			.mouseleave(this.mouseleave);
	};

	this.resetInput = function () {
		this.element$.val(this.element$.attr('data-start-value'));
		
	};
	
	this.remove = function () {
		$(this).fadeOut('fast').remove();
	};

	this.mouseenter = function () {
		$(this).animate({'opacity': 0.4}, 'fast');
	};

	this.mouseleave = function () {
		$(this).animate({'opacity': 1}, 'fast');
	};
};