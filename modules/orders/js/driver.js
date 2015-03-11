$(function(){
	$('.mapAddress').each(function(){
		var that = this;
		function init() {
			var mapBlock = $(that).parents('.orderPage').find('.maps').attr('id');
			var myMap = new ymaps.Map(mapBlock, { 
				center: [55.734046, 37.588628],
				zoom: 12,
				controls: ['zoomControl', 'typeSelector',  'fullscreenControl']
			});
			var objects = ymaps.geoQuery(ymaps.geocode($(that).text())).addToMap(myMap);
			
			objects.then(function () {
				var object = objects.get(0);
				if ( object !== 'undefined' )
					object.balloon.open();
			});
		}
		ymaps.ready(init);
	});
});

