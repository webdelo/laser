$(function () {
	window.articlesSorting = new groupSorting({
		'element$' : $( "#objects-tbl" ).find( "tbody" ),
		'containment' : $( "#objects-tbl" )
	});
	window.articlesSorting.action = $('#objects-tbl').attr('data-sortUrlAction');
	articlesSorting.sortable().stylize();
	
	window.parameterValuesSorting = new groupSorting({
		'element$' : $( ".parameterValuesTable" ).find( "tbody" ),
		'containment' : $( ".parameterValuesTable" )
	});
	window.parameterValuesSorting.action = $('.parameterValuesTable').attr('data-sortUrlAction');
	parameterValuesSorting.sortable().stylize();
})