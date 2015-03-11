$(function(){

	var goodEditForm = new form;
	goodEditForm.setSettings({
				'form': '.goodEditForm'
			})
			.setLoader(new loaderLight)
			.setCallback(function(response){
				if ( typeof response !== 'number' ) {
					alert('Error');
				}
			}).init();

	//$('.colors input[type=radio], .colors input[type=checkbox]').click(function(){
	//	goodEditForm.submit();
	//});


	var saveRelation = new form;
	saveRelation.setSettings({'form':'.saveRelation'})
				.setLoader(new loaderLight)
				.setCallback(function(response){
					if ( typeof response === "number" && saveRelation.getObject().find('.editRelation').val() !== '' ) {
						saveRelation.getObject().find('input[name=id]').val(response);
					} else {
						saveRelation.getObject().find('input[name=id]').val('')
					}
				})
				.init();

	$('.measurements').on('change', function() {
		if ( $(this).prev('.editRelation').val() !== '' ) {
			$(this).parents('.saveRelation').find('.saveRelationSubmit').click();
		}
	});
	$('.editRelation').on('blur', function() {
		$(this).parents('.saveRelation').find('.saveRelationSubmit').click();
	});

	$('.colors input[type=radio]').each(function() {
		var secondClick = true;
		$(this).change(function() {
			secondClick = false;
//			goodEditForm.submit();
			$(this).parents(".goodEditForm").find(".goodEditFormSubmit").click()
		});
		$(this).click(function() {
			if (secondClick) {
				$(this).prop("checked", false);
//				goodEditForm.submit();
				$(this).parents(".goodEditForm").find(".goodEditFormSubmit").click()
			}
			secondClick = true;
		});
	});

	$('.colors input[type=checkbox]').click(function(){
		goodEditForm.submit();
	});

});