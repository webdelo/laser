$(function(){
	(new availabilityHandler())
	.editAvailability()
	.saveAvailability()
	.toggleCommentsText();
});

var availabilityHandler = function()
{
	this.sources = {
		'addAvailabilityButton'  : '.editAvailabilityForPartner',
		'saveAvailabilityButton' : '.saveAvailabilityForPartner',
		'viewFullCommentsButton' : '.viewComment',
		'partnerIdField'         : '.partnerId',
		'commentField'           : '.comment',
		'manufactureField'       : '.manufacture',
		'quantityField'          : '.quantity',
		'objectId'               : '#objectId'
	};

	this.editAvailability = function()
	{
		var that = this;
		$(that.sources.addAvailabilityButton).live('click',function(){
			var availabilityId = $(this).data('partner_id');
			that.toggleForms(availabilityId);
		});
		return this;
	};
	
	this.toggleForms = function(formId)
	{
		$('#viewAvailabilityBlock_'+formId).toggleClass('hide');
		$('#editAvailabilityBlock_'+formId).toggleClass('hide');
		return this;
	};
	
	this.saveAvailability = function()
	{
		var that = this;
		$(that.sources.saveAvailabilityButton).live('click',function(){
			var block$ = $(this).parent().parent();
                        
			var postData = {
				'partnerId'  : block$.find(that.sources.partnerIdField).val(),
				'comment'    : block$.find(that.sources.commentField).val(),
				'manufacturer' : block$.find(that.sources.manufactureField+':checked').val(),
				'quantity'   : block$.find(that.sources.quantityField).val(),
				'objectId'   : $(that.sources.objectId).val()
			};
			(new availability).updateAvailability(postData, function() {
				var availabilityId = $(this).data('partner_id');
				that.toggleForms(availabilityId);
			});
		});
		return this;
	};
	
	this.toggleCommentsText = function()
	{
		$(this.sources.viewFullCommentsButton).live('click',function(){
			var id = $(this).data('partner_id');
			$('#comment_'+id).toggleClass('more');
		});
		return this;
	};
};