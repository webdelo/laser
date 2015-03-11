$(function(){
	initGoodAutosuggest();
	selectionClearOnSave();
});

var initGoodAutosuggest = function(){
	$('.inputGoodId').autoSuggest('/admin/complectGoods/ajaxGetAutosuggestGoods/', {
		selectedItemProp: "name",
		searchObjProps: "name",
		targetInputName: 'goodId',
		secondItemAtribute: 'code',
		thirdItemAtribute: 'price',
		fourthItemAtribute: 'basePrice',
		start: disableForm,
		selectionRemoved: canceledResult,
		resultClick: activateForm
	});
}

var selectionClearOnSave = function(){
	$('.formEditSubmit').live('click', function(){
		$('.as-selection-item').remove();
	})
}

var onSelectionAdded = function(targetInputName){
    goodIdAdded(targetInputName);
}

var onSelectionRemoved = function(targetInputName){
    goodIdRemoved();
}


var goodIdAdded = function(targetInputName){
	var goodId = $('input[name=goodId]').val();
	var data = {goodId : goodId}
	$.ajax({
			before: $('.inputGoodLoader').show(),
			url: '/admin/complectGoods/ajaxGetAutosuggestGoodById/',
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(data){
				if(data.price){
					$('.inputGoodLoader').hide();
					$('.addedGoodQuantity').val(data.quantity).attr({'data-goodId': goodId});
					$('.addedGoodPrice').text(data.price);
					$('.addedGoodBasePrice').html(data.basePrice);
				}
				else{
					alert('Произошла ошибка при получении данных товара. Обратитесь к разработчикам.');
				}
			}
		});
}

var goodIdRemoved = function(){
	$('.addedGoodQuantity').val('');
	$('.addedGoodPrice').val('');
	$('.addedGoodBasePrice').val('');
}

var activateForm = function () {
    $('#addGoodToComplect').removeClass('blockedButton')
    $('.blocked').removeAttr('disabled');
    $('.blockedRow').fadeTo('fast', 1);
}
var disableForm = function (elem) {
    $('#addGoodToComplect').addClass('blockedButton')
    $('.blocked').attr('disabled');
    $('.blockedRow').fadeTo('fast', 0.4);
}
var canceledResult = function (elem) {
    $('.as-values').val("");elem.remove();
    disableForm();
}