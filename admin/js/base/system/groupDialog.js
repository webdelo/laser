$(function() {
	window.groupDialog = new groupDialog (".groupDialog");
	groupDialog.selectable();
	
	window.dialog = new form;
	window.dialog.setSettings({
			'form'    : '.formDialog',
			'active'  : '.active',
			'message' : '.message'
		})
		.setCallback(function (response) {
			if (typeof(response) == "number")
				location.reload();
		}).init();
	$('.closeForm').live("click", window.groupDialog.closeAction);
});

var groupDialog = function (element) {
	this.element = element;
	this.filter = 'tr:not(.header)';
	this.dialog$ = $('<div class="detailActions">');
	this.close$ = $('<span class="close">X</span>');
	this.data = [];
	
	this.selectable = function () {
		$( element ).selectable({
			filter: this.filter,
			cancel: 'a',
			start: this.start,
			stop: this.stop,
			distance: 1
		});
	}
	
	this.start = function () {
		window.dialog.resetActive();
		window.dialog.errors.reset();
		window
			.groupDialog
			.dialog$
			.stop(true)
			.fadeTo('fast', 1);
	}
	
	this.stop = function (event, ui) {
		window.groupDialog.dialog(event, ui);
	}
	this.dialog = function (event, ui) {
		this.resetDataInForm()
			.setDataInForm()
			.dialog$
			.mouseenter(this.dialogMouseEnter)
			.html(
				$('.'+this.dialog$.attr('class')+'Block').html()
			)
			.prepend(this.close$.click(this.closeAction))
			.css(this.getBlockCss(event))
			.appendTo('body')
			.fadeTo('fast', 1)
			.delay(1000)
			.fadeOut('fast', window.groupDialog.afterClose);
		
		window.dialog.setActive(this.dialog$.find('.formDialogSubmit'));
	}
	
	this.resetDataInForm = function () {
		$('form.form')
			.find('input[name*=group]')
				.each(function () {
					$(this).remove();
				});
		return this;
	}
	
	this.setDataInForm = function () {
		$('.ui-selected').each(function (i){
			var input$ = $('<input type="hidden" name="group['+i+']" value="'+$(this).data('id')+'">');
			$('form'+window.dialog.settings.form).append(input$);
		});
		
		return this;
	}
	
	this.dialogMouseEnter = function (event, element) {
		window
			.groupDialog
			.dialog$
			.stop(true)
			.fadeTo('fast', 1)
			.css({'height':'auto'});
		
	}
	
	this.getBlockCss = function (event) {
		var pos = (window.outerWidth < event.clientX + 202) ? event.clientX - 202 : event.clientX ;
		
		return {
			'top'    : event.clientY + 2,
			'left'   : pos + 2,
			'height' : '10px'
		}
	}
	
	this.closeAction = function (event, element) {
		window.dialog.errors.reset();
		window.groupDialog.resetSelected();
		window.groupDialog.dialog$.fadeOut('fast', window.groupDialog.afterClose);
		return false;
	}
	
	this.resetSelected = function () {
		$('.ui-selected').removeClass('ui-selected');
		$('input[name^=group]').remove();
	}
	
	this.afterClose = function () {
		window.dialog.resetActive();
		window.groupDialog.resetSelected();
		$(this).remove();
	}
}