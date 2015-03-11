$(function(){
	var deliveryForm = new form;
	deliveryForm
		.setSettings({'form' : '.deliveryFormAdd'})
		.setCallback(function (response) {
			(new order).resetOrderGoodsTableBlock();
		})
		.init();
	
	var deliveryCategories = new selects;
	deliveryCategories
		.setSettings({'element' : '.deliveryCategories', 'showError':false})
		.setCallback(function (response) {
			if(typeof response == 'object'){
				var delivery$ = $('.deliveries');
				delivery$.children().not(':first').remove();
				$.each(response, function(){
					delivery$.append($('<option></option>').val(this.value).text(this.name));
				});
				delivery$.fadeIn()
						.children('option:first')
						.text(
							deliveryCategories.getChoosedObject()
											.data('next_step_name')
						);
			} else {
				$('.deliveries').html('').fadeOut();
			}
			$('.deliveryAddressBlock').html('');
			$('.deliveryFormAddSubmit').attr('disabled', true)
		})
		.init();

	var deliveries = new selects;
	deliveries
		.setSettings({'element' : '.deliveries'})
		.setCallback(function (response) {
			$('.deliveryAddressBlock').html(response);
			$('.deliveryFormAddSubmit').removeAttr('disabled');
		})
		.init();
});