$(function(){
	$('[name=countryId]').change(function(){
		changeRegionsSelect( $(this).val() );
	});
});

changeRegionsSelect = function(countryId){
	$('.regionsBlocks').htmlFromServer({
		'source' : '/admin/locationCities/ajaxGetRegionSelect/?countryId='+countryId,
		'loader' : (new loaderBlock).init($('.regionsBlocks')),
		'callback': function(){}
	});
}

