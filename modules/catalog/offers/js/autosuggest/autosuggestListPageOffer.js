$(function(){
	initGoodAutosuggestOffer();
});

var initGoodAutosuggestOffer = function(){
	var goodId = $('.goodId').val();
	var goodName = $('.goodName').val();
	$('.inputGood').autoSuggest('/admin/orderGoods/ajaxGetAutosuggestGoods/', {
		selectedItemProp: "name",
		searchObjProps: "name",
		targetInputName: 'goodId',
		secondItemAtribute: 'code',
		thirdItemAtribute: 'price',
		fourthItemAtribute: 'basePrice',
		preFill: [ { value: goodId, name: goodName } ],
		actionOnSelectionAdded: true,
	});
	$('input[name=goodId]').val(goodId);
	$('.inputGoodLoader').hide();
}