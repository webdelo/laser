//Важно! В случае сортировки объектов трансформеров, нужно снова трансформировать эти объекты.
//	Пример:
//	$( ".sortable" ).sortable({
//		update: function (event, ui) {
//			$('.transformer').transformer()
//		}
//	}).disableSelection();
//

(function($){
	$.fn.transformer = function (settings) {
		var settings = $.extend({
			'class'        : 'transformedAdditionalClass',
			'event'        : 'click',
			'editMode'     :true,
			'enable'       :true,
			'defaultValue' : 'не указан',
			'hoverClass'   : 'hoverUnderLine',
			'eventStart'   : function(){}
		},settings||{});
		var that  = this;
		
		var transformator = function () {
			
			this.setObject = function (object$) {
				this.object$ = object$;
				return this;
			};
			
			this.getObject = function () {
				return this.object$;
			};
			
			this.setSettings = function (settings) {
				this.settings = settings;
				return this;
			};
			
			this.getSettings = function () {
				return this.settings;
			};
			
			this.transformToSpan = function () {
				var that = this;
				if (this.settings.enable) {
					this.getObject()
						.before(this.getTransformed())
						.one('blur',function (event) {
							$(this).transformer(that.settings);
						}).hide();
				} else {
					this.getObject()
						.one('blur',function (event) {
							$(this).transformer(that.settings);
						}).show();
				}
				return this;
			};
			
			this.transformToField = function () {
				if ( this.settings.editMode ) {
					var that = this;
					var marginRight = 0;
					if (this.settings.event!=='click') {
						this.getTransformed().click(function(e){
							e.stopPropagation();
						});
					};
					
					this.getTransformed().addClass(this.settings.hoverClass).on(this.settings.event, function(e){
						if ( that.getObject().is('textarea') ) 
							that.getObject().css({'height' : $(this).height()});
						if ( that.getObject().is('select') || $(this).width() < 10 ) 
							marginRight = 20;
						if ( that.getObject().css('width') == 'undefined' )
							this.getObject().css({'width' : $(this).width() - 2 + marginRight });

						$(this).hide().remove();

						that.getObject()
							.show()
							.val( that.getObject().val() )
							.focus();
					
						if ($.isFunction(that.settings.eventStart))
							that.settings.eventStart(that.getObject());

						e.stopPropagation();
					});
				}
				
				return this;
			};
			
			this.getTransformed = function() {
				if (this.transformed$ === undefined || this.transformed$.length === 0 ) {
					this.removeOldTransformed().setTransformed();
				}
				return this.transformed$;
			};
			
			this.removeOldTransformed = function () {
				var prev$ = this.getObject().prev();
				if ( prev$.hasClass(this.settings.class) )
					prev$.remove();
				return this;
			};
			
			this.setTransformed = function() {
				this.transformed$ = $('<span></span>').attr({
					'class' : 'transformed',
					'title' : this.getTitle(),
					'id'    : this.getName()
				}).addClass(this.settings.class).html(this.getValue());
				return this;
			};
			
			this.setName = function () {
				this.name = this.getObject().attr('name') + this.getObject().index( '[name='+this.getObject().attr('name')+']' );
				return this;
			};
	
			this.getName = function () {
				if ( this.name === undefined ) {
					this.setName();
				}
				return this.name ;
			};
			
			this.setTitle = function () {
				this.title = this.settings.editMode ? this.getObject().attr('title') || 'Клик для редактирования'  : '';
				return this;
			};
	
			this.getTitle = function () {
				if ( this.title === undefined ) {
					this.setTitle();
				}
				return this.title ;
			};
			
			this.getValue = function () {
				return this.getRealValue() || this.getDefaultValue();
			}
			
			this.getDefaultValue = function () {
				var defaultValue = this.getObject().data('default') ? this.getObject().data('default') : this.settings.defaultValue;
				return $('<span>').addClass('defaultValue').text(defaultValue);
			}
	
			this.getRealValue = function () {
				var value  = this.getObject().find('option:selected').text() || this.getObject().val() ;
				return this.getObject().is('textarea') ? value.replace(/\n/g, "<br />") : value ;
			};
			
			this.init = function(object$, settings) {
				this.setObject(object$)
					.setSettings(settings)
					.transformToSpan()
					.transformToField();
			};
			
		};
		
		$(this).each(function(){
			(new transformator).init($(this), settings);
		});
		
		return this;
	};
})(jQuery);
