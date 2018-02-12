var imagesHandler = function()
{
    this.initImageControll = function()
    {
	var imagesControl = new imagesUpload();
	$.each(imagesControl.handlers, function(name, method){
		method.call(imagesControl);
	});
	
	return this;
    }
    
    this.initImage = function()
    {
		var imageControl = new image();
		$.each(imageControl.handlers, function(name, method){
			method.call(imageControl);
		});

		return this;
    };
    
    this.InitImageAddForm = function()
    {
		var imagesAdd = new form;
		imagesAdd.setSettings({
			'form'    : '.imagesAddForm',
			'active'  : '.active',
			'message' : '.message'
		})
		.setCallback(function (response) {
			if (response == true) {
				(new imagesUpload).reloadForm().getImagesListBlock();
			}
		})
		.init();

		return this;
    };
    
    this.InitImageEditForm = function()
    {
		var imagesEdit = new form;
		imagesEdit.setSettings({
			'form'    : '.formImageEdit',
			'active'  : '.active',
			'message' : '.message'
		})
		.setCallback(function (response) {
			$('.ui-dialog').fadeOut('fast', function () {
				$(this).remove();
			});
		})
		.init();

		return this;
    };
    
    this.InitImageRemoveForm = function()
    {
		var imageRemove = new buttons;
		imageRemove.setSettings({
			'element'    : '.removeImage'
		})
		.setCallback(function (response) {
			if (typeof response == "number") {
				$('#image'+response).fadeOut('fast', function(){
					$(this).remove();
					if ( $('.imagesList .image').length === 0 ) {
						if ( $('.imagesHeader').length > 0 ) {
							$('.imagesHeader').find('img').fadeOut();
						}
						if ( $('.finalSave').length > 0 ) {
							$('.finalSave').addClass('disabled');
						}
					}
				});
				
			}
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
		imageRemove.init();

		return this;
    };
    
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
    };
    
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
    };
    
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
    };
    
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
    };
    
    this.InitImageRemoveFromDetails = function()
    {
		var imageRemoveFromDetails = new buttons;
		imageRemoveFromDetails.setSettings({
			'element'    : '.removeImageFromDetails'
		})
		.setCallback(function (response) {
			if (typeof response == "number")
				location.reload();
		}).init();

		return this;
    };
    
    this.InitAjaxModal = function()
    {
		(new ajaxModal).init({
			'button': '.editImage',
			'dialog' : {
				'title' : 'Редактирование изображения',
				'modal' : true,
				'zIndex': 400,
				'width' : '750px',
				'buttons' : {
					"Удалить" : function () {
						$(this).find('.removeImageFromDetails').click();
					},
					"Сохранить" : function () {
						var but$ = $(this).find('.formImageEditSubmit');
						but$.click();
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
    };
	
    this.InitImagesSortable = function()
    {
		$('.imagesSortable').sortable({
			helper: 'clone',
			containment: 'body',
			update: function(){
				var query = '';
				$('.imagesSortable').children('.image').each(function (i){
					if ($(this).data('id') !== undefined) {
						query += '&data['+$(this).data('id')+']='+i;
					}
				});
				$.ajax({
					error: function(){ alert('Приоритеты не сохранены. Обратитесь к разработчикам.'); },
					url: $('.imagesSortable').data('action')+query,
					type: 'post',
					dataType: 'json'
				});
			}
		})
		return this;
    };
    
    this.InitAll = function()
    {
		this.initImage()
			.InitImageEditForm()
			.InitImageRemoveForm()
			.InitPrimaryButton()
			.InitResetPrimaryButton()
			.InitBlockButton()
			.InitResetBlockButton()
			.InitImageRemoveFromDetails()
			.InitAjaxModal()
			.InitImagesSortable();
    };
};

$(function () {
	(new imagesHandler())
	    .initImageControll()
	    .initImage()
	    .InitImageAddForm()
	    .InitImageEditForm()
	    .InitImageRemoveForm()
	    .InitPrimaryButton()
	    .InitResetPrimaryButton()
	    .InitBlockButton()
	    .InitResetBlockButton()
	    .InitImageRemoveFromDetails()
	    .InitAjaxModal()
		.InitImagesSortable();
});