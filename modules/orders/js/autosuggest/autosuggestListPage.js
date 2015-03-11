$(function(){
	initClientAutosuggest();
	initGoodAutosuggest();
});

var initClientAutosuggest = function(){
	var getClientId = $('.getClientId').val();
	var getClientName = $('.getClientName').val();
	$('.inputClient').autoSuggest('/admin/clients/ajaxGetAutosuggestClients/', {
		selectedItemProp: "name",
		searchObjProps: "name",
		targetInputName: 'clientId',
		secondItemAtribute: 'login',
		preFill: [ { value: getClientId, name: getClientName } ],
		actionOnSelectionAdded: false
	});
	$('input[name=clientId]').val(getClientId);
}

var initGoodAutosuggest = function(){
	var getGoodId = $('.getGoodId').val();
	var getGoodName = $('.getGoodName').val();
	$('.inputGood').autoSuggest('/admin/orderGoods/ajaxGetAutosuggestGoods/', {
		selectedItemProp: "name",
		searchObjProps: "name",
		targetInputName: 'goodId',
		secondItemAtribute: 'code',
		thirdItemAtribute: 'price',
		fourthItemAtribute: 'basePrice',
		fifthItemAtribute: 'availability',
		preFill: [ { value: getGoodId, name: getGoodName } ],
		actionOnSelectionAdded: false
	});
	$('input[name=goodId]').val(getGoodId);
}