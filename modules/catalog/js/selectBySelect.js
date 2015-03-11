$(function(){
	var selectBySelect = new selects;
	selectBySelect
		.setSettings({'element' : '.parametersCategories'})
		.setCallback(function (response) {
			if(typeof response == 'number'  &&  data > 0){
//			if (response != 1) {
				var delivery$ = $('.parameters');
				delivery$.html('')
						.html(response)
						.fadeIn()
			}
		})
		.init();
});