$(function() {
	var falls = new selectsFalls;
	falls.setSettings({ 'hiddingMode' : false })
		 .setStep('.countryChoose')
		 .setStep('.regionChoose')
		 .setStep('.cityChoose');
 
	var adressSave = new form;
	adressSave.setSettings({
		'form':'.setToObjectBlock'
	}).setLoader(new loaderBlock).setCallback(function(response){
			if (typeof response == 'number') {
				$('.addressDataBlock').htmlFromServer({
					'callback': function(){
						initMap();
					}
				});
				if ( $('.baseInfo').length > 0 ) {
					$('.baseInfo').fadeIn();
				}
			} else {
				(new error($('.citySearch'), 'Выберите пожалуйста город!')).show();
			}
		}).init();
	
	var deleteLoader = new loaderBlock;
	deleteLoader.setTarget($('.addressDetails'));
	var deleteAddress = new buttons;
	deleteAddress.setSettings({
		'element':'.deleteAddress'
	}).setLoader(deleteLoader).setCallback(function(response){
		if (typeof response == 'number') {
			$('.addressDataBlock').htmlFromServer({callback: function(){
				citiesSearch();
				adressSave.init();
			}});
			
			if ( $('.baseInfo').length > 0 ) {
				$('.baseInfo').fadeOut();
			}
		}
	}).init();
	
	var citiesSearch = function () {
		var citiesSearch = new objectsSearch();
		citiesSearch.setSettings({
			'element'  : '.citySearch',
			'resultTo' : 'citySuggest'
		})
		.setChooseRow(function(choosedRow){
			adressSave.addDataToPost({
				'cityId':choosedRow.attributes.value,
			});
		}).init();
	};
	citiesSearch();
	
	initMap();
});

var objectsSearch = function (settings) {
	this.settings = $.extend({
		'element' : '.objectsSearch',
		'resultTo': 'clientId'
	}, settings||{});
	
	this.setSettings = function(settings) {
		this.settings = $.extend({
			'element' : '.objectsSearch',
			'resultTo': 'clientId'
		}, settings||{});
		
		return this;
	};
	
	this.init = function () {
		this.requestMonitoring();
	};
	
	this.requestMonitoring = function() {
		var that = this;
		this.getObject().autoSuggest(this.getAction(), {
			selectedItemProp   : "name",
			searchObjProps     : "name",
			emptyText		   : "Такой город не найден в нашей Базе Данных. Заполните пожалуйста форму и нажмите кнопку 'добавить', город автоматически добавиться в нашу базу.",
			targetInputName    : that.settings.resultTo,
			retrieveLimit: 20,
			resultClick: that.chooseRow
		});
	};
	
	this.getAction = function() {
		if ( this.action === undefined )
			this.setAction();
		
		return this.action;
	};
	
	this.setAction = function() {
		this.action = this.getObject().data('action');
		return this;
	};
	
	this.getObject = function() {
		if ( this.object$ === undefined )
			this.setObject();
		
		return this.object$;
	};
	
	this.setObject = function() {
		this.object$ = $(this.settings.element);
	};
	
	this.setChooseRow = function(chooseRow) {
		if ( !$.isFunction(chooseRow)) {
			alert('Not function given');
			return this;
		}
		this.chooseRow = chooseRow;
		return this;
	};
}

function initMap() {
		
	var dragEnd = function () {
		sendCoordinatesToServer(map.draggMarker.position.lat(), map.draggMarker.position.lng());
		$('.dragEndSuccess').fadeIn().delay(2000).fadeOut();
		
		$('#googleMaps').data('coordinates', map.draggMarker.position.lat() + ' ' + map.draggMarker.position.lng());
	};
	
	function sendCoordinatesToServer(lat, lng){
		var dataJson = {
			'objectId'  : $('.objectId').val(),
			'latitude'  : lat,
			'longitude' : lng
		};
		$.ajax({
			'dataType': 'json',
			'url': '/admin/realties/ajaxSaveCoordinates/',
			'data': dataJson
		});
	}
	
	function getAddressPlace(){
		return ( typeof $('#googleMaps').data('coordinates') !== 'undefined' ) 
									? $('#googleMaps').data('coordinates')
									: getPostAddress();
	};
	
	function getPostAddress() {
		return $('.cityName').text() + ' ' + $('.name-street').val() + ' ' + $('.number-home').val();
	}
	
	var isNotLatLng = function(){
		var latVal = $('.lat').val();
		var lngVal = $('.lng').val();
		
		return ( typeof latVal === 'undefined' && typeof lngVal === 'undefined');
	};
	
	var map = new maps({'zoom':15});
	var infoWindow = new google.maps.InfoWindow();
	infoWindow.setContent($('.markerWindow').html());
	if ( isNotLatLng() )
		map.createMap().createDraggMarker(getAddressPlace(), dragEnd, infoWindow);
	else {
		var loc = getAddressPlace().split(',');
		var lat = loc.shift();
		var lng = loc.shift();
		map.createMap().createDraggMarkerByLatLng(lat, lng, dragEnd, infoWindow);
	}
}