//Важно! В случае сортировки объектов трансформеров, нужно снова трансформировать эти объекты.
//	Пример:
//	$( ".sortable" ).sortable({
//		update: function (event, ui) {
//			$('.transformer').transformer()
//		}
//	}).disableSelection();
//

(function($){
	$.fn.formTransformer = function (settings) {
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
			
			this.init = function(object$, settings) {
				this.setObject(object$)
					.setSettings(settings)
					.initTransformerToFields();
			};
			
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
			
			this.initTransformerToFields = function () {
				var that = this;
				this.getObject().click(function(e){
					that.fieldsToEdit();
					e.stopPropagation();
					return false;
				});
				$('body').click(function(){
					if (that.getFields().filter(':visible').length > 0 )
						that.fieldsToSpan();
				});
				this.fieldsToSpan();
			};	
			
			this.fieldsToSpan = function() {
				this.getFields().transformer({'editMode':false});
			};
			
			this.fieldsToEdit = function() {
				var test = this.getFields();
				this.getFields().transformer({'enable':false});
			};
			
			this.getFields = function () {
				return this.getObject().find('input,select');
			}
		};
		
		$(this).each(function(){
			(new transformator).init($(this), settings);
		});
		
		return this;
	};
})(jQuery);
