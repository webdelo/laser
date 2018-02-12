var datepickerWithStopPeriodsInit = function(monthes)
{
	(function( factory ){
	if ( typeof define === "function" && define.amd )
		// AMD. Register as an anonymous module.
		define([ "../datepicker" ], factory );
	else
		// Browser globals
		factory( jQuery.datepicker );
	}(function( datepicker ) {datepicker.setDefaults(datepicker.regional[currentLang]);}));

	$('.datepicker').datepicker({
		minDate: new Date,
		beforeShowDay: function(date) {
			var nonDates = $(this).attr('data-nonDates');
			var dates = [];
			$.each(nonDates.split(','), function( k, v ) {
				if(v != ""){
					var dateAr = v.split('-');
					dates[k] = dateAr[1] + '/' + dateAr[0] + '/' + dateAr[2];
				}
			});
	//			for texting:
	//			dates = [ "03/29/2015", "03/30/2015", "03/31/2015", "04/01/2015" ];
			for (var i = 0; i < dates.length; i++) {
				var nextDate = new Date((new Date(dates[i])).valueOf() + 96350989);
				var previousDate = new Date((new Date(dates[i])).valueOf() - 82350989);
	//				for testing:
	//				console.log( previousDate, new Date(dates[i]).toString(), nextDate );
				if (new Date(dates[i]).toString() == date.toString()) {
					if( $.inArray( $.datepicker.formatDate('mm/dd/yy', previousDate), dates ) < 0 )
						return [true, 'highlightHalfStartDatePicker ui-datepicker-unselectable ui-state-disabled', 'blocked periods'];

					if( $.inArray( $.datepicker.formatDate('mm/dd/yy', nextDate), dates ) < 0 )
						return [true, 'highlightHalfEndDatePicker ui-datepicker-unselectable ui-state-disabled', 'blocked periods'];

					return [true, 'highlightDatePicker ui-datepicker-unselectable ui-state-disabled', 'blocked periods'];
				}
			}
			return [false, ''];
		},
		numberOfMonths: monthes
	});
}