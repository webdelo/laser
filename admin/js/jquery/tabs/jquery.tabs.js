var tabs = function (settings) {
	this.settings = $.extend({
		'active'       : 'active',
		'keyInLocation': 'tab',
		'navigation'   : '.tabNavigation',
		'containers'   : '.containers',
		'useLocation'  : true
	}, settings||{});

	this.init = function() {
		this.setActiveTabFromNavigation();
		if (this.settings.useLocation)
			this.setActiveTabFromLocation();
		
		return this;
	};
	
	this.setSettings = function(settings){
		this.settings = $.extend(this.settings, settings);
		return this;
	}
	
	this.setActiveTabFromLocation = function() {
		var nav$ = $(this.settings.navigation).eq(this.getTabFromLocation());
		this.changeTab(nav$);
		return this;
	};
	
	this.getTabFromLocation = function() {
		var hash = $.deparam(location.hash.replace('#',''));
		return hash[this.settings.keyInLocation]?hash[this.settings.keyInLocation]:0;
	};
	
	this.setActiveTabFromNavigation = function() {
		var that = this;
		var nav$ = $(this.settings.navigation);
		$(this.settings.navigation).live('click', function(){
			if ( !$(this).hasClass(that.settings.active) ) {
				that.changeTab($(this));
				if (that.settings.useLocation)
					that.setTabInLocation($(this));
			}
			return false;
		});
		return this;
	}; 
	
	this.changeTab = function(tab$) {
		this.getContentBlockByTab(tab$).fadeTo('fast',1);
		$(this.settings.containers).not(this.getContentBlockByTab(tab$)).hide();
		this.setActiveTab(tab$)
			.useTabsCallback(tab$)
			.useCallback(tab$);
		return this;
	};
	
	this.getContentBlockByTab = function(tab$){
		var index = tab$.index(this.settings.navigation);
		return $(this.settings.containers).eq(index);
	};
	
	this.setActiveTab = function(tab$) {
		$(this.settings.navigation).removeClass(this.settings.active);
		tab$.addClass(this.settings.active);
		
		return this;
	};
	
	this.useTabsCallback = function (tab$) {
		var callbackName = tab$.data('callback');
		if ( callbackName !== undefined ) {
			var callback = this.callbacks[callbackName];
			if ( $.isFunction(callback) ) {
				callback.call();
			}
		}
		return this;
	};
	
	this.useCallback = function (tab$) {
		if ( $.isFunction(this.callback) ) {
			this.callback.call(this, tab$);
		}
		return this;
	};
	
	this.setTabInLocation = function(tab$) {
		location.href = '#'+this.settings.keyInLocation+'='+tab$.index(this.settings.navigation);
		return this;
	};
	
	this.setTabCallbacks = function (callbacks) {
		this.callbacks = callbacks;
		return this;
	};
	
	this.setChangeTabCallback = function (callback) {
		if ($.isFunction(callback))
			this.callback = callback;
		else
			alert('Invalid argument in method tabs::setChangeTabCallback');
		
		return this;
	};
};