var filesHandler = function()
{
    this.initFileControll = function()
    {
	var filesControl = new filesUpload();
	$.each(filesControl.handlers, function(name, method){
		method.call(filesControl);
	});

	return this;
    }

    this.initFile = function()
    {
	var fileControl = new fileClass();
	$.each(fileControl.handlers, function(name, method){
		method.call(fileControl);
	});

	return this;
    }

    this.InitFileAddForm = function()
    {
	var filesAdd = new form;
	filesAdd.setSettings({
		'form'    : '.filesAddForm',
		'active'  : '.active',
		'message' : '.message'
	})
	.setCallback(function (response) {
		if (typeof response == 'number') {
		    (new filesUpload).reloadForm();
		}
	})
	.init();

	return this;
    }

    this.InitFileEditForm = function()
    {
	var filesEdit = new form;
	filesEdit.setSettings({
		'form'    : '.formFileEdit',
		'active'  : '.active',
		'message' : '.message'
	})
	.setCallback(function (response) {
		(new filesHandler).updateFilesListBlock();
		$('.ui-dialog').fadeOut('fast', function () {
			$(this).remove();
		});
	})
	.init();

	return this;
    }

    this.InitFileRemoveForm = function()
    {
	var fileRemove = new buttons;
	fileRemove.setSettings({
		'element'    : '.removeFile'
	})
	.setCallback(function (response) {
		if (typeof response == "number"){
			(new filesHandler).updateFilesListBlock();
		}

	}).init = function (){
		this.loader.init();
		var that = this;
		$(this.settings.element).live('click', function (event) {
			event.stopPropagation();
			var access = true;
			that.setActive($(this));
			if ($(this).hasClass('confirm'))
				if (!confirm($(this).data('confirm')||'Do you sure?'))
					access = false;
			if (access) {
				that.element$ = $(this);
				that.start();
			}
			return false;
		});
		return this;
	}
	fileRemove.init();

	return this;
    }

	this.updateFilesListBlock = function()
	{
		(new filesUpload).getFilesListBlock( $('.objectId').val() );
	}

    this.InitPrimaryButton = function()
    {
	var setPrimary = new buttons;
	setPrimary.setSettings({
		'element'    : '.setPrimary'
	})
	.setCallback(function (response, element$) {
		if (typeof response == "number")
			element$.hide()
				.parent()
				.children('.resetPrimary')
				.css({ 'display' : 'block' });
	}).init = function (){
		this.loader.init();
		var that = this;
		$(this.settings.element).click(function (event) {
			event.stopPropagation();
			var access = true;
			that.setActive($(this));
			if ($(this).hasClass('confirm'))
				if (!confirm($(this).data('confirm')||'Do you sure?'))
					access = false;
			if (access) {
				that.element$ = $(this);
				that.start();
			}
			return false;
		});
		return this;
	}
	setPrimary.init();

	return this;
    }

    this.InitResetPrimaryButton = function()
    {
	var resetPrimary = new buttons;
	resetPrimary.setSettings({
		'element'    : '.resetPrimary'
	})
	.setCallback(function (response, element$) {
		if (typeof response == "number")
			element$.hide()
				.parent()
				.children('.setPrimary')
				.css({ 'display' : 'block' });
	}).init = function (){
		this.loader.init();
		var that = this;
		$(this.settings.element).click(function (event) {
			event.stopPropagation();
			var access = true;
			that.setActive($(this));
			if ($(this).hasClass('confirm'))
				if (!confirm($(this).data('confirm')||'Do you sure?'))
					access = false;
			if (access) {
				that.element$ = $(this);
				that.start();
			}
			return false;
		});
		return this;
	}
	resetPrimary.init();

	return this;
    }

    this.InitBlockButton = function()
    {
		var setBlock = new buttons;
	setBlock.setSettings({
		'element'    : '.setBlock'
	})
	.setCallback(function (response, element$) {
		if (typeof response == "number")
			element$.hide()
				.parent()
				.children('.resetBlock')
				.css({ 'display' : 'block' });
	}).init = function (){
		this.loader.init();
		var that = this;
		$(this.settings.element).click(function (event) {
			event.stopPropagation();
			var access = true;
			that.setActive($(this));
			if ($(this).hasClass('confirm'))
				if (!confirm($(this).data('confirm')||'Do you sure?'))
					access = false;
			if (access) {
				that.element$ = $(this);
				that.start();
			}
			return false;
		});
		return this;
	}
	setBlock.init();

	return this;
    }

    this.InitResetBlockButton = function()
    {
		var resetBlock = new buttons;
	resetBlock.setSettings({
		'element'    : '.resetBlock'
	})
	.setCallback(function (response, element$) {
		if (typeof response == "number")
			element$.hide()
				.parent()
				.children('.setBlock')
				.css({ 'display' : 'block' });
	}).init = function (){
		this.loader.init();
		var that = this;
		$(this.settings.element).click(function (event) {
			event.stopPropagation();
			var access = true;
			that.setActive($(this));
			if ($(this).hasClass('confirm'))
				if (!confirm($(this).data('confirm')||'Do you sure?'))
					access = false;
			if (access) {
				that.element$ = $(this);
				that.start();
			}
			return false;
		});
		return this;
	}
	resetBlock.init();

	return this;
    }

    this.InitFileRemoveFromDetails = function()
    {
	var fileRemoveFromDetails = new buttons;
	fileRemoveFromDetails.setSettings({
		'element'    : '.removeFileFromDetails'
	})
	.setCallback(function (response) {
		if (typeof response == "number"){
			(new filesHandler).updateFilesListBlock();
			$('.modalContainer').remove();
		}
	}).init();

	return this;
    }

    this.InitAjaxModal = function()
    {
	(new ajaxModal).init({
		'button': '.editFile',
		'callback': function(){  (
			new filesHandler).InitFileEditForm().InitFileRemoveForm();
		},
		'dialog' : {
			'title' : 'Редактирование свойств файла',
			'modal' : true,
			'zIndex': 400,
			'width' : '410px',
			'buttons' : {
				"Удалить" : function () {
					$(this).find('.removeFileFromDetails').click();
				},
				"Сохранить" : function () {
					$(this).find('.formFileEditSubmit').click();
					$('.modalContainer').remove();
				},
				"Закрыть" : function () {
					$(this).dialog('close');
				}
			}
		}
	});

	$('.ui-dialog').live("dialogclose", function(){
		$('.modalContainer').remove();
	});

	return this;
    }

    this.InitAll = function()
    {
	this.initFileControll()
	    .initFile()
	    .InitPrimaryButton()
	    .InitResetPrimaryButton()
	    .InitBlockButton()
	    .InitResetBlockButton()
	    .InitFileRemoveFromDetails()
	    .InitAjaxModal();
    }
}