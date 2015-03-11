// non declared functions will be taked from groupActions.js

$(function () {
	checkUncheckAllDriver();
	onElementSelectedDriver();
	onActionSelectedDriver();
	onGroupDriverButtonClick();
});

var checkUncheckAllDriver = function(){
	$('.check_all').click(function(){
		if ($(this).hasClass('on')  &&  getAction() === 'groupDriver') {
			$('.groupDriverButton').show();
		} else {
			$('.groupDriverButton').hide();
		}
	});
};

var onElementSelectedDriver = function(){
	$('.groupElements').change( function() {
		if(getCheckedElements().length  &&  getAction() === 'groupDriver'){
			$('.groupDriverButton').show();
		}
		else{
			$('.groupDriverButton').hide();
		}
	});
};

var onActionSelectedDriver = function(){
	$('.groupActionSelect').change( function() {
		if(getAction() === 'groupDriver')
			$('.groupDriverButton').show();
		else
			$('.groupDriverButton').hide();
	});
};

var onGroupDriverButtonClick = function()
{
	$('.groupDriverButton').live('click', function() {
		var url = '/admin/orders/groupDriver/?ids=';
		getCheckedElements().each(function(key, value){
			url += $(this).parent().parent().attr('data-id') + ',';
		});
		url = url.substring(0, url.length - 1);
		window.open(url);
	});



}