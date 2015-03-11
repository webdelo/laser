$(function(){
	var dropLoader = new loaderBlock;
	dropLoader.init($('.parameterBlocks'));
	
	var parametersSortable = function () {
		this.initSortable = function () {
			var that = this;
			
			$( "#partsSortable" ).sortable({
				start: that.showDeleteParameterPartButton,
				stop: that.showNewParameterPartButton,
				items: ".parametersParts:not(.notSortable)",
				delay: 150,
				containment: "body",
				placeholder: "parameterPartPlaceHolder",
				update: function (event, ui) {
					var data = '';
					var elements = $( "#partsSortable" ).children('.parametersParts:not(.notSortable)');
					elements.each(function(i){
						i = i + 1;
						data += '&data['+$(this).data('id')+']='+i;
					});
					$.ajax({
						error: function () { alert('Пожалуйста обратитесь к разработчикам!'); },
						url: $( "#partsSortable" ).data('action'),
						type: 'post',
						data: data,
						dataType: 'json'
					});
				}
			}).disableSelection();

			$( ".parametersSortable" ).sortable({
				items: "table:not(.notSortable)",
				connectWith: ".parametersSortable",
				delay: 150,
				placeholder: "parameterPlaceHolder",
				update: function (event, ui) {
					var data = '';
					var elements = $( ".parametersSortable" ).children('table:not(.notSortable)');
					elements.each(function(i){
						i = i + 1;
						data += '&data['+$(this).data('id')+']='+i;
					});
					$.ajax({
						error: function () { alert('Пожалуйста обратитесь к разработчикам!'); },
						url: $( ".parametersSortable" ).data('action'),
						type: 'post',
						data: data,
						dataType: 'json'
					});
				},
				start : that.showDeleteParameterValueButton,
				stop  : that.showNewParameterValueButton
			}).disableSelection();
		};
		
		this.destroySortable = function() {
			$( ".parametersSortable" ).sortable('destroy');
			$( "#partsSortable" ).sortable('destroy');
		};
		
		this.showNewParameterValueButton = function () {
			$('.addParameterValue', $(this)).css('display', 'inline-block');
			$('.deleteParameterValue', $(this)).css('display', 'none');
		};
		this.showDeleteParameterValueButton = function () {
			$('.addParameterValue', $(this)).css('display', 'none');
			$('.deleteParameterValue', $(this)).css('display', 'inline-block');
		};
		this.showNewParameterPartButton = function (that$) {
			$('.parametersParts.addingMode').css('display', 'inline-block');
			$('.parametersParts.removeMode').css('display', 'none');
		};
		this.showDeleteParameterPartButton = function (that$) {
			$('.parametersParts.addingMode').css('display', 'none');
			$('.parametersParts.removeMode').css('display', 'inline-block');
		};
	};
	
	(new parametersSortable).initSortable();
	
	$('.deleteParameterValue').droppable({
		accept: 'table:not(.notSortable)',
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
					url: '/admin/parameters/deleteParameterValue/'+element$.data('id')+'/',
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

	var deleteParameterPart = function(event, ui) {
		var parameterId = ui.draggable.data('id');
		if ( confirm('Объект будет удален, продолжить?') ) {
			$("#partsSortable").sortable("cancel");
			ui.draggable.hide().remove();
			$.ajax({
				error: function () { alert('Пожалуйста обратитесь к разработчикам!'); },
				url: '/admin/parameters/remove/'+parameterId+'/',
				type: 'post',
				dataType: 'json',
				success: function (response) {
					if ( typeof response == 'number' ) {
						$('.parameterBlocks').htmlFromServer({'loader':dropLoader});
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
	var overDeleteParameterPart = function(event, ui){
		 $(this).addClass('highlight');
		 ui.draggable.css('opacity', '0.5');
	};
	var outDeleteParameterPart = function (event, ui){ 
		$(this).removeClass('highlight');
		ui.draggable.css('opacity', '1');
	};

	$('.removeMode').droppable({
		over: overDeleteParameterPart,
		out: outDeleteParameterPart,
		accept: '.parametersParts',
		drop: deleteParameterPart
	});
	$('.main').droppable({
		over: overDeleteParameterPart,
		out: outDeleteParameterPart,
		accept: '.parametersParts',
		greedy: true,
		tolerance: 'intersect',
		drop: deleteParameterPart
	});
	
	$('.max_width').droppable({
		accept: '.parametersParts',
		greedy: true,
		drop: function(){}
	});
	
	$('.parametersSortable').droppable({
		over: function () { $(this).parents('.parametersParts').addClass('highlight'); },
		out : function () { $(this).parents('.parametersParts').removeClass('highlight'); },
		accept: '.parametersSortable table',
		drop: function (event, ui) {
			var id = ui.draggable.data('id');
			var oldParameterId = ui.draggable.parents('.parametersParts').data('id');
			var parameterId = $(this).parents('.parametersParts').data('id');
			if ( oldParameterId != parameterId ) {
				$.ajax({
					error: function () { alert('Пожалуйста обратитесь к разработчикам!'); },
					url: '/admin/parameters/editParameterValue/',
					type: 'post',
					data: {
						'id' : id,
						'parameterId': parameterId
					},
					dataType: 'json',
					success: function (response) {
						if ( typeof response == 'number' ) {
							$('.parameterBlocks').htmlFromServer({'loader':dropLoader});
						} else {
							alert('Error');
						}
					}
				});
			}
			$(this).parents('.parametersParts').removeClass('highlight');
		}
	});	
	
	$('.newParameterPart,.transformer').transformer();
	
	$('.editParameterPartChooseMode').transformer({'class':'actions'});
	
	$('.editParameterValue').transformer({'editMode':false})
							.on('mousedown', function(e){
								e.stopPropagation();
							});
							
	$('input[type=text]', $(this)).click(function(e){ e.stopPropagation(); });
	
	
	var showActions = function (event) {
		$(this).find('.actions').stop().css('display', 'inline-block').fadeTo('200', 1);
	};
	var hideActions = function(event) {
		if ( $(this).find('.addParameterValue input').is(':hidden') )
			$(this).find('.actions').stop().css('display', 'inline-block').fadeTo('200', 0);
	};
	$('.parametersParts').hover(
		showActions,
		hideActions
	);
	if ( 'ontouchstart' in document )
		$('.parametersParts').tap(function(){
			showActions();
		});
	
	
	var editMode = function() {
		this.init = function () {
			
			this.editModeEnable()
				.editModeDisable();
			
		};
		
		this.editModeEnable = function () {
			var that = this;
			$('.editModeToParameterValuesEnable').click(function() {
				(new parametersSortable).destroySortable();
				
				$(this).parents('.parametersParts').addClass('highlight').addClass('editMode');
				$('input[type=checkbox], input[type=radio]', $(this).parents('.parametersParts')).attr('disabled', 'true').hide();
				$('input[type=text]:first', $(this).parents('.parametersParts')).focus();
				$('.editParameterValue', $(this).parents('.parametersParts')).transformer({'enable': false});
				that.showButtonDisableEditMode($(this).parents('.parametersParts'));
				return false;
			});
			return this;
		};
		
		this.editModeDisable = function () {
			var that = this;
			$('.editModeToParameterValuesDisable').click(function() {
				(new parametersSortable).initSortable();
				
				$(this).parents('.parametersParts').removeClass('highlight').removeClass('editMode');
				
				$('input[type=checkbox], input[type=radio]', $(this).parents('.parametersParts')).removeAttr('disabled').show();
				$('.editParameterValue', $(this).parents('.parametersParts')).transformer({'editMode': false});
				that.showButtonEnableEditMode($(this).parents('.parametersParts'));
				return false;
			});
			return this;
		};
		
		this.showButtonEnableEditMode = function (context$) {
			$('.editModeToParameterValuesEnable', context$).show();
			$('.editModeToParameterValuesDisable', context$).hide();
		};
		
		this.showButtonDisableEditMode = function (context$) {
			$('.editModeToParameterValuesEnable', context$).hide();
			$('.editModeToParameterValuesDisable', context$).show();
		};
	};	
	
	var edit = new editMode();
	edit.init();
});