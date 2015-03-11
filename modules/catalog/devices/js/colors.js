$(function(){
	$('#colorField').ColorPicker({
		color: function () {
			return '#'+$('#colorInput').val();
		},
		onShow: function (colpkr) {
			$(colpkr).fadeIn();
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut();
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#colorField').css('backgroundColor', '#' + hex);
			$('#colorInput').val(hex);
		}
	});
	
	$('.otherColor').autoSuggest('/admin/devices/ajaxGetOtherColor/', {
		selectedItemProp   : "name",
		searchObjProps     : "name",
		targetInputName    : 'otherColorId',
		secondItemAtribute : 'code',
		thirdItemAtribute  : 'price',
		fourthItemAtribute : 'basePrice',
		retrieveLimit: 20,
		start: function(){
			
		},
		resultClick: function(){
			
		},
		onSelectionAdded: function(){
			
		}
	});
	
	var reloadAssociatedDevices = function () {
		$('.associatedDevices').htmlFromServer();
	};
	
	var associateDevice = new form;
	associateDevice.setSettings({'form' : '.associateDevice'})
		.setCallback(reloadAssociatedDevices)
		.init();
	
	var deassociateDevice = new buttons();
	deassociateDevice.setSettings({'element':'.deassociateDevice'})
		.setCallback(reloadAssociatedDevices)
		.init();
	
});