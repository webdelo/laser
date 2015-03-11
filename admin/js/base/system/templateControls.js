$(function(){
	toggleFilters();
});

function toggleFilters () {
	$('.filters').click(function () {
		$('#filter-form').toggle();
	});
}