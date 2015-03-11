var feedback = function()
{
	this.ajax = {
		'sendReview' : '/order/ajaxAddReviewToOrder/',
		'sendContactMessage' : '/feedback/ajaxSendContactMessage/'
	};

	this.sources = {
		'sendOrderReviewForm' : '.sendOrderReviewForm',
		'sendContactMessageForm' : '.contactForm',
		'sendContactMessageOkBlock' : '.sendMessageOkBlock'
	}

    this.errors = new errors({
            'form'	:	'.form_contact',
            'submit'  : '.form_contact button',
            'message' : '#message',
            'error'   : '.hint',
            'showMessage' : 'showMessage'
    });
	
	this.orderReviewErrors  = new errors({
		'form' : this.sources.sendOrderReviewForm,
		'error' : '.hint',
		'showMessage' : 'showMessage'
	});
	
	this.contactMessageErrors  = new errors({
		'form' : this.sources.sendContactMessageForm,
		'error' : '.hint',
		'showMessage' : 'showMessage'
	});


	this.loader = new ajaxLoader();
	
	this.sendReview = function()
	{
		var that = this;
		that.loader.setLoader((new feedbackHandler).sources.sendReviewButton);
		$.ajax({
			url: that.ajax.sendReview,
			type: 'POST',
			data: {
				'reviewMessage' : ((new feedbackHandler).sources.reviewMessageField).val(),
				'orderId'		: ((new feedbackHandler).sources.revOrdId).val()
			},
			dataType: 'json',
			success: function(data){
				that.loader.getElement();
				if(data[ 'result']=='ok'){
					that.orderReviewErrors.reset();
					location.reload();
					//$(that.sources.sendOrderReviewForm).html(data['content']);
					//$(that.sources.sendOrderOkBlockMin).show();
				} else {
					that.orderReviewErrors.show(data);
					$(that.sources.sendMessageOkBlockMin).hide();
				}
			}
		});
	}
	
	this.sendContactMessage = function()
	{
		var that = this;
		that.loader.setLoader((new feedbackHandler).sources.contactMessageFormButton);
		$.ajax({
			url: that.ajax.sendContactMessage,
			type: 'POST',
			data: {
				'msgName'  : ((new feedbackHandler).sources.contactName).val(),
				'email' : ((new feedbackHandler).sources.contactEmail).val(),
				'msgText'  : ((new feedbackHandler).sources.contactMessage).val()
			},
			dataType: 'json',
			success: function(data){
				that.loader.getElement();
				if(data[ 'result']=='ok'){
					that.contactMessageErrors.reset();
					((new feedbackHandler).sources.contactName).val("");
					((new feedbackHandler).sources.contactEmail).val("");
					((new feedbackHandler).sources.contactMessage).val("");
					//$(that.sources.sendOrderReviewForm).html(data['content']);
					$(that.sources.sendContactMessageOkBlock).show();
				} else {
					that.contactMessageErrors.show(data);
					$(that.sources.sendContactMessageOkBlock).hide();
				}
			}
		});		
	}
	
	
	



    this.sendForm = function()
    {
	var handlerFeedback = (new feedbackHandler);
	var that = this;

	var data ={
	    'clientName':   $(handlerFeedback.sources.clientName).val(),
	    'phone'	:   $(handlerFeedback.sources.phone).val(),
	    'email'	:   $(handlerFeedback.sources.email).val(),
	    'captcha'	:   $(handlerFeedback.sources.captcha).val(),
	    'textToSend':   $(handlerFeedback.sources.textToSend).val()
	};
	$.ajax({
		before: handlerFeedback.ajaxLoader.setLoader(handlerFeedback.sources.sendButton),
		url: handlerFeedback.actions.sendMessage,
		type: 'POST',
		dataType: 'json',
		data: data,
		success: function(data){
		    handlerFeedback.ajaxLoader.getElement();
			if(data == 1){
				that.errors.reset();
				that.resetContactsFormBlock();
			} else {
				that.errors.show(data);
			}
		}
	});
    }

	this.resetContactsFormBlock = function()
	{
		var handlerFeedback = (new feedbackHandler);
		var that = this;

		$.ajax({
			url: handlerFeedback.actions.getContactsFormBlockAction,
			type: 'POST',
			dataType: 'html',
			success: function(data){
				$(handlerFeedback.sources.contactForm).replaceWith(data);
				$(handlerFeedback.sources.messageSent).show();
			}
		});
	}
}


