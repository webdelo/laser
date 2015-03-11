$(function () {
	printPartnersManagersRightsTree();
});

var printPartnersManagersRightsTree = function(){
	$('.printPartnersManagersRightsTree').click(function(){
		printTreeByGroupId( $(this).attr('data-groupId') );
	});
}