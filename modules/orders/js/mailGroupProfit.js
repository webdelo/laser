$(function (){
	mailGroupProfitInit(),
	okButtonClick(),
	setEmails(),
	showHideMailTable(),
	persentPayedInit();
});

var mailGroupProfitInit = function(){
	var mailGroupProfit = new form;
	mailGroupProfit.setSettings({'form':'.mailGroupProfit'})
				.setCallback(function (response) {
					$('.okBlock').hide();
					if(response == 1){
						$('.okBlock').fadeIn("slow");
						$('.mailGroupProfitSubmit').fadeOut("slow");
					}

				})
				.init();
};

var okButtonClick = function(){
	$('.okButton').click(function () {
		$('.to_clear').val('');
		$('.okBlock').fadeOut("slow");
		$('.mailGroupProfitSubmit').fadeIn("slow");
		$('.manager').removeClass('jquery-tree-checked').addClass('jquery-tree-unchecked');
	});
};

var setEmails = function(){
	$('label').click(function(){
		var string = '';
		$('label').each(function(){
			if($(this).hasClass("jquery-tree-checked") && $(this).hasClass("manager"))
				string = string + $(this).attr('value') + ', ';
		  });

		if($(this).attr('value')){
			var substring = $(this).attr('value');
			if( string.indexOf(substring) < 0 )
				string = string + substring + ', ';
			else
				string = string.replace(substring + ', ', '');
		}

		 $('textarea[name="emails"]').val(string);
	})
}

var showHideMailTable = function(){
	$('.show_table').click(function(){
		if($('.mail_table').is(':visible')){
			$(this).html('Рассылка ↓');
			$('.mail_table').fadeOut('slow');
		}
		else{
			$(this).html('Скрыть ↑');
			$('.mail_table').fadeIn('slow');
		}
	})
}

var persentPayedInit=function(){
	$('.persentPayed').click(function(){
		var loader = new ajaxLoader();
		$.ajax({
			before: loader.setLoader('.persentPayed'),
			url: '/admin/orders/ajaxEditPaidPercentGroup',
			type: 'POST',
			dataType: 'json',
			data: {
				'idsString' : $('[name="ids"]').val(),
				'paidPercent' : 1
			},
			success: function(data){
				loader.getElement();
				if($.isNumeric(data)){
					$('.persentPayed').hide();
					$('.persentPayedOk').fadeIn('slow');
				}
				else
					alert('Error while trying to change payed persent for group of orders!');
			}
		});
	});
}