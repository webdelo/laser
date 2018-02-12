$(function () {

	(new filesHandler())
	    .initFileControll()
	    .initFile()
	    .InitFileAddForm()
	    .InitFileEditForm()
	    .InitFileRemoveForm()
	    .InitPrimaryButton()
	    .InitResetPrimaryButton()
	    .InitBlockButton()
	    .InitResetBlockButton()
	    .InitFileRemoveFromDetails()
	    .InitAjaxModal();
});

var filesUpload = function (settings) {
	this.settings = $.extend({
		'container'    : '.newFiles',
		'controller'	: '.mainControllerFiles',
		'uploadButton' : '#file_uploadFile',
		'bgColor' : 'ECF5FC'
	}, settings||{});

	this.reloadForm = function()
	{
	    this.getNewFileForm();
	}

	this.getNewFileForm = function()
	{
		var id = $('.objectId').val();
		var that = this;

		$.ajax({
			url: '/admin/'+$(that.settings.controller).val()+'/ajaxGetFilesBlock/',
			type: 'POST',
			data: {'objectId': id},
			success: function(data){
				$('.newFiles').html(data);
				that.getFilesListBlock(id);
			}
		});
	}

	this.getFilesListBlock = function(id)
	{
	    var that = this;

	    $.ajax({
			url: '/admin/'+$(that.settings.controller).val()+'/ajaxGetFilesListBlock/',
			type: 'POST',
			data: {'objectId' : id},
			success: function(data){
				$('.filesList').html(data);
				(new filesHandler()).InitAll();
		    }
	    });
	}

	this.setSettings = function (sources) {
		this.settings = $.extend(this.settings, sources||{});
		return this;
	}

	this.handlers = {
		init  : function () {
			this.initUploadifiveFile();
			return this;
		}
	}

	this.initUploadifiveFile = function () {
		var that = this;
		$(this.settings.uploadButton).uploadifiveFile({
	        'uploadScript' : that.getUploadScript(),
			'simUploadLimit' : 1,
			'buttonText': that.getButtonTitle(),
			'removeCompleted' : false,
			'onUploadComplete' : $.proxy(that.onUploadComplete, that)
	    });
	}

	this.getUploadScript = function () {
		return $(this.settings.container).data('action') || '/admin/articles/uploadFile/';
	}

	this.getButtonTitle = function () {
		return $(this.settings.container).data('title') || 'Добавить новые изображения';
	}

	this.onUploadComplete = function(file, response) {
		if (response.result == true) {
			this.createImgForm(file, response)
				.activateFilesSaveButton()
				.removeExampleForm()
				.implementImgForm(file);
			this.createFileIcon(response.message);
		}
	}

	this.createImgForm = function (file, response) {
		var name = response.message.split('/');
			name = name.pop();
		var imgForm$ = $('.filesForm').first().clone();

		imgForm$.removeClass('example')
				.removeClass('hide')
				.find('.name').attr('name', 'filesData['+name+'][name]').end()
				.find('.name').val(file.name).end()
				.find('.tmpName').attr('name', 'filesData['+name+'][tmpName]').end()
				.find('.tmpName').val(response.message.substr(1)).end()
				.find('.alias input[type=text]').attr('name', 'filesData['+name+'][alias]').end()
				.find('.title input[type=text]').attr('name', 'filesData['+name+'][title]').end()
				.find('.status select').attr('name', 'filesData['+name+'][statusId]').end()
				.find('.category select').attr('name', 'filesData['+name+'][categoryId]').end()
				.find('.description textarea').attr('name', 'filesData['+name+'][description]');

		this.imgForm$ = imgForm$;
		return this;
	}

	this.activateFilesSaveButton = function () {
		if ( $('.filesAddFormSubmitBlock').length > 0 )
			$('.filesAddFormSubmitBlock').fadeIn();
		return this;
	}

	this.removeExampleForm = function () {
		$('.filesForm').filter('.example').remove();
		return this;
	}

	this.implementImgForm = function (file) {
		this.replaceCloseToRemove(file);
		file.queueItem.append(this.imgForm$).autoScroll({
			'duration'   : '2000',
			'paddingTop' : '100',
			'callback'   : null
		});

		return this;
	}

	this.replaceCloseToRemove = function (file) {
		var that = this;
		file.queueItem.find('.close').remove();
		this.imgForm$.find('.remove a').click(function () {
			if ( $('.uploadifiveFile-queue-item').length > 1 ) {
				that.cancelUploadedFile(file);
			} else {
				var example$ = $('.uploadifiveFile-queue-item').clone();
				example$.addClass('example').addClass('hide');
				$('body').append(example$);
				if ( $('.filesAddFormSubmitBlock').length > 0)
					$('.filesAddFormSubmitBlock').hide();
				that.cancelUploadedFile(file);
			}

		});
	}

	this.cancelUploadedFile = function (file) {
		(function (id) {
			$('#file_upload_files').uploadifiveFile('cancel', $('.uploadifiveFile-queue-item').filter('[id='+id+']').data('file'));
		})(file.queueItem.attr('id'))
	}

	this.createFileIcon = function(path){
		var fileName = 'filesData[' + path.substr(11) + '][tmpName]';
		$.ajax({
			url: '/admin/'+$(this.settings.controller).val()+'/getFileIcon/',
			type: 'POST',
			data: {'pathToFile': path},
			success: function(data){
				$(document.getElementsByName(fileName)).parent().find('.file').html('<img src="' + data + '">');
			}
		});
	}

	this.getFilePath = function (fileUrl, fileResolution) {
		if (fileResolution != undefined) {
			var fileArr = fileUrl.split('/');
			fileArr.shift();
			var filePath = '';
			$.each(fileArr, function (i) {
				if (i != 2)
					filePath = filePath+'/'+fileArr[i];
				else
					filePath = filePath+'/'+fileResolution+'/'+fileArr[i];
			});
			return filePath;
		} else {
			return fileUrl;
		}
	}
}

var fileClass = function (settings) {
	this.settings = $.extend({
		'element'  : '.file',
		'header'   : '.fileHeader',
		'inactive' : '.inactive',
		'controls' : '.control',
		'save'     : '.save',
		'title'    : '.title'
	}, settings||{});

	this.setSettings = function (sources) {
		this.settings = $.extend(this.settings, sources||{});
		return this;
	}

	this.handlers = {
		init  : function () {
			this.files = new filesUpload();
			return this;
		}
	}

	this.toggleInlineLayer = function () {
		var file$ = $(this.settings.element);
		var controls$ = $(this.settings.controls);
		var header$ = $(this.settings.header);

		file$.live("mouseenter", function(){
			header$.css({
				'width'  : $(this).width(),
				'height' : $(this).height()
			});
			$(this).find(header$).fadeIn('fast');
		}).live("mouseleave", function(){
			$(this).find(header$).fadeOut('fast');
		});
		return this;
	}

	this.save = function (element$) {
		element$.loaderMini({
			'file'      : '/modules/files/img/loader.gif',
			'titleText' : 'Saving...'
		}).delay(3000).loaderMini('stop');

		this.files.deSelectBlock();
	}

	this.remove = function () {

	}
}