$(function (){
	mailGroupDriverInit(),
	okButtonClick(),
	showHideMailTable();
});

var mailGroupDriverInit = function(){
	var mailGroupDriver = new form;
	mailGroupDriver.setSettings({'form':'.mailGroupDriver'})
				.setCallback(function (response) {
					$('.okBlock').hide();
					if(response == 1){
						$('.okBlock').fadeIn("slow");
						$('.mailGroupDriverSubmit').fadeOut("slow");
					}

				})
				.init();
};

var okButtonClick = function(){
	$('.okButton').click(function () {
		$('.to_clear').val('');
		$('.okBlock').fadeOut("slow");
		$('.mailGroupDriverSubmit').fadeIn("slow");
		$('.manager').removeClass('jquery-tree-checked').addClass('jquery-tree-unchecked');
	});
};

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