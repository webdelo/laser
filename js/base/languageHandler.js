$(function(){
	$('.lang span').live('click', function() {
		$.ajax({
			url: $(this).attr('data-rdr'),
			type: 'GET',
			success: function(data){
				if(data)
					location.reload();
				else
					alert('Error while sending request');
			}
		});
	});
	
});