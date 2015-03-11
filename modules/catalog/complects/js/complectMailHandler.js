$(function(){
	(new complectMailHandler())
		.initAjaxModal();
});

var complectMailHandler = function()
{
	this.sources = {
		'alertPartnerButton' : '.alertPartner',
		'complectId' : '.objectId',
		'aditionalMessage' : '.aditionalMessage',
		'copyToAdmin' : '.copyToAdmin',
		'loader' : '#ajax_bg',
		'buttonsClass' : '.ui-button',
		'modalWindow' : '.ui-dialog',
		'modalContainer' : '.modalContainer',
		'partnerHistory' : '.partnerHistory',
		'time' : 'input.time',
		'partnerNotifiedCheckbox' : '[name=partnerNotified]'
	};

	this.ajax = {
		'ajaxAlertPartner' : '/admin/complects/ajaxAlertPartner/',
	};

	this.initAjaxModal = function()
	{
		var that = this;
		(new ajaxModal).init({
			'button': that.sources.alertPartnerButton,
			'dialog' : {
				'title' : 'Отправка оповещения партнеру',
				'modal' : true,
				'zIndex': 400,
				'width' : 'auto',
				'buttons' : {
					"Отправить оповещение" : function () {
						(new complectMail).alertPartnerSend();
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

}