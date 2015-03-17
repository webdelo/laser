$(function() {
	var deleteLoader = new loaderBlock;
	deleteLoader.setTarget($('.clientDetails'));
	var deleteClient = new buttons;
	deleteClient.setSettings({
		'element':'.deleteClient'
	}).setLoader(deleteLoader).setCallback(function(response){
		if (typeof response == 'number')
			$('.clientDataBlock').htmlFromServer({
				'callback' : function(){
					initObjectSearch();
				}
			});
	}).init();
	
	function initObjectSearch()
	{
		var clientRelationSave = new form;
		clientRelationSave.setSettings({
			'form':'.clientSearchBlock'
		}).setLoader(new loaderBlock).setCallback(function(response){
			$().val(response);
			$('.searchClientContainer').hide();
			$('.clientDataBlock').htmlFromServer();
		}).init();
		
		var clientsSearch = new objectsSearch();
		clientsSearch.setSettings({
			'element'  : '.clientSearchInput',
			'resultTo' : 'clientId'
		})
		.setChooseRow(function(choosedRow){
			clientRelationSave.submit();
		}).init();
	}
	initObjectSearch();
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