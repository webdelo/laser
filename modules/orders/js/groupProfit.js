// non declared functions will be taked from groupActions.js

$(function () {
	checkUncheckAllProfit();
	onElementSelectedProfit();
	onActionSelectedProfit();
	onGroupProfitButtonClick();
});

var checkUncheckAllProfit = function(){
	$('.check_all').click(function(){
		if ($(this).hasClass('on')  &&  getAction() === 'groupProfit') {
			$('.groupProfitButton').show();
		} else {
			$('.groupProfitButton').hide();
		}
	});
};

var onElementSelectedProfit = function(){
	$('.groupElements').change( function() {
		if(getCheckedElements().length  &&  getAction() === 'groupProfit'){
			$('.groupProfitButton').show();
		}
		else{
			$('.groupProfitButton').hide();
		}
	});
};

var onActionSelectedProfit = function(){
	$('.groupActionSelect').change( function() {
		if(getAction() === 'groupProfit')
			$('.groupProfitButton').show();
		else
			$('.groupProfitButton').hide();
	});
};

var onGroupProfitButtonClick = function()
{
	$('.groupProfitButton').live('click', function() {

		var url = '/admin/orders/groupProfit/?ids=';
		getCheckedElements().each(function(key, value){
			url += $(this).parent().parent().attr('data-id') + ',';
		});
		url = url.substring(0, url.length - 1);
		window.open(url);
	});



}