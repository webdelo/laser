var orderMail = function()
{
	this.handler = new orderMailHandler;

	this.alertPartnerSend = function()
	{
		var that = this;

		var managers = [];
		$((that.handler).sources.managers + ":checked").each(function() {
			managers.push(this.value);
		});

		var data ={
			'orderId' : $((that.handler).sources.orderId).val(),
			'aditionalMessage' : $((that.handler).sources.aditionalMessage).val(),
			'copyToAdmin' : $((that.handler).sources.copyToAdmin).prop("checked"),
			'time' : $((new orderMailHandler).sources.time).last().val(),
			'managers' : managers
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
				$(that.handler.sources.modalWindow).css('left','40%').css('top','40%');
				$('body,html').animate({scrollTop: 0}, 800);
				$(that.handler.sources.modalContainer).html(data.message);
				$((new orderHandler).sources.showOrderHistoryButton).removeClass('hide');
				$((new orderMailHandler).sources.messagePartnerButton).removeClass('hide');
				$((new orderMailHandler).sources.alertPartnerButton).addClass('hide');
			}
		});
	};

	this.messagePartnerSend = function()
	{
		var that = this;

		var managers = [];
		$((that.handler).sources.managers + ":checked").each(function() {
			managers.push(this.value);
		});

		var data ={
			'orderId' : $((that.handler).sources.orderId).val(),
			'aditionalMessage' : $((that.handler).sources.aditionalMessage).val(),
			'copyToAdmin' : $((that.handler).sources.copyToAdmin).prop("checked"),
			'time' : $((new orderMailHandler).sources.time).last().val(),
			'managers' : managers
		};

		$.ajax({
			before: $(that.handler.sources.loader).show(),
			url: that.handler.ajax.ajaxMessagePartner,
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(data){
				$(that.handler.sources.loader).hide();
				$(that.handler.sources.buttonsClass).first().hide();
				$(that.handler.sources.modalWindow).css('left','40%').css('top','40%');
				$('body,html').animate({scrollTop: 0}, 800);
				$(that.handler.sources.modalContainer).html(data.message);
			}
		});
	};
};


