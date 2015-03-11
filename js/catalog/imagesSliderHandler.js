$(function(){
	$('.lightboxPreview').gallery({'opacity':.9});
	$('.lightboxOne').gallery({'opacity':.9});
	
	(new imagesSliderHandler())
		.clickImage();
});

var imagesSliderHandler = function()
{
	this.sources = {
		'smallImage' : '.small_image'
	};
	
	this.clickImage = function()
	{
		$(this.sources.smallImage).live('click',function(){
			var bigImagesClass = 'object_'+$(this).data('object');
			$('.'+bigImagesClass).addClass('hide');
			var bigImageId = 'big_'+$(this).attr('id');
			$('#'+bigImageId).removeClass('hide');
			return false;
		});			
	};
};
