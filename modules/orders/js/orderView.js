$(function(){
	$('.goodsList .line').live({
		mouseenter: function () {
			$(this).find('.deleteOrderGoodFromView').stop(true, true).fadeTo('fast', '1');
			$(this).find('.deleteDeliveryFromView').stop(true, true).fadeTo('fast', '1');
		},
		mouseleave: function () {
			$(this).find('.deleteOrderGoodFromView').stop(true, true).fadeTo('fast', '0');
			$(this).find('.deleteDeliveryFromView').stop(true, true).fadeTo('fast', '0');
		}
	});
	
	$('.deliveryContainer').live({
		mouseenter: function () {
			$(this).find('.pen').stop(true, true).fadeTo('fast', '1');
		},
		mouseleave: function () {
			$(this).find('.pen').stop(true, true).fadeTo('fast', '0');
		}
	});
	
	$('.timeEdit').inputmask("mask", {"mask": "с 99 до 99"});
	
	$("select[name='paidPercent']").change(function() {
		if ($(this).val() === '1') { 
			$("#editPaidPercentDate").css("display","inline");
			current_date = $.datepicker.formatDate('dd-mm-yy', new Date());
			$("#editPaidPercentDate").find( ".defaultValue" ).replaceWith(current_date);
		} else if ($(this).val() === '0') { 
			$("#editPaidPercentDate").css("display","none");
		}
	});
	
	$('.dateEdit').datepicker({
		dateFormat: "dd-mm-yy",
		onSelect: function(){
			$(this).blur();
		}
	});
	
	var editOrderSelects = new selects;
	editOrderSelects
		.setSettings({'element' : '.editOrderSelects', 'event': 'change' })
		.setCallback(function (response) {
			if ( typeof response !== 'number' )
				alert(response);
		})
		.init();

	var editOrderInputs = new inputs;
	editOrderInputs
		.setSettings({'element' : '.editOrderInputs', 'event': 'blur'})
		.setCallback(function (response) {
			if ( typeof response !== 'number' )
				alert(response);
		})
		.init();

	var editOrderInputs = new inputs;
	editOrderInputs
		.setSettings({'element' : '.changeCashRate', 'event': 'blur'})
		.setCallback(function (response) {
			if ( typeof response === 'number' )
				reloadGoodsList();
			else
				alert(response);
		})
		.init();

	var editOrderInputs = new inputs;
	editOrderInputs
		.setSettings({'element' : '.editLogin', 'event': 'blur', 'showError':false})
		.setCallback()
		.init();

	var changePartner = new selects;
	changePartner
		.setSettings({'element' : '.changePartner', 'event': 'change' })
		.setCallback(function (response) {
			if ( typeof response === 'number' ) {
				$('[name=cashRate]').val(response).transformer();
				reloadGoodsList();
			} else alert(response);
		})
		.init();


	$('.newOrderGood').click(function(){
		$('.newOrderGoodForm').dialog({
			'width'  : '500',
			'height' : '400',
			'modal'  : true
		});
	});
	
	var addGood = new form;
	addGood.setSettings({
		'form' : '.addGoodBlock'
	}).setCallback(function(response){
		if ( typeof response == 'number' ) {
			addGood.reset();
			$('.addGoodBlock .as-close').click();
			reloadGoodsList();
			$('.newOrderGoodForm').dialog('close');
		}
	}).init();
	
	var reloadGoodsList = function () {
		$('.goodsTableList').htmlFromServer();
	};
	
	var deleteGood = new buttons;
	deleteGood.setSettings({
		'element': '.deleteOrderGoodFromView'
	})
	.setCallback(function(response){
		if ( typeof response == 'number' )
			reloadGoodsList();
	}).init();
	
	var deleteDelivery = new buttons;
	deleteDelivery.setSettings({
		'element': '.deleteDeliveryFromView'
	})
	.setCallback(function(response){
		if ( typeof response == 'number' ) {
			$('.goodsTableList').htmlFromServer();
			$('.deliveryEditBlock').remove();
			$('.deliveryContainer').htmlFromServer();
		}
	}).init();
	
});