$(function () {
	$('.filterGroupActionByCategory').change(function(){
		location.href = '?form_in_use=true&categoryId='+$(this).val()+'&groupAction=setParameters';
	});
	
	
	checkUncheckAll();
	shiftKeyInit();
	onElementSelected();
	onActionSelected();
	onOkPressed();
	formInit();
});

var checkUncheckAll = function(){
	$('.check_all').click(function(){
		$(this).toggleClass('on');
		if ($(this).hasClass('on')) {
			$(this).find('span').html('Снять все выделения');
			$('.table_edit input[type=checkbox]').attr('checked', true);
			$('.groupActionSelect').show();
		} else {
			$(this).find('span').html('Выделить все');
			$('.table_edit input[type=checkbox]').attr('checked', false);
			$('.groupActionSelect').hide();
			$('.groupAction').hide();
		}
		if(getAction() === 'groupRemove'){
			groupReset();
			createGroupBloks();
		}
	});
};

var shiftKeyInit = function(){
	var lastChecked = null;
	var $chkboxes = $('.groupElements');
	$chkboxes.click(function(e) {
		if(!lastChecked) {
			lastChecked = this;
			return;
		}
		if(e.shiftKey) {
			var start = $chkboxes.index(this);
			var end = $chkboxes.index(lastChecked);
			$chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).attr('checked', lastChecked.checked);
		}
		lastChecked = this;
		if(getAction() === 'groupRemove'){
			groupReset();
			createGroupBloks();
		}
	});
};

var onElementSelected = function(){
	$('.groupElements').change( function() {
		if(getCheckedElements().length){
			$('.groupActionSelect').show();
		}
		else{
			$('.groupActionSelect').hide();
			$('.groupAction').hide();
		}
		if(getAction() === 'groupRemove'){
			groupReset();
			createGroupBloks();
		}
	});
};

var onActionSelected = function(){
	$('.groupActionSelect').change( function() {
		groupReset();
		var action = getAction();
		if(action){
			$('.groupAction').hide();
			$('.' + action).show();
			if(action === 'groupRemove'){
				groupReset();
				createGroupBloks();
			}
		}
		else
			$('.groupAction').hide();
	});
};

var groupReset = function(){
	$('.group').remove();
};

var getAction = function(){
	return $('.groupActionSelect option:selected').val();
};

var onOkPressed = function(){
	$('.ok').live('click', okPressedFunc); 
};

var okPressedFunc = function(){
		var action = getAction();
		if( saveValidate(action) ){
			createGroupBloks();
		}
};

var saveValidate = function(action){
	if (action === 'parametersId')
		return true;
	return ($('#' + action + ' option:selected').val()   ||   action === 'groupRemove')   &&   getCheckedElements().length;
};

var getCheckedElements = function(){
	return $('.groupElements:checked');
};

var createGroupBloks = function(){
	getCheckedElements().each(function(key, value){
		$('#groupArray').append('<input type="hidden" class="group" name=group['+key+'] value="'+$(this).parent().parent().attr('data-id')+'">');
	});
	if(getAction() !== 'groupRemove')
		$('#groupArray').append('<input type="hidden" name="' + getAction() + '" value="' + $('#' + getAction()).val() + '">');
};

var formInit = function(){
	window.dialog = new form;
	window.dialog.setSettings({
		'form'    : '.groupArray',
		'onBeforeSend' : function(that){
			okPressedFunc();
		}
	}).setCallback(function (response){
		if (typeof(response) === "number") {
			if (window.dialog.getObject().data('post-action')){
				location.href = window.dialog.getObject().data('post-action');
			}
			location.reload();
		}
	}).init();
};