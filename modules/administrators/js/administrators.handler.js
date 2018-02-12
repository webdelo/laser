$(function () {
	var changePassword = new form;
	changePassword.setSettings({
			'form'    : '.formChangePassword',
			'active'  : '.active',
			'message' : '.changePasswordMessage'
		})
		.setCallback(function (response) {
			return true;
		})
		.init();

	treeInit();
	changeGroupHandler();
});

var treeInit = function(){
	$('.tree ul:first').Tree();
}

var changeGroupHandler = function(){
	$('.groupId').change(function() {
		printTreeByGroupId( $('.groupId :selected').val() );
	});
}

var printTreeByGroupId =function (groupId){
	$.ajax({
		url: '/admin/administrators/ajaxGetGroupRightsById/',
		type: 'get',
		dataType: 'json',
		data: {'groupId': groupId},
		success: function(data){
			$('.tree').html('');
			$('.tree').html(data);
			treeInit();
		}
	});
}