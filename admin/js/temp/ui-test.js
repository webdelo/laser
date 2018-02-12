function postRemoveFromDetails (element$) {
	location.href = element$.data('post-action') ;
}

function reloadPage () {
	location.reload();
}

function postRemoveArticle (element$, response) {
	var element = $('#id'+response);
	if ( element.length > 0 )
		element.fadeOut('fast');

//	if ( element$.length > 0 )
//		element$.parent().parent().fadeOut('fast', function () {
//			$(this).remove();
//		});
}

function postRemoveGroupArticle (element$) {
	var data = element$.data('data');
	$(data).each(function () {
		$('tr[data-id='+$(this).val()+']').fadeOut('fast', function () {
			$(this).remove();
		});
	});
}

function postRemoveCategory (element$) {
	element$.parent().parent().fadeOut('fast', function () {
		$(this).remove();
	});
}

function postRemoveGroupCategories (element$) {
	var data = element$.data('data');
	$(data).each(function () {
		$('tr[data-id='+$(this).val()+']').fadeOut('fast', function () {
			$(this).remove();
		});
	});
}

$(function() {
	var objectAdd = new form;
	objectAdd.setSettings({
			'form'    : '.formAdd',
			'active'  : '.active',
			'message' : '.message'
		})
		.setCallback(function (response) {
			if (typeof response == "number")
				location.href = $(objectAdd.settings.form).data('post-action')+response+'/';
		})
		.init();

	var objectAddCategory = new form;
	objectAddCategory.setSettings({
			'form'    : '.formAddCategory',
			'active'  : '.active',
			'message' : '.message'
		})
		.setCallback(function (response) {
			if ( typeof response == "number" )
				location.href = $(objectAddCategory.settings.form).data('post-action');
		})
		.init();

	var objectEdit = new form;
	objectEdit.setSettings({
			'form'    : '.formEdit',
			'active'  : '.active',
			'message' : '.message',
			'onBeforeSend' : function () {objectEdit.loader.start();}
		})
		.setCallback(function (response) {
			objectEdit.loader.stop();
		})
		.init();

	var removeButton = new buttons();
	removeButton.init();

	var deleteOrder = new buttons();
	deleteOrder
		.setSettings({
			'element' : '.deleteOrder'
		})
		.setCallback(function (response) {
			if ( typeof response === 'number' ) {
				$('.incompleteOrders').htmlFromServer();
			} else {
				alert(response);
			}
 		})
		.init();

	$(".dblclick").live('dblclick', function (event) {
		$(this).addClass('highlight');
		location.href = $(this).data('url');
	});

	$('.buttons').button();

	$('input.date').live('click', function() {
		$(this).datepicker({showOn:'focus', dateFormat : 'dd-mm-yy'}).focus();
	});
});