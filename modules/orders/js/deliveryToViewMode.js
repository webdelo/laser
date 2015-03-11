$(function(){
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
			'Удалить' : function() {
				$('.deleteDeliveryFromView').click();
			},
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
			'position': {
				my: "left top",
				at: "left bottom",
				of: ".h2"
			},
			buttons: buttonsToDeliveryDialog
		});
	});
	
	var deliveryForm = new form;
	deliveryForm
		.setSettings({'form' : '.deliveryFormAdd'})
		.setCallback(function (response) {
			if ( typeof response == 'number' ) {
				$('.deliveryEditBlock').dialog('destroy').remove();
				$('.goodsTableList').htmlFromServer();
				$('.deliveryEditBlock').remove();
				$('.deliveryContainer').htmlFromServer();
			}
		})
		.init();

});