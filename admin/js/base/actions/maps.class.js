var maps = function (settings) {
	this.mapPlace = 'googleMaps';
	this.settings = $.extend({
		'center' : new google.maps.LatLng(-34.397, 150.644),
		'zoom'   : 15
	}, settings||{});
	
	this.setMapPlace = function (id){
		if (typeof id === 'undefined') {
			alert('Передайте пожалуйста id объекта для размещения карты');
		}
		this.mapPlace = id;
		return this;
	};
	
	this.setSettings = function (settings){
		this.settings = $.extend(this.settings, settings||{});
		return this;
	};
	
	this.createMap = function () {
		var that = this;
		that.setMap();
		return this;
	};
	
	this.setMap = function() {
		var that = this;
		this.map = new google.maps.Map(document.getElementById(that.mapPlace), that.settings);
		return this;
	};
	
	this.getMap = function() {
		if (this.map === undefined)
			this.setMap();
		return this.map;
	};
	
	this.setCenterByAddress = function(address) {
		var that = this;
		address = $.trim(address);
		var geocoder = new google.maps.Geocoder()
		geocoder.geocode( { 'address':address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				that.createDraggMarker(results[0].geometry.location)
					.getMap().setCenter(results[0].geometry.location);
			}
		});
	};
	
	this.removeMarkers = function(){
		if ( typeof this.marker !== 'undefined' )
			this.marker.setMap(null);
		if ( typeof this.draggMarker !== 'undefined' )
			this.draggMarker.setMap(null);
		
		return this;
	};
	
	this.createMarker = function(loc){
		var that = this;
		this.removeMarkers();
		this.marker = new google.maps.Marker({
			position: loc,
			map: that.getMap()
		});
		that.getMap().setCenter(loc);
		return this;
	};
	
	this.createMarkerByLatLng = function(lat, lng){
		var that = this;
		this.removeMarkers();
		var loc      = new google.maps.LatLng(lat, lng);
		this.marker = new google.maps.Marker({
			position: loc,
			map: that.getMap()
		});
		that.getMap().setCenter(loc);
		return this;
	};
	
	this.createDraggMarker = function(loc, draggEnd, infoWindow){
		var that = this;
		loc = $.trim(loc);
		var geocoder = new google.maps.Geocoder()
		geocoder.geocode( { 'address':loc}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) 
				var place = results[0].geometry.location;
			else {
				var place = new google.maps.LatLng(42.71099706708453, 27.756775543066396);
				that.getMap().setOptions({'zoom': 7});
			}
			
			that.removeMarkers();
			that.draggMarker = new google.maps.Marker({
				position: place,
				map: that.getMap(),
				draggable: true
			});
			
			if (typeof infoWindow == 'object' ) {
				infoWindow.open(that.map, that.draggMarker);
				google.maps.event.addListener(that.draggMarker, 'click', function() {
					infoWindow.open(that.map, that.draggMarker);
				});
			}

			google.maps.event.addListener(that.draggMarker, 'dragend', draggEnd);
			that.getMap().setCenter(place);
		});
		return this;
	};
	
	this.createDraggMarkerByLatLng = function(Lat, Lng, draggEnd, infoWindow){
		var that     = this;
		var loc      = new google.maps.LatLng(Lat, Lng);
		
		that.draggMarker = new google.maps.Marker({
			position: loc,
			map: that.getMap(),
			draggable: true
		});

		if (typeof infoWindow == 'object' ) {
			infoWindow.open(that.map, that.draggMarker);
			google.maps.event.addListener(that.draggMarker, 'click', function() {
				infoWindow.open(that.map, that.draggMarker);
			});
		}

		google.maps.event.addListener(that.draggMarker, 'dragend', draggEnd);
		that.getMap().setCenter(loc);
		
//			var geocoder = new google.maps.Geocoder();
//			geocoder.geocode( { 'address':loc}, function(results, status) {
//				if (status == google.maps.GeocoderStatus.OK) 
//					var place = results[0].geometry.location;
//				else {
//					var place = new google.maps.LatLng(42.71099706708453, 27.756775543066396);
//					that.getMap().setOptions({'zoom': 7});
//				}
//
//				that.removeMarkers();
//
//			});
		return this;
	};
};