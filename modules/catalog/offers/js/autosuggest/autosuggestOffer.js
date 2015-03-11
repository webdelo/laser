$(function(){
	initGoodAutosuggest();
});

var initGoodAutosuggest = function(){
	$('.inputGoodId').autoSuggest('/admin/orderGoods/ajaxGetAutosuggestGoods/', {
		selectedItemProp: "name",
		searchObjProps: "name",
		targetInputName: 'goodId',
		secondItemAtribute: 'code',
		thirdItemAtribute: 'price',
		fourthItemAtribute: 'basePrice'
	});
}

var onSelectionAdded = function(targetInputName){
	if(targetInputName=='goodId'  &&  $('input[name=goodId]').length > 0)
		goodIdAdded(targetInputName);
}

var onSelectionRemoved = function(targetInputName){
}

var goodIdAdded = function(targetInputName){
	var goodId = $('input[name=goodId]').val();
	var data = {goodId : goodId}
	$.ajax({
			before: $('.inputGoodLoader').show(),
			url: '/admin/offers/ajaxGetGoodTable/',
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(data){
				$('.inputGoodLoader').hide();
				if(data){
					$('.offerGoodTalbeCell').show();
					$('.offerGoodTalbePlace').html(data);
				}
				else{
					alert('Произошла ошибка при получении данных товара акции. Обратитесь к разработчикам.');
				}
			}
		});
}