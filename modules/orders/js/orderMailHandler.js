$(function(){
	(new orderMailHandler())
		.initAjaxModal()
		.initMessageAjaxModal();
});

var orderMailHandler = function()
{
	this.sources = {
		'alertPartnerButton' : '.alertPartner',
		'messagePartnerButton' : '.messagePartner',
		'orderId' : '.objectId',
		'aditionalMessage' : '.aditionalMessage',
		'copyToAdmin' : '.copyToAdmin',
		'managers' : '.managers',
		'loader' : '#ajax_bg',
		'buttonsClass' : '.ui-button',
		'modalWindow' : '.ui-dialog',
		'modalContainer' : '.modalContainer',
		'time' : 'input.time',
		'partnerConfirmedCheckbox' : '[name=partnerConfirmed]'
	};

	this.ajax = {
		'ajaxAlertPartner' : '/admin/orders/ajaxAlertPartner/',
		'ajaxMessagePartner' : '/admin/orders/ajaxMessagePartner/',
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
						(new orderMail).alertPartnerSend();
						switchtoWritetoPartner();
						
					},
					"Закрыть" : function () {
						$(this).dialog('close');
					}
				},
				open: function(  ) {
					if($('.noPartnerId').length>0)
						$('.ui-dialog-buttonpane').hide();
				}
			}
		});

		$('.ui-dialog').live("dialogclose", function(){
			$('.modalContainer').remove();
		});

		return this;
	};

	this.initMessageAjaxModal = function()
	{
		var that = this;
		(new ajaxModal).init({
			'button': that.sources.messagePartnerButton,
			'dialog' : {
				'title' : 'Отправка письма партнеру',
				'modal' : true,
				'zIndex': 400,
				'width' : 'auto',
				'buttons' : {
					"Отправить сообщение" : function () {
						(new orderMail).messagePartnerSend();
					},
					"Закрыть" : function () {
						$(this).dialog('close');
					}
				},
				open: function(  ) {
					if($('.noPartnerId').length>0)
						$('.ui-dialog-buttonpane').hide();
				}
			}
		});

		$('.ui-dialog').live("dialogclose", function(){
			$('.modalContainer').remove();
		});

		return this;
	};
	
	switchtoWritetoPartner = function()
	{
	$('.alertPartner.pointer').css("display","none");
	$('.messagePartner.pointer').css("display","");
	};

};