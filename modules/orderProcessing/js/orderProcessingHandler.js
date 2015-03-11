$(function(){
	$('.notifyOrder').click(function(){
		var that = this;
		$('.incompleteOrders').htmlFromServer().dialog({
			'title' : $(this).attr('title'),
			'width' : '850',
			'zIndex': '1050',
			'position': {
				my: "right top", 
				at: "right bottom", 
				of: that 
			},
			'modal'   : true
		});
	});
});