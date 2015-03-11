// non declared functions will be taked from groupActions.js

$(function () {
	onActionSelectedPercent();
	checkUncheckAllProcent();
	onProcentButtonClick();
});

var onActionSelectedPercent = function(){
	$('#paidPercent').change(function(){
		if(getCheckedElements().length  &&  getAction() === 'paidPercent' && $('#paidPercent option:selected').val() != ''){
			$('.paidPercentButton').show();
		}
		else{
			$('.paidPercentButton').hide();
		}
	});
}

var checkUncheckAllProcent = function(){
	$('.check_all').click(function(){
		if ($(this).hasClass('on')  &&  getAction() === 'paidPercent') {
			$('.paidPercent').show();
		} else {
			$('.paidPercent').hide();
		}
	});
};

var onProcentButtonClick = function()
{
	$('.paidPercentButton').live('click', function() {
		$.ajax({
			url: '/admin/orders/ajaxGroupEdit/',
			type: 'POST',
			data: {
				'field' : 'paidPercent',
				'value' : $('#paidPercent option:selected').val(),
				'group' : getIds()
			},
			success: function(data){
				if(data == 1)
					location.reload();
				else
					alert(data);
			}
		});
	});
}

var getIds = function()
{
	var ids = new Array;
	getCheckedElements().each(function(key, value){
		ids.push($(this).parent().parent().attr('data-id'));
	});
	return ids;
}