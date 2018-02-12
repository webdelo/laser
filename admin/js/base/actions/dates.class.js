var dates = function (date) {
	if ( typeof date === "undefined" ) {
		this.date = new Date();
	} else {
		this.date = date;
	}
	this.dateFormat = ['day', 'month', 'year'];
	
	this.setDateFormat = function (dateArray) {
		if (typeof dateArray === "undefined")
			this.dateFormat = dateArray;
	};
	
	this.setDateObject = function(date){
		this.dateObject = date;
		return this;
	};
	
	this.isDatepicker = function () {
		return ( typeof this.date.selectedMonth !== "undefined" );
	};
	
	this.isMyDate = function () {
		return ( typeof this.date.month !== "undefined" );
	};
	
	this.isNativeDate = function () {
		return this.date instanceof Date;
	};
	
	this.isStringDate = function () {
		return typeof this.date === 'string';
	};
	
	this.getObjectFromDateNative = function () {
		var that = this;
		return {
			'year'  : that.date.getFullYear(),
			'month' : that.date.getMonth(),
			'day'   : that.date.getDate()
		};
	};
	
	this.getObjectFromDatePicker = function () {
		var that = this;	
		return {
			'year'  : parseInt(that.date.selectedYear),
			'month' : parseInt(that.date.selectedMonth),
			'day'   : parseInt(that.date.selectedDay)
		};
	};
	
	this.getObjectFromDateString = function () {
		var date = this.date.split('-');
		var that = this;
		return {
			'year'  : parseInt(date[that.dateFormat.indexOf('year')]),
			'month' : parseInt(date[that.dateFormat.indexOf('month')]),
			'day'   : parseInt(date[that.dateFormat.indexOf('day')])
		};
	};
	
	this.generateObject = function (){
		if ( this.isNativeDate() ) {
			this.setDateObject(this.getObjectFromDateNative());
		} else if ( this.isStringDate() ) {
			this.setDateObject(this.getObjectFromDateString());
		} else  if ( this.isDatepicker() ) {
			this.setDateObject(this.getObjectFromDatePicker());
		} else  if ( this.isMyDate() ) {
			this.setDateObject(this.date);
		} else {
			alert('Пожалуйста проверьте тип объекта передаваемый в класс.');
		}
	};
	this.generateObject();
	
	this.getDateObject = function(){
		return this.dateObject;
	};
	
	this.getDateString = function(){
		return this.getDateObject().day + '-' + this.getDateObject().month + '-' + this.getDateObject().year;
	};
	
	this.getTodayNative = function(){
		return new Date();
	};
	
	this.setDate = function(date){
		this.date = date;
		this.generateObject();
		
		return this;
	};
	
	this.getTomorrowNative = function(){
		this.setDate(this.getTodayNative());
		return new Date(this.dateObject.year, this.dateObject.month, this.dateObject.day+1);
	};
	
	this.getNextDayNative = function(){
		return new Date(this.dateObject.year, this.dateObject.month, this.dateObject.day+1);
	};
};