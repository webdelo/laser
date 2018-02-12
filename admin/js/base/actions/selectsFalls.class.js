var selectsFalls = function () {
	this.settings = { 
		'objects'     : [], 
		'callbacks'   : [],
		'hiddingMode' : true
		
	};
	this.objects = new Array;
	
	this.setSettings = function(settings) {
		this.settings = $.extend(this.settings, settings||{});
		
		return this;
	};
	
	this.setStep = function(object, callback) {
		this.settings.objects.push(object);
		this.settings.callbacks.push(callback||$.noop);
		var select   = new selects;
		var that     = this;
		
		select.setSettings({
			'element'   : $(object).selector,
			'showError' : false
		}).setCallback(function(response){
			that.everNextStepModify(object);
			if ( that.isNextStepExists(this) ) {
				that.addOptions(response, $(that.getNextStep(object)));
			}
			that.useCurrentCallback(object, select.getObject(), response);
		}).init();
		
		
		return this;
	};
	
	this.setNextStep = function (object, options, callback) {
		this.setStep(object, callback)
			.addOptions(options, object);
		
	}
	
	this.everNextStepModify = function (current$) {
		var next = this.getNextStep(current$);
		if ( next ) {
			this.disableStep($(next));
			this.everNextStepModify(next);
		} 
	};
	
	this.useCurrentCallback = function (current$, activatedObject, response) {
		var index = this.settings.objects.indexOf(current$);
		var callback = eval(this.settings.callbacks[index]);
		if ( $.isFunction(callback) )
			callback.call(this, activatedObject, response);
	};
	
	this.getNextStep = function (current$) {
		if ( this.isNextStepExists(current$) ) {
			var index = this.settings.objects.indexOf(current$);
			var next = this.settings.objects[index+1];
			return next;
		}
		return false;
	};
	
	this.isNextStepExists = function (current$) {
		var size = this.settings.objects.length - 1;
		return size > this.settings.objects.indexOf(current$);
	};
	
	this.addOptions = function (options, select$) {
		if ( this.isFirstUseLikeTitle(select$) ) {
			this.optionsToSelectAfterFirst(options, select$)
		} else {
			this.optionsToSelect(options, select$);
		}
	};
	
	this.isFirstUseLikeTitle = function (select$) {
		return select$.children('option:first').val() === "0";
	};
	
	this.optionsToSelect = function (options, select$) {
//		this.setChoosedOption(select$);
		select$.html('');
		$.each(options, function(){
			select$.append($('<option></option>').val(this.value).text(this.name));
		});
//		this.chooseOption(select$);
		var notJump = select$.data('notJumpStep');
		var length = select$.children().length;
		if ( length === 1 && notJump == undefined ) {
			select$.children(':first').change();
		}
		this.enableStep(select$);
	};
	
	this.optionsToSelectAfterFirst = function (options, select$) {
//		this.setChoosedOption(select$);
		select$.children().not(':first').remove();
		$.each(options, function(){
			select$.append($('<option></option>').val(this.value).text(this.name));
		});
//		this.chooseOption(select$);
		var notJump = select$.data('notJumpStep');
		var length = select$.children().length;
		if ( length === 2 && select$.data('notJumpStep') == undefined ) {
			select$.children(':last').attr('selected','selected').change();
		}
		this.enableStep(select$);
	};
	
	this.setChoosedOption = function(select$) {
		if ( select$.children(':selected').length === 1 ) {
			this.selected$ = select$.children(':selected');
		}
	};
	
	this.chooseOption = function(select$) {
		if (select$.length ===1)
		var option$ = select$.children('value='+this.selected$.attr('value'));
		if ( option$.length === 1 ) {
			option$.attr('selected', 'selected');
		}
	};
	
	this.enableStep = function(element$) {
		if ( this.settings.hiddingMode )
			element$.fadeIn('fast');
		else
			element$.removeAttr('disabled');
	};
	
	this.disableStep = function(element$) {
		if ( this.settings.hiddingMode )
			element$.hide();
		else
			element$.attr('disabled', true);
		
		this.addOptions({}, element$);
	};
	
};