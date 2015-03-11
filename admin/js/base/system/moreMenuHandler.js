$(function(){
	var menuHandle = new moreMenu();
	menuHandle.init();
});

var moreMenu = function (settings) {
	this.settings = $.extend({
		'moreLinkToggle' : '.more',
		'menuBlock'      : '.menu',
		'moreMenuBlock'  : '.more-list'
	}, settings||{});

	this.setSettings = function (sources) {
		this.settings = $.extend(this.settings, sources||{});
		return this;
	};
	
	this.init = function () {
		var that = this;
		$.each(this.handlers, function () {
			this.call(that);
		});
	};
	
	this.handlers = {
		'toogleMoreMenu': function() {
			var that = this;
			$(this.settings.moreLinkToggle).click(function(e){
				$(that.settings.moreMenuBlock).toggle().click(function(event) {
					event.stopPropagation();
				});
				e.stopPropagation();
			});
			$('body').click(function() {
				$(that.settings.moreMenuBlock).hide();
			});
		},
		'analyzeMenu': function() {
			this.analyzeMenu();
		}
	};
	
	this.analyzeMenu2 = function () {
		var that = this;
		$(this.settings.menuBlock).children().each(function () {
			alert($(this).is(':hidden'));
			 if ( !$(this).is(':visible') ) {
				 $(this).appendTo(that.settings.moreMenuBlock);
			 }
		});
		if ( $(this.settings.moreMenuBlock).children('a').length > 0 ) {
			$(this.settings.moreLinkToggle).hide();
		}
	};
	
	this.analyzeMenu = function () {
		var that = this;
		var totalMenuWidth = $(this.settings.menuBlock).width();
		var elementsWidth = 0;
		$(this.settings.menuBlock).children().each(function () {
			 elementsWidth += $(this).outerWidth(true);
			 if ( elementsWidth >= totalMenuWidth ) {
				 $(this).appendTo(that.settings.moreMenuBlock);
			 }
		});
		if ( elementsWidth < totalMenuWidth ) {
			$(this.settings.moreLinkToggle).hide();
		}
	};
	
	this.getElementWidthInMenu = function (element) {
		$(element).clone().addClass('tempLinkMenu').appendTo(this.settings.menuBlock);
		var width = $('.tempLinkMenu').actual('width');
		$('.tempLinkMenu').remove();
		return width;
	}
};