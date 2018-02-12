$(function(){
	(new feedbackHandler())
		.sendReview()
		.sendContactMessage()
		.clickSendButton();
});

var feedbackHandler = function()
{
	this.feedbackObject = new feedback;
	
    this.sources = {
	'contactForm'	:   '.form_contact',
	'sendButton'	:   '.form_contact button',
	'clientName'	:   'input[name=clientName]',
	'phone'			:   'input[name=phone]',
	'email'			:   'input[name=email]',
	'captcha'		:   'input[name=captcha]',
	'textToSend'	:   'textarea[name=textToSend]',
	'messageSent'	:   '.messageSent',
	
	'sendReviewButton' : '.sendreviewMessage',
	'reviewMessageField' : $('[name=reviewMessage]'),
	'revOrdId' : $('.reviewOrderId'),
	
	'contactMessageFormButton'	:   '.sendMessageButton',
	'contactName' : $('[name=msgName]'),
	'contactEmail' : $('[name=email]'),
	'contactMessage' : $('[name=msgText]')
    };

    this.ajaxLoader = new ajaxLoader();

    this.actions = {
	'sendMessage'	:   '/feedback/ajaxSendMessage/',
	'getContactsFormBlockAction' : '/feedback/getFeedbackContactBlock/'
    };


	this.sendReview = function()
	{
		var that = this;
		$(that.sources.sendReviewButton).live('click', function(){
			that.feedbackObject.sendReview();
		});
		return this;
	}

	this.sendContactMessage = function()
	{
		var that = this;
		$(that.sources.contactMessageFormButton).live('click', function(){
			that.feedbackObject.sendContactMessage();
		});
		return this;
	}

    this.clickSendButton = function()
    {
		var that = this;
		$(that.sources.sendButton).live('click',function(){
			$(that.sources.messageSent).hide();
			(new feedback).sendForm();
		});
    }

}