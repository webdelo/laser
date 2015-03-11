$(function () {
    $('.quantityComplects').on('textchange', function () {
	$('.totalComplectPrice:visible').html(
	    parseInt( $(this).attr('data-totalComplectPrice') ) * $(this).val()
	);
	$('.totalComplectDiscount:visible').html(
	    parseInt( $(this).attr('data-totalComplectDiscount') ) * $(this).val()
	);
   });
    
});