var imagesUpload = function (settings) {
	this.settings = $.extend({
		'container'	   : '.newImages',
		'controller'   : '.mainController',
		'uploadButton' : '#file_upload',
		'bgColor'	   : 'ECF5FC'
	}, settings||{});

	this.reloadForm = function()
	{
	    this.getNewImageForm();
		return this;
	};
	
	this.getNewImageForm = function()
	{
	    var id = $('.objectId').val();
	    var that = this;
		var exampleForm$ = $('.newImages').find('.imagesForm:first').clone();
		$('.newImages').find('.imagesForm').remove();
		
		exampleForm$.addClass('example')
					.addClass('hide')
					.find('input,select,textarea').attr('name', '').val('');
		$('#placeForQueue').html('');
		$('.imagesAddFormSubmitBlock').fadeOut();
		
		$('.newImages').append(exampleForm$);
//	    $.ajax({
//		    url: $('.newImages').data('source') || '/admin/'+$(that.settings.controller).val()+'/ajaxGetImagesBlock/',
//		    type: 'POST',
//		    data: {'objectId': id}, 
//		    success: function(data){
//			    $('.newImages').html(data);
//		    }
//	    });
	};
	
	this.getImagesListBlock = function()
	{
		var id = $('.objectId').val();
	    var that = this;
	    
	    $.ajax({
		    url: $('.imagesList').data('source') || '/admin/'+$(that.settings.controller).val()+'/ajaxGetImagesListBlock/',
		    type: 'POST',
		    data: {'objectId' : id},
		    success: function(data){
			    $('.imagesList').html(data);
			    (new image()).toggleInlineLayer(); 
			    (new imagesHandler()).InitAll();
		    }
	    });	    
	};
	
	this.setSettings = function (sources) {
		this.settings = $.extend(this.settings, sources||{});
		return this;
	};

	this.handlers = {
		init  : function () {
			this.initUploadifive();
			return this;
		}
	};

	this.initUploadifive = function () {
		var that = this;
		$(this.settings.uploadButton).uploadifive({
	        'uploadScript' : that.getUploadScript(),
			'simUploadLimit' : 1,
			'buttonText': that.getButtonTitle(),
			'removeCompleted' : false,
			'onUploadComplete' : $.proxy(that.onUploadComplete, that)
	    });
	};

	this.getUploadScript = function () {
		return $(this.settings.container).data('action') || '/admin/articles/uploadImage/';
	};

	this.getButtonTitle = function () {
		return $(this.settings.container).data('title') || 'Добавить новые изображения';
	};

	this.onUploadComplete = function(file, response) {
		if (response.result == true) {
			this.createImgForm(file, response)
				.activateImagesSaveButton()
				.removeExampleForm()
				.implementImgForm(file);
		}
	};

	this.createImgForm = function (file, response) {
		var name = response.message.split('/');
			name = name.pop();
		var imgForm$ = $('.imagesForm').first().clone();

		imgForm$.removeClass('example')
				.removeClass('hide')
				.find('.image').html(this.createImgTag(response.message)).end()
				.find('.name').attr('name', 'imagesData['+name+'][name]').end()
				.find('.name').val(file.name).end()
				.find('.tmpName').attr('name', 'imagesData['+name+'][tmpName]').end()
				.find('.tmpName').val(response.message.substr(1)).end()
				.find('.alias input[type=text]').attr('name', 'imagesData['+name+'][alias]').end()
				.find('.title input[type=text]').attr('name', 'imagesData['+name+'][title]').end()
				.find('.status select').attr('name', 'imagesData['+name+'][statusId]').end()
				.find('.category select').attr('name', 'imagesData['+name+'][categoryId]').end()
				.find('.description textarea').attr('name', 'imagesData['+name+'][description]');

		this.imgForm$ = imgForm$;
		return this;
	};

	this.activateImagesSaveButton = function () {
		if ( $('.imagesAddFormSubmitBlock').length > 0 )
			$('.imagesAddFormSubmitBlock').fadeIn();
		return this;
	};

	this.removeExampleForm = function () {
		$('.imagesForm').filter('.example').remove();
		return this;
	};

	this.implementImgForm = function (file) {
		this.replaceCloseToRemove(file);
		file.queueItem.append(this.imgForm$).autoScroll({
			'duration'   : '2000',
			'paddingTop' : '100',
			'callback'   : null
		});

		return this;
	};

//	this.formsWrapFilesQueue = function () {
//		$('#uploadifive-file_upload-queue').wrapInner($('.imagesAddForm'));
//		return this;
//	}
//
//	this.impelentSubmitButton = function () {
//		$('#uploadifive-file_upload-queue').append($('.imagesAddFormSubmit'));
//	}

	this.replaceCloseToRemove = function (file) {
		var that = this;
		file.queueItem.find('.close').remove();
		this.imgForm$.find('.remove a').click(function () {
			if ( $('.uploadifive-queue-item').length > 1 ) {
				that.cancelUploadedFile(file);
			} else {
				var example$ = $('.uploadifive-queue-item').clone();
				example$.addClass('example').addClass('hide').removeClass('uploadifive-queue-item');
				$('body').append(example$);
				if ( $('.imagesAddFormSubmitBlock').length > 0)
					$('.imagesAddFormSubmitBlock').hide();
				that.cancelUploadedFile(file);
			}

		});
	};

	this.cancelUploadedFile = function (file) {
		(function (id) {
			$('#file_upload').uploadifive('cancel', $('.uploadifive-queue-item').filter('[id='+id+']').data('file'));
		})(file.queueItem.attr('id'))
	};

	this.createImgTag = function (imageUrl) {
		return $('<a href="'+this.getImagePath(imageUrl, '800x600')+'" class="lightbox"><img src="'+this.getImagePath(imageUrl, '400x300xcolor:'+this.settings.bgColor)+'"/></a>');
	};

	this.getImagePath = function (imageUrl, imageResolution) {
		if (imageResolution != undefined) {
			var imageArr = imageUrl.split('/');
			imageArr.shift();
			var imagePath = '';
			$.each(imageArr, function (i) {
				if (i != 2)
					imagePath = imagePath+'/'+imageArr[i];
				else
					imagePath = imagePath+'/'+imageResolution+'/'+imageArr[i];
			});
			return imagePath;
		} else {
			return imageUrl;
		}
	};
}

var image = function (settings) {
	this.settings = $.extend({
		'element'  : '.image',
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
			this.images = new imagesUpload();
			return this;
		},
		hover : function () {
			this.toggleInlineLayer();
		}
	}

	this.toggleInlineLayer = function () {
		var image$ = $(this.settings.element);
		var controls$ = $(this.settings.controls);
		var header$ = $(this.settings.header);

		image$.live("mouseenter", function(){
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
			'image'      : '/modules/images/img/loader.gif',
			'titleText' : 'Saving...'
		}).delay(3000).loaderMini('stop');

		this.images.deSelectBlock();
	}

	this.remove = function () {

	}

}