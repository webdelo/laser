$(function(){
	initAutosuggest();
	selectionClearOnSave();
});

var initAutosuggest = function(){
	$('.inputClient').autoSuggest('/admin/clients/ajaxGetAutosuggestClients/', {
		selectedItemProp: "name",
		searchObjProps: "name",
		targetInputName: 'clientId',
		secondItemAtribute: 'login'
	});
	$('input[name=clientId]').val( $('input[name=curentClientId]').attr('value') );

	initGoodAutosuggest();
}

var initGoodAutosuggest = function(){
	$('.inputGoodId').autoSuggest('/admin/orderGoods/ajaxGetAutosuggestGoods/', {
		selectedItemProp: "name",
		searchObjProps: "name",
		targetInputName: 'goodId',
		secondItemAtribute: 'code',
		thirdItemAtribute: 'price',
		fourthItemAtribute: 'basePrice',
		fifthItemAtribute: 'availability'
	});
}

var selectionClearOnSave = function(){
	$('.formEditSubmit').live('click', function(){
		$('.as-selection-item').remove();
	})
}

var onSelectionAdded = function(targetInputName){
	if(targetInputName=='clientId')
		clientIdAdded(targetInputName);
	if(targetInputName=='goodId')
		goodIdAdded(targetInputName);
}

var clientIdAdded = function(targetInputName){
	var clientId = $('input[name=clientId]').val();
	var pastClientId = $('input[name=curentClientId]').val();
	var data = {clientId : clientId}
	$.ajax({
			before: $('.inputClientLoader').show(),
			url: '/admin/clients/ajaxGetClientById/',
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(data){
				if(data.name){
					$('.inputClientLoader').hide()
					$('.clientData').html('<a href="/admin/clients/client/' + clientId + '/" title="Показать данные клиента в новом окне" target="_blank">' + data.name + '</a><br />( ' + data.email + ' )');
					$('input[name=curentClientId]').val(clientId);
					$('input[name=pastClientId]').val(pastClientId);
				}
				else{
					alert('Произошла ошибка при получении данных клиента. Обратитесь к разработчикам.');
				}
			}
		});
}

var onSelectionRemoved = function(targetInputName){
	if(targetInputName=='clientId')
		clientIdRemoved();
	if(targetInputName=='goodId')
		goodIdRemoved();
}

var clientIdRemoved = function(){
	var clientId = $('input[name=pastClientId]').val();
	var data = {clientId : clientId}
	$.ajax({
		before: $('.inputClientLoader').show(),
		url: '/admin/clients/ajaxGetClientById/',
		type: 'POST',
		dataType: 'json',
		data: data,
		success: function(data){
			if(data.name){
				$('.inputClientLoader').hide()
				$('.clientData').html('<a href="/admin/clients/client/' + clientId + '/" title="Показать данные клиента в новом окне" target="_blank">' + data.name + '</a><br />( ' + data.email + ' )');
				$('input[name=clientId]').val(clientId);
				$('input[name=curentClientId]').val(clientId);
			}
			else{
				alert('Произошла ошибка при получении данных клиента. Обратитесь к разработчикам.');
			}
		}
	});
}

var goodIdAdded = function(targetInputName){
	var goodId = $('input[name=goodId]').val();
	var data = {goodId : goodId}
	$.ajax({
			before: $('.inputGoodLoader').show(),
			url: '/admin/orderGoods/ajaxGetAutosuggestGoodById/',
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(data){
				if(data.price){
					$('.inputGoodLoader').hide();
					$('.addedGoodQuantity').val(data.quantity);
					$('.addedGoodPrice').val(data.price);
					$('.addedGoodBasePrice').val(data.basePrice);
					$('.addedGoodAvailability').val(data.baseAvailability);
				}
				else{
					alert('Произошла ошибка при получении данных товара. Обратитесь к разработчикам.');
				}
			}
		});
}

var goodIdRemoved = function(){
	if ( $('.addedGoodQuantity').length > 0 )
		$('.addedGoodQuantity').val('');
	
	if ( $('.addedGoodPrice').length > 0 )
		$('.addedGoodPrice').val('');
	
	if ( $('.addedGoodBasePrice').length > 0 )
		$('.addedGoodBasePrice').val('');
	
	if ( $('.addedGoodAvailability').length > 0 )
		$('.addedGoodAvailability').val('');
}