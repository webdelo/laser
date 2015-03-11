$(function(){
	var dropLoader = new loaderBlock;
	dropLoader.init($('.propertiesBlocks'));
	
	$('.property').hover(
		function(){
			$(this).find('.measurementsCategories').stop().fadeTo('200', 1);
			if ( $(this).find('.editProperty').is(':visible') )
				$(this).addClass('hover');
		}
		, function(){
			$(this).removeClass('hover').find('.measurementsCategories').stop().fadeTo('200', 0);
		}
	);
	
	var propertiesSortable = function () {
		this.initSortable = function () {
			var that = this;
			
			$( "#partsPropertiesSortable" ).sortable({
				start: that.showDeletePropertyPartButton,
				stop: that.showNewPropertyPartButton,
				items: ".propertiesParts:not(.notSortable)",
				delay: 150,
				containment: "body",
				placeholder: "propertyPartPlaceHolder",
				update: function (event, ui) {
					var data = '';
					var elements = $( "#partsPropertiesSortable" ).children('.propertiesParts:not(.notSortable)');
					elements.each(function(i){
						i = i + 1;
						data += '&data['+$(this).data('id')+']='+i;
					});
					$.ajax({
						error: function () { alert('Пожалуйста обратитесь к разработчикам!'); },
						url: $( "#partsPropertiesSortable" ).data('action'),
						type: 'post',
						data: data,
						dataType: 'json'
					});
				}
			}).disableSelection();

			$( ".propertiesSortable" ).sortable({
				items: "div.property:not(.notSortable)",
				connectWith: ".propertiesSortable",
				delay: 150,
				placeholder: "propertyPlaceHolder",
				update: function (event, ui) {
					var data = 'objectId='+$('.objectId').val();
					var elements = $( ".propertiesSortable" ).children('div.property:not(.notSortable)');
					elements.each(function(i){
						i = i + 1;
						data += '&data['+$(this).data('id')+']='+i;
					});
					$.ajax({
						error: function () { alert('Пожалуйста обратитесь к разработчикам!'); },
						url: $( ".propertiesSortable" ).data('action'),
						type: 'post',
						data: data,
						dataType: 'json'
					});
				},
				start : that.showDeletePropertyValueButton,
				stop  : that.showNewPropertyValueButton
			}).disableSelection();
		};
		
		this.destroySortable = function() {
			$( ".propertiesSortable" ).sortable('destroy');
			$( "#partsPropertiesSortable" ).sortable('destroy');
		};
		
		this.showNewPropertyValueButton = function (event, ui) {
			$('.addPropertyValue', $(this)).css('display', 'inline-block');
			$('.deletePropertyValue', $(this)).css('display', 'none');
			
			ui.item.removeClass('sortItem');
		};
		this.showDeletePropertyValueButton = function (event, ui) {
			$('.addPropertyValue', $(this)).css('display', 'none');
			$('.deletePropertyValue', $(this)).css('display', 'inline-block');
			
			ui.item.addClass('sortItem');
		};
		this.showNewPropertyPartButton = function (that$) {
			$('.propertiesParts.addingMode').css('display', 'inline-block');
			$('.propertiesParts.removeMode').css('display', 'none');
		};
		this.showDeletePropertyPartButton = function (that$) {
			$('.propertiesParts.addingMode').css('display', 'none');
			$('.propertiesParts.removeMode').css('display', 'inline-block');
		};
	};
	
	(new propertiesSortable).initSortable();
	
	$('.deletePropertyValue').droppable({
		accept: 'div.property',
		over: function (event, ui) {
			ui.draggable.css('opacity', '0.5');
			var element$ = $(this).addClass('highlight');
			var textBlock$ = $('td:last', element$);
			textBlock$.data('start', textBlock$.text())
					  .text($(this).data('confirm'));
		},
		out: function (event, ui) {
			ui.draggable.css('opacity', '1');
			var element$ = $(this).removeClass('highlight');
			var textBlock$ = $('td:last', element$);
			textBlock$.text(textBlock$.data('start'));
		},
		drop: function (event, ui) {
			var element$ = ui.draggable;
			if ( confirm('Удалить параметр?') ) {
				element$.hide();
				$.ajax({
					error: function () { alert('Пожалуйста обратитесь к разработчикам!'); },
					url: '/admin/properties/deletePropertyValue/'+element$.data('id')+'/',
					type: 'post',
					dataType: 'json',
					success: function (response) {
						if ( typeof response == 'number' ) {
							element$.fadeOut('fast', function(){ $(this).remove(); });
						} else {
							element$.css('display', 'inline-block');
						}
					}
				});
			} else {
				element$.css({ 
					'opacity': '1',
					'display': 'inline-block'
				});
				var element$ = $(this).removeClass('highlight');
				var textBlock$ = $('td:last', element$);
				textBlock$.text(textBlock$.data('start'));
			}
		}
	});

	var deletePropertyPart = function(event, ui) {
		var propertyId = ui.draggable.data('id');
		if ( confirm('Объект будет удален, продолжить?') ) {
			$("#partsPropertiesSortable").sortable("cancel");
			ui.draggable.hide().remove();
			$.ajax({
				error: function () { alert('Пожалуйста обратитесь к разработчикам!'); },
				url: '/admin/properties/remove/'+propertyId+'/',
				type: 'post',
				dataType: 'json',
				success: function (response) {
					if ( typeof response == 'number' ) {
						$('.propertiesBlocks').htmlFromServer({'loader':dropLoader});
					} else {
						alert('Error');
					}
					$(this).removeClass('highlight');
				}
			});
			$(this).removeClass('highlight');
			ui.draggable.css('opacity', '1');
		} else {
			$(this).removeClass('highlight');
			ui.draggable.css('opacity', '1');
		}
	};
	var overDeletePropertyPart = function(event, ui){
		 $(this).addClass('highlight');
		 ui.draggable.css('opacity', '0.5');
	};
	var outDeletePropertyPart = function (event, ui){ 
		$(this).removeClass('highlight');
		ui.draggable.css('opacity', '1');
	};

	$('.removeMode').droppable({
		over: overDeletePropertyPart,
		out: outDeletePropertyPart,
		accept: '.propertiesParts',
		drop: deletePropertyPart
	});
	$('.main').droppable({
		over: overDeletePropertyPart,
		out: outDeletePropertyPart,
		accept: '.propertiesParts',
		greedy: true,
		tolerance: 'intersect',
		drop: deletePropertyPart
	});
	
	$('.max_width').droppable({
		accept: '.propertiesParts',
		greedy: true,
		drop: function(){}
	});
	
	$('.propertiesSortable').droppable({
		over: function () { $(this).parents('.propertiesParts').addClass('highlight'); },
		out : function () { $(this).parents('.propertiesParts').removeClass('highlight'); },
		drop: function (event, ui) {
			var id = ui.draggable.data('id');
			var oldPropertyId = ui.draggable.parents('.propertiesParts').data('id');
			var propertyId = $(this).parents('.propertiesParts').data('id');
			if ( oldPropertyId != propertyId ) {
				$.ajax({
					error: function () { alert('Пожалуйста обратитесь к разработчикам!'); },
					url: '/admin/properties/editPropertyValue/',
					type: 'post',
					data: {
						'id' : id,
						'propertyId': propertyId
					},
					dataType: 'json',
					success: function (response) {
						if ( typeof response == 'number' ) {
							$('.propertiesBlocks').htmlFromServer({'loader':dropLoader});
						} else {
							alert('Error');
						}
					}
				});
			}
			$(this).parents('.propertiesParts').removeClass('highlight');
		}
	});	
	
	$('.newPropertyPart,.transformer').transformer();
	
	$('.editPropertyPartChooseMode').transformer({'class':'actions'});
	
	$('.editPropertyValue').on('mousedown', function(e){ e.stopPropagation(); });
							
	$('.editRelation,.measurements,.measurementsCategories').on('mousedown', function(e){ e.stopPropagation(); });

	
	
	var showActions = function (event) {
		$(this).find('.actions').stop().css('display', 'inline-block').fadeTo('200', 1);
	};
	var hideActions = function(event) {
		if ( $(this).find('.addPropertyValue input').is(':hidden') )
			$(this).find('.actions').stop().css('display', 'inline-block').fadeTo('200', 0);
	};
	$('.propertiesParts').hover(
		showActions,
		hideActions
	);
	if ( 'ontouchstart' in document )
		$('.propertiesParts').tap(function(){
			showActions();
		});
	
	
	var editMode = function() {
		this.init = function () {
			
			this.editModeEnable()
				.editModeDisable();
			
		};
		
		this.editModeEnable = function () {
			var that = this;
			$('.editModeToPropertyValuesEnable').click(function() {
				editRelation.editModeDisable();
				
				(new propertiesSortable).destroySortable();
				$(this).parents('.propertiesParts').addClass('highlight').addClass('editMode');
				$('.viewMode', $(this).parents('.propertiesParts')).toggle();
				$('.editProperty', $(this).parents('.propertiesParts')).toggle();
				
				that.showButtonDisableEditMode($(this).parents('.propertiesParts'));
				return false;
			});
			return this;
		};
		
		this.editModeDisable = function () {
			var that = this;
			$('.editModeToPropertyValuesDisable').click(function() {
				(new propertiesSortable).initSortable();
				$(this).parents('.propertiesParts').removeClass('highlight').removeClass('editMode');
				$('.viewMode', $(this).parents('.propertiesParts')).toggle();
				$('.editProperty', $(this).parents('.propertiesParts')).toggle();
				
				that.showButtonEnableEditMode($(this).parents('.propertiesParts'));
				return false;
			});
			return this;
		};
		
		this.showButtonEnableEditMode = function (context$) {
			$('.editModeToPropertyValuesEnable', context$).show();
			$('.editModeToPropertyValuesDisable', context$).hide();
		};
		
		this.showButtonDisableEditMode = function (context$) {
			$('.editModeToPropertyValuesEnable', context$).hide();
			$('.editModeToPropertyValuesDisable', context$).show();
		};
	};	
	var edit = new editMode();
	edit.init();
	
	
	var relationEditMode = function() {
		this.init = function () {
			
			this.editModeEnableMonitoring()
				.editModeDisableMonitoring();
			
		};
		
		
		
		this.editModeEnableMonitoring = function () {
			var that = this;
			$('div.viewMode').click(function(e){
				that.editModeEnable(e, this);
				return false;
			});
			return this;
		};
		
		this.editModeEnable = function(e, that) {
			this.showViewBlock().showEditBlock($(that));
			e.stopPropagation();
		};
		
		this.editModeDisableMonitoring = function () {
			var that = this;
			$('body').click(function() {
				that.editModeDisable();
			});
			return this;
		};
		
		this.editModeDisable = function () {
			if ($('.editPropertyRelation:visible').length > 0) {
				this.showViewBlock();
			}
		};
		
		this.showEditBlock = function(that$) {
			that$.hide();
			$('.editPropertyRelation', that$.parents('.property')).fadeIn().click(function(e){
				e.stopPropagation();
			}).find('input').focus(function(){
				$(this).val( $(this).val() );
			}).focus();
			return this;
		};
		
		this.showViewBlock = function() {
			$('.editPropertyRelation:visible').hide();
			$('.viewMode:hidden').fadeIn();
			return this;
		};
		
	};	
	var editRelation = new relationEditMode();
	editRelation.init();
});