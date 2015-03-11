$(function(){
	var editDelivery = new inputs;
	editDelivery
		.setSettings({'element' : '.editDelivery'})
		.setCallback(function (response) {
			if ( typeof response === 'number' ) {
				$('.deliveryEditBlock').remove();
				$('.deliveryContainer').htmlFromServer();
				$('.goodsTableList').htmlFromServer();
			} else
				alert(response);
		})
		.init();

	$('.orderGoodDetailsModal').live('click', function(){
		showOrderGoodDetailsModal($(this));
	});
});

var showOrderGoodDetailsModal = function(element){
	$('.orderGoodDetails').data('source', element.attr('href'))
						  .htmlFromServer()
						  .dialog({
							  'title'   : element.attr('modalTitle'),
							  'width'   : '700',
							  'height'  : '720',
							  'position': 'top',
							  'height' : 'auto',
							  'modal' : true,
							  'buttons' : {
								  'Закрыть' : function(){ $(this).dialog('close'); }
							  },
							  close: function( event, ui ) { $('.orderGoodDetails').html(''); }
						  });
	return false;
}