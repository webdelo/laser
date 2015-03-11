var complectMail = function()
{
	this.handler = new complectMailHandler;

	this.alertPartnerSend = function()
	{
		var that = this;
		var data ={
			'complectId' : $((that.handler).sources.complectId).val(),
			'aditionalMessage' : $((that.handler).sources.aditionalMessage).val(),
			'copyToAdmin' : $((that.handler).sources.copyToAdmin).prop("checked"),
			'time' : $((new complectMailHandler).sources.time).last().val(),
		};

		$.ajax({
			before: $(that.handler.sources.loader).show(),
			url: that.handler.ajax.ajaxAlertPartner,
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(data){
				$(that.handler.sources.loader).hide();
				$(that.handler.sources.buttonsClass).first().hide();
				$(that.handler.sources.modalWindow).css('left','40%').css('top','50%');
				$(that.handler.sources.partnerNotifiedCheckbox).prop('checked', false)
				if(data.res=='ok'){
					$(that.handler.sources.partnerHistory).html(data.partnerHistory);
				}
				$(that.handler.sources.modalContainer).html(data.message);
			}
		});
	}
}


