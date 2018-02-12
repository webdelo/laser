(function($){
	$.fn.loaderMini = function (settings) {
		
		var loaderMini = function (settings) {
			this.settings = $.extend({
				'destination$' : '.loaderTarget',
				'image'		   : '/admin/images/loaders/ajax-loader.gif',
				'blockId'      : 'imageMini',
				'titleClass'   : 'loaderMiniTitle',
				'titleText'    : 'Loading'
			}, settings || {});

			this.img$   = $('<div id="'+this.settings.blockId+'" class="hide"><img src="'+this.settings.image+'"/></div>');
			this.title$ = $('<div class="'+this.settings.titleClass+'">'+this.settings.titleText+'</span>');

			this.init = function () {
				this.img$
					.append(this.title$);
					
				$('body').append(
						this.img$
					);
						
				return this.setCss();
			}

			this.setCss = function () {
				var position = this.getLoaderPosition();
				
				this.img$.css({
					'left'        : position.left,
					'top'         : position.top
				});

				return this;
			}

			this.getLoaderPosition = function () {
				var dest$   = this.settings.destination$;
				var offset  = dest$.offset();
				var width   = this.img$.width()/2;
				var height  = this.img$.height()/2;
				var dHeight = dest$.height()/2;
				var top     = (dHeight > height) ? dHeight - height : 0;
				
				return {
					'left' : offset.left + (dest$.width()/2 - width),
					'top'  : offset.top + top
				}
			}

			this.start = function () {
				this.settings.destination$.fadeTo('fast', '0.1',$.proxy(function () { 
					this.img$.fadeIn();
				}, this) );
				
				return this;
			}

			this.title = function (title) {
				this.title$.text(title);
				return this;
			}

			this.stop = function () {
				
				this.settings.destination$.fadeTo('fast', '1', $.proxy(function () {
					this.img$.fadeOut('fast', function () {$(this).remove()});
				}, this) );
				return this;
			}
		}
		
		if (typeof settings == "string") {
			if ( settings == 'stop' ) window.loaderMiniObj.stop();
		} else {
			window.loaderMiniObj = new loaderMini($.extend({'destination$': $(this)}, settings || {}));
			window.loaderMiniObj.init().start();
		}
		
		
		return this;
	}
})(jQuery);