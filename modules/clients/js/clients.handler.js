$(function () {
	var changePassword = new form;
	changePassword.setSettings({
			'form'    : '.formChangePassword',
			'active'  : '.active',
			'message' : '.changePasswordMessage'
		})
		.setCallback(function (response) {
			return true;
		})
		.init();

	var editClientInputs = new inputs;
	editClientInputs
		.setSettings({'element' : '.editClientInputs', 'event': 'blur'})
		.setCallback(function (response) {
			if ( typeof response !== 'number' )
				alert(response);
		})
		.init();

	var editClientSelects = new selects;
	editClientSelects
		.setSettings({'element' : '.editClientSelects', 'event': 'change' })
		.setCallback(function (response) {
			if ( typeof response !== 'number' )
				alert(response);
		})
		.init();



	$('.ClientAddressBlock input[type=text]').focus(function(){
		if ( $(this).data('clicktext') && $(this).val() === '' )
			$(this).val($(this).data('clicktext'));
	}).blur(function(){
		if ( $(this).data('clicktext') === $(this).val() )
			$(this).val('');
	});
		
	$('.transformer,.transformerTextarea').transformer();
		
	$('.transformer.teledit').focus(function() {
		$(this).inputmask("mask", {"mask": "+9 (999) 999-99-99 доб. 99999"});
	}).focusout(function() {
		$(this).inputmask('remove');
		var targ = $(this).val();
		
		if (targ.length > 11)
			targ = targ.replace(/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})(\d{1})/, "+$1 ($2) $3-$4-$5 доб. $6");
		else if (targ.length === 11)
			targ = targ.replace(/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/, "+$1 ($2) $3-$4-$5");
		else if (targ.length > 9 && targ.length < 11)
			targ = targ.replace(/(\d{1})(\d{3})(\d{3})(\d{2})(\d{1})/, "+$1 ($2) $3-$4-$5");
		else if (targ.length > 7 && targ.length <= 9)
			targ = targ.replace(/(\d{1})(\d{3})(\d{3})(\d{1})/, "+$1 ($2) $3-$4");
		else if (targ.length > 4 && targ.length < 8)
			targ = targ.replace(/(\d{1})(\d{3})(\d{1})/, "+$1 ($2) $3");

		$(this).val(targ);
	});
	
	
	
	
	
	
	
	var buttonsToDeliveryDialog = {
		"Сохранить": function() {
			$('.deliveryFormAddSubmit').click();
		},
		"Отмена": function() {
			$(this).dialog( "close" );
		}
	}
	if ( $('.deliveryAddressContent').length > 0 ) {
		buttonsToDeliveryDialog = {
			"Сохранить": function() {
				$('.deliveryFormAddSubmit').click();
			},
			"Отмена": function() {
				$(this).dialog( "close" );
			}
		};
	}
	
	$('.editDeliveryButton').click(function(){
		$('.deliveryEditBlock').dialog({
			'width': 'auto',

			buttons: buttonsToDeliveryDialog
		});
	});
	
	var deliveryForm = new form;
	deliveryForm
		.setSettings({'form' : '.deliveryFormAdd'})
		.setCallback(function (response) {
			if ( typeof response == 'number' ) {
				$('.deliveryEditBlock').dialog('destroy').remove();
				//$('.goodsTableList').htmlFromServer();
				$('.deliveryEditBlock').remove();
				//$('.deliveryContainer').htmlFromServer();
			}
		})
		.init();

});