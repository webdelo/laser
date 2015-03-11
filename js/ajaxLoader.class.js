var ajaxLoader = function(arrayId){
	this.arrayId = arrayId;

	this.element = [];
	this.loader = $("<center><img src='/images/loaders/loader.gif' /></center>");

	this.show = function(key){
	      $(this.arrayId[key]).show();
	};

	this.hide = function(key){
	     $(this.arrayId[key]).hide();
	};

	this.innerLoader = function(elementClass)
	{
	      var loader = "<center><img src='/images/loaders/loader.gif' /></center>";
	      $(elementClass).html(loader);
	};

	this.setLoader = function(elementClass)
	{
	    this.element = $(elementClass);
	    this.element.replaceWith(this.loader);
	};

	this.getElement = function()
	{
	    this.loader.replaceWith(this.element);
	};
};