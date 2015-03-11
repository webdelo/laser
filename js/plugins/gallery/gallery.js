(function($){
	$.fn.gallery = function (settings) {
		var gallery = function(settings) {
			this.element$    = {};
			this.container$  = $('<div></div>');
			this.background$ = $('<div></div>');
			this.imageContainer$ = $('<div></div>');
			this.image$      = $('<img>');
			this.preview$    = $('<div></div>');
			this.preImages$  = $('<div></div>');
			this.close$      = $('<a></a>');
			this.prev$       = $('<a></a>');
			this.next$       = $('<a></a>');
			this.line$       = {};
			
			this.settings = $.extend({
				'container'      : 'gallery-container',
				'background'     : 'gallery-background',
				'close'          : 'gallery-close',
				'prev'           : 'gallery-prev',
				'imageClass'     : 'gallery-image',
				'imageContainerClass' : 'gallery-image-container',
				'previewClass'   : 'gallery-preview',
				'preImagesClass' : 'gallery-pre-images',
				'opacity' : 1
			},settings||{});
			
			this.init = function (target$, imagesList) {
				var that = this;
				this.imagesList = imagesList;
				
				$(target$).live('click', function(){
					that.setLine(target$)
						.setElement($(this))
						.start();
					return false;
				});
			};
			
			this.setLine = function (line$) {
				this.line$ = line$;
				return this;
			};
			
			this.getLine = function () {
				return this.line$;
			};
			
			this.setElement = function (element$) {
				this.element$ = element$;
				return this;
			};
			
			this.getElement = function () {
				return this.element$;
			};
			
			this.start = function(){
				this.createContainer()
					.createBackground()
					.createPreviewBlock()
					.createBigImage()
					.createClose()
					.createPrev()
					.createNext()
					.show();
				
				var that = this;
				$(window).resize(function(){
					that.setAllParameters();
				});
			};
			
			this.isSingleImage = function(){
				return ( this.imagesList.imagesList.length == 1 );
			};
			
			this.createContainer = function(){
				var that = this;
				this.container$
					.addClass(this.settings.container)
					.css({
						'display'         : 'block',
						'opacity'		  : '0',
						'top'			  : '0',
						'left'			  : '0',
						'position'        : 'fixed',
						'z-index'         : '999999',
						'width'           : '100%',
						'height'          : $('body').outerHeight(),
					});
				
				$('body').append(this.container$);
				return this;
			};
			
			this.createBackground = function(){
				var that = this;
				this.background$
					.addClass(this.settings.background)
					.css({
						'display'         : 'block',
						'opacity'         : that.settings.opacity,
						'top'			  : '0',
						'left'			  : '0',
						'position'        : 'fixed',
						'z-index'         : '1000000',
						'background-color': '#000',
						'width'           : '100%',
						'height'          : $('body').outerHeight(),
					});
				
				$(this.container$).append(this.background$);
				return this;
			};
			
			this.createBigImage = function(){
				this.createImage(this.imagesList.get( this.getCurrentImageIndex() ));
				return this;
			};
			
			this.getCurrentImageIndex = function () {
				return this.getElement().index(this.getLine().selector)
			};
			
			this.createImage = function (image$) {
				$(this.container$).find('.'+this.settings.imageClass).remove();
				
				this.image$ = image$;
				this.image$
					.addClass(this.settings.imageClass)
					.css({
						'display'  : 'none',
						'position' : 'fixed',
						'top'      : '50px',
						'z-index'  : '1000000',
					});
				
				this.imageContainer$.append(this.image$).addClass(this.settings.imageContainerClass);
					
				$(this.container$).append(this.imageContainer$.append(this.image$));
				this.setCurrentInPreviewBlock()
					.setRatioByCurrentImage();
			};
			
			this.createPreviewBlock = function() {
				var that = this;
				this.preview$
					.addClass(this.settings.previewClass)
					.css({
						'display'  : that.isSingleImage()?'none':'block',
						'position' : 'fixed',
						'bottom'   : '10px',
						'width'    : 'auto',
						'height'   : '60px',
						'z-index'  : '1000002',
						'overflow' : 'hidden'
					});
					
				this.preImages$
					.addClass(this.settings.preImagesClass)
					.css({
						'display'  : that.isSingleImage()?'none':'block',
						'position' : 'absolute',
						'top'      : '0',
						'left'     : '0',
						'width'    : '5000px',
						'height'   : '60px',
						'z-index'  : '1000002',
					});
						
				var that = this;
				this.preImages$.children().remove();
				$.each(this.imagesList.imagesList, function(){
					that.preImages$
						.append(
							$(this).clone()
								.removeClass(that.settings.imageClass)
								.css({
									'height':'55px',
									'cursor':'pointer',
									'width' :'100px',
									'height':'60px',
									'margin':'2px'
								}).click(function(){
									that.changeImage(that.imagesList.get( $(this).index() ));
								})
						);
				});
				
				$(this.container$).append(
					this.preview$.append(this.preImages$)
				);
				return this;
			};
			
			this.changeImage = function (image$) {
				var that = this;
				this.image$
					.fadeOut(20, function(){ 
						that.createImage(image$);
						that.image$.fadeIn(50);
						that.setAllParameters();
					});
			};
			
			this.setRatioByCurrentImage = function () {
				var sourceImage = this.image$.prevObject[0];
				var width  = sourceImage.naturalWidth;
				var height = sourceImage.naturalHeight;
				this.ratioWidth  = width / height;
				this.ratioHeight = height / width;
				return this;
			};
			
			this.setCurrentInPreviewBlock = function () {
				this.current$  = this.preImages$
									.children()
									.css('border', 'none')
									.eq(this.imagesList.currentElement)
									.css('border', 'solid 1px #479ebc');
				return this;
			};
			
			this.createClose = function(){
				var that = this;
				this.close$
					.css({
						'position' : 'fixed',
						'opacity'  : '.6',
						'top'      : '0px',
						'left'     : '0px',
						'cursor'   : 'pointer',
						'width'    : '17px',
						'height'   : '17px',
						'z-index'  : '1000002',
						'background': 'url(/js/plugins/gallery/close.png) no-repeat right 70%',
					}).hover(function(){ 
						$(this).stop(true, true).fadeTo('fast', 1) 
					}, function(){ 
						$(this).stop(true, true).fadeTo('fast', .6) 
					}).click(function(){
						that.stop();
					});
					
				$(this.container$).append(this.close$);
				return this;
			};
			
			this.setParametersToCloseByBlock = function (block$) {
				var wParameters = this.getWindowParameters();
				var css = {
					'width'  : '91%',
					'height' : block$.offset().top - $(document).scrollTop()
				};
				this.close$.css(css);
				
				return this;
			};
			
			this.createPrev = function(){
				var that = this;
				this.prev$
					.css({
						'position' : 'fixed',
						'display'  : that.isSingleImage()?'none':'block',
						'opacity'  : '.6',
						'top'      : '0',
						'left'     : '0',
						'cursor'   : 'pointer',
						'width'    : '17px',
						'height'   : '17px',
						'z-index'  : '1000001',
						'background': 'url(/js/plugins/gallery/left.png) no-repeat right center',
						'min-width'    : '20px'
					}).hover(function(){ 
						$(this).stop(true, true).fadeTo('fast', 1) 
					}, function(){ 
						$(this).stop(true, true).fadeTo('fast', .6) 
					}).click(function(){
						that.prev();
					});
					
				$(this.container$).append(this.prev$);
				return this;
			};
			
			this.createNext = function(){
				var that = this;
				this.next$
					.css({
						'position' : 'fixed',
						'opacity'  : '.6',
						'display'  : that.isSingleImage()?'none':'block',
						'top'      : '0',
						'right'    : '0',
						'cursor'   : 'pointer',
						'width'    : '17px',
						'height'   : '17px',
						'z-index'  : '1000001',
						'background': 'url(/js/plugins/gallery/right.png) no-repeat left center',
						'min-width'    : '20px'
					}).hover(function(){ 
						$(this).stop(true, true).fadeTo('fast', 1);
					}, function(){ 
						$(this).stop(true, true).fadeTo('fast', .6);
					}).click(function(){
						that.next();
					});
					
				$(this.container$).append(this.next$);
				return this;
			};
			
			this.show = function () {
				this.image$.fadeIn(1);
				this.container$.fadeTo('fast', 1);
				this.setAllParameters();
			};
			
			this.setAllParameters = function(){
				this.setParametersToImage()
					.setParametersToCloseByBlock(this.image$)
					.setParametersToPreviewBlock()
					.setParametersToPrevByBlock(this.image$)
					.setParametersToNextByBlock(this.image$)
					.previewCrossMonitoring();
				
			};
			
			this.getImageUrl = function () {
				return this.getUrl(this.getElement());
			};
			
			this.getUrl = function(element$) {
				var url = '';
				if ( element$.attr('href') !== undefined ) {
					url = element$.attr('href');
				} else {
					if ( element$.data('href') !== undefined )
						url = element$.data('href');
				}
				return url;
			}
			
			this.setParametersToImage = function () {
				var that = this;
				var wParameters = this.getWindowParameters();
				var block = {};
				
				var block = {} ;
				var windowRatio = that.getWindowRatio();
				
				if ( this.isAlbumRatio(wParameters) ) {
					if ( windowRatio > that.ratioWidth ) {
						block = {
							'width'  : parseInt(((wParameters.height * 80) / 100) * that.ratioWidth),
							'height' : parseInt((wParameters.height * 80) / 100) - that.preview$.height()
						};
					} else if (that.ratioWidth > 1) {
						block = {
							'width'  : parseInt((wParameters.width * 80) / 100),
							'height' : parseInt((((wParameters.width * 80) / 100) * that.ratioHeight) - that.preview$.height())
						};
					} else {
						block = {
							'width'  : parseInt(((wParameters.height * 80) / 100) * that.ratioWidth),
							'height' : parseInt((wParameters.height * 80) / 100) - that.preview$.height()
						};
					}
				} else {
					if ( windowRatio < that.ratioWidth ) {
						block = {
							'width'  : parseInt((wParameters.width * 80) / 100),
							'height' : parseInt((((wParameters.width * 80) / 100) * that.ratioHeight) - that.preview$.height())
						};
					} else if (that.ratioHeight > 1) {
						block = {
							'width'  : parseInt(((wParameters.height * 80) / 100) * that.ratioWidth),
							'height' : parseInt(((wParameters.height * 80) / 100) - that.preview$.height())
						};
					} else {
						block = {
							'width'  : parseInt((wParameters.width * 80) / 100),
							'height' : parseInt((((wParameters.width * 80) / 100) * that.ratioHeight) - that.preview$.height())
						};
					}
				}
				
				this.image$.css(block);
				var block = {
					'height'     : this.image$.height(),
					'width'      : this.image$.width(),
					'halfWidth'  : this.image$.width() / 2,
					'halfHeight' : this.image$.height() / 2,
				};
				
				this.image$.css({
					'left'  : wParameters.halfWidth - block.halfWidth,
					'top'   : wParameters.halfHeight - block.halfHeight
				});
				
				return this;
			};
			
			this.setParametersToPreviewBlock = function () {
				var wParameters = this.getWindowParameters();
				if ( wParameters.width < 400 || wParameters.height < 400 ) {
					this.preview$.css({'display'  : 'none'});
				} else {
					this.preview$.css({'display'  : 'block'});
					var width80Percent = this.getWindow80PercentWidth();
					var previewWidth   = this.getPreviewContentWidth();
					
					this.preview$.css({
						'width' : (previewWidth >= width80Percent ) ? width80Percent : previewWidth
					});

					var block = {
						'height'     : this.preview$.height(),
						'width'      : this.preview$.width(),
						'halfWidth'  : this.preview$.width() / 2,
						'halfHeight' : this.preview$.height() / 2,
					};

					this.preview$.css({
						'left'  : wParameters.halfWidth - block.halfWidth
					});
				}
				return this;
			};
			
			this.getWindow80PercentWidth = function() {
				return (this.getWindowParameters().width * 80) / 100;
			};
			
			this.getPreviewContentWidth = function () {
				var previewWidth = 0;
				this.preImages$.children().each(function(){
					previewWidth += $(this).width() + 5;
				});
				return previewWidth;
			};
			
			this.rightCross = function () {
				var image$ = this.current$;
				var imageLeft   = image$.offset().left;
				var previewLeft = this.preview$.position().left;
				if ( imageLeft - 10 <= previewLeft ) {
					var preImagesPosLeft = this.preImages$.position().left;
					var imagePosLeft = this.preImages$.position().left;
					var preImagesLeft = preImagesPosLeft  + ( imagePosLeft * -1 ) ;
					this.preImages$.animate({ left: preImagesLeft }, 150);
				}
				return this;
			};
			
			this.leftCross = function () {
				var image$ = this.current$;
				var imageWidth = image$.width() + 5;
				var imageRight = (image$.offset().left + imageWidth ) - this.preview$.offset().left;
				var previewWidth = this.preview$.width();
				if ( imageRight + 10 >= previewWidth ) {
					var left = this.preImages$.position().left;
					if (  this.imagesList.currentElement !== this.imagesList.imagesList.length - 1  ) {
						left = left - imageWidth;
					} else if ( imageRight > previewWidth ) {
						left = left - ( imageRight - previewWidth );
					}
					this.preImages$.animate({ left: left }, 150);
				}
				return this;
			};
			
			this.previewCrossMonitoring = function () {
				this.leftCross()
					.rightCross();
			
				return this;
			};
			
			this.isAlbumRatio = function(block) {
				return ((block.height / block.width) <= 0.8);
			};
			
			this.getWindowRatio = function() {
				return $(window).width() / $(window).height();
			};
				
			this.stop = function(){
				this.container$.fadeOut('fast', function(){
					$(this).remove();
				});
			};
			
			this.prev = function(){
				this.changeImage( this.imagesList.prev() );
			};
			
			this.setParametersToPrevByBlock = function (block$) {
				var wParameters = this.getWindowParameters();
				var width = block$.offset().left - 10;
				var backPos = parseInt((wParameters.width * 10) / 100);
				if (width <= backPos+20)
					backPos = '100%';
				else {
					backPos+='px';
				}
				var css = {
					'width'  : (width>20)?width:20,
					'height' : wParameters.height,
					'background-position': backPos + ' center'
				};
				
				this.prev$.css(css);
				
				return this;
			};
			
			this.next = function(){
				this.changeImage( this.imagesList.next() );
			};
			
			this.setParametersToNextByBlock = function (block$) {
				var wParameters = this.getWindowParameters();
				var backPos = 'left';
				var width = wParameters.width - (block$.offset().left + block$.width()) - 10;
				if ( width > 20 ) {
					var backPos = parseInt(width - (wParameters.width * 10) / 100);
					if (width <= backPos+20 || backPos<0){
						backPos = '0%';
					} else {
						backPos+='px';
					}
				}
				
				var css = {
					'width'  : (width>20)?width:20,
					'height' : wParameters.height,
					'background-position': backPos + ' center'
				};
				this.next$.css(css);
				
				return this;
			};
			
			this.getWindowParameters = function () {
				return {
					'height'     : $(window).height(),
					'width'      : $(window).width(),
					'halfWidth'  : $(window).width() / 2,
					'halfHeight' : $(window).height() / 2,
				};
			};
			
		};
		
		var images = function(imagesList) {
			this.imagesList = []||imagesList;
			this.currentElement = 0;
			
			this.get = function(index){
				return this.setCurrent(index).imagesList[index].clone();
			};
			
			this.setCurrent = function(index){
				this.currentElement = index;
				return this;
			};
			
			this.add = function(img$){
				this.imagesList.push(img$);
				return this;
			};
			
			this.current = function(){
				return this.imagesList[this.currentElement];
			};
			
			this.next = function(){
				return ( this.currentElement + 1 >= this.imagesList.length ) ? this.first() : this.get(this.currentElement + 1);
			}
			
			this.prev = function(){
				return ( this.currentElement - 1 < 0 ) ? this.last() : this.get(this.currentElement - 1);
			}
			
			this.first = function(){
				return this.get(0);
			};
			
			this.last = function(){
				return this.get(this.imagesList.length-1);;
			};
		};
		
		var gelleryShell   = new gallery(settings);
		var galleryImages = new images();
		$(this).each(function(){
			galleryImages.add( $('<img>').attr('src', (new gallery(settings)).getUrl($(this)) ) );
		});
		
		gelleryShell.init($(this), galleryImages);
		
	};
})(jQuery);
 
