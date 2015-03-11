// Данный класс должен содержать методы:
// start,stop,title, init
// Таким образом он будет без ошибок работать в классах form, selects, buttons
var loaderBlock = function (image) {
	this.loadingBlock = '.ajax_block';
	this.title        = '.loaderTitle';
	this.image        = image || '/admin/images/loaders/ajax-loader.gif';
	
	this.img$        = $('<div id="ajax_block_img"><img src="'+this.image+'"/></div>');
	this.title$      = $('<span class="'+this.title.replace('.', '')+'">Загрузка данных...</span>');
	this.bg$         = $('<div class="'+this.loadingBlock.replace('.', '')+'">&nbsp;</div>').css('position', 'absolute');
	this.destination = 'body';

	this.init = function (object$) {
		if ( !this.issetTarget() )
			this.setTarget(object$);
		
		if ( $('body').find(this.loadingBlock).length >= 1 ){
			return this;
		}
				
		this.bg$
			.append(
				this.img$.append(this.title$)
			)
			.appendTo(this.destination)
			.hide();
	
		return this;
	};
	
	this.issetTarget = function () {
		return this.target$ !== undefined ;
	}
	
	this.setTarget = function (object$) {
		if ( object$ === undefined ) {
			alert('You must specify the object which will be imposed loading');
		}
		this.target$ = object$;
		return this;
	};
	
	this.getTarget = function () {
		if ( $(this.target$).length === 0 ) {
			this.setTarget($(this.target$.selector));
			if ( $(this.target$).length === 0 ) {
				alert('Selector '+this.target$.selector+' choosed no one object');
				return this;
			}
		}
		return this.target$;
	};

	this.start = function (object$) {
		this.setTarget(object$).getTarget().fadeTo('0.2');
		this.copy$ = $(this.loadingBlock).first().clone();
		this.copy$.appendTo(this.destination).css(this.getParameters()).fadeIn();
		
		return this;
	};
	
	this.getParameters = function(){
		var object$ = this.getTarget();
		var parameters = {
			'width'  : object$.outerWidth(),
			'height' : object$.outerHeight(),
			'top'    : object$.offset().top,
			'left'   : object$.offset().left
		};
		return parameters;
	};
	
	this.title = function (title) {
		$(this.loadingBlock+' '+this.title).text(title);
		
		return this;
	};
	
	this.stop = function (object$) {
		this.setTarget(object$).getTarget().fadeTo('slow','1');
		this.copy$.fadeOut().remove();
		
		return this;
	};
};