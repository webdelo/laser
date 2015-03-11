$(function(){
	onSelectFabricatorChange();
});

var onSelectFabricatorChange = function(){
	$('select[name=fabricatorId]').live('change', function(){
		var that = this;
		new loader().start();
		$.ajax({
			url: '/admin/catalog/ajaxGetSerialIdSelect/',
			type: 'post',
			dataType: 'html',
			data: {
				'fabricatorId' : $(that).val(),
				'objectId' : $('#objectId').val()
			},
			success: function(data){
				$('select[name=seriaId]').replaceWith(data);
				new errors().show( {seriaId: "Не забудьте проверить серию и сохранить изменения"} );
				new loader().stop();
			}
		});
	});
}